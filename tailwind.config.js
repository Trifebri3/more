/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.vue",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        // LUXURY CLASSIC BRAND COLORS
        brand: {
          vinto: '#1E6EBD',    // Primary - Original Blue
          pudar: '#2C2A29',    // Secondary - Warm Charcoal
          tinta: '#1C1B1A',    // Dark - Main Text
          natural: '#F5F2EB',  // Accent - Warm Champagne Cream
          noir: '#121110',     // Deep Dark Slate
          orange: '#FA5424',   // Orange Accent
        },

        // Alias mapping
        primary: {
          DEFAULT: '#1E6EBD',
          light: '#4A8FC9',
          dark: '#155A9E',
        },
        secondary: {
          DEFAULT: '#2C2A29',
          light: '#423F3E',
          dark: '#1C1B1A',
        },
        dark: {
          DEFAULT: '#1C1B1A',
        },
        neutral: {
          DEFAULT: '#F5F2EB',
          light: '#FAF8F5',
          dark: '#E5E0D5',
        },
        white: {
          DEFAULT: '#FFFFFF',
        }
      },
      fontFamily: {
        'now': ['Now', 'sans-serif'],
        'inter': ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
