# Review kódu – TJ Slavoj Mýto WordPress téma

Datum review: březen 2026
Reviewer: Claude Code (AI asistent)

---

## Co bylo zkontrolováno

- Všechny PHP šablony tématu (`functions.php`, `front-page.php`, `page-*.php`, `single-*.php`, `archive-*.php`, `template-parts/`)
- Všechny CSS soubory (`assets/css/`)
- Plugin `slavoj-custom-fields.php`

---

## Celkové hodnocení

**Projekt je v dobré kondici pro maturitní práci.** Kód je čistý, bezpečný a logicky strukturovaný. Bootstrap je správně využit pro responzivní design. JavaScript je omezen na minimum (pouze lightbox a přidávání střelců).

---

## Silné stránky

### 1. Bezpečnost – správné ošetření vstupů

Všude tam, kde web přijímá data od uživatele (filtry přes URL), je použito:
- `sanitize_text_field()` – vyčistí text od HTML tagů
- `wp_unslash()` – odstraní přidané lomítka (WordPress zvyk)
- `esc_html()` – bezpečný výpis do HTML
- `esc_url()` – bezpečný výpis URL adres
- `esc_attr()` – bezpečný výpis do atributů HTML

```php
// Správný postup (příklad z page-zapasy.php):
$filtr_tym = isset($_GET['tym'])
    ? sanitize_text_field(wp_unslash($_GET['tym']))
    : '';
```

### 2. Filtry bez JavaScriptu

Filtry (výběr týmu, sezóny, stavu zápasu) fungují přes HTML formulář:
```html
<select onchange="this.form.submit()">...</select>
<noscript><button type="submit">Filtrovat</button></noscript>
```
`onchange="this.form.submit()"` odešle formulář ihned po výběru hodnoty.
`<noscript>` zajistí záložní tlačítko pro prohlížeče bez JavaScriptu.

### 3. WP_Query pro dynamický obsah

Data jsou načítána z WordPress databáze, ne hardcoded v kódu:
```php
$q = new WP_Query(array(
    'post_type'      => 'zapas',
    'posts_per_page' => 10,
    'tax_query'      => array(
        array('taxonomy' => 'kategorie-tymu', 'field' => 'slug', 'terms' => $filtr_tym),
    ),
));
```

### 4. Bootstrap 5 – správná mobilní verze

Téma používá Bootstrap grid pro responzivní rozvržení:
```html
<div class="row g-4">
  <div class="col-6 col-md-3">  <!-- 2 sloupce mobile, 4 desktop -->
```

Hamburger menu je Bootstrap komponent bez vlastního JavaScriptu:
```html
<button data-bs-toggle="collapse" data-bs-target="#site-nav">
```

### 5. WordPress best practices

- `wp_enqueue_style()` a `wp_enqueue_script()` pro správné načítání souborů
- `get_template_part()` pro znovupoužitelné části šablon
- `wp_reset_postdata()` po každé WP_Query smyčce
- Nonce ověření pro admin formulář (`wp_nonce_field`, `check_admin_referer`)
- `current_user_can()` pro kontrolu oprávnění před zobrazením admin funkcí

---

## Nalezené problémy a opravy

### Bug 1 – `single-galerie.php`: Sezóna čtena ze špatného místa ✅ OPRAVENO

**Původní kód (špatně):**
```php
// Sezóna je uložena jako taxonomie, ne jako meta pole!
$sezona = esc_html(get_post_meta(get_the_ID(), 'sezona', true));
```

**Opravený kód:**
```php
// Správně: číst z taxonomie 'sezona'
$sezona_terms = get_the_terms(get_the_ID(), 'sezona');
$sezona = (!empty($sezona_terms) && !is_wp_error($sezona_terms))
    ? esc_html($sezona_terms[0]->name)
    : '';
```

**Proč to bylo špatně?** Sezóna je registrovaná jako taxonomie (podobně jako kategorie ve WordPress). Taxonomie se čtou funkcí `get_the_terms()`, ne `get_post_meta()`. `get_post_meta()` by vrátilo prázdný řetězec, takže sezóna by se na stránce alba nikdy nezobrazila.

### Bug 2 – `front-page.php`: Bootstrap `.card` přidával nežádoucí rámeček ✅ OPRAVENO

**Původní kód (špatně):**
```html
<div class="card">  <!-- Bootstrap .card přidá border: 1px solid -->
```

Bootstrap třída `.card` automaticky přidává viditelný rámeček. Naše vlastní styly ho nepřepisovaly.

**Oprava v CSS (`main.css`):**
```css
.zapasy-container .card {
    background: var(--card);
    border: none;  /* ← přidáno: zruší Bootstrap rámeček */
    border-radius: 0 var(--radius) 0 0;
    padding: 16px;
    box-shadow: 0 2px 6px rgba(0,0,0,.06);
}
```

### Bug 3 – `front-page.php`: Horizontální scrollbar na mobilu ✅ OPRAVENO

**Původní kód (špatně):**
```html
<div class="row">  <!-- Bootstrap .row má negativní margin → scrollbar -->
```

**Opravený kód:**
```html
<div class="row g-0">  <!-- g-0 = nulové gutters, žádný přesah -->
```

**Vysvětlení:** Bootstrap `.row` používá negativní okraje (`margin-left: -12px; margin-right: -12px`) pro správné zarovnání sloupců. Pokud `.row` není uvnitř `.container`, tyto negativní okraje způsobí, že obsah přesáhne šířku obrazovky a vznikne horizontální scrollbar. Třída `g-0` tyto mezery (gutters) nastaví na nulu a problém odstraní.

### Bug 4 – `page-galerie.php`: Načítání všech galerií ✅ OPRAVENO

**Původní kód:**
```php
'posts_per_page' => -1,  // Načte VŠECHNY galerie = pomalé pro velký web
```

**Opravený kód:**
```php
'posts_per_page' => 24,  // Maximálně 24 alb – rozumný limit
```

---

## Menší poznámky (nebrání funkci)

### 1. Velký soubor functions.php

`functions.php` má přes 600 řádků. Pro maturitní projekt je to přijatelné. V profesionálním projektu by se rozdělil do více souborů (např. `inc/cpt.php`, `inc/meta-boxes.php`, `inc/helpers.php`).

### 2. JavaScript se `var` místo `let/const`

V lightboxu a admin formuláři je použito staré `var`. Moderní JavaScript preferuje `const` a `let`. Pro funkčnost to nevadí – jde jen o styl kódu.

### 3. Logo v site-header.php – prázdný `alt`

```html
<img class="brand__logo" src="..." alt="">
```

`alt=""` je správně pro dekorativní obrázky (logo je tu doplněk k textu vedle). Splňuje pravidla přístupnosti WCAG 2.1.

---

## Závěr

Kód splňuje požadavky maturitního projektu:
- **Funkčnost**: všechny stránky fungují, filtry filtrují, data se načítají z databáze
- **Bezpečnost**: správné ošetření vstupů, nonce, escape funkcí
- **Responsivita**: Bootstrap 5 + mobile-first CSS
- **Srozumitelnost**: kód je okomentován, struktura logická
- **WordPress best practices**: správné enqueuování, query funkce, template hierarchy
