import React from 'react'

import '../styles/Mistakes.css'

const MISTAKES = [
  {
    title: '**Медленный ответ** = **Потерянный клиент**',
    text: 'Тот, кто ответил **первым и по делу** — тот и получает сделку. Даже 10–15 минут ожидания снижают шансы на ответ. А обращения, которые приходят **вечером или в выходные**, ждут до утра — и клиент **просто уходит.**',
    result: 'Вы теряете не диалог, а\n**недополученную выручку.**',
  },
  {
    title: 'Менеджеры **отвечают на одно и то же**',
    text: 'Одни и те же вопросы весь день, запросы приходят **неполными**, а менеджер возвращается к покупателю за размерами и фото. Переписок много, а **до расчёта доходят единицы**.',
    result: 'Время уходит на **переписку**,\nа не на **расчёты и сделки.**',
  },
  {
    title: 'Результат **зависит от сотрудника**',
    text: 'Условия и ответы на нестандартные вопросы **разбросаны по людям и чатам**. Когда сильный менеджер болеет или уходит в отпуск, обработка Авито проседает. А **рост обращений — это рост нагрузки**.',
    result: 'Больше обращений —\n**больше сотрудников** в штат.',
  },
  {
    title: 'Нет контроля — **нет следующего шага**',
    text: 'Чтобы понять, всё ли обработано, руководитель **сам открывает чаты**. Цены и фото в объявлениях устаревают, а какие из них приносят заказы и какие **съедают бюджет** — не видно.',
    result: 'Проблемы заметны, когда клиент\nуже **ушёл**, а что менять\nсегодня — **непонятно.**',
  },
]

const rich = (text) =>
  text
    .split('**')
    .map((part, index) => (index % 2 ? <strong key={index}>{part}</strong> : part))

export default function Mistakes() {
  return (
    <section id="problem" className="mistakes section">
      <div className="container mistakes__container">
        <h2 className="mistakes__title">
          Вы <span className="mistakes__accent">уже платите</span> за эти ошибки{' '}
          <br className="mistakes__br" />— просто не видите, сколько:
        </h2>

        <p className="mistakes__subtitle">
          На Авито клиент выбирает не только по цене. Он выбирает{' '}
          <strong>того, кто ответил быстрее и не потерял его вопрос</strong>. Пока вы
          разбираете чаты и объявления вручную, часть сделок{' '}
          <strong>уходит конкурентам</strong>.
        </p>

        <ul className="mistakes__list">
          {MISTAKES.map(({ title, text, result }) => (
            <li key={title} className="mistake">
              <div className="mistake__main">
                <h3 className="mistake__title">{rich(title)}</h3>
                <p className="mistake__text">{rich(text)}</p>
              </div>
              <div className="mistake__result">
                <p className="mistake__result-text">{rich(result)}</p>
              </div>
            </li>
          ))}
        </ul>

        <p className="mistakes__footer">
          Одна потерянная заявка — это не просто диалог.{' '}
          <br className="mistakes__br" />
          Это <strong>недополученная выручка</strong>, которую уже не вернуть. А если{' '}
          <br className="mistakes__br" />
          таких заявок <strong>десятки</strong> в месяц?
        </p>

        <div className="mistakes__cta">
          <span className="mistakes__glow" aria-hidden="true" />
          <p className="mistakes__cta-label">Узнать, сколько обращений теряете Вы</p>
          <svg
            className="mistakes__arrow"
            width="616"
            height="94"
            viewBox="0 0 616 94"
            fill="none"
            aria-hidden="true"
          >
            <path d="M214 26C214 59.07 338.432 64 400 64" stroke="#006BCF" />
            <path
              d="M386.203 52C385.358 53.45 386.203 64 408 64C386.203 64 386.541 75.03 388.737 76"
              stroke="#006BCF"
            />
          </svg>
          <svg
            className="mistakes__arrow-mobile"
            width="36"
            height="44"
            viewBox="0 0 36 44"
            fill="none"
            aria-hidden="true"
          >
            <path d="M7 4C-5 20 23 24 29 34" stroke="#3E6FD8" strokeWidth="1.2" />
            <path d="M23 32L29 34V27" stroke="#3E6FD8" strokeWidth="1.2" />
          </svg>
          <a href="#calculator" className="mistakes__button">
            Калькулятор
          </a>
        </div>
      </div>
    </section>
  )
}
