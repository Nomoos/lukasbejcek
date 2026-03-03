# GitHub Copilot Instructions – TJ Slavoj Mýto Football Club Website

This document defines the project requirements and guidelines that GitHub Copilot should follow when assisting with code in this repository.

---

## Copilot Role

You are an assistant for a WordPress graduation thesis project. Your primary goal is to help build and maintain a WordPress website for the football club TJ Slavoj Mýto. JavaScript was covered only briefly during studies, so keep JS usage to an absolute minimum and always prefer native WordPress or PHP solutions first.

---

## Technology Priorities

When suggesting solutions, follow this priority order from highest to lowest:

1. **WordPress core features** – blocks, navigation menus, custom post types, taxonomies, `WP_Query`, hooks and filters
2. **PHP + HTML templates** – `template-parts`, simple and readable markup following the WordPress template hierarchy
3. **CSS** – mobile-first styling, BEM naming or straightforward component classes; Bootstrap 5 utilities preferred
4. **Free plugins** – only from the official WordPress plugin repository; always explain briefly why a plugin is the right choice
5. **Minimal JavaScript** – only for small UI interactions like toggling a mobile menu; vanilla JS only, no frameworks or bundlers

---

## Project Overview

**Project:** Graduation thesis (Maturitní práce)  
**Author:** Lukáš Bejček  
**Field of study:** Information Technology  
**Topic:** Web presentation of a football club with a match database and content management in WordPress

The goal is to build a modern informational website for the football club **TJ Slavoj Mýto** that clearly displays matches, teams, club history, partners, and current news. The site is built on a custom graphic design created in Figma and deployed on WordPress using a custom Bootstrap 5 theme. The site is prepared for content management by club leadership or a designated administrator.

Full assignment specification (in Czech): [`ZADANI-MATURITNI-PRACE.md`](../ZADANI-MATURITNI-PRACE.md)

---

## Technology Stack

- **CMS:** WordPress
- **Theme:** Custom PHP theme based on Bootstrap 5
- **Key template files:** `header.php`, `footer.php`, `single.php`, `archive.php`, `functions.php`, `style.css`
- **Design tool:** Figma (desktop and mobile variants)
- **Local development:** wp-env (see `.wp-env.json`)

---

## Custom Post Types

### Match (`zapas`)
Fields: date, time, team, opponent, result, goal scorers, season, match status  
Suggested plugins: Custom Post Type UI, Advanced Custom Fields (ACF), Pods

### Team (`tym`)
Fields: name, season, coach, assistants, player list  
Suggested plugins: Custom Post Type UI, Advanced Custom Fields (ACF)

---

## Taxonomies

- **Season** (`sezona`) – used for both Matches and Teams (e.g. `2024/2025`)
- **Team category** (`kategorie-tymu`) – e.g. A-team, B-team, youth
- **Match status** (`stav-zapasu`) – upcoming, played, cancelled

---

## Key Features to Implement

1. **Figma design** – Desktop and mobile variants for all pages, including plugin/component visual styles that match the overall design.

2. **WordPress Bootstrap 5 theme** – Custom PHP template files deployed in WordPress.

3. **Matches & Teams as custom post types** – see above.

4. **Filtering by season or team**  
   - Filter matches and teams by season, status, or category  
   - Suggested plugins: FacetWP, Search & Filter Pro

5. **Calendar module**  
   - Visual calendar showing upcoming and past matches  
   - Filterable by team, category, or season  
   - Suggested plugins: The Events Calendar, Event Organiser, Simple Calendar

6. **Gallery**  
   - Grouped by name or year  
   - Suggested plugins: Modula, Envira Gallery, NextGEN Gallery

7. **Performance optimisation**  
   - Caching (WP Super Cache, LiteSpeed Cache)
   - Lazy loading of images and external content
   - CSS/JS minification and combination
   - Image compression and WebP format
   - Suggested plugins: Smush, Autoptimize, Asset CleanUp

8. **SEO & Analytics**  
   - Suggested plugins: Yoast SEO, Rank Math, Google Analytics integration

9. **Hosting & Security**  
   - HTTPS certificate, attack protection, and backups

10. **User roles & permissions**  
    - Administrator, Contributor – different permissions for content management  
    - Suggested plugins: User Role Editor, Members

---

## Deliverables

- Detailed documentation of all plugins (functionality, configuration, integration into the theme, visual customisation)
- WordPress theme source code with descriptions of all modifications
- Functional website (hosted or as a local project with setup instructions and all access credentials)
- Documentation length: 15–50 pages, electronic format

---

## Coding Guidelines for Copilot

- Follow WordPress coding standards for PHP, CSS, and JavaScript.
- Use Bootstrap 5 utility classes and components where possible; avoid writing custom CSS for things Bootstrap already handles.
- All custom post types and taxonomies should be registered in `functions.php` using `register_post_type()` and `register_taxonomy()`.
- Use `WP_Query` for database queries; avoid raw SQL unless necessary.
- All ACF field groups should be registered programmatically (via PHP) for version control compatibility.
- Template files should be kept thin — move logic into functions or template-parts.
- Ensure all public-facing templates are accessible and responsive (mobile-first).
- Prefix all custom functions, hooks, and CSS classes with `tjsm_` to avoid conflicts.
- Comment non-obvious logic and document each template file's purpose at the top.
- Use `wp_enqueue_scripts` for all CSS and JS assets; never hardcode `<link>` or `<script>` tags in templates.

### Additional Rules

- **Keep JavaScript minimal.** If JS is truly required, keep it under 30–50 lines, use plain vanilla JS, and avoid any build tools or frameworks.
- **Don't reinvent the wheel.** If something already exists as a free plugin (forms, SEO, caching, galleries, sliders, cookie banners, analytics, CPT UI…), recommend that plugin and briefly explain why it fits rather than writing a custom solution.
- **Prefer built-in WordPress components** – `wp_nav_menu()`, `the_content()`, the template hierarchy, and Gutenberg blocks should be the first option considered.
- **Write secure code** – use `sanitize_*` functions for all user inputs, `esc_*` functions for all outputs, nonces on any forms, and never use raw SQL queries when `WP_Query` can do the job.
- **No paid plugins or services.** Only free, openly available solutions.
- **Accessibility and mobile-first are non-negotiable.** Every output must be responsive and include appropriate ARIA attributes (`aria-label`, focus management, sufficient colour contrast).
- **No inline CSS or JS.** All styles and scripts must be enqueued via `wp_enqueue_style()` / `wp_enqueue_scripts()`.

---

## Output Style

When suggesting a solution, follow this order:

1. **Start with the simplest WordPress-native solution** (no JavaScript). Use core features, template parts, and WP_Query first.
2. **Only if a pure WordPress/PHP solution is not possible**, propose the minimal JavaScript necessary (vanilla, ≤ 50 lines).
3. **Always state clearly** whether the recommendation is a free plugin (name + one-line reason) or a small custom code snippet, so the developer knows what to install or write.
