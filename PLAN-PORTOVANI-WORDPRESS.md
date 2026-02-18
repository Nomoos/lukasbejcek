# Návrh postupu portování webu do WordPress

## Executive Summary

Tento dokument navrhuje kompletní postup pro dokončení migrace webu TJ Slavoj Mýto do plně funkčního WordPress tématu. Současný stav je částečně implementované téma s mnoha hardcoded prvky a nefunkčními komponentami.

## Současný stav

- ✅ Základní WordPress téma struktura (header, footer, style.css)
- ✅ Některé page templates vytvořeny
- ✅ Použití WP_Query na některých stránkách
- ✅ Základní navigační menu funkční
- ❌ Většina obsahu je hardcoded v PHP souborech
- ❌ Filtry a interaktivní prvky nefunkční
- ❌ Chybí custom post types pro strukturovaný obsah
- ❌ Není admin rozhraní pro správu dat

## Fáze migrace

### Fáze 1: Analýza a příprava (1-2 dny)

#### 1.1 Inventarizace obsahu
- [ ] Sepsat seznam všech typů obsahu (zápasy, týmy, hráči, galerie, kontakty)
- [ ] Identifikovat všechna potřebná pole pro každý typ
- [ ] Vytvořit datový model
- [ ] Připravit ukázková data pro testování

#### 1.2 Nastavení vývojového prostředí
- [ ] Nainstalovat lokální WordPress (Local, XAMPP, nebo Docker)
- [ ] Aktivovat téma z `original` složky
- [ ] Nainstalovat potřebné pluginy (viz níže)
- [ ] Nastavit Git pro verzování změn

### Fáze 2: Datová struktura (2-3 dny)

#### 2.1 Custom Post Types

Vytvořit následující custom post types:

**1. Zápasy (Match)**
```php
Pole:
- Tým (taxonomie)
- Domácí tým (text)
- Hostující tým (text)
- Skóre domácí (číslo)
- Skóre hosté (číslo)
- Datum a čas (datetime)
- Sezóna (taxonomie)
- Střelci (text/textarea)
- Místo konání (text)
- Stav (taxonomie: nadcházející/odehraný)
```

**2. Týmy (Team)**
```php
Pole:
- Název týmu (title)
- Kategorie (taxonomie: Muži A, Muži B, Dorost, Žáci)
- Sezóna (taxonomie)
- Počet hráčů (číslo)
- Hlavní trenér (text)
- Asistent trenéra (text)
- Zdravotník (text)
- Logo týmu (obrázek)
- Popis (editor)
```

**3. Hráči (Player)**
```php
Pole:
- Jméno (title)
- Číslo dresu (číslo)
- Rok narození (číslo/datum)
- Pozice (taxonomie: brankář, obránce, záložník, útočník)
- Tým (vztah k custom post type Team)
- Fotografie (obrázek)
- Kontakt (text)
```

**4. Galerie (Gallery)**
```php
Pole:
- Název události (title)
- Datum události (datum)
- Popis (editor)
- Tým (taxonomie)
- Sezóna (taxonomie)
- Fotografie (galerie - ACF Gallery nebo WordPress galerie)
```

**5. Kontakty (Contact)**
```php
Pole:
- Jméno (title)
- Funkce (text)
- Telefon (text)
- Email (email)
- Fotografie (obrázek)
- Pořadí (číslo pro řazení)
```

**6. Sponzoři (Sponsor)**
```php
Pole:
- Název (title)
- Logo (obrázek)
- URL webových stránek (url)
- Popis (editor)
- Typ sponzorství (taxonomie: hlavní, vedlejší, partneři)
- Pořadí (číslo)
```

#### 2.2 Taxonomie (kategorie a tagy)

- **Sezóna**: 2024/25, 2025/26, atd.
- **Kategorie týmu**: Muži A, Muži B, Dorost, Starší žáci, Mladší žáci, Přípravka
- **Pozice hráče**: Brankář, Obránce, Záložník, Útočník
- **Stav zápasu**: Nadcházející, Odehraný, Zrušený
- **Typ sponzorství**: Hlavní partner, Partner, Podpora

#### 2.3 Plugin recommendations

**Nutné:**
1. **Advanced Custom Fields (ACF) PRO** - pro custom fields a flexible content
2. **Custom Post Type UI** - pro snadné vytvoření custom post types (nebo kódovat do functions.php)

