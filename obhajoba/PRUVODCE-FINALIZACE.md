# Průvodce dokončením obhajoby — krok za krokem

**Pro:** Lukáš Bejček
**Cíl:** Dotáhnout obhajobu od hotových textů do funkční Google Slides prezentace a být připravený na den D.

---

## Co máš v ruce (přehled)

Všechny texty jsou hotové a sjednocené. Zbývá:
1. Vytvořit Google Slides ze zdroje
2. Doplnit obrázky
3. Natrénovat
4. Vytisknout materiály

**Pro komisi vytiskni:**
- `STRUCNY-PREHLED.md` — **4×** (předseda, místopředseda, vedoucí, oponent)

**Pro sebe vytiskni:**
- `CUE-CARDS.md` — **1×** oboustranně A4

**NETISKNI komisi:**
- `HANDOUT-OBHAJOBA.md` (interní příprava Q&A)
- `POSUDKY.md`, `REVIEW-*.md` (interní reference)

---

## Krok 1 — Google Slides ze zdroje

### Cesta A — Marp → PPTX (doporučeno, ~15 min)

**Předpoklady:** Node.js (zkontroluj `node -v` v terminálu)

```powershell
# Instalace Marp CLI (jednou)
npm install -g @marp-team/marp-cli

# V adresáři projektu spusť:
marp obhajoba/prezentace.md --pptx --output obhajoba/prezentace.pptx

# Volitelně HTML náhled:
marp obhajoba/prezentace.md --html --output obhajoba/prezentace.html
```

