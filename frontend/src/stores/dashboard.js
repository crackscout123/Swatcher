import { defineStore } from 'pinia'
import axios from 'axios'

export const useDashboardStore = defineStore('dashboard', {
  state: () => ({
    dashboards: [],
    activeDashboard: null,
    loading: false,
  }),

  actions: {
    async fetchDashboards() {
      this.loading = true
      try {
        const { data } = await axios.get('/api/dashboards')
        this.dashboards = data
      } finally {
        this.loading = false
      }
    },

    async fetchDashboard(id) {
      const { data } = await axios.get(`/api/dashboards/${id}`)
      this.activeDashboard = data
      return data
    },

    async createDashboard(payload) {
      const { data } = await axios.post('/api/dashboards', payload)
      this.dashboards.push(data)
      return data
    },

    async updateLayout(dashboardId, layout) {
      await axios.put(`/api/dashboards/${dashboardId}/layout`, { layout })
    },

    async deleteDashboard(id) {
      await axios.delete(`/api/dashboards/${id}`)
      this.dashboards = this.dashboards.filter(d => d.id !== id)
    },
  }
})
