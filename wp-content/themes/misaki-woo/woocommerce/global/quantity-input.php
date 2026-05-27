<?php
/**
 * Input de cantidad con botones +/− en página de producto.
 *
 * @package WooCommerce\Templates
 * @version 10.1.0
 *
 * @var bool   $readonly
 * @var string $type
 */

defined('ABSPATH') || exit;

$label = !empty($args['product_name'])
    ? sprintf(esc_html__('%s quantity', 'woocommerce'), wp_strip_all_tags($args['product_name']))
    : esc_html__('Quantity', 'woocommerce');

$use_stepper = function_exists('is_product') && is_product();

if ($use_stepper) :
    ?>
    <div class="product-quantity-field">
        <label class="product-quantity-field__label" for="<?php echo esc_attr($input_id); ?>">
            <?php esc_html_e('Quantity', 'woocommerce'); ?>
        </label>
        <div class="quantity quantity--stepper">
            <?php do_action('woocommerce_before_quantity_input_field'); ?>
            <button type="button" class="quantity__btn quantity__btn--minus" aria-label="<?php esc_attr_e('Disminuir cantidad', 'misaki-woo'); ?>">−</button>
            <input
                type="<?php echo esc_attr($type); ?>"
                <?php echo $readonly ? 'readonly="readonly"' : ''; ?>
                id="<?php echo esc_attr($input_id); ?>"
                class="<?php echo esc_attr(join(' ', (array) $classes)); ?>"
                name="<?php echo esc_attr($input_name); ?>"
                value="<?php echo esc_attr($input_value); ?>"
                aria-label="<?php echo esc_attr($label); ?>"
                <?php if (in_array($type, ['text', 'search', 'tel', 'url', 'email', 'password'], true)) : ?>
                    size="4"
                <?php endif; ?>
                min="<?php echo esc_attr($min_value); ?>"
                <?php if (0 < $max_value) : ?>
                    max="<?php echo esc_attr($max_value); ?>"
                <?php endif; ?>
                <?php if (!$readonly) : ?>
                    step="<?php echo esc_attr($step); ?>"
                    placeholder="<?php echo esc_attr($placeholder); ?>"
                    inputmode="<?php echo esc_attr($inputmode); ?>"
                    autocomplete="<?php echo esc_attr(isset($autocomplete) ? $autocomplete : 'on'); ?>"
                <?php endif; ?>
            />
            <button type="button" class="quantity__btn quantity__btn--plus" aria-label="<?php esc_attr_e('Aumentar cantidad', 'misaki-woo'); ?>">+</button>
            <?php do_action('woocommerce_after_quantity_input_field'); ?>
        </div>
    </div>
    <?php
    return;
endif;
?>
<div class="quantity">
    <?php do_action('woocommerce_before_quantity_input_field'); ?>
    <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo esc_attr($label); ?></label>
    <input
        type="<?php echo esc_attr($type); ?>"
        <?php echo $readonly ? 'readonly="readonly"' : ''; ?>
        id="<?php echo esc_attr($input_id); ?>"
        class="<?php echo esc_attr(join(' ', (array) $classes)); ?>"
        name="<?php echo esc_attr($input_name); ?>"
        value="<?php echo esc_attr($input_value); ?>"
        aria-label="<?php esc_attr_e('Product quantity', 'woocommerce'); ?>"
        <?php if (in_array($type, ['text', 'search', 'tel', 'url', 'email', 'password'], true)) : ?>
            size="4"
        <?php endif; ?>
        min="<?php echo esc_attr($min_value); ?>"
        <?php if (0 < $max_value) : ?>
            max="<?php echo esc_attr($max_value); ?>"
        <?php endif; ?>
        <?php if (!$readonly) : ?>
            step="<?php echo esc_attr($step); ?>"
            placeholder="<?php echo esc_attr($placeholder); ?>"
            inputmode="<?php echo esc_attr($inputmode); ?>"
            autocomplete="<?php echo esc_attr(isset($autocomplete) ? $autocomplete : 'on'); ?>"
        <?php endif; ?>
    />
    <?php do_action('woocommerce_after_quantity_input_field'); ?>
</div>
