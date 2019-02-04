<?php
/**
 * @package WordPress
 * @subpackage PBTheme Theme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */

get_header();
global $pbtheme_data, $pbtheme_post_counter;
$pbtheme_post_counter  = 0;
if ( $pbtheme_data['sidebar-blog'] == 1 ) {
	switch ($pbtheme_data['sidebar-size']):
	case '3' :
	$blog_class = 'pbuilder_column pbuilder_column-2-3 pbtheme_hidden_flow margin-bottom36';
	$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-3 margin-bottom36';
	break;
	case '4' :
	$blog_class = 'pbuilder_column pbuilder_column-3-4 pbtheme_hidden_flow margin-bottom36';
	$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-4';
	break;
	case '5' :
	$blog_class = 'pbuilder_column pbuilder_column-4-5 pbtheme_hidden_flow margin-bottom36';
	$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-5';
	break;
	endswitch;
}
else {
	$blog_class = 'pbuilder_column pbuilder_column-1-1';
}
$pbtheme_page_width = get_post_meta(get_the_ID(), 'pbtheme_page_width', true );
?>
	<div id="pbtheme_content" class="pbtheme_container" style="<?php if ($pbtheme_page_width != '') echo ' max-width: '.$pbtheme_page_width.'; ';?>">
		<?php if ( isset($pbtheme_data['blog-widgets-before']) && $pbtheme_data['blog-widgets-before'] !== 'none' ) : ?>
		<div class="anivia_row pbuilder_row">
		<div>
			<?php
				$blog_widgets_before = $pbtheme_data['blog-widgets-before'];
				for ($i = 1; $i <= $blog_widgets_before; $i++) {
					printf( '<div class="pbuilder_column pbuilder_column-1-%1$s">', $blog_widgets_before );
					dynamic_sidebar('blog-widgets-before-' . $i);
					printf( '</div><!-- pbuilder_column pbuilder_column-1-%1$s -->', $blog_widgets_before );
				}
			?>
		</div>
		</div><!-- row -->
		<?php endif; ?>
		<div class="anivia_row pbuilder_row">
		<div>
			<?php if ( $pbtheme_data['sidebar-blog'] == 1 && $pbtheme_data['sidebar-position'] == 1 ) : ?>
			<div class="<?php echo $sidebar_class; ?>">
				<?php dynamic_sidebar( 'sidebar-blog' ); ?>
			</div>
			<?php endif; ?>
			<?php if ( get_query_var('paged') ) { $paged = get_query_var('paged'); } elseif ( get_query_var('page') ) { $paged = get_query_var('page'); } else { $paged = 1; } ?>
			<div id="content" class="<?php echo $blog_class; ?>"><div>

			<?php
					echo do_shortcode('[pbtheme_title align="left"]'.__('No posts found / Showing random posts', 'pbtheme').'[/pbtheme_title]');
					echo do_shortcode('[pbtheme_insert_posts type="2" rows="5" orderby="rand" pagination="no"]');
			?>
			</div></div>
			<?php if ( $pbtheme_data['sidebar-blog'] == 1 && $pbtheme_data['sidebar-position'] == 0 ) : ?>
			<div class="<?php echo $sidebar_class; ?>">
				<?php dynamic_sidebar( 'sidebar-blog' ); ?>
			</div>
			<?php endif; ?>
		</div>
		</div>
		<?php if ( isset($pbtheme_data['blog-widgets-after']) && $pbtheme_data['blog-widgets-after'] !== 'none' ) : ?>
		<div class="anivia_row pbuilder_row">
		<div>
			<?php
				$blog_widgets_after = $pbtheme_data['blog-widgets-after'];
				for ($i = 1; $i <= $blog_widgets_after; $i++) {
					printf( '<div class="pbuilder_column pbuilder_column-1-%1$s">', $blog_widgets_after );
					dynamic_sidebar('blog-widgets-after-' . $i);
					printf( '</div><!-- pbuilder_column pbuilder_column-1-%1$s -->', $blog_widgets_after );
				}
			?>
		</div>
		</div>
		<?php endif; ?>
	</div>
<?php get_footer(); ?>