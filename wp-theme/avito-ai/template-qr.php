<?php
/**
 * Template Name: QR-страница
 */
if (!defined('ABSPATH')) {
    exit;
}

$meta = function (string $name, string $default = '') {
    $value = carbon_get_post_meta(get_the_ID(), $name);

    return ($value !== null && $value !== '' && $value !== false) ? $value : $default;
};

$logo = carbon_get_theme_option('logo_text') ?: 'САММИ';
$logo_src = carbon_get_theme_option('logo_image') ?: avito_asset('images/logo-mark.svg');

$features = avito_list($meta('features', "ИИ отвечает клиентам 24/7\nВсе объявления в одном окне\nНаглядная аналитика"));
$niches = avito_list($meta('niches', "Недвижимость\nАвто\nУслуги\nТовары\nДругое"));
$button_label = $meta('button_label', 'Получить демо-доступ');
$video = avito_video([
    'video_file' => $meta('video_file'),
    'video_url' => $meta('video_url'),
]);
$poster = trim((string) $meta('video_poster'));

$icons = [
    '<svg class="qr-feature__icon" viewBox="33 345 44 44" fill="none" aria-hidden="true"><path d="M65 345H45C38.3726 345 33 350.373 33 357V377C33 383.627 38.3726 389 45 389H65C71.6274 389 77 383.627 77 377V357C77 350.373 71.6274 345 65 345Z" fill="#2E4D89"/><circle cx="55" cy="367" r="10" stroke="#fff" stroke-width="2.2"/><path d="M55 361V367L59 369.5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    '<svg class="qr-feature__icon" viewBox="33 433 44 44" fill="none" aria-hidden="true"><path d="M65 433H45C38.3726 433 33 438.373 33 445V465C33 471.627 38.3726 477 45 477H65C71.6274 477 77 471.627 77 465V445C77 438.373 71.6274 433 65 433Z" fill="#2E4D89"/><rect x="45" y="445" width="8.5" height="8.5" rx="2.5" fill="#fff"/><rect x="56.5" y="445" width="8.5" height="8.5" rx="2.5" fill="#fff"/><rect x="45" y="456.5" width="8.5" height="8.5" rx="2.5" fill="#fff"/><rect x="56.5" y="456.5" width="8.5" height="8.5" rx="2.5" fill="#fff"/></svg>',
    '<svg class="qr-feature__icon" viewBox="33 521 44 44" fill="none" aria-hidden="true"><path d="M65 521H45C38.3726 521 33 526.373 33 533V553C33 559.627 38.3726 565 45 565H65C71.6274 565 77 559.627 77 553V533C77 526.373 71.6274 521 65 521Z" fill="#2E4D89"/><rect x="45" y="544" width="5" height="9" rx="2.5" fill="#fff"/><rect x="52.5" y="539" width="5" height="14" rx="2.5" fill="#fff"/><rect x="60" y="533" width="5" height="20" rx="2.5" fill="#fff"/></svg>',
];

$legal = avito_link_markers(
    $meta('legal', "Нажимая «Получить демо-доступ», вы соглашаетесь\nна обработку персональных данных и принимаете\nусловия [[Политики конфиденциальности]]"),
    avito_privacy_url(),
    'qr-link'
);
$more = avito_link_markers($meta('more', 'Больше о продукте — [[на основном сайте]]'), home_url('/'), 'qr-link');
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php wp_head(); ?>
</head>
<body <?php body_class('qr-body'); ?>>
<?php wp_body_open(); ?>

