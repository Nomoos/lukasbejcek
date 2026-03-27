# TJ Slavoj Mýto – WordPress web fotbalového klubu

Maturitní práce: WordPress téma a plugin pro fotbalový klub TJ Slavoj Mýto.

**Autor:** Lukáš Bejček
**Technologie:** WordPress 6.x, PHP, Bootstrap 5.3.3 (CDN), vlastní CSS (mobile-first)

## Struktura projektu

```
lukasbejcek/
├── web/                              # Nový web
│   ├── wp-content/
│   │   ├── themes/tj-slavoj-myto/    # WordPress téma
│   │   │   ├── front-page.php        # Úvodní stránka
│   │   │   ├── archive-zapas.php     # Archiv zápasů (filtry, karty)
│   │   │   ├── archive-galerie.php   # Archiv galerií
│   │   │   ├── archive-tym.php       # Archiv týmů
│   │   │   ├── single-zapas.php      # Detail zápasu
│   │   │   ├── single-galerie.php    # Detail alba
│   │   │   ├── single-tym.php        # Detail týmu
│   │   │   ├── single-hrac.php       # Detail hráče
│   │   │   ├── page-kontakty.php     # Kontakty
│   │   │   ├── page-sponzori.php     # Sponzoři
│   │   │   ├── page-historie.php     # Historie klubu
│   │   │   ├── page-aktuality.php    # Aktuality (příspěvky)
│   │   │   ├── functions.php         # Registrace CPT, taxonomií, helper funkce, seed
│   │   │   ├── header.php / footer.php
│   │   │   ├── template-parts/       # Znovupoužitelné části (card-match, hero-team, …)
│   │   │   └── assets/css/main.css   # Vlastní styly
│   │   └── plugins/
│   │       └── slavoj-custom-fields/ # Plugin – meta boxy pro CPT v admin rozhraní
│   └── install.bat                   # Skript pro kopírování do XAMPP
├── original/                         # Původní téma (referenční, neměnit)
├── wordpress/                        # WordPress core
├── docs/                             # Dokumentace (15 kapitol)
├── Design/                           # Grafické návrhy
├── ZADANI-MATURITNI-PRACE.md
├── PRISTUPOVE-UDAJE.md
├── DOKUMENTACE-KOD.md
└── PLAN-PORTOVANI-WORDPRESS.md
```

## Custom Post Types a taxonomie

| CPT | Slug | Šablona |
|-----|------|---------|
| Zápas | `zapas` | archive-zapas.php / single-zapas.php |
| Tým | `tym` | archive-tym.php / single-tym.php |
| Hráč | `hrac` | single-hrac.php |
| Galerie | `galerie` | archive-galerie.php / single-galerie.php |
| Sponzor | `sponzor` | page-sponzori.php |
| Kontakt | `kontakt` | page-kontakty.php |

| Taxonomie | Slug | Použití |
|-----------|------|---------|
| Sezóna | `sezona` | Zápasy, galerie |
| Kategorie týmu | `kategorie-tymu` | Zápasy, týmy, hráči |
| Stav zápasu | `stav-zapasu` | Zápasy |
| Pozice hráče | `pozice-hrace` | Hráči |

## Lokální instalace

1. Nainstalujte [XAMPP](https://www.apachefriends.org/) (PHP 8+, MySQL)
2. Naklonujte repozitář
3. Spusťte `web/install.bat` – zkopíruje téma a plugin do `C:\xampp\htdocs\fotbal_club`
4. V administraci aktivujte téma **TJ Slavoj Mýto** a plugin **Slavoj Custom Fields**
5. Přejděte na **Nástroje → Slavoj nastavení** → „Vytvořit ukázková data (sezóna 2025/26)"

Podrobnosti: [docs/04-lokalni-instalace.md](./docs/04-lokalni-instalace.md)

## Dokumentace

### Hlavní dokument k odevzdání

- **[docs/DOKUMENTACE.md](./docs/DOKUMENTACE.md)** – Kompletní dokumentace maturitní práce (titulní strana, prohlášení, abstrakt, úvod, analýza, implementace, testování, uživatelská příručka, závěr, seznam literatury dle ČSN ISO 690:2022)
- **[docs/generate-pdf.sh](./docs/generate-pdf.sh)** – Skript pro generování PDF z dokumentace (vyžaduje pandoc + LaTeX)

### Přístupové údaje a zadání

- [PRISTUPOVE-UDAJE.md](./PRISTUPOVE-UDAJE.md) – Přístupové údaje k testovacím účtům
- [ZADANI-MATURITNI-PRACE.md](./ZADANI-MATURITNI-PRACE.md) – Zadání maturitní práce

### Dílčí dokumentace ([docs/k-odevzdani/](./docs/k-odevzdani/))

| Soubor | Téma |
|--------|------|
| [01-uvod.md](./docs/k-odevzdani/01-uvod.md) | Úvod, technologie, prostředí |
| [08-datovy-model.md](./docs/k-odevzdani/08-datovy-model.md) | Datový model, ER diagram |
| [13-typy-obsahu.md](./docs/k-odevzdani/13-typy-obsahu.md) | Přehled typů obsahu (CPT, taxonomie) |
| [15-souhrnna-dokumentace.md](./docs/k-odevzdani/15-souhrnna-dokumentace.md) | Souhrnná technická dokumentace |
| [16-navod-administrace.md](./docs/k-odevzdani/16-navod-administrace.md) | Návod na použití administrace |
| [navod-web.md](./docs/k-odevzdani/navod-web.md) | Návod k použití webu |

### Pracovní dokumentace ([docs/pracovni/](./docs/pracovni/))

Průběžná dokumentace vzniklá během vývoje – referenční materiál.

| Soubor | Téma |
|--------|------|
| [02-analyza-original.md](./docs/pracovni/02-analyza-original.md) | Analýza původního kódu |
| [03-nova-struktura.md](./docs/pracovni/03-nova-struktura.md) | Adresářová struktura, URL mapování |
| [04-lokalni-instalace.md](./docs/pracovni/04-lokalni-instalace.md) | Lokální instalace (XAMPP) |
| [05-deployment-ftp.md](./docs/pracovni/05-deployment-ftp.md) | Deployment na hosting (FTP) |
| [06–14](./docs/pracovni/) | Inventář obsahu, pluginy, data model, review, šablony, JS, admin UX |

### Další dokumenty

- [DOKUMENTACE-KOD.md](./DOKUMENTACE-KOD.md) – Vysvětlení původního kódu
- [PLAN-PORTOVANI-WORDPRESS.md](./PLAN-PORTOVANI-WORDPRESS.md) – Plán migrace do WordPress

## Stav projektu

### Hotovo
- 6 custom post types s meta boxy v admin rozhraní
- 4 taxonomie (sezóna, kategorie týmu, stav zápasu, pozice hráče)
- Šablony: homepage, zápasy (filtry + karty + stránkování), galerie, týmy, kontakty, sponzoři, historie
- Seed dat přes admin rozhraní (Nástroje → Slavoj nastavení)
- Mobile-first responsivní CSS
- Bootstrap 5 grid a komponenty (bez vlastního JS kde možno)

### Zbývá dokončit
- Testování mobilního zobrazení
- Testování admin formulářů
- Deployment na produkční hosting

---

**Poslední aktualizace:** Březen 2026
