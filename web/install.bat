@echo off
:: ============================================================
:: install.bat – Instalace TJ Slavoj Mýto do XAMPP
:: ============================================================
:: Tento skript zkopiruje tema a plugin z teto slozky do
:: spravnych mist ve WordPress instalaci v XAMPP.
::
:: Pouziti:
::   1. Zkopirujte celou slozku "web" kam chcete (napr. na plochu).
::   2. Otevrete tuto slozku v Pruzkumniku Windows.
::   3. Dvakrat kliknete na install.bat.
::   4. Skript se zepta na cestu k WordPress (predvyplnena
::      C:\xampp\htdocs\fotbal_club). Potvrdte nebo zadejte
::      vlastni cestu.
:: ============================================================

setlocal EnableDelayedExpansion

:: -- Prepnout pracovni adresar na slozku tohoto skriptu ----------
:: Zdrojove cesty jsou relativni vuci teto slozce (web/).
pushd "%~dp0"

echo.
echo  ============================================================
echo   Instalator TJ Slavoj Myto – WordPress tema a plugin
echo  ============================================================
echo.

:: -- Aktualizace repozitare ------------------------------------
echo  Stahuji nejnovejsi zmeny z repozitare (git pull)...

:: Zapamatuj aktualni HEAD pred pullem
for /f %%h in ('git rev-parse HEAD 2^>nul') do set "OLD_HEAD=%%h"

git pull
if errorlevel 1 (
    echo  [UPOZORNENI] git pull selhal. Pokracuji s aktualnimi soubory.
) else (
    :: Zjisti novy HEAD
    for /f %%h in ('git rev-parse HEAD 2^>nul') do set "NEW_HEAD=%%h"

    :: Pokud se HEAD zmenil, over jestli byl aktualizovan install.bat
    if defined OLD_HEAD if defined NEW_HEAD (
        if not "!OLD_HEAD!"=="!NEW_HEAD!" (
            git diff --name-only "!OLD_HEAD!" "!NEW_HEAD!" 2>nul | findstr /e /i /c:"install.bat" >nul
            if not errorlevel 1 set "SELF_UPDATED=1"
        )
    )
)
echo.

:: Pokud byl aktualizovan install.bat, spustit novou verzi a ukoncit stary proces
if defined SELF_UPDATED (
    echo  Skript install.bat byl aktualizovan. Spoustim novou verzi...
    echo.
    start "" "%~f0"
    goto :end
)

:: -- Zdrojove slozky (relativni cesta od tohoto skriptu) ---------
set "SRC_THEME=wp-content\themes\tj-slavoj-myto"
set "SRC_PLUGIN=wp-content\plugins\slavoj-custom-fields"

:: -- Cil – absolutni cesta k WordPress instalaci v XAMPP ---------
set "DEFAULT_WP=C:\xampp\htdocs\fotbal_club"
set /p "WP_DIR=Zadejte cestu ke WordPress instalaci [%DEFAULT_WP%]: "
if "!WP_DIR!"=="" set "WP_DIR=%DEFAULT_WP%"

echo.
echo  Cilovy adresar: !WP_DIR!
echo.

:: -- Overit, ze cilovy adresar existuje --------------------------
if not exist "!WP_DIR!\wp-content\" (
    echo  [CHYBA] Slozka "!WP_DIR!\wp-content" nebyla nalezena.
    echo          Zkontrolujte, ze WordPress je nainstalovany na zadane
    echo          ceste a ze slozka wp-content existuje.
    echo.
    pause
    popd
    exit /b 1
)

:: -- MySQL nastaveni ---------------------------------------------
set "DEFAULT_DB=slavoj_myto"
set "DEFAULT_MYSQL=C:\xampp\mysql\bin\mysql.exe"
set /p "DB_NAME=Zadejte nazev databaze [%DEFAULT_DB%]: "
if "!DB_NAME!"=="" set "DB_NAME=%DEFAULT_DB%"

:: -- Tema --------------------------------------------------------
set "DEST_THEME=!WP_DIR!\wp-content\themes\tj-slavoj-myto"

echo  [1/3] Instalace tematu...

if not exist "!SRC_THEME!\" (
    echo  [CHYBA] Zdrojova slozka tematu nebyla nalezena: !SRC_THEME!
    echo          Zkontrolujte, ze spoustite skript ze slozky "web" a ze
    echo          soubory byly stazeny pomoci "git pull".
    pause
    popd
    exit /b 1
)

if exist "!DEST_THEME!\" (
    echo       Mazani stare verze tematu: !DEST_THEME!
    rd /s /q "!DEST_THEME!"
    if errorlevel 1 (
        echo  [CHYBA] Nepodarilo se smazat starou verzi tematu.
        echo          Zkuste spustit skript jako spravce.
        pause
        popd
        exit /b 1
    )
)

