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

1. Stáhněte nejnovější WordPress z [wordpress.org/download](https://wordpress.org/download/).
2. Rozbalte do složky `C:\xampp\htdocs\tj-slavoj-myto\` (Windows)  
   nebo `/Applications/XAMPP/htdocs/tj-slavoj-myto/` (macOS).

### Krok 3 – Vytvořte databázi

1. Otevřete prohlížeč na `http://localhost/phpmyadmin`.
2. Klikněte **Nová databáze**, pojmenujte ji `slavoj_myto` a klikněte **Vytvořit**.

### Krok 4 – Nainstalujte WordPress

1. V prohlížeči otevřete `http://localhost/tj-slavoj-myto/`.
2. Projděte instalačním průvodcem WordPress:
   - Databáze: `slavoj_myto`
   - Uživatel DB: `root`
   - Heslo DB: *(prázdné v XAMPP)*
   - Host DB: `localhost`
3. Vyplňte název webu a admin přihlašovací údaje.

### Krok 5 – Zkopírujte téma

1. Klonujte tento repozitář (pokud ještě není stažený):
   ```bash
   git clone https://github.com/Nomoos/lukasbejcek.git
   ```
2. Zkopírujte obsah složky `web/theme/` do:
   ```
   C:\xampp\htdocs\tj-slavoj-myto\wp-content\themes\slavoj-myto\
   ```

Aktivujte téma v admin rozhraní (**Vzhled → Témata**).

### Krok 6 – Nainstalujte potřebné pluginy

#### 6a – Vlastní plugin (povinné)

Zkopírujte složku `web/plugins/slavoj-custom-fields` do adresáře:
```
wp-content/plugins/slavoj-custom-fields/
```
*(Při použití složky `wordpress/` z tohoto repozitáře je plugin již na správném místě: `wordpress/wp-content/plugins/slavoj-custom-fields/`.)*

Poté v sekci **Pluginy** aktivujte plugin **Slavoj Custom Fields** (`slavoj-custom-fields`).  
Plugin zastupuje funkce Advanced Custom Fields a Custom Post Type UI – registruje všechny vlastní typy obsahu (CPT), taxonomie, meta boxy a administrační nástrojovou stránku.

#### 6b – Doporučené pluginy (volitelné)

V sekci **Pluginy → Přidat nový** nainstalujte dle potřeby:

| Plugin | Účel |
|--------|------|
| Contact Form 7 | Kontaktní formulář |
| Yoast SEO | SEO optimalizace |

### Krok 7 – Nastavte permalink strukturu

Přejděte na **Nastavení → Trvalé odkazy** a zvolte **Název příspěvku** (`/%postname%/`). Uložte.

---

## Ověření funkčnosti

Po instalaci zkontrolujte:

- [ ] Web se zobrazuje na `http://localhost/...`
- [ ] WordPress admin je dostupný na `.../wp-admin`
- [ ] Téma je aktivní
- [ ] Plugin **Slavoj Custom Fields** (`slavoj-custom-fields`) je aktivní
- [ ] Permalink struktura je nastavena na **Název příspěvku**

---

*Vytvořeno: únor 2026*
