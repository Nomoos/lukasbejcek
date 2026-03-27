# Webová prezentace fotbalového klubu s databází zápasů a správou obsahu ve WordPressu

---

**Maturitní práce**

**Autor:** Lukáš Bejček
**Obor:** Informační technologie
**Školní rok:** 2025/2026

---

\newpage

## Prohlášení

Prohlašuji, že jsem maturitní práci na téma *Webová prezentace fotbalového klubu s databází zápasů a správou obsahu ve WordPressu* vypracoval samostatně pod vedením vedoucího práce a s použitím odborné literatury a dalších informačních zdrojů, které jsou citovány v práci a uvedeny v seznamu použité literatury.

V Mýtě dne .................... Podpis: ......................

\newpage

## Poděkování

Děkuji vedoucímu práce za odborné vedení, cenné rady a připomínky při zpracování maturitní práce.

\newpage

## Abstrakt

Tato maturitní práce se zabývá návrhem a implementací webové prezentace fotbalového klubu TJ Slavoj Mýto. Cílem bylo vytvořit moderní, responzivní web, který umožňuje správci klubu spravovat zápasy, týmy, hráče, galerie, sponzory a kontakty bez znalosti programování. Web je postaven na redakčním systému WordPress s vlastním tématem založeným na frameworku Bootstrap 5 a vlastním pluginem pro rozšíření administrace. Místo externích placených pluginů byla zvolena vlastní implementace klíčových funkcí – registrace typů obsahu, vlastních polí, filtrů a uživatelských rolí – přímo v kódu tématu a pluginu. Práce popisuje analýzu původního řešení, návrh datového modelu, implementaci šablon a administračních nástrojů, testování a uživatelskou příručku pro správce webu.

**Klíčová slova:** WordPress, fotbalový klub, webová prezentace, custom post types, Bootstrap 5, PHP, responzivní design

\newpage

## Obsah

*(Obsah je generován automaticky při exportu do PDF – viz `docs/generate-pdf.sh`)*

\newpage

## 1 Úvod

Fotbalový klub TJ Slavoj Mýto je amatérský sportovní klub působící v obci Mýto u Rokycan. Klub má několik týmů od mužů po mládežnické kategorie a potřebuje moderní webovou prezentaci, na které budou přehledně zobrazeny zápasy, týmy, hráčské soupisky, fotogalerie, partneři klubu a kontakty na vedení.

Původní web klubu byl postaven na nedokončeném WordPress tématu s pevně zakódovaným obsahem přímo v PHP šablonách. Správce klubu nemohl bez zásahu programátora přidat nový zápas, aktualizovat soupisku ani publikovat novinky. Cílem této práce je vytvořit plně funkční a spravovatelný WordPress web, kde veškerý obsah lze spravovat přes standardní administrační rozhraní.

Práce je členěna do následujících kapitol. Kapitola 2 analyzuje původní řešení a porovnává možné přístupy k implementaci. Kapitola 3 popisuje implementaci – datový model, šablony, administrační nástroje a bezpečnost. Kapitola 4 dokumentuje testování a ověření funkčnosti. Kapitola 5 slouží jako uživatelská příručka pro správce webu. Kapitola 6 obsahuje závěr s návrhy na rozšíření.

Celý projekt je dostupný jako open-source repozitář na GitHubu a je připraven k lokálnímu nasazení pomocí XAMPP nebo wp-env.

---

## 2 Analýza problému

### 2.1 Původní stav webu

Původní web TJ Slavoj Mýto byl postaven na WordPress tématu ve složce `original/`. Analýza zdrojového kódu odhalila několik zásadních problémů:

1. **Pevně zakódovaný obsah** – názvy týmů, zápasy a kontakty byly zapsány přímo v PHP šablonách. Každá změna obsahu vyžadovala zásah do zdrojového kódu.

2. **Nefunkční filtry** – stránky zápasů a týmů obsahovaly HTML prvky pro filtrování, ale JavaScript zajišťující jejich funkčnost nebyl implementován nebo nefungoval správně.

3. **Chybějící datový model** – zápasy a hráči nebyli uloženi jako samostatné záznamy v databázi (custom post types), ale jako součást statických šablon. Nebylo možné je filtrovat, řadit ani stránkovat.

4. **Nekonzistentní použití WordPress API** – některé stránky používaly `WP_Query`, jiné měly data zakódována přímo v HTML. Neexistoval jednotný přístup.

5. **Nedostatečná responzivita** – mobilní zobrazení nebylo důsledně otestováno; některé prvky přetékaly mimo obrazovku.

Z původního kódu bylo možné převzít HTML strukturu šablon, napojení na Bootstrap 5 a základní vizuální styl. Veškerá datová a logická vrstva musela být implementována znovu.

### 2.2 Požadavky na nové řešení

Na základě zadání maturitní práce [1] a potřeb klubu byly stanoveny tyto požadavky:

- Evidence zápasů s výsledky, střelci a filtrováním podle sezóny, týmu a stavu
- Evidence týmů se soupiskami hráčů a realizačním týmem
- Fotogalerie členěná podle týmů a sezón
- Kontakty na vedení klubu s možností řazení
- Přehled sponzorů s logy a odkazy
- Stránka historie klubu
- Správa veškerého obsahu přes administraci WordPress bez zásahu do kódu
- Responzivní design (desktop i mobilní zařízení)
- Minimální JavaScript – preferovat serverové řešení v PHP

### 2.3 Volba technologií

#### 2.3.1 CMS – WordPress

Pro implementaci byl zvolen redakční systém WordPress [2], nejrozšířenější CMS na světě s podílem přes 40 % všech webových stránek. WordPress nabízí:

- Vestavěné administrační rozhraní pro správu obsahu
- Systém custom post types a taxonomií pro strukturovaná data
- Šablonovou hierarchii pro mapování URL na šablony
- REST API pro případné budoucí rozšíření
- Rozsáhlou dokumentaci a komunitu [3]

