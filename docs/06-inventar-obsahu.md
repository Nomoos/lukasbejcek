# 06 – Inventář typů obsahu (Content Types)

Tento dokument je výstupem **Fáze 1 – Analýza a příprava** (krok 1.1).  
Obsahuje úplný seznam všech implementovaných typů obsahu, jejich skutečné meta klíče a taxonomie.

> **Stav:** Aktualizováno po implementaci – všechny CPT a pole jsou funkční v `functions.php` tématu a v pluginu `slavoj-custom-fields`.

---

## Implementované typy obsahu

### 1. Zápasy (`zapas`)

**Archivní URL:** `/zapasy/`  
**Šablona archivu:** `archive-zapas.php`  
**Šablona detailu:** `single-zapas.php`

| Pole | Meta klíč | Typ | Popis |
|------|-----------|-----|-------|
| Domácí tým | `domaci` | text | Název domácího týmu (např. „TJ Slavoj Mýto") |
| Hostující tým | `hoste` | text | Název hostujícího týmu |
| Skóre | `skore` | text | Výsledek ve formátu „3:1"; NULL = VS |
| Datum zápasu | `datum_zapasu` | date (Y-m-d) | Datum konání zápasu – slouží i pro filtrování |
| Čas výkopu | `cas_zapasu` | time (HH:MM) | Čas zahájení zápasu |
| Střelci | `strelci` | text | Střelci gólů, např. „2× Novák, Bejček" |
| Kategorie týmu | taxonomie `kategorie-tymu` | taxonomie | Muži A, Muži B, Dorost, Starší žáci… |
| Sezóna | taxonomie `sezona` | taxonomie | 2024/25, 2025/26… |
| Stav zápasu | taxonomie `stav-zapasu` | taxonomie | Nadcházející, Odehraný, Zrušený |

**Správa z frontendu:** Na stránce detailu zápasu (`single-zapas.php`) mohou přihlášení správci obsahu přímo zapsat skóre a střelce pomocí inline formuláře. Hráče lze přidávat rychlým výběrem ze soupisky týmu.

---

### 2. Týmy (`tym`)

**Archivní URL:** `/tymy/`  
**Šablona archivu:** `archive-tym.php`  
**Šablona detailu:** `single-tym.php`

| Pole | Meta klíč | Typ | Popis |
|------|-----------|-----|-------|
| Název týmu | *(titulek příspěvku)* | title | Automaticky jako název příspěvku WordPress |
| Identifikátor (slug) | `tym_slug` | text | Slug pro propojení s hráči (např. `muzi-a`) |
| Počet hráčů | `pocet_hracu` | number | Celkový počet hráčů v soupisku |
| Hlavní trenér | `hlavni_trener` | text | Jméno hlavního trenéra |
| Asistent trenéra | `asistent_trenera` | text | Jméno asistenta trenéra |
| Zdravotník | `zdravotnik` | text | Jméno zdravotníka |
| Logo týmu | *(obrázek příspěvku)* | image | Přes funkci Obrázek příspěvku |
| Popis | *(obsah příspěvku)* | editor | Plný WordPress editor |
| Kategorie | taxonomie `kategorie-tymu` | taxonomie | Muži A, Muži B, Dorost, Starší žáci, Mladší žáci, Přípravka |
| Sezóna | taxonomie `sezona` | taxonomie | 2024/25, 2025/26… |

---

### 3. Hráči (`hrac`)

**Šablony:** hráči nemají vlastní archivní stránku; jsou zobrazeni v soupisce týmu přes funkci `slavoj_vypis_hrace_tymu()`.

| Pole | Meta klíč | Typ | Popis |
|------|-----------|-----|-------|
| Jméno | *(titulek příspěvku)* | title | Celé jméno hráče |
| Číslo dresu | `cislo` | number (1–99) | Číslo na dresu; slouží pro řazení v soupisku |
| Rok narození | `rok_narozeni` | number | Rok narození (např. 1995) |
| Slug týmu | `tym_slug` | text | Musí odpovídat hodnotě `tym_slug` příslušného týmu |
| Pozice | taxonomie `pozice-hrace` | taxonomie | Brankáři, Hráči v poli |
| Kategorie týmu | taxonomie `kategorie-tymu` | taxonomie | Muži A, Muži B, Dorost… |
| Sezóna | taxonomie `sezona` | taxonomie | 2024/25, 2025/26… |

---

### 4. Galerie (`galerie`)

**Archivní URL:** `/galerie/`  
**Šablona archivu:** `page-galerie.php` (page template) nebo `archive-galerie.php`  
**Šablona detailu:** `single-galerie.php`

| Pole | Meta klíč | Typ | Popis |
|------|-----------|-----|-------|
| Název alba | *(titulek příspěvku)* | title | Název fotoalba nebo události |
| Náhledová fotografie | *(obrázek příspěvku)* | image | Přes funkci Obrázek příspěvku |
| Tým (popis) | `tym` | text | Textový popis týmu (záložní pole) |
| Sezóna | taxonomie `sezona` | taxonomie | 2024/25, 2025/26… |
| Kategorie týmu | taxonomie `kategorie-tymu` | taxonomie | Muži A, Muži B, Dorost… |
| Sezóna | taxonomie `sezona` | taxonomie | Pro filtrování galerie |
| Fotografie v albu | *(obsah příspěvku)* | gallery block | Vkládání fotek přes WordPress editor nebo ACF |

---

### 5. Kontakty (`kontakt`)

**Šablona:** `page-kontakty.php` (page template)

| Pole | Meta klíč | Typ | Popis |
|------|-----------|-----|-------|
| Jméno | *(titulek příspěvku)* | title | Celé jméno člena výboru |
| Fotografie | *(obrázek příspěvku)* | image | Přes funkci Obrázek příspěvku |
| Funkce / Pozice | `pozice` | text | Např. „Předseda klubu" |
| Telefon | `telefon` | text | Telefonní číslo |
| E-mail | `email` | email | E-mailová adresa |
| Pořadí zobrazení | `poradi` | number | Číslo pro řazení na stránce (nižší = výše) |

---

### 6. Sponzoři (`sponzor`)

**Šablona:** `page-sponzori.php` (page template)

| Pole | Meta klíč | Typ | Popis |
|------|-----------|-----|-------|
| Název sponzora | *(titulek příspěvku)* | title | Název firmy / partnera |
| Logo | *(obrázek příspěvku)* | image | Přes funkci Obrázek příspěvku |
| Webové stránky | `web_sponzora` | url | Odkaz na web sponzora (klikatelný) |

---

## Taxonomie (implementované)

| Taxonomie | Slug | Výchozí hodnoty | Používá se v |
|-----------|------|-----------------|--------------|
| Kategorie týmu | `kategorie-tymu` | muzi-a, muzi-b, dorost, starsi-zaci, mladsi-zaci, pripravka | zapas, tym, hrac, galerie |
| Sezóna | `sezona` | 2025/26, 2024/25, 2023/24 | zapas, tym, hrac, galerie |
| Stav zápasu | `stav-zapasu` | nadchazejici, odehrany, zruseny | zapas |
| Pozice hráče | `pozice-hrace` | brankari, hraci-v-poli | hrac |

> Výchozí hodnoty taxonomií jsou automaticky vytvořeny při aktivaci pluginu **Slavoj Custom Fields** nebo ručně přes **Nástroje → Slavoj nastavení**.

---

## Závislosti a propojení

```
zapas  →  taxonomie: kategorie-tymu, sezona, stav-zapasu
          meta: datum_zapasu, cas_zapasu, domaci, hoste, skore, strelci, misto_konani

tym    →  taxonomie: kategorie-tymu, sezona
          meta: tym_slug, pocet_hracu, hlavni_trener, asistent_trenera, zdravotnik

hrac   →  taxonomie: kategorie-tymu, sezona, pozice-hrace
          meta: cislo, rok_narozeni, tym_slug
          propojení: tym_slug musí odpovídat hodnotě tym_slug v příslušném záznamu CPT tym

galerie → taxonomie: kategorie-tymu, sezona
          meta: tym, sezona

kontakt → meta: pozice, telefon, email, poradi

sponzor → meta: web_sponzora
```

---

*Vytvořeno: únor 2026 – Fáze 1 (krok 1.1)*  
*Aktualizováno: únor 2026 – po implementaci CPT, taxonomií a meta boxů*

