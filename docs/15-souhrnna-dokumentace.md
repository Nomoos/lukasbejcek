# 15 – Souhrnná technická dokumentace projektu

## 1. Úvod a cíl projektu

Cílem projektu je vytvořit moderní informační web pro fotbalový klub **TJ Slavoj Mýto**, který přehledně zobrazuje zápasy, týmy, hráče, historii, partnery a aktuální zprávy. Web je postaven na systému WordPress s vlastním tématem a pluginem, bez závislosti na placených doplňcích. Správa obsahu je navržena tak, aby ji zvládl administrátor klubu bez znalosti programování.

Projekt je zpracováván jako maturitní práce oboru Informační technologie. Grafický návrh vychází z designu vytvořeného ve Figmě; implementace staví na frameworku Bootstrap 5 a vlastní PHP šabloně pro WordPress.

---

## 2. Použité technologie

| Technologie | Verze / detail | Účel |
|---|---|---|
| **WordPress** | 6.x | CMS — správa obsahu, uživatelské role, REST API |
| **PHP** | 8.x | Serverový jazyk pro šablony, registraci CPT, hooky |
| **Bootstrap** | 5.3.3 (CDN) | Responsivní grid, utility třídy, komponenty (navbar, cards) |
| **HTML5 + CSS3** | — | Markup a stylování (mobile-first, BEM přístup) |
| **JavaScript** | Vanilla JS, ~70 řádků celkem | Minimální interakce: lightbox galerie, auto-submit filtrů, quick-add hráčů |
| **MySQL / MariaDB** | přes WordPress | Databáze (WP_Query, žádné raw SQL) |
| **Git + GitHub** | — | Verzování kódu a spolupráce |
| **XAMPP** | Apache + MySQL + PHP | Lokální vývojové prostředí |

### Proč tyto technologie

WordPress byl zvolen, protože je nejrozšířenějším CMS s rozsáhlou dokumentací a silnou komunitou. Pro fotbalový klub je zásadní, aby správce obsahu mohl přidávat zápasy a aktualizovat soupisky bez zásahu do kódu — přesně to WordPress umožňuje.

Bootstrap 5 byl zvolen pro rychlý vývoj responsivního layoutu. Místo instalace přes npm je načítán z CDN, čímž odpadá nutnost build toolů. Projekt záměrně nepoužívá žádný JavaScript framework — veškerá logika je řešena serverově v PHP, což odpovídá zaměření studia a snižuje složitost údržby.

---

## 3. Architektura řešení

### 3.1 Rozdělení na téma a plugin

Projekt se skládá ze dvou komponent:

**Téma `tj-slavoj-myto`** obsahuje veškerou prezentační logiku a registraci datového modelu:
- registrace custom post types a taxonomií
- šablony pro front-end (homepage, archivy, detaily)
- CSS styly (rozdělené do komponentových souborů)
- pomocné funkce (řazení, vazby, počítání hráčů)
- globální filtry pro konzistentní chování taxonomií

**Plugin `slavoj-custom-fields`** poskytuje administrační nástroje:
- vlastní sloupce v admin přehledech (datum, tým, skóre, číslo dresu)
- dropdown filtry pro filtrování podle sezóny, týmu, kategorie
- řaditelné sloupce (datum zápasu, číslo dresu)
- nástrojová stránka pro správu a seedování ukázkových dat
- inicializace výchozích termů taxonomií při aktivaci

Toto rozdělení odpovídá doporučené WordPress architektuře: téma řeší „jak vypadá", plugin řeší „jak se spravuje". Při změně tématu zůstane administrační funkčnost zachována.

### 3.2 Adresářová struktura

