# Prezentace k obhajobě maturitní práce — FINÁLNÍ PLÁN

**Autor:** Lukáš Bejček
**Téma:** Webová prezentace fotbalového klubu TJ Slavoj Mýto (WordPress)
**Délka:** 8 minut
**Hodnocení posudků:** chvalitebně (Bělský), chvalitebně (Háka), shoda 0 %
**Cíl obhajoby:** posun na výborně

> **Strukturální princip:** Prezentace volně sleduje strukturu posudků (I. Splnění zadání → II. Praktická část → III. Dokumentace → IV. Jazyk → Slabá místa). Odpovědi na všech 6 otázek z posudků jsou **vepsány přímo do mluveného textu příslušných slidů**, aby komise viděla, že jsi otázky předvídal.

---

## Návrh osnovy (8 slidů, sekce posudků jako kostra)

| # | Slide | Sekce posudku | Čas | Kam patří otázky |
|---|-------|---------------|-----|------------------|
| 1 | Titulní | — | 0:20 | — |
| 2 | **Splnění zadání** — co dodáno + reframe kalendáře | I. | 1:30 | Háka Q3 (kalendář) |
| 3 | **Vlastní implementace** — architektura & rozhodnutí o pluginech | II. | 1:20 | Bělský Q1 (ACF), Háka Q1 (impl. vs. pluginy) |
| 4 | **Datový model** — 6 CPT + 4 taxonomie | II. | 1:00 | Bělský Q2 (Stará garda) |
| 5 | **Galerie + ŽIVÉ DEMO na hostingu** | I./II. | 1:30 | — (vizuální důkaz) |
| 6 | **Dokumentace + Jazyk** | III. + IV. | 0:30 | — |
| 7 | **Slabá místa & rozšíření** | — | 1:20 | Bělský Q3 (lazy LCP), Háka Q2 (AJAX) |
| 8 | Závěr + dotazy | — | 0:30 | — |
| Σ | | | **8:00** | |

> **Buffer:** efektivně 7:00 mluveného + ~30 s demo + ~30 s nervozita = přesně 8:00. Pokud sklouzneš o 30 s, slide 5 nebo 7 se dá zkrátit jedním bullet bodem.

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
| **5. Kalendářový modul** | **🔄 reframe: banner + filtry** |
| 6. Galerie | ✅ + lightbox |
| 7. Optimalizace | ⚠️ částečně (minifikace, WebP — cache plánována) |
| 8. SEO & analytika | ⚠️ plán: Yoast + GA4 |
| 9. Hosting, HTTPS | ✅ |
| 10. Role | ✅ vlastní RBAC |

Pod tabulkou screenshot side-by-side: **banner s nadcházejícími zápasy** vs. **filtry na /zapasy**.

**Mluvený text (zahrnuje odpověď na Háka Q3 — kalendář):**
> „Pojďme po sekcích posudku. **Sekce první — splnění zadání.** Splněno: vlastní téma v Bootstrapu 5, šest custom post types — což jsou tři navíc oproti zadání — čtyři taxonomie, filtrování, lightbox galerie, role, hosting a HTTPS.
>
> Bod 5 zadání — **kalendářový modul** — vyřešen funkčním ekvivalentem ve dvou částech. Na homepage je **interaktivní banner s nadcházejícími zápasy** ve vizuálních kartách — to pokrývá vizuální zobrazení. Na stránce Zápasy jsou **filtry podle týmu, sezóny a stavu** — to pokrývá kategorické a časové filtrování. Společně plní stejnou UX funkci jako dedikovaný kalendář, ale bez závislosti na pluginu třetí strany. Plnohodnotný gridový kalendář by se dal doplnit přes knihovnu FullCalendar.js napojenou na WordPress REST API.
>
> Body 7 a 8 — cache plugin a SEO plugin — jsou aktuálně řešeny částečně, na úrovni kódu (minifikace, WebP, meta tagy). Pro produkci je plán LiteSpeed Cache plus Yoast nebo Rank Math."

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

| Doporučený plugin | Nahrazeno vlastní implementací |
|---|---|
| ACF | `register_meta` + admin meta boxy v pluginu |
| CPT UI | `register_post_type` v `functions.php` |
| FacetWP | `WP_Query` + GET parametry |
| User Role Editor | `add_role` + `current_user_can` |

