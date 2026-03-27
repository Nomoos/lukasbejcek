# Plán portování webu do WordPress – TJ Slavoj Mýto

## Přehled projektu

Migrace webu fotbalového klubu TJ Slavoj Mýto ze statických HTML stránek do plně funkčního WordPress tématu.
Cíl: dynamická správa zápasů, týmů, hráčů a galerií přes WordPress admin bez znalosti kódu.

---

## Aktuální stav (březen 2026)

| Oblast | Stav |
|--------|------|
| Struktura tématu (header, footer, style.css) | ✅ |
| Custom Post Types (CPT) | ✅ |
| Taxonomie (sezóna, kategorie, stav...) | ✅ |
| Šablony stránek (zapasy, tymy, galerie...) | ✅ |
| HTML filtry (výběr týmu, sezóny, stavu) | ✅ |
| Stránkování výsledků | ✅ |
| Bootstrap 5 – responzivní design | ✅ |
| Mobilní menu (Bootstrap hamburger) | ✅ |
| Lightbox pro galerie | ✅ |
| Admin formulář pro zadání skóre | ✅ |
| Plugin slavoj-custom-fields (ukázková data) | ✅ |
| Dokumentace | ✅ průběžně |
| Migrace reálných dat | ⏳ |
| Školení správce webu | ⏳ |

---

## Fáze migrace

### Fáze 1: Analýza a příprava ✅ HOTOVO

- [x] Inventarizace obsahu → `docs/06-inventar-obsahu.md`
- [x] Datový model → `docs/08-datovy-model.md`
- [x] Ukázková data → `docs/09-ukazková-data.md`, `wordpress/seed-data.sql`
- [x] Nastavení lokálního prostředí (wp-env) → `.wp-env.json`
- [x] Git verzování

### Fáze 2: Datová struktura ✅ HOTOVO

Zaregistrováno v `web/wp-content/themes/tj-slavoj-myto/functions.php`:

**Custom Post Types:**
- [x] `zapas` – datum, čas, domácí, hosté, skóre, střelci
- [x] `tym` – trenér, asistent, zdravotník, počet hráčů
- [x] `hrac` – číslo dresu, rok narození, pozice, tým
- [x] `galerie` – název, obrázky, kategorie, sezóna
- [x] `sponzor` – logo, odkaz na web
- [x] `kontakt` – jméno, pozice, telefon, email, pořadí

**Taxonomie:**
- [x] `sezona` – 2024/25, 2025/26 atd.
- [x] `kategorie-tymu` – Muži A, Muži B, Dorost, Žáci...
- [x] `stav-zapasu` – nadcházející, odehraný, zrušený
- [x] `pozice-hrace` – brankář, obránce, záložník, útočník

### Fáze 3: Šablony stránek ✅ HOTOVO

| Šablona | Stav | Popis |
|---------|------|-------|
| `front-page.php` | ✅ | Banner, 4 nejbližší zápasy, aktuality |
| `page-zapasy.php` | ✅ | Filtry, seznam zápasů, stránkování |
| `page-tymy.php` | ✅ | Filtry, info o týmu, soupiska |
| `page-galerie.php` | ✅ | Filtry, mřížka alb |
| `page-kontakty.php` | ✅ | Výbor klubu, mapa |
| `page-sponzori.php` | ✅ | Loga sponzorů s odkazy |
| `page-aktuality.php` | ✅ | Novinky se stránkováním |
| `page-historie.php` | ✅ | Obsah z WP editoru |
| `single-zapas.php` | ✅ | Detail zápasu, admin formulář |
| `single-tym.php` | ✅ | Detail týmu, soupiska, nejbližší zápasy |
| `single-hrac.php` | ✅ | Profil hráče |
| `single-galerie.php` | ✅ | Fotogalerie s lightboxem |
| `archive-zapas.php` | ✅ | Archiv zápasů s filtry |
| `archive-tym.php` | ✅ | Přehled všech týmů |

### Fáze 4: Frontend ✅ HOTOVO

**Filtry:**
- [x] Filtry pomocí HTML formuláře (GET parametry), bez JavaScriptu
- [x] `onchange="this.form.submit()"` – okamžitá odezva po výběru
- [x] `<noscript>` záložní tlačítko pro případ bez JS

**Mobilní zobrazení:**
- [x] Bootstrap 5 grid – `col-12 col-md-6 col-lg-4` atd.
- [x] Hamburger menu přes Bootstrap `data-bs-toggle="collapse"` (bez JS)
- [x] Touch-friendly velikosti tlačítek (min 44×44 px)

