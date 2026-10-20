import React, { useEffect } from 'react'

import { PRIVACY } from '../content/privacy'
import '../styles/Privacy.css'

export default function PrivacyPage() {
  useEffect(() => {
    window.scrollTo(0, 0)
    document.title = `${PRIVACY.title} — САММИ`
  }, [])

  return (
    <article className="doc">
      <div className="container doc__inner">
        <div className="doc__head">
          <h1 className="doc__title">{PRIVACY.title}</h1>
          <p className="doc__updated">{PRIVACY.updated}</p>
        </div>

        {PRIVACY.sections.map(({ title, items }, index) => (
          <section key={title} className="doc__section">
            <h2 className="doc__heading">
              <span className="doc__number">{index + 1}</span>
              {title}
            </h2>
            <ul className="doc__list">
              {items.map(({ n, text, list }) => (
                <li key={n}>
                  <span className="doc__item-number">{n}.</span>
                  <p className="doc__text">{text}</p>
                  {list && (
                    <ul className="doc__sublist">
                      {list.map((entry) => (
                        <li key={entry}>{entry}</li>
                      ))}
                    </ul>
                  )}
                </li>
              ))}
            </ul>
          </section>
        ))}
      </div>
    </article>
  )
}
