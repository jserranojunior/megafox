<?php
/**
 * @package WordPress
 * @subpackage PBTheme Theme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */

get_header();
global $pbtheme_data;
$pbtheme_page_width = get_post_meta(get_the_ID(), 'pbtheme_page_width', true );
?>
	<div id="pbtheme_content">
		<div class="pbtheme_container" style="<?php if ($pbtheme_page_width != '') echo ' max-width: '.$pbtheme_page_width.'; ';?>">
		<?php if ( have_posts() ) : ?>
		<div id="content" <?php post_class(); ?>>
			<?php the_post(); ?>
			<?php the_content(); ?>
		</div>
		<?php if ( $pbtheme_data['enable_comments'] == 1 )comments_template(); ?>
		<?php else : ?>
		<div id="content">
			<?php _e('No ','pbtheme'); ?>
		</div>
		<?php endif; ?>
		</div>
	</div>
<?php get_footer(); ?>