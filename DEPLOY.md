# Despliegue en producción — Misaki Drinks

Stack: WordPress + WooCommerce + MySQL 8 en Docker, detrás de **Traefik** existente.

| Entorno | Ruta / comando |
|---------|----------------|
| Servidor | `/opt/misakidrinks` |
| Dominio | `https://misakidrinks.com` (www → redirect 301) |
| Compose prod | `docker compose -f docker-compose.prod.yml` |
| Red Traefik | `root_default` (externa) |
| TLS | Let's Encrypt (`mytlschallenge`) |

## Requisitos en el servidor

- Docker + Docker Compose v2
- Traefik ya corriendo con red `root_default`
- DNS en Cloudflare (proxy naranja): registros **A** de `misakidrinks.com` y `www` → IP del VPS
- Cloudflare **SSL/TLS → Full** (o Full strict)

## 1. Preparar el servidor

```bash
sudo mkdir -p /opt/misakidrinks
sudo chown "$USER":"$USER" /opt/misakidrinks
cd /opt/misakidrinks

git clone https://github.com/lcrender/Misaki_Drinks_WP_Woo.git .
# o, en despliegues posteriores:
# git pull
```

## 2. Variables de entorno

```bash
cp .env.production.example .env
nano .env   # completar contraseñas y salts
```

Variables obligatorias:

- `WORDPRESS_DB_*` y `MYSQL_ROOT_PASSWORD` — contraseñas fuertes y únicas
- `WP_HOME` / `WP_SITEURL` — `https://misakidrinks.com`
- Salts de WordPress — generar en https://api.wordpress.org/secret-key/1.1/salt/

## 3. Exportar datos desde local

En tu máquina de desarrollo (con Docker levantado):

```bash
chmod +x scripts/export-local-db.sh
./scripts/export-local-db.sh
# Genera: backups/misakidrinks-local-YYYYMMDD-HHMMSS.sql
```

Los **uploads** van en el repo (`wp-content/uploads/`). Tras el próximo commit/push, llegarán con `git pull`. Si prefieres copiarlos aparte:

```bash
rsync -avz wp-content/uploads/ usuario@SERVIDOR:/opt/misakidrinks/wp-content/uploads/
```

## 4. Subir el dump al servidor

```bash
scp backups/misakidrinks-local-*.sql usuario@SERVIDOR:/opt/misakidrinks/backups/
```

## 5. Levantar producción

```bash
cd /opt/misakidrinks
docker compose -f docker-compose.prod.yml up -d
docker compose -f docker-compose.prod.yml ps
```

No se publican puertos al host; Traefik enruta por hostname.

## 6. Importar base de datos

```bash
chmod +x scripts/import-db-prod.sh
./scripts/import-db-prod.sh backups/misakidrinks-local-YYYYMMDD-HHMMSS.sql
```

El script importa el SQL, reemplaza `localhost:8080` → `misakidrinks.com` y regenera permalinks.

## 7. Verificación

- https://misakidrinks.com — home y diseño
- https://www.misakidrinks.com — debe redirigir al apex
- `/wp-admin` — acceso admin (mismas credenciales que en local tras importar DB)
- Imágenes y media cargando desde `/wp-content/uploads/`

## Comandos útiles (producción)

```bash
# Logs
docker compose -f docker-compose.prod.yml logs -f wordpress

# WP-CLI
docker compose -f docker-compose.prod.yml run --rm wpcli wp plugin list
docker compose -f docker-compose.prod.yml run --rm wpcli wp cache flush

# Reiniciar solo WordPress
docker compose -f docker-compose.prod.yml restart wordpress
```

## Actualizaciones de código

```bash
cd /opt/misakidrinks
git pull
docker compose -f docker-compose.prod.yml up -d
```

## Notas

- **WooCommerce / emails / pagos**: configurar después desde el panel de admin.
- **Coming soon**: desactivado en local; no debería bloquear la vista en producción.
- Desarrollo local sigue usando `docker-compose.yml` y `.env.example` (puerto `8080`).
