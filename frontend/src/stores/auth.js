import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    loading: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.user,
    hasRole: (state) => (role) => {
      if (!state.user?.roles) return false
      const roles = Array.isArray(state.user.roles) ? state.user.roles : [state.user.roles]
      return roles.includes(role)
    },
    hasAnyRole: (state) => (roles) => {
      if (!state.user?.roles) return false
      const userRoles = Array.isArray(state.user.roles) ? state.user.roles : [state.user.roles]
      return roles.some(r => userRoles.includes(r))
    },
  },

  actions: {
    async login(email, password) {
      await axios.get('/sanctum/csrf-cookie')
      await axios.post('/api/login', { email, password })
      await this.fetchUser()
    },

    async logout() {
      await axios.post('/api/logout')
      this.user = null
    },

    async fetchUser() {
      this.loading = true
      try {
        const { data } = await axios.get('/api/me')
        this.user = data
      } catch {
        this.user = null
      } finally {
        this.loading = false
      }
    },
  }
})
