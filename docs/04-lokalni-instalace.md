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

### Krok 2 – Naklonujte repozitář

Naklonujte repozitář přímo do složky `htdocs` pod názvem `fotbal_club`:

```bash
# Windows
git clone https://github.com/Nomoos/lukasbejcek.git C:\xampp\htdocs\fotbal_club

# macOS / Linux
git clone https://github.com/Nomoos/lukasbejcek.git /Applications/XAMPP/htdocs/fotbal_club
```

WordPress core soubory se nacházejí v podsložce `wordpress/` repozitáře, ale veřejná adresa webu **neobsahuje `/wordpress/`** v URL.  
Stránky (např. Kontakty) budou dostupné na: **`http://localhost/fotbal_club/kontakty/`**

> Toto je standardní technika „[WordPress in its own directory](https://developer.wordpress.org/advanced-administration/server/wordpress-in-directory/)":
> kořenový `index.php` přesměruje požadavky do `wordpress/`, zatímco Apache přepisuje URL pomocí `.htaccess` v kořeni repozitáře.

### Krok 3 – Vytvořte databázi

1. Otevřete prohlížeč na `http://localhost/phpmyadmin`.
2. Klikněte **Nová databáze**, pojmenujte ji `slavoj_myto` a klikněte **Vytvořit**.

### Krok 4 – Nastavte wp-config.php

Repozitář obsahuje připravený soubor `wordpress/wp-config.php` s lokální konfigurací.  
Ujistěte se, že v souboru jsou správně nastaveny hodnoty:

```php
define( 'DB_NAME', 'slavoj_myto' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );          // v XAMPP prázdné
define( 'WP_HOME',    'http://localhost/fotbal_club' );           // veřejná URL webu
define( 'WP_SITEURL', 'http://localhost/fotbal_club/wordpress' ); // kde jsou soubory WP
```

Dále nahraďte zástupné fráze `'put your unique phrase here'` vlastními hodnotami  
vygenerovanými na https://api.wordpress.org/secret-key/1.1/salt/

### Krok 5 – Nainstalujte WordPress

1. V prohlížeči otevřete `http://localhost/fotbal_club/wordpress/` (instalační průvodce funguje přes adresu SITEURL).
2. Projděte instalačním průvodcem WordPress a vyplňte název webu a admin přihlašovací údaje.
3. Po instalaci bude web dostupný na `http://localhost/fotbal_club/`.

### Krok 6 – Aktivujte téma

Zkopírujte obsah složky `web/theme/tj-slavoj-myto/` do:
```
wordpress/wp-content/themes/tj-slavoj-myto/
```

Aktivujte téma v admin rozhraní (**Vzhled → Témata**).

### Krok 7 – Nainstalujte potřebné pluginy

#### 6a – Vlastní plugin (povinné)

Zkopírujte složku `web/plugins/slavoj-custom-fields` do adresáře:
```
wp-content/plugins/slavoj-custom-fields/
```
*(Při použití složky `wordpress/` z tohoto repozitáře je plugin již na správném místě: `wordpress/wp-content/plugins/slavoj-custom-fields/`.)*

Poté v sekci **Pluginy** aktivujte plugin **Slavoj Custom Fields** (`slavoj-custom-fields`).  
Plugin zastupuje funkce Advanced Custom Fields a Custom Post Type UI – registruje všechny vlastní typy obsahu (CPT), taxonomie, meta boxy a administrační nástrojovou stránku.

#### 6b – Doporučené pluginy (volitelné)

V sekci **Pluginy → Přidat nový** nainstalujte následující pluginy. Každý z nich plní specifickou roli, která není pokryta vlastním kódem projektu:

| Plugin | Odkaz | Proč ho potřebujeme |
|--------|-------|---------------------|
| Contact Form 7 | [wordpress.org/plugins/contact-form-7](https://wordpress.org/plugins/contact-form-7/) | Zpracování a odesílání kontaktního formuláře na stránce Kontakty; zajišťuje validaci vstupů a odesílání e-mailů bez vlastního PHP kódu |
| Yoast SEO | [wordpress.org/plugins/wordpress-seo](https://wordpress.org/plugins/wordpress-seo/) | Správa meta titulků, popisů, XML sitemapy a Open Graph tagů; zlepšuje viditelnost webu ve vyhledávačích a náhled při sdílení na sociálních sítích |
| WP Super Cache | [wordpress.org/plugins/wp-super-cache](https://wordpress.org/plugins/wp-super-cache/) | Ukládání vygenerovaných stránek do mezipaměti (statické HTML); výrazně zkracuje dobu načítání a snižuje zátěž serveru při větším počtu návštěvníků |
| Wordfence Security | [wordpress.org/plugins/wordfence](https://wordpress.org/plugins/wordfence/) | Firewall, ochrana přihlašovací stránky a skenování malwaru; chrání web před útoky hrubou silou a neoprávněným přístupem |
| UpdraftPlus | [wordpress.org/plugins/updraftplus](https://wordpress.org/plugins/updraftplus/) | Automatické zálohování souborů WordPressu a databáze na vzdálené úložiště (Google Drive, Dropbox); umožňuje obnovu webu při výpadku nebo chybě |

> **Poznámka:** Pluginy **Advanced Custom Fields (ACF)** a **Custom Post Type UI** není nutné instalovat – vlastní typy obsahu (CPT) i meta pole jsou implementovány přímo v kódu tématu v souboru `functions.php`. Podrobný přehled všech pluginů a vlastního kódu najdete v [07-pluginy.md](./07-pluginy.md).

### Krok 8 – Nastavte permalink strukturu

Přejděte na **Nastavení → Trvalé odkazy** a zvolte **Název příspěvku** (`/%postname%/`). Uložte.

---

## Ověření funkčnosti

Po instalaci zkontrolujte:

- [ ] Web se zobrazuje na `http://localhost/fotbal_club/`
- [ ] Stránka Kontakty je dostupná na `http://localhost/fotbal_club/kontakty/`
- [ ] WordPress admin je dostupný na `http://localhost/fotbal_club/wordpress/wp-admin`
- [ ] Téma je aktivní
- [ ] Plugin **Slavoj Custom Fields** (`slavoj-custom-fields`) je aktivní
- [ ] Permalink struktura je nastavena na **Název příspěvku**

---

*Vytvořeno: únor 2026*
