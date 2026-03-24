# 04 – Lokální instalace (jak rozchodit projekt lokálně)

Tento dokument popisuje, jak spustit WordPress web TJ Slavoj Mýto na **vlastním počítači** pro účely vývoje a testování pomocí **XAMPP** (Apache + MySQL + PHP).

---

## Požadavky

Před instalací se ujistěte, že máte nainstalováno:

- **Git** – pro klonování repozitáře
- [XAMPP](https://www.apachefriends.org/) – klasické prostředí Apache + MySQL + PHP

---

## Instalace pomocí XAMPP

### Krok 1 – Nainstalujte XAMPP

1. Stáhněte a nainstalujte [XAMPP](https://www.apachefriends.org/).
2. Spusťte **Apache** a **MySQL** v XAMPP Control Panel.

### Krok 2 – Stáhněte WordPress

Stáhněte nejnovější verzi WordPressu z [wordpress.org/download](https://wordpress.org/download/) a rozbalte jej do složky:

```
C:\xampp\htdocs\fotbal_club\        (Windows)
/Applications/XAMPP/htdocs/fotbal_club/  (macOS)
```

Tím vznikne standardní WordPress instalace přímo ve složce `fotbal_club/` (tj. soubory `wp-admin/`, `wp-includes/`, `wp-login.php`, `index.php` atd. jsou přímo v `fotbal_club/`).

### Krok 3 – Naklonujte repozitář

Naklonujte repozitář do **dočasné složky** mimo `htdocs`, např.:

```bash
git clone https://github.com/Nomoos/lukasbejcek.git C:\Users\<jmeno>\lukasbejcek
```

> **Poznámka:** Složka `wordpress/` v repozitáři slouží pouze jako **referenční dokumentace** – ukazuje, jaké soubory WordPress používá a jak jsou nakonfigurovány. Do lokální instalace se nekopíruje.

### Krok 4 – Nainstalujte téma a plugin

**Doporučený způsob:** Přejděte do složky `web/` v repozitáři a spusťte `install.bat`. Skript automaticky zkopíruje téma a plugin na správná místa a odstraní případné starší verze.

**Ruční způsob:** Ze složky repozitáře zkopírujte téma a plugin do WordPress instalace:

```
web/wp-content/themes/tj-slavoj-myto/   →  C:\xampp\htdocs\fotbal_club\wp-content\themes\tj-slavoj-myto\
web/wp-content/plugins/slavoj-custom-fields/  →  C:\xampp\htdocs\fotbal_club\wp-content\plugins\slavoj-custom-fields\
```

### Krok 5 – Vytvořte databázi

1. Otevřete prohlížeč na `http://localhost/phpmyadmin`.
2. Klikněte **Nová databáze**, pojmenujte ji `slavoj_myto` a klikněte **Vytvořit**.

### Krok 6 – Nastavte wp-config.php

Spusťte instalační průvodce WordPress na adrese `http://localhost/fotbal_club/wp-admin/install.php` a vyplňte:

- **Název databáze:** `slavoj_myto`
- **Uživatelské jméno:** `root`
- **Heslo:** *(prázdné – výchozí v XAMPP)*
- **Server databáze:** `localhost`

Průvodce vygeneruje soubor `wp-config.php`. Po vygenerování do něj přidejte (nebo zkontrolujte) hodnoty URL konstant:

```php
define( 'WP_HOME',    'http://localhost/fotbal_club' );
define( 'WP_SITEURL', 'http://localhost/fotbal_club' );
```

### Krok 7 – Dokončete instalaci WordPress

1. Pokračujte v průvodci: vyplňte název webu a admin přihlašovací údaje.
2. Po instalaci bude web dostupný na `http://localhost/fotbal_club/`.

### Krok 8 – Aktivujte téma a plugin

1. Přihlaste se do admin rozhraní na `http://localhost/fotbal_club/wp-admin`.
2. Přejděte na **Vzhled → Témata** a aktivujte téma **TJ Slavoj Mýto**.
3. Přejděte na **Pluginy** a aktivujte plugin **Slavoj Custom Fields** (`slavoj-custom-fields`).

### Krok 9 – Doporučené pluginy (volitelné)

V sekci **Pluginy** aktivujte jediný potřebný plugin:

| Plugin | Umístění | Popis |
|--------|----------|-------|
| Slavoj Custom Fields | `wp-content/plugins/slavoj-custom-fields/` | Administrační nástroje – seed taxonomií a dat, rozšířené sloupce, dropdown filtry |

> **Poznámka:** Žádné externí pluginy (ACF, Custom Post Type UI, Yoast SEO apod.) nejsou potřeba – veškerá funkcionalita je implementována přímo v kódu tématu a vlastním pluginu. Podrobnosti viz [07-pluginy.md](../k-odevzdani/07-pluginy.md).

### Krok 10 – Nastavte permalink strukturu

Přejděte na **Nastavení → Trvalé odkazy** a zvolte **Název příspěvku** (`/%postname%/`). Uložte.

### Krok 11 – Naimportujte ukázková data (seed)

Po aktivaci pluginu jsou automaticky vytvořeny výchozí hodnoty taxonomií. Chcete-li naplnit web ukázkovými zápasy, týmy a hráči ze sezóny 2025/26:

1. V administraci přejděte na **Nástroje → Slavoj nastavení**.
2. Klikněte na **„Vytvořit ukázková data (sezóna 2025/26)"**.

Seed vytvoří 2 týmy, 28 zápasů a 7 hráčů. Lze spustit opakovaně – duplicity jsou automaticky přeskočeny.

> **Alternativy:** SQL import (`wordpress/seed-data.sql`) nebo WP-CLI příkaz `wp eval 'slavoj_cf_seed_demo_data();'`.
> Podrobný popis všech způsobů seedování viz **[docs/09-ukazková-data.md](./09-ukazková-data.md)**.

---

## Ověření funkčnosti

Po instalaci zkontrolujte:

- [ ] Web se zobrazuje na `http://localhost/fotbal_club/`
- [ ] Stránka Kontakty je dostupná na `http://localhost/fotbal_club/kontakty/`
- [ ] WordPress admin je dostupný na `http://localhost/fotbal_club/wp-admin`
- [ ] Téma je aktivní
- [ ] Plugin **Slavoj Custom Fields** (`slavoj-custom-fields`) je aktivní
- [ ] Permalink struktura je nastavena na **Název příspěvku**
- [ ] Ukázková data byla vytvořena (**Nástroje → Slavoj nastavení → Vytvořit ukázková data**)

---

*Vytvořeno: únor 2026*
