<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $pbtheme_data, $post;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );

?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span>.</span>

	<?php endif; ?>

	<?php echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'woocommerce' ) . ' ', '.</span>' ); ?>

	<?php echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'woocommerce' ) . ' ', '.</span>' ); ?>
	
    <?php 
	$product_attributes = get_post_meta($product->id,'_product_attributes',true);
	if(!is_array($product_attributes)) $product_attributes = unserialize($product_attributes);
	
	foreach($product_attributes as $attribute){
		if($attribute['is_visible']==1 && $attribute['name']!='pa_color' && $attribute['name']!='pa_size'){ ?>
		<span class="product_attribute"><?php echo ucfirst($attribute['name']); ?>: <?php echo $attribute['value']; ?></span>
		<?php
		}
	}?>
	    
	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>

<?php
if($pbtheme_data['woo_enable_fb'] || $pbtheme_data['woo_enable_tw'] || $pbtheme_data['woo_enable_pin'] || $pbtheme_data['woo_enable_gplus']) echo "<br />";
if($pbtheme_data['woo_enable_fb']){ ?>
	<a href="//www.facebook.com/sharer.php?u=<?php echo get_permalink($post->ID); ?>" onclick="window.open(this.href,this.title,'width=500,height=500,top=300px,left=300px');  return false;" rel="nofollow" target="_blank" class="pbwoo-icon"><span class="pbwoo-icon-facebook"></span></a>
<?php }
if($pbtheme_data['woo_enable_tw']){ ?>
	<a href="//twitter.com/share?url=<?php echo get_permalink($post->ID); ?>" onclick="window.open(this.href,this.title,'width=500,height=500,top=300px,left=300px');  return false;" rel="nofollow" target="_blank" class="pbwoo-icon"><span class="pbwoo-icon-twitter"></span></a>
<?php }
if($pbtheme_data['woo_enable_pin']){ 
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
	?>
	<a href="//pinterest.com/pin/create/button/?url=<?php echo get_permalink($post->ID); ?>&amp;media=<?php echo $image[0]; ?>&amp;description=<?php echo urlencode(get_the_title($post->ID)); ?>" onclick="window.open(this.href,this.title,'width=500,height=500,top=300px,left=300px');  return false;" rel="nofollow" target="_blank" class="pbwoo-icon"><span class="pbwoo-icon-pinterest"></span></a>
<?php }
if($pbtheme_data['woo_enable_gplus']){ ?>
	<a href="//plus.google.com/share?url=<?php echo get_permalink($post->ID); ?>" target="_blank" onclick="window.open(this.href,this.title,'width=500,height=500,top=300px,left=300px');  return false;" rel="nofollow" class="pbwoo-icon"><span class="pbwoo-icon-google-plus"></span></a>
<?php }

?>