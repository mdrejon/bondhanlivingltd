/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './index.html',
    './src/**/*.{js,ts,scss,css}',
  ],
  theme: {
    extend: {
      colors: {
        gold: {
          DEFAULT: '#C9A47A',
          light:   '#D9BC9A',
          dark:    '#A8845A',
        },
        dark:  '#0C0C1E',
        navy:  '#14142A',
      },
      fontFamily: {
        serif: ['"Playfair Display"', 'Georgia', 'serif'],
        sans:  ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
