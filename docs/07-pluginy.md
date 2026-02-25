# 07 – Dokumentace pluginů

Tento dokument popisuje všechny použité pluginy a vlastní kód, který plní jejich funkce na webu TJ Slavoj Mýto. Každý plugin nebo vlastní řešení je popsáno z hlediska funkce, konfigurace, způsobu integrace a vizuálního přizpůsobení.

---

## Přehled přístupu

Pro maximální kontrolu nad kódem a minimální závislost na externích pluginech jsou klíčové funkce implementovány přímo v **tématu** (`functions.php`) a vlastním **pluginu** (`slavoj-custom-fields`). Tam, kde je vhodné použít hotové pluginy (galerie, SEO, výkon), jsou doporučeny konkrétní pluginy s popisem konfigurace.

---

## 1. Custom Post Type UI (CPT UI) – vlastní implementace

**Funkce:** Registrace vlastních typů příspěvků (Custom Post Types) a taxonomií.

**Přístup:** Místo instalace pluginu Custom Post Type UI jsou CPT a taxonomie registrovány přímo v `functions.php` tématu pomocí WordPress funkcí `register_post_type()` a `register_taxonomy()`.

**Registrované CPT:**

| CPT | Slug | Popis |
|-----|------|-------|
| Zápasy | `zapas` | Fotbalové zápasy s výsledky |
| Týmy | `tym` | Přehled týmů a soupiska |
| Hráči | `hrac` | Hráčské profily |
| Galerie | `galerie` | Fotoalba |
| Sponzoři | `sponzor` | Partneři klubu |
| Kontakty | `kontakt` | Výbor a kontaktní osoby |

**Registrované taxonomie:**

| Taxonomie | Slug | Typy obsahu |
|-----------|------|-------------|
| Sezóna | `sezona` | zapas, tym, hrac, galerie |
| Kategorie týmu | `kategorie-tymu` | zapas, tym, hrac, galerie |
| Stav zápasu | `stav-zapasu` | zapas |
| Pozice hráče | `pozice-hrace` | hrac |

**Konfigurace:** Viz soubor `web/theme/tj-slavoj-myto/functions.php`, funkce `slavoj_register_post_types()` a `slavoj_register_taxonomies()`.

**Vizuální přizpůsobení:** CPT jsou dostupné v administraci WordPress v levém menu s vlastními ikonami (dashicons). Administrátorský přehled zápasů zobrazuje sloupce Datum, Tým a Skóre pro přehlednou správu.

---

## 2. Advanced Custom Fields (ACF) – vlastní implementace

**Funkce:** Správa vlastních polí (meta dat) pro jednotlivé typy obsahu.

**Přístup:** Vlastní meta boxy jsou implementovány v `functions.php` pomocí WordPress funkcí `add_meta_box()`, `get_post_meta()` a `update_post_meta()`. Výsledek je totožný s ACF, ale bez závislosti na externím pluginu.

**Pole pro Zápas (`zapas`):**

| Pole | Klíč | Typ | Popis |
|------|------|-----|-------|
| Datum zápasu | `datum_zapasu` | date (Y-m-d) | Datum konání zápasu |
| Čas výkopu | `cas_zapasu` | time (HH:MM) | Čas zahájení zápasu |
| Domácí tým | `domaci` | text | Název domácího týmu |
| Hostující tým | `hoste` | text | Název hostujícího týmu |
| Skóre | `skore` | text | Výsledek ve formátu „3:1" |
| Střelci | `strelci` | text | Jména střelců, např. „2× Novák, Bejček" |
| Místo konání | `misto_konani` | text | Název hřiště nebo adresa |

**Pole pro Tým (`tym`):**

| Pole | Klíč | Typ | Popis |
|------|------|-----|-------|
| Slug týmu | `tym_slug` | text | Identifikátor pro propojení s hráči (např. `muzi-a`) |
| Počet hráčů | `pocet_hracu` | number | Celkový počet hráčů v soupisku |
| Hlavní trenér | `hlavni_trener` | text | Jméno hlavního trenéra |
| Asistent trenéra | `asistent_trenera` | text | Jméno asistenta trenéra |
| Zdravotník | `zdravotnik` | text | Jméno zdravotníka |

**Pole pro Hráče (`hrac`):**

