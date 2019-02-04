<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $wp_query;

if ( ! woocommerce_products_will_display() )
	return;
?>
<p class="woocommerce-result-count">
	<?php
	$paged    = max( 1, $wp_query->get( 'paged' ) );
	$per_page = $wp_query->get( 'posts_per_page' );
	$total    = $wp_query->found_posts;
	$first    = ( $per_page * $paged ) - $per_page + 1;
	$last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );

	if ( 1 == $total ) {
		_e( 'Showing the single result', 'woocommerce' );
	} elseif ( $total <= $per_page || -1 == $per_page ) {
		$result_str = sprintf( __( 'Showing all %d results', 'woocommerce' ), $total );
		echo do_shortcode( sprintf( '[pbtheme_title type="h3" align="left" bot_margin="0"]%1$s[/pbtheme_title]', $result_str ) );
	} else {
		$result_str = sprintf( _x( 'Showing results %1$d - %2$d of %3$d results', '%1$d = first, %2$d = last, %3$d = total', 'woocommerce' ), $first, $last, $total );
		echo do_shortcode( sprintf( '[pbtheme_title type="h3" align="left" bot_margin="0"]%1$s[/pbtheme_title]', $result_str ) );

	}
	?>
</p>