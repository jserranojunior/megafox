<?php
/**
 * @package WordPress
 * @subpackage pbtheme Theme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */

add_filter( 'widget_text', 'do_shortcode' );

if ( DIVWP_FBUILDER === true ) :

global $pbtheme_data, $pbuilder;

$counter = 0;

$pbtheme_categories = get_categories(array('order'=>'desc'));
$pbtheme_ready_categories = array( '-1' => __('All', 'pbtheme') );
$pbtheme_margin = $pbtheme_data['fb_bmargin'];

foreach ( $pbtheme_categories as $category ) {
	$counter++;
	$pbtheme_ready_categories = $pbtheme_ready_categories + array($category->term_id=>$category->name);
}

$pbtheme_ready_contacts = array();
$counter = 0;

if ( is_array( $pbtheme_data['contact'] ) ) $pbtheme_contacts = $pbtheme_data['contact']; else return;
foreach ( $pbtheme_contacts as $contact ) {
	$counter++;
	$pbtheme_ready_contacts = $pbtheme_ready_contacts+array($counter=>$contact['name']);
}

	$insert_posts_selected = 1;

$querystr = "
	SELECT $wpdb->posts.ID, $wpdb->posts.post_title
	FROM $wpdb->posts
	WHERE $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_type = 'post'
	ORDER BY $wpdb->posts.post_date DESC
	";
$posts_array = $wpdb->get_results($querystr, OBJECT);
$pbuilder_wp_posts = array();
$first_post = '';
foreach($posts_array as $key => $obj) {
	if($first_post == '') $first_post = $obj->ID;
	$pbuilder_wp_posts[$obj->ID] = $obj->post_title;
}

$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => true ));
$pbuilder_menus = array();
$pbuilder_menu_std = '';
if(is_array($nav_menus)) 
	foreach($nav_menus as $menu_div) {
		if($pbuilder_menu_std == '') $pbuilder_menu_std = $menu_div->slug;
		$pbuilder_menus[$menu_div->slug] = $menu_div->name; 
	}

if($pbuilder){
    $admin_optionsDB = $pbuilder->option();
    $opts = array();
    foreach($admin_optionsDB as $opt) {
    	if(isset($opt->name) && isset($opt->value))
    		$opts[$opt->name] = $opt->value;
    }
}

$animationList = array(
	'none' => __('None', 'pbtheme'),
	'flipInX' => __('Flip in X', 'pbtheme'),
	'flipInY' => __('Flip in Y', 'pbtheme'),
	'fadeIn' => __('Fade in', 'pbtheme'),
	'fadeInDown' => __('Fade in from top', 'pbtheme'),
	'fadeInUp' => __('Fade in from bottom', 'pbtheme'),
	'fadeInLeft' => __('Fade in from left', 'pbtheme'),
	'fadeInRight' => __('Fade in from right', 'pbtheme'),
	'fadeInDownBig' => __('Slide in from top', 'pbtheme'),
	'fadeInUpBig' => __('Slide in from bottom', 'pbtheme'),
	'fadeInLeftBig' => __('Slide in from left', 'pbtheme'),
	'fadeInRightBig' => __('Slide in from right', 'pbtheme'),
	'bounceIn' => __('Bounce in', 'pbtheme'),
	'bounceInDown' => __('Bounce in from top', 'pbtheme'),
	'bounceInUp' => __('Bounce in from bottom', 'pbtheme'),
	'bounceInLeft' => __('Bounce in from left', 'pbtheme'),
	'bounceInRight' => __('Bounce in from right', 'pbtheme'),
	'rotateIn' => __('Rotate in', 'pbtheme'),
	'rotateInDownLeft' => __('Rotate in from top-left', 'pbtheme'),
	'rotateInDownRight' => __('Rotate in from top-right', 'pbtheme'),
	'rotateInUpLeft' => __('Rotate in from bottom-left', 'pbtheme'),
	'rotateInUpRight' => __('Rotate in from bottom-right', 'pbtheme'),
	'lightSpeedIn' => __('Lightning speed', 'pbtheme'),
	'rollIn' => __('Roll in', 'pbtheme')
);

$animationControl = array(
	'group_animate' => array(
		'type' => 'collapsible',
		'label' => __('Animation','pbtheme'),
		'options' => array(
			'animate' => array(
				'type' => 'select',
				'label' => __('Type:','pbtheme'),
				'std' => 'none',
				'label_width' => 0.25,
				'control_width' => 0.75,
				'options' => $animationList
			),
			'animation_group' => array(
				'type' => 'input',
				'label' => __('Group:','pbtheme'),
				'std' => '',
				'half_column' => 'true'
			),
			'animation_delay' => array(
				'type' => 'number',
				'label' => __('Delay:','pbtheme'),
				'std' => 0,
				'unit' => 'ms',
				'min' => 0,
				'step' => 50,
				'max' => 10000,
				'half_column' => 'true'
			)
		)
	)
);

if(isset($opts['css_classes']) && $opts['css_classes'] == 'true') {
	$classControl = array(
		'group_css' => array(
			'type' => 'collapsible',
			'label' => __('ID & Custom CSS','pbtheme'),
			'options' => array(
				'shortcode_id' => array(
					'type' => 'input',
					'label' => __('ID:','pbtheme'),
					'desc' => __('For linking via hashtags','pbtheme'),
					'label_width' => 0.25,
					'control_width' => 0.75,
					'std' => ''
				),
				'class' => array(
					'type' => 'input',
					'label' => __('Class:','pbtheme'),
					'desc' => __('For custom css','pbtheme'),
					'label_width' => 0.25,
					'control_width' => 0.75,
					'std' => ''
				)
			)
		)
	);
	$tabsId = array(
		'custom_id' => array(
			'type' => 'input',
			'label' => __('Tab ID:','pbtheme'),
			'desc' => __('For use of anchor in url. Make sure that this ID is unique on the page.','pbtheme'),
			'label_width' => 0.25,
			'std' => ''
		)
	);
}
else {
	$classControl = array();
	$tabsId = array();
}



/* -------------------------------------------------------------------------------- */
/* TABS */
/* -------------------------------------------------------------------------------- */

