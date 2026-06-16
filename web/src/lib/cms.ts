import type { SiteContent } from './types'
import type { Locale } from '../i18n/config'
import { fallback } from './fallback'

// Server-side CMS client. Called from .astro frontmatter with top-level await, so the
// fetch happens at BUILD time (SSG) and the response is baked into static HTML — the
// browser never talks to Laravel. No PUBLIC_ prefix means CMS_API_URL stays server-only.
const API_URL = import.meta.env.CMS_API_URL ?? 'http://localhost:8000/api'

/**
 * Fetch the full localized site payload for one language. On any failure (API down,
 * non-2xx, bad JSON) it logs and returns bundled fallback content so the build still
 * produces a complete site rather than crashing.
 */
export async function fetchSite(lang: Locale): Promise<SiteContent> {
  try {
    const res = await fetch(`${API_URL}/cms/site?lang=${lang}`, {
      headers: { Accept: 'application/json' },
    })
    if (!res.ok) throw new Error(`CMS responded ${res.status}`)
    return (await res.json()) as SiteContent
  } catch (err) {
    const reason = err instanceof Error ? err.message : String(err)
    console.warn(`[cms] /cms/site?lang=${lang} unavailable — using bundled fallback (${reason})`)
    return fallback
  }
}