#### 2.3.2 CSS framework – Bootstrap 5

Pro responzivní layout byl zvolen framework Bootstrap 5 [4], který poskytuje:

- Grid systém pro responzivní rozvržení stránky
- Utility třídy pro rychlé stylování (spacing, barvy, typography)
- Hotové komponenty (navbar, cards, badges, forms)
- Mobile-first přístup k responzivnímu designu

Bootstrap je načítán z CDN [5], čímž odpadá nutnost build nástrojů a zároveň se využívá cache prohlížeče.

#### 2.3.3 Lokální vývojové prostředí – XAMPP

Pro lokální vývoj a testování byl zvolen balík XAMPP [6], který obsahuje Apache webserver, MySQL databázi a PHP interpret v jedné instalaci. XAMPP je dostupný zdarma pro Windows, macOS i Linux.

#### 2.3.4 Grafický návrh – Figma

Grafický návrh webu byl vytvořen v nástroji Figma [7] ve variantách pro desktop i mobilní zařízení.

### 2.4 Vlastní implementace vs. externí pluginy

Zadání práce doporučuje řadu externích pluginů: Custom Post Type UI [8] pro registraci CPT, Advanced Custom Fields [9] pro správu vlastních polí, FacetWP pro filtrování obsahu, The Events Calendar pro kalendář zápasů, Modula pro galerie a User Role Editor pro správu rolí.

Po analýze jsme zvolili **vlastní implementaci** klíčových funkcí přímo v kódu tématu a vlastním pluginu. Důvody:

| Aspekt | Externí pluginy | Vlastní implementace |
|---|---|---|
| Kontrola nad kódem | Omezená – závislost na cizím kódu | Plná – kód je čitelný a obhajitelný |
| Závislosti | Více pluginů = více bodů selhání | Pouze WordPress core + vlastní kód |
| Výkon | Každý plugin přidává zátěž | Minimální overhead |
| Údržba | Nutnost aktualizovat pluginy | Kód je pod vlastní správou |
| Licence | Některé pluginy jsou placené | Vše zdarma (GPL-2.0) |

Jediným nainstalovaným pluginem je **Slavoj Custom Fields** – vlastní plugin vytvořený pro tento projekt, který rozšiřuje administrační prostředí.

### 2.5 Custom post types vs. vlastní databázové tabulky

WordPress nabízí dva přístupy k ukládání strukturovaných dat: custom post types (CPT) s meta poli, nebo vlastní databázové tabulky.

Pro tento projekt byly zvoleny CPT z následujících důvodů [3]:

1. **Integrace s WordPress admin** – CPT automaticky získají administrační rozhraní (přehled, editace, mazání, koš) bez nutnosti psát vlastní CRUD logiku.
2. **Podpora taxonomií** – termy (sezóna, kategorie týmu) lze přiřazovat nativně.
3. **WP_Query** – dotazování probíhá standardním WordPress API, které řeší caching, stránkování i bezpečnost.
4. **REST API** – parametr `show_in_rest => true` zpřístupní data přes JSON API.
5. **Šablonová hierarchie** – WordPress automaticky mapuje URL na šablony (`/zapasy/` → `archive-zapas.php`).

Alternativa s vlastními tabulkami by vyžadovala ruční implementaci administrace, validace, stránkování a URL routingu.

### 2.6 Archivní šablony vs. stránkové šablony

Pro zobrazení seznamů CPT (zápasy, týmy, galerie) byly zvoleny **archivní šablony** (`archive-zapas.php`) místo stránkových šablon (`page-zapasy.php`). Důvody [3]:

- WordPress automaticky vytvoří čistou URL `/zapasy/` bez nutnosti zakládat stránku v administraci
- Stránkování funguje nativně
- Taxonomické filtry se integrují přirozeně přes GET parametry

Stránkové šablony zůstávají pouze pro statický obsah: historie, kontakty, sponzoři.

---

## 3 Implementace

### 3.1 Architektura řešení

Projekt se skládá ze dvou komponent:

**Téma `tj-slavoj-myto`** obsahuje prezentační logiku a registraci datového modelu:

- registrace custom post types a taxonomií
- šablony pro front-end (homepage, archivy, detaily)
- CSS styly rozdělené do komponentových souborů
- pomocné funkce (řazení, vazby, počítání hráčů)
- globální filtry pro konzistentní chování taxonomií

**Plugin `slavoj-custom-fields`** poskytuje administrační nástroje:

- vlastní sloupce v admin přehledech (datum, tým, skóre, číslo dresu)
- dropdown filtry pro filtrování v administraci
- řaditelné sloupce (datum zápasu, číslo dresu)
- nástrojová stránka pro správu a seedování ukázkových dat

Toto rozdělení odpovídá doporučené WordPress architektuře [3]: téma řeší „jak vypadá", plugin řeší „jak se spravuje".

### 3.2 Adresářová struktura

```
web/
└── wp-content/
    ├── themes/tj-slavoj-myto/
    │   ├── functions.php              # Registrace CPT, taxonomií, helperů
    │   ├── front-page.php             # Homepage
    │   ├── archive-zapas.php          # Seznam zápasů s filtry
    │   ├── archive-tym.php            # Seznam týmů
    │   ├── archive-galerie.php        # Přehled fotoalb
    │   ├── single-zapas.php           # Detail zápasu
    │   ├── single-tym.php             # Profil týmu se soupiskou
    │   ├── single-hrac.php            # Profil hráče
    │   ├── single-galerie.php         # Fotoalbum s lightboxem
    │   ├── page-historie.php          # Historie klubu
    │   ├── page-kontakty.php          # Kontakty výboru
    │   ├── page-sponzori.php          # Partneři klubu
    │   ├── template-parts/            # Znovupoužitelné komponenty
    │   └── assets/css/                # Stylové soubory
    └── plugins/slavoj-custom-fields/
        └── slavoj-custom-fields.php   # Administrační plugin
```

