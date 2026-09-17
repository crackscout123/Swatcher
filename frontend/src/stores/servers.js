import { defineStore } from 'pinia'
import axios from 'axios'

export const useServerStore = defineStore('servers', {
  state: () => ({
    servers: [],
    activeServer: null,
    loading: false,
  }),

  actions: {
    async fetchServers() {
      this.loading = true
      try {
        const { data } = await axios.get('/api/servers')
        this.servers = data
      } finally {
        this.loading = false
      }
    },

    async fetchServer(id) {
      const { data } = await axios.get(`/api/servers/${id}`)
      this.activeServer = data
      return data
    },

    async createServer(payload) {
      const { data } = await axios.post('/api/servers', payload)
      this.servers.push(data)
      return data
    },

    async updateServer(id, payload) {
      const { data } = await axios.put(`/api/servers/${id}`, payload)
      const idx = this.servers.findIndex(s => s.id === id)
      if (idx !== -1) this.servers[idx] = data
      return data
    },

    async deleteServer(id) {
      await axios.delete(`/api/servers/${id}`)
      this.servers = this.servers.filter(s => s.id !== id)
    },

    async fetchMetrics(serverId, query, start, end, step = '60s') {
      const { data } = await axios.get(`/api/servers/${serverId}/metrics`, {
        params: { query, start, end, step }
      })
      return data
    }
  }
})
