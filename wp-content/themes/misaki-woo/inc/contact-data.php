<?php
/**
 * Valores por defecto de la página Contact.
 */

if (!defined('ABSPATH')) {
    exit;
}

function misaki_woo_get_contact_intro_image_filename(): string
{
    return 'misaki-contact.jpg';
}

function misaki_woo_get_contact_distributors_bg_filename(): string
{
    return 'bg-black.jpg';
}

/**
 * @return array{url: string, handle: string}
 */
function misaki_woo_get_contact_instagram_defaults(): array
{
    return [
        'url'    => 'https://www.instagram.com/misakidrinks',
        'handle' => '@misakidrinks',
    ];
}

function misaki_woo_get_contact_intro_title_default(): string
{
    return 'Get in touch.';
}

function misaki_woo_get_contact_intro_lead_default(): string
{
    return 'For all questions about our products, services, dealers and/or importership, please feel free to contact us:';
}

/**
 * @return array<int, array{name: string, role: string, role_ja: string, phone: string, email: string}>
 */
function misaki_woo_get_contact_team_defaults(): array
{
    return [
        [
            'name'    => 'TOINE MAART',
            'role'    => 'CEO',
            'role_ja' => '共同創設者',
            'phone'   => '+31 634 659 800',
            'email'   => 'toine@misakidrinks.com',
        ],
        [
            'name'    => 'YADI VILLANUEVA',
            'role'    => 'CO-OWNER',
            'role_ja' => '共同所有者',
            'phone'   => '+31 640 277 275',
            'email'   => 'yadi@misakidrinks.com',
        ],
        [
            'name'    => 'HARCO HIROKI',
            'role'    => 'CO-OWNER',
            'role_ja' => '共同所有者',
            'phone'   => '',
            'email'   => 'harco@misakidrinks.com',
        ],
        [
            'name'    => 'ALAYNE FERREIRA',
            'role'    => 'SALES EXECUTIVE NEVADA',
            'role_ja' => 'ネバダ州の販売責任者',
            'phone'   => '+17 025 410 768',
            'email'   => 'alayneferreira@hotmail.com',
        ],
        [
            'name'    => 'DANA GOMEZ',
            'role'    => 'SALES EXECUTIVE SPAIN',
            'role_ja' => '営業責任者スペイン',
            'phone'   => '+34 633 793 690',
            'email'   => 'dana@misakidrinks.com',
        ],
        [
            'name'    => 'PETER STRATOSKI',
            'role'    => 'SALES EXECUTIVE THAILAND',
            'role_ja' => '営業責任者 タイ',
            'phone'   => '+66 810 231 541',
            'email'   => 'peter@misakidrinks.com',
        ],
    ];
}

/**
 * @return array{name: string, address_lines: string[]}
 */
function misaki_woo_get_contact_company_defaults(): array
{
    return [
        'name'           => 'KANPAI B.V.',
        'address_lines'  => [
            'Oosteinderweg 303A',
            '1432AW Aalsmeer',
            'The Netherlands',
        ],
    ];
}

function misaki_woo_get_contact_distributors_title_default(): string
{
    return 'Distributors';
}

function misaki_woo_get_contact_distributors_lead_default(): string
{
    return 'We have distributors worldwide. Here, you can find the contact list to reach out and access our products:';
}

/**
 * @return array<int, array{country: string, flag: string, distributors: array<int, array{name: string, tel: string, emails: string[]}>}>
 */
function misaki_woo_get_distributors_defaults(): array
{
    return [
        [
            'country'      => 'Spain',
            'flag'         => '🇪🇸',
            'distributors' => [
                [
                    'name'   => 'La Fuente / Madrid, Barcelona',
                    'tel'    => '+34 932 011 513',
                    'emails' => ['lafuente@lafuente.es'],
                ],
                [
                    'name'   => 'Bebidas Sur / Seville',
                    'tel'    => '+34 955 66 58 37',
                    'emails' => ['comunicaciones@bebidassur.com'],
                ],
                [
                    'name'   => 'Valmed Servicios Integrales / Valencia',
                    'tel'    => '+34 655 85 28 43',
                    'emails' => ['Joan@mexandworld.com'],
                ],
                [
                    'name'   => 'Isla Catavinos / Islas Baleares',
                    'tel'    => '+34 618014198',
                    'emails' => ['albert@islacatavinos.com', 'daniel@islacatavinos.com'],
                ],
            ],
        ],
        [
            'country'      => 'Thailand',
            'flag'         => '🇹🇭',
            'distributors' => [
                [
                    'name'   => 'Kawin International',
                    'tel'    => '+66 890 938 028',
                    'emails' => ['patrick@mampeberlin.com'],
                ],
            ],
        ],
        [
            'country'      => 'United States',
            'flag'         => '🇺🇸',
            'distributors' => [
                [
                    'name'   => 'Buendia LLC, Folsom, CA',
                    'tel'    => '',
                    'emails' => ['info@tequilabuendia.com'],
                ],
            ],
        ],
        [
            'country'      => 'Netherlands',
            'flag'         => '🇳🇱',
            'distributors' => [
                [
                    'name'   => 'The Real State Spirits',
                    'tel'    => '+31 0180 54 74 00 / +31 0 653286678',
                    'emails' => ['nicky@casa-ron.com'],
                ],
            ],
        ],
    ];
}
