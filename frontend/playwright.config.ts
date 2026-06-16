import { defineConfig, devices } from '@playwright/test'

// E2e for the admin SPA. Unlike the public-site suites in web/, these drive the
// authenticated editor, so they need a live, seeded Laravel backend on :8000
// (admin@example.com / password123). See e2e/README.md for the local run recipe.
//
// Content tests run at a phone-sized viewport on purpose: below the 1024px desktop
// breakpoint the app always renders the single mobile layout (FrHomeScreen), so the
// specs don't have to account for the three admin-selectable desktop layouts.
const PORT = Number(process.env.E2E_PORT ?? 5173)
const BASE_URL = `http://localhost:${PORT}`

export default defineConfig({
  testDir: './e2e',
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 1 : 0,
  reporter: process.env.CI ? 'github' : 'list',
  use: {
    baseURL: BASE_URL,
    trace: 'on-first-retry',
  },
  projects: [
    {
      name: 'chromium',
      // Phone-sized viewport overrides the device default (1280×720) so the mobile
      // layout renders — see the note above.
      use: { ...devices['Desktop Chrome'], viewport: { width: 414, height: 896 } },
    },
  ],
  webServer: {
    // Serve the SPA; the app talks to the backend at VITE_API_URL
    // (defaults to http://localhost:8000/api).
    command: `npm run dev -- --port ${PORT} --strictPort`,
    url: BASE_URL,
    reuseExistingServer: !process.env.CI,
    timeout: 120_000,
  },
})
