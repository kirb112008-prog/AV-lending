<?php
if (!defined('ABSPATH')) {
    exit;
}
$cards = $section['cards'] ?? [];
?>
<section id="solution" class="reason section">
  <div class="container reason__container">
    <h2 class="reason__title"><?php avito_rich_e($section['title'] ?? '', 'reason'); ?></h2>

    <?php if ($cards) : ?>
      <ul class="reason__cards">
        <?php foreach ($cards as $card) : ?>
          <li class="reason-card">
            <div class="reason-card__icon"><?php echo avito_reason_icon($card['icon'] ?? 'chat'); ?></div>
            <h3 class="reason-card__title"><?php echo esc_html($card['title'] ?? ''); ?></h3>
            <ul class="reason-card__items">
              <?php foreach (avito_list($card['items'] ?? '') as $line) : ?>
                <li><?php echo esc_html($line); ?></li>
              <?php endforeach; ?>
            </ul>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php if (!empty($section['button_label'])) : ?>
      <a href="<?php echo esc_url($section['button_href'] ?? '#contact'); ?>" class="reason__button"><?php echo esc_html($section['button_label']); ?></a>
    <?php endif; ?>
  </div>
</section>
