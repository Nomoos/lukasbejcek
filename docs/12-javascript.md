# 12 – JavaScript v projektu

## Pravidlo projektu

> Minimální JavaScript — pouze tam kde Bootstrap nebo čisté HTML/CSS nestačí.
> Filtry, formuláře a stránkování fungují jako čisté `<form method="get">` bez AJAXu.

---

## Použité knihovny

| Knihovna | Verze | Způsob načtení | Účel |
|----------|-------|----------------|------|
| Bootstrap JS | 5.3.3 | CDN | Mobilní navigace (hamburger), dropdown menu |

---

## Vlastní JS kód (~70 řádků)

### 1. `onchange="this.form.submit()"` — filtry

**Soubory:** `archive-zapas.php`, `archive-tym.php`, `page-galerie.php`

**Co dělá:** Odešle formulář okamžitě po změně `<select>`, bez nutnosti klikat na tlačítko.

**Záloha bez JS:** Implementována ve všech filtrech přes `<noscript>` tlačítko — formulář funguje i bez JS.

**Lze nahradit?** Ne bez kompromisu — jedinou alternativou je viditelné submit tlačítko.

**Závěr: ✅ Ponechat.**

---

### 2. Lightbox galerie — `single-galerie.php` (~55 řádků)

**Co dělá:** Po kliknutí na fotku zobrazí překryvné okno s plnou verzí. Navigace šipkami a klávesnicí (Escape, ← →).

**Bez JS:** Fotky by se otevíraly jako samostatné stránky.

**Lze nahradit?** CSS `:target` alternativa neumožňuje klávesnicovou navigaci mezi fotkami.

**Závěr: ✅ Ponechat.**

---

### 3. Přidávání střelců — `single-zapas.php` (~12 řádků)

**Co dělá:** Tlačítka u jmen hráčů v admin formuláři přidají jméno do pole "Střelci" jedním kliknutím.

**Bez JS:** Uživatel zadá jméno ručně. Formulář funguje bez omezení.

**Závěr: ✅ Ponechat** — pohodlnostní funkce pro admina.

---

## Celkové hodnocení

Projekt splňuje požadavek na minimální JavaScript. Vlastní kód tvoří ~70 řádků ve 2 souborech + inline `onchange` atributy. Každé použití je odůvodnitelné a kde je to možné, existuje funkční záloha bez JS.

---

*Vytvořeno: březen 2026*
