# Cue cards — Obhajoba MP (Lukáš Bejček)

> **K tisku:** 1 strana A4, oboustranně, ohnout na 4 sloupce.
> **Použití:** v ruce při prezentaci. Nečti — jen klíčové fráze a časové kotvy.
> **Cíl času:** 7:30 mluveného + 30 s buffer = 8:00 total.

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
- „Plugin v `wp-content/plugins/`, vlastní header, aktivace v administraci"
- ACF reframe: „**Tři důvody:** porozumění · breaking changes · obhajitelnost"
- ACF konkrétně: „**ACF 5 → 6 mělo breaking changes v meta API**"
- Háka Q1: „**Výhody:** transparentnost · žádná závislost · výkon"

**Časové kotvy:**
- @ 0:00 — „Sekce druhá — vlastní přínos"
- @ 0:20 — „Dva deployment artefakty…"
- @ 0:35 — „Pan Bělský se ptá, proč ne ACF…"
- @ 1:00 — „Pan Háka se ptá na výhody a nevýhody…"

**Nezapomenout:** **NEZNÍT VINNĚ.** Vlastní impl. je hlavní přínos, ne slabost.

---

## Slide 4 — Datový model + Stará garda (1:00)

**Klíčové fráze:**
- „6 CPT, 4 taxonomie — sezóna a kategorie sdílené"
- „Sezóna je **taxonomie**, ne meta — sdílená přes zápasy, týmy, galerii"
- Bělský Q2: „`WP_Query` `tax_query` — frontend filtruje, **data v DB by byla nekonzistentní**"
- „Řešení: **`save_post` hook s validací**"

**Časové kotvy:**
- @ 0:00 — „Datový model — 6 typů, 4 taxonomie"
- @ 0:25 — „Pan Bělský se ptá na Starou gardu…"

**Nezapomenout:** komise může chtít vidět ER diagram — připravit v dokumentaci PDF na druhém monitoru.

---

## Slide 5 — Galerie + ŽIVÉ DEMO (1:30)

**Demo skript (přesné pořadí, natrénovat 5×):**

1. **Přepnout na browser** (Alt+Tab nebo cmd+Tab)
2. **Homepage** → ukázat banner s nadcházejícími zápasy (~10 s)
3. Klik **Zápasy** → vybrat **Muži A** + **2025/2026** + **Odehrané** → karty se přefiltrují (~20 s)
4. Klik **Galerie** → otevřít album → lightbox (~15 s)
5. **Zpět na slidy** (~5 s)

**Záchranná hláška, kdyby hosting nereagoval > 3 s:**
> „Pojďme se podívat na záložní screenshoty, hosting reaguje pomalu."
→ otevřít PDF screenshotů.

**Klíčové fráze při demu:**
- „URL `?kat=muzi-a` — GET parametr, **sdílitelný odkazem**"
- „Bez AJAXu — vědomá volba"

**Nezapomenout:** **otevřít browser tab + cache warm-up 30 min před obhajobou.**

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

**Klíčové fráze:**
- Lazy LCP (Bělský Q3): „**Plně uznávám**. Banner je LCP element. Řešení: výjimka pro hero, nebo nativní `wp_get_attachment_image` z WP 5.5+."
- AJAX (Háka Q2): „`wp_ajax_filter_zapasy` + `admin-ajax.php` + History API. Aktuální GET je **vědomá volba — SEO-friendly**."
- Cache: „**LiteSpeed Cache**"
- SEO: „**Yoast** nebo **Rank Math** + GA4"
- Kalendář full: „**FullCalendar.js** + REST API"

**Časové kotvy:**
- @ 0:00 — „Pan Bělský se ptá na lazy loading…"
- @ 0:30 — „Pan Háka se ptá, jak rozšířit filtry o AJAX…"
- @ 1:00 — „Pro produkci doplním cache plugin a SEO plugin…"

**Nezapomenout:** „**Plně uznávám**" u lazy LCP — to je **silný moment**, signál jistoty.

---

## Slide 8 — Závěr + dotazy (0:30)

**Klíčové fráze:**
- „Většina bodů zadání splněna vlastní implementací"
- „Hlavní přínos: vlastní řešení CPT, meta polí, filtrace, rolí"
- „**Děkuji za pozornost. Otázky?**"

**Nezapomenout:** **zpomalit, dýchat**, dívat se na komisi, ne na slide.

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

## Před obhajobou (checklist 30 min předem)

- [ ] Hosting načten v browseru, cache warm-up (otevřít všechny hlavní stránky)
- [ ] Slidy spuštěné v prezentačním režimu
- [ ] Záložní PDF screenshoty otevřené na druhém monitoru / mobilu
- [ ] Cue cards vytištěné v ruce
- [ ] Voda po ruce
- [ ] Hodinky / stopky viditelné
- [ ] Vypnuté notifikace na laptopu
- [ ] Mobilní hotspot připravený (pro případ výpadku Wi-Fi)