<main class="qr">
  <div class="qr-page">
    <section class="qr-hero">
      <p class="qr-hero__logo">
        <img src="<?php echo esc_url($logo_src); ?>" alt="" width="26" height="26" />
        <?php echo esc_html($logo); ?>
      </p>
      <div class="qr-hero__badge"><?php echo esc_html($meta('badge', 'Платформа для Авито')); ?></div>
      <h1 class="qr-hero__title">
        <?php echo esc_html($meta('title_line', 'Не теряйте клиентов')); ?>
        <br />
        <small><?php echo esc_html($meta('title_small', 'на')); ?></small> <?php echo esc_html($meta('title_accent', 'Авито')); ?>
      </h1>
      <p class="qr-hero__subtitle"><?php echo esc_html($meta('subtitle', 'ИИ-сотрудник в чате + аналитика')); ?></p>
      <a href="#qr-name" class="qr-hero__cta"><?php echo esc_html($button_label); ?></a>
    </section>

    <?php foreach (array_slice($features, 0, 3) as $index => $text) : ?>
      <div class="qr-feature" style="top: <?php echo 328 + $index * 88; ?>px">
        <?php echo $icons[$index]; ?>
        <p class="qr-feature__text"><?php echo esc_html($text); ?></p>
      </div>
    <?php endforeach; ?>

    <h2 class="qr-how"><?php avito_rich_e($meta('how_title', 'Как это работает — за {{2 минуты}}'), 'qr-how'); ?></h2>

    <div class="qr-video">
      <?php if ($video && $video['type'] === 'file') : ?>
        <video
          class="qr-video__media"
          data-autoplay
          muted
          loop
          playsinline
          controls
          preload="none"
          <?php echo $poster ? 'poster="' . esc_url($poster) . '"' : ''; ?>
          src="<?php echo esc_url($video['src']); ?>"
        ></video>
      <?php elseif ($video) : ?>
        <div class="qr-video__embed" data-video-src="<?php echo esc_url($video['src']); ?>"></div>
      <?php else : ?>
        Video
      <?php endif; ?>
    </div>

    <form
      class="qr-lead"
      data-cta-form
      novalidate
      data-endpoint="<?php echo esc_url(avito_leads_endpoint_url()); ?>"
      data-captcha-key="<?php echo esc_attr(avito_captcha_client_key()); ?>"
      data-redirect="<?php echo esc_url($meta('redirect', 'https://159-194-249-131.sslip.io/c/litera/agent')); ?>"
      data-label="<?php echo esc_attr($button_label); ?>"
    >
      <h2 class="qr-lead__title"><?php echo esc_html($meta('form_title', 'Демо-доступ бесплатно')); ?></h2>
      <p class="qr-lead__subtitle"><?php echo esc_html($meta('form_subtitle', 'Перезвоним в течение дня и все покажем')); ?></p>

      <p class="qr-field__error" style="top: 94.5px" data-error-for="name" role="alert" hidden></p>
      <label class="qr-field__label" style="top: 94.5px" for="qr-name">Имя</label>
      <input id="qr-name" class="qr-field__control" style="top: 119px" type="text" autocomplete="name" placeholder="Как к Вам обращаться" name="name" required />

      <p class="qr-field__error" style="top: 190px" data-error-for="email" role="alert" hidden></p>
      <label class="qr-field__label" style="top: 190px" for="qr-email">Email</label>
      <input id="qr-email" class="qr-field__control" style="top: 215px" type="email" autocomplete="email" inputmode="email" placeholder="example@mail.ru" name="email" required />

      <p class="qr-field__error" style="top: 286px" data-error-for="phone" role="alert" hidden></p>
      <label class="qr-field__label" style="top: 286px" for="qr-phone">Телефон</label>
      <input id="qr-phone" class="qr-field__control" style="top: 311px" type="tel" autocomplete="tel" inputmode="tel" placeholder="+7 (___) ___-__-__" name="phone" data-phone-mask required />

      <p class="qr-field__error" style="top: 381.5px" data-error-for="niche" role="alert" hidden></p>
      <label class="qr-field__label" style="top: 381.5px" for="qr-niche">Ниша</label>
      <div class="qr-field__select-wrap" style="top: 406.5px">
        <select id="qr-niche" class="qr-field__control qr-field__select" name="niche" required>
          <option value="" disabled selected>Выберите нишу</option>
          <?php foreach ($niches as $niche) : ?>
            <option value="<?php echo esc_attr($niche); ?>"><?php echo esc_html($niche); ?></option>
          <?php endforeach; ?>
        </select>
        <svg class="qr-field__chevron" viewBox="0 0 12 7" fill="none" aria-hidden="true"><path d="M1 1L6 6L11 1" stroke="#0B1530" stroke-width="2"/></svg>
      </div>

      <button type="submit" class="qr-lead__submit" style="top: 488px" data-submit><?php echo esc_html($button_label); ?></button>

      <p class="qr-lead__status qr-lead__status--error" data-status-error hidden>Не удалось отправить. Попробуйте ещё раз.</p>

      <p class="qr-lead__legal"><?php echo nl2br($legal); ?></p>
    </form>

    <p class="qr-more"><?php echo $more; ?></p>
  </div>
</main>

<?php wp_footer(); ?>
</body>
</html>