**Import:**
1. Otevři [Google Drive](https://drive.google.com)
2. Nahraj `obhajoba/prezentace.pptx` (drag & drop)
3. Pravý klik na soubor → Open with → Google Slides
4. Google automaticky převede a otevře editor

### Cesta B — manuální paste (bez Node.js, ~60 min)

1. Otevři [Google Slides](https://slides.google.com) → Blank
2. Pro každý slide v `obhajoba/slides-copypaste.md`:
   - Nový slide (`Ctrl+M`)
   - Vlož **TITULEK** do title boxu
   - Vlož **OBSAH** do content boxu (tabulky přes Insert → Table)
   - Otevři speaker notes (View → Show speaker notes) a vlož **SPEAKER NOTES**

---

## Krok 2 — Doplnit obrázky

Slidy 1, 4, 5 potřebují vizuální podporu. Bez nich budou holé tabulky.

| Slide | Co doplnit | Odkud |
|-------|------------|-------|
| 1 (titulní) | Logo TJ Slavoj Mýto vpravo dole | Klubové materiály |
| 1 (titulní) | Malý mockup banneru | Screenshot z hostingu |
| 4 (datový model) | Mini ER diagram | Z `DOKUMENTACE.pdf` |
| 5 (galerie a ostatní) | 2–4 thumbnaily: galerie grid, lightbox, sponzoři, mobil | Screenshoty z hostingu |

**Jak nasnímat web:**
1. Otevři web na hostingu v Chrome
2. `F12` → DevTools → device toggle (Ctrl+Shift+M) pro mobilní pohled
3. Print Screen → ulož jako PNG
4. Insert → Image v Google Slides

---

## Krok 3 — Styling (kritické: kontrast pro učebnu)

Komise výslovně varovala před špatným kontrastem v kmenové třídě.

| Položka | Hodnota |
|---------|---------|
| **Pozadí** | čistě bílá `#FFFFFF` |
| **Hlavní text** | tmavě šedá `#1A1A1A` (NE čistá černá — méně namáhavá) |
| **Akcent (nadpisy, proužky, šipky)** | klubová modrá `#003366` nebo přesnější odstín z loga |
| **Tabulky** | hlavička barevná, řádky střídavě jemně podbarvené |
| **Font Title** | Roboto / Open Sans, 36–42 pt |
| **Font Body** | Roboto / Open Sans, 22–26 pt |
| **Page numbers** | zapnout (Slide → Apply layout → with footer) |

**Vyhni se:** světle šedé pastely, tmavomodrý text na modrém pozadí, dekorativní fonty.

---

## Krok 4 — Vytisknout materiály

### Pro komisi (4×):
- **`STRUCNY-PREHLED.md`** export do PDF nebo Word, vytisknout 1 strana A4

### Pro sebe (1×):
- **`CUE-CARDS.md`** oboustranně A4, ohnout na 4 sloupce

### Záložní PDF pro případ výpadku hostingu:
- Vyfotit screenshoty webu (homepage, /zapasy s filtry, /galerie, album, lightbox)
- Spojit do jednoho PDF
- Mít otevřené na druhém monitoru nebo v telefonu

---

## Krok 5 — Natrénovat (3 průchody minimum)

### 1. průchod — čisté čtení (~10 min)
- Přečíst speaker notes nahlas, **se stopkami**
- Cíl: 7:30–8:00 celkem
- Pokud > 8 min → najít, kde se zaseknout, zkrátit

### 2. průchod — bez čtení (~10 min)
- Klíčové fráze z `CUE-CARDS.md` na papír
- Mluvit volně, podle slidů
- **Stopky pořád zapnuté**
- Najít, kde sklouzáváš — tam si přidej kotvy

### 3. průchod — generálka (~15 min)
- Stejně jako den D: spusť slidy v prezentačním režimu
- Zkus i Q&A demo simulaci (přepnutí na browser)
- Žádné přerušení

### Bonus — natrénovat Q&A
- Otevři `HANDOUT-OBHAJOBA.md`
- Pro každou ze 6 otázek z posudků: nahlas, plynule, do 45 sekund
- Pak doplňující otázky (Bootstrap vs Tailwind, bezpečnost, 0 % shoda)

---

## Den obhajoby — checklist

### Před cestou do školy
- [ ] Cue cards vytištěné v batohu
- [ ] Stručný přehled 4× vytištěný v batohu
- [ ] Laptop nabitý + nabíječka + HDMI/VGA redukce
- [ ] Slidy zálohované v cloudu (Google Drive, e-mail si poslat)
- [ ] Backup PDF screenshotů na mobilu
- [ ] Mobilní hotspot funkční, datový tarif OK
- [ ] Dokumentace PDF v cloudu (kdyby komise chtěla referenci)
- [ ] Vyspaný, nasnídaný, 0 kofeinu navíc

### V učebně (5 min setup time od komise)
- [ ] Slidy spuštěné v prezentačním režimu (full screen)
- [ ] Browser tab → homepage klubu → **proklikat** všechny sekce (Zápasy, Galerie, album, Sponzoři) kvůli cache
- [ ] Backup PDF screenshotů otevřený na druhém monitoru / mobilu
- [ ] Cue cards v ruce
- [ ] Voda po ruce
- [ ] Hodinky / stopky viditelné
- [ ] Vypnuté notifikace (e-mail, Discord, Slack)
- [ ] 3× hluboký nádech, ramena dolů, úsměv

### Po závěrečném slidu („Děkuji za pozornost")
- [ ] **Počkat 5 sekund** mlčky — komise potřebuje chvilku
- [ ] Když mlčí: „Jsem připraven na otázky."
- [ ] Když položí otázku: **počkat na celou otázku**, pak odpovědět
- [ ] Demo otevírej až když je relevantní — viz `CUE-CARDS.md` sekce „Q&A — DEMO TALKING POINTS"

---

## Q&A — co dělat když…

| Situace | Co říct |
|---------|---------|
| Nerozumím otázce | „Mohu si ověřit, jestli jsem otázku pochopil správně? Ptáte se na…?" |
| Nevím odpověď | „To je dobrá otázka. Nevím přesně, ale šel bych se podívat do [konkrétní dokumentace / API reference]." |
| Komise namítá chybu | „Ano, to je platná výtka. Řešení by bylo [konkrétní krok]." |
| Komise zpochybňuje vědomé rozhodnutí | „Rozhodl jsem se tak ze tří důvodů: [důvod 1], [důvod 2], [důvod 3]." |
| Detail v kódu | „Mám web otevřený, mohu ukázat konkrétní funkci?" → otevři browser |
| Hosting nereaguje | „Hosting reaguje pomalu, přepnu na záložní screenshoty." → otevři backup PDF |

---

## Krizový plán

| Krize | Řešení |
|-------|--------|
| Slidy se nespustí | Backup PDF na flashce, otevřít přes standardní PDF reader |
| Internet vypadne | Mobilní hotspot, případně bez demo (mluv slovně) |
| Slovní zámrz uprostřed | „Dovolte mi vrátit se k tomuto bodu — [další věta z cue card]" |
| Komise mě přeruší | Doposlechnout, krátce odpovědět, zeptat se „Mohu pokračovat?" |
| Skončí mi čas | „Zbývající body jsou popsané v dokumentaci a stručném přehledu" |

---

## Klíčové principy obhajoby (3 věci nesmí chybět)

1. **NEŘÍKEJ „Pan Bělský / Háka se ptá"** — odpovědi jsou v autonomní argumentaci ve slidech. Když položí otázku v Q&A, odkážeš se: „Jak jsem zmínil v prezentaci…"

2. **3 kategorie reakcí na výtky:**
   - **PŘIJMOUT** (lazy LCP, cache plugin, SEO plugin) — „Toto je oprávněná připomínka"
   - **VYSVĚTLIT** (GA4, AJAX, sdílená taxonomie) — „Toto je vědomá volba s konsekvencemi"
   - **OBHÁJIT** (vlastní implementace = optimalizovaná výseč pluginů, kalendář = aktuální dostačující) — „Důvody jsou tři…"

3. **Kalendář = YAGNI.** Banner + filtry je pro klubový web plně dostačující. FullCalendar by byl overkill — dával by smysl až s dalšími typy událostí (akce, prodej lístků).

---

## Hodně štěstí. Jdeš na to.
