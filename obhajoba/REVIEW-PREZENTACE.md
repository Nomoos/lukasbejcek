# Review: struktura prezentace k obhajobě MP

**Reviewer:** assistant
**Datum:** 2026-05-13
**Předmět review:** `PREZENTACE-OBHAJOBA.md` — finální plán 8 slidů
**Cíl review:** identifikovat strukturální slabiny a navrhnout konkrétní úpravy

> ## ✅ STATUS: VYŘEŠENO
>
> Strukturální problémy identifikované v tomto review byly z velké části vyřešeny pozdějšími rozhodnutími autora — demo se přesunulo do Q&A, tón obhajoby přerámován do 3 kategorií reakcí (přijmout / vysvětlit / obhájit), narrativ kalendáře přitvrzen na YAGNI postoj (commit `f512a7f`).
>
> Tento dokument je ponechán jako **historická reference**. Aktuální plán viz [`PREZENTACE-OBHAJOBA.md`](./PREZENTACE-OBHAJOBA.md), postup dokončení viz [`PRUVODCE-FINALIZACE.md`](./PRUVODCE-FINALIZACE.md).

---

> **Původní verdikt:** Plán je obsahově silný (odpovědi na všech 6 otázek vetknuté, reframe kalendáře, vlastní plugin jako deployment artefakt), ale **strukturálně má 5 problémů**, které brzdí dramaturgii a oslabují celkový dojem. Doporučení: jednoznačná přeskupení (žádný slide nepřepisovat, jen 3 z nich přesunout/sloučit).

---

## Souhrn problémů (od největšího po nejmenší)

| # | Problém | Závažnost | Doporučená akce |
|---|---------|-----------|-----------------|
| 1 | Demo (slide 5) přijde příliš pozdě | 🔴 vysoká | **Posunout na pozici 3** |
| 2 | Slide 7 „Slabá místa" je defensivní zóna | 🔴 vysoká | **Rozsekat výtky mezi relevantní slidy** |
| 3 | Slide 6 (Dokumentace + Jazyk) je filler | 🟡 střední | **Sloučit do titulního / závěru** |
| 4 | Sekce IV (Jazyk) má 0 informační hodnotu | 🟡 střední | **Vyhodit ze slidů úplně** |
| 5 | Závěr nemá hlavní message | 🟡 střední | **Přidat osobní reflexi / klíčovou větu** |
| 6 | „Sekce X posudku" zní rétoricky kostrbatě | 🟢 nízká | Přejmenovat — ústně neříkat |
| 7 | Slide 3 má 1:20 a hodně textu | 🟢 nízká | Riziko překotné řeči — zkrátit o 1 odstavec |

---

## Detailní analýza

### 🔴 Problém 1: Demo přichází příliš pozdě (slide 5 / pozice 5 z 8)

**Co se děje:** Komise prvních 4–5 minut poslouchá teorii (zadání, ACF, datový model) **bez vizuálního důkazu, že web funguje**. Demo je naplánováno jako pátý slide — když pozornost komise klesá a kontext už je „akademický" místo „produkční".

**Proč to vadí:**
- Demo má největší impact, když přijde DŘÍVE — komise pak teorii poslouchá s konkrétní představou v hlavě.
- Pozdní demo = pozorování „je hotové" se mísí s defensivní zónou Slabá místa hned po něm. Špatná návaznost.
- Pokud hosting nereaguje a Lukáš musí na záložní screenshoty, na pozici 5 už nezbývá čas přesvědčit komisi jinak.

**Doporučení:** **Posunout demo na pozici 3** — hned po splnění zadání. Komise vidí, co web umí; pak slyší, jak to bylo postavené (slidy 4–6).

---

### 🔴 Problém 2: Slide 7 je defensivní zóna se vším špatným pohromadě

**Co se děje:** Slide „Slabá místa & rozšíření" obsahuje 5 položek: lazy LCP, AJAX, kalendář full, cache plugin, SEO plugin. Na 1:20 = ~16 s na položku, přitom **všechny zní jako přiznání chyby**.

**Proč to vadí:**
- Vizuálně jeden slide se všemi negativy → komise odejde s dojmem „dlouhý list problémů".
- Položky jsou tematicky různé (výkon, UX, plugin management) — sloučení do jedné tabulky působí jako „seznam výtek", ne jako „přehled vědomých rozhodnutí".
- Tempo 16 s na položku = překotné, riziko brept.

