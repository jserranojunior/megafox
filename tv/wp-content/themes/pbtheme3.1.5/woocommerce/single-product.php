<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
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

		<?php
			/**
			 * woocommerce_sidebar hook
			 *
			 * @hooked woocommerce_get_sidebar - 10
			 */
			if ( $pbtheme_data['sidebar-woo-single'] !== '0' && $pbtheme_data['woo-sidebar-position-single'] == 1 ) do_action('woocommerce_sidebar');
		?>

		<?php
			if ( $pbtheme_data['sidebar-woo-single'] == 1 ) $woo_class = 'pbuilder_column pbuilder_column-2-3 pbtheme_hidden_flow'; else $woo_class = 'pbuilder_column pbuilder_column-1-1';
			printf('<div id="content" class="%1$s">', $woo_class);
		?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php woocommerce_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php echo '</div>'; ?>

		<?php
			/**
			 * woocommerce_sidebar hook
			 *
			 * @hooked woocommerce_get_sidebar - 10
			 */
			if ( $pbtheme_data['sidebar-woo-single'] !== '0' && $pbtheme_data['woo-sidebar-position-single'] == 0 ) do_action('woocommerce_sidebar');
		?>
	</div>
	</div>
	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		remove_action ( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		add_action ( 'woocommerce_after_main_content', 'woocommerce_output_product_data_tabs', 10 );
		add_action ( 'woocommerce_after_main_content', 'woocommerce_upsell_display', 15 );
		add_action ( 'woocommerce_after_main_content', 'woocommerce_output_related_products', 20 );
		do_action('woocommerce_after_main_content');
		$widgets = 'product';
	?>
	<?php if ( isset($pbtheme_data['product-widgets-after']) || $pbtheme_data['product-widgets-after'] !== 'none' ) : ?>
	<div class="anivia_row pbuilder_row">
	<div>
		<?php
			$widgets_after = $pbtheme_data['product-widgets-after'];
			for ($i = 1; $i <= $widgets_after; $i++) {
				printf( '<div class="pbuilder_column pbuilder_column-1-%1$s">', $widgets_after );
				dynamic_sidebar('product-widgets-after-' . $i);
				printf( '</div><!-- pbuilder_column pbuilder_column-1-%1$s -->', $widgets_after );
			}
		?>
	</div>
	</div><!-- row -->
	<?php endif; ?>

</div>
<?php get_footer('shop'); ?>