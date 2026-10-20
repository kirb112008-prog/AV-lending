<?php
if (!defined('ABSPATH')) {
    exit;
}
$niches = avito_list($section['niches'] ?? '');
$label = $section['button_label'] ?? 'Отправить и получить демо';
?>
<section id="contact" class="cta section">
  <div class="container cta__container">
    <div class="cta__panel">
      <div class="cta__intro">
        <h2 class="cta__title">
          <?php echo esc_html($section['title_line'] ?? ''); ?>
          <span class="cta__title-accent"><?php echo esc_html($section['title_accent'] ?? ''); ?></span>
        </h2>
        <?php if (!empty($section['text'])) : ?>
          <p class="cta__text"><?php echo esc_html($section['text']); ?></p>
        <?php endif; ?>
      </div>

      <form
        class="cta__form"
        data-cta-form
        novalidate
        data-endpoint="<?php echo esc_url(avito_leads_endpoint_url()); ?>"
        data-captcha-key="<?php echo esc_attr(avito_captcha_client_key()); ?>"
        data-redirect="<?php echo esc_url($section['redirect'] ?? ''); ?>"
        data-label="<?php echo esc_attr($label); ?>"
      >
        <div class="cta__fields">
          <div class="cta__field">
            <input name="email" type="email" placeholder="Email" autocomplete="email" required />
            <p class="cta__error" data-error-for="email" role="alert" hidden></p>
          </div>
          <div class="cta__field">
            <input name="phone" type="tel" placeholder="Телефон" autocomplete="tel" inputmode="tel" data-phone-mask required />
            <p class="cta__error" data-error-for="phone" role="alert" hidden></p>
          </div>
          <div class="cta__field">
            <select name="niche" required>
              <option value="" disabled hidden selected>Рабочая ниша</option>
              <?php foreach ($niches as $niche) : ?>
                <option value="<?php echo esc_attr($niche); ?>"><?php echo esc_html($niche); ?></option>
              <?php endforeach; ?>
            </select>
            <p class="cta__error" data-error-for="niche" role="alert" hidden></p>
          </div>
          <div class="cta__field">
            <input name="name" type="text" placeholder="Компания" autocomplete="organization" required />
            <p class="cta__error" data-error-for="name" data-label="название компании" role="alert" hidden></p>
          </div>
        </div>

        <button type="submit" class="cta__button"><?php echo esc_html($label); ?></button>

        <?php if (!empty($section['agreement'])) : ?>
          <p class="cta__agreement"><?php echo avito_agreement($section['agreement']); ?></p>
        <?php endif; ?>
      </form>
    </div>
  </div>
</section>
