# 16 – Návod na použití administrace

Návod pro správce obsahu webu TJ Slavoj Mýto. Popisuje, jak přidávat a editovat obsah přes administraci WordPress.

## Přihlášení do administrace

1. Otevřete `http://localhost/fotbal_club/wp-admin/` (lokálně) nebo `https://vasedomena.cz/wp-admin/` (produkce)
2. Přihlaste se uživatelským jménem a heslem
3. V levém menu uvidíte sekce: **Zápasy**, **Týmy**, **Hráči**, **Galerie**, **Sponzoři**, **Kontakty**

---

## Prvotní nastavení (jen jednou)

Po instalaci tématu a aktivaci pluginu **Slavoj Custom Fields** proveďte:

1. Přejděte na **Nástroje → Slavoj nastavení**
2. Klikněte **„Vytvořit výchozí hodnoty taxonomií"** – vytvoří sezóny, kategorie týmů, stavy zápasů a pozice hráčů
3. Klikněte **„Vytvořit stránky webu"** – vytvoří WordPress stránky (Domů, Zápasy, Týmy, Galerie, Sponzoři, Kontakty, Historie) se správnými šablonami
4. Volitelně klikněte **„Vytvořit ukázková data"** – naplní web testovacími zápasy, hráči, kontakty a galerií

### Nastavení hlavní stránky

1. Přejděte na **Nastavení → Čtení**
2. Vyberte **„Statická stránka"**
3. Jako úvodní stránku zvolte **Domů**

### Nastavení menu

1. Přejděte na **Vzhled → Menu**
2. Vytvořte nové menu a přidejte stránky: Domů, Zápasy, Týmy, Galerie, Sponzoři, Kontakty, Historie
3. Jako umístění vyberte **Hlavní menu**

---

## Správa zápasů

### Přidat nový zápas

1. V menu klikněte **Zápasy → Přidat zápas**
2. **Titulek** – název zápasu, např. `TJ Slavoj Mýto vs SK Nepomuk`
3. Vyplňte pole v sekci **Detail zápasu**:

| Pole | Popis | Příklad |
|------|-------|---------|
| Datum zápasu | Výběr data | `2026-04-05` |
| Čas výkopu | Čas začátku | `16:30` |
| Domácí tým | Název domácího týmu | `TJ Slavoj Mýto` |
| Hostující tým | Název hostů | `SK Nepomuk` |
| Skóre | Výsledek (prázdné = neodehráno) | `3:1` |
| Střelci | Jména střelců | `2× Křivánek, Vaněček` |

4. V pravém panelu přiřaďte:
   - **Kategorie týmu** – zaškrtněte tým (Muži A, Muži B, Dorost…)
   - **Sezóna** – zaškrtněte sezónu (2025/26)
5. Klikněte **Publikovat**

### Zadat výsledek odehraného zápasu

1. Přejděte na **Zápasy → Všechny zápasy**
2. Najděte zápas (lze filtrovat podle sezóny a týmu v dropdownech nad seznamem)
3. Klikněte na název zápasu
4. Vyplňte pole **Skóre** (např. `2:1`) a **Střelci**
5. Klikněte **Aktualizovat**

### Sloupce v přehledu zápasů

V seznamu zápasů vidíte sloupce **Datum zápasu**, **Tým** a **Skóre**. Kliknutím na Datum zápasu lze seznam seřadit chronologicky.

---

## Správa týmů

### Přidat nový tým

1. **Týmy → Přidat tým**
2. **Titulek** – název týmu, např. `Muži A – sezóna 2025/26`
3. Vyplňte pole v sekci **Detail týmu**:

| Pole | Popis | Příklad |
|------|-------|---------|
| Identifikátor týmu (slug) | Musí odpovídat kategorii | `muzi-a` |
| Hlavní trenér | Jméno trenéra | `Jan Novák` |
| Asistent trenéra | Jméno asistenta | `Petr Dvořák` |
| Zdravotník | Jméno zdravotníka | `Karel Malý` |

4. **Počet hráčů** se zobrazuje automaticky – vypočítá se z hráčů přiřazených k tomuto týmu
5. V pravém panelu přiřaďte **Sezónu** a **Kategorii týmu**
6. Nastavte **Obrázek příspěvku** – týmová fotografie
7. Klikněte **Publikovat**

> **Důležité:** Slug týmu (např. `muzi-a`) propojuje tým s hráči. Musí odpovídat slugu kategorie týmu.

---

## Správa hráčů

### Přidat nového hráče

1. **Hráči → Přidat hráče**
2. **Titulek** – jméno hráče, např. `Jan Křivánek`
3. Vyplňte pole v sekci **Detail hráče**:

