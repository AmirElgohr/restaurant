/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/frontend/**/*.blade.php",
    "./resources/views/frontend/**/*.js",
    "./resources/views/frontend/**/*.vue",
  ],
  darkMode: 'class',
  theme: {
    container: {
      center: true,
      padding: "1rem",
    },
    screens: {
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1142px',
    },
    fontFamily: {
      montserrat : ['Montserrat', 'sans-serif'],
    },
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      white: '#ffffff',
      black: '#000000',
      primary : '#1C84EE',
      secondary : '#08111F',
      neutral : '#7F90BC',
    },

    extend: {
      boxShadow: {
        shadowitem : '5px 10px 80px rgba(119, 128, 161, 0.15)',
        shadowdark : '5px 10px 80px rgba(0, 0, 0, 0.2)',
      }
    },
  },
  plugins: [
    require('tailwind-scrollbar'),
    require('@tailwindcss/line-clamp')
  ],
}