| Pole | Klíč | Typ | Popis |
|------|------|-----|-------|
| Číslo dresu | `cislo` | number | Číslo na dresu hráče (1–99) |
| Rok narození | `rok_narozeni` | number | Rok narození hráče |
| Slug týmu | `tym_slug` | text | Propojení s týmem (musí odpovídat poli v CPT Tým) |

**Integrace:** Meta boxy se zobrazují na editační stránce každého příspěvku daného CPT v administraci WordPress. Data jsou uložena v databázi WordPress ve tabulce `wp_postmeta`.

**Vizuální přizpůsobení:** Meta boxy používají standardní WordPress administrační styly (`widefat`, `form-table`), čímž jsou vizuálně konzistentní s ostatními částmi administrace. Pole jsou opatřena popisky a ukázkovými hodnotami (`placeholder`).

---

## 3. Slavoj Custom Fields (vlastní plugin)

**Soubor:** `web/plugins/slavoj-custom-fields/slavoj-custom-fields.php`

**Funkce:** Rozšiřující administrační funkce – nástrojová stránka s přehledem nastavení, automatické vytvoření výchozích hodnot taxonomií (seed), rozšíření sloupců v admin přehledech a řazení.

**Instalace:**
1. Zkopírujte složku `slavoj-custom-fields` do adresáře `wp-content/plugins/`.
2. V administraci WordPress přejděte na **Pluginy** a aktivujte plugin **Slavoj Custom Fields**.
3. Při aktivaci se automaticky vytvoří výchozí hodnoty taxonomií.

**Nástrojová stránka:** Po aktivaci je dostupná pod **Nástroje → Slavoj nastavení**. Obsahuje:
- Přehled registrovaných CPT a jejich polí
- Přehled taxonomií
- Tlačítko pro vytvoření výchozích hodnot taxonomií

**Vizuální přizpůsobení:** Plugin neobsahuje vlastní frontend styly. V administraci používá standardní WordPress tabulky (`widefat striped`) a notifikace.

---

## 4. FacetWP / Search & Filter Pro – filtrace obsahu

**Funkce:** Filtrování zápasů a týmů podle sezóny, kategorie týmu a stavu zápasu.

**Přístup:** Filtrace je implementována přímo v šabloně pomocí HTML `<form>` s `<select>` prvky a GET parametrů. Hodnoty se zpracovávají přes WordPress `WP_Query` s `tax_query` (pro taxonomie) a `meta_query` (pro datum).

**Šablony s filtrací:**
- `page-zapasy.php` – filtrace zápasů (tým, sezóna, stav)
- `page-tymy.php` – filtrace týmů (tým, sezóna)
- `page-galerie.php` – filtrace galerie (tým, sezóna)
- `archive-zapas.php` – archivní šablona CPT s filtrací
- `archive-tym.php` – archivní šablona CPT s filtrací

**Integrace:** Formuláře odesílají GET požadavek na aktuální stránku. PHP kód načte parametry pomocí `$_GET`, sanitizuje je pomocí `sanitize_text_field()` a předá je do `WP_Query`. Stránka se automaticky přenačte s filtrovanými výsledky.

**Vizuální přizpůsobení:** Filtrovací formuláře používají Bootstrap 5 třídy (`form-select`, `d-flex`, `gap-3`) s barvami klubu (modrá `#233D97` pro filtry tým a stav). CSS třídy `filter-select-team`, `filter-select-season`, `filter-select-status` jsou definovány v `style.css`.

---

## 5. The Events Calendar – kalendářový modul

**Plugin:** The Events Calendar (od Modern Tribe)  
**WordPress.org:** https://wordpress.org/plugins/the-events-calendar/

**Funkce:** Vizuální zobrazení nadcházejících a odehraných zápasů v kalendáři. Umožňuje filtrování podle kategorií, zobrazení ve formátu měsíc/týden/den/seznam.

**Instalace a konfigurace:**
1. Nainstalujte plugin přes **Pluginy → Přidat nový** a vyhledejte „The Events Calendar".
2. Po aktivaci přejděte na **Události → Nastavení** a nakonfigurujte:
   - Formát data: `j. n. Y`
   - Čas: 24hodinový formát
   - Barva akcentu: `#233D97` (modrá klubu)
