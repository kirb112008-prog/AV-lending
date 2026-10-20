<?php
if (!defined('ABSPATH')) {
    exit;
}

$logo = carbon_get_theme_option('logo_text') ?: 'САММИ';
$logo_image = carbon_get_theme_option('logo_image');
$logo_src = $logo_image ?: avito_asset('images/logo-mark.svg');
$base = is_front_page() ? '' : home_url('/');
$nav = carbon_get_theme_option('nav');
$nav = !empty($nav) ? $nav : avito_default_nav();
$button_label = carbon_get_theme_option('header_button_label') ?: 'На платформу →';
$button_href = carbon_get_theme_option('header_button_href') ?: '#contact';
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header>
  <div class="header">
    <div class="container header__inner">
      <a href="<?php echo esc_url($base . '#top'); ?>" class="header__logo">
        <img src="<?php echo esc_url($logo_src); ?>" alt="" class="header__logo-mark" width="30" height="30" />
        <span><?php echo esc_html($logo); ?></span>
      </a>

      <nav class="header__nav" id="site-nav">
        <?php foreach ($nav as $item) : ?>
          <a href="<?php echo esc_url(($item['href'][0] ?? '') === '#' ? $base . $item['href'] : $item['href']); ?>" class="header__link"><?php echo esc_html($item['label']); ?></a>
        <?php endforeach; ?>
      </nav>

      <a href="<?php echo esc_url(($button_href[0] ?? '') === '#' ? $base . $button_href : $button_href); ?>" class="button button--primary"><?php echo esc_html($button_label); ?></a>

      <button type="button" class="header__burger" aria-label="Открыть меню" aria-expanded="false" aria-controls="site-nav">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </div>
</header>

<main>
