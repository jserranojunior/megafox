<?php
/**
 * Display single product reviews (comments)
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.2
 */
/*
 * Change By Asim Ashraf - DevBatch
 * File version changed 2.1.0 to 2.3.2
 * Date: 12 Feb 2015
 */
global $woocommerce, $product;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$count = $product->get_rating_count();
if ( ! comments_open() )
	return;
?>
<div id="reviews" class="pbuilder_row anivia_row">
	<div>
<?php

	$title_reply = '';
	if ( $count > 0 ) :
		echo '<div id="comments" class="pbuilder_column pbuilder_column-1-2">';
		echo do_shortcode( sprintf( '[pbtheme_title type="h3" align="left" bot_margin="24"]%1$s (%2$s)[/pbtheme_title]', ( $count == 1 ? __('Review', 'pbtheme') : __('Reviews', 'pbtheme') ), $count ) );

		if ( have_comments() ) :

			echo '<ol class="commentlist">';
			wp_list_comments( array( 'callback' => 'woocommerce_comments' ) );
			echo '</ol>';
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
					'prev_text' 	=> '&larr;',
					'next_text' 	=> '&rarr;',
					'type'			=> 'list',
				) ) );
				echo '</nav>';
			endif;

			$title_reply = __( 'Add a review', 'pbtheme' );
		else :
			$title_reply = __( 'Be the first to review', 'pbtheme' ).' &ldquo;'.$post->post_title.'&rdquo;';
			echo '<p class="noreviews">'.__( 'There are no reviews yet, would you like to', 'pbtheme').'<a href="#review_form" class="inline show_review_form">'.__('submit yours', 'pbtheme').'</a>?</p>';
		endif;
		$commenter = wp_get_current_commenter();
		echo '</div>';
	endif;
?>

<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->id ) ) : ?>
<?php
	echo '<div id="review_form_wrapper" class="'.( $count > 0 ? 'pbuilder_column pbuilder_column-1-2' : 'pbuilder_column pbuilder_column-1-1' ).'">';

	echo do_shortcode( sprintf( '[pbtheme_title type="h3" align="left" bot_margin="24"]%1$s[/pbtheme_title]', __('Add a Review', 'pbtheme') ) );

	echo '<div id="review_form">';

	$add_rating;
	if ( get_option('woocommerce_enable_review_rating') == 'yes' ) {
		$add_rating = '<p class="comment-form-rating"><label for="rating">' . __( 'Rating', 'pbtheme' ) .'</label><select name="rating" id="rating">
			<option value="">'.__( 'Rate&hellip;', 'pbtheme' ).'</option>
			<option value="5">'.__( 'Perfect', 'pbtheme' ).'</option>
			<option value="4">'.__( 'Good', 'pbtheme' ).'</option>
			<option value="3">'.__( 'Average', 'pbtheme' ).'</option>
			<option value="2">'.__( 'Not that bad', 'pbtheme' ).'</option>
			<option value="1">'.__( 'Very Poor', 'pbtheme' ).'</option>
		</select></p>';
	}

	$comment_form = array(
		'title_reply' => $title_reply,
		'comment_notes_before' => '',
		'comment_notes_after' => '',
		'fields' => array(
			'author' => $add_rating.'<p class="comment-form-author">' .
			            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" placeholder="' . __( 'Name (Required)', 'pbtheme' ) . '"/></p>',
			'email'  => '<p class="comment-form-email">' .
			            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" placeholder="' . __( 'Email (Required)', 'pbtheme' ) . '"/></p>',
		),
		'label_submit' => __( 'Submit Review', 'pbtheme' ),
		'logged_in_as' => $add_rating,
		'comment_field' => ''
	);

		$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . __( 'Your Review', 'pbtheme' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>' . wp_nonce_field( 'woocommerce-comment_rating', '_wpnonce', true, false ) . '</p>';

	comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );

	echo '</div></div>';
?>
	<?php else : ?>
		<p class="woocommerce-verification-required"><?php _e( 'Only logged in customers who have purchased this product may leave a review.', 'pbtheme' ); ?></p>
	<?php endif; ?>

	<div class="clear"></div>
	</div>
</div>