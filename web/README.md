# web/ – Nový web TJ Slavoj Mýto

Tato složka obsahuje nový web fotbalového klubu TJ Slavoj Mýto, vzniklý portováním stávajícího WordPress tématu ze složky `original/`.

## Cíl

Vytvořit plně funkční WordPress téma, které nahradí původní nedokončené řešení. Obsah bude spravovatelný přes WordPress admin rozhraní bez nutnosti editovat kód.

## Struktura

```
web/
├── wp-content/         # WordPress wp-content – kopírujte do WP instalace
│   ├── themes/         # Téma tj-slavoj-myto
│   └── plugins/        # Plugin slavoj-custom-fields
├── theme/              # (starý adresář – pouze pro vývoj, nekopírovat do WP)
├── plugins/            # (starý adresář – pouze pro vývoj, nekopírovat do WP)
├── assets/             # (starý adresář – pouze pro vývoj, nekopírovat do WP)
├── install.bat         # Instalační skript pro Windows / XAMPP
└── README.md
```

## Instalace pomocí install.bat (doporučeno)

Skript automaticky zkopíruje téma a plugin na správná místa a odstraní
případné starší verze, aby nevznikaly konflikty nebo zbytky souborů.

1. Ujistěte se, že WordPress je nainstalovaný v `C:\xampp\htdocs\fotbal_club`
   (nebo na jiné cestě).
2. Poklepejte na **`install.bat`** v této složce.
3. Potvrďte cestu k WordPress instalaci (nebo zadejte vlastní) a stiskněte Enter.
4. Skript nainstaluje téma a plugin; na konci se zobrazí pokyny k aktivaci.

> **Poznámka:** Pokud se zobrazí chyba přístupu, spusťte skript jako správce
> (pravý klik → *Spustit jako správce*).

## Jak začít

1. Přečtěte si `docs/01-uvod.md` pro přehled projektu.
2. Postupujte podle fází popsaných v `docs/`.
3. Téma a plugin nainstalujte do lokálního WordPressu pomocí skriptu `install.bat` (viz níže).

## Stav

- [ ] Fáze 1 – Analýza a příprava
- [ ] Fáze 2 – Datová struktura
- [ ] Fáze 3 – Implementace šablon
- [ ] Fáze 4 – Frontend funkcionalita
- [ ] Fáze 5 – Admin rozhraní
- [ ] Fáze 6 – Migrace dat
- [ ] Fáze 7 – Optimalizace a SEO
- [ ] Fáze 8 – Testování
- [ ] Fáze 9 – Launch
- [ ] Fáze 10 – Školení a údržba
