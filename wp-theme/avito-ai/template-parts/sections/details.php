<?php
if (!defined('ABSPATH')) {
    exit;
}
$columns = $section['columns'] ?? [];
$shots = [
    'chats' => 'images/platform-chats.jpg',
    'ads' => 'images/platform-ads.jpg',
    'analytics' => 'images/platform-analytics.jpg',
    'adcabinet' => 'images/platform-adcabinet.jpg',
];
?>
<section id="details" class="details section">
  <div class="container details__container">
    <h2 class="details__title"><?php avito_rich_e($section['title'] ?? '', 'details'); ?></h2>

    <?php if ($columns) : ?>
      <div class="details__tabs" role="tablist">
        <?php foreach ($columns as $i => $column) : ?>
          <button type="button" role="tab" id="details-tab-<?php echo (int) $i; ?>" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-controls="details-panel-<?php echo (int) $i; ?>" class="details__tab<?php echo $i === 0 ? ' details__tab--active' : ''; ?>">
            <?php echo esc_html($column['title'] ?? ''); ?>
          </button>
        <?php endforeach; ?>
      </div>

      <?php foreach ($columns as $i => $column) : ?>
        <?php
        $shot = $column['shot'] ?? '';
        $image = isset($shots[$shot]) ? avito_asset($shots[$shot]) : '';
        $extras = avito_list($column['extras'] ?? '');
        ?>
        <div role="tabpanel" id="details-panel-<?php echo (int) $i; ?>" aria-labelledby="details-tab-<?php echo (int) $i; ?>" class="details__panel<?php echo $image ? '' : ' details__panel--text'; ?>"<?php echo $i === 0 ? '' : ' hidden'; ?>>
          <?php if ($image) : ?>
            <div class="details__shot">
              <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr('Платформа: ' . ($column['title'] ?? '')); ?>" loading="lazy" decoding="async" />
            </div>
          <?php endif; ?>

          <ul class="details__items">
            <?php foreach (avito_list($column['items'] ?? '') as $line) : ?>
              <li><?php echo esc_html($line); ?></li>
            <?php endforeach; ?>
          </ul>

          <?php if ($image && $extras) : ?>
            <p class="details__extras"><span>А также:</span> <?php echo esc_html(implode(' · ', $extras)); ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($section['button_label'])) : ?>
      <a href="<?php echo esc_url($section['button_href'] ?? '#contact'); ?>" class="details__cta"><?php echo esc_html($section['button_label']); ?></a>
    <?php endif; ?>
  </div>
</section>