3. Vytvořte zápasy jako **Události** (alternativně napojte na CPT `zapas` – vyžaduje vlastní kód).

**Vizuální přizpůsobení:** V `style.css` přidejte pravidla pro CSS třídy pluginu:
```css
.tribe-events-calendar th,
.tribe-events-calendar td.tribe-events-has-events {
    background-color: #233D97;
    color: #fff;
}
.tribe-event-url {
    color: #233D97;
}
```

**Alternativy:** Event Organiser, Simple Calendar (Google Calendar).

---

## 6. Modula / NextGEN Gallery – galerie fotografií

**Plugin:** Modula Image Gallery  
**WordPress.org:** https://wordpress.org/plugins/modula-best-grid-gallery/

**Funkce:** Vytváření přizpůsobených fotografických galérií se světelnými efekty (lightbox), různými rozloženími mřížky a filtrováním.

**Instalace a konfigurace:**
1. Nainstalujte plugin přes **Pluginy → Přidat nový** a vyhledejte „Modula".
2. Přejděte na **Modula → Přidat novou galerii** a nahrajte fotografie.
3. Nastavte styl:
   - Barva pozadí lightboxu: `rgba(0,0,0,0.9)`
   - Barva hover efektu: `#233D97`
   - Zaoblení rohů: `15px`
4. Zkopírujte shortcode (např. `[modula id="1"]`) a vložte ho do obsahu fotoalba.

**Vlastní lightbox (záložní řešení):** Téma obsahuje vlastní JavaScript lightbox v souboru `single-galerie.php`, který funguje bez externího pluginu. Lightbox se aktivuje kliknutím na miniaturu a umožňuje procházení fotografií klávesnicí (šipky, Escape).

**Vizuální přizpůsobení:** Vlastní lightbox je stylizován v `style.css` s třídami `.lightbox-overlay`, `.lightbox-close`, `.lightbox-prev`, `.lightbox-next`. Barva navigačních šipek a tlačítka zavření je bílá na tmavém pozadí.

**Alternativy:** Envira Gallery, NextGEN Gallery.

---

## 7. WP Super Cache / LiteSpeed Cache – výkon a cache

**Plugin:** WP Super Cache  
**WordPress.org:** https://wordpress.org/plugins/wp-super-cache/

**Funkce:** Ukládání statických HTML stránek do mezipaměti pro rychlejší načítání. Snižuje zátěž serveru a dobu odezvy.

**Instalace a konfigurace:**
1. Nainstalujte plugin a přejděte na **Nastavení → WP Super Cache**.
2. Zapněte **Cache ON** na záložce Snadné nastavení.
3. Doporučená nastavení (záložka Pokročilé):
   - ✅ Komprimovat stránky
   - ✅ Cache na disk (použít mod_rewrite)
   - ✅ Mobilní podpora

**Implementované optimalizace v tématu:**
- **Lazy loading:** Funkce `slavoj_add_lazy_loading()` v `functions.php` přidává atribut `loading="lazy"` ke všem obrázkům v obsahu a náhledovým obrázkům.
- **Bootstrap z CDN:** Bootstrap 5 CSS a JS jsou načítány z CDN (jsdelivr.net), čímž se využívá cache prohlížeče.

**Alternativy:** LiteSpeed Cache (pro hosting s LiteSpeed serverem), W3 Total Cache.

---

## 8. Smush – optimalizace obrázků

**Plugin:** Smush – Compress, Optimize and Lazy Load Images  
**WordPress.org:** https://wordpress.org/plugins/wp-smushit/

**Funkce:** Automatická komprese a optimalizace obrázků při nahrávání. Snižuje velikost souborů bez viditelné ztráty kvality.

**Instalace a konfigurace:**
1. Nainstalujte plugin a přejděte na **Smush → Nastavení**.
2. Povolte:
   - ✅ Automatická komprese při nahrání
   - ✅ Lazy Loading (pokud není aktivní vlastní implementace)
   - ✅ WebP konverze (pokud server podporuje)
3. Spusťte **Hromadná komprese** pro stávající obrázky.

**Alternativy:** ShortPixel, Imagify, EWWW Image Optimizer.

---

## 9. Yoast SEO – SEO a analytika

