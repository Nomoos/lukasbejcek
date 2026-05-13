# INTERNÍ PŘÍPRAVA K Q&A (autor, NE pro komisi)

> ⚠️ **POZOR:** Tento dokument **NENÍ určen k rozdání komisi**. Slouží Lukášovi pro **vlastní přípravu na Q&A** — detailní odpovědi k natrénování, banka pravděpodobných otázek.
>
> Pro komisi se rozdává krátký **`STRUCNY-PREHLED.md`** (1 strana A4, neutrální).

**Maturitní práce:** Webová prezentace fotbalového klubu s databází zápasů a správou obsahu ve WordPressu
**Autor:** Lukáš Bejček · **Vedoucí:** Mgr. Jaromír Háka · **Oponent:** Mgr. Miloslav Bělský

---

## STRANA 1 — Souhrn projektu

### Co je odevzdáno

| Artefakt | Cesta | Popis |
|----------|-------|-------|
| **Šablona** | `web/wp-content/themes/tj-slavoj-myto/` | Prezentační vrstva (frontend, Bootstrap 5) |
| **Vlastní plugin** | `web/wp-content/plugins/slavoj-custom-fields/` | Admin meta boxy, CPT registrace, role |
| **Dokumentace** | `DOKUMENTACE.pdf` | 40 stran, ER diagram, testovací scénáře |
| **Web na hostingu** | URL klubu | Živé demo dostupné |

### Splnění bodů zadání

| # | Bod zadání | Stav |
|---|------------|------|
| 1 | Návrh ve Figmě (desktop + mobil) | ✅ |
| 2 | Šablona v Bootstrapu 5 | ✅ vlastní |
| 3 | CPT zápas / tým / hráč | ✅ + 3 navíc (galerie, sponzor, kontakt) |
| 4 | Filtrování dle sezóny / týmu | ✅ GET parametry |
| **5** | **Kalendářový modul** | 🔄 funkční ekvivalent: banner na homepage + filtry na /zapasy |
| 6 | Galerie | ✅ s lightboxem |
| 7 | Optimalizace (cache, lazy, WebP, minifikace) | ⚠️ částečně — cache plugin v plánu (LiteSpeed) |
| 8 | SEO | ⚠️ řešeno jen alt atributy + meta tagy v kódu |
| 8b | Analytika (GA4) | ⚠️ plánováno do odevzdání, časově nestihnuto |
| 9 | Hosting + HTTPS | ✅ |
| 10 | Role | ✅ vlastní RBAC (`add_role` + `current_user_can`) |

### Klíčové architektonické rozhodnutí

**Vlastní implementace = portovaná optimalizovaná výseč z principů doporučených pluginů.**

Většina funkcionality vychází z principů doporučených pluginů (ACF, CPT UI, FacetWP, User Role Editor). Místo plné integrace jsem prošel jejich logiku a portoval do vlastního pluginu `slavoj-custom-fields` jen optimalizovanou výseč — pouze to, co projekt skutečně potřebuje.

| Doporučený plugin | Portovaná optimalizovaná výseč |
|---|---|
| ACF | `register_meta` + jen potřebné meta boxy |
| CPT UI | `register_post_type` v `functions.php`, bez UI builderu |
| FacetWP | `WP_Query` + GET parametry, jen použité filtry |
| User Role Editor | `add_role` + `current_user_can`, jen reálné role klubu |

**Důvody:** porozumění WP API (abych mohl optimalizovat, musel jsem nejdřív pochopit) · nezávislost na breaking changes externích pluginů · obhajitelnost každého řádku kódu.

### Hodnocení posudků

| Sekce | Bělský (oponent) | Háka (vedoucí) |
|-------|------------------|----------------|
| Splnění zadání | většinově splněno | většinově splněno |
| Praktická část | velmi dobrá | velmi dobrá |
| Dokumentace | **vynikající** | velmi dobrá |
| Jazyk | velmi dobrá | velmi dobrá |
| Aktivita studenta | — | velmi dobrá |
| **Celkové** | **chvalitebně** | **chvalitebně** |
| Shoda s odevzdej.cz | — | **0 %** |

---

## STRANA 2 — Odpovědi na otázky z posudků

### Bělský — Q1: Proč jste se rozhodl nepoužít plugin Advanced Custom Fields?

Nejde o nepoužití principů — většina funkcionality vychází z principů ACF a dalších doporučených pluginů. **Místo plné integrace jsem portoval do vlastního pluginu jen optimalizovanou výseč** — pouze ty části, které projekt potřebuje. Tři důvody: **(1)** hlubší porozumění WordPress API (abych mohl optimalizovat, musel jsem nejdřív pochopit logiku) — oponent v posudku ocenil. **(2)** Nezávislost na breaking changes ACF (přechod ACF 5 → 6 měl breaking changes v meta API). WordPress core funkce `register_post_type` a `register_meta` jsou stabilní 10+ let. **(3)** Obhajitelnost — můžu vysvětlit každý řádek kódu, ne pouze klik v administraci. U klubového webu s nízkou frekvencí změn převažují výhody minimální údržby.

### Bělský — Q2: Sdílená taxonomie `kategorie-tymu` a kategorie „Stará garda"

