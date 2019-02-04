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
			<?php if ( have_posts() ) : ?>
			<?php if ( get_query_var('paged') ) { $paged = get_query_var('paged'); } elseif ( get_query_var('page') ) { $paged = get_query_var('page'); } else { $paged = 1; } ?>
			<div id="content" class="<?php echo $blog_class; ?>">
			<?php
				echo do_shortcode('[pbtheme_title align="left"]' . get_bloginfo('name') . '[/pbtheme_title]');
			?>
			<?php
				if ( $pbtheme_data['blog_layout'] == '1' ) :
					$query_string_ajax = http_build_query($wp_query->query_vars).'&paged=0&post_status=publish';
					printf( '<div class="blog_content_infinite infinite-load-target anivia_row pbuilder_row" data-string="%1$s"><ul class="infinite-load-init">', $query_string_ajax);
					while ( have_posts() ) : the_post();
						$pbtheme_post_counter++;
						get_template_part( 'pbtheme_blocks' );
					endwhile;
					echo '</ul></div><div class="clearfix"></div>';
					echo '<div class="text-center fullwidth"><div class="infinite-load-button margin-top20 margin-bottom20" data-page="'.$paged.'">'.__('Load more posts', 'anivia').'</div></div>';
				elseif ($pbtheme_data['blog_layout'] == '7'):
					while ( have_posts() ) : the_post();
						$pbtheme_post_counter++;
						get_template_part( 'pbtheme_small' );
					endwhile;
					echo pbtheme_pagination($wp_query->max_num_pages, $paged, 2, 'no');
				else :
			?>
				<div class="pbtheme_type_<?php echo $pbtheme_data['blog_layout']; ?> anivia_row pbuilder_row">
				<div>
			<?php
				while ( have_posts() ) : the_post();
					$pbtheme_post_counter++;
					get_template_part( 'pbtheme_content' );
				endwhile;
			?>
				</div>
				</div>
			<div class="clearfix"></div>
			<?php
				echo pbtheme_pagination($wp_query->max_num_pages, $paged, 2, 'no');
				endif;
			?>
			</div>
			<?php
				else :
					_e('No posts','pbtheme');
				endif;
			?>
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