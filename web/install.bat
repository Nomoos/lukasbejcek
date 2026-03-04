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
git pull
if errorlevel 1 (
    echo  [UPOZORNENI] git pull selhal. Pokracuji s aktualnimi soubory.
)
echo.

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

:: -- Tema --------------------------------------------------------
set "DEST_THEME=!WP_DIR!\wp-content\themes\tj-slavoj-myto"

echo  [1/2] Instalace tematu...

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

echo  [2/2] Instalace pluginu...

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
popd
endlocal
