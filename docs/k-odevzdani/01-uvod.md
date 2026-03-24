# 01 – Úvod a přehled projektu

## O projektu

Cílem bylo přenést web fotbalového klubu **TJ Slavoj Mýto** ze stávajícího, nedokončeného WordPress tématu (složka `original/`) do plně funkčního a spravovatelného WordPress webu.

**Stav k březnu 2026:** Projekt je funkční a nasazený lokálně (XAMPP).

## Technologie

| Vrstva | Technologie |
|--------|-------------|
| CMS | WordPress 6.x |
| Backend | PHP 8.x |
| Frontend | Bootstrap 5.3.3 (CDN), vlastní CSS (mobile-first) |
| JavaScript | Minimální — pouze tam kde Bootstrap nebo CSS nestačí |
| Verzování | Git → GitHub (Nomoos/lukasbejcek) |

## Adresářová struktura

```
lukasbejcek/
├── web/                              # WordPress instalace
│   └── wp-content/
│       └── themes/tj-slavoj-myto/   # ← aktivní téma
├── original/                         # Původní téma (referenční, neměnit)
├── docs/                             # Dokumentace
└── notes/                            # Pracovní poznámky
```

## Klíčová rozhodnutí

- **Minimální JS** — filtry fungují jako HTML `<form method="get">`, bez AJAXu
- **Bootstrap 5 grid** — responzivní layout bez vlastního CSS frameworku
- **CPT místo custom tabulek** — veškerý obsah (zápasy, týmy, hráči) v nativních WP post types
- **Žádné page templates pro CPT URL** — `/zapasy/` a `/tymy/` obsluhují `archive-*.php`, ne `page-*.php`

## Lokální prostředí

- XAMPP: `C:\xampp\htdocs\fotbal_club`
- URL: `http://localhost/fotbal_club`
- Admin: `http://localhost/fotbal_club/wp-admin`

---

*Aktualizováno: březen 2026*
