# 04 – Lokální instalace (jak rozchodit projekt lokálně)

Tento dokument popisuje, jak spustit WordPress web TJ Slavoj Mýto na **vlastním počítači** pro účely vývoje a testování.

---

## Požadavky

Před instalací se ujistěte, že máte nainstalováno:

- **Git** – pro klonování repozitáře
- Jedno z lokálních prostředí:
  - [Local (by WP Engine)](https://localwp.com/) – doporučeno pro začátečníky
  - [XAMPP](https://www.apachefriends.org/) – klasické prostředí Apache + MySQL + PHP
  - [Docker](https://www.docker.com/) + [wp-env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) – pro pokročilé

---

## Možnost A – Local (by WP Engine) – nejjednodušší

### Krok 1 – Nainstalujte Local

1. Stáhněte [Local](https://localwp.com/) a nainstalujte na svůj systém.
2. Otevřete aplikaci Local.

### Krok 2 – Vytvořte nový web

1. Klikněte na **+ Create a new site** (tlačítko vlevo dole).
2. Zadejte název webu, např. `tj-slavoj-myto`.
3. Zvolte verzi PHP ≥ 8.0 a MySQL ≥ 8.0.
4. Nastavte libovolné admin heslo.
5. Klikněte **Add Site** – Local automaticky stáhne a nakonfiguruje WordPress.

### Krok 3 – Zkopírujte téma z repozitáře

1. Klonujte tento repozitář (pokud ještě není stažený):
   ```bash
   git clone https://github.com/Nomoos/lukasbejcek.git
   ```
2. Najděte složku WordPressu vytvořenou aplikací Local – typicky:
   ```
   ~/Local Sites/tj-slavoj-myto/app/public/wp-content/themes/
   ```
3. Zkopírujte obsah složky `web/theme/` z repozitáře do:
   ```
   wp-content/themes/slavoj-myto/
   ```

### Krok 4 – Aktivujte téma

1. V aplikaci Local klikněte na **WP Admin** (otevře se prohlížeč).
2. Přejděte na **Vzhled → Témata** a aktivujte téma `slavoj-myto`.

### Krok 5 – Nainstalujte potřebné pluginy

V sekci **Pluginy → Přidat nový** nainstalujte:

| Plugin | Účel |
|--------|------|
| Advanced Custom Fields (ACF) | Správa custom polí |
| Custom Post Type UI | Snadné vytvoření CPT |
| Contact Form 7 | Kontaktní formulář |
| Yoast SEO | SEO optimalizace |

### Krok 6 – Nastavte permalink strukturu

Přejděte na **Nastavení → Trvalé odkazy** a zvolte **Název příspěvku** (`/%postname%/`). Uložte.

---

## Možnost B – XAMPP

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

Zkopírujte obsah složky `web/theme/` do:
```
C:\xampp\htdocs\tj-slavoj-myto\wp-content\themes\slavoj-myto\
```

Aktivujte téma v admin rozhraní (**Vzhled → Témata**).

---

## Možnost C – Docker + wp-env

### Požadavky

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- Node.js (pro `@wordpress/env`)

### Instalace wp-env

```bash
npm install -g @wordpress/env
```

### Spuštění

Ve složce repozitáře (kde bude `.wp-env.json`):

```bash
wp-env start
```

WordPress poběží na `http://localhost:8888`, admin panel na `http://localhost:8888/wp-admin`  
(výchozí přihlášení: `admin` / `password`).

### Zastavení prostředí

```bash
wp-env stop
```

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
