#!/usr/bin/env bash
# Importa un dump SQL en producción y actualiza URLs a misakidrinks.com
# Uso en el servidor (/opt/misakidrinks):
#   ./scripts/import-db-prod.sh backups/misakidrinks-local-YYYYMMDD-HHMMSS.sql
set -euo pipefail

if [[ $# -lt 1 ]]; then
  echo "Uso: $0 <archivo.sql>" >&2
  exit 1
fi

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"
SQL_FILE="$1"

if [[ ! -f "$SQL_FILE" ]]; then
  echo "No existe el archivo: ${SQL_FILE}" >&2
  exit 1
fi

COMPOSE="docker compose -f docker-compose.prod.yml --profile tools"

echo "Importando ${SQL_FILE}..."
$COMPOSE run --rm -T wpcli wp db import - < "$SQL_FILE"

echo "Reemplazando URLs locales..."
$COMPOSE run --rm wpcli wp search-replace 'http://localhost:8080' 'https://misakidrinks.com' --all-tables --skip-columns=guid
$COMPOSE run --rm wpcli wp search-replace 'https://localhost:8080' 'https://misakidrinks.com' --all-tables --skip-columns=guid

echo "Actualizando home y siteurl..."
$COMPOSE run --rm wpcli wp option update home 'https://misakidrinks.com'
$COMPOSE run --rm wpcli wp option update siteurl 'https://misakidrinks.com'

echo "Regenerando permalinks..."
$COMPOSE run --rm wpcli wp rewrite flush --hard

echo "Importación completada."