```
lukasbejcek/
├── original/                  # Původní téma studenta (reference, neměnit)
├── web/
│   ├── wp-content/
│   │   ├── themes/tj-slavoj-myto/   # AKTIVNÍ téma
│   │   │   ├── functions.php         # Registrace CPT, taxonomií, helper funkce
│   │   │   ├── front-page.php        # Homepage
│   │   │   ├── archive-*.php         # Archivní šablony (zapas, tym, galerie)
│   │   │   ├── single-*.php          # Detailní šablony (zapas, tym, hrac, galerie)
│   │   │   ├── page-*.php            # Stránkové šablony (historie, kontakty, sponzori)
│   │   │   ├── template-parts/       # Znovupoužitelné části (card-match, hero-team, …)
│   │   │   ├── assets/css/           # Stylové soubory (main.css + components/)
│   │   │   ├── img/                  # Obrázky tématu (logo, banner)
│   │   │   └── style.css             # WP identifikátor tématu (bez stylů)
│   │   └── plugins/slavoj-custom-fields/
│   │       └── slavoj-custom-fields.php
│   ├── plugins/slavoj-custom-fields/ # Synchronizovaná kopie pluginu
│   ├── install.bat                   # Instalační skript pro XAMPP
│   └── README.md
├── docs/                      # Průběžná dokumentace (01–15)
├── wordpress/                 # WordPress core (reference)
├── .wp-env.json               # Konfigurace pro wp-env
├── ZADANI-MATURITNI-PRACE.md  # Zadání práce
├── DOKUMENTACE-KOD.md         # Dokumentace původního kódu
├── PLAN-PORTOVANI-WORDPRESS.md # Plán migrace
└── README.md                  # Hlavní README projektu
```

### 3.3 CSS architektura

Styly jsou rozděleny do komponentových souborů a načítány přes `wp_enqueue_style()` s definovanými závislostmi:

```
assets/css/
├── utilities.css              # Základní utility třídy (barvy, spacing)
├── main.css                   # Hlavní styly šablon
└── components/
    ├── header.css             # Navigace a hlavička
    ├── nav-mobile.css         # Mobilní menu
    ├── buttons.css            # Tlačítka
    ├── hero.css               # Hero sekce
    └── cards.css              # Karty zápasů, galerií, hráčů
```

Soubor `style.css` v kořeni tématu slouží pouze jako identifikátor pro WordPress (název tématu, verze, autor) — neobsahuje žádné styly. Všechny styly jsou enqueued přes `functions.php`, nikdy hardcoded v šablonách.

---

## 4. Datový model

### 4.1 Custom Post Types

Projekt definuje šest vlastních typů obsahu registrovaných v `functions.php` pomocí `register_post_type()`:

| CPT | Slug | Meta pole | Supports | Účel |
|---|---|---|---|---|
| **Zápasy** | `zapas` | `datum_zapasu`, `cas_zapasu`, `domaci`, `hoste`, `skore`, `strelci` | title, thumbnail | Evidence zápasů s výsledky |
| **Týmy** | `tym` | `hlavni_trener`, `asistent_trenera`, `zdravotnik`, `tym_slug` | title, editor, thumbnail | Profily týmů s realizačním týmem |
| **Hráči** | `hrac` | `cislo`, `rok_narozeni`, `tym_slug` | title | Soupisky hráčů |
| **Galerie** | `galerie` | — | title, editor, thumbnail | Fotoalba |
| **Sponzoři** | `sponzor` | `web_sponzora` | title, thumbnail | Partneři klubu |
| **Kontakty** | `kontakt` | `pozice`, `telefon`, `email`, `poradi` | title, thumbnail | Výbor klubu |

