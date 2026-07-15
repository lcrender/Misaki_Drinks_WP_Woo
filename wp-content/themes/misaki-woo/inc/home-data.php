<?php
/**
 * Valores por defecto de la homepage.
 */

if (!defined('ABSPATH')) {
    exit;
}

function misaki_woo_home_hero_bg_filename(): string
{
    return 'background.jpg';
}

function misaki_woo_home_hero_video_path(): string
{
    return '2026/07/misaki-intro.mp4';
}

function misaki_woo_home_hero_brand_filename(): string
{
    return 'misaki-big.png';
}

function misaki_woo_home_we_are_bg_filename(): string
{
    return 'cascada2.jpg';
}

function misaki_woo_home_we_are_panel_filename(): string
{
    return 'compo1.png';
}

function misaki_woo_get_home_products_defaults(): array
{
    return [
        ['filename' => 'misaki3_b.jpg', 'title' => 'Product 01'],
        ['filename' => 'misaki2_b.jpg', 'title' => 'Product 02'],
        ['filename' => 'misaki4.jpg', 'title' => 'Product 03'],
        ['filename' => 'misaki1.jpg', 'title' => 'Product 04'],
    ];
}

function misaki_woo_get_home_products_title_default(): string
{
    return 'Our Products';
}

function misaki_woo_get_home_products_intro_default(): string
{
    return 'Discover our collection of premium saké and yuzu liqueurs, inspired by Japanese tradition and elegance. Each bottle offers a unique experience, balancing authenticity, quality, and refined flavor.';
}

function misaki_woo_get_home_we_are_title_default(): string
{
    return 'We Are';
}

/**
 * @return array<int, string>
 */
function misaki_woo_get_home_we_are_paragraphs_default(): array
{
    return [
        'MISAKI®, three entrepreneurial friends who are passionate about Sake and admire its rich thousand-year-old culture. Our drive is to share this mysterious drink with the rest of the world.',
        'Misaki was born in 2016, with the vision of bringing the most exquisite and magical bottle to every table in the world. Since then, we have embarked on a journey to introduce this coveted Japanese drink to Europe, with the mission to share our knowledge of Sake and Yuzu.',
        'But it\'s not just about passing on knowledge, we also want to spread our passion and love for our products.',
        'Thanks to this trip we can present you the best product of Japan: Misaki®, a bottle full of pure magic and ancestral culture.',
    ];
}

/**
 * @return array<int, array{id: string, label: string}>
 */
function misaki_woo_get_home_values_jump_links_defaults(): array
{
    return [
        ['id' => 'our-values', 'label' => 'Our Values'],
        ['id' => 'our-mission', 'label' => 'Our Mission'],
        ['id' => 'the-brewery', 'label' => 'The Brewery'],
        ['id' => 'sake-production', 'label' => 'Sake Production'],
    ];
}

/**
 * Campos planos por defecto para reconstruir Our Values.
 *
 * @return array<string, string>
 */
