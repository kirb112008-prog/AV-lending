<?php
if (!defined('ABSPATH')) {
    exit;
}
$items = $section['items'] ?? [];
?>
<section id="problem" class="mistakes section">
  <div class="container mistakes__container">
    <h2 class="mistakes__title"><?php avito_rich_e($section['title'] ?? '', 'mistakes'); ?></h2>

    <?php if (!empty($section['subtitle'])) : ?>
      <p class="mistakes__subtitle"><?php avito_rich_e($section['subtitle']); ?></p>
    <?php endif; ?>

    <?php if ($items) : ?>
      <ul class="mistakes__list">
        <?php foreach ($items as $item) : ?>
          <li class="mistake">
            <div class="mistake__main">
              <h3 class="mistake__title"><?php avito_rich_e($item['title'] ?? ''); ?></h3>
              <p class="mistake__text"><?php avito_rich_e($item['text'] ?? ''); ?></p>
            </div>
            <div class="mistake__result">
              <p class="mistake__result-text"><?php avito_rich_e($item['result'] ?? ''); ?></p>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php if (!empty($section['footer'])) : ?>
      <p class="mistakes__footer"><?php avito_rich_e($section['footer'], 'mistakes'); ?></p>
    <?php endif; ?>

    <?php if (!empty($section['cta_button_label'])) : ?>
      <div class="mistakes__cta">
        <span class="mistakes__glow" aria-hidden="true"></span>
        <p class="mistakes__cta-label"><?php echo esc_html($section['cta_label'] ?? ''); ?></p>
        <svg class="mistakes__arrow" width="616" height="94" viewBox="0 0 616 94" fill="none" aria-hidden="true">
          <path d="M214 26C214 59.07 338.432 64 400 64" stroke="#006BCF" />
          <path d="M386.203 52C385.358 53.45 386.203 64 408 64C386.203 64 386.541 75.03 388.737 76" stroke="#006BCF" />
        </svg>
        <svg class="mistakes__arrow-mobile" width="36" height="44" viewBox="0 0 36 44" fill="none" aria-hidden="true">
          <path d="M7 4C-5 20 23 24 29 34" stroke="#3E6FD8" stroke-width="1.2" />
          <path d="M23 32L29 34V27" stroke="#3E6FD8" stroke-width="1.2" />
        </svg>
        <a href="<?php echo esc_url($section['cta_button_href'] ?? '#calculator'); ?>" class="mistakes__button"><?php echo esc_html($section['cta_button_label']); ?></a>
      </div>
    <?php endif; ?>
  </div>
</section>
