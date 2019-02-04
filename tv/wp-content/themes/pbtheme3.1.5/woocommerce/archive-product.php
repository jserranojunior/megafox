<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $pbtheme_data;

get_header('shop'); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		remove_action ( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		do_action('woocommerce_before_main_content');
	?>

		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<?php /* echo do_shortcode(sprintf('[pbtheme_title_feat]%1$s[/pbtheme_title_feat]', get_the_title())); */ ?>

		<?php endif; ?>

		<?php do_action( 'woocommerce_archive_description' ); ?>

		<?php
			/**
			 * woocommerce_sidebar hook
			 *
			 * @hooked woocommerce_get_sidebar - 10
			 */
			if ( $pbtheme_data['woo-sidebar-position'] == 1 ) do_action('woocommerce_sidebar');
		?>

		<?php
			if ( $pbtheme_data['sidebar-woo'] == 1 ) {
				switch ($pbtheme_data['sidebar-size']):
				case '3' :
				$woo_class = 'pbuilder_column pbuilder_column-2-3 pbtheme_hidden_flow margin-bottom36';
				$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-3';
				break;
				case '4' :
				$woo_class = 'pbuilder_column pbuilder_column-3-4 pbtheme_hidden_flow margin-bottom36';
				$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-4';
				break;
				case '5' :
				$woo_class = 'pbuilder_column pbuilder_column-4-5 pbtheme_hidden_flow margin-bottom36';
				$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-5';
				break;
				endswitch;
			}
			else {
				$woo_class = 'pbuilder_column pbuilder_column-1-1 margin-bottom36';
			}
			printf('<div id="content" class="%1$s">', $woo_class);
		?>

		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php woocommerce_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php echo '</div>'; ?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		if ( $pbtheme_data['woo-sidebar-position'] == 0 ) do_action('woocommerce_sidebar');
	?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>

<?php get_footer('shop'); ?>