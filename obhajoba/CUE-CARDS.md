# Cue cards — Obhajoba MP (Lukáš Bejček)

> **K tisku:** 1 strana A4, oboustranně, ohnout na 4 sloupce.
> **Použití:** v ruce při prezentaci. Nečti — jen klíčové fráze a časové kotvy.
> **Cíl času:** 7:30 mluveného + 30 s buffer = 8:00 total.
> **Celá obhajoba:** 5 min setup + 8 min prezentace + ~7 min Q&A = max 20 min.

---

## SETUP (5 min před prezentací)

- [ ] Slidy spuštěné v prezentačním režimu (full screen)
- [ ] Browser tab → homepage klubu → **proklikat** všechny sekce (Zápasy, Galerie, album, Sponzoři) kvůli cache
- [ ] Backup PDF screenshotů otevřený na druhém monitoru / mobilu
- [ ] Cue cards vytištěné v ruce
- [ ] Voda po ruce
- [ ] Hodinky / stopky viditelné
- [ ] Vypnuté notifikace (e-mail, Discord, Slack)
- [ ] Mobilní hotspot zapnutý jako záloha sítě
- [ ] Hluboký nádech 3×, ramena dolů, úsměv

---

## KLÍČOVÉ PRINCIPY pro celou prezentaci

- **NEŘÍKAT „Pan Bělský se ptá" ani „Pan Háka se ptá"** — odpovědi jsou vetknuté jako vlastní argumentace. Komise se zeptá v Q&A; pak se odkážeš.
- **Kontrast** — v kmenové třídě je horší světlo, mluvit směrem ke komisi, ne k plátnu.
- **Demo až v Q&A** — během prezentace na browser NEPŘEPÍNAT.

---

## Slide 1 — Titulní (0:20)

**Klíčové:**
- Jméno + téma + vedoucí (Háka) + oponent (Bělský)
- „Web je nasazený na hostingu, ukážu živě."

**Stopka:** odejít ze slidu do 20 s.

---

## Slide 2 — Splnění zadání + reframe kalendáře (1:30)

**Klíčové fráze (nesmí chybět):**
- „6 CPT — o tři navíc oproti zadání"
- „Kalendář = banner + filtry, funkční ekvivalent"
- „Plnohodnotný gridový kalendář by se dal doplnit přes **FullCalendar.js + REST API**"
- „Cache + SEO plugin — plán pro produkci"

**Časové kotvy:**
- @ 0:00 — „Sekce první posudku — splnění zadání"
- @ 0:40 — „Bod 5 — kalendář — vyřešen funkčním ekvivalentem"
- @ 1:15 — „Body 7 a 8 — cache a SEO plugin — plán"

**Nezapomenout:** komise vidí tabulku zadání → soustředit se na **bod 5**.

---

## Slide 3 — Vlastní implementace + ACF + plugin (1:20)

**Klíčové fráze:**
- „Dva deployment artefakty: **šablona** + **plugin `slavoj-custom-fields`**"
- **„Většina funkcionality vychází z principů doporučených pluginů — portoval jsem do vlastního pluginu jen optimalizovanou výseč."**
- „Bez balastu: UI builder, lokalizace, advanced fields, které nevyužívám"
- **„Tři důvody:** porozumění · breaking changes (ACF 5→6 v meta API) · obhajitelnost"
- „**Výhody:** výkon · transparentnost · žádná závislost — **nevýhody:** víc kódu k údržbě"

**Časové kotvy:**
- @ 0:00 — „Druhá oblast — vlastní přínos"
- @ 0:20 — „Dva deployment artefakty…"
- @ 0:35 — „Klíčové rozhodnutí — jak jsem k vlastnímu pluginu došel…"
- @ 1:00 — „Výhody a nevýhody…"

**Nezapomenout:**
- **NEŘÍKAT „nepoužívám pluginy"** — narrative je **„portovaná optimalizovaná výseč"** (čestnější + technicky obhajitelné).
- **NEZNÍT VINNĚ.** Optimalizace pluginů na potřebnou výseč je hlavní přínos, ne slabost.
- **NIKDY neříkat „Pan Bělský se ptá" / „Pan Háka se ptá"** — odpověď je v autonomní argumentaci.

---

## Slide 4 — Datový model + Stará garda (1:00)

**Klíčové fráze:**
- „6 CPT, 4 taxonomie — sezóna a kategorie sdílené"
- „Sezóna je **taxonomie**, ne meta — sdílená přes zápasy, týmy, galerii"
- **„Klíčový designový rys — sdílená taxonomie napříč CPT"**
- „`WP_Query` `tax_query` — frontend filtruje, **data v DB by byla nekonzistentní**"
- „Řešení: **`save_post` hook s validací**"

**Časové kotvy:**
- @ 0:00 — „Datový model — 6 typů, 4 taxonomie"
- @ 0:25 — „Klíčový designový rys — sdílená taxonomie napříč CPT…"