**Plugin:** Yoast SEO  
**WordPress.org:** https://wordpress.org/plugins/wordpress-seo/

**Funkce:** Optimalizace pro vyhledávače – nastavení meta titulků, popisů, Open Graph tagů, XML sitemap, analýza čitelnosti obsahu.

**Instalace a konfigurace:**
1. Nainstalujte plugin a projděte průvodce nastavením.
2. V **SEO → Obecné → Funkce** povolte:
   - ✅ XML sitemap
   - ✅ Open Graph metadata
3. V **SEO → Vyhledávání → Typy obsahu** nastavte, které CPT mají být indexovány (zapas, tym, galerie).
4. Vyplňte **SEO → Obecné → Organizace** – název klubu, logo, adresa.

**Google Analytics:** Propojení přes **Google Site Kit** (https://wordpress.org/plugins/google-site-kit/) nebo vložením tracking kódu přes `functions.php`:
```php
function slavoj_google_analytics() {
    ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-XXXXXXXXXX');
    </script>
    <?php
}
add_action('wp_head', 'slavoj_google_analytics');
```

**Vizuální přizpůsobení:** Yoast SEO zobrazuje v administraci meta box pod editorem příspěvku. Bez frontendových změn.

**Alternativy:** Rank Math SEO.

---

## 10. User Role Editor – uživatelské role

**Plugin:** User Role Editor  
**WordPress.org:** https://wordpress.org/plugins/user-role-editor/

**Funkce:** Správa uživatelských rolí a oprávnění. Umožňuje vytvořit vlastní roli „Správce obsahu" s oprávněním editovat zápasy, týmy a galerie, ale bez přístupu k nastavení WordPressu.

**Instalace a konfigurace:**
1. Nainstalujte plugin a přejděte na **Nástroje → User Role Editor**.
2. Vytvořte novou roli „Správce obsahu" se základem Editor.
3. Přidejte oprávnění pro CPT:
   - `edit_zapas`, `edit_zapasy`, `publish_zapasy`
   - `edit_tym`, `edit_tymy`, `publish_tymy`
   - `edit_galerie`, `publish_galerie`
4. Odeberte oprávnění: `manage_options`, `edit_theme_options`, `install_plugins`.

**Uživatelské role na webu:**

| Role | Popis | Oprávnění |
|------|-------|-----------|
| Administrátor | Správce webu (vedení klubu) | Plný přístup ke všemu |
| Správce obsahu | Správce zápasů a obsahu | Editace CPT, bez nastavení |
| Přispěvatel | Zapisovatel výsledků | Vytváření nových záznamů |

**Alternativy:** Members (by MemberPress).

---

## 11. Wordfence Security – zabezpečení

**Plugin:** Wordfence Security  
**WordPress.org:** https://wordpress.org/plugins/wordfence/

**Funkce:** Firewall, skenování malware, ochrana přihlašovací stránky, blokování podezřelých IP adres, dvoufaktorové ověřování.

**Instalace a konfigurace:**
1. Nainstalujte plugin a přejděte na **Wordfence → Všechny možnosti**.
2. Povolte:
   - ✅ Web Application Firewall (WAF)
   - ✅ Ochrana přihlášení (limit pokusů)
   - ✅ Pravidelné skenování
3. V **Přihlášení a zabezpečení** nastavte:
   - Maximální počet pokusů o přihlášení: 5
   - Zablokovat po: 30 minutách

**Hosting a HTTPS:** Web je nasazen na webhostingu se SSL certifikátem (HTTPS). Certifikát je zajišťován poskytovatelem hostingu (Let's Encrypt nebo komerční certifikát).

---

## 12. UpdraftPlus – zálohování

**Plugin:** UpdraftPlus Backup/Restore  
**WordPress.org:** https://wordpress.org/plugins/updraftplus/

**Funkce:** Automatické zálohování souborů WordPressu a databáze. Zálohy mohou být ukládány na Google Drive, Dropbox, FTP nebo lokálně.

**Konfigurace:**
- Frekvence zálohování databáze: denně
- Frekvence zálohování souborů: týdně
- Vzdálené úložiště: Google Drive nebo Dropbox
- Počet uchovávaných záloh: 10

---

*Vytvořeno: únor 2026 – Fáze implementace*