| Pole | Popis | Příklad |
|------|-------|---------|
| Číslo dresu | Číslo 1–99 | `10` |
| Rok narození | Rok | `1995` |
| Slug týmu | Identifikátor týmu | `muzi-a` |

4. V pravém panelu přiřaďte:
   - **Pozice hráče** – Brankáři nebo Hráči v poli
   - **Sezóna** – zaškrtněte sezónu
5. Nastavte **Obrázek příspěvku** – fotografie hráče
6. Klikněte **Publikovat**

### Filtrování hráčů v přehledu

V seznamu hráčů lze filtrovat podle **sezóny** a **týmu** pomocí dropdownů. Sloupce **Číslo dresu**, **Pozice** a **Tým** jsou řaditelné kliknutím.

---

## Správa galerií

### Přidat nové fotoalbum

1. **Galerie → Přidat album**
2. **Titulek** – název alba, např. `Slavoj Mýto vs Nepomuk – 6. 9. 2025`
3. V sekci **Detail alba** zadejte **Sezónu** (text, např. `2025/26`)
4. V pravém panelu přiřaďte **Kategorii týmu**
5. **Obrázek příspěvku** – náhledový obrázek alba
6. **Obsah příspěvku** – vložte fotografie:
   - Klikněte **Přidat média** → vyberte/nahrajte fotky → **Vytvořit galerii** → **Vložit galerii**
7. Klikněte **Publikovat**

---

## Správa sponzorů

### Přidat sponzora

1. **Sponzoři → Přidat sponzora**
2. **Titulek** – název firmy/partnera
3. V sekci **Detail sponzora** zadejte **Webové stránky** (URL, např. `https://firma.cz`)
4. **Obrázek příspěvku** – logo sponzora
5. Klikněte **Publikovat**

---

## Správa kontaktů

### Přidat kontaktní osobu

1. **Kontakty → Přidat kontakt**
2. **Titulek** – jméno osoby, např. `Jaroslav Bejček`
3. Vyplňte pole v sekci **Detail kontaktu**:

| Pole | Popis | Příklad |
|------|-------|---------|
| Funkce / Pozice | Role v klubu | `Předseda klubu` |
| Telefon | Kontaktní telefon | `+420 777 000 000` |
| E-mail | Kontaktní e-mail | `predseda@slavojmyto.cz` |
| Pořadí zobrazení | Číslo pro řazení (0 = první) | `1` |

4. Klikněte **Publikovat**

---

## Správa aktualit (příspěvky)

Aktuality využívají nativní WordPress příspěvky:

1. **Příspěvky → Přidat příspěvek**
2. Napište titulek a text aktuality
3. Nastavte **Obrázek příspěvku** (náhled na homepage)
4. Klikněte **Publikovat**

---

## Stránka Historie

Stránka Historie je klasická WordPress stránka:

1. **Stránky → Všechny stránky** → klikněte na **Historie**
2. Upravte obsah přímo v editoru – text, obrázky, časová osa
3. Klikněte **Aktualizovat**

---

## Přehled admin menu

| Menu položka | Co obsahuje | Kde se zobrazí na webu |
|---|---|---|
| Zápasy | Seznam zápasů s výsledky | `/zapasy` |
| Týmy | Týmy se soupiskami | `/tymy` |
| Hráči | Hráčské profily | Detail týmu (soupiska) |
| Galerie | Fotoalba | `/galerie` |
| Sponzoři | Partneři klubu | `/sponzori` |
| Kontakty | Výbor klubu | `/kontakty` |
| Příspěvky | Aktuality | Homepage + `/aktuality` |
| Nástroje → Slavoj nastavení | Seed dat, přehled CPT | Pouze admin |

---

## Časté dotazy

**Jak změním pořadí kontaktů?**
Upravte pole „Pořadí zobrazení" u každého kontaktu. Nižší číslo = dříve v seznamu.

**Jak přidám novou sezónu?**
Přejděte na libovolný zápas/tým → v pravém panelu v sekci Sezóna klikněte „+ Přidat novou sezónu" a zadejte název (např. `2026/27`).

**Jak přidám novou kategorii týmu?**
Stejný postup jako u sezóny – v pravém panelu klikněte „+ Přidat novou kategorii týmu".

**Proč se nezobrazuje počet hráčů u týmu?**
Zkontrolujte, že hráči mají v poli „Slug týmu" správnou hodnotu (např. `muzi-a`) a přiřazenou stejnou sezónu jako tým.

**Jak vytvořím WordPress galerii v albu?**
V editoru obsahu klikněte **Přidat média → Vytvořit galerii**, vyberte fotky a klikněte **Vložit galerii**.
