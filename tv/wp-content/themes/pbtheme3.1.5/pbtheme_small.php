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

	$columns = 2;
	$image_size = 'pbtheme-square';
	$words = 500;
	$align = 'right';

	if ( is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';

	$out .= '<div class="'.implode(' ', get_post_class()).' margin-bottom24"><div class="headline_highlighted_column_block">';
	if ( get_post_format() == 'quote' ) {
		$feat_area .= '<div class="div_featarea div_feat_quote pbtheme_header_font margin-bottom36">'.get_the_content().'</div>';
	}
	elseif ( has_post_thumbnail() ) {
		$feat_area .= '<div class="div_featarea div_feat_small">'.get_the_post_thumbnail(get_the_ID(),'pbtheme-square');
		$feat_area .= sprintf( '<div class="pbtheme_image_hover"><div><a href="%1$s" class="pbtheme_image_hover_button" rel="bookmark"><i class="divicon-plus"></i></a></div></div></div>', get_permalink() );
	}

	$heading .= '<h3 class="entry-title"><a href="'.get_permalink().'" rel="bookmark">'.$sticky_icon.get_the_title().'</a></h3>';
	if ( get_post_format() !== 'quote' ) {
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

	$out .= $feat_area . $heading;
	$excerpt = get_the_excerpt();
	$out .= '<div class="text margin-top6 margin-bottom24">'. pbtheme_string_limit_words( $excerpt, $words ).'</div>';
	$out .= '<div class="clearfix"></div>';
	$out .= do_shortcode( sprintf( '[pbtheme_title color="small_separator_pale" link="%2$s" type="h5" align="%3$s" bot_margin="0"]%1$s[/pbtheme_title]', __('Read more', 'pbtheme'), get_permalink(), $align ) );
	$out .= '</div></div>';

	echo $out;

?>