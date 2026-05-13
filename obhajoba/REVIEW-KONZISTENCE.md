# Revize konzistence materiálů k obhajobě

**Datum:** 2026-05-13
**Předmět:** Kontrola 6 souborů na vzájemnou konzistenci, defenzivní tón a zmínky věcí, které nebyly implementovány.

> **Verdikt:** **Obsah ZATÍM NENÍ připraven pro Google Slides.** Hlavní blocker: `HANDOUT-OBHAJOBA.md` má **starý narrativ** („vlastní implementace místo pluginů"), který je v rozporu s aktualizovanými 4 soubory („portovaná optimalizovaná výseč"). Pokud Lukáš trénuje Q&A z HANDOUTu, v ústní obhajobě by formulačně skočil mezi dvě verze. **Doba úprav: ~45 min, pak READY.**

---

## Seznam konkrétních nekonzistencí

### N1. 🔴 Narrativ vlastní implementace neaktualizován v HANDOUT-OBHAJOBA.md

| Soubor | Říká |
|--------|------|
| PREZENTACE-OBHAJOBA.md slide 3 | „Portoval jsem do vlastního pluginu jen optimalizovanou výseč" |
| CUE-CARDS.md slide 3 | „Optimalizace pluginů na potřebnou výseč je hlavní přínos" |
| STRUCNY-PREHLED.md | „Portována jen jako optimalizovaná výseč" |
| slides-copypaste.md | (totéž) |
| **HANDOUT-OBHAJOBA.md** | **„Vlastní implementace místo doporučených pluginů"** ✗ |

**Důsledek:** Lukáš trénuje Q&A z HANDOUTu, v Q&A by mohl říct „neřešil jsem doporučené pluginy" — komise pak namítne „ale na slidu 3 jste říkal, že většina vychází z jejich principů". Nekonzistence v ústní obhajobě.

### N2. 🔴 SEO / GA4 split neaktualizován v HANDOUT

| Soubor | Říká |
|--------|------|
| PREZENTACE-OBHAJOBA.md | SEO = uznávám (alt atributy, meta tagy) · GA4 = plánováno, nestihlo se |
| STRUCNY-PREHLED.md | SEO = uznávám · GA4 = plánováno |
| CUE-CARDS.md | totéž |
| **HANDOUT-OBHAJOBA.md** | **„SEO & analytika \| ⚠️ plán: Yoast + GA4"** (lumped together) ✗ |

### N3. 🟡 Memory file `posudky_a_obhajoba.md` má staré framing + rozbitý odkaz

- Line 22–30: **„7 slabých míst k opravě PŘED obhajobou"** — ale scope je locknut, nic se neopravuje. Memory by měla říkat „připravené reakce", ne „opravit".
- Line 29: **„SEO plugin chybí — alespoň základní Yoast/Rank Math"** — slovo „alespoň" implikuje implementační plán, ne obhajitelnou reakci.
- Line 45: odkaz `[[../../../POSUDKY.md]]` — soubor byl přesunut do `obhajoba/POSUDKY.md`.

### N4. 🟡 PREZENTACE slide 3 — drobně schizofrenní úvodní věta

PREZENTACE-OBHAJOBA.md slide 3 mluvený text začíná:
> „Oba posudky jako hlavní přínos uvádějí, že **nepoužívám doporučené pluginy** a vše implementuji vlastním PHP kódem."

Pak ale ve stejném slidu argumentuje, že „většina vychází z principů doporučených pluginů". Dvě věty navzájem částečně si protiřečí.

### N5. 🟡 HANDOUT „Co byste dělal jinak (1)" je defenzivní

HANDOUT line 113:
> „**(1)** Od začátku přidat SEO a cache plugin — **to bylo v zadání**."

„To bylo v zadání" zní jako sebeobviňování. Komisi to neotevře nic, co už nezná, ale signalizuje rezignaci. Lepší: „Více času na SEO plugin a GA4 — věděl jsem o nich, stihl jsem jen základní meta tagy a alt atributy."

### N6. 🟢 Yoast je všude jen jako budoucí rozšíření — ALE specifické jméno opakovaně

Yoast je zmíněn v 5 souborech, vždy jen jako možnost. To není problém. **Drobné riziko:** komise se zeptá „Proč Yoast a ne Rank Math?". V STRUCNY-PREHLED je věta „SEO plugin Yoast / Rank Math (sitemap, breadcrumbs, structured data)" — uvedení obou alternativ tomu předchází, ale specifikace „sitemap, breadcrumbs, structured data" otevírá detail, který Lukáš musí umět rozvést.

**Doporučení:** v STRUCNY-PREHLED (rozdaný handout) zobecnit na „SEO plugin (např. Yoast, Rank Math) — sitemap, breadcrumbs". V PREZENTACE / CUE-CARDS ponechat detail.

### N7. 🟢 POSUDKY.md — sekce „Souhrn k obhajobě" má staré framing

Můj přidaný oddíl na konci POSUDKY.md říká „7 slabých míst k opravě PŘED obhajobou" — stejný problém jako memory. Mělo by se přeformulovat na „připravené reakce".

