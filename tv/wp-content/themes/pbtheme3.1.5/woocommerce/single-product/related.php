<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $product, $woocommerce_loop, $pbtheme_data;
$related = $product->get_related( $pbtheme_data['woo-columns-rel'] );
if ( sizeof( $related ) == 0 ) return;
$args = apply_filters('woocommerce_related_products_args', array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows' 		=> 1,
	'posts_per_page' 		=> $pbtheme_data['woo-columns-rel'],
	'orderby' 				=> $orderby,
	'post__in' 				=> $related,
	'post__not_in'			=> array($product->id)
));
$products = new WP_Query( $args );
$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', $pbtheme_data['woo-columns-rel'] );
if ( $products->have_posts() ) : ?>
	<div class="related products">
		<?php echo do_shortcode(sprintf('[pbtheme_title type="h3" align="left"]%1$s[/pbtheme_title]', __( 'Related Products', 'pbtheme' ))); ?>
		<?php woocommerce_product_loop_start(); ?>
			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
				<?php woocommerce_get_template_part( 'content', 'product' ); ?>
			<?php endwhile; // end of the loop. ?>
		<?php woocommerce_product_loop_end(); ?>
	</div>
<?php endif;
wp_reset_postdata();