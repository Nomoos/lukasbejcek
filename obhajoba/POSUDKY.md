# Posudky maturitní práce

**Autor:** Lukáš Bejček
**Název:** Webová prezentace fotbalového klubu s databází zápasů a správou obsahu ve WordPressu
**Škola:** Gymnázium a Střední odborná škola Rokycany

---

## 1. Posudek oponenta — Mgr. Miloslav Bělský

**Datum:** 24. 4. 2026

### I. Splnění zadání a cílů práce — **většinově splněno**
Práce splňuje většinu požadovaných bodů zadání: vlastní téma s Bootstrap 5, šest custom post types (zápasy, týmy, hráči, galerie, sponzoři, kontakty), čtyři taxonomie, filtrování přes GET parametry, lightbox galerie, uživatelské role, bezpečnostní opatření, nasazení na hosting.

**Nedostatky:**
- Záměrně vynechaný **kalendářní modul** (bod 5) a **SEO/analytika** (bod 8) — uvedeny pouze jako návrhy v závěru
- Z bodu 7 implementovány: minifikace CSS/JS (7c), konverze do WebP (7d), **cacheovací plugin (7a) nahrazen pouze CDN**
- Přestože student zdůvodňuje volbu vlastní implementace, zadání není zcela naplněno v původním zamýšleném rozsahu

### II. Kvalita praktické části — **velmi dobrá**
Klíčovým přínosem je **vědomé rozhodnutí nepoužívat doporučené pluginy** (ACF, CPT UI, FacetWP, User Role Editor) a místo toho implementovat vše vlastním PHP kódem. Tím student prokazuje hlubší porozumění WordPress API, než by bylo nutné pro splnění zadání.

- Architektura má správné rozdělení (prezentační logika, plugin administrační nástroje)
- Kód dodržuje WordPress konvence (`wp_enqueue_scripts`, template hierarchy, `WP_Query`, nonce ochrana, sanitizace, escapování)
- Čistá struktura HTML, ARIA atributy, hamburger menu bez vlastního JS

**Nedostatky:**
- Prázdný tag `<h5></h5>` v banner sekci úvodní stránky
- Meta tag `noindex, nofollow` by měl být na produkčním serveru odstraněn
- Galerie neukazuje fotografie online

### III. Obsahová úroveň dokumentace — **vynikající**
Dokumentace o 40 stranách v předepsaném rozsahu, logicky strukturovaná. Obsahuje analýzu původního řešení, srovnávací tabulky (pluginy vs. vlastní implementace, CPT vs. vlastní tabulky), ER diagram datového modelu, přehled šablon, ukázky kódu, testovací scénáře, příručku administrátora.

**Slabina:** chybí hodnocení výsledku z pohledu uživatelské zkušenosti.

### IV. Jazyková a grafická úroveň — **velmi dobrá**

### Celkové hodnocení: **CHVALITEBNĚ**

> Práce představuje nadstandardně řešený projekt pro maturitní úroveň. Žák prokázal schopnost samostatně navrhnout a implementovat komplexní webové řešení s dobrou technickou kulturou kódu. Za hlavní nedostatek považuji chybějící kalendářní modul, který byl explicitně požadován v zadání, a absenci SEO řešení. Tyto mezery jsou kompenzovány výrazně vyšší technickou hloubkou vlastní implementace oproti zadáním předpokládanému plug-and-play přístupu.

### Otázky pro obhajobu (Bělský)

1. **Proč jste se rozhodl nepoužít plugin Advanced Custom Fields** a jak byste obhájil toto rozhodnutí z hlediska dlouhodobé udržitelnosti projektu (aktualizace WordPress core, breaking changes v API)?
2. **Taxonomie `kategorie-tymu` je sdílena mezi zápasy, týmy, hráči i galeriemi**, ale kategorie "Stará garda" se zobrazuje jen v kontextu galerií. Jak je toto kontextové filtrování implementováno a co by se stalo, kdyby správce obsahu omylem přiřadil zápas ke "Staré gardě"?
3. V dokumentaci uvádíte **lazy loading přes `str_replace('<img', '<img loading="lazy"')`**. Jaká jsou rizika tohoto přístupu a v jakých případech by mohlo docházet k nežádoucímu chování (např. LCP obrázek v banner sekci)?

---

## 2. Posudek vedoucího práce — Mgr. Jaromír Háka

**Datum:** 27. 4. 2026

### I. Aktivita studenta a spolupráce s vedoucím — **velmi dobrá**
Žák pracoval samostatně a systematicky. Konzultace probíhaly pravidelně, student byl schopen reagovat na připomínky, několikrát plán předělával. Oproti běžnému standardu je patrná vyšší snaha řešit problémy vlastními silami, nikoliv pouze využíváním pluginů.

### II. Splnění zadání a cílů práce — **většinově splněno**
Plus: většina klíčových sekcí (zápasy, týmy, galerie, kontakty, sponzoři, historie), CPT a vlastní typy obsahu (`register_post_type`, custom fields) jsou implementovány korektně. Filtrování funkční pomocí `WP_Query` a GET parametrů.

