# Copy-paste sheet pro Google Slides

> **Pro koho:** Lukáš, při manuální tvorbě slidů v Google Slides.
> **Jak používat:** Otevři Google Slides → Blank prezentace → pro každý slide níže vytvoř nový slide, paste TITULEK + OBSAH + (volitelně) SPEAKER NOTES.
> **Speaker notes:** v Google Slides klikni dolů na panel „Click to add speaker notes" — paste tam mluvený text.

---

## SLIDE 1 — Titulní (0:20)

### TITULEK
Webová prezentace fotbalového klubu
s databází zápasů a správou obsahu ve WordPressu

### OBSAH
Autor: Lukáš Bejček
Obor: Informační technologie · Rok: 2026

Vedoucí: Mgr. Jaromír Háka
Oponent: Mgr. Miloslav Bělský

40 stran dokumentace · 0 % shoda · web nasazený

### SPEAKER NOTES
Dobrý den, jmenuji se Lukáš Bejček. Moje maturitní práce se zabývá tvorbou vlastní WordPress šablony pro fotbalový klub TJ Slavoj Mýto. Vedoucím práce byl pan Háka, oponentem pan Bělský. Web je nasazený na hostingu, v rámci dotazů ho mohu ukázat živě.

---

## SLIDE 2 — Splnění zadání (1:30)

### TITULEK
Splnění zadání

### OBSAH (tabulka)
| # | Bod zadání | Stav |
|---|-----------|------|
| 1 | Návrh ve Figmě (desktop + mobil) | ✅ |
| 2 | Vlastní šablona v Bootstrapu 5 | ✅ |
| 3 | CPT zápas / tým / hráč | ✅ + 3 navíc |
| 4 | Filtrování dle sezóny / týmu | ✅ GET parametry |
| 5 | Kalendářový modul | 🔄 funkční ekvivalent: banner + filtry |
| 6 | Galerie s lightboxem | ✅ |
| 7 | Optimalizace (cache, lazy, WebP) | ⚠️ částečně |
| 8 | SEO a analytika | ⚠️ plán |
| 9 | Hosting + HTTPS | ✅ |
| 10 | Uživatelské role | ✅ vlastní RBAC |

### SPEAKER NOTES
Pojďme po sekcích posudku. První oblast — splnění zadání. Splněno: vlastní téma v Bootstrapu 5, šest custom post types (o tři navíc oproti zadání), čtyři taxonomie, filtrování, lightbox galerie, role, hosting a HTTPS.

Bod 5 zadání — kalendářový modul — vyřešen funkčním ekvivalentem ve dvou částech. Na homepage je interaktivní banner s nadcházejícími zápasy ve vizuálních kartách — to pokrývá vizuální zobrazení. Na stránce Zápasy jsou filtry podle týmu, sezóny a stavu — to pokrývá kategorické a časové filtrování. Společně plní stejnou UX funkci jako dedikovaný kalendář, ale bez závislosti na pluginu třetí strany. Plnohodnotný gridový kalendář by se dal doplnit přes knihovnu FullCalendar.js napojenou na WordPress REST API.

Body 7 a 8 — cache plugin a SEO plugin — jsou aktuálně řešeny částečně, na úrovni kódu (minifikace, WebP, meta tagy). Pro produkci je plán LiteSpeed Cache plus Yoast nebo Rank Math.

---

## SLIDE 3 — Vlastní implementace (1:20)

### TITULEK
Vlastní implementace místo pluginů

### OBSAH
Dva deployment artefakty:
• šablona tj-slavoj-myto
• vlastní plugin slavoj-custom-fields

| Doporučený plugin | Nahrazeno |
|-------------------|-----------|
| Advanced Custom Fields | register_meta + admin meta boxy v pluginu |
| CPT UI | register_post_type ve functions.php |
| FacetWP | WP_Query + GET parametry |
| User Role Editor | add_role + current_user_can |

Tři důvody: porozumění WP API · nezávislost na breaking changes · obhajitelnost

### SPEAKER NOTES
Druhá oblast — kvalita praktické části a vlastní přínos. Oba posudky jako hlavní přínos uvádějí, že nepoužívám doporučené pluginy a vše implementuji vlastním PHP kódem.

