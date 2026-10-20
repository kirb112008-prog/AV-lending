import React, { useState } from 'react'

import { calculateLosses, formatNumber } from '../utils/calculateLosses'
import '../styles/Calculator.css'

const INPUT_FIELDS = [
  { name: 'requestsPerMonth', label: 'Количество обращений в месяц' },
  { name: 'averageCheck', label: 'Средний чек, ₽' },
  { name: 'responseMinutes', label: 'Текущая скорость ответа, мин' },
  { name: 'conversionPercent', label: 'Конверсия в сделку, %' },
]

const DEFAULT_VALUES = {
  requestsPerMonth: 300,
  averageCheck: 25000,
  responseMinutes: 15,
  conversionPercent: 10,
}

export default function Calculator() {
  const [values, setValues] = useState(DEFAULT_VALUES)

  const handleChange = (event) => {
    const { name, value } = event.target
    if (!isNaN(value)) setValues((previous) => ({ ...previous, [name]: Math.max(0, Number(value)) }))
  }

  const { lostRequests, lostRevenue, recoverableRevenue } = calculateLosses(values)

  return (
    <section id="calculator" className="calculator section">
      <div className="container">
        <div className="calculator__panel">
          <h2 className="calculator__title">
            Сколько клиентов <span className="calculator__accent">теряете Вы</span>
          </h2>

          <p className="calculator__subtitle">
            Введите данные — мы покажем, сколько выручки уходит и сколько может вернуть платформа
          </p>

          <div className="calculator__body">
            <form className="calculator__form" onSubmit={(event) => event.preventDefault()}>
              {INPUT_FIELDS.map(({ name, label }) => (
                <label key={name} className="calculator__field">
                  <span>{label}</span>
                  <input
                    type="text"
                    inputMode="numeric"
                    name={name}
                    value={values[name]}
                    onChange={handleChange}
                  />
                </label>
              ))}
            </form>

            <div className="calculator__result">
              <span className="calculator__result-title">Ваш результат</span>

              <div>
                <strong>{formatNumber(lostRequests)}</strong>
                <span>обращений теряется в месяц</span>
              </div>
              <div>
                <strong>{formatNumber(lostRevenue)} ₽</strong>
                <span>недополученная выручка в месяц</span>
              </div>
              <div>
                <strong>до {formatNumber(recoverableRevenue)} ₽</strong>
                <span>может вернуть платформа</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
