import React from 'react'

export default function Button({ href = '#contact', variant = 'primary', children, ...rest }) {
  return (
    <a href={href} className={`button button--${variant}`} {...rest}>
      {children}
    </a>
  )
}