**Mluvený text (zahrnuje odpovědi na Bělský Q1 + Háka Q1 + zmínka vlastního pluginu):**
> „**Sekce druhá posudku — kvalita praktické části a vlastní přínos.** Oba posudky jako hlavní přínos uvádějí, že nepoužívám doporučené pluginy a vše implementuji vlastním PHP kódem.
>
> Práce má **dva samostatné deployment artefakty**: šablonu `tj-slavoj-myto` a vlastní WordPress plugin **`slavoj-custom-fields`**. Šablona řeší prezentační logiku — frontend, template hierarchy, styling. Plugin řeší správu obsahu — admin meta boxy, validace, registraci vlastních rolí. To je stejný architektonický princip, jako když by se použila kombinace standardní šablony plus ACF — jen že plugin je psaný mnou. Plugin je nasazený standardním způsobem v `wp-content/plugins/`, má vlastní header komentář a aktivuje se v administraci.
>
> **Pan Bělský se v otázce ptá, proč ne ACF a jak obhájím udržitelnost.** Tři důvody. Za prvé — hlubší porozumění WordPress API, což oponent v posudku ocenil. Za druhé — nezávislost na breaking changes. ACF mělo breaking changes v meta API mezi verzemi 5 a 6, naproti tomu WordPress core funkce `register_post_type` a `register_meta` jsou stabilní deset let a víc. Za třetí — obhajitelnost. Mohu vysvětlit každý řádek kódu, ne pouze klik v administraci.
>
> **Pan Háka se ptá na výhody a nevýhody vlastní implementace.** Výhody: porozumění, žádná závislost na třetích stranách, lepší výkon — žádné nevyužité features, transparentnost. Nevýhody: víc kódu k údržbě, žádný UI builder pro netechnické adminy, žádná komunita pro support. Pro klubový web s nízkou frekvencí změn převažují výhody — minimální údržba a předvídatelnost.
>
> Kód respektuje WordPress konvence: `wp_enqueue_scripts`, template hierarchy, nonce ochrana, sanitizace a escapování."

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

**Mluvený text (zahrnuje odpověď na Bělský Q2 — Stará garda):**
> „Datový model — šest typů obsahu, čtyři taxonomie. Sezóna je **taxonomie, ne meta pole**, protože je sdílená napříč zápasy, týmy a galeriemi. Kategorie týmu funguje stejně.
>
> **Pan Bělský se v otázce ptá na sdílenou taxonomii a kategorii 'Stará garda', která se používá jen u galerií. Co kdyby admin omylem přiřadil zápas?** Filtrování ve frontendu běží přes `WP_Query` s `tax_query`. Zápas přiřazený omylem ke Staré gardě se ve frontendu nezobrazí mimo její archiv — ale data v databázi by byla nekonzistentní. Správné řešení je validační vrstva v `save_post` hooku, která pro CPT `zapas` povolí jen kategorie A-mužstvo až Minipřípravka, a Starou gardu jen pro CPT `galerie`. Alternativou je registrovat taxonomii samostatně pro každý CPT, ale ztratila by se výhoda sdílení."

---

## Slide 5 — Galerie + ŽIVÉ DEMO na hostingu

**Čas:** 1:30 (z toho ~50 s demo)

**Hlavní sdělení:** Vizuální důkaz, že web funguje. Demo galerie, bannera a filtrů na produkčním hostingu.

**Vizuál:** Slide má dvě části:
- Vlevo: 3 thumbnaily galerie (fotky z klubu)
- Vpravo: URL hostingu (např. `https://tjslavojmyto.cz`) + QR kód pro komisi

Tip: PO TOMTO SLIDU přepnout na browser tab s webem a 50 sekund ukazovat živě.

**Mluvený text + demo skript:**
> „Pojďme se podívat na živé demo na hostingu." *(přepne na browser)*
>
> *(Homepage)* „Tady je homepage s **interaktivním bannerem** — nadcházející zápasy ve vizuálních kartách, generované přes `WP_Query` orderby date."
>
> *(Klik na Zápasy)* „Stránka zápasů. Vyberu kategorii **Muži A** — filtr posílá GET parametr `?kat=muzi-a`. Sezónu **2025/2026**. Stav **Odehrané**. Karty se přefiltrují bez AJAXu, URL je sdílitelná. Karty mají barevný klubový proužek a velké skóre."
>
> *(Klik na Galerie)* „Galerie — alba dle sezón. *(otevře album)* Lightbox modal pro náhledy."
>
> *(Zpět na slidy)*

> *Pozn.: Demo natrénovat 5× se stopkami. Pokud hosting nereaguje > 3 s, přepnout na backup screenshoty. Připravit záložní screenshoty pro každou fázi.*

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
> „**Sekce třetí a čtvrtá posudku — dokumentace a jazyk.** Dokumentace má čtyřicet stran, oponent ji hodnotí jako vynikající. Obsahuje analýzu původního řešení, srovnávací tabulky pluginy versus vlastní implementace, ER diagram datového modelu, **popis nasazení obou artefaktů — šablony i pluginu `slavoj-custom-fields`**, testovací scénáře a příručku administrátora. Shoda s externími zdroji je nula procent.
>
> *Pan Háka v posudku zmiňuje malý důraz na reálné nasazení standardních pluginů — to je dáno tím, že jsem je nahradil vlastní implementací. Nasazení vlastního pluginu je přitom popsané standardním WordPress způsobem.*"

