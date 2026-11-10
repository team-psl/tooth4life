# WordPress Architecture & Tech Stack: Tooth4Life

## 1. Architectural Strategy & Goals
* **Speed of Execution:** Prioritize lightweight, flexible tools to enable completion within the 2-working-day window.
* **Low Maintenance:** Minimal dependencies and high stability for easy client management after delivery.
* **Performance Optimization:** Clean, fast rendering of video embeds and responsive media assets.

---

## 2. Core Technical Stack

### A. Base Environment & Core
* **CMS Platform:** WordPress (Latest Stable Version)
* **PHP Engine:** PHP 8.x
* **Database:** MySQL 8.0 / MariaDB

### B. Theme Framework
* **Selected Theme:** **Astra (Free)** or **GeneratePress (Free)**
  * *Rationale:* Extremely lightweight, fast loading out of the box, natively compatible with modern page builders, zero bloating.

### C. Site Builder & Layout Engine
* **Visual Builder:** **Elementor (Free)**
  * *Rationale:* Provides fast drag-and-drop page creation, intuitive grid controls for the 6 pages, and easy embedding for media without writing custom code.

---

## 3. Essential Plugin Architecture

To maintain high performance and simplicity, the plugin stack is limited strictly to necessary utilities:

| Plugin Name | Purpose | Usage Detail |
| :--- | :--- | :--- |
| **Elementor (Free)** | Page Layouts | Building and organizing the 6 core pages. |
| **Essential Addons for Elementor** | Component Library | Light UI components (video grid cards, testimonial blocks). |
| **Rank Math SEO (Free)** | Search Engine Optimization | Meta tags, basic schema, sitemap generation. |
| **LiteSpeed Cache / WP Super Cache** | Speed & Caching | CSS/JS minification, page caching for fast load times. |
| **Really Simple SSL** | Security | Enforcing HTTPS across all resources and assets. |
| **All-in-One WP Migration** | Migration & Package | Exporting the final site as a standalone `.wpress`/ZIP package. |

---

## 4. Asset & Media Handling Strategy

### A. Video Hosting & Integration
* Videos are **externally hosted** (e.g., Vimeo / YouTube unlisted / Cloud CDN) or embedded via HTML5 responsive containers.
* **No Direct Video Hosting on WP Database:** Prevents database bloating and guarantees quick backup/restore operations.

### B. Image Optimization & Referencing
* **Image Compression:** WebP conversion via native WP image processing or light compression before upload.
* **Copyright Attribution:** Footnote/Credit module in the site footer or dedicated image attribution section.

---

## 5. Build & Deployment Pipeline

```
[ Local / Staging WP Environment ]
             │
             ├── Theme: Astra / GeneratePress
             ├── Builder: Elementor Layouts
             ├── Content: Text + Video Embeds + Attributed Images
             └── Optimization: Caching + Basic SEO Setup
             │
[ Export Package (Database + WP Content Files via All-in-One WP Migration / Zip) ]
             │
[ Handover to Client / Deployment on Target Host ]
```