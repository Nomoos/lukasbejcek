# 03 – Struktura projektu

## Adresářová struktura repozitáře

```
lukasbejcek/
├── web/                                      # WordPress instalace
│   └── wp-content/
│       └── themes/tj-slavoj-myto/           # Aktivní téma
│           ├── style.css                     # Meta tématu + globální styly
│           ├── functions.php                 # CPT, taxonomie, helpery, admin nástroje
│           ├── front-page.php                # Homepage
│           ├── header.php / footer.php       # Hlavička a patička
│           ├── archive-zapas.php             # Seznam zápasů (/zapasy/)
│           ├── archive-tym.php               # Seznam týmů (/tymy/)
│           ├── single-zapas.php              # Detail zápasu
│           ├── single-tym.php                # Detail týmu
│           ├── single-hrac.php               # Detail hráče
│           ├── single-galerie.php            # Detail alba s lightboxem
│           ├── page-galerie.php              # Přehled galerií
│           ├── page-sponzori.php             # Sponzoři
│           ├── page-historie.php             # Historie klubu
│           ├── page-aktuality.php            # Aktuality
│           ├── archive.php / single.php      # Fallback šablony
│           ├── template-parts/
│           │   ├── card-match.php            # Karta zápasu (fotbalový styl)
│           │   └── hero-team.php             # Hero sekce týmu
│           └── img/                          # Obrázky (logo, banner)
├── original/                                 # Původní téma (referenční, neměnit)
├── docs/                                     # Dokumentace
└── notes/                                    # Pracovní poznámky
```

## Mapování URL → šablona

| URL | Soubor | Typ |
|-----|--------|-----|
| `/` | `front-page.php` | Page template |
| `/zapasy/` | `archive-zapas.php` | CPT archive |
| `/zapasy/{slug}/` | `single-zapas.php` | CPT single |
| `/tymy/` | `archive-tym.php` | CPT archive |
| `/tymy/{slug}/` | `single-tym.php` | CPT single |
| `/galerie/` | `page-galerie.php` | Page template |
| `/galerie/{slug}/` | `single-galerie.php` | CPT single |
| `/sponzori/` | `page-sponzori.php` | Page template |
| `/historie/` | `page-historie.php` | Page template |

Viz také `11-wordpress-sablony.md` pro vysvětlení rozdílu CPT archive vs page template.

## Custom Post Types

| CPT slug | Název | Archiv | Taxonomie |
|----------|-------|--------|-----------|
| `zapas` | Zápasy | `/zapasy/` | kategorie-tymu, sezona, stav-zapasu |
| `tym` | Týmy | `/tymy/` | kategorie-tymu, sezona |
| `hrac` | Hráči | ne | kategorie-tymu, pozice-hrace |
| `galerie` | Galerie | ne | — |
| `sponzor` | Sponzoři | ne | — |
| `kontakt` | Kontakty | ne | — |

## Helper funkce v functions.php

| Funkce | Popis |
|--------|-------|
| `slavoj_kategorie_poradi()` | Kanonické pořadí kategorií (Muži A → Miniprípravka) |
| `slavoj_sort_tymy($terms)` | Seřadí WP_Term[] dle kanonického pořadí |
| `slavoj_get_latest_sezona_slug()` | Slug nejnovější sezóny (orderby name DESC) |
| `slavoj_is_club_team($nazev)` | True pokud je tým TJ Slavoj / TJ Mýto |
| `slavoj_zapas_vysledek($d, $h, $s)` | Vrátí `vyhral`/`prohral`/`remiza`/`''` |
| `slavoj_get_team_display_name($slug)` | Název kategorie dle slugu |
| `slavoj_vypis_hrace_tymu($slug)` | Vypíše soupisku hráčů daného týmu |

---

*Aktualizováno: březen 2026*
