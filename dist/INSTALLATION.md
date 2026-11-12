# Tooth4Life theme installation

This package is the Tooth4Life theme only. The three film files are not included. They are about 1 GB together, so they are added after the theme is installed.

The site needs WordPress 6.7 or newer. It runs on PHP 7.4 and newer, including PHP 8.2. It was tested on WordPress 7.1.

## 1. Install the theme

1. In WordPress admin, go to **Appearance → Themes → Add New → Upload Theme**.
2. Choose `tooth4life.zip` and click **Install Now**.
3. Click **Activate**.

If the upload fails because the file is too large, unzip it and place the `tooth4life` folder in `wp-content/themes/`, then activate **Tooth4Life** from **Appearance → Themes**.

## 2. Install the video plugin

The films use **HTML5 Video Player** by bPlugins. The theme calls its `[video_player]` shortcode. Install it from WordPress, not from a copied folder.

1. Go to **Plugins → Add New**.
2. Search for **HTML5 Video Player**.
3. Install the plugin by **bPlugins** and click **Activate**.

Without this plugin, a film can still play as a plain video or an embedded YouTube or Vimeo link. The branded player on the site needs the plugin to be active.

The theme sets the player colour to `#1e4b9b` unless another colour has already been saved in the plugin settings.

## 3. Create the pages

After activation, the dashboard shows **The Tooth4Life demo is not installed yet**.

1. Open **Appearance → Tooth4Life demo**.
2. Click **Install demo pages**.

That creates and publishes Home, For Parents, Research, About, and Contact Us, sets Home as the front page, and adds the three film entries and the questions. It also creates the subpages under For Parents (Stored Cells, Storage to Treatment, Banking Checklist) and Research (Stem Cell Science, Why Tooth Stem Cells?, Studies in People, Lessons from Research). It does not upload the videos. Running it again does not duplicate pages that already exist.

Set **Settings → Permalinks** to **Post name** if the page addresses do not look like `/for-parents/`. The demo installer switches an empty permalink setting to that structure.

If About was deleted and created again, WordPress may name the new page `about-2` while the old one is still in the trash. The theme still shows the designed About page on that new page, and the About menu link follows it. Empty the trash, then set the new page’s slug back to `about` if you want the address to be `/about/`. In the page sidebar, the template should be **About**.

## 4. Add the three films

Upload each film to the media library, or host it on YouTube or Vimeo, then paste the address into the matching film.

| Film | File you already have | Where it appears |
| --- | --- | --- |
| What are stem cells? | `what-is-stem-cell.mp4` | Home, and Research |
| Why baby teeth are valuable | `why-baby-teeth.mp4` | Home, and For Parents |
| How collection works | `how-collection-works.mp4` | Home, and For Parents |

Each file is roughly 300–370 MB. The host must allow an upload at least that large (`upload_max_filesize` and `post_max_size` in PHP). A YouTube or Vimeo address avoids that limit. The player accepts an MP4, WebM, HLS (`.m3u8`), YouTube, or Vimeo address.

1. Go to **Films** and open a film.
2. Paste the address into **Streaming URL**. A full URL is used as written. A path such as `/wp-content/uploads/2026/11/what-is-stem-cell.mp4` is played from the current site.
3. Update the film.

If **Streaming URL** is left empty, the theme uses the matching fallback under **Appearance → Customize → Tooth4Life**:

- Fallback URL: What are stem cells?
- Fallback URL: Why baby teeth are valuable
- Fallback URL: How collection works

The excerpt under the title, the duration, and the related link are already filled in by the demo installer. Order on the home page follows the film order field.

## 5. Edit the homepage text

Open **Pages → Home**. The page itself is a preview. The wording is in the **Homepage text** panel in the settings sidebar (the Page tab). Change a field and click **Save**. The preview updates after the save.

Film titles and the text under each film are edited on the **Films** screen. Questions and answers are edited on the **FAQs** screen.

## 6. Check the contact details

Under **Appearance → Customize → Tooth4Life**:

- **Email** is public. The theme uses `enquiry@tooth4life.co.uk` until it is changed.
- **Phone** and **WhatsApp** stay hidden until a number is saved.

There is no contact form. The buttons open an email to the team.
