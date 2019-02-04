<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop, $pbtheme_data;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', $pbtheme_data['woo-columns'] );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
//$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
$classes[] = 'product text-center pbuilder_column pbuilder_column-1-'.$woocommerce_loop['columns'];

?>
<li <?php post_class( $classes ); ?>>
	
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>
	<div class="div_linked_wrap div_flip_wrapper">
	<a href="<?php the_permalink(); ?>" class="linked_image">
		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			remove_action ( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
			do_action( 'woocommerce_before_shop_loop_item_title' );
			$attachment_ids = $product->get_gallery_attachment_ids();
			if ($attachment_ids) {
				$image = wp_get_attachment_image_src( $attachment_ids[0], 'shop_catalog' );
			}
			?>
    
    
    
    <div class="div_flip_wrapper_z">
		<div class="<?php if(!empty($image[0]) || $pbtheme_data['woo_force_imageflip'] ) echo "div_flip_card"?>">
 		<?php the_post_thumbnail('shop_catalog', array( 'class'	=> "div_flip_front")); ?>
		<?php 
		if(!empty($image[0])) {
			printf('<img src="%1$s" class="attachment_shop-image wp-post-image div_flip_back" />', $image[0]); 
		} else if($pbtheme_data['woo_force_imageflip']){
			the_post_thumbnail('shop_catalog', array( 'class'	=> "div_flip_back"));
		}
		?>
 		</div>
 	</div>
    
	</a>
    <?php if(!isset($pbtheme_data['woo_disable_carticon']) || !$pbtheme_data['woo_disable_carticon']){ ?>
	<div class="linked_image_buttons">
		<?php
			do_action( 'woocommerce_after_shop_loop_item' );
			$kklike = '';
			if ( in_array( 'kk-i-like-it/admin.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				echo '<div class="pbtheme_image_hover_button div_button_like inline-block">'.do_shortcode('[kklike_button]').'</div>';
			}
		?>
	</div>
    <?php } ?>
    
	</div>
	<?php
		$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
		echo $product->get_categories( ', ', '<span class="posted_in a-inherit pbtheme_header_font margin-bottom6 block">' . _n( '', '', $size, 'pbtheme' ) . ' ', '</span>' );
	?>
		<h3 class="product_title pbtheme_header_font a-inherit margin-bottom6"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
	<?php
		/**
		 * woocommerce_after_shop_loop_item_title hook
		 *
		 * @hooked woocommerce_template_loop_price - 10
		 */
		remove_action ( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		add_action ( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );
		do_action( 'woocommerce_after_shop_loop_item_title' );
	?>
</li>