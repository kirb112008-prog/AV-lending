<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) {
    the_post();
    avito_render_sections(avito_get_sections(get_the_ID()));
}

get_footer();
