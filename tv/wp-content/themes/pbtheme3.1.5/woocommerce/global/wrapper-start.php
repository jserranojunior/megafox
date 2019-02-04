<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $pbtheme_data;

if ( is_product() ) {
	$widgets = 'product';
}
elseif ( is_shop() || is_product_category() || is_product_tag() ) {
	$widgets = 'shop';
}
else {
	$widgets = 'none';
}
echo '<div id="pbtheme_content" class="pbtheme_container">';
if ( $widgets !== 'none' ) :?>
	<?php if ( isset($pbtheme_data[$widgets.'-widgets-before']) || $pbtheme_data[$widgets.'-widgets-before'] !== 'none' ) : ?>
	<div class="anivia_row pbuilder_row">
	<div>
		<?php
			$widgets_before = $pbtheme_data[$widgets.'-widgets-before'];
			for ($i = 1; $i <= $widgets_before; $i++) {
				printf( '<div class="pbuilder_column pbuilder_column-1-%1$s">', $widgets_before );
				dynamic_sidebar($widgets.'-widgets-before-' . $i);
				printf( '</div><!-- pbuilder_column pbuilder_column-1-%1$s -->', $widgets_before );
			}
		?>
	</div>
	</div><!-- row -->
	<?php endif; ?>
<?php
endif;
echo '<div class="anivia_row pbuilder_row"><div>';