Práce má dva samostatné deployment artefakty: šablonu tj-slavoj-myto a vlastní WordPress plugin slavoj-custom-fields. Šablona řeší prezentační logiku — frontend, template hierarchy, styling. Plugin řeší správu obsahu — admin meta boxy, validace, registraci vlastních rolí. To je stejný architektonický princip jako kombinace standardní šablony plus ACF — jen že plugin je psaný mnou. Plugin je nasazený standardním způsobem v wp-content/plugins/, má vlastní header komentář a aktivuje se v administraci.

Klíčové rozhodnutí — proč jsem nepoužil Advanced Custom Fields. Tři důvody. Za prvé, hlubší porozumění WordPress API — to oponent v posudku ocenil. Za druhé, nezávislost na breaking changes externích pluginů. ACF mělo breaking changes v meta API mezi verzemi 5 a 6, naproti tomu WordPress core funkce register_post_type a register_meta jsou stabilní víc než deset let. Za třetí, obhajitelnost — mohu vysvětlit každý řádek kódu, ne pouze klik v administraci.

Výhody a nevýhody tohoto přístupu. Výhody: porozumění, žádná závislost na třetích stranách, lepší výkon, transparentnost. Nevýhody: víc kódu k údržbě, žádný UI builder pro netechnické adminy. Pro klubový web s nízkou frekvencí změn převažují výhody.

---

## SLIDE 4 — Datový model (1:00)

### TITULEK
Datový model

### OBSAH
6 vlastních typů obsahu (CPT):
zapas · tym · hrac · galerie · sponzor · kontakt

4 taxonomie (sdílené napříč CPT):
sezona · kategorie-tymu · stav-zapasu · pozice-hrace

Klíčový designový rys: sdílená taxonomie napříč CPT
Konzistenci dat by zajistil save_post hook s validací.

### SPEAKER NOTES
Datový model — šest typů obsahu, čtyři taxonomie. Sezóna je taxonomie, ne meta pole, protože je sdílená napříč zápasy, týmy a galeriemi. Kategorie týmu funguje stejně.

Klíčový designový rys — sdílená taxonomie napříč CPT. Kategorie „Stará garda" se prakticky používá jen u galerií, ale technicky je dostupná i pro zápasy. Filtrování ve frontendu běží přes WP_Query s tax_query, takže zápas omylem přiřazený ke Staré gardě se v UI mimo její archiv nezobrazí. Data v databázi by ale byla nekonzistentní. Správné řešení je validační vrstva v save_post hooku, která pro CPT zapas povolí jen kategorie A-mužstvo až Minipřípravka, a Starou gardu jen pro CPT galerie.

---

## SLIDE 5 — Galerie a ostatní stránky (1:00)

### TITULEK
Galerie a ostatní stránky

### OBSAH
• Galerie — alba taxonomií sezóna, nativní HTML <dialog> pro lightbox
• Sponzoři — vlastní CPT, admin spravuje partnery klubu
• Kontakty — strukturované role v klubu
• Banner na homepage — nadcházející zápasy ve vizuálních kartách

Web nasazený na produkčním hostingu — pro Q&A otevřený v prohlížeči.

### SPEAKER NOTES
Kromě zápasů má web galerii s lightboxem, stránku sponzorů a kontakty. Galerie využívá sezónu jako taxonomii — alba se filtrují podle ročníku, stejně jako zápasy. Lightbox je nativní HTML dialog element, bez JavaScriptu mimo otevírací handler. Sponzoři jsou vlastním CPT — administrátor klubu může přidávat partnery včetně loga a odkazu. Web je nasazený na produkčním hostingu, otevřu ho v rámci dotazů, pokud bude komise chtít cokoliv vidět živě.

---

## SLIDE 6 — Dokumentace (0:30)

### TITULEK
Dokumentace

### OBSAH
40 stran · 0 % shoda · „vynikající" hodnocení

• Analýza původního řešení
• Srovnávací tabulky (pluginy vs. vlastní)
• ER diagram datového modelu
• Popis nasazení obou artefaktů
• Testovací scénáře
• Příručka administrátora

### SPEAKER NOTES
Třetí a čtvrtá oblast — dokumentace a jazyk. Dokumentace má čtyřicet stran, oponent ji hodnotí jako vynikající. Obsahuje analýzu původního řešení, srovnávací tabulky pluginy versus vlastní implementace, ER diagram datového modelu, popis nasazení obou artefaktů — šablony i pluginu slavoj-custom-fields, testovací scénáře a příručku administrátora. Shoda s externími zdroji je nula procent.

