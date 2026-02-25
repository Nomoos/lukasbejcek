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

Naklonujte repozitář přímo do složky `htdocs` pod názvem `lukasbejcek`:

```bash
# Windows
git clone https://github.com/Nomoos/lukasbejcek.git C:\xampp\htdocs\lukasbejcek

# macOS / Linux
git clone https://github.com/Nomoos/lukasbejcek.git /Applications/XAMPP/htdocs/lukasbejcek
```

WordPress core soubory se nacházejí v podsložce `wordpress/` repozitáře.  
Lokální URL webu bude: **`http://localhost/lukasbejcek/wordpress/`**

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
define( 'WP_HOME',    'http://localhost/lukasbejcek/wordpress' );
define( 'WP_SITEURL', 'http://localhost/lukasbejcek/wordpress' );
```

Dále nahraďte zástupné fráze `'put your unique phrase here'` vlastními hodnotami  
vygenerovanými na https://api.wordpress.org/secret-key/1.1/salt/

### Krok 5 – Nainstalujte WordPress

1. V prohlížeči otevřete `http://localhost/lukasbejcek/wordpress/`.
2. Projděte instalačním průvodcem WordPress a vyplňte název webu a admin přihlašovací údaje.

### Krok 6 – Aktivujte téma

Zkopírujte obsah složky `web/theme/tj-slavoj-myto/` do:
```
wordpress/wp-content/themes/tj-slavoj-myto/
```

Aktivujte téma v admin rozhraní (**Vzhled → Témata**).

### Krok 7 – Nainstalujte potřebné pluginy

V sekci **Pluginy → Přidat nový** nainstalujte:

| Plugin | Účel |
|--------|------|
| Advanced Custom Fields (ACF) | Správa custom polí |
| Custom Post Type UI | Snadné vytvoření CPT |
| Contact Form 7 | Kontaktní formulář |
| Yoast SEO | SEO optimalizace |

### Krok 8 – Nastavte permalink strukturu

Přejděte na **Nastavení → Trvalé odkazy** a zvolte **Název příspěvku** (`/%postname%/`). Uložte.

---

## Ověření funkčnosti

Po instalaci zkontrolujte:

- [ ] Web se zobrazuje na `http://localhost/lukasbejcek/wordpress/`
- [ ] WordPress admin je dostupný na `http://localhost/lukasbejcek/wordpress/wp-admin`
- [ ] Téma je aktivní
- [ ] Pluginy jsou aktivní
- [ ] Permalink struktura je nastavena na **Název příspěvku**

---

*Vytvořeno: únor 2026*
