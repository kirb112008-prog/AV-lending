<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<section id="top" class="hero section">
  <div class="container hero__inner">
    <div class="hero__content">
      <?php if (!empty($section['eyebrow'])) : ?>
        <p class="hero__eyebrow"><?php echo esc_html($section['eyebrow']); ?></p>
      <?php endif; ?>

      <h1 class="hero__title"><?php avito_rich_e($section['title'] ?? '', 'hero'); ?></h1>

      <p class="hero__text"><?php echo esc_html($section['text'] ?? ''); ?></p>

      <div class="hero__buttons">
        <?php if (!empty($section['primary_label'])) : ?>
          <a href="<?php echo esc_url($section['primary_href'] ?? '#contact'); ?>" class="button button--primary"><?php echo esc_html($section['primary_label']); ?></a>
        <?php endif; ?>
        <?php if (!empty($section['secondary_label'])) : ?>
          <a href="<?php echo esc_url($section['secondary_href'] ?? '#preview'); ?>" class="button button--secondary"><?php echo esc_html($section['secondary_label']); ?></a>
        <?php endif; ?>
      </div>
    </div>

    <div class="hero__preview" aria-label="Превью платформы">
      <div class="hero__shot hero__shot--dialogs">
        <img src="<?php echo avito_asset('images/hero-image0.png'); ?>" alt="" decoding="async" fetchpriority="high" />
      </div>
      <div class="hero__shot hero__shot--cabinet">
        <img src="<?php echo avito_asset('images/hero-image1.png'); ?>" alt="" decoding="async" />
      </div>
      <div class="hero__shot hero__shot--summary">
        <img src="<?php echo avito_asset('images/hero-image2.png'); ?>" alt="" decoding="async" />
      </div>
    </div>
  </div>
</section>
