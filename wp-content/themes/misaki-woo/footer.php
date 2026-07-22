<?php
/**
 * Pie del sitio — 4 columnas.
 */

if (!defined('ABSPATH')) {
    exit;
}

$logo_url = content_url('uploads/2026/05/logo-misaki-drinks.png');
$privacy_url = get_privacy_policy_url();
$cookie_page = get_page_by_path('cookie-policy');
$cookie_url  = $cookie_page ? get_permalink($cookie_page) : '';
$trade_url   = function_exists('misaki_woo_get_trade_contact_page_url')
    ? misaki_woo_get_trade_contact_page_url()
    : home_url('/misaki-contact-form/');
$terms_url   = function_exists('misaki_woo_get_terms_conditions_url')
    ? misaki_woo_get_terms_conditions_url()
    : home_url('/terms-conditions/');
$account_url = function_exists('wc_get_page_permalink')
    ? wc_get_page_permalink('myaccount')
    : home_url('/my-account/');
$cart_url = function_exists('wc_get_page_permalink')
    ? wc_get_page_permalink('cart')
    : home_url('/cart/');

$contact_details = function_exists('misaki_woo_get_contact_details')
    ? misaki_woo_get_contact_details()
    : ['email' => 'info@misakidrinks.com', 'phone' => '', 'addresses' => []];
$instagram = function_exists('misaki_woo_get_contact_instagram')
    ? misaki_woo_get_contact_instagram()
    : ['url' => 'https://www.instagram.com/misakidrinks', 'handle' => '@misakidrinks'];
?>
<footer class="site-footer" role="contentinfo">
    <div class="site-footer__inner">
        <div class="site-footer__col site-footer__col--brand">
            <a class="site-footer__logo" href="<?php echo esc_url(home_url('/')); ?>">
                <img
                    class="site-footer__logo-image"
                    src="<?php echo esc_url($logo_url); ?>"
                    alt="<?php echo esc_attr(get_bloginfo('name') ?: 'Misaki Drinks'); ?>"
                    width="208"
                    height="56"
                    loading="lazy"
                    decoding="async"
                >
            </a>
            <p class="site-footer__tagline"><?php esc_html_e('Premium Saké & Yuzu Saké', 'misaki-woo'); ?></p>
        </div>

        <nav class="site-footer__col" aria-labelledby="footer-account-title">
            <h2 id="footer-account-title" class="site-footer__title"><?php esc_html_e('Account', 'misaki-woo'); ?></h2>
            <ul class="site-footer__list">
                <li>
                    <a class="site-footer__link" href="<?php echo esc_url($account_url); ?>"><?php esc_html_e('My account', 'misaki-woo'); ?></a>
                </li>
                <li>
                    <a class="site-footer__link" href="<?php echo esc_url($cart_url); ?>"><?php esc_html_e('Cart', 'misaki-woo'); ?></a>
                </li>
            </ul>
        </nav>

        <nav class="site-footer__col" aria-labelledby="footer-legals-title">
            <h2 id="footer-legals-title" class="site-footer__title"><?php esc_html_e('Legals', 'misaki-woo'); ?></h2>
            <ul class="site-footer__list">
                <?php if ($privacy_url) : ?>
                    <li>
                        <a class="site-footer__link" href="<?php echo esc_url($privacy_url); ?>"><?php esc_html_e('Privacy Policy', 'misaki-woo'); ?></a>
                    </li>
                <?php endif; ?>
                <?php if ($cookie_url) : ?>
                    <li>
                        <a class="site-footer__link" href="<?php echo esc_url($cookie_url); ?>"><?php esc_html_e('Cookie Policy', 'misaki-woo'); ?></a>
                    </li>
                <?php endif; ?>
                <li>
                    <button type="button" class="site-footer__link cmplz-manage-consent">
                        <?php esc_html_e('Manage cookies', 'misaki-woo'); ?>
                    </button>
                </li>
                <li>
                    <a class="site-footer__link" href="<?php echo esc_url($terms_url); ?>"><?php esc_html_e('Terms & Conditions', 'misaki-woo'); ?></a>
                </li>
            </ul>
        </nav>

        <div class="site-footer__col site-footer__col--contact" aria-labelledby="footer-contact-title">
            <h2 id="footer-contact-title" class="site-footer__title"><?php esc_html_e('Contact', 'misaki-woo'); ?></h2>
            <ul class="site-footer__list site-footer__list--contact">
                <?php if (!empty($contact_details['addresses'])) : ?>
                    <?php foreach ($contact_details['addresses'] as $address) : ?>
                        <?php
                        $text = isset($address['text']) ? trim((string) $address['text']) : '';
                        if ($text === '') {
                            continue;
                        }
                        ?>
                        <li class="site-footer__contact-item site-footer__contact-item--address">
                            <span><?php echo esc_html($text); ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (!empty($contact_details['phone'])) : ?>
                    <li>
                        <a class="site-footer__link" href="<?php echo esc_url(misaki_woo_phone_href($contact_details['phone'])); ?>">
                            <?php echo esc_html($contact_details['phone']); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (!empty($contact_details['email'])) : ?>
                    <li>
                        <a class="site-footer__link" href="mailto:<?php echo esc_attr($contact_details['email']); ?>">
                            <?php echo esc_html($contact_details['email']); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <li>
                    <a class="site-footer__link" href="<?php echo esc_url($trade_url); ?>"><?php esc_html_e('Misaki Contact Form', 'misaki-woo'); ?></a>
                </li>

                <?php if (!empty($instagram['url'])) : ?>
                    <li>
                        <a
                            class="site-footer__link"
                            href="<?php echo esc_url($instagram['url']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <?php echo esc_html($instagram['handle'] ?: 'Instagram'); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <p class="site-footer__copy">&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?></p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
