<?php
/**
 * Contact Form 7 — formulario de contacto Misaki.
 */

if (!defined('ABSPATH')) {
    exit;
}

const MISAKI_CONTACT_CF7_FORM_OPTION = 'misaki_contact_cf7_form_id';

/**
 * Markup del formulario CF7.
 */
function misaki_woo_get_contact_cf7_form_markup(): string
{
    return <<<'FORM'
<div class="misaki-contact-form">
<fieldset class="misaki-contact-form__field misaki-contact-form__field--subject">
<legend class="misaki-contact-form__legend">SUBJECT <span class="misaki-contact-form__required" aria-hidden="true">*</span></legend>
[select* subject include_blank "About Order" "About Products" "About Wholesale" "Others"]
</fieldset>

<fieldset class="misaki-contact-form__field misaki-contact-form__field--name">
<legend class="misaki-contact-form__legend">NAME <span class="misaki-contact-form__required" aria-hidden="true">*</span></legend>
<div class="misaki-contact-form__name-row">
[text* last-name autocomplete:name placeholder "Last name"]
[text* first-name autocomplete:given-name placeholder "First name"]
</div>
</fieldset>

<fieldset class="misaki-contact-form__field">
<legend class="misaki-contact-form__legend">EMAIL ADDRESS <span class="misaki-contact-form__required" aria-hidden="true">*</span></legend>
[email* your-email autocomplete:email placeholder "Email address"]
</fieldset>

<fieldset class="misaki-contact-form__field">
<legend class="misaki-contact-form__legend">MESSAGE <span class="misaki-contact-form__required" aria-hidden="true">*</span></legend>
[textarea* your-message placeholder "Message"]
</fieldset>

<div class="misaki-contact-form__actions">
[submit "Send"]
</div>
</div>
FORM;
}

/**
 * Configuración de correo CF7.
 *
 * @return array<string, mixed>
 */
function misaki_woo_get_contact_cf7_mail_properties(): array
{
    $recipient = get_option('admin_email');

    if (function_exists('misaki_woo_get_contact_form_recipient')) {
        $custom = misaki_woo_get_contact_form_recipient();

        if ($custom !== '') {
            $recipient = $custom;
        }
    }

    return [
        'active'            => true,
        'subject'           => '[subject]',
        'sender'            => '[last-name] [first-name] <wordpress@' . wp_parse_url(home_url(), PHP_URL_HOST) . '>',
        'recipient'         => $recipient,
        'body'              => "Subject: [subject]\nName: [last-name] [first-name]\nEmail: [your-email]\n\nMessage:\n[your-message]",
        'additional_headers'=> 'Reply-To: [your-email]',
        'attachments'       => '',
        'use_html'          => false,
        'exclude_blank'     => false,
    ];
}

/**
 * Email de destino por defecto del formulario.
 */
function misaki_woo_get_contact_form_recipient(): string
{
    return 'info@misakidrinks.com';
}

/**
 * Mensaje de éxito tras enviar el formulario de /contact/.
 */
function misaki_woo_get_contact_cf7_mail_sent_ok_message(): string
{
    return "Thank you for your message.\n\n"
        . "We have received your enquiry and will get back to you as soon as possible.\n\n"
        . "The Misaki Team";
}

/**
 * Crea el formulario CF7 del tema si no existe (no sobrescribe ediciones del admin).
 */
function misaki_woo_ensure_contact_cf7_form(): void
{
    if (!class_exists('WPCF7_ContactForm')) {
        return;
    }

    $form_id   = (int) get_option(MISAKI_CONTACT_CF7_FORM_OPTION, 0);
    $form_post = $form_id > 0 ? get_post($form_id) : null;
    $created   = false;

    // ID de otro entorno / borrado: resetear y buscar por título o crear de nuevo.
    if (!$form_post || $form_post->post_type !== 'wpcf7_contact_form') {
        $form_id = 0;
        $forms   = get_posts([
            'post_type'      => 'wpcf7_contact_form',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ]);

        foreach ($forms as $candidate_id) {
            if (get_the_title((int) $candidate_id) === 'Misaki Contact') {
                $form_id = (int) $candidate_id;
                break;
            }
        }
    }

    if ($form_id <= 0) {
        $form_id = wp_insert_post([
            'post_title'   => 'Misaki Contact',
            'post_status'  => 'publish',
            'post_type'    => 'wpcf7_contact_form',
            'post_content' => misaki_woo_get_contact_cf7_form_markup(),
        ]);

        if (is_wp_error($form_id) || !$form_id) {
            return;
        }

        $created = true;
    }

    update_option(MISAKI_CONTACT_CF7_FORM_OPTION, (int) $form_id);

    // Solo aplicar defaults al crear; si ya existe, respetar cambios del admin.
    if (!$created) {
        return;
    }

    $contact_form = wpcf7_contact_form($form_id);

    if (!$contact_form) {
        return;
    }

    $properties = $contact_form->get_properties();
    $properties['form'] = misaki_woo_get_contact_cf7_form_markup();
    $properties['mail'] = misaki_woo_get_contact_cf7_mail_properties();
    $properties['mail_2']['active'] = false;
    $properties['messages']['mail_sent_ok'] = misaki_woo_get_contact_cf7_mail_sent_ok_message();

    $contact_form->set_properties($properties);
    $contact_form->save();

    // get_instance() marca este form como "current"; si no se limpia,
    // Contact → Contact Forms abre el editor en vez de la lista.
    WPCF7_ContactForm::get_instance(0);
}

add_action('init', 'misaki_woo_ensure_contact_cf7_form', 20);

/**
 * ID del formulario CF7 de contacto.
 */
function misaki_woo_get_contact_cf7_form_id(): int
{
    return (int) get_option(MISAKI_CONTACT_CF7_FORM_OPTION, 0);
}

/**
 * Renderiza el formulario de contacto.
 */
function misaki_woo_render_contact_form(): void
{
    $form_id = misaki_woo_get_contact_cf7_form_id();

    if ($form_id > 0 && function_exists('wpcf7_contact_form')) {
        echo do_shortcode('[contact-form-7 id="' . $form_id . '" title="Misaki Contact"]');
        return;
    }

    if (shortcode_exists('contact-form-7')) {
        echo '<p class="misaki-contact-form__missing">' . esc_html__('Contact form is not configured yet. Please install and activate Contact Form 7.', 'misaki-woo') . '</p>';
    }
}

/**
 * Desactiva estilos por defecto de CF7 en la página de contacto.
 */
function misaki_woo_contact_cf7_dequeue_default_styles(): void
{
    if (!is_page('contact') && !is_page_template('page-contact.php')) {
        return;
    }

    wp_dequeue_style('contact-form-7');
    wp_dequeue_style('wpcf7-redirect');
}

add_action('wp_enqueue_scripts', 'misaki_woo_contact_cf7_dequeue_default_styles', 20);
