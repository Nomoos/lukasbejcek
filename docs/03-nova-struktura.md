# 03 – Nová struktura projektu

## Adresářová struktura repozitáře

```
lukasbejcek/
├── original/               # Původní WordPress téma (referenční, neměnit)
├── wordpress/              # WordPress core soubory
├── web/                    # 🆕 Nový web – sem portujeme téma
│   ├── theme/              # WordPress téma (PHP, CSS, JS)
│   ├── plugins/            # Vlastní pluginy
│   └── assets/             # Statické soubory (obrázky, fonty)
├── docs/                   # 🆕 Průběžná dokumentace portování
├── notes/                  # 🆕 Pracovní poznámky
├── README.md               # Přehled projektu
├── DOKUMENTACE-KOD.md      # Dokumentace původního kódu
└── PLAN-PORTOVANI-WORDPRESS.md  # Plán portování
```

## Struktura WordPress tématu (`web/theme/`)

Plánovaná struktura po dokončení portování:

```
web/theme/
├── style.css               # Hlavní styly + meta tématu
├── functions.php           # Registrace CPT, taxonomií, menu, skriptů
├── index.php               # Fallback šablona
├── header.php              # Hlavička
├── footer.php              # Patička
├── front-page.php          # Homepage
├── archive.php             # Archivní stránky
├── single.php              # Detail příspěvku
├── page.php                # Obecná stránka
├── templates/              # Page templates
│   ├── page-zapasy.php
│   ├── page-tymy.php
│   ├── page-galerie.php
│   ├── page-historie.php
│   ├── page-kontakty.php
│   └── page-sponzori.php
├── inc/                    # Pomocné PHP soubory
│   ├── custom-post-types.php
│   ├── taxonomies.php
│   └── enqueue.php
└── assets/
    ├── css/
    ├── js/
    └── images/
```

## Custom Post Types (plán)

| CPT | Slug | Popis |
|-----|------|-------|
| Zápasy | `zapas` | Fotbalové zápasy s výsledky |
| Týmy | `tym` | Přehled týmů klubu |
| Hráči | `hrac` | Hráčské profily |
| Galerie | `galerie` | Fotogalerie |
| Sponzoři | `sponzor` | Partneři a sponzoři |

---

*Vytvořeno: únor 2026*
