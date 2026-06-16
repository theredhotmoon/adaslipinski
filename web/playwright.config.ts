import { defineConfig, devices } from '@playwright/test'

// Smoke tests run against the built site served by `astro preview`. In CI we always
// start a fresh preview server; locally we reuse a running dev server (e.g. on :4321)
// if there is one, so `npm run test:e2e` "just works" during development.
const PORT = 4321
const BASE_URL = `http://localhost:${PORT}`

export default defineConfig({
  testDir: './e2e',
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 1 : 0,
  reporter: process.env.CI ? 'github' : 'list',
  use: {
    baseURL: BASE_URL,
    trace: 'on-first-retry',
  },
  projects: [{ name: 'chromium', use: { ...devices['Desktop Chrome'] } }],
  webServer: {
    command: 'npm run preview',
    url: BASE_URL,
    reuseExistingServer: !process.env.CI,
    timeout: 120_000,
  },
})
