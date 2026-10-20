import React, { useState } from 'react'

import chatsImg from '../images/platform-chats.jpg'
import adsImg from '../images/platform-ads.jpg'
import analyticsImg from '../images/platform-analytics.jpg'
import adCabinetImg from '../images/platform-adcabinet.jpg'
import '../styles/Details.css'

// items — все возможности раздела (на телефоне показываются списком);
// extras — те, что не подписаны стрелками на скриншоте (на десктопе показываются под ним).
const DETAILS = [
  {
    id: 'chats',
    title: 'Для чатов',
    items: ['Автоответы 24/7', 'Передача менеджеру', 'История диалогов', 'Заявка из диалога', 'Шаблоны ответов', 'Ни одно сообщение не теряется'],
    extras: ['Шаблоны ответов', 'Ни одно сообщение не теряется'],
    image: chatsImg,
  },
  {
    id: 'ads',
    title: 'Для объявлений',
    items: ['Массовое редактирование', 'Статусы и фильтры', 'Автоподнятие и расписание', 'Копирование объявлений'],
    extras: ['Автоподнятие и расписание', 'Копирование объявлений'],
    image: adsImg,
  },
  {
    id: 'analytics',
    title: 'Для аналитики',
    items: ['Воронка и конверсия', 'Лидеры по контактам', 'Антирейтинг объявлений', 'Быстрый ответ', 'Контроль неотвеченных чатов', 'Подсказки по падению контактов', 'Источники обращений'],
    extras: ['Быстрый ответ', 'Контроль неотвеченных чатов', 'Подсказки по падению контактов', 'Источники обращений'],
    image: analyticsImg,
  },
  {
    id: 'promo',
    title: 'Для рекламы',
    items: ['Расходы и баланс аванса', 'Стоимость контакта', 'Куда уходят деньги', 'Подсказки по ставкам'],
    extras: [],
    image: adCabinetImg,
  },
]

export default function Details() {
  const [activeId, setActiveId] = useState(DETAILS[0].id)

  return (
    <section id="details" className="details section">
      <div className="container details__container">
        <h2 className="details__title">
          Что <span className="details__accent">внутри платформы</span>
        </h2>

        <div className="details__tabs" role="tablist">
          {DETAILS.map(({ id, title }) => (
            <button
              key={id}
              type="button"
              role="tab"
              id={`details-tab-${id}`}
              aria-selected={activeId === id}
              aria-controls={`details-panel-${id}`}
              className={`details__tab${activeId === id ? ' details__tab--active' : ''}`}
              onClick={() => setActiveId(id)}
            >
              {title}
            </button>
          ))}
        </div>

        {DETAILS.map(({ id, title, items, extras = [], image }) => (
          <div
            key={id}
            role="tabpanel"
            id={`details-panel-${id}`}
            aria-labelledby={`details-tab-${id}`}
            hidden={activeId !== id}
            className={`details__panel${image ? '' : ' details__panel--text'}`}
          >
            {image && (
              <div className="details__shot">
                <img src={image} alt={`Платформа: ${title.toLowerCase()}`} loading="lazy" decoding="async" />
              </div>
            )}

            <ul className="details__items">
              {items.map((item) => (
                <li key={item}>{item}</li>
              ))}
            </ul>

            {image && extras.length > 0 && (
              <p className="details__extras">
                <span>А также:</span> {extras.join(' · ')}
              </p>
            )}
          </div>
        ))}

        <a href="#contact" className="details__cta">
          Получить демо
        </a>
      </div>
    </section>
  )
}
