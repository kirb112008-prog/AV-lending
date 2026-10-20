<?php
if (!defined('ABSPATH')) {
    exit;
}
$stats = $section['stats'] ?? [];
?>
<section id="preview" class="presentation section">
  <div class="container presentation__container">
    <div class="presentation__top">
      <div class="presentation__video">
        <?php
        $video = avito_video($section);
        $poster = trim((string) ($section['video_poster'] ?? ''));
        ?>
        <div class="presentation__video-inner<?php echo $video ? ' presentation__video-inner--media' : ''; ?>">
          <?php if ($video && $video['type'] === 'file') : ?>
            <video
              class="presentation__media"
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
            <?php if ($poster) : ?>
              <img class="presentation__poster" src="<?php echo esc_url($poster); ?>" alt="" decoding="async" />
            <?php endif; ?>
            <div class="presentation__embed" data-video-src="<?php echo esc_url($video['src']); ?>"></div>
          <?php endif; ?>
        </div>
      </div>

      <div class="presentation__aside">
        <h2 class="presentation__title"><?php avito_rich_e($section['title'] ?? '', 'presentation'); ?></h2>

        <?php if (!empty($section['button_label'])) : ?>
          <div class="presentation__cta">
            <a href="<?php echo esc_url($section['button_href'] ?? '#contact'); ?>" class="presentation__button"><?php echo esc_html($section['button_label']); ?></a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($stats) : ?>
      <ul class="presentation__stats">
        <?php foreach ($stats as $stat) :
            $value = (string) ($stat['value'] ?? '');
            $has_unit = substr($value, -1) === '%';
            ?>
          <li class="stat-card">
            <span class="stat-card__prefix"><?php echo esc_html($stat['prefix'] ?? ''); ?></span>
            <span class="stat-card__value">
              <?php echo esc_html($has_unit ? substr($value, 0, -1) : $value); ?>
              <?php if ($has_unit) : ?><span class="stat-card__unit">%</span><?php endif; ?>
            </span>
            <span class="stat-card__text"><?php echo esc_html($stat['text'] ?? ''); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</section>
