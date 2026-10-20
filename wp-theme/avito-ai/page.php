<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) {
    the_post();
    $sections = avito_get_sections(get_the_ID());

    if ($sections) {
        avito_render_sections($sections);
    } else {
        echo '<article class="doc"><div class="container doc__inner">';
        echo '<div class="doc__head"><h1 class="doc__title">' . esc_html(get_the_title()) . '</h1></div>';
        echo '<div class="doc__body">';
        the_content();
        echo '</div></div></article>';
    }
}

get_footer();
