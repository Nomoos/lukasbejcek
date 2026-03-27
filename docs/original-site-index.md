# Original Website Index: slavojmyto.cz

> Crawled: 2026-03-27 | Base URL: http://www.slavojmyto.cz | All pages under `/cs/` prefix

## Site Overview

- **Platform**: Custom static/server-rendered site (NOT WordPress)
- **CSS Framework**: Bootstrap (older version) + custom CSS (animate.css, font-awesome, themify-icons, magnific-popup)
- **Fonts**: Google Fonts (Lora, Montserrat, Open Sans)
- **Language**: Czech only (`/cs/` prefix)
- **Footer**: "© 2026 slavojmyto.cz | Vsechna prava jsou vyhrazena."
- **Social**: Facebook page https://www.facebook.com/slavoj.myto
- **E-shop**: JAKO team shop https://team.jako.com/de-en/team/tj_slavoj_myto/
- **Logo**: http://slavojmyto.cz/assets/images/logo-tjslavoj.png

---

## Navigation Menu (exact hierarchy)

```
[Logo: TJ Slavoj Myto]
O klubu (dropdown)
  ├── Historie        → /cs/historie
  └── Vybor           → /cs/vybor
Tymy (dropdown)
  ├── Muzi A          → /cs/tymy/muzi-a
  ├── Muzi B          → /cs/tymy/muzi-b
  ├── Mladsi zaci     → /cs/tymy/mladsi-zaci
  ├── Starsi zaci     → /cs/tymy/starsi-zaci
  ├── Starsi pripravka→ /cs/tymy/starsi-pripravka
  ├── Mladsi pripravka→ /cs/tymy/mladsi-pripravka
  ├── Dorost          → /cs/tymy/dorost
  └── Minipripravka   → /cs/tymy/minipripravka
Kalendar (dropdown - seasons)
  ├── 2025/2026       → /cs/kalendar/2025-2026
  ├── 2024/2025       → /cs/kalendar/2024-2025
  ├── 2023/2024       → /cs/kalendar/2023-2024
  ├── 2022/2023       → /cs/kalendar/2022-2023
  ├── 2021/2022       → /cs/kalendar/2021-2022
  ├── 2020/2021       → /cs/kalendar/2020-2021
  └── 2019/2020       → /cs/kalendar/2019-2020
Galerie (dropdown)
  ├── Zaci            → /cs/galerie/zaci
  ├── Dorost          → /cs/galerie/dorost
  ├── Stara garda     → /cs/galerie/stara-garda
  ├── Muzi            → /cs/galerie/muzi
  ├── ML. pripravka   → /cs/galerie/ml-pripravka
  ├── St. pripravka   → /cs/galerie/st-pripravka
  ├── Minipripravka   → /cs/galerie/minipripravka
  └── Muzi - B tym    → /cs/galerie/muzi-b-tym
Sponzori              → /cs/sponzori
```

**Note**: There is NO separate "Zapasy" (matches) page, "Kontakt" page, "Aktuality/Novinky" page, or "Hledani" (search) in the navigation. Matches are shown on the homepage and under "Kalendar" (season schedules).

---

## Page Details

### 1. Homepage (`/` or `/cs/index.html`)

**Sections:**
1. **Header/Nav**: Logo + main menu (desktop + mobile hamburger)
2. **Hero Banner**: Full-width carousel/slider area with club tagline:
   - "Fotbalovy klub TJ Slavoj Myto"
   - "Trenujeme mlade fotbalove nadeje"
   - "Prijimame fotbalisty od 3 let"
   - "Tym A - Krajsky prebor | Tym B - I. B trida | Dorost - krajska soutez | Zaci - okresni prebor"
3. **Klubovy e-shop**: Link to JAKO team shop for club clothing
4. **Nejblizsi zapasy** (Upcoming matches): List of 8 upcoming matches showing:
   - Home team - **Away team** (bold = opponent)
   - Date and time
   - Team category (Muzi A, Muzi B, Starsi zaci, Dorost, Mladsi zaci)
5. **Footer**: Copyright + Facebook icon link

**NO aktuality/novinky section on homepage** (the WordPress version adds this).

---

### 2. Historie (`/cs/historie`)

**Title**: "Historie klubu"

