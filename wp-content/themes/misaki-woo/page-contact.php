<?php
/**
 * Plantilla de la página Contact.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/contact-data.php';
require_once get_template_directory() . '/inc/contact-admin.php';

get_header();

$page_id            = misaki_woo_get_contact_page_id();
$instagram          = misaki_woo_get_contact_instagram($page_id);
$contact_details    = misaki_woo_get_contact_details($page_id);
$contact_image_url  = esc_url(misaki_woo_get_contact_intro_image_url($page_id));
$section_bg         = esc_url(misaki_woo_get_contact_distributors_bg_url($page_id));
?>
<main id="site-main" class="site-main site-main--contact contact-page" tabindex="-1">
    <section
        class="contact-section contact-section--intro"
        style="--contact-section-bg: url('<?php echo $section_bg; ?>');"
        aria-label="<?php esc_attr_e('Get in touch', 'misaki-woo'); ?>"
    >
        <div class="contact-section__inner contact-intro">
            <div class="contact-intro__copy">
                <h1 class="misaki-section-title contact-intro__title"><?php echo esc_html(misaki_woo_get_contact_intro_title($page_id)); ?></h1>

                <div class="contact-intro__info">
                    <p class="contact-intro__lead">
                        <?php echo esc_html(misaki_woo_get_contact_intro_lead($page_id)); ?>
                    </p>

                    <div class="contact-intro__rows">
                        <div class="contact-intro__row">
                            <span class="contact-intro__row-label"><?php esc_html_e('Email', 'misaki-woo'); ?></span>
                            <span class="contact-intro__row-value">
                                <a href="mailto:<?php echo esc_attr($contact_details['email']); ?>"><?php echo esc_html($contact_details['email']); ?></a>
                            </span>
                        </div>

                        <?php if ($contact_details['phone'] !== '') : ?>
                            <div class="contact-intro__row">
                                <span class="contact-intro__row-label"><?php esc_html_e('Phone', 'misaki-woo'); ?></span>
                                <span class="contact-intro__row-value">
                                    <a href="<?php echo esc_attr(misaki_woo_phone_href($contact_details['phone'])); ?>"><?php echo esc_html($contact_details['phone']); ?></a>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php
                        $visible_addresses = array_filter(
                            $contact_details['addresses'],
                            static function (array $address): bool {
                                return trim($address['text']) !== '';
                            }
                        );
                        ?>
                        <?php if ($visible_addresses !== []) : ?>
                            <div class="contact-intro__row contact-intro__row--address">
                                <span class="contact-intro__row-label"><?php esc_html_e('Address', 'misaki-woo'); ?></span>
                                <div class="contact-intro__row-value">
                                    <?php foreach ($visible_addresses as $address) : ?>
                                        <p class="contact-intro__address-line">
                                            <?php if (trim($address['flag']) !== '') : ?>
                                                <span class="contact-intro__address-flag" aria-hidden="true"><?php echo esc_html($address['flag']); ?></span>
                                            <?php endif; ?>
                                            <span class="contact-intro__address-text"><?php echo esc_html($address['text']); ?></span>
                                        </p>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="contact-intro__social">
                        <a
                            class="contact-intro__instagram"
                            href="<?php echo esc_url($instagram['url']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span class="contact-intro__instagram-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7Zm5 3.5A5.5 5.5 0 1 1 6.5 13 5.5 5.5 0 0 1 12 7.5Zm0 2A3.5 3.5 0 1 0 15.5 13 3.5 3.5 0 0 0 12 9.5ZM17.75 6a1.25 1.25 0 1 1-1.25 1.25A1.25 1.25 0 0 1 17.75 6Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <?php echo esc_html($instagram['handle']); ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="contact-intro__layout">
            <div class="contact-form-wrap">
                <?php misaki_woo_render_contact_form(); ?>
            </div>

            <div class="contact-intro__media">
                <img
                    class="contact-intro__image"
                    src="<?php echo $contact_image_url; ?>"
                    alt="<?php echo esc_attr(get_bloginfo('name') ?: 'Misaki Drinks'); ?>"
                    width="800"
                    height="1000"
                    loading="lazy"
                    decoding="async"
                >
            </div>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
