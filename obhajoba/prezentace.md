---
marp: true
theme: default
size: 16:9
paginate: true
backgroundColor: '#ffffff'
color: '#1a1a1a'
style: |
  section {
    font-family: 'Segoe UI', 'Helvetica', sans-serif;
    font-size: 26px;
    padding: 50px 70px;
  }
  h1 {
    color: #003366;
    font-size: 42px;
    border-bottom: 3px solid #003366;
    padding-bottom: 10px;
    margin-bottom: 25px;
  }
  h2 {
    color: #003366;
    font-size: 32px;
  }
  table {
    font-size: 22px;
    border-collapse: collapse;
    margin: 10px 0;
  }
  th {
    background-color: #003366;
    color: #ffffff;
    padding: 8px 14px;
    text-align: left;
  }
  td {
    padding: 6px 14px;
    border-bottom: 1px solid #cccccc;
  }
  code {
    background-color: #f4f4f4;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 22px;
  }
  .small { font-size: 20px; }
  .center { text-align: center; }
  .lead h1 { font-size: 50px; border: none; }
  .lead { text-align: center; }
  footer { color: #888; font-size: 14px; }
---

<!-- _class: lead -->

# Webová prezentace fotbalového klubu

## s databází zápasů a správou obsahu ve WordPressu

<br>

**Autor:** Lukáš Bejček
**Obor:** Informační technologie · **Rok:** 2026

<br>

**Vedoucí:** Mgr. Jaromír Háka · **Oponent:** Mgr. Miloslav Bělský

<!-- 40 stran dokumentace · 0 % shoda s ostatními zdroji · web nasazený na produkčním hostingu -->

---

# Splnění zadání

| # | Bod zadání | Stav |
|---|------------|------|
| 1 | Návrh ve Figmě (desktop + mobil) | ✅ |
| 2 | Vlastní šablona v Bootstrapu 5 | ✅ |
| 3 | CPT zápas, tým, hráč | ✅ + 3 navíc |
| 4 | Filtrování dle sezóny / týmu | ✅ GET parametry |
| **5** | **Kalendářový modul** | **🔄 funkční ekvivalent: banner + filtry** |
| 6 | Galerie s lightboxem | ✅ |
| 7 | Optimalizace (cache, lazy, WebP) | ⚠️ částečně |
| 8 | SEO a analytika | ⚠️ plán |
| 9 | Hosting + HTTPS | ✅ |
| 10 | Uživatelské role | ✅ vlastní RBAC |

---

# Vlastní implementace — optimalizovaná výseč pluginů

**Dva deployment artefakty:** šablona `tj-slavoj-myto` + vlastní plugin `slavoj-custom-fields`

| Doporučený plugin | Portovaná optimalizovaná výseč |
|-------------------|-------------------------------|
| Advanced Custom Fields | `register_meta` + jen potřebné meta boxy |
| CPT UI | `register_post_type`, bez UI builderu |
| FacetWP | `WP_Query` + GET, jen použité filtry |
| User Role Editor | `add_role` + `current_user_can`, reálné role klubu |

Místo plné integrace **portováno jen to, co projekt potřebuje** — bez UI builderu, balastu lokalizací a advanced fields bez uplatnění.

**Tři důvody:** porozumění WordPress API · nezávislost na breaking changes (ACF 5 → 6 mělo breaking changes v meta API; `register_post_type` je stabilní 10+ let) · obhajitelnost každého řádku kódu

---

# Datový model

<div class="small">

**6 vlastních typů obsahu:**

| CPT | Popis |
|-----|-------|
| `zapas` | jednotlivé zápasy |
| `tym` | týmy klubu |
| `hrac` | hráči |
| `galerie` | fotoalba |
| `sponzor` | partneři klubu |
| `kontakt` | kontaktní osoby |

**4 taxonomie (sdílené):** `sezona` · `kategorie-tymu` · `stav-zapasu` · `pozice-hrace`

</div>

**Sdílení taxonomií:** sezóna a kategorie napříč zápasy, týmy, galerií. Konzistenci dat by zajistila validační vrstva v `save_post` hooku.

---

# Galerie a ostatní stránky

<div class="small">

- **Galerie** — alba taxonomií sezóna, **nativní HTML `<dialog>` element** pro lightbox (žádný externí plugin)
- **Sponzoři** — vlastní CPT, admin si je sám spravuje
- **Kontakty** — strukturovaný CPT s rolemi v klubu
- **Banner na homepage** — nadcházející zápasy ve vizuálních kartách

</div>

> Web je nasazený na produkčním hostingu. V rámci dotazů ho otevřu živě.

<!-- Sem doplnit screenshoty: galerie grid, lightbox modal, sponzoři, mobilní pohled -->

---

# Dokumentace

<div class="center">

## 40 stran · 0 % shoda · „vynikající"

</div>

<br>

- Analýza původního řešení
- Srovnávací tabulky (pluginy vs. vlastní implementace)
- **ER diagram** datového modelu
- Popis nasazení obou artefaktů (šablona + plugin)
- Testovací scénáře
- Příručka administrátora

---

# Slabá místa a rozšíření

<div class="small">

| Téma | Postoj | Plán |
|------|--------|------|
| **Lazy loading na LCP banneru** | uznávám | výjimka pro hero, nebo nativní `wp_get_attachment_image` (WP 5.5+) |
| **AJAX filtry** | vědomá volba (SEO URL) | `wp_ajax_filter_zapasy` + History API |
| **Cache plugin** | uznávám | LiteSpeed Cache |
| **SEO plugin** | uznávám — jen `alt` atributy a meta tagy v kódu | Yoast / Rank Math |
| **GA4 (analytika)** | plánováno, časově nestihnuto | `wp_head` hook nebo Site Kit |
| **Plnohodnotný kalendář** | aktuální řešení dostačující | FullCalendar.js pouze při rozšíření na další typy událostí |

</div>

---

<!-- _class: lead -->

# Děkuji za pozornost

<br>

✅ **6 CPT, vlastní plugin**
✅ **banner + filtry (kalendář)**
✅ **chvalitebně z obou posudků**

<br>

**Web mám otevřený v prohlížeči pro případné dotazy.**