**Content**: Long-form text article about club history from 1909 to present:
- 1909: Founded as "Sportovni klub Olympia Myto" (2nd club in district after Rokycany)
- Played at "hriste u sv. Vojtecha"
- Renamed "Sportovni klub Cesky Lev", interrupted by WWI
- Post-war: "Ruda hvezda Myto" → "SK Viktoria Myto" → "SK Olympia Myto" (1931)
- After WWII: Moved to Benatky, incorporated into Sokol, then ROH (1952)
- Named "Slavoj" in 1952 (affiliated with local brewery/maltings)
- 1950-1987: Wooden facilities rebuilt to brick
- 1990s: Improvements, youth focus
- 1994: Men promoted to 1.B class
- 2001-02: Promotion to A class
- 2004-05: Krajsky prebor (county league)
- 2009-10: Return to 1.A class
- 2017: Return to krajsky prebor
- 2019: **Historic promotion to Divize** (division)
- 2022/2023: Back to krajsky prebor, B team in I.B class

**Layout**: Simple text page, no images.

---

### 3. Vybor (`/cs/vybor`) - Club Committee / Contacts

**Title**: "Vybor klubu TJ Slavoj Myto"

**Members** (each with photo, name, role, phone, email):

| Name | Role | Phone | Email |
|------|------|-------|-------|
| Radek Koula | Prezident klubu | +420 602 224 684 | tjslavojmyto@seznam.cz |
| Milan Huml | Sekretari klubu a administrativa | +420 737 259 684 | tjslavojmyto@seznam.cz |
| Frantisek Koncel | Pokladnik | — | tjslavojmyto@seznam.cz |
| Petr Bejcek | Clen vyboru, trener mladeze | +420 776 137 057 | tjslavojmyto@seznam.cz |
| Michal Chvala | Clen vyboru | — | tjslavojmyto@seznam.cz |

**Layout**: Cards with profile photos, centered layout. All share same email.

---

### 4. Team Pages (`/cs/tymy/{slug}`)

**Title**: "Sestava muzstva"

**Structure** (same for all 8 teams): Each page shows multiple seasons in collapsible accordion:

#### Current Season (2025/2026) - expanded by default:
- **Realizacni tym** (Coaching staff): Hlavni trener, Asistent trenera, Vedouci druzstva, Zdravotnik
- **Sestava hracu** (Player roster): Numbered list with jersey number, full name, rocnik narozeni (birth year)

#### Previous seasons - collapsed:
- Same structure, going back to 2019/2020 or 2022/2023 depending on team

**Teams indexed:**

| Team | URL slug | Seasons available |
|------|----------|-------------------|
| Muzi A | muzi-a | 2025/26, 2024/25, 2023/24, 2022/23+ |
| Muzi B | muzi-b | 2025/26, 2024/25, 2023/24, 2022/23+ |
| Dorost | dorost | 2025/26, 2024/25, 2023/24, 2019/20 |
| Starsi zaci | starsi-zaci | 2025/26, 2024/25, 2023/24, 2022/23 |
| Mladsi zaci | mladsi-zaci | 2025/26, 2024/25, 2023/24, 2022/23 |
| Starsi pripravka | starsi-pripravka | 2025/26, 2024/25, 2023/24, 2022/23 |
| Mladsi pripravka | mladsi-pripravka | 2025/26, 2024/25, 2023/24, 2022/23 |
| Minipripravka | minipripravka | 2025/26, 2024/25, 2023/24, 2022/23, 2021/22, 2020/21 |

**Key data per player**: Jersey number, full name, birth year.
**Key data per staff**: Role (Hlavni trener, Asistent, Vedouci, Zdravotnik), name, sometimes birth year.

---

### 5. Kalendar / Zapasy sezony (`/cs/kalendar/{season}`)

**Title**: "Zapasy sezony"

**THIS IS THE MATCHES PAGE** - equivalent to "Zapasy" in the WordPress version.

**Structure**: One page per season. Matches grouped by team category:
- Muzi A, Muzi B, Dorost, Starsi zaci, Mladsi zaci, Starsi pripravka, Mladsi pripravka, Minipripravka

**Per match:**
- **Date and time** (DD. MM. YYYY - HH:MM)
- **Home team** - **Away team** (Slavoj team bolded or plain, opponent always bolded)
- **Score** (if played) - e.g. "2:4", "4:0"
- **Scorers** (if available) - e.g. "_Drabek, Hatina, Stych, Smid_" in italics

**Seasons available**: 2019/2020 through 2025/2026 (7 seasons)

