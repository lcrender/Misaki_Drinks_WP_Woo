<?php
/**
 * Pie del sitio.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<footer class="site-footer" role="contentinfo">
    <p class="site-footer__copy">&copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?></p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
