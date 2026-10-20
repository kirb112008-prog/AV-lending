const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/

export function phoneDigits(raw) {
  let digits = String(raw).replace(/\D/g, '')
  if (digits.startsWith('7') || digits.startsWith('8')) digits = digits.slice(1)
  return digits.slice(0, 10)
}

export function formatPhone(raw) {
  const digits = phoneDigits(raw)
  if (!digits) return ''
  let out = '+7 (' + digits.slice(0, 3)
  if (digits.length >= 3) out += ')'
  if (digits.length > 3) out += ' ' + digits.slice(3, 6)
  if (digits.length > 6) out += '-' + digits.slice(6, 8)
  if (digits.length > 8) out += '-' + digits.slice(8, 10)
  return out
}

export function validateLead({ name, email, phone, niche }, nameLabel = 'имя') {
  const errors = {}

  if (String(name || '').trim().length < 2) {
    errors.name = `Укажите ${nameLabel}`
  }

  if (!EMAIL_PATTERN.test(String(email || '').trim())) {
    errors.email = 'Введите email в формате name@mail.ru'
  }

  if (phoneDigits(phone || '').length !== 10) {
    errors.phone = 'Введите номер полностью: +7 (900) 000-00-00'
  }

  if (!niche) {
    errors.niche = 'Выберите нишу'
  }

  return errors
}
