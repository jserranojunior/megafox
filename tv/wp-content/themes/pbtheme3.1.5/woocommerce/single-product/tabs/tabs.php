<?php
/**
 * Single Product tabs
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper pbuilder_row anivia_row pbtheme_hidden_flow">
		<div>
		<?php
			$i = 0;
            unset($tabs['additional_information']);
			$columns = ( array_key_exists('additional_information', $tabs) ? 'entry-content pbuilder_column pbuilder_column-1-2' : 'entry-content pbuilder_column pbuilder_column-1-1' );
			foreach ( $tabs as $key => $tab ) :
			$i++;
			if ( $key == 'reviews' ) {
				echo '<div class="clearfix"></div>';
				$columns = 'entry-content pbuilder_column pbuilder_column-1-1';
			}
		?>
		<div class="<?php echo $columns; ?>" id="tab-<?php echo $key ?>">
			<?php
				if ( $key !== 'reviews' ) {
					echo do_shortcode( sprintf( '[pbtheme_title type="h3" align="left" bot_margin="24"]%1$s[/pbtheme_title]', $tab['title'], get_permalink() ) );
				}
				call_user_func( $tab['callback'], $key, $tab );
			?>
		</div>
		<?php
			endforeach;
		?>
		</div>
	</div>
<?php endif; ?>