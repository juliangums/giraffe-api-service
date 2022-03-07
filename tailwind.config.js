const colors = require('tailwindcss/colors')
module.exports = {
  purge: [
         './resources/**/*.blade.php',
         './resources/**/*.js',
         './resources/**/*.vue',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
        backgroundImage: {
            'giraffe':  "url('/public/images/confirmImage.jpg')",
        },
    },
    colors: {
        orange : colors.orange,
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
