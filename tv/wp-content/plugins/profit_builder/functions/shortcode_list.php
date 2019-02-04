<?php

$pbuilder_google_font_names = $this->get_google_fonts();
$pbuilder_google_font_variants = $this->get_font_variants();
/* Gather wordpress menus */
$nav_menus = get_terms('nav_menu', array('hide_empty' => true));
$pbuilder_menus = array();
$pbuilder_menu_std = '';
if (is_array($nav_menus))
    foreach ($nav_menus as $menu) {
        if ($pbuilder_menu_std == '')
            $pbuilder_menu_std = $menu->slug;
        $pbuilder_menus[$menu->slug] = $menu->name;
    }
/* Gather wordpress sidebars (Must be done from the wp_head)
  $pbuilder_sidebars = array();
  $pbuilder_sidebar_std = '';
  foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
  if($pbuilder_sidebar_std == '') $pbuilder_sidebar_std = $sidebar['id'];
  $pbuilder_sidebars[$sidebar['id']] = ucwords( $sidebar['name'] );
  }
 */
/* Gather wordpress posts */
global $wpdb;
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
foreach ($posts_array as $key => $obj) {
    if ($first_post == '')
        $first_post = $key;
    $pbuilder_wp_posts[$obj->ID] = $obj->post_title;
}
$admin_optionsDB = $this->option();
$opts = array();
foreach ($admin_optionsDB as $opt) {
    if (isset($opt->name) && isset($opt->value))
        $opts[$opt->name] = $opt->value;
}
$animationList = array(
    'none' => __('None', 'profit-builder'),
    'flipInX' => __('Flip in X', 'profit-builder'),
    'flipInY' => __('Flip in Y', 'profit-builder'),
    'fadeIn' => __('Fade in', 'profit-builder'),
    'fadeInDown' => __('Fade in from top', 'profit-builder'),
    'fadeInUp' => __('Fade in from bottom', 'profit-builder'),
    'fadeInLeft' => __('Fade in from left', 'profit-builder'),
    'fadeInRight' => __('Fade in from right', 'profit-builder'),
    'fadeInDownBig' => __('Slide in from top', 'profit-builder'),
    'fadeInUpBig' => __('Slide in from bottom', 'profit-builder'),
    'fadeInLeftBig' => __('Slide in from left', 'profit-builder'),
    'fadeInRightBig' => __('Slide in from right', 'profit-builder'),
    'bounceIn' => __('Bounce in', 'profit-builder'),
    'bounceInDown' => __('Bounce in from top', 'profit-builder'),
    'bounceInUp' => __('Bounce in from bottom', 'profit-builder'),
    'bounceInLeft' => __('Bounce in from left', 'profit-builder'),
    'bounceInRight' => __('Bounce in from right', 'profit-builder'),
    'rotateIn' => __('Rotate in', 'profit-builder'),
    'rotateInDownLeft' => __('Rotate in from top-left', 'profit-builder'),
    'rotateInDownRight' => __('Rotate in from top-right', 'profit-builder'),
    'rotateInUpLeft' => __('Rotate in from bottom-left', 'profit-builder'),
    'rotateInUpRight' => __('Rotate in from bottom-right', 'profit-builder'),
    'lightSpeedIn' => __('Lightning speed', 'profit-builder'),
    'rollIn' => __('Roll in', 'profit-builder')
);
$animationControl = array(
    'group_animate' => array(
        'type' => 'collapsible',
        'label' => __('Animation', 'profit-builder'),
        'options' => array(
            'animate' => array(
                'type' => 'select',
                'label' => __('Type:', 'profit-builder'),
                'std' => 'none',
                'label_width' => 0.25,
                'control_width' => 0.75,
                'options' => $animationList
            ),
            'animation_delay' => array(
                'type' => 'number',
                'label' => __('Delay:', 'profit-builder'),
                'std' => 0,
                'unit' => 'ms',
                'min' => 0,
                'step' => 50,
                'max' => 10000,
                'half_column' => 'true'
            ),
            'animation_speed' => array(
                'type' => 'number',
                'label' => __('Speed:', 'profit-builder'),
                'std' => 1000,
                'unit' => 'ms',
                'min' => 100,
                'step' => 50,
                'max' => 3000,
                'half_column' => 'true'
            ),
            'animation_group' => array(
                'type' => 'input',
                'label_width' => 0.25,
                'control_width' => 0.75,
                'label' => __('Group:', 'profit-builder'),
                'std' => '',
            )
        )
    )
);
$formImageControl = array(
    'group_formImage' => array(
        'type' => 'collapsible',
        'label' => __('Form Image', 'profit-builder'),
        'options' => array(
            'fimg_form_image' => array(
                'type' => 'checkbox',
                'label' => 'Use form image',
                'std' => 'false',
                'half_column' => 'false',
                'desc' => __('Enable form image', 'profit-builder'),
                'hide_if' => array(
                    'formstyle' => array('Horizontal'),
                )
            ),
            'fimg_content' => array(
                'type' => 'image',
                'std' => $this->url . 'images/image-default.jpg'
            ),
            'fimg_custom_dimensions' => array(
                'type' => 'checkbox',
                'label' => __('Custom Dimensions', 'profit-builder'),
                'std' => 'false'
            ),
            'fimg_image_width' => array(
                'type' => 'number',
                'label' => __('Width:', 'profit-builder'),
                'std' => 200,
                'min' => 0,
                'max' => 1200,
                'unit' => 'px',
                'half_column' => 'true',
                'hide_if' => array(
                    'custom_dimensions' => array('false')
                )
            ),
            'fimg_image_height' => array(
                'type' => 'number',
                'label' => __('Height:', 'profit-builder'),
                'std' => 200,
                'min' => 0,
                'max' => 1200,
                'unit' => 'px',
                'half_column' => 'true',
                'hide_if' => array(
                    'custom_dimensions' => array('false')
                )
            ),
            'fimg_text_align' => array(
                'type' => 'select',
                'label' => __('Image alignment:', 'profit-builder'),
                'std' => 'center',
                'options' => array(
                    'left' => __('Left', 'profit-builder'),
                    'center' => __('Center', 'profit-builder'),
                    'right' => __('Right', 'profit-builder')
                )
            ),
            'fimg_image_position' => array(
                'type' => 'select',
                'label' => __('Image position:', 'profit-builder'),
                'std' => 'top',
                'options' => array(
                    'left' => __('Left', 'profit-builder'),
                    'right' => __('Right', 'profit-builder'),
                    'top' => __('Top', 'profit-builder'),
                    'bottom' => __('Bottom', 'profit-builder')
                )
            ),
            'fimg_round' => array(
                'type' => 'checkbox',
                'label' => __('Round edges', 'profit-builder'),
                'std' => 'false',
            ),
            'fimg_round_width' => array(
                'type' => 'number',
                'label' => __('Roundness', 'profit-builder'),
                'std' => 0,
                'max' => 100,
                'unit' => 'px',
                'hide_if' => array(
                    'round' => array('false')
                )
            ),
            'fimg_border' => array(
                'type' => 'checkbox',
                'label' => __('Image border', 'profit-builder'),
                'std' => 'false',
            ),
            'fimg_border_color' => array(
                'type' => 'color',
                'label' => __('Border color:', 'profit-builder'),
                'std' => $opts['dark_border_color'],
                'label_width' => 0.5,
                'control_width' => 0.50,
                'hide_if' => array(
                    'border' => array('false')
                )
            ),
            /* 'fimg_border_hover_color' => array(
              'type' => 'color',
              'label' => __('Border hover color:','profit-builder'),
              'std' => $opts['main_color'],
              'label_width' => 0.5,
              'control_width' => 0.50,
              'hide_if' => array(
              'border' => array('false'),
              'link' => array('')
              )
              ), */
            'fimg_border_width' => array(
                'type' => 'number',
                'label' => __('Width', 'profit-builder'),
                'std' => 0,
                'max' => 100,
                'unit' => 'px',
                'hide_if' => array(
                    'border' => array('false')
                )
            ),
            'fimg_border_style' => array(
                'type' => 'select',
                'label' => __('Border Style', 'profit-builder'),
                'label_width' => 0.5,
                'control_width' => 0.5,
                'std' => 'solid',
                'options' => array(
                    'none' => 'None',
                    'hidden' => 'Hidden',
                    'dotted' => 'Dotted',
                    'dashed' => 'Dashed',
                    'solid' => 'Solid',
                    'double' => 'Double',
                    'groove' => 'Groove',
                    'ridge' => 'Ridge',
                    'inset' => 'Inset',
                    'outset' => 'Outset',
                    'initial' => 'Initial',
                    'inherit' => 'Inherit',
                ),
                'hide_if' => array(
                    'border' => array('false')
                )
            ),
            'fimg_shadow' => array(
                'type' => 'checkbox',
                'label' => __('Image shadow', 'profit-builder'),
                'std' => 'false',
            ),
            'fimg_shadow_color' => array(
                'type' => 'color',
                'label' => __('Color', 'profit-builder'),
                'label_width' => 0.5,
                'control_width' => 0.5,
                'std' => $this->option('row_shadow_color')->value,
                'hide_if' => array(
                    'shadow' => array('false')
                )
            ),
            'fimg_shadow_h_shadow' => array(
                'type' => 'number',
                'label' => __('Horizontal Shadow', 'profit-builder'),
                'std' => 0,
                'max' => 100,
                'unit' => 'px',
                'hide_if' => array(
                    'shadow' => array('false')
                )
            ),
            'fimg_shadow_v_shadow' => array(
                'type' => 'number',
                'label' => __('Vertical Shadow', 'profit-builder'),
                'std' => 0,
                'max' => 100,
                'unit' => 'px',
                'hide_if' => array(
                    'shadow' => array('false')
                )
            ),
            'fimg_shadow_blur' => array(
                'type' => 'number',
                'label' => __('Blur', 'profit-builder'),
                'std' => 0,
                'max' => 100,
                'unit' => 'px',
                'hide_if' => array(
                    'shadow' => array('false')
                )
            ),
            'fimg_top_margin' => array(
                'type' => 'number',
                'label' => __('Top margin:', 'profit-builder'),
                'std' => ( @$opts['fimg_top_margin'] > 0 ? $opts['fimg_top_margin'] : 0 ),
                'unit' => 'px'
            ),
            'fimg_bottom_margin' => array(
                'type' => 'number',
                'label' => __('Bottom margin:', 'profit-builder'),
                'std' => ( @$opts['fimg_bottom_margin'] > 0 ? $opts['fimg_bottom_margin'] : 0 ),
                'unit' => 'px'
            ),
            'fimg_left_margin' => array(
                'type' => 'number',
                'label' => __('Left margin:', 'profit-builder'),
                'std' => ( @$opts['fimg_left_margin'] > 0 ? $opts['fimg_left_margin'] : 0 ),
                'unit' => 'px'
            ),
            'fimg_right_margin' => array(
                'type' => 'number',
                'label' => __('Right margin:', 'profit-builder'),
                'std' => ( @$opts['fimg_right_margin'] > 0 ? $opts['fimg_right_margin'] : 0 ),
                'unit' => 'px'
            )
        )
    )
);
/* $formImageControl = array(
  'group_formImage' => array(
  'type' => 'collapsible',
  'label' => __('From Image','profit-builder'),
  'options' => array(
  'form_image' => array(
  'type' => 'collapsible',
  'label' => __('Image','profit-builder'),
  'open' => 'true',
  'options' => array(
  'fimg_form_image' => array(
  'type' => 'checkbox',
  'label' => 'Use form image',
  'std' => 'false',
  'half_column' => 'false',
  'desc' => __('Enable form image','profit-builder'),
  'hide_if' => array(
  'formstyle' => array('Horizontal'),
  )
  ),
  'fimg_content' => array(
  'type' => 'image',
  'std' => $this->url.'images/image-default.jpg'
  ),
  'fimg_custom_dimensions' => array(
  'type' => 'checkbox',
  'label' => __('Custom Dimensions','profit-builder'),
  'std' => 'false'
  ),
  'fimg_image_width' => array(
  'type' => 'number',
  'label' => __('Width:','profit-builder'),
  'std' => 200,
  'min' => 0,
  'max' => 1200,
  'unit' => 'px',
  'half_column' => 'true',
  'hide_if' => array(
  'custom_dimensions' => array('false')
  )
  ),
  'fimg_image_height' => array(
  'type' => 'number',
  'label' => __('Height:','profit-builder'),
  'std' => 200,
  'min' => 0,
  'max' => 1200,
  'unit' => 'px',
  'half_column' => 'true',
  'hide_if' => array(
  'custom_dimensions' => array('false')
  )
  ),
  'fimg_text_align' => array(
  'type' => 'select',
  'label' => __('Image alignment:','profit-builder'),
  'std' => 'center',
  'options' => array(
  'left' => __('Left','profit-builder'),
  'center' => __('Center','profit-builder'),
  'right' => __('Right','profit-builder')
  )
  ),
  'fimg_image_position' => array(
  'type' => 'select',
  'label' => __('Image position:','profit-builder'),
  'std' => 'top',
  'options' => array(
  'left' => __('Left','profit-builder'),
  'right' => __('Right','profit-builder'),
  'top' => __('Top','profit-builder'),
  'bottom' => __('Bottom','profit-builder')
  )
  ),
  )
  ),
  'group_fimg_image_border' => array(
  'type' => 'collapsible',
  'label' => __('Image Border','profit-builder'),
  'open' => 'true',
  'options' => array(
  'fimg_round' => array(
  'type' => 'checkbox',
  'label' => __('Round edges','profit-builder'),
  'std' => 'false',
  ),
  'fimg_round_width' => array(
  'type' => 'number',
  'label' => __('Roundness','profit-builder'),
  'std' => 0,
  'max' => 100,
  'unit' => 'px',
  'hide_if' => array(
  'round' => array('false')
  )
  ),
  'fimg_border' => array(
  'type' => 'checkbox',
  'label' => __('Image border','profit-builder'),
  'std' => 'false',
  ),
  'fimg_border_color' => array(
  'type' => 'color',
  'label' => __('Border color:','profit-builder'),
  'std' => $opts['dark_border_color'],
  'label_width' => 0.5,
  'control_width' => 0.50,
  'hide_if' => array(
  'border' => array('false')
  )
  ),
  'fimg_border_hover_color' => array(
  'type' => 'color',
  'label' => __('Border hover color:','profit-builder'),
  'std' => $opts['main_color'],
  'label_width' => 0.5,
  'control_width' => 0.50,
  'hide_if' => array(
  'border' => array('false'),
  'link' => array('')
  )
  ),
  'fimg_border_width' => array(
  'type' => 'number',
  'label' => __('Width','profit-builder'),
  'std' => 0,
  'max' => 100,
  'unit' => 'px',
  'hide_if' => array(
  'border' => array('false')
  )
  ),
  'fimg_border_style' => array(
  'type' => 'select',
  'label' => __('Border Style','profit-builder'),
  'label_width' => 0.5,
  'control_width' => 0.5,
  'std' => 'solid',
  'options' => array(
  'none' => 'None',
  'hidden' => 'Hidden',
  'dotted' => 'Dotted',
  'dashed' => 'Dashed',
  'solid' => 'Solid',
  'double' => 'Double',
  'groove' => 'Groove',
  'ridge' => 'Ridge',
  'inset' => 'Inset',
  'outset' => 'Outset',
  'initial' => 'Initial',
  'inherit' => 'Inherit',
  ),
  'hide_if' => array(
  'border' => array('false')
  )
  ),
  )
  ),
  'group_fimg_image_shadow' => array(
  'type' => 'collapsible',
  'label' => __('Shadow','profit-builder'),
  'open' => 'true',
  'options' => array(
  'fimg_shadow' => array(
  'type' => 'checkbox',
  'label' => __('Image shadow','profit-builder'),
  'std' => 'false',
  ),
  'fimg_shadow_color' => array(
  'type' => 'color',
  'label' => __('Color','profit-builder'),
  'label_width' => 0.5,
  'control_width' => 0.5,
  'std' => $this->option('row_shadow_color')->value,
  'hide_if' => array(
  'shadow' => array('false')
  )
  ),
  'fimg_shadow_h_shadow' => array(
  'type' => 'number',
  'label' => __('Horizontal Shadow','profit-builder'),
  'std' => 0,
  'max' => 100,
  'unit' => 'px',
  'hide_if' => array(
  'shadow' => array('false')
  )
  ),
  'fimg_shadow_v_shadow' => array(
  'type' => 'number',
  'label' => __('Vertical Shadow','profit-builder'),
  'std' => 0,
  'max' => 100,
  'unit' => 'px',
  'hide_if' => array(
  'shadow' => array('false')
  )
  ),
  'fimg_shadow_blur' => array(
  'type' => 'number',
  'label' => __('Blur','profit-builder'),
  'std' => 0,
  'max' => 100,
  'unit' => 'px',
  'hide_if' => array(
  'shadow' => array('false')
  )
  ),
  )
  ),
  'group_form_image_general' => array(
  'type' => 'collapsible',
  'label' => __('General','profit-builder'),
  'open' => 'true',
  'options' => array(
  'fimg_top_margin' => array(
  'type' => 'number',
  'label' => __('Top margin:','profit-builder'),
  'std' => ( $opts['fimg_top_margin'] > 0 ? $opts['fimg_top_margin'] : 0 ),
  'unit' => 'px'
  ),
  'fimg_bottom_margin' => array(
  'type' => 'number',
  'label' => __('Bottom margin:','profit-builder'),
  'std' => ( $opts['fimg_bottom_margin'] > 0 ? $opts['fimg_bottom_margin'] : 0 ),
  'unit' => 'px'
  ),
  'fimg_left_margin' => array(
  'type' => 'number',
  'label' => __('Left margin:','profit-builder'),
  'std' => ( $opts['fimg_left_margin'] > 0 ? $opts['fimg_left_margin'] : 0 ),
  'unit' => 'px'
  ),
  'fimg_right_margin' => array(
  'type' => 'number',
  'label' => __('Right margin:','profit-builder'),
  'std' => ( $opts['fimg_right_margin'] > 0 ? $opts['fimg_right_margin'] : 0 ),
  'unit' => 'px'
  )
  )
  ),
  )
  )
  ); */
