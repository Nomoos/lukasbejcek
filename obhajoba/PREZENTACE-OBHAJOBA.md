# Prezentace k obhajobě maturitní práce — FINÁLNÍ PLÁN

**Autor:** Lukáš Bejček
**Téma:** Webová prezentace fotbalového klubu TJ Slavoj Mýto (WordPress)
**Délka:** max 8 minut · **Celá obhajoba:** max 20 min (5 min setup + 8 min prezentace + ~7 min Q&A)
**Hodnocení posudků:** chvalitebně (Bělský), chvalitebně (Háka), shoda 0 %
**Cíl obhajoby:** posun na výborně

---

## Praktické info z e-mailu komise

| Položka | Detail |
|---------|--------|
| Setup time | **5 min na začátku** — využít na cache warm-up + otevření slidů a tabů |
| Total | **20 min max** |
| Prezentace | max 8 min · **může obsahovat videoukázku** |
| Q&A | vedoucí + oponent pokládají otázky z posudků · mohou navázat další |
| Etiketa | **počkat na položení otázky**, neodpovídat předem |
| Místo | **kmenová třída** — pozor na kontrast text/pozadí v slidech |

### Důsledky pro design slidů a obsah:

1. **Bílé pozadí + tmavý text** (univerzální kontrast i v třídě s denním světlem). Žádné světle šedé pastely, žádný tmavomodrý text na modrém pozadí.
2. **Klubové barvy** jen jako akcenty (proužky, ikony, nadpisy), ne jako podklad.
3. **Mluvený text slidů 3, 4, 7 přerámován** — odpovědi na otázky jsou v autonomní argumentaci, NE jako „Pan Bělský se ptá…". Komise může otázku položit znovu v Q&A — Lukáš se pak odkáže „jak jsem zmínil v prezentaci…".
4. **Videoukázka jako Plan B** — komise výslovně připouští videoukázku. Pokud je obava o hosting, lze připravit ~30 s screencast (banner → filtry → galerie) a embed do slidu 5 nebo přehrát v Q&A.
5. **Demo proběhne v Q&A**, ne v hlavní prezentaci — viz sekce na konci.

### Setup 5 min checklist (před prezentací):

- [ ] Spustit slidy v prezentačním režimu (full screen)
- [ ] Browser tab → homepage klubu → **proklikat** všechny sekce kvůli cache warm-up
- [ ] Backup PDF screenshotů otevřený na druhém monitoru / mobilu
- [ ] Cue cards v ruce (vytištěné)
- [ ] Voda po ruce
- [ ] Hodinky / stopky viditelné
- [ ] Vypnuté notifikace na laptopu
- [ ] Mobilní hotspot zapnutý jako záloha sítě

---

> **Strukturální princip:** Prezentace volně sleduje strukturu posudků (I. Splnění zadání → II. Praktická část → III. Dokumentace → IV. Jazyk → Slabá místa). Odpovědi na všech 6 otázek z posudků jsou **vepsány přímo do mluveného textu příslušných slidů jako autonomní argumentace**, aby komise viděla, že tématu rozumíš, ale bez explicitního „Pan X se ptá".

---

## Návrh osnovy (8 slidů, sekce posudků jako kostra)

| # | Slide | Sekce posudku | Čas | Kam patří otázky |
|---|-------|---------------|-----|------------------|
| 1 | Titulní | — | 0:20 | — |
| 2 | **Splnění zadání** — co dodáno + reframe kalendáře | I. | 1:30 | Háka Q3 (kalendář) |
| 3 | **Vlastní implementace** — architektura & rozhodnutí o pluginech | II. | 1:20 | Bělský Q1 (ACF), Háka Q1 (impl. vs. pluginy) |
| 4 | **Datový model** — 6 CPT + 4 taxonomie | II. | 1:00 | Bělský Q2 (Stará garda) |
| 5 | **Galerie + ostatní stránky** (vizuál, statické obrázky) | I./II. | 1:00 | — (vizuální důkaz) |
| 6 | **Dokumentace + Jazyk** | III. + IV. | 0:30 | — |
| 7 | **Slabá místa & rozšíření** | — | 1:20 | Bělský Q3 (lazy LCP), Háka Q2 (AJAX) |
| 8 | Závěr | — | 0:30 | — |
| Σ | | | **7:30** | |
| **→ po závěru: Dotazy komise + ŽIVÉ DEMO** | | mimo 8 min | Demo využije se kontextově podle dotazů |

