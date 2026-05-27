<?php
/**
 * Valores por defecto de la página Services (fallback si no hay meta en el admin).
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Imagen de fondo de página (uploads / Media Library).
 */
function misaki_woo_get_services_background_filename(): string
{
    return 'services-bg.jpg';
}

/**
 * Textura del panel oscuro.
 */
function misaki_woo_get_services_panel_background_filename(): string
{
    return 'bg-black.jpg';
}
