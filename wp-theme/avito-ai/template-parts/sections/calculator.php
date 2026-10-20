<?php
if (!defined('ABSPATH')) {
    exit;
}
$defaults = ['requestsPerMonth' => 300, 'averageCheck' => 25000, 'responseMinutes' => 15, 'conversionPercent' => 10];
$fields = [
    'requestsPerMonth' => 'Количество обращений в месяц',
    'averageCheck' => 'Средний чек, ₽',
    'responseMinutes' => 'Текущая скорость ответа, мин',
    'conversionPercent' => 'Конверсия в сделку, %',
];

$loss_rate = min(0.6, $defaults['responseMinutes'] * (0.4 / 15));
$lost_requests = (int) round($defaults['requestsPerMonth'] * $loss_rate);
$lost_revenue = (int) round($lost_requests * ($defaults['conversionPercent'] / 100) * $defaults['averageCheck']);
$recoverable = (int) round($lost_revenue * 0.75);
$fmt = fn(int $n) => number_format($n, 0, ',', "\u{00A0}");
?>
<section id="calculator" class="calculator section">
  <div class="container">
    <div class="calculator__panel">
      <h2 class="calculator__title"><?php avito_rich_e($section['title'] ?? '', 'calculator'); ?></h2>

      <?php if (!empty($section['subtitle'])) : ?>
        <p class="calculator__subtitle"><?php echo esc_html($section['subtitle']); ?></p>
      <?php endif; ?>

      <div class="calculator__body" data-calculator>
        <form class="calculator__form" onsubmit="return false">
          <?php foreach ($fields as $name => $label) : ?>
            <label class="calculator__field">
              <span><?php echo esc_html($label); ?></span>
              <input type="text" inputmode="numeric" name="<?php echo esc_attr($name); ?>" value="<?php echo (int) $defaults[$name]; ?>" />
            </label>
          <?php endforeach; ?>
        </form>

        <div class="calculator__result">
          <span class="calculator__result-title">Ваш результат</span>

          <div>
            <strong data-result="lostRequests"><?php echo esc_html($fmt($lost_requests)); ?></strong>
            <span>обращений теряется в месяц</span>
          </div>
          <div>
            <strong data-result="lostRevenue" data-suffix=" ₽"><?php echo esc_html($fmt($lost_revenue)); ?> ₽</strong>
            <span>недополученная выручка в месяц</span>
          </div>
          <div>
            <strong data-result="recoverableRevenue" data-prefix="до " data-suffix=" ₽">до <?php echo esc_html($fmt($recoverable)); ?> ₽</strong>
            <span>может вернуть платформа</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
