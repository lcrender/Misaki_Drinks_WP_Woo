<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Settings for MRW Carrier shipping
 */
return array(
    'disclaimer' => array(
        'type' => 'disclaimer',
    ),
    'acceptdisclaimer' => array(
        'type' => 'checkbox',
        'label' => __('Accept Terms', 'woocommerce-mrw-carrier'),
        'default' => '',
    ),

);