**Doporučené:**
3. **WP Query Console** - pro testování queries
4. **Admin Columns** - pro lepší přehled v admin rozhraní
5. **Duplicate Post** - pro rychlé kopírování zápasů
6. **Regenerate Thumbnails** - pro optimalizaci obrázků

**Volitelné:**
7. **WPML** nebo **Polylang** - pokud plánujete vícejazyčnost
8. **WP Rocket** - pro cachování a rychlost
9. **Yoast SEO** - pro SEO optimalizaci

### Fáze 3: Implementace šablon (3-4 dny)

#### 3.1 Aktualizace function.php

```php
Přidat:
1. Registrace všech custom post types
2. Registrace taxonomií
3. Podpora featured images
4. Podpora HTML5
5. Registrace velikostí obrázků
6. Enqueue skriptů a stylů (správná cesta)
7. AJAX funkce pro filtrování
8. Helper funkce pro výpis dat
```

#### 3.2 Přepracování šablon

**index.php (Domovská stránka)**
- [ ] Banner zůstává, ale obrázek jako featured image stránky
- [ ] Karty zápasů - nahradit WP_Query na custom post type "Match"
- [ ] Filtrovat pouze nadcházející zápasy (4 nejbližší)
- [ ] Sekce aktualit - zachovat, ale optimalizovat

**page-zapasy.php**
- [ ] Implementovat funkční filtry pomocí AJAX/JavaScript
- [ ] WP_Query na custom post type "Match"
- [ ] Meta query pro filtrování podle týmu, sezóny, stavu
- [ ] Pagination pro seznam zápasů
- [ ] Zobrazit detaily z custom fields

**page-tymy.php**
- [ ] Funkční select boxy s AJAX
- [ ] WP_Query na custom post type "Team"
- [ ] Načíst souvisící hráče přes post relationships
- [ ] Zobrazit všechny údaje z custom fields
- [ ] Možnost stažení soupisek jako PDF

**page-galerie.php**
- [ ] Implementovat funkční filtry
- [ ] WP_Query na custom post type "Gallery"
- [ ] Grid layout s featured images
- [ ] Lightbox pro zobrazení obrázků
- [ ] Lazy loading obrázků

**single-galerie.php**
- [ ] Detailní stránka galerie
- [ ] Zobrazení všech fotek z galerie
- [ ] Lightbox s navigací
- [ ] Breadcrumbs zpět na přehled

**page-historie.php**
- [ ] Přesunout obsah do editoru WordPress stránky
- [ ] Zachovat formátování a obrázky
- [ ] Přidat možnost úpravy přes admin

**page-kontakty.php**
- [ ] WP_Query na custom post type "Contact"
- [ ] Zobrazit všechny údaje z custom fields (tel, email)
- [ ] Možnost kontaktního formuláře (Contact Form 7)

**page-sponzori.php (nová)**
- [ ] Vytvořit kompletní šablonu
- [ ] WP_Query na custom post type "Sponsor"
- [ ] Rozdělení podle typu sponzorství
- [ ] Zobrazení log s odkazy
- [ ] Responsivní grid

#### 3.3 Šablona pro single posts

**single-match.php** (detail zápasu)
- [ ] Kompletní informace o zápase
- [ ] Historie vzájemných zápasů
- [ ] Fotogalerie ze zápasu (pokud existuje)
- [ ] Související zápasy

**single-player.php** (profil hráče)
- [ ] Fotografie a základní údaje
- [ ] Statistiky (góly, asistence - budoucí rozšíření)
- [ ] Zápasy, kde hrál
- [ ] Historie v klubu

### Fáze 4: Frontend funkcionality (2-3 dny)

#### 4.1 Implementace filtrů

**JavaScript/AJAX komponenta:**
```javascript
Funkce:
1. Posluchač změn na select elementech
2. AJAX request na WordPress s parametry filtru
3. Aktualizace DOM s výsledky
4. Loading states a error handling
5. URL parameters pro sdílení filtrovaných výsledků
```

**PHP AJAX handler:**
```php
Funkce:
1. Přijmout AJAX request
2. Sestavit WP_Query s meta_query
3. Renderovat HTML výsledků
4. Vrátit JSON response
```

#### 4.2 JavaScript vylepšení

