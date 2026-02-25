# 08 – Datový model

Tento dokument popisuje formální datový model webu TJ Slavoj Mýto – entity, jejich atributy,
datové typy a vzájemné vztahy. Slouží jako referenční podklad pro implementaci CPT a meta polí
a pro přípravu testovacích dat.

> **Viz také:** [06 – Inventář typů obsahu](./06-inventar-obsahu.md) – podrobný seznam meta klíčů
> a taxonomií; [09 – Ukázková data](./09-ukazková-data.md) – konkrétní testovací záznamy.

---

## Entity – přehled

| Entita | WordPress CPT slug | Popis |
|--------|-------------------|-------|
| Zápas | `zapas` | Fotbalový zápas s výsledkem a detaily |
| Tým | `tym` | Tým klubu (soupiska, trenéři) |
| Hráč | `hrac` | Hráčský profil vázaný na tým |
| Galerie | `galerie` | Fotoalbum z akce nebo zápasu |
| Kontakt | `kontakt` | Člen výboru / kontaktní osoba |
| Sponzor | `sponzor` | Partner nebo sponzor klubu |

---

## ER diagram

```
┌────────────────────────────────┐
│            SEZÓNA              │  taxonomie: sezona
│  slug: 2024-25 / 2025-26 …    │
└──────────┬─────────────────────┘
           │ m:n (term relationship)
     ┌─────┼──────┬─────────────────┐
     │     │      │                 │
     ▼     ▼      ▼                 ▼
┌────────┐ ┌────────┐ ┌──────────┐ ┌─────────┐
│ ZÁPAS  │ │  TÝM   │ │  HRÁČ    │ │ GALERIE │
└───┬────┘ └───┬────┘ └────┬─────┘ └─────────┘
    │          │           │
    │          │  1:n      │ n:1 (tym_slug)
    │          └───────────┘
    │
    ▼ m:n
┌────────────────────────────────┐
│         KATEGORIE TÝMU         │  taxonomie: kategorie-tymu
│  muzi-a / muzi-b / dorost …   │
└────────────────────────────────┘

┌────────────────────────────────┐
│          STAV ZÁPASU           │  taxonomie: stav-zapasu
│  nadchazejici / odehrany …    │
└────────────────────────────────┘

┌────────────────────────────────┐
│         POZICE HRÁČE           │  taxonomie: pozice-hrace
│  brankari / hraci-v-poli      │
└────────────────────────────────┘

┌─────────┐   ┌──────────┐
│ KONTAKT │   │ SPONZOR  │
└─────────┘   └──────────┘
(samostatné entity bez taxonomií)
```

---

## Entita: Zápas (`zapas`)

| Atribut | Meta klíč / zdroj | Datový typ | Povinný | Příklad hodnoty |
|---------|-------------------|------------|---------|-----------------|
| Název (titulek) | `post_title` | string | ✅ | „TJ Slavoj Mýto vs Rapid Plzeň" |
| Domácí tým | `domaci` | string | ✅ | „TJ Slavoj Mýto" |
| Hostující tým | `hoste` | string | ✅ | „Rapid Plzeň" |
| Datum zápasu | `datum_zapasu` | date `Y-m-d` | ✅ | `2025-08-08` |
| Čas výkopu | `cas_zapasu` | time `HH:MM` | ✅ | `18:00` |
| Skóre | `skore` | string | ❌ | `4:2`; prázdné = nadcházející |
| Střelci | `strelci` | string | ❌ | „2× Schmid, Bejček, Otec" |
| Místo konání | `misto_konani` | string | ❌ | „Mýto – domácí" |
| Kategorie týmu | taxonomie `kategorie-tymu` | term slug | ✅ | `muzi-a` |
| Sezóna | taxonomie `sezona` | term slug | ✅ | `2025-26` |
| Stav zápasu | taxonomie `stav-zapasu` | term slug | ✅ | `odehrany` |

### Pravidla

- Pokud `skore` je prázdné, stav zápasu se nastavuje na `nadchazejici`.
- Slug zápasu se generuje automaticky z titulku a data.

---

## Entita: Tým (`tym`)

| Atribut | Meta klíč / zdroj | Datový typ | Povinný | Příklad hodnoty |
|---------|-------------------|------------|---------|-----------------|
| Název | `post_title` | string | ✅ | „Muži A" |
| Slug týmu | `tym_slug` | string | ✅ | `muzi-a` |
| Popis | `post_content` | HTML/editor | ❌ | Volný popis týmu |
| Logo | `_thumbnail_id` | image ID | ❌ | ID přílohy |
| Počet hráčů | `pocet_hracu` | integer | ❌ | `16` |
| Hlavní trenér | `hlavni_trener` | string | ❌ | „Nyklas Petr" |
| Asistent trenéra | `asistent_trenera` | string | ❌ | „Honzík Ivan" |
| Zdravotník | `zdravotnik` | string | ❌ | „Hrabák Jan" |
| Kategorie | taxonomie `kategorie-tymu` | term slug | ✅ | `muzi-a` |
| Sezóna | taxonomie `sezona` | term slug | ✅ | `2024-25` |

### Pravidla

- `tym_slug` musí být jedinečný – slouží jako cizí klíč pro propojení s hráči.
- Hodnota `tym_slug` **musí** odpovídat slugu příslušné taxonomie `kategorie-tymu`.

---

## Entita: Hráč (`hrac`)

