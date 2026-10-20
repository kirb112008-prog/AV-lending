import { useState } from 'react'
import axios from 'axios'

import { formatPhone, validateLead } from './validation'

// адрес обработчика заявок на сайте (WordPress); токен сервера заявок подставляется там, а не в браузере
const LEADS_ENDPOINT = process.env.REACT_APP_LEADS_ENDPOINT || '/wp-json/avito/v1/lead'
const REDIRECT_URL = 'https://159-194-249-131.sslip.io/c/litera/agent'

// Яндекс SmartCaptcha в невидимом режиме; без ключа в .env капча выключена
const CAPTCHA_KEY = process.env.REACT_APP_SMARTCAPTCHA_SITEKEY
const CAPTCHA_SCRIPT = 'https://smartcaptcha.yandexcloud.net/captcha.js'

let captchaScript = null
let captchaWidget = null
let captchaPending = null

const loadCaptcha = () => {
  if (window.smartCaptcha) return Promise.resolve(window.smartCaptcha)

  if (!captchaScript) {
    captchaScript = new Promise((resolve, reject) => {
      const script = document.createElement('script')
      script.src = CAPTCHA_SCRIPT
      script.async = true
      script.onload = () => resolve(window.smartCaptcha)
      script.onerror = () => {
        captchaScript = null
        reject(new Error('captcha unavailable'))
      }
      document.head.appendChild(script)
    })
  }

  return captchaScript
}

const settleCaptcha = (method, value) => {
  if (captchaPending && !captchaPending.done) {
    captchaPending.done = true
    captchaPending[method](value)
  }
}

const getCaptchaToken = async () => {
  const api = await loadCaptcha()

  return new Promise((resolve, reject) => {
    const pending = { resolve, reject, done: false }
    captchaPending = pending

    if (captchaWidget === null) {
      const holder = document.createElement('div')
      document.body.appendChild(holder)

      captchaWidget = api.render(holder, {
        sitekey: CAPTCHA_KEY,
        invisible: true,
        hl: 'ru',
        callback: (token) => settleCaptcha('resolve', token),
      })

      // окно проверки закрыли, не пройдя её: даём отправить форму ещё раз
      api.subscribe(captchaWidget, 'challenge-hidden', () => {
        const current = captchaPending
        setTimeout(() => {
          if (current === captchaPending) settleCaptcha('reject', new Error('cancelled'))
        }, 500)
      })
      api.subscribe(captchaWidget, 'javascript-error', () => settleCaptcha('reject', new Error('captcha error')))
      api.subscribe(captchaWidget, 'network-error', () => settleCaptcha('reject', new Error('captcha error')))
    } else {
      api.reset(captchaWidget)
    }

    api.execute(captchaWidget)
  })
}

const readValues = (form) => {
  const data = new FormData(form)

  return {
    name: String(data.get('name') || '').trim(),
    email: String(data.get('email') || '').trim(),
    phone: String(data.get('phone') || '').trim(),
    niche: data.get('niche') || '',
  }
}

export default function useLeadForm({ nameLabel } = {}) {
  const [status, setStatus] = useState('idle')
  const [errors, setErrors] = useState({})

  const handleInput = (event) => {
    const { name } = event.target

    if (name === 'phone') {
      event.target.value = formatPhone(event.target.value)
    }

    setErrors((current) => {
      if (!current[name]) return current

      const { [name]: removed, ...rest } = current
      return rest
    })
  }

  const handleSubmit = async (event) => {
    event.preventDefault()

    if (status !== 'idle') return

    const form = event.currentTarget
    const values = readValues(form)
    const found = validateLead(values, nameLabel)

    setErrors(found)

    const [firstInvalid] = Object.keys(found)

    if (firstInvalid) {
      form.querySelector(`[name="${firstInvalid}"]`)?.focus()
      return
    }

    setStatus('posting')

    try {
      let payload = values

      if (CAPTCHA_KEY) {
        try {
          payload = { ...values, captcha: await getCaptchaToken() }
        } catch (captchaError) {
          setStatus(captchaError.message === 'cancelled' ? 'idle' : 'error')
          return
        }
      }

      const response = await axios.post(LEADS_ENDPOINT, payload)

      if (response.status !== 200) {
        setStatus('error')
        return
      }

      setStatus('success')
      window.location.href = REDIRECT_URL
    } catch (err) {
      setStatus('error')
    }
  }

  return { status, errors, handleInput, handleSubmit }
}
