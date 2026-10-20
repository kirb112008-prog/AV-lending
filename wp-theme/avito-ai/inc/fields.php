<?php
if (!defined('ABSPATH')) {
    exit;
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('after_setup_theme', function () {
    \Carbon_Fields\Carbon_Fields::boot();
});

add_action('carbon_fields_register_fields', function () {
    $rich_help = 'Разметка: **жирный**, {{акцент}}, || — перенос строки на десктопе.';

    Container::make('theme_options', 'avito-ai-settings', 'Настройки сайта')
        ->set_page_file('avito-ai-settings')
        ->set_page_menu_position(30)
        ->set_icon('dashicons-admin-generic')
        ->add_fields([
            Field::make('text', 'logo_text', 'Название в логотипе')->set_default_value('САММИ'),
            Field::make('image', 'logo_image', 'Знак логотипа (необязательно)')->set_value_type('url')->set_help_text('Квадратная картинка или SVG. Если не задана, используется знак по умолчанию.'),
            Field::make('complex', 'nav', 'Меню')
                ->set_layout('tabbed-horizontal')
                ->add_fields([
                    Field::make('text', 'label', 'Название')->set_required(true),
                    Field::make('text', 'href', 'Ссылка или якорь (например #faq)')->set_required(true),
                ])
                ->set_header_template('<%- label %>'),
            Field::make('text', 'header_button_label', 'Кнопка в шапке')->set_default_value('На платформу →'),
            Field::make('text', 'header_button_href', 'Ссылка кнопки в шапке')->set_default_value('#contact'),
            Field::make('text', 'sticky_label', 'Плавающая кнопка: текст')->set_default_value('Демо'),
            Field::make('text', 'sticky_href', 'Плавающая кнопка: ссылка')->set_default_value('#contact'),
            Field::make('textarea', 'footer_col1', 'Футер: первый столбец ссылок')->set_rows(5)->set_help_text('По одной на строку: Название | ссылка'),
            Field::make('textarea', 'footer_col2', 'Футер: второй столбец ссылок')->set_rows(6)->set_help_text('По одной на строку: Название | ссылка'),
            Field::make('text', 'footer_contacts_title', 'Футер: заголовок контактов'),
            Field::make('textarea', 'footer_contacts', 'Футер: контакты')->set_rows(5)->set_help_text('По одному на строку, например: телефон: +7 900 000-00-00'),
        ]);

    Container::make('post_meta', 'Разделы страницы')
        ->where('post_type', '=', 'page')
        ->add_fields([
            Field::make('html', 'avito_tools')->set_html(function () {
                $page_id = (int) get_the_ID();

                if (!$page_id || !function_exists('avito_missing_section_types')) {
                    return '';
                }

                $missing = avito_missing_section_types($page_id);

                if (!$missing) {
                    return '<p>Все разделы добавлены.</p>';
                }

                $url = wp_nonce_url(admin_url('index.php?avito_add_missing=1&avito_page=' . $page_id), 'avito_add_missing');

                return '<p>Нет разделов: <code>' . esc_html(implode(', ', array_column($missing, '_type'))) . '</code>.</p>'
                    . '<p><a class="button button-primary" href="' . esc_url($url) . '">Добавить недостающие разделы</a></p>'
                    . '<p>Сначала нажмите «Save» (Сохранить), если вы что-то меняли на странице.</p>';
            }),
            Field::make('complex', 'sections', 'Разделы')
                ->set_layout('tabbed-vertical')
                ->setup_labels(['plural_name' => 'разделы', 'singular_name' => 'раздел'])

                ->add_fields('hero', 'Hero (первый экран)', [
                    Field::make('text', 'eyebrow', 'Надзаголовок (над основным текстом)'),
                    Field::make('textarea', 'title', 'Заголовок')->set_rows(4)->set_help_text($rich_help),
                    Field::make('textarea', 'text', 'Текст')->set_rows(4),
                    Field::make('text', 'primary_label', 'Основная кнопка: текст'),
                    Field::make('text', 'primary_href', 'Основная кнопка: ссылка'),
                    Field::make('text', 'secondary_label', 'Вторая кнопка: текст'),
                    Field::make('text', 'secondary_href', 'Вторая кнопка: ссылка'),
                ])

                ->add_fields('presentation', 'Видео и статистика', [
                    Field::make('textarea', 'title', 'Заголовок')->set_rows(3)->set_help_text($rich_help . ' ((слово)) — акцент только на мобильной версии.'),
                    Field::make('text', 'button_label', 'Кнопка: текст'),
                    Field::make('text', 'button_href', 'Кнопка: ссылка'),
                    Field::make('file', 'video_file', 'Видео: файл')
                        ->set_type(['video'])
                        ->set_value_type('url')
                        ->set_help_text('Загрузите mp4 в медиабиблиотеку (до 10–20 МБ, лучше сжать). Если указан файл, ссылка ниже не используется.'),
                    Field::make('text', 'video_url', 'Видео: ссылка')
                        ->set_help_text('YouTube, RuTube, VK Видео (ссылка на видео) или прямая ссылка на mp4.'),
                    Field::make('image', 'video_poster', 'Видео: обложка')
                        ->set_value_type('url')
                        ->set_help_text('Картинка до запуска видео. Размер примерно 1200×680.'),
                    Field::make('complex', 'stats', 'Карточки статистики')
                        ->set_layout('tabbed-horizontal')
                        ->add_fields([
                            Field::make('text', 'value', 'Число (например 60% или 24)'),
                            Field::make('textarea', 'prefix', 'Надпись над числом')->set_rows(2),
                            Field::make('textarea', 'text', 'Текст под числом')->set_rows(4),
                        ])
                        ->set_header_template('<%- value %>'),
                ])

                ->add_fields('mistakes', 'Проблемы (карточки)', [
                    Field::make('textarea', 'title', 'Заголовок')->set_rows(2)->set_help_text($rich_help),
                    Field::make('textarea', 'subtitle', 'Подзаголовок')->set_rows(4)->set_help_text('**жирный**'),
                    Field::make('complex', 'items', 'Карточки')
                        ->set_layout('tabbed-vertical')
                        ->add_fields([
                            Field::make('text', 'title', 'Заголовок')->set_help_text('**жирный**'),
                            Field::make('textarea', 'text', 'Текст')->set_rows(5)->set_help_text('**жирный**'),
                            Field::make('textarea', 'result', 'Результат (красная плашка)')->set_rows(3)->set_help_text('**жирный**'),
                        ])
                        ->set_header_template('<%- title %>'),
                    Field::make('textarea', 'footer', 'Текст под карточками')->set_rows(4)->set_help_text($rich_help),
                    Field::make('text', 'cta_label', 'Подпись над кнопкой'),
                    Field::make('text', 'cta_button_label', 'Кнопка: текст'),
                    Field::make('text', 'cta_button_href', 'Кнопка: ссылка'),
                ])

                ->add_fields('reason', 'Решение (три карточки)', [
                    Field::make('textarea', 'title', 'Заголовок')->set_rows(2)->set_help_text($rich_help),
                    Field::make('complex', 'cards', 'Карточки')
                        ->set_layout('tabbed-vertical')
                        ->add_fields([
                            Field::make('select', 'icon', 'Иконка')->add_options(['chat' => 'Чат', 'grid' => 'Сетка', 'bars' => 'Столбцы']),
                            Field::make('textarea', 'title', 'Заголовок')->set_rows(2),
                            Field::make('textarea', 'items', 'Пункты')->set_rows(6)->set_help_text('По одному на строку'),
                        ])
                        ->set_header_template('<%- title %>'),
                    Field::make('text', 'button_label', 'Кнопка: текст'),
                    Field::make('text', 'button_href', 'Кнопка: ссылка'),
                ])

                ->add_fields('connection', 'Подключение (шаги)', [
                    Field::make('text', 'title', 'Заголовок')->set_help_text($rich_help),
                    Field::make('textarea', 'subtitle', 'Подзаголовок')->set_rows(4),
                    Field::make('complex', 'steps', 'Шаги')
                        ->set_layout('tabbed-vertical')
                        ->add_fields([
                            Field::make('textarea', 'title', 'Заголовок')->set_rows(2)->set_help_text('Перенос строки — Enter (на мобильной версии игнорируется)'),
                            Field::make('textarea', 'text', 'Текст')->set_rows(5),
                        ])
                        ->set_header_template('<%- title %>'),
                    Field::make('text', 'badge_1', 'Плашка 1'),
                    Field::make('text', 'badge_2', 'Плашка 2 (залитая)'),
                    Field::make('text', 'button_label', 'Кнопка 1: текст'),
                    Field::make('text', 'button_href', 'Кнопка 1: ссылка'),
                    Field::make('text', 'button2_label', 'Кнопка 2: текст'),
                    Field::make('text', 'button2_href', 'Кнопка 2: ссылка'),
                ])

                ->add_fields('integrations', 'Интеграции и безопасность', [
                    Field::make('text', 'title', 'Заголовок')->set_help_text($rich_help),
                    Field::make('complex', 'cards', 'Карточки')
                        ->add_fields([
                            Field::make('select', 'icon', 'Иконка')->add_options([
                                'lock' => 'Замок', 'rings' => 'Кольца', 'head' => 'Человек', 'shield' => 'Защита', 'bars' => 'Столбцы',
                            ]),
                            Field::make('text', 'title', 'Заголовок'),
                            Field::make('text', 'text', 'Текст'),
                        ])
                        ->set_header_template('<%- title %>'),
                ])

                ->add_fields('details', 'Детали (колонки)', [
                    Field::make('text', 'title', 'Заголовок')->set_help_text($rich_help),
                    Field::make('complex', 'columns', 'Колонки')
                        ->set_layout('tabbed-vertical')
                        ->add_fields([
                            Field::make('select', 'icon', 'Иконка')->add_options(['chat' => 'Чат', 'grid' => 'Сетка', 'bars' => 'Столбцы', 'team' => 'Команда']),
                            Field::make('text', 'title', 'Название кнопки (вкладки)'),
                            Field::make('select', 'shot', 'Скриншот платформы')->add_options([
                                '' => 'Без скриншота',
                                'chats' => 'Диалоги (со стрелками)',
                                'ads' => 'Объявления (со стрелками)',
                                'analytics' => 'Сводка (со стрелками)',
                                'adcabinet' => 'Рекламный кабинет (со стрелками)',
                            ])->set_help_text('Стрелки и подписи нарисованы на самих картинках.'),
                            Field::make('textarea', 'items', 'Все возможности раздела')->set_rows(6)->set_help_text('По одному на строку. На телефоне показываются списком под скриншотом.'),
                            Field::make('textarea', 'extras', 'Не подписаны на скриншоте')->set_rows(3)->set_help_text('По одному на строку. Показываются строкой «А также:» под скриншотом на компьютере.'),
                        ])
                        ->set_header_template('<%- title %>'),
                    Field::make('text', 'button_label', 'Кнопка: текст'),
                    Field::make('text', 'button_href', 'Кнопка: ссылка'),
                ])

                ->add_fields('calculator', 'Калькулятор', [
                    Field::make('text', 'title', 'Заголовок')->set_help_text($rich_help),
                    Field::make('textarea', 'subtitle', 'Подзаголовок')->set_rows(3),
                ])

                ->add_fields('faq', 'Частые вопросы', [
                    Field::make('text', 'title', 'Заголовок')->set_help_text($rich_help),
                    Field::make('complex', 'items', 'Вопросы')
                        ->add_fields([
                            Field::make('text', 'question', 'Вопрос'),
                            Field::make('textarea', 'answer', 'Ответ')->set_rows(4),
                        ])
                        ->set_header_template('<%- question %>'),
                ])

                ->add_fields('cta', 'Форма заявки', [
                    Field::make('text', 'title_line', 'Заголовок: первая строка'),
                    Field::make('text', 'title_accent', 'Заголовок: вторая строка'),
                    Field::make('text', 'text', 'Текст под заголовком'),
                    Field::make('textarea', 'niches', 'Список ниш')->set_rows(6)->set_help_text('По одной на строку'),
                    Field::make('text', 'button_label', 'Кнопка: текст'),
                    Field::make('textarea', 'agreement', 'Текст согласия')->set_rows(3)->set_help_text('Слова в двойных квадратных скобках [[Политикой конфиденциальности]] становятся ссылкой на страницу политики.'),
                    Field::make('text', 'redirect', 'Куда перенаправить после успешной отправки'),
                ])

                ->add_fields('tariffs', 'Тарифы', [
                    Field::make('text', 'title', 'Заголовок'),
                    Field::make('text', 'price_note', 'Подпись под ценой'),
                    Field::make('text', 'badge', 'Метка «популярный»'),
                    Field::make('text', 'button_label', 'Кнопка: текст'),
                    Field::make('text', 'button_href', 'Кнопка: ссылка'),
                    Field::make('complex', 'items', 'Тарифы')
                        ->set_layout('tabbed-vertical')
                        ->add_fields([
                            Field::make('text', 'name', 'Название'),
                            Field::make('text', 'price', 'Цена'),
                            Field::make('checkbox', 'featured', 'Выделить тариф'),
                            Field::make('textarea', 'features', 'Характеристики')->set_rows(6)->set_help_text('По одной на строку: Название | значение'),
                        ])
                        ->set_header_template('<%- name %>'),
                ]),
        ]);
});

add_action('carbon_fields_register_fields', function () {
    Container::make('post_meta', 'QR-страница')
        ->where('post_template', '=', 'template-qr.php')
        ->add_fields([
            Field::make('text', 'badge', 'Плашка вверху')->set_default_value('Платформа для Авито'),
            Field::make('text', 'title_line', 'Заголовок: первая строка')->set_default_value('Не теряйте клиентов'),
            Field::make('text', 'title_small', 'Заголовок: малое слово')->set_default_value('на'),
            Field::make('text', 'title_accent', 'Заголовок: вторая строка')->set_default_value('Авито'),
            Field::make('text', 'subtitle', 'Подзаголовок')->set_default_value('ИИ-сотрудник в чате + аналитика'),
            Field::make('text', 'button_label', 'Текст кнопок')->set_default_value('Получить демо-доступ'),
            Field::make('textarea', 'features', 'Три преимущества')->set_rows(3)->set_help_text('По одному на строку, ровно три.'),
            Field::make('text', 'how_title', 'Заголовок над видео')->set_help_text('Разметка: {{акцент}}.'),
            Field::make('file', 'video_file', 'Видео: файл')->set_type(['video'])->set_value_type('url'),
            Field::make('text', 'video_url', 'Видео: ссылка')->set_help_text('YouTube, RuTube, VK Видео или mp4. Если загружен файл, ссылка не используется.'),
            Field::make('image', 'video_poster', 'Видео: обложка')->set_value_type('url'),
            Field::make('text', 'form_title', 'Форма: заголовок')->set_default_value('Демо-доступ бесплатно'),
            Field::make('text', 'form_subtitle', 'Форма: подзаголовок')->set_default_value('Перезвоним в течение дня и все покажем'),
            Field::make('textarea', 'niches', 'Список ниш')->set_rows(5)->set_help_text('По одной на строку.'),
            Field::make('text', 'redirect', 'Куда перенаправить после отправки'),
            Field::make('textarea', 'legal', 'Текст согласия')->set_rows(3)->set_help_text('Слова в [[двойных скобках]] становятся ссылкой на политику.'),
            Field::make('text', 'more', 'Нижняя строка')->set_help_text('Слова в [[двойных скобках]] становятся ссылкой на главную.'),
        ]);
});
