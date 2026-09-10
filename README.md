# NgoHoWei-Portfilio

Personal portfolio site for **Ngo Ho Wei** — full-stack web developer, Kuala Lumpur, Malaysia.

**Live:** https://ngohowei.github.io/NgoHoWei-Portfilio/

## About this repo

A single, self-contained `index.html` — no build step, no dependencies, no
framework. All styling is inline in a `<style>` block; the only script sets the
footer year. The contact form posts to [Formspree](https://formspree.io/).

```
index.html      # the whole site
.gitignore
README.md
```

## Develop

Open `index.html` in a browser, or serve the folder:

```bash
python -m http.server 8000
# then visit http://localhost:8000
```

## Deploy

Hosted on GitHub Pages from the `main` branch (root). Push to `main` and Pages
redeploys automatically.

## Editing content

Everything lives in `index.html`:

- **Projects** — the `<article class="project">` blocks in `#projects`.
- **Skills** — the `<div class="skill-group">` blocks in `#skills`.
- **Contact** — update the Formspree endpoint in the `<form action="...">` if the
  form owner changes.
