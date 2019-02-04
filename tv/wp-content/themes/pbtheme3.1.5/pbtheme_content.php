<?php
/**
 * @package WordPress
 * @subpackage PBTheme Theme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */

	$out = '';
	global $pbtheme_data, $pbtheme_post_counter;
	$feat_area = '';
	$heading = '';
	
	global $div_blog_type;

	if ( isset($div_blog_type) ) {
		$div_blog = $div_blog_type;
	}
	else {
		$div_blog = $pbtheme_data['blog_layout'];
	}

	switch ($div_blog) {
		case 'default' :
			$columns = 2;
			$image_size = 'pbtheme-blog';
			$words = 192;
			$align = 'left';
		break;
		case 1 :
			$columns = 1;
			$image_size = 'pbtheme-blog';
			$words = 1001;
			$align = 'left';
		break;
		case 2 :
			$columns = 2;
			$image_size = 'pbtheme-blog';
			$words = 192;
			$align = 'left';
		break;
		case 3 :
			$columns = 3;
			$image_size = 'pbtheme-blog';
			$words = 128;
			$align = 'left';
		break;
		case 4 :
			$columns = 4;
			$image_size = 'pbtheme-blog';
			$words = 72;
			$align = 'left';
		break;
		case 5 :
			$columns = 5;
			$image_size = 'pbtheme-blog';
			$words = 48;
			$align = 'left';
		break;
		case 6 :
			$columns = 1;
			$image_size = 'pbtheme-fullblog';
			$words = 768;
			$align = 'right';
		break;
	}

	if ( is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';

	$out .= '<div class="'.implode(' ', get_post_class()).' pbuilder_column pbuilder_column-1-'.$columns.' margin-bottom36"><div class="headline_highlighted_column_block">';
	$feat_area .= pbtheme_get_featarea($image_size, get_post_format());

	$heading .= '<h3 class="entry-title"><a href="'.get_permalink().'" rel="bookmark">'.$sticky_icon.get_the_title().'</a></h3>';
	if ( get_post_format() !== 'quote' ) {
		if ( $image_size == 'pbtheme-fullblog' ) {
			$timecode = get_the_date().' @ '.get_the_time();
			$num_comments = get_comments_number();
			if ( comments_open() ) {
				if ( $num_comments == 0 ) {
					$comments = __('Leave a comment', 'pbtheme');
				} elseif ( $num_comments > 1 ) {
					$comments = $num_comments . __(' Comments', 'pbtheme');
				} else {
					$comments = __('1 Comment', 'pbtheme');
				}
				$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
			} else {
				$write_comments =  __('Comments are off for this post.', 'pbtheme');
			}

			$heading .= '<div class="posts_meta"><div class="div_date_meta inline-block post-date date updated">'.$timecode.'</div><div class="div_author_meta inline-block a-inherit author">'.__('by', 'anivia').' '.get_the_author_link().'</div><div class="div_category_meta inline-block a-inherit">'.__('in', 'pbtheme').' '.get_the_category_list( ', ' ).'</div><div class="div_comment_meta inline-block a-inherit">'.$write_comments.'</div></div>';
		}
		else {
			$timecode = get_the_date();
			$heading .= '<div class="posts_meta"><div class="div_date_meta inline-block post-date date updated">'.$timecode.'</div><div class="div_category_meta inline-block a-inherit">'.__('in', 'pbtheme').' '.get_the_category_list( ', ' ).'</div></div>';
		}
	}

	$out .= $feat_area . $heading;
	$excerpt = get_the_excerpt();
	$out .= '<div class="text margin-top6 margin-bottom6">'. pbtheme_string_limit_words( $excerpt, $words ).'</div>';
	if ( $div_blog = 6 ) {
		$out .= '<div class="clearfix"></div>';
	}
	$out .= do_shortcode( sprintf( '[pbtheme_title color="small_separator_pale" link="%2$s" type="h5" align="%3$s" bot_margin="0"]%1$s[/pbtheme_title]', __('Read more', 'pbtheme'), get_permalink(), $align ) );
	$out .= '</div></div>';
	if ( $pbtheme_post_counter == $columns ) {
		$out .= '<div class="clearfix"></div>';
		$pbtheme_post_counter = 0;
	}

	echo $out;

?>