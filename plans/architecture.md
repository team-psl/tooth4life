# WordPress Architecture & Server Stack (`architecture.md`)

## 1. Core Technical Stack
* **CMS:** WordPress (Latest stable release)
* **Theme:** Astra (Free version) or GeneratePress (Free version) — ultra-lightweight, clean code, highly performant.
* **Page Builder:** Elementor (Free version) for drag-and-drop structural design.
* **Migration & Backup Tool:** Duplicator or All-in-One WP Migration (packages files and database into a single, seamless `.zip` archive for instant deployment on any host).

---

## 2. Server & PHP Requirements (October 2026 Security Standards)

To ensure robust security against critical core PHP vulnerabilities (such as the October 2026 remote code execution flaws, CVE-2026-109618 / CVE-2026-109619, often referenced in server security advisories), hosting servers must run hardened and fully patched runtime environments.

### Recommended PHP Versions
* **Primary Recommendation:** **PHP 8.3 or PHP 8.4** (Fully supported, highly stable for modern WordPress ecosystems and plugins).
* **Minimum Patched Baseline:** If running older branches due to legacy constraints, servers must be explicitly patched to **PHP 8.2.35, 8.3.36, 8.4.27, or 8.5.12** (or vendor-backported equivalents). 
* **End-of-Life Warning:** Avoid unpatched PHP 8.1 or older, as they lack official security support.

### Required PHP Extensions & Settings
* **Extensions:** `mysqli`, `curl`, `mbstring`, `xml`, `gd` or `imagick`, `zip`, `openssl`.
* **Memory Limit:** Minimum `256M` (`memory_limit = 256M`).
* **Upload Max Filesize / Post Max Size:** `64M` or higher (to accommodate high-resolution video thumbnails and theme assets).
* **Session Upload Progress Configuration:** Ensure `session.upload_progress.enabled` is properly managed or disabled via server pools (`php_admin_flag[session.upload_progress.enabled] = off`) if operating under strict server-hardening guidelines.