### 3.3 Datový model

#### 3.3.1 Custom post types

Projekt definuje šest vlastních typů obsahu registrovaných v `functions.php` pomocí funkce `register_post_type()` [3]:

| CPT | Slug | Meta pole | Účel |
|---|---|---|---|
| **Zápasy** | `zapas` | `datum_zapasu`, `cas_zapasu`, `domaci`, `hoste`, `skore`, `strelci` | Evidence zápasů |
| **Týmy** | `tym` | `hlavni_trener`, `asistent_trenera`, `zdravotnik`, `tym_slug` | Profily týmů |
| **Hráči** | `hrac` | `cislo`, `rok_narozeni`, `tym_slug` | Soupisky hráčů |
| **Galerie** | `galerie` | — | Fotoalba |
| **Sponzoři** | `sponzor` | `web_sponzora` | Partneři klubu |
| **Kontakty** | `kontakt` | `pozice`, `telefon`, `email`, `poradi` | Výbor klubu |

Ukázka registrace CPT pro zápasy:

```php
register_post_type('zapas', array(
    'labels' => array(
        'name'               => 'Zápasy',
        'singular_name'      => 'Zápas',
        'add_new'            => 'Přidat zápas',
        'add_new_item'       => 'Přidat nový zápas',
        // ...
    ),
    'public'              => true,
    'has_archive'         => true,
    'rewrite'             => array('slug' => 'zapasy'),
    'supports'            => array('title', 'thumbnail'),
    'menu_icon'           => 'dashicons-awards',
    'show_in_rest'        => true,
    'taxonomies'          => array('sezona', 'kategorie-tymu', 'stav-zapasu'),
    'capability_type'     => array('zapas', 'zapasy'),
    'map_meta_cap'        => true,
));
```

Každý CPT má vlastní `capability_type` s `map_meta_cap => true`, což umožňuje granulární řízení oprávnění pro jednotlivé uživatelské role.

#### 3.3.2 Taxonomie

Projekt používá čtyři vlastní taxonomie registrované přes `register_taxonomy()` [3]:

| Taxonomie | Slug | Sdílená mezi CPT | Účel |
|---|---|---|---|
| **Sezóna** | `sezona` | zapas, tym, hrac, galerie | Filtrování podle ročníku |
| **Kategorie týmu** | `kategorie-tymu` | zapas, tym, hrac, galerie | Muži A, B, Dorost, Žáci… |
| **Stav zápasu** | `stav-zapasu` | zapas | Nadcházející, Odehraný, Zrušený |
| **Pozice hráče** | `pozice-hrace` | hrac | Brankáři, Hráči v poli |

Ukázka registrace taxonomie:

```php
register_taxonomy('sezona', array('zapas', 'tym', 'hrac', 'galerie'), array(
    'labels'            => array(
        'name'          => 'Sezóny',
        'singular_name' => 'Sezóna',
    ),
    'hierarchical'      => false,
    'public'            => true,
    'show_admin_column' => true,
    'show_in_rest'      => true,
));
```

#### 3.3.3 Vazby mezi entitami

Vztahy mezi typy obsahu jsou řešeny dvěma mechanismy:

**Taxonomická vazba (M:N)** – sezóna a kategorie týmu jsou sdíleny mezi zápasy, týmy, hráči a galeriemi pomocí WordPress taxonomií.

**Meta pole vazba (1:N)** – hráči jsou propojeni s týmy přes meta pole `tym_slug`. Každý hráč má uložen slug svého týmu (např. `muzi-a`), který odpovídá hodnotě `tym_slug` u příslušného záznamu CPT `tym`.

```
Zápas ──(taxonomie)──> Sezóna <──(taxonomie)── Tým
  │                                               │
  └──(taxonomie)──> Kategorie týmu <──(taxonomie)─┘
                                                  │
                                            (meta: tym_slug)
                                                  │
                                                Hráč
```

Funkce `slavoj_count_hracu_tymu()` automaticky počítá hráče přiřazené k týmu a výsledek ukládá do meta pole `pocet_hracu`.

#### 3.3.4 ER diagram

```
┌────────────────────────────────┐
│            SEZÓNA              │  taxonomie: sezona
│  slug: 2024-25 / 2025-26 …    │
└──────────┬─────────────────────┘
           │ m:n (term relationship)
     ┌─────┼──────┬─────────────────┐
     │     │      │                 │
     ▼     ▼      ▼                 ▼
┌────────┐ ┌────────┐ ┌──────────┐ ┌─────────┐
│ ZÁPAS  │ │  TÝM   │ │  HRÁČ    │ │ GALERIE │
└───┬────┘ └───┬────┘ └────┬─────┘ └─────────┘
    │          │           │
    │          │  1:n      │ n:1 (tym_slug)
    │          └───────────┘
    ▼ m:n
┌────────────────────────────────┐
│         KATEGORIE TÝMU         │  taxonomie: kategorie-tymu
└────────────────────────────────┘

┌─────────┐   ┌──────────┐
│ KONTAKT │   │ SPONZOR  │   (samostatné entity)
└─────────┘   └──────────┘
```

### 3.4 Vlastní meta pole (nahrazuje Advanced Custom Fields)

Místo pluginu Advanced Custom Fields [9] jsou meta boxy implementovány přímo v PHP pomocí WordPress funkcí `add_meta_box()`, `get_post_meta()` a `update_post_meta()` [3].

Ukázka definice meta boxu pro zápasy:

```php
add_meta_box(
    'slavoj_zapas_detail',
    'Detail zápasu',
    'slavoj_zapas_meta_box_html',
    'zapas',
    'normal',
    'high'
);
```

