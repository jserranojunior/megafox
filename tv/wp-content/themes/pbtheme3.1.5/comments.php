<?php
/**
 * @package WordPress
 * @subpackage PBTheme Theme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
?>
<div id="comments">
	<?php if ( post_password_required() ) : ?>
	<div class="nopassword">
		<?php _e( 'This post is password protected. Enter the password to view any comments.', 'pbtheme' ); ?>
	</div>
<?php return; endif;?>

<?php if ( have_comments() ) : ?>

<?php echo do_shortcode('[pbtheme_title align="left"]'.__( 'Comments', 'pbtheme' ).'[/pbtheme_title]'); ?>


<ul class="comments_wrapper" id="comments_wrapper">
	<?php wp_list_comments( array( 'callback' => 'pbtheme_comment' ) ); ?>
</ul>
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
<nav id="comment-nav-below">
	<div class="nav-previous">
		<?php previous_comments_link( __( '&larr; Older Comments', 'pbtheme' ) ); ?>
	</div>
	<div class="nav-next">
		<?php next_comments_link( __( 'Newer Comments &rarr;', 'pbtheme' ) ); ?>
	</div>
</nav>
<?php
	endif;
	else :
	if ( comments_open() ) :
	else :
	if ( !comments_open() && !is_page() ) :
?>
<p class="nocomments">
  <?php _e( 'Comments are closed.', 'pbtheme' ); ?>
</p>
<?php
	endif;
	endif;
	endif;
?>
<?php if ( comments_open() ) : ?>
	<?php
		$fields =  array(
			'author' =>'<input id="author" class="pbtheme_dark_border input_field block margin-bottom20" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="' . __( 'Name', 'pbtheme' ) . '" />',

			'email' => '<input id="email" class="pbtheme_dark_border input_field block margin-bottom20" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="' . __( 'Email', 'pbtheme' ) . '"/>',

			'url' => '<input id="url" class="pbtheme_dark_border input_field block margin-bottom20" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . __( 'Website', 'pbtheme' ) . '" />'
		);
		comment_form( array('fields'=>$fields, 'comment_field' => '<textarea id="comment" name="comment" maxlength="500" class="pbtheme_dark_border textarea_field block" aria-required="true">' . __( 'Your message goes here (max 500 chars)', 'pbtheme' ) . '</textarea>', 'title_reply' => do_shortcode('[pbtheme_title align="left"]'.__( 'Leave a comment', 'pbtheme' ).'[/pbtheme_title]'), 'title_reply_to' => __( 'Leave a Reply to %s' , 'pbtheme' ), 'label_submit' => __( 'Send comment', 'pbtheme' )));
		?>
		<div class="clearfix"></div>

<?php endif; ?>
</div>