**Doporučení:** **Rozsekat výtky mezi relevantní slidy**:

| Výtka | Kam ji přesunout | Proč |
|-------|------------------|------|
| **Lazy LCP** (Bělský Q3) | Krátká vsuvka do slide 5 (vlastní implementace) NEBO nový mini-slide po architektuře | Patří k „vědomá rozhodnutí + co bych vylepšil" |
| **AJAX filtry** (Háka Q2) | Slide 5 (vlastní implementace) — část „GET je vědomá volba" | AJAX patří k filtrům, ne ke kalendáři |
| **Cache + SEO plugin** | Závěrečný slide „Co bych dodělal" — jako body do budoucna | Toto je legitimní rozšíření, ne přiznání chyby |
| **Plnohodnotný kalendář** | Slide 2 (reframe kalendáře) má už zmínku FullCalendar.js → necháváme | Už pokryto |

Po rozdělení slide 7 zmizí. Místo něj zůstane krátký **slide „Co bych dodělal"** (~0:40) s pozitivním tónem.

---

### 🟡 Problém 3: Slide 6 (Dokumentace + Jazyk) je filler

**Co se děje:** Třicet sekund na suchá čísla — 40 stran, 0 % shoda, „vynikající" hodnocení. To je „performance metric" slide, ne dramaturgický bod.

**Proč to vadí:**
- 30 s + přepínání slidů = ~10 % času na slide bez nového obsahu.
- Komise dostane stejná čísla v handoutu — tam patří.
- Pokud Lukáš sklouzne s časem o 30 s, **tento slide je první kandidát na vyhození**.

**Doporučení:** **Smazat slide. Místo toho:**
- Tři čísla (40 / 0 % / vynikající) přidat jako **podtitulek na titulní slide** nebo **na závěrečný slide**.
- Při titulním slide Lukáš řekne: „Web je nasazený, dokumentace má 40 stran, shoda 0 %." → tři vteřiny.

---

### 🟡 Problém 4: Sekce IV (Jazyk) má v prezentaci nulovou hodnotu

**Co se děje:** „Velmi dobrá jazyková úroveň" je formální položka posudku, ale **nepředstavuje žádný diskusní bod pro komisi**. Není o čem mluvit.

**Doporučení:** **Vyhodit ze slidů úplně.** Pokud se komise zeptá na jazyk → odkaz na handout.

---

### 🟡 Problém 5: Závěr (slide 8) nemá hlavní message

**Co se děje:** Závěr aktuálně obsahuje:
> „Projekt splňuje většinu bodů zadání s vlastní implementací bez závislosti na externích pluginech. Hlavní přínos je vlastní řešení CPT, meta polí, filtrace a rolí. Rozšíření jsou přímočará a popsaná v dokumentaci."

To je **shrnutí, ne klíčová věta**. Komise odejde bez „vzkazu, co si zapamatovat".

**Doporučení:** Přidat **jednu hlavní větu** typu:
> „Pokud bych projekt měl shrnout jednou větou — vlastní implementace mě naučila WordPress jako platformu, ne jako nástroj. To považuji za hlavní přínos práce."

Nebo:
> „Vědomě jsem vyměnil rychlost vývoje za porozumění a obhajitelnost. To se mi v průběhu obhajoby vrátilo."

(Druhá varianta je sebevědomější, ale i riskantnější — pokud komise namítne, vyžaduje pohotovou reakci.)

---

### 🟢 Problém 6: „Sekce X posudku" zní rétoricky kostrbatě

**Co se děje:** Slide 2 a 3 v mluveném textu obsahují fráze typu „Sekce první posudku — splnění zadání" a „Sekce druhá posudku — vlastní přínos."

**Proč to vadí:**
- Komise chce slyšet o **práci**, ne o **posudku**.
- Mluvit o struktuře posudku otevřeně **signalizuje úzkost** — jako by se Lukáš schovával za rámec posudku, místo aby vedl vlastní příběh.
- Posudek by měl být skrytá kostra, ne explicitní téma.

