<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $product, $pbtheme_data, $post;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo do_shortcode(sprintf('[pbtheme_title type="h3" align="left" add_element="itemprop=\'name\'"]%1$s[/pbtheme_title]', get_the_title()));

?>




