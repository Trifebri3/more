export function validateEmail(email) {
  if (!email) return true // Email is optional in customer form
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

export function validatePhone(phone) {
  if (!phone) return false
  // Standard Indonesian phone format: 08 or +62
  const re = /^(?:\+62|62|0)8[1-9][0-9]{6,10}$/
  return re.test(phone)
}
