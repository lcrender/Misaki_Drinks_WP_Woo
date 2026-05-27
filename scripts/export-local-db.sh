#!/usr/bin/env bash
# Exporta la base de datos local a un archivo SQL en ./backups/
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

mkdir -p backups
OUTPUT="backups/misakidrinks-local-$(date +%Y%m%d-%H%M%S).sql"

echo "Exportando base de datos local → ${OUTPUT}"
docker compose --profile tools run --rm wpcli wp db export - > "$OUTPUT"
echo "Listo: ${OUTPUT}"
