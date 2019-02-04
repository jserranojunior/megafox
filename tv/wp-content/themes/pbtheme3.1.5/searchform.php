<?php
/**
 * @package WordPress
 * @subpackage AllAround Theme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */
?>
<div class="search-form">
<form action="<?php echo home_url( '/' ); ?>" method="get">
	<div class="search_box">
		<input name="s" type="text" value="<?php _e('Search the website', 'pbtheme'); ?>" class="input_field border-color-main" />
		<input type="submit"  class="submit_button background-color-main hover-background-color-lighter-main" value="<?php _e('Search', 'pbtheme'); ?>" />
		<div class="clearfix"></div>
	</div>
</form>
</div>