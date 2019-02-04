<?php
/**
 * @package WordPress
 * @subpackage PBTheme Theme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */

get_header();
global $pbtheme_data;
if ( $pbtheme_data['sidebar-bbpress'] == 1 ) {
	switch ($pbtheme_data['sidebar-bbpress-size']):
	case '3' :
	$blog_class = 'pbuilder_column pbuilder_column-2-3 margin-bottom36';
	$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-3';
	break;
	case '4' :
	$blog_class = 'pbuilder_column pbuilder_column-3-4 margin-bottom36';
	$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-4';
	break;
	case '5' :
	$blog_class = 'pbuilder_column pbuilder_column-4-5 margin-bottom36';
	$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-5';
	break;
	endswitch;
}
else {
	$blog_class = 'pbuilder_column pbuilder_column-1-1 margin-bottom36';
}
$pbtheme_page_width = get_post_meta(get_the_ID(), 'pbtheme_page_width', true );
?>
	<div id="pbtheme_content" class="pbtheme_container" style="<?php if ($pbtheme_page_width != '') echo ' max-width: '.$pbtheme_page_width.'; ';?>">
		<?php if ( isset($pbtheme_data['bbpress-widgets-before']) && $pbtheme_data['bbpress-widgets-before'] !== 'none' ) : ?>
		<div class="anivia_row pbuilder_row">
		<div>
			<?php
				$bbpress_widgets_before = $pbtheme_data['bbpress-widgets-before'];
				for ($i = 1; $i <= $bbpress_widgets_before; $i++) {
					printf( '<div class="pbuilder_column pbuilder_column-1-%1$s">', $bbpress_widgets_before );
					dynamic_sidebar('bbpress-widgets-before-' . $i);
					printf( '</div><!-- pbuilder_column pbuilder_column-1-%1$s -->', $bbpress_widgets_before );
				}
			?>
		</div>
		</div><!-- row -->
		<?php endif; ?>
		<div class="anivia_row pbuilder_row">
		<div>
			<?php if ( $pbtheme_data['sidebar-bbpress'] == 1 && $pbtheme_data['sidebar-bbpress-position'] == 1 ) : ?>
			<div class="<?php echo $sidebar_class; ?>">
				<?php dynamic_sidebar( 'sidebar-bbpress' ); ?>
			</div>
			<?php endif; ?>
			<?php if ( have_posts() ) : ?>
			<div id="content" class="<?php echo $blog_class; ?>">
				<?php
					if ( bbp_is_forum_archive() ) {
						echo do_shortcode(sprintf('[pbtheme_title align="left"]%1$s[/pbtheme_title]', $pbtheme_data['bbpress_forum']));
					}
				?>
				<?php the_post(); ?>
				<?php the_content(); ?>
			</div>
			<?php
				else :
					_e('No posts','pbtheme');
				endif;
			?>
			<?php if ( $pbtheme_data['sidebar-bbpress'] == 1 && $pbtheme_data['sidebar-bbpress-position'] == 0 ) : ?>
			<div class="<?php echo $sidebar_class; ?>">
				<?php dynamic_sidebar( 'sidebar-bbpress' ); ?>
			</div>
			<?php endif; ?>
		</div>
		</div>
		<?php if ( isset($pbtheme_data['bbpress-widgets-after']) && $pbtheme_data['bbpress-widgets-after'] !== 'none' ) : ?>
		<div class="anivia_row pbuilder_row">
		<div>
			<?php
				$bbpress_widgets_after = $pbtheme_data['bbpress-widgets-after'];
				for ($i = 1; $i <= $bbpress_widgets_after; $i++) {
					printf( '<div class="pbuilder_column pbuilder_column-1-%1$s">', $bbpress_widgets_after );
					dynamic_sidebar('bbpress-widgets-after-' . $i);
					printf( '</div><!-- pbuilder_column pbuilder_column-1-%1$s -->', $bbpress_widgets_after );
				}
			?>
		</div>
		</div>
		<?php endif; ?>
	</div>
<?php get_footer(); ?>