# Složka obhajoba/ — všechny materiály pro maturitní obhajobu

**Autor:** Lukáš Bejček · **Termín:** maturitní zkouška jaro 2026
**Hodnocení posudků:** chvalitebně (Bělský), chvalitebně (Háka) · shoda 0 %
**Cíl obhajoby:** posunout na výborně

---

## Obsah složky

| Soubor | Použití | Komu |
|--------|---------|------|
| **`PREZENTACE-OBHAJOBA.md`** | Master plán prezentace — slide po slidu s detailním mluveným textem, mapování otázek, časový rozpočet | Autor (příprava) |
| **`prezentace.md`** | Marp source — generování prezentace do PPTX/HTML pro Google Slides | Autor (export) |
| **`slides-copypaste.md`** | Slide-po-slide text pro manuální tvorbu v Google Slides | Autor (sazba) |
| **`CUE-CARDS.md`** | Krátké poznámky s časovými kotvami, demo skripty pro Q&A | Autor (k tisku, do ruky) |
| **`STRUCNY-PREHLED.md`** | 1 strana A4 — krátký handout pro komisi | **KOMISI** (4× výtisk) |
| **`HANDOUT-OBHAJOBA.md`** | Detailní odpovědi na 6+ otázek z posudků | Autor (interní příprava) — **NE komisi** |
| **`POSUDKY.md`** | Plný přepis posudků oponenta i vedoucího | Reference |
| **`REVIEW-PREZENTACE.md`** | Strukturální review plánu prezentace | Reference |

---

## Plán obhajoby (20 min total)

```
┌────────────────────────────────────────────────────────────┐
│  5 min setup        8 min prezentace        ~7 min Q&A     │
│  ─────────────      ─────────────────       ──────────     │
│  - slidy fullscreen - 8 slidů               - 6 otázek     │
│  - browser cache    - 7:30 mluvené          - live demo    │
│  - backup PDF       - 30 s buffer           - další dotazy │
└────────────────────────────────────────────────────────────┘
```

---

## Co vytisknout na obhajobu

- [ ] **`STRUCNY-PREHLED.md`** — **4 výtisky** pro komisi (předseda, místopředseda, vedoucí, oponent)
- [ ] **`CUE-CARDS.md`** — **1 výtisk** pro Lukáše do ruky (A4 oboustranně)
- [ ] Dokumentace `DOKUMENTACE.pdf` — **2 výtisky** pro vedoucího a oponenta (pokud žádají)

**Co NEtisknout pro komisi:** `HANDOUT-OBHAJOBA.md` — to je interní příprava, ne handout pro komisi.

---

## Jak dostat prezentaci do Google Slides

Mám připravené **dvě cesty**. Vyberte podle toho, co máte k dispozici.

### Cesta A: Marp → PPTX → Google Slides (rychlejší, formátování zachováno)

**Předpoklady:** Node.js + npm (nebo Docker)

```bash
# Instalace Marp CLI (jednou)
npm install -g @marp-team/marp-cli

# Export do PPTX
marp obhajoba/prezentace.md --pptx --output obhajoba/prezentace.pptx

# Alternativně export do HTML pro náhled
marp obhajoba/prezentace.md --html --output obhajoba/prezentace.html
```

**Import do Google Slides:**
1. Otevřít [Google Drive](https://drive.google.com)
2. Pravý klik → New → File Upload → vybrat `prezentace.pptx`
3. Po nahrání pravý klik na soubor → Open with → Google Slides
4. Google Slides automaticky převede PPTX a otevře editor
5. Doplnit obrázky (Figma návrhy, screenshoty webu, ER diagram) — viz `slides-copypaste.md` sekce „Doplnění obrázků"

### Cesta B: Ručně v Google Slides s copy-paste sheetem (bez instalace)

**Pro:** Lukáš který nemá Node.js / chce plnou kontrolu nad designem

1. Otevřít [Google Slides](https://slides.google.com)
2. Nová prezentace → Blank
3. Pro každý slide v `slides-copypaste.md`:
   - Vytvořit nový slide (Ctrl+M)
   - Vložit **TITULEK** do title boxu
   - Vložit **OBSAH** do content boxu (tabulky vložit přes Insert → Table)
   - Otevřít speaker notes panel (View → Show speaker notes)
   - Vložit **SPEAKER NOTES**
4. Doplnit obrázky (Figma návrh, ER diagram, screenshoty webu)
5. Aplikovat design settings ze `slides-copypaste.md` — pozadí bílé, akcent klubová modrá, fonty Roboto / Open Sans

**Odhad času:** Marp cesta ~15 min · ruční cesta ~60 min

---

## Setup 5 min před obhajobou (instrukce komise)

- [ ] Google Slides spuštěné v prezentačním režimu (Slideshow → Start from beginning)
- [ ] Browser tab → produkční web → proklikat všechny sekce kvůli cache warm-up
- [ ] Backup PDF screenshotů otevřený (telefon / druhý monitor)
- [ ] Cue cards v ruce
- [ ] Voda po ruce
- [ ] Hotspot zapnutý jako záloha sítě
- [ ] Notifikace na laptopu vypnuté

---

## Klíčové principy obhajoby

1. **Demo až v Q&A, ne během prezentace.** Web otevřený v browseru, použít kontextově podle dotazů komise.
2. **3 kategorie reakcí na výtky:**
   - **PŘIJMOUT** (Lazy LCP, cache, SEO plugin) — „toto je oprávněná připomínka; v další verzi bych…"
   - **VYSVĚTLIT** (sdílená taxonomie, AJAX vs. GET) — „toto je vědomá volba s kompromisy…"
   - **OBHÁJIT** (vlastní implementace = optimalizovaná výseč pluginů, kalendář = aktuální banner + filtry dostačující) — „rozhodnutí je vědomé, důvody jsou…"
3. **NEŘÍKAT „Pan Bělský/Háka se ptá".** Odpovědi jsou v autonomní argumentaci. V Q&A se odkážeš: „Jak jsem zmínil v prezentaci…"
4. **Kontrast** — bílé pozadí, tmavý text. Učebna má horší světlo než aula.

---

## Reference k dalším souborům projektu

- `../DOKUMENTACE.pdf` — finální dokumentace (40 stran)
- `../web/wp-content/themes/tj-slavoj-myto/` — šablona
- `../web/wp-content/plugins/slavoj-custom-fields/` — vlastní plugin
- `../Posudek/` — fotky původních posudků
