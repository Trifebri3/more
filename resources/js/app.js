import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'
import '../css/app.css'

// Import komponen global
import BaseButton from './Components/ui/BaseButton.vue'
import BaseCard from './Components/ui/BaseCard.vue'
import BaseInput from './Components/ui/BaseInput.vue'
import BaseModal from './Components/ui/BaseModal.vue'

const app = createApp(App)
const pinia = createPinia()

// Registrasi komponen global
app.component('BaseButton', BaseButton)
app.component('BaseCard', BaseCard)
app.component('BaseInput', BaseInput)
app.component('BaseModal', BaseModal)

app.use(pinia)
app.use(router)
app.mount('#app')
