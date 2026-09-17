import { ref, watchEffect } from 'vue'

const colorMode = ref(localStorage.getItem('swatcher-color-mode') || 'system')

export function useTheme() {
  function applyMode(mode) {
    const html = document.documentElement
    if (mode === 'system') {
      html.removeAttribute('data-theme')
    } else {
      html.setAttribute('data-theme', mode)
    }
  }

  function setMode(mode) {
    colorMode.value = mode
    localStorage.setItem('swatcher-color-mode', mode)
  }

  function applyTokens(tokens = {}) {
    const root = document.documentElement
    Object.entries(tokens).forEach(([key, value]) => {
      root.style.setProperty(`--${key}`, value)
    })
  }

  function applyScopedCss(dashboardId, css = '') {
    const id = `swatcher-custom-css-${dashboardId}`
    let el = document.getElementById(id)
    if (!el) {
      el = document.createElement('style')
      el.id = id
      el.setAttribute('data-swatcher-scoped', dashboardId)
      document.head.appendChild(el)
    }
    el.textContent = css
  }

  watchEffect(() => applyMode(colorMode.value))

  return {
    colorMode,
    setMode,
    applyTokens,
    applyScopedCss,
  }
}
