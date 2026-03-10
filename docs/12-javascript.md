# 12 – JavaScript v projektu

## Pravidlo projektu

> Minimální JavaScript — pouze tam kde Bootstrap nebo čisté HTML/CSS nestačí.
> Filtry, formuláře a stránkování fungují jako čisté `<form method="get">` bez AJAXu.

---

## Inventář použitého JS

### 1. `onchange="this.form.submit()"` — filtry

**Soubory:** `archive-zapas.php`, `archive-tym.php`, `page-galerie.php`

```html
<select name="kat" onchange="this.form.submit()">...</select>
<noscript>
  <button type="submit">Filtrovat</button>
</noscript>
```

**Co dělá:** Odešle formulář okamžitě po změně výběru v `<select>`, bez nutnosti klikat na tlačítko.

**Bez JS:** Formulář funguje — záloha přes `<noscript>` tlačítko je implementována ve všech filtrech.

**Lze nahradit?** Ne bez kompromisu — jedinou čistou HTML alternativou je viditelné submit tlačítko, což je horší UX.

**Závěr: ✅ Ponechat.** Jde o 1 řádek inline JS na element. Je odůvodnitelné, záloha bez JS funguje.

---

### 2. Lightbox v galerii — `single-galerie.php` (řádky 76–130)

**Co dělá:** Po kliknutí na fotku zobrazí překryvné okno (overlay) s plnou verzí fotky. Podporuje navigaci šipkami (← →) i klávesnicí (Escape, ArrowLeft, ArrowRight).

```js
triggers.forEach(function(el) {
    el.addEventListener('click', function() {
        showImage(parseInt(this.getAttribute('data-index'), 10));
    });
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') overlay.classList.remove('active');
    if (e.key === 'ArrowLeft') showImage(currentIndex - 1);
    if (e.key === 'ArrowRight') showImage(currentIndex + 1);
});
```

**Bez JS:** Fotky by se otevíraly jako samostatné stránky (odkaz na plnou URL) nebo lightbox by vůbec nefungoval.

**Lze nahradit?**
- **CSS `:target`** — technicky možné, ale každá fotka potřebuje vlastní `#id` v URL, navigace šipkami nefunguje
- **HTML `<dialog>`** — modernější přístup, stále potřebuje JS pro `showModal()` a navigaci
- **Čisté CSS** — není reálná alternativa pro navigaci mezi fotkami

**Závěr: ✅ Ponechat.** Lightbox je standardní interaktivní komponenta, která bez JS nemá rovnocennou náhradu. Kód je čistý (~55 řádků), bez závislostí, plně obhajitelný.

---

### 3. Přidávání střelců — `single-zapas.php` (řádky 234–245)

**Co dělá:** V admin formuláři pro úpravu zápasu — tlačítka u jmen hráčů přidají hráče do pole "Střelci" kliknutím.

```js
document.querySelectorAll('.slavoj-add-scorer').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var input = document.getElementById('strelci_inline');
        input.value = input.value ? input.value + ', ' + name : name;
    });
});
```

**Bez JS:** Uživatel zadá jméno střelce ručně do textového pole. Formulář funguje bez omezení.

**Lze nahradit?** Ano — funkce je čistě pohodlnostní. Bez ní je UX horší ale formulář plně funkční.

**Závěr: ✅ Ponechat.** 12 řádků čistého JS pro pohodlnost admina. Plně obhajitelné, formulář funguje i bez JS.

---

### 4. Bootstrap JS bundle (CDN)

**Soubor:** `functions.php` (enqueue)

```php
wp_enqueue_script('bootstrap-js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    array(), '5.3.3', true
);
```

**Co Bootstrap JS zajišťuje v projektu:**
- Mobilní navigační menu (hamburger → collapse)
- Dropdown menu v navigaci
- Dismiss pro `.alert` a `.notice` komponenty

**Lze nahradit?** Mobilní menu a dropdown menu by bez JS nefungovaly. Čistě CSS alternativa pro hamburger menu je složitá a hůře obhajitelná.

**Závěr: ✅ Ponechat.** Bootstrap JS je nutný pro mobilní navigaci. Je načítán z CDN, žádný vlastní kód.

---

## Celkové hodnocení

| Použití | Řádků JS | Nezbytný? | Záloha bez JS |
|---------|----------|-----------|---------------|
| Filtry `onchange` | ~1 na filtr | Ne | ✅ `<noscript>` tlačítko |
| Lightbox galerie | ~55 | Prakticky ano | Fotky jako odkazy |
| Přidávání střelců | ~12 | Ne | Ruční zadání textu |
| Bootstrap JS | externí | Ano (nav) | Bez mobilní navigace |

**Celkem vlastního JS: ~70 řádků** ve 2 souborech + inline atributy `onchange`.

Projekt splňuje požadavek na minimální JavaScript. Každé použití je odůvodnitelné a kde je to možné, existuje funkční záloha bez JS.

---

*Vytvořeno: březen 2026*
