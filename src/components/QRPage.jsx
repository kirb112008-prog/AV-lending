import React, { useEffect } from 'react'
import { Link } from 'react-router'

import logoMark from '../images/logo-mark.svg'
import useLeadForm from '../utils/useLeadForm'
import '../styles/QRPage.css'

const BUTTON_TEXT = {
  idle: 'Получить демо-доступ',
  posting: 'Отправляем…',
  success: 'Заявка успешно отправлена!',
  error: 'Ошибка отправки',
}

const NICHES = ['Недвижимость', 'Авто', 'Услуги', 'Товары', 'Другое']

const IconClock = () => (
  <svg className="qr-feature__icon" viewBox="33 345 44 44" fill="none" aria-hidden="true">
    <path
      d="M65 345H45C38.3726 345 33 350.373 33 357V377C33 383.627 38.3726 389 45 389H65C71.6274 389 77 383.627 77 377V357C77 350.373 71.6274 345 65 345Z"
      fill="#2E4D89"
    />
    <circle cx="55" cy="367" r="10" stroke="#fff" strokeWidth="2.2" />
    <path d="M55 361V367L59 369.5" stroke="#fff" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round" />
  </svg>
)

const IconGrid = () => (
  <svg className="qr-feature__icon" viewBox="33 433 44 44" fill="none" aria-hidden="true">
    <path
      d="M65 433H45C38.3726 433 33 438.373 33 445V465C33 471.627 38.3726 477 45 477H65C71.6274 477 77 471.627 77 465V445C77 438.373 71.6274 433 65 433Z"
      fill="#2E4D89"
    />
    <rect x="45" y="445" width="8.5" height="8.5" rx="2.5" fill="#fff" />
    <rect x="56.5" y="445" width="8.5" height="8.5" rx="2.5" fill="#fff" />
    <rect x="45" y="456.5" width="8.5" height="8.5" rx="2.5" fill="#fff" />
    <rect x="56.5" y="456.5" width="8.5" height="8.5" rx="2.5" fill="#fff" />
  </svg>
)

const IconBars = () => (
  <svg className="qr-feature__icon" viewBox="33 521 44 44" fill="none" aria-hidden="true">
    <path
      d="M65 521H45C38.3726 521 33 526.373 33 533V553C33 559.627 38.3726 565 45 565H65C71.6274 565 77 559.627 77 553V533C77 526.373 71.6274 521 65 521Z"
      fill="#2E4D89"
    />
    <rect x="45" y="544" width="5" height="9" rx="2.5" fill="#fff" />
    <rect x="52.5" y="539" width="5" height="14" rx="2.5" fill="#fff" />
    <rect x="60" y="533" width="5" height="20" rx="2.5" fill="#fff" />
  </svg>
)

