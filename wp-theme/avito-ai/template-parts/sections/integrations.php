<?php
if (!defined('ABSPATH')) {
    exit;
}
$cards = $section['cards'] ?? [];
?>
<section id="integrations" class="integrations section">
  <div class="container">
    <div class="integrations__panel">
      <h2 class="integrations__title"><?php avito_rich_e($section['title'] ?? '', 'integrations'); ?></h2>

      <?php if ($cards) : ?>
        <ul class="integrations__list">
          <?php foreach ($cards as $card) : ?>
            <li class="integration">
              <span class="integration__icon" aria-hidden="true"><?php echo avito_integration_icon($card['icon'] ?? 'lock'); ?></span>
              <h3 class="integration__title"><?php echo esc_html($card['title'] ?? ''); ?></h3>
              <p class="integration__text"><?php echo esc_html($card['text'] ?? ''); ?></p>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</section>
