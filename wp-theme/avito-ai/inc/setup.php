<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['style', 'script', 'search-form', 'gallery', 'caption']);
});

add_action('wp_enqueue_scripts', function () {
    $uri = get_template_directory_uri() . '/assets';
    $ver = AVITO_AI_VERSION;

    wp_enqueue_style(
        'avito-montserrat',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap&subset=cyrillic',
        [],
        null
    );

    $styles = [
        'global', 'Header', 'Hero', 'Presentation', 'Mistakes', 'Reason', 'Connection', 'Integrations',
        'Details', 'Calculator', 'FAQ', 'CTAForm', 'Tarifs', 'Footer', 'Reveal', 'StickyDemo', 'Privacy',
    ];

    foreach ($styles as $name) {
        wp_enqueue_style('avito-' . strtolower($name), $uri . '/css/' . $name . '.css', ['avito-montserrat'], $ver);
    }

    if (is_page_template('template-qr.php')) {
        wp_enqueue_style('avito-qrpage', $uri . '/css/QRPage.css', ['avito-montserrat'], $ver);
    }

    wp_enqueue_script('avito-main', $uri . '/js/main.js', [], $ver, ['in_footer' => true, 'strategy' => 'defer']);
    wp_enqueue_script('avito-forms', $uri . '/js/forms.js', [], $ver, ['in_footer' => true, 'strategy' => 'defer']);

    if (function_exists('avito_captcha_client_key') && avito_captcha_client_key() !== '') {
        wp_enqueue_script('avito-smartcaptcha', 'https://smartcaptcha.yandexcloud.net/captcha.js', [], null, ['in_footer' => true, 'strategy' => 'defer']);
    }
});

// the same page titles and language as the React version
add_filter('pre_get_document_title', function ($title) {
    if (is_front_page()) {
        return 'САММИ — ИИ-сотрудник для чатов Авито';
    }

    if (is_page_template('template-qr.php')) {
        return 'САММИ — демо-доступ';
    }

    if (function_exists('avito_privacy_slug') && is_page(avito_privacy_slug())) {
        return 'Политика обработки персональных данных — САММИ';
    }

    return $title;
});

add_filter('language_attributes', function ($output) {
    return preg_match('/lang="[^"]*"/', $output) ? preg_replace('/lang="[^"]*"/', 'lang="ru"', $output) : $output . ' lang="ru"';
});

add_action('wp_head', function () {
    echo '<script>document.documentElement.classList.add("js")</script>' . "\n";
    echo '<meta name="theme-color" content="#1f3a8a" />' . "\n";
}, 1);

add_action('after_switch_theme', function () {
    if (!function_exists('carbon_set_post_meta')) {
        return;
    }

    $front_id = (int) get_option('page_on_front');

    if (!$front_id) {
        $front_id = wp_insert_post([
            'post_title' => 'Главная',
            'post_status' => 'publish',
            'post_type' => 'page',
        ]);

        if (is_wp_error($front_id) || !$front_id) {
            return;
        }

        update_option('show_on_front', 'page');
        update_option('page_on_front', $front_id);
    }

    $existing = carbon_get_post_meta($front_id, 'sections');

    if (empty($existing)) {
        carbon_set_post_meta($front_id, 'sections', avito_default_sections());
    }

    if (function_exists('carbon_set_theme_option') && !carbon_get_theme_option('nav')) {
        carbon_set_theme_option('nav', avito_default_nav());
    }
});

function avito_missing_section_types(int $page_id): array
{
    $existing = carbon_get_post_meta($page_id, 'sections');
    $have = is_array($existing) ? array_column($existing, '_type') : [];
    $missing = [];

    foreach (avito_default_sections() as $section) {
        if (!in_array($section['_type'], $have, true)) {
            $missing[] = $section;
        }
    }

    return $missing;
}

add_action('admin_init', function () {
    if (empty($_GET['avito_add_missing']) || empty($_GET['avito_page']) || !current_user_can('manage_options')) {
        return;
    }

    check_admin_referer('avito_add_missing');

    $page_id = (int) $_GET['avito_page'];

    if (!$page_id || get_post_type($page_id) !== 'page' || !function_exists('carbon_set_post_meta')) {
        return;
    }

    $missing = avito_missing_section_types($page_id);

    if ($missing) {
        $existing = carbon_get_post_meta($page_id, 'sections');
        $existing = is_array($existing) ? $existing : [];
        carbon_set_post_meta($page_id, 'sections', array_merge($existing, $missing));
    }

    wp_safe_redirect(add_query_arg('avito_done', count($missing), admin_url('post.php?post=' . $page_id . '&action=edit')));
    exit;
});

