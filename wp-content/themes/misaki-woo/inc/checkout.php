<?php
/**
 * Checkout — plantilla, layout y ajustes.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Usa checkout clásico (shortcode) para aplicar plantillas del tema.
 */
function misaki_woo_setup_checkout_classic(): void
{
    if (get_option('misaki_woo_checkout_classic_content')) {
        return;
    }

    $checkout_id = function_exists('wc_get_page_id') ? wc_get_page_id('checkout') : 0;

    if ($checkout_id > 0) {
        wp_update_post([
            'ID'           => $checkout_id,
            'post_content' => '<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->',
        ]);
    }

    update_option('misaki_woo_checkout_classic_content', '1');
}

add_action('after_setup_theme', 'misaki_woo_setup_checkout_classic', 25);

/**
 * Fuerza la plantilla de checkout.
 */
function misaki_woo_checkout_page_template(string $template): string
{
    if (!function_exists('is_checkout') || !is_checkout()) {
        return $template;
    }

    $checkout_template = locate_template('page-checkout.php');

    return $checkout_template ?: $template;
}

add_filter('template_include', 'misaki_woo_checkout_page_template', 20);

/**
 * Oculta el título por defecto de WooCommerce en checkout.
 */
function misaki_woo_checkout_show_page_title(bool $show): bool
{
    if (function_exists('is_checkout') && is_checkout()) {
        return false;
    }

    return $show;
}

add_filter('woocommerce_show_page_title', 'misaki_woo_checkout_show_page_title');

/**
 * Abre el layout principal del checkout.
 */
function misaki_woo_checkout_layout_open(): void
{
    if (!function_exists('is_checkout') || !is_checkout() || is_order_received_page()) {
        return;
    }

    echo '<div class="misaki-checkout__grid">';
    echo '<div class="misaki-checkout__main misaki-checkout__panel">';
}

add_action('woocommerce_checkout_before_customer_details', 'misaki_woo_checkout_layout_open', 5);

/**
 * Cierra datos del cliente y abre resumen del pedido.
 */
function misaki_woo_checkout_sidebar_open(): void
{
    if (!function_exists('is_checkout') || !is_checkout() || is_order_received_page()) {
        return;
    }

    echo '</div><aside class="misaki-checkout__sidebar misaki-checkout__panel">';
}

add_action('woocommerce_checkout_after_customer_details', 'misaki_woo_checkout_sidebar_open', 50);

/**
 * Cierra el layout del checkout.
 */
function misaki_woo_checkout_layout_close(): void
{
    if (!function_exists('is_checkout') || !is_checkout() || is_order_received_page()) {
        return;
    }

    echo '</aside></div>';
}

add_action('woocommerce_checkout_after_order_review', 'misaki_woo_checkout_layout_close', 50);

/**
 * Opciones de tipo de documento.
 *
 * @return array<string, string>
 */
function misaki_woo_get_document_type_options(): array
{
    return [
        'dni'      => __('National ID / DNI', 'misaki-woo'),
        'nie'      => __('Foreign ID / NIE', 'misaki-woo'),
        'passport' => __('Passport', 'misaki-woo'),
        'cif'      => __('Business Tax ID / CIF', 'misaki-woo'),
        'other'    => __('Other', 'misaki-woo'),
    ];
}

/**
 * Etiqueta legible del tipo de documento.
 */
function misaki_woo_get_document_type_label(string $type): string
{
    $options = misaki_woo_get_document_type_options();

    return $options[$type] ?? $type;
}

/**
 * Añade tipo y número de documento al checkout.
 *
 * @param array<string, array<string, mixed>> $fields
 * @return array<string, array<string, mixed>>
 */
function misaki_woo_checkout_document_fields(array $fields): array
{
    $document_types = ['' => __('Select…', 'misaki-woo')] + misaki_woo_get_document_type_options();

    $fields['billing']['billing_document_type'] = [
        'type'     => 'select',
        'label'    => __('Document type', 'misaki-woo'),
        'required' => true,
        'class'    => ['form-row-first'],
        'priority' => 25,
        'options'  => $document_types,
    ];

    $fields['billing']['billing_document_number'] = [
        'type'        => 'text',
        'label'       => __('Document number', 'misaki-woo'),
        'required'    => true,
        'class'       => ['form-row-last'],
        'priority'    => 26,
        'placeholder' => __('e.g. 12345678A', 'misaki-woo'),
    ];

    return $fields;
}

add_filter('woocommerce_checkout_fields', 'misaki_woo_checkout_document_fields');

/**
 * Sanitiza el número de documento.
 */
function misaki_woo_sanitize_document_number(string $value): string
{
    return preg_replace('/\s+/', '', sanitize_text_field($value));
}

