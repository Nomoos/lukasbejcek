# web/ – Web TJ Slavoj Mýto

Tato složka obsahuje WordPress téma a plugin pro web fotbalového klubu TJ Slavoj Mýto.

## Struktura

```
web/
├── wp-content/
│   ├── themes/tj-slavoj-myto/   # WordPress téma (šablony, CSS, obrázky)
│   └── plugins/slavoj-custom-fields/  # Plugin (admin sloupce, filtry, seed data)
├── plugins/slavoj-custom-fields/ # Synchronizovaná kopie pluginu
├── install.bat                   # Instalační skript pro XAMPP
└── README.md
```

## Instalace

### Pomocí install.bat (doporučeno)

1. WordPress musí být nainstalovaný v `C:\xampp\htdocs\fotbal_club` (nebo na jiné cestě).
2. Poklepejte na **`install.bat`**.
3. Potvrďte cestu k WordPress instalaci a stiskněte Enter.
4. Skript stáhne nejnovější změny z Gitu, smaže staré verze a nakopíruje aktuální téma a plugin.

### Ruční instalace

Zkopírujte dvě složky do WordPress instalace:

```
wp-content/themes/tj-slavoj-myto/       →  <wordpress>/wp-content/themes/tj-slavoj-myto/
wp-content/plugins/slavoj-custom-fields/ →  <wordpress>/wp-content/plugins/slavoj-custom-fields/
```

### Po instalaci

1. Aktivujte téma: Vzhled → Témata → TJ Slavoj Mýto
2. Aktivujte plugin: Pluginy → Slavoj Custom Fields
3. Nastavte permalinky: Nastavení → Trvalé odkazy → Název příspěvku
4. Naimportujte data: Nástroje → Slavoj nastavení → Vytvořit ukázková data

Podrobný návod: [docs/04-lokalni-instalace.md](../docs/04-lokalni-instalace.md)
