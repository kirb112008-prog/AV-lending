<?php
if (!defined('ABSPATH')) {
    exit;
}

define('AVITO_AI_VERSION', '0.1.0');

$avito_ai_autoload = get_template_directory() . '/vendor/autoload.php';
$avito_ai_includes = ['helpers', 'defaults', 'privacy', 'leads'];

if (!file_exists($avito_ai_autoload)) {
    add_action('admin_notices', function () {
        echo '<div class="notice notice-error"><p>Тема Avito AI: не найдена папка <code>vendor</code>. Выполните <code>composer install</code> в папке темы, чтобы установить Carbon Fields.</p></div>';
    });

    if (!function_exists('carbon_get_theme_option')) {
        function carbon_get_theme_option($name)
        {
            return null;
        }
    }

    if (!function_exists('carbon_get_post_meta')) {
        function carbon_get_post_meta($id, $name)
        {
            return [];
        }
    }

    foreach ($avito_ai_includes as $include) {
        require_once get_template_directory() . '/inc/' . $include . '.php';
    }

    return;
}

require_once $avito_ai_autoload;

foreach (array_merge($avito_ai_includes, ['fields', 'setup']) as $include) {
    require_once get_template_directory() . '/inc/' . $include . '.php';
}