> **Buffer:** 7:30 mluveného + 30 s nervozita = 8:00. **Live demo bylo přesunuto z hlavního času do Q&A sekce po závěru** — komise dostane demo kontextově, podle toho na co se ptá. Web bude otevřený v browseru, připravený k otevření kdykoli během dotazů.

---

## Slide 1 — Titulní

**Čas:** 0:20

**Vizuál:**
- Logo TJ Slavoj Mýto (vlevo)
- Název: „Webová prezentace fotbalového klubu s databází zápasů a správou obsahu ve WordPressu"
- Autor: Lukáš Bejček
- Vedoucí: Mgr. Jaromír Háka · Oponent: Mgr. Miloslav Bělský
- Rok: 2026
- Vpravo dole malý mockup hero banneru z webu

**Mluvený text:**
> „Dobrý den, jmenuji se Lukáš Bejček. Moje maturitní práce se zabývá tvorbou vlastní WordPress šablony pro fotbalový klub TJ Slavoj Mýto. Vedoucím práce byl pan Háka, oponentem pan Bělský. Web je nasazený na hostingu, v průběhu prezentace ho ukážu živě."

---

## Slide 2 — Splnění zadání (sekce I. posudku)

**Čas:** 1:30

**Hlavní sdělení:** Většina bodů zadání splněna vlastní implementací. **Kalendářní modul nahrazen funkčním ekvivalentem** — bannerem na homepage + filtry na stránce Zápasů.

**Vizuál:** Tabulka „bod zadání → status":

| Bod zadání | Status |
|------------|--------|
| 1. Návrh ve Figmě | ✅ desktop + mobil |
| 2. Šablona v Bootstrapu 5 | ✅ vlastní |
| 3. CPT zápas, tým, hráč | ✅ + 3 navíc (galerie, sponzor, kontakt) |
| 4. Filtrování dle sezóny/týmu | ✅ GET parametry |
| **5. Kalendářový modul** | **🔄 funkční ekvivalent: banner + filtry** |
| 6. Galerie | ✅ + lightbox |
| 7. Optimalizace | ⚠️ částečně (minifikace, WebP, lazy load — cache plánována) |
| 8. SEO | ⚠️ řešeno jen alt atributy + meta tagy v kódu |
| 8b. Analytika (GA4) | ⚠️ plánováno, časově nestihnuto |
| 9. Hosting, HTTPS | ✅ |
| 10. Role | ✅ vlastní RBAC |

Pod tabulkou screenshot side-by-side: **banner s nadcházejícími zápasy** vs. **filtry na /zapasy**.

**Mluvený text:**
> „Začnu shrnutím splnění zadání. Splněno: vlastní téma v Bootstrapu 5, šest vlastních typů obsahu — což jsou tři navíc oproti zadání — čtyři taxonomie, filtrování, lightbox galerie, role, hosting a HTTPS.
>
> Bod 5 zadání — kalendářový modul — je vyřešen funkčním ekvivalentem ve dvou částech. Na homepage je interaktivní banner s nadcházejícími zápasy ve vizuálních kartách. Na stránce Zápasy jsou filtry podle týmu, sezóny a stavu. Aktuální řešení je pro klubový web plně dostačující — klub na webu prezentuje pouze zápasy. Plnohodnotný gridový kalendář by dával smysl až ve chvíli, kdy by web prezentoval i další události — klubové akce, prodej lístků, ples.
>
> Body 7 a 8 — optimalizace a SEO — jsou aktuálně řešeny jen na úrovni kódu: minifikace, WebP, lazy loading, alt atributy a meta tagy v hlavičce. Cache plugin a SEO plugin jsou v plánu pro produkční nasazení."

---

## Slide 3 — Vlastní implementace: architektura & rozhodnutí o pluginech (sekce II. posudku, část 1)

**Čas:** 1:20

**Hlavní sdělení:** Vědomé rozhodnutí nepoužívat ACF, CPT UI, FacetWP. **Posudky to chválí — hlavní přínos práce.**

