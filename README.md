# Tooth4Life

Custom WordPress theme for the Tooth4Life dental stem cell storage service. Editors change text, films, and questions in WordPress. Developers change the layout and features in this repository, check them on a local site, then publish a zip to the hosted site.

The public site is [tooth4life.co.uk](https://tooth4life.co.uk/). The theme tagline is “The Dental Stem Cells Storage Service”.

## Why this way of working

A lot of WordPress work today happens inside a page builder. Pages are assembled by clicking, and the result is stored in the database. That is enough for a simple brochure. It gets in the way as soon as the site needs a real feature: a film player, a designed page, a menu that behaves a certain way, or a homepage whose wording is editable without breaking the layout.

This project keeps WordPress as the content system and puts the product in code.

- The layout is files in git. Another developer can read a change, review it, and run the same site locally.
- A new feature is a template, a component, or a class. It is not another plugin and it is not a pile of blocks that only exist on one server.
- The UI updates while you work. The theme folder is mounted into the local site, so a PHP change is visible on refresh. CSS rebuilds in the background.
- The hosted site stays a normal WordPress install. The client edits the homepage text, films, FAQs, and the contact email there. They never need Docker.
- Shipping is one zip. The hosted site does not receive a git checkout. It receives the theme, the same way any other WordPress theme is installed.

WordPress still does what it is good at: pages, media, users, and the admin. The theme does what a page builder cannot keep consistent: the design and the behaviour.

## What you need

Docker, with Compose. That is the whole local stack: WordPress, MySQL, phpMyAdmin, and the CSS build. You do not install WordPress or Node on the machine unless you want to.

Copy the environment file once:

```bash
cp .env.example .env
```

The defaults serve the site at <http://localhost:8080> and phpMyAdmin at <http://localhost:8081>.

## Start the local site

From the project root:

```bash
docker compose up
```

Leave that terminal open. The site is running while the log keeps printing and the command has not exited. When you are finished, press Ctrl+C in that same terminal. The command returns to the prompt and the site stops.

Open <http://localhost:8080> in a browser while the log is still running. On a fresh database, finish the WordPress installer, then activate **Tooth4Life** under **Appearance → Themes**. The theme is already on disk. Do not upload `tooth4life.zip` here.

If the dashboard says the demo is not installed, open **Appearance → Tooth4Life demo** and click **Install demo pages**. That publishes Home, About, Contact Us, For Parents, Research, the guide pages, the films, and the FAQs. Running it again does not duplicate pages that already exist.

Set **Settings → Permalinks** to **Post name** if addresses do not look like `/about/`.

Start the site again later with the same command, `docker compose up`. The database and uploads live in Docker volumes, so they are still there after Ctrl+C.

Any other command, such as the CSS build below, needs a second terminal. The first one is busy showing that the site is running.

## Change the theme and look at it

Edit files under `wp-content/themes/tooth4life/`. Refresh <http://localhost:8080> to see PHP, template, and copy changes.

Styles are written in `src/app.css`. The `tailwind` service watches that file and writes `assets/app.css`, which is the sheet WordPress loads. After a CSS edit, watch the terminal where `docker compose up` is running. A line that says the build is done means you can refresh the browser. If that terminal is sitting at a prompt, the site is not running. Start it again with `docker compose up`.

Useful local URLs:

| What | Address |
| --- | --- |
| Site | <http://localhost:8080> |
| Admin | <http://localhost:8080/wp-admin/> |
| Database | <http://localhost:8081> |

Log in with the WordPress user created during install. phpMyAdmin uses the MySQL root password from `.env` (`root` in the example file).

## Where a change goes

| You want to | Edit |
| --- | --- |
| A section on a page | `components/` |
| A whole designed page | `templates/` |
| The header, footer, or shared page width | `header.php`, `footer.php`, `.t4l-wrap` in `src/app.css` |
| Colours, type, spacing | `src/app.css` |
| The menu | `inc/classes/Navigation.php` |
| Default homepage wording | `inc/classes/Content.php` |
| Films, FAQs, testimonials | `inc/post-types.php` and `inc/custom-fields.php` |
| A new PHP class | the class file, then the map in `inc/autoload.php` |

`functions.php` only loads the autoloader and boots the theme. Page sections are classes under `Tooth4Life\Components`. Behaviour lives in `inc/classes/`.

The designed pages are About, Contact Us, For Parents, Research, and the guide pages under those last two. Their layout comes from the template, not from blocks in the editor. Extra blocks on those pages are printed at the bottom. Homepage wording is edited in the **Homepage text** sidebar on the Home page, not in the block canvas.

Films and FAQs are their own screens in wp-admin. A film plays the address saved as its streaming URL. A full URL is used as written. A path such as `/wp-content/uploads/2026/11/what-is-stem-cell.mp4` is played from the current site. The three MP4 files are not in git and not in the zip. Each one is about 300–370 MB. Put them in the media library, or use a YouTube or Vimeo address.

## Rules that keep the theme installable

The hosted site may be on PHP 8.2. The theme still has to run on PHP 7.4.

- Keep `declare(strict_types=1);` on new PHP files.
- Do not use PHP 8-only syntax: union types, `mixed`, `str_contains`, named arguments, or constructor property promotion.
- Escape output with `esc_html`, `esc_attr`, and `esc_url`.
- Register every new class in `inc/autoload.php`. The theme does not scan folders.
- User-facing copy uses UK spelling.
- Do not invent a phone number, WhatsApp number, address, price, or a claim that stored cells are a treatment. Storage is the service. Treatment is only described as science that may come later.

After a CSS change that will be published, produce the minified sheet before you zip. Run this in a second terminal, while `docker compose up` is still running in the first:

```bash
docker compose exec tailwind npm run build
```

## Build the zip

The file to upload is `dist/tooth4life.zip`. Build it from the project root after the minified CSS step above.

```bash
rm -rf dist/build
mkdir -p dist/build
rsync -a --delete \
  --exclude 'node_modules' \
  --exclude 'assets/films' \
  --exclude '.DS_Store' \
  wp-content/themes/tooth4life/ dist/build/tooth4life/
cp dist/INSTALLATION.md dist/build/tooth4life/INSTALLATION.md
(cd dist/build && zip -r -X ../tooth4life.zip tooth4life)
rm -rf dist/build
```

`dist/INSTALLATION.md` is the note that goes inside the package for the person installing it. This README stays in the repository and is not part of that zip.

## Publish to the hosted site

Upload `dist/tooth4life.zip` only on the hosted WordPress site.

1. In wp-admin, go to **Appearance → Themes → Add New → Upload Theme**.
2. Choose `dist/tooth4life.zip` and install it.
3. Activate **Tooth4Life**.

If WordPress says the theme is already installed, switch to a default theme such as Twenty Twenty-Five, delete **Tooth4Life**, upload the zip, and activate it again. Deleting the theme removes its files. Pages, films, FAQs, and media stay in the database.

Then check the public pages, including Home and About. If About was deleted and created again, WordPress may have named it `about-2` while the old page sits in the trash. Restore or keep the published page, set its template to **About**, and set the slug back to `about` if the address should be `/about/`.

Do not upload this zip on the local Docker site. The local theme folder is a mount of this repository. WordPress would delete the files in that folder and then fail to remove the mount. The local site is already running the theme you just edited.

## What each environment is for

| | Local (`docker compose`) | Hosted WordPress |
| --- | --- | --- |
| Purpose | Build and preview | The site people visit |
| Theme files | The folder in this repo | The uploaded zip |
| How you update it | Save a file and refresh | Upload a new zip |
| Content | Demo content for development | The real pages, films, and questions |

A developer can change the UI or add a feature at any time on the local site, confirm it in the browser, and only then build the zip. The hosted site changes when that zip is installed, not when a file is saved locally.
