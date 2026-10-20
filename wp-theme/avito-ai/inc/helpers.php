<?php
if (!defined('ABSPATH')) {
    exit;
}

function avito_rich(string $text, string $prefix = ''): string
{
    $out = esc_html($text);
    $out = preg_replace('/\*\*(.+?)\*\*/su', '<strong>$1</strong>', $out);

    if ($prefix !== '') {
        $p = esc_attr($prefix);
        $out = preg_replace('/\{\{(.+?)\}\}/su', '<span class="' . $p . '__accent">$1</span>', $out);
        $out = preg_replace('/\(\((.+?)\)\)/su', '<span class="' . $p . '__accent-mobile">$1</span>', $out);
        $out = preg_replace('/\s*\|\|\s*/u', ' <br class="' . $p . '__br" />', $out);
    }

    return $out;
}

function avito_rich_e(string $text, string $prefix = ''): void
{
    echo avito_rich($text, $prefix);
}

function avito_asset(string $path): string
{
    $relative = '/assets/' . ltrim($path, '/');
    $file = get_template_directory() . $relative;
    $url = get_template_directory_uri() . $relative;

    if (file_exists($file)) {
        $url = add_query_arg('ver', filemtime($file), $url);
    }

    return esc_url($url);
}

function avito_get_sections(?int $post_id = null): array
{
    $post_id = $post_id ?: get_the_ID();
    $sections = carbon_get_post_meta($post_id, 'sections');

    return is_array($sections) ? $sections : [];
}

function avito_render_sections(array $sections): void
{
    foreach ($sections as $section) {
        $type = $section['_type'] ?? '';
        $file = get_template_directory() . '/template-parts/sections/' . sanitize_file_name($type) . '.php';

        if ($type === '' || !file_exists($file)) {
            continue;
        }

        $wrap = $type !== 'hero';

        if ($wrap) {
            echo '<div class="reveal">';
        }

        include $file;

        if ($wrap) {
            echo '</div>';
        }
    }
}

function avito_lines(string $text, string $class = 'step__br'): string
{
    $lines = preg_split('/\r\n|\r|\n/', $text);
    $out = '';
    $last = count($lines) - 1;

    foreach ($lines as $i => $line) {
        $out .= esc_html($line);

        if ($i < $last) {
            if (substr($line, -1) !== '-') {
                $out .= ' ';
            }
            $out .= '<br class="' . esc_attr($class) . '" />';
        }
    }

    return $out;
}

function avito_list(string $text): array
{
    $lines = preg_split('/\r\n|\r|\n/', $text);
    $lines = array_map('trim', $lines);

    return array_values(array_filter($lines, fn($l) => $l !== ''));
}

function avito_pairs(string $text, string $separator = '|'): array
{
    $pairs = [];

    foreach (avito_list($text) as $line) {
        $parts = array_map('trim', explode($separator, $line, 2));
        $pairs[] = [$parts[0], $parts[1] ?? ''];
    }

    return $pairs;
}

function avito_icon_svg(string $key): string
{
    $icons = [
        'chat' => '<svg width="22" height="14" viewBox="0 0 22 14" fill="none" aria-hidden="true"><rect width="22" height="14" rx="5" fill="#fff" /><circle cx="6" cy="7" r="1.6" fill="#5372BC" /><circle cx="11" cy="7" r="1.6" fill="#5372BC" /><circle cx="16" cy="7" r="1.6" fill="#5372BC" /></svg>',
        'grid' => '<svg width="17" height="17" viewBox="0 0 17 17" fill="none" aria-hidden="true"><rect width="7" height="7" rx="2" fill="#fff" /><rect x="10" width="7" height="7" rx="2" fill="#fff" /><rect y="10" width="7" height="7" rx="2" fill="#fff" /><rect x="10" y="10" width="7" height="7" rx="2" fill="#fff" /></svg>',
        'bars' => '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><rect y="9" width="4" height="7" rx="2" fill="#fff" /><rect x="6" y="7" width="4" height="9" rx="2" fill="#fff" /><rect x="12" y="2" width="4" height="14" rx="2" fill="#fff" /></svg>',
        'team' => '<svg width="18" height="16" viewBox="0 0 18 16" fill="none" aria-hidden="true"><circle cx="6" cy="4" r="3" fill="#fff" /><circle cx="13" cy="5" r="2.2" fill="#fff" /><rect y="9" width="12" height="7" rx="3.5" fill="#fff" /></svg>',
    ];

    return $icons[$key] ?? '';
}

function avito_reason_icon(string $key): string
{
    return match ($key) {
        'grid' => '<span class="reason-card__icon-grid"><i></i><i></i><i></i><i></i></span>',
        'bars' => '<span class="reason-card__icon-bars"><i></i><i></i><i></i></span>',
        default => '<span class="reason-card__icon-chat"><i></i><i></i><i></i></span>',
    };
}

function avito_integration_icon(string $key): string
{
    return match ($key) {
        'rings' => '<i class="ic-ring ic-ring--a"></i><i class="ic-ring ic-ring--b"></i>',
        'head' => '<i class="ic-head"></i><i class="ic-neck"></i>',
        'shield' => '<i class="ic-shackle"></i><i class="ic-body"></i>',
        'bars' => '<i class="ic-bar ic-bar--1"></i><i class="ic-bar ic-bar--2"></i><i class="ic-bar ic-bar--3"></i>',
        default => '<i class="ic-lock"></i>',
    };
}

function avito_video(array $section): ?array
{
    $file = trim((string) ($section['video_file'] ?? ''));

    if ($file !== '') {
        return ['type' => 'file', 'src' => $file];
    }

    $url = trim((string) ($section['video_url'] ?? ''));

    if ($url === '') {
        return null;
    }

    if (preg_match('~\.(mp4|webm|ogg)(\?.*)?$~i', $url)) {
        return ['type' => 'file', 'src' => $url];
    }

    if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,})~', $url, $m)) {
        return ['type' => 'iframe', 'src' => 'https://www.youtube-nocookie.com/embed/' . $m[1] . '?autoplay=1&mute=1&loop=1&playlist=' . $m[1] . '&rel=0&playsinline=1'];
    }

    if (preg_match('~rutube\.ru/(?:video|play/embed)/([A-Za-z0-9]+)~', $url, $m)) {
        return ['type' => 'iframe', 'src' => 'https://rutube.ru/play/embed/' . $m[1] . '?autoplay=1&mute=1'];
    }

    if (preg_match('~vk(?:video)?\.(?:com|ru)/video(-?\d+)_(\d+)~', $url, $m)) {
        return ['type' => 'iframe', 'src' => 'https://vk.com/video_ext.php?oid=' . $m[1] . '&id=' . $m[2] . '&autoplay=1&mute=1'];
    }

    if (preg_match('~^https?://~i', $url)) {
        return ['type' => 'iframe', 'src' => $url];
    }

    return null;
}
