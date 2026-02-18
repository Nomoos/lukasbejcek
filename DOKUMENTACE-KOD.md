# Dokumentace - Jak funguje současný kód (složka original)

## Přehled struktury

Složka `original` obsahuje částečně implementovaný WordPress téma pro webové stránky fotbalového klubu TJ Slavoj Mýto. Téma je ve stavu nedokončené migrace - obsahuje jak statické HTML soubory (ve složce `html`), tak i WordPress šablonové soubory.

## Struktura souborů

### 1. Základní WordPress soubory

#### `style.css`
- **Účel**: Hlavní CSS soubor WordPress tématu
- **Obsahuje**: 
  - Definici WordPress tématu v hlavičce (Theme Name, Author, Version atd.)
  - Vlastní CSS styly pro layout a vzhled
  - Využívá Bootstrap 5.3.3 (načítaný přes CDN)
- **Hlavní třídy**:
  - `.container` - kontejner šířky 1140px
  - `.banner` - sekce pro hlavní banner
  - `.zapasy-container` - kontejner pro karty zápasů
  - `.match-card` - karta jednotlivého zápasu
  - `.gallery-card` - karta galerie

#### `function.php`
- **Účel**: Soubor s vlastními PHP funkcemi pro WordPress téma
- **Hlavní funkce**:
  1. **`moje_sablona_menus()`**
     - Registruje navigační menu v WordPressu
     - Vytváří "Hlavní menu" přístupné přes WordPress admin
  
  2. **`slavoj_vypis_soupisku($kategorie)`**
     - Vlastní funkce pro výpis soupisek hráčů
     - Parametr: název kategorie (`brankari`, `hraci`)
     - Načítá příspěvky z dané kategorie
     - Řadí hráče podle custom fieldu `cislo` (číslo dresu)
     - Zobrazuje: číslo hráče, jméno (title), rok narození (custom field)

### 2. WordPress šablonové soubory

#### `header.php`
- **Účel**: Hlavička webu (načítána pomocí `get_header()`)
- **Co obsahuje**:
  - HTML DOCTYPE a meta tagy
  - Načítání Bootstrap CSS z CDN
  - Logo a název klubu s dynamickým URL obrázku (`bloginfo('template_directory')`)
  - Navigační menu generované funkcí `wp_nav_menu()`
  - Nastavení: `theme_location => 'main_menu'`

#### `footer.php`
- **Účel**: Patička webu (načítána pomocí `get_footer()`)
- **Co obsahuje**: Pouze ukončovací HTML tagy (velmi jednoduché)

#### `index.php`
- **Účel**: Hlavní stránka / domovská stránka
- **Co obsahuje**:
  1. Banner s obrázkem a textem "Fotbalový klub s tradicí od roku 1909"
  2. Sekce s kartami nadcházejících zápasů (statický HTML obsah)
  3. Dekorativní modré a šedé pruhy
  4. Sekce "Aktuální zprávy"
     - Využívá `WP_Query` pro načtení příspěvků
     - Filtr: kategorie `aktuality`, 5 nejnovějších
     - Zobrazuje title a content každé aktuality

### 3. Stránkové šablony (Page Templates)

#### `page-tymy.php`
- **Účel**: Stránka zobrazující týmy klubu
- **Funkce**:
  - Statické select boxy pro výběr týmu a sezóny (nefunkční)
  - Informační box s údaji o týmu (počet hráčů, trenér, atd.)
  - Soupiska hráčů rozdělená na:
    - Brankáře - volá `slavoj_vypis_soupisku('brankari')`
    - Hráče - volá `slavoj_vypis_soupisku('hraci')`

#### `page-galerie.php`
- **Účel**: Stránka s fotografickou galerií
- **Funkce**:
  - Filtry pro výběr týmu a sezóny (statické HTML)
  - Dekorativní modré pruhy s logem
  - Statické placeholder karty galerie (nefunkční HTML)
  - WordPress Query pro načtení galerií:
    - Kategorie: `galerie`
    - 5 příspěvků
    - Zobrazuje featured image nebo placeholder
    - Každá položka je klikatelná odkaz na detail (`the_permalink()`)

#### `page-zapasy.php`
- **Účel**: Stránka se seznamem zápasů
- **Funkce**:
  - Filtry pro týmy, sezónu a typ zápasů (statické)
  - Dekorativní modré pruhy
  - Statické ukázkové karty zápasů (3 ks)
  - WordPress Query pro dynamické zápasy:
    - Kategorie: `zapasy`
    - 20 příspěvků
    - Poznámka: Query je definovaný, ale výstup není plně implementován