- [ ] Sticky menu při scrollování
- [ ] Mobile hamburger menu
- [ ] Lazy loading obrázků
- [ ] Lightbox pro galerie (např. GLightbox)
- [ ] Smooth scroll pro kotvy
- [ ] Form validace pro kontaktní formulář

#### 4.3 Responsivita

- [ ] Otestovat na mobilních zařízeních
- [ ] Upravit `.container` na fluid-container s max-width
- [ ] Media queries pro breakpointy
- [ ] Touch-friendly navigace
- [ ] Optimalizované obrázky pro mobil

### Fáze 5: Admin rozhraní (1-2 dny)

#### 5.1 Customizace admin

- [ ] Custom dashboard widget s rychlými statistikami
- [ ] Admin columns pro custom post types
- [ ] Quick edit pro časté změny
- [ ] Bulk actions pro hromadné úpravy
- [ ] Admin menu reorganizace (seskupit sportovní sekce)

#### 5.2 User roles

- [ ] **Administrátor** - plná práva
- [ ] **Editor** - může editovat veškerý obsah
- [ ] **Přispěvatel** - může přidávat aktuality a galerie
- [ ] **Autor** - může přidávat pouze aktuality

#### 5.3 Dokumentace pro editory

- [ ] Návod jak přidat zápas
- [ ] Návod jak přidat hráče
- [ ] Návod jak vytvořit galerii
- [ ] Návod jak změnit banner
- [ ] Video tutoriály

### Fáze 6: Migrace dat (1 den)

#### 6.1 Import obsahu

- [ ] Převést aktuální kategorie na custom post types
- [ ] Import starých příspěvků aktualit
- [ ] Vytvořit všechny týmy s údaji
- [ ] Import soupisek hráčů
- [ ] Nahrát obrázky a loga
- [ ] Vytvořit ukázkové zápasy

#### 6.2 Nastavení

- [ ] Nastavit permalink strukturu
- [ ] Vytvořit hlavní menu
- [ ] Nastavit homepage na index.php
- [ ] Vytvořit 404 stránku
- [ ] Nastavit často používané velikosti obrázků

### Fáze 7: Optimalizace a SEO (1-2 dny)

#### 7.1 Performance

- [ ] Minifikace CSS a JavaScript
- [ ] Lazy loading obrázků
- [ ] Optimalizace databázových queries
- [ ] Caching (WP Rocket nebo W3 Total Cache)
- [ ] CDN pro statické soubory
- [ ] Optimalizace obrázků (WebP format)

#### 7.2 SEO

- [ ] Meta tagy pro všechny stránky
- [ ] Schema.org markup pro sportovní klub
- [ ] XML sitemap
- [ ] Robots.txt
- [ ] Open Graph tagy pro social sharing
- [ ] Alt texty pro všechny obrázky

#### 7.3 Bezpečnost

- [ ] Aktualizace WordPress, téma a pluginů
- [ ] Silná hesla pro admin
- [ ] Two-factor authentication
- [ ] Security plugin (Wordfence nebo Sucuri)
- [ ] Regular backups (UpdraftPlus)
- [ ] SSL certifikát

### Fáze 8: Testování (2-3 dny)

#### 8.1 Funkční testování

- [ ] Testovat všechny filtry a vyhledávání
- [ ] Ověřit všechny odkazy a navigaci
- [ ] Testovat formuláře
- [ ] Ověřit správné zobrazení všech custom post types
- [ ] Testovat admin rozhraní

#### 8.2 Cross-browser testing

- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] Mobilní prohlížeče (iOS Safari, Chrome Android)

#### 8.3 Responsivita testing

- [ ] Desktop (1920px, 1366px)
- [ ] Tablet (768px, 1024px)
- [ ] Mobile (375px, 414px)
- [ ] Landscape orientace

#### 8.4 Performance testing

- [ ] PageSpeed Insights
- [ ] GTmetrix
- [ ] Loading time pod 3 sekundy
- [ ] Lighthouse audit

### Fáze 9: Launch (1 den)

#### 9.1 Pre-launch checklist

- [ ] Záloha současného webu
- [ ] Export produkční databáze
- [ ] Kontrola všech URL adres
- [ ] Nastavení přesměrování (301 redirects)
- [ ] Testování na staging serveru

#### 9.2 Deployment

