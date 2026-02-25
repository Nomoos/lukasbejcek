# 09 – Ukázková data pro testování

Tento dokument obsahuje konkrétní ukázková data extrahovaná z původního kódu (`original/`),
která lze použít pro testování webu a demonstraci funkčnosti v WordPress.

> **Viz také:** [08 – Datový model](./08-datovy-model.md) – popis polí a datových typů;
> [06 – Inventář obsahu](./06-inventar-obsahu.md) – přehled CPT a taxonomií.

---

## Zdroj dat

Ukázková data byla extrahována ze souborů:

| Soubor | Typ obsahu |
|--------|-----------|
| `original/html/zapasy.html` | Zápasy (statické HTML) |
| `original/page-zapasy.php` | Zápasy (WordPress PHP šablona) |
| `original/html/tymy.html` | Týmy a hráči (statické HTML) |
| `original/html/kontakty.html` | Výbor klubu (statické HTML) |
| `original/html/galerie.html` | Galerie – názvy alb (statické HTML) |
| `original/html/index.html` | Homepage – aktuality a nadcházející zápasy |
| `original/html/historie.html` | Historie klubu |

---

## Ukázková data: Zápasy

### Muži A – Sezóna 2025/26

| Titulek | Domácí | Hosté | Datum | Čas | Skóre | Střelci | Stav | Kategorie |
|---------|--------|-------|-------|-----|-------|---------|------|-----------|
| TJ Slavoj Mýto vs Rapid Plzeň | Rapid Plzeň | TJ Slavoj Mýto | 2025-08-08 | 18:00 | 4:2 | 2× Schmid, Bejček, Otec | odehrany | muzi-a |
| TJ Slavoj Mýto vs TJ Chotěšov | TJ Slavoj Mýto | TJ Chotěšov | 2025-08-15 | 18:00 | *(prázdné)* | *(prázdné)* | nadchazejici | muzi-a |
| TJ Slavoj Mýto vs FK Bohemia Kaznějov | FK Bohemia Kaznějov | TJ Slavoj Mýto | 2025-08-08 | 18:00 | *(prázdné)* | *(prázdné)* | nadchazejici | muzi-a |

### Ostatní týmy – nadcházející zápasy (z homepage)

| Titulek | Domácí | Hosté | Datum | Čas | Stav | Kategorie |
|---------|--------|-------|-------|-----|------|-----------|
| Rapid Plzeň vs TJ Slavoj Mýto | Rapid Plzeň | TJ Slavoj Mýto | 2025-08-08 | 18:00 | nadchazejici | muzi-b |
| TJ Slavoj Mýto vs FK Bohemia Kaznějov | TJ Slavoj Mýto | FK Bohemia Kaznějov | 2025-08-08 | 18:00 | nadchazejici | dorost |
| Rapid Plzeň vs TJ Slavoj Mýto | Rapid Plzeň | TJ Slavoj Mýto | 2025-08-08 | 18:00 | nadchazejici | starsi-zaci |

---

## Ukázková data: Týmy

### Muži A – Sezóna 2024/25

| Pole | Hodnota |
|------|---------|
| Název | Muži A |
| tym_slug | `muzi-a` |
| Sezóna | 2024/25 |
| Počet hráčů | 16 |
| Hlavní trenér | Nyklas Petr |
| Asistent trenéra | Honzík Ivan |
| Zdravotník | Hrabák Jan |
| Kategorie | muzi-a |

---

## Ukázková data: Hráči

### Soupiska – Muži A, Sezóna 2024/25

| Jméno | Číslo dresu | Rok narození | Pozice | tym_slug |
|-------|------------|--------------|--------|---------|
| Josef Brankář | 1 | 1987 | brankari | muzi-a |
| Pavel Brankář | 2 | 1987 | brankari | muzi-a |
| Adam Bejček | 4 | 1989 | hraci-v-poli | muzi-a |
| Jan Novák | 5 | 1990 | hraci-v-poli | muzi-a |
| Martin Procházka | 6 | 2000 | hraci-v-poli | muzi-a |
| Tomáš Horáček | 7 | 2005 | hraci-v-poli | muzi-a |
| Jakub Kříž | 8 | 1999 | hraci-v-poli | muzi-a |

