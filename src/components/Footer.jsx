import React from 'react'
import { useLocation } from 'react-router'

import logoMark from '../images/logo-mark.svg'
import '../styles/Footer.css'

const FOOTER_LINKS = [
  [
    { label: 'Превью', href: '#preview' },
    { label: 'Проблема', href: '#problem' },
    { label: 'Решение', href: '#solution' },
    { label: 'Подключение', href: '#connection' },
  ],
  [
    { label: 'Интеграции', href: '#integrations' },
    { label: 'Детали', href: '#details' },
    { label: 'Калькулятор', href: '#calculator' },
    { label: 'Оставьте данные', href: '#contact' },
    { label: 'Тарифы', href: '#tariffs' },
  ],
]

const CONTACTS = [
  { label: 'email', value: 'kirb112008@gmail.com' },
  { label: 'телефон', value: '+7 900-555-35-35' },
  { label: 'Telegram', value: '@Frozzts' },
  { label: 'VK', value: '@Frozzts' },
]

export default function Footer() {
  const { pathname } = useLocation()
  const base = pathname === '/' ? '' : '/'

  return (
    <footer className="footer">
      <div className="container footer__inner">
        <div className="footer__links">
          {FOOTER_LINKS.map((column, index) => (
            <ul key={index} className="footer__column">
              {column.map(({ label, href }) => (
                <li key={href}>
                  <a href={`${base}${href}`}>{label}</a>
                </li>
              ))}
            </ul>
          ))}
        </div>

        <span className="footer__logo">
          <img src={logoMark} alt="" className="footer__logo-mark" width="36" height="36" />
          САММИ
        </span>

        <div className="footer__contacts">
          <h3>Сотрудничество</h3>
          <ul>
            {CONTACTS.map(({ label, value }) => (
              <li key={label}>{label}: {value}</li>
            ))}
          </ul>
        </div>
      </div>
    </footer>
  )
}
