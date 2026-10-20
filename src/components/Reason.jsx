import React from 'react'

import '../styles/Reason.css'

const SOLUTIONS = [
  {
    title: 'ИИ-сотрудник\nАвито',
    items: [
      'Отвечает мгновенно, в том числе вечером и в выходные',
      'Уточняет детали: размеры, материал, количество, фото',
      'К приходу менеджера заявка уже собрана',
      'Сложные вопросы передаёт человеку',
      'Говорит в вашем тоне, по вашим скриптам',
    ],
  },
  {
    title: 'Удобное управление\nкабинетом и объявлениями',
    items: [
      'Все объявления в одном окне',
      'Цены, фото и условия легко держать актуальными',
      'Меньше времени на ручную поддержку',
      'Управлять удобнее, чем в самом Авито',
    ],
  },
  {
    title: 'Понятный контроль',
    items: [
      'Весь путь от объявления до заказа — в одном месте',
      'Видно, какие обращения остались без ответа',
      'Понятно, какие объявления работают, а какие тратят бюджет',
      'Ясно, что менять уже сегодня',
    ],
  },
]

const ICONS = [
  <span key="chat" className="reason-card__icon-chat">
    <i />
    <i />
    <i />
  </span>,
  <span key="grid" className="reason-card__icon-grid">
    <i />
    <i />
    <i />
    <i />
  </span>,
  <span key="bars" className="reason-card__icon-bars">
    <i />
    <i />
    <i />
  </span>,
]

export default function Reason() {
  return (
    <section id="solution" className="reason section">
      <div className="container reason__container">
        <h2 className="reason__title">
          Одно подключение — и <span className="reason__accent">Авито</span> работает на Вас
        </h2>

        <ul className="reason__cards">
          {SOLUTIONS.map(({ title, items }, index) => (
            <li key={title} className="reason-card">
              <div className="reason-card__icon">{ICONS[index]}</div>
              <h3 className="reason-card__title">{title}</h3>
              <ul className="reason-card__items">
                {items.map((item) => (
                  <li key={item}>{item}</li>
                ))}
              </ul>
            </li>
          ))}
        </ul>

        <a href="#contact" className="reason__button">
          Получить демо
        </a>
      </div>
    </section>
  )
}
