/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
        colors: {
            'primary': '#17181A',
            'secondary': '#232428',
            'orange': '#FF9501',
            'green': '#2CCA73',
            'dark-red-translucent': 'rgba(201,78,98,0.38)',
            'dark-red': 'rgb(201,78,98)',
        }
    },
  },
  plugins: [],
}