**Vizuál:** Schéma šablony + tabulka pluginy vs. vlastní:

```
header.php
   ↓
front-page.php / archive-zapas.php / single-*.php
   ↓
footer.php
```

```
functions.php  →  helpery, registrace CPT a taxonomií
                                ↓
                   plugin slavoj-custom-fields
                                ↓
                   admin meta boxy, validace, role
```

| Doporučený plugin | Optimalizovaná výseč ve vlastním pluginu |
|---|---|
| ACF | `register_meta` + jen ty meta boxy, které projekt potřebuje |
| CPT UI | `register_post_type` v `functions.php`, bez UI builderu |
| FacetWP | `WP_Query` + GET parametry — jen použitý typ filtrů |
| User Role Editor | `add_role` + `current_user_can`, jen reálné role klubu |

**Mluvený text:**
> „Klíčovým technickým přínosem práce je přístup ke správě obsahu vlastním kódem místo plné integrace doporučených pluginů.
>
> Práce má **dva samostatné deployment artefakty**: šablonu `tj-slavoj-myto` a vlastní WordPress plugin **`slavoj-custom-fields`**. Šablona řeší prezentační logiku, plugin řeší správu obsahu — admin meta boxy, validace, role.
>
> **Klíčové rozhodnutí — jak jsem k vlastnímu pluginu došel.** Většina funkcionality vychází z principů doporučených pluginů ACF, CPT UI, FacetWP a User Role Editor. **Místo plné integrace jsem prošel jejich logiku a portoval do vlastního pluginu jen optimalizovanou výseč** — pouze ty části, které projekt skutečně potřebuje. Tím jsem se vyhnul balastu, který by plné pluginy přinášely: stovky řádků pro UI builder, který nevyužívám, lokalizace pro desítky jazyků, advanced fields, které tady nemají uplatnění.
>
> **Tři důvody pro tento přístup.** Za prvé, **hlubší porozumění WordPress API** — abych mohl něco optimalizovat, musel jsem nejdřív pochopit, jak to funguje. Za druhé, **nezávislost na breaking changes** externích pluginů. ACF mělo breaking changes v meta API mezi verzemi 5 a 6, naproti tomu WordPress core funkce `register_post_type` a `register_meta` jsou stabilní víc než deset let. Za třetí, **obhajitelnost** — mohu vysvětlit každý řádek kódu, ne pouze klik v administraci.
>
> **Výhody a nevýhody.** Výhody: lepší výkon (žádné nevyužité features), transparentnost, žádná závislost na třetích stranách. Nevýhody: víc kódu k údržbě, žádný UI builder pro netechnické adminy. Pro klubový web s nízkou frekvencí změn převažují výhody."

---

## Slide 4 — Datový model: CPT a sdílené taxonomie (sekce II. posudku, část 2)

**Čas:** 1:00

**Hlavní sdělení:** 6 CPT pro entity klubu, 4 taxonomie pro klasifikaci. **Taxonomie jsou sdílené napříč CPT** — to je rys, ne chyba.

**Vizuál:** Zjednodušený ER diagram + tabulka:

| CPT (6) | Taxonomie (4) |
|---------|---------------|
| zapas | sezona |
| tym | kategorie-tymu |
| hrac | stav-zapasu |
| galerie | pozice-hrace |
| sponzor | |
| kontakt | |

