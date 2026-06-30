import { defineStore } from 'pinia'
import api from '../utils/api'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || null)
  const isAuthenticated = ref(!!token.value)

  async function login(credentials) {
    try {
      const response = await api.post('/login', credentials)
      token.value = response.data.token
      user.value = response.data.user
      localStorage.setItem('token', token.value)
      isAuthenticated.value = true
      return { success: true }
    } catch (error) {
      return { success: false, error: error.response?.data?.message }
    }
  }

  async function logout() {
    try {
      await api.post('/logout')
    } finally {
      token.value = null
      user.value = null
      isAuthenticated.value = false
      localStorage.removeItem('token')
    }
  }

  async function initAuth() {
    if (token.value) {
      try {
        const response = await api.get('/user')
        user.value = response.data
        isAuthenticated.value = true
      } catch {
        logout()
      }
    }
  }

  return {
    user,
    token,
    isAuthenticated,
    login,
    logout,
    initAuth
  }
})