xcopy /e /i /q /y "!SRC_THEME!" "!DEST_THEME!" >nul
if errorlevel 1 (
    echo  [CHYBA] Kopirovani tematu selhalo.
    pause
    popd
    exit /b 1
)
echo       Tema uspesne nainstalovano.

:: -- Plugin ------------------------------------------------------
set "DEST_PLUGIN=!WP_DIR!\wp-content\plugins\slavoj-custom-fields"

echo  [2/3] Instalace pluginu...

if not exist "!SRC_PLUGIN!\" (
    echo  [CHYBA] Zdrojova slozka pluginu nebyla nalezena: !SRC_PLUGIN!
    echo          Zkontrolujte, ze spoustite skript ze slozky "web" a ze
    echo          soubory byly stazeny pomoci "git pull".
    pause
    popd
    exit /b 1
)

if exist "!DEST_PLUGIN!\" (
    echo       Mazani stare verze pluginu: !DEST_PLUGIN!
    rd /s /q "!DEST_PLUGIN!"
    if errorlevel 1 (
        echo  [CHYBA] Nepodarilo se smazat starou verzi pluginu.
        echo          Zkuste spustit skript jako spravce.
        pause
        popd
        exit /b 1
    )
)

xcopy /e /i /q /y "!SRC_PLUGIN!" "!DEST_PLUGIN!" >nul
if errorlevel 1 (
    echo  [CHYBA] Kopirovani pluginu selhalo.
    pause
    popd
    exit /b 1
)
echo       Plugin uspesne nainstalovan.

:: -- Taxonomy termy v DB (pres php.exe – zachova UTF-8) ----------
echo  [3/3] Nastaveni taxonomy termu v databazi...

set "PHP_EXE=C:\xampp\php\php.exe"
set "SEED_SCRIPT=!WP_DIR!\slavoj-seed-terms.php"

if not exist "!PHP_EXE!" (
    echo  [PRESKOCENO] php.exe nenalezen na !PHP_EXE!.
    echo               Taxonomy termy vloz rucne pres WP Admin.
    goto :db_skip
)

:: Zapis docasny PHP seed skript do WP adresare
(
echo ^<?php
echo define^('ABSPATH', __DIR__ . '/'^);
echo define^('WPINC', 'wp-includes'^);
echo require_once __DIR__ . '/wp-load.php';
echo $terms = array^(
echo     'kategorie-tymu' =^> array^(
echo         array^('muzi-a',           'Mu\u017ei A'^),
echo         array^('muzi-b',           'Mu\u017ei B'^),
echo         array^('dorost',           'Dorost'^),
echo         array^('starsi-zaci',      'Star\u0161\u00ed \u017e\u00e1ci'^),
echo         array^('mladsi-zaci',      'Mlad\u0161\u00ed \u017e\u00e1ci'^),
echo         array^('starsi-pripravka', 'Star\u0161\u00ed p\u0159\u00edpravka'^),
echo         array^('mladsi-pripravka', 'Mlad\u0161\u00ed p\u0159\u00edpravka'^),
echo         array^('mini-pripravka',   'Minip\u0159\u00edpravka'^),
echo         array^('stara-garda',      'Star\u00e1 garda'^),
echo     ^),
echo     'stav-zapasu' =^> array^(
echo         array^('nadchazejici', 'Nach\u00e1zej\u00edc\u00ed'^),
echo         array^('odehrany',     'Odehr\u00e1n\u00fd'^),
echo         array^('zruseny',      'Zru\u0161en\u00fd'^),
echo     ^),
echo     'sezona' =^> array^(
echo         array^('2024-2025', '2024/2025'^),
echo         array^('2025-2026', '2025/2026'^),
echo     ^),
echo ^);
echo foreach ^($terms as $tax =^> $list^) {
echo     foreach ^($list as $t^) {
echo         list^($slug, $name^) = $t;
echo         $name = json_decode^('"' . $name . '"'^);
echo         if ^(!term_exists^($slug, $tax^)^) {
echo             wp_insert_term^($name, $tax, array^('slug' =^> $slug^)^);
echo             echo "+ $name\n";
echo         } else { echo "= $name\n"; }
echo     }
echo }
) > "!SEED_SCRIPT!"

"!PHP_EXE!" "!SEED_SCRIPT!"
if errorlevel 1 (
    echo  [UPOZORNENI] PHP seed skript selhal.
) else (
    echo       Taxonomy termy OK.
)

del "!SEED_SCRIPT!" >nul 2>&1

:db_skip

:: -- Hotovo ------------------------------------------------------
echo.
echo  ============================================================
echo   Instalace dokoncena!
echo  ============================================================
echo.
echo   Dalsi kroky:
echo   1. Spustte XAMPP a overujte ze Apache a MySQL bezi.
echo   2. Otevrete http://localhost/fotbal_club/wp-admin/
echo   3. Aktivujte tema:  Vzhled ^> Temata ^> TJ Slavoj Myto
echo   4. Aktivujte plugin: Pluginy ^> Slavoj Custom Fields
echo.
pause
:end
popd
endlocal
