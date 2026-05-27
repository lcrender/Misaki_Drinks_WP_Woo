<?php
/**
 * My Account — layout Misaki Woo.
 *
 * @package WooCommerce\Templates
 */

defined('ABSPATH') || exit;

if (!is_user_logged_in()) :
    ?>
    <div class="misaki-account__guest">
        <?php do_action('woocommerce_account_content'); ?>
    </div>
    <?php
    return;
endif;
?>
<div class="misaki-account__layout">
    <?php do_action('woocommerce_account_navigation'); ?>

    <div class="woocommerce-MyAccount-content misaki-account__content">
        <?php do_action('woocommerce_account_content'); ?>
    </div>
</div>
