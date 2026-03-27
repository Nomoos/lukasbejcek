# Přístupové údaje k testovacím účtům

Tento dokument obsahuje přístupové údaje pro testování webu TJ Slavoj Mýto.

> **Upozornění:** Tyto údaje jsou určeny výhradně pro lokální testování.
> Při nasazení na produkční server je nutné zvolit silná a unikátní hesla.

---

## WordPress administrace

**URL:** `http://localhost/fotbal_club/wp-admin/`

| Role | Uživatelské jméno | Heslo | Oprávnění |
|------|-------------------|-------|-----------|
| Administrátor | `admin` | `admin123` | Plný přístup k celému WordPressu |
| Správce obsahu | `redaktor` | `redaktor123` | Editace CPT, nahrávání médií, bez nastavení WP a pluginů |

### Popis rolí

**Administrátor** má plný přístup ke všem funkcím WordPressu včetně nastavení, pluginů, témat a všech typů obsahu.

**Správce obsahu** může:
- Přidávat, editovat a mazat zápasy, týmy, hráče, galerie, sponzory a kontakty
- Nahrávat obrázky a média
- Spravovat kategorie a taxonomie

Správce obsahu **nemůže**:
- Měnit nastavení WordPressu
- Instalovat nebo mazat pluginy a témata
- Spravovat uživatelské účty

---

## Databáze (MySQL – XAMPP)

| Parametr | Hodnota |
|----------|---------|
| Server | `localhost` |
| Port | `3306` |
| Uživatel | `root` |
| Heslo | *(prázdné – výchozí XAMPP)* |
| Databáze | `slavoj_myto` |
| phpMyAdmin | `http://localhost/phpmyadmin/` |

---

## wp-env (alternativní prostředí – Docker)

Pokud je projekt spuštěn přes wp-env:

| Parametr | Hodnota |
|----------|---------|
| URL webu | `http://localhost:8888` |
| URL administrace | `http://localhost:8888/wp-admin/` |
| Uživatel | `admin` |
| Heslo | `password` |

> **Poznámka:** Výchozí wp-env účet `admin` / `password` je vytvořen automaticky při spuštění `npx @wordpress/env start`.

---

## FTP přístup (produkční hosting)

Přístupové údaje k produkčnímu hostingu nejsou součástí tohoto repozitáře. Kontaktujte správce webu pro získání FTP přístupu.

---

*Aktualizováno: březen 2026*