**Nezapomenout:** komise může chtít vidět ER diagram — připravit v dokumentaci PDF na druhém monitoru.

---

## Slide 5 — Galerie + ostatní stránky (1:00)

**Klíčové fráze (3 věci nahlas):**
- „Galerie — sezóna jako **taxonomie**, alba se filtrují podle ročníku"
- „Lightbox — nativní HTML **`<dialog>` element**, žádný externí plugin"
- „Sponzoři a kontakty — vlastní CPT, administrátor klubu si je sám spravuje"

**Časové kotvy:**
- @ 0:00 — „Kromě zápasů má web galerii…"
- @ 0:50 — „Web je nasazený na produkčním hostingu, otevřu ho v rámci dotazů…"

**Nezapomenout:**
- **NEPŘEPÍNAT na browser během tohoto slidu** — demo je až v Q&A
- Pokud sklouzneš s časem, vynechej zmínku sponzorů nebo kontaktů
- Závěr slidu = signpost na live demo v Q&A

---

## Slide 6 — Dokumentace + Jazyk (0:30)

**Klíčové fráze (3 čísla nahlas):**
- „**40 stran**"
- „**0 % shoda** na odevzdej.cz"
- „**'vynikající'** hodnocení dokumentace od oponenta"

- „Popsané nasazení **obou artefaktů** — šablony i pluginu"

**Časové kotvy:**
- @ 0:00 — „Sekce třetí a čtvrtá — dokumentace a jazyk"

**Nezapomenout:** krátký slide, **nezdržovat se**.

---

## Slide 7 — Slabá místa & rozšíření (1:20)

**Klíčové fráze — 3 kategorie reakce:**

| Kategorie | Téma | Fráze |
|-----------|------|-------|
| **PŘIJMOUT** | Lazy LCP | „**Toto je oprávněná připomínka oponenta.** V další verzi bych to řešil…" |
| **PŘIJMOUT** | Cache plugin | „**Cache plugin chybí — to uznávám.** LiteSpeed pro produkci." |
| **PŘIJMOUT** | SEO plugin | „**Uznávám — řešil jsem jen alt atributy a meta tagy v kódu.** Plugin Yoast / Rank Math by přidal sitemap, breadcrumbs, structured data." |
| **VYSVĚTLIT** | GA4 (analytika) | „**Bylo plánováno do odevzdání, časově se nestihlo.** Triviální přes `wp_head` hook nebo Site Kit." |
| **VYSVĚTLIT/OBHÁJIT** | AJAX vs. GET | „**Toto je vědomá volba.** GET je SEO-friendly, sdílitelný. AJAX legitimní upgrade." |
| **OBHÁJIT** | Kalendář (full) | „Legitimní rozšíření — FullCalendar.js + REST API." |

**Časové kotvy:**
- @ 0:00 — „K oblastem, kde projekt má slabší body…"
- @ 0:15 — „Lazy loading — toto je oprávněná připomínka oponenta…"
- @ 0:45 — „AJAX filtry — toto je vědomá volba…"
- @ 1:00 — „Cache a SEO plugin chybí — to uznávám…"

**Nezapomenout:**
- **Rozlišovat 3 kategorie**: přijmout (lazy LCP, cache, SEO) · vysvětlit (AJAX) · obhájit (kalendář reframe).
- Otvírací věta **„chci je sám zmínit, protože je znám"** = silný signál jistoty.
- **NEUTÉCT do defenzivy** u věcí, kde má oponent pravdu. Přiznání = jistota, ne slabost.

---

## Slide 8 — Závěr (0:30)

**Klíčové fráze:**
- „Většina bodů zadání splněna vlastní implementací"
- „Hlavní přínos: vlastní řešení CPT, meta polí, filtrace, rolí"
- „**Děkuji za pozornost. Web mám otevřený v prohlížeči pro případné dotazy.**"

**Nezapomenout:**
- **Zpomalit, dýchat**, dívat se na komisi, ne na slide
- Po posledním slově **zůstat klidně stát**, ne se zmateně rozhlížet
- Pokud komise mlčí, **vydržet 5 s** — pak: „Jsem připraven na otázky."

---

## Q&A — DEMO TALKING POINTS

> **Princip:** demo NENÍ scripted block. Komise se zeptá → reaguj. Web je otevřený v browseru.

### Co říct PŘED přepnutím na demo

> „Mohu to ukázat živě na webu — moment, přepínám na browser."
> *(přepne Alt+Tab)*

### Talking points pro jednotlivé scénáře

