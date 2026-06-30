$files = @(
# Root Pages
"resources/js/pages/App.vue",
"resources/js/pages/LandingPage.vue",
"resources/js/pages/BookingFlow.vue",
"resources/js/pages/BookingSuccess.vue",

# Public Pages
"resources/js/pages/public/About.vue",
"resources/js/pages/public/Services.vue",
"resources/js/pages/public/Outlets.vue",
"resources/js/pages/public/Contact.vue",

# Customer Pages
"resources/js/pages/customer/Dashboard.vue",
"resources/js/pages/customer/Profile.vue",
"resources/js/pages/customer/BookingHistory.vue",
"resources/js/pages/customer/CheckIn.vue",
"resources/js/pages/customer/LoyaltyProgram.vue",

# Admin Pages
"resources/js/pages/admin/Dashboard.vue",
"resources/js/pages/admin/Outlets/OutletList.vue",
"resources/js/pages/admin/Outlets/OutletForm.vue",
"resources/js/pages/admin/Stylists/StylistList.vue",
"resources/js/pages/admin/Stylists/StylistForm.vue",
"resources/js/pages/admin/Services/ServiceList.vue",
"resources/js/pages/admin/Services/ServiceForm.vue",
"resources/js/pages/admin/Customers/CustomerList.vue",
"resources/js/pages/admin/Customers/CustomerDetail.vue",
"resources/js/pages/admin/Bookings/BookingList.vue",
"resources/js/pages/admin/Bookings/BookingDetail.vue",
"resources/js/pages/admin/Promotions/PromotionList.vue",
"resources/js/pages/admin/Promotions/PromotionForm.vue",
"resources/js/pages/admin/Analytics/AnalyticsDashboard.vue",
"resources/js/pages/admin/Settings/SystemSettings.vue",

# Staff Pages
"resources/js/pages/staff/CheckInPage.vue",
"resources/js/pages/staff/WalkInPage.vue",
"resources/js/pages/staff/QueuePage.vue",
"resources/js/pages/staff/CustomerLookup.vue",

# Stylist Pages
"resources/js/pages/stylist/DashboardPage.vue",
"resources/js/pages/stylist/SchedulePage.vue",
"resources/js/pages/stylist/LeaveRequest.vue",
"resources/js/pages/stylist/Performance.vue",

# Layouts
"resources/js/layouts/PublicLayout.vue",
"resources/js/layouts/CustomerLayout.vue",
"resources/js/layouts/StaffLayout.vue",
"resources/js/layouts/AdminLayout.vue",
"resources/js/layouts/StylistLayout.vue",

# Components - Common
"resources/js/components/common/Navbar.vue",
"resources/js/components/common/Footer.vue",
"resources/js/components/common/PageHeader.vue",
"resources/js/components/common/LoadingSpinner.vue",
"resources/js/components/common/ToastNotification.vue",
"resources/js/components/common/ModalDialog.vue",
"resources/js/components/common/ConfirmDialog.vue",
"resources/js/components/common/EmptyState.vue",
"resources/js/components/common/ErrorBoundary.vue",

# Components - UI
"resources/js/components/ui/Button.vue",
"resources/js/components/ui/Card.vue",
"resources/js/components/ui/Badge.vue",
"resources/js/components/ui/Avatar.vue",
"resources/js/components/ui/Rating.vue",
"resources/js/components/ui/InputField.vue",
"resources/js/components/ui/SelectField.vue",
"resources/js/components/ui/DatePicker.vue",
"resources/js/components/ui/TimePicker.vue",
"resources/js/components/ui/PhoneInput.vue",
"resources/js/components/ui/FileUpload.vue",
"resources/js/components/ui/Tabs.vue",
"resources/js/components/ui/Accordion.vue",
"resources/js/components/ui/Dropdown.vue",
"resources/js/components/ui/Pagination.vue",
"resources/js/components/ui/DataTable.vue",

# Components - Booking
"resources/js/components/booking/OutletSelector.vue",
"resources/js/components/booking/ServiceSelector.vue",
"resources/js/components/booking/StylistSelector.vue",
"resources/js/components/booking/DateTimePicker.vue",
"resources/js/components/booking/CustomerForm.vue",
"resources/js/components/booking/BookingSummary.vue",
"resources/js/components/booking/BookingConfirmation.vue",
"resources/js/components/booking/QRCodeDisplay.vue",

# Components - Customer
"resources/js/components/customer/CustomerProfileCard.vue",
"resources/js/components/customer/CustomerBookingHistory.vue",
"resources/js/components/customer/CustomerLoyaltyCard.vue",

# Components - Stylist
"resources/js/components/stylist/StylistCard.vue",
"resources/js/components/stylist/StylistSchedule.vue",
"resources/js/components/stylist/StylistRating.vue",

# Components - Outlet
"resources/js/components/outlet/OutletCard.vue",
"resources/js/components/outlet/OutletMap.vue",
"resources/js/components/outlet/OutletHours.vue",

# Stores
"resources/js/stores/authStore.js",
"resources/js/stores/bookingStore.js",
"resources/js/stores/customerStore.js",
"resources/js/stores/outletStore.js",
"resources/js/stores/serviceStore.js",
"resources/js/stores/stylistStore.js",
"resources/js/stores/notificationStore.js",
"resources/js/stores/uiStore.js",
"resources/js/stores/analyticsStore.js",

# Router
"resources/js/router/index.js",
"resources/js/router/routes.js",
"resources/js/router/middleware.js",

# Utils
"resources/js/utils/dateHelper.js",
"resources/js/utils/validation.js",
"resources/js/utils/formatter.js",
"resources/js/utils/geolocation.js",
"resources/js/utils/analytics.js",

# Composables
"resources/js/composables/useAuth.js",
"resources/js/composables/useBooking.js",
"resources/js/composables/useGeolocation.js",
"resources/js/composables/useNotification.js",
"resources/js/composables/usePagination.js",
"resources/js/composables/useQRCode.js",
"resources/js/composables/useDebounce.js"
)

foreach ($file in $files) {
    $dir = Split-Path $file
    if (!(Test-Path $dir)) {
        New-Item -ItemType Directory -Force -Path $dir | Out-Null
    }

    if (!(Test-Path $file)) {
        New-Item -ItemType File -Path $file | Out-Null
    }
}

Write-Host ""
Write-Host "====================================="
Write-Host "✅ Semua folder dan file berhasil dibuat!"
Write-Host "====================================="
