# TJ Slavoj Mýto - Dokumentace projektu

Tento repository obsahuje dokumentaci a návrh pro web fotbalového klubu TJ Slavoj Mýto.

## 📁 Struktura projektu

```
lukasbejcek/
├── original/              # Složka s původním WordPress tématem (referenční, neměnit)
│   ├── *.php             # WordPress šablonové soubory
│   ├── style.css         # CSS styly tématu
│   ├── function.php      # WordPress funkce
│   └── html/             # Původní statické HTML soubory (reference)
├── wordpress/             # WordPress core soubory
├── web/                   # 🆕 Nový web – portované WordPress téma
│   ├── theme/            # WordPress téma (PHP šablony, CSS, JS)
│   ├── plugins/          # Vlastní pluginy
│   └── assets/           # Statické soubory (obrázky, fonty)
├── docs/                  # 🆕 Průběžná dokumentace portování
│   ├── 01-uvod.md        # Úvod a přehled projektu
│   ├── 02-analyza-original.md  # Analýza původního kódu
│   ├── 03-nova-struktura.md    # Nová adresářová a datová struktura
│   ├── 04-lokalni-instalace.md # Lokální instalace (XAMPP)
│   ├── 05-deployment-ftp.md    # Deployment na hosting přes FTP
│   ├── 06-inventar-obsahu.md   # Inventář CPT a taxonomií
│   ├── 07-pluginy.md           # Přehled pluginů
│   ├── 08-datovy-model.md      # Datový model a ER diagram
│   └── 09-ukazková-data.md     # Ukázková data + jak provést seed
├── notes/                 # 🆕 Pracovní poznámky a nápady
├── ZADANI-MATURITNI-PRACE.md    # ✅ Zadání maturitní práce
├── DOKUMENTACE-KOD.md    # ✅ Vysvětlení jak funguje současný kód
└── PLAN-PORTOVANI-WORDPRESS.md  # ✅ Plán kompletní migrace do WordPress
```

## 📚 Dokumenty

### 1. [DOKUMENTACE-KOD.md](./DOKUMENTACE-KOD.md)
**Vysvětlení současného kódu ve složce `original`**

Tento dokument obsahuje:
- 📋 Detailní popis všech souborů a jejich účelu
- 🏗️ Architektura WordPress tématu
- 💾 Datový model a použití kategorií/custom fields
- ⚠️ Identifikace problémů a nedokončených částí
- 🔧 Technologie a nástroje použité v projektu

**Klíčové poznatky:**
- Téma je v raném stádiu migrace ze statických HTML stránek
- Mnoho funkcionality je hardcoded nebo nefunkční
- Použité technologie: WordPress, PHP, Bootstrap 5.3.3, WP_Query

### 2. [PLAN-PORTOVANI-WORDPRESS.md](./PLAN-PORTOVANI-WORDPRESS.md)
**Kompletní plán pro portování do plně funkčního WordPress webu**

Tento dokument obsahuje:
- 📊 Analýza současného stavu
- 🎯 10 fází implementace s detailními kroky
- 📅 Časové odhady (14-23 dnů čistého vývoje)
- 🗂️ Návrh custom post types (Zápasy, Týmy, Hráči, Galerie, Kontakty, Sponzoři)
- 🏷️ Definice taxonomií (sezóny, kategorie týmů, pozice)
- 🔌 Doporučené WordPress pluginy
- ⚡ Optimalizace, SEO a bezpečnost
- ✅ Testing a launch checklist
- 🎓 Plán školení a údržby

**Fáze implementace:**
1. Analýza a příprava (1-2 dny)
2. Datová struktura (2-3 dny)
3. Implementace šablon (3-4 dny)
4. Frontend funkcionality (2-3 dny)
5. Admin rozhraní (1-2 dny)
6. Migrace dat (1 den)
7. Optimalizace a SEO (1-2 dny)
8. Testování (2-3 dny)
9. Launch (1 den)
10. Školení a údržba (průběžné)

### 3. [docs/](./docs/)
**Průběžná dokumentace portování (krok po kroku)**

- [01-uvod.md](./docs/01-uvod.md) – Úvod a přehled projektu
- [02-analyza-original.md](./docs/02-analyza-original.md) – Analýza původního kódu
- [03-nova-struktura.md](./docs/03-nova-struktura.md) – Nová adresářová a datová struktura
- [04-lokalni-instalace.md](./docs/04-lokalni-instalace.md) – Jak rozchodit projekt lokálně
- [05-deployment-ftp.md](./docs/05-deployment-ftp.md) – Deployment na hosting přes FTP
- [06-inventar-obsahu.md](./docs/06-inventar-obsahu.md) – Inventář CPT a taxonomií
- [07-pluginy.md](./docs/07-pluginy.md) – Přehled pluginů
- [08-datovy-model.md](./docs/08-datovy-model.md) – Datový model a ER diagram
- [09-ukazková-data.md](./docs/09-ukazková-data.md) – Ukázková data + jak provést seed

## 🎯 Současný stav projektu

### ✅ Hotovo
- Základní WordPress téma struktura (header, footer, style.css)
- Některé page templates vytvořeny
- Použití WP_Query na některých stránkách
- Základní navigační menu funkční

### ❌ Potřebuje dokončit
- Většina obsahu je hardcoded v PHP souborech
- Filtry a interaktivní prvky nefunkční
- Chybí custom post types pro strukturovaný obsah
- Není admin rozhraní pro správu dat
- Responsivita není plně otestována
- SEO a optimalizace

## 🚀 Jak začít s implementací

1. **Přečtěte si dokumentaci**: Začněte s [DOKUMENTACE-KOD.md](./DOKUMENTACE-KOD.md) pro pochopení současného stavu
2. **Prostudujte plán**: Pokračujte s [PLAN-PORTOVANI-WORDPRESS.md](./PLAN-PORTOVANI-WORDPRESS.md)
3. **Nastavte vývojové prostředí**: Viz [docs/04-lokalni-instalace.md](./docs/04-lokalni-instalace.md)
4. **Naimportujte ukázková data**: Po aktivaci pluginu přejděte v administraci na **Nástroje → Slavoj nastavení** a klikněte „Vytvořit ukázková data (sezóna 2025/26)". Podrobnosti viz [docs/09-ukazková-data.md](./docs/09-ukazková-data.md)
5. **Následujte fáze**: Postupujte podle 10 fází v plánu portování
6. **Nasazení na hosting**: Viz [docs/05-deployment-ftp.md](./docs/05-deployment-ftp.md)

## 💡 Doporučení

- **Fázovaný přístup**: Neimplementovat vše najednou
- **Staging prostředí**: Vždy testovat na kopii před nasazením
- **Version control**: Používat Git pro verzování
- **Mobile-first**: Navrhovat nejprve pro mobily
- **Dokumentace**: Dokumentovat custom funkce a rozhodnutí

## 📞 Kontakt

Pro otázky k implementaci nebo další informace kontaktujte správce projektu.

---

**Poslední aktualizace**: Únor 2026  
**Verze dokumentace**: 1.0
