export function formatPrice(value) {
  if (value === undefined || value === null) return 'Rp 0'
  return 'Rp ' + parseFloat(value).toLocaleString('id-ID', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  })
}

export function formatDuration(minutes) {
  if (!minutes) return '0 m'
  if (minutes < 60) return `${minutes} menit`
  const hrs = Math.floor(minutes / 60)
  const mins = minutes % 60
  return mins > 0 ? `${hrs} jam ${mins} menit` : `${hrs} jam`
}
