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

V sekci **Pluginy → Přidat nový** nainstalujte následující pluginy. Každý z nich plní specifickou roli, která není pokryta vlastním kódem projektu:

| Plugin | Odkaz | Proč ho potřebujeme |
|--------|-------|---------------------|
| Contact Form 7 | [wordpress.org/plugins/contact-form-7](https://wordpress.org/plugins/contact-form-7/) | Zpracování a odesílání kontaktního formuláře na stránce Kontakty; zajišťuje validaci vstupů a odesílání e-mailů bez vlastního PHP kódu |
| Yoast SEO | [wordpress.org/plugins/wordpress-seo](https://wordpress.org/plugins/wordpress-seo/) | Správa meta titulků, popisů, XML sitemapy a Open Graph tagů; zlepšuje viditelnost webu ve vyhledávačích a náhled při sdílení na sociálních sítích |
| WP Super Cache | [wordpress.org/plugins/wp-super-cache](https://wordpress.org/plugins/wp-super-cache/) | Ukládání vygenerovaných stránek do mezipaměti (statické HTML); výrazně zkracuje dobu načítání a snižuje zátěž serveru při větším počtu návštěvníků |
| Wordfence Security | [wordpress.org/plugins/wordfence](https://wordpress.org/plugins/wordfence/) | Firewall, ochrana přihlašovací stránky a skenování malwaru; chrání web před útoky hrubou silou a neoprávněným přístupem |
| UpdraftPlus | [wordpress.org/plugins/updraftplus](https://wordpress.org/plugins/updraftplus/) | Automatické zálohování souborů WordPressu a databáze na vzdálené úložiště (Google Drive, Dropbox); umožňuje obnovu webu při výpadku nebo chybě |

> **Poznámka:** Pluginy **Advanced Custom Fields (ACF)** a **Custom Post Type UI** není nutné instalovat – vlastní typy obsahu (CPT) i meta pole jsou implementovány přímo v kódu tématu v souboru `functions.php`. Podrobný přehled všech pluginů a vlastního kódu najdete v [07-pluginy.md](./07-pluginy.md).

### Krok 7 – Nastavte permalink strukturu

Přejděte na **Nastavení → Trvalé odkazy** a zvolte **Název příspěvku** (`/%postname%/`). Uložte.

---

## Ověření funkčnosti

Po instalaci zkontrolujte:

- [ ] Web se zobrazuje na `http://localhost/...`
- [ ] WordPress admin je dostupný na `.../wp-admin`
- [ ] Téma je aktivní
- [ ] Pluginy jsou aktivní
- [ ] Permalink struktura je nastavena na **Název příspěvku**

---

*Vytvořeno: únor 2026*
