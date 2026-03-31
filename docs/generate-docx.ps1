# ============================================================================
# Generování DOCX dokumentace maturitní práce
# TJ Slavoj Mýto – WordPress web fotbalového klubu
#
# Šablona: reference.docx (založená na sablona_MP_orange_outline.docx)
#          Styly upraveny dle gasos-manual-jak_napsat_MP-240221.pdf:
#          - Normal: Times New Roman 12pt, zarovnání do bloku, řádkování 1,5
#          - Nadpisy: Arial (bezpatkové), tučné, max 16pt, 3 úrovně
#          - Okraje: 2,5 cm, A4
#          - Odstavce: bez odsazení, mezera 6pt
#
# Požadavky: pouze pandoc (žádný LaTeX!)
#   Windows:  choco install pandoc -y
#
# Spuštění:  pwsh generate-docx.ps1   (PowerShell 7+)
#            powershell -File generate-docx.ps1   (Windows PowerShell 5)
# ============================================================================

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

$ScriptDir  = Split-Path -Parent $MyInvocation.MyCommand.Definition
$Input      = Join-Path $ScriptDir "DOKUMENTACE.md"
$Output     = Join-Path $ScriptDir "DOKUMENTACE.docx"
$Reference  = Join-Path $ScriptDir "reference.docx"

# --- Ověření závislostí ---

if (-not (Test-Path $Input)) {
    Write-Error "Chyba: Soubor $Input neexistuje."
    exit 1
}

if (-not (Get-Command pandoc -ErrorAction SilentlyContinue)) {
    Write-Host "Chyba: pandoc neni nainstalovany." -ForegroundColor Red
    Write-Host "Instalace:  choco install pandoc -y  (admin PowerShell)"
    exit 1
}

if (-not (Test-Path $Reference)) {
    Write-Host "Chyba: reference.docx chybi!" -ForegroundColor Red
    Write-Host "Zkopiruj skolni sablonu: cp sablona_MP_orange_outline.docx docs/reference.docx"
    exit 1
}

# --- Generování DOCX ---

Write-Host "Generuji DOCX z $Input ..."
Write-Host "Sablona: $Reference"

$PandocArgs = @(
    $Input
    "-o", $Output
    # Školní šablona se styly (fonty, řádkování, okraje)
    "--reference-doc=$Reference"
    # Automatický obsah (požadavek školy: ne ručně, vygenerovaný)
    "--toc"
    "--toc-depth=3"
    # Číslování sekcí: nepoužíváme, DOKUMENTACE.md má ruční čísla (## 1 Úvod atd.)
    # "--number-sections"
    # Zvýrazňování syntaxe kódu
    "--syntax-highlighting=tango"
)

pandoc @PandocArgs

if ($LASTEXITCODE -ne 0) {
    Write-Error "Chyba: pandoc skoncil s chybou (kod $LASTEXITCODE)."
    exit $LASTEXITCODE
}

$Size = (Get-Item $Output).Length / 1KB
Write-Host ""
Write-Host "Hotovo: $Output" -ForegroundColor Green
Write-Host ("Velikost: {0:N0} KB" -f $Size)
Write-Host ""
Write-Host "Dalsi kroky ve Wordu:" -ForegroundColor Cyan
Write-Host "  1. Otevri DOKUMENTACE.docx"
Write-Host "  2. Pravym klikem na Obsah -> Aktualizovat pole -> Aktualizovat cely obsah"
Write-Host "  3. Cislovani stran (pozadavek skoly: pocitat od titulni, zobrazit az za obsahem):"
Write-Host "     a) Vloz konec sekce za Obsah (Rozlozeni -> Konce -> Dalsi stranka)"
Write-Host "     b) Zahlavi/zapati sekce 2 -> Odpojit od predchozi -> Cislo stranky -> Zacat od 1"
Write-Host "     c) V sekci 1 (titulni az obsah) smazat cisla stran"
Write-Host "  4. Zkontroluj titulni stranu (nazev prace, autor, vedouci, rok)"
Write-Host "  5. Soubor -> Exportovat -> PDF (pro odevzdani)"