Všechny CPT mají vlastní `capability_type` s `map_meta_cap => true`, což umožňuje granulární řízení oprávnění (např. role „Správce obsahu" může editovat zápasy, ale ne mazat galerie).

### 4.2 Proč custom post types místo vlastních tabulek

WordPress nabízí dva přístupy k ukládání strukturovaných dat: custom post types s meta poli, nebo vlastní databázové tabulky. Pro tento projekt byly zvoleny CPT z následujících důvodů:

1. **Integrace s WordPress admin** — CPT automaticky získají administrační rozhraní (přehled, editace, mazání, košík) bez nutnosti psát vlastní CRUD logiku.
2. **Podpora taxonomií** — termy (sezóna, kategorie týmu) lze přiřazovat nativně.
3. **WP_Query** — dotazování na data probíhá standardním WordPress API, které řeší caching, stránkování i bezpečnost.
4. **REST API zdarma** — parametr `show_in_rest => true` zpřístupní data přes JSON API.
5. **Šablonová hierarchie** — WordPress automaticky mapuje URL na šablony (`/zapasy/` → `archive-zapas.php`, `/zapasy/nazev/` → `single-zapas.php`).

Alternativa s vlastními tabulkami by vyžadovala ruční implementaci administrace, validace, stránkování a URL routingu — mnohem více kódu s vyšším rizikem chyb.

### 4.3 Taxonomie

Projekt používá čtyři vlastní taxonomie registrované přes `register_taxonomy()`:

| Taxonomie | Slug | Typ | Sdílená mezi CPT | Účel |
|---|---|---|---|---|
| **Sezóna** | `sezona` | Tag (neierarchická) | zapas, tym, hrac, galerie | Filtrování podle ročníku (2024/25, 2025/26) |
| **Kategorie týmu** | `kategorie-tymu` | Kategorie (hierarchická) | zapas, tym, hrac, galerie | Muži A, Muži B, Dorost, Žáci, Přípravky |
| **Stav zápasu** | `stav-zapasu` | Tag | zapas | Nadcházející, Odehraný, Zrušený |
| **Pozice hráče** | `pozice-hrace` | Kategorie | hrac | Brankář, Obránce, Záložník, Útočník |

Všechny taxonomie mají `show_admin_column => true`, takže WordPress automaticky zobrazuje přiřazené termy jako sloupce v administraci.

### 4.4 Vazby mezi entitami

Vztahy mezi typy obsahu jsou řešeny dvěma mechanismy:

**Taxonomická vazba (M:N).** Sezóna a kategorie týmu jsou sdíleny mezi zápasy, týmy, hráči a galeriemi. Jeden zápas patří do sezóny 2025/26 a ke kategorii Muži A — obojí je přiřazeno jako term taxonomie.

**Meta pole vazba (1:N).** Hráči jsou propojeni s týmy prostřednictvím meta pole `tym_slug`. Každý hráč má uložen slug svého týmu (např. `muzi-a`), který odpovídá hodnotě `tym_slug` u příslušného týmu. Tento přístup byl zvolen místo přímého post-to-post vztahu, protože:

- nevyžaduje žádný externí plugin (např. Posts 2 Posts)
- umožňuje jednoduché dotazování přes `meta_query` ve `WP_Query`
- jeden tým může mít v různých sezónách různé hráče (filtrováno přes sdílenou taxonomii `sezona`)

Funkce `slavoj_count_hracu_tymu()` automaticky počítá hráče přiřazené k týmu na základě shodného `tym_slug` a sezóny, a výsledek ukládá do meta pole `pocet_hracu` pro rychlé zobrazení.

```
Zápas ──(taxonomie)──> Sezóna <──(taxonomie)── Tým
  │                                               │
  └──(taxonomie)──> Kategorie týmu <──(taxonomie)─┘
                                                  │
                                            (meta: tym_slug)
                                                  │
                                                Hráč
```

---

## 5. Řazení a kontextové filtrování taxonomií

### 5.1 Problém

WordPress řadí termy taxonomií abecedně. Pro fotbalový klub je ale přirozené hierarchické pořadí: Muži A → Muži B → Dorost → Starší žáci → … → Minipřípravka. Navíc kategorie „Stará garda" se má zobrazovat pouze v kontextu galerie (historické fotografie), nikoli u zápasů nebo hráčů.

### 5.2 Řešení

Pořadí je definováno centrálně ve funkci `slavoj_kategorie_poradi()`, která vrací asociativní pole slug → název v požadovaném pořadí. Řadící funkce `slavoj_sort_tymy()` seřadí libovolné pole `WP_Term` objektů podle tohoto kanonického pořadí.

Klíčovým prvkem je **globální filtr** na WordPress hooky `get_terms` a `get_the_terms`. Tento filtr se automaticky uplatní při každém dotazu na taxonomii `kategorie-tymu` — v administraci, na front-endu i v REST API. Po seřazení odfiltruje kontextově omezené termy na základě deklarativní mapy `slavoj_kategorie_kontextove_vyjimky()`.

Podrobný technický popis tohoto řešení je v dokumentu [14 – Admin UX a řazení taxonomií](./14-admin-ux-a-razeni-taxonomii.md).

---

## 6. Administrační rozhraní

### 6.1 Vlastní sloupce

Pro rychlý přehled v administraci jsou definovány vlastní sloupce:

**Zápasy:** Datum zápasu (formátovaný, řaditelný chronologicky), Tým (z taxonomie), Skóre.

**Hráči:** Číslo dresu (řaditelné numericky), Pozice (z taxonomie), Tým (čitelný název z meta pole přes `slavoj_get_team_display_name()`).

### 6.2 Dropdown filtry

Nad seznamem příspěvků jsou zobrazeny dropdown filtry implementované přes hook `restrict_manage_posts`:

| CPT | Filtry |
|---|---|
| Zápasy | Sezóna, Kategorie týmu |
| Hráči | Sezóna, Tým (meta pole — speciální dropdown načítající CPT Týmy) |
| Týmy | Sezóna |
| Galerie | Sezóna, Kategorie týmu |

Taxonomické filtry jsou zpracovány WordPressem automaticky. Filtr hráčů podle týmu vyžaduje vlastní `pre_get_posts` handler s `meta_query`, protože propojení je přes meta pole, ne taxonomii.

### 6.3 Seedování ukázkových dat

Plugin poskytuje nástrojovou stránku (Nástroje → Slavoj nastavení), odkud lze jedním kliknutím vytvořit ukázková data — zápasy, týmy, hráče, kontakty a galerie pro testovací sezónu. Duplicity jsou přeskočeny na základě shody názvu a typu příspěvku.

---

## 7. Front-end šablony

### 7.1 Šablonová hierarchie

Projekt využívá WordPress šablonovou hierarchii. Pro custom post types jsou použity archivní šablony (`archive-*.php`) místo stránkových šablon (`page-*.php`), protože:

- URL jsou čistější (`/zapasy/` místo `/?page_id=42`)
- stránkování funguje nativně
- taxonomické filtry se integrují přirozeně přes query parametry

Stránkové šablony zůstávají pouze pro statický obsah: historie, kontakty, sponzoři, aktuality.

| Šablona | URL | Obsah |
|---|---|---|
| `front-page.php` | `/` | Nadcházející zápasy (1 na kategorii), aktuality |
| `archive-zapas.php` | `/zapasy/` | Filtrovaný seznam zápasů |
| `archive-tym.php` | `/tymy/` | Karty týmů s realizačním týmem |
| `archive-galerie.php` | `/galerie/` | Grid fotoalb |
| `single-zapas.php` | `/zapasy/{slug}/` | Detail zápasu s výsledkem a střelci |
| `single-tym.php` | `/tymy/{slug}/` | Profil týmu, soupiska, nejbližší zápasy |
| `single-hrac.php` | `/hraci/{slug}/` | Profil hráče |
| `single-galerie.php` | `/galerie/{slug}/` | Fotoalbum s lightboxem |
| `page-historie.php` | `/historie/` | Historie klubu (z editoru) |
| `page-kontakty.php` | `/kontakty/` | Kontakty výboru (z CPT Kontakty) |
| `page-sponzori.php` | `/sponzori/` | Loga partnerů (z CPT Sponzoři) |

### 7.2 Znovupoužitelné části (template-parts)

Pro opakující se komponenty jsou vytvořeny template-parts:

- **`card-match.php`** — karta zápasu (domácí vs hosté, datum, skóre)
- **`hero-team.php`** — hero sekce s názvem týmu a trenéry
- **`site-header.php`** — hlavička webu s navigací
- **`site-footer.php`** — patička webu

---

## 8. Bezpečnost

Projekt dodržuje standardní bezpečnostní praktiky WordPressu:

- **Sanitizace vstupů:** Všechna data z formulářů procházejí přes `sanitize_text_field()` nebo `sanitize_email()`.
- **Escapování výstupů:** Veškerý výstup do HTML používá `esc_html()`, `esc_attr()` nebo `esc_url()`.
- **Nonce ochrana:** Každý meta box formulář je chráněn WordPress nonce tokenem (`wp_nonce_field` / `wp_verify_nonce`).
- **WP_Query:** Databázové dotazy využívají výhradně WordPress API, nikoli přímé SQL.
- **Capability mapping:** Každý CPT má vlastní `capability_type`, což umožňuje granulární řízení oprávnění.

---

## 9. Lokální vývojové prostředí

### 9.1 XAMPP (primární)

1. Nainstalovat [XAMPP](https://www.apachefriends.org/), spustit Apache a MySQL.
2. Stáhnout WordPress do `C:\xampp\htdocs\fotbal_club\`.
3. Naklonovat repozitář: `git clone https://github.com/Nomoos/lukasbejcek.git`
4. Spustit `web/install.bat` — skript zkopíruje téma a plugin do WordPress instalace.
5. V administraci aktivovat téma (Vzhled → Témata) a plugin (Pluginy → Slavoj Custom Fields).
6. Naimportovat ukázková data: Nástroje → Slavoj nastavení → „Vytvořit ukázková data".

Skript `install.bat` při každém spuštění nejprve stáhne nejnovější změny z Gitu (`git pull`), smaže případné staré verze tématu a pluginu v cílové instalaci, a nakopíruje aktuální soubory ze složky `web/wp-content/`.

### 9.2 wp-env (alternativa)

Pro vývojáře se znalostí Dockeru je k dispozici konfigurace `.wp-env.json`:

```json
{
  "core": null,
  "plugins": [ "./web/wp-content/plugins/slavoj-custom-fields" ],
  "themes": [ "./web/wp-content/themes/tj-slavoj-myto" ],
  "config": {
    "WP_HOME": "http://localhost:8888",
    "WP_SITEURL": "http://localhost:8888"
  }
}
```

Spuštění: `wp-env start` z kořene repozitáře. WordPress bude dostupný na `http://localhost:8888`.

### 9.3 Nasazení na hosting (FTP)

Pro produkční nasazení se nahrají na hosting pouze dvě složky:

```
web/wp-content/themes/tj-slavoj-myto/   →   wp-content/themes/tj-slavoj-myto/
web/wp-content/plugins/slavoj-custom-fields/  →   wp-content/plugins/slavoj-custom-fields/
```

Po nahrání je nutné v administraci aktivovat téma a plugin, nastavit permalinky (Nastavení → Trvalé odkazy → Název příspěvku) a naimportovat obsah.

---

## 10. Přehled klíčových rozhodnutí

| Rozhodnutí | Alternativa | Důvod volby |
|---|---|---|
| CPT místo vlastních tabulek | Vlastní DB tabulky | Automatická administrace, WP_Query, REST API, šablonová hierarchie |
| Meta pole místo ACF pluginu | Advanced Custom Fields | Žádná závislost na externím pluginu, plná kontrola nad kódem |
| `tym_slug` meta vazba místo post-to-post | Plugin Posts 2 Posts | Jednoduché dotazování přes meta_query, bez externích závislostí |
| `archive-*.php` místo `page-*.php` | Stránkové šablony | Čisté URL, nativní stránkování, přirozená integrace filtrů |
| Globální filtr řazení taxonomií | Manuální řazení na každém místě | Centralizace, konzistence, eliminace duplicit |
| Bootstrap 5 z CDN | npm install + bundler | Žádné build nástroje, jednodušší deployment |
| Minimální vanilla JS | React / Vue | Odpovídá zaměření studia, nižší složitost údržby |
| Komponenty CSS (oddělené soubory) | Jeden velký CSS soubor | Přehlednost, snadná údržba, WordPress dependency chain |