add_action('admin_notices', function () {
    if (!current_user_can('manage_options') || !function_exists('carbon_get_post_meta')) {
        return;
    }

    $page_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;

    if (!$page_id || get_post_type($page_id) !== 'page') {
        return;
    }

    $missing = avito_missing_section_types($page_id);

    if (isset($_GET['avito_done']) && $missing) {
        echo '<div class="notice notice-error"><p>Не удалось добавить разделы автоматически (не добавлено: <code>'
            . esc_html(implode(', ', array_column($missing, '_type'))) . '</code>). Пришлите это сообщение разработчику.</p></div>';
        return;
    }

    if (!$missing) {
        return;
    }

    $url = wp_nonce_url(admin_url('index.php?avito_add_missing=1&avito_page=' . $page_id), 'avito_add_missing');
    $names = implode(', ', array_column($missing, '_type'));

    echo '<div class="notice notice-info"><p>На этой странице нет разделов: <code>' . esc_html($names) . '</code>. '
        . '<a class="button button-primary" href="' . esc_url($url) . '">Добавить недостающие разделы</a></p></div>';
});

add_filter('page_row_actions', function (array $actions, WP_Post $post) {
    if (!current_user_can('manage_options') || !function_exists('avito_missing_section_types')) {
        return $actions;
    }

    if (avito_missing_section_types($post->ID)) {
        $url = wp_nonce_url(admin_url('index.php?avito_add_missing=1&avito_page=' . $post->ID), 'avito_add_missing');
        $actions['avito_add_missing'] = '<a href="' . esc_url($url) . '">Добавить недостающие разделы</a>';
    }

    return $actions;
}, 10, 2);

add_filter('pre_get_document_title', function ($title) {
    if (is_front_page()) {
        return 'САММИ — ИИ-сотрудник для чатов Авито';
    }

    return $title;
});

add_action('wp_head', function () {
    echo '<link rel="icon" type="image/svg+xml" href="' . esc_url(get_template_directory_uri() . '/assets/images/favicon.svg') . '" />' . "\n";
    echo '<meta name="description" content="САММИ — ИИ-сотрудник для чатов Авито: отвечает за 5 секунд, управление объявлениями и аналитика в одном окне." />' . "\n";
}, 2);

add_action('after_switch_theme', function () {
    if (get_page_by_path(avito_privacy_slug())) {
        return;
    }

    wp_insert_post([
        'post_title' => 'Политика обработки персональных данных',
        'post_name' => avito_privacy_slug(),
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_content' => avito_default_privacy_html(),
    ]);
});

function avito_privacy_missing(): bool
{
    return !get_page_by_path(avito_privacy_slug());
}

add_action('admin_init', function () {
    if (empty($_GET['avito_add_privacy']) || !current_user_can('manage_options')) {
        return;
    }

    check_admin_referer('avito_add_privacy');

    if (avito_privacy_missing()) {
        wp_insert_post([
            'post_title' => 'Политика обработки персональных данных',
            'post_name' => avito_privacy_slug(),
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => avito_default_privacy_html(),
        ]);
    }

    wp_safe_redirect(admin_url('edit.php?post_type=page'));
    exit;
});

add_action('admin_notices', function () {
    if (!current_user_can('manage_options') || !avito_privacy_missing()) {
        return;
    }

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;

    if (!$screen || $screen->id !== 'edit-page') {
        return;
    }

    $url = wp_nonce_url(admin_url('index.php?avito_add_privacy=1'), 'avito_add_privacy');

    echo '<div class="notice notice-info"><p>Страницы «Политика конфиденциальности» ещё нет. '
        . '<a class="button button-primary" href="' . esc_url($url) . '">Создать страницу с типовым текстом</a></p></div>';
});

add_action('after_switch_theme', function () {
    if (get_page_by_path('qr')) {
        return;
    }

    wp_insert_post([
        'post_title' => 'QR-страница',
        'post_name' => 'qr',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template' => 'template-qr.php',
    ]);
});