function misaki_woo_get_home_values_flat_defaults(): array
{
    return [
        'values_h2'           => 'Our Values',
        'values_h3_1_title'   => 'Authentic and Unique',
        'values_h3_1_text'    => 'Misaki is a young, cheerful company, full of energy and desire to share our exquisite products worldwide. We have one goal: We want our Sake and Yuzu to be authentic and unique in the market.',
        'values_h3_2_title'   => 'Pure Quality',
        'values_h3_2_text'    => "At Misaki we don't just want to offer a good product, we want to deliver the best Sake and Yuzu to our customers.\nWe have been searching for the best Sake masters in Japan, along with the best products from the region in order to provide a unique experience in every bottle.",
        'img_values'          => 'our-values.jpg',
        'img_mission'         => 'our-mision.jpg',
        'mission_quote'       => 'Finding the best traditional Japanese Sake and Yuzu and reintroduce them to the world.',
        'mission_h2'          => 'Our Mission',
        'mission_text'        => "In 2016 we started the search for the best brewery to find the ideal Sake. To achieve our mission, we travelled around Japan seeking to learn the history behind this millenary drink, in order to find the finest and most exquisite product.\nOur search was not without problems as Sake is an ancient drink with secret recipes that most of the brewers obviously did not want to share with us.\nWe did not give up, and persisted in bringing the best traditional Japanese Sake and Yuzu to the world!",
        'brewery_h2'          => 'The Brewery',
        'brewery_text'        => "With determination and unstoppable passion we finally found the best in the world: The thousand-year-old winery located Northwest of Tokyo in Moroyamamachi. It's a village in Saitama Prefecture, with the best hills for harvesting fruit trees and the purest rice in the region, which is reflected in the final product, the finest quality Sake and Yuzu.\nIt is one of the oldest family-owned sake breweries in Japan, open since 1882, with more than 135 years of experience in making the best Sake. The founder of the company studied Sake brewing from the age of 9 and started the brewery at the age of 29. More than five generations have been at the helm of this family business, dedicated to bringing the finest Sake and Yuzu to their customers. It was the leader of the fifth generation who started producing Yuzu fruit liqueur from the best fruit trees in the region in 1988.\nMisaki wants to preserve the magic of this family business dedicated to making the best product in the area, and share its magic and the history behind these ancient beverages. We love to distribute these wonderful products and their history worldwide.",
        'img_brewery'         => 'the-brewery.jpg',
        'sake_h2'             => 'Facts about Sake production:',
        'sake_list'           => "Sake\" in Japan is usually called \"Nihon Shu\", the product of the rice fermentation.\nThere are special varieties of rice to make quality Nihon-Shu: Yamada Nishiki, Gohyakumangoku, Omachi, Miyama Nishiki, Musashi, and others.\nAfter the harvest, the process starts when the rice is polished to remove the parts with fats, vitamins and proteins (lead off-flavors).\nHigher percentage of polish is better for a high quality and final taste.\nThe rice is streamed and then Koji-Kin (fugus Asperguillus oryzae) is added\nAt a controlled environment the in an special place at brewery the Koji-kin converts the starch in maltose and glucose.\nFermentation is produced by the addition of water and yeast.",
        'img_sake'            => 'sake-production.jpg',
    ];
}

/**
 * @param array<string, string> $flat
 * @param array<string, int>    $image_ids
 * @return array<int, array<string, mixed>>
 */
function misaki_woo_home_build_values_blocks(array $flat, array $image_ids = []): array
{
    $lines = static function (string $text): array {
        $out = [];

        foreach (preg_split('/\r\n|\r|\n/', $text) ?: [] as $line) {
            $line = trim($line);

            if ($line !== '') {
                $out[] = $line;
            }
        }

        return $out;
    };

    $image_block = static function (string $key, string $filename, string $alt) use ($image_ids): array {
        $block = [
            'type'  => 'image',
            'image' => $filename,
            'alt'   => $alt,
        ];

        if (!empty($image_ids[$key])) {
            $block['image_id'] = (int) $image_ids[$key];
        }

        return $block;
    };

    return [
        [
            'type'     => 'text',
            'sections' => [
                ['heading' => $flat['values_h2'], 'level' => 2, 'anchor' => 'our-values'],
                ['heading' => $flat['values_h3_1_title'], 'level' => 3, 'paragraphs' => $lines($flat['values_h3_1_text'])],
                ['heading' => $flat['values_h3_2_title'], 'level' => 3, 'paragraphs' => $lines($flat['values_h3_2_text'])],
            ],
        ],
        $image_block('img_values', $flat['img_values'], 'Our Values'),
        $image_block('img_mission', $flat['img_mission'], 'Our Mission'),
        [
            'type'     => 'text',
            'sections' => [
                ['heading' => $flat['mission_quote'], 'level' => 3, 'paragraphs' => []],
                ['heading' => $flat['mission_h2'], 'level' => 2, 'anchor' => 'our-mission'],
                ['paragraphs' => $lines($flat['mission_text'])],
            ],
        ],
        [
            'type'     => 'text',
            'sections' => [
                ['heading' => $flat['brewery_h2'], 'level' => 2, 'anchor' => 'the-brewery'],
                ['paragraphs' => $lines($flat['brewery_text'])],
            ],
        ],
        $image_block('img_brewery', $flat['img_brewery'], 'The Brewery'),
        [
            'type'     => 'text',
            'sections' => [
                ['heading' => $flat['sake_h2'], 'level' => 2, 'anchor' => 'sake-production'],
                ['list' => $lines($flat['sake_list'])],
            ],
        ],
        $image_block('img_sake', $flat['img_sake'], 'Facts about Sake production'),
    ];
}

/**
 * @return array<int, array<string, mixed>>
 */
function misaki_woo_get_home_values_blocks_defaults(): array
{
    $defaults = misaki_woo_get_home_values_flat_defaults();

    return misaki_woo_home_build_values_blocks($defaults);
}
