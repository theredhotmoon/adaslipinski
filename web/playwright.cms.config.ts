import { defineConfig, devices } from '@playwright/test'

// Separate config for the CMS integration e2e (a real backend hides a section, then
// the site is built against it). Kept apart from the fallback smoke suite (./e2e) so
// each job runs only the specs it has an environment for.
const PORT = 4321
const BASE_URL = `http://localhost:${PORT}`

export default defineConfig({
  testDir: './e2e-cms',
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 1 : 0,
  reporter: process.env.CI ? 'github' : 'list',
  use: { baseURL: BASE_URL },
  projects: [{ name: 'chromium', use: { ...devices['Desktop Chrome'] } }],
  webServer: {
    command: 'npm run preview',
    url: BASE_URL,
    reuseExistingServer: !process.env.CI,
    timeout: 120_000,
  },
})
