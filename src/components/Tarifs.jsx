import React from 'react'

import '../styles/Tarifs.css'

const TARIFFS = [
  { name: 'Базовый', price: '4 990', features: ['Объявления', 'Менеджеры', 'ИИ-сотрудник', 'Аналитика', 'Поддержка'] },
  { name: 'Расширенный', price: '24 990', featured: true, features: ['Объявления', 'Менеджеры', 'ИИ-сотрудник', 'Аналитика', 'Поддержка'] },
  { name: 'Профи', price: '39 990', features: ['Объявления', 'Менеджеры', 'ИИ-сотрудник', 'Аналитика', 'Поддержка'] },
]

const FEATURE_VALUE = '111'

export default function Tarifs() {
  return (
    <section id="tariffs" className="tariffs section">
      <div className="container">
        <h2 className="tariffs__title">Тарифы</h2>

        <ul className="tariffs__list">
          {TARIFFS.map(({ name, price, features, featured }) => (
            <li key={name} className={`tariff${featured ? ' tariff--featured' : ''}`}>
              {featured && <span className="tariff__badge">Популярный</span>}
              <h3 className="tariff__name">{name}</h3>

              <p className="tariff__price">
                <strong>{price}</strong>
                <span>рублей в месяц</span>
              </p>

              <ul className="tariff__features">
                {features.map((feature) => (
                  <li key={feature}>
                    <span className="tariff__label">{feature}</span>
                    <span className="tariff__value">{FEATURE_VALUE}</span>
                  </li>
                ))}
              </ul>

              <a href="#contact" className="tariff__button">
                Выбрать тариф
              </a>
            </li>
          ))}
        </ul>
      </div>
    </section>
  )
}
