# 07 – Dokumentace pluginů a vlastních řešení

Tento dokument popisuje všechny použité pluginy a vlastní kód, který plní jejich funkce na webu TJ Slavoj Mýto. Každé řešení je popsáno z hlediska funkce, konfigurace, způsobu integrace a vizuálního přizpůsobení.

---

## Přehled přístupu

Zadání maturitní práce (body 3–6, 10) doporučuje řadu externích pluginů (Custom Post Type UI, Advanced Custom Fields, FacetWP, The Events Calendar, Modula, User Role Editor aj.). Po analýze jsme zvolili **vlastní implementaci** klíčových funkcí přímo v kódu tématu a vlastním pluginu. Důvody:

- **Plná kontrola nad kódem** – kód je čitelný, obhajitelný u maturity
- **Žádná závislost na externích pluginech** – web funguje jen s WordPress core + vlastní téma + vlastní plugin
- **Minimální JavaScript** – filtry řešeny přes HTML formuláře (GET), galerie přes nativní WordPress

Jediný nainstalovaný plugin je **Slavoj Custom Fields** – vlastní plugin vytvořený pro tento projekt.

---

## 1. Slavoj Custom Fields (vlastní plugin)

**Soubor:** `web/wp-content/plugins/slavoj-custom-fields/slavoj-custom-fields.php`

**Funkce:** Rozšiřující administrační nástroje pro správu obsahu webu TJ Slavoj Mýto:
- Nástrojová stránka s přehledem CPT a taxonomií
- Automatické vytvoření výchozích hodnot taxonomií (seed)
- Automatické vytvoření WordPress stránek se správnými šablonami
- Generátor ukázkových dat pro testování (zápasy, hráči, kontakty, galerie)
- Rozšířené sloupce v admin přehledech (datum, tým, skóre u zápasů; číslo, pozice, tým u hráčů)
- Řaditelné sloupce (datum zápasu, číslo dresu)
- Dropdown filtry v admin přehledech (sezóna, kategorie týmu)

**Instalace:**
1. Zkopírujte složku `slavoj-custom-fields` do `wp-content/plugins/`
2. V administraci přejděte na **Pluginy** a aktivujte **Slavoj Custom Fields**
3. Při aktivaci se automaticky vytvoří výchozí hodnoty taxonomií

**Nástrojová stránka:** Dostupná pod **Nástroje → Slavoj nastavení**. Obsahuje:
- Přehledovou tabulku registrovaných CPT a jejich polí
- Přehledovou tabulku taxonomií
- Tlačítko „Vytvořit výchozí hodnoty taxonomií"
- Tlačítko „Vytvořit ukázková data"
- Tlačítko „Vytvořit stránky webu"
- Nápovědu pro správce obsahu

**Vizuální přizpůsobení:** Plugin používá standardní WordPress administrační styly (`widefat striped` tabulky, `notice` notifikace). Nemá vlastní frontend styly.

---

## 2. Registrace CPT a taxonomií (nahrazuje Custom Post Type UI)