Pole pro jednotlivé CPT:

**Zápas:** datum zápasu (date), čas výkopu (time), domácí tým (text), hostující tým (text), skóre (text), střelci (text).

**Tým:** slug týmu (text), hlavní trenér (text), asistent trenéra (text), zdravotník (text), počet hráčů (automaticky vypočteno).

**Hráč:** číslo dresu (number 1–99), rok narození (number), slug týmu (text).

**Kontakt:** funkce/pozice (text), telefon (text), e-mail (email), pořadí zobrazení (number).

**Sponzor:** webové stránky (url).

### 3.5 Filtrace obsahu

Místo pluginů FacetWP nebo Search & Filter Pro [10] jsou filtry implementovány jako HTML formuláře s GET parametry:

```html
<form method="get" action="<?php echo esc_url(get_post_type_archive_link('zapas')); ?>">
    <select name="kat" onchange="this.form.submit()">
        <option value="">Všechny týmy</option>
        <!-- dynamicky generované možnosti -->
    </select>
    <noscript><button type="submit">Filtrovat</button></noscript>
</form>
```

PHP kód v šabloně načte GET parametry, sanitizuje je pomocí `sanitize_text_field()` a předá do `WP_Query` s `tax_query` a `meta_query` [3].

Šablony s filtrací:

| Šablona | Filtry |
|---------|--------|
| `archive-zapas.php` | Tým, sezóna, stav zápasu |
| `archive-tym.php` | Sezóna |
| `archive-galerie.php` | Tým, sezóna |

Formuláře používají `onchange="this.form.submit()"` pro automatické odeslání při změně výběru, s `<noscript>` fallbackem pro prohlížeče bez JavaScriptu.

### 3.6 Řazení a kontextové filtrování taxonomií

#### 3.6.1 Problém

WordPress řadí termy taxonomií abecedně. Pro fotbalový klub je přirozené hierarchické pořadí: Muži A → Muži B → Dorost → Starší žáci → Mladší žáci → Přípravky. Navíc kategorie „Stará garda" se má zobrazovat pouze u galerií, nikoli u zápasů nebo hráčů.

#### 3.6.2 Řešení

Pořadí je definováno centrálně ve funkci `slavoj_kategorie_poradi()`:

```php
function slavoj_kategorie_poradi() {
    return array(
        'muzi-a'            => 'Muži A',
        'muzi-b'            => 'Muži B',
        'stara-garda'       => 'Stará garda',
        'dorost'            => 'Dorost',
        'starsi-zaci'       => 'Starší žáci',
        'mladsi-zaci'       => 'Mladší žáci',
        'starsi-pripravka'  => 'Starší přípravka',
        'mladsi-pripravka'  => 'Mladší přípravka',
        'mini-pripravka'    => 'Mini přípravka',
    );
}
```

