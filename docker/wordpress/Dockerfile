# WordPress + PHP SOAP (requerido por woocommerce-mrw-carrier para etiquetas).
ARG WORDPRESS_BASE=wordpress:6-apache
FROM ${WORDPRESS_BASE}

RUN apt-get update \
    && apt-get install -y --no-install-recommends libxml2-dev \
    && docker-php-ext-install soap \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
