# 06 – Inventář typů obsahu (Content Types)

Tento dokument je výstupem **Fáze 1 – Analýza a příprava** (krok 1.1).  
Obsahuje seznam všech identifikovaných typů obsahu, jejich pole a prostor pro doplnění uživatelem.

---

## Identifikované typy obsahu

### 1. Zápasy (`zapas`)

| Pole | Typ | Poznámka |
|------|-----|----------|
| Domácí tým | text | název domácího týmu |
| Hostující tým | text | název hostujícího týmu |
| Skóre domácí | číslo | prázdné u nadcházejících zápasů |
| Skóre hosté | číslo | prázdné u nadcházejících zápasů |
| Datum a čas | datetime | datum a čas výkopu |
| Místo konání | text | hřiště / adresa |
| Střelci | textarea | volný text, např. „2× Schmid, Bejček" |
| Tým (kategorie) | taxonomie | Muži A, Muži B, Dorost, Žáci… |
| Sezóna | taxonomie | 2024/25, 2025/26… |
| Stav zápasu | taxonomie | Nadcházející / Odehraný / Zrušený |

**Doplnění od uživatele:**

| Pole | Typ | Poznámka |
|------|-----|----------|
| | | |
| | | |
| | | |

---

### 2. Týmy (`tym`)

| Pole | Typ | Poznámka |
|------|-----|----------|
| Název týmu | title | automaticky jako název příspěvku |
| Kategorie | taxonomie | Muži A, Muži B, Dorost, Starší žáci, Mladší žáci, Přípravka |
| Sezóna | taxonomie | 2024/25, 2025/26… |
| Počet hráčů | číslo | |
| Hlavní trenér | text | |
| Asistent trenéra | text | |
| Zdravotník | text | |
| Logo týmu | obrázek | |
| Popis | editor | |

**Doplnění od uživatele:**

| Pole | Typ | Poznámka |
|------|-----|----------|
| | | |
| | | |
| | | |

---

### 3. Hráči (`hrac`)

| Pole | Typ | Poznámka |
|------|-----|----------|
| Jméno | title | automaticky jako název příspěvku |
| Číslo dresu | číslo | |
| Rok narození | číslo | |
| Pozice | taxonomie | Brankář, Obránce, Záložník, Útočník |
| Tým | vztah (CPT) | vazba na příspěvek typu Tým |
| Fotografie | obrázek | |
| Kontakt | text | volitelný kontaktní údaj |

**Doplnění od uživatele:**

| Pole | Typ | Poznámka |
|------|-----|----------|
| | | |
| | | |
| | | |

---

### 4. Galerie (`galerie`)

| Pole | Typ | Poznámka |
|------|-----|----------|
| Název události | title | automaticky jako název příspěvku |
| Datum události | datum | |
| Popis | editor | |
| Tým | taxonomie | |
| Sezóna | taxonomie | |
| Fotografie | galerie | ACF Gallery nebo WordPress media galerie |

**Doplnění od uživatele:**

| Pole | Typ | Poznámka |
|------|-----|----------|
| | | |
| | | |
| | | |

---

### 5. Kontakty (`kontakt`)

| Pole | Typ | Poznámka |
|------|-----|----------|
| Jméno | title | automaticky jako název příspěvku |
| Funkce / Role | text | např. „Předseda klubu" |
| Telefon | text | |
| E-mail | email | |
| Fotografie | obrázek | |
| Pořadí | číslo | pro řazení kontaktů na stránce |

**Doplnění od uživatele:**

| Pole | Typ | Poznámka |
|------|-----|----------|
| | | |
| | | |
| | | |

---

### 6. Sponzoři (`sponzor`)

| Pole | Typ | Poznámka |
|------|-----|----------|
| Název | title | automaticky jako název příspěvku |
| Logo | obrázek | |
| URL webových stránek | url | odkaz na web sponzora |
| Popis | editor | krátký text o spolupráci |
| Typ sponzorství | taxonomie | Hlavní partner, Partner, Podpora |
| Pořadí | číslo | pro řazení na stránce sponzorů |

**Doplnění od uživatele:**

| Pole | Typ | Poznámka |
|------|-----|----------|
| | | |
| | | |
| | | |

---

## Další typy obsahu – prostor pro doplnění

Pokud web bude potřebovat další typy obsahu, doplňte je sem:

### 7. _(název – doplní uživatel)_

| Pole | Typ | Poznámka |
|------|-----|----------|
| | | |
| | | |
| | | |
| | | |
| | | |

---

### 8. _(název – doplní uživatel)_

| Pole | Typ | Poznámka |
|------|-----|----------|
| | | |
| | | |
| | | |
| | | |
| | | |

---

## Taxonomie (sdílené kategorie)

| Taxonomie | Slug | Hodnoty | Používá se v |
|-----------|------|---------|--------------|
| Kategorie týmu | `kategorie-tymu` | Muži A, Muži B, Dorost, Starší žáci, Mladší žáci, Přípravka | Zápasy, Týmy, Hráči, Galerie |
| Sezóna | `sezona` | 2024/25, 2025/26, … | Zápasy, Týmy, Galerie |
| Pozice hráče | `pozice-hrace` | Brankář, Obránce, Záložník, Útočník | Hráči |
| Stav zápasu | `stav-zapasu` | Nadcházející, Odehraný, Zrušený | Zápasy |
| Typ sponzorství | `typ-sponzorství` | Hlavní partner, Partner, Podpora | Sponzoři |

**Doplnění od uživatele:**

| Taxonomie | Slug | Hodnoty | Používá se v |
|-----------|------|---------|--------------|
| | | | |
| | | | |

---

*Vytvořeno: únor 2026 – Fáze 1 (krok 1.1)*
