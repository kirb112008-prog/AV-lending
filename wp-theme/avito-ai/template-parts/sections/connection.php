<?php
if (!defined('ABSPATH')) {
    exit;
}
$steps = $section['steps'] ?? [];
?>
<section id="connection" class="connection section">
  <div class="container connection__container">
    <h2 class="connection__title"><?php avito_rich_e($section['title'] ?? '', 'connection'); ?></h2>

    <?php if (!empty($section['subtitle'])) : ?>
      <p class="connection__subtitle"><?php echo esc_html($section['subtitle']); ?></p>
    <?php endif; ?>

    <?php if ($steps) : ?>
      <div class="connection__steps-wrap">
        <span class="connection__line" aria-hidden="true"></span>
        <ol class="connection__steps">
          <?php foreach ($steps as $index => $step) : ?>
            <li class="step">
              <span class="step__number"><?php echo (int) $index + 1; ?></span>
              <h3 class="step__title"><?php echo avito_lines($step['title'] ?? ''); ?></h3>
              <p class="step__text"><?php echo avito_lines($step['text'] ?? ''); ?></p>
            </li>
          <?php endforeach; ?>
        </ol>
      </div>
    <?php endif; ?>

    <?php if (!empty($section['badge_1']) || !empty($section['badge_2'])) : ?>
      <div class="connection__badges">
        <?php if (!empty($section['badge_1'])) : ?>
          <span class="connection__badge"><?php echo esc_html($section['badge_1']); ?></span>
        <?php endif; ?>
        <?php if (!empty($section['badge_2'])) : ?>
          <span class="connection__badge connection__badge--filled"><?php echo esc_html($section['badge_2']); ?></span>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="connection__buttons">
      <?php if (!empty($section['button_label'])) : ?>
        <a href="<?php echo esc_url($section['button_href'] ?? '#contact'); ?>" class="connection__button"><?php echo esc_html($section['button_label']); ?></a>
      <?php endif; ?>
      <?php if (!empty($section['button2_label'])) : ?>
        <a href="<?php echo esc_url($section['button2_href'] ?? '#preview'); ?>" class="connection__button connection__button--light"><?php echo esc_html($section['button2_label']); ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