Globální filtr na WordPress hook `get_terms` automaticky seřadí termy podle tohoto kanonického pořadí a odfiltruje kontextově omezené termy (např. „Stará garda" se zobrazí pouze v kontextu galerií).

### 3.7 Šablony

#### 3.7.1 Přehled šablon

| Šablona | URL | Obsah |
|---|---|---|
| `front-page.php` | `/` | Nadcházející zápasy, aktuality |
| `archive-zapas.php` | `/zapasy/` | Filtrovaný seznam zápasů |
| `archive-tym.php` | `/tymy/` | Karty týmů |
| `archive-galerie.php` | `/galerie/` | Grid fotoalb |
| `single-zapas.php` | `/zapasy/{slug}/` | Detail zápasu |
| `single-tym.php` | `/tymy/{slug}/` | Profil týmu, soupiska |
| `single-hrac.php` | `/hraci/{slug}/` | Profil hráče |
| `single-galerie.php` | `/galerie/{slug}/` | Fotoalbum s lightboxem |
| `page-historie.php` | `/historie/` | Historie klubu |
| `page-kontakty.php` | `/kontakty/` | Kontakty výboru |
| `page-sponzori.php` | `/sponzori/` | Loga partnerů |

#### 3.7.2 Znovupoužitelné komponenty (template-parts)

- **`card-match.php`** – karta zápasu (domácí vs hosté, datum, skóre, střelci)
- **`hero-team.php`** – hero sekce s názvem týmu a trenéry
- **`site-header.php`** – hlavička webu s navigací
- **`site-footer.php`** – patička webu

### 3.8 CSS architektura

Styly jsou rozdělené do komponentových souborů a načítány přes `wp_enqueue_style()` [3] s definovanými závislostmi:

```php
function slavoj_enqueue_scripts() {
    $ver = wp_get_theme()->get('Version');
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true);

    $css = get_template_directory_uri() . '/assets/css/';
    wp_enqueue_style('slavoj-utilities',  $css . 'utilities.css',            array('bootstrap'), $ver);
    wp_enqueue_style('slavoj-header',     $css . 'components/header.css',    array('slavoj-utilities'), $ver);
    wp_enqueue_style('slavoj-buttons',    $css . 'components/buttons.css',   array('slavoj-utilities'), $ver);
    wp_enqueue_style('slavoj-cards',      $css . 'components/cards.css',     array('slavoj-utilities'), $ver);
    wp_enqueue_style('slavoj-main', $css . 'main.css', array('slavoj-utilities', 'slavoj-buttons', 'slavoj-cards', 'slavoj-header'), $ver);
}
add_action('wp_enqueue_scripts', 'slavoj_enqueue_scripts');
```

Soubor `style.css` v kořeni tématu slouží pouze jako identifikátor pro WordPress (název tématu, verze, autor) a neobsahuje žádné styly. Veškeré styly a skripty jsou načítány přes `wp_enqueue_style()` a `wp_enqueue_script()` – nikdy přímo v šablonách [3].

```
assets/css/
├── utilities.css              # Barvy, spacing, utility třídy
├── main.css                   # Hlavní styly šablon
└── components/
    ├── header.css             # Navigace a hlavička
    ├── nav-mobile.css         # Mobilní menu
    ├── buttons.css            # Tlačítka
    ├── hero.css               # Hero sekce
    └── cards.css              # Karty zápasů, galerií, hráčů
```

### 3.9 Plugin Slavoj Custom Fields

Plugin je uložen v souboru `slavoj-custom-fields.php` a poskytuje administrační nástroje:

**Vlastní sloupce v přehledech:**
- Zápasy: datum (řaditelný chronologicky), tým, skóre
- Hráči: číslo dresu (řaditelné numericky), pozice, tým

Ukázka implementace vlastního sloupce:

```php
function slavoj_zapas_admin_column_data($column, $post_id) {
    if ($column === 'datum_zapasu') {
        $datum = get_post_meta($post_id, 'datum_zapasu', true);
        if ($datum) {
            $dt = DateTime::createFromFormat('Y-m-d', $datum);
            echo $dt ? esc_html($dt->format('j. n. Y')) : esc_html($datum);
        }
    }
    // ...
}
```

**Dropdown filtry:** nad seznamy příspěvků lze filtrovat podle sezóny, kategorie týmu nebo týmu (u hráčů).

**Nástrojová stránka** (Nástroje → Slavoj nastavení):
- přehled registrovaných CPT a taxonomií
- tlačítko „Vytvořit výchozí hodnoty taxonomií"
- tlačítko „Vytvořit ukázková data" (zápasy, hráči, kontakty, galerie)
- tlačítko „Vytvořit stránky webu"

### 3.10 Uživatelské role

Místo pluginu User Role Editor [11] je vlastní role registrována přímo v kódu:

```php
add_role('slavoj_editor', 'Správce obsahu', array(
    'read'                   => true,
    'edit_posts'             => true,
    'edit_others_posts'      => true,
    'publish_posts'          => true,
    'upload_files'           => true,
    'manage_categories'      => true,
    // + oprávnění pro všechny CPT
));
```

| Role | Popis | Oprávnění |
|------|-------|-----------|
| Administrátor | Správce webu | Plný přístup |
| Správce obsahu | Správce zápasů a obsahu | Editace CPT, nahrávání médií, bez nastavení WP |

### 3.11 Optimalizace výkonu

Základní optimalizace jsou implementovány přímo v kódu tématu:

| Optimalizace | Implementace |
|---|---|
| Lazy loading obrázků | Funkce `slavoj_add_lazy_loading()` přidává `loading="lazy"` [12] |
| Bootstrap z CDN | CSS a JS z jsdelivr.net – využívá cache prohlížeče [5] |
| Minimální JavaScript | Filtry přes HTML formuláře, bez AJAX knihoven |
| Mobile-first CSS | Vlastní CSS psaný mobile-first, minimální velikost |

```php
function slavoj_add_lazy_loading($content) {
    return str_replace('<img ', '<img loading="lazy" ', $content);
}
add_filter('the_content', 'slavoj_add_lazy_loading');
add_filter('post_thumbnail_html', 'slavoj_add_lazy_loading');
```

### 3.12 Bezpečnost

Projekt dodržuje standardní bezpečnostní praktiky WordPressu [3] [13]:

- **Sanitizace vstupů:** Všechna data z formulářů procházejí přes `sanitize_text_field()` nebo `sanitize_email()`.
- **Escapování výstupů:** Veškerý výstup do HTML používá `esc_html()`, `esc_attr()` nebo `esc_url()`.
- **Nonce ochrana:** Každý meta box formulář je chráněn WordPress nonce tokenem (`wp_nonce_field` / `wp_verify_nonce`).
- **WP_Query:** Databázové dotazy využívají výhradně WordPress API, nikoli přímé SQL.
- **Capability mapping:** Každý CPT má vlastní `capability_type` pro granulární řízení oprávnění.

### 3.13 JavaScript

Projekt záměrně minimalizuje použití JavaScriptu. Celkový rozsah vlastního JS je přibližně 70 řádků [14]:

1. **Filtry** – `onchange="this.form.submit()"` s `<noscript>` fallbackem (1 řádek na formulář)
2. **Lightbox galerie** v `single-galerie.php` (~55 řádků) – kliknutí na foto, navigace šipkami/Escape
3. **Quick-add střelců** v `single-zapas.php` (~12 řádků) – administrační pomůcka

Všechny funkce mají funkční fallback bez JavaScriptu. Žádné frameworky ani build nástroje nejsou použity.

---

## 4 Testování

### 4.1 Přehled testování

Testování probíhalo průběžně během vývoje. Byly provedeny následující typy testů:

1. **Funkční testování** – ověření správné funkčnosti všech stránek, filtrů a administrace
2. **Bezpečnostní kontrola kódu** – kontrola sanitizace, escapování a nonce ochrany
3. **Responzivní testování** – ověření zobrazení na různých zařízeních
4. **Kontrola WordPress best practices** – dodržení standardů pro vývoj témat [13]

### 4.2 Funkční testovací scénáře

#### Scénář 1: Přidání nového zápasu

| Krok | Akce | Očekávaný výsledek | Stav |
|------|------|--------------------|------|
| 1 | Přihlášení do administrace | Zobrazí se dashboard | ✅ |
| 2 | Klik na Zápasy → Přidat zápas | Zobrazí se formulář | ✅ |
| 3 | Vyplnění polí (datum, čas, týmy, skóre) | Pole přijímají data | ✅ |
| 4 | Přiřazení sezóny a kategorie týmu | Taxonomie přiřazeny | ✅ |
| 5 | Klik na Publikovat | Zápas uložen | ✅ |
| 6 | Zobrazení na `/zapasy/` | Zápas se zobrazí v seznamu | ✅ |
| 7 | Klik na detail zápasu | Detail s daty se zobrazí | ✅ |

#### Scénář 2: Filtrování zápasů

| Krok | Akce | Očekávaný výsledek | Stav |
|------|------|--------------------|------|
| 1 | Otevření `/zapasy/` | Zobrazí se všechny zápasy | ✅ |
| 2 | Výběr „Muži A" ve filtru týmu | Stránka se přenačte, zobrazí pouze Muži A | ✅ |
| 3 | Výběr „2025/26" ve filtru sezóny | Zobrazí pouze zápasy dané sezóny | ✅ |
| 4 | Výběr „Odehrané" ve filtru stavu | Zobrazí pouze odehrané zápasy | ✅ |
| 5 | Reset filtrů (výběr „Všechny") | Zobrazí se všechny zápasy | ✅ |

#### Scénář 3: Soupiska týmu

| Krok | Akce | Očekávaný výsledek | Stav |
|------|------|--------------------|------|
| 1 | Otevření `/tymy/` | Zobrazí se karty týmů | ✅ |
| 2 | Klik na tým | Detail týmu se soupiskou | ✅ |
| 3 | Ověření počtu hráčů | Odpovídá počtu hráčů v databázi | ✅ |
| 4 | Kontrola řazení hráčů | Seřazeni podle čísla dresu vzestupně | ✅ |

#### Scénář 4: Galerie

| Krok | Akce | Očekávaný výsledek | Stav |
|------|------|--------------------|------|
| 1 | Otevření `/galerie/` | Zobrazí se přehled alb | ✅ |
| 2 | Klik na album | Detail s fotogalerií | ✅ |
| 3 | Klik na fotografii | Lightbox se otevře | ✅ |
| 4 | Navigace šipkami/Escape | Funkční navigace, zavření | ✅ |

#### Scénář 5: Uživatelská role – Správce obsahu

| Krok | Akce | Očekávaný výsledek | Stav |
|------|------|--------------------|------|
| 1 | Přihlášení jako Správce obsahu | Dashboard bez nastavení WP | ✅ |
| 2 | Editace zápasu | Povoleno | ✅ |
| 3 | Nahrání obrázku | Povoleno | ✅ |
| 4 | Přístup k Nastavení → Obecné | Zamítnuto | ✅ |
| 5 | Přístup k Pluginy | Zamítnuto | ✅ |

### 4.3 Bezpečnostní kontrola

Kontrola kódu provedená v březnu 2026 potvrdila dodržení bezpečnostních standardů [13]:

- ✅ Sanitizace vstupů: `sanitize_text_field()`, `sanitize_email()`, `wp_unslash()`
- ✅ Escapování výstupů: `esc_html()`, `esc_attr()`, `esc_url()`
- ✅ Nonce ochrana formulářů: `wp_nonce_field()`, `wp_verify_nonce()`
- ✅ WP_Query místo raw SQL
- ✅ Správné použití `wp_reset_postdata()` po custom queries

### 4.4 Nalezené a opravené chyby

Během testování byly nalezeny a opraveny tyto chyby:

1. **`single-galerie.php`** – sezóna se načítala z meta pole místo z taxonomie. Opraveno na čtení z taxonomie `sezona`.
2. **`front-page.php`** – Bootstrap třída `.card` přidávala nechtěný rámeček. Opraveno přidáním CSS `border: none`.
3. **`front-page.php`** – horizontální scrollbar na mobilních zařízeních. Opraveno přidáním Bootstrap třídy `g-0` pro odstranění gutterů.

---

## 5 Uživatelská příručka

### 5.1 Instalace a zprovoznění

#### 5.1.1 Požadavky

- XAMPP [6] (Apache + MySQL + PHP 8.x) nebo jiný webový server s PHP a MySQL
- Git [15] pro klonování repozitáře

#### 5.1.2 Postup instalace (XAMPP)

1. Nainstalujte a spusťte XAMPP – zapněte Apache a MySQL.

2. Stáhněte WordPress [2] do `C:\xampp\htdocs\fotbal_club\`.

3. Naklonujte repozitář projektu:
   ```
   git clone https://github.com/Nomoos/lukasbejcek.git
   ```

4. Spusťte instalační skript `web/install.bat`, který zkopíruje téma a plugin do WordPress instalace. Alternativně zkopírujte ručně:
   ```
   web/wp-content/themes/tj-slavoj-myto/    →  wp-content/themes/tj-slavoj-myto/
   web/wp-content/plugins/slavoj-custom-fields/  →  wp-content/plugins/slavoj-custom-fields/
   ```

5. Vytvořte MySQL databázi `slavoj_myto`:
   ```
   CREATE DATABASE slavoj_myto CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci;
   ```

6. Otevřete `http://localhost/fotbal_club/` a dokončete instalaci WordPressu:
   - **Název databáze:** `slavoj_myto`
   - **Uživatel databáze:** `root`
   - **Heslo databáze:** (prázdné – výchozí XAMPP)
   - **Název webu:** TJ Slavoj Mýto
   - **Uživatelské jméno administrátora:** dle volby (např. `admin`)
   - **Heslo administrátora:** dle volby (doporučeno silné heslo)

7. V administraci aktivujte téma **TJ Slavoj Mýto** (Vzhled → Témata) a plugin **Slavoj Custom Fields** (Pluginy).

8. Nastavte trvalé odkazy: Nastavení → Trvalé odkazy → **Název příspěvku**.

9. Přejděte na **Nástroje → Slavoj nastavení** a klikněte postupně:
   - „Vytvořit výchozí hodnoty taxonomií"
   - „Vytvořit stránky webu"
   - „Vytvořit ukázková data" (volitelné, pro testování)

10. Nastavte hlavní stránku: Nastavení → Čtení → Statická stránka → **Domů**.

11. Vytvořte hlavní menu: Vzhled → Menu → přidejte stránky → vyberte umístění **Hlavní menu**.

#### 5.1.3 Alternativní instalace – wp-env

Pro vývojáře se znalostí Dockeru [16] je k dispozici konfigurace `.wp-env.json`:

```
npx @wordpress/env start
```

WordPress bude dostupný na `http://localhost:8888`.

### 5.2 Přístupové údaje k testovacím účtům

Po instalaci s ukázkovými daty jsou k dispozici následující testovací účty:

| Role | Uživatelské jméno | Heslo | Oprávnění |
|------|-------------------|-------|-----------|
| Administrátor | `admin` | `admin123` | Plný přístup k celému WordPressu |
| Správce obsahu | `redaktor` | `redaktor123` | Editace CPT, nahrávání médií, bez nastavení WP |

**URL administrace:** `http://localhost/fotbal_club/wp-admin/`

> **Poznámka:** Výše uvedené přístupové údaje jsou určeny výhradně pro lokální testování. Při nasazení na produkční server je nutné zvolit silná hesla.

**Přístup k databázi (XAMPP):**

| Parametr | Hodnota |
|----------|---------|
| Server | `localhost` |
| Uživatel | `root` |
| Heslo | *(prázdné)* |
| Databáze | `slavoj_myto` |
| phpMyAdmin | `http://localhost/phpmyadmin/` |

### 5.3 Návod pro správce – administrace WordPress

#### 5.3.1 Přihlášení

Otevřete `http://localhost/fotbal_club/wp-admin/` a přihlaste se. V levém menu uvidíte sekce: **Zápasy**, **Týmy**, **Hráči**, **Galerie**, **Sponzoři**, **Kontakty**.

#### 5.3.2 Správa zápasů

1. Klikněte **Zápasy → Přidat zápas**
2. Vyplňte titulek (např. „TJ Slavoj Mýto vs SK Nepomuk")
3. V sekci **Detail zápasu** vyplňte datum, čas, domácí tým, hosty, skóre a střelce
4. V pravém panelu přiřaďte **kategorii týmu** a **sezónu**
5. Klikněte **Publikovat**

Pro zadání výsledku odehraného zápasu: otevřete existující zápas, vyplňte **Skóre** a **Střelce**, klikněte **Aktualizovat**.

#### 5.3.3 Správa týmů

1. Klikněte **Týmy → Přidat tým**
2. Vyplňte název, identifikátor týmu (slug, např. `muzi-a`), trenéry
3. Nastavte **Obrázek příspěvku** (týmová fotografie)
4. Přiřaďte sezónu a kategorii
5. **Počet hráčů** se vypočítá automaticky z přiřazených hráčů

#### 5.3.4 Správa hráčů

1. Klikněte **Hráči → Přidat hráče**
2. Vyplňte jméno, číslo dresu (1–99), rok narození, slug týmu
3. Přiřaďte pozici (Brankář / Hráč v poli) a sezónu

#### 5.3.5 Správa galerií

1. Klikněte **Galerie → Přidat album**
2. Vyplňte název alba
3. V obsahu příspěvku klikněte **Přidat média → Vytvořit galerii** → vyberte fotky → **Vložit galerii**
4. Nastavte **Obrázek příspěvku** jako náhled alba

#### 5.3.6 Správa sponzorů a kontaktů

Sponzoři: název, logo (obrázek příspěvku), URL webu.

Kontakty: jméno, funkce, telefon, e-mail, pořadí zobrazení (nižší číslo = výše v seznamu).

### 5.4 Návod pro návštěvníka webu

**Navigace:** Hlavní menu obsahuje odkazy na Domů, Zápasy, Týmy, Galerie, Sponzoři, Kontakty, Historie. Na mobilních zařízeních se menu sbalí do hamburger ikony.

**Zápasy (`/zapasy/`):** Přehled zápasů s filtry podle týmu, sezóny a stavu. Každý zápas zobrazuje datum, týmy, skóre (barevně – zelená = výhra, červená = prohra) a střelce.

**Týmy (`/tymy/`):** Karty týmů s fotografiemi. Kliknutím na tým se zobrazí detail se soupiskou, realizačním týmem a nadcházejícími zápasy.

**Galerie (`/galerie/`):** Přehled fotoalb. Kliknutím na album se zobrazí fotografie s lightboxem.

---

## 6 Závěr

Cílem této maturitní práce bylo vytvořit moderní webovou prezentaci pro fotbalový klub TJ Slavoj Mýto, která umožní správci klubu spravovat obsah bez znalosti programování. Tento cíl byl splněn.

Výsledný web je postaven na systému WordPress s vlastním tématem `tj-slavoj-myto` a pluginem `slavoj-custom-fields`. Místo závislosti na externích pluginech byla klíčová funkcionalita implementována vlastním kódem – registrace šesti typů obsahu, čtyř taxonomií, administrační meta boxy, vlastní sloupce, filtry a uživatelská role „Správce obsahu".

Web je plně responzivní díky frameworku Bootstrap 5 a mobile-first přístupu ke stylování. Kód dodržuje bezpečnostní standardy WordPressu (sanitizace, escapování, nonce) a minimalizuje použití JavaScriptu.

### Splnění bodů zadání

| Bod zadání | Stav |
|---|---|
| Figma design (desktop + mobilní) | ✅ Vytvořen |
| WordPress téma s Bootstrap 5 | ✅ Implementováno |
| Custom post types (zápasy, týmy, hráči…) | ✅ 6 CPT + 4 taxonomie |
| Filtrování podle sezóny a týmu | ✅ GET filtry na archivních stránkách |
| Galerie fotografií | ✅ CPT s nativní WordPress galerií + lightbox |
| Optimalizace výkonu | ✅ Lazy loading, CDN, minimální JS |
| Uživatelské role | ✅ Administrátor + Správce obsahu |

### Návrhy na rozšíření

1. **Kalendářní modul** – vizuální kalendář zápasů (doporučen plugin The Events Calendar [17])
2. **SEO optimalizace** – integrace pluginu Rank Math [18] nebo Yoast SEO [19]
3. **Cache a komprese** – nasazení WP Super Cache [20] a Smush [21] pro produkční prostředí
4. **Vícejazyčnost** – příprava pro anglickou verzi webu
5. **Statistiky hráčů** – rozšíření dat hráčů o góly, asistence a zápasy

---

## Seznam použité literatury

[1] BEJČEK, Lukáš. *Zadání maturitní práce: Webová prezentace fotbalového klubu s databází zápasů a správou obsahu ve WordPressu* [dokument]. 2025. Soubor `ZADANI-MATURITNI-PRACE.md` v kořeni repozitáře.

[2] WORDPRESS FOUNDATION. *WordPress* [software]. Verze 6.x. San Francisco (CA): WordPress Foundation, 2024 [cit. 2026-03-27]. Dostupné z: https://wordpress.org

[3] WORDPRESS FOUNDATION. *WordPress Developer Resources* [online]. San Francisco (CA): WordPress Foundation, 2024 [cit. 2026-03-27]. Dostupné z: https://developer.wordpress.org

[4] OTTO, Mark a Jacob THORNTON. *Bootstrap* [software]. Verze 5.3.3. [b.m.]: Bootstrap Team, 2024 [cit. 2026-03-27]. Dostupné z: https://getbootstrap.com

[5] JSDELIVR. *jsDelivr – free CDN for open source* [online]. 2024 [cit. 2026-03-27]. Dostupné z: https://www.jsdelivr.com

[6] APACHE FRIENDS. *XAMPP* [software]. [b.m.]: Apache Friends, 2024 [cit. 2026-03-27]. Dostupné z: https://www.apachefriends.org

[7] FIGMA, INC. *Figma* [software]. San Francisco (CA): Figma, 2024 [cit. 2026-03-27]. Dostupné z: https://www.figma.com

[8] FLAVOR, WebDevStudios. Custom Post Type UI. In: *WordPress Plugin Directory* [online]. San Francisco (CA): WordPress Foundation, 2024 [cit. 2026-03-27]. Dostupné z: https://wordpress.org/plugins/custom-post-type-ui/

[9] WP ENGINE. Advanced Custom Fields. In: *WordPress Plugin Directory* [online]. San Francisco (CA): WordPress Foundation, 2024 [cit. 2026-03-27]. Dostupné z: https://wordpress.org/plugins/advanced-custom-fields/

[10] FACETWP, LLC. *FacetWP – Advanced Filtering for WordPress* [online]. 2024 [cit. 2026-03-27]. Dostupné z: https://facetwp.com

[11] FLAVOR, Vladimir Garagulja. User Role Editor. In: *WordPress Plugin Directory* [online]. San Francisco (CA): WordPress Foundation, 2024 [cit. 2026-03-27]. Dostupné z: https://wordpress.org/plugins/user-role-editor/

[12] WORLD WIDE WEB CONSORTIUM (W3C). *HTML Living Standard: Lazy loading* [online]. 2024 [cit. 2026-03-27]. Dostupné z: https://html.spec.whatwg.org/multipage/urls-and-fetching.html#lazy-loading-attributes

[13] WORDPRESS FOUNDATION. *Theme Developer Handbook* [online]. San Francisco (CA): WordPress Foundation, 2024 [cit. 2026-03-27]. Dostupné z: https://developer.wordpress.org/themes/

[14] BEJČEK, Lukáš. *JavaScript v projektu TJ Slavoj Mýto* [dokument]. 2026. Soubor `docs/pracovni/12-javascript.md` v repozitáři.

[15] SOFTWARE FREEDOM CONSERVANCY. *Git* [software]. Verze 2.x. [b.m.]: Software Freedom Conservancy, 2024 [cit. 2026-03-27]. Dostupné z: https://git-scm.com

[16] DOCKER, INC. *Docker* [software]. San Francisco (CA): Docker, 2024 [cit. 2026-03-27]. Dostupné z: https://www.docker.com

[17] THE EVENTS CALENDAR. The Events Calendar. In: *WordPress Plugin Directory* [online]. San Francisco (CA): WordPress Foundation, 2024 [cit. 2026-03-27]. Dostupné z: https://wordpress.org/plugins/the-events-calendar/

[18] FLAVOR, Starter Sites. Starter Templates. Rank Math SEO. In: *WordPress Plugin Directory* [online]. San Francisco (CA): WordPress Foundation, 2024 [cit. 2026-03-27]. Dostupné z: https://wordpress.org/plugins/seo-by-rank-math/

[19] YOAST BV. Yoast SEO. In: *WordPress Plugin Directory* [online]. San Francisco (CA): WordPress Foundation, 2024 [cit. 2026-03-27]. Dostupné z: https://wordpress.org/plugins/wordpress-seo/

[20] FLAVOR, Developer. WP Super Cache. In: *WordPress Plugin Directory* [online]. San Francisco (CA): WordPress Foundation, 2024 [cit. 2026-03-27]. Dostupné z: https://wordpress.org/plugins/wp-super-cache/

[21] FLAVOR, Developer. Smush. In: *WordPress Plugin Directory* [online]. San Francisco (CA): WordPress Foundation, 2024 [cit. 2026-03-27]. Dostupné z: https://wordpress.org/plugins/wp-smushit/

---

## Příloha A – Kopie zadání práce

Viz soubor `ZADANI-MATURITNI-PRACE.md` v kořeni repozitáře.

## Příloha B – Přístupové údaje

Viz soubor `PRISTUPOVE-UDAJE.md` v kořeni repozitáře.