Filtrování ve frontendu běží přes `WP_Query` s `tax_query`. Zápas omylem přiřazený ke Staré gardě by se ve frontendu **nezobrazil mimo její archiv**, ale **data v databázi by byla nekonzistentní**. Správné řešení je validační vrstva v `save_post` hooku — pro CPT `zapas` povolit jen kategorie A-mužstvo až Minipřípravka, Starou gardu jen pro CPT `galerie`. Alternativou je registrovat taxonomii samostatně pro každý CPT, ale to by ztratilo výhodu sdílení.

### Bělský — Q3: Lazy loading přes `str_replace` — rizika a LCP?

**Plně uznávám výtku.** Banner v hero sekci je LCP element a `loading="lazy"` zhorší Largest Contentful Paint a Core Web Vitals. **Správné řešení:** výjimka pro hero sekci (regex se selektorem), nebo **lépe nativní `wp_get_attachment_image` z WordPress 5.5+**, který první obrázek na stránce vyloučí automaticky a navíc přidá `fetchpriority="high"`.

### Háka — Q1: Výhody a nevýhody vlastní implementace oproti pluginům?

Vlastní implementace v projektu znamená **portovaná optimalizovaná výseč** z principů doporučených pluginů, ne kód od nuly. **Výhody:** lepší výkon (žádné nevyužité features), transparentnost, žádná závislost na třetích stranách, hlubší porozumění kódu, obhajitelnost. **Nevýhody:** víc kódu k údržbě, žádný UI builder pro netechnické adminy, žádná komunita pro support. Pro klubový web s nízkou frekvencí změn převažují výhody.

### Háka — Q2: Jak rozšířit filtry o AJAX?

Vytvořil bych endpoint `wp_ajax_filter_zapasy` + `wp_ajax_nopriv_filter_zapasy` v pluginu `slavoj-custom-fields`. JavaScript `fetch` na `admin-ajax.php` s parametry tým/sezóna/stav, server vrátí HTML fragment přes `get_template_part`, klient replace přes `innerHTML` kontejneru. URL synchronizovaná přes History API kvůli sdílitelnosti odkazu. Aktuální GET implementace je ale vědomá volba — je SEO-friendly a indexovatelná.

### Háka — Q3: Jak implementovat plnohodnotný kalendářový modul?

Plnohodnotný gridový kalendář považuji v tomto kontextu za **overkill**. Pro klub, jehož web prezentuje pouze zápasy, je aktuální řešení — banner s nadcházejícími zápasy plus filtry tým/sezóna/stav — z hlediska potřeb plně dostačující.

Knihovnu jako FullCalendar.js bych přidával až ve chvíli, kdy by se web rozšířil o **další typy událostí**: klubové akce, ples, prodej lístků, jiné týmové aktivity. V tom případě jsou tři varianty: **(1)** vlastní šablona `page-kalendar.php` s gridem 7×5, generovaným z `WP_Query` orderby date. **(2)** knihovna `FullCalendar.js` napojená na WordPress REST API endpoint `/wp-json/wp/v2/zapas` s vlastním transformerem dat. **(3)** plugin The Events Calendar, ale vyžaduje mapování CPT `zapas` na CPT pluginu.

---

## Doplňující odpovědi (možné dotazy komise)

### Vlastní plugin `slavoj-custom-fields` — co dělá a proč je to plnohodnotný plugin?

Plugin je nasazený standardním WordPress deployment cyklem v `wp-content/plugins/slavoj-custom-fields/` s vlastním header komentářem, aktivuje se v administraci. Funkčnost: admin meta boxy pro CPT `zapas` (datum, soupeř, skóre, střelci), validace vstupů, registrace vlastních rolí (klubový admin, přispěvatel zápasů), kontextové filtrování taxonomií. **Z hlediska deploymentu je identický s externími pluginy** — separuje prezentační logiku (šablona) od správy obsahu (plugin), stejný princip jako kombinace standardní téma + ACF.

### Proč Bootstrap 5 a ne Tailwind?

Bootstrap má grid systém a komponenty pro responzivitu out-of-the-box. Pro maturitní úroveň je obhajitelnější — píšu standardní HTML třídy, ne utility-first. Menší overhead — z CDN, bez build pipeline.

### Bezpečnost — XSS přes admin formulář?

Nonce v každém formuláři (`wp_nonce_field` + `check_admin_referer`), `sanitize_text_field` na vstup, `esc_html` / `esc_attr` na výstup, `current_user_can` pro kontrolu rolí. WordPress konvence.

### Proč 0 % shoda na odevzdej.cz?

Kód šablony i pluginu je vlastní. WordPress hook system a template hierarchy nejsou plagiát — to je standardní API použití, jako `import` knihovny v Pythonu. Kdyby šablona pocházela z marketplace, shoda by byla 50 % a víc.

### Co byste dělal jinak?

**(1)** Více času na SEO plugin a Google Analytics 4 — věděl jsem o nich, stihl jsem jen základní meta tagy v hlavičce a alt atributy u obrázků. **(2)** AJAX filtry pro lepší UX bez page reloadu. **(3)** Podrobnější administrátorská příručka pro vedení klubu — aktuálně je technická.