---

## Slide 7 — Slabá místa & rozšíření

**Čas:** 1:20

**Hlavní sdělení:** Vědomé přiznání zbývajících výtek + konkrétní plán doplnění. **Signalizuje jistotu a sebereflexi.**

**Vizuál:** Tabulka 3 sloupce, 5 řádků:

| Výtka | Stav | Plán |
|-------|------|------|
| Lazy loading na LCP banneru (Bělský 3) | ⚠️ uznáno | Výjimka pro hero, nebo `wp_get_attachment_image` nativní |
| AJAX filtry (Háka 2) | vědomá volba | Doplnitelné přes `wp_ajax` + `admin-ajax.php`, History API |
| Kalendář — plnohodnotný grid (Bělský, Háka 3) | reframe (banner+filtry) | FullCalendar.js + REST API |
| Cache plugin | chybí | LiteSpeed Cache |
| SEO plugin + analytika | chybí | Yoast / Rank Math + GA4 |

**Mluvený text (zahrnuje odpovědi na Bělský Q3 + Háka Q2):**
> „K zbývajícím výtkám z posudků.
>
> **Pan Bělský se ptá na lazy loading přes `str_replace` a LCP obrázek v banneru.** Tuto výtku **plně uznávám.** Banner v hero sekci je LCP element a `loading=lazy` zhorší Largest Contentful Paint a Core Web Vitals. Správné řešení je výjimka pro hero sekci, nebo lépe přejít na nativní `wp_get_attachment_image` z WordPress 5.5+, který první obrázek na stránce vyloučí automaticky a navíc přidá `fetchpriority=high`.
>
> **Pan Háka se ptá, jak rozšířit filtry o AJAX.** Vytvořil bych endpoint přes `wp_ajax_filter_zapasy` v pluginu `slavoj-custom-fields`. JavaScript by poslal `fetch` na `admin-ajax.php`, server by vrátil HTML fragment přes `get_template_part`, klient replace přes `innerHTML`. URL synchronizovaná přes History API kvůli sdílitelnosti. Aktuální GET implementace je ale **vědomá volba** — je SEO-friendly a indexovatelná.
>
> Pro produkční nasazení doplním **cache plugin** LiteSpeed a **SEO plugin** Yoast nebo Rank Math s napojením na Google Analytics. Plnohodnotný **gridový kalendář** přes FullCalendar.js a WordPress REST API."

---

## Slide 8 — Závěr + dotazy

**Čas:** 0:30

**Vizuál:** Tři kompaktní bloky:

| ✅ Hotovo | 🔄 Rozšiřitelné | 🎯 Výsledek |
|----------|----------------|-------------|
| 6 CPT, vlastní plugin | AJAX filtry | chvalitebně (oba) |
| Banner + filtry (kalendář) | FullCalendar.js | dokumentace „vynikající" |
| Galerie + lightbox | Cache + SEO plugin | 0 % shoda |

Velký nadpis: **„Děkuji za pozornost. Otázky?"**

**Mluvený text:**
> „Projekt splňuje většinu bodů zadání s vlastní implementací bez závislosti na externích pluginech. Hlavní přínos je vlastní řešení CPT, meta polí, filtrace a rolí. Rozšíření jsou přímočará a popsaná v dokumentaci. Děkuji za pozornost a jsem připraven na otázky."

---

## Mapování 6 otázek z posudků → slidů

| Otázka | Slide | Kde v mluveném textu |
|--------|-------|----------------------|
| Bělský 1 (ACF udržitelnost) | 3 | „Pan Bělský se v otázce ptá, proč ne ACF…" |
| Bělský 2 (sdílená taxonomie / Stará garda) | 4 | „Pan Bělský se v otázce ptá na sdílenou taxonomii…" |
| Bělský 3 (lazy loading LCP) | 7 | „Pan Bělský se ptá na lazy loading…" |
| Háka 1 (impl. vs. pluginy) | 3 | „Pan Háka se ptá na výhody a nevýhody…" |
| Háka 2 (AJAX filtry) | 7 | „Pan Háka se ptá, jak rozšířit filtry o AJAX…" |
| Háka 3 (plnohodnotný kalendář) | 2 + 7 | Slide 2: reframe; slide 7: plán FullCalendar.js |

> **Důsledek:** komise dostane všechny odpovědi už v prezentaci. V Q&A pak může jít víc do hloubky nebo se ptát na další věci — viz handout.

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
