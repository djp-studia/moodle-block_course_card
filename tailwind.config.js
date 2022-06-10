/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.{html,js,mustache}"
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/line-clamp'),
  ],
  // important: true,
  prefix: "tw-"
}