#### `page-historie.php`
- **Účel**: Stránka s historií klubu
- **Funkce**:
  - Zobrazuje statický text o historii klubu od roku 1909
  - Logo klubu jako dekorační prvek
  - Kompletně statický obsah (bez WordPress query)

#### `page-kontakty.php`
- **Účel**: Stránka s kontakty na výbor klubu
- **Funkce**:
  - WordPress Query pro načtení kontaktů:
    - Kategorie: `kontakty`
    - 5 příspěvků
    - Pro každý kontakt: title (jméno), content (funkce), statické tel/email
  - Poznámka: Tel. a email jsou hardcoded (stejné pro všechny)

#### `page-sponzori.php`
- Soubor existuje, ale je prázdný (neimplementováno)

#### `single-galerie.php`
- Soubor existuje pro jednotlivé položky galerie, ale obsah není viditelný v kontrole

### 4. Statické HTML soubory (složka `html`)

Ve složce `html` jsou původní statické HTML soubory:
- `index.html` - domovská stránka
- `zapasy.html` - zápasy
- `tymy.html` - týmy
- `galerie.html` - seznam galerií
- `galerie-rozkliknutí.html` - detail galerie
- `historie.html` - historie klubu
- `kontakty.html` - kontakty

Tyto soubory slouží pravděpodobně jako **reference/předloha** pro WordPress verzi.

## Architektura WordPress implementace

### Datový model (Custom Fields & Kategorie)

Web využívá WordPress příspěvky (posts) s kategoriemi pro různé typy obsahu:

1. **Kategorie `aktuality`** - pro novinky na hlavní stránce
2. **Kategorie `galerie`** - pro fotogalerie
3. **Kategorie `zapasy`** - pro zápasy
4. **Kategorie `kontakty`** - pro kontaktní osoby
5. **Kategorie `brankari`** - pro brankáře týmu
6. **Kategorie `hraci`** - pro hráče týmu

### Custom Fields (meta data)

Pro hráče se používají custom fields:
- `cislo` - číslo dresu (používá se pro řazení)
- `rok_narozeni` - rok narození hráče

## Problémy a nedokončené části

### 1. **Filtry nefungují**
- Všechny select boxy (filtry týmů, sezón) jsou pouze statické HTML
- Nejsou napojeny na JavaScript ani WordPress query
- Nefiltrují obsah při změně výběru

### 2. **Hardcoded data**
- Mnoho obsahu je stále staticky vloženo v PHP souborech
- Mělo by být nahrazeno WordPress příspěvky s custom fields
- Např.: údaje o zápasech, týmech, trénerech

### 3. **Nekonzistentní implementace**
- Některé stránky používají WP_Query (dynamický obsah)
- Jiné mají čistě statický HTML obsah
- Mix obou přístupů na stejné stránce

### 4. **Chybějící funkcionality**
- Stránka sponzorů není implementována
- Není implementováno admin rozhraní pro správu zápasů
- Chybí pokročilé filtrování a vyhledávání

### 5. **Obrázky a media**
- Odkazy na obrázky (logo, banner) předpokládají strukturu `img/` složky
- Není jasné, kde jsou obrázky uloženy
- Featured images u galerií nejsou konsistentně nastaveny

### 6. **Responsivita**
- Používá Bootstrap 5.3.3, takže základní responsivita je zajištěna
- Ale vlastní CSS může mít problémy na mobilech (pevná šířka `.container`)

## Technologie

- **WordPress**: CMS systém
- **PHP**: serverový jazyk pro WordPress šablony
- **Bootstrap 5.3.3**: CSS framework (přes CDN)
- **Vlastní CSS**: doplňkové styly v `style.css`
- **HTML5**: pro markup
- **WP_Query**: pro dotazy na databázi WordPressu

## Závěr

Jedná se o **rozpracované WordPress téma** v raném stádiu migrace ze statických HTML stránek. Základní struktura je vytvořena, ale mnoho funkcionality je buď hardcoded nebo nefunkční. Pro plně funkční web je potřeba:

1. Dokončit implementaci WordPress queries na všech stránkách
2. Vytvořit custom post types pro zápasy a týmy
3. Implementovat funkční filtrování pomocí AJAX
4. Vytvořit admin rozhraní pro správu obsahu
5. Přesunout všechen statický obsah do WordPress databáze
6. Optimalizovat pro mobilní zařízení
