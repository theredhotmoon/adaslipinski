// @ts-check
import { defineConfig } from 'astro/config'
import vue from '@astrojs/vue'
import sitemap from '@astrojs/sitemap'

// Canonical origin — drives <link rel="canonical">, hreflang, and sitemap.xml.
const site = process.env.PUBLIC_SITE_URL ?? 'https://adaslipinski.pl'

// https://astro.build/config
export default defineConfig({
  site,

  // Default output is 'static' (SSG): every page is built to HTML at deploy time
  // and the bundled CMS content is fetched once, in .astro frontmatter. To make a
  // single route always-fresh, add an adapter (@astrojs/node|vercel|cloudflare)
  // and put `export const prerender = false` in that page — nothing else changes.

  integrations: [vue(), sitemap()],

  image: {
    // Hosts whose images Astro's <Image> may fetch + optimize at build time.
    // Production media is https (tighten the bare https rule to your real storage
    // host); the localhost rules cover the Laravel dev server's /storage URLs.
    remotePatterns: [
      { protocol: 'https' },
      { protocol: 'http', hostname: 'localhost' },
      { protocol: 'http', hostname: '127.0.0.1' },
    ],
  },

  // Tailwind v4 is wired through postcss.config.mjs (@tailwindcss/postcss). global.css
  // is imported by the base layout.
})
