import React, { useEffect, useState } from 'react'
import { useLocation } from 'react-router'

import Button from './Button'
import logoMark from '../images/logo-mark.svg'
import '../styles/Header.css'

const NAV_LINKS = [
  { label: 'Превью', href: '#preview' },
  { label: 'Проблема', href: '#problem' },
  { label: 'Решение', href: '#solution' },
  { label: 'Подключение', href: '#connection' },
  { label: 'Детали', href: '#details' },
  { label: 'Частые вопросы', href: '#faq' },
  { label: 'Тарифы', href: '#tariffs' },
]

export default function Header() {
  const { pathname } = useLocation()
  const base = pathname === '/' ? '' : '/'
  const [activeId, setActiveId] = useState(null)
  const [isMenuOpen, setIsMenuOpen] = useState(false)

  useEffect(() => {
    const sections = NAV_LINKS.map(({ href }) => document.querySelector(href)).filter(Boolean)

    if (!sections.length) {
      return undefined
    }

    let frame = 0

    const update = () => {
      frame = 0
      const line = window.innerHeight * 0.4
      const current = sections.find((section) => {
        const { top, bottom } = section.getBoundingClientRect()
        return top <= line && bottom > line
      })
      setActiveId(current ? current.id : null)
    }

    const onScroll = () => {
      if (!frame) {
        frame = window.requestAnimationFrame(update)
      }
    }

    update()
    window.addEventListener('scroll', onScroll, { passive: true })
    window.addEventListener('resize', onScroll)

    return () => {
      window.removeEventListener('scroll', onScroll)
      window.removeEventListener('resize', onScroll)
      if (frame) window.cancelAnimationFrame(frame)
    }
  }, [pathname])

  const closeMenu = () => setIsMenuOpen(false)

  return (
    <header>
      <div className='header'>
        <div className="container header__inner">
          <a href={`${base}#top`} className="header__logo" onClick={closeMenu}>
            <img src={logoMark} alt="" className="header__logo-mark" width="30" height="30" />
            <span>САММИ</span>
          </a>

          <nav className={`header__nav${isMenuOpen ? ' header__nav--open' : ''}`}>
            {NAV_LINKS.map(({ label, href }) => {
              const isActive = activeId === href.slice(1)

              return (
                <a
                  key={href}
                  href={`${base}${href}`}
                  className={`header__link${isActive ? ' header__link--active' : ''}`}
                  aria-current={isActive ? 'true' : undefined}
                  onClick={closeMenu}
                >
                  {label}
                </a>
              )
            })}
          </nav>

          <Button href={`${base}#contact`} onClick={closeMenu}>На платформу →</Button>

          <button
            type="button"
            className={`header__burger${isMenuOpen ? ' header__burger--open' : ''}`}
            aria-label={isMenuOpen ? 'Закрыть меню' : 'Открыть меню'}
            aria-expanded={isMenuOpen}
            onClick={() => setIsMenuOpen((open) => !open)}
          >
            <span />
            <span />
            <span />
          </button>
        </div>
      </div>
    </header>
  )
}