Šipky:
- `zapas` ↔ `sezona`, `kategorie-tymu`, `stav-zapasu`
- `galerie` ↔ `sezona`, `kategorie-tymu` (zde i „Stará garda")
- `hrac` ↔ `pozice-hrace`, `kategorie-tymu`

**Mluvený text:**
> „Datový model je postavený na šesti vlastních typech obsahu a čtyřech taxonomiích. Sezóna je taxonomie, ne meta pole, protože je sdílená napříč zápasy, týmy a galeriemi. Kategorie týmu funguje stejně.
>
> Sdílení taxonomií napříč CPT je vědomé designové rozhodnutí. Kategorie 'Stará garda' se prakticky používá jen u galerií, ale technicky je dostupná i pro zápasy. Filtrování ve frontendu běží přes `WP_Query` s `tax_query`, takže zápas omylem přiřazený ke Staré gardě se v UI mimo její archiv nezobrazí. Data v databázi by ale byla nekonzistentní. Správné řešení je validační vrstva v `save_post` hooku, která pro CPT `zapas` povolí jen reálné kategorie A-mužstvo až Minipřípravka. Alternativou je registrovat taxonomii samostatně pro každý CPT, ale ztratila by se výhoda sdílení."

---

## Slide 5 — Galerie + ostatní stránky

**Čas:** 1:00

**Hlavní sdělení:** Vizuální důkaz, že web vypadá profesionálně a obsahuje všechny sekce klubu. **Statické obrázky, žádné živé demo** — to si komise nechá na Q&A.

**Vizuál:** Slide jako mřížka 2×2 nebo 1×4 se screenshoty:
- Galerie — náhledový grid alb s fotkami
- Detail alba — lightbox modal
- Stránka sponzorů
- Stránka kontaktů

Pod tím malý popisek:
> „URL hostingu: [vlož skutečnou URL klubu] · QR kód pro komisi"

**Mluvený text:**
> „Kromě zápasů má web galerii s lightboxem, stránku sponzorů a kontakty. Galerie využívá sezónu jako taxonomii — alba se filtrují podle ročníku, stejně jako zápasy. Lightbox je nativní HTML `<dialog>` element, bez JavaScriptu mimo otevírací handler. Sponzoři jsou vlastním CPT — administrátor klubu může přidávat partnery včetně loga a odkazu. Web je nasazený na produkčním hostingu, otevřu ho v rámci dotazů, pokud bude komise chtít cokoliv vidět živě."

> *Pozn.: Tento slide je nejkratší. Pokud sklouzneš s časem, **dá se zkrátit o jeden bullet** (vynechat sponzory nebo kontakty).*

---

## Slide 6 — Dokumentace + Jazyk (sekce III. + IV. posudku)

**Čas:** 0:30

**Hlavní sdělení:** Dokumentace „vynikající" (Bělský), shoda 0 %, ER diagram, testovací scénáře, příručka adminů.

**Vizuál:** Tři čísla na slidu:
- **40** stran dokumentace
- **0 %** shoda s ostatními zdroji (odevzdej.cz)
- **„vynikající"** hodnocení od oponenta

Pod tím mini-thumbnaily 4 stránek z dokumentace: titulní, ER diagram, srovnávací tabulka, testovací scénář.

**Mluvený text:**
> „Dokumentace má čtyřicet stran a oponent ji hodnotí jako vynikající. Obsahuje analýzu původního řešení, srovnávací tabulky pluginy versus vlastní implementace, ER diagram datového modelu, popis nasazení obou artefaktů — šablony i pluginu `slavoj-custom-fields` — testovací scénáře a příručku administrátora. Shoda s externími zdroji je nula procent."

---

## Slide 7 — Slabá místa & rozšíření

**Čas:** 1:20

**Hlavní sdělení:** Vědomé přiznání zbývajících výtek + konkrétní plán doplnění. **Signalizuje jistotu a sebereflexi.**

**Vizuál:** Tabulka 3 sloupce, 5 řádků:

| Výtka | Stav | Plán |
|-------|------|------|
| Lazy loading na LCP banneru (Bělský 3) | ⚠️ uznáno | Výjimka pro hero, nebo `wp_get_attachment_image` nativní |
| AJAX filtry (Háka 2) | vědomá volba | Doplnitelné přes `wp_ajax` + `admin-ajax.php`, History API |
| Kalendář — plnohodnotný grid (Bělský, Háka 3) | YAGNI — aktuální banner + filtry dostačující | rozšíření až s dalšími typy událostí (akce, prodej lístků) |
| Cache plugin | chybí | LiteSpeed Cache |
| SEO plugin | chybí (jen alt atributy + meta tagy v kódu) | Yoast / Rank Math |
| GA4 (analytika) | plánováno, časově nestihnuto | `wp_head` hook |

**Mluvený text:**
> „Závěrem chci sám zmínit body, které v práci považuji za slabší.
>
> **Lazy loading — toto je oprávněná připomínka oponenta.** Implementoval jsem ho přes `str_replace` na všechny `<img>` tagy, **včetně LCP obrázku v hero sekci**. To zhorší Largest Contentful Paint a Core Web Vitals. V další verzi bych to řešil výjimkou pro hero, nebo lépe přechodem na nativní `wp_get_attachment_image` z WordPress 5.5+, který první obrázek na stránce vyloučí automaticky a navíc přidá `fetchpriority=high`.
>
> **AJAX filtry — toto je vědomá volba.** Aktuální GET implementace je SEO-friendly, indexovatelná, URL sdílitelná. AJAX by ale byl legitimní upgrade — endpoint `wp_ajax_filter_zapasy` v pluginu, fetch na `admin-ajax.php`, HTML fragment přes `get_template_part`, History API kvůli sdílitelnosti URL.
>
> **SEO plugin uznávám jako mezeru.** Řešil jsem pouze `alt` atributy u obrázků a meta tagy přímo v `header.php`. Dedikovaný plugin Yoast nebo Rank Math by přidal sitemap, breadcrumbs a structured data. **Google Analytics 4 byla plánována do odevzdání**, časově se nestihla — napojení je triviální přes `wp_head` hook nebo plugin Site Kit by Google. **Cache plugin také chybí — to uznávám**, pro produkci doplním LiteSpeed Cache.
>
> **Plnohodnotný gridový kalendář** považuji v tomto kontextu za overkill. Pro klub, jehož web prezentuje jen zápasy, banner s nadcházejícími zápasy a filtry tým/sezóna/stav plní stejnou funkci. Knihovnu jako FullCalendar.js bych přidával až ve chvíli, kdy by web prezentoval i jiné události — klubové akce, prodej lístků. Aktuální řešení je z hlediska potřeb klubu plně dostačující."

---

## Slide 8 — Závěr

**Čas:** 0:30

**Vizuál:** Tři kompaktní bloky:

| ✅ Hotovo | 🔄 Rozšiřitelné | 🎯 Výsledek |
|----------|----------------|-------------|
| 6 CPT, vlastní plugin | AJAX filtry | chvalitebně (oba) |
| Banner + filtry | Cache plugin | dokumentace „vynikající" |
| Galerie + lightbox | SEO plugin + GA4 | 0 % shoda |

Velký nadpis: **„Děkuji za pozornost. Web mám otevřený pro případné dotazy."**

**Mluvený text:**
> „Projekt splňuje většinu bodů zadání s vlastní implementací bez závislosti na externích pluginech. Hlavní přínos je vlastní řešení CPT, meta polí, filtrace a rolí. Rozšíření jsou přímočará a popsaná v dokumentaci. Děkuji za pozornost. Web mám otevřený v prohlížeči, takže pokud bude komise chtít cokoliv vidět živě, mohu se kdykoli přepnout. Jsem připraven na otázky."

---

## Po závěru: Dotazy komise + LIVE DEMO (mimo 8 min)

**Princip:** demo není časovaný blok, ale **kontextový nástroj během Q&A**. Komise se zeptá → Lukáš podle relevance buď odpoví slovně, nebo přepne na browser a ukáže to na webu.

**Co mít připraveno PŘED Q&A:**
- Browser s otevřenými 4 záložkami: Homepage, /zapasy, /galerie, libovolné album
- Web cache warm-up — všechny stránky načtené 30 min předem
- Záložní PDF screenshotů na druhém monitoru / mobilu pro případ výpadku hostingu

**Mapování dotazů → demo akcí:**

| Pokud se komise zeptá na… | Otevři tab… | Co krátce komentovat |
|---------------------------|-------------|----------------------|
| **Filtrování zápasů, AJAX, GET parametry** | `/zapasy` | „Vyberu kategorii Muži A — vidíte v URL `?kat=muzi-a`, sdílitelný odkaz. Filtry posílají HTML form GET, server odpoví novou stránkou. To samé pro sezónu a stav." |
| **Banner / nadcházející zápasy** | Homepage | „Banner generovaný přes `WP_Query` orderby date, omezeno na status `nadchazejici`. Karty mají barevný klubový proužek." |
| **Galerie / lightbox** | `/galerie` → album | „Alba taxonomií sezóna. Klik na fotku otevře nativní HTML `<dialog>` element, žádný externí lightbox plugin." |
| **Datový model / Stará garda** | WP admin (pokud login funguje) | „V administraci vidíte taxonomii kategorie-tymu — Stará garda je jeden z termů. U CPT `zapas` se v dropdownu zobrazí, ale validační vrstvu by ošetřil `save_post` hook." |
| **Vlastní plugin** | WP admin → Plugins | „Plugin `slavoj-custom-fields` aktivovaný v administraci, samostatný deployment artefakt v `wp-content/plugins/`." |
| **Mobilní zobrazení** | DevTools (F12) → responsive mode | „Mobile-first, Bootstrap 5 breakpointy. Hamburger menu bez vlastního JavaScriptu." |
| **Něco, co nefunguje / hosting nereaguje** | Backup PDF | „Hosting reaguje pomalu, ukážu na záložních screenshotech." |

**Záchranné fráze v demu:**

| Situace | Co říct |
|---------|---------|
| Stránka se načítá > 3 s | „Web je na sdíleném hostingu, načítání může chvíli trvat — krátce komentář k tomu, jak by produkční cache plugin (LiteSpeed) výrazně urychlil." |
| Komise se ptá na něco, co web neumí | „Toto v aktuální verzi není implementováno, ale šlo by to řešit takto: [konkrétní krok]. V dokumentaci je to popsané jako možné rozšíření." |
| Lukáš zabloudí v navigaci | „Vrátím se zpět na úvodní stránku a otevřu to z hlavního menu." |
| Web se rozbije (error 500 atd.) | „Vidíme chybu na produkci — to je dobrá ukázka, proč se před nasazením testuje na staging prostředí. Mohu ukázat lokální verzi nebo pokračovat dotazy slovně." |

> **Klíčový princip:** demo není performance pro komisi, je to **odpověď na konkrétní dotaz**. Lukáš mluví o webu, ne web sám.

---

## Mapování 6 otázek z posudků → slidů (autonomní rámování)

| Otázka | Slide | Jak je implicitně pokryta v mluveném textu |
|--------|-------|--------------------------------------------|
| Bělský 1 (ACF udržitelnost) | 3 | „Klíčové rozhodnutí — proč jsem nepoužil ACF. Tři důvody…" |
| Bělský 2 (sdílená taxonomie / Stará garda) | 4 | „Klíčový designový rys — sdílená taxonomie napříč CPT…" |
| Bělský 3 (lazy loading LCP) | 7 | „Lazy loading… banner v hero sekci je LCP element…" |
| Háka 1 (impl. vs. pluginy) | 3 | „Výhody a nevýhody tohoto přístupu…" |
| Háka 2 (AJAX filtry) | 7 | „AJAX filtry. Aktuální GET implementace je vědomá volba…" |
| Háka 3 (plnohodnotný kalendář) | 2 + 7 | Slide 2: banner + filtry = funkční ekvivalent; slide 7: aktuální řešení dostačující, FullCalendar pouze s dalšími typy událostí |

> **Důsledek:** Lukáš odpovědi vysvětluje **jako vlastní rozhodnutí, ne jako reakci na komisi**. V Q&A když komise otázku položí, Lukáš se odkáže: „Jak jsem zmínil v prezentaci, hlavní důvod je X — a rád to rozvedu detailněji o Y a Z." Tím dodržuje etiketu „počkat na položení otázky" a zároveň ukazuje, že měl odpověď připravenou.

---

## Časová kontrola (před obhajobou)

- [ ] Přečíst slidy nahlas se stopkami, cíl 7:30–8:00
- [ ] Slide 5 demo natrénovat 5× — golden path + fallback při selhání hostingu
- [ ] Připravit záložní screenshoty pro každou fázi dema (PDF v telefonu)
- [ ] Slide 7 (Reakce) — pokud > 1:30, zkrátit poslední odstavec
- [ ] Slide 3 a 4 — natrénovat zvlášť, obsahují odpovědi na otázky a musí znít plynule
- [ ] Spustit web na hostingu 30 min před obhajobou (cache warm-up)
- [ ] Otevřený browser tab přepnutý před prezentací

---

## Reference

- Posudky v plném znění: [`POSUDKY.md`](./POSUDKY.md)
- Cue cards k tisku: [`CUE-CARDS.md`](./CUE-CARDS.md)
- Handout pro komisi: [`HANDOUT-OBHAJOBA.md`](./HANDOUT-OBHAJOBA.md)
