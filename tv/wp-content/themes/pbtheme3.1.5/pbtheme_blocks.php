<?php
/**
 * @package WordPress
 * @subpackage PBTheme Theme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */

if ( is_sticky() ) $sticky_icon = '<i class="icon-pushpin"></i>'; else $sticky_icon = '';
$category = get_the_category(); 
if($category[0]){
	$cat = $category[0]->term_id;
}
$prod_extras = get_option(Category_Extras);
if ( isset($prod_extras[$cat]['catcolor']) ) $category_color = $prod_extras[$cat]['catcolor']; else $category_color = '#888888';

$rgb_category_color = implode(',', pbtheme_hex2rgb($category_color));

if ( has_post_thumbnail() ) 
	$magazine = array (
		'class' => 'magazine_image_column magazine_image_column_item fullmaxwidth relative float_left margin-bottom20',
		'style' => 'style="background:rgb('.$rgb_category_color.');"','style-no-image' => '' ); else $magazine = array ( 'class' => 'magazine_image_column magazine_no_image_column_item fullwidth float_left margin-bottom20', 'style' => 'style="background:rgba('.$rgb_category_color.',0.85);"', 'style-no-image' => 'style="background:rgb('.$rgb_category_color.')"' );
?>
<li><div <?php post_class($magazine['class']); ?> <?php echo $magazine['style-no-image']; ?>>
	<?php echo pbtheme_get_featarea('pbtheme-blog'); ?>
		<div class="category_tag">
		<?php
			$tags = get_the_tags();
			if ( $tags ) {
				shuffle( $tags );
				$mag_tags = '';
				foreach ( $tags as $tag ) {
					$tag_link = get_tag_link( $tag->term_id );
					$mag_tags .= "<div class='mag_tag'><a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>";
					$mag_tags .= "{$tag->name}</a><span class='tag_block' {$magazine["style"]}></span></div>";
				}
				echo $mag_tags;	
			}
		?>
		</div>
		<div class="hover_effect_wrapper" <?php echo $magazine['style']; ?>>
			<div class="hover_transparent not-transparent pbtheme_pale_border">
				<div class="posts_meta">
					<div class="div_date_meta inline-block">
					<?php
						$timecode = get_the_date();
						echo $timecode;
					?>
					</div>
					<div class="div_category_meta inline-block a-inherit">
					<?php
						echo __('in', 'pbtheme').' '.get_the_category_list( ', ' );
					?>
					</div>
				</div>
				
				<h3 class="margin-bottom12 a-inherit"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php printf('%1$s %2$s', $sticky_icon, get_the_title() ); ?></a></h3>
				<div class="post_excerpt">
				<?php
					$excerpt = get_the_excerpt();
					echo pbtheme_string_limit_words( $excerpt, 196 );
				?>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</li>