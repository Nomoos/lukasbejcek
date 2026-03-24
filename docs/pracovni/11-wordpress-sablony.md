# 11 – WordPress šablony: CPT Archive vs Page Template

## Přehled všech typů šablon WordPress

WordPress vybírá šablonu podle tzv. **Template Hierarchy** — prioritního seznamu souborů od nejkonkrétnějšího po nejobecnější fallback.

| Typ šablony | Soubor | URL vzor | Příklad v projektu |
|---|---|---|---|
| **Homepage** | `front-page.php` | `/` | Úvodní stránka s bannerem a kartami zápasů |
| **Stránka (obecná)** | `page.php` | `/cokoliv/` | Fallback pro stránky bez vlastní šablony |
| **Stránka (dle slugu)** | `page-{slug}.php` | `/sponzori/` | `page-sponzori.php`, `page-galerie.php` |
| **CPT archiv** | `archive-{cpt}.php` | `/zapasy/` | `archive-zapas.php`, `archive-tym.php` |
| **CPT detail** | `single-{cpt}.php` | `/zapasy/muj-zapas/` | `single-zapas.php`, `single-tym.php`, `single-galerie.php` |
| **Příspěvek blogu** | `single.php` | `/aktuality/clanek/` | `single.php` — aktuality klubu |
| **Archiv (obecný)** | `archive.php` | `/aktuality/` | Fallback pro archivy bez vlastního souboru |
| **Fallback** | `index.php` | cokoliv | Poslední záchrana — WordPress vždy najde tento soubor |

### Priorita výběru šablony (WordPress Template Hierarchy)

WordPress prochází soubory od **nejkonkrétnějšího** k nejobecnějšímu a použije první který existuje:

```
/zapasy/              → archive-zapas.php → archive.php → index.php
/zapasy/muzi-vs-x/   → single-zapas.php  → single.php  → index.php
/sponzori/            → page-sponzori.php → page.php    → index.php
/                     → front-page.php    → home.php    → index.php
```

---

## Základní rozdíl

WordPress má dva různé způsoby jak zobrazit obsah na dané URL. Je důležité vědět který se používá, protože úprava špatného souboru nemá žádný efekt.

---

## Custom Post Type (CPT) Archive

CPT je vlastní typ obsahu registrovaný v `functions.php` přes `register_post_type()`.

```php
register_post_type('zapas', array(
    'public'      => true,
    'has_archive' => true,  // → WordPress automaticky vytvoří /zapasy/
    'label'       => 'Zápasy',
));
```

**Jak funguje:**
- WordPress automaticky vytvoří archivní URL (`/zapasy/`, `/tymy/`)
- Každý záznam má vlastní detail URL (`/zapasy/muzi-a-vs-sokol/`)
- Obsah se spravuje v administraci jako samostatná sekce
- Šablony: `archive-{cpt}.php` (seznam) a `single-{cpt}.php` (detail)

---

## Page Template

Běžná WordPress stránka s přiřazenou vlastní PHP šablonou.

```php
<?php
/**
 * Template Name: Sponzoři
 */
```

**Jak funguje:**
- Stránka musí existovat v administraci (Stránky → Přidat novou)
- Šablona se přiřadí v editoru stránky: Atributy stránky → Šablona
- Nemá archiv ani detail záznamy — je to jedna statická stránka
- Soubor: `page-{slug}.php` nebo libovolný název s komentářem `Template Name:`

---

## Srovnání

| | CPT Archive | Page Template |
|---|---|---|
| Registrace | `functions.php` | komentář `Template Name:` |
| Stránka v adminu | ne (URL automatická) | ano (musí existovat) |
| Archivní URL | automaticky | ne |
| Detail URL | automaticky | ne |
| Počet záznamů | N (přidávají se) | 1 (statická stránka) |
| Šablona (seznam) | `archive-{cpt}.php` | `page-{slug}.php` |
| Šablona (detail) | `single-{cpt}.php` | — |

---

## Mapování šablon v tomto projektu

### CPT Archives (dynamický obsah ze záznamů)

| URL | Soubor šablony | Poznámka |
|---|---|---|
| `/zapasy/` | `archive-zapas.php` | seznam zápasů s filtry |
| `/zapasy/{slug}/` | `single-zapas.php` | detail jednoho zápasu |
| `/tymy/` | `archive-tym.php` | seznam týmů s filtry |
| `/tymy/{slug}/` | `single-tym.php` | detail týmu se soupiskou |

### Page Templates (statický obsah)

| URL | Soubor šablony | Poznámka |
|---|---|---|
| `/` | `front-page.php` | homepage |
| `/sponzori/` | `page-sponzori.php` | seznam sponzorů |
| `/historie/` | `page-historie.php` | historie klubu |
| `/galerie/` | `page-galerie.php` | přehled galerií |
| `/kontakt/` | `page-kontakt.php` | kontaktní stránka |

---

## Častá chyba: kombinace obou přístupů

V projektu původně existovaly soubory `page-zapasy.php` i `page-tymy.php` zároveň s `archive-zapas.php` a `archive-tym.php`.

**Problém:** URL `/zapasy/` a `/tymy/` jsou CPT archivní URL — WordPress vždy použije `archive-*.php`. Soubory `page-zapasy.php` a `page-tymy.php` se nikdy nepoužijí, protože žádná WordPress stránka s touto šablonou neexistuje (a ani nemůže — URL je obsazena CPT archivem).

Úpravy v `page-tymy.php` tedy neměly žádný efekt, přestože soubor existoval.

**Řešení:** Nepoužívané `page-*.php` soubory odstranit, aby nevznikalo zmatení.

---

## Jak zjistit který soubor WordPress používá

Dočasně přidat na začátek jakékoliv šablony:

```php
// DEBUG – smazat po ověření
echo '<!-- Template: ' . basename(__FILE__) . ' -->';
```

Ve zdrojovém kódu stránky pak bude vidět název aktivního souboru.

Alternativně: plugin **Query Monitor** zobrazí název šablony v admin liště.

---

## Pravidlo pro tento projekt

> Obsah ze záznamů (zápasy, týmy, hráči) → vždy `archive-*.php` / `single-*.php`
>
> Statické stránky (sponzoři, historie, kontakt) → vždy `page-*.php`

---

*Vytvořeno: březen 2026*
