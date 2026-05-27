<?php
/**
 * Template principal fallback del tema.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main id="site-main" class="site-main" tabindex="-1">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?>>
                <h1><?php the_title(); ?></h1>
                <div><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p>No hay contenido para mostrar.</p>
    <?php endif; ?>
</main>
<?php
get_footer();