if (isset($opts['css_classes']) && $opts['css_classes'] == 'true') {
    $classControl = array(
        'group_css' => array(
            'type' => 'collapsible',
            'label' => __('ID & Custom CSS', 'profit-builder'),
            'options' => array(
                'shortcode_id' => array(
                    'type' => 'input',
                    'label' => __('ID:', 'profit-builder'),
                    'desc' => __('For linking via hashtags', 'profit-builder'),
                    'label_width' => 0.25,
                    'control_width' => 0.75,
                    'std' => ''
                ),
                'class' => array(
                    'type' => 'input',
                    'label' => __('Class:', 'profit-builder'),
                    'desc' => __('For custom css', 'profit-builder'),
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
            'label' => __('Tab ID:', 'profit-builder'),
            'desc' => __('For use of anchor in url. Make sure that this ID is unique on the page.', 'profit-builder'),
            'label_width' => 0.25,
            'std' => ''
        )
    );
} else {
    $classControl = array();
    $tabsId = array();
}
/* -------------------------------------------------------------------------------- */
/* HEADING */
/* -------------------------------------------------------------------------------- */
$heading = array(
    'heading' => array(
        'type' => 'draggable',
        'text' => __('Heading', 'profit-builder'),
        'icon' => $this->url . 'images/icons/heading.png',
        'function' => 'pbuilder_h',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_heading' => array(
                'type' => 'collapsible',
                'label' => __('Heading', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'content' => array(
                        'type' => 'textarea',
                        'std' => 'Lorem ipsum',
                    ),
                    'type' => array(
                        'type' => 'select',
                        'label' => __('Type:', 'profit-builder'),
                        'std' => 'h1',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'h1' => 'H1',
                            'h2' => 'H2',
                            'h3' => 'H3',
                            'h4' => 'H4',
                            'h5' => 'H5',
                            'h6' => 'H6'
                        )
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['title_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.75
                    ),
                    'text_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Hover Color:', 'profit-builder'),
                        'std' => $opts['title_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.75
                    ),
                    'custom_font_size' => array(
                        'type' => 'checkbox',
                        'label' => __('Use custom font size', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 36,
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'line_height' => array(
                        'type' => 'number',
                        'label' => __('Line:', 'profit-builder'),
                        'std' => 'default', //'std' => 40,
                        'default' => 'default',
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'letter_spacing' => array(
                        'type' => 'number',
                        'label' => __('Spacing:', 'profit-builder'),
                        'min' => -2,
                        'max' => 10,
                        'std' => 0,
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'align' => array(
                        'type' => 'select',
                        'label' => __('Text alignment:', 'profit-builder'),
                        'options' => array(
                            'left' => 'Left',
                            'right' => 'Right',
                            'center' => 'Center'
                        ),
                        'std' => 'left'
                    )
                )
            ),
            'group_button_font_style' => array(
                'type' => 'collapsible',
                'label' => __('Font Style', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'google_font' => array(
                        'type' => 'select',
                        'class' => 'pbuilder_font_select',
                        'search' => 'true',
                        'std' => 'default',
                        'label' => __('Font family', 'profit-builder'),
                        'options' => $pbuilder_google_font_names
                    ),
                    'google_font_style' => array(
                        'name' => 'h1_font_style',
                        'type' => 'select',
                        'std' => 'default',
                        'label' => __('Font style', 'profit-builder'),
                        'options' => array('default' => 'Default'),
                        'hide_if' => array(
                            'google_font' => array('default')
                        )
                    ),
                )
            ),
            'group_heading_shadow' => array(
                'type' => 'collapsible',
                'label' => __('Shadow', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'shadow' => array(
                        'type' => 'checkbox',
                        'label' => __('Heading shadow', 'profit-builder'),
                        'std' => 'false',
                    ),
                    'shadow_color' => array(
                        'type' => 'color',
                        'label' => __('Color', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => $this->option('row_shadow_color')->value,
                        'hide_if' => array(
                            'shadow' => array('false')
                        )
                    ),
                    'shadow_h_shadow' => array(
                        'type' => 'number',
                        'label' => __('Horizontal Shadow', 'profit-builder'),
                        'std' => 0,
                        'max' => 100,
                        'unit' => 'px',
                        'hide_if' => array(
                            'shadow' => array('false')
                        )
                    ),
                    'shadow_v_shadow' => array(
                        'type' => 'number',
                        'label' => __('Vertical Shadow', 'profit-builder'),
                        'std' => 0,
                        'max' => 100,
                        'unit' => 'px',
                        'hide_if' => array(
                            'shadow' => array('false')
                        )
                    ),
                    'shadow_blur' => array(
                        'type' => 'number',
                        'label' => __('Blur', 'profit-builder'),
                        'std' => 0,
                        'max' => 100,
                        'unit' => 'px',
                        'hide_if' => array(
                            'shadow' => array('false')
                        )
                    ),
                )
            ),
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* TEXT */
/* -------------------------------------------------------------------------------- */
$text = array(
    'text' => array(
        'type' => 'draggable',
        'text' => __('Text / HTML', 'profit-builder'),
        'icon' => $this->url . 'images/icons/text-html.png',
        'function' => 'pbuilder_text',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Text / HTML', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'content' => array(
                        'type' => 'textarea',
                        'label' => __('Content:', 'profit-builder'),
                        'desc' => 'You can use text, html and/or wordpress shortcodes',
                        'std' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
                    ),
                    'autop' => array(
                        'type' => 'checkbox',
                        'label' => __('Format new lines', 'profit-builder'),
                        'std' => 'true',
                        'desc' => '"Enter" key is a new line'
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['text_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.50
                    ),
                    'text_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Hover Color:', 'profit-builder'),
                        'std' => $opts['text_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.50
                    ),
                    'custom_font_size' => array(
                        'type' => 'checkbox',
                        'label' => __('Use custom font size', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 12,
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'line_height' => array(
                        'type' => 'number',
                        'label' => __('Line:', 'profit-builder'),
                        'std' => 'default', //'std' => 15,
                        'default' => 'default',
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'align' => array(
                        'type' => 'select',
                        'label' => __('Text alignment:', 'profit-builder'),
                        'options' => array(
                            'left' => __('Left', 'profit-builder'),
                            'right' => __('Right', 'profit-builder'),
                            'center' => __('Center', 'profit-builder')
                        ),
                        'std' => 'left'
                    ),
                ),
            ),
            'group_button_font_style' => array(
                'type' => 'collapsible',
                'label' => __('Font Style', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'google_font' => array(
                        'type' => 'select',
                        'class' => 'pbuilder_font_select',
                        'search' => 'true',
                        'std' => 'default',
                        'label' => __('Font family', 'profit-builder'),
                        'options' => $pbuilder_google_font_names
                    ),
                    'google_font_style' => array(
                        'name' => 'h1_font_style',
                        'type' => 'select',
                        'std' => 'default',
                        'label' => __('Font style', 'profit-builder'),
                        'options' => array('default' => 'Default'),
                        'hide_if' => array(
                            'google_font' => array('default')
                        )
                    ),
                )
            ),
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* COMMENTS */
/* -------------------------------------------------------------------------------- */
$comments = array(
    'comments' => array(
        'type' => 'draggable',
        'text' => __('Comments', 'profit-builder'),
        'icon' => $this->url . 'images/icons/comments.png',
        'function' => 'pbuilder_comments',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_heading' => array(
                'type' => 'collapsible',
                'label' => __('Comment Block', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'content' => array(
                        'type' => 'textarea',
                        'std' => 'Leave a Comment Below and Tell us What You Think...'
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['title_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.75
                    ),
                    'custom_font_size' => array(
                        'type' => 'checkbox',
                        'label' => __('Use custom font size', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 28,
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'line_height' => array(
                        'type' => 'number',
                        'label' => __('Line:', 'profit-builder'),
                        'std' => 'default', //'std' => 28,
                        'default' => 'default',
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'letter_spacing' => array(
                        'type' => 'number',
                        'label' => __('Spacing:', 'profit-builder'),
                        'min' => -2,
                        'max' => 10,
                        'std' => 0,
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                )
            ),
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* Facebook COMMENTS */
/* -------------------------------------------------------------------------------- */
$fbcomments = array(
    'fbcomments' => array(
        'type' => 'draggable',
        'text' => __('FB Comments', 'profit-builder'),
        'icon' => $this->url . 'images/icons/comments.png',
        'function' => 'pbuilder_fbcomments',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_heading' => array(
                'type' => 'collapsible',
                'label' => __('Comment Block', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'fb_comment_url' => array(
                        'type' => 'input',
                        'label' => 'URL to comment on:',
                        'desc' => 'Enter the URL of the page you want to comment on',
                        'std' => get_bloginfo('url'),
                        'class' => 'fb_pbuilder_control',
                    ),
                    'fb_language' => array(
                        'type' => 'select',
                        'std' => (isset($opts['fb_language']) ? $opts['fb_language'] : 'en_US'),
                        'label' => 'Language',
                        'desc' => '',
                        'class' => 'fb_pbuilder_control',
                        'options' => $this->get_languages(),
                    ),
                    'fb_no_posts' => array(
                        'type' => 'input',
                        'label' => 'Number of posts:',
                        'desc' => 'The number of posts to display by default',
                        'std' => (isset($opts['fb_no_posts']) ? $opts['fb_no_posts'] : '10'),
                        'class' => 'fb_pbuilder_control',
                    ),
                    'fb_width' => array(
                        'type' => 'input',
                        'label' => 'Width:',
                        'desc' => '',
                        'std' => (isset($opts['fb_width']) ? $opts['fb_width'] : '100'),
                        'class' => 'fb_pbuilder_control',
                    ),
                    'fb_width_type' => array(
                        'type' => 'select',
                        'std' => (isset($opts['fb_width_type']) ? $opts['fb_width_type'] : '%'),
                        'label' => 'Width Type',
                        'desc' => '',
                        'class' => 'fb_pbuilder_control',
                        'options' => array(
                            'px' => "px",
                            '%' => "%",
                        ),
                    ),
                    'fb_color_scheme' => array(
                        'type' => 'select',
                        'search' => 'true',
                        'std' => (isset($opts['fb_color_scheme']) ? $opts['fb_color_scheme'] : 'light'),
                        'label' => 'Color Scheme',
                        'desc' => '',
                        'class' => 'fb_pbuilder_control',
                        'options' => array(
                            'light' => "Light",
                            'dark' => "Dark",
                        ),
                    ),
                    'fb_form_title' => array(
                        'type' => 'input',
                        'label' => 'Form Title:',
                        'desc' => 'Just in case you need to add a title above your comment form, e.g., &lt;h3&gt;Comments&lt;/h3&gt;',
                        'std' => (isset($opts['fb_form_title']) ? $opts['fb_form_title'] : ''),
                        'class' => 'fb_pbuilder_control',
                    ),
                    'fb_source_url' => array(
                        'type' => 'input',
                        'label' => 'Source URL:',
                        'desc' => 'Facebook Comments Source URL',
                        'std' => (isset($opts['fb_source_url']) ? $opts['fb_source_url'] : ''),
                        'class' => 'fb_pbuilder_control',
                    ),
                )
            ),
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* SOCIAL BUTTONS */
/* -------------------------------------------------------------------------------- */
$social = array(
    'social' => array(
        'type' => 'draggable',
        'text' => __('Social Share', 'profit-builder'),
        'icon' => $this->url . 'images/icons/social.png',
        'function' => 'pbuilder_social',
        'group' => __('Advanced', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_heading' => array(
                'type' => 'collapsible',
                'label' => __('Title Settings', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'content' => array(
                        'type' => 'textarea',
                        'std' => '',
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['title_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.75
                    ),
                    'custom_font_size' => array(
                        'type' => 'checkbox',
                        'label' => __('Use custom font size', 'profit-builder'),
                        /*
                         * Asim Ashraf - DevBatch
                         * false change to true
                         * Date: 2-9-2014
                         * Edit Start
                         */
                        'std' => 'true'
                    /*
                     * Edit End;
                     */
                    ),
                    'font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        /*
                         * Asim Ashraf - DevBatch
                         * STd => 28 change to 14
                         * Date: 2-9-2014
                         * Edit Start
                         */
                        'std' => 14,
                        /*
                         * Edit End;
                         */
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'line_height' => array(
                        'type' => 'number',
                        'label' => __('Line:', 'profit-builder'),
                        'std' => 'default', //'std' => 28,
                        'default' => 'default',
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'letter_spacing' => array(
                        'type' => 'number',
                        'label' => __('Spacing:', 'profit-builder'),
                        'min' => -2,
                        'max' => 10,
                        'std' => 0,
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                )
            ),
            'group_social' => array(
                'type' => 'collapsible',
                'label' => __('Social Share Buttons', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'email' => array(
                        'type' => 'checkbox',
                        'label' => __('Email', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'facebook' => array(
                        'type' => 'checkbox',
                        'label' => __('Facebook', 'profit-builder'),
                        'std' => 'true'
                    ),
                    'twitter' => array(
                        'type' => 'checkbox',
                        'label' => __('Twitter', 'profit-builder'),
                        'std' => 'true'
                    ),
                    'google' => array(
                        'type' => 'checkbox',
                        'label' => __('Google+', 'profit-builder'),
                        'std' => 'true'
                    ),
                    'linkedin' => array(
                        'type' => 'checkbox',
                        'label' => __('LinkedIn', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'pinterest' => array(
                        'type' => 'checkbox',
                        'label' => __('Pinterest', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'pinteresturl' => array(
                        'type' => 'input',
                        'label' => __('Pinterst URL', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'pinterest' => array('false')
                        )
                    ),
                    'instagram' => array(
                        'type' => 'checkbox',
                        'label' => __('Instagram', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'instagramurl' => array(
                        'type' => 'input',
                        'label' => __('Instagram URL', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'instagram' => array('false')
                        )
                    ),
                    'tumblr' => array(
                        'type' => 'checkbox',
                        'label' => __('Tumblr', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'tumblrurl' => array(
                        'type' => 'input',
                        'label' => __('Tumblr URL', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'tumblr' => array('false')
                        )
                    ),
                    'pocket' => array(
                        'type' => 'checkbox',
                        'label' => __('Pocket', 'profit-builder'),
                        'std' => 'false'
                    ),
                ),
            ),
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* BULLET LIST */
/* -------------------------------------------------------------------------------- */
$bulletlist = array(
    'bulletlist' => array(
        'type' => 'draggable',
        'text' => __('Bullet List', 'profit-builder'),
        'icon' => $this->url . 'images/icons/bullet-list.png',
        'function' => 'pbuilder_list',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Text', 'profit-builder'),
                'desc' => __('new row - new bullet', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'content' => array(
                        'type' => 'textarea',
                        'std' => 'Lorem ipsum'
                    ),
                    'color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['text_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.50
                    ),
                    'custom_font_size' => array(
                        'type' => 'checkbox',
                        'label' => __('Use custom font size', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 18,
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'line_height' => array(
                        'type' => 'number',
                        'label' => __('Line:', 'profit-builder'),
                        'std' => 'default', //'std' => 18,
                        'default' => 'default',
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                ),
            ),
            'group_button_font_style' => array(
                'type' => 'collapsible',
                'label' => __('Font Style', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'google_font' => array(
                        'type' => 'select',
                        'class' => 'pbuilder_font_select',
                        'search' => 'true',
                        'std' => 'default',
                        'label' => __('Font family', 'profit-builder'),
                        'options' => $pbuilder_google_font_names
                    ),
                    'google_font_style' => array(
                        'name' => 'h1_font_style',
                        'type' => 'select',
                        'std' => 'default',
                        'label' => __('Font style', 'profit-builder'),
                        'options' => array('default' => 'Default'),
                        'hide_if' => array(
                            'google_font' => array('default')
                        )
                    ),
                )
            ),
            'group_icon' => array(
                'type' => 'collapsible',
                'label' => __('Icon', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'icon' => array(
                        'type' => 'icon',
                        'label' => __('Icon:', 'profit-builder'),
                        'std' => 'fa-plus',
                        'label_width' => 0.25,
                        'control_width' => 0.75
                    ),
                    'icon_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['main_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.50
                    ),
                    'icon_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 18,
                        'unit' => 'px',
                        'half_column' => 'true'
                    )
                )
            ),
            'group_background' => array(
                'type' => 'collapsible',
                'label' => __('Background', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'radius' => array(
                        'type' => 'checkbox',
                        'label' => __('Border radius', 'profit-builder'),
                        'std' => false
                    ),
                    'background' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.25,
                        'control_width' => 0.50
                    ),
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* OPTIN */
/* -------------------------------------------------------------------------------- */
$files = array();
$dir = opendir(IMSCPB_DIR . "/images/buttons");
while (false !== ($filename = readdir($dir))) {
    if ($filename != "." && $filename != ".." && substr_count($filename, "gold.png") > 0) {
        $files[str_replace('gold.png', "", $filename)] = '<img src="' . IMSCPB_URL . '/images/buttons/' . $filename . '" class="pre-done-preview" />';
    }
}
$styles = array();
for ($index = 1; $index <= 16; $index++)
//$styles['style'.$index] = __('CSS3 Button '.$index,'profit-builder');//<a class="pbcss3button'.$css3btnstyle.'" '.$style.' href="'.$url.'" '.($type=='new-tab'? ' target="_blank" ':"").'><span class="text" style="font-size:'.$font_size.';">'.$text.'</span></a>
    $styles['style' . $index] = '<button type="button" class="pbcss3buttonpreview pbcss3buttonstyle' . $index . '"><div class="text">Read More...</div></button>';

/*
 * GoToWebinar integration
 * Code Added By Asim Ashraf - DevBatch
 * Date: 03-04-2015
 */
global $wpdb;
$table_name = $wpdb->prefix . 'profit_builder_extensions';
$extension = $wpdb->get_results('SELECT name FROM ' . $table_name . ' where name = "profit_builder_instant_gotowebinar" ', ARRAY_A);
$group_gotowebinar = array();
$imscpbiw_access_response = $this->options(" WHERE name = 'imscpbiw_access_response'");
$imscpbiw_access_response = json_decode(@$imscpbiw_access_response[0]->value);
if (!empty($extension[0]['name']) && !empty($imscpbiw_access_response->access_token) && class_exists("Curl")) {

    $Curl = new Curl($imscpbiw_access_response->access_token);
    $GetWebinarUrl = "https://api.citrixonline.com/G2W/rest/organizers/" . $imscpbiw_access_response->organizer_key . "/upcomingWebinars";
    $response = $Curl->Get($GetWebinarUrl);
    $jsonDecodeRs = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
    $fwebinars['select'] = "Please Select";
    if (!empty($imscpbiw_access_response->access_token)) {
        foreach ($jsonDecodeRs as $webinars) {
            if (!empty($webinars['organizerKey'])) {
                $fwebinars[$webinars['organizerKey'] . "," . $webinars['webinarKey']] = $webinars['subject'];
            }
        }

        $group_gotowebinar = array(
            'type' => 'collapsible',
            'label' => __('Integrate GoToWebinar', 'profit-builder'),
            'open' => 'false',
            'options' => array(
                'gotowebinarenable' => array(
                    'type' => 'checkbox',
                    'label' => 'Enable GoToWebinar',
                    'std' => 'false',
                    'half_column' => 'false',
                    'desc' => __('Enable GoToWebinar', 'profit-builder'),
                ),
                'gotowebinarshowbar' => array(
                    'type' => 'checkbox',
                    'label' => 'Enable Percentage',
                    'std' => 'false',
                    'half_column' => 'false',
                    'desc' => __('Enable Percentage', 'profit-builder'),
                    'hide_if' => array(
                        'gotowebinarenable' => array('false'),
                    )
                ),
                'gotowebinarurl' => array(
                    'type' => 'input',
                    'label' => 'Redirect Url',
                    'std' => '',
                    'half_column' => 'false',
                    'desc' => __('Redirect url after registration', 'profit-builder'),
                    'hide_if' => array(
                        'gotowebinarenable' => array('false'),
                    )
                ),
                'upcommingwebinar' => array(
                    'type' => 'select',
                    'label' => __('Select Webinar:', 'profit-builder'),
                    'std' => '',
                    'options' => $fwebinars,
                    'hide_if' => array(
                        'gotowebinarenable' => array('false'),
                    )
                ),
//                'customfieldsdiv' => array(
//                    'type' => 'div',
//                    'id' => 'customfieldsdiv',
//                ),
            )
        );
    }
}


$optin_form = array(
    'optin' => array(
        'type' => 'draggable',
        'text' => __('Optin', 'profit-builder'),
        'icon' => $this->url . 'images/icons/optin.png',
        'function' => 'pbuilder_optin',
        'group' => __('Advanced', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_form' => array(
                'type' => 'collapsible',
                'label' => __('Form HTML', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'formcode' => array(
                        'type' => 'textarea',
                        'label' => __('Form Code', 'profit-builder'),
                        'std' => '',
//                        'hide_if' => array(
//                            'gotowebinarenable' => array('true'),
//                        )
                    ),
                    'formurl' => array(
                        'type' => 'input',
                        'label' => __('Form URL:', 'profit-builder'),
                        'desc' => __('ex. http://yoursite.com/form.php', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
//                        'hide_if' => array(
//                            'gotowebinarenable' => array('true'),
//                        )
                    ),
                    'formmethod' => array(
                        'type' => 'select',
                        'label' => __('Method:', 'profit-builder'),
                        'std' => 'POST',
                        'options' => array(
                            'GET' => __('GET', 'profit-builder'),
                            'POST' => __('POST', 'profit-builder'),
                        ),
//                        'hide_if' => array(
//                            'gotowebinarenable' => array('true'),
//                        )
                    ),
                    'newwindow' => array(
                        'type' => 'checkbox',
                        'label' => 'New Window',
                        'std' => 'false',
                        'half_column' => 'true',
                        'desc' => __('Open form in new window...', 'profit-builder'),
                    ),
                    'hide_if' => array(
                        'gotowebinarenable' => array('true'),
                    )
                )
            ),
            'group_gotowebinar' => $group_gotowebinar,
            'group_form_fields' => array(
                'type' => 'collapsible',
                'label' => __('Form Fields', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'emailfield' => array(
                        //'type' => 'input',
                        'type' => 'select',
                        'label' => __('Email Field:', 'profit-builder'),
                        'desc' => __('Form input for email... Usually \'email\'', 'profit-builder'),
                        'std' => '',
                        'options' => array(""),
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'emailimage' => array(
                        'type' => 'image',
                        'label' => __('Email Image:', 'profit-builder'),
                        'std' => IMSCPB_URL . '/images/icons/email.png',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'fieldbg' => array('false'),
                        )
                    ),
                    'emailvalue' => array(
                        'type' => 'input',
                        'label' => __('Email Value:', 'profit-builder'),
                        'desc' => __('Default Value for Email Field', 'profit-builder'),
                        'std' => 'Enter your email...',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'emailerror' => array(
                        'type' => 'input',
                        'label' => __('Error Value:', 'profit-builder'),
                        'std' => 'Please enter an email',
                    ),
                    'namefield' => array(
                        //'type' => 'input',
                        'type' => 'select',
                        'label' => __('Name Field:', 'profit-builder'),
                        'desc' => __('Form input for name... Usually \'name\'', 'profit-builder'),
                        'std' => '',
                        'options' => array(""),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                      'hide_if' => array(
                      'disablename' => array('true'),
                          /* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'formstyle' => array('Horizontal'), */
                      )
                    ),
                    'nameimage' => array(
                        'type' => 'image',
                        'label' => __('Name Image:', 'profit-builder'),
                        'std' => IMSCPB_URL . '/images/icons/nameicon.png',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                      'hide_if' => array(
                      'disablename' => array('true'),
                          /* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'formstyle' => array('Horizontal'), */
                      'fieldbg' => array('false'),
                      )
                    ),
                    'namevalue' => array(
                        'type' => 'input',
                        'label' => __('Name Value:', 'profit-builder'),
                        'desc' => __('Default Value for Name Field', 'profit-builder'),
                        'std' => 'Enter your first name...',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                      'hide_if' => array(
                      'disablename' => array('true'),
                          /* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'formstyle' => array('Horizontal'),*/
                      )
                    ),
                    'namerequired' => array(
                        'type' => 'checkbox',
                        'label' => 'Required:',
                        'std' => 'false',
                        'half_column' => 'true',
                      'hide_if' => array(
                      'disablename' => array('true')/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'formstyle' => array('Horizontal'), */
                      )
                    ),
                    'nameerror' => array(
                        'type' => 'input',
                        'desc' => __('Error value for name field', 'profit-builder'),
                        'std' => 'Please enter your first name',
                        'half_column' => 'true',
                        'label_width' => 0,
                        'control_width' => 1,
                      'hide_if' => array(
                      'disablename' => array('true'),
                      'namerequired' => array('false'),
                      /* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'formstyle' => array('Horizontal'), */
                      )
                    ),
                    'formstyle' => array(
                        'type' => 'select',
                        'label' => __('Form Style:', 'profit-builder'),
                        'std' => 'Vertical',
                        'options' => array(
                            'Vertical' => __('Vertical', 'profit-builder'),
                            'Horizontal' => __('Horizontal', 'profit-builder'),
                        )
                    ),
                    'fieldbg' => array(
                        'type' => 'checkbox',
                        'label' => 'Show Icons',
                        'std' => 'true',
                        'desc' => __('Use Field Backgrounds', 'profit-builder'),
                    ),
                    'fieldbgtransparent' => array(
                        'type' => 'checkbox',
                        'label' => 'Transparent Fields',
                        'std' => 'false',
                    ),
                    'fieldbgcolor' => array(
                        'type' => 'color',
                        'label' => __('Field Color:', 'profit-builder'),
                        'std' => '#ffffff',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'fieldbgtransparent' => array('true'),
                        )
                    ),
                    'fieldtextcolor' => array(
                        'type' => 'color',
                        'label' => __('Text Color:', 'profit-builder'),
                        'std' => '#111111',
                        'label_width' => 0.5,
                        'control_width' => 0.50,
                    ),
                    'fieldfontsize' => array(
                        'type' => 'number',
                        'label' => __('Font Size:', 'profit-builder'),
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 18 to 20
                         * Edit Start
                         */
                        'std' => '20px',
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 18 to 20
                         * Edit END
                         */
                        'unit' => 'px',
                    ),
                    'disablename' => array(
                        'type' => 'checkbox',
                        'label' => 'No Name',
                        'std' => 'false',
                        'half_column' => 'true',
                        'desc' => __('Disable Name Field', 'profit-builder'),
                    /* 'hide_if' => array(
                      'formstyle' => array('Horizontal'),
                      ) */
                    ),
                )
            ),
            'group_customfields' => array(
                'type' => 'collapsible',
                'label' => __('Custom Fields', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'customfields' => array(
                        'type' => 'checkbox',
                        'label' => 'Custom Fields',
                        'std' => 'false',
                        'half_column' => 'false',
                        'desc' => __('Enable Custom Fields', 'profit-builder'),
                        'hide_if' => array(
                            'formstyle' => array('Horizontal'),
                        )
                    ),
                    'addcustomfield' => array(
                        'type' => 'button',
                        'label' => 'Add New Custom Field',
                        'id' => 'addcustomfield',
                        'control_width' => 1,
                        'hide_if' => array(
                            'customfields' => array('false'),
                            'formstyle' => array('Horizontal'),
                        )
                    ),
                    'customfieldsdiv' => array(
                        'type' => 'div',
                        'id' => 'customfieldsdiv',
                    ),
                )
            ),
            'group_hiddenfields' => array(
                'type' => 'collapsible',
                'label' => __('Hidden Fields', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'hiddenfields' => array(
                        'type' => 'checkbox',
                        'label' => 'Hidden Fields',
                        'std' => 'false',
                        'half_column' => 'false',
                        'desc' => __('Enable Hidden Fields', 'profit-builder'),
                    ),
                    'addhiddenfield' => array(
                        'type' => 'button',
                        'label' => 'Add New Hidden Field',
                        'id' => 'addhiddenfield',
                        'control_width' => 1,
                        'hide_if' => array(
                            'hiddenfields' => array('false'),
                        )
                    ),
                    'hiddenfieldsdiv' => array(
                        'type' => 'div',
                        'id' => 'hiddenfieldsdiv',
                    ),
                )
            ),
            'group_content' => array(
                'type' => 'collapsible',
                'label' => __('Form Content', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'leadin' => array(
                        'type' => 'textarea',
                        'label' => __('Lead In', 'profit-builder'),
                        'std' => 'Enter your name and email below and click the button to get started now...',
                    ),
                    'privacy' => array(
                        'type' => 'input',
                        'label' => __('Privacy:', 'profit-builder'),
                        'desc' => __('Privacy and anti-spam notice', 'profit-builder'),
                        'std' => 'We value your privacy and will never spam you',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                )
            ),
            'group_formstyle' => array(
                'type' => 'collapsible',
                'label' => __('Form Style', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'formroundedsize' => array(
                        'type' => 'number',
                        'label' => __('Radius:', 'profit-builder'),
                        'std' => 10,
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 20,
                    ),
                    'formpadding' => array(
                        'type' => 'number',
                        'label' => __('Padding:', 'profit-builder'),
                        'std' => 10,
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 20,
                    ),
                    'formbgtransparent' => array(
                        'type' => 'checkbox',
                        'label' => 'Transparent',
                        'std' => 'false',
                    ),
                    'formbgcolor' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => '#FFFFFF',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'formbgtransparent' => array('true'),
                        )
                    ),
                    'formtextcolor' => array(
                        'type' => 'color',
                        'label' => __('Text Color:', 'profit-builder'),
                        'std' => '#111111',
                        'label_width' => 0.5,
                        'control_width' => 0.50,
                    ),
                    'formborder' => array(
                        'type' => 'checkbox',
                        'label' => 'Border',
                        'std' => 'false',
                        'half_column' => 'true',
                    ),
                    'formbordercolor' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => '#cccccc',
                        'label_width' => 0.5,
                        'control_width' => 0.50,
                        'half_column' => 'true',
                        'hide_if' => array(
                            'formborder' => array('false'),
                        )
                    ),
                )
            ),
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Button Type', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'btype' => array(
                        'type' => 'select',
                        'label' => __('Button Type:', 'profit-builder'),
                        'std' => 'custom',
                        'options' => array(
                            'custom' => __('Custom Text', 'profit-builder'),
                            'css3' => __('CSS3', 'profit-builder'),
                            'predone' => __('Pre-Done', 'profit-builder'),
                            'image' => __('Image', 'profit-builder')
                        )
                    ),
                    'image' => array(
                        'type' => 'image',
                        'label' => __('Image:', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'btype' => array('custom', 'predone', 'css3'),
                        )
                    ),
                )
            ),
            'group_css3' => array(
                'type' => 'collapsible',
                'label' => __('CSS3 Buttons', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'css3btnstyle' => array(
                        'type' => 'select',
                        'label' => __('Button Style:', 'profit-builder'),
                        'std' => 'style1',
                        'options' => $styles,
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'predone'),
                        )
                    ),
                /* 'css3btnwidth' => array(
                  'type' => 'number',
                  'label' => __('Width:','profit-builder'),
                  'std' =>  '230px',
                  'min' => 0,
                  'max' => 500,
                  'half_column' => 'false',
                  'unit' => 'px',
                  'hide_if' => array(
                  'btype' => array('image','custom', 'predone'),
                  'css3btnwidthfull' => array('true')
                  )
                  ),
                  'css3btnwidthfull' => array(
                  'type' => 'checkbox',
                  'label' => '100% Width',
                  'std' => 'false',
                  'half_column' => 'false',
                  'hide_if' => array(
                  'btype' => array('image','custom', 'predone'),
                  )
                  ),
                  'css3btnheight' => array(
                  'type' => 'number',
                  'label' => __('Height:','profit-builder'),
                  'std' =>  '50px',
                  'min' => 0,
                  'max' => 500,
                  'half_column' => 'false',
                  'unit' => 'px',
                  'hide_if' => array(
                  'btype' => array('image','custom', 'predone'),
                  )
                  ), */
                )
            ),
            'group_predone' => array(
                'type' => 'collapsible',
                'label' => __('Pre-Done Buttons', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'pname' => array(
                        'type' => 'select',
                        'label' => __('Predone:', 'profit-builder'),
                        'std' => 'addtocart',
                        'options' => $files,
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'css3'),
                        )
                    ),
                    'pcolor' => array(
                        'type' => 'select',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => 'gold',
                        'options' => array(
                            'gold' => __('Gold', 'profit-builder'),
                            'black' => __('Black', 'profit-builder'),
                            'blue' => __('Blue', 'profit-builder'),
                            'red' => __('Red', 'profit-builder'),
                            'white' => __('White', 'profit-builder'),
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'css3'),
                        )
                    ),
                    'panimated' => array(
                        'type' => 'checkbox',
                        'label' => __('Animated', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'css3'),
                        )
                    ),
                )
            ),
            'group_textbutton' => array(
                'type' => 'collapsible',
                'label' => __('Text Button', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'text' => array(
                        'type' => 'input',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => __('Get Instant Access', 'profit-builder'),
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 34 to 30
                         * Edit Start
                         */
                        'std' => 20,
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 34 to 30
                         * Edit End
                         */
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'letter_spacing' => array(
                        'type' => 'number',
                        'label' => __('Spacing:', 'profit-builder'),
                        'min' => -2,
                        'max' => 10,
                        'std' => -1,
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'font_weight' => array(
                        'type' => 'select',
                        'label' => __('Weight:', 'profit-builder'),
                        'std' => 'bold',
                        'options' => array(
                            'normal' => 'Normal',
                            'lighter' => 'Lighter',
                            'bold' => 'Bold',
                            'bolder' => 'Bolder',
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'text_align' => array(
                        'type' => 'select',
                        'label' => __('Text alignment:', 'profit-builder'),
                        'std' => 'center',
                        'options' => array(
                            'left' => __('Left', 'profit-builder'),
                            'center' => __('Centered', 'profit-builder'),
                            'right' => __('Right', 'profit-builder')
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => '#ffffff',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'hover_text_color' => array(
                        'type' => 'color',
                        'label' => __('Hover:', 'profit-builder'),
                        'std' => '#ffffff',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'icon' => array(
                        'type' => 'icon',
                        'label' => __('Icon Type:', 'profit-builder'),
                        'notNull' => false,
                        'std' => 'no-icon',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'icon_align' => array(
                        'type' => 'select',
                        'label' => __('Icon alignment:', 'profit-builder'),
                        'std' => 'left',
                        'options' => array(
                            'left' => __('Left', 'profit-builder'),
                            'right' => __('Right', 'profit-builder'),
                            'inline' => __('Inline', 'profit-builder')
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'icon_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 16,
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Background Color:', 'profit-builder'),
                        'std' => '#ff9900',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'hover_back_color' => array(
                        'type' => 'color',
                        'label' => __('Background Hover:', 'profit-builder'),
                        'std' => '#ff9900',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'round' => array(
                        'type' => 'checkbox',
                        'label' => 'Round',
                        'std' => 'true',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'fill' => array(
                        'type' => 'checkbox',
                        'label' => __('Fill', 'profit-builder'),
                        'std' => 'true',
                        'half_column' => 'true',
                        'desc' => __('turn off to get a button with border', 'profit-builder'),
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'border_thickness' => array(
                        'type' => 'number',
                        'label' => __('Border thickness:', 'profit-builder'),
                        'std' => 1,
                        'min' => 0,
                        'max' => 20,
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                )
            ),
            'group_button_font_style' => array(
                'type' => 'collapsible',
                'label' => __('Font Style', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'google_font' => array(
                        'type' => 'select',
                        'class' => 'pbuilder_font_select',
                        'search' => 'true',
                        'std' => 'default',
                        'label' => __('Font family', 'profit-builder'),
                        'options' => $pbuilder_google_font_names
                    ),
                    'google_font_style' => array(
                        'name' => 'h1_font_style',
                        'type' => 'select',
                        'std' => 'default',
                        'label' => __('Font style', 'profit-builder'),
                        'options' => array('default' => 'Default'),
                        'hide_if' => array(
                            'google_font' => array('default')
                        )
                    ),
                )
            ),
            'group_buttonsize' => array(
                'type' => 'collapsible',
                'label' => __('Button Size', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'buttonwidth' => array(
                        'type' => 'number',
                        'label' => __('Width:', 'profit-builder'),
                        'std' => '230px',
                        'min' => 0,
                        'max' => 500,
                        'half_column' => 'false',
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                            'buttonwidthfull' => array('true')
                        )
                    ),
                    'buttonwidthfull' => array(
                        'type' => 'checkbox',
                        'label' => '100% Width',
                        'std' => 'true',
                        'half_column' => 'false',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                /* 'buttonheight' => array(
                  'type' => 'number',
                  'label' => __('Height:','profit-builder'),
                  'std' =>  '50px',
                  'min' => 0,
                  'max' => 500,
                  'half_column' => 'false',
                  'unit' => 'px',
                  'hide_if' => array(
                  'btype' => array('image','predone'),
                  )
                  ), */
                )
            ),
            'group_twostep' => array(
                'type' => 'collapsible',
                'label' => __('Two Step', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'enabletwostep' => array(
                        'type' => 'checkbox',
                        'label' => 'Enable',
                        'std' => 'false',
                        'half_column' => 'true',
                    ),
                    'leadin2step' => array(
                        'type' => 'textarea',
                        'label' => __('Lead In', 'profit-builder'),
                        'std' => 'Change this text to be a great call to action to click initially...',
                    ),
                    'buttontext' => array(
                        'type' => 'input',
                        'label' => __('Button Text:', 'profit-builder'),
                        'desc' => __('Button Text before Optin Shows....', 'profit-builder'),
                        'std' => 'Click to Learn More',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                )
            ),
            'group_advanced' => array(
                'type' => 'collapsible',
                'label' => __('Advanced', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'v_padding' => array(
                        'type' => 'number',
                        'label' => __('Vertical padding:', 'profit-builder'),
                        'std' => 10,
                        'unit' => 'px'
                    ),
                    'h_padding' => array(
                        'type' => 'number',
                        'label' => __('Horizontal padding:', 'profit-builder'),
                        'std' => 10,
                        'unit' => 'px'
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* BUTTON */
/* -------------------------------------------------------------------------------- */
$files = array();
$dir = opendir(IMSCPB_DIR . "/images/buttons");
while (false !== ($filename = readdir($dir))) {
    if ($filename != "." && $filename != ".." && substr_count($filename, "gold.png") > 0) {
        $files[str_replace('gold.png', "", $filename)] = '<img src="' . IMSCPB_URL . '/images/buttons/' . $filename . '" class="pre-done-preview" />';
    }
}
$styles = array();
for ($index = 1; $index <= 16; $index++)
//$styles['style'.$index] = __('CSS3 Button '.$index,'profit-builder');//<a class="pbcss3button'.$css3btnstyle.'" '.$style.' href="'.$url.'" '.($type=='new-tab'? ' target="_blank" ':"").'><span class="text" style="font-size:'.$font_size.';">'.$text.'</span></a>
    $styles['style' . $index] = '<button type="button" class="pbcss3buttonpreview pbcss3buttonstyle' . $index . '"><div class="text">Read More...</div></button>';
$button = array(
    'button' => array(
        'type' => 'draggable',
        'text' => __('Button', 'profit-builder'),
        'icon' => $this->url . 'images/icons/button.png',
        'function' => 'pbuilder_button',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Button', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'btype' => array(
                        'type' => 'select',
                        'label' => __('Button Type:', 'profit-builder'),
                        'std' => 'custom',
                        'options' => array(
                            'custom' => __('Custom Text', 'profit-builder'),
                            'css3' => __('CSS3', 'profit-builder'),
                            'predone' => __('Pre-Done', 'profit-builder'),
                            'image' => __('Image', 'profit-builder')
                        )
                    ),
                    'image' => array(
                        'type' => 'image',
                        'label' => __('Image:', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'btype' => array('custom', 'predone', 'css3'),
                        )
                    ),
										'url_target' => array(
                        'type' => 'select',
                        'label' => __('Target:', 'profit-builder'),
                        'std' => '_blank',
                        'options' => array(
                            '_blank' => __('Blank', 'profit-builder'),
                            '_new' => __('New', 'profit-builder'),
                            '_parent' => __('Parent', 'profit-builder'),
                            '_self' => __('Self', 'profit-builder'),
                            '_top' => __('Top', 'profit-builder')
                        )
                    ),
                    'url' => array(
                        'type' => 'input',
                        'label' => __('URL:', 'profit-builder'),
                        'desc' => __('ex. http://yoursite.com/', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.25,
                        'control_width' => 0.75
                    ),
					'urlnofollow' => array(
                        'type' => 'checkbox',
                        'label' => __('Add nofollow rel attribute', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'false'
                    ),
                )
            ),
            'group_css3' => array(
                'type' => 'collapsible',
                'label' => __('CSS3 Buttons', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'css3btnstyle' => array(
                        'type' => 'select',
                        'label' => __('Button Style:', 'profit-builder'),
                        'std' => 'style1',
                        'options' => $styles,
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'predone'),
                        )
                    ),
                /* 'css3btnwidth' => array(
                  'type' => 'number',
                  'label' => __('Width:','profit-builder'),
                  'std' =>  '230px',
                  'min' => 0,
                  'max' => 500,
                  'half_column' => 'false',
                  'unit' => 'px',
                  'hide_if' => array(
                  'btype' => array('image','custom', 'predone'),
                  'css3btnwidthfull' => array('true')
                  )
                  ),
                  'css3btnwidthfull' => array(
                  'type' => 'checkbox',
                  'label' => '100% Width',
                  'std' => 'true',
                  'half_column' => 'false',
                  'hide_if' => array(
                  'btype' => array('image','custom', 'predone'),
                  )
                  ),
                  'css3btnheight' => array(
                  'type' => 'number',
                  'label' => __('Height:','profit-builder'),
                  'std' =>  '50px',
                  'min' => 0,
                  'max' => 500,
                  'half_column' => 'false',
                  'unit' => 'px',
                  'hide_if' => array(
                  'btype' => array('image','custom', 'predone'),
                  )
                  ), */
                )
            ),
            'group_predone' => array(
                'type' => 'collapsible',
                'label' => __('Pre-Done Buttons', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'pname' => array(
                        'type' => 'select',
                        'label' => __('Predone:', 'profit-builder'),
                        'std' => 'addtocart',
                        'options' => $files,
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'css3'),
                        )
                    ),
                    'pcolor' => array(
                        'type' => 'select',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => 'gold',
                        'options' => array(
                            'gold' => __('Gold', 'profit-builder'),
                            'black' => __('Black', 'profit-builder'),
                            'blue' => __('Blue', 'profit-builder'),
                            'red' => __('Red', 'profit-builder'),
                            'white' => __('White', 'profit-builder'),
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'css3'),
                        )
                    ),
                    'panimated' => array(
                        'type' => 'checkbox',
                        'label' => __('Animated', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'css3'),
                        )
                    ),
                )
            ),
            'group_textbutton' => array(
                'type' => 'collapsible',
                'label' => __('Text Button', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'text' => array(
                        'type' => 'input',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => __('Get Instant Access', 'profit-builder'),
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 20,
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'letter_spacing' => array(
                        'type' => 'number',
                        'label' => __('Spacing:', 'profit-builder'),
                        'min' => -2,
                        'max' => 10,
                        'std' => -1,
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'font_weight' => array(
                        'type' => 'select',
                        'label' => __('Weight:', 'profit-builder'),
                        'std' => 'bold',
                        'options' => array(
                            'normal' => 'Normal',
                            'lighter' => 'Lighter',
                            'bold' => 'Bold',
                            'bolder' => 'Bolder',
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'text_align' => array(
                        'type' => 'select',
                        'label' => __('Text alignment:', 'profit-builder'),
                        'std' => 'center',
                        'options' => array(
                            'left' => __('Left', 'profit-builder'),
                            'center' => __('Centered', 'profit-builder'),
                            'right' => __('Right', 'profit-builder')
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => '#ffffff', //$opts['main_color'],
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'hover_text_color' => array(
                        'type' => 'color',
                        'label' => __('Hover:', 'profit-builder'),
                        'std' => '#ffffff', //$opts['light_main_color'],
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'icon' => array(
                        'type' => 'icon',
                        'label' => __('Icon Type:', 'profit-builder'),
                        'notNull' => false,
                        'std' => 'no-icon',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'icon_align' => array(
                        'type' => 'select',
                        'label' => __('Icon alignment:', 'profit-builder'),
                        'std' => 'left',
                        'options' => array(
                            'left' => __('Left', 'profit-builder'),
                            'right' => __('Right', 'profit-builder'),
                            'inline' => __('Inline', 'profit-builder')
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'icon_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 16,
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    )
                )
            ),
            'group_button_font_style' => array(
                'type' => 'collapsible',
                'label' => __('Font Style', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'google_font' => array(
                        'type' => 'select',
                        'class' => 'pbuilder_font_select',
                        'search' => 'true',
                        'std' => 'default',
                        'label' => __('Font family', 'profit-builder'),
                        'options' => $pbuilder_google_font_names
                    ),
                    'google_font_style' => array(
                        'name' => 'h1_font_style',
                        'type' => 'select',
                        'std' => 'default',
                        'label' => __('Font style', 'profit-builder'),
                        'options' => array('default' => 'Default'),
                        'hide_if' => array(
                            'google_font' => array('default')
                        )
                    ),
                )
            ),
            'group_button_size' => array(
                'type' => 'collapsible',
                'label' => __('Button Size', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'buttonwidth' => array(
                        'type' => 'number',
                        'label' => __('Width:', 'profit-builder'),
                        'std' => '230px',
                        'min' => 0,
                        'max' => 500,
                        'half_column' => 'false',
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                            'buttonwidthfull' => array('true')
                        )
                    ),
                    'buttonwidthfull' => array(
                        'type' => 'checkbox',
                        'label' => '100% Width',
                        'std' => 'true',
                        'half_column' => 'false',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                /* 'buttonheight' => array(
                  'type' => 'number',
                  'label' => __('Height:','profit-builder'),
                  'std' =>  '50px',
                  'min' => 0,
                  'max' => 500,
                  'half_column' => 'false',
                  'unit' => 'px',
                  'hide_if' => array(
                  'btype' => array('image', 'predone'),
                  )
                  ), */
                )
            ),
            'group_background' => array(
                'type' => 'collapsible',
                'label' => __('Background', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => '#ff6600', //$opts['main_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.50,
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'hover_back_color' => array(
                        'type' => 'color',
                        'label' => __('Hover:', 'profit-builder'),
                        'std' => '#ff9900', //$opts['light_main_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.50,
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    /* 'fullwidth' => array(
                      'type' => 'checkbox',
                      'label' => __('Full width','profit-builder'),
                      'std' => 'false',
                      'half_column' => 'true',
                      'hide_if' => array(
                      'btype' => array('image','predone', 'css3'),
                      )
                      ),
                      'round' => array(
                      'type' => 'checkbox',
                      'label' => 'Round',
                      'std' => 'true',
                      'half_column' => 'true',
                      'hide_if' => array(
                      'btype' => array('image','predone', 'css3'),
                      )
                      ), */
                    'fill' => array(
                        'type' => 'checkbox',
                        'label' => __('Fill', 'profit-builder'),
                        'std' => 'true',
                        'desc' => __('turn off to get a button with border', 'profit-builder'),
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'border_thickness' => array(
                        'type' => 'number',
                        'label' => __('Border thickness:', 'profit-builder'),
                        'std' => 1,
                        'min' => 0,
                        'max' => 20,
                        'unit' => 'px',
                        'hide_if' => array(
                            'fill' => array('true'),
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    )
                )
            ),
            /* 'group_link' => array(
              'type' => 'collapsible',
              'label' => __('Link','profit-builder'),
              'open' => 'true',
              'options' => array(
              'type' => array(
              'type' => 'select',
              'label' => __('Type','profit-builder'),
              'label_width' => 0.25,
              'control_width' => 0.75,
              'std' => 'standard',
              'options' => array(
              'standard' => __('Standard','profit-builder'),
              'new-tab' => __('Open in new tab','profit-builder'),
              'lightbox-image' => __('Lightbox image','profit-builder'),
              'lightbox-iframe' => __('Lightbox iframe','profit-builder')
              )
              ),
              'iframe_width' => array(
              'type' => 'number',
              'label' => __('Width:','profit-builder'),
              'std' => 600,
              'min' => 0,
              'max' => 1200,
              'unit' => 'px',
              'half_column' => 'true',
              'hide_if' => array(
              'type' => array('standard', 'new-tab', 'lightbx-image')
              )
              ),
              'iframe_height' => array(
              'type' => 'number',
              'label' => __('Height:','profit-builder'),
              'std' => 300,
              'min' => 0,
              'max' => 1200,
              'unit' => 'px',
              'half_column' => 'true',
              'hide_if' => array(
              'type' => array('standard', 'new-tab', 'lightbx-image')
              )
              )
              )
              ), */
            'group_creditcards' => array(
                'type' => 'collapsible',
                'label' => __('Credit Cards', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'showcards' => array(
                        'type' => 'checkbox',
                        'label' => __('Show Credit Cards Under Button', 'profit-builder'),
                        'std' => 'false',
                    ),
                    'amex' => array(
                        'type' => 'checkbox',
                        'label' => 'Amex',
                        'std' => 'true',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'showcards' => array('false')
                        )
                    ),
                    'visa' => array(
                        'type' => 'checkbox',
                        'label' => 'Visa',
                        'std' => 'true',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'showcards' => array('false')
                        )
                    ),
                    'mc' => array(
                        'type' => 'checkbox',
                        'label' => 'Mastercard',
                        'std' => 'true',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'showcards' => array('false')
                        )
                    ),
                    'pp' => array(
                        'type' => 'checkbox',
                        'label' => 'Paypal',
                        'std' => 'true',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'showcards' => array('false')
                        )
                    ),
                )
            ),
            'group_advanced' => array(
                'type' => 'collapsible',
                'label' => __('Advanced', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'v_padding' => array(
                        'type' => 'number',
                        'label' => __('Vertical padding:', 'profit-builder'),
                        'std' => 20,
                        'unit' => 'px'
                    ),
                    'h_padding' => array(
                        'type' => 'number',
                        'label' => __('Horizontal padding:', 'profit-builder'),
                        'std' => 20,
                        'unit' => 'px'
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* IMAGE */
/* -------------------------------------------------------------------------------- */
$image = array(
    'image' => array(
        'type' => 'draggable',
        'text' => __('Image', 'profit-builder'),
        'icon' => $this->url . 'images/icons/image.png',
        'function' => 'pbuilder_image',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_image' => array(
                'type' => 'collapsible',
                'label' => __('Image', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'content' => array(
                        'type' => 'image',
                        'std' => $this->url . 'images/image-default.jpg'
                    ),
                    'custom_dimensions' => array(
                        'type' => 'checkbox',
                        'label' => __('Custom Dimensions', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'image_width' => array(
                        'type' => 'number',
                        'label' => __('Width:', 'profit-builder'),
                        'std' => 600,
                        'min' => 0,
                        'max' => 1200,
                        'unit' => 'px',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'custom_dimensions' => array('false')
                        )
                    ),
                    'image_height' => array(
                        'type' => 'number',
                        'label' => __('Height:', 'profit-builder'),
                        'std' => 300,
                        'min' => 0,
                        'max' => 1200,
                        'unit' => 'px',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'custom_dimensions' => array('false')
                        )
                    ),
                    'text_align' => array(
                        'type' => 'select',
                        'label' => __('Image alignment:', 'profit-builder'),
                        'std' => 'center',
                        'options' => array(
                            'left' => __('Left', 'profit-builder'),
                            'center' => __('Center', 'profit-builder'),
                            'right' => __('Right', 'profit-builder')
                        )
                    ),
                )
            ),
            'group_image_border' => array(
                'type' => 'collapsible',
                'label' => __('Image Border', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'round' => array(
                        'type' => 'checkbox',
                        'label' => __('Round edges', 'profit-builder'),
                        'std' => 'false',
                    ),
                    'round_width' => array(
                        'type' => 'number',
                        'label' => __('Roundness', 'profit-builder'),
                        'std' => 0,
                        'max' => 100,
                        'unit' => 'px',
                        'hide_if' => array(
                            'round' => array('false')
                        )
                    ),
                    'border' => array(
                        'type' => 'checkbox',
                        'label' => __('Image border', 'profit-builder'),
                        'std' => 'false',
                    ),
                    'border_color' => array(
                        'type' => 'color',
                        'label' => __('Border color:', 'profit-builder'),
                        'std' => $opts['dark_border_color'],
                        'label_width' => 0.5,
                        'control_width' => 0.50,
                        'hide_if' => array(
                            'border' => array('false')
                        )
                    ),
                    'border_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Border hover color:', 'profit-builder'),
                        'std' => $opts['main_color'],
                        'label_width' => 0.5,
                        'control_width' => 0.50,
                        'hide_if' => array(
                            'border' => array('false'),
                            'link' => array('')
                        )
                    ),
                    'border_width' => array(
                        'type' => 'number',
                        'label' => __('Width', 'profit-builder'),
                        'std' => 0,
                        'max' => 100,
                        'unit' => 'px',
                        'hide_if' => array(
                            'border' => array('false')
                        )
                    ),
                    'border_style' => array(
                        'type' => 'select',
                        'label' => __('Border Style', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => 'solid',
                        'options' => array(
                            'none' => 'None',
                            'hidden' => 'Hidden',
                            'dotted' => 'Dotted',
                            'dashed' => 'Dashed',
                            'solid' => 'Solid',
                            'double' => 'Double',
                            'groove' => 'Groove',
                            'ridge' => 'Ridge',
                            'inset' => 'Inset',
                            'outset' => 'Outset',
                            'initial' => 'Initial',
                            'inherit' => 'Inherit',
                        ),
                        'hide_if' => array(
                            'border' => array('false')
                        )
                    ),
                )
            ),
            'group_image_shadow' => array(
                'type' => 'collapsible',
                'label' => __('Shadow', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'shadow' => array(
                        'type' => 'checkbox',
                        'label' => __('Image shadow', 'profit-builder'),
                        'std' => 'false',
                    ),
                    'shadow_color' => array(
                        'type' => 'color',
                        'label' => __('Color', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => $this->option('row_shadow_color')->value,
                        'hide_if' => array(
                            'shadow' => array('false')
                        )
                    ),
                    'shadow_h_shadow' => array(
                        'type' => 'number',
                        'label' => __('Horizontal Shadow', 'profit-builder'),
                        'std' => 0,
                        'max' => 100,
                        'unit' => 'px',
                        'hide_if' => array(
                            'shadow' => array('false')
                        )
                    ),
                    'shadow_v_shadow' => array(
                        'type' => 'number',
                        'label' => __('Vertical Shadow', 'profit-builder'),
                        'std' => 0,
                        'max' => 100,
                        'unit' => 'px',
                        'hide_if' => array(
                            'shadow' => array('false')
                        )
                    ),
                    'shadow_blur' => array(
                        'type' => 'number',
                        'label' => __('Blur', 'profit-builder'),
                        'std' => 0,
                        'max' => 100,
                        'unit' => 'px',
                        'hide_if' => array(
                            'shadow' => array('false')
                        )
                    ),
                )
            ),
            'group_image_link' => array(
                'type' => 'collapsible',
                'label' => __('Image Link', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'link' => array(
                        'type' => 'input',
                        'label' => __('URL:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'std' => ''
                    ),
                    'link_type' => array(
                        'type' => 'select',
                        'label' => __('Link type:', 'profit-builder'),
                        'std' => 'lightbox-image',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'standard' => __('Standard', 'profit-builder'),
                            'new-tab' => __('Open in new tab', 'profit-builder'),
                            'lightbox-image' => __('Lightbox image', 'profit-builder'),
                            'lightbox-iframe' => __('Lightbox iframe/video', 'profit-builder')
                        ),
                        'hide_if' => array(
                            'link' => array('')
                        )
                    ),
                    'iframe_width' => array(
                        'type' => 'number',
                        'label' => __('Width:', 'profit-builder'),
                        'std' => 600,
                        'min' => 0,
                        'max' => 1200,
                        'unit' => 'px',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'link' => array(''),
                            'link_type' => array('standard', 'new-tab', 'lightbox-image')
                        )
                    ),
                    'iframe_height' => array(
                        'type' => 'number',
                        'label' => __('Height:', 'profit-builder'),
                        'std' => 300,
                        'min' => 0,
                        'max' => 1200,
                        'unit' => 'px',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'link' => array(''),
                            'link_type' => array('standard', 'new-tab', 'lightbox-image')
                        )
                    )
                )
            ),
            'group_hover_icon' => array(
                'type' => 'collapsible',
                'label' => __('Hover Icon', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'hover_icon' => array(
                        'type' => 'icon',
                        'label' => __('Hover icon:', 'profit-builder'),
                        'std' => 'fa-search',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'link' => array('')
                        )
                    ),
                    'hover_icon_size' => array(
                        'type' => 'number',
                        'label' => __('Hover icon size:', 'profit-builder'),
                        'std' => '30',
                        'unit' => 'px',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'link' => array('')
                        )
                    )
                )
            ),
            'group_hover_shade' => array(
                'type' => 'collapsible',
                'label' => __('Hover Shade', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'hover_shade_color' => array(
                        'type' => 'color',
                        'label' => __('Hover Color:', 'profit-builder'),
                        'std' => '#000000',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'link' => array('')
                        )
                    ),
                    'hover_shade_opacity' => array(
                        'type' => 'select',
                        'label' => __('Hover Opacity:', 'profit-builder'),
                        'std' => '0.4',
                        'options' => array(
                            '0.0' => '0',
                            '0.1' => '0.1',
                            '0.2' => '0.2',
                            '0.3' => '0.3',
                            '0.4' => '0.4',
                            '0.5' => '0.5',
                            '0.6' => '0.6',
                            '0.7' => '0.7',
                            '0.8' => '0.8',
                            '0.9' => '0.9',
                            '1.0' => '1'
                        ),
                        'hide_if' => array(
                            'link' => array('')
                        )
                    )
                )
            ),
            'group_image_alert' => array(
                'type' => 'collapsible',
                'label' => __('Image Alert', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'alert' => array(
                        'type' => 'checkbox',
                        'label' => __('Click Alert', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true'
                    ),
                    'alerttext' => array(
                        'type' => 'input',
                        'label' => __('Text:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'std' => 'Hey this image was clicked...',
                        'hide_if' => array(
                            'alert' => array('false')
                        )
                    ),
                )
            ),
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* VIDEO */
/* -------------------------------------------------------------------------------- */
$video = array(
    'video' => array(
        'type' => 'draggable',
        'text' => __('Video', 'profit-builder'),
        'icon' => $this->url . 'images/icons/video.png',
        'function' => 'pbuilder_video',
        'group' => __('Content', 'profit-builder'),
        'options' => array_merge(
                array(
            'group_video' => array(
                'type' => 'collapsible',
                'label' => __('Video', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'vtype' => array(
                        'type' => 'select',
                        'label' => __('Type:', 'profit-builder'),
                        'std' => 'youtube',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'youtube' => __('Youtube / Vimeo', 'profit-builder'),
                            'direct' => __('MP4 / OGV /WEBM', 'profit-builder'),
                            'embed' => __('Embed Code', 'profit-builder'),
                        )
                    ),
                    'url' => array(
                        'type' => 'input',
                        'label' => __('URL', 'profit-builder'),
                        'std' => 'http://www.youtube.com/watch?v=it28C6rG2tY',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'vtype' => array('direct', 'embed'),
                        )
                    ),
                    'webm' => array(
                        'type' => 'input',
                        'label' => __('WEBM', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'vtype' => array('youtube', 'embed'),
                        )
                    ),
                    'mp4' => array(
                        'type' => 'input',
                        'label' => __('MP4', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'vtype' => array('youtube', 'embed'),
                        )
                    ),
                    'ogv' => array(
                        'type' => 'input',
                        'label' => __('OGV', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'vtype' => array('youtube', 'embed'),
                        )
                    ),
                    'poster' => array(
                        'label' => __('Splash', 'profit-builder'),
                        'type' => 'image',
                        'std' => '',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'vtype' => array('youtube', 'embed'),
                            'autoplay' => array('true'),
                        )
                    ),
                    'content' => array(
                        'type' => 'textarea',
                        'label' => __('Embed Code', 'profit-builder'),
                        'std' => '',
                        'hide_if' => array(
                            'vtype' => array('direct', 'youtube'),
                        )
                    ),
                )
            ),
            'group_size' => array(
                'type' => 'collapsible',
                'label' => __('Size and Controls', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'presetsize' => array(
                        'type' => 'select',
                        'label' => __('Preset Size:', 'profit-builder'),
                        'std' => 'fluid',
                        'desc' => __('Recommended preset size is fluid this would be show layout correct.', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'fluid' => __('Fluid', 'profit-builder'),
                            'custom' => __('Custom', 'profit-builder'),
                            '1' => __('960 x 540', 'profit-builder'),
                            '2' => __('850 x 478', 'profit-builder'),
                            '3' => __('640 x 360', 'profit-builder'),
                            '4' => __('550 x 309', 'profit-builder'),
                            '5' => __('500 x 281', 'profit-builder'),
                        )
                    ),
                    'width' => array(
                        'type' => 'number',
                        'label' => __('Width', 'profit-builder'),
                        'half_column' => 'true',
                        'min' => 100,
                        'max' => 1200,
                        'std' => 640,
                        'unit' => 'px',
                        'hide_if' => array(
                            'auto_width' => array('true'),
                            'presetsize' => array('1', '2', '3', '4', '5'),
                        )
                    ),
                    'height' => array(
                        'type' => 'number',
                        'label' => __('Height', 'profit-builder'),
                        'half_column' => 'true',
                        'min' => 100,
                        'max' => 1200,
                        'std' => 360,
                        'unit' => 'px',
                        'hide_if' => array(
                            'presetsize' => array('1', '2', '3', '4', '5'),
                        )
                    ),
                    'autoplay' => array(
                        'type' => 'checkbox',
                        'label' => __('Auto Play', 'profit-builder'),
                        'desc' => __('Autoplay the video (if using YouTube or Vimeo)', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true'
                    ),
                    'minimalbranding' => array(
                        'type' => 'checkbox',
                        'label' => __('Min Branding', 'profit-builder'),
                        'desc' => __('Remove overt branding, related videos, etc for the video (if using YouTube or Vimeo)', 'profit-builder'),
                        'std' => 'true',
                        'half_column' => 'true'
                    ),
                    'hidecontrols' => array(
                        'type' => 'checkbox',
                        'label' => __('No Controls', 'profit-builder'),
                        'desc' => __('Removes controls if it is a direct video', 'profit-builder'),
                        'std' => 'true',
                        'half_column' => 'true'
                    ),
                    'forcehd' => array(
                        'type' => 'checkbox',
                        'label' => __('Force HD', 'profit-builder'),
                        'desc' => __('Force HD Playback with YouTube', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'vtype' => array('direct', 'embed', 'amazon'),
                        )
                    ),
                    'preload' => array(
                        'type' => 'checkbox',
                        'label' => __('Preload', 'profit-builder'),
                        'desc' => __('Start video buffering in advance (Only in Direct Mode)', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'vtype' => array('youtube', 'embed'),
                        )
                    ),
                    'fullscreen' => array(
                        'type' => 'checkbox',
                        'label' => __('Allow Fullscreen', 'profit-builder'),
                        'desc' => __('Allow video fullscreen (Only in Direct Mode)', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'vtype' => array('youtube', 'embed'),
                        )
                    ),
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* AUDIO */
/* -------------------------------------------------------------------------------- */
$audio = array(
    'audio' => array(
        'type' => 'draggable',
        'text' => __('Audio', 'profit-builder'),
        'icon' => $this->url . 'images/icons/audio.png',
        'function' => 'pbuilder_audio',
        'group' => __('Content', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_heading' => array(
                'type' => 'collapsible',
                'label' => __('Audio', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'content_mp3' => array(
                        'label' => __('Source - .mp3', 'profit-builder'),
                        'desc' => __('URL to the audio file in .mp3 format', 'profit-builder'),
                        'type' => 'input',
                        'std' => 'http://media.w3.org/2010/07/bunny/04-Death_Becomes_Fur.mp4',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'content_ogg' => array(
                        'label' => __('Source - .ogg', 'profit-builder'),
                        'desc' => __('URL to the audio file in .ogg format', 'profit-builder'),
                        'type' => 'input',
                        'std' => 'http://media.w3.org/2010/07/bunny/04-Death_Becomes_Fur.oga',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'background_color' => array(
                        'type' => 'color',
                        'label' => __('Background color:', 'profit-builder'),
                        'std' => '#464646',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'bar_color' => array(
                        'type' => 'color',
                        'label' => __('Progress bar color:', 'profit-builder'),
                        'std' => '#21CDEC',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'icon_type' => array(
                        'type' => 'select',
                        'label' => __('Icon style:', 'profit-builder'),
                        'std' => 'default',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'default' => __('Default', 'profit-builder'),
                            'light' => __('Light', 'profit-builder'),
                            'dark' => __('Dark', 'profit-builder'),
                            'light_transparent' => __('Light Transparent', 'profit-builder'),
                            'dark_transparent' => __('Dark Transparent', 'profit-builder')
                        )
                    ),
                    'start_at' => array(
                        'type' => 'number',
                        'label' => __('Starting time', 'profit-builder'),
                        'desc' => __('Starting time of the audio file in seconds', 'profit-builder'),
                        'std' => 0,
                        'min' => 0,
                        'max' => 5000,
                        'unit' => ' sec'
                    ),
                    'autoplay' => array(
                        'type' => 'checkbox',
                        'label' => __('Autoplay', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true'
                    ),
                    'loop' => array(
                        'type' => 'checkbox',
                        'label' => __('Loop', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true'
                    ),
                    'mute' => array(
                        'type' => 'checkbox',
                        'label' => __('Mute', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true'
                    ),
                    'hide_controls' => array(
                        'type' => 'checkbox',
                        'label' => __('Hide controls', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true'
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* FEATURED POST */
/* -------------------------------------------------------------------------------- */
$post = array(
    'post' => array(
        'type' => 'draggable',
        'text' => __('Featured post', 'profit-builder'),
        'icon' => $this->url . 'images/icons/featured-post.png',
        'function' => 'pbuilder_post',
        'group' => __('Content', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'id' => array(
                        'type' => 'select',
                        'label' => __('Post id:', 'profit-builder'),
                        'std' => $first_post,
                        'desc' => __('You must have at leest one wordpress post', 'profit-builder'),
                        'options' => $pbuilder_wp_posts
                    ),
                    'style' => array(
                        'type' => 'select',
                        'label' => __('Style:', 'profit-builder'),
                        'std' => 'clean',
                        'options' => array(
                            'clean' => __('Clean', 'profit-builder'),
                            'squared' => __('Squared', 'profit-builder'),
                            'rounded' => __('Rounded', 'profit-builder')
                        )
                    ),
                    'link_type' => array(
                        'type' => 'select',
                        'label' => __('Img Link Type:', 'profit-builder'),
                        'std' => 'post',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'post' => __('Post', 'profit-builder'),
                            'prettyphoto' => __('Lightbox', 'profit-builder')
                        )
                    ),
                    'excerpt_lenght' => array(
                        'type' => 'number',
                        'label' => __('Excerpt Length', 'profit-builder'),
                        'std' => 150,
                        'max' => 300,
                        'unit' => ''
                    ),
                    'hover_icon' => array(
                        'type' => 'icon',
                        'label' => __('Image hover icon:', 'profit-builder'),
                        'std' => 'fa-plus'
                    )
                )
            ),
            'group_button' => array(
                'type' => 'collapsible',
                'label' => __('Button', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'button_text' => array(
                        'type' => 'input',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => 'Read more'
                    ),
                    'button_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['main_color']
                    ),
                    'button_text_color' => array(
                        'type' => 'color',
                        'label' => __('Text color:', 'profit-builder'),
                        'std' => $opts['main_back_text_color']
                    ),
                    'button_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Hover color:', 'profit-builder'),
                        'std' => $opts['light_main_color']
                    ),
                    'button_text_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Text hover color:', 'profit-builder'),
                        'std' => $opts['main_back_text_color']
                    )
                )
            ),
            'group_colors' => array(
                'type' => 'collapsible',
                'label' => __('Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'head_color' => array(
                        'type' => 'color',
                        'label' => __('Heading color:', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'meta_color' => array(
                        'type' => 'color',
                        'label' => __('Meta links color:', 'profit-builder'),
                        'desc' => __('color of the meta links - Date, Author, Comments', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'meta_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Meta hover color:', 'profit-builder'),
                        'std' => $opts['main_color']
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Text color:', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Background color:', 'profit-builder'),
                        'std' => $opts['light_back_color'],
                        'hide_if' => array(
                            'style' => array('clean')
                        )
                    ),
                    'border_color' => array(
                        'type' => 'color',
                        'label' => __('Border color:', 'profit-builder'),
                        'std' => $opts['main_color']
                    )
                )
            ),
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* RECENT POST */
/* -------------------------------------------------------------------------------- */
$recent_post = array(
    'recent_post' => array(
        'type' => 'draggable',
        'text' => __('Recent post', 'profit-builder'),
        'icon' => $this->url . 'images/icons/recent-post.png',
        'function' => 'pbuilder_recent_post',
        'group' => __('Content', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'offset' => array(
                        'type' => 'number',
                        'label' => __('Offset:', 'profit-builder'),
                        'std' => 0,
                        'desc' => __('Number of post to displace or pass over', 'profit-builder'),
                        'min' => 0,
                        'max' => 100
                    ),
                    'style' => array(
                        'type' => 'select',
                        'label' => __('Style:', 'profit-builder'),
                        'std' => 'clean',
                        'options' => array(
                            'clean' => __('Clean', 'profit-builder'),
                            'squared' => __('Squared', 'profit-builder'),
                            'rounded' => __('Rounded', 'profit-builder')
                        )
                    ),
                    'link_type' => array(
                        'type' => 'select',
                        'label' => __('Img Link Type:', 'profit-builder'),
                        'std' => 'post',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'post' => __('Post', 'profit-builder'),
                            'prettyphoto' => __('Lightbox', 'profit-builder')
                        )
                    ),
                    'excerpt_lenght' => array(
                        'type' => 'number',
                        'label' => __('Excerpt Length', 'profit-builder'),
                        'std' => 150,
                        'max' => 300,
                        'unit' => ''
                    ),
                    'hover_icon' => array(
                        'type' => 'icon',
                        'label' => __('Image hover icon:', 'profit-builder'),
                        'std' => 'fa-plus'
                    )
                )
            ),
            'group_button' => array(
                'type' => 'collapsible',
                'label' => __('Button', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'button_text' => array(
                        'type' => 'input',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => 'Read more'
                    ),
                    'button_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['main_color']
                    ),
                    'button_text_color' => array(
                        'type' => 'color',
                        'label' => __('Text color:', 'profit-builder'),
                        'std' => $opts['main_back_text_color']
                    ),
                    'button_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Hover color:', 'profit-builder'),
                        'std' => $opts['light_main_color']
                    ),
                    'button_text_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Text hover color:', 'profit-builder'),
                        'std' => $opts['main_back_text_color']
                    )
                )
            ),
            'group_colors' => array(
                'type' => 'collapsible',
                'label' => __('Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'head_color' => array(
                        'type' => 'color',
                        'label' => __('Heading color:', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'meta_color' => array(
                        'type' => 'color',
                        'label' => __('Meta links color:', 'profit-builder'),
                        'desc' => __('color of the meta links - Date, Author, Comments', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'meta_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Meta hover color:', 'profit-builder'),
                        'std' => $opts['main_color']
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Text color:', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Background color:', 'profit-builder'),
                        'std' => $opts['light_back_color'],
                        'hide_if' => array(
                            'style' => array('clean')
                        )
                    ),
                    'border_color' => array(
                        'type' => 'color',
                        'label' => __('Border color:', 'profit-builder'),
                        'std' => $opts['main_color']
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* GALLERY */
/* -------------------------------------------------------------------------------- */
$gallery = array(
    'gallery' => array(
        'type' => 'draggable',
        'text' => __('Gallery', 'profit-builder'),
        'icon' => $this->url . 'images/icons/gallery-shortcode.png',
        'function' => 'pbuilder_gallery',
        'group' => __('Content', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_chart' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'column_number' => array(
                        'type' => 'number',
                        'label' => __('No. of columns:', 'profit-builder'),
                        'min' => 1,
                        'max' => 5,
                        'std' => 3
                    ),
                    'item_padding' => array(
                        'type' => 'number',
                        'label' => __('Item padding:', 'profit-builder'),
                        'min' => 0,
                        'max' => 100,
                        'std' => 10,
                        'unit' => 'px'
                    ),
                    'image_size' => array(
                        'type' => 'select',
                        'label' => __('Image Size:', 'profit-builder'),
                        'std' => 'full',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'full' => 'Full',
                            'large' => 'Large',
                            'creative_post_slider_medium' => 'Medium',
                            'medium' => 'Small',
                            'thumbnail' => 'Thumbnail'
                        )
                    ),
                    'aspect_ratio' => array(
                        'type' => 'select',
                        'label' => __('Aspect Ratio:', 'profit-builder'),
                        'std' => '16:9',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            '1:1' => '1:1',
                            '4:3' => '4:3',
                            '16:9' => '16:9',
                            '16:10' => '16:10',
                            '1:2' => '1:2'
                        )
                    ),
                    'media_files' => array(
                        'type' => 'media_select',
                        'label' => __('Select media files:', 'profit-builder'),
                        'label_width' => 1,
                        'control_width' => 1,
                        'hide_if' => array(
                            'enable_categories' => array('true')
                        )
                    ),
                    'on_image_click' => array(
                        'type' => 'select',
                        'label' => __('Action on click:', 'profit-builder'),
                        'std' => 'none',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'none' => 'None',
                            'pretty_photo' => 'prettyPhoto',
                            'new_tab' => 'Open in new tab'
                        )
                    )
                )
            ),
            'group_hover_element' => array(
                'type' => 'collapsible',
                'label' => __('Hover Element', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'hover_content' => array(
                        'type' => 'select',
                        'label' => __('Overlay content:', 'profit-builder'),
                        'std' => 'icon',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'icon' => 'Icon',
                            'title' => 'Title'
                        ),
                        'hide_if' => array(
                            'on_image_click' => array('none')
                        )
                    ),
                    'hover_icon' => array(
                        'type' => 'icon',
                        'label' => __('Hover icon:', 'profit-builder'),
                        'std' => 'fa-search',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'on_image_click' => array('none'),
                            'hover_content' => array('title')
                        )
                    ),
                    'hover_icon_size' => array(
                        'type' => 'number',
                        'label' => __('Hover icon size:', 'profit-builder'),
                        'std' => '30',
                        'unit' => 'px',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'on_image_click' => array('none'),
                            'hover_content' => array('title')
                        )
                    ),
                    'hover_title_color' => array(
                        'type' => 'color',
                        'label' => __('Title Color:', 'profit-builder'),
                        'std' => '#ffffff',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'on_image_click' => array('none'),
                            'hover_content' => array('icon')
                        )
                    )
                )
            ),
            'group_hover_shade' => array(
                'type' => 'collapsible',
                'label' => __('Hover Shade', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'initial_shade_color' => array(
                        'type' => 'color',
                        'label' => __('Initial Color:', 'profit-builder'),
                        'std' => '#000000',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'initial_shade_opacity' => array(
                        'type' => 'select',
                        'label' => __('Initial Opacity:', 'profit-builder'),
                        'std' => '0.0',
                        'options' => array(
                            '0.0' => '0',
                            '0.1' => '0.1',
                            '0.2' => '0.2',
                            '0.3' => '0.3',
                            '0.4' => '0.4',
                            '0.5' => '0.5',
                            '0.6' => '0.6',
                            '0.7' => '0.7',
                            '0.8' => '0.8',
                            '0.9' => '0.9',
                            '1.0' => '1'
                        )
                    ),
                    'hover_shade_color' => array(
                        'type' => 'color',
                        'label' => __('Hover Color:', 'profit-builder'),
                        'std' => '#000000',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'hover_shade_opacity' => array(
                        'type' => 'select',
                        'label' => __('Hover Opacity:', 'profit-builder'),
                        'std' => '0.3',
                        'options' => array(
                            '0.0' => '0',
                            '0.1' => '0.1',
                            '0.2' => '0.2',
                            '0.3' => '0.3',
                            '0.4' => '0.4',
                            '0.5' => '0.5',
                            '0.6' => '0.6',
                            '0.7' => '0.7',
                            '0.8' => '0.8',
                            '0.9' => '0.9',
                            '1.0' => '1'
                        )
                    )
                )
            ),
            'group_categories' => array(
                'type' => 'collapsible',
                'label' => __('Categories', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'enable_categories' => array(
                        'type' => 'checkbox',
                        'half_column' => 'true',
                        'std' => 'false',
                        'label' => __('Categories', 'profit-builder')
                    ),
                    'show_all_category' => array(
                        'type' => 'checkbox',
                        'std' => 'false',
                        'half_column' => 'true',
                        'label' => __('"All" category', 'profit-builder'),
                        'hide_if' => array(
                            'enable_categories' => array('false')
                        )
                    ),
                    'sortable' => array(
                        'type' => 'sortable',
                        'label_width' => 0,
                        'control_width' => 1,
                        'desc' => __('Elements are sortable', 'profit-builder'),
                        'item_name' => __('Category', 'profit-builder'),
                        'std' => array(
                            'items' => array(
                                0 => array(
                                    'category_name' => 'Images',
                                    'active' => 'true',
                                    'category_media_files' => ''
                                )
                            ),
                            'order' => array(
                                0 => 0
                            )
                        ),
                        'options' => array(
                            'category_name' => array(
                                'type' => 'input',
                                'std' => 'Images',
                                'label' => __('Category name', 'profit-builder'),
                                'hide_if' => array(
                                    'enable_categories' => array('false')
                                )
                            ),
                            'active' => array(
                                'type' => 'checkbox',
                                'std' => 'false',
                                'label' => __('Set this category as default', 'profit-builder'),
                                'hide_if' => array(
                                    'enable_categories' => array('false')
                                )
                            ),
                            'category_media_files' => array(
                                'type' => 'media_select',
                                'label' => __('Select media files:', 'profit-builder'),
                                'label_width' => 1,
                                'control_width' => 1,
                                'hide_if' => array(
                                    'enable_categories' => array('false')
                                )
                            )
                        )
                    )
                )
            ),
            'style_gallery' => array(
                'type' => 'collapsible',
                'label' => __('Category styling', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'bckg_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => 'transparent',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'enable_categories' => array('false')
                        )
                    ),
                    'bckg_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Background hover:', 'profit-builder'),
                        'std' => 'transparent',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'enable_categories' => array('false')
                        )
                    ),
                    /* 'bckg_active_color' => array(
                      'type' => 'color',
                      'label' => __('Background active:','profit-builder'),
                      'std' => 'transparent',
                      'label_width' => 0.5,
                      'control_width' => 0.5,
                      'hide_if' => array(
                      'enable_categories' => array('false')
                      )
                      ), */
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => '#232323',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'enable_categories' => array('false')
                        )
                    ),
                    'text_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Text hover:', 'profit-builder'),
                        'std' => '#232323',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'enable_categories' => array('false')
                        )
                    ),
                    /* 	'text_active_color' => array(
                      'type' => 'color',
                      'label' => __('Text active:','profit-builder'),
                      'std' => '#232323',
                      'label_width' => 0.5,
                      'control_width' => 0.5,
                      'hide_if' => array(
                      'enable_categories' => array('false')
                      )
                      ), */
                    'border_color' => array(
                        'type' => 'color',
                        'label' => __('Border:', 'profit-builder'),
                        'std' => 'transparent',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'enable_categories' => array('false')
                        )
                    ),
                    'border_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Border hover:', 'profit-builder'),
                        'std' => 'transparent',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'enable_categories' => array('false')
                        )
                    ),
                    /* 'border_active_color' => array(
                      'type' => 'color',
                      'label' => __('Border active:','profit-builder'),
                      'std' => 'transparent',
                      'label_width' => 0.5,
                      'control_width' => 0.5,
                      'hide_if' => array(
                      'enable_categories' => array('false')
                      )
                      ), */
                    'border_thickness' => array(
                        'type' => 'number',
                        'label' => __('Border width:', 'profit-builder'),
                        'min' => 0,
                        'max' => 100,
                        'std' => 0,
                        'unit' => 'px',
                        'hide_if' => array(
                            'enable_categories' => array('false')
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* SLIDER */
/* -------------------------------------------------------------------------------- */
$slider = array(
    'slider' => array(
        'type' => 'draggable',
        'text' => __('Slider', 'profit-builder'),
        'icon' => $this->url . 'images/icons/slider.png',
        'function' => 'pbuilder_slider',
        'group' => __('Content', 'profit-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'mode' => array(
                        'type' => 'select',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'label' => __('Mode:', 'profit-builder'),
                        'std' => 'horizontal',
                        'options' => array(
                            'horizontal' => __('Hortizontal', 'profit-builder'),
                            'vertical' => __('Vertical', 'profit-builder'),
                        )
                    ),
                    'slides_per_view' => array(
                        'type' => 'number',
                        'label_width' => 0.75,
                        'control_width' => 0.25,
                        'min' => 1,
                        'label' => __('Slides per view:', 'profit-builder'),
                        'max' => 10,
                        'std' => 1,
                        'unit' => ''
                    ),
                    'auto_delay' => array(
                        'type' => 'number',
                        'std' => 5,
                        'label_width' => 0.75,
                        'control_width' => 0.25,
                        'label' => __('Transition delay time:', 'profit-builder'),
                        'unit' => 's',
                        'hide_if' => array(
                            'auto_play' => 'false'
                        )
                    ),
                    'auto_play' => array(
                        'type' => 'checkbox',
                        'std' => 'true',
                        'label' => __('Auto play', 'profit-builder')
                    )
                )
            ),
            'group_navigation' => array(
                'type' => 'collapsible',
                'label' => __('Navigation', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'navigation' => array(
                        'type' => 'select',
                        'std' => 'none',
                        'label' => __('Arrows:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'none' => __('None', 'profit-builder'),
                            'squared' => __('Squared', 'profit-builder'),
                            'round' => __('Round', 'profit-builder')
                        )
                    ),
                    'navigation_color' => array(
                        'type' => 'color',
                        'std' => '#ffffff',
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'label' => __('Color:', 'profit-builder'),
                        'hide_if' => array(
                            'navigation' => array('none')
                        )
                    ),
                    'pagination' => array(
                        'type' => 'checkbox',
                        'std' => 'true',
                        'label' => __('Pagination', 'profit-builder')
                    ),
                )
            ),
            'group_slides' => array(
                'type' => 'collapsible',
                'label' => __('Slides', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'sortable' => array(
                        'type' => 'sortable',
                        'label_width' => 0,
                        'control_width' => 1,
                        'desc' => __('Elements are sortable', 'profit-builder'),
                        'item_name' => __('slide', 'profit-builder'),
                        'std' => array(
                            'items' => array(
                                0 => array(
                                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                    'image' => $this->url . 'images/image-default.jpg',
                                    'ctype' => 'image',
                                    'back_color' => '#000000',
                                    'text_color' => '#ffffff',
                                    'image_link' => '',
                                    'image_link_type' => 'standard'
                                ),
                                1 => array(
                                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                    'image' => '',
                                    'ctype' => 'html',
                                    'back_color' => '#34495e',
                                    'text_color' => '#ffffff',
                                    'image_link' => '',
                                    'image_link_type' => 'standard'
                                ),
                                2 => array(
                                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                    'image' => $this->url . 'images/image-default.jpg',
                                    'ctype' => 'image',
                                    'back_color' => '#000000',
                                    'text_color' => '#ffffff',
                                    'image_link' => '',
                                    'image_link_type' => 'standard'
                                )
                            ),
                            'order' => array(
                                0 => 0,
                                1 => 1,
                                2 => 2
                            )
                        ),
                        'options' => array(
                            'ctype' => array(
                                'type' => 'select',
                                'label' => __('Content Type:', 'profit-builder'),
                                'std' => 'image',
                                'options' => array(
                                    'image' => __('Image', 'profit-builder'),
                                    'html' => __('Text / Html', 'profit-builder')
                                )
                            ),
                            'image' => array(
                                'type' => 'image',
                                'desc' => __('Add an image to tab content', 'profit-builder'),
                                'hide_if' => array(
                                    'sortable' => array(
                                        'ctype' => array('html')
                                    )
                                )
                            ),
                            'image_link' => array(
                                'type' => 'input',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Link:', 'profit-builder'),
                                'hide_if' => array(
                                    'sortable' => array(
                                        'ctype' => array('html')
                                    )
                                )
                            ),
                            'image_link_type' => array(
                                'type' => 'select',
                                'label_width' => 0.5,
                                'control_width' => 0.5,
                                'label' => __('Link Type:', 'profit-builder'),
                                'std' => 'standard',
                                'desc' => __('open in new tab or lightbox', 'profit-builder'),
                                'options' => array(
                                    'standard' => __('Standard', 'profit-builder'),
                                    'new-tab' => __('Open in new tab', 'profit-builder'),
                                    'lightbox-image' => __('Lightbox image', 'profit-builder'),
                                    'lightbox-iframe' => __('Lightbox iframe', 'profit-builder')
                                ),
                                'hide_if' => array(
                                    'sortable' => array(
                                        'ctype' => array('html')
                                    )
                                )
                            ),
                            'iframe_width' => array(
                                'type' => 'number',
                                'label' => __('Width:', 'profit-builder'),
                                'std' => 600,
                                'min' => 0,
                                'half_column' => 'true',
                                'max' => 1200,
                                'unit' => 'px',
                                'hide_if' => array(
                                    'sortable' => array(
                                        'ctype' => array('html'),
                                        'image_link_type' => array('standard', 'new-tab', 'lightbox-image')
                                    )
                                )
                            ),
                            'iframe_height' => array(
                                'type' => 'number',
                                'label' => __('Height:', 'profit-builder'),
                                'std' => 300,
                                'min' => 0,
                                'half_column' => 'true',
                                'max' => 1200,
                                'unit' => 'px',
                                'hide_if' => array(
                                    'sortable' => array(
                                        'ctype' => array('html'),
                                        'image_link_type' => array('standard', 'new-tab', 'lightbox-image')
                                    )
                                )
                            ),
                            'content' => array(
                                'type' => 'textarea',
                                'hide_if' => array(
                                    'sortable' => array(
                                        'ctype' => array('image')
                                    )
                                )
                            ),
                            'text_align' => array(
                                'type' => 'select',
                                'label' => __('Text alignment:', 'profit-builder'),
                                'std' => 'left',
                                'options' => array(
                                    'left' => __('Left', 'profit-builder'),
                                    'center' => __('Center', 'profit-builder'),
                                    'right' => __('Right', 'profit-builder')
                                ),
                                'hide_if' => array(
                                    'sortable' => array(
                                        'ctype' => array('image')
                                    )
                                )
                            ),
                            'text_color' => array(
                                'type' => 'color',
                                'label_width' => 0.5,
                                'control_width' => 0.5,
                                'label' => __('Text:', 'profit-builder'),
                                'std' => $opts['text_color'],
                                'hide_if' => array(
                                    'sortable' => array(
                                        'ctype' => array('image')
                                    )
                                )
                            ),
                            'back_color' => array(
                                'type' => 'color',
                                'label_width' => 0.5,
                                'control_width' => 0.5,
                                'label' => __('Background:', 'profit-builder'),
                                'std' => $opts['light_back_color'],
                                'hide_if' => array(
                                    'sortable' => array(
                                        'ctype' => array('image')
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            'group_responsive' => array(
                'type' => 'collapsible',
                'label' => __('Responsive', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'responsive_layout' => array(
                        'type' => 'checkbox',
                        'std' => 'false',
                        'label' => __('Responsive Layout', 'profit-builder'),
                        'hide_if' => array(
                            'mode' => 'vertical'
                        )
                    ),
                    'min_slide_width' => array(
                        'type' => 'number',
                        'label' => __('Min. Slide Width:', 'profit-builder'),
                        'std' => 200,
                        'min' => 0,
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'max' => 1200,
                        'unit' => 'px',
                        'hide_if' => array(
                            'responsive_layout' => array('false')
                        )
                    ),
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* TIMERS */
/* -------------------------------------------------------------------------------- */
$dtNow = new DateTime('now', new DateTimeZone('UTC'));
$dtNow->modify("+3 days");
$timer = array(
    'timer' => array(
        'type' => 'draggable',
        'text' => __('Countdown Timers', 'profit-builder'),
        'icon' => $this->url . 'images/icons/timer.png',
        'function' => 'pbuilder_timer',
        'group' => __('Advanced', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Countdown Timer', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'timer_type' => array(
                        'type' => 'select',
                        'label' => __('Type:', 'profit-builder'),
                        'std' => 'flipclock',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'flipclock' => 'Flip Clock',
                            'lightfluid' => 'Fluid Clock',
                        //'darkfluid' => 'Dark Fluid',
                        )
                    ),
                    'enddate' => array(
                        'type' => 'input',
                        'label' => __('End Date:', 'profit-builder'),
                        'label_width' => 0.35,
                        'control_width' => 0.60,
                        'std' => $dtNow->format("Y/m/d H:i:s O"), //date("Y/m/d H:i:s O",strtotime("+3 days")),
                        'class' => 'pbuilder_datetime',
                    ),
                    'showlabels' => array(
                        'type' => 'checkbox',
                        'label' => __('Show Labels', 'profit-builder'),
                        'std' => 'true',
                        'half_column' => 'true',
                    ),
                    'align' => array(
                        'type' => 'select',
                        'label' => __('Timer alignment:', 'profit-builder'),
                        'options' => array(
                            'left' => 'Left',
                            'right' => 'Right',
                            'center' => 'Center'
                        ),
                        'std' => 'center'
                    ),
                    'label_color' => array(
                        'type' => 'color',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'label' => __('Label Color:', 'profit-builder'),
                        'std' => '#777777',
                    ),
                    'timer_style' => array(
                        'type' => 'select',
                        'label' => __('Timer Style:', 'profit-builder'),
                        'std' => 'Fixed',
                        'options' => array(
                            'Fixed' => "Fixed",
                            'EverGreen' => "EverGreen",
                        ),
                    ),
                    'timout_url' => array(
                        'type' => 'input',
                        'label' => __('Timeout Url:', 'profit-builder'),
                        'std' => '',
                    ),
                ),
            ),
            'group_labels' => array(
                'type' => 'collapsible',
                'label' => __('Labels', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'days_label' => array(
                        'type' => 'input',
                        'label' => __('Days Label:', 'profit-builder'),
                        'std' => 'Days',
                    ),
                    'hours_label' => array(
                        'type' => 'input',
                        'label' => __('Hours Label:', 'profit-builder'),
                        'std' => 'Hours',
                    ),
                    'minutes_label' => array(
                        'type' => 'input',
                        'label' => __('Minutes Label:', 'profit-builder'),
                        'std' => 'Minutes',
                    ),
                    'seconds_label' => array(
                        'type' => 'input',
                        'label' => __('Seconds Label:', 'profit-builder'),
                        'std' => 'Seconds',
                    ),
                ),
            ),
            'group_fluid' => array(
                'type' => 'collapsible',
                'label' => __('Fluid Clock', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'fluid_height' => array(
                        'type' => 'number',
                        'label' => __('Digit Height:', 'profit-builder'),
                        'std' => '166px',
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 500,
                        'hide_if' => array(
                            'timer_type' => array('flipclock')
                        ),
                    ),
                    'fluid_width' => array(
                        'type' => 'number',
                        'label' => __('Digit Width:', 'profit-builder'),
                        'std' => '150px',
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 500,
                        'hide_if' => array(
                            'timer_type' => array('flipclock')
                        ),
                    ),
                    'fluid_bg' => array(
                        'type' => 'color',
                        'label' => __('Digit Background:', 'profit-builder'),
                        'std' => '#ffffff',
                        'hide_if' => array(
                            'timer_type' => array('flipclock')
                        ),
                    ),
                    'fluid_font_size' => array(
                        'type' => 'number',
                        'label' => __('Digit Size:', 'profit-builder'),
                        'std' => '140px',
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 500,
                        'hide_if' => array(
                            'timer_type' => array('flipclock')
                        ),
                    ),
                    'fluid_font_color' => array(
                        'type' => 'color',
                        'label' => __('Digit Color:', 'profit-builder'),
                        'std' => '#444444',
                        'hide_if' => array(
                            'timer_type' => array('flipclock')
                        ),
                    ),
                    'fluid_font_margin' => array(
                        'type' => 'number',
                        'label' => __('Digit Margin:', 'profit-builder'),
                        'std' => '20px',
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 100,
                        'hide_if' => array(
                            'timer_type' => array('flipclock')
                        ),
                    ),
                    'fluid_label_size' => array(
                        'type' => 'number',
                        'label' => __('Label Size:', 'profit-builder'),
                        'std' => '30px',
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 500,
                        'hide_if' => array(
                            'timer_type' => array('flipclock')
                        ),
                    ),
                    'fluid_label_color' => array(
                        'type' => 'color',
                        'label' => __('Label Color:', 'profit-builder'),
                        'std' => '#444444',
                        'hide_if' => array(
                            'timer_type' => array('flipclock')
                        ),
                    ),
                    'fluid_label_align' => array(
                        'type' => 'select',
                        'label' => __('Label Align:', 'profit-builder'),
                        'std' => 'center',
                        'options' => array(
                            'left' => 'Left',
                            'center' => 'Center',
                            'right' => 'Right',
                        )
                    ),
                ),
            ),
            'group_evergreen' => array(
                'type' => 'collapsible',
                'label' => __('EverGreen', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'evergreen_days' => array(
                        'type' => 'number',
                        'label' => __('Days:', 'profit-builder'),
                        'std' => 0,
                        'min' => 0,
                        'max' => 31,
                        'hide_if' => array(
                            'timer_style' => array('Fixed')
                        ),
                    ),
                    'evergreen_hours' => array(
                        'type' => 'number',
                        'label' => __('Hours:', 'profit-builder'),
                        'std' => 0,
                        'min' => 0,
                        'max' => 24,
                        'hide_if' => array(
                            'timer_style' => array('Fixed')
                        ),
                    ),
                    'evergreen_minutes' => array(
                        'type' => 'number',
                        'label' => __('Minutes:', 'profit-builder'),
                        'std' => 0,
                        'min' => 0,
                        'max' => 60,
                        'hide_if' => array(
                            'timer_style' => array('Fixed')
                        ),
                    ),
                    'evergreen_seconds' => array(
                        'type' => 'number',
                        'label' => __('Seconds:', 'profit-builder'),
                        'std' => 0,
                        'min' => 0,
                        'max' => 60,
                        'hide_if' => array(
                            'timer_style' => array('Fixed')
                        ),
                    ),
                ),
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* TEXTAREA */
/* -------------------------------------------------------------------------------- */
$textarea = array(
    'textarea' => array(
        'type' => 'draggable',
        'text' => __('Textarea', 'profit-builder'),
        'icon' => $this->url . 'images/icons/textarea.png',
        'function' => 'pbuilder_textarea',
        'group' => __('Advanced', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Textarea', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'title' => array(
                        'type' => 'input',
                        'label' => __('Title:', 'profit-builder'),
                        'label_width' => 0.35,
                        'control_width' => 0.60,
                        'std' => '',
                    ),
                    'content' => array(
                        'type' => 'textarea',
                        'std' => 'Lorem ipsum',
                    ),
                    'boxwidth' => array(
                        'type' => 'number',
                        'label' => __('Width:', 'profit-builder'),
                        'std' => 600,
                        'min' => 0,
                        'half_column' => 'true',
                        'max' => 1200,
                        'unit' => 'px',
                        'hide_if' => array(
                            'fullwidth' => array('true')
                        )
                    ),
                    'boxheight' => array(
                        'type' => 'number',
                        'label' => __('Height:', 'profit-builder'),
                        'std' => 150,
                        'min' => 0,
                        'half_column' => 'true',
                        'max' => 400,
                        'unit' => 'px',
                    ),
                    'fullwidth' => array(
                        'type' => 'checkbox',
                        'label' => __('Full Width', 'profit-builder'),
                        'std' => 'true',
                        'half_column' => 'true',
                    ),
                    'autop' => array(
                        'type' => 'checkbox',
                        'label' => __('Format new lines', 'profit-builder'),
                        'std' => 'true',
                        'desc' => '"Enter" key is a new line',
                        'half_column' => 'true',
                    ),
                    'stripbr' => array(
                        'type' => 'checkbox',
                        'label' => __('Strip &lt;BR&gt;\'s', 'profit-builder'),
                        'std' => 'false',
                        'desc' => 'Strip &lt;BR&gt; line breaks',
                        'half_column' => 'true',
                    ),
                    'showcontent' => array(
                        'type' => 'checkbox',
                        'label' => __('Show Content', 'profit-builder'),
                        'std' => 'true',
                        'half_column' => 'true',
                    ),
                ),
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* IFRAME */
/* -------------------------------------------------------------------------------- */
$iframe = array(
    'iframe' => array(
        'type' => 'draggable',
        'text' => __('iFrame', 'profit-builder'),
        'icon' => $this->url . 'images/icons/iframe.png',
        'function' => 'pbuilder_iframe',
        'group' => __('Advanced', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('IFrame', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'url' => array(
                        'type' => 'input',
                        'label' => __('URL:', 'profit-builder'),
                        'label_width' => 0.35,
                        'control_width' => 0.60,
                        'std' => 'http://imsuccesscenter.com',
                    ),
                    'iframe_width' => array(
                        'type' => 'number',
                        'label' => __('Width:', 'profit-builder'),
                        'std' => 600,
                        'min' => 0,
                        'half_column' => 'true',
                        'max' => 1200,
                        'unit' => 'px',
                        'hide_if' => array(
                            'fullwidth' => array('true')
                        )
                    ),
                    'iframe_height' => array(
                        'type' => 'number',
                        'label' => __('Height:', 'profit-builder'),
                        'std' => 600,
                        'min' => 0,
                        'half_column' => 'true',
                        'max' => 10000,
                        'unit' => 'px',
                        'hide_if' => array(
                            'fullheight' => array('true')
                        )
                    ),
                    'fullwidth' => array(
                        'type' => 'checkbox',
                        'label' => __('Full Width', 'profit-builder'),
                        'std' => 'true',
                        'half_column' => 'true',
                    ),
                    'noscroll' => array(
                        'type' => 'checkbox',
                        'label' => __('No Scroll', 'profit-builder'),
                        'std' => 'false',
                        'desc' => 'Turn off scroll bars',
                        'half_column' => 'true',
                    ),
                ),
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* CREATIVE POST SLIDER */
/* -------------------------------------------------------------------------------- */
$frb_creative_post_categories = get_categories(array('order' => 'desc'));
$frb_creative_post_ready_categories = array();
if (is_object($frb_creative_post_categories)) {
    foreach ($frb_creative_post_categories as $category) {
        $frb_creative_post_ready_categories = $frb_creative_post_ready_categories + array($category->term_id => $category->name);
    }
} else {
    if (is_array($frb_creative_post_categories) && !empty($frb_creative_post_categories)) {
        foreach ($frb_creative_post_categories as $category) {
            $frb_creative_post_ready_categories = $frb_creative_post_ready_categories + array($category->term_id => $category->name);
        }
    }
}

$creative_post_slider = array(
    'creative_post_slider' => array(
        'type' => 'draggable',
        'text' => __('Creative Post Slider', 'profit-builder'),
        'icon' => $this->url . 'images/icons/creative-post-slider.png',
        'function' => 'pbuilder_creative_post_slider',
        'group' => __('Content', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'slides_per_view' => array(
                        'type' => 'number',
                        'label' => __('Slides Per View:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'min' => 1,
                        'max' => 15,
                        'unit' => '',
                        'std' => 3
                    ),
                    'number_of_posts' => array(
                        'type' => 'number',
                        'label' => __('Number Of Posts:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'min' => 1,
                        'max' => 50,
                        'unit' => '',
                        'std' => 5
                    ),
                    'categories' => array(
                        'type' => 'select',
                        'multiselect' => 'true',
                        'label' => __('Categories:', 'profit-builder'),
                        'std' => '1',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => $frb_creative_post_ready_categories
                    ),
                    'category_order' => array(
                        'type' => 'select',
                        'label' => __('Category Order:', 'profit-builder'),
                        'std' => 'desc',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'asc' => 'Ascending',
                            'desc' => 'Descending'
                        )
                    ),
                    'order_by' => array(
                        'type' => 'select',
                        'label' => __('Order by:', 'profit-builder'),
                        'std' => 'ID',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'ID' => 'ID',
                            'author' => 'Author',
                            'title' => 'Title',
                            'name' => 'Name',
                            'date' => 'Date',
                            'comment_count' => 'Comments',
                            'rand' => 'Random'
                        )
                    ),
                    'image_size' => array(
                        'type' => 'select',
                        'label' => __('Image Size:', 'profit-builder'),
                        'std' => 'full',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'full' => 'Full',
                            'large' => 'Large',
                            'creative_post_slider_medium' => 'Medium',
                            'medium' => 'Small',
                            'thumbnail' => 'Thumbnail'
                        )
                    ),
                    'aspect_ratio' => array(
                        'type' => 'select',
                        'label' => __('Aspect Ratio:', 'profit-builder'),
                        'std' => '16:9',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            '1:1' => '1:1',
                            '4:3' => '4:3',
                            '16:9' => '16:9',
                            '16:10' => '16:10',
                            '1:2' => '1:2'
                        )
                    ),
                    'resize_reference' => array(
                        'type' => 'number',
                        'label' => __('Min. Slide Width:', 'profit-builder'),
                        'desc' => __('Reference for responsive layout calculations', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'unit' => 'px',
                        'std' => 200
                    ),
                    'enable_custom_height' => array(
                        'type' => 'checkbox',
                        'std' => 'false',
                        'label' => __('Custom Height', 'profit-builder')
                    ),
                    'custom_slider_height' => array(
                        'type' => 'number',
                        'label' => __('Slider Height:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'min' => 1,
                        'max' => 1200,
                        'unit' => 'px',
                        'std' => 300,
                        'hide_if' => array(
                            'enable_custom_height' => array('false')
                        )
                    )
                )
            ),
            'group_hover' => array(
                'type' => 'collapsible',
                'label' => __('Hover Element', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'hover_background_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => '#ffffff',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'hover_text_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => $opts['text_color'],
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'link_type' => array(
                        'type' => 'select',
                        'label' => __('Link Type:', 'profit-builder'),
                        'std' => 'prettyphoto',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'prettyphoto' => 'Lightbox',
                            'post' => 'Post'
                        )
                    ),
                    'open_link_in' => array(
                        'type' => 'select',
                        'label' => __('Open Link In:', 'profit-builder'),
                        'std' => 'default',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'options' => array(
                            'default' => 'Same Tab',
                            'post' => 'New Tab'
                        ),
                        'hide_if' => array(
                            'link_type' => array('prettyphoto')
                        )
                    ),
                    'enable_icon' => array(
                        'type' => 'checkbox',
                        'std' => 'true',
                        'label' => __('Hover Icon', 'profit-builder')
                    ),
                    'hover_icon' => array(
                        'type' => 'icon',
                        'label' => __('Icon:', 'profit-builder'),
                        'std' => 'fa-search',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'enable_icon' => array('false')
                        )
                    ),
                    'hover_icon_size' => array(
                        'type' => 'number',
                        'label' => __('Hover icon size:', 'profit-builder'),
                        'std' => '30',
                        'unit' => 'px',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'enable_icon' => array('false')
                        )
                    ),
                    'title_size' => array(
                        'type' => 'number',
                        'label' => __('Title Font Size:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'min' => 12,
                        'std' => 20,
                        'unit' => 'px'
                    ),
                    'title_line_height' => array(
                        'type' => 'number',
                        'label' => __('Title Line Height:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'min' => 12,
                        'std' => 'default', //'std' => 28,
                        'default' => 'default',
                        'unit' => 'px'
                    ),
                    'cat_size' => array(
                        'type' => 'number',
                        'label' => __('Cat. Font Size:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'min' => 12,
                        'std' => 14,
                        'unit' => 'px',
                        'hide_if' => array(
                            'category_show' => array('false')
                        )
                    ),
                    'cat_line_height' => array(
                        'type' => 'number',
                        'label' => __('Cat. Line Height:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'min' => 12,
                        'std' => 'default', //'std' => 18,
                        'default' => 'default',
                        'unit' => 'px',
                        'hide_if' => array(
                            'category_show' => array('false')
                        )
                    ),
                    'excerpt_size' => array(
                        'type' => 'number',
                        'label' => __('Excerpt Font Size:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'min' => 12,
                        'std' => 14,
                        'unit' => 'px',
                        'hide_if' => array(
                            'excerpt_show' => array('false')
                        )
                    ),
                    'excerpt_line_height' => array(
                        'type' => 'number',
                        'label' => __('Excerpt Line Height:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'min' => 12,
                        'std' => 'default', //'std' => 18,
                        'default' => 'default',
                        'unit' => 'px',
                        'hide_if' => array(
                            'excerpt_show' => array('false')
                        )
                    ),
                    'excerpt_lenght' => array(
                        'type' => 'number',
                        'label' => __('Excerpt Lenght:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'min' => 0,
                        'std' => 30,
                        'unit' => '',
                        'hide_if' => array(
                            'excerpt_show' => array('false')
                        )
                    ),
                    'category_show' => array(
                        'type' => 'checkbox',
                        'half_column' => 'true',
                        'std' => 'true',
                        'label' => __('Category', 'profit-builder')
                    ),
                    'excerpt_show' => array(
                        'type' => 'checkbox',
                        'half_column' => 'true',
                        'std' => 'false',
                        'label' => __('Excerpt', 'profit-builder')
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* FEATURES */
/* -------------------------------------------------------------------------------- */
$features = array(
    'features' => array(
        'type' => 'draggable',
        'text' => __('Features', 'profit-builder'),
        'icon' => $this->url . 'images/icons/features.png',
        'function' => 'pbuilder_features',
        'group' => __('Content', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'order' => array(
                        'type' => 'select',
                        'label' => __('Order:', 'profit-builder'),
                        'std' => 'icon-after-title',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'icon-left' => __('Icon left', 'profit-builder'),
                            'icon-right' => __('Icon right', 'profit-builder'),
                            'icon-after-title' => __('Icon after title', 'profit-builder'),
                            'icon-before-title' => __('Icon before title', 'profit-builder')
                        )
                    ),
                    'style' => array(
                        'type' => 'select',
                        'label' => __('Style:', 'profit-builder'),
                        'std' => 'clean',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'clean' => __('Clean', 'profit-builder'),
                            'squared' => __('Squared', 'profit-builder'),
                            'rounded' => __('Rounded', 'profit-builder'),
                            'icon-squared' => __('Icon squared', 'profit-builder'),
                            'icon-rounded' => __('Icon rounded', 'profit-builder')
                        )
                    ),
                    'link' => array(
                        'type' => 'input',
                        'label' => __('Link:', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'desc' => __('leave empty if you dont want the feature to be linked', 'profit-builder')
                    ),
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['dark_back_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'style' => array('clean')
                        )
                    ),
                    'back_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Hover:', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'style' => array('clean')
                        )
                    )
                )
            ),
            'group_title' => array(
                'type' => 'collapsible',
                'label' => __('Title', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'title' => array(
                        'type' => 'input',
                        'label' => __('Title:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'std' => 'Lorem ipsum'
                    ),
                    'title_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['title_color']
                    ),
                    'title_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Hover:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['main_color']
                    )
                )
            ),
            'group_icon' => array(
                'type' => 'collapsible',
                'label' => __('Icon', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'icon' => array(
                        'type' => 'icon',
                        'label' => __('Icon Type:', 'profit-builder'),
                        'std' => 'na-svg23'
                    ),
                    'icon_size' => array(
                        'type' => 'number',
                        'std' => 40,
                        'unit' => 'px',
                        'half_column' => 'true',
                        'max' => 150,
                        'label' => __('Size:', 'profit-builder')
                    ),
                    'icon_padding' => array(
                        'type' => 'number',
                        'std' => 0,
                        'unit' => 'px',
                        'half_column' => 'true',
                        'max' => 100,
                        'label' => __('Padding:', 'profit-builder')
                    ),
                    'icon_color' => array(
                        'type' => 'color',
                        'label' => __('Color', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['title_color']
                    ),
                    'icon_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Hover:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['main_color']
                    ),
                    'icon_border' => array(
                        'type' => 'checkbox',
                        'std' => 'false',
                        'label' => __('Icon border', 'profit-builder')
                    )
                )
            ),
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Text', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'content' => array(
                        'type' => 'textarea',
                        'label' => __('Content', 'profit-builder'),
                        'std' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.'
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['text_color']
                    ),
                    'text_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Hover:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['main_color']
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* CONTACT FORM */
/* -------------------------------------------------------------------------------- */
$contact_form = array(
    'contact_form' => array(
        'type' => 'draggable',
        'text' => __('Contact Form', 'profit-builder'),
        'icon' => $this->url . 'images/icons/contact-form.png',
        'function' => 'pbuilder_contact_form',
        'group' => __('Content', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_colors' => array(
                'type' => 'collapsible',
                'label' => __('Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => '#333333',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
                    'active_text_color' => array(
                        'type' => 'color',
                        'label' => __('Active Text:', 'profit-builder'),
                        'std' => '#cccccc',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
					'placeholder_color' => array(
                        'type' => 'color',
                        'label' => __('Placeholder Text Color:', 'profit-builder'),
                        'std' => '#CCCCCC',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
                    'background_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => 'transparent',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
                    'active_background_color' => array(
                        'type' => 'color',
                        'label' => __('Active Background:', 'profit-builder'),
                        'std' => 'transparent',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
                    'border_color' => array(
                        'type' => 'color',
                        'label' => __('Border:', 'profit-builder'),
                        'std' => '#e7e7e7',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
                    'active_border_color' => array(
                        'type' => 'color',
                        'label' => __('Active Border:', 'profit-builder'),
                        'std' => '#cccccc',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
					'required_error_color' => array(
                        'type' => 'color',
                        'label' => __('Required Field Error Border:', 'profit-builder'),
                        'std' => '#FF0000',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    )
                )
            ),
            'group_form' => array(
                'type' => 'collapsible',
                'label' => __('Form', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'email_ph' => array(
                        'type' => 'input',
                        'label' => __('Email Field Text:', 'profit-builder'),
                        'label_width' => 0.5,
                        'std' => __('E-mail Address', 'profit-builder'),
                        'control_width' => 0.5,
                        'desc' => 'Text for the email field'
                    ),
                    'name_ph' => array(
                        'type' => 'input',
                        'label' => __('Name Field Text:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => __('Name', 'profit-builder'),
                        'desc' => 'Text for the name field'
                    ),
                    'website_ph' => array(
                        'type' => 'input',
                        'label' => __('Website Field Text:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'desc' => 'Text for the website field',
                        'std' => __('Website', 'profit-builder')
                    ),
                    'textarea_ph' => array(
                        'type' => 'input',
                        'label' => __('Message Field Text:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'desc' => 'Text for the textarea field',
                        'std' => __('Message goes here', 'profit-builder')
                    ),
                    'custom_field' => array(
                        'type' => 'checkbox',
                        'half_column' => 'true',
                        'label' => __('Custom Field', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'datepicker' => array(
                        'type' => 'checkbox',
                        'half_column' => 'true',
                        'label' => __('Date picker', 'profit-builder'),
                        'std' => 'false',
                        'hide_if' => array(
                            'custom_field' => array('false')
                        )
                    ),
                    'custom_ph' => array(
                        'type' => 'input',
                        'label' => __('Custom Field Text:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'desc' => 'Text for the custom field',
                        'hide_if' => array(
                            'custom_field' => array('false')
                        )
                    ),
                    'recipient_name' => array(
                        'type' => 'input',
                        'label' => __('Recipient Name:', 'profit-builder'),
                        'label_width' => 1,
                        'control_width' => 1,
                        'std' => '',
                        'desc' => __('Name to be shown in auto-response message', 'profit-builder')
                    ),
                    'recipient_email' => array(
                        'type' => 'input',
                        'label' => __('Recipient E-mail:', 'profit-builder'),
                        'label_width' => 1,
                        'std' => '',
                        'control_width' => 1
                    ),
                    'required' => array(
                        'type' => 'select',
                        'multiselect' => 'true',
                        'label' => __('Required Fields:', 'profit-builder'),
                        'std' => 'name,email,textarea',
                        'label_width' => 1,
                        'control_width' => 1,
                        'options' => array(
                            'email' => 'E-mail',
                            'name' => 'Name',
                            'website' => 'Website',
                            'custom' => 'Custom Field',
                            'textarea' => 'Message',
                        )
                    ),
                    'send_response_delay' => array(
                        'type' => 'number',
                        'label' => __('Response Delay:', 'profit-builder'),
                        'max' => 10000,
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => 1500,
                        'unit' => 'ms'
                    ),
                    'response_message' => array(
                        'type' => 'textarea',
                        'label' => __('Response Message:', 'profit-builder'),
                        'label_width' => 1,
                        'control_width' => 1,
                        'std' => __('Message Successfully Sent!', 'profit-builder'),
                    )
                )
            ),
            'group_button' => array(
                'type' => 'collapsible',
                'label' => __('Button', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'submit_ph' => array(
                        'type' => 'input',
                        'label' => __('Submit Field Text:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'desc' => 'Text for the submit field',
                        'std' => __('Submit', 'profit-builder')
                    ),
                    'button_text_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => '#ffffff',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
                    'active_button_text_color' => array(
                        'type' => 'color',
                        'label' => __('Active Text:', 'profit-builder'),
                        'std' => '#ffffff',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
                    'button_background_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => '#555555',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
                    'active_button_background_color' => array(
                        'type' => 'color',
                        'label' => __('Active Background:', 'profit-builder'),
                        'std' => '#222222',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
                    'button_border_color' => array(
                        'type' => 'color',
                        'label' => __('Border:', 'profit-builder'),
                        'std' => '#555555',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
                    'active_button_border_color' => array(
                        'type' => 'color',
                        'label' => __('Active Border:', 'profit-builder'),
                        'std' => '#222222',
                        'label_width' => 0.5,
                        'control_width' => 0.50
                    ),
                    'button_align' => array(
                        'type' => 'select',
                        'label' => __('Button Alignment:', 'profit-builder'),
                        'std' => 'right',
                        'options' => array(
                            'center' => 'Center',
                            'left' => 'Left',
                            'right' => 'Right'
                        )
                    ),
                    'button_fullwidth' => array(
                        'type' => 'checkbox',
                        'label' => __('Button Fullwidth', 'profit-builder'),
                        'std' => 'false'
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* TESTIMONIALS */
/* -------------------------------------------------------------------------------- */
$testimonials = array(
    'testimonials' => array(
        'type' => 'draggable',
        'text' => __('Testimonials', 'profit-builder'),
        'icon' => $this->url . 'images/icons/testimonials.png',
        'function' => 'pbuilder_testimonials',
        'group' => __('Advanced', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_name' => array(
                'type' => 'collapsible',
                'label' => __('Name', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'name' => array(
                        'type' => __('input', 'profit-builder'),
                        'label' => __('Name:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'std' => __('John Dough', 'profit-builder')
                    ),
                    'profession' => array(
                        'type' => 'input',
                        'label' => __('Title:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'std' => __('photographer / fashion interactive', 'profit-builder'),
                    ),
                    'name_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['title_color']
                    ),
                    'url' => array(
                        'type' => 'input',
                        'label' => __('Link:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'desc' => 'Type if you want to link the testimonial'
                    ),
                    'main_color' => array(
                        'type' => 'color',
                        'label' => __('Line:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['main_color']
                    )
                )
            ),
            'group_quote' => array(
                'type' => 'collapsible',
                'label' => __('Quote', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'quote' => array(
                        'type' => 'input',
                        'label' => __('Quote:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'std' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
                    ),
                    'style' => array(
                        'type' => 'select',
                        'label' => __('Style:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'std' => 'default',
                        'options' => array(
                            'default' => __('Default', 'profit-builder'),
                            'clean' => __('Clean', 'profit-builder'),
                            'squared' => __('Squared', 'profit-builder'),
                            'rounded' => __('Rounded', 'profit-builder')
                        )
                    ),
                    'quote_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['text_color']
                    ),
                    'italic' => array(
                        'type' => 'checkbox',
                        'std' => 'true',
                        'label' => __('Italic', 'profit-builder'),
                        'desc' => __('Enabling this option makes the text italic', 'profit-builder')
                    )
                )
            ),
            'group_image' => array(
                'type' => 'collapsible',
                'open' => 'true',
                'label' => __('Image', 'profit-builder'),
                'options' => array(
                    'image' => array(
                        'type' => 'image',
                        'std' => $this->url . 'images/testimonials-image.jpg',
                        'desc' => '80x80'
                    )
                )
            ),
            'group_background' => array(
                'type' => 'collapsible',
                'label' => __('Background', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['dark_back_color']
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* TABS */
/* -------------------------------------------------------------------------------- */
$tabs = array(
    'tabs' => array(
        'type' => 'draggable',
        'text' => __('Tabs', 'profit-builder'),
        'icon' => $this->url . 'images/icons/tabs.png',
        'function' => 'pbuilder_tabs',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'style' => array(
                        'type' => 'select',
                        'label' => __('Style', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'std' => 'default',
                        'options' => array(
                            'default' => __('Default', 'profit-builder'),
                            'clean' => __('Clean', 'profit-builder'),
                            'squared' => __('Squared', 'profit-builder'),
                            'rounded' => __('Rounded', 'profit-builder')
                        )
                    )
                )
            ),
            'group_tab_colors' => array(
                'type' => 'collapsible',
                'label' => __('Tab Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'title_color' => array(
                        'type' => 'color',
                        'label' => __('Title:', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'active_tab_title_color' => array(
                        'type' => 'color',
                        'label' => __('Active title:', 'profit-builder'),
                        'std' => $opts['title_color'],
                        'hide_if' => array(
                            'style' => array('clean', 'default')
                        )
                    ),
                    'tab_back_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => $opts['dark_back_color'],
                        'hide_if' => array(
                            'style' => array('clean')
                        )
                    ),
                    'active_tab_border_color' => array(
                        'type' => 'color',
                        'label' => __('Active border:', 'profit-builder'),
                        'std' => $opts['main_color']
                    )
                )
            ),
            'group_content_colors' => array(
                'type' => 'collapsible',
                'label' => __('Content Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'border_color' => array(
                        'type' => 'color',
                        'label' => __('Border:', 'profit-builder'),
                        'std' => $opts['light_border_color'],
                        'hide_if' => array(
                            'style' => array('default')
                        )
                    ),
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => $opts['light_back_color'],
                        'hide_if' => array(
                            'style' => array('clean', 'default')
                        )
                    )
                )
            ),
            'group_tabs' => array(
                'type' => 'collapsible',
                'label' => __('Tabs', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'sortable' => array(
                        'type' => 'sortable',
                        'desc' => __('Elements are sortable', 'profit-builder'),
                        'item_name' => __('tab item', 'profit-builder'),
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
                        'options' => array(
                            'title' => array(
                                'type' => 'input',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Title:', 'profit-builder')
                            ),
                            'content' => array(
                                'type' => 'textarea'
                            ),
                            'image' => array(
                                'type' => 'image',
                                'label' => __('Image:', 'profit-builder'),
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'desc' => __('Add an image to tab content', 'profit-builder')
                            ),
                            'active' => array(
                                'type' => 'checkbox',
                                'label' => __('Mark as Default', 'profit-builder'),
                                'desc' => __('Only one panel can be active at a time, so be sure to uncheck the others for it to work properly', 'profit-builder')
                            )
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* TOUR */
/* -------------------------------------------------------------------------------- */
$tour = array(
    'tour' => array(
        'type' => 'draggable',
        'text' => __('Tour', 'profit-builder'),
        'icon' => $this->url . 'images/icons/tour.png',
        'function' => 'pbuilder_tour',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'tab_position' => array(
                        'type' => 'select',
                        'label' => __('Tab position:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => 'left',
                        'options' => array(
                            'left' => __('Left', 'profit-builder'),
                            'right' => __('Right', 'profit-builder')
                        )
                    ),
                    'tab_text_align' => array(
                        'type' => 'select',
                        'label' => __('Tab header align:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => 'left',
                        'options' => array(
                            'left' => __('Left', 'profit-builder'),
                            'right' => __('Right', 'profit-builder'),
                            'center' => __('Center', 'profit-builder')
                        )
                    ),
                    'round' => array(
                        'type' => 'checkbox',
                        'label' => __('Round', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'desc' => __('Adds border-radius to "Tour" shortcode', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'border_position' => array(
                        'type' => 'checkbox',
                        'label' => __('Full tab border', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'desc' => __('If this option isn\'t set, only left/right border will be applied to tabs. Otherwise, full border will be set.', 'profit-builder'),
                        'std' => 'false'
                    )
                )
            ),
            'group_tab_colors' => array(
                'type' => 'collapsible',
                'label' => __('Tour Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'title_color' => array(
                        'type' => 'color',
                        'label' => __('Title:', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'active_tab_title_color' => array(
                        'type' => 'color',
                        'label' => __('Active title:', 'profit-builder'),
                        'std' => $opts['title_color'],
                    ),
                    'tab_back_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => $opts['dark_back_color'],
                    ),
                    'tab_border_color' => array(
                        'type' => 'color',
                        'label' => __('Border:', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'active_tab_border_color' => array(
                        'type' => 'color',
                        'label' => __('Active border:', 'profit-builder'),
                        'std' => $opts['main_color']
                    )
                )
            ),
            'group_content_colors' => array(
                'type' => 'collapsible',
                'label' => __('Content Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'border_color' => array(
                        'type' => 'color',
                        'label' => __('Border:', 'profit-builder'),
                        'std' => $opts['light_border_color']
                    ),
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => $opts['light_back_color']
                    )
                )
            ),
            'group_tabs' => array(
                'type' => 'collapsible',
                'label' => __('Tour', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'sortable' => array(
                        'type' => 'sortable',
                        'desc' => __('Elements are sortable', 'profit-builder'),
                        'item_name' => __('Tour item', 'profit-builder'),
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
                        'options' => array(
                            'title' => array(
                                'type' => 'input',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Title:', 'profit-builder')
                            ),
                            'content' => array(
                                'type' => 'textarea'
                            ),
                            'image' => array(
                                'type' => 'image',
                                'label' => __('Image:', 'profit-builder'),
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'desc' => __('Add an image to tour content', 'profit-builder')
                            ),
                            'active' => array(
                                'type' => 'checkbox',
                                'label' => __('Mark as Default', 'profit-builder'),
                                'desc' => __('Only one panel can be active at a time, so be sure to uncheck the others for it to work properly', 'profit-builder')
                            )
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* QUESTIONSANSWERS */
/* -------------------------------------------------------------------------------- */
$qanda = array(
    'qanda' => array(
        'type' => 'draggable',
        'text' => __('FAQs', 'profit-builder'),
        'icon' => $this->url . 'images/icons/question.png',
        'function' => 'pbuilder_qanda',
        'group' => __('Advanced', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Questions and Answers', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'title_color' => array(
                        'type' => 'color',
                        'label' => __('Title', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'title_size' => array(
                        'type' => 'number',
                        'label' => __('Title Font Size:', 'profit-builder'),
                        'std' => 18,
                        'unit' => 'px',
                    ),
                    'fixed_height' => array(
                        'type' => 'checkbox',
                        'label' => __('Fixed height', 'profit-builder'),
                        'desc' => __('if disabled height will vary due to content height', 'profit-builder'),
                        'std' => 'true',
                        'half_column' => 'true',
                    ),
                )
            ),
            'group_icon' => array(
                'type' => 'collapsible',
                'label' => __('Icon', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'icon' => array(
                        'type' => 'icon',
                        'label' => __('Icon:', 'profit-builder'),
                        'notNull' => false,
                        'std' => 'fa-caret-square-o-right',
                        'label_width' => 0.25,
                        'control_width' => 0.75
                    ),
                    'icon_color' => array(
                        'type' => 'color',
                        'label' => __('Icon Color:', 'profit-builder'),
                        'std' => '#dddddd',
                    ),
                    'icon_size' => array(
                        'type' => 'number',
                        'label' => __('Icon Size:', 'profit-builder'),
                        'std' => 28,
                        'unit' => 'px',
                    ),
                )
            ),
            'group_question_elements' => array(
                'type' => 'collapsible',
                'label' => __('Questions', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'sortable' => array(
                        'type' => 'sortable',
                        'desc' => __('Elements are sortable', 'profit-builder'),
                        'item_name' => __('Question', 'profit-builder'),
                        'label_width' => 0,
                        'control_width' => 1,
                        'std' => array(
                            'items' => array(
                                0 => array(
                                    'title' => 'Lorem ipsum?',
                                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                    'image' => '',
                                    'active' => 'true'
                                ),
                                1 => array(
                                    'title' => 'Lorem ipsum?',
                                    'content' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.',
                                    'image' => '',
                                    'active' => 'false'
                                ),
                                2 => array(
                                    'title' => 'Lorem ipsum?',
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
                        'options' => array(
                            'title' => array(
                                'type' => 'input',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Title:', 'profit-builder')
                            ),
                            'content' => array(
                                'type' => 'textarea'
                            ),
                            'image' => array(
                                'type' => 'image',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Image:', 'profit-builder'),
                                'desc' => __('Add an image to accordion content', 'profit-builder')
                            ),
                            'active' => array(
                                'type' => 'checkbox',
                                'label' => __('Active', 'profit-builder'),
                                'desc' => __('Only one panel can be active at a time, so be sure to uncheck the others for it to work properly', 'profit-builder')
                            )
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* ACCORDION */
/* -------------------------------------------------------------------------------- */
$accordion = array(
    'accordion' => array(
        'type' => 'draggable',
        'text' => __('Accordion', 'profit-builder'),
        'icon' => $this->url . 'images/icons/accordion.png',
        'function' => 'pbuilder_accordion',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'style' => array(
                        'type' => 'select',
                        'label' => __('Style:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'std' => 'default',
                        'options' => array(
                            'default' => __('Default', 'profit-builder'),
                            'clean-right' => __('Clean right', 'profit-builder'),
                            'squared-right' => __('Squared right', 'profit-builder'),
                            'rounded-right' => __('Rounded right', 'profit-builder'),
                            'clean-left' => __('Clean left', 'profit-builder'),
                            'squared-left' => __('Squared left', 'profit-builder'),
                            'rounded-left' => __('Rounded left', 'profit-builder')
                        )
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'main_color' => array(
                        'type' => 'color',
                        'label' => __('Main:', 'profit-builder'),
                        'std' => $opts['main_color'],
                        'hide_if' => array(
                            'style' => array('default')
                        )
                    ),
                    'fixed_height' => array(
                        'type' => 'checkbox',
                        'label' => __('Fixed height', 'profit-builder'),
                        'desc' => __('if disabled height will vary due to content height', 'profit-builder'),
                        'std' => 'true'
                    )
                )
            ),
            'group_title_colors' => array(
                'type' => 'collapsible',
                'label' => __('Title Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'title_color' => array(
                        'type' => 'color',
                        'label' => __('Title', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'title_active_color' => array(
                        'type' => 'color',
                        'label' => __('Active Title:', 'profit-builder'),
                        'std' => $opts['title_color'],
                        'hide_if' => array(
                            'style' => array('clean-right', 'clean-left', 'squared-left', 'rounded-left')
                        )
                    )
                )
            ),
            'group_trigger_colors' => array(
                'type' => 'collapsible',
                'label' => __('Trigger Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'trigger_color' => array(
                        'type' => 'color',
                        'label' => __('Trigger:', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'trigger_active_color' => array(
                        'type' => 'color',
                        'label' => __('Trigger active:', 'profit-builder'),
                        'std' => $opts['title_color']
                    )
                )
            ),
            'group_background_colors' => array(
                'type' => 'collapsible',
                'label' => __('Background Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => ''
                    ),
                    'border_color' => array(
                        'type' => 'color',
                        'label' => __('Border:', 'profit-builder'),
                        'std' => $opts['light_border_color']
                    )
                )
            ),
            'group_accordion_elements' => array(
                'type' => 'collapsible',
                'label' => __('Accordion Elements', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'sortable' => array(
                        'type' => 'sortable',
                        'desc' => __('Elements are sortable', 'profit-builder'),
                        'item_name' => __('accordion item', 'profit-builder'),
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
                        'options' => array(
                            'title' => array(
                                'type' => 'input',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Title:', 'profit-builder')
                            ),
                            'content' => array(
                                'type' => 'textarea'
                            ),
                            'image' => array(
                                'type' => 'image',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Image:', 'profit-builder'),
                                'desc' => __('Add an image to accordion content', 'profit-builder')
                            ),
                            'active' => array(
                                'type' => 'checkbox',
                                'label' => __('Active', 'profit-builder'),
                                'desc' => __('Only one panel can be active at a time, so be sure to uncheck the others for it to work properly', 'profit-builder')
                            )
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* TOGGLE */
/* -------------------------------------------------------------------------------- */
$toggle = array(
    'toggle' => array(
        'type' => 'draggable',
        'text' => __('Toggle', 'profit-builder'),
        'icon' => $this->url . 'images/icons/toggle.png',
        'function' => 'pbuilder_toggle',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'style' => array(
                        'type' => 'select',
                        'label' => __('Style:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'std' => 'clean-right',
                        'options' => array(
                            'clean-right' => __('Clean right', 'profit-builder'),
                            'squared-right' => __('Squared right', 'profit-builder'),
                            'rounded-right' => __('Rounded right', 'profit-builder'),
                            'clean-left' => __('Clean left', 'profit-builder'),
                            'squared-left' => __('Squared left', 'profit-builder'),
                            'rounded-left' => __('Rounded left', 'profit-builder')
                        )
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'main_color' => array(
                        'type' => 'color',
                        'label' => __('Main:', 'profit-builder'),
                        'std' => $opts['main_color']
                    ),
                    'fixed_height' => array(
                        'type' => 'checkbox',
                        'label' => __('Fixed height', 'profit-builder'),
                        'desc' => __('if disabled height will vary due to content height', 'profit-builder'),
                        'std' => 'false'
                    )
                )
            ),
            'group_title_colors' => array(
                'type' => 'collapsible',
                'label' => __('Title Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'title_color' => array(
                        'type' => 'color',
                        'label' => __('Title', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'title_active_color' => array(
                        'type' => 'color',
                        'label' => __('Active Title:', 'profit-builder'),
                        'std' => $opts['title_color'],
                        'hide_if' => array(
                            'style' => array('clean-right', 'clean-left', 'squared-left', 'rounded-left')
                        )
                    )
                )
            ),
            'group_trigger_colors' => array(
                'type' => 'collapsible',
                'label' => __('Trigger Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'trigger_color' => array(
                        'type' => 'color',
                        'label' => __('Trigger:', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'trigger_active_color' => array(
                        'type' => 'color',
                        'label' => __('Trigger active:', 'profit-builder'),
                        'std' => $opts['title_color']
                    )
                )
            ),
            'group_background_colors' => array(
                'type' => 'collapsible',
                'label' => __('Background Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => ''
                    ),
                    'border_color' => array(
                        'type' => 'color',
                        'label' => __('Border:', 'profit-builder'),
                        'std' => $opts['light_border_color']
                    ),
                    'active_border_color' => array(
                        'type' => 'color',
                        'label' => __('Border active:', 'profit-builder'),
                        'std' => '#cccccc',
                        'desc' => 'Active item\'s border'
                    )
                )
            ),
            'group_accordion_elements' => array(
                'type' => 'collapsible',
                'label' => __('Trigger Elements', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'sortable' => array(
                        'type' => 'sortable',
                        'desc' => __('Elements are sortable', 'profit-builder'),
                        'item_name' => __('Trigger item', 'profit-builder'),
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
                        'options' => array(
                            'title' => array(
                                'type' => 'input',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Title:', 'profit-builder')
                            ),
                            'content' => array(
                                'type' => 'textarea'
                            ),
                            'image' => array(
                                'type' => 'image',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Image:', 'profit-builder'),
                                'desc' => __('Add an image to trigger item content', 'profit-builder')
                            ),
                            'active' => array(
                                'type' => 'checkbox',
                                'label' => __('Active', 'profit-builder'),
                                'desc' => __('Only one panel can be active at a time, so be sure to uncheck the others for it to work properly', 'profit-builder')
                            )
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* COUNTER */
/* -------------------------------------------------------------------------------- */
$counter = array(
    'counter' => array(
        'type' => 'draggable',
        'text' => __('Counter', 'profit-builder'),
        'icon' => $this->url . 'images/icons/counter.png',
        'function' => 'pbuilder_counter',
        'group' => __('Charts, Bars, Counters', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_counter' => array(
                'type' => 'collapsible',
                'label' => __('Counter', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'start_val' => array(
                        'type' => 'input',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'label' => __('Start No:', 'profit-builder'),
                        'std' => 9999
                    ),
                    'end_val' => array(
                        'type' => 'input',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'label' => __('End No:', 'profit-builder'),
                        'std' => 8847
                    ),
                    'direction' => array(
                        'type' => 'select',
                        'label' => __('Direction:', 'profit-builder'),
                        'std' => 'auto',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'auto' => 'Auto',
                            'upward' => 'Upward',
                            'downward' => 'Downward'
                        )
                    ),
                    'color' => array(
                        'type' => 'color',
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 60,
                        'min' => 10,
                        'half_column' => 'true',
                        'max' => 350,
                        'unit' => 'px'
                    ),
                    'item_align' => array(
                        'type' => 'select',
                        'label' => __('Item Alignment:', 'profit-builder'),
                        'std' => 'center',
                        'options' => array(
                            'center' => 'Center',
                            'left' => 'Left',
                            'right' => 'Right'
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* PERCENTAGE BARS */
/* -------------------------------------------------------------------------------- */
$percentage_bars = array(
    'percentage_bars' => array(
        'type' => 'draggable',
        'text' => __('Percentage Bars', 'profit-builder'),
        'icon' => $this->url . 'images/icons/percentage-bar.png',
        'function' => 'pbuilder_percentage_bars',
        'group' => __('Charts, Bars, Counters', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'bar_style' => array(
                        'type' => 'select',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'label' => __('Bar Style:', 'profit-builder'),
                        'std' => 'sharp',
                        'options' => array(
                            'sharp' => 'Sharp',
                            'round' => 'Round',
                        )
                    ),
                    'element_spacing' => array(
                        'type' => 'number',
                        'label' => __('Bar Margin:', 'profit-builder'),
                        'min' => 0,
                        'max' => 100,
                        'std' => 20,
                        'unit' => 'px'
                    ),
                    'custom_height' => array(
                        'type' => 'number',
                        'label' => __('Bar Height:', 'profit-builder'),
                        'min' => 1,
                        'max' => 100,
                        'std' => 5,
                        'unit' => 'px'
                    ),
                    'headline_color' => array(
                        'type' => 'color',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'label' => __('Headline Color:', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'line_background' => array(
                        'type' => 'color',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'label' => __('Bar Bg. Color:', 'profit-builder'),
                        'std' => '#eeeeee'
                    ),
                    'percent_pin' => array(
                        'type' => 'checkbox',
                        'half_column' => 'true',
                        'label' => __('Percentage Pin', 'profit-builder'),
                        'std' => 'true'
                    ),
                    'headline_inside' => array(
                        'type' => 'checkbox',
                        'half_column' => 'true',
                        'label' => __('Headline Inside', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'headline_top_margin' => array(
                        'type' => 'number',
                        'label' => __('H-Tag Top Margin:', 'profit-builder'),
                        'min' => -50,
                        'max' => 50,
                        'std' => 0,
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'unit' => 'px',
                        'hide_if' => array(
                            'headline_inside' => array('false')
                        )
                    )
                )
            ),
            'group_sortable' => array(
                'type' => 'collapsible',
                'label' => __('Sortable', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'sortable' => array(
                        'type' => 'sortable',
                        'label' => __('Bars', 'profit-builder'),
                        'item_name' => 'bar',
                        'label_width' => 0,
                        'control_width' => 1,
                        'std' => array(
                            'items' => array(
                                0 => array(
                                    'direction' => 'ltr',
                                    'headline_content' => 'Percentage Bar',
                                    'line_color' => $opts['main_color'],
                                    'pin_color' => '#222222',
                                    'pin_text_color' => '#ffffff',
                                    'percentage' => 89,
                                    'an_speed' => 1000,
                                    'pattern_url' => ' '
                                )
                            ),
                            'order' => array(
                                0 => 0
                            )
                        ),
                        'options' => array(
                            'headline_content' => array(
                                'type' => 'input',
                                'label_width' => 1,
                                'control_width' => 1,
                                'label' => __('Headline Content:', 'profit-builder'),
                                'std' => 'Percentage Bar'
                            ),
                            'pattern_url' => array(
                                'type' => 'image',
                                'label' => __('Image/Pattern:', 'profit-builder'),
                                'std' => ' '
                            ),
                            'direction' => array(
                                'type' => 'select',
                                'label_width' => 0.5,
                                'control_width' => 0.5,
                                'label' => __('Direction:', 'profit-builder'),
                                'std' => 'ltr',
                                'options' => array(
                                    'ltr' => 'Left to Right',
                                    'rtl' => 'Right to Left',
                                )
                            ),
                            'line_color' => array(
                                'type' => 'color',
                                'label_width' => 0.5,
                                'control_width' => 0.5,
                                'label' => __('Bar Active Color:', 'profit-builder'),
                                'std' => $opts['main_color']
                            ),
                            'percentage' => array(
                                'type' => 'number',
                                'label' => __('Percentage:', 'profit-builder'),
                                'min' => 0,
                                'max' => 100,
                                'std' => 100,
                                'unit' => '%'
                            ),
                            'an_speed' => array(
                                'type' => 'number',
                                'label' => __('Animation Speed:', 'profit-builder'),
                                'min' => 100,
                                'max' => 10000,
                                'std' => 1000,
                                'unit' => 'ms'
                            ),
                            'pin_color' => array(
                                'type' => 'color',
                                'label_width' => 0.5,
                                'control_width' => 0.5,
                                'label' => __('Pin Color:', 'profit-builder'),
                                'std' => '#222222',
                                'hide_if' => array(
                                    'percent_pin' => array('false')
                                )
                            ),
                            'pin_text_color' => array(
                                'type' => 'color',
                                'label_width' => 0.5,
                                'control_width' => 0.5,
                                'label' => __('Pin Text Color:', 'profit-builder'),
                                'std' => '#ffffff',
                                'hide_if' => array(
                                    'percent_pin' => array('false')
                                )
                            )
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* PERCENTAGE CHART */
/* -------------------------------------------------------------------------------- */
$percentage_chart = array(
    'percentage_chart' => array(
        'type' => 'draggable',
        'text' => __('Percentage Chart', 'profit-builder'),
        'icon' => $this->url . 'images/icons/percentage-cart.png',
        'function' => 'pbuilder_percentage_chart',
        'group' => __('Charts, Bars, Counters', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_number' => array(
                'type' => 'collapsible',
                'label' => __('Number', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 36,
                        'min' => 10,
                        'half_column' => 'true',
                        'max' => 200,
                        'unit' => 'px'
                    ),
                    'percentage' => array(
                        'type' => 'number',
                        'label' => __('Number:', 'profit-builder'),
                        'min' => 0,
                        'half_column' => 'true',
                        'max' => 100,
                        'std' => 73
                    ),
                    'color' => array(
                        'type' => 'color',
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'item_align' => array(
                        'type' => 'select',
                        'label' => __('Item Alignment:', 'profit-builder'),
                        'std' => 'center',
                        'options' => array(
                            'center' => 'Center',
                            'left' => 'Left',
                            'right' => 'Right'
                        )
                    ),
                    'percent_char' => array(
                        'type' => 'checkbox',
                        'label' => __('Percentage Character', 'profit-builder'),
                        'std' => 'true'
                    )
                )
            ),
            'group_line' => array(
                'type' => 'collapsible',
                'label' => __('Line', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    /* 'line_style' => array(
                      'type' => 'select',
                      'label' => __('Cap:','profit-builder'),
                      'label_width' => 0.25,
                      'control_width' => 0.75,
                      'std' => 'square',
                      'options' => array(
                      'square' => 'Square',
                      'round' => 'Round'
                      )
                      ), */
                    'line_width' => array(
                        'type' => 'number',
                        'label' => __('Width:', 'profit-builder'),
                        'min' => 1,
                        'half_column' => 'true',
                        'max' => 20,
                        'std' => 3
                    ),
                    'radius' => array(
                        'type' => 'number',
                        'label' => __('Radius:', 'profit-builder'),
                        'min' => 50,
                        'half_column' => 'true',
                        'max' => 280,
                        'std' => 200
                    ),
                    'line_color' => array(
                        'type' => 'color',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'label' => __('Active Color:', 'profit-builder'),
                        'std' => $opts['main_color']
                    ),
                    'background_line_color' => array(
                        'type' => 'color',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'label' => __('Passive Color:', 'profit-builder'),
                        'std' => '#ffffff'
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* GRAPH */
/* -------------------------------------------------------------------------------- */
$graph = array(
    'graph' => array(
        'type' => 'draggable',
        'text' => __('Graph', 'profit-builder'),
        'icon' => $this->url . 'images/icons/graph.png',
        'function' => 'pbuilder_graph',
        'group' => __('Charts, Bars, Counters', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'item_align' => array(
                        'type' => 'select',
                        'label' => __('Item alignment:', 'profit-builder'),
                        'std' => 'center',
                        'options' => array(
                            'center' => 'Center',
                            'left' => 'Left',
                            'right' => 'Right'
                        )
                    ),
                    'item_height' => array(
                        'type' => 'number',
                        'label' => __('Height:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'max' => 1200,
                        'min' => 100,
                        'std' => 300
                    ),
                    'item_width' => array(
                        'type' => 'number',
                        'label' => __('Width:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'max' => 1200,
                        'min' => 100,
                        'std' => 1000
                    )
                )
            ),
            'group_legend' => array(
                'type' => 'collapsible',
                'label' => __('Legend', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'legend_position' => array(
                        'type' => 'select',
                        'label' => __('Position:', 'profit-builder'),
                        'std' => 'right',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'bottom' => 'Bottom',
                            'left' => 'Left',
                            'right' => 'Right'
                        )
                    ),
                    'legend_shape' => array(
                        'type' => 'select',
                        'label' => __('Shape:', 'profit-builder'),
                        'std' => 'circle',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'square' => 'Square',
                            'round' => 'Round',
                            'circle' => 'Circle'
                        )
                    ),
                    'legend_font_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['text_color']
                    ),
                    'legend_font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'half_column' => 'true',
                        'min' => 10,
                        'max' => 100,
                        'std' => 14,
                        'unit' => 'px'
                    )
                )
            ),
            'group_graph' => array(
                'type' => 'collapsible',
                'label' => __('Graph', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'graph_style' => array(
                        'type' => 'select',
                        'label' => __('Style:', 'profit-builder'),
                        'std' => 'bar',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'line' => 'Line',
                            'bar' => 'Bar'
                        )
                    ),
                    'scale_font_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['text_color']
                    ),
                    'labels' => array(
                        'type' => 'input',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'label' => __('Labels:', 'profit-builder'),
                        'desc' => __('Words divided by comma'),
                        'std' => 'January,February,March,April,May,June,July'
                    ),
                    'fill' => array(
                        'type' => 'checkbox',
                        'std' => 'true',
                        'half_column' => 'true',
                        'label' => __('Fill', 'profit-builder'),
                        'hide_if' => array(
                            'graph_style' => array('bar'),
                        )
                    ),
                    'curve' => array(
                        'type' => 'checkbox',
                        'std' => 'true',
                        'half_column' => 'true',
                        'label' => __('Curve', 'profit-builder'),
                        'hide_if' => array(
                            'graph_style' => array('bar'),
                        )
                    ),
                    'bar_stroke' => array(
                        'type' => 'checkbox',
                        'std' => 'true',
                        'label' => __('Stroke', 'profit-builder'),
                        'hide_if' => array(
                            'graph_style' => array('line'),
                        )
                    )
                )
            ),
            'group_items' => array(
                'type' => 'collapsible',
                'label' => __('Items', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'sortable' => array(
                        'type' => 'sortable',
                        'label_width' => 0,
                        'control_width' => 1,
                        'desc' => __('Elements are sortable', 'profit-builder'),
                        'item_name' => __('Chart', 'profit-builder'),
                        'std' => array(
                            'items' => array(
                                0 => array(
                                    'data_value' => '15,20,25,30,50,60,80',
                                    'data_color' => '#44bdd6',
                                    'data_caption' => 'Lorem ipsum'
                                )
                            ),
                            'order' => array(
                                0 => 0
                            )
                        ),
                        'options' => array(
                            'data_value' => array(
                                'type' => 'input',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Values:', 'profit-builder'),
                                'desc' => __('Numbers divided by comma'),
                                'std' => '1,34,53,22,13,2,3'
                            ),
                            'data_caption' => array(
                                'type' => 'input',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Desc.:', 'profit-builder'),
                                'desc' => __('Descriptive legend text'),
                                'std' => 'Lorem ipsum'
                            ),
                            'data_color' => array(
                                'type' => 'color',
                                'label_width' => 0.25,
                                'control_width' => 0.5,
                                'label' => __('Color:', 'profit-builder'),
                                'std' => '#000000'
                            )
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* GAUGE CHART */
/* -------------------------------------------------------------------------------- */
$gauge_chart = array(
    'gauge' => array(
        'type' => 'draggable',
        'text' => __('Gauge', 'profit-builder'),
        'icon' => $this->url . 'images/icons/gauge.png',
        'function' => 'pbuilder_gauge_chart',
        'group' => __('Charts, Bars, Counters', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_chart' => array(
                'type' => 'collapsible',
                'label' => __('Gauge', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'item_align' => array(
                        'type' => 'select',
                        'label' => __('Item alignment:', 'profit-builder'),
                        'std' => 'center',
                        'options' => array(
                            'center' => 'Center',
                            'left' => 'Left',
                            'right' => 'Right'
                        )
                    ),
                    'radius' => array(
                        'type' => 'number',
                        'label' => __('Radius:', 'profit-builder'),
                        'min' => 20,
                        'max' => 1000,
                        'std' => 400
                    ),
                    'value' => array(
                        'type' => 'number',
                        'label' => __('Value:', 'profit-builder'),
                        'std' => 80,
                        'min' => 0,
                        'max' => 100,
                        'desc' => __('Default value of the gauge chart', 'profit-builder')
                    ),
                    'min_value' => array(
                        'type' => 'number',
                        'label' => __('Min value:', 'profit-builder'),
                        'min' => 0,
                        'max' => 10000,
                        'std' => 0,
                        'desc' => __('Minimum value of the gauge chart', 'profit-builder')
                    ),
                    'max_value' => array(
                        'type' => 'number',
                        'label' => __('Max value:', 'profit-builder'),
                        'min' => 0,
                        'max' => 10000,
                        'std' => 100,
                        'desc' => __('Maximum value of the gauge chart', 'profit-builder')
                    ),
                    'unit' => array(
                        'type' => 'input',
                        'label' => __('Unit:', 'profit-builder'),
                        'std' => '%',
                        'desc' => __('Gauge value unit', 'profit-builder')
                    ),
                    'show_min_max' => array(
                        'type' => 'checkbox',
                        'label' => __('Show min and max labels', 'profit-builder'),
                        'std' => 'true'
                    ),
                    'show_inner_shadow' => array(
                        'type' => 'checkbox',
                        'label' => __('Show inner shadow', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'animation_length' => array(
                        'type' => 'number',
                        'label' => __('Animation length:', 'profit-builder'),
                        'min' => 0,
                        'max' => 10000,
                        'std' => 2500,
                        'desc' => __('Length of the gauge animation in milliseconds', 'profit-builder'),
                        'unit' => 'ms'
                    )
                )
            ),
            'group_items' => array(
                'type' => 'collapsible',
                'label' => __('Gauge gradient colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'gauge_gradient_1' => array(
                        'type' => 'color',
                        'label' => __('Gradient color 1:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => '#1abc9c'
                    ),
                    'gauge_gradient_2' => array(
                        'type' => 'color',
                        'label' => __('Gradient color 2:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => '#9b59b6'
                    ),
                    'gauge_gradient_3' => array(
                        'type' => 'color',
                        'label' => __('Gradient color 3:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => '#2c3e50'
                    ),
                    'gauge_gradient_4' => array(
                        'type' => 'color',
                        'label' => __('Gradient color 4:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => '#d35400'
                    ),
                    'gauge_gradient_5' => array(
                        'type' => 'color',
                        'label' => __('Gradient color 5:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => '#c0392b'
                    ),
                )
            ),
            'group_legend' => array(
                'type' => 'collapsible',
                'label' => __('Style', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'value_color' => array(
                        'type' => 'color',
                        'label' => __('Value text color:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => $opts['text_color']
                    ),
                    'unit_color' => array(
                        'type' => 'color',
                        'label' => __('Unit text color:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => $opts['text_color']
                    ),
                    'gauge_color' => array(
                        'type' => 'color',
                        'label' => __('Gauge color:', 'profit-builder'),
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'std' => '#bfbfbf'
                    ),
                    'gauge_thickness' => array(
                        'type' => 'number',
                        'label' => __('Gauge thickness:', 'profit-builder'),
                        'min' => 0,
                        'max' => 20,
                        'std' => 2,
                        'desc' => __('Thickness of the gauge chart', 'profit-builder')
                    ),
                    'shadow_opacity' => array(
                        'type' => 'number',
                        'label' => __('Shadow opacity:', 'profit-builder'),
                        'min' => 0,
                        'max' => 10,
                        'std' => 5,
                        'desc' => __('Thickness of the gauge chart', 'profit-builder'),
                        'hide_if' => array(
                            'show_inner_shadow' => array('false')
                        )
                    ),
                    'shadow_size' => array(
                        'type' => 'number',
                        'label' => __('Shadow size:', 'profit-builder'),
                        'min' => 0,
                        'max' => 100,
                        'std' => 20,
                        'desc' => __('Size of the gauge shadow', 'profit-builder'),
                        'hide_if' => array(
                            'show_inner_shadow' => array('false')
                        )
                    ),
                    'shadow_v_offset' => array(
                        'type' => 'number',
                        'label' => __('Shadow vertical offset:', 'profit-builder'),
                        'min' => 0,
                        'max' => 50,
                        'std' => 5,
                        'desc' => __('Vertical offset of the gauge shadow', 'profit-builder'),
                        'hide_if' => array(
                            'show_inner_shadow' => array('false')
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* PIECHART */
/* -------------------------------------------------------------------------------- */
$piechart = array(
    'piechart' => array(
        'type' => 'draggable',
        'text' => __('Piechart', 'profit-builder'),
        'icon' => $this->url . 'images/icons/piecart.png',
        'function' => 'pbuilder_piechart',
        'group' => __('Charts, Bars, Counters', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_chart' => array(
                'type' => 'collapsible',
                'label' => __('Chart', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'item_align' => array(
                        'type' => 'select',
                        'label' => __('Item alignment:', 'profit-builder'),
                        'std' => 'center',
                        'options' => array(
                            'center' => 'Center',
                            'left' => 'Left',
                            'right' => 'Right'
                        )
                    ),
                    'radius' => array(
                        'type' => 'number',
                        'label' => __('Radius:', 'profit-builder'),
                        'min' => 20,
                        'half_column' => 'true',
                        'max' => 1000,
                        'std' => 220
                    ),
                    'inner_cut' => array(
                        'type' => 'number',
                        'label' => __('Mid Cut:', 'profit-builder'),
                        'min' => 0,
                        'half_column' => 'true',
                        'max' => 100,
                        'std' => 0
                    ),
                    'stroke_width' => array(
                        'type' => 'number',
                        'label' => __('Split Line Width:', 'profit-builder'),
                        'min' => 0,
                        'max' => 20,
                        'std' => 15
                    ),
                    'stroke_color' => array(
                        'type' => 'color',
                        'label' => __('Split Line Color:', 'profit-builder'),
                        'std' => '#ffffff'
                    )
                )
            ),
            'group_legend' => array(
                'type' => 'collapsible',
                'label' => __('Legend', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'legend_position' => array(
                        'type' => 'select',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'label' => __('Position:', 'profit-builder'),
                        'std' => 'bottom',
                        'options' => array(
                            'bottom' => 'Bottom',
                            'left' => 'Left',
                            'right' => 'Right'
                        )
                    ),
                    'color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'std' => $opts['text_color']
                    ),
                    'font_size' => array(
                        'type' => 'number',
                        'half_column' => 'true',
                        'label' => __('Size:', 'profit-builder'),
                        'min' => 10,
                        'max' => 100,
                        'std' => 16,
                        'unit' => 'px'
                    )
                )
            ),
            'group_items' => array(
                'type' => 'collapsible',
                'label' => __('Items', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'sortable' => array(
                        'type' => 'sortable',
                        'label_width' => 0,
                        'control_width' => 1,
                        'desc' => __('Elements are sortable', 'profit-builder'),
                        'item_name' => __('Chart', 'profit-builder'),
                        'std' => array(
                            'items' => array(
                                0 => array(
                                    'data_value' => '15',
                                    'data_color' => '#6b58cd',
                                    'data_caption' => 'Lorem ipsum'
                                ),
                                1 => array(
                                    'data_value' => '14',
                                    'data_color' => '#8677d4',
                                    'data_caption' => 'Lorem ipsum'
                                ),
                                2 => array(
                                    'data_value' => '13',
                                    'data_color' => '#9c8ddc',
                                    'data_caption' => 'Lorem ipsum'
                                )
                            ),
                            'order' => array(
                                0 => 0,
                                1 => 1,
                                2 => 2
                            )
                        ),
                        'options' => array(
                            'data_value' => array(
                                'type' => 'input',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Value:', 'profit-builder'),
                                'desc' => __('Numeric Value'),
                                'std' => 1
                            ),
                            'data_caption' => array(
                                'type' => 'input',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Desc.:', 'profit-builder'),
                                'desc' => __('Descriptive text'),
                                'std' => 'Lorem ipsum'
                            ),
                            'data_color' => array(
                                'type' => 'color',
                                'label_width' => 0.25,
                                'control_width' => 0.5,
                                'label' => __('Color:', 'profit-builder'),
                                'std' => '#000000'
                            )
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* PRICING TABLE */
/* -------------------------------------------------------------------------------- */
$pricing = array(
    'pricing' => array(
        'type' => 'draggable',
        'text' => __('Pricing table', 'profit-builder'),
        'icon' => $this->url . 'images/icons/pricing-table-V2.png',
        'function' => 'pbuilder_pricing',
        'group' => __('Advanced', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_pricing' => array(
                'type' => 'collapsible',
                'label' => __('Pricing', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'colnum' => array(
                        'type' => 'select',
                        'label' => __('Number of columns', 'profit-builder'),
                        'std' => '1',
                        'options' => array(
                            '1' => __('One column', 'profit-builder'),
                            '2' => __('Two columns', 'profit-builder'),
                            '3' => __('Three columns', 'profit-builder'),
                            '4' => __('Four columns', 'profit-builder'),
                            '5' => __('Five columns', 'profit-builder')
                        )
                    ),
                    'services_sidebar' => array(
                        'type' => 'checkbox',
                        'label' => __('Show services sidebar', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'sortable' => array(
                        'type' => 'sortable',
                        'label_width' => 0,
                        'control_width' => 1,
                        'desc' => __('Rows are sortable', 'profit-builder'),
                        'item_name' => __('row', 'profit-builder'),
                        'std' => array(
                            'items' => array(
                                0 => array(
                                    'row_type' => 'heading',
                                    'bot_border' => 'true',
                                    'service_label' => 'Lorem Ipsum',
                                    'service_icon' => 'fa-star',
                                    'column_1_icon' => 'fa-check',
                                    'column_1_text' => '<br><STRONG>REGULAR LICENSE</STRONG><BR><BR>',
                                    'column_1_price' => '40',
                                    'column_1_interval' => '/dolor sit amet',
                                    'column_1_button_text' => 'Lorem ipsum',
                                    'column_1_button_link' => '#',
                                    'column_2_icon' => 'fa-check',
                                    'column_2_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_2_price' => '40',
                                    'column_2_interval' => '/dolor sit amet',
                                    'column_2_button_text' => 'Lorem ipsum',
                                    'column_2_button_link' => '#',
                                    'column_3_icon' => 'fa-check',
                                    'column_3_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_3_price' => '40',
                                    'column_3_interval' => '/dolor sit amet',
                                    'column_3_button_text' => 'Lorem ipsum',
                                    'column_3_button_link' => '#',
                                    'column_4_icon' => 'fa-check',
                                    'column_4_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_4_price' => '40',
                                    'column_4_interval' => '/dolor sit amet',
                                    'column_4_button_text' => 'Lorem ipsum',
                                    'column_4_button_link' => '#',
                                    'column_5_icon' => 'fa-check',
                                    'column_5_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_5_price' => '40',
                                    'column_5_interval' => '/dolor sit amet',
                                    'column_5_button_text' => 'Lorem ipsum',
                                    'column_5_button_link' => '#'
                                ),
                                1 => array(
                                    'row_type' => 'price',
                                    'bot_border' => 'true',
                                    'service_label' => 'Lorem Ipsum',
                                    'service_icon' => 'fa-star',
                                    'column_1_icon' => 'fa-check',
                                    'column_1_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_1_price' => '40',
                                    'column_1_interval' => '/single use',
                                    'column_1_button_text' => 'Lorem ipsum',
                                    'column_1_button_link' => '#',
                                    'column_2_icon' => 'fa-check',
                                    'column_2_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_2_price' => '40',
                                    'column_2_interval' => '/dolor sit amet',
                                    'column_2_button_text' => 'Lorem ipsum',
                                    'column_2_button_link' => '#',
                                    'column_3_icon' => 'fa-check',
                                    'column_3_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_3_price' => '40',
                                    'column_3_interval' => '/dolor sit amet',
                                    'column_3_button_text' => 'Lorem ipsum',
                                    'column_3_button_link' => '#',
                                    'column_4_icon' => 'fa-check',
                                    'column_4_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_4_price' => '40',
                                    'column_4_interval' => '/dolor sit amet',
                                    'column_4_button_text' => 'Lorem ipsum',
                                    'column_4_button_link' => '#',
                                    'column_5_icon' => 'fa-check',
                                    'column_5_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_5_price' => '40',
                                    'column_5_interval' => '/dolor sit amet',
                                    'column_5_button_text' => 'Lorem ipsum',
                                    'column_5_button_link' => '#'
                                ),
                                2 => array(
                                    'row_type' => 'service',
                                    'bot_border' => 'true',
                                    'service_label' => 'Lorem Ipsum',
                                    'service_icon' => 'fa-star',
                                    'column_1_icon' => 'no-icon',
                                    'column_1_text' => '<br>Premium Support Included<br><br>',
                                    'column_1_price' => '40',
                                    'column_1_interval' => '/dolor sit amet',
                                    'column_1_button_text' => 'Lorem ipsum',
                                    'column_1_button_link' => '#',
                                    'column_2_icon' => 'fa-check',
                                    'column_2_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_2_price' => '40',
                                    'column_2_interval' => '/dolor sit amet',
                                    'column_2_button_text' => 'Lorem ipsum',
                                    'column_2_button_link' => '#',
                                    'column_3_icon' => 'fa-check',
                                    'column_3_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_3_price' => '40',
                                    'column_3_interval' => '/dolor sit amet',
                                    'column_3_button_text' => 'Lorem ipsum',
                                    'column_3_button_link' => '#',
                                    'column_4_icon' => 'fa-check',
                                    'column_4_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_4_price' => '40',
                                    'column_4_interval' => '/dolor sit amet',
                                    'column_4_button_text' => 'Lorem ipsum',
                                    'column_4_button_link' => '#',
                                    'column_5_icon' => 'fa-check',
                                    'column_5_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_5_price' => '40',
                                    'column_5_interval' => '/dolor sit amet',
                                    'column_5_button_text' => 'Lorem ipsum',
                                    'column_5_button_link' => '#'
                                ),
                                3 => array(
                                    'row_type' => 'service',
                                    'bot_border' => 'true',
                                    'service_label' => 'Lorem Ipsum',
                                    'service_icon' => 'fa-star',
                                    'column_1_icon' => 'no-icon',
                                    'column_1_text' => '<br>Free Modification of the Template<br><br>',
                                    'column_1_price' => '40',
                                    'column_1_interval' => '/dolor sit amet',
                                    'column_1_button_text' => 'Lorem ipsum',
                                    'column_1_button_link' => '#',
                                    'column_2_icon' => 'fa-check',
                                    'column_2_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_2_price' => '40',
                                    'column_2_interval' => '/dolor sit amet',
                                    'column_2_button_text' => 'Lorem ipsum',
                                    'column_2_button_link' => '#',
                                    'column_3_icon' => 'fa-check',
                                    'column_3_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_3_price' => '40',
                                    'column_3_interval' => '/dolor sit amet',
                                    'column_3_button_text' => 'Lorem ipsum',
                                    'column_3_button_link' => '#',
                                    'column_4_icon' => 'fa-check',
                                    'column_4_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_4_price' => '40',
                                    'column_4_interval' => '/dolor sit amet',
                                    'column_4_button_text' => 'Lorem ipsum',
                                    'column_4_button_link' => '#',
                                    'column_5_icon' => 'fa-check',
                                    'column_5_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_5_price' => '40',
                                    'column_5_interval' => '/dolor sit amet',
                                    'column_5_button_text' => 'Lorem ipsum',
                                    'column_5_button_link' => '#'
                                ),
                                4 => array(
                                    'row_type' => 'service',
                                    'bot_border' => 'true',
                                    'service_label' => 'Lorem Ipsum',
                                    'service_icon' => 'fa-star',
                                    'column_1_icon' => 'no-icon',
                                    'column_1_text' => '<br>3 Premium Plugins Included<br><br>',
                                    'column_1_price' => '40',
                                    'column_1_interval' => '/dolor sit amet',
                                    'column_1_button_text' => 'Lorem ipsum',
                                    'column_1_button_link' => '#',
                                    'column_2_icon' => 'fa-check',
                                    'column_2_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_2_price' => '40',
                                    'column_2_interval' => '/dolor sit amet',
                                    'column_2_button_text' => 'Lorem ipsum',
                                    'column_2_button_link' => '#',
                                    'column_3_icon' => 'fa-check',
                                    'column_3_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_3_price' => '40',
                                    'column_3_interval' => '/dolor sit amet',
                                    'column_3_button_text' => 'Lorem ipsum',
                                    'column_3_button_link' => '#',
                                    'column_4_icon' => 'fa-check',
                                    'column_4_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_4_price' => '40',
                                    'column_4_interval' => '/dolor sit amet',
                                    'column_4_button_text' => 'Lorem ipsum',
                                    'column_4_button_link' => '#',
                                    'column_5_icon' => 'fa-check',
                                    'column_5_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_5_price' => '40',
                                    'column_5_interval' => '/dolor sit amet',
                                    'column_5_button_text' => 'Lorem ipsum',
                                    'column_5_button_link' => '#'
                                ),
                                5 => array(
                                    'row_type' => 'service',
                                    'bot_border' => 'true',
                                    'service_label' => 'Lorem Ipsum',
                                    'service_icon' => 'fa-star',
                                    'column_1_icon' => 'no-icon',
                                    'column_1_text' => '<br>Drag & Drop Content Editing<br><br>',
                                    'column_1_price' => '40',
                                    'column_1_interval' => '/dolor sit amet',
                                    'column_1_button_text' => 'Lorem ipsum',
                                    'column_1_button_link' => '#',
                                    'column_2_icon' => 'fa-check',
                                    'column_2_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_2_price' => '40',
                                    'column_2_interval' => '/dolor sit amet',
                                    'column_2_button_text' => 'Lorem ipsum',
                                    'column_2_button_link' => '#',
                                    'column_3_icon' => 'fa-check',
                                    'column_3_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_3_price' => '40',
                                    'column_3_interval' => '/dolor sit amet',
                                    'column_3_button_text' => 'Lorem ipsum',
                                    'column_3_button_link' => '#',
                                    'column_4_icon' => 'fa-check',
                                    'column_4_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_4_price' => '40',
                                    'column_4_interval' => '/dolor sit amet',
                                    'column_4_button_text' => 'Lorem ipsum',
                                    'column_4_button_link' => '#',
                                    'column_5_icon' => 'fa-check',
                                    'column_5_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_5_price' => '40',
                                    'column_5_interval' => '/dolor sit amet',
                                    'column_5_button_text' => 'Lorem ipsum',
                                    'column_5_button_link' => '#'
                                ),
                                6 => array(
                                    'row_type' => 'service',
                                    'bot_border' => 'true',
                                    'service_label' => 'Lorem Ipsum',
                                    'service_icon' => 'fa-star',
                                    'column_1_icon' => 'no-icon',
                                    'column_1_text' => '<br>You Can Not Resell the Item<br><br>',
                                    'column_1_price' => '40',
                                    'column_1_interval' => '/dolor sit amet',
                                    'column_1_button_text' => 'Lorem ipsum',
                                    'column_1_button_link' => '#',
                                    'column_2_icon' => 'fa-check',
                                    'column_2_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_2_price' => '40',
                                    'column_2_interval' => '/dolor sit amet',
                                    'column_2_button_text' => 'Lorem ipsum',
                                    'column_2_button_link' => '#',
                                    'column_3_icon' => 'fa-check',
                                    'column_3_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_3_price' => '40',
                                    'column_3_interval' => '/dolor sit amet',
                                    'column_3_button_text' => 'Lorem ipsum',
                                    'column_3_button_link' => '#',
                                    'column_4_icon' => 'fa-check',
                                    'column_4_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_4_price' => '40',
                                    'column_4_interval' => '/dolor sit amet',
                                    'column_4_button_text' => 'Lorem ipsum',
                                    'column_4_button_link' => '#',
                                    'column_5_icon' => 'fa-check',
                                    'column_5_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_5_price' => '40',
                                    'column_5_interval' => '/dolor sit amet',
                                    'column_5_button_text' => 'Lorem ipsum',
                                    'column_5_button_link' => '#'
                                ),
                                7 => array(
                                    'row_type' => 'service',
                                    'bot_border' => 'false',
                                    'service_label' => 'Lorem Ipsum',
                                    'service_icon' => 'fa-star',
                                    'column_1_icon' => 'no-icon',
                                    'column_1_text' => '',
                                    'column_1_price' => '40',
                                    'column_1_interval' => '/dolor sit amet',
                                    'column_1_button_text' => 'Lorem ipsum',
                                    'column_1_button_link' => '#',
                                    'column_2_icon' => 'fa-check',
                                    'column_2_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_2_price' => '40',
                                    'column_2_interval' => '/dolor sit amet',
                                    'column_2_button_text' => 'Lorem ipsum',
                                    'column_2_button_link' => '#',
                                    'column_3_icon' => 'fa-check',
                                    'column_3_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_3_price' => '40',
                                    'column_3_interval' => '/dolor sit amet',
                                    'column_3_button_text' => 'Lorem ipsum',
                                    'column_3_button_link' => '#',
                                    'column_4_icon' => 'fa-check',
                                    'column_4_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_4_price' => '40',
                                    'column_4_interval' => '/dolor sit amet',
                                    'column_4_button_text' => 'Lorem ipsum',
                                    'column_4_button_link' => '#',
                                    'column_5_icon' => 'fa-check',
                                    'column_5_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_5_price' => '40',
                                    'column_5_interval' => '/dolor sit amet',
                                    'column_5_button_text' => 'Lorem ipsum',
                                    'column_5_button_link' => '#'
                                ),
                                8 => array(
                                    'row_type' => 'button',
                                    'bot_border' => 'false',
                                    'service_label' => 'Lorem Ipsum',
                                    'service_icon' => 'fa-star',
                                    'column_1_icon' => 'no-icon',
                                    'column_1_text' => '<br>3 Premium Plugins Included<br><br>',
                                    'column_1_price' => '40',
                                    'column_1_interval' => '/dolor sit amet',
                                    'column_1_button_text' => '<br><strong>Purchase Now</strong><br><br>',
                                    'column_1_button_link' => '#',
                                    'column_2_icon' => 'fa-check',
                                    'column_2_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_2_price' => '40',
                                    'column_2_interval' => '/dolor sit amet',
                                    'column_2_button_text' => 'Lorem ipsum',
                                    'column_2_button_link' => '#',
                                    'column_3_icon' => 'fa-check',
                                    'column_3_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_3_price' => '40',
                                    'column_3_interval' => '/dolor sit amet',
                                    'column_3_button_text' => 'Lorem ipsum',
                                    'column_3_button_link' => '#',
                                    'column_4_icon' => 'fa-check',
                                    'column_4_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_4_price' => '40',
                                    'column_4_interval' => '/dolor sit amet',
                                    'column_4_button_text' => 'Lorem ipsum',
                                    'column_4_button_link' => '#',
                                    'column_5_icon' => 'fa-check',
                                    'column_5_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_5_price' => '40',
                                    'column_5_interval' => '/dolor sit amet',
                                    'column_5_button_text' => 'Lorem ipsum',
                                    'column_5_button_link' => '#'
                                ),
                                9 => array(
                                    'row_type' => 'border',
                                    'bot_border' => 'true',
                                    'service_label' => 'Lorem Ipsum',
                                    'service_icon' => 'fa-star',
                                    'column_1_icon' => 'no-icon',
                                    'column_1_text' => '<br>3 Premium Plugins Included<br><br>',
                                    'column_1_price' => '40',
                                    'column_1_interval' => '/dolor sit amet',
                                    'column_1_button_text' => '<br><strong>Purchase Now</strong><br><br>',
                                    'column_1_button_link' => '#',
                                    'column_2_icon' => 'fa-check',
                                    'column_2_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_2_price' => '40',
                                    'column_2_interval' => '/dolor sit amet',
                                    'column_2_button_text' => 'Lorem ipsum',
                                    'column_2_button_link' => '#',
                                    'column_3_icon' => 'fa-check',
                                    'column_3_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_3_price' => '40',
                                    'column_3_interval' => '/dolor sit amet',
                                    'column_3_button_text' => 'Lorem ipsum',
                                    'column_3_button_link' => '#',
                                    'column_4_icon' => 'fa-check',
                                    'column_4_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_4_price' => '40',
                                    'column_4_interval' => '/dolor sit amet',
                                    'column_4_button_text' => 'Lorem ipsum',
                                    'column_4_button_link' => '#',
                                    'column_5_icon' => 'fa-check',
                                    'column_5_text' => '<br><STRONG>LOREM IPSUM</STRONG><BR><BR>',
                                    'column_5_price' => '40',
                                    'column_5_interval' => '/dolor sit amet',
                                    'column_5_button_text' => 'Lorem ipsum',
                                    'column_5_button_link' => '#'
                                )
                            ),
                            'order' => array(
                                0 => 0,
                                1 => 1,
                                2 => 2,
                                3 => 3,
                                4 => 4,
                                5 => 5,
                                6 => 6,
                                7 => 7,
                                8 => 8,
                                9 => 9
                            )
                        ),
                        'options' => array(
                            'row_type' => array(
                                'type' => 'select',
                                'label' => __('Row type', 'profit-builder'),
                                'std' => 'service',
                                'options' => array(
                                    'heading' => __('Heading', 'profit-builder'),
                                    'price' => __('Price', 'profit-builder'),
                                    'button' => __('Button', 'profit-builder'),
                                    'text-button' => __('Text with button', 'profit-builder'),
                                    'section' => __('Section', 'profit-builder'),
                                    'service' => __('Service', 'profit-builder'),
                                    'border' => __('Border', 'profit-builder')
                                )
                            ),
                            'bot_border' => array(
                                'type' => 'checkbox',
                                'label' => __('Bottom border', 'profit-builder'),
                                'std' => 'true'
                            ),
                            // sidebar
                            'service_label' => array(
                                'type' => 'input',
                                'label' => __('Service label', 'profit-builder'),
                                'std' => 'Lorem ipsum',
                                'hide_if' => array(
                                    'services_sidebar' => array('false'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'button', 'border')
                                    )
                                )
                            ),
                            'service_icon' => array(
                                'type' => 'icon',
                                'label' => __('Icon', 'profit-builder'),
                                'std' => 'fa-star',
                                'notNull' => false,
                                'hide_if' => array(
                                    'services_sidebar' => array('false'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'button', 'text-button', 'service', 'border')
                                    )
                                )
                            ),
                            // column 1
                            'column_1_icon' => array(
                                'type' => 'icon',
                                'label' => __('Column 1 icon', 'profit-builder'),
                                'std' => 'fa-check',
                                'notNull' => false,
                                'hide_if' => array(
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'button', 'text-button', 'section', 'border')
                                    )
                                )
                            ),
                            'column_1_text' => array(
                                'type' => 'input',
                                'label' => __('Column 1 text', 'profit-builder'),
                                'std' => 'Lorem ipstum',
                                'hide_if' => array(
                                    'sortable' => array(
                                        'row_type' => array('price', 'button', 'border', 'section')
                                    )
                                )
                            ),
                            'column_1_price' => array(
                                'type' => 'input',
                                'label' => __('Column 1 price', 'profit-builder'),
                                'std' => '42',
                                'hide_if' => array(
                                    'sortable' => array(
                                        'row_type' => array('heading', 'button', 'text-button', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_1_interval' => array(
                                'type' => 'input',
                                'label' => __('Column 1 interval', 'profit-builder'),
                                'std' => '/per month',
                                'hide_if' => array(
                                    'sortable' => array(
                                        'row_type' => array('heading', 'button', 'text-button', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_1_button_text' => array(
                                'type' => 'input',
                                'label' => __('Column 1 button text', 'profit-builder'),
                                'std' => 'Lorem ipsum',
                                'hide_if' => array(
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_1_button_link' => array(
                                'type' => 'input',
                                'label' => __('Column 1 button link', 'profit-builder'),
                                'std' => '#',
                                'hide_if' => array(
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            // column 2
                            'column_2_icon' => array(
                                'type' => 'icon',
                                'label' => __('Column 2 icon', 'profit-builder'),
                                'std' => 'fa-times',
                                'notNull' => false,
                                'hide_if' => array(
                                    'colnum' => array('1'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'button', 'text-button', 'section', 'border')
                                    )
                                )
                            ),
                            'column_2_text' => array(
                                'type' => 'input',
                                'label' => __('Column 2 text', 'profit-builder'),
                                'std' => 'Lorem ipstum',
                                'hide_if' => array(
                                    'colnum' => array('1'),
                                    'sortable' => array(
                                        'row_type' => array('price', 'button', 'border', 'section')
                                    )
                                )
                            ),
                            'column_2_price' => array(
                                'type' => 'input',
                                'label' => __('Column 2 price', 'profit-builder'),
                                'std' => '42',
                                'hide_if' => array(
                                    'colnum' => array('1'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'button', 'text-button', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_2_interval' => array(
                                'type' => 'input',
                                'label' => __('Column 2 interval', 'profit-builder'),
                                'std' => '/per month',
                                'hide_if' => array(
                                    'colnum' => array('1'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'button', 'text-button', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_2_button_text' => array(
                                'type' => 'input',
                                'label' => __('Column 2 button text', 'profit-builder'),
                                'std' => 'Lorem ipstum',
                                'hide_if' => array(
                                    'colnum' => array('1'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_2_button_link' => array(
                                'type' => 'input',
                                'label' => __('Column 2 button link', 'profit-builder'),
                                'std' => '#',
                                'hide_if' => array(
                                    'colnum' => array('1'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            // column 3
                            'column_3_icon' => array(
                                'type' => 'icon',
                                'label' => __('Column 3 icon', 'profit-builder'),
                                'std' => 'no-icon',
                                'notNull' => false,
                                'hide_if' => array(
                                    'colnum' => array('1', '2'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'button', 'text-button', 'section', 'border')
                                    )
                                )
                            ),
                            'column_3_text' => array(
                                'type' => 'input',
                                'label' => __('Column 3 text', 'profit-builder'),
                                'std' => 'Lorem ipstum',
                                'hide_if' => array(
                                    'colnum' => array('1', '2'),
                                    'sortable' => array(
                                        'row_type' => array('price', 'button', 'section', 'border')
                                    )
                                )
                            ),
                            'column_3_price' => array(
                                'type' => 'input',
                                'label' => __('Column 3 price', 'profit-builder'),
                                'std' => '42',
                                'hide_if' => array(
                                    'colnum' => array('1', '2'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'button', 'text-button', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_3_interval' => array(
                                'type' => 'input',
                                'label' => __('Column 3 interval', 'profit-builder'),
                                'std' => '/per month',
                                'hide_if' => array(
                                    'colnum' => array('1', '2'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'button', 'text-button', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_3_button_text' => array(
                                'type' => 'input',
                                'label' => __('Column 3 button text', 'profit-builder'),
                                'std' => 'Lorem ipstum',
                                'hide_if' => array(
                                    'colnum' => array('1', '2'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_3_button_link' => array(
                                'type' => 'input',
                                'label' => __('Column 3 button link', 'profit-builder'),
                                'std' => '#',
                                'hide_if' => array(
                                    'colnum' => array('1', '2'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            // column 4
                            'column_4_icon' => array(
                                'type' => 'icon',
                                'label' => __('Column 4 icon', 'profit-builder'),
                                'std' => 'no-icon',
                                'notNull' => false,
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'button', 'text-button', 'section', 'border')
                                    )
                                )
                            ),
                            'column_4_text' => array(
                                'type' => 'input',
                                'label' => __('Column 4 text', 'profit-builder'),
                                'std' => 'Lorem ipstum',
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3'),
                                    'sortable' => array(
                                        'row_type' => array('price', 'button', 'section', 'border')
                                    )
                                )
                            ),
                            'column_4_price' => array(
                                'type' => 'input',
                                'label' => __('Column 4 price', 'profit-builder'),
                                'std' => '42',
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'button', 'text-button', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_4_interval' => array(
                                'type' => 'input',
                                'label' => __('Column 4 interval', 'profit-builder'),
                                'std' => '/per month',
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'button', 'text-button', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_4_button_text' => array(
                                'type' => 'input',
                                'label' => __('Column 4 button text', 'profit-builder'),
                                'std' => 'Lorem ipstum',
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_4_button_link' => array(
                                'type' => 'input',
                                'label' => __('Column 4 button link', 'profit-builder'),
                                'std' => '#',
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            // column 5
                            'column_5_icon' => array(
                                'type' => 'icon',
                                'label' => __('Column 5 icon', 'profit-builder'),
                                'std' => 'no-icon',
                                'notNull' => false,
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3', '4'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'button', 'text-button', 'section', 'border')
                                    )
                                )
                            ),
                            'column_5_text' => array(
                                'type' => 'input',
                                'label' => __('Column 5 text', 'profit-builder'),
                                'std' => 'Lorem ipstum',
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3', '4'),
                                    'sortable' => array(
                                        'row_type' => array('price', 'button', 'section', 'border')
                                    )
                                )
                            ),
                            'column_5_price' => array(
                                'type' => 'input',
                                'label' => __('Column 5 price', 'profit-builder'),
                                'std' => '42',
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3', '4'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'button', 'text-button', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_5_interval' => array(
                                'type' => 'input',
                                'label' => __('Column 5 interval', 'profit-builder'),
                                'std' => '/per month',
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3', '4'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'button', 'text-button', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_5_button_text' => array(
                                'type' => 'input',
                                'label' => __('Column 5 button text', 'profit-builder'),
                                'std' => 'Lorem ipstum',
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3', '4'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'section', 'service', 'border')
                                    )
                                )
                            ),
                            'column_5_button_link' => array(
                                'type' => 'input',
                                'label' => __('Column 5 button link', 'profit-builder'),
                                'std' => '#',
                                'hide_if' => array(
                                    'colnum' => array('1', '2', '3', '4'),
                                    'sortable' => array(
                                        'row_type' => array('heading', 'price', 'section', 'service', 'border')
                                    )
                                )
                            )
                        )
                    ),
                    'currency' => array(
                        'type' => 'input',
                        'label' => __('Currency', 'profit-builder'),
                        'std' => '$'
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Text color', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Background color', 'profit-builder'),
                        'std' => $opts['light_back_color']
                    ),
                    'column_1_main_color' => array(
                        'type' => 'color',
                        'label' => __('Column 1 main color', 'profit-builder'),
                        'std' => '#445a68'
                    ),
                    'column_1_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Column 1 hover color', 'profit-builder'),
                        'std' => '#5b798c'
                    ),
                    'column_1_button_text_color' => array(
                        'type' => 'color',
                        'label' => __('Column 1 button text color', 'profit-builder'),
                        'std' => '#ffffff'
                    ),
                    'column_2_main_color' => array(
                        'type' => 'color',
                        'label' => __('Column 2 main color', 'profit-builder'),
                        'std' => '#ed4c3a',
                        'hide_if' => array(
                            'colnum' => array('1')
                        )
                    ),
                    'column_2_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Column 2 hover color', 'profit-builder'),
                        'std' => '#f17163',
                        'hide_if' => array(
                            'colnum' => array('1')
                        )
                    ),
                    'column_2_button_text_color' => array(
                        'type' => 'color',
                        'label' => __('Column 2 button text color', 'profit-builder'),
                        'std' => '#ffffff',
                        'hide_if' => array(
                            'colnum' => array('1')
                        )
                    ),
                    'column_3_main_color' => array(
                        'type' => 'color',
                        'label' => __('Column 3 main color', 'profit-builder'),
                        'std' => '#2b98d3',
                        'hide_if' => array(
                            'colnum' => array('1', '2')
                        )
                    ),
                    'column_3_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Column 3 hover color', 'profit-builder'),
                        'std' => '#54acdc',
                        'hide_if' => array(
                            'colnum' => array('1', '2')
                        )
                    ),
                    'column_3_button_text_color' => array(
                        'type' => 'color',
                        'label' => __('Column 3 button text color', 'profit-builder'),
                        'std' => '#ffffff',
                        'hide_if' => array(
                            'colnum' => array('1', '2')
                        )
                    ),
                    'column_4_main_color' => array(
                        'type' => 'color',
                        'label' => __('Column 4 main color', 'profit-builder'),
                        'std' => '#16a085',
                        'hide_if' => array(
                            'colnum' => array('1', '2', '3')
                        )
                    ),
                    'column_4_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Column 4 hover color', 'profit-builder'),
                        'std' => '#1abc9c',
                        'hide_if' => array(
                            'colnum' => array('1', '2', '3')
                        )
                    ),
                    'column_4_button_text_color' => array(
                        'type' => 'color',
                        'label' => __('Column 4 button text color', 'profit-builder'),
                        'std' => '#ffffff',
                        'hide_if' => array(
                            'colnum' => array('1', '2', '3')
                        )
                    ),
                    'column_5_main_color' => array(
                        'type' => 'color',
                        'label' => __('Column 5 main color', 'profit-builder'),
                        'std' => '#f39c12',
                        'hide_if' => array(
                            'colnum' => array('1', '2', '3', '4')
                        )
                    ),
                    'column_5_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Column 5 hover color', 'profit-builder'),
                        'std' => '#f1c40f',
                        'hide_if' => array(
                            'colnum' => array('1', '2', '3', '4')
                        )
                    ),
                    'column_5_button_text_color' => array(
                        'type' => 'color',
                        'label' => __('Column 5 button text color', 'profit-builder'),
                        'std' => '#ffffff',
                        'hide_if' => array(
                            'colnum' => array('1', '2', '3', '4')
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* CODE */
/* -------------------------------------------------------------------------------- */
$code = array(
    'code' => array(
        'type' => 'draggable',
        'text' => 'Code',
        'icon' => $this->url . 'images/icons/code.png',
        'function' => 'pbuilder_code',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_code' => array(
                'type' => 'collapsible',
                'open' => 'true',
                'label' => __('Code', 'profit-builder'),
                'options' => array(
                    'content' => array(
                        'type' => 'textarea',
                        'std' => 'function Start(){// do something}'
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* ALERT */
/* -------------------------------------------------------------------------------- */
$alert = array(
    'alert' => array(
        'type' => 'draggable',
        'text' => __('Alert box', 'profit-builder'),
        'icon' => $this->url . 'images/icons/alert-box.png',
        'function' => 'pbuilder_alert',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_alert_box' => array(
                'type' => 'collapsible',
                'label' => __('Alert Box', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'style' => array(
                        'type' => 'select',
                        'label' => __('Style:', 'profit-builder'),
                        'std' => 'clean',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'clean' => __('Clean', 'profit-builder'),
                            'squared' => __('Squared', 'profit-builder'),
                            'rounded' => __('Rounded', 'profit-builder')
                        )
                    ),
                    'type' => array(
                        'type' => 'select',
                        'label' => __('Type:', 'profit-builder'),
                        'std' => 'info',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'options' => array(
                            'info' => __('Info', 'profit-builder'),
                            'success' => __('Success', 'profit-builder'),
                            'notice' => __('Notice', 'profit-builder'),
                            'warning' => __('Warning', 'profit-builder'),
                            'custom' => __('Custom', 'profit-builder')
                        )
                    ),
                    'text' => array(
                        'type' => 'textarea',
                        'std' => __('This is an alert', 'profit-builder')
                    )
                )
            ),
            'group_custom' => array(
                'type' => 'collapsible',
                'label' => __('Custom', 'profit-builder'),
                'desc' => 'select "Custom" type',
                'open' => 'true',
                'options' => array(
                    'icon' => array(
                        'type' => 'icon',
                        'label' => __('Icon Type:', 'profit-builder'),
                        'std' => 'fa-warning',
                        'hide_if' => array(
                            'type' => array('info', 'success', 'notice', 'warning')
                        )
                    ),
                    'main_color' => array(
                        'type' => 'color',
                        'label' => __('Main:', 'profit-builder'),
                        'std' => $opts['main_color'],
                        'hide_if' => array(
                            'type' => array('info', 'success', 'notice', 'warning')
                        )
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => $opts['text_color'],
                        'hide_if' => array(
                            'type' => array('info', 'success', 'notice', 'warning')
                        )
                    ),
                    'icon_color' => array(
                        'type' => 'color',
                        'label' => __('Icon:', 'profit-builder'),
                        'std' => $opts['main_color'],
                        'hide_if' => array(
                            'type' => array('info', 'success', 'notice', 'warning')
                        )
                    ),
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => '',
                        'hide_if' => array(
                            'type' => array('info', 'success', 'notice', 'warning')
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* MENU */
/* -------------------------------------------------------------------------------- */
$menu = array(
    'menu' => array(
        'type' => 'draggable',
        'text' => __('Nav menu', 'profit-builder'),
        'icon' => $this->url . 'images/icons/nav-menu.png',
        'function' => 'pbuilder_nav_menu',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'wp_menu' => array(
                        'type' => 'select',
                        'label' => __('Menu:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'desc' => __('Select wordpress nav menu that you want to display', 'profit-builder'),
                        'options' => $pbuilder_menus,
                        'std' => $pbuilder_menu_std
                    ),
                    'type' => array(
                        'type' => 'select',
                        'label' => __('Type:', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'std' => 'horizontal-clean',
                        'options' => array(
                            'horizontal-clean' => __('Clean horizontal menu', 'profit-builder'),
                            'horizontal-squared' => __('Squared horizontal menu', 'profit-builder'),
                            'horizontal-rounded' => __('Rounded horizontal menu', 'profit-builder'),
                            'vertical-clean' => __('Clean vertical menu', 'profit-builder'),
                            'vertical-squared' => __('Squared vertical menu', 'profit-builder'),
                            'vertical-rounded' => __('Rounded vertical menu', 'profit-builder'),
                            'dropdown-clean' => __('Clean dropdown menu', 'profit-builder'),
                            'dropdown-squared' => __('Squared dropdown menu', 'profit-builder'),
                            'dropdown-rounded' => __('Rounded dropdown menu', 'profit-builder')
                        )
                    ),
                    'menu_title' => array(
                        'type' => 'input',
                        'label' => __('Title:', 'profit-builder'),
                        'std' => __('Nav menu', 'profit-builder'),
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'type' => array('horizontal-clean', 'horizontal-squared', 'horizontal-rounded')
                        )
                    )
                )
            ),
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Text', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'hover_text_color' => array(
                        'type' => 'color',
                        'label' => __('Hover:', 'profit-builder'),
                        'std' => $opts['main_back_text_color']
                    )
                )
            ),
            'group_sub_menu' => array(
                'type' => 'collapsible',
                'label' => __('Sub-menu', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'sub_text_color' => array(
                        'type' => 'color',
                        'label' => __('Text', 'profit-builder'),
                        'std' => $opts['text_color']
                    ),
                    'separator_color' => array(
                        'type' => 'color',
                        'label' => __('Separator:', 'profit-builder'),
                        'std' => $opts['light_border_color'],
                        'hide_if' => array(
                            'type' => array('horizontal-squared', 'horizontal-rounded', 'dropdown-squared', 'dropdown-rounded')
                        )
                    ),
                    'sub_back_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => $opts['light_back_color']
                    ),
                )
            ),
            'group_advanced' => array(
                'type' => 'collapsible',
                'label' => __('Advanced', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Background:', 'profit-builder'),
                        'std' => ''
                    ),
                    'hover_color' => array(
                        'type' => 'color',
                        'label' => __('Hover:', 'profit-builder'),
                        'std' => $opts['main_color']
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* ICON MENU */
/* -------------------------------------------------------------------------------- */
$icon_menu = array(
    'icon_menu' => array(
        'type' => 'draggable',
        'text' => __('Icon menu', 'profit-builder'),
        'icon' => $this->url . 'images/icons/icon-menu.png',
        'function' => 'pbuilder_icon_menu',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'align' => array(
                        'type' => 'select',
                        'std' => 'left',
                        'label' => __('Icon alignment', 'profit-builder'),
                        'options' => array(
                            'left' => __('Left', 'profit-builder'),
                            'right' => __('Right', 'profit-builder'),
                            'center' => __('Center', 'profit-builder')
                        )
                    ),
                    'icon_padding' => array(
                        'type' => 'number',
                        'std' => 5,
                        'label' => __('Icon spacing:', 'profit-builder'),
                        'unit' => 'px',
                        'desc' => __('Spacing between menu icons', 'profit-builder'),
                    ),
                    'icon_size' => array(
                        'type' => 'number',
                        'std' => 24,
                        'label' => __('Size:', 'profit-builder'),
                        'half_column' => 'true',
                        'unit' => 'px'
                    ),
                    'round' => array(
                        'type' => 'checkbox',
                        'std' => 'false',
                        'half_column' => 'true',
                        'label' => __('Round edges', 'profit-builder')
                    )
                )
            ),
            'group_colors' => array(
                'type' => 'collapsible',
                'label' => __('Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'icon_color' => array(
                        'type' => 'color',
                        'std' => $opts['title_color'],
                        'label' => __('Icon:', 'profit-builder')
                    ),
                    'icon_hover_color' => array(
                        'type' => 'color',
                        'std' => $opts['main_color'],
                        'label' => __('Hover:', 'profit-builder')
                    ),
                    'back_color' => array(
                        'type' => 'color',
                        'std' => '',
                        'label' => __('Background:', 'profit-builder')
                    ),
                    'back_hover_color' => array(
                        'type' => 'color',
                        'std' => '',
                        'label' => __('Hover:', 'profit-builder')
                    )
                )
            ),
            'group_icons' => array(
                'type' => 'collapsible',
                'label' => __('Icons', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'sortable' => array(
                        'type' => 'sortable',
                        'label' => __('Icons', 'profit-builder'),
                        'item_name' => 'icon',
                        'label_width' => 0,
                        'control_width' => 1,
                        'std' => array(
                            'items' => array(
                                0 => array(
                                    'icon' => 'fa-gears',
                                    'url' => '#',
                                    'link_type' => 'standard'
                                ),
                                1 => array(
                                    'icon' => 'fa-key',
                                    'url' => '#',
                                    'link_type' => 'standard'
                                ),
                                2 => array(
                                    'icon' => 'fa-group',
                                    'url' => '#',
                                    'link_type' => 'standard'
                                ),
                            ),
                            'order' => array(
                                0 => 0,
                                1 => 1,
                                2 => 2
                            )
                        ),
                        'options' => array(
                            'icon' => array(
                                'type' => 'icon',
                                'label' => __('Icon:', 'profit-builder'),
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'std' => 'fa-check-square-o'
                            ),
                            'url' => array(
                                'type' => 'input',
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'label' => __('Link:', 'profit-builder')
                            ),
                            'link_type' => array(
                                'type' => 'select',
                                'label' => __('Type:', 'profit-builder'),
                                'label_width' => 0.25,
                                'control_width' => 0.75,
                                'std' => 'standard',
                                'desc' => __('open in new tab or lightbox', 'profit-builder'),
                                'options' => array(
                                    'standard' => __('Standard', 'profit-builder'),
                                    'new-tab' => __('Open in new tab', 'profit-builder'),
                                    'lightbox-image' => __('Lightbox image', 'profit-builder'),
                                    'lightbox-iframe' => __('Lightbox iframe', 'profit-builder')
                                )
                            ),
                            'iframe_width' => array(
                                'type' => 'number',
                                'label' => __('Width:', 'profit-builder'),
                                'std' => 600,
                                'min' => 0,
                                'max' => 1200,
                                'unit' => 'px',
                                'half_column' => 'true',
                                'hide_if' => array(
                                    'sortable' => array(
                                        'link_type' => array('standard', 'new-tab', 'lightbx-image')
                                    )
                                )
                            ),
                            'iframe_height' => array(
                                'type' => 'number',
                                'label' => __('Height:', 'profit-builder'),
                                'std' => 300,
                                'min' => 0,
                                'max' => 1200,
                                'unit' => 'px',
                                'half_column' => 'true',
                                'hide_if' => array(
                                    'sortable' => array(
                                        'link_type' => array('standard', 'new-tab', 'lightbx-image')
                                    )
                                )
                            )
                        )
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* SIDEBAR */
/* -------------------------------------------------------------------------------- */
$sidebar = array(
    'sidebar' => array(
        'type' => 'draggable',
        'text' => __('Sidebar', 'profit-builder'),
        'icon' => $this->url . 'images/icons/sidebar.png',
        'function' => 'pbuilder_sidebar',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_sidebar' => array(
                'type' => 'collapsible',
                'label' => __('Sidebar', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'name' => array(
                        'type' => 'select',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'label' => __('WP Sidebar:', 'profit-builder'),
                        'desc' => __('Select wordpress sidebar that you want to display', 'profit-builder'),
                        'options' => '$pbuilder_sidebars',
                        'std' => '$pbuilder_sidebar_std'
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* SEARCH */
/* -------------------------------------------------------------------------------- */
$search = array(
    'search' => array(
        'type' => 'draggable',
        'text' => __('Search box', 'profit-builder'),
        'icon' => $this->url . 'images/icons/search-box.png',
        'function' => 'pbuilder_search',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_basic' => array(
                'type' => 'collapsible',
                'label' => __('Basic', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'text' => array(
                        'type' => 'input',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'label' => __('Text', 'profit-builder'),
                        'std' => __('Search', 'profit-builder')
                    ),
                    'round' => array(
                        'type' => 'checkbox',
                        'label' => __('Round edges', 'profit-builder'),
                        'std' => 'false'
                    )
                )
            ),
            'group_text_colors' => array(
                'type' => 'collapsible',
                'label' => __('Text Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'text_color' => array(
                        'type' => 'color',
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'label' => __('Main:', 'profit-builder'),
                        'std' => $opts['title_color']
                    ),
                    'text_focus_color' => array(
                        'type' => 'color',
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'label' => __('Focus:', 'profit-builder'),
                        'std' => $opts['dark_border_color']
                    )
                )
            ),
            'group_border_colors' => array(
                'type' => 'collapsible',
                'label' => __('Border Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'border_color' => array(
                        'type' => 'color',
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'label' => __('Main:', 'profit-builder'),
                        'std' => $opts['light_border_color']
                    ),
                    'border_focus_color' => array(
                        'type' => 'color',
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'label' => __('Focus:', 'profit-builder'),
                        'std' => $opts['dark_border_color']
                    )
                )
            ),
            'group_background_colors' => array(
                'type' => 'collapsible',
                'label' => __('Background Colors', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'back_color' => array(
                        'type' => 'color',
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'label' => __('Main:', 'profit-builder'),
                        'std' => ''
                    ),
                    'back_focus_color' => array(
                        'type' => 'color',
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'label' => __('Focus:', 'profit-builder'),
                        'std' => ''
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* SEPARATOR */
/* -------------------------------------------------------------------------------- */
$separator = array(
    'separator' => array(
        'type' => 'draggable',
        'text' => __('Separator', 'profit-builder'),
        'icon' => $this->url . 'images/icons/separator.png',
        'function' => 'pbuilder_separator',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_separator' => array(
                'type' => 'collapsible',
                'label' => __('Separator', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'style' => array(
                        'type' => 'select',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'label' => __('Type:', 'profit-builder'),
                        'options' => array(
                            'solid' => __('Solid', 'profit-builder'),
                            'dashed' => __('Dashed', 'profit-builder'),
                            'dotted' => __('Dotted', 'profit-builder'),
                            'double' => __('Double', 'profit-builder')
                        )
                    ),
                    'width' => array(
                        'type' => 'number',
                        'label' => __('Width:', 'profit-builder'),
                        'std' => 1,
                        'max' => 20,
                        'half_column' => 'true',
                        'unit' => 'px',
                    ),
                    'color' => array(
                        'type' => 'color',
                        'label_width' => 0.25,
                        'control_width' => 0.5,
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['main_color']
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* READ MORE */
/* -------------------------------------------------------------------------------- */
$read_more = array(
    'read_more' => array(
        'type' => 'draggable',
        'text' => __('More tag', 'profit-builder'),
        'desc' => __('This module has no options', 'profit-builder'),
        'icon' => $this->url . 'images/icons/more-tags.png',
        'function' => 'pbuilder_more',
        'group' => __('Basic', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Text', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'content' => array(
                        'type' => 'textarea',
                        'label' => __('Content:', 'profit-builder'),
                        'desc' => 'You can use text.',
                        'std' => 'Read More'
                    ), 'read_more_url' => array(
                        'type' => 'input',
                        'label' => __('URL:', 'profit-builder'),
                        'desc' => __('ex. http://yoursite.com/post.php', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['text_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.50
                    ),
                    'text_hover_color' => array(
                        'type' => 'color',
                        'label' => __('Hover Color:', 'profit-builder'),
                        'std' => $opts['text_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.50
                    ),
                    'custom_font_size' => array(
                        'type' => 'checkbox',
                        'label' => __('Use custom font size', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 12,
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'line_height' => array(
                        'type' => 'number',
                        'label' => __('Line:', 'profit-builder'),
                        'std' => 'default', //'std' => 15,
                        'default' => 'default',
                        'half_column' => 'true',
                        'unit' => 'px',
                        'hide_if' => array(
                            'custom_font_size' => array('false')
                        )
                    ),
                    'align' => array(
                        'type' => 'select',
                        'label' => __('Text alignment:', 'profit-builder'),
                        'options' => array(
                            'center' => __('Center', 'profit-builder'),
                            'left' => __('Left', 'profit-builder'),
                            'right' => __('Right', 'profit-builder')
                        ),
                        'std' => 'center'
                    ),
                ),
            ),
            'group_icon' => array(
                'type' => 'collapsible',
                'label' => __('Icon', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'icon' => array(
                        'type' => 'icon',
                        'label' => __('Icon:', 'profit-builder'),
                        'notNull' => false,
                        'std' => 'no-icon',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'icon_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => $opts['main_color'],
                        'label_width' => 0.25,
                        'control_width' => 0.50
                    ),
                    'icon_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 18,
                        'unit' => 'px',
                        'half_column' => 'true'
                    )
                )
            ),
            'group_button_font_style' => array(
                'type' => 'collapsible',
                'label' => __('Font Style', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'google_font' => array(
                        'type' => 'select',
                        'class' => 'pbuilder_font_select',
                        'search' => 'true',
                        'std' => 'default',
                        'label' => __('Font family', 'profit-builder'),
                        'options' => $pbuilder_google_font_names
                    ),
                    'google_font_style' => array(
                        'name' => 'h1_font_style',
                        'type' => 'select',
                        'std' => 'default',
                        'label' => __('Font style', 'profit-builder'),
                        'options' => array('default' => 'Default'),
                        'hide_if' => array(
                            'google_font' => array('default')
                        )
                    ),
                )
            ),
                ),
                /* $classControl,
                  array(
                  'group_general' => array(
                  'type' => 'collapsible',
                  'label' => __('General','profit-builder'),
                  'options' => array(
                  'bot_margin' => array(
                  'type' => 'number',
                  'label' => __('Bottom margin:','profit-builder'),
                  'std' => $opts['bottom_margin'],
                  'unit' => 'px'
                  ),
                  'top_margin' => array(
                  'type' => 'number',
                  'label' => __('Top margin:','profit-builder'),
                  'std' => $opts['top_margin'],
                  'unit' => 'px'
                  )
                  )
                  )
                  ), */ $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* GOOGLE MAP */
/* -------------------------------------------------------------------------------- */
$gmap = array(
    'gmap' => array(
        'type' => 'draggable',
        'text' => __('Google Map', 'profit-builder'),
        'icon' => $this->url . 'images/icons/gmap.png',
        'function' => 'pbuilder_gmap',
        'group' => __('Advanced', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Google Map', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'address' => array(
                        'type' => 'input',
                        'label' => __('Address:', 'profit-builder'),
                        'label_width' => 0.35,
                        'control_width' => 0.60,
                        'std' => 'Disneyland Resort, Disneyland Drive, Anaheim, CA',
                    ),
                    'iframe_width' => array(
                        'type' => 'number',
                        'label' => __('Width:', 'profit-builder'),
                        'std' => 600,
                        'min' => 0,
                        'half_column' => 'true',
                        'max' => 1200,
                        'unit' => 'px',
                        'hide_if' => array(
                            'fullwidth' => array('true')
                        )
                    ),
                    'iframe_height' => array(
                        'type' => 'number',
                        'label' => __('Height:', 'profit-builder'),
                        'std' => 600,
                        'min' => 0,
                        'half_column' => 'true',
                        'max' => 10000,
                        'unit' => 'px',
                        'hide_if' => array(
                            'fullheight' => array('true')
                        )
                    ),
                    'fullwidth' => array(
                        'type' => 'checkbox',
                        'label' => __('Full Width', 'profit-builder'),
                        'std' => 'true',
                        'half_column' => 'true',
                    ),

                ),
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )

                ), $animationControl
        )
    )
);
/* -------------------------------------------------------------------------------- */
/* Pregressbar */
/* -------------------------------------------------------------------------------- */
$progressbar = array(
    'progressbar' => array(
        'type' => 'draggable',
        'text' => __('Progress Bar Style', 'profit-builder'),
        'icon' => $this->url . 'images/icons/progress-bar.png',
        'function' => 'pbuilder_progressbar',
        'group' => __('Charts, Bars, Counters', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Progress Bar', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'pbar_style' => array(
                        'type' => 'select',
                        'label' => __('Style:', 'profit-builder'),
                        'std' => 'meter',
                        'options' => array(
                            'meter' => "Green",
                            'orange' => "Orange",
                            'red' => "Red",
                            'custom' => "Custom",
                        ),
                    ),
                    'pbar_color1' => array(
                        'type' => 'color',
                        'label' => __('Bar Color 1:', 'profit-builder'),
                        'std' => '#a9a9ff',
                        'hide_if' => array(
                            'pbar_style' => array('meter', 'orange', 'red')
                        ),
                    ),
                    'pbar_color2' => array(
                        'type' => 'color',
                        'label' => __('Bar Color 2:', 'profit-builder'),
                        'std' => '#0000d3',
                        'hide_if' => array(
                            'pbar_style' => array('meter', 'orange', 'red')
                        ),
                    ),
                    'pbar_animate' => array(
                        'type' => 'checkbox',
                        'label' => __('Animated Bar', 'profit-builder'),
                        'std' => 'true'
                    ),
                    'pbar_size' => array(
                        'type' => 'number',
                        'label' => __('Percent Complete', 'profit-builder'),
                        'std' => "50%",
                        'unit' => '%',
                        'min' => 0,
                        'max' => 100,
                    ),
                    'pbar_padding' => array(
                        'type' => 'number',
                        'label' => __('Padding', 'profit-builder'),
                        'std' => "10px",
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 100,
                    ),
                    'pbar_height' => array(
                        'type' => 'number',
                        'label' => __('Bar Height', 'profit-builder'),
                        'std' => "40px",
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 100,
                    ),
                    'pbar_width' => array(
                        'type' => 'number',
                        'label' => __('Bar Width', 'profit-builder'),
                        'std' => "96%",
                        'unit' => '%',
                        'min' => 0,
                        'max' => 100,
                    ),
                    'pbar_transparent' => array(
                        'type' => 'checkbox',
                        'label' => __('Transparent BG', 'profit-builder'),
                        'std' => 'false'
                    ),
                    'pbar_bg' => array(
                        'type' => 'color',
                        'label' => __('Background Color:', 'profit-builder'),
                        'std' => '#555555',
                    ),
                ),
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl
        )
    )
);
//------------------------------------------- Overlay ------------------------------------------------
/*
 * GoToWebinar integration
 * Code Added By Asim Ashraf - DevBatch
 * Date: 03-04-2015
 */
global $wpdb;
$table_name = $wpdb->prefix . 'profit_builder_extensions';
$extension = $wpdb->get_results('SELECT name FROM ' . $table_name . ' where name = "profit_builder_instant_gotowebinar" ', ARRAY_A);
$group_gotowebinar = array();
$imscpbiw_access_response = $this->options(" WHERE name = 'imscpbiw_access_response'");
$imscpbiw_access_response = json_decode(@$imscpbiw_access_response[0]->value);
if (!empty($extension[0]['name']) && !empty($imscpbiw_access_response->access_token) && class_exists("Curl")) {

    $Curl = new Curl($imscpbiw_access_response->access_token);
    $GetWebinarUrl = "https://api.citrixonline.com/G2W/rest/organizers/" . $imscpbiw_access_response->organizer_key . "/upcomingWebinars";
    $response = $Curl->Get($GetWebinarUrl);
    $jsonDecodeRs = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
    $fwebinars['select'] = "Please Select";
    if (!empty($imscpbiw_access_response->access_token)) {
        foreach ($jsonDecodeRs as $webinars) {
            if (!empty($webinars['organizerKey'])) {
                $fwebinars[$webinars['organizerKey'] . "," . $webinars['webinarKey']] = $webinars['subject'];
            }
        }

        $group_gotowebinar = array(
            'type' => 'collapsible',
            'label' => __('Integrate GoToWebinar', 'profit-builder'),
            'open' => 'false',
            'options' => array(
                'gotowebinarenable' => array(
                    'type' => 'checkbox',
                    'label' => 'Enable GoToWebinar',
                    'std' => 'false',
                    'half_column' => 'false',
                    'desc' => __('Enable GoToWebinar', 'profit-builder'),
                ),
                'gotowebinarshowbar' => array(
                    'type' => 'checkbox',
                    'label' => 'Enable Percentage',
                    'std' => 'false',
                    'half_column' => 'false',
                    'desc' => __('Enable Percentage', 'profit-builder'),
                    'hide_if' => array(
                        'gotowebinarenable' => array('false'),
                    )
                ),
                'gotowebinarurl' => array(
                    'type' => 'input',
                    'label' => 'Redirect Url',
                    'std' => '',
                    'half_column' => 'false',
                    'desc' => __('Redirect url after registration', 'profit-builder'),
                    'hide_if' => array(
                        'gotowebinarenable' => array('false'),
                    )
                ),
                'upcommingwebinar' => array(
                    'type' => 'select',
                    'label' => __('Select Webinar:', 'profit-builder'),
                    'std' => '',
                    'options' => $fwebinars,
                    'hide_if' => array(
                        'gotowebinarenable' => array('false'),
                    )
                ),
//                'customfieldsdiv' => array(
//                    'type' => 'div',
//                    'id' => 'customfieldsdiv',
//                ),
            )
        );
    }
}
$overlay = array(
    'overlay' => array(
        'type' => 'draggable',
        'text' => __('Overlay', 'profit-builder'),
        'icon' => '',
        'function' => 'pbuilder_overlay',
        'group' => __('Advanced', 'frontent-builder'),
        'options' => array_merge(
                array(
            'group_form' => array(
                'type' => 'collapsible',
                'label' => __('Form HTML2', 'profit-builder'),
                'open' => 'true',
                'options' => array(
                    'formcode' => array(
                        'type' => 'textarea',
                        'label' => __('Form Code', 'profit-builder'),
                        'std' => '',
//                        'hide_if' => array(
//                            'gotowebinarenable' => array('true'),
//                        )
                    ),
                    'formurl' => array(
                        'type' => 'input',
                        'label' => __('Form URL:', 'profit-builder'),
                        'desc' => __('ex. http://yoursite.com/form.php', 'profit-builder'),
                        'std' => '',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
//                        'hide_if' => array(
//                            'gotowebinarenable' => array('true'),
//                        )
                    ),
                    'formmethod' => array(
                        'type' => 'select',
                        'label' => __('Method:', 'profit-builder'),
                        'std' => 'POST',
                        'options' => array(
                            'GET' => __('GET', 'profit-builder'),
                            'POST' => __('POST', 'profit-builder'),
                        ),
//                        'hide_if' => array(
//                            'gotowebinarenable' => array('true'),
//                        )
                    ),
                    'newwindow' => array(
                        'type' => 'checkbox',
                        'label' => 'New Window',
                        'std' => 'false',
                        'half_column' => 'true',
                        'desc' => __('Open form in new window...', 'profit-builder'),
//                        'hide_if' => array(
//                            'gotowebinarenable' => array('true'),
//                        )
                    ),
                    'alertmsg' => array(
                        'type' => 'checkbox',
                        'label' => 'Exit Popup',
                        'std' => 'false',
                        'half_column' => 'true',
                        'desc' => __('Overlay form close without submit alert message will show if is checked...', 'profit-builder'),
                    ),
                )
            ),
            'group_gotowebinar' => $group_gotowebinar,
            'base_btn' => array(
                'type' => 'collapsible',
                'label' => __('Overlay Button', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'base_btype' => array(
                        'type' => 'select',
                        'label' => __('Button Type:', 'profit-builder'),
                        'std' => 'base_custom',
                        'options' => array(
                            'base_custom' => __('Custom Text', 'profit-builder'),
                            'base_css3' => __('CSS3', 'profit-builder'),
                            'base_predone' => __('Pre-Done', 'profit-builder'),
                        )
                    ),
                    'base_predone' => array(
                        'type' => 'select',
                        'label' => __('Base_button', 'profit-builder'),
                        'std' => 'addtocart',
                        'options' => $files,
                        'label_width' => 0.25,
                        'control_width' => 0.75/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'hide_if' => array(
                      'base_btype' => array('base_custom', 'base_css3'),
                      ) */
                    ),
                    'base_pcolor' => array(
                        'type' => 'select',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => 'gold',
                        'options' => array(
                            'gold' => __('Gold', 'profit-builder'),
                            'black' => __('Black', 'profit-builder'),
                            'blue' => __('Blue', 'profit-builder'),
                            'red' => __('Red', 'profit-builder'),
                            'white' => __('White', 'profit-builder'),
                        )/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'hide_if' => array(
                      'base_btype' => array('base_custom', 'base_css3'),
                      ) */
                    ),
                    'base_css3_btn' => array(
                        'type' => 'select',
                        'label' => __('Css3 Button Style:', 'profit-builder'),
                        'std' => 'style1',
                        'options' => $styles,
                        'label_width' => 0.25,
                        'control_width' => 0.75/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'hide_if' => array(
                      'base_btype' => array('base_custom', 'base_predone'),
                      ) */
                    ),
                    'base_text_btn' => array(
                        'type' => 'input',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => __('Get Instant Access', 'profit-builder')/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'hide_if' => array(
                      'base_btype' => array('base_predone'),
                      ) */
                    ),
                    'base_font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        /*
                         * Asim Ashraf - DevBatch
                         * std change 34 to 30;
                         * Edit Start
                         */
                        'std' => 20,
                        /*
                         * Edit End
                         */
                        'unit' => 'px'/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'hide_if' => array(
                      'base_btype' => array('base_predone'),
                      ) */
                    ),
                    'base_text_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => '#ffffff'/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'hide_if' => array(
                      'base_btype' => array('base_predone', 'base_css3'),
                      ) */
                    ),
                    'base_hover_text_color' => array(
                        'type' => 'color',
                        'label' => __('Hover:', 'profit-builder'),
                        'std' => '#ffffff'/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'hide_if' => array(
                      'base_btype' => array('base_predone', 'base_css3'),
                      ) */
                    ),
                    'base_back_color' => array(
                        'type' => 'color',
                        'label' => __('Background Color:', 'profit-builder'),
                        'std' => '#ff6600'/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'hide_if' => array(
                      'base_btype' => array('base_image','base_predone', 'base_css3'),
                      ) */
                    ),
                    'base_hover_back_color' => array(
                        'type' => 'color',
                        'label' => __('Background Hover:', 'profit-builder'),
                        'std' => '#ff9900'/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'hide_if' => array(
                      'base_btype' => array('base_predone', 'base_css3'),
                      ) */
                    ),
                )
            ),
            'group_form_fields' => array(
                'type' => 'collapsible',
                'label' => __('Form Fields', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'emailfield' => array(
                        //'type' => 'input',
                        'type' => 'select',
                        'label' => __('Email Field:', 'profit-builder'),
                        'desc' => __('Form input for email... Usually \'email\'', 'profit-builder'),
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 18 to 20
                         * Edit Start
                         */
                        'std' => 'email',
                        'options' => array(
                            'email' => __('email', 'profit-builder'),
                            'name' => __('name', 'profit-builder'),
                        ),
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 18 to 20
                         * Edit END
                         */
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'emailimage' => array(
                        'type' => 'image',
                        'label' => __('Email Image:', 'profit-builder'),
                        'std' => IMSCPB_URL . '/images/icons/email.png',
                        'label_width' => 0.5,
                        'control_width' => 0.5/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'hide_if' => array(
                      'fieldbg' => array('false'),
                      ) */
                    ),
                    'emailvalue' => array(
                        'type' => 'input',
                        'label' => __('Email Value:', 'profit-builder'),
                        'desc' => __('Default Value for Email Field', 'profit-builder'),
                        'std' => 'Enter your email...',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                    'emailerror' => array(
                        'type' => 'input',
                        'label' => __('Error Value:', 'profit-builder'),
                        'std' => 'Please enter an email',
                    ),
                    'namefield' => array(
                        //'type' => 'input',
                        'type' => 'select',
                        'label' => __('Name Field:', 'profit-builder'),
                        'desc' => __('Form input for name... Usually \'name\'', 'profit-builder'),
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 18 to 20
                         * Edit Start
                         */
                        'std' => 'name',
                        'options' => array(
                            'name' => __('name', 'profit-builder'),
                            'email' => __('email', 'profit-builder'),
                        ),
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 18 to 20
                         * Edit End
                         */
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                      'hide_if' => array(
                      'disablename' => array('true'),
                          /* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'formstyle' => array('Horizontal'), */
                      )
                    ),
                    'nameimage' => array(
                        'type' => 'image',
                        'label' => __('Name Image:', 'profit-builder'),
                        'std' => IMSCPB_URL . '/images/icons/nameicon.png',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                      'hide_if' => array(
                      'disablename' => array('true'),/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'formstyle' => array('Horizontal'), */
                      'fieldbg' => array('false'),
                      )
                    ),
                    'namevalue' => array(
                        'type' => 'input',
                        'label' => __('Name Value:', 'profit-builder'),
                        'desc' => __('Default Value for Name Field', 'profit-builder'),
                        'std' => 'Enter your first name...',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                      'hide_if' => array(
                      'disablename' => array('true'),/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'formstyle' => array('Horizontal'), */
                      )
                    ),
                    'namerequired' => array(
                        'type' => 'checkbox',
                        'label' => 'Required:',
                        'std' => 'false',
                        'half_column' => 'true',
                      'hide_if' => array(
                      'disablename' => array('true'),/* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'formstyle' => array('Horizontal'), */
                      )
                    ),
                    'nameerror' => array(
                        'type' => 'input',
                        'desc' => __('Error value for name field', 'profit-builder'),
                        'std' => 'Please enter your first name',
                        'half_column' => 'true',
                        'label_width' => 0,
                        'control_width' => 1,
                      'hide_if' => array(
                      'disablename' => array('true'),
                      'namerequired' => array('false'),
                          /* , 26-01-2015 In horizontal case this wasdisabled. Now this functionality removed as per following Tanveer sir instruction.
                      'formstyle' => array('Horizontal'), */
                      )
                    ),
                    'formstyle' => array(
                        'type' => 'select',
                        'label' => __('Form Style:', 'profit-builder'),
                        'std' => 'Vertical',
                        'options' => array(
                            'Vertical' => __('Vertical', 'profit-builder'),
                            'Horizontal' => __('Horizontal', 'profit-builder'),
                        )
                    ),
                    'fieldbg' => array(
                        'type' => 'checkbox',
                        'label' => 'Show Icons',
                        'std' => 'true',
                        'desc' => __('Use Field Backgrounds', 'profit-builder'),
                    ),
                    'fieldbgtransparent' => array(
                        'type' => 'checkbox',
                        'label' => 'Transparent Fields',
                        'std' => 'false',
                    ),
                    'fieldbgcolor' => array(
                        'type' => 'color',
                        'label' => __('Field Color:', 'profit-builder'),
                        'std' => '#ffffff',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'fieldbgtransparent' => array('true'),
                        )
                    ),
                    'fieldtextcolor' => array(
                        'type' => 'color',
                        'label' => __('Text Color:', 'profit-builder'),
                        'std' => '#111111',
                        'label_width' => 0.5,
                        'control_width' => 0.50,
                    ),
                    'fieldfontsize' => array(
                        'type' => 'number',
                        'label' => __('Font Size:', 'profit-builder'),
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 18 to 20
                         * Edit Start
                         */
                        'std' => '20px',
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 18 to 20
                         * Edit END
                         */
                        'unit' => 'px',
                    ),
                    'disablename' => array(
                        'type' => 'checkbox',
                        'label' => 'No Name',
                        'std' => 'false',
                        'half_column' => 'true',
                        'desc' => __('Disable Name Field', 'profit-builder'),
                    /* 'hide_if' => array(
                      'formstyle' => array('Horizontal'),
                      ) */
                    ),
                )
            ),
            'group_customfields' => array(
                'type' => 'collapsible',
                'label' => __('Custom Fields', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'customfields' => array(
                        'type' => 'checkbox',
                        'label' => 'Custom Fields',
                        'std' => 'false',
                        'half_column' => 'false',
                        'desc' => __('Enable Custom Fields', 'profit-builder'),
                        'hide_if' => array(
                            'formstyle' => array('Horizontal'),
                        )
                    ),
                    'addcustomfield' => array(
                        'type' => 'button',
                        'label' => 'Add New Custom Field',
                        'id' => 'addcustomfield',
                        'control_width' => 1,
                        'hide_if' => array(
                            'customfields' => array('false'),
                            'formstyle' => array('Horizontal'),
                        )
                    ),
                    'customfieldsdiv' => array(
                        'type' => 'div',
                        'id' => 'customfieldsdiv',
                    ),
                )
            ),
            'group_hiddenfields' => array(
                'type' => 'collapsible',
                'label' => __('Hidden Fields', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'hiddenfields' => array(
                        'type' => 'checkbox',
                        'label' => 'Hidden Fields',
                        'std' => 'false',
                        'half_column' => 'false',
                        'desc' => __('Enable Hidden Fields', 'profit-builder'),
                    ),
                    'addhiddenfield' => array(
                        'type' => 'button',
                        'label' => 'Add New Hidden Field',
                        'id' => 'addhiddenfield',
                        'control_width' => 1,
                        'hide_if' => array(
                            'hiddenfields' => array('false'),
                        )
                    ),
                    'hiddenfieldsdiv' => array(
                        'type' => 'div',
                        'id' => 'hiddenfieldsdiv',
                    ),
                )
            ),
            'group_content' => array(
                'type' => 'collapsible',
                'label' => __('Form Content', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'leadin' => array(
                        'type' => 'textarea',
                        'label' => __('Lead In', 'profit-builder'),
                        'std' => 'Enter your name and email below and click the button to get started now...',
                    ),
                    'privacy' => array(
                        'type' => 'input',
                        'label' => __('Privacy:', 'profit-builder'),
                        'desc' => __('Privacy and anti-spam notice', 'profit-builder'),
                        'std' => 'We value your privacy and will never spam you',
                        'label_width' => 0.5,
                        'control_width' => 0.5
                    ),
                )
            ),
            'group_formstyle' => array(
                'type' => 'collapsible',
                'label' => __('Form Style', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'formroundedsize' => array(
                        'type' => 'number',
                        'label' => __('Radius:', 'profit-builder'),
                        'std' => 10,
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 20,
                    ),
                    'formpadding' => array(
                        'type' => 'number',
                        'label' => __('Padding:', 'profit-builder'),
                        'std' => 10,
                        'unit' => 'px',
                        'min' => 0,
                        'max' => 20,
                    ),
                    'formbgtransparent' => array(
                        'type' => 'checkbox',
                        'label' => 'Transparent',
                        'std' => 'false',
                    ),
                    'formbgcolor' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => '#FFFFFF',
                        'label_width' => 0.5,
                        'control_width' => 0.5,
                        'hide_if' => array(
                            'formbgtransparent' => array('true'),
                        )
                    ),
                    'formtextcolor' => array(
                        'type' => 'color',
                        'label' => __('Text Color:', 'profit-builder'),
                        'std' => '#111111',
                        'label_width' => 0.5,
                        'control_width' => 0.50,
                    ),
                    'formborder' => array(
                        'type' => 'checkbox',
                        'label' => 'Border',
                        'std' => 'false',
                        'half_column' => 'true',
                    ),
                    'formbordercolor' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => '#cccccc',
                        'label_width' => 0.5,
                        'control_width' => 0.50,
                        'half_column' => 'true',
                        'hide_if' => array(
                            'formborder' => array('false'),
                        )
                    ),
                )
            ),
            'group_text' => array(
                'type' => 'collapsible',
                'label' => __('Form Button', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'btype' => array(
                        'type' => 'select',
                        'label' => __('Form Button Type:', 'profit-builder'),
                        'std' => 'custom',
                        'options' => array(
                            'custom' => __('Custom Text', 'profit-builder'),
                            'css3' => __('CSS3', 'profit-builder'),
                            'predone' => __('Pre-Done', 'profit-builder'),
                            'image' => __('Image', 'profit-builder')
                        )
                    ),
                    'image' => array(
                        'type' => 'image',
                        'label' => __('Image:', 'profit-builder'),
                        'std' => IMSCPB_URL . '/images/buttons/getinstantaccessgold.png',
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'btype' => array('custom', 'predone', 'css3'),
                        )
                    ),
                    'css3btnstyle' => array(
                        'type' => 'select',
                        'label' => __('Button Style:', 'profit-builder'),
                        'std' => 'style1',
                        'options' => $styles,
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'predone'),
                        )
                    ),
                    'pname' => array(
                        'type' => 'select',
                        'label' => __('Predone:', 'profit-builder'),
                        'std' => 'addtocart',
                        'options' => $files,
                        'label_width' => 0.25,
                        'control_width' => 0.75,
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'css3'),
                        )
                    ),
                    'pcolor' => array(
                        'type' => 'select',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => 'gold',
                        'options' => array(
                            'gold' => __('Gold', 'profit-builder'),
                            'black' => __('Black', 'profit-builder'),
                            'blue' => __('Blue', 'profit-builder'),
                            'red' => __('Red', 'profit-builder'),
                            'white' => __('White', 'profit-builder'),
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'css3'),
                        )
                    ),
                    'panimated' => array(
                        'type' => 'checkbox',
                        'label' => __('Animated', 'profit-builder'),
                        'std' => 'false',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'btype' => array('image', 'custom', 'css3'),
                        )
                    ),
                    'text' => array(
                        'type' => 'input',
                        'label' => __('Text:', 'profit-builder'),
                        'std' => __('Get Instant Access', 'profit-builder'),
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'font_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 34 to 30
                         * Edit Start
                         */
                        'std' => 20,
                        /*
                         * Code added by Asim Ashraf - DevBatch
                         * DateTime: 27 Jan 2015
                         * Code Edit Start
                         * @std 34 to 30
                         * Edit End
                         */
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'letter_spacing' => array(
                        'type' => 'number',
                        'label' => __('Spacing:', 'profit-builder'),
                        'min' => -2,
                        'max' => 10,
                        'std' => -1,
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'font_weight' => array(
                        'type' => 'select',
                        'label' => __('Weight:', 'profit-builder'),
                        'std' => 'bold',
                        'options' => array(
                            'normal' => 'Normal',
                            'lighter' => 'Lighter',
                            'bold' => 'Bold',
                            'bolder' => 'Bolder',
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'text_align' => array(
                        'type' => 'select',
                        'label' => __('Text alignment:', 'profit-builder'),
                        'std' => 'center',
                        'options' => array(
                            'left' => __('Left', 'profit-builder'),
                            'center' => __('Centered', 'profit-builder'),
                            'right' => __('Right', 'profit-builder')
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                    'text_color' => array(
                        'type' => 'color',
                        'label' => __('Color:', 'profit-builder'),
                        'std' => '#ffffff',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'hover_text_color' => array(
                        'type' => 'color',
                        'label' => __('Hover:', 'profit-builder'),
                        'std' => '#ffffff',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'icon' => array(
                        'type' => 'icon',
                        'label' => __('Icon Type:', 'profit-builder'),
                        'notNull' => false,
                        'std' => 'no-icon',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'icon_align' => array(
                        'type' => 'select',
                        'label' => __('Icon alignment:', 'profit-builder'),
                        'std' => 'left',
                        'options' => array(
                            'left' => __('Left', 'profit-builder'),
                            'right' => __('Right', 'profit-builder'),
                            'inline' => __('Inline', 'profit-builder')
                        ),
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'icon_size' => array(
                        'type' => 'number',
                        'label' => __('Size:', 'profit-builder'),
                        'std' => 16,
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'back_color' => array(
                        'type' => 'color',
                        'label' => __('Background Color:', 'profit-builder'),
                        'std' => '#ff6600',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'hover_back_color' => array(
                        'type' => 'color',
                        'label' => __('Background Hover:', 'profit-builder'),
                        'std' => '#ff6600',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'round' => array(
                        'type' => 'checkbox',
                        'label' => 'Round',
                        'std' => 'true',
                        'half_column' => 'true',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'fill' => array(
                        'type' => 'checkbox',
                        'label' => __('Fill', 'profit-builder'),
                        'std' => 'true',
                        'half_column' => 'true',
                        'desc' => __('turn off to get a button with border', 'profit-builder'),
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                    'border_thickness' => array(
                        'type' => 'number',
                        'label' => __('Border thickness:', 'profit-builder'),
                        'std' => 1,
                        'min' => 0,
                        'max' => 20,
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone', 'css3'),
                        )
                    ),
                )
            ),
            'group_button_font_style' => array(
                'type' => 'collapsible',
                'label' => __('Form Button Font Style', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'google_font' => array(
                        'type' => 'select',
                        'class' => 'pbuilder_font_select',
                        'search' => 'true',
                        'std' => 'default',
                        'label' => __('Font family', 'profit-builder'),
                        'options' => $pbuilder_google_font_names
                    ),
                    'google_font_style' => array(
                        'name' => 'h1_font_style',
                        'type' => 'select',
                        'std' => 'default',
                        'label' => __('Font style', 'profit-builder'),
                        'options' => array('default' => 'Default'),
                        'hide_if' => array(
                            'google_font' => array('default')
                        )
                    ),
                )
            ),
            'group_buttonsize' => array(
                'type' => 'collapsible',
                'label' => __('Form Button Size', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'buttonwidth' => array(
                        'type' => 'number',
                        'label' => __('Width:', 'profit-builder'),
                        'std' => '230px',
                        'min' => 0,
                        'max' => 500,
                        'half_column' => 'false',
                        'unit' => 'px',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                            'buttonwidthfull' => array('true')
                        )
                    ),
                    'buttonwidthfull' => array(
                        'type' => 'checkbox',
                        'label' => '100% Width',
                        'std' => 'true',
                        'half_column' => 'false',
                        'hide_if' => array(
                            'btype' => array('image', 'predone'),
                        )
                    ),
                /* 'buttonheight' => array(
                  'type' => 'number',
                  'label' => __('Height:','profit-builder'),
                  'std' =>  '50px',
                  'min' => 0,
                  'max' => 500,
                  'half_column' => 'false',
                  'unit' => 'px',
                  'hide_if' => array(
                  'btype' => array('image','predone'),
                  )
                  ), */
                )
            )/* ,
              'group_twostep' => array(
              'type' => 'collapsible',
              'label' => __('Two Step','profit-builder'),
              'open' => 'false',
              'options' => array(
              'enabletwostep' => array(
              'type' => 'checkbox',
              'label' => 'Enable',
              'std' => 'false',
              'half_column' => 'true',
              ),
              'leadin2step' => array(
              'type' => 'textarea',
              'label' => __('Lead In','profit-builder'),
              'std' => 'Change this text to be a great call to action to click initially...',
              ),
              'buttontext' => array(
              'type' => 'input',
              'label' => __('Button Text:','profit-builder'),
              'desc' => __('Button Text before Optin Shows....','profit-builder'),
              'std' => 'Click to Learn More',
              'label_width' => 0.5,
              'control_width' => 0.5
              ),
              )
              ) */,
            'group_advanced' => array(
                'type' => 'collapsible',
                'label' => __('Advanced', 'profit-builder'),
                'open' => 'false',
                'options' => array(
                    'v_padding' => array(
                        'type' => 'number',
                        'label' => __('Vertical padding:', 'profit-builder'),
                        'std' => 10,
                        'unit' => 'px'
                    ),
                    'h_padding' => array(
                        'type' => 'number',
                        'label' => __('Horizontal padding:', 'profit-builder'),
                        'std' => 10,
                        'unit' => 'px'
                    )
                )
            )
                ), $classControl, array(
            'group_general' => array(
                'type' => 'collapsible',
                'label' => __('General', 'profit-builder'),
                'options' => array(
                    'bot_margin' => array(
                        'type' => 'number',
                        'label' => __('Bottom margin:', 'profit-builder'),
                        'std' => $opts['bottom_margin'],
                        'unit' => 'px'
                    )
                )
            )
                ), $animationControl, $formImageControl
        )
    )
);
$pbuilder_shortcodes = array_merge($button, $text, $bulletlist, $image, $heading, $video, $audio, $features, $contact_form, $slider, $creative_post_slider, $testimonials, $tabs, $tour, $accordion, $toggle, $counter, $percentage_bars, $percentage_chart, $graph, $gauge_chart, $piechart, $pricing, $code, $alert, $menu, $icon_menu, $sidebar, $search, $separator, $read_more, $post, $recent_post, $gallery, $timer, $textarea, $iframe, $qanda, $comments, $social, $overlay, $optin_form, $fbcomments, $gmap, $progressbar);
$output = $pbuilder_shortcodes;