---

## Doporučené úpravy po souborech

### PREZENTACE-OBHAJOBA.md

**Změnit:** v slide 3 mluveném textu vyhodit nebo přeformulovat větu „Oba posudky jako hlavní přínos uvádějí, že nepoužívám doporučené pluginy…".
**Doporučená nová:** „Oba posudky jako hlavní přínos uvádějí přístup ke správě obsahu vlastním kódem místo plné integrace doporučených pluginů."

**Ponechat:** zbytek.

**Verdikt:** ~2 min úpravy, jinak ready.

### CUE-CARDS.md

**Změnit:** nic kritického.

**Verdikt:** ready.

### HANDOUT-OBHAJOBA.md (interní příprava)

**Blocker — nutné upravit před tréninkem Q&A:**

1. **Tabulka „Klíčové architektonické rozhodnutí"** (line 38–49):
   - Změnit nadpis: „Vlastní implementace místo doporučených pluginů" → „Vlastní implementace = optimalizovaná výseč z principů doporučených pluginů"
   - Změnit sloupec „Nahrazeno" → „Portovaná optimalizovaná výseč"
   - Přepsat 4 řádky tabulky podle vzoru v PREZENTACE.md

2. **Tabulka „Splnění bodů zadání"** (line 33–34):
   - Bod 8: „SEO & analytika ⚠️ plán: Yoast + GA4" → rozdělit na dva body nebo přepsat: „⚠️ částečně (alt atributy, meta tagy); SEO plugin + GA4 jako rozšíření"

3. **Bělský Q1** (line 67–69):
   - Přidat věty o portované optimalizované výseči
   - Vzor: viz PREZENTACE slide 3 mluvený text

4. **Háka Q1** (line 79–81):
   - Stejně: zmínit, že vychází z principů pluginů + výseč

5. **„Co byste dělal jinak"** (line 113):
   - Přeformulovat (1) z „Od začátku přidat SEO a cache plugin — to bylo v zadání" na „Více času na SEO plugin a GA4 — věděl jsem o nich, stihl jsem jen alt atributy a meta tagy v kódu"

**Verdikt:** ~25 min úpravy.

### STRUCNY-PREHLED.md (handout pro komisi)

**Změnit:** „SEO plugin Yoast / Rank Math (sitemap, breadcrumbs, structured data)" → „SEO plugin (např. Yoast nebo Rank Math) pro sitemap, breadcrumbs a structured data".

**Ponechat:** zbytek je neutrální a konzistentní.

**Verdikt:** ~2 min úpravy, jinak ready.

### POSUDKY.md

**Změnit:** sekce „Souhrn k obhajobě" → „Slabá místa, na která se komise pravděpodobně zeptá":
- Přejmenovat „Slabá místa, na která se komise pravděpodobně zeptá" zachovat
- Ale podsekci „k opravě před obhajobou" přepsat na „k zmínění v obhajobě jako PŘIJATÉ"
- Explicitně dodat: „Scope je locknut na prezentační materiály — kód se neupravuje."

**Verdikt:** ~5 min úpravy.

### memory/posudky_a_obhajoba.md

**Změnit:**
1. „7 slabých míst k opravě PŘED obhajobou" → „7 slabých míst — připravené reakce v PREZENTACE/CUE-CARDS"
2. „SEO plugin chybí — alespoň základní Yoast/Rank Math" → „SEO plugin neimplementován — pouze alt atributy a meta tagy v kódu (reakce v slidu 7)"
3. Odkaz `[[../../../POSUDKY.md]]` → `[[../../../obhajoba/POSUDKY.md]]` (nebo `[[obhajoba/POSUDKY]]`)

**Verdikt:** ~5 min úpravy.

---

## Co odstranit

| Soubor | Co | Důvod |
|--------|-----|-------|
| HANDOUT | Defenzivní formulace „to bylo v zadání" | Sebeobviňující |
| HANDOUT | Sloupec „Nahrazeno" v tabulce architektury | Neaktuální narrativ |
| memory | „k opravě PŘED obhajobou" | Scope locknut |

## Co přeformulovat

| Soubor | Co | Jak |
|--------|-----|-----|
| HANDOUT | Bělský Q1 | + zmínit optimalizovanou výseč |
| HANDOUT | Háka Q1 | + zmínit optimalizovanou výseč |
| HANDOUT | Bod 7+8 zadání | Rozdělit SEO a GA4 |
| PREZENTACE | Slide 3 první věta | Vyhnout se „nepoužívám pluginy" |
| POSUDKY | „k opravě před obhajobou" | „k zmínění v obhajobě" |
| memory | totéž + opravit odkaz | viz výše |
| STRUCNY-PREHLED | „Yoast / Rank Math (...)" | „SEO plugin (např. Yoast nebo Rank Math)" |

## Co ponechat