#### A) Komise se zeptá na filtry / GET / AJAX
**Otevři:** `/zapasy`
**Říkej:**
> „Tady je stránka zápasů. Vyberu kategorii Muži A — vidíte v URL parametr `?kat=muzi-a`. To je vědomá volba — odkaz je **sdílitelný**, indexovatelný Googlem. Přidám sezónu 2025/2026 a stav Odehrané. Stránka se přefiltruje. Pokud bych chtěl AJAX, server by místo celé stránky vrátil jen HTML fragment přes `get_template_part`."

#### B) Komise se zeptá na banner / nadcházející zápasy
**Otevři:** Homepage
**Říkej:**
> „Banner s nadcházejícími zápasy generovaný přes `WP_Query` orderby date, omezeno na status `nadchazejici`. Karty mají barevný klubový proužek a velké skóre. To je část funkčního ekvivalentu kalendáře."

#### C) Komise se zeptá na galerii / lightbox
**Otevři:** `/galerie` → klikni na album → klikni na fotku
**Říkej:**
> „Alba jsou taxonomií sezóna. Klik na fotku otevře **nativní HTML `<dialog>` element** — žádný externí lightbox plugin. To je princip celé práce: standard místo závislostí."

#### D) Komise se zeptá na vlastní plugin / admin
**Otevři:** WP admin (pokud login funguje) → Plugins → Custom Fields
**Říkej:**
> „Tady vidíte aktivovaný plugin `slavoj-custom-fields` v `wp-content/plugins/`. Z hlediska deploymentu je identický s ACF — vlastní header, hooks, role. V CPT zápas vidíte meta boxy generované tímto pluginem."

#### E) Komise se zeptá na mobilní zobrazení
**Otevři:** DevTools (F12) → responsive mode (Ctrl+Shift+M)
**Říkej:**
> „Mobile-first design — vidíte hamburger menu bez vlastního JavaScriptu, jen Bootstrap třídy. Karty zápasů se přepnou do jednoho sloupce, banner zůstane pixel-perfect."

#### F) Komise se zeptá na Starou gardu / taxonomii
**Otevři:** WP admin → CPT zápas → editace zápasu
**Říkej:**
> „V dropdownu kategorie vidíte i Starou gardu, ačkoliv se technicky používá jen u galerií. Validační vrstvu by řešil `save_post` hook v pluginu — pokud by admin omylem přiřadil, hook by to odmítl."

### Záchranné fráze v demu

| Situace | Co říct |
|---------|---------|
| Stránka se načítá > 3 s | „Web je na sdíleném hostingu, načítání může chvíli trvat. Cache plugin LiteSpeed by tohle vyřešil — to je jedno z plánovaných rozšíření." |
| Hosting nereaguje úplně | „Hosting nereaguje. Přepnu se na záložní screenshoty." *(otevři backup PDF)* |
| Web vrátí chybu 500 | „To je dobrá ukázka, proč produkční nasazení vyžaduje monitoring. Mohu pokračovat dotazy slovně, screenshoty mám v záloze." |
| Lukáš zabloudí v navigaci | „Vrátím se zpět na úvodní stránku." *(klik na logo)* |

### Kdy NEPŘEPÍNAT na demo

- Pokud otázka je teoretická (např. „proč jste zvolil Bootstrap?") — odpověz slovně, nepřepínej
- Pokud otázka cílí na konkrétní řádek kódu — řekni „mohu odkázat na příslušnou pasáž v dokumentaci"
- Pokud otázka je rychlá (< 30 s odpovědi) — neztrácet čas přepínáním

---

## Záchranné fráze pro nečekané otázky

| Situace | Co říct |
|---------|---------|
| Nevím odpověď | „To je dobrá otázka. Nevím přesně, ale šel bych se podívat do [konkrétní dokumentace]." |
| Komise namítá chybu | „Ano, to je platná výtka. Řešení by bylo [konkrétní krok]." |
| Komise zpochybňuje rozhodnutí | „Rozhodl jsem se tak ze tří důvodů: [důvod 1], [důvod 2], [důvod 3]." |
| Komise se ptá na detail v kódu | „Mám web otevřený, mohu ukázat konkrétní funkci?" |
| Nepochopil jsem otázku | „Mohu si ověřit, jestli jsem otázku pochopil správně? Ptáte se na…?" |

---

## Den obhajoby — širší checklist (před cestou do školy)

- [ ] Cue cards vytištěné (1 strana A4 oboustranně)
- [ ] Handout vytištěný 4×: pro 4 členy komise (vedoucí, oponent, předseda, místopředseda)
- [ ] Laptop s notebookem, nabíječka, redukce na HDMI/VGA (kmenová třída)
- [ ] Slidy lokálně + cloud zálohy (Google Drive, e-mail si poslat)
- [ ] Backup PDF screenshotů
- [ ] Mobilní hotspot funkční, datový tarif OK
- [ ] Dokumentace `.pdf` lokálně i v cloudu (kdyby komise chtěla referenci)
- [ ] Vyspat se, snídaně, 0 kofeinu navíc
