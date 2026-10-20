import React, { useEffect, useRef, useState } from 'react'

import '../styles/Reveal.css'

export default function Reveal({ children }) {
  const ref = useRef(null)
  const [isVisible, setIsVisible] = useState(false)

  useEffect(() => {
    const node = ref.current

    if (!node || typeof IntersectionObserver === 'undefined') {
      setIsVisible(true)
      return undefined
    }

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true)
          observer.disconnect()
        }
      },
      { threshold: 0.1, rootMargin: '0px 0px -60px 0px' }
    )

    observer.observe(node)

    return () => observer.disconnect()
  }, [])

  return (
    <div ref={ref} className={`reveal${isVisible ? ' reveal--visible' : ''}`}>
      {children}
    </div>
  )
}
