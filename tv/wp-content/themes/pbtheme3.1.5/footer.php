<?php
/**
 * @package WordPress
 * @subpackage PBTheme Theme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */

global $pbtheme_data;

if ( is_page() || is_single()) {
	$hidefooter = get_post_meta( get_the_ID(), 'pbtheme_hidefooter', true );
	$retargetpixel = do_shortcode(get_post_meta( get_the_ID(), 'pbtheme_retargetpixel', true ));
}


// Do we hide the menu
	if ( !(isset($hidefooter) && $hidefooter === '1') ) {
	
	ob_start();
	pbtheme_header_elements('left', 'footer');
	$footer_left_elemets = ob_get_contents();
	ob_end_clean();
	
	ob_start();
	pbtheme_header_elements('right', 'footer');
	$footer_right_elemets = ob_get_contents();
	ob_end_clean();

?>
		<div class="footer_wrapper solid_box<?php printf( ' layout-%1$s', $pbtheme_data['header_layout']); ?>">

				<div class="pbtheme_container_max footer_container">
					<?php if ( $pbtheme_data['footer_widgets'] !== '1' ) : ?>
                    <div class="footer_main_widgets">
						
						<div class="anivia_row pbuilder_row">
						<div>
							<?php
								$footer_sidebar = $pbtheme_data['footer_sidebar'];
								for ($i = 1; $i <= $footer_sidebar; $i++) {
									printf( '<div class="pbuilder_column pbuilder_column-1-%1$s">', $footer_sidebar );
									dynamic_sidebar('footer-' . $i);
									printf( '</div><!-- pbuilder_column pbuilder_column-1-%1$s -->', $footer_sidebar );
								}
							?>
						</div>
						</div>
						
					</div>
                    <?php endif; ?>
				</div>
				
                <?php if(strlen($footer_left_elemets)>0 || strlen($footer_right_elemets)>0){ ?>
				<div class="small_separator"></div>
				<div class="header_pbtheme_bottom pbtheme_dark_border"></div>

				<div class="pbtheme_container_max footer_container">
					<div class="pbtheme_footer_bottom">
						<div class="pbtheme_top pbtheme_bottom pbtheme_header_font">
							<div class="pbtheme_top_left float_left">
								<?php echo $footer_left_elemets; ?>
							</div>
							<div class="pbtheme_top_right float_right">
								<?php echo $footer_right_elemets; ?>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
                <?php } ?>
                
			</div>

		</div>

</div>

<?php
} // End check for Hide Footer

 wp_footer();
 
 if (!empty($retargetpixel)){
	 echo $retargetpixel;
 }
 

 ?>
 
</body>
</html>