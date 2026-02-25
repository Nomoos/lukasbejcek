# 02 – Analýza původního kódu (`original/`)

## Soubory v `original/`

| Soubor | Typ | Popis |
|--------|-----|-------|
| `header.php` | WordPress šablona | Hlavička stránky, navigace |
| `footer.php` | WordPress šablona | Patička stránky |
| `index.php` | WordPress šablona | Hlavní šablona / homepage |
| `function.php` | WordPress funkce | Registrace menu, skriptů a stylů |
| `style.css` | CSS | Styly tématu (meta + custom CSS) |
| `page-galerie.php` | Page template | Galerie fotek |
| `page-historie.php` | Page template | Historie klubu |
| `page-kontakty.php` | Page template | Kontaktní informace |
| `page-sponzori.php` | Page template | Přehled sponzorů |
| `page-tymy.php` | Page template | Přehled týmů |
| `page-zapasy.php` | Page template | Přehled zápasů |
| `single-galerie.php` | Single template | Detail galerie |
| `html/` | Složka | Původní statické HTML soubory (referenční) |

## Identifikované problémy

1. **Hardcoded obsah** – data jsou přímo v PHP, nelze měnit přes admin.
2. **Nefunkční filtry** – JS filtry na stránce zápasů a týmů jsou nefunkční.
3. **Chybí custom post types** – žádné CPT pro Zápasy, Hráče, Galerie apod.
4. **Nekonzistentní WP_Query** – některé stránky používají WP_Query, jiné ne.
5. **Responsivita** – není plně otestována na mobilních zařízeních.
6. **Chybí ACF/meta pole** – žádná správa pokročilých metadat.

## Co lze přenést

- Strukturu HTML šablon (header, footer, navigace).
- Bootstrap 5 styly a layout.
- Logiku WP_Query tam, kde je použita.
- Statické HTML soubory z `html/` jako referenci pro obsah a design.

---

*Vytvořeno: únor 2026*