> **Poznámka:** Jména brankářů a část hráčů jsou v originálu uvedena jako placeholder „Josef Jan".
> Pro testovací účely jsou nahrazena smysluplnými jmény. Jméno „Adam Bejček" a výsledek zápasu
> (4:2, střelci: 2× Schmid, Bejček, Otec) jsou přímo z originálního kódu.

---

## Ukázková data: Kontakty (Výbor klubu)

| Jméno | Funkce / Pozice | Telefon | E-mail | Pořadí |
|-------|-----------------|---------|--------|--------|
| Radek Koula | Prezident klubu | +420 602 224 684 | tjslavojmyto@seznam.cz | 1 |
| Milan Huml | Sekretář klubu a administrativa | +420 737 259 684 | tjslavojmyto@seznam.cz | 2 |
| František Končel | Pokladník | *(bez telefonu)* | tjslavojmyto@seznam.cz | 3 |
| Petr Bejček | Člen výboru, trenér mládeže | +420 776 137 057 | tjslavojmyto@seznam.cz | 4 |

---

## Ukázková data: Galerie

| Název alba | Kategorie | Sezóna |
|------------|-----------|--------|
| Mýto vs Lhota | muzi-a | 2024-25 |
| Horní Bříza vs Mýto | muzi-a | 2024-25 |
| Turnaj mladších žáků – Rokycany | mladsi-zaci | 2024-25 |

---

## Ukázková data: Aktuality (příspěvky WordPress)

| Titulek | Perex |
|---------|-------|
| Postup do 7. ligy | V nejvyšší okresní soutěži na Rokycansku se celé jaro odehrával souboj o první místo. To nakonec uhájila Raková před rezervou Mýta, čímž si tak zajistila postup. Smutnit ale nakonec nemusel ani Slavoj, který původně měl mít černého Petra. Postupové čachry znamenaly i pro něj návrat zpět do krajské soutěže. |
| Starší žáci Mýta udrželi třikrát čisté konto | V sobotu 22.3.2025 na umělém trávníku mezi rokycanskými bytovkami sehráli turnaj mladších žáků okresního fotbalového svazu. O jejich svěřence se staral šéftrenér OTM Michal Šilhánek. |

---

## Jak importovat ukázková data do WordPress

### Možnost A – Automatický seed přes administraci (doporučeno)

Plugin `slavoj-custom-fields` obsahuje tlačítko **„Vytvořit ukázková data"** dostupné přes
**Nástroje → Slavoj nastavení**. Kliknutím se automaticky vytvoří všechna testovací data
popsaná v tomto dokumentu.

> ⚠️ Seed lze spustit pouze jednou – při opakovaném spuštění se duplicity přeskočí
> (kontrola podle titulku příspěvku).

### Možnost B – Ruční zadání přes administraci

1. Přihlaste se do administrace WordPress (`/wp-admin`).
2. Přejděte do **Nástroje → Slavoj nastavení** a klikněte **„Vytvořit výchozí hodnoty taxonomií"**.
3. Zadejte data ručně:
   - **Zápasy → Přidat zápas** – vyplňte meta pole a přiřaďte taxonomie.
   - **Týmy → Přidat tým** – vyplňte informace o týmu.
   - **Hráči → Přidat hráče** – zadejte číslo dresu, rok narození a `tym_slug`.
   - **Kontakty → Přidat kontakt** – vyplňte jméno, funkci a kontaktní údaje.

### Možnost C – WP-CLI (pro pokročilé / CI prostředí)

```bash
# Spustit seed taxonomií
wp eval 'slavoj_cf_seed_taxonomies();'

# Spustit seed ukázkových dat
wp eval 'slavoj_cf_seed_demo_data();'
```

---

## Poznámky

- Telefonní čísla a e-maily jsou přebírány přímo z originálu (veřejně dostupná data).
- Fotografie hráčů, log sponzorů a galerie nejsou v originálním kódu přítomny jako soubory
  (pouze prázdné nebo placeholder elementy) – pro testování je třeba nahrát obrázky ručně.
- Historický text klubu (od roku 1909) z `original/html/historie.html` je připraven pro použití
  na stránce **Historie** – lze jej vložit přímo do obsahu WordPress stránky „Historie".

---

*Vytvořeno: únor 2026 – Fáze 1 (krok 1.2 – analýza historických dat)*
