import React, { useState } from 'react'

import '../styles/FAQ.css'

const FAQ_ITEMS = [
  { question: 'Авито запрещает автоматизацию?', answer: 'Нет, работаем через официальный API.' },
  { question: 'ИИ будет отвечать глупо?', answer: 'ИИ обучается на ваших скриптах и частых вопросах, а сложные диалоги передаёт менеджеру.' },
  { question: 'Сложно подключить?', answer: 'Нет. Настройку берём на себя, от вас нужны скрипты и условия. Среднее время подключения — 1 рабочий день.' },
  { question: 'Когда будут первые результаты?', answer: 'Первые результаты — в первую неделю после подключения.' },
  { question: 'Есть ли поддержка?', answer: 'Да, поддержка входит в каждый тариф.' },
]

export default function FAQ() {
  const [openIndex, setOpenIndex] = useState(0)

  const toggleItem = (index) => {
    setOpenIndex((current) => (current === index ? null : index))
  }

  return (
    <section id="faq" className="faq section">
      <div className="container faq__container">
        <h2 className="faq__title">
          Частые <span className="faq__accent">вопросы</span>
        </h2>

        <ul className="faq__list">
          {FAQ_ITEMS.map(({ question, answer }, index) => {
            const isOpen = openIndex === index

            return (
              <li key={question} className={`faq__item${isOpen ? ' faq__item--open' : ''}`}>
                <button
                  type="button"
                  className="faq__question"
                  aria-expanded={isOpen}
                  onClick={() => toggleItem(index)}
                >
                  <span>{question}</span>
                  <span className="faq__icon" aria-hidden="true" />
                </button>

                <div className="faq__panel" aria-hidden={!isOpen}>
                  <div className="faq__panel-inner">
                    <p className="faq__answer">{answer}</p>
                  </div>
                </div>
              </li>
            )
          })}
        </ul>
      </div>
    </section>
  )
}
