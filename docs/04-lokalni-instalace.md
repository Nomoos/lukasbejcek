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

### Krok 4 – Zkopírujte téma a plugin

Ze složky repozitáře zkopírujte vlastní téma a plugin do WordPress instalace:

```
web/theme/tj-slavoj-myto/  →  C:\xampp\htdocs\fotbal_club\wp-content\themes\tj-slavoj-myto\
web/plugins/slavoj-custom-fields/  →  C:\xampp\htdocs\fotbal_club\wp-content\plugins\slavoj-custom-fields\
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

V sekci **Pluginy → Přidat nový** nainstalujte následující pluginy:

| Plugin | Odkaz | Proč ho potřebujeme |
|--------|-------|---------------------|
| Contact Form 7 | [wordpress.org/plugins/contact-form-7](https://wordpress.org/plugins/contact-form-7/) | Zpracování a odesílání kontaktního formuláře na stránce Kontakty; zajišťuje validaci vstupů a odesílání e-mailů bez vlastního PHP kódu |
| Yoast SEO | [wordpress.org/plugins/wordpress-seo](https://wordpress.org/plugins/wordpress-seo/) | Správa meta titulků, popisů, XML sitemapy a Open Graph tagů; zlepšuje viditelnost webu ve vyhledávačích a náhled při sdílení na sociálních sítích |
| WP Super Cache | [wordpress.org/plugins/wp-super-cache](https://wordpress.org/plugins/wp-super-cache/) | Ukládání vygenerovaných stránek do mezipaměti (statické HTML); výrazně zkracuje dobu načítání a snižuje zátěž serveru při větším počtu návštěvníků |
| Wordfence Security | [wordpress.org/plugins/wordfence](https://wordpress.org/plugins/wordfence/) | Firewall, ochrana přihlašovací stránky a skenování malwaru; chrání web před útoky hrubou silou a neoprávněným přístupem |
| UpdraftPlus | [wordpress.org/plugins/updraftplus](https://wordpress.org/plugins/updraftplus/) | Automatické zálohování souborů WordPressu a databáze na vzdálené úložiště (Google Drive, Dropbox); umožňuje obnovu webu při výpadku nebo chybě |

> **Poznámka:** Pluginy **Advanced Custom Fields (ACF)** a **Custom Post Type UI** není nutné instalovat – vlastní typy obsahu (CPT) i meta pole jsou implementovány přímo v kódu tématu v souboru `functions.php`. Podrobný přehled všech pluginů a vlastního kódu najdete v [07-pluginy.md](./07-pluginy.md).

### Krok 10 – Nastavte permalink strukturu

Přejděte na **Nastavení → Trvalé odkazy** a zvolte **Název příspěvku** (`/%postname%/`). Uložte.

---

## Ověření funkčnosti

Po instalaci zkontrolujte:

- [ ] Web se zobrazuje na `http://localhost/fotbal_club/`
- [ ] Stránka Kontakty je dostupná na `http://localhost/fotbal_club/kontakty/`
- [ ] WordPress admin je dostupný na `http://localhost/fotbal_club/wp-admin`
- [ ] Téma je aktivní
- [ ] Plugin **Slavoj Custom Fields** (`slavoj-custom-fields`) je aktivní
- [ ] Permalink struktura je nastavena na **Název příspěvku**

---

*Vytvořeno: únor 2026*
