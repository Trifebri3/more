import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

// Layouts
import DefaultLayout from '../Layouts/DefaultLayout.vue'
import AuthLayout from '../Layouts/AuthLayout.vue'
import AdminLayout from '../Layouts/AdminLayout.vue'
import StaffLayout from '../Layouts/StaffLayout.vue'
import StylistLayout from '../Layouts/StylistLayout.vue'

// Public Routes
import LandingPage from '../Pages/LandingPage.vue'
import BookingFlow from '../Pages/BookingFlow.vue'
import OutletList from '../Pages/OutletList.vue'
import OutletDetail from '../Pages/OutletDetail.vue'
import ServiceList from '../Pages/ServiceList.vue'
import ContactPage from '../Pages/ContactPage.vue'

// Customer Routes
import CustomerDashboard from '../Pages/customer/Dashboard.vue'
import CustomerProfile from '../Pages/customer/Profile.vue'
import CustomerBookings from '../Pages/customer/Bookings.vue'
import CheckInPage from '../Pages/customer/CheckIn.vue'

// Staff Routes
import StaffDashboard from '../Pages/staff/Dashboard.vue'
import StaffCheckIn from '../Pages/staff/CheckIn.vue'
import StaffWalkIn from '../Pages/staff/WalkIn.vue'
import StaffQueue from '../Pages/staff/Queue.vue'

// Stylist Routes
import StylistDashboard from '../Pages/stylist/Dashboard.vue'
import StylistSchedule from '../Pages/stylist/Schedule.vue'
import StylistLeave from '../Pages/stylist/Leave.vue'

// Admin Routes
import AdminDashboard from '../Pages/admin/Dashboard.vue'
import AdminOutlets from '../Pages/admin/Outlets.vue'
import AdminStylists from '../Pages/admin/Stylists.vue'
import AdminServices from '../Pages/admin/Services.vue'
import AdminCustomers from '../Pages/admin/Customers.vue'
import AdminBookings from '../Pages/admin/Bookings.vue'
import AdminPromotions from '../Pages/admin/Promotions.vue'
import AdminAnalytics from '../Pages/admin/Analytics.vue'
import AdminSettings from '../Pages/admin/Settings.vue'

// Kiosk Routes
import KioskHome from '../Pages/kiosk/Home.vue'
import KioskWalkIn from '../Pages/kiosk/WalkIn.vue'
import KioskCheckIn from '../Pages/kiosk/CheckIn.vue'
import KioskQueue from '../Pages/kiosk/Queue.vue'

const routes = [
  // Public routes
  {
    path: '/',
    component: DefaultLayout,
    children: [
      { path: '', component: LandingPage, name: 'home' },
      { path: 'booking', component: BookingFlow, name: 'booking' },
      { path: 'outlets', component: OutletList, name: 'outlets' },
      { path: 'outlets/:id', component: OutletDetail, name: 'outlet-detail' },
      { path: 'services', component: ServiceList, name: 'services' },
      { path: 'contact', component: ContactPage, name: 'contact' },
    ]
  },

  // Customer routes
  {
    path: '/customer',
    component: AuthLayout,
    meta: { requiresAuth: true, role: 'customer' },
    children: [
      { path: 'dashboard', component: CustomerDashboard, name: 'customer.dashboard' },
      { path: 'profile', component: CustomerProfile, name: 'customer.profile' },
      { path: 'bookings', component: CustomerBookings, name: 'customer.bookings' },
      { path: 'check-in', component: CheckInPage, name: 'customer.checkin' },
    ]
  },

  // Staff routes
  {
    path: '/staff',
    component: StaffLayout,
    meta: { requiresAuth: true, role: 'staff' },
    children: [
      { path: 'dashboard', component: StaffDashboard, name: 'staff.dashboard' },
      { path: 'check-in', component: StaffCheckIn, name: 'staff.checkin' },
      { path: 'walk-in', component: StaffWalkIn, name: 'staff.walkin' },
      { path: 'queue', component: StaffQueue, name: 'staff.queue' },
    ]
  },

  // Stylist routes
  {
    path: '/stylist',
    component: StylistLayout,
    meta: { requiresAuth: true, role: 'stylist' },
    children: [
      { path: 'dashboard', component: StylistDashboard, name: 'stylist.dashboard' },
      { path: 'schedule', component: StylistSchedule, name: 'stylist.schedule' },
      { path: 'leave', component: StylistLeave, name: 'stylist.leave' },
    ]
  },

  // Admin routes
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      { path: 'dashboard', component: AdminDashboard, name: 'admin.dashboard' },
      { path: 'outlets', component: AdminOutlets, name: 'admin.outlets' },
      { path: 'stylists', component: AdminStylists, name: 'admin.stylists' },
      { path: 'services', component: AdminServices, name: 'admin.services' },
      { path: 'customers', component: AdminCustomers, name: 'admin.customers' },
      { path: 'bookings', component: AdminBookings, name: 'admin.bookings' },
      { path: 'promotions', component: AdminPromotions, name: 'admin.promotions' },
      { path: 'analytics', component: AdminAnalytics, name: 'admin.analytics' },
      { path: 'settings', component: AdminSettings, name: 'admin.settings' },
    ]
  },

  // Kiosk routes
  {
    path: '/kiosk',
    component: DefaultLayout,
    meta: { kioskMode: true },
    children: [
      { path: '', component: KioskHome, name: 'kiosk.home' },
      { path: 'walk-in', component: KioskWalkIn, name: 'kiosk.walkin' },
      { path: 'check-in', component: KioskCheckIn, name: 'kiosk.checkin' },
      { path: 'queue', component: KioskQueue, name: 'kiosk.queue' },
    ]
  },

  // Public login route
  {
    path: '/login',
    component: AuthLayout,
    name: 'login'
  },

  // 404
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const requiredRole = to.meta.role

  if (requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login' })
  } else if (requiresAuth && requiredRole && authStore.user?.role !== requiredRole) {
    next({ name: 'home' })
  } else {
    next()
  }
})

export default router