---

## SLIDE 7 — Slabá místa a rozšíření (1:20)

### TITULEK
Slabá místa a rozšíření

### OBSAH (tabulka)
| Téma | Postoj | Plán |
|------|--------|------|
| Lazy loading na LCP banneru | uznávám | výjimka pro hero / nativní wp_get_attachment_image |
| AJAX filtry | vědomá volba (SEO) | wp_ajax + History API |
| Cache plugin | uznávám | LiteSpeed Cache |
| SEO + analytika | uznávám | Yoast / Rank Math + GA4 |
| Plnohodnotný kalendář | rozšíření | FullCalendar.js + REST API |

### SPEAKER NOTES
K oblastem, kde projekt má slabší body — chci je sám zmínit, protože je znám.

Lazy loading — toto je oprávněná připomínka oponenta. Implementoval jsem ho přes str_replace na všechny img tagy, včetně LCP obrázku v hero sekci. To zhorší Largest Contentful Paint a Core Web Vitals. V další verzi bych to řešil výjimkou pro hero sekci, nebo lépe přechodem na nativní wp_get_attachment_image z WordPress 5.5+, který první obrázek na stránce vyloučí automaticky a navíc přidá fetchpriority=high.

AJAX filtry — toto je vědomá volba. Aktuální GET implementace je SEO-friendly, indexovatelná, URL sdílitelná. AJAX by ale byl legitimní upgrade — endpoint wp_ajax_filter_zapasy v pluginu, fetch na admin-ajax.php, HTML fragment přes get_template_part, History API kvůli sdílitelnosti URL.

Cache a SEO plugin chybí — to uznávám. Pro produkční nasazení doplním LiteSpeed Cache a Yoast nebo Rank Math s napojením na Google Analytics. Jsou to triviální doplnění.

Plnohodnotný kalendář by se dal udělat přes FullCalendar.js a WordPress REST API — to je legitimní rozšíření, na kterém bych pracoval jako první po obhajobě.

---

## SLIDE 8 — Závěr (0:30)

### TITULEK
Děkuji za pozornost

### OBSAH
✅ 6 CPT, vlastní plugin
✅ Banner + filtry = funkční ekvivalent kalendáře
✅ Chvalitebně z obou posudků

Web mám otevřený v prohlížeči pro případné dotazy.

### SPEAKER NOTES
Projekt splňuje většinu bodů zadání s vlastní implementací bez závislosti na externích pluginech. Hlavní přínos je vlastní řešení CPT, meta polí, filtrace a rolí. Rozšíření jsou přímočará a popsaná v dokumentaci. Děkuji za pozornost. Web mám otevřený v prohlížeči, takže pokud bude komise chtít cokoliv vidět živě, mohu se kdykoli přepnout. Jsem připraven na otázky.

---

## DOPLNĚNÍ OBRÁZKŮ

Slidy 1, 4, 5 mohou mít obrázky:

| Slide | Doporučený obrázek |
|-------|---------------------|
| 1 | Logo TJ Slavoj Mýto vpravo dole + screenshot banneru |
| 4 | Mini ER diagram (CPT + taxonomie + šipky) — z dokumentace |
| 5 | 2-4 thumbnaily: galerie grid, lightbox modal, sponzoři, mobil |
| 7 | (nepotřeba) |

Zdroje pro screenshoty:
- Web: produkční hosting → otevři příslušnou stránku → Print Screen
- ER diagram: z `DOKUMENTACE.pdf`
- Logo: z dokumentačních zdrojů klubu

---

## DESIGN SETTINGS pro Google Slides

- **Téma:** Simple Light nebo Material vibrant (bílé pozadí)
- **Fonty:** Title — Roboto / Open Sans, 36-42pt · Body — Roboto / Open Sans, 22-26pt
- **Barvy:**
  - Pozadí: bílá (#FFFFFF)
  - Text: tmavě šedá (#1A1A1A) — NE čistá černá kvůli kontrastu
  - Akcent (nadpisy, proužky): klubová modrá (#003366 nebo přesnější odstín loga)
- **Tabulky:** hlavička barevná, řádky střídavě jemně podbarvené
- **Page numbers:** zapnout (Slide → Apply layout → with footer)
