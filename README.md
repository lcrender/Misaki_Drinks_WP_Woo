# MisakiDrinks - WordPress 6.9.4 en Docker

Este proyecto ejecuta WordPress 6.9.4 con MySQL 8 usando Docker Compose.

## Requisitos

- Docker Desktop (o Docker Engine + plugin Compose)
- Docker Compose v2 (`docker compose`)

## Configuracion inicial

1. Copia variables de entorno:

   ```bash
   cp .env.example .env
   ```

2. (Opcional) Ajusta credenciales y puerto en `.env`.

## Levantar entorno

```bash
docker compose up -d
```

Acceso local:

- WordPress: [http://localhost:8080](http://localhost:8080) (o el puerto definido en `WORDPRESS_PORT`)
- Base de datos host interno: `db:3306`

## Comandos WP-CLI (WooCommerce)

La imagen `wordpress` no incluye el binario `wp`, por eso este proyecto usa el servicio `wpcli`.

- Instalar y activar WooCommerce:

  ```bash
  docker compose run --rm wpcli plugin install woocommerce --activate
  ```

- Listar plugins instalados:

  ```bash
  docker compose run --rm wpcli plugin list
  ```

## Producción (Traefik)

Ver [DEPLOY.md](DEPLOY.md) para desplegar en `/opt/misakidrinks` con `docker-compose.prod.yml`.

```bash
cp .env.production.example .env   # en el servidor
docker compose -f docker-compose.prod.yml up -d
```

## Detener entorno

```bash
docker compose down
```

Para detener y eliminar volumen de base de datos:

```bash
docker compose down -v
```

## Verificacion tecnica

- Ver estado de servicios:

  ```bash
  docker compose ps
  ```

- Revisar logs:

  ```bash
  docker compose logs -f
  ```

- Confirmar persistencia:
  1. Crea una pagina o cambia configuraciones en WordPress.
  2. Ejecuta `docker compose down`.
  3. Ejecuta `docker compose up -d`.
  4. Verifica que los cambios sigan presentes.

## Base preparada para WooCommerce

Se agrego una base de tema clasico en `wp-content/themes/misaki-woo` con:

- `style.css`
- `functions.php`
- `index.php`
- `woocommerce/` para overrides

En la siguiente fase se implementaran templates y estilos especificos para la tienda.
