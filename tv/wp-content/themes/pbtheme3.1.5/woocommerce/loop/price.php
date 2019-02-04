<?php
/**
 * Loop Price
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="price pbtheme_header_font">
	<span class="title_header_line pbtheme_dark_border"></span>
	<?php
		echo do_shortcode( sprintf( '[pbtheme_title type="h5" align="center" bot_margin="0"]%1$s[/pbtheme_title]', $price_html ) );
	?>
	</span>
<?php endif; ?>