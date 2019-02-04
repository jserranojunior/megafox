<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $pbtheme_data;
?>
	</div>
	</div>
<?php
	if ( is_product() ) {
		$widgets = 'product';
	}
	elseif ( is_shop() || is_product_category() || is_product_tag() ) {
		$widgets = 'shop';
	}
	else {
		$widgets = 'none';
	}

	if ( $widgets !== 'none' ) :?>
		<?php if ( isset($pbtheme_data[$widgets.'-widgets-after']) || $pbtheme_data[$widgets.'-widgets-after'] !== 'none' ) : ?>
		<div class="anivia_row pbuilder_row">
		<div>
			<?php
				$widgets_after = $pbtheme_data[$widgets.'-widgets-after'];
				for ($i = 1; $i <= $widgets_after; $i++) {
					printf( '<div class="pbuilder_column pbuilder_column-1-%1$s">', $widgets_after );
					dynamic_sidebar($widgets.'-widgets-after-' . $i);
					printf( '</div><!-- pbuilder_column pbuilder_column-1-%1$s -->', $widgets_after );
				}
			?>
		</div>
		</div><!-- row -->
		<?php endif; ?>
	<?php
	endif;
?>
</div>