add_filter('woocommerce_process_checkout_field_billing_document_number', 'misaki_woo_sanitize_document_number');

/**
 * Obtiene los datos de documento de un pedido.
 *
 * @return array{type: string, number: string}
 */
function misaki_woo_get_order_document_data(WC_Order $order): array
{
    return [
        'type'   => (string) $order->get_meta('_billing_document_type'),
        'number' => (string) $order->get_meta('_billing_document_number'),
    ];
}

/**
 * Muestra documento en el admin del pedido.
 */
function misaki_woo_checkout_admin_order_document(WC_Order $order): void
{
    $document = misaki_woo_get_order_document_data($order);

    if (!$document['type'] && !$document['number']) {
        return;
    }

    echo '<p>';
    if ($document['type']) {
        echo '<strong>' . esc_html__('Document type', 'misaki-woo') . ':</strong> ';
        echo esc_html(misaki_woo_get_document_type_label($document['type']));
    }
    if ($document['number']) {
        if ($document['type']) {
            echo '<br>';
        }
        echo '<strong>' . esc_html__('Document number', 'misaki-woo') . ':</strong> ';
        echo esc_html($document['number']);
    }
    echo '</p>';
}

add_action('woocommerce_admin_order_data_after_billing_address', 'misaki_woo_checkout_admin_order_document');

/**
 * Columna Document en el listado de pedidos (admin).
 *
 * @param array<string, string> $columns
 * @return array<string, string>
 */
function misaki_woo_checkout_orders_list_columns(array $columns): array
{
    $updated = [];

    foreach ($columns as $key => $label) {
        $updated[$key] = $label;

        if ($key === 'billing_address') {
            $updated['billing_document'] = __('Document', 'misaki-woo');
        }
    }

    return $updated;
}

add_filter('woocommerce_shop_order_list_table_columns', 'misaki_woo_checkout_orders_list_columns');

/**
 * Renderiza la columna Document en el listado de pedidos.
 */
function misaki_woo_checkout_orders_list_column(string $column, WC_Order $order): void
{
    if ($column !== 'billing_document') {
        return;
    }

    $document = misaki_woo_get_order_document_data($order);

    if (!$document['type'] && !$document['number']) {
        echo '<span class="misaki-order-document misaki-order-document--empty">&mdash;</span>';
        return;
    }

    echo '<span class="misaki-order-document">';

    if ($document['type']) {
        echo esc_html(misaki_woo_get_document_type_label($document['type']));
    }

    if ($document['number']) {
        if ($document['type']) {
            echo '<br>';
        }
        echo '<span class="misaki-order-document__number">' . esc_html($document['number']) . '</span>';
    }

    echo '</span>';
}

add_action('woocommerce_shop_order_list_table_custom_column', 'misaki_woo_checkout_orders_list_column', 10, 2);

/**
 * Muestra documento en detalle del pedido para el cliente.
 */
function misaki_woo_checkout_customer_order_document(string $address_type, WC_Order $order): void
{
    if ($address_type !== 'billing') {
        return;
    }

    $document = misaki_woo_get_order_document_data($order);

    if (!$document['type'] && !$document['number']) {
        return;
    }

    if ($document['type']) {
        echo '<p class="woocommerce-customer-details--document-type">';
        echo esc_html__('Document type', 'misaki-woo') . ': ';
        echo esc_html(misaki_woo_get_document_type_label($document['type']));
        echo '</p>';
    }

    if ($document['number']) {
        echo '<p class="woocommerce-customer-details--document-number">';
        echo esc_html__('Document number', 'misaki-woo') . ': ';
        echo esc_html($document['number']);
        echo '</p>';
    }
}

add_action('woocommerce_order_details_after_customer_address', 'misaki_woo_checkout_customer_order_document', 10, 2);

/**
 * Incluye documento en emails del pedido.
 *
 * @param array<int, array<string, string>> $fields
 * @return array<int, array<string, string>>
 */
function misaki_woo_checkout_email_document_fields(array $fields, bool $sent_to_admin, WC_Order $order): array
{
    $document = misaki_woo_get_order_document_data($order);

    if ($document['type']) {
        $fields['billing_document_type'] = [
            'label' => __('Document type', 'misaki-woo'),
            'value' => misaki_woo_get_document_type_label($document['type']),
        ];
    }

    if ($document['number']) {
        $fields['billing_document_number'] = [
            'label' => __('Document number', 'misaki-woo'),
            'value' => $document['number'],
        ];
    }

    return $fields;
}

add_filter('woocommerce_email_order_meta_fields', 'misaki_woo_checkout_email_document_fields', 10, 3);