| Atribut | Meta klíč / zdroj | Datový typ | Povinný | Příklad hodnoty |
|---------|-------------------|------------|---------|-----------------|
| Celé jméno | `post_title` | string | ✅ | „Adam Bejček" |
| Číslo dresu | `cislo` | integer 1–99 | ✅ | `4` |
| Rok narození | `rok_narozeni` | integer (rok) | ❌ | `1989` |
| Slug týmu | `tym_slug` | string | ✅ | `muzi-a` |
| Pozice | taxonomie `pozice-hrace` | term slug | ✅ | `hraci-v-poli` |
| Kategorie | taxonomie `kategorie-tymu` | term slug | ✅ | `muzi-a` |
| Sezóna | taxonomie `sezona` | term slug | ✅ | `2024-25` |

### Pravidla

- `tym_slug` musí odpovídat hodnotě `tym_slug` existujícího záznamu CPT `tym`.
- Hráči jsou řazeni v soupisce podle `cislo` (vzestupně).
- Hráči nemají vlastní archivní stránku; zobrazují se výhradně v soupisce svého týmu.

---

## Entita: Galerie (`galerie`)

| Atribut | Meta klíč / zdroj | Datový typ | Povinný | Příklad hodnoty |
|---------|-------------------|------------|---------|-----------------|
| Název alba | `post_title` | string | ✅ | „Mýto vs Lhota" |
| Náhled | `_thumbnail_id` | image ID | ❌ | ID přílohy |
| Tým (text) | `tym` | string | ❌ | „Muži A" |
| Fotografie | `post_content` | gallery block | ❌ | WP Gallery block |
| Kategorie | taxonomie `kategorie-tymu` | term slug | ❌ | `muzi-a` |
| Sezóna | taxonomie `sezona` | term slug | ❌ | `2024-25` |

---

## Entita: Kontakt (`kontakt`)

| Atribut | Meta klíč / zdroj | Datový typ | Povinný | Příklad hodnoty |
|---------|-------------------|------------|---------|-----------------|
| Celé jméno | `post_title` | string | ✅ | „Radek Koula" |
| Fotografie | `_thumbnail_id` | image ID | ❌ | ID přílohy |
| Funkce / Pozice | `pozice` | string | ✅ | „Prezident klubu" |
| Telefon | `telefon` | string | ❌ | `+420 602 224 684` |
| E-mail | `email` | string | ❌ | `tjslavojmyto@seznam.cz` |
| Pořadí zobrazení | `poradi` | integer | ❌ | `1` (nižší = výše) |

---

## Entita: Sponzor (`sponzor`)

| Atribut | Meta klíč / zdroj | Datový typ | Povinný | Příklad hodnoty |
|---------|-------------------|------------|---------|-----------------|
| Název | `post_title` | string | ✅ | „Pivovar Gambrinus" |
| Logo | `_thumbnail_id` | image ID | ❌ | ID přílohy |
| Web sponzora | `web_sponzora` | URL | ❌ | `https://www.gambrinus.cz` |

---

## Taxonomie

### `sezona` – Sezóna

| Slug | Název | Popis |
|------|-------|-------|
| `2025-26` | 2025/26 | Aktuální sezóna |
| `2024-25` | 2024/25 | Předchozí sezóna |
| `2023-24` | 2023/24 | Archivní sezóna |

### `kategorie-tymu` – Kategorie týmu

| Slug | Název | Popis |
|------|-------|-------|
| `muzi-a` | Muži A | Mužský A-tým |
| `muzi-b` | Muži B | Mužský B-tým |
| `dorost` | Dorost | Dorostenecký tým |
| `starsi-zaci` | Starší žáci | Tým starších žáků |
| `mladsi-zaci` | Mladší žáci | Tým mladších žáků |
| `pripravka` | Přípravka | Nejmenší kategorie |

### `stav-zapasu` – Stav zápasu

| Slug | Název | Popis |
|------|-------|-------|
| `nadchazejici` | Nadcházející | Zápas ještě nebyl odehrán |
| `odehrany` | Odehraný | Zápas byl odehrán, skóre zadáno |
| `zruseny` | Zrušený | Zápas byl zrušen |

### `pozice-hrace` – Pozice hráče

| Slug | Název | Popis |
|------|-------|-------|
| `brankari` | Brankáři | Hráči na pozici brankáře |
| `hraci-v-poli` | Hráči v poli | Ostatní hráči |

---

## Vztahy mezi entitami

```
Tým (tym)
  └── 1:n → Hráč (hrac)           [ přes meta pole tym_slug ]
  └── n:m → Sezóna (sezona)       [ taxonomie ]
  └── n:m → Kategorie (kat.-tymu) [ taxonomie ]

Zápas (zapas)
  └── n:m → Sezóna (sezona)       [ taxonomie ]
  └── n:m → Kategorie (kat.-tymu) [ taxonomie ]
  └── n:m → Stav zápasu           [ taxonomie ]

Hráč (hrac)
  └── n:1 → Tým (tym)             [ přes meta pole tym_slug ]
  └── n:m → Sezóna (sezona)       [ taxonomie ]
  └── n:m → Kategorie (kat.-tymu) [ taxonomie ]
  └── n:m → Pozice hráče          [ taxonomie ]

Galerie (galerie)
  └── n:m → Sezóna (sezona)       [ taxonomie ]
  └── n:m → Kategorie (kat.-tymu) [ taxonomie ]
```

---

## Poznámky k implementaci

- Všechny CPT a taxonomie jsou registrovány v `web/theme/tj-slavoj-myto/functions.php`.
- Meta boxy pro zadávání polí jsou registrovány tamtéž (sekce `META BOXY`).
- Plugin `slavoj-custom-fields` rozšiřuje administrační prostředí (sloupce v seznamech, seed taxonomií, seed demo dat).
- Pro import ukázkových dat viz **[09 – Ukázková data](./09-ukazková-data.md)**.

---

*Vytvořeno: únor 2026 – Fáze 1 (krok 1.1 – datový model)*