**Sample data from 2025/2026 Muzi A:**
- 30 matches total (15 home, 15 away - full round-robin)
- First match: 08.08.2025 Rapid Plzen vs Slavoj 2:4
- Spring matches from March 2026 onwards (some without scores = upcoming)
- Scorers tracked for each match

---

### 6. Galerie (`/cs/galerie/{team-slug}`)

**Structure**: Two-level gallery system:

**Level 1**: Team gallery index (`/cs/galerie/muzi`) - shows albums grouped by year (rocnik)
- Each album = thumbnail image + team name + year
- Links to: `/cs/galerie/{slug}?rocnik={year}`

**Level 2**: Year detail (`/cs/galerie/muzi?rocnik=2025`) - shows individual match albums
- Each album = thumbnail + match opponent name + season
- Links to: `/cs/galerie/{slug}?albumID={id}`

**Level 3**: Album detail (`/cs/galerie/{slug}?albumID=151`) - individual match photos (not crawled)

**Gallery teams and their album counts:**

| Gallery | Albums by year |
|---------|---------------|
| Muzi | 2025, 2024, 2021, 2020, 2019 |
| Muzi B tym | 2025, 2024, 2023, 2021 |
| Dorost | 2025, 2024, 2023, 2022, 2021, 2019 |
| Zaci | 2025, 2024, 2022, 2021, 2020, 2018, Ostatni |
| St. pripravka | 2025, 2022, 2021, 2020, Ostatni |
| ML. pripravka | 2025, 2022, Ostatni |
| Minipripravka | 2025, 2020, 2019 |
| Stara garda | 2018, 2017, 2016, 2015 |

**Image hosting**: `/images/team/icons/` for thumbnails, likely full-size in `/images/team/`

---

### 7. Sponzori (`/cs/sponzori`)

**Title**: "Nasi sponzori - Dekujeme za Vasi podporu"

**11 sponsors**, each shown as logo + name:

| Sponsor | Has website link? | Logo format |
|---------|-------------------|-------------|
| Mesto Myto | Yes (mestomyto.cz) | PNG |
| Strabag | Yes (strabag.cz) | PNG |
| TYC | No | PNG |
| Auto pujcovna Brasy | Yes (autopujcovnabrasy.cz) | PNG |
| Bernie | No | JPG |
| Biggest | No | PNG |
| Carrier | No | PNG |
| Feli | No | JPG |
| Karas | No | PNG |
| Koloc Oil | No | PNG |
| Valcano | No | JPG |

**Layout**: Grid of sponsor cards with logos, centered.

---

## Pages NOT Present on Original Site

The following pages exist in the WordPress version but have NO equivalent on slavojmyto.cz:

1. **Aktuality / Novinky** - No news/blog section at all
2. **Kontakty** - Club committee is under "Vybor" (part of "O klubu")
3. **Dedicated Zapasy page** - Matches are under "Kalendar" (per-season)
4. **Search** - No search functionality
5. **Single match detail** - No individual match pages
6. **Single team detail with roster** - Team pages ARE present but as roster lists, not rich profiles
7. **Player profiles** - No individual player pages

---

## Comparison: Original vs WordPress Theme

| Feature | Original (slavojmyto.cz) | WordPress (tj-slavoj-myto theme) |
|---------|--------------------------|----------------------------------|
| Matches | Under "Kalendar" by season | Dedicated "Zapasy" archive with filters |
| Contacts | "Vybor" page under "O klubu" | Dedicated "Kontakty" page |
| News | None | "Aktuality" on homepage |
| Galleries | 2-level: team → year → album | CPT with taxonomy filtering |
| Teams | Roster lists with accordion | CPT with taxonomy |
| Players | Inline in team page | Separate CPT "hrac" |
| Filtering | None (separate pages per season) | GET-based filters (team, season, status) |
| Map | None visible | OpenStreetMap embed |
| E-shop link | On homepage | (to be added) |

---

## Technical Notes

- All URLs use `/cs/` prefix (language routing)
- No `/cs/zapasy` or `/zapasy` endpoint exists
- Gallery uses query params: `?rocnik=` for year filter, `?albumID=` for specific album
- Team pages use collapsible accordion for season history (Bootstrap collapse)
- No JavaScript-heavy SPA - server-rendered HTML pages
- Images stored in `/images/team/icons/`, `/images/sponsors/`, `/images/mediasource/`
- CSS stack: Bootstrap + animate.css + Font Awesome + Themify Icons + Magnific Popup + custom vertical.min.css + addstyle.css