**Chybí:** prokazatelné využití SEO pluginů a analytiky. Optimalizace výkonu je řešena pouze částečně (spíše na úrovni kódu, ne standardních pluginů).

### III. Kvalita praktické části — **velmi dobrá**
Žák nepoužívá doporučené pluginy, implementuje většinu funkcionality vlastní cestou (CPT, meta pole, filtrace, role). Některé části (např. kalendář) nejsou řešeny plnohodnotně, chybí pokročilejší prvky jako AJAX filtrování. Vlastní přínos je rozšířená práce s API.

### IV. Obsahová úroveň dokumentace — **velmi dobrá**
Návrh datového modelu (včetně ER diagramu), detailní popis implementace (CPT, taxonomie, filtrace, role), testovací scénáře. Menší slabinou je malý důraz na reálné nasazení (protože byly nahrazeny vlastními řešeními).

### V. Jazyková a grafická úroveň — **velmi dobrá**
Drobné stylistické nedostatky a jednoduché formulace. Grafická úprava standardní, strukturovaná, čitelná.

### Míra shodnosti s jinými zdroji (odevzdej.cz): **0 %**

### Celkové hodnocení: **CHVALITEBNĚ**

> Práce představuje kvalitní a funkční řešení webové prezentace fotbalového klubu. Žák prokázal velmi dobré technické znalosti, zejména v oblasti WordPress vývoje, kde řešil funkcionalitu implementací vlastní cestou bez závislosti na externích pluginech. Největší přínos práce spočívá právě v této vlastní implementaci (CPT, meta pole, filtrace, role). Rezervy jsou zejména ve splnění některých konkrétních bodů zadání (kalendář, SEO, optimalizace pomocí pluginů), které nejsou plně dotaženy.

### Otázky pro obhajobu (Háka)

1. **Jaké jsou výhody a nevýhody vlastní implementace** oproti použití pluginů (např. ACF, CPT UI)?
2. **Jak bys rozšířil současné filtrování o dynamické (AJAX) načítání dat?**
3. **Jak bys implementoval plnohodnotný kalendářový modul** v souladu se zadáním?

---

## Souhrn k obhajobě

| Kategorie | Oponent (Bělský) | Vedoucí (Háka) |
|-----------|------------------|----------------|
| Splnění zadání | většinově splněno | většinově splněno |
| Praktická část | velmi dobrá | velmi dobrá |
| Dokumentace | **vynikající** | velmi dobrá |
| Jazyk | velmi dobrá | velmi dobrá |
| **Celkové** | **chvalitebně** | **chvalitebně** |

### Slabá místa — připravené reakce v prezentaci

> **Pozn.:** Scope je locknut na prezentační materiály — kód se před obhajobou neupravuje. Tato místa jsou v prezentaci a Q&A zmíněna jako vědomá rozhodnutí nebo přijaté připomínky.

| Slabé místo | Kategorie reakce | Kde je v prezentaci |
|-------------|------------------|---------------------|
| Chybějící kalendářový modul (bod 5) | **OBHÁJIT** — funkční ekvivalent přes banner + filtry je pro klubový web dostačující; plnohodnotný grid by byl overkill, dával by smysl až s dalšími typy událostí (akce, prodej lístků) | slide 2 + slide 7 |
| SEO plugin (bod 8) | **PŘIJMOUT** — řešeno jen alt atributy a meta tagy v kódu | slide 7 |
| GA4 / analytika (bod 8) | **VYSVĚTLIT** — plánováno do odevzdání, časově nestihnuto | slide 7 |
| Cache plugin (bod 7a) | **PŘIJMOUT** — pouze CDN, plán LiteSpeed Cache | slide 7 |
| Lazy loading na LCP banneru | **PŘIJMOUT** — oprávněná připomínka, v další verzi výjimka nebo `wp_get_attachment_image` | slide 7 |
| Sdílená taxonomie napříč CPT (Stará garda) | **VYSVĚTLIT** — designové rozhodnutí, řešení v `save_post` hooku | slide 4 |
| Prázdný `<h5></h5>` v banneru | (jen pokud se zeptají) — drobné opomenutí | Q&A |
| Meta `noindex, nofollow` na produkci | (jen pokud se zeptají) — opomenutí, snadno opravitelné | Q&A |
| Galerie bez online fotek | (jen pokud se zeptají) — testovací data, klub teprve dodá produkční fotky | Q&A |

### Otázky pro obhajobu — sumář (6 otázek celkem)

**Architektura/rozhodnutí:**
- O1, H1: Proč vlastní implementace místo ACF/CPT UI? (udržitelnost, breaking changes)

**Technické detaily:**
- O2: Kontextové filtrování taxonomie sdílené napříč CPT ("Stará garda")
- O3: Rizika lazy loading přes `str_replace` (LCP, banner)

**Co chybí v zadání:**
- H2: Jak rozšířit filtrování o AJAX?
- H3: Jak implementovat plnohodnotný kalendář?