export default function QRPage() {
  const { status, errors, handleInput, handleSubmit } = useLeadForm()
  const locked = status !== 'idle'

  useEffect(() => {
    window.scrollTo(0, 0)
    document.title = 'САММИ — демо-доступ'
  }, [])

  const focusForm = () => document.getElementById('qr-name')?.focus()

  return (
    <main className="qr">
      <div className="qr-page">
        <section className="qr-hero">
          <p className="qr-hero__logo">
            <img src={logoMark} alt="" width="26" height="26" />
            САММИ
          </p>
          <div className="qr-hero__badge">Платформа для Авито</div>
          <h1 className="qr-hero__title">
            Не теряйте клиентов
            <br />
            <small>на</small> Авито
          </h1>
          <p className="qr-hero__subtitle">ИИ-сотрудник в чате + аналитика</p>
          <button type="button" className="qr-hero__cta" onClick={focusForm}>
            Получить демо-доступ
          </button>
        </section>

        <div className="qr-feature" style={{ top: 328 }}>
          <IconClock />
          <p className="qr-feature__text">ИИ отвечает клиентам 24/7</p>
        </div>
        <div className="qr-feature" style={{ top: 416 }}>
          <IconGrid />
          <p className="qr-feature__text">Все объявления в одном окне</p>
        </div>
        <div className="qr-feature" style={{ top: 504 }}>
          <IconBars />
          <p className="qr-feature__text">Наглядная аналитика</p>
        </div>

        <h2 className="qr-how">
          Как это работает — за <span>2 минуты</span>
        </h2>

        <div className="qr-video">Video</div>

        <form className="qr-lead" onSubmit={handleSubmit} noValidate>
          <h2 className="qr-lead__title">Демо-доступ бесплатно</h2>
          <p className="qr-lead__subtitle">Перезвоним в течение дня и все покажем</p>

          {errors.name && (
            <p className="qr-field__error" style={{ top: 94.5 }} role="alert">
              {errors.name}
            </p>
          )}
          <label className="qr-field__label" style={{ top: 94.5 }} htmlFor="qr-name">
            Имя
          </label>
          <input
            id="qr-name"
            className="qr-field__control"
            style={{ top: 119 }}
            type="text"
            autoComplete="name"
            placeholder="Как к Вам обращаться"
            name="name"
            required
            disabled={locked}
            aria-invalid={errors.name ? 'true' : undefined}
            onInput={handleInput}
          />

          {errors.email && (
            <p className="qr-field__error" style={{ top: 190 }} role="alert">
              {errors.email}
            </p>
          )}
          <label className="qr-field__label" style={{ top: 190 }} htmlFor="qr-email">
            Email
          </label>
          <input
            id="qr-email"
            className="qr-field__control"
            style={{ top: 215 }}
            type="email"
            autoComplete="email"
            inputMode="email"
            placeholder="example@mail.ru"
            name="email"
            required
            disabled={locked}
            aria-invalid={errors.email ? 'true' : undefined}
            onInput={handleInput}
          />

          {errors.phone && (
            <p className="qr-field__error" style={{ top: 286 }} role="alert">
              {errors.phone}
            </p>
          )}
          <label className="qr-field__label" style={{ top: 286 }} htmlFor="qr-phone">
            Телефон
          </label>
          <input
            id="qr-phone"
            className="qr-field__control"
            style={{ top: 311 }}
            type="tel"
            autoComplete="tel"
            inputMode="tel"
            placeholder="+7 (___) ___-__-__"
            name="phone"
            required
            disabled={locked}
            aria-invalid={errors.phone ? 'true' : undefined}
            onInput={handleInput}
          />

          {errors.niche && (
            <p className="qr-field__error" style={{ top: 381.5 }} role="alert">
              {errors.niche}
            </p>
          )}
          <label className="qr-field__label" style={{ top: 381.5 }} htmlFor="qr-niche">
            Ниша
          </label>
          <div className="qr-field__select-wrap" style={{ top: 406.5 }}>
            <select
              id="qr-niche"
              className="qr-field__control qr-field__select"
              name="niche"
              defaultValue=""
              required
              disabled={locked}
              aria-invalid={errors.niche ? 'true' : undefined}
              onChange={handleInput}
            >
              <option value="" disabled>
                Выберите нишу
              </option>
              {NICHES.map((niche) => (
                <option key={niche} value={niche}>
                  {niche}
                </option>
              ))}
            </select>
            <svg className="qr-field__chevron" viewBox="0 0 12 7" fill="none" aria-hidden="true">
              <path d="M1 1L6 6L11 1" stroke="#0B1530" strokeWidth="2" />
            </svg>
          </div>

          <button type="submit" className="qr-lead__submit" style={{ top: 488 }} disabled={locked}>
            {BUTTON_TEXT[status]}
          </button>

          {status === 'error' && (
            <p className="qr-lead__status qr-lead__status--error">Не удалось отправить. Попробуйте ещё раз.</p>
          )}

          <p className="qr-lead__legal">
            Нажимая «Получить демо-доступ», вы соглашаетесь
            <br />
            на обработку персональных данных и принимаете
            <br />
            условия <Link to="/privacy">Политики конфиденциальности</Link>
          </p>
        </form>

        <p className="qr-more">
          Больше о продукте — <Link to="/">на основном сайте</Link>
        </p>
      </div>
    </main>
  )
}