- [ ] Upload souborů tématu na produkci
- [ ] Import databáze
- [ ] Aktualizace URLs v databázi (Search & Replace)
- [ ] Nahrát media soubory
- [ ] Aktivovat produkční pluginy
- [ ] Zapnout caching

#### 9.3 Post-launch

- [ ] Monitorovat chyby v konzoli
- [ ] Sledovat Google Search Console
- [ ] Testovat kontaktní formuláře
- [ ] Verify analytics tracking
- [ ] Odeslat sitemap do Google

### Fáze 10: Školení a údržba (průběžné)

#### 10.1 Školení klientů

- [ ] Jak přidat nový zápas
- [ ] Jak upravit tým a soupisku
- [ ] Jak vytvořit galerii
- [ ] Jak publikovat aktualitu
- [ ] Jak aktualizovat kontakty

#### 10.2 Údržba

- [ ] Měsíční aktualizace WordPressu
- [ ] Pravidelné zálohy
- [ ] Monitoring bezpečnosti
- [ ] Performance monitoring
- [ ] Content updates

## Časový odhad

| Fáze | Čas | Popis |
|------|-----|-------|
| Fáze 1 | 1-2 dny | Analýza a příprava |
| Fáze 2 | 2-3 dny | Datová struktura |
| Fáze 3 | 3-4 dny | Implementace šablon |
| Fáze 4 | 2-3 dny | Frontend funkcionality |
| Fáze 5 | 1-2 dny | Admin rozhraní |
| Fáze 6 | 1 den | Migrace dat |
| Fáze 7 | 1-2 dny | Optimalizace a SEO |
| Fáze 8 | 2-3 dny | Testování |
| Fáze 9 | 1 den | Launch |
| Fáze 10 | Průběžné | Školení a údržba |
| **CELKEM** | **14-23 dnů** | **Čistý vývoj** |

## Prioritizace

### Must-have (Kritické)
1. Custom post types pro zápasy, týmy, hráče
2. Funkční filtry a vyhledávání
3. Responsivní design
4. Admin rozhraní pro správu obsahu
5. Bezpečnost a zálohy

### Should-have (Důležité)
1. Galerie s lightboxem
2. Stránka sponzorů
3. SEO optimalizace
4. Performance optimalizace
5. Detailní statistiky

### Could-have (Nice to have)
1. PDF export soupisek
2. Statistiky hráčů
3. Porovnání týmů
4. Newsletter
5. Social media integrace

### Won't-have (Pro budoucnost)
1. E-shop s klubovým zbožím
2. Online vstupenky
3. Live scoring
4. Mobilní aplikace
5. Fanouškovská sekce

## Rizika a jejich mitigace

### Riziko 1: Ztráta dat při migraci
**Mitigace**: Pravidelné zálohy, testování na staging serveru

### Riziko 2: Performance problémy
**Mitigace**: Caching, CDN, optimalizace queries, lazy loading

### Riziko 3: Kompatibilita pluginů
**Mitigace**: Testování před nasazením, výběr stabilních a udržovaných pluginů

### Riziko 4: Chyby v custom kódu
**Mitigace**: Code review, testování, error logging

### Riziko 5: SEO pokles po migraci
**Mitigace**: 301 redirects, zachování URL struktury, sitemap update

## Doporučení

1. **Fázovaný přístup**: Neimplementovat vše najednou, prioritizovat must-have features
2. **Staging prostředí**: Vždy testovat na kopii před nasazením na produkci
3. **Version control**: Používat Git pro verzování kódu
4. **Dokumentace**: Dokumentovat custom funkce a rozhodnutí
5. **User testing**: Nechat otestovat několik uživatelů před spuštěním
6. **Mobile-first**: Navrhovat nejprve pro mobily, pak desktop
7. **Accessibility**: Dbát na přístupnost pro všechny uživatele
8. **Future-proof**: Navrhovat s ohledem na budoucí rozšíření

## Závěr

Kompletní migrace webu TJ Slavoj Mýto do plně funkčního WordPress tématu je realizovatelná za 3-5 týdnů čistého vývoje. Klíčem k úspěchu je systematický přístup, důkladné testování a kvalitní dokumentace pro budoucí správu webu.

Současná struktura v `original` složce poskytuje dobrý základ, ale vyžaduje významné rozšíření o custom post types, AJAX filtrování a admin rozhraní pro pohodlnou správu obsahu.
