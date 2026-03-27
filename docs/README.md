# docs/ – Dokumentace projektu TJ Slavoj Mýto

## Hlavní dokument k odevzdání

| Soubor | Popis |
|--------|-------|
| **[DOKUMENTACE.md](./DOKUMENTACE.md)** | **Kompletní dokumentace maturitní práce** (titulní strana, prohlášení, abstrakt, úvod, analýza, implementace, testování, uživatelská příručka, závěr, seznam literatury dle ČSN ISO 690:2022) |
| [generate-pdf.sh](./generate-pdf.sh) | Skript pro vygenerování PDF z dokumentace (vyžaduje pandoc + LaTeX) |

Dokumentace je dále rozdělena do dvou složek podle účelu.

## K odevzdání (`k-odevzdani/`)

Dokumenty popisující aktuální stav projektu a návody k použití. Součást maturitní práce dle [zadání](../ZADANI-MATURITNI-PRACE.md), bod 11 – Odevzdávané výstupy.

| Soubor | Popis |
|--------|-------|
| [01-uvod.md](./k-odevzdani/01-uvod.md) | Úvod, technologie, prostředí |
| [07-pluginy.md](./k-odevzdani/07-pluginy.md) | Dokumentace pluginů a vlastních řešení |
| [08-datovy-model.md](./k-odevzdani/08-datovy-model.md) | Datový model – entity, atributy, ER diagram |
| [13-typy-obsahu.md](./k-odevzdani/13-typy-obsahu.md) | Přehled typů obsahu – CPT, taxonomie, nativní WP |
| [15-souhrnna-dokumentace.md](./k-odevzdani/15-souhrnna-dokumentace.md) | Souhrnná technická dokumentace projektu |
| [16-navod-administrace.md](./k-odevzdani/16-navod-administrace.md) | Návod na použití administrace WordPress |
| [navod-web.md](./k-odevzdani/navod-web.md) | Návod k použití webu pro návštěvníky |

## Pracovní dokumentace (`pracovni/`)

Průběžná dokumentace vzniklá během vývoje. Slouží jako referenční materiál.

| Soubor | Popis |
|--------|-------|
| [02-analyza-original.md](./pracovni/02-analyza-original.md) | Analýza původního kódu ve složce `original/` |
| [03-nova-struktura.md](./pracovni/03-nova-struktura.md) | Adresářová struktura, mapování URL → šablona |
| [04-lokalni-instalace.md](./pracovni/04-lokalni-instalace.md) | Jak rozchodit projekt lokálně (XAMPP) |
| [05-deployment-ftp.md](./pracovni/05-deployment-ftp.md) | Deployment na hosting přes FTP |
| [06-inventar-obsahu.md](./pracovni/06-inventar-obsahu.md) | Inventář CPT s poli a taxonomiemi |
| [09-ukazková-data.md](./pracovni/09-ukazková-data.md) | Ukázková data a seed |
| [10-review-kod.md](./pracovni/10-review-kod.md) | Review kódu – problémy, opravy |
| [11-wordpress-sablony.md](./pracovni/11-wordpress-sablony.md) | CPT archive vs Page template |
| [12-javascript.md](./pracovni/12-javascript.md) | Inventář JS v projektu |
| [14-admin-ux-a-razeni-taxonomii.md](./pracovni/14-admin-ux-a-razeni-taxonomii.md) | Admin UX, řazení taxonomií |
