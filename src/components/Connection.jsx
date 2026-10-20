import React from 'react'

import '../styles/Connection.css'

const CONNECTION_STEPS = [
  {
    title: 'Подключаемся к\nвашему Авито',
    text: 'Через официальный API.\nБезопасно, без нарушения\nправил платформы. Чаты и\nобъявления\nсинхронизируются.',
  },
  {
    title: 'Настраиваем ИИ-\nсотрудника',
    text: 'Настройку берём на себя.\nОт вас — скрипты, тон и\nчастые вопросы, на них\nмы обучаем ИИ.',
  },
  {
    title: 'Запускаем и\nнаблюдаем вместе',
    text: 'ИИ отвечает клиентам, а\nкогда нужен человек — диалог\nуходит менеджеру со всей\nперепиской.',
  },
  {
    title: 'Видим, где нужно\nваше решение',
    text: 'Вам не нужно проверять\nчаты вручную: видно, что\nобработано, а что ждёт\nменеджера или вас.',
  },
]

const lines = (text) =>
  text.split('\n').map((line, index, all) => (
    <React.Fragment key={index}>
      {line}
      {index < all.length - 1 && (
        <>
          {!line.endsWith('-') && ' '}
          <br className="step__br" />
        </>
      )}
    </React.Fragment>
  ))

export default function Connection() {
  return (
    <section id="connection" className="connection section">
      <div className="container connection__container">
        <h2 className="connection__title">
          Подключение за 1 день. <span className="connection__accent">Работает сразу</span>
        </h2>

        <p className="connection__subtitle">
          {'Вы не меняете Авито и не перестраиваете команду. Просто подключаете наш сервис — мы настроим его\nпод ваши скрипты и условия, а человек подключается там, где без него не обойтись.'}
        </p>

        <div className="connection__steps-wrap">
          <span className="connection__line" aria-hidden="true" />
          <ol className="connection__steps">
            {CONNECTION_STEPS.map(({ title, text }, index) => (
              <li key={title} className="step">
                <span className="step__number">{index + 1}</span>
                <h3 className="step__title">{lines(title)}</h3>
                <p className="step__text">{lines(text)}</p>
              </li>
            ))}
          </ol>
        </div>

        <div className="connection__badges">
          <span className="connection__badge">Среднее время подключения — 1 рабочий день</span>
          <span className="connection__badge connection__badge--filled">
            Первые результаты — в первую неделю
          </span>
        </div>

        <div className="connection__buttons">
          <a href="#contact" className="connection__button">
            Получить демо
          </a>
          <a href="#preview" className="connection__button connection__button--light">
            Смотреть превью
          </a>
        </div>
      </div>
    </section>
  )
}
