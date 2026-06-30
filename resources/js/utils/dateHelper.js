export function formatDate(dateString) {
  if (!dateString) return ''
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', options)
}

export function formatShortDate(dateString) {
  if (!dateString) return ''
  const options = { day: 'numeric', month: 'short', year: 'numeric' }
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', options)
}

export function getDayName(dayIndex) {
  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
  return days[dayIndex] || ''
}
