<?php
if (!defined('ABSPATH')) {
    exit;
}

$base = is_front_page() ? '' : home_url('/');
$sticky_label = carbon_get_theme_option('sticky_label') ?: 'Демо';
$sticky_href = carbon_get_theme_option('sticky_href') ?: '#contact';
$logo = carbon_get_theme_option('logo_text') ?: 'САММИ';
$logo_src = carbon_get_theme_option('logo_image') ?: avito_asset('images/logo-mark.svg');

$defaults = avito_default_footer();
$col1 = avito_pairs(carbon_get_theme_option('footer_col1') ?: $defaults['col1']);
$col2 = avito_pairs(carbon_get_theme_option('footer_col2') ?: $defaults['col2']);
$contacts_title = carbon_get_theme_option('footer_contacts_title') ?: $defaults['contacts_title'];
$contacts = avito_list(carbon_get_theme_option('footer_contacts') ?: $defaults['contacts']);
?>
</main>

<footer class="footer">
  <div class="container footer__inner">
    <div class="footer__links">
      <?php foreach ([$col1, $col2] as $column) : ?>
        <ul class="footer__column">
          <?php foreach ($column as $link) : ?>
            <li><a href="<?php echo esc_url(($link[1][0] ?? '') === '#' ? $base . $link[1] : $link[1]); ?>"><?php echo esc_html($link[0]); ?></a></li>
          <?php endforeach; ?>
        </ul>
      <?php endforeach; ?>
    </div>

    <span class="footer__logo">
      <img src="<?php echo esc_url($logo_src); ?>" alt="" class="footer__logo-mark" width="36" height="36" />
      <?php echo esc_html($logo); ?>
    </span>

    <div class="footer__contacts">
      <h3><?php echo esc_html($contacts_title); ?></h3>
      <ul>
        <?php foreach ($contacts as $contact) : ?>
          <li><?php echo esc_html($contact); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</footer>

<?php if (is_front_page()) : ?>
<a href="<?php echo esc_url($sticky_href); ?>" class="sticky-demo"><?php echo esc_html($sticky_label); ?></a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
