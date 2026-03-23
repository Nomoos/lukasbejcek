# 05 – Deployment na hosting (přes FTP)

Tento dokument popisuje, jak nasadit WordPress web TJ Slavoj Mýto na **sdílený webhosting** pomocí FTP.

---

## Požadavky na hosting

Před deploymentem ověřte, že váš hosting splňuje:

| Požadavek | Minimální hodnota |
|-----------|-------------------|
| PHP | ≥ 8.0 |
| MySQL / MariaDB | ≥ 8.0 / 10.4 |
| Diskový prostor | ≥ 1 GB |
| FTP přístup | Ano |
| SSL certifikát | Doporučeno (Let's Encrypt nebo placený) |

Doporučené hostingy (sdílený hosting s podporou WordPress):

- [Wedos](https://www.wedos.cz/) – česky, dobré ceny
- [Active24](https://www.active24.cz/) – česky, populární
- [Blueboard](https://www.blueboard.cz/) – česky
- [SiteGround](https://www.siteground.com/) – mezinárodní, vysoký výkon

---

## FTP klient

Pro přenos souborů doporučujeme:

- [FileZilla](https://filezilla-project.org/) – zdarma, multiplatformní ✅ (doporučeno)
- [Cyberduck](https://cyberduck.io/) – macOS a Windows, zdarma
- [WinSCP](https://winscp.net/) – Windows, zdarma

FTP přihlašovací údaje (host, uživatel, heslo, port) najdete v **klientské zóně** vašeho hostingu.

---

## Krok 1 – Příprava WordPress na lokálním počítači

1. Stáhněte nejnovější WordPress z [wordpress.org/download](https://wordpress.org/download/) a rozbalte.
2. Do WordPress složky zkopírujte téma a plugin z tohoto repozitáře:
   ```
   web/wp-content/themes/tj-slavoj-myto/   →  wp-content/themes/tj-slavoj-myto/
   web/wp-content/plugins/slavoj-custom-fields/  →  wp-content/plugins/slavoj-custom-fields/
   ```

---

## Krok 2 – Vytvoření databáze na hostingu

1. Přihlaste se do **klientské zóny** / **cPanelu** hostingu.
2. Přejděte na **MySQL databáze** (nebo phpMyAdmin).
3. Vytvořte novou databázi, např. `slavoj_myto`.
4. Vytvořte databázového uživatele a nastavte mu silné heslo.
5. Přidejte uživatele k databázi s oprávněním **All Privileges**.
6. Poznamenejte si:
   - název databáze
   - uživatelské jméno
   - heslo
   - hostname (obvykle `localhost`)

---

## Krok 3 – Nastavení wp-config.php

1. Ve stažené složce WordPress otevřete soubor `wp-config-sample.php`.
2. Vyplňte údaje databáze:
   ```php
   define( 'DB_NAME',     'slavoj_myto' );
   define( 'DB_USER',     'vas_db_uzivatel' );
   define( 'DB_PASSWORD', 'vas_db_heslo' );
   define( 'DB_HOST',     'localhost' );
   define( 'DB_CHARSET',  'utf8mb4' );
   ```
3. Vygenerujte bezpečnostní klíče na [https://api.wordpress.org/secret-key/1.1/salt/](https://api.wordpress.org/secret-key/1.1/salt/) a vložte je do souboru.
4. Uložte soubor pod názvem **`wp-config.php`** (odstraňte příponu `-sample`).

---

## Krok 4 – Nahrání souborů přes FTP (FileZilla)

### Připojení k FTP

1. Otevřete FileZilla.
2. Zadejte do horního pruhu:
   - **Host**: ftp adresa z hostingové administrace (např. `ftp.vasedomena.cz`)
   - **Uživatelské jméno**: FTP login
   - **Heslo**: FTP heslo
   - **Port**: `21` (FTP) nebo `22` (SFTP – bezpečnější)
3. Klikněte **Rychlé připojení**.

### Nahrání WordPressu

1. V levém panelu FileZilla přejděte do složky s připraveným WordPressem na vašem počítači.
2. V pravém panelu přejděte do kořenové složky webu na hostingu – obvykle:
   - `public_html/`
   - `www/`
   - nebo složka s názvem domény
3. Označte **vše** (Ctrl+A) v levém panelu a přetáhněte do pravého panelu (nebo klikněte pravým tlačítkem → **Nahrát**).
4. Počkejte na dokončení přenosu (může trvat několik minut podle rychlosti připojení).

> ⚠️ **Tip**: Pokud hostujete web v podsložce (např. `vasedomena.cz/myto/`), nahrajte soubory do té podsložky místo do kořene.

---

## Krok 5 – Instalace WordPressu v prohlížeči

1. Otevřete v prohlížeči adresu vašeho webu, např. `https://vasedomena.cz/`.
2. WordPress rozpozná, že je potřeba dokončit instalaci, a spustí průvodce.
3. Vyplňte:
   - Název webu: `TJ Slavoj Mýto`
   - Uživatelské jméno administrátora
   - Silné heslo
   - E-mailová adresa
4. Klikněte **Nainstalovat WordPress**.
5. Přihlaste se do admin rozhraní na `https://vasedomena.cz/wp-admin/`.

---

## Krok 6 – Aktivace tématu a pluginů

1. Přejděte na **Vzhled → Témata** a aktivujte **TJ Slavoj Mýto**.
2. Přejděte na **Pluginy** a aktivujte **Slavoj Custom Fields**.
3. Přejděte na **Nastavení → Trvalé odkazy**, zvolte **Název příspěvku** a uložte.
4. Naimportujte ukázková data: **Nástroje → Slavoj nastavení → Vytvořit ukázková data**.

> **Poznámka:** Pluginy ACF a Custom Post Type UI nejsou potřeba — vlastní typy obsahu i meta pole jsou implementovány přímo v kódu tématu.

---

## Krok 7 – Nastavení SSL (HTTPS)

1. V klientské zóně hostingu aktivujte **SSL certifikát** (většina poskytovatelů nabízí Let's Encrypt zdarma).
2. V WordPress admin přejděte na **Nastavení → Obecné** a změňte URL webu na `https://...`.
3. Nainstalujte plugin **Really Simple SSL** pro automatické přesměrování HTTP → HTTPS.

---

## Aktualizace webu přes FTP (po první instalaci)

Při nasazení změn v tématu:

1. Na lokálním počítači upravte soubory tématu ve `web/wp-content/themes/tj-slavoj-myto/`.
2. Připojte se přes FileZilla k FTP.
3. Nahrajte **pouze změněné soubory** do `wp-content/themes/tj-slavoj-myto/` na hostingu — přepište stávající.

> 💡 **Doporučení**: Pro větší projekty zvažte nasazení přes Git nebo CI/CD pipeline (GitHub Actions → FTP deploy), aby se předešlo chybám při ručním přenosu.

---

## Zálohování před deploymentem

Vždy před nahráním nové verze:

- [ ] Záloha databáze (phpMyAdmin → Export → SQL)
- [ ] Záloha souborů (stáhnout `wp-content/` přes FTP na lokální disk)
- [ ] Případně použijte plugin **UpdraftPlus** pro automatické zálohy

---

## Časté problémy a jejich řešení

| Problém | Řešení |
|---------|--------|
| „Chyba při navazování spojení s databází" | Zkontrolujte údaje v `wp-config.php` |
| Bílá obrazovka (White Screen of Death) | Zapněte debug mód: `define('WP_DEBUG', true);` v `wp-config.php` |
| FTP odmítá připojení | Ověřte přihlašovací údaje, zkuste port 21 nebo 22 (SFTP) |
| Nahrávání trvá příliš dlouho | Zkomprimujte soubory do `.zip` a rozbalte přes cPanel File Manager |
| Stránky vracejí 404 | Uložte znovu Trvalé odkazy v **Nastavení → Trvalé odkazy** |
| Obrázky se nezobrazují | Zkontrolujte oprávnění složky `wp-content/uploads/` (doporučeno 755) |

---

*Vytvořeno: únor 2026*