**Doporučení:** **Slidy nadepsat jiným způsobem** a v mluveném textu vyhodit slovo „sekce posudku":
- „Sekce I." → **„Co měl klub a co jsem dodal"**
- „Sekce II." → **„Jak je to postavené"** nebo **„Vědomá rozhodnutí"**
- „Sekce III." → integrováno do úvodu / závěru

---

### 🟢 Problém 7: Slide 3 má 1:20 a 4 odstavce textu

**Co se děje:** Mluvený text slide 3 obsahuje (a) intro, (b) dva deployment artefakty, (c) odpověď Bělský Q1 (ACF, 3 důvody), (d) odpověď Háka Q1 (výhody/nevýhody), (e) WP konvence. To je **5 témat na 80 sekundách**.

**Riziko:** překotná řeč, omluva za zrychlení, dojem „má toho moc na srdci, ale neumí vybrat".

**Doporučení:** Vyhodit poslední odstavec o WP konvencích (`wp_enqueue_scripts`, escapování). Komise to ví, a kdyby se ptala, je to v handoutu.

---

## Souhrnný návrh nové struktury (po implementaci doporučení 1–5)

| # | Slide | Čas | Změna oproti aktuálnímu |
|---|-------|-----|-------------------------|
| 1 | Titulní + projekt v číslech | 0:30 | Přidat „40 stran / 0 % shoda / nasazeno" jako podtitul |
| 2 | Co měl klub a co jsem dodal (zadání + reframe kalendáře) | 1:15 | Beze změny obsahu, jen přejmenovat |
| 3 | **ŽIVÉ DEMO** | 1:30 | **Posunuto z pozice 5** |
| 4 | Architektura: šablona + vlastní plugin | 1:00 | Vyjmout odpověď ACF (do slidu 5) |
| 5 | Vědomá rozhodnutí (ACF, AJAX, lazy LCP) | 1:30 | Sloučit Q1+Q2 z původního slide 3+7 |
| 6 | Datový model (Stará garda) | 0:50 | Beze změny |
| 7 | Co bych dodělal (cache, SEO, kalendář full) | 0:40 | Zúženo, pozitivní tón |
| 8 | Závěr + hlavní věta + dotazy | 0:30 | Přidat hlavní message |
| Σ | | **7:45** | + 15 s buffer = 8:00 |

> **Pozn.:** Slide 6 (Dokumentace + Jazyk) vyhozen, slide 7 zúžen, demo posunuto na pozici 3.

---

## Co plán dělá DOBŘE (nepřepisovat)

| ✅ | Co je správně |
|---|---------------|
| ✅ | 8 slidů — správný počet pro 8 minut |
| ✅ | Odpovědi na 6 otázek z posudků vetknuté přímo do mluveného textu — komise neuvidí samostatný Q&A slide |
| ✅ | Reframe kalendáře na banner + filtry — silná, obhajitelná argumentace |
| ✅ | Vlastní plugin `slavoj-custom-fields` jako samostatný deployment artefakt — kontruje Hákovu výtku o nasazení |
| ✅ | Demo skript v cue cards s fallbackem na záložní screenshoty |
| ✅ | Cue cards + handout vytvořené jako samostatné dokumenty |
| ✅ | Mapování otázek → slidů explicitně tabulkou |
| ✅ | Checklist před obhajobou (cache warm-up, tištěné karty, voda) |

---

## Doporučená priorita úprav

Pokud chce Lukáš upravit jen 3 věci, nech:

1. **Posunout demo na pozici 3** (problém 1) — největší dramaturgický zisk
2. **Rozsekat slide 7 mezi relevantní slidy** (problém 2) — odstraní defensivní zónu
3. **Smazat slide 6 (Dokumentace + Jazyk)** (problémy 3 + 4) — uvolní 30 s, které jsou potřeba kvůli posunu demo

Problémy 5–7 jsou kosmetické, mohou počkat.

---

## Otevřené otázky pro autora

1. **Souhlasíš s posunem demo na pozici 3?** Riziko: pokud hosting nereaguje, plán se zhroutí brzy.
2. **Chceš slide 7 úplně zrušit a rozprostřít výtky, nebo nechat slide „Co bych dodělal" zúžený?**
3. **Chceš v závěru osobní hlavní větu** (riskantnější, ale silnější) **nebo bezpečné shrnutí**?
