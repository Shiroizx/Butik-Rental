/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
      colors: {
        'deep-charcoal': '#1A1A1B',
        'metallic-gold': '#D4AF37',
        'soft-ivory': '#F9F9F7',
        'slate-gray': '#707070',
      }
    },
  },
  plugins: [],
}