| Soubor | Co | Proč |
|--------|-----|-------|
| PREZENTACE | Slide 7 (3 kategorie reakcí: přijmout/vysvětlit/obhájit) | Vyvážený tón |
| PREZENTACE | Slide 6 reframe kalendáře | Konzistentní napříč soubory |
| CUE-CARDS | Celé | Konzistentní |
| STRUCNY-PREHLED | Většina | Neutrální |
| POSUDKY | Transcript posudků (sekce I–IV) | Immutable |
| memory | Hodnocení + 6 otázek | Faktické |

---

## Yoast / SEO plugin — speciální audit

| Kde zmíněn | Jak | Verdikt |
|------------|-----|---------|
| PREZENTACE slide 7 | „Yoast nebo Rank Math by přidal sitemap..." | ✅ jen jako rozšíření |
| CUE-CARDS slide 7 | „Yoast / Rank Math by přidal sitemap..." | ✅ jen jako rozšíření |
| HANDOUT splnění zadání | „⚠️ plán: Yoast + GA4" | 🟡 příliš konkrétní pro plán |
| STRUCNY-PREHLED tabulka | „Yoast / Rank Math by přidal..." | ✅ jen jako rozšíření |
| STRUCNY-PREHLED rozšíření | „Yoast / Rank Math (sitemap, breadcrumbs, structured data)" | 🟡 zobecnit |
| slides-copypaste | totéž jako PREZENTACE | ✅ |
| memory | „alespoň základní Yoast/Rank Math" | 🔴 implikuje plán implementace |

**Souhrnné doporučení k Yoast:** ponechat ve všech souborech, ale **vždy formulovat jako alternativu** („např. Yoast nebo Rank Math"), nikdy jako preferovanou volbu. V STRUCNY-PREHLED v sekci rozšíření zobecnit.

---

## Defenzivní tón — souhrnný audit

| Soubor / místo | Tón | Verdikt |
|----------------|-----|---------|
| PREZENTACE slide 7 (lazy LCP) | „Toto je oprávněná připomínka oponenta" | ✅ PŘIJMOUT, vyvážený |
| PREZENTACE slide 7 (SEO) | „Uznávám jako mezeru" | ✅ PŘIJMOUT |
| PREZENTACE slide 7 (AJAX) | „Toto je vědomá volba" | ✅ VYSVĚTLIT |
| PREZENTACE slide 3 (ACF) | „Klíčové rozhodnutí, tři důvody" | ✅ OBHÁJIT |
| HANDOUT „Co byste dělal jinak" | „Od začátku přidat SEO — to bylo v zadání" | 🔴 sebeobviňující |
| HANDOUT Bělský Q3 | „Plně uznávám výtku" | ✅ PŘIJMOUT |
| memory | „k opravě PŘED obhajobou" | 🟡 strach z chyby |

**Verdikt:** většina souborů má vyvážený tón. Hlavní defenzivní bod je HANDOUT line 113.

---

## STRUCNY-PREHLED — otevírá nové otázky?

Sekce po sekci:

| Sekce | Otevírá novou otázku? | Riziko |
|-------|----------------------|--------|
| Cíl práce | Ne | — |
| Výsledný produkt | Ne | — |
| Vlastní přínos | „4 pluginy" + „portovaná výseč" → Komise: „Vy jste je tedy nainstaloval?" | 🟡 nízké, dá se vysvětlit |
| Použité technologie | Ne | — |
| Hlavní reakce — SEO | „řešil jsem jen alt atributy a meta tagy v kódu" → Komise: „Ukažte mi konkrétní meta tagy v header.php" | 🟡 střední, Lukáš musí mít připraveno |
| Hlavní reakce — GA4 | „plánováno, časově nestihnuto" | ✅ nízké |
| Možná rozšíření — „Yoast / Rank Math (sitemap, breadcrumbs, structured data)" | „Co je structured data?" | 🟡 nízké, Lukáš to musí umět |

**Verdikt:** STRUCNY-PREHLED je v podstatě bezpečný. Drobné riziko u SEO meta tagů — Lukáš musí být schopen ukázat konkrétní řádky v `header.php` (např. `<meta name="description">`, `<meta property="og:title">`).

---

## Finální verdikt

**Obsah NENÍ připraven pro Google Slides** v aktuálním stavu.

**Příprava na READY trvá ~45 min:**
1. HANDOUT-OBHAJOBA.md — aktualizovat narrativ + SEO/GA4 split + Co byste dělal jinak (25 min)
2. POSUDKY.md — přeformulovat sekci „Slabá místa" (5 min)
3. memory/posudky_a_obhajoba.md — aktualizovat framing + opravit odkaz (5 min)
4. PREZENTACE-OBHAJOBA.md — drobná oprava slide 3 první věty (2 min)
5. STRUCNY-PREHLED.md — drobné zobecnění Yoast (2 min)
6. Cross-check final pass — projít všechny soubory na nový narrativ (5 min)

**Poté:** READY pro Marp export → Google Slides.

**Alternativa:** pokud chcete jít do Google Slides okamžitě, nutné minimum je **bod 1** (HANDOUT). Ostatní jsou kosmetika.
