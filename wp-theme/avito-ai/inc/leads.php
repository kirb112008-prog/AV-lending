<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Приём заявок с форм сайта.
 *
 * Браузер отправляет данные на этот адрес, а токен для сервера заявок подставляется здесь,
 * на стороне WordPress, поэтому в коде страницы и в запросах браузера его нет.
 *
 * Значения задаются в wp-config.php (до строки «That's all, stop editing!»):
 *   define('AVITO_LEADS_URL', 'https://api.example.ru/postUserData');
 *   define('AVITO_LEADS_TOKEN', 'значение ACCESS_TOKEN из .env сервера заявок');
 */

const AVITO_LEADS_IP_LIMIT = 5;
const AVITO_LEADS_IP_WINDOW = 600;
const AVITO_LEADS_EMAIL_WINDOW = DAY_IN_SECONDS;

function avito_leads_endpoint_url(): string
{
    return rest_url('avito/v1/lead');
}

add_action('rest_api_init', function () {
    register_rest_route('avito/v1', '/lead', [
        'methods' => 'POST',
        'callback' => 'avito_leads_handle',
        'permission_callback' => '__return_true',
    ]);
});

function avito_leads_config(string $name): string
{
    if (defined($name)) {
        return (string) constant($name);
    }

    $value = getenv($name);

    return $value === false ? '' : (string) $value;
}

/**
 * Яндекс SmartCaptcha (необязательна). Ключи из консоли Yandex Cloud, тоже в wp-config.php:
 *   define('AVITO_SMARTCAPTCHA_CLIENT_KEY', 'ключ клиента');   // публичный, попадает на страницу
 *   define('AVITO_SMARTCAPTCHA_SERVER_KEY', 'ключ сервера');   // секретный, остаётся на сервере
 * Пока ключи не заданы, формы работают без капчи.
 */
function avito_captcha_client_key(): string
{
    return avito_leads_config('AVITO_SMARTCAPTCHA_CLIENT_KEY');
}

function avito_leads_captcha_ok(string $token): bool
{
    $secret = avito_leads_config('AVITO_SMARTCAPTCHA_SERVER_KEY');

    if ($secret === '') {
        return true;
    }

    if ($token === '') {
        return false;
    }

    $response = wp_remote_post('https://smartcaptcha.yandexcloud.net/validate', [
        'timeout' => 10,
        'body' => [
            'secret' => $secret,
            'token' => $token,
            'ip' => (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
        ],
    ]);

    if (is_wp_error($response) || (int) wp_remote_retrieve_response_code($response) !== 200) {
        error_log('Avito leads: SmartCaptcha validation request failed');

        return false;
    }

    $result = json_decode(wp_remote_retrieve_body($response), true);

    return is_array($result) && ($result['status'] ?? '') === 'ok';
}

function avito_leads_response(int $status, string $message): WP_REST_Response
{
    return new WP_REST_Response(['message' => $message], $status);
}

function avito_leads_clean(array $data): ?array
{
    $name = trim(wp_strip_all_tags((string) ($data['name'] ?? '')));
    $email = trim((string) ($data['email'] ?? ''));
    $phone = trim((string) ($data['phone'] ?? ''));
    $niche = trim(wp_strip_all_tags((string) ($data['niche'] ?? '')));

    $digits = preg_replace('/\D/', '', $phone);

    if (in_array(substr($digits, 0, 1), ['7', '8'], true)) {
        $digits = substr($digits, 1);
    }

    $valid = mb_strlen($name) >= 2
        && mb_strlen($name) <= 255
        && preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/', $email) === 1
        && mb_strlen($email) <= 100
        && strlen($digits) === 10
        && mb_strlen($phone) <= 20
        && $niche !== ''
        && mb_strlen($niche) <= 50;

    // порядок ключей важен: сервер заявок сверяет его с ожидаемым
    return $valid ? ['name' => $name, 'email' => $email, 'phone' => $phone, 'niche' => $niche] : null;
}

function avito_leads_handle(WP_REST_Request $request)
{
    $url = avito_leads_config('AVITO_LEADS_URL');
    $token = avito_leads_config('AVITO_LEADS_TOKEN');

    if ($url === '' || $token === '') {
        error_log('Avito leads: AVITO_LEADS_URL / AVITO_LEADS_TOKEN are not set in wp-config.php');

        return avito_leads_response(500, 'Leads server is not configured');
    }

    $data = $request->get_json_params();
    $lead = is_array($data) ? avito_leads_clean($data) : null;

    if (!$lead) {
        return avito_leads_response(400, 'Bad request');
    }

    $ip_key = 'avito_lead_ip_' . md5((string) ($_SERVER['REMOTE_ADDR'] ?? ''));
    $ip_count = (int) get_transient($ip_key);

    if ($ip_count >= AVITO_LEADS_IP_LIMIT) {
        return avito_leads_response(429, 'Too many requests');
    }

    set_transient($ip_key, $ip_count + 1, AVITO_LEADS_IP_WINDOW);

    if (!avito_leads_captcha_ok((string) ($data['captcha'] ?? ''))) {
        return avito_leads_response(403, 'Captcha failed');
    }

    // повторная заявка с того же адреса в течение суток не пересылается: форму нельзя использовать для рассылки спама
    $email_key = 'avito_lead_mail_' . md5(strtolower($lead['email']));

    if (get_transient($email_key)) {
        return avito_leads_response(200, 'ok');
    }

    set_transient($email_key, 1, AVITO_LEADS_EMAIL_WINDOW);

    $response = wp_remote_post($url, [
        'timeout' => 15,
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ],
        'body' => wp_json_encode($lead, JSON_UNESCAPED_UNICODE),
    ]);

    if (is_wp_error($response) || (int) wp_remote_retrieve_response_code($response) !== 200) {
        delete_transient($email_key);
        error_log('Avito leads: the leads server did not accept the request');

        return avito_leads_response(502, 'Leads server error');
    }

    return avito_leads_response(200, 'ok');
}
