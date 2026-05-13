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

Veškerá funkcionalita implementována **vlastním PHP kódem** místo doporučených pluginů (ACF, CPT UI, FacetWP, User Role Editor). Motivace: hlubší porozumění WordPress API, nezávislost na breaking changes externích pluginů, transparentnost a obhajitelnost každého řádku kódu.

## Použité technologie

WordPress 6.x · PHP · MySQL · Bootstrap 5.3.3 · HTML5 (`<dialog>` pro lightbox) · vlastní CSS (mobile-first) · WordPress REST API · WP\_Query · `register_post_type` / `register_meta` · hosting s HTTPS

## Hlavní reakce na posudky

| Připomínka | Reakce |
|------------|--------|
| **Vlastní implementace vs. ACF/CPT UI** | Vědomé rozhodnutí — stabilní WP core API, nezávislost na breaking changes externích pluginů. |
| **Kalendářový modul** | Řešen funkčním ekvivalentem: interaktivní banner s nadcházejícími zápasy + filtry tým/sezóna/stav. Plnohodnotný kalendář jako rozšíření. |
| **Lazy loading na LCP banneru** | Oprávněná připomínka. V další verzi vyřeším výjimkou pro hero sekci, nebo přechodem na nativní `wp_get_attachment_image` (WP 5.5+). |
| **Sdílená taxonomie napříč CPT** | Designové rozhodnutí. Konzistenci dat by zajistila validační vrstva v `save_post` hooku. |
| **AJAX filtry** | Aktuální GET implementace je vědomá volba (SEO, sdílitelná URL). AJAX doplnitelný přes `wp_ajax` endpoint + History API. |
| **Cache a SEO plugin** | Uznávám, v projektu chybí. Pro produkci doplním LiteSpeed Cache a Yoast (nebo Rank Math) s GA4. |

## Možná rozšíření

- AJAX filtry s History API
- Plnohodnotný gridový kalendář přes FullCalendar.js a WP REST API
- Cache plugin LiteSpeed Cache
- SEO plugin Yoast / Rank Math + napojení na Google Analytics 4

---

*Hodnocení posudků: chvalitebně (Bělský), chvalitebně (Háka). Shoda s ostatními zdroji: 0 %.*
