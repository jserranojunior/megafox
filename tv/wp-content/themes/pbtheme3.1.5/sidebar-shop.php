<?php
	global $pbtheme_data;
	if ( is_product() ) {
		$sidebar = 'sidebar-woo-single';
		$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-3';
	}
	else {
		$sidebar = 'sidebar-woo';
		switch ($pbtheme_data['sidebar-size-woo']):
		case '3' :
			$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-3';
		break;
		case '4' :
			$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-4';
		break;
		case '5' :
			$sidebar_class = 'sidebar_wrapper pbuilder_column pbuilder_column-1-5';
		break;
		endswitch;
	}
?>
<div class="<?php echo $sidebar_class; ?>">
	<?php dynamic_sidebar( $sidebar ); ?>
</div>