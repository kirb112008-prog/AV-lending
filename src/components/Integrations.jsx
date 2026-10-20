import React from 'react'

import '../styles/Integrations.css'

const INTEGRATIONS = [
  { title: 'Официальный API Авито', text: 'Работаем без нарушения правил платформы' },
  { title: 'CRM-интеграции', text: 'Интеграции с amoCRM и Bitrix24' },
  { title: 'Уведомления', text: 'В Max и Telegram' },
  { title: 'Безопасность данных', text: 'Защита данных и роли доступа' },
  { title: 'Правила платформы', text: 'Полное соответствие требованиям Авито' },
]

const ICONS = [
  <>
    <i className="ic-lock" />
  </>,
  <>
    <i className="ic-ring ic-ring--a" />
    <i className="ic-ring ic-ring--b" />
  </>,
  <>
    <i className="ic-head" />
    <i className="ic-neck" />
  </>,
  <>
    <i className="ic-shackle" />
    <i className="ic-body" />
  </>,
  <>
    <i className="ic-bar ic-bar--1" />
    <i className="ic-bar ic-bar--2" />
    <i className="ic-bar ic-bar--3" />
  </>,
]

export default function Integrations() {
  return (
    <section id="integrations" className="integrations section">
      <div className="container">
        <div className="integrations__panel">
          <h2 className="integrations__title">
            Интеграции и <span className="integrations__accent">безопасность</span>
          </h2>

          <ul className="integrations__list">
            {INTEGRATIONS.map(({ title, text }, index) => (
              <li key={title} className="integration">
                <span className="integration__icon" aria-hidden="true">
                  {ICONS[index]}
                </span>
                <h3 className="integration__title">{title}</h3>
                <p className="integration__text">{text}</p>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </section>
  )
}
