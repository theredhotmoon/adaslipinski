// Tailwind v4 via PostCSS. We use this rather than @tailwindcss/vite because Astro 6
// bundles rolldown-vite, whose resolver plugin the Vite variant isn't yet compatible
// with (it throws "Missing field `tsconfigPaths`"). PostCSS sidesteps that entirely.
export default {
  plugins: {
    '@tailwindcss/postcss': {},
  },
}
