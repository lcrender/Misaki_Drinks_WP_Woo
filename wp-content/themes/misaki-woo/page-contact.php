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
$team               = misaki_woo_get_contact_team($page_id);
$company            = misaki_woo_get_contact_company($page_id);
$instagram          = misaki_woo_get_contact_instagram($page_id);
$contact_image_url  = esc_url(misaki_woo_get_contact_intro_image_url($page_id));
$distributors_bg    = esc_url(misaki_woo_get_contact_distributors_bg_url($page_id));
?>
<main id="site-main" class="site-main site-main--contact contact-page" tabindex="-1">
    <section class="contact-section contact-section--intro" aria-label="<?php esc_attr_e('Get in touch', 'misaki-woo'); ?>">
        <div class="contact-section__inner contact-intro">
            <div class="contact-intro__copy">
                <h1 class="misaki-section-title contact-intro__title"><?php echo esc_html(misaki_woo_get_contact_intro_title($page_id)); ?></h1>
                <p class="contact-intro__lead">
                    <?php echo esc_html(misaki_woo_get_contact_intro_lead($page_id)); ?>
                </p>
                <p class="contact-intro__social">
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
                </p>
            </div>

            <div class="contact-team">
                    <div class="contact-team__columns">
                        <div class="contact-team__left">
                            <?php foreach ($team as $member) : ?>
                                <div class="contact-team__person-meta">
                                    <p class="contact-team__name"><?php echo esc_html($member['name']); ?></p>
                                    <p class="contact-team__role"><?php echo esc_html($member['role']); ?></p>
                                    <p class="contact-team__role-ja"><?php echo esc_html($member['role_ja']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="contact-team__divider" aria-hidden="true"></div>
                        <div class="contact-team__right">
                            <?php foreach ($team as $member) : ?>
                                <div class="contact-team__person-contact">
                                    <?php if (!empty($member['phone'])) : ?>
                                        <p>
                                            <a href="<?php echo esc_attr(misaki_woo_phone_href($member['phone'])); ?>">
                                                <?php echo esc_html($member['phone']); ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                    <p>
                                        <a href="mailto:<?php echo esc_attr($member['email']); ?>">
                                            <?php echo esc_html($member['email']); ?>
                                        </a>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="contact-team__columns contact-team__columns--company">
                        <div class="contact-team__left">
                            <p class="contact-team__name contact-team__name--company"><?php echo esc_html($company['name']); ?></p>
                        </div>
                        <div class="contact-team__divider" aria-hidden="true"></div>
                        <div class="contact-team__right">
                            <p class="contact-team__address">
                                <?php echo esc_html(implode("\n", $company['address_lines'])); ?>
                            </p>
                        </div>
                    </div>
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
    </section>

    <section
        class="contact-section contact-section--distributors"
        style="--contact-distributors-bg: url('<?php echo $distributors_bg; ?>');"
        aria-label="<?php esc_attr_e('Distributors', 'misaki-woo'); ?>"
    >
        <div class="contact-section__inner contact-distributors">
            <h2 class="misaki-section-title contact-distributors__title"><?php echo esc_html(misaki_woo_get_contact_distributors_title($page_id)); ?></h2>
            <p class="contact-distributors__lead">
                <?php echo esc_html(misaki_woo_get_contact_distributors_lead($page_id)); ?>
            </p>

            <?php
            $distributors       = misaki_woo_get_distributors($page_id);
            $distributors_left  = array_slice($distributors, 0, 1);
            $distributors_right = array_slice($distributors, 1);
            ?>
            <div class="contact-distributors__grid">
                <div class="contact-distributors__column">
                <?php foreach ($distributors_left as $country) : ?>
                    <div class="contact-distributors__country">
                        <h3 class="contact-distributors__country-name">
                            <?php echo esc_html($country['country']); ?>
                            <span class="contact-distributors__flag" aria-hidden="true"><?php echo esc_html($country['flag']); ?></span>
                        </h3>
                        <?php foreach ($country['distributors'] as $distributor) : ?>
                            <div class="contact-distributors__item">
                                <p class="contact-distributors__item-name"><?php echo esc_html($distributor['name']); ?></p>
                                <?php if (!empty($distributor['tel'])) : ?>
                                    <p class="contact-distributors__item-line">
                                        <span><?php esc_html_e('Tel:', 'misaki-woo'); ?></span>
                                        <a href="<?php echo esc_attr(misaki_woo_phone_href($distributor['tel'])); ?>">
                                            <?php echo esc_html($distributor['tel']); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                                <p class="contact-distributors__item-line">
                                    <span><?php esc_html_e('Email:', 'misaki-woo'); ?></span>
                                    <?php
                                    $email_links = [];
                                    foreach ($distributor['emails'] as $email) {
                                        $email_links[] = '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
                                    }
                                    echo wp_kses_post(implode(' / ', $email_links));
                                    ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                </div>
                <div class="contact-distributors__column">
                <?php foreach ($distributors_right as $country) : ?>
                    <div class="contact-distributors__country">
                        <h3 class="contact-distributors__country-name">
                            <?php echo esc_html($country['country']); ?>
                            <span class="contact-distributors__flag" aria-hidden="true"><?php echo esc_html($country['flag']); ?></span>
                        </h3>
                        <?php foreach ($country['distributors'] as $distributor) : ?>
                            <div class="contact-distributors__item">
                                <p class="contact-distributors__item-name"><?php echo esc_html($distributor['name']); ?></p>
                                <?php if (!empty($distributor['tel'])) : ?>
                                    <p class="contact-distributors__item-line">
                                        <span><?php esc_html_e('Tel:', 'misaki-woo'); ?></span>
                                        <a href="<?php echo esc_attr(misaki_woo_phone_href($distributor['tel'])); ?>">
                                            <?php echo esc_html($distributor['tel']); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                                <p class="contact-distributors__item-line">
                                    <span><?php esc_html_e('Email:', 'misaki-woo'); ?></span>
                                    <?php
                                    $email_links = [];
                                    foreach ($distributor['emails'] as $email) {
                                        $email_links[] = '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
                                    }
                                    echo wp_kses_post(implode(' / ', $email_links));
                                    ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