$tabs = array(
	'tabs' => array(
		'type' => 'draggable',
		'text' => __('Tabs','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/tabs.png',
		'function' => 'pbuilder_tabs',
		'group' => __('Basic', 'pbtheme'),
		'options'  => array_merge(
		 array(
		 	'group_basic' => array(
				'type' => 'collapsible',
				'label' => __('Basic','pbtheme'),
				'open' => 'true',
				'options' => array(
					'style' => array(
						'type' => 'select',
						'label' => __('Style','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'pbtheme',
						'options' => array(
							'pbtheme' => __('PBTheme', 'pbtheme'),
							'clean' => __('Clean','pbtheme'),
							'squared' => __('Squared','pbtheme'),
							'rounded' => __('Rounded','pbtheme')
						)
					)
				)
			),
			'group_tab_colors' => array(
				'type' => 'collapsible',
				'label' => __('Tab Colors','pbtheme'),
				'open' => 'true',
				'options' => array(
					'title_color' => array(
						'type' => 'color',
						'label' => __('Title:','pbtheme'),
						'std' => $opts['title_color']
					),
					'active_tab_title_color' => array(
						'type' => 'color',
						'label' => __('Active title:','pbtheme'),
						'std' => $opts['title_color'],
						'hide_if' => array(
							'style' => array('clean', 'pbtheme')
						)
					),
					'tab_back_color' => array(
						'type' => 'color',
						'label' => __('Background:','pbtheme'),
						'std' => $opts['dark_back_color'],
						'hide_if' => array(
							'style' => array('clean')
						)
					),
					'active_tab_border_color' => array(
						'type' => 'color',
						'label' => __('Active border:','pbtheme'),
						'std' => $opts['main_color']
					)
				)
			),
			'group_content_colors' => array(
				'type' => 'collapsible',
				'label' => __('Content Colors','pbtheme'),
				'open' => 'true',
				'options' => array(	
						'text_color' => array(
						'type' => 'color',
						'label' => __('Text:','pbtheme'),
						'std' => $opts['text_color']
					),
					'border_color' => array(
						'type' => 'color',
						'label' => __('Border:','pbtheme'),
						'std' => $opts['light_border_color'],
						'hide_if' => array(
							'style' => array('clean', 'pbtheme')
						)
					),
					'back_color' => array(
						'type' => 'color',
						'label' => __('Background:','pbtheme'),
						'std' => $opts['light_back_color'],
						'hide_if' => array(
							'style' => array('clean', 'pbtheme')
						)
					)
				)
			),
			'group_tabs' => array(
				'type' => 'collapsible',
				'label' => __('Tabs','pbtheme'),
				'open' => 'true',
				'options' => array(
					'sortable' => array(
						'type' => 'sortable',
						'desc' => __('Elements are sortable','pbtheme'),
						'item_name' => __('tab item','pbtheme'),
						'label_width' => 0,
						'control_width' => 1,
						'std' => array(
							'items' => array(
								0 => array(
									'title' => 'Lorem ipsum',
									'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
									'image' => '',
									'active' => 'true'
								),
								1 => array(
									'title' => 'Lorem ipsum',
									'content' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.',
									'image' => '',
									'active' => 'false'
								),
								2 => array(
									'title' => 'Lorem ipsum',
									'content' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
									'image' => '',
									'active' => 'false'
								)
							),
							'order' => array(
								0 => 0,
								1 => 1,
								2 => 2
							)
						),
						
						'options'=> array(
							'title' => array(
								'type' => 'input',
								'label_width' => 0.25,
								'control_width' => 0.75,
								'label' => __('Title:','pbtheme')
							),
							'content' => array(
								'type' => 'textarea'
							),
							'image' => array(
								'type' => 'image',
								'label' => __('Image:','pbtheme'),
								'label_width' => 0.25,
								'control_width' => 0.75,
								'desc' => __('Add an image to tab content','pbtheme')
							),
							'active' => array(
								'type' => 'checkbox',
								'label' => __('Mark as Default','pbtheme'),
								'desc' => __('Only one panel can be active at a time, so be sure to uncheck the others for it to work properly','pbtheme')
							)
						)
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* ACCORDION */
/* -------------------------------------------------------------------------------- */
$accordion = array(
	'accordion' => array(
		'type' => 'draggable',
		'text' => __('Accordion','pbtheme'),
		'icon' => $pbuilder->url.'images/icons/accordion.png',
		'function' => 'pbuilder_accordion',
		'group' => __('Basic', 'pbtheme'),
		'options'  => array_merge(
		 array(
		 	'group_basic' => array(
				'type' => 'collapsible',
				'label' => __('Basic','pbtheme'),
				'open' => 'true',
				'options' => array(
					'style' => array(
						'type' => 'select',
						'label' => __('Style:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'clean-right',
						'options' => array(
							'pbtheme' => __('PBTheme style', 'pbtheme'),
							'clean-right' => __('Clean right','pbtheme'),
							'squared-right' => __('Squared right','pbtheme'),
							'rounded-right' => __('Rounded right','pbtheme'),
							'clean-left' => __('Clean left','pbtheme'),
							'squared-left' => __('Squared left','pbtheme'),
							'rounded-left' => __('Rounded left','pbtheme')
						)
					),
					'text_color' => array(
						'type' => 'color',
						'label' => __('Text:','pbtheme'),
						'std' => $opts['text_color']
					),
					'main_color' => array(
						'type' => 'color',
						'label' => __('Main:','pbtheme'),
						'std' => $opts['main_color'],
						'hide_if' => array(
							'style' => array('pbtheme')
						)
					),
					'fixed_height' => array(
						'type' => 'checkbox',
						'label' => __('Fixed height','pbtheme'),
						'desc' => __('if desabled height will vary due to content height','pbtheme'),
						'std' => 'true'
					)
				)
			),
			'group_title_colors' => array(
				'type' => 'collapsible',
				'label' => __('Title Colors','pbtheme'),
				'open' => 'true',
				'options' => array(
					'title_color' => array(
						'type' => 'color',
						'label' => __('Title','pbtheme'),
						'std' => $opts['title_color']
					),
					'title_active_color' => array(
						'type' => 'color',
						'label' => __('Active Title:','pbtheme'),
						'std' =>  $opts['main_color'],
						'hide_if' => array(
							'style' => array('clean-right', 'clean-left')
						)
					)
				)
			),
			'group_trigger_colors' => array(
				'type' => 'collapsible',
				'label' => __('Trigger Colors','pbtheme'),
				'open' => 'true',
				'options' => array(
					'trigger_color' => array(
						'type' => 'color',
						'label' => __('Trigger:','pbtheme'),
						'std' => $opts['title_color']
					),
					'trigger_active_color' => array(
						'type' => 'color',
						'label' => __('Trigger active:','pbtheme'),
						'std' => $opts['main_color']
					)
				)
			),
			'group_background_colors' => array(
				'type' => 'collapsible',
				'label' => __('Background Colors','pbtheme'),
				'open' => 'true',
				'options' => array(	
					'back_color' => array(
						'type' => 'color',
						'label' => __('Background:','pbtheme'),
						'std' => ''
					),
					'border_color' => array(
						'type' => 'color',
						'label' => __('Border:','pbtheme'),
						'std' => $opts['light_border_color']
					)
				)
			),
			'group_accordion_elements' => array(
				'type' => 'collapsible',
				'label' => __('Accordion Elements','pbtheme'),
				'open' => 'true',
				'options' => array(
					'sortable' => array(
						'type' => 'sortable',
						'desc' => __('Elements are sortable','pbtheme'),
						'item_name' => __('accordion item','pbtheme'),
						'label_width' => 0,
						'control_width' => 1,
						'std' => array(
							'items' => array(
								0 => array(
									'title' => 'Lorem ipsum',
									'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
									'image' => '',
									'active' => 'true'
								),
								1 => array(
									'title' => 'Lorem ipsum',
									'content' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.',
									'image' => '',
									'active' => 'false'
								),
								2 => array(
									'title' => 'Lorem ipsum',
									'content' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
									'image' => '',
									'active' => 'false'
								)
							),
							'order' => array(
								0 => 0,
								1 => 1,
								2 => 2
							)
						),
						'options'=> array(
							'title' => array(
								'type' => 'input',
								'label_width' => 0.25,
								'control_width' => 0.75,
								'label' => __('Title:','pbtheme')
							),
							'content' => array(
								'type' => 'textarea'
							),
							'image' => array(
								'type' => 'image',
								'label_width' => 0.25,
								'control_width' => 0.75,
								'label' => __('Image:','pbtheme'),
								'desc' => __('Add an image to accordion content','pbtheme')
							),
							'active' => array(
								'type' => 'checkbox',
								'label' => __('Active','pbtheme'),
								'desc' => __('Only one panel can be active at a time, so be sure to uncheck the others for it to work properly','pbtheme')
							)
						)
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME PROGRESS BAR */
/* -------------------------------------------------------------------------------- */
$divshort_progbar = array(
	'pbtheme_progress_bar' => array(
		'type' => 'draggable',
		'text' => __('Progress Bar','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/progress-bar.png',
		'function' => 'pbtheme_progress_bar',
		'group' => __('Charts, Bars, Counters', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_progsbar' => array(
				'type' => 'collapsible',
				'label' => __('Progress Bar','pbtheme'),
				'open' => 'true',
				'options' => array(
					'title' => array(
						'type' => 'input',
						'label' => __('Title:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'Title'
					),
					'per_cent' => array(
						'type' => 'number',
						'label' => __('Value:','pbtheme'),
						'half_column' => 'true',
						'std' => 50,
						'min' => 0,
						'max' => 100,
						'unit' => '%'
					),
				)
			),
			'group_colors' => array(
				'type' => 'collapsible',
				'label' => __('Colors','pbtheme'),
				'open' => 'true',
				'options' => array(	
					'back_color' => array(
						'type' => 'color',
						'label' => __('Back color:','pbtheme'),
						'std' => $opts['light_back_color']
					),
					'front_color' => array(
						'type' => 'color',
						'label' => __('Front color:','pbtheme'),
						'std' => $opts['main_color']
					),
					'title_color' => array(
						'type' => 'color',
						'label' => __('Title color:','pbtheme'),
						'std' => $opts['title_color']
					),
					'arrow_color' => array(
						'type' => 'color',
						'label' => __('Tag color:','pbtheme'),
						'std' => $opts['dark_back_color']
					),
					'arrow_text_color' => array(
						'type' => 'color',
						'label' => __('Tag text color:','pbtheme'),
						'std' => $opts['light_back_color']
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);

/* -------------------------------------------------------------------------------- */
/* PBTHEME TITLE */
/* -------------------------------------------------------------------------------- */
$divshort_title = array(
	'pbtheme_title' => array(
		'type' => 'draggable',
		'text' => __('Title','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/title.png',
		'function' => 'pbtheme_title',
		'group' => __('Basic', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_title' => array(
				'type' => 'collapsible',
				'label' => __('Title','pbtheme'),
				'open' => 'true',
				'options' => array(
					'content' => array(
						'type' => 'textarea',
						'label' => __('Title:', 'pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'Title'
					),
					'type' => array(
						'type' => 'select',
						'label' => __('Type:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'h3',
						'options' => array (
							'h1'=> 'h1',
							'h2'=> 'h2',
							'h3'=> 'h3',
							'h4'=> 'h4',
							'h5'=> 'h5',
							'h6'=> 'h6'
						)
					),
					'align' => array(
						'type' => 'select',
						'label' => __('Align:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'center',
						'options' => array (
							'center'=> __('Center', 'pbtheme'),
							'left'=> __('Left', 'pbtheme'),
							'right'=> __('Right', 'pbtheme')
						)
					),
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);

/* -------------------------------------------------------------------------------- */
/* PBTHEME INSERT POSTS */
/* -------------------------------------------------------------------------------- */
$divshort_insposts = array(
	'pbtheme_insert_posts' => array(
		'type' => 'draggable',
		'text' => __('Posts','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/insered-posts.png',
		'function' => 'pbtheme_insert_posts',
		'group' => __('Content', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_insposts' => array(
				'type' => 'collapsible',
				'label' => __('Posts','pbtheme'),
				'open' => 'true',
				'options' => array(
					'category' => array(
						'type' => 'select',
						'label' => __('Category:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '-1',
						'options' => $pbtheme_ready_categories,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'title' => array(
						'type' => 'input',
						'label' => __('Title:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => __('Previous/Next Posts','pbtheme')
					),
					'type' => array(
						'type' => 'select',
						'label' => __('Columns','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '2',
						'options' => array (
							'0' => '1',
							'1' => '2',
							'2' => '3',
							'3' => '4',
							'4' => '5',
							'small' => 'small'
							)
						),
					'rows' => array(
						'type' => 'input',
						'label' => __('Rows:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.25,
						'std' => 1
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'excerpt_lenght' => array(
						'type' => 'number',
						'min' => 0,
						'label' => __('Excerpt:','pbtheme'),
						'half_column' => 'true',
						'max' => 999,
						'std' => 128,
						'unit' => ''
					),
					'ajax' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ajax Load','pbtheme'),
						'half_column' => 'true'
					),
					'pagination' => array(
						'type' => 'checkbox',
						'label' => __('Show Pagination','pbtheme'),
						'std' => 'true',
						'half_column' => 'true'
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME POSTS GRID */
/* -------------------------------------------------------------------------------- */
$divshort_postgrid = array(
	'pbtheme_insert_posts_grid' => array(
		'type' => 'draggable',
		'text' => __('Posts Grid','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/insered-posts-grid.png',
		'function' => 'pbtheme_insert_posts_grid',
		'group' => __('Content', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_postgrid' => array(
				'type' => 'collapsible',
				'label' => __('Posts Grid','pbtheme'),
				'open' => 'true',
				'options' => array(
					'category' => array(
						'type' => 'select',
						'label' => __('Category:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '-1',
						'options' => $pbtheme_ready_categories,
						'search' => 'true',
						'multiselect' => 'true'
						),
					'slides' => array(
						'type' => 'input',
						'label' => __('Slides:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 2
						),
					'type' => array(
						'type' => 'select',
						'label' => __('Type:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '1',
						'options' => array (
							'1' => 'Layout #1',
							'2' => 'Layout #2',
							'3' => 'Layout #3',
							'4' => 'Layout #4',
							'5' => 'Layout #5',
							'6' => 'Layout #6',
							'rnd' => 'Random'
							),
						'multiselect' => 'true'
						),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
						),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
						),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme')
					),
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME GRID */
/* -------------------------------------------------------------------------------- */
$divshort_grid = array(
	'pbtheme_grid' => array(
		'type' => 'draggable',
		'text' => __('Grid','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/insert-grid.png',
		'function' => 'pbtheme_grid',
		'group' => __('Content', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_grid' => array(
				'type' => 'collapsible',
				'label' => __('Separator','pbtheme'),
				'open' => 'true',
				'options' => array(
					'image' => array(
						'type' => 'image',
						'label' => __('Image','pbtheme'),
						'std' => 'http://wscont2.apps.microsoft.com/winstore/1x/65e960ea-2698-4a8f-90e2-552f3a832367/Screenshot.2056.1000000.jpg'
					),
					'title' => array(
						'type' => 'input',
						'label' => __('Title:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'Lorem ipsum dolor sit amet'
					),
					'description' => array(
						'type' => 'textarea',
						'label' => __('Description','pbtheme'),
						'std' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
					),
					'align' => array(
						'type' => 'select',
						'label' => __('Content Alignment:','pbtheme'),
						'std' => 'left',
						'options' => array(
							'left' => __('Left','pbtheme'),
							'right' => __('Right','pbtheme')
						)
					),
					'link' => array(
						'type' => 'input',
						'label' => __('Link:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => ''
					),
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME MASONRY POSTS */
/* -------------------------------------------------------------------------------- */
$divshort_masonary = array(
	'pbtheme_magazine_fluid_posts' => array(
		'type' => 'draggable',
		'text' => __('Masonry Posts','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/magazine-fluid-posts.png',
		'function' => 'pbtheme_magazine_fluid_posts',
		'group' => __('Content', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_masonary' => array(
				'type' => 'collapsible',
				'label' => __('Masonry posts','pbtheme'),
				'open' => 'true',
				'options' => array(
					'category' => array(
						'type' => 'select',
						'label' => __('Category:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '-1',
						'options' => $pbtheme_ready_categories,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'posts' => array(
						'type' => 'input',
						'label' => __('Per page:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 9
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By comment count', 'pbtheme'),
							'date'=> __('By date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'excerpt_lenght' => array(
						'type' => 'number',
						'min' => 0,
						'label' => __('Excerpt:','pbtheme'),
						'half_column' => 'true',
						'max' => 999,
						'std' => 128,
						'unit' => ''
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme'),
					),
					'colored' => array(
						'type' => 'checkbox',
						'std' => 'false',
						'label' => __('Use category colors as backgrounds','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME PORTFOLIO */
/* -------------------------------------------------------------------------------- */
$divshort_portfolio = array(
	'pbtheme_portfolio' => array(
		'type' => 'draggable',
		'text' => __('Portfolio','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/insert-portfolio.png',
		'function' => 'pbtheme_portfolio',
		'group' => __('Content', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_portfolio' => array(
				'type' => 'collapsible',
				'label' => __('Portfolio','pbtheme'),
				'open' => 'true',
				'options' => array(
					'category' => array(
						'type' => 'select',
						'label' => __('Category:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '-1',
						'options' => $pbtheme_ready_categories,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'type' => array(
						'type' => 'select',
						'label' => __('Columns:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '3',
						'options' => array (
							'1' => '2',
							'2' => '3',
							'3' => '4',
							'4' => '5'
							)
					),
					'rows' => array(
						'type' => 'input',
						'label' => __('Rows:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'trans_effect' => array(
						'type' => 'select',
						'label' => __('Animation:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'snake',
						'options' => array (
							'snake'=> __('Snake', 'pbtheme'),
							'fade'=> __('Rows', 'pbtheme')
							)
					),
					'top_pagination' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Show Categories','pbtheme')
					),
					'top_align' => array(
						'type' => 'select',
						'label' => __('Categories Alignment','pbtheme'),
						'std' => 'left',
						'options' => array (
							'left'=> __('left', 'pbtheme'),
							'center'=> __('center', 'pbtheme'),
							'right'=> __('right', 'pbtheme')
							)
					),
					'ajax' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'half_column' => 'true',
						'label' => __('Ajax Load','pbtheme')
					),
					'pagination' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'half_column' => 'true',
						'label' => __('Show Pagination','pbtheme')
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME PORTFOLIO ALTERNATIVE */
/* -------------------------------------------------------------------------------- */
$divshort_portfolioalt = array(
	'pbtheme_portfolio_alt' => array(
		'type' => 'draggable',
		'text' => __('Portfolio Alternative','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/insert-portfolio-alternative.png',
		'function' => 'pbtheme_portfolio_alt',
		'group' => __('Content', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_portfolioalt' => array(
				'type' => 'collapsible',
				'label' => __('Portfolio','pbtheme'),
				'open' => 'true',
				'options' => array(
					'category' => array(
						'type' => 'select',
						'label' => __('Category:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '-1',
						'options' => $pbtheme_ready_categories,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'type' => array(
						'type' => 'select',
						'label' => __('Columns:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '3',
						'options' => array (
							'1' => '2',
							'2' => '3',
							'3' => '4',
							'4' => '5'
							)
					),
					'rows' => array(
						'type' => 'input',
						'label' => __('Rows:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'trans_effect' => array(
						'type' => 'select',
						'label' => __('Animation:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'snake',
						'options' => array (
							'snake'=> __('Snake', 'pbtheme'),
							'fade'=> __('Rows', 'pbtheme')
							)
					),
					'top_pagination' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Show Categories','pbtheme')
					),
					'top_align' => array(
						'type' => 'select',
						'label' => __('Categories Alignment','pbtheme'),
						'std' => 'left',
						'options' => array (
							'left'=> __('left', 'pbtheme'),
							'center'=> __('center', 'pbtheme'),
							'right'=> __('right', 'pbtheme')
							)
						),
					'ajax' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'half_column' => 'true',
						'label' => __('Ajax Load','pbtheme')
					),
					'pagination' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'half_column' => 'true',
						'label' => __('Show Pagination','pbtheme')
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME POST SLIDER */
/* -------------------------------------------------------------------------------- */
$divshort_postslider = array(
	'pbtheme_slider' => array(
		'type' => 'draggable',
		'text' => __('Post Slider','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/post-slider.png',
		'function' => 'pbtheme_slider',
		'group' => __('Content', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_postslider' => array(
				'type' => 'collapsible',
				'label' => __('Post Slider','pbtheme'),
				'open' => 'true',
				'options' => array(
					'category' => array(
						'type' => 'select',
						'label' => __('Select categories. Use CTRL+Click to select multiple categories.','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '-1',
						'options' => $pbtheme_ready_categories,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
						),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'post_count' => array(
						'type' => 'number',
						'label' => __('Posts','pbtheme'),
						'half_column' => 'true',
						'std' => '',
						'std' => 5,
						'min' => 1,
						'max' => 100
					),
					'slides' => array(
						'type' => 'number',
						'label' => __('Per View','pbtheme'),
						'half_column' => 'true',
						'std' => '',
						'std' => 3,
						'min' => 1,
						'max' => 5
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME NEWS CATEGORIES */
/* -------------------------------------------------------------------------------- */
$divshort_news = array(
	'pbtheme_news_categories' => array(
		'type' => 'draggable',
		'text' => __('Categories','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/categories.png',
		'function' => 'pbtheme_news_categories',
		'group' => __('Content', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_news' => array(
				'type' => 'collapsible',
				'label' => __('Categories','pbtheme'),
				'open' => 'true',
				'options' => array(
					'categories' => array(
						'type' => 'select',
						'label' => __('Category:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '-1',
						'options' => $pbtheme_ready_categories,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'rows' => array(
						'type' => 'input',
						'label' => __('Rows:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By comment count', 'pbtheme'),
							'date'=> __('By date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'excerpt_lenght' => array(
						'type' => 'number',
						'min' => 0,
						'label' => __('Excerpt:','pbtheme'),
						'half_column' => 'true',
						'max' => 999,
						'std' => 256,
						'unit' => ''
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts / Please note that sticky posts are available when displaying All categories (All categories ID / -1).','pbtheme')
					),
					'colors' => array(
						'type' => 'checkbox',
						'std' => 'false',
						'label' => __('Show category colors.','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME NEWS POSTS */
/* -------------------------------------------------------------------------------- */
$divshort_newsposts = array(
	'pbtheme_news_related_posts' => array(
		'type' => 'draggable',
		'text' => __('Related Posts','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/related-posts.png',
		'function' => 'pbtheme_news_related_posts',
		'group' => __('Content', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_newsposts' => array(
				'type' => 'collapsible',
				'label' => __('Related Posts','pbtheme'),
				'open' => 'true',
				'options' => array(
					'post' => array(
						'type' => 'select',
						'label' => __('Post:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => $first_post,
						'options' => $pbuilder_wp_posts,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'rows' => array(
						'type' => 'input',
						'label' => __('Rows:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'excerpt_lenght' => array(
						'type' => 'number',
						'min' => 0,
						'label' => __('Excerpt:','pbtheme'),
						'half_column' => 'true',
						'max' => 999,
						'std' => 256,
						'unit' => ''
					),
					'includeref' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Show reference post','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME LINK LIST */
/* -------------------------------------------------------------------------------- */
$divshort_linklist = array(
	'pbtheme_link_list' => array(
		'type' => 'draggable',
		'text' => __('Link List','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/link-list.png',
		'function' => 'pbtheme_link_list',
		'group' => __('Link Lists', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_linklist' => array(
				'type' => 'collapsible',
				'label' => __('Link List','pbtheme'),
				'open' => 'true',
				'options' => array(
					'category' => array(
						'type' => 'select',
						'label' => __('Category:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '-1',
						'options' => $pbtheme_ready_categories,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'rows' => array(
						'type' => 'input',
						'label' => __('Rows:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME LINK LIST RELATED */
/* -------------------------------------------------------------------------------- */
$divshort_linklistrel = array(
	'pbtheme_link_list_related' => array(
		'type' => 'draggable',
		'text' => __('Link List Related Posts','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/link-related-posts.png',
		'function' => 'pbtheme_link_list_related',
		'group' => __('Link Lists', 'pbtheme'),
		'options'  => array_merge(
		array(
			'group_linklistrel' => array(
				'type' => 'collapsible',
				'label' => __('Link List Related','pbtheme'),
				'open' => 'true',
				'options' => array(
					'post' => array(
						'type' => 'select',
						'label' => __('Post:','pbtheme'),
						'std' => $first_post,
						'options' => $pbuilder_wp_posts,
						'search' => 'true'
					),
					'rows' => array(
						'type' => 'input',
						'label' => __('Rows:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);



/* -------------------------------------------------------------------------------- */
/* PBTHEME MAGAZINE LINKS */
/* -------------------------------------------------------------------------------- */
$divshort_maglinks = array(
	'pbtheme_magazine_link_list' => array(
		'type' => 'draggable',
		'text' => __('Magazine Link List','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/magazine-link-list.png',
		'function' => 'pbtheme_magazine_link_list',
		'group' => __('Link Lists', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_maglinks' => array(
				'type' => 'collapsible',
				'label' => __('Separator','pbtheme'),
				'open' => 'true',
				'options' => array(
					'category' => array(
						'type' => 'select',
						'label' => __('Category:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '-1',
						'options' => $pbtheme_ready_categories,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'rows' => array(
						'type' => 'input',
						'label' => __('Rows:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By comment count', 'pbtheme'),
							'date'=> __('By date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME MAGAZINE LINKS RELATED */
/* -------------------------------------------------------------------------------- */
$divshort_maglinksrel = array(
	'pbtheme_magazine_link_list_related' => array(
		'type' => 'draggable',
		'text' => __('Magazine Link List Related','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/magazine-link-list-related-posts.png',
		'function' => 'pbtheme_magazine_link_list_related',
		'group' => __('Link Lists', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_maglinksrel' => array(
				'type' => 'collapsible',
				'label' => __('Magazine Link List Related','pbtheme'),
				'open' => 'true',
				'options' => array(
					'post' => array(
						'type' => 'select',
						'label' => __('Post:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => $first_post,
						'options' => $pbuilder_wp_posts,
						'search' => 'true'
					),
					'rows' => array(
						'type' => 'input',
						'label' => __('Rows:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By comment count', 'pbtheme'),
							'date'=> __('By date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME SMALL LINK LIST */
/* -------------------------------------------------------------------------------- */
$divshort_smalllinks = array(
	'pbtheme_small_link_list' => array(
		'type' => 'draggable',
		'text' => __('Text Links','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/small-links.png',
		'function' => 'pbtheme_small_link_list',
		'group' => __('Link Lists', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_smalllinks' => array(
				'type' => 'collapsible',
				'label' => __('Magazine Link List Related','pbtheme'),
				'open' => 'true',
				'options' => array(
					'category' => array(
						'type' => 'select',
						'label' => __('Category:','pbtheme'),
						'std' => '-1',
						'options' => $pbtheme_ready_categories,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'rows' => array(
						'type' => 'input',
						'label' => __('Rows:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By comment count', 'pbtheme'),
							'date'=> __('By date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME SMALL LINK LIST RELATED */
/* -------------------------------------------------------------------------------- */
$divshort_smalllinksrel = array(
	'pbtheme_small_link_list_related' => array(
		'type' => 'draggable',
		'text' => __('Text Links Related','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/small-related-links.png',
		'function' => 'pbtheme_small_link_list_related',
		'group' => __('Link Lists', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_smalllinksrel' => array(
				'type' => 'collapsible',
				'label' => __('Magazine Link List Related','pbtheme'),
				'open' => 'true',
				'options' => array(
					'post' => array(
						'type' => 'select',
						'label' => __('Select post','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => $first_post,
						'options' => $pbuilder_wp_posts,
						'search' => 'true'
					),
					'rows' => array(
						'type' => 'input',
						'label' => __('Rows:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By comment count', 'pbtheme'),
							'date'=> __('By date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'ignoresticky' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Ignore Sticky Posts','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME POST PAGINATION */
/* -------------------------------------------------------------------------------- */
$divshort_postpag = array(
	'pbtheme_post_pagination' => array(
		'type' => 'draggable',
		'text' => __('Posts Pagination','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/insert-post-pagination.png',
		'function' => 'pbtheme_post_pagination',
		'group' => __('Content', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_postpag' => array(
				'type' => 'collapsible',
				'label' => __('Posts Pagination','pbtheme'),
				'open' => 'true',
				'options' => array(
					'category' => array(
						'type' => 'select',
						'label' => __('Category:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '-1',
						'options' => $pbtheme_ready_categories,
						'search' => 'true',
						'multiselect' => 'true'
						),
					'previous' => array(
						'type' => 'input',
						'label' => __('Previous','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => __('Previous Post', 'pbtheme')
						),
					'next' => array(
						'type' => 'input',
						'label' => __('Next','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => __('Next Post', 'pbtheme')
						),
					'show_title' => array(
						'type' => 'checkbox',
						'std' => 'true',
						'label' => __('Show Title','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME TEAM */
/* -------------------------------------------------------------------------------- */
$divshort_team = array(
	'pbtheme_team' => array(
		'type' => 'draggable',
		'text' => __('Team','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/team.png',
		'function' => 'pbtheme_team',
		'group' => __('Content', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_team' => array(
				'type' => 'collapsible',
				'label' => __('Team','pbtheme'),
				'label_width' => 0.25,
				'control_width' => 0.75,
				'open' => 'true',
				'options' => array(
					'user' => array(
						'type' => 'select',
						'label' => __('User:','pbtheme'),
						'std' => '1',
						'options' => $pbtheme_ready_contacts,
						'search' => 'true'
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME CONTACT FORM */
/* -------------------------------------------------------------------------------- */
$divshort_contactform = array(
	'pbtheme_contactform' => array(
		'type' => 'draggable',
		'text' => __('Contact Form','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/contact-form.png',
		'function' => 'pbtheme_contactform',
		'group' => __('Content', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_contactform' => array(
				'type' => 'collapsible',
				'label' => __('Contact Form','pbtheme'),
				'open' => 'true',
				'options' => array(
					'users' => array(
						'type' => 'select',
						'label' => __('Users:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'options' => $pbtheme_ready_contacts,
						'search' => 'true',
						'multiselect' => 'true'
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);

$pbtheme_shortcodes = array_merge( $tabs, $accordion, $divshort_contactform, $divshort_grid, $divshort_insposts, $divshort_linklist, $divshort_linklistrel, $divshort_maglinks, $divshort_maglinksrel, $divshort_masonary, $divshort_news, $divshort_newsposts, $divshort_portfolio, $divshort_portfolioalt, $divshort_postgrid, $divshort_postpag, $divshort_postslider, $divshort_progbar, $divshort_smalllinks, $divshort_smalllinksrel, $divshort_team, $divshort_title);

if ( isset($pbuilder) && is_admin()) {
	$pbuilder->add_new_shortcodes($pbtheme_shortcodes);
}

if ( DIVWP_REVSLIDER === true ) {
	global $wpdb;
	$get_sliders = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'revslider_sliders');
	$revsliders = array();
	if( $get_sliders ) {
		$default = $get_sliders[0]->alias;
		foreach( $get_sliders as $slider ) {
			$revsliders[$slider->alias] = $slider->title;
		}
	}
else {
	$default = array( 1 => __('No sliders set.', 'pbtheme') );
}

	$revolution_slider = array (
		'pbtheme_revslider' => array(
			'type' => 'draggable',
			'text' => __('Revolution Slider','pbtheme'),
			'icon' => get_template_directory_uri() . '/images/pbuilder/revolution-slider.png',
			'function' => 'pbtheme_revslider',
			'group' => __('Content', 'pbtheme'),
			'options' => array(
				'slider' => array(
					'type' => 'select',
					'label' => __('Select slider','pbtheme'),
					'label_width' => 0.25,
					'control_width' => 0.75,
					'std' => $default,
					'options' => $revsliders
					)
				),
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
						)
					)
				)
			)
		)
	);

	if ( isset($pbuilder) && is_admin()) {
		$pbuilder->add_new_shortcodes($revolution_slider);
	}

}

if ( DIVWP_WOOCOMMERCE === true ) {

	function refresh_woo_categories() {
		global $pbuilder, $pbtheme_data;

		$woo_categories = get_terms('product_cat', array('hide_empty' => 0, 'orderby' => 'ASC'));
		$pbuilder_woo_categories = array();
		$first_category = '';
		$pbuilder_woo_categories_slug = array();
		$first_category_slug = '';

		foreach($woo_categories as $obj) :
			if($first_category == '') $first_category = $obj->term_id;
			$pbuilder_woo_categories[$obj->term_id] = $obj->slug;
		endforeach;

		foreach($woo_categories as $obj) :
			if($first_category_slug == '') $first_category_slug = $obj->slug;
			$pbuilder_woo_categories_slug[$obj->slug] = $obj->slug;
		endforeach;


		$woo_products = get_posts( array(
			'post_type'      => array( 'product', 'product_variation' ),
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_query' => array(
				array(
					'key' 		=> '_visibility',
					'value' 	=> array('catalog', 'visible'),
					'compare' 	=> 'IN'
				)
			)
		) );

		$pbtheme_margin = $pbtheme_data['fb_bmargin'];
		$pbuilder_woo_products = array();
		$first_product = '';
		foreach($woo_products as $key => $obj) {
			if($first_product == '') $first_product = $obj->ID;
			$pbuilder_woo_products[$obj->ID] = $obj->post_title;
		}

if($pbuilder){
    $admin_optionsDB = $pbuilder->option();
    $opts = array();
    foreach($admin_optionsDB as $opt) {
    	if(isset($opt->name) && isset($opt->value))
    		$opts[$opt->name] = $opt->value;
    }
}

		$animationList = array(
			'none' => __('None', 'pbtheme'),
			'flipInX' => __('Flip in X', 'pbtheme'),
			'flipInY' => __('Flip in Y', 'pbtheme'),
			'fadeIn' => __('Fade in', 'pbtheme'),
			'fadeInDown' => __('Fade in from top', 'pbtheme'),
			'fadeInUp' => __('Fade in from bottom', 'pbtheme'),
			'fadeInLeft' => __('Fade in from left', 'pbtheme'),
			'fadeInRight' => __('Fade in from right', 'pbtheme'),
			'fadeInDownBig' => __('Slide in from top', 'pbtheme'),
			'fadeInUpBig' => __('Slide in from bottom', 'pbtheme'),
			'fadeInLeftBig' => __('Slide in from left', 'pbtheme'),
			'fadeInRightBig' => __('Slide in from right', 'pbtheme'),
			'bounceIn' => __('Bounce in', 'pbtheme'),
			'bounceInDown' => __('Bounce in from top', 'pbtheme'),
			'bounceInUp' => __('Bounce in from bottom', 'pbtheme'),
			'bounceInLeft' => __('Bounce in from left', 'pbtheme'),
			'bounceInRight' => __('Bounce in from right', 'pbtheme'),
			'rotateIn' => __('Rotate in', 'pbtheme'),
			'rotateInDownLeft' => __('Rotate in from top-left', 'pbtheme'),
			'rotateInDownRight' => __('Rotate in from top-right', 'pbtheme'),
			'rotateInUpLeft' => __('Rotate in from bottom-left', 'pbtheme'),
			'rotateInUpRight' => __('Rotate in from bottom-right', 'pbtheme'),
			'lightSpeedIn' => __('Lightning speed', 'pbtheme'),
			'rollIn' => __('Roll in', 'pbtheme')
		);

		$animationControl = array(
			'group_animate' => array(
				'type' => 'collapsible',
				'label' => __('Animation','pbtheme'),
				'options' => array(
					'animate' => array(
						'type' => 'select',
						'label' => __('Type:','pbtheme'),
						'std' => 'none',
						'label_width' => 0.25,
						'control_width' => 0.75,
						'options' => $animationList
					),
					'animation_group' => array(
						'type' => 'input',
						'label' => __('Group:','pbtheme'),
						'std' => '',
						'half_column' => 'true'
					),
					'animation_delay' => array(
						'type' => 'number',
						'label' => __('Delay:','pbtheme'),
						'std' => 0,
						'unit' => 'ms',
						'min' => 0,
						'step' => 50,
						'max' => 10000,
						'half_column' => 'true'
					)
				)
			)
		);

		if(isset($opts['css_classes']) && $opts['css_classes'] == 'true') {
			$classControl = array(
				'group_css' => array(
					'type' => 'collapsible',
					'label' => __('ID & Custom CSS','pbtheme'),
					'options' => array(
						'shortcode_id' => array(
							'type' => 'input',
							'label' => __('ID:','pbtheme'),
							'desc' => __('For linking via hashtags','pbtheme'),
							'label_width' => 0.25,
							'control_width' => 0.75,
							'std' => ''
						),
						'class' => array(
							'type' => 'input',
							'label' => __('Class:','pbtheme'),
							'desc' => __('For custom css','pbtheme'),
							'label_width' => 0.25,
							'control_width' => 0.75,
							'std' => ''
						)
					)
				)
			);
			$tabsId = array(
				'custom_id' => array(
					'type' => 'input',
					'label' => __('Tab ID:','pbtheme'),
					'desc' => __('For use of anchor in url. Make sure that this ID is unique on the page.','pbtheme'),
					'label_width' => 0.25,
					'std' => ''
				)
			);
		}
		else {
			$classControl = array();
			$tabsId = array();
		}


/* -------------------------------------------------------------------------------- */
/* PBTHEME TOP RATED PRODUCTS */
/* -------------------------------------------------------------------------------- */
$divshort_toprated_prd = array(
	'pbtheme_top_rated_products' => array(
		'type' => 'draggable',
		'text' => __('Top Rated Products','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/top-rated-products.png',
		'function' => 'pbtheme_top_rated_products',
		'group' => __('WooCommerce', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_toprated_prd' => array(
				'type' => 'collapsible',
				'label' => __('Top Rated Products','pbtheme'),
				'open' => 'true',
				'options' => array(
					'per_page' => array(
						'type' => 'input',
						'label' => __('Products:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'columns' => array(
						'type' => 'select',
						'label' => __('Columns:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '4',
						'options' => array (
							'1'=> '1',
							'2'=> '2',
							'3'=> '3',
							'4'=> '4'
							)
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME SALE PRODUCTS */
/* -------------------------------------------------------------------------------- */
$divshort_sale_prd = array(
	'pbtheme_sale_products' => array(
		'type' => 'draggable',
		'text' => __('Sale Products','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/sale-products.png',
		'function' => 'pbtheme_sale_products',
		'group' => __('WooCommerce', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_sale_prd' => array(
				'type' => 'collapsible',
				'label' => __('Sale Products','pbtheme'),
				'open' => 'true',
				'options' => array(
					'per_page' => array(
						'type' => 'input',
						'label' => __('Products:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'columns' => array(
						'type' => 'select',
						'label' => __('Columns:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '4',
						'options' => array (
							'1'=> '1',
							'2'=> '2',
							'3'=> '3',
							'4'=> '4'
							)
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME RECENT PRODUCTS */
/* -------------------------------------------------------------------------------- */
$divshort_recent_prd = array(
	'pbtheme_recent_products' => array(
		'type' => 'draggable',
		'text' => __('Recent Products','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/recent-products.png',
		'function' => 'pbtheme_recent_products',
		'group' => __('WooCommerce', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_recent_prd' => array(
				'type' => 'collapsible',
				'label' => __('Recent Products','pbtheme'),
				'open' => 'true',
				'options' => array(
					'per_page' => array(
						'type' => 'input',
						'label' => __('Products:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'columns' => array(
						'type' => 'select',
						'label' => __('Columns:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '4',
						'options' => array (
							'1'=> '1',
							'2'=> '2',
							'3'=> '3',
							'4'=> '4'
							)
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME FEATURED PRODUCTS */
/* -------------------------------------------------------------------------------- */
$divshort_featured_prd = array(
	'pbtheme_featured_products' => array(
		'type' => 'draggable',
		'text' => __('Featured Products','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/featured-products.png',
		'function' => 'pbtheme_featured_products',
		'group' => __('WooCommerce', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_featured_prd' => array(
				'type' => 'collapsible',
				'label' => __('Featured Products','pbtheme'),
				'open' => 'true',
				'options' => array(
					'per_page' => array(
						'type' => 'input',
						'label' => __('Products:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'columns' => array(
						'type' => 'select',
						'label' => __('Columns:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '4',
						'options' => array (
							'1'=> '1',
							'2'=> '2',
							'3'=> '3',
							'4'=> '4'
							)
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);



/* -------------------------------------------------------------------------------- */
/* PBTHEME PRODUCTS */
/* -------------------------------------------------------------------------------- */
$divshort_products = array(
	'pbtheme_products' => array(
		'type' => 'draggable',
		'text' => __('Products','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/products.png',
		'function' => 'pbtheme_products',
		'group' => __('WooCommerce', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_products' => array(
				'type' => 'collapsible',
				'label' => __('Products','pbtheme'),
				'open' => 'true',
				'options' => array(
					'ids' => array(
						'type' => 'select',
						'label' => __('Products:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '',
						'options' => $pbuilder_woo_products,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'columns' => array(
						'type' => 'select',
						'label' => __('Columns:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '4',
						'options' => array (
							'1'=> '1',
							'2'=> '2',
							'3'=> '3',
							'4'=> '4'
							)
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By comment count', 'pbtheme'),
							'date'=> __('By date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME CATEGORY SLIDER */
/* -------------------------------------------------------------------------------- */
$divshort_products_cat = array(
	'pbtheme_products_category' => array(
		'type' => 'draggable',
		'text' => __('Products Slider','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/products-slider.png',
		'function' => 'pbtheme_products_category',
		'group' => __('WooCommerce', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_products_cat' => array(
				'type' => 'collapsible',
				'label' => __('Products Slider','pbtheme'),
				'open' => 'true',
				'options' => array(
					'ids' => array(
						'type' => 'select',
						'label' => __('Products:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '',
						'options' => $pbuilder_woo_categories_slug,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'title' => array(
						'type' => 'input',
						'label' => __('Title:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => __('Previous/Next Products','pbtheme')
					),
					'per_page' => array(
						'type' => 'input',
						'label' => __('Per page:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 5
					),
					'columns' => array(
						'type' => 'select',
						'label' => __('Columns:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '4',
						'options' => array (
							'1'=> '1',
							'2'=> '2',
							'3'=> '3',
							'4'=> '4'
							)
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By Comment Count', 'pbtheme'),
							'date'=> __('By Date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME CATEGORIES */
/* -------------------------------------------------------------------------------- */
$divshort_products_caties = array(
	'pbtheme_product_categories' => array(
		'type' => 'draggable',
		'text' => __('Product Categories','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/products-categories.png',
		'function' => 'pbtheme_product_categories',
		'group' => __('WooCommerce', 'pbtheme'),
		'options' => array_merge(
		array(
			'group_products_caties' => array(
				'type' => 'collapsible',
				'label' => __('Product Categories','pbtheme'),
				'open' => 'true',
				'options' => array(
					'ids' => array(
						'type' => 'select',
						'label' => __('Category:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '',
						'options' => $pbuilder_woo_categories,
						'search' => 'true',
						'multiselect' => 'true'
					),
					'per_page' => array(
						'type' => 'input',
						'label' => __('Per page:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 4
					),
					'columns' => array(
						'type' => 'select',
						'label' => __('Columns:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => '4',
						'options' => array (
							'1'=> '1',
							'2'=> '2',
							'3'=> '3',
							'4'=> '4'
							)
					),
					'orderby' => array(
						'type' => 'select',
						'label' => __('Sort by:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'date',
						'options' => array (
							'comment_count'=> __('By comment count', 'pbtheme'),
							'date'=> __('By date', 'pbtheme'),
							'title'=> __('By Title', 'pbtheme'),
							'rand'=> __('Random', 'pbtheme')
							)
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Order:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => 'DESC',
						'options' => array (
							'ASC'=> __('Ascending', 'pbtheme'),
							'DESC'=> __('Descending', 'pbtheme')
							)
					),
					'title' => array(
						'type' => 'input',
						'label' => __('Title:','pbtheme'),
						'label_width' => 0.25,
						'control_width' => 0.75,
						'std' => __('Previous/Next Products','pbtheme')
					)
				)
			)
		),
		$classControl,
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
					)
				)
			)
		),
		$animationControl
		)
	)
);


/* -------------------------------------------------------------------------------- */
/* PBTHEME CART */
/* -------------------------------------------------------------------------------- */
$divshort_divcart = array(
	'pbtheme_cart' => array(
		'type' => 'draggable',
		'text' => __('Cart','pbtheme'),
		'icon' => get_template_directory_uri() . '/images/pbuilder/cart.png',
		'function' => 'pbtheme_cart',
		'group' => __('WooCommerce', 'pbtheme'),
		'options' => array_merge(
		array(
		array(
			'group_general' => array(
				'type' => 'collapsible',
				'label' => __('General','pbtheme'),
				'options' => array(
					'bot_margin' => array(
						'type' => 'number',
						'label' => __('Bottom margin:','pbtheme'),
						'std' => $opts['bottom_margin'],
						'unit' => 'px'
							)
						)
					)
				)
			)
		)
	)
);

			$woocommerce_shortcodes = array_merge ($divshort_toprated_prd, $divshort_sale_prd, $divshort_recent_prd, $divshort_featured_prd, $divshort_products, $divshort_products_cat, $divshort_products_caties, $divshort_divcart);

			if ( isset($pbuilder) && is_admin()) {
				$pbuilder->add_new_shortcodes($woocommerce_shortcodes);
			}
		}
	add_action('init', 'refresh_woo_categories');
}

endif;

// [pbtheme_team]
if ( !function_exists('pbtheme_team') ) :
function pbtheme_team( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'user' => '1',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	global $pbtheme_data;

	$bot_margin = (int)$bot_margin;

	$out = '';
	
	if ( is_array( $pbtheme_data['contact'] ) ) $contacts = $pbtheme_data['contact']; else return;
	$users_array = explode( ',', $user );
	if ( !is_array ( $users_array ) ) { $users_array[] = $user; }
	$counter = 0;
	$counter_clear = 0;
	$out = '';
	foreach ( $contacts as $contact ) {
		$counter++;
		$contact_networks = $contact['contact'];
		if ( in_array ( $counter, $users_array ) ) {
			$counter_clear++;
			$contact_name = $contact['name'];
			$contact_description = $contact['description'];
			$contact_url = $contact['url'];
			$contact_job = $contact['job'];
			
			$out .= '<div class="team_member_module"><div class="img_wrapper margin-bottom18 relative float_left"><img src="'.$contact_url.'" alt="image" class="maxfullwidth block" /><div class="shade"></div><div class="hover_element"><div class="vert_align_wrap_system"><nav><ul class="socials list_style text-center">';
			
			foreach ( $contact_networks as $contact_network ) {
				$out .= '<li><a href="'. $contact_network['socialnetworksurl'] .'" class="background-color-main hover-background-color-lighter-main"><img width="24px" height="24px" src="' . get_bloginfo ( 'template_directory' ) . '/images/socialnetworks/' . $contact_network['socialnetworks'] . '" class="block" /></a></li>';
			}

			$out .= sprintf ('</ul></nav></div></div><!-- hover_element --></div><!-- img_wrapper --><div class="clearfix"></div><h3>%1$s</h3><div class="margin-bottom10 pbtheme_header_font">%2$s</div><!-- workplace --><div class="text">%3$s</div><!-- text --><div class="clearfix"></div></div><!-- team_member_module -->', $contact_name, $contact_job, $contact_description );

		}
	}

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;


// [pbtheme_news_categories]
if ( !function_exists('pbtheme_news_categories') ) :
function pbtheme_news_categories( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'categories' => '0',
		'rows' => '1',
		'orderby' => 'date',
		'order' => 'DESC',
		'ignoresticky' => 1,
		'excerpt_lenght' => 128,
		'bot_margin' => 36,
		'colors' => 'false',
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	$bot_margin = (int)$bot_margin;

	$out = '';
	$counter = 0;
	
	if ( $ignoresticky == 'false' ) $ignoresticky = 0; else $ignoresticky = 1;
	
	if ( $categories == 0 ) return __('No categories set.', 'pbtheme');
	$separate_categories = explode(',', $categories);
	
	$out .= '<div class="news_feed_tabs relative"><nav class="tabsnav"><ul>';
	
	$category_unique_id = array();
	foreach ( $separate_categories as $category ) {
		if ( !get_category($category) && $category != '-1' ) continue;
		
		if ( $colors == 'true' ) :
			$prod_extras = get_option(Category_Extras);
			if ( isset($prod_extras[$category]['catcolor']) ) $category_color = $prod_extras[$category]['catcolor']; else $category_color = '#888888';
			$rgb_category_color = implode(',', pbtheme_hex2rgb($category_color));
			$category_unique_id[$category] = uniqid('tab-');
			if ( $category == '-1' ) $queried_category = __('All Categories', 'pbtheme'); else $queried_category = get_cat_name( $category );
			$out .= sprintf( '<li><a href="#%1$s" class="hover-background-color-main-rgba uppercase"%3$s>%2$s</a></li>', $category_unique_id[$category], $queried_category, ' style="background-color:rgba('.$rgb_category_color.',0.8);"' );
		else :
			$category_unique_id[$category] = uniqid('tab-');
			if ( $category == '-1' ) $queried_category = __('All Categories', 'pbtheme'); else $queried_category = get_cat_name( $category );
			$out .= sprintf( '<li><a href="#%1$s" class="hover-background-color-main-rgba uppercase">%2$s</a></li>', $category_unique_id[$category], $queried_category );
		endif;
	}
	$out .= '</ul></nav><!-- tabsnav -->';

	foreach ( $separate_categories as $category ) {
		if ( !get_category($category) && $category != '-1' ) continue;
		
		if ( $category == '-1' ) {
			$category_post = new WP_Query( array( 'posts_per_page' => $rows, 'orderby' => $orderby, 'order' => $order, 'post_type' => 'post', 'post_status' => 'publish', 'ignore_sticky_posts' => $ignoresticky ) );
		}
		else {
			$category_post = new WP_Query( array( 'posts_per_page' => $rows, 'category__in' => array( $category ), 'orderby' => $orderby, 'order' => $order, 'post_type' => 'post', 'post_status' => 'publish', 'ignore_sticky_posts' => $ignoresticky ) );
		}

		if ( $category_post->have_posts() ) :

			$out .= sprintf( '<div class="single_slide" id="%1$s">', $category_unique_id[$category]);
			while ( $category_post->have_posts() ) : $category_post->the_post();
				$counter++;
				if ( $ignoresticky == 0 && is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';
				$post_format = get_post_format();
				if ( false === $post_format ) { $post_format_insert = ''; } else { $post_format_insert = sprintf( '<span class="inline_tag_block %1$s">&nbsp;%1$s&nbsp;</span>', $post_format); }
				if ( $counter == 1 ) {
					if ( has_post_thumbnail()) {
						$add_class = '';
						$video_override = get_post_meta( get_the_ID(),'pbtheme_video_override', true );
						if ( $video_override !== '' ) {
							$add_class = 'pbtheme_featured_video';
						}
						$out .= sprintf('<a href="%1$s">', get_permalink() );
						$out .= get_the_post_thumbnail( get_the_ID(), 'pbtheme-fullblog', array('class' => sprintf('block maxfullwidth margin-bottom24 %1$s pbtheme_border', $add_class)));
						$out .= '</a>';
					}
					$excerpt = get_the_excerpt();
					$timecode = get_the_date()/* . ' - ' . get_the_time()*/;
					$out .= '<div class="posts_meta margin-bottom12 pbtheme_header_font uppercase"><div class="category_meta inline-block a-inherit">'.get_the_category_list( ', ' ).'</div><div class="date_meta inline-block">'.$timecode.'</div></div>';
					$out .= sprintf( '<h3 class="margin-bottom12 block headline"><a href="%3$s">%5$s%1$s</a></h3><div class="text margin-bottom12">%2$s %4$s</div><!-- text --><div class="clearfix"></div>', get_the_title(), pbtheme_string_limit_words( $excerpt, $excerpt_lenght ), get_permalink(), $post_format_insert, $sticky_icon );

				}
				elseif ( $counter == 2 ) {
					$out .= '<div class="small_separator small_separator_pale"></div>';
					$out .= '<div class="linklist margin-top12"><ul><li class="a-inherit"><a href="'.get_permalink().'" class="background-color-main-before link-color-main">'.get_the_title().' '.$post_format_insert.'</a></li>';
				}
				else {
					$out .= '<li class="a-inherit"><a href="'.get_permalink().'" class="background-color-main-before link-color-main">'.get_the_title().' '.$post_format_insert.'</a></li>';
				}
			endwhile;
			if ( $counter !== 1 ) $out .= '</ul></div><!-- linklist -->';
		endif;
	$out .= '<div class="clearfix"></div></div><!-- single_silde -->';
	$counter = 0;
	}
	$out .= '</div>';
	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;


// [pbtheme_news_realted_posts]
if ( !function_exists('pbtheme_news_related_posts') ) :
function pbtheme_news_related_posts( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'post' => '0',
		'rows' => '1',
		'orderby' => 'rand',
		'order' => 'DESC',
		'includeref' => 'yes',
		'excerpt_lenght' => 128,
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	$bot_margin = (int)$bot_margin;

	$out = '';
	$counter = 0;
	if ( $includeref == 'true' ) $includeref = 'yes'; else $includeref = 'no';
	
	if ( $post == 0 ) {
		if ( is_single() ) {
			$post = get_the_ID();
		}
		else {
			return 'No posts set.';
		}
	}

	$separate_posts = explode(',', $post);
		
	$out .= '<div class="news_feed_tabs relative"><nav class="tabsnav"><ul>';
	foreach ( $separate_posts as $posts ) {
		if ( !get_post($posts) ) continue;
		$category = get_the_category($posts);
		$out .= sprintf( '<li><a href="#tab-%1$s" class="hover-background-color-main-rgba">%2$s</a></li>', $posts, $category[0]->cat_name );
	}
	$out .= '</ul></nav><!-- tabsnav -->';

	foreach ( $separate_posts as $posts ) {
		if ( !get_post($posts) ) continue;
		$tags = wp_get_post_tags($posts);
		$related_ids = array();
		if ( $includeref == 'yes' ) $related_ids[] = $posts;

		if ($tags) {
			$tag_ids = array();
			foreach($tags as $individual_tag) { $tag_ids[] = $individual_tag->term_id; }
			$args = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'tag__in' => $tag_ids,
				'post__not_in' => array($posts),
				'posts_per_page' => $rows-1,
				'ignore_sticky_posts' => 1,
				'orderby' => $orderby,
				'order' => $order
				);
			$related_posts = get_posts( $args );
			foreach ($related_posts as $related_post) {
				$related_ids[] = $related_post->ID;
				
			}
		}
		
		$related_args = array(
			'post__in' => $related_ids,
			'orderby' => 'post__in',
			'ignore_sticky_posts' => 1
			);

		$separate_post = new WP_Query( $related_args );

		if ( $separate_post->have_posts() ) :

			$out .= sprintf( '<div class="single_slide" id="tab-%1$s">', $posts);
			while ( $separate_post->have_posts() ) : $separate_post->the_post();
				$counter++;
				$post_format = get_post_format();
				if ( false === $post_format ) { $post_format_insert = ''; } else { $post_format_insert = sprintf( '<span class="inline_tag_block %1$s">&nbsp;%1$s&nbsp;</span>', $post_format); }
				if ( $counter == 1 ) {
					if ( has_post_thumbnail()) {
						$add_class = '';
						$video_override = get_post_meta( get_the_ID(),'pbtheme_video_override', true );
						if ( $video_override !== '' ) {
							$add_class = 'pbtheme_featured_video';
						}
						$out .= sprintf('<a href="%1$s">', get_permalink() );
						$out .= get_the_post_thumbnail( get_the_ID(), 'pbtheme-fullblog', array('class' => sprintf('block maxfullwidth margin-bottom24 %1$s pbtheme_border', $add_class)));
						$out .= '</a>';
					}
					$excerpt = get_the_excerpt();
					$timecode = get_the_date();
					$out .= '<div class="posts_meta margin-bottom12 pbtheme_header_font uppercase"><div class="category_meta inline-block a-inherit">'.get_the_category_list( ', ' ).'</div><div class="date_meta inline-block">'.$timecode.'</div></div>';
					$out .= sprintf( '<h3 class="margin-bottom12 block headline"><a href="%3$s">%1$s</a></h3><div class="text margin-bottom12">%2$s %4$s</div><!-- text --><div class="clearfix"></div>', get_the_title(), pbtheme_string_limit_words( $excerpt, $excerpt_lenght ), get_permalink(), $post_format_insert );

				}
				elseif ( $counter == 2 ) {
					$out .= '<div class="small_separator small_separator_pale"></div>';
					$out .= '<div class="linklist margin-top12"><ul><li class="a-inherit"><a href="'.get_permalink().'" class="background-color-main-before link-color-main">'.get_the_title().' '.$post_format_insert.'</a></li>';
				}
				else {
					$out .= '<li class="a-inherit"><a href="'.get_permalink().'" class="background-color-main-before link-color-main">'.get_the_title().' '.$post_format_insert.'</a></li>';
				}
			endwhile;
			if ( $counter !== 1 ) $out .= '</ul></div><!-- linklist -->';
		endif;
	$out .= '<div class="clearfix"></div></div><!-- single_silde -->';
	$counter = 0;
	}
	$out .= '</div>';
	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// [pbtheme_link_list]
if ( !function_exists('pbtheme_link_list') ) :
function pbtheme_link_list( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'category' => "-1",
		'rows' => 1,
		'orderby' => 'date',
		'order' => 'DESC',
		'ignoresticky' => 1,
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	$bot_margin = (int)$bot_margin;

	$out = '';
	$counter = 0;
	$images = array();
	$titles = array();
	$excerpts = array();
	$permalinks = array();
	if ( $ignoresticky == 'false' ) $ignoresticky = 0; else $ignoresticky = 1;

	$query_string = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $rows,
		'orderby' => $orderby,
		'order' => $order,
		'ignore_sticky_posts' => $ignoresticky
		);

	if ( $category !== "-1" ){
		$query_string = $query_string + array(
			'cat' => $category
			);
	}

	$pbtheme_posts = new WP_Query( $query_string );
	if ( $pbtheme_posts->have_posts() ) :
		while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
			$counter++;
			if ( $ignoresticky == 0 && is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';
			$out .= '';
			if ( $counter == 1 ) {
				if ( has_post_thumbnail() ) {
					$add_class = '';
					$video_override = get_post_meta( get_the_ID(),'pbtheme_video_override', true );
					if ( $video_override !== '' ) {
						$add_class = 'pbtheme_featured_video';
					}
					$images[$counter] = sprintf('<a href="%1$s">%2$s</a>', get_permalink(), get_the_post_thumbnail( get_the_ID(), 'pbtheme-fullblog', array('class' => sprintf('fullwidth margin-bottom10 %1$s', $add_class))) );
				}
				else $images[$counter] = '';
			}
			$titles[$counter] = $sticky_icon . get_the_title();
			$permalinks[$counter] = get_permalink();
		endwhile;
		
		$out .= '<div class="linklist linklist_highlighted">'.$images[1].'<div class="major_text margin-bottom10 a-inherit"><i class="fa fa-link"></i> <a href="'.$permalinks[1].'" title="'.$titles[1].'">'.$titles[1].'</a></div><!-- major_text --><div class="small_separator small_separator_pale margin-bottom10"></div><div class="linklist"><ul>';
		for ($i = 2; $i <= $counter; $i++) {
			$out .= '<li class="a-inherit"><a href="'.$permalinks[$i].'" title="'.$titles[$i].'">'.$titles[$i].'</a></li>';
		}
		$out .= '</ul></div>
		</div><!-- linklist linklist_highlighted -->';
	endif;
	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// [pbtheme_link_list_related]
if ( !function_exists('pbtheme_link_list_related') ) :
function pbtheme_link_list_related( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'post' => 0,
		'rows' => 1,
		'orderby' => 'date',
		'order' => 'DESC',
		'includeref' => 'yes',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	if ( $post == 0 ) {
		if ( is_single() ) {
			$post = get_the_ID();
		}
		else {
			return 'No posts set.';
		}
	}

	$bot_margin = (int)$bot_margin;

	$out = '';
	$counter = 0;
	$images = array();
	$titles = array();
	$excerpts = array();
	$permalinks = array();
	if ( $includeref == 'true' ) $includeref = 'yes'; else $includeref = 'no';

	$tags = wp_get_post_tags($post);
	$related_ids = array();
	if ( $includeref == 'yes' ) $related_ids[] = $post;

	if ($tags) {
		$tag_ids = array();
		foreach($tags as $individual_tag) { $tag_ids[] = $individual_tag->term_id; }
		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'tag__in' => $tag_ids,
			'post__not_in' => array($post),
			'posts_per_page' => $rows-1,
			'ignore_sticky_posts' => 1,
			'orderby' => $orderby,
			'order' => $order
			);
		$related_posts = get_posts( $args );
		foreach ($related_posts as $related_post) {
			$related_ids[] = $related_post->ID;
			
		}
	}
	
	$related_args = array(
		'post__in' => $related_ids,
		'orderby' => 'post__in',
		'ignore_sticky_posts' => 1
		);

	$pbtheme_posts = new WP_Query( $related_args );
	if ( $pbtheme_posts->have_posts() ) :
		while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
			$counter++;
			$out .= '';
			if ( $counter == 1 ) {
				if ( has_post_thumbnail() ) {
					$add_class = '';
					$video_override = get_post_meta( get_the_ID(),'pbtheme_video_override', true );
					if ( $video_override !== '' ) {
						$add_class = 'pbtheme_featured_video';
					}
					$images[$counter] = sprintf('<a href="%1$s">%2$s</a>', get_permalink(), get_the_post_thumbnail( get_the_ID(), 'pbtheme-fullblog', array('class' => sprintf('fullwidth margin-bottom10 %1$s', $add_class))) );
				}
				else $images[$counter] = '';
				$excerpt = get_the_excerpt();
				$excerpts[$counter] = pbtheme_string_limit_words( $excerpt, 96 );
			}
			$titles[$counter] = get_the_title();
			$permalinks[$counter] = get_permalink();
		endwhile;
		
		$out .= '<div class="linklist linklist_highlighted">'.$images[1].'<div class="major_text margin-bottom10"><i class="fa fa-link"></i><a href="'.$permalinks[1].'">'.$titles[1].'</a></div><!-- major_text --><div class="small_separator small_separator_pale margin-bottom10"></div><div class="linklist"><ul>';
		for ($i = 2; $i <= $counter; $i++) {
			$out .= '<li class="a-inherit"><a href="'.$permalinks[$i].'">'.$titles[$i].'</a></li>';
		}
		$out .= '</ul></div>
		</div><!-- linklist linklist_highlighted -->';
	endif;
	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;


// [pbtheme_insert_posts]
if ( !function_exists('pbtheme_insert_posts') ) :
function pbtheme_insert_posts( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title' => 'Title',
		'type' => 1,
		'category' => "-1",
		'rows' => 1,
		'orderby' => 'date',
		'ajax' => 'no',
		'order' => 'DESC',
		'pagination' => 'yes',
		'ignoresticky' => 1,
		'excerpt_lenght' => 128,
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );
	$out = '';
	$post_counter = 0;
	$add_class = '';
	
	$bot_margin = (int)$bot_margin;
	
	if ( $ajax == 'true' ) $ajax = 'yes'; else $ajax = 'no';
	if ( $pagination == 'true' ) $pagination = 'yes';
	if ( $ignoresticky == 'false' ) $ignoresticky = 0; else $ignoresticky = 1;
	
	if ( get_query_var('paged') ) { $paged = get_query_var('paged'); } elseif ( get_query_var('page') ) { $paged = get_query_var('page'); } else { $paged = 1; }
	if ( $ajax == 'yes' ) $paged = 1;
	
	switch ($type) {
		case 0 :
			$columns = 1;
			$image_size = 'pbtheme-blog';
		break;
		case 1 :
			$columns = 2;
			$image_size = 'pbtheme-blog';
		break;
		case 2 :
			$columns = 3;
			$image_size = 'pbtheme-blog';
		break;
		case 3 :
			$columns = 4;
			$image_size = 'pbtheme-blog';
		break;
		case 4 :
			$columns = 5;
			$image_size = 'pbtheme-blog';
		break;
		default :
			$columns = 1;
			$image_size = 'pbtheme-square';
		break;
		
	}

	$words = $excerpt_lenght;
	$query_string = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $columns * $rows,
		'paged' => $paged,
		'orderby' => $orderby,
		'order' => $order,
		'ignore_sticky_posts' => $ignoresticky
		);
	if ( $category !== "-1" ){
		$query_string = $query_string + array(
			'cat' => $category
			);
	}
	
	$query_string_ajax = http_build_query($query_string);
	
	$pbtheme_posts = new WP_Query( $query_string );
	$count = $pbtheme_posts->post_count;
	
	if ( $pbtheme_posts->have_posts() ) :

		if ( $pagination == 'yes' ) { if ( pbtheme_mini_pagination($pbtheme_posts->max_num_pages, $paged, 3, $ajax, $title) ) { $out .= pbtheme_mini_pagination($pbtheme_posts->max_num_pages, $paged, 3, $ajax, $title); } else { $out .= ''; } } else { $out .= ''; }

		$out .= "<div class='blog_content pbtheme_type_{$type}' data-string='{$query_string_ajax}' data-shortcode='{$words}|{$bot_margin}|{$title}'>";

			$out .= '<div class="separate-post-column anivia_row margin-top24 pbuilder_row"><div>';
			while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
				$feat_area = '';
				$heading = '';
				$post_counter++;
				if ( $ignoresticky == 0 && is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';
				if ( $add_class !== '' ) $out .= '</div></div><div class="separate-post-column anivia_row margin-top36 pbuilder_row"><div>';
				
				$out .= '<div class=" '.implode(' ', get_post_class()).' pbuilder_column pbuilder_column-1-'.$columns.'"><div class="headline_highlighted_column_block">';

				if ( $type !== 'small' ) {
					$out .= pbtheme_get_featarea($image_size);
					$timecode = get_the_date();
					$heading .= '<h3><a href="'.get_permalink().'">'.$sticky_icon.get_the_title().'</a></h3>';
					$heading .= '<div class="posts_meta"><div class="date_meta inline-block">'.$timecode.'</div><div class="category_meta inline-block a-inherit">'.__('in', 'pbtheme').' '.get_the_category_list( ', ' ).'</div></div>';
									$out .= ( $type == 0 ? $heading . $feat_area : $feat_area . $heading );
					if ( $words !== '0 ' ) {
						$excerpt = get_the_excerpt();
						$out .= '<div class="text margin-top6 margin-bottom6">'. pbtheme_string_limit_words( $excerpt, $words ).'</div>';
					}
					$out .= '<div class="div_readmore">'.do_shortcode( sprintf( '[pbtheme_title color="small_separator_pale" link="%2$s" type="h5" align="left" bot_margin="0"]%1$s[/pbtheme_title]', __('Read more', 'pbtheme'), get_permalink() ) ).'</div>';
					$out .= '</div></div>';
				}
				else {

						if ( get_post_format() == 'quote' ) {
							$feat_area .= '<div class="div_featarea div_feat_quote pbtheme_header_font margin-bottom36">'.get_the_content().'</div>';
						}
						elseif ( has_post_thumbnail() ) {
							$feat_area .= '<div class="div_featarea div_feat_small">'.get_the_post_thumbnail(get_the_ID(),'pbtheme-square');
							$feat_area .= sprintf( '<div class="pbtheme_image_hover"><div><a href="%1$s" class="pbtheme_image_hover_button" rel="bookmark"><i class="divicon-plus"></i></a></div></div></div>', get_permalink() );
						}

						$heading .= '<h3><a href="'.get_permalink().'" rel="bookmark">'.$sticky_icon.get_the_title().'</a></h3>';
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

							$heading .= '<div class="posts_meta"><div class="div_date_meta inline-block">'.$timecode.'</div><div class="div_author_meta inline-block a-inherit">'.__('by', 'anivia').' '.get_the_author_link().'</div><div class="div_category_meta inline-block a-inherit">'.__('in', 'pbtheme').' '.get_the_category_list( ', ' ).'</div><div class="div_comment_meta inline-block a-inherit">'.$write_comments.'</div></div>';
						}

						$out .= $feat_area . $heading;
						$excerpt = get_the_excerpt();
						$out .= '<div class="text margin-top6 margin-bottom24">'. pbtheme_string_limit_words( $excerpt, $words ).'</div>';
						$out .= '<div class="clearfix"></div>';
						$out .= do_shortcode( sprintf( '[pbtheme_title color="small_separator_pale" link="%2$s" type="h5" align="%3$s" bot_margin="0"]%1$s[/pbtheme_title]', __('Read more', 'pbtheme'), get_permalink(), 'right' ) );
						$out .= '</div></div>';

				}

				
				if ( $post_counter == $columns ){
					$post_counter = 0;
					$add_class = 'new_row';
				}
				else {
					$add_class = '';
				}
			endwhile;
			$out .= '</div></div>';
		$out .=  '<div class="clearfix"></div>';


	endif;
	$out .= '</div>';
	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="div_inherit_width div_touch_optimized '.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// [pbtheme_insert_posts_grid]
if ( !function_exists('pbtheme_insert_posts_grid') ) :
function pbtheme_insert_posts_grid( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'type' => 1,
		'category' => "-1",
		'slides' => 1,
		'orderby' => 'date',
		'order' => 'DESC',
		'ignoresticky' => 1,
		'excerpt_lenght' => 128,
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );
	$out = '';
	$post_counter = 0;
	$slide_counter = 0;
	$add_class = '';
	$slide = 0;
	$slide_arr = array();
	$loged = '';
	$typed_counter = 0;

	$bot_margin = (int)$bot_margin;

	$exploded = ( strpos($type,',') !== false ? explode(',', $type) : '' );


	for ($n = 1; $n <= $slides; $n++) {
		if ( is_array( $exploded ) ) {
			$typed_counter++;
			$type = $exploded[$typed_counter-1];
			$typed_counter = ( $typed_counter == count($exploded) ? 0 : $typed_counter );
		}

		if ( $type == 'rnd' ) {
			$loged = 'rnd';
			$type = rand(1,6);
		}

		switch ( $type ) {
			case 1 :
				$slide_arr[$n]['image_sizes'] = array( 'pbtheme-grid-large', 'pbtheme-grid-medium', 'pbtheme-grid-small');
				$slide_arr[$n]['post_number'] = 3;
				$slide_arr[$n]['type'] = $type;
				$slide_counter = $slide_counter + 3;
			break;
			case 2 :
				$slide_arr[$n]['image_sizes'] = array( 'pbtheme-grid-large', 'pbtheme-grid-small', 'pbtheme-grid-medium');
				$slide_arr[$n]['post_number'] = 3;
				$slide_arr[$n]['type'] = $type;
				$slide_counter = $slide_counter + 3;
			break;
			case 3 :
				$slide_arr[$n]['image_sizes'] = array( 'pbtheme-grid-large', 'pbtheme-grid-small', 'pbtheme-grid-small', 'pbtheme-grid-small');
				$slide_arr[$n]['post_number'] = 4;
				$slide_arr[$n]['type'] = $type;
				$slide_counter = $slide_counter + 4;
			break;
			case 4 :
				$slide_arr[$n]['image_sizes'] = array( 'pbtheme-grid-medium', 'pbtheme-grid-small', 'pbtheme-grid-large');
				$slide_arr[$n]['post_number'] = 3;
				$slide_arr[$n]['type'] = $type;
				$slide_counter = $slide_counter + 3;
			break;
			case 5 :
				$slide_arr[$n]['image_sizes'] = array( 'pbtheme-grid-small', 'pbtheme-grid-medium', 'pbtheme-grid-large');
				$slide_arr[$n]['post_number'] = 3;
				$slide_arr[$n]['type'] = $type;
				$slide_counter = $slide_counter + 3;
			break;
			case 6 :
				$slide_arr[$n]['image_sizes'] = array( 'pbtheme-grid-small', 'pbtheme-grid-small', 'pbtheme-grid-small', 'pbtheme-grid-large');
				$slide_arr[$n]['post_number'] = 4;
				$slide_arr[$n]['type'] = $type;
				$slide_counter = $slide_counter + 4;
			break;
		}
		if ( $loged == 'rnd' ) {
			$type = 'rnd';
			$loged = '';
		}
	}

	if ( $ignoresticky == 'false' ) $ignoresticky = 0; else $ignoresticky = 1;

	$query_string = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $slide_counter,
		'orderby' => $orderby,
		'order' => $order,
		'ignore_sticky_posts' => $ignoresticky
		);

	if ( $category !== "-1" ){
		$query_string = $query_string + array(
			'cat' => $category
			);
	}

	$pbtheme_posts = new WP_Query( $query_string );
	$count = $pbtheme_posts->post_count;


	if ( $pbtheme_posts->have_posts() ) :
		$current_type = $slide_arr[$slide+1]['type'];
		$out .= "<div class='pbtheme_grid grid_content div_grid_notresponsive'><div class='grid_slides'><div class='grid_slide pbtheme_grid_{$current_type}'><div class='grid_slide_inner'>";
			while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
				$post_counter++;
				if ( $add_class !== '' ) $out .= sprintf('</div></div><div class="grid_slide%2$s"%1$s><div class="grid_slide_inner">', ( $slide !== 0 ? ' style="left:'.$slide.'00%;"' : '' ), ' pbtheme_grid_'.$slide_arr[$slide+1]['type'] );
				if ( $ignoresticky == 0 && is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';
				$out .= '<div class="grid_single_post grid_post_'.$post_counter.'">';
				if ( has_post_thumbnail()) {
					$out .= sprintf('<a href="%1$s">', get_permalink() );
					$out .= get_the_post_thumbnail( get_the_ID(), $slide_arr[$slide+1]['image_sizes'][$post_counter-1], array('class' => sprintf('block')));
					$out .= sprintf('</a>' );
				}
				$out .= '<div class="pbtheme_grid_description pbtheme_border div_clac">';
				$out .= sprintf( '<h3 class="a-inherit"><a href ="%2$s">%1$s</a></h3>', get_the_title(), get_permalink() );
				$out .= '</div>';
				$out .= '</div>';
				if ( $post_counter == $slide_arr[$slide+1]['post_number'] ){
					$post_counter = 0;
					$slide++;
					$add_class = 'true';
				}
				else {
					$add_class = '';
				}
			endwhile;
			$out .= '</div></div></div>';
			if ( $slides !== '1') {$out .= '<div class="grid_navigation"><div class="grid_navigation_previous"><i class="fa fa-angle-left div_clac"></i></div><div class="grid_navigation_next"><i class="fa fa-angle-right div_clac"></i></div></div>';}
			$out .= '</div>';

		$post_counter = 0;
		$add_class = '';
		$slide = 0;

		$out .= "<div class='pbtheme_grid grid_content div_grid_responsive div_dis_none'><div class='grid_slides'><div class='grid_slide'><div class='grid_slide_inner'>";
			while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
				if ( $add_class !== '' ) $out .= sprintf('</div></div><div class="grid_slide"%1$s><div class="grid_slide_inner">', ( $slide !== 0 ? ' style="left:'.$slide.'00%;"' : '' ) );
				$post_counter++;

				if ( $ignoresticky == 0 && is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';
				$out .= '<div class="grid_single_post">';
				if ( has_post_thumbnail()) {
					$out .= sprintf('<a href="%1$s">', get_permalink() );
					$out .= get_the_post_thumbnail( get_the_ID(), 'pbtheme-grid-large', array('class' => sprintf('block')));
					$out .= sprintf('</a>' );
				}
				$out .= '<div class="pbtheme_grid_description pbtheme_border div_clac">';
				$out .= sprintf( '<h3 class="a-inherit"><a href ="%2$s">%1$s</a></h3>', get_the_title(), get_permalink() );
				$out .= '</div>';
				$out .= '</div>';
				if ( $post_counter == 1 ){
					$post_counter = 0;
					$slide++;
					$add_class = 'true';
				}
				else {
					$add_class = '';
				}
			endwhile;
			$out .= '</div></div></div>';
			if ( $slides !== '1') {$out .= '<div class="grid_navigation"><div class="grid_navigation_previous"><i class="fa fa-angle-left div_clac"></i></div><div class="grid_navigation_next"><i class="fa fa-angle-right div_clac"></i></div></div>';}
			$out .= '</div>';

	endif;

	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// [pbtheme_magazine_link_list]
if ( !function_exists('pbtheme_magazine_link_list') ) :
function pbtheme_magazine_link_list( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'category' => "-1",
		'rows' => 1,
		'orderby' => 'date',
		'order' => 'DESC',
		'ignoresticky' => 1,
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	$bot_margin = (int)$bot_margin;

	$out = '';
	$counter = 0;
	$images = array();
	$titles = array();
	$excerpts = array();
	$permalinks = array();
	$dates = array();
	if ( $ignoresticky == 'false' ) $ignoresticky = 0; else $ignoresticky = 1;
	
	$query_string = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $rows,
		'orderby' => $orderby,
		'order' => $order,
		'ignore_sticky_posts' => $ignoresticky
		);

	if ( $category !== "-1" ){
		$query_string = $query_string + array(
			'cat' => $category
			);
	}

	$pbtheme_posts = new WP_Query( $query_string );
	if ( $pbtheme_posts->have_posts() ) :
		while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
			$counter++;
			if ( $ignoresticky == 0 && is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';
			$out .= '';
			if ( $counter == 1 ) {
				if ( has_post_thumbnail() ) {
					$images[$counter] = sprintf('<a href="%1$s">%2$s</a>', get_permalink(), get_the_post_thumbnail( get_the_ID(), 'pbtheme-fullblog', array('class' => 'main_image block fullmaxwidth margin-bottom12')) );
				} else $images[$counter] = '';
				$excerpt = get_the_excerpt();
				$excerpts[$counter] = pbtheme_string_limit_words( $excerpt, 110 );
			}
			else {
				if ( has_post_thumbnail() ) {
					$images[$counter] = sprintf('<a href="%1$s">%2$s</a>', get_permalink(), get_the_post_thumbnail( get_the_ID(), 'pbtheme-magazine-small', array('class' => 'block')) );
				} else $images[$counter] = '';
			}
			$titles[$counter] = $sticky_icon.get_the_title();
			$permalinks[$counter] = get_permalink();
			$dates[$counter] = get_the_date();
		endwhile;
		
		$out .= '<div class="news_block_with_related">'.$images[1].'<h3 class="a-inherit margin-bottom6"><a href="'.$permalinks[1].'">'.$titles[1].'</a></h3><div class="date_blog text-color-main margin-bottom12">'.$dates[1].'</div><!-- date --><p class="margin-bottom12 block">'.$excerpts[1].'</p><div class="clearfix"></div>';
								
		for ($i = 2; $i <= $counter; $i++) {
			$out .= '<div class="small_separator small_separator_pale margin-bottom12 margin-top12"></div><div class="link_with_image_column_block">'.$images[$i].'<div class="text_wrap a-inherit"><a href="'.$permalinks[$i].'" class="block margin-bottom5 pbtheme_header_font">'.$titles[$i].'</a><div class="date_blog text-color-main">'.$dates[$i].'</div><div class="clearfix"></div></div><!-- text_wrap --></div><!-- link_with_image_column_block -->';
		}
		$out .= '</div><!-- news_block_with_related -->';
	endif;
	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;


// [pbtheme_magazine_link_list_related]
if ( !function_exists('pbtheme_magazine_link_list_related') ) :
function pbtheme_magazine_link_list_related( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'post' => 0,
		'rows' => 1,
		'orderby' => 'date',
		'order' => 'DESC',
		'includeref' => 'yes',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	if ( $post == 0 ) {
		if ( is_single() ) {
			$post = get_the_ID();
		}
		else {
			return 'No posts set.';
		}
	}

	$bot_margin = (int)$bot_margin;

	$out = '';
	$counter = 0;
	$images = array();
	$titles = array();
	$excerpts = array();
	$permalinks = array();
	if ( $includeref == 'true' ) $includeref = 'yes'; else $includeref = 'no';

	$tags = wp_get_post_tags($post);
	$related_ids = array();
	if ( $includeref == 'yes' ) $related_ids[] = $post;

	if ($tags) {
		$tag_ids = array();
		foreach($tags as $individual_tag) { $tag_ids[] = $individual_tag->term_id; }
		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'tag__in' => $tag_ids,
			'post__not_in' => array($post),
			'ignore_sticky_posts' => 1,
			'orderby' => $orderby,
			'order' => $order
			);
		$related_posts = get_posts( $args );
		foreach ($related_posts as $related_post) {
			$related_ids[] = $related_post->ID;
			
		}
	}
	
	$related_args = array(
		'post__in' => $related_ids,
		'orderby' => 'post__in',
		'ignore_sticky_posts' => 1,
		'posts_per_page' => $rows
		);

	$pbtheme_posts = new WP_Query( $related_args );
	if ( $pbtheme_posts->have_posts() ) :
		while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
			$counter++;
			$out .= '';
			if ( $counter == 1 ) {
				if ( has_post_thumbnail() ) {
					$add_class = '';
					$video_override = get_post_meta( get_the_ID(),'pbtheme_video_override', true );
					if ( $video_override !== '' ) {
						$add_class = 'pbtheme_featured_video';
					}
					$images[$counter] = sprintf('<a href="%1$s">%2$s</a>', get_permalink(), get_the_post_thumbnail( get_the_ID(), 'pbtheme-fullblog', array('class' => sprintf('main_image block fullmaxwidth margin-bottom15 %1$s', $add_class))) );
				} else $images[$counter] = '';
				$excerpt = get_the_excerpt();
				$excerpts[$counter] = pbtheme_string_limit_words( $excerpt, 110 );
			}
			else {
				if ( has_post_thumbnail() ) {
					$add_class = '';
					$video_override = get_post_meta( get_the_ID(),'pbtheme_video_override', true );
					if ( $video_override !== '' ) {
						$add_class = 'pbtheme_featured_video';
					}
					$images[$counter] = sprintf('<a href="%1$s">%2$s</a>', get_permalink(), get_the_post_thumbnail( get_the_ID(), 'pbtheme-magazine-small', array('class' => sprintf('block %1$s', $add_class))) );
				} else $images[$counter] = '';
			}
			$titles[$counter] = get_the_title();
			$permalinks[$counter] = get_permalink();
			$dates[$counter] = get_the_date() . ' - ' . get_the_time();
		endwhile;
		
		$out .= '<div class="news_block_with_related">'.$images[1].'<h3 class="a-inherit margin-bottom6"><a href="'.$permalinks[1].'">'.$titles[1].'</a></h3><div class="date_blog text-color-main margin-bottom12">'.$dates[1].'</div><!-- date --><p class="margin-bottom12 block">'.$excerpts[1].'</p><div class="clearfix"></div>';
								
		for ($i = 2; $i <= $counter; $i++) {
			$out .= '<div class="small_separator small_separator_pale margin-bottom12 margin-top12"></div><div class="link_with_image_column_block">'.$images[$i].'<div class="text_wrap a-inherit"><a href="'.$permalinks[$i].'" class="block margin-bottom5 pbtheme_header_font">'.$titles[$i].'</a><div class="date_blog text-color-main">'.$dates[$i].'</div><div class="clearfix"></div></div><!-- text_wrap --></div><!-- link_with_image_column_block -->';
		}
		$out .= '</div><!-- news_block_with_related -->';
	endif;
	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// [pbtheme_title]
if ( !function_exists('pbtheme_title') ) :
function pbtheme_title( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'align' => 'center',
		'type' => 'h3',
		'link' => '',
		'color' => 'pbtheme_dark_border',
		'add_element' => '',
		'bot_margin' => 24,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	$add = ( $add_element !== '' ? $add_element : '' );
	$bot_margin = (int)$bot_margin;

	if ( $link !== '' ) {
		$link_before = '<a href="'.$link.'"';
		$link_after = '</a>';
	}
	else {
		$link_before = '<span';
		$link_after = '</span>';
	}

	switch ($align) :
		case 'left' :
			$out = sprintf( '<%3$s class="blog_header_title pbtheme_header_font text-%2$s"><div class="title_container">%4$s %6$s class="title_holder float_left">%1$s%5$s<span class="blog_header_line left_line %7$s float_left"></span><div class="clearfix"></div></div></%3$s>', $content, $align, $type, $link_before, $link_after, $add, $color );
		break;
		case 'center' :
			$out = sprintf( '<%3$s class="blog_header_title pbtheme_header_font text-%2$s"><div class="title_container"><span class="blog_header_line left_line %7$s inline-block"></span>%4$s %6$s class="title_holder inline-block">%1$s%5$s<span class="blog_header_line right_line %7$s inline-block"></span></div></%3$s>', $content, $align, $type, $link_before, $link_after, $add, $color );
		break;
		case 'right' :
			$out = sprintf( '<%3$s class="blog_header_title pbtheme_header_font text-%2$s"><div class="title_container">%4$s %6$s class="title_holder float_right">%1$s%5$s<span class="blog_header_line left_line %7$s float_right"></span><div class="clearfix"></div></div></%3$s>', $content, $align, $type, $link_before, $link_after, $add, $color );
		break;
	endswitch;

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// [pbtheme_revslider]
if ( !function_exists('pbtheme_revslider') ) :
function pbtheme_revslider( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'slider' => '',
		'bot_margin' => 36
	), $atts ) );

	$bot_margin = (int)$bot_margin;

	if ( $slider == '' ) echo 'Please set slider.';
	
	return '<div style="padding-bottom:'.$bot_margin.'px !important;">'.do_shortcode('[rev_slider '.$slider.']' ).'</div>';
}
endif;


// [pbtheme_magazine_fluid_posts]
if ( !function_exists('pbtheme_magazine_fluid_posts') ) :
function pbtheme_magazine_fluid_posts( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'category' => "-1",
		'posts' => 10,

		'orderby' => 'date',
		'order' => 'DESC',
		'ignoresticky' => 1,
		'excerpt_lenght' => 128,
		'colored' => 0,
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	$bot_margin = (int)$bot_margin;

	$out = '';
	$post_counter = 0;
	$add_class = '';
	if ( $ignoresticky == 'false' ) $ignoresticky = 0; else $ignoresticky = 1;
	if ( $colored == 'false' ) $transparent = ' not-transparent pbtheme_pale_border'; else $transparent = '';

	$query_string = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $posts,
		'paged' => 1,
		'orderby' => $orderby,
		'order' => $order,
		'ignore_sticky_posts' => $ignoresticky
		);
	if ( $category !== "-1" ){
		$query_string = $query_string + array(
			'cat' => $category
			);
	}
	
	$query_string_ajax = http_build_query($query_string);
	
	$pbtheme_posts = new WP_Query( $query_string );
	
	if ( $pbtheme_posts->have_posts() ) :
	
		$out .= "<div class='blog_content_infinite infinite-load-target pbuilder_row' data-string='{$query_string_ajax}'><ul class='infinite-load-init'>";
		while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
			$post_counter++;
			if ( $ignoresticky == 0 && is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';
			$category = get_the_category(); 
			if($category[0]){
				$cat = $category[0]->term_id;
			}
			$prod_extras = get_option(Category_Extras);
			if ( isset($prod_extras[$cat]['catcolor']) ) $category_color = $prod_extras[$cat]['catcolor']; else $category_color = '#888888';
			
			$rgb_category_color = implode(',', pbtheme_hex2rgb($category_color));

			if ( has_post_thumbnail() ) $magazine = array ( 'class' => 'magazine_image_column magazine_image_column_item fullmaxwidth relative float_left margin-bottom20', 'style' => 'style="background:rgb('.$rgb_category_color.');"', 'style-no-image' => '' ); else $magazine = array ( 'class' => 'magazine_image_column magazine_no_image_column_item fullwidth float_left margin-bottom20', 'style' => 'style="background:rgba('.$rgb_category_color.',0.85);"', 'style-no-image' => 'style="background:rgb('.$rgb_category_color.')"' );
			
			$out .= sprintf ('<li><div class="%1$s" %2$s>', $magazine['class'], $magazine['style-no-image']);
			$out .= pbtheme_get_featarea('large');
			$tags = get_the_tags();
			$mag_tags = '';
			if ( $tags ) {
				shuffle( $tags );
				foreach ( $tags as $tag ) {
					$tag_link = get_tag_link( $tag->term_id );
							
					$mag_tags .= "<div class='mag_tag'><a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>";
					$mag_tags .= "{$tag->name}</a><span class='tag_block' {$magazine["style"]}></span></div>";
				}
			}
			$timecode = '<div class="time_code pbtheme_header_font"><div class="float_left a-inherit">' . get_the_date() . ' ' . __('in', 'pbtheme') . ' ' . get_the_category_list( ', ' ) . '</div><div class="clearfix"></div></div>';
			$out .= sprintf ('<div class="category_tag">%2$s</div><!-- category_tag -->
			<div class="hover_effect_wrapper" %1$s><div class="hover_transparent%8$s">%7$s<h4 class="margin-bottom6 a-inherit"><a href="%3$s" rel="bookmark" title="%4$s">%6$s%4$s</a></h4><div>%5$s</div></div><!-- hover_effect_wrapper --><div class="clearfix"></div></div></div><!-- magazine_image_column_item --></li>', $magazine['style'], $mag_tags, get_permalink(), get_the_title(), pbtheme_string_limit_words( get_the_excerpt(), $excerpt_lenght ), $sticky_icon, $timecode, $transparent );
		endwhile;
		
		$out .= '</ul>';


	$out .= '</div>';
	$out .= '<div class="clearfix"></div>';
	$out .= '<div class="text-center fullwidth"><div class="infinite-load-button" data-page="1">'.__('Load more posts', 'pbtheme').'</div></div>';
	endif;

	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;


// [pbtheme_contactform]
if ( !function_exists('pbtheme_contactform') ) :
function pbtheme_contactform( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'users' => '1',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	$bot_margin = (int)$bot_margin;
	$margin = ' style="margin-bottom:'.$bot_margin.'px"';

	$out = '';
	$out .= pbtheme_contact_form( $users, $margin );

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.'>'.$out.'</div>';
	return $html_animated;
}
endif;


// [pbtheme_small_link_list]
if ( !function_exists('pbtheme_small_link_list') ) :
function pbtheme_small_link_list( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'category' => "-1",
		'rows' => 1,
		'orderby' => 'date',
		'order' => 'DESC',
		'ignoresticky' => 1,
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );
	$out = '';
	$counter = 0;

	$bot_margin = (int)$bot_margin;

	if ( $ignoresticky == 'false' ) $ignoresticky = 0; else $ignoresticky = 1;

	$query_string = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $rows,
		'orderby' => $orderby,
		'order' => $order,
		'ignore_sticky_posts' => $ignoresticky
		);
	if ( $category !== "-1" ){
		$query_string = $query_string + array(
			'cat' => $category
			);
	}

	$pbtheme_posts = new WP_Query( $query_string );
	if ( $pbtheme_posts->have_posts() ) :
		while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
			$counter++;
			$titles[$counter] = get_the_title();
			$permalinks[$counter] = get_permalink();
			$dates[$counter] = get_the_date();
		endwhile;
		
		$out .= '<div class="related_links_list">';

		for ($i = 1; $i <= $counter; $i++) {
			$out .= sprintf('<div class="related_single fullwidth margin-top12"><div class="small_separator small_separator_pale margin-bottom12 margin-top12"></div><a href="%1$s" class="fullwidth link-color-main block" title="%2$s">%2$s</a><div>%4$s %3$s</div></div><!-- related_single -->', $permalinks[$i], $titles[$i], $dates[$i], __('on', 'pbtheme') );		}

		$out .= '</div><!-- related_links_list -->';

	endif;
	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// [pbtheme_small_link_list_related]
if ( !function_exists('pbtheme_small_link_list_related') ) :
function pbtheme_small_link_list_related( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'post' => 0,
		'rows' => 5,
		'orderby' => 'rand',
		'order' => 'DESC',
		'includeref' => 'no',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	if ( $post == 0 ) {
		if ( is_single() ) {
			$post = get_the_ID();
		}
		else {
			return 'No posts set.';
		}
	}

	$bot_margin = (int)$bot_margin;

	$out = '';
	$counter = 0;
	$images = array();
	$titles = array();
	$permalinks = array();
	$dates = array();
	if ( $includeref == 'true' ) $includeref = 'yes'; else $includeref = 'no';

	$tags = wp_get_post_tags($post);
	$related_ids = array();
	if ( $includeref == 'yes' ) $related_ids[] = $post;

	if ($tags) {
		$tag_ids = array();
		foreach($tags as $individual_tag) { $tag_ids[] = $individual_tag->term_id; }
		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'tag__in' => $tag_ids,
			'post__not_in' => array($post),
			'posts_per_page' => $rows-1,
			'ignore_sticky_posts' => 1,
			'orderby' => $orderby,
			'order' => $order
			);
		$related_posts = get_posts( $args );
		foreach ($related_posts as $related_post) {
			$related_ids[] = $related_post->ID;
			
		}
	}
	
	$related_args = array(
		'post__in' => $related_ids,
		'orderby' => 'post__in',
		'ignore_sticky_posts' => 1
		);

	$pbtheme_posts = new WP_Query( $related_args );
	if ( $pbtheme_posts->have_posts() ) :
		while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
			$counter++;
			$titles[$counter] = get_the_title();
			$permalinks[$counter] = get_permalink();
			$dates[$counter] = get_the_date();
		endwhile;
		
		$out .= '<div class="related_links_list">';

		for ($i = 1; $i <= $counter; $i++) {
			$out .= sprintf('<div class="related_single fullwidth margin-top12"><div class="small_separator small_separator_pale margin-bottom12 margin-top12"></div><a href="%1$s" class="fullwidth link-color-main block" title="%2$s">%2$s</a><div>%4$s %3$s</div></div><!-- related_single -->', $permalinks[$i], $titles[$i], $dates[$i], __('on', 'pbtheme') );		}

		$out .= '</div><!-- related_links_list -->';

	endif;
	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;


// [pbtheme_grid]
if ( !function_exists('pbtheme_grid') ) :
function pbtheme_grid( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'image' => '',
		'title' => '',
		'description' => '',
		'align' => 'left',
		'link' => '#',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );
	
	$bot_margin = (int)$bot_margin;

	$out = sprintf('
	<div class="pbtheme_grid pbtheme_grid_%4$s">
		<a href="%5$s"><img class="block" src="%1$s" /></a>
		<div class="pbtheme_grid_description pbtheme_border div_clac">
			<h3 class="margin-bottom12"><a href="%5$s" title="%2$s">%2$s</a></h3>
			<div>%3$s</div>
		</div>
	</div>', $image, $title, $description, $align, $link);

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;


// Woo

// [pbtheme_top_rated_products]
if ( !function_exists('pbtheme_top_rated_products') ) :
function pbtheme_top_rated_products( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'per_page' => 5,
		'columns' => '4',
		'orderby' => 'date',
		'order' => 'DESC',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	$bot_margin = (int)$bot_margin;

	$out = '';
	$shortcode = '[top_rated_products per_page='.$per_page.' columns="'.$columns.'" orderby="'.$orderby.'" order="'.$order.'"]';
	$out .= do_shortcode($shortcode);

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="pbtheme_woo_wrap '.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;

}
endif;

// [pbtheme_sale_products]
if ( !function_exists('pbtheme_sale_products') ) :
function pbtheme_sale_products( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'per_page' => 5,
		'columns' => '4',
		'orderby' => 'date',
		'order' => 'DESC',
		'bot_margin' => 36,
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	$bot_margin = (int)$bot_margin;

	$out = '';
	$shortcode = '[sale_products per_page='.$per_page.' columns="'.$columns.'" orderby="'.$orderby.'" order="'.$order.'"]';
	$out .= '<div class="pbtheme_woo_wrap">'.do_shortcode($shortcode).'</div>';

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// [pbtheme_recent_products]
if ( !function_exists('pbtheme_recent_products') ) :
function pbtheme_recent_products( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'per_page' => 5,
		'columns' => '4',
		'orderby' => 'date',
		'order' => 'DESC',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	$bot_margin = (int)$bot_margin;

	$out = '';
	$shortcode = '[recent_products per_page='.$per_page.' columns="'.$columns.'" orderby="'.$orderby.'" order="'.$order.'"]';
	$out .= do_shortcode($shortcode);

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="pbtheme_woo_wrap '.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// [pbtheme_featured_products]
if ( !function_exists('pbtheme_featured_products') ) :
function pbtheme_featured_products( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'per_page' => 5,
		'columns' => '4',
		'orderby' => 'date',
		'order' => 'DESC',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	$bot_margin = (int)$bot_margin;

	$out = '';
	$shortcode = '[featured_products per_page='.$per_page.' columns="'.$columns.'" orderby="'.$orderby.'" order="'.$order.'"]';
	$out .= do_shortcode($shortcode);

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="pbtheme_woo_wrap '.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// [pbtheme_products]
if ( !function_exists('pbtheme_products') ) :
function pbtheme_products( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'ids' => '',
		'columns' => '4',
		'orderby' => 'date',
		'order' => 'DESC',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	if ( $ids == '' ) return __('Please select products', 'pbtheme');

	$bot_margin = (int)$bot_margin;

	$bot_margin = (int)$bot_margin;
	$margin = ' style="margin-bottom:'.$bot_margin.'px"';

	$out = '';
	$shortcode = '[products ids="'.$ids.'" columns="'.$columns.'" orderby="'.$orderby.'" order="'.$order.'"]';
	$out .= '<div class="pbtheme_woo_wrap"'.$margin.'>'.do_shortcode($shortcode).'</div>';
	return $out;
}
endif;

// [pbtheme_product_categories]
if ( !function_exists('pbtheme_product_categories') ) :
function pbtheme_product_categories( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'ids' => '',
		'per_page' => 4,
		'columns' => '4',
		'orderby' => 'date',
		'order' => 'DESC',
		'bot_margin' => 36,
		'title' => __('Next/Prev Categories', 'pbtheme'),
		'hide_empty' => 1,
		'parent'     => '',
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	if ( $ids == '' ) return __('Please select categories', 'pbtheme');

	$bot_margin = (int)$bot_margin;
	$margin = ' style="margin-bottom:'.$bot_margin.'px"';


	$out = '';

		global $woocommerce_loop;

		if ( isset( $atts[ 'ids' ] ) ) {
			$ids = explode( ',', $atts[ 'ids' ] );
			$ids = array_map( 'trim', $ids );
		} else {
			$ids = array();
		}

		$hide_empty = ( $hide_empty == true || $hide_empty == 1 ) ? 1 : 0;

		$args = array(
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => 1,
			'include'    => $ids,
			'pad_counts' => true,
			'child_of'   => $parent,
			'parent'     => ''
		);

		$product_categories = get_terms( 'product_cat', $args );
		$cat_num = count($product_categories);
		$product_categories = array_slice($product_categories, 0, $per_page);

		$pagination = pbtheme_mini_woo_pagination_cat($cat_num, 1, $per_page, 'yes', $title);

		if ( $parent !== "" ) {
			$product_categories = wp_list_filter( $product_categories, array( 'parent' => $parent ) );
		}

		if ( $hide_empty ) {
			foreach ( $product_categories as $key => $category ) {
				if ( $category->count == 0 ) {
					unset( $product_categories[ $key ] );
				}
			}
		}

		$woocommerce_loop['columns'] = $columns;

		ob_start();

		$woocommerce_loop['loop'] = $woocommerce_loop['column'] = '';

		if ( $product_categories ) {

			woocommerce_product_loop_start();

			foreach ( $product_categories as $category ) {

				wc_get_template( 'content-product_cat.php', array(
					'category' => $category
				) );

			}

			woocommerce_product_loop_end();

		}

		woocommerce_reset_loop();

		$shortcode = ob_get_clean();

	$out .= '<div class="pbtheme_woo_wrap div_touch_optimized"'.$margin.' data-shortcode="'.$bot_margin.'|'.$title.'|'.$columns.'|'.$per_page.'|'.$order.'|'.$orderby.'|'.implode(',',$ids).'">'.$pagination.do_shortcode($shortcode).'</div>';
	return $out;

}
endif;

// [pbtheme_products_category]
if ( !function_exists('pbtheme_products_category') ) :
function pbtheme_products_category( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'ids' => '',
		'title' => '',
		'per_page' => 4,
		'columns' => '4',
		'orderby' => 'date',
		'order' => 'DESC',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );

	if ( $ids == '' ) return __('Please select categories', 'pbtheme');
	$exploded = explode(',', $ids);

	$args = array(
		'post_type'				=> 'product',
		'post_status' 			=> 'publish',
		'ignore_sticky_posts'	=> 1,
		'orderby' 				=> $orderby,
		'order' 				=> $order,
		'posts_per_page' 		=> $per_page,
		'paged' 				=> 1,
		'meta_query' 			=> array(
			array(
				'key' 			=> '_visibility',
				'value' 		=> array('catalog', 'visible'),
				'compare' 		=> 'IN'
			)
		),
		'tax_query' 			=> array(
			array(
				'taxonomy' 		=> 'product_cat',
				'terms' 		=> $exploded,
				'field' 		=> 'slug',
				'operator' 		=> 'IN'
			)
		)
	);
	
	$query_string_ajax = http_build_query($args);

	$bot_margin = (int)$bot_margin;
	$margin = " style='margin-bottom".$bot_margin."px'";

	$out = '';
	$pagination = pbtheme_mini_woo_pagination('', 1, 1, 'yes', $title);
	
	global $woocommerce, $woocommerce_loop;
	
	$woocommerce_loop['columns'] = $columns;

	ob_start();

	$products = new WP_Query( $args );

	if ( $products->have_posts() ) : ?>

		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>


				<?php woocommerce_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; ?>

		<?php woocommerce_product_loop_end(); ?>

	<?php endif;

	wp_reset_postdata();
	
	$shortcode = ob_get_clean();

	$out .= $pagination.do_shortcode($shortcode);

		if($animate != 'none') {
			$animate = ' frb_animated frb_'.$animate.'"';
			
			if($animation_delay != 0) {
				$animation_delay = (int)$animation_delay;
				$animate .= ' data-adelay="'.$animation_delay.'"';
			}
			if($animation_group != '') {
				$animate .= ' data-agroup="'.$animation_group.'"';
			}
		}
		else
			$animate = '"';
		$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="pbtheme_woo_wrap div_touch_optimized '.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;" data-string="'.$query_string_ajax.'" data-shortcode="'.$margin.'|'.$title.'|'.$columns.'">'.$out.'</div>';
		return $html_animated;

}
endif;

// [pbtheme_product_cart]
if ( !function_exists('pbtheme_cart') ) :
function pbtheme_cart( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'bot_margin' => 36
	), $atts ) );

	$bot_margin = (int)$bot_margin;
	$margin = ' style="padding-bottom:'.$bot_margin.'px"';

	$out = '';
	$shortcode = '[woocommerce_cart]';
	$out .= '<div class="pbtheme_woo_wrap"'.$margin.'>'.do_shortcode($shortcode).'</div>';
	return $out;
}
endif;

if ( !function_exists('pbuilder_accordion_alt') ) :
function pbuilder_accordion_alt ( $atts, $content=null ) {
		extract (shortcode_atts( array(
		'active' => '',
		'title' => '',
		'image' => '',
		'style' => 'pbtheme',
		'fixed_height' => 'true',
		'bot_margin' => 24,
		'title_color' => '#376a6e',
		'text_color' => '#376a6e',
		'trigger_color' => '#376a6e',
		'title_active_color' => '#376a6e',
		'trigger_active_color' => '#376a6e',
		'main_color' => '#27a8e1',
		'border_color' => '#376a6e',
		'back_color' => '',
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ));
	$content = nl2br($content);
	$styled = $style;
	$styleArray = array('pbtheme', 'clean-right', 'squared-right', 'rounded-right', 'clean-left', 'squared-left', 'rounded-left');
	if(!in_array($style,$styleArray)) $style = 'clean-right';
	$title = explode('|', $title);
	$content = explode('|', $content);
	$active = explode('|', $active);
	$image = explode('|', $image);
	
	if($border_color == '') $border_color = 'transparent';
	if($back_color == '') $back_color = 'transparent';
	$randomId = rand();
	
	if ( $styled !== 'pbtheme' ) :
		$html = '
		<style type="text/css" scoped="scoped">
			#frb_accordion_' . $randomId . ' {border-bottom-color:' . $border_color . ';}
			#frb_accordion_' . $randomId . ' h3 {color:' . $title_color . '; background:' . $back_color . '; border-top-color:' . $border_color . '; border-left-color:' . $border_color . '; border-right-color:' . $border_color . ';}
			#frb_accordion_' . $randomId . ' h3 .frb_accordion_trigger{color:' . $trigger_color . '; background:' . $back_color . ';}
			#frb_accordion_' . $randomId . ' h3.ui-state-active {color:' . $title_active_color . ' !important;}
			#frb_accordion_' . $randomId . ' h3.ui-state-active .frb_accordion_trigger{color:' . $trigger_active_color . ';}
			#frb_accordion_' . $randomId . ' div {color:' . $text_color . '; background:' . $back_color . ';}
			#frb_accordion_' . $randomId . ' h3.ui-accordion-header-active{background:' . $main_color . '; border-left-color:'.$border_color.'; border-right-color:' . $border_color . ';}
			#frb_accordion_' . $randomId . ' h3.ui-accordion-header-active .frb_accordion_trigger{' . ($style == 'squared-left' || $style == 'rounded-left' ? 'background:' . $main_color . ';' : '') . '}
			#frb_accordion_' . $randomId . ' div.ui-accordion-content-active{background:' . $main_color . '; border-left-color:'.$border_color.'; border-right-color:' . $border_color . ';}
		</style>';
	else :
		$html = '
		<style>
			#frb_accordion_'.$randomId.' {border-bottom-color:'.$border_color.';}
			#frb_accordion_'.$randomId.' h3 {color:'.$title_color.'; background:'.$back_color.'; border-top-color:'.$border_color.'; border-left-color:'.$border_color.'; border-right-color:' . $border_color . ';}
			#frb_accordion_'.$randomId.' h3 .frb_accordion_trigger:after {background:'.$trigger_color.';}
			#frb_accordion_'.$randomId.' h3.ui-state-active {color:'.$title_active_color.' !important;}
			#frb_accordion_'.$randomId.' div {color:'.$text_color.'; background:'.$back_color.';}
			#frb_accordion_'.$randomId.' h3.ui-state-active .frb_accordion_trigger:after {background:'.$trigger_active_color.';}
		</style>';
	endif;
	
	$html .= '<div id="frb_accordion_'.$randomId.'" class="frb_accordion frb_accordion_'.$style.'" data-fixedheight="'.$fixed_height.'">';
	
	if(is_array($title) && is_array($content)){
		for($i=0; $i<count($title); $i++) {
			$html .= '<h3'.($active[$i] == 'true' ? ' class="ui-state-active"' : '').' >'.$title[$i].'<span class="frb_accordion_trigger"></span></h3>';
			$image[$i] = ($image[$i] != '' ? '<img style="float:left; margin-right:10px;" src="'.$image[$i].'" alt="" />' : '');
			$html .= '<div style="">'.$image[$i].$content[$i].'<div style="clear:both;"></div></div>';
		}
	}
			
	$html .='</div>';
	
	
	$bot_margin = (int)$bot_margin;
	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.(do_shortcode($html)).'</div>';
	
	return $html;
}
endif;
remove_shortcode( 'pbuilder_accordion' );
add_shortcode( 'pbuilder_accordion', 'pbuilder_accordion_alt' );

if ( !function_exists('pbuilder_tabs_alt') ) :
function pbuilder_tabs_alt ( $atts, $content=null ) {
		extract (shortcode_atts( array(
		'active' => '',
		'title' => '',
		'image' => '',
		'style' => 'clean',
		'bot_margin' => 24,
		'title_color' => '#376a6e',
		'text_color' => '#376a6e',
		'active_tab_title_color' => '#376a6e',
		'active_tab_border_color' => '#27a8e1',
		'border_color' => '#ebecee',
		'tab_back_color' => '#376a6e',
		'back_color' => '#f4f4f4',
		'class' => '',
		'custom_id' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ));
	$content = nl2br($content);
	$styled = $style;
	$styleArray = array('pbtheme', 'clean', 'squared', 'rounded');
	if(!in_array($style,$styleArray)) $style = 'clean';
	$title = explode('|', $title);
	$content = explode('|', $content);
	$active = explode('|', $active);
	$image = explode('|', $image);
	$custom_id = explode('|', $custom_id);
	
	if($border_color == '') $border_color = 'transparent';
	if($back_color == '') $back_color = 'transparent';
	$randomId = rand();

	if ( $styled !== 'pbtheme' ) :
	$html = '
	<style>
		#frb_tabs_'.$randomId.' .frb_tabs-content {
			color:'.$text_color.';
			border:2px solid '.$border_color.';
			'.($style != 'clean' ? 'background:'.$back_color.';' :'').'
		}
		#frb_tabs_'.$randomId.' ul:first-child a {
			color:'.$title_color.';
			'.($style != 'clean' ? '
			background:'.$tab_back_color.';':'').'
		}
		#frb_tabs_'.$randomId.' ul:first-child a.active{
			'.($style != 'clean' ? '
			background:'.$back_color.';
			color:'.$active_tab_title_color.';
			border-top:2px solid '.$active_tab_border_color.';
			padding-bottom:10px !important;
			margin-top:-2px !important' : '
			padding-bottom:10px !important;
			border-bottom:2px solid '.$active_tab_border_color.';').'
		}
		#frb_tabs_'.$randomId.' ul:first-child a:hover{
			'.($style != 'clean' ? '
			background-color:'.$back_color.';
			color:'.$active_tab_title_color.';
			border-top:2px solid '.$active_tab_border_color.';
			padding-bottom:10px !important;
			margin-top:-2px !important;
			transition: border-top-color 300ms, background-color 300ms;
			-webkit-transition: border-top-color 300ms, background-color 300ms;' : '
			padding-bottom:10px !important;
			border-bottom:2px solid '.$active_tab_border_color.';
			transition: border-bottom-color 300ms;
			-webkit-transition: border-bottom-color 300ms;').'
		}
		'.($style == 'rounded' ? '
			#frb_tabs_'.$randomId.' ul:first-child li:first-child a {
				border-radius:5px 0 0 0;
			}
			#frb_tabs_'.$randomId.' ul:first-child li:last-child a {
				border-radius:0 5px 0 0;
			}
		': '').'
	</style>';
	else :
	$html = '
	<style>
		#frb_tabs_'.$randomId.' .frb_tabs-content {
			color:'.$text_color.';
			background: '.$tab_back_color.';
		}
		#frb_tabs_'.$randomId.' ul:first-child a {
			color:'.$title_color.';
		}
		#frb_tabs_'.$randomId.' ul:first-child a.active{
			color:'.$active_tab_title_color.';
		}
		#frb_tabs_'.$randomId.' ul:first-child a.active:after {
			border-top-color:'.$active_tab_border_color.' !important;
		}
		#frb_tabs_'.$randomId.' ul:first-child a.active {
			background:'.$active_tab_border_color.';
		}
		#frb_tabs_'.$randomId.' ul:first-child a {
			background:'.$tab_back_color.';
			
		}

	</style>';
	endif;
	
	$html .= '<div id="frb_tabs_'.$randomId.'" class="frb_tabs frb_tabs_'.$styled.'"><ul>';
	
	if(is_array($title) && is_array($content)){
		for($i=0; $i<count($title); $i++) {
			$html .='<li><a href="'.(isset($custom_id[$i]) && $custom_id[$i] != '' ? '#'.$custom_id[$i] : '#frb_tabs_'.$randomId.'_'.$i).'"'.($active[$i] == 'true' ? ' class="active"' : '').'>'.$title[$i].'</a></li>';
			
		}
	}
			
	$html .='</ul><div style="clear:both;"></div>';
	
	if(is_array($title) && is_array($content)){
		for($i=0; $i<count($title); $i++) {
			$image[$i] = ($image[$i] != '' ? '<img style="float:left; margin-right:10px;" src="'.$image[$i].'" alt="" />' : '');
			$html .= '<div id="'.(isset($custom_id[$i]) && $custom_id[$i] != '' ? $custom_id[$i] : 'frb_tabs_'.$randomId.'_'.$i).'" class="frb_tabs-content">'.$image[$i].$content[$i].'<div style="clear:both;"></div></div>';
		}
	}
	
	$html .='</div>';
	
	
	$bot_margin = (int)$bot_margin;
	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.(do_shortcode($html)).'</div>';
	
	return $html;
}
endif;
remove_shortcode( 'pbuilder_tabs' );
add_shortcode( 'pbuilder_tabs', 'pbuilder_tabs_alt' );

// [pbtheme_progress_bar]
if ( !function_exists('pbtheme_progress_bar') ) :
function pbtheme_progress_bar( $atts, $content = null ) {
	extract (shortcode_atts( array(
		'title' => '',
		'per_cent' => 50,
		'bot_margin' => 36,
		'back_color' => '',
		'front_color' => '',
		'arrow_color' => '',
		'title_color' => '',
		'arrow_text_color' => '',
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ));
	$randomId = 'frb_div_prog_'.rand();

	$bot_margin = (int)$bot_margin;
	$per_cent = (int)$per_cent;

	$html = '';

	$script = '<script>
		(function($){
			$(document).ready(function(){
				$(document).on("onscreen", "#'.$randomId.' .progress-done", function(){
					$(this).css({"width": "'.$per_cent.'%", "opacity": 1});
				});
			});
		})(jQuery);
	</script>';
	
	$style = '
		<style>
			#'.$randomId.' .progress-title {
				color: '.$title_color.';
			}
			#'.$randomId.' .progress-full {
				background-color: '.$back_color.';
			}
			#'.$randomId.' .progress-done {
				background-color: '.$front_color.';
			}
			#'.$randomId.' .tag-place {
				background-color: '.$arrow_color.';
				color: '.$arrow_text_color.';
			}
			#'.$randomId.' .tag-place:after {
				border-top-color: '.$arrow_color.';
			}
		</style>
	';

	$html .= $script . $style;
	$html .= '<div id="'.$randomId.'" class="progers-bars-wrapper"><div class="progress-title pbtheme_header_font margin-bottom12">'.$title.'</div><div class="progress-full"><div class="progress-done frb_animated"><div class="progress-tag"><span class="tag-place">'.$per_cent.'%</span></div></div></div></div>';

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';

	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$html.'</div>';
	return $html_animated;
}
endif;


// [pbtheme_post_pagination]
if ( !function_exists('pbtheme_post_pagination') ) :
function pbtheme_post_pagination( $atts, $content = null ) {
	extract (shortcode_atts( array(
		'previous' => __('Previous Post', 'pbtheme'),
		'next' => __('Next Post', 'pbtheme'),
		'category' => '-1',
		'show_title' => 'true',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ));

	global $post, $wp_query;
	$wp_query->is_single = true;
	$prev_link = '';
	$next_link = '';
	$bot_margin = (int)$bot_margin;

	if ( $category !== '-1' ) :
		$category = $category;
		$category = explode(',', $category);
		$category_ids = get_all_category_ids();
		$exclude = implode(',', array_diff($category_ids, $category));

		$next_post = get_next_post(false, $exclude );
		$prev_post = get_previous_post(false, $exclude );
	else :
		$next_post = get_next_post(false);
		$prev_post = get_previous_post(false);
	endif;
	
	if (!empty( $next_post )) {
		$next_link = '<div class="float_right"><a href="'.get_permalink( $next_post->ID ).'">'.$next.' &rarr;</a></div>';
	}

	if (!empty( $prev_post )){
		$prev_link = '<div class="float_left"><a href="'.get_permalink( $prev_post->ID ).'">&larr; '.$previous.'</a></div>';
	}

	$html = '';

	if ( empty( $next_post ) && empty( $prev_post ) ) {
		$next_link = '<div class="float_right"><a href="'.get_permalink().'">'.$next.' &rarr;</a></div>';
		$prev_link = '<div class="float_left"><a href="'.get_permalink().'">&larr;'.$previous.'</a></div>';
	}

	$html .= sprintf( '<div class="pbtheme_nav_element pbtheme_header_font">
		%1$s
		%2$s
		<div class="clearfix"></div>
	</div>', $next_link, $prev_link );

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$html.'</div>';
	return $html_animated;
}
endif;


// [pbtheme_portfolio]
if ( !function_exists('pbtheme_portfolio') ) :
function pbtheme_portfolio( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'type' => 1,
		'category' => "-1",
		'rows' => 1,
		'orderby' => 'date',
		'ajax' => 'no',
		'order' => 'DESC',
		'top_pagination' => 'yes',
		'top_align' => 'left',
		'pagination' => 'yes',
		'ignoresticky' => 1,
		'trans_effect' => 'snake',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );
	$out = '';
	$post_counter = 0;
	$add_class = '';
	
	$bot_margin = (int)$bot_margin;
	
	if ( $ajax == 'true' ) $ajax = 'yes'; else $ajax = 'no';
	if ( $top_pagination == 'true' ) $top_pagination = 'yes';
	if ( $pagination == 'true' ) $pagination = 'yes';
	if ( $ignoresticky == 'false' ) $ignoresticky = 0; else $ignoresticky = 1;
	
	if ( get_query_var('paged') ) { $paged = get_query_var('paged'); } elseif ( get_query_var('page') ) { $paged = get_query_var('page'); } else { $paged = 1; }
	if ( $ajax == 'yes' ) $paged = 1;
	
	switch ($type) {
		case 1 :
			$columns = 2;
			$image_size = 'pbtheme-portfolio';
		break;
		case 2 :
			$columns = 3;
			$image_size = 'pbtheme-portfolio';
		break;
		case 3 :
			$columns = 4;
			$image_size = 'pbtheme-portfolio';
		break;
		case 4 :
			$columns = 5;
			$image_size = 'pbtheme-portfolio';
		break;
	}

	$query_string = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $columns * $rows,
		'paged' => $paged,
		'orderby' => $orderby,
		'order' => $order,
		'ignore_sticky_posts' => $ignoresticky
		);
	if ( $category !== "-1" ){
		$query_string = $query_string + array(
			'cat' => $category
			);
	}
	
	$query_string_ajax = http_build_query($query_string);
	
	$pbtheme_posts = new WP_Query( $query_string );
	$count = $pbtheme_posts->post_count;
	
	if ( $pbtheme_posts->have_posts() ) :

		$out .= "<div class='portfolio_content pbtheme_portfolio_{$type}' data-string='{$query_string_ajax}' data-shortcode='{$bot_margin}|{$category}|{$top_pagination}|{$top_align}|{$trans_effect}|{$pagination}' data-columns='{$columns}'>";

		if ( $top_pagination == 'yes' ) {
			$separate_categories = explode(',', $category);
			$out .= '<div class="div_top_nav_wrap"><ul class="pbtheme_container div_top_nav_cat a-inherit pbtheme_anim_'.$trans_effect.' text-'.$top_align.'">';
			$sms_cnt = 0;
			if ( count($separate_categories) > 1 && !array_search('-1', $separate_categories, true) ) {
				$out .= sprintf( '<li class="div_ajax_port_selected"><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', __('All', 'pbtheme'), '-1' );
				$sms_cnt++;
			}
			foreach ( $separate_categories as $category_loop ) {
				$sms_cnt++;
				$selected_class = ( $sms_cnt == 1 ? ' class="div_ajax_port_selected"' : ' class="div_clac"' );
				if ( $category_loop == '-1' ) {
					$category_unique = __('All', 'pbtheme');
					$queried_category = '-1';
					$out .= sprintf( '<li%3$s><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', $category_unique, $category_loop, $selected_class );
				}
				else {
					$category_unique = get_cat_name( $category_loop );
					$queried_category =  sanitize_title($category_unique);
					$out .= sprintf( '<li%3$s><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', $category_unique, $category_loop, $selected_class );
				}
			}
			$out .= '</ul></div>';
		}

			$out .= '<div class="separate-portfolio-column anivia_row margin-top24 pbuilder_row text-left"><div>';
			while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
				$cat = get_the_category();

				if ( $ignoresticky == 0 && is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';
				$out .= '<div class="div_ajax_col pbuilder_column pbuilder_column-1-'.$columns.'" data-type="'.sanitize_title($cat[0]->name).'"><div class="headline_highlighted_column_block margin-bottom18">';
				if ( has_post_thumbnail()) {
                                     /*
                                            Code Added by Asim Ashraf - DevBatch
                                            Date: 2/2/2015
                                            Edit margin-bottom12 class remove pbtheme_portfolio class in get_the_post_thumbnail function. 
                                        */
					$out .= sprintf('<div href="%1$s" class="pbtheme_hover">', get_permalink() );
                                       
					$out .= get_the_post_thumbnail( get_the_ID(), $image_size, array('class' => sprintf('block pbtheme_portfolio')));
					$kklike = '';
					if ( in_array( 'kk-i-like-it/admin.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
						$kklike = '<div class="pbtheme_image_hover_button div_button_like inline-block">'.do_shortcode('[kklike_button]').'</div>';
					}
					$out .= sprintf('<div class="pbtheme_hover_over"><div class="div_buttons"><a href="%3$s" class="div_hov_link"><i class="divicon-plus"></i></a><a href="%1$s" class="div_hov_zoom" rel="lightbox"><i class="divicon-search"></i></a>%4$s</div></div>', wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ), get_the_title(), get_permalink(), $kklike );
					$out .= sprintf('</div>' );
				}

				$out .= '<div class="portfolio_meta pbtheme_header_font">
				<h3><a href="'.get_permalink().'">'.$sticky_icon.get_the_title().'</a></h3>
				<div class="category_meta inline-block a-inherit">'.get_the_category_list( ', ' ).'</div>
				</div>';
				$out .= '</div></div>';

			endwhile;
			$out .= '</div></div>';

		if ( $pagination == 'yes' ) { if ( pbtheme_pagination($pbtheme_posts->max_num_pages, $paged, 3, $ajax) ) { $out .= pbtheme_pagination($pbtheme_posts->max_num_pages, $paged, 3, $ajax); } else { $out .= ''; } } else { $out .= ''; }
	$out .= '</div>';
	endif;

	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="div_inherit_width div_touch_optimized '.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;

}
endif;


// [pbtheme_portfolio_alt]
if ( !function_exists('pbtheme_portfolio_alt') ) :
function pbtheme_portfolio_alt( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'type' => 1,
		'category' => "-1",
		'rows' => 1,
		'orderby' => 'date',
		'ajax' => 'no',
		'order' => 'DESC',
		'top_pagination' => 'yes',
		'top_align' => 'left',
		'pagination' => 'yes',
		'ignoresticky' => 1,
		'trans_effect' => 'snake',
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );
	$out = '';
	$post_counter = 0;
	$add_class = '';
	
	$bot_margin = (int)$bot_margin;
	
	if ( $ajax == 'true' ) $ajax = 'yes'; else $ajax = 'no';
	if ( $top_pagination == 'true' ) $top_pagination = 'yes';
	if ( $pagination == 'true' ) $pagination = 'yes';
	if ( $ignoresticky == 'false' ) $ignoresticky = 0; else $ignoresticky = 1;
	
	if ( get_query_var('paged') ) { $paged = get_query_var('paged'); } elseif ( get_query_var('page') ) { $paged = get_query_var('page'); } else { $paged = 1; }
	if ( $ajax == 'yes' ) $paged = 1;
	
	switch ($type) {
		case 1 :
			$columns = 2;
			$image_size = 'pbtheme-portfolio';
		break;
		case 2 :
			$columns = 3;
			$image_size = 'pbtheme-portfolio';
		break;
		case 3 :
			$columns = 4;
			$image_size = 'pbtheme-portfolio';
		break;
		case 4 :
			$columns = 5;
			$image_size = 'pbtheme-portfolio';
		break;
	}

	$query_string = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $columns * $rows,
		'paged' => $paged,
		'orderby' => $orderby,
		'order' => $order,
		'ignore_sticky_posts' => $ignoresticky
		);
	if ( $category !== "-1" ){
		$query_string = $query_string + array(
			'cat' => $category
			);
	}
	
	$query_string_ajax = http_build_query($query_string);
	
	$pbtheme_posts = new WP_Query( $query_string );
	$count = $pbtheme_posts->post_count;
	
	if ( $pbtheme_posts->have_posts() ) :

		$out .= "<div class='div_portfolio_slider pbtheme_portslider_{$type}' data-string='{$query_string_ajax}' data-shortcode='{$bot_margin}|{$category}|{$top_pagination}|{$top_align}|{$trans_effect}|{$pagination}' data-columns='{$columns}'>";
		if ( $top_pagination == 'yes' ) {
			$separate_categories = explode(',', $category);
			$out .= '<div class="pbtheme_background_dark div_top_nav_wrap"><ul class="pbtheme_container div_top_nav_cat pbtheme_anim_'.$trans_effect.' text-'.$top_align.'">';
			$sms_cnt = 0;
			if ( count($separate_categories) > 1 && !array_search('-1', $separate_categories, true) ) {
				$out .= sprintf( '<li class="div_ajax_port_selected"><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', __('All', 'pbtheme'), '-1' );
				$sms_cnt++;
			}
			foreach ( $separate_categories as $category_loop ) {
				$sms_cnt++;
				$selected_class = ( $sms_cnt == 1 ? ' class="div_ajax_port_selected"' : '' );
				if ( $category_loop == '-1' ) {
					$category_unique = __('All', 'pbtheme');
					$queried_category = '-1';
					$out .= sprintf( '<li%3$s><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', $category_unique, $category_loop, $selected_class );
				}
				else {
					$category_unique = get_cat_name( $category_loop );
					$queried_category =  sanitize_title($category_unique);
					$out .= sprintf( '<li%3$s><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', $category_unique, $category_loop, $selected_class );
				}
			}
			$out .= '</ul></div>';
		}
		$out .= "<div class='div_portfolio_slides'>";
			while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();
				$post_counter++;
				if ( $ignoresticky == 0 && is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';
				$out .= '<div class="div_ajax_col portslider_column divslider-column-1-'.$columns.'"><div class="headline_highlighted_column_block">';
				if ( has_post_thumbnail()) {
					$out .= sprintf('<div href="%1$s" class="pbtheme_hover">', get_permalink() );
					$out .= get_the_post_thumbnail( get_the_ID(), $image_size, array('class' => sprintf('block')));
					$kklike = '';
					if ( in_array( 'kk-i-like-it/admin.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
						$kklike = '<div class="pbtheme_image_hover_button div_button_like inline-block">'.do_shortcode('[kklike_button]').'</div>';
					}
					$out .= sprintf('<div class="pbtheme_hover_over"><div class="div_buttons"><a href="%3$s" class="div_hov_link"><i class="divicon-plus"></i></a><a href="%1$s" class="div_hov_zoom" rel="lightbox"><i class="divicon-search"></i></a>%4$s</div>', wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ), get_the_title(), get_permalink(), $kklike );
					$out .= '<div class="portslider_meta pbtheme_header_font">
					<h3><a href="'.get_permalink().'">'.$sticky_icon.get_the_title().'</a></h3>
					<div class="category_meta inline-block a-inherit">'.get_the_category_list( ', ' ).'</div>
					</div></div>';
					$out .= sprintf('</div>' );
				}
				$out .= '</div></div>';

			endwhile;
			$out .= '<div class="clearfix"></div></div>';

		if ( $pagination == 'yes' ) { if ( pbtheme_pagination($pbtheme_posts->max_num_pages, $paged, 3, $ajax) ) { $out .= pbtheme_pagination($pbtheme_posts->max_num_pages, $paged, 3, $ajax); } else { $out .= ''; } } else { $out .= ''; }
	$out .= '</div>';

	endif;

	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="div_inherit_width div_touch_optimized '.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// [pbtheme_slider]
if ( !function_exists('pbtheme_slider') ) :
function pbtheme_slider( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'category' => "-1",
		'post_count' => 10,
		'slides' => 3,
		'orderby' => 'date',
		'order' => 'DESC',
		'ignoresticky' => 1,
		'bot_margin' => 36,
		'class' => '',
		'shortcode_id' => '',
		'animate' => 'none',
		'animation_delay' => 0,
		'animation_group' => ''
	), $atts ) );
	$out = '';
	$post_counter = 0;
	$add_class = '';
	
	$bot_margin = (int)$bot_margin;

	
	if ( $ignoresticky == 'false' ) $ignoresticky = 0; else $ignoresticky = 1;
	
	if ( get_query_var('paged') ) { $paged = get_query_var('paged'); } elseif ( get_query_var('page') ) { $paged = get_query_var('page'); } else { $paged = 1; }

	$query_string = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $post_count,
		'paged' => $paged,
		'orderby' => $orderby,
		'order' => $order,
		'ignore_sticky_posts' => $ignoresticky
		);
	if ( $category !== "-1" ){
		$query_string = $query_string + array(
			'cat' => $category
			);
	}

	$pbtheme_posts = new WP_Query( $query_string );
	$count = $pbtheme_posts->post_count;
	$out .= "<div class='pbtheme_slider_wrapper' data-slides='{$slides}'>";
	if ( $pbtheme_posts->have_posts() ) :

		$out .= "<div class='pbtheme_slider_content'>";

			while( $pbtheme_posts->have_posts() ): $pbtheme_posts->the_post();

				$post_counter++;
				if ( $ignoresticky == 0 && is_sticky() ) $sticky_icon = '<i class="fa fa-pushpin"></i> '; else $sticky_icon = '';

				$out .= '<div class="separate-slider-column">';

				if ( has_post_thumbnail()) {
					$out .= sprintf('<a class="div_slider_img" href="%1$s" rel="bookmark">', get_permalink() );
					$out .= get_the_post_thumbnail( get_the_ID(), 'pbtheme-blog', array('class' => 'block'));
					$out .= '</a>';
				}
				$out .= '<div class="pbtheme_slider_meta pbtheme_header_font text-center"><h3><a href="'.get_permalink().'" rel="bookmark">'.$sticky_icon.get_the_title().'</a></h3><div class="category_meta inline-block a-inherit">'.get_the_category_list( ', ' ).'</div></div>';
				$out .= '</div>';

			endwhile;

	$out .= '</div>';
	endif;
	$out .= '</div>';

	wp_reset_query();

	if($animate != 'none') {
		$animate = ' frb_animated frb_'.$animate.'"';
		
		if($animation_delay != 0) {
			$animation_delay = (int)$animation_delay;
			$animate .= ' data-adelay="'.$animation_delay.'"';
		}
		if($animation_group != '') {
			$animate .= ' data-agroup="'.$animation_group.'"';
		}
	}
	else
		$animate = '"';
	$html_animated = '<div '.($shortcode_id != '' ? 'id="'.$shortcode_id.'"' : '').' class="'.$class.$animate.' style="padding-bottom:'.$bot_margin.'px !important;">'.$out.'</div>';
	return $html_animated;
}
endif;

// Init shortcodes
if ( !function_exists('pbtheme_shortcodes') ) :
function pbtheme_shortcodes() {
	add_shortcode( 'pbtheme_progress_bar', 'pbtheme_progress_bar' );
	add_shortcode( 'pbtheme_team', 'pbtheme_team' );
	add_shortcode( 'pbtheme_contactform', 'pbtheme_contactform' );
	add_shortcode( 'pbtheme_insert_posts', 'pbtheme_insert_posts' );
	add_shortcode( 'pbtheme_news_categories', 'pbtheme_news_categories' );
	add_shortcode( 'pbtheme_news_related_posts', 'pbtheme_news_related_posts' );
	add_shortcode( 'pbtheme_link_list', 'pbtheme_link_list' );
	add_shortcode( 'pbtheme_link_list_related', 'pbtheme_link_list_related' );
	add_shortcode( 'pbtheme_magazine_link_list', 'pbtheme_magazine_link_list' );
	add_shortcode( 'pbtheme_magazine_link_list_related', 'pbtheme_magazine_link_list_related' );
	add_shortcode( 'pbtheme_magazine_fluid_posts', 'pbtheme_magazine_fluid_posts' );
	add_shortcode( 'pbtheme_title', 'pbtheme_title' );
	add_shortcode( 'pbtheme_revslider', 'pbtheme_revslider' );
	add_shortcode( 'pbtheme_small_link_list', 'pbtheme_small_link_list' );
	add_shortcode( 'pbtheme_small_link_list_related', 'pbtheme_small_link_list_related' );
	add_shortcode( 'pbtheme_grid', 'pbtheme_grid' );
	add_shortcode( 'pbtheme_insert_posts_grid', 'pbtheme_insert_posts_grid' );
	add_shortcode( 'pbtheme_post_pagination', 'pbtheme_post_pagination' );
	add_shortcode( 'pbtheme_portfolio', 'pbtheme_portfolio' );
	add_shortcode( 'pbtheme_portfolio_alt', 'pbtheme_portfolio_alt' );
	add_shortcode( 'pbtheme_slider', 'pbtheme_slider' );

	add_shortcode( 'pbtheme_top_rated_products', 'pbtheme_top_rated_products' );
	add_shortcode( 'pbtheme_sale_products', 'pbtheme_sale_products' );
	add_shortcode( 'pbtheme_recent_products', 'pbtheme_recent_products' );
	add_shortcode( 'pbtheme_featured_products', 'pbtheme_featured_products' );
	add_shortcode( 'pbtheme_products', 'pbtheme_products' );
	add_shortcode( 'pbtheme_products_category', 'pbtheme_products_category' );
	add_shortcode( 'pbtheme_product_categories', 'pbtheme_product_categories' );
	add_shortcode( 'pbtheme_cart', 'pbtheme_cart' );
}
endif;
add_action( 'init', 'pbtheme_shortcodes' );


?>