// import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import containerQueries from '@tailwindcss/container-queries';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
  ],

  theme: {
    extend: {
      colors: {
        'main-skin': '#FFEBDC',
        'main-white-gray': '#D7D2CE',
        'main-light-gray': '#7E7E7E',
        'main-gray': '#404040',
        'main-middle-gray': '#2E2E2E',
        'main-dark-gray': '#212121',
        'main-black': '#0D0D0D',
        'main-blue': '#2A79BE',
        'main-light-blue': '#3A95C1',
        'main-light-green': '#509D50',
        'main-dark-green': '#2A3E3E',
        'main-light-red': '#E46278',
        'main-brown': '#795642',
        'main-yellow': '#FFFAAB',
      },
      fontFamily: {
        sans: ['Noto Sans TC, sans-serif', 'Microsoft JhengHei'],
      },
    },
  },

  plugins: [
    forms,
    containerQueries,
  ],
};
