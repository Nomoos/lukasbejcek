# 13 – Typy obsahu v projektu

## Přehled

WordPress rozlišuje několik způsobů jak ukládat a zobrazovat obsah. V tomto projektu používáme všechny tři vrstvy: **nativní WordPress typy**, **Custom Post Types (CPT)** a **taxonomie**.

---

## 1. Nativní WordPress typy

Tyto typy jsou součástí každé WordPress instalace — není třeba je registrovat.

| Typ | Slug | URL vzor | Použití v projektu |
|-----|------|----------|--------------------|
| **Příspěvek** | `post` | `/aktuality/{slug}/` | Novinky a zprávy klubu |
| **Stránka** | `page` | `/{slug}/` | Sponzoři, Historie, Galerie (přehled), Kontakt |

**Klíčový rozdíl:**
- **Příspěvky** mají datum, kategorie a jsou chronologicky řazené → vhodné pro aktuality
- **Stránky** jsou hierarchické a statické → vhodné pro obsah který se nemění

---

## 2. Custom Post Types (CPT)

CPT jsou vlastní typy obsahu registrované v `functions.php` přes `register_post_type()`. Umožňují ukládat strukturovaná data (zápasy, hráče, galerie) odděleně od příspěvků a stránek.

### Přehled CPT

| CPT slug | Název | Archiv URL | Detail URL | Import připraven |
|----------|-------|------------|------------|-----------------|
| `zapas` | Zápasy | `/zapasy/` | `/zapasy/{slug}/` | ✅ (Muži A + Muži B) |
| `tym` | Týmy | `/tymy/` | `/tymy/{slug}/` | — |
| `hrac` | Hráči | ne | `/hraci/{slug}/` | ✅ (Muži A) |
| `galerie` | Galerie | ne | `/galerie/{slug}/` | — |
| `sponzor` | Sponzoři | ne | — | — |
| `kontakt` | Kontakty | ne | — | — |

> **`has_archive`**: pokud je `true`, WordPress automaticky vytvoří archivní URL a šablonu `archive-{cpt}.php`.

### Pole (meta data) jednotlivých CPT

#### Zápas (`zapas`)

| Pole | Klíč | Typ | Příklad |
|------|------|-----|---------|
| Datum | `datum_zapasu` | date | `2025-09-14` |
| Domácí tým | `domaci_tym` | text | `TJ Slavoj Mýto` |
| Hostující tým | `hoste_tym` | text | `FC Písek B` |
| Skóre domácí | `skore_domaci` | number | `3` |
| Skóre hosté | `skore_hoste` | number | `1` |
| Střelci | `strelci` | text | `Novák, Kovář` |
| Místo | `misto` | text | `Mýto` |

#### Hráč (`hrac`)

| Pole | Klíč | Typ | Příklad |
|------|------|-----|---------|
| Číslo dresu | `cislo_dresu` | number | `10` |
| Věk / datum nar. | `datum_narozeni` | date | `2000-05-12` |
| Fotografie | `fotografie` | image | (ID přílohy) |

#### Tým (`tym`)

| Pole | Klíč | Typ | Příklad |
|------|------|-----|---------|
| Trenér | `trener` | text | `Jan Novák` |
| Popis | `popis` | textarea | — |

---

## 3. Taxonomie

Taxonomie slouží k **třídění obsahu** (podobně jako štítky nebo kategorie). V projektu jsou registrované přes `register_taxonomy()` v `functions.php`.

| Taxonomie | Slug | Přiřazena k | Příklady hodnot | Hierarchická |
|-----------|------|-------------|-----------------|--------------|
| **Sezóna** | `sezona` | zapas, tym | `2024-2025`, `2023-2024` | ne |
| **Kategorie týmu** | `kategorie-tymu` | zapas, tym, hrac | `muzi-a`, `muzi-b`, `dorost` | ne |
| **Stav zápasu** | `stav-zapasu` | zapas | `odehran`, `nadchazejici` | ne |
| **Pozice hráče** | `pozice-hrace` | hrac | `brankař`, `obránce`, `záložník`, `útočník` | ne |

### Kanonické pořadí kategorií týmů

```
Muži A → Muži B → Dorost → Starší žáci → Mladší žáci →
Starší přípravka → Mladší přípravka → Minipřípravka
```

Pořadí definuje helper funkce `slavoj_kategorie_poradi()` v `functions.php`.

---

## 4. Celkový přehled

| Vrstva | Počet | Příklady |
|--------|-------|---------|
| Nativní WP (post, page) | 2 | Aktuality, Sponzoři, Historie |
| Custom Post Types | 6 | Zápas, Tým, Hráč, Galerie, Sponzor, Kontakt |
| Taxonomie | 4 | Sezóna, Kategorie týmu, Stav zápasu, Pozice hráče |
| **Celkem** | **12** | |

---

## 5. Vztahy mezi typy obsahu

```
sezona ──────────────────────────┐
                                 ↓
kategorie-tymu ──→  [tym]  ←──── [hrac]  ←── pozice-hrace
                     ↑
                    ─┴─────────────────────────────
                                 ↓
kategorie-tymu ──→  [zapas]  ←── stav-zapasu
sezona ──────────────────────────┘
```

**Příklad dotazu:** „Všechny odehrané zápasy Mužů A v sezóně 2024-2025"
→ CPT `zapas`, taxonomie `kategorie-tymu=muzi-a`, `sezona=2024-2025`, `stav-zapasu=odehran`

---

## 6. Registrace v kódu

Všechny CPT a taxonomie jsou registrované v jednom souboru:

```
web/wp-content/themes/tj-slavoj-myto/functions.php
```

Pomocný plugin pro vlastní pole:

```
web/wp-content/plugins/slavoj-custom-fields/slavoj-custom-fields.php
```

---

*Vytvořeno: březen 2026*
