<?php
if (!defined('ABSPATH')) {
    exit;
}
$items = $section['items'] ?? [];
?>
<section id="faq" class="faq section">
  <div class="container faq__container">
    <h2 class="faq__title"><?php avito_rich_e($section['title'] ?? '', 'faq'); ?></h2>

    <?php if ($items) : ?>
      <ul class="faq__list">
        <?php foreach ($items as $index => $item) :
            $open = $index === 0;
            ?>
          <li class="faq__item<?php echo $open ? ' faq__item--open' : ''; ?>">
            <button type="button" class="faq__question" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
              <span><?php echo esc_html($item['question'] ?? ''); ?></span>
              <span class="faq__icon" aria-hidden="true"></span>
            </button>

            <div class="faq__panel" aria-hidden="<?php echo $open ? 'false' : 'true'; ?>">
              <div class="faq__panel-inner">
                <p class="faq__answer"><?php echo esc_html($item['answer'] ?? ''); ?></p>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</section>
