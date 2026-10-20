import React from 'react'
import { Link } from 'react-router'

import useLeadForm from '../utils/useLeadForm'
import '../styles/CTAForm.css'

const NICHES = ['Недвижимость', 'Авто', 'Услуги', 'Товары', 'Другое']

const BUTTON_TEXT = {
  idle: 'Отправить и получить демо',
  posting: 'Одну секунду...',
  success: 'Заявка успешно отправлена!',
  error: 'Что-то пошло не так, перезагрузите страницу и попробуйте снова',
}

const FORM_FIELDS = [
  { name: 'email', type: 'email', placeholder: 'Email' },
  { name: 'phone', type: 'tel', placeholder: 'Телефон' },
  { name: 'niche', type: 'select', placeholder: 'Рабочая ниша' },
  { name: 'name', type: 'text', placeholder: 'Компания' },
]

export default function CTAForm() {
  const { status, errors, handleInput, handleSubmit } = useLeadForm({ nameLabel: 'название компании' })

  return (
    <section id="contact" className="cta section">
      <div className="container cta__container">
        <div className="cta__panel">
          <div className="cta__intro">
            <h2 className="cta__title">
              Перестаньте терять
              <span className="cta__title-accent">клиентов на Авито</span>
            </h2>
            <p className="cta__text">До пилота осталось совсем чуть-чуть...</p>
          </div>

          <form className="cta__form" onSubmit={handleSubmit} noValidate>
            <div className="cta__fields">
              {FORM_FIELDS.map(({ name, type, placeholder }) => (
                <div key={name} className="cta__field">
                  {type === 'select' ? (
                    <select
                      name={name}
                      defaultValue=""
                      required
                      aria-invalid={errors[name] ? 'true' : undefined}
                      onChange={handleInput}
                    >
                      <option value="" disabled hidden>
                        {placeholder}
                      </option>
                      {NICHES.map((niche) => (
                        <option key={niche} value={niche}>
                          {niche}
                        </option>
                      ))}
                    </select>
                  ) : (
                    <input
                      name={name}
                      type={type}
                      placeholder={placeholder}
                      required
                      inputMode={type === 'tel' ? 'tel' : undefined}
                      autoComplete={name === 'email' ? 'email' : type === 'tel' ? 'tel' : 'organization'}
                      aria-invalid={errors[name] ? 'true' : undefined}
                      onInput={handleInput}
                    />
                  )}
                  {errors[name] && (
                    <p className="cta__error" role="alert">
                      {errors[name]}
                    </p>
                  )}
                </div>
              ))}
            </div>

            <button type="submit" className="cta__button" disabled={status !== 'idle'}>
              {BUTTON_TEXT[status]}
            </button>

            <p className="cta__agreement">
              {'Нажимая «Отправить и получить демо» выше, вы соглашаетесь с\n'}
              <Link to="/privacy" className="cta__agreement-link">
                Политикой конфиденциальности
              </Link>
            </p>
          </form>
        </div>
      </div>
    </section>
  )
}
