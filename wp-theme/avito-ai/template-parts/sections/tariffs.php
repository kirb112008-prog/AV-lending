<?php
if (!defined('ABSPATH')) {
    exit;
}
$items = $section['items'] ?? [];
?>
<section id="tariffs" class="tariffs section">
  <div class="container">
    <h2 class="tariffs__title"><?php echo esc_html($section['title'] ?? ''); ?></h2>

    <?php if ($items) : ?>
      <ul class="tariffs__list">
        <?php foreach ($items as $tariff) :
            $featured = !empty($tariff['featured']);
            ?>
          <li class="tariff<?php echo $featured ? ' tariff--featured' : ''; ?>">
            <?php if ($featured && !empty($section['badge'])) : ?>
              <span class="tariff__badge"><?php echo esc_html($section['badge']); ?></span>
            <?php endif; ?>
            <h3 class="tariff__name"><?php echo esc_html($tariff['name'] ?? ''); ?></h3>

            <p class="tariff__price">
              <strong><?php echo esc_html($tariff['price'] ?? ''); ?></strong>
              <span><?php echo esc_html($section['price_note'] ?? ''); ?></span>
            </p>

            <ul class="tariff__features">
              <?php foreach (avito_pairs($tariff['features'] ?? '') as $feature) : ?>
                <li>
                  <span class="tariff__label"><?php echo esc_html($feature[0]); ?></span>
                  <span class="tariff__value"><?php echo esc_html($feature[1]); ?></span>
                </li>
              <?php endforeach; ?>
            </ul>

            <?php if (!empty($section['button_label'])) : ?>
              <a href="<?php echo esc_url($section['button_href'] ?? '#contact'); ?>" class="tariff__button"><?php echo esc_html($section['button_label']); ?></a>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

  </div>
</section>
