# Stručný přehled k obhajobě maturitní práce

**Autor:** Lukáš Bejček · **Obor:** Informační technologie
**Téma:** Webová prezentace fotbalového klubu s databází zápasů a správou obsahu ve WordPressu
**Vedoucí:** Mgr. Jaromír Háka · **Oponent:** Mgr. Miloslav Bělský

---

## Cíl práce

Vytvořit moderní informační web fotbalového klubu TJ Slavoj Mýto se správou zápasů, týmů, galerií a partnerů. Web umožňuje vedení klubu samostatně spravovat obsah bez potřeby programátora.

## Výsledný produkt

- Vlastní WordPress šablona `tj-slavoj-myto` (mobile-first, responzivní)
- Vlastní WordPress plugin `slavoj-custom-fields` (admin meta boxy, validace, role)
- **6 vlastních typů obsahu:** zápas, tým, hráč, galerie, sponzor, kontakt
- **4 taxonomie:** sezóna, kategorie týmu, stav zápasu, pozice hráče
- Web nasazený na produkčním hostingu s HTTPS
- Dokumentace 40 stran (vč. ER diagramu, srovnávacích tabulek, testovacích scénářů, příručky administrátora)

## Vlastní přínos

Funkcionalita implementována ve vlastním pluginu `slavoj-custom-fields`. Většina vychází z principů doporučených pluginů (ACF, CPT UI, FacetWP, User Role Editor), ale **portována jen jako optimalizovaná výseč** — bez UI builderu, balastu lokalizací a advanced fields, které projekt nevyužívá. Motivace: hlubší porozumění WordPress API, nezávislost na breaking changes externích pluginů, transparentnost a obhajitelnost každého řádku kódu.

## Použité technologie

WordPress 6.x · PHP · MySQL · Bootstrap 5.3.3 · HTML5 (`<dialog>` pro lightbox) · vlastní CSS (mobile-first) · WordPress REST API · WP\_Query · `register_post_type` / `register_meta` · hosting s HTTPS

## Hlavní reakce na posudky

| Připomínka | Reakce |
|------------|--------|
| **Vlastní implementace vs. ACF/CPT UI** | Vědomé rozhodnutí — portovaná optimalizovaná výseč z doporučených pluginů místo plné integrace s balastem. Stabilní WP core API. |
| **Kalendářový modul** | Řešen funkčním ekvivalentem: interaktivní banner s nadcházejícími zápasy + filtry tým/sezóna/stav. Pro klubový web prezentující pouze zápasy je aktuální řešení plně dostačující; plnohodnotný gridový kalendář by dával smysl až s rozšířením o další typy událostí (klubové akce, prodej lístků). |
| **Lazy loading na LCP banneru** | Oprávněná připomínka. V další verzi vyřeším výjimkou pro hero sekci, nebo přechodem na nativní `wp_get_attachment_image` (WP 5.5+). |
| **Sdílená taxonomie napříč CPT** | Designové rozhodnutí. Konzistenci dat by zajistila validační vrstva v `save_post` hooku. |
| **AJAX filtry** | Aktuální GET implementace je vědomá volba (SEO, sdílitelná URL). AJAX doplnitelný přes `wp_ajax` endpoint + History API. |
| **SEO plugin** | Uznávám — řešil jsem pouze `alt` atributy a meta tagy v kódu (header.php). Dedikovaný plugin Yoast / Rank Math by přidal sitemap, breadcrumbs a structured data. |
| **GA4 (analytika)** | Plánováno do odevzdání, časově se nestihlo. Integrace přes `wp_head` hook nebo plugin Site Kit by Google je triviální. |
| **Cache plugin** | Uznávám, v projektu chybí. Pro produkci doplním LiteSpeed Cache. |

## Možná rozšíření

- AJAX filtry s History API
- Cache plugin LiteSpeed Cache
- SEO plugin (např. Yoast nebo Rank Math) — sitemap, breadcrumbs, structured data
- Google Analytics 4 přes `wp_head` hook
- Plnohodnotný gridový kalendář (přes FullCalendar.js) — pouze pokud by web prezentoval i další typy událostí mimo zápasy

---

*Hodnocení posudků: chvalitebně (Bělský), chvalitebně (Háka). Shoda s ostatními zdroji: 0 %.*