**Inspirace:** Plugin [Custom Post Type UI](https://wordpress.org/plugins/custom-post-type-ui/) umožňuje registrovat CPT a taxonomie přes grafické rozhraní. My jsme zvolili přímou registraci v kódu.

**Implementace:** V souboru `web/wp-content/themes/tj-slavoj-myto/functions.php` pomocí WordPress funkcí `register_post_type()` a `register_taxonomy()`.

**Registrované CPT:**

| CPT | Slug | Ikona (dashicon) | Popis |
|-----|------|-------------------|-------|
| Zápasy | `zapas` | `dashicons-awards` | Fotbalové zápasy s výsledky |
| Týmy | `tym` | `dashicons-groups` | Přehled týmů a soupiska |
| Hráči | `hrac` | `dashicons-id` | Hráčské profily |
| Galerie | `galerie` | `dashicons-format-gallery` | Fotoalba |
| Sponzoři | `sponzor` | `dashicons-money-alt` | Partneři klubu |
| Kontakty | `kontakt` | `dashicons-phone` | Výbor a kontaktní osoby |

**Registrované taxonomie:**

| Taxonomie | Slug | Typy obsahu |
|-----------|------|-------------|
| Sezóna | `sezona` | zapas, tym, hrac, galerie |
| Kategorie týmu | `kategorie-tymu` | zapas, tym, hrac, galerie |
| Stav zápasu | `stav-zapasu` | zapas |
| Pozice hráče | `pozice-hrace` | hrac |

**Vizuální přizpůsobení:** CPT jsou dostupné v levém menu administrace s vlastními ikonami (dashicons). Administrátorský přehled zápasů zobrazuje rozšířené sloupce Datum, Tým a Skóre.

---

## 3. Vlastní meta pole (nahrazuje Advanced Custom Fields)

**Inspirace:** Plugin [Advanced Custom Fields (ACF)](https://wordpress.org/plugins/advanced-custom-fields/) umožňuje definovat meta pole přes grafické rozhraní. My jsme implementovali meta boxy přímo v PHP.

**Implementace:** V `functions.php` pomocí WordPress funkcí `add_meta_box()`, `get_post_meta()` a `update_post_meta()`. Výsledek je funkčně totožný s ACF, ale bez závislosti na externím pluginu.

**Pole pro Zápas (`zapas`):**

| Pole | Klíč | Typ | Popis |
|------|------|-----|-------|
| Datum zápasu | `datum_zapasu` | date | Datum konání zápasu |
| Čas výkopu | `cas_zapasu` | time | Čas zahájení |
| Domácí tým | `domaci` | text | Název domácího týmu |
| Hostující tým | `hoste` | text | Název hostů |
| Skóre | `skore` | text | Výsledek, např. `3:1` (prázdné = neodehráno) |
| Střelci | `strelci` | text | Jména střelců, např. `2× Novák, Bejček` |

**Pole pro Tým (`tym`):**

| Pole | Klíč | Typ | Popis |
|------|------|-----|-------|
| Slug týmu | `tym_slug` | text | Identifikátor pro propojení s hráči (např. `muzi-a`) |
| Počet hráčů | `pocet_hracu` | number | Automaticky vypočítáno z hráčů přiřazených k týmu |
| Hlavní trenér | `hlavni_trener` | text | Jméno hlavního trenéra |
| Asistent trenéra | `asistent_trenera` | text | Jméno asistenta |
| Zdravotník | `zdravotnik` | text | Jméno zdravotníka |

**Pole pro Hráče (`hrac`):**

| Pole | Klíč | Typ | Popis |
|------|------|-----|-------|
| Číslo dresu | `cislo` | number | Číslo na dresu (1–99) |
| Rok narození | `rok_narozeni` | number | Rok narození |
| Slug týmu | `tym_slug` | text | Propojení s týmem |

**Pole pro Galerii (`galerie`):**

| Pole | Klíč | Typ | Popis |
|------|------|-----|-------|
| Sezóna | `sezona` | text | Sezóna alba (např. `2025/26`) |

**Pole pro Sponzora (`sponzor`):**

| Pole | Klíč | Typ | Popis |
|------|------|-----|-------|
| Webové stránky | `web_sponzora` | url | URL webu sponzora |

**Pole pro Kontakt (`kontakt`):**

| Pole | Klíč | Typ | Popis |
|------|------|-----|-------|
| Funkce / Pozice | `pozice` | text | Role v klubu |
| Telefon | `telefon` | text | Kontaktní telefon |
| E-mail | `email` | email | Kontaktní e-mail |
| Pořadí zobrazení | `poradi` | number | Číslo pro řazení (0 = první) |

**Vizuální přizpůsobení:** Meta boxy používají standardní WordPress styly (`widefat`, `form-table`). Pole mají popisky a ukázkové hodnoty (`placeholder`).

---

## 4. Filtrace obsahu (nahrazuje FacetWP / Search & Filter Pro)

**Inspirace:** Pluginy [FacetWP](https://facetwp.com/) a [Search & Filter Pro](https://searchandfilter.com/) poskytují AJAX filtrování obsahu. My jsme implementovali filtry jednodušeji – HTML formuláře s GET parametry.

**Implementace:** Šablony obsahují `<form method="get">` s `<select>` prvky. Po výběru hodnoty se formulář automaticky odešle (`onchange="this.form.submit()"`). PHP kód v šabloně načte GET parametry, sanitizuje je a předá do `WP_Query` s `tax_query` a `meta_query`.

**Šablony s filtrací:**

| Šablona | Filtry |
|---------|--------|
| `archive-zapas.php` | Tým (`kat`), sezóna (`sezona`), stav (`stav`) |
| `archive-tym.php` | Sezóna |
| `archive-galerie.php` | Tým, sezóna |

**Vizuální přizpůsobení:** Filtry používají Bootstrap 5 třídy (`form-select`, `row`, `col-md-4`). Vlastní CSS třídy `filter-select-team`, `filter-select-season`, `filter-select-status` přidávají barevné akcenty v barvách klubu.

---

## 5. Galerie fotografií (nativní WordPress)

**Inspirace:** Pluginy [Modula](https://wordpress.org/plugins/modula-best-grid-gallery/) a [NextGEN Gallery](https://wordpress.org/plugins/nextgen-gallery/) nabízí pokročilé galerie s lightboxem. My využíváme nativní WordPress galerii.

**Implementace:** Galerie je řešena jako CPT `galerie` s vlastní archivní šablonou. Fotografie se vkládají přes standardní WordPress editor (**Přidat média → Vytvořit galerii**). Náhled alba je řešen přes **Obrázek příspěvku** (featured image).

**Šablony:**
- `archive-galerie.php` – přehled alb s náhledy a filtrací
- `single-galerie.php` – detail alba s kompletní fotogalerií

---

## 6. Uživatelská role „Správce obsahu" (nahrazuje User Role Editor)

**Inspirace:** Plugin [User Role Editor](https://wordpress.org/plugins/user-role-editor/) umožňuje graficky spravovat role a oprávnění. My jsme vytvořili vlastní roli přímo v kódu.

**Implementace:** V `functions.php` je registrována role `spravce_obsahu` pomocí `add_role()` s oprávněními pro editaci všech CPT, ale bez přístupu k nastavení WordPressu a pluginů.

**Uživatelské role na webu:**

| Role | Popis | Oprávnění |
|------|-------|-----------|
| Administrátor | Správce webu | Plný přístup |
| Správce obsahu | Správce zápasů a obsahu | Editace CPT, nahrávání médií, bez nastavení WP |

---

## 7. Optimalizace výkonu (implementováno v kódu)

Místo externích pluginů pro výkon jsou základní optimalizace implementovány přímo v kódu tématu:

| Optimalizace | Implementace |
|---|---|
| Lazy loading obrázků | Funkce `slavoj_add_lazy_loading()` v `functions.php` přidává `loading="lazy"` ke všem obrázkům |
| Bootstrap z CDN | CSS a JS načítány z jsdelivr.net – využívá cache prohlížeče |
| Minimální JavaScript | Filtry přes HTML formuláře (GET), bez AJAX knihoven |
| Mobile-first CSS | Vlastní CSS psaný mobile-first, minimální velikost |

> **Poznámka:** Pro produkční nasazení je doporučeno doplnit cache plugin (např. WP Super Cache) a kompresi obrázků (např. Smush). Tyto pluginy nejsou součástí projektu, ale jsou snadno doinstalovatelné.

---

## Shrnutí

| Funkce ze zadání | Plugin doporučený v zadání | Naše řešení |
|---|---|---|
| Registrace CPT a taxonomií | Custom Post Type UI | Vlastní kód v `functions.php` |
| Správa meta polí | Advanced Custom Fields | Vlastní meta boxy v `functions.php` |
| Admin rozšíření + seed dat | – | Vlastní plugin Slavoj Custom Fields |
| Filtrace obsahu | FacetWP, Search & Filter Pro | HTML formuláře s GET parametry |
| Galerie fotografií | Modula, NextGEN Gallery | Nativní WordPress galerie + CPT |
| Uživatelské role | User Role Editor | Vlastní role v `functions.php` |
| Optimalizace výkonu | WP Super Cache, Smush | Lazy loading, CDN, minimální JS |

---

*Aktualizováno: březen 2026*
