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
| 7 | Optimalizace (lazy, WebP, minifikace) | ⚠️ částečně |
| 8 | SEO | ⚠️ jen alt atributy + meta tagy v kódu |
| 8b | Analytika (GA4) | ⚠️ plánováno, nestihnuto |
| 9 | Hosting + HTTPS | ✅ |
| 10 | Uživatelské role | ✅ vlastní RBAC |

### SPEAKER NOTES
Začnu shrnutím splnění zadání. Splněno: vlastní téma v Bootstrapu 5, šest vlastních typů obsahu — což jsou tři navíc oproti zadání — čtyři taxonomie, filtrování, lightbox galerie, role, hosting a HTTPS.

Bod 5 zadání — kalendářový modul — je vyřešen funkčním ekvivalentem ve dvou částech. Na homepage je interaktivní banner s nadcházejícími zápasy ve vizuálních kartách. Na stránce Zápasy jsou filtry podle týmu, sezóny a stavu. Aktuální řešení je pro klubový web plně dostačující — klub na webu prezentuje pouze zápasy. Plnohodnotný gridový kalendář by dával smysl až ve chvíli, kdy by web prezentoval i další události — klubové akce, prodej lístků.

Body 7 a 8 — optimalizace a SEO — jsou aktuálně řešeny jen na úrovni kódu: minifikace, WebP, lazy loading, alt atributy a meta tagy v hlavičce. Cache plugin a SEO plugin jsou v plánu pro produkční nasazení.

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
| Advanced Custom Fields | register_meta + jen potřebné meta boxy |
| CPT UI | register_post_type ve functions.php, bez UI builderu |
| FacetWP | WP_Query + GET parametry, jen použité filtry |
| User Role Editor | add_role + current_user_can, jen reálné role klubu |

Většina funkcionality vychází z principů doporučených pluginů — portována jen optimalizovaná výseč.
Tři důvody: porozumění WP API · nezávislost na breaking changes · obhajitelnost

### SPEAKER NOTES
Klíčovým technickým přínosem práce je přístup ke správě obsahu vlastním kódem místo plné integrace doporučených pluginů.

Práce má dva samostatné deployment artefakty: šablonu tj-slavoj-myto a vlastní WordPress plugin slavoj-custom-fields. Šablona řeší prezentační logiku, plugin řeší správu obsahu — admin meta boxy, validace, role.

Většina funkcionality vychází z principů doporučených pluginů ACF, CPT UI, FacetWP a User Role Editor. Místo plné integrace jsem prošel jejich logiku a portoval do vlastního pluginu jen optimalizovanou výseč — pouze ty části, které projekt skutečně potřebuje. Tím jsem se vyhnul balastu, který by plné pluginy přinášely: stovky řádků pro UI builder, který nevyužívám, lokalizace pro desítky jazyků a advanced fields bez uplatnění v tomto projektu.

Pro tento přístup jsem měl tři důvody. Za prvé, hlubší porozumění WordPress API — abych mohl něco optimalizovat, musel jsem nejdřív pochopit, jak to funguje. Za druhé, nezávislost na breaking changes externích pluginů. ACF mělo breaking changes v meta API mezi verzemi 5 a 6, naproti tomu WordPress core funkce register_post_type a register_meta jsou stabilní víc než deset let. Za třetí, obhajitelnost — mohu vysvětlit každý řádek kódu, ne pouze klik v administraci.

Výhody tohoto přístupu: lepší výkon, transparentnost a žádná závislost na třetích stranách. Nevýhody: víc kódu k údržbě a žádný UI builder pro netechnické adminy. Pro klubový web s nízkou frekvencí změn převažují výhody.

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
Datový model je postavený na šesti vlastních typech obsahu a čtyřech taxonomiích. Sezóna je taxonomie, ne meta pole, protože je sdílená napříč zápasy, týmy a galeriemi. Kategorie týmu funguje stejně.

Sdílení taxonomií napříč CPT je vědomé designové rozhodnutí. Kategorie „Stará garda" se prakticky používá jen u galerií, ale technicky je dostupná i pro zápasy. Filtrování ve frontendu běží přes WP_Query s tax_query, takže zápas omylem přiřazený ke Staré gardě se v UI mimo její archiv nezobrazí. Data v databázi by ale byla nekonzistentní. Správné řešení je validační vrstva v save_post hooku, která pro CPT zapas povolí jen reálné kategorie A-mužstvo až Minipřípravka.

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
Dokumentace má čtyřicet stran a oponent ji hodnotí jako vynikající. Obsahuje analýzu původního řešení, srovnávací tabulky pluginy versus vlastní implementace, ER diagram datového modelu, popis nasazení obou artefaktů — šablony i pluginu slavoj-custom-fields — testovací scénáře a příručku administrátora. Shoda s externími zdroji je nula procent.

---

## SLIDE 7 — Slabá místa a rozšíření (1:20)

### TITULEK
Slabá místa a rozšíření

### OBSAH (tabulka)
| Téma | Postoj | Plán |
|------|--------|------|
| Lazy loading na LCP banneru | uznávám | výjimka pro hero / nativní wp_get_attachment_image |
| AJAX filtry | vědomá volba (SEO URL) | wp_ajax + History API |
| Cache plugin | uznávám | LiteSpeed Cache |
| SEO plugin | uznávám — jen alt atributy a meta tagy v kódu | Yoast / Rank Math |
| GA4 (analytika) | plánováno, časově nestihnuto | wp_head hook nebo Site Kit |
| Plnohodnotný kalendář | aktuální řešení dostačující | FullCalendar.js pouze při rozšíření na další typy událostí |

### SPEAKER NOTES
Závěrem chci sám zmínit body, které v práci považuji za slabší.

Lazy loading je oprávněná připomínka oponenta. Implementoval jsem ho přes str_replace na všechny img tagy, včetně LCP obrázku v hero sekci. To zhoršuje Largest Contentful Paint a Core Web Vitals. V další verzi bych ho řešil výjimkou pro hero sekci, nebo lépe přechodem na nativní wp_get_attachment_image z WordPress 5.5+, který první obrázek na stránce vyloučí automaticky a navíc přidá fetchpriority=high.

AJAX filtry jsou vědomá volba. Aktuální GET implementace je SEO-friendly, indexovatelná a sdílitelná odkazem. AJAX by ale byl legitimní upgrade — endpoint wp_ajax_filter_zapasy v pluginu, fetch na admin-ajax.php, HTML fragment přes get_template_part a History API kvůli sdílitelnosti URL.

SEO plugin uznávám jako mezeru — řešil jsem pouze alt atributy u obrázků a meta tagy v hlavičce. Dedikovaný plugin Yoast nebo Rank Math by přidal sitemap, breadcrumbs a structured data. Google Analytics 4 byla plánována do odevzdání, časově se nestihla — napojení je triviální přes wp_head hook. Cache plugin také chybí — pro produkci doplním LiteSpeed Cache.

Plnohodnotný gridový kalendář považuji v tomto kontextu za overkill. Pro klub, jehož web prezentuje jen zápasy, je banner s nadcházejícími zápasy a filtry tým/sezóna/stav plně dostačující. Knihovnu jako FullCalendar.js bych přidával až ve chvíli, kdy by web prezentoval i jiné události — klubové akce, prodej lístků.

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
