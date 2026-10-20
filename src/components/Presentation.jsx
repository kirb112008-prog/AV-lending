import React from 'react'

import '../styles/Presentation.css'

const HERO_STATS = [
  { value: '60%', prefix: 'До', text: 'обращений\nтеряются, если\nотвечать дольше\n5–10 минут' },
  { value: '24', prefix: 'ИИ отвечает\nза 5 секунд', text: 'часа в сутки' },
  { value: '+30%', prefix: 'До', text: 'к конверсии\nв сделку из тех же\nобращений' },
  { value: '100+', prefix: 'Более', text: 'объявлений в одном\nокне' },
]

export default function Presentation() {
  return (
    <section id="preview" className="presentation section">
      <div className="container presentation__container">
        <div className="presentation__top">
          <div className="presentation__video">
            <div className="presentation__video-inner" />
          </div>

          <div className="presentation__aside">
            <h2 className="presentation__title">
              Собрали все{' '}
              <br className="presentation__br" />
              преимущества{' '}
              <br className="presentation__br" />
              платформы в <span className="presentation__accent">одном</span>{' '}
              <br className="presentation__br" />
              <span className="presentation__accent-mobile">видео</span>
            </h2>

            <div className="presentation__cta">
              <a href="#contact" className="presentation__button">
                Получить демо
              </a>
            </div>
          </div>
        </div>

        <ul className="presentation__stats">
          {HERO_STATS.map(({ prefix, value, text }) => (
            <li key={value} className="stat-card">
              <span className="stat-card__prefix">{prefix}</span>
              <span className="stat-card__value">
                {value.endsWith('%') ? value.slice(0, -1) : value}
                {value.endsWith('%') && <span className="stat-card__unit">%</span>}
              </span>
              <span className="stat-card__text">{text}</span>
            </li>
          ))}
        </ul>
      </div>
    </section>
  )
}
