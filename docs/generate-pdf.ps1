# ============================================================================
# Generování PDF dokumentace maturitní práce
# TJ Slavoj Mýto – WordPress web fotbalového klubu
#
# Požadavky: pandoc, LaTeX engine (MiKTeX nebo TeX Live)
#   Windows:  choco install pandoc miktex -y
#   macOS:    brew install pandoc && brew install --cask mactex
#
# Spuštění:  pwsh generate-pdf.ps1   (PowerShell 7+)
#            powershell -File generate-pdf.ps1   (Windows PowerShell 5)
# ============================================================================

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
$Input     = Join-Path $ScriptDir "DOKUMENTACE.md"
$Output    = Join-Path $ScriptDir "DOKUMENTACE.pdf"
$Header    = Join-Path $ScriptDir "header.tex"

# --- Ověření závislostí ---

if (-not (Test-Path $Input)) {
    Write-Error "Chyba: Soubor $Input neexistuje."
    exit 1
}

# Přidat MiKTeX do PATH pokud chybí xelatex
$MiKTeXPath = "C:\Program Files\MiKTeX\miktex\bin\x64"
if (-not (Get-Command xelatex -ErrorAction SilentlyContinue)) {
    if (Test-Path $MiKTeXPath) {
        $env:Path += ";$MiKTeXPath"
        Write-Host "MiKTeX přidán do PATH: $MiKTeXPath"
    }
}

if (-not (Get-Command pandoc -ErrorAction SilentlyContinue)) {
    Write-Host "Chyba: pandoc není nainstalovaný." -ForegroundColor Red
    Write-Host "Instalace:  choco install pandoc miktex -y  (admin PowerShell)"
    exit 1
}

if (-not (Get-Command xelatex -ErrorAction SilentlyContinue)) {
    Write-Host "Chyba: xelatex není nainstalovaný." -ForegroundColor Red
    Write-Host "Instalace:  choco install miktex -y  (admin PowerShell)"
    exit 1
}

# --- Generování PDF ---

Write-Host "Generuji PDF z $Input ..."

$PandocArgs = @(
    $Input
    "-o", $Output
    "--pdf-engine=xelatex"
    # Formát stránky
    "-V", "geometry:a4paper"
    "-V", "geometry:margin=2.5cm"
    "-V", "fontsize=12pt"
    # Český jazyk a hyphenace
    "-V", "lang=cs"
    "-V", "toc-title=Obsah"
    # Fonty (Windows – Cambria/Calibri/Consolas dostupné všude)
    "-V", "mainfont=Cambria"
    "-V", "sansfont=Calibri"
    "-V", "monofont=Consolas"
    # Řádkování
    "-V", "linestretch=1.25"
    # Obsah
    "--toc"
    "--toc-depth=3"
    "--number-sections"
    # Zvýrazňování syntaxe
    "--syntax-highlighting=tango"
    # Barvy odkazů
    "-V", "colorlinks=true"
    "-V", "linkcolor=black"
    "-V", "toccolor=black"
    "-V", "urlcolor=blue"
)

# Přidat header.tex pokud existuje (řeší tabulky, uvozovky)
if (Test-Path $Header) {
    $PandocArgs += "--include-in-header=$Header"
}

pandoc @PandocArgs

if ($LASTEXITCODE -ne 0) {
    Write-Error "Chyba: pandoc skončil s chybou (kód $LASTEXITCODE)."
    exit $LASTEXITCODE
}

$Size = (Get-Item $Output).Length / 1KB
Write-Host "Hotovo: $Output" -ForegroundColor Green
Write-Host ("Velikost: {0:N0} KB" -f $Size)
