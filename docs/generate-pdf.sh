#!/bin/bash
# ============================================================================
# Generování PDF dokumentace maturitní práce
# TJ Slavoj Mýto – WordPress web fotbalového klubu
#
# Požadavky: pandoc, texlive (nebo jiný LaTeX engine)
#   Ubuntu/Debian: sudo apt install pandoc texlive-latex-recommended texlive-lang-czechslovak texlive-latex-extra
#   macOS (Homebrew): brew install pandoc && brew install --cask mactex
#   Windows: https://pandoc.org/installing.html + MiKTeX
# ============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
INPUT="$SCRIPT_DIR/DOKUMENTACE.md"
OUTPUT="$SCRIPT_DIR/DOKUMENTACE.pdf"

if [ ! -f "$INPUT" ]; then
    echo "Chyba: Soubor $INPUT neexistuje."
    exit 1
fi

if ! command -v pandoc &> /dev/null; then
    echo "Chyba: pandoc není nainstalován."
    echo "Instalace:"
    echo "  Ubuntu/Debian: sudo apt install pandoc texlive-latex-recommended texlive-lang-czechslovak texlive-latex-extra"
    echo "  macOS:         brew install pandoc && brew install --cask mactex"
    echo "  Windows:       https://pandoc.org/installing.html"
    exit 1
fi

echo "Generuji PDF z $INPUT ..."

pandoc "$INPUT" \
    -o "$OUTPUT" \
    --pdf-engine=xelatex \
    -V geometry:margin=2.5cm \
    -V fontsize=12pt \
    -V lang=cs \
    -V mainfont="DejaVu Serif" \
    -V monofont="DejaVu Sans Mono" \
    --toc \
    --toc-depth=3 \
    -V toc-title="Obsah" \
    --highlight-style=tango \
    -V colorlinks=true \
    -V linkcolor=black \
    -V urlcolor=blue \
    --number-sections

echo "Hotovo: $OUTPUT"
echo "Velikost: $(du -h "$OUTPUT" | cut -f1)"