**Ostatní:**
- [x] Lightbox pro galerie (vlastní minimální JS ~50 řádků)
- [x] Smooth scroll (`scroll-behavior: smooth` v CSS)
- [x] Stránkování zápasů a aktualit
- [x] Lazy loading obrázků (`loading="lazy"`)

### Fáze 5: Admin rozhraní ⏳ ČÁSTEČNĚ

- [x] Admin formulář pro zadání skóre a střelců přímo ze stránky zápasu
- [x] Admin sloupce v přehledu zápasů (datum, skóre, tým)
- [x] Plugin slavoj-custom-fields – tlačítka pro vložení ukázkových dat
- [ ] Dashboard widget se statistikami (počet zápasů, hráčů...)
- [ ] Upravit pořadí položek v levém menu adminu

### Fáze 6: Migrace dat ⏳ ČEKÁ

- [ ] Vytvoření týmů s reálnými údaji (trenér, hráči)
- [ ] Import soupisek hráčů
- [ ] Zadání zápasů aktuální sezóny
- [ ] Nahrání fotografií do galerií
- [ ] Přidání sponzorů s logy
- [ ] Aktualizace kontaktů výboru klubu
- [ ] Aktualizace textu na stránce Historie

### Fáze 7: Optimalizace ⏳ VOLITELNÉ (pro maturitu dostačuje současný stav)

- [ ] Minifikace CSS (WP plugin nebo build script)
- [ ] WebP formát obrázků
- [ ] Caching plugin (WP Super Cache – zdarma)
- [ ] SEO plugin (Yoast SEO – zdarma)
- [ ] Meta description pro každou stránku

### Fáze 8: Testování ⏳

- [ ] Ověřit filtry na mobilním telefonu (Chrome Android, iOS Safari)
- [ ] Zkontrolovat zobrazení na tabletu (768 px)
- [ ] Projít všechny stránky a ověřit funkčnost
- [ ] Zkontrolovat zobrazení prázdných stavů (žádné výsledky)

### Fáze 9: Nasazení na hosting ⏳

- [ ] Záloha původního webu
- [ ] Upload tématu přes FTP → `web/wp-content/themes/tj-slavoj-myto/`
- [ ] Upload pluginu → `web/wp-content/plugins/slavoj-custom-fields/`
- [ ] Import databáze
- [ ] Aktualizace URL adres (WP admin → Nastavení → Záložní URL)
- [ ] Aktivace tématu

---

## Klíčové technické rozhodnutí

### Filtry bez AJAXu

Filtry (výběr týmu, sezóny, stavu zápasu) fungují přes standardní HTML formulář metodou GET.
Stránka se po výběru znovu načte s novými parametry v URL.

**Výhody pro maturitní projekt:**
- Jednoduché na pochopení a vysvětlení
- Funguje bez JavaScriptu (dostupné pro všechny)
- URL lze sdílet (filtrovaný výsledek má vlastní odkaz)

### Bootstrap 5 jako základ

Web používá Bootstrap 5.3 pro:
- Grid systém (responzivní sloupce)
- Komponenty: navbar, badge, card, table, alert, btn
- Utility třídy: `d-flex`, `gap-3`, `text-center`, `fw-bold`, `mb-4`...

Vlastní CSS styly jsou minimální – pouze tam, kde Bootstrap nestačí (barvy klubu, lightbox, match karty).

### Custom Post Types místo stránek

Každý typ obsahu (zápas, tým, hráč...) je samostatný CPT. To umožňuje:
- Správu obsahu přes WordPress admin (bez editace kódu)
- Filtrování pomocí taxonomií (kategorie, sezóna)
- Propojení mezi CPT (hráč → tým, zápas → tým)

---

## Adresářová struktura

```
web/
└── wp-content/
    ├── themes/
    │   └── tj-slavoj-myto/               ← vlastní téma
    │       ├── functions.php              ← CPT, taxonomie, helpery
    │       ├── front-page.php             ← úvodní stránka
    │       ├── page-*.php                 ← šablony stránek
    │       ├── single-*.php               ← šablony detailů
    │       ├── archive-*.php              ← archivy
    │       ├── template-parts/            ← znovupoužitelné části
    │       └── assets/css/                ← vlastní CSS (main + komponenty)
    └── plugins/
        └── slavoj-custom-fields/          ← plugin pro ukázková data
```
