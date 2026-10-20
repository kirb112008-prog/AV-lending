import React from 'react'

import Button from './Button'
import dialogsImg from '../images/hero-image0.png'
import cabinetImg from '../images/hero-image1.png'
import summaryImg from '../images/hero-image2.png'
import '../styles/Hero.css'

export default function Hero() {
  return (
    <section id="top" className="hero section">
      <div className="container hero__inner">
        <div className="hero__content">
          <p className="hero__eyebrow">Для компаний и предпринимателей, которые продают на Авито</p>

          <h1 className="hero__title">
            Не теряйте клиентов на Авито: <br className="hero__br" /> ИИ-сотрудник, за{' '}
            <span className="hero__accent">5 секунд</span> отвечает <br className="hero__br" /> в чате, пока вы
            управляете <br className="hero__br" /> продажами
          </h1>

          <p className="hero__text">
            Помогаем превращать обращения с Авито в подготовленные заявки,
            освобождать время менеджеров и видеть, где требуется ваше решение.
          </p>

          <div className="hero__buttons">
            <Button href="#contact">Получить демо</Button>
            <Button href="#preview" variant="secondary">Смотреть превью</Button>
          </div>
        </div>

        <div className="hero__preview" aria-label="Превью платформы">
          <div className="hero__shot hero__shot--dialogs">
            <img src={dialogsImg} alt="" />
          </div>
          <div className="hero__shot hero__shot--cabinet">
            <img src={cabinetImg} alt="" />
          </div>
          <div className="hero__shot hero__shot--summary">
            <img src={summaryImg} alt="" />
          </div>
        </div>
      </div>
    </section>
  )
}
