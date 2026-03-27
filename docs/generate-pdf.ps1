# ============================================================================
# Generování PDF dokumentace maturitní práce
# TJ Slavoj Mýto – WordPress web fotbalového klubu
#
# Požadavky: pandoc, LaTeX engine (MiKTeX nebo TeX Live)
#   Windows:  https://pandoc.org/installing.html + https://miktex.org/download
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

if (-not (Test-Path $Input)) {
    Write-Error "Chyba: Soubor $Input neexistuje."
    exit 1
}

if (-not (Get-Command pandoc -ErrorAction SilentlyContinue)) {
    Write-Host "Chyba: pandoc není nainstalovaný." -ForegroundColor Red
    Write-Host "Instalace:"
    Write-Host "  Windows: https://pandoc.org/installing.html + https://miktex.org/download"
    Write-Host "  macOS:   brew install pandoc && brew install --cask mactex"
    exit 1
}

Write-Host "Generuji PDF z $Input ..."

pandoc $Input `
    -o $Output `
    --pdf-engine=xelatex `
    -V geometry:margin=2.5cm `
    -V fontsize=12pt `
    -V lang=cs `
    -V mainfont="DejaVu Serif" `
    -V monofont="DejaVu Sans Mono" `
    --toc `
    --toc-depth=3 `
    -V toc-title="Obsah" `
    --highlight-style=tango `
    -V colorlinks=true `
    -V linkcolor=black `
    -V urlcolor=blue `
    --number-sections

if ($LASTEXITCODE -ne 0) {
    Write-Error "Chyba: pandoc skončil s chybou (kód $LASTEXITCODE)."
    exit $LASTEXITCODE
}

$Size = (Get-Item $Output).Length / 1KB
Write-Host "Hotovo: $Output"
Write-Host ("Velikost: {0:N0} KB" -f $Size)
