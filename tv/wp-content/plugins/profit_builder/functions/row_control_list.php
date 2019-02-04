<?php
$pbuilder_controls = array(

	'group_general' => array(
		'type' => 'collapsible',
		'label' => __('Dimensions','profit-builder'),
		'open' => 'true',
		'options' => array(
			'full_width' => array(
				'type' => 'checkbox',
				'label' => __('Full width row','profit-builder'),
				'std' => 'false'
				
			),
			'padding_top' => array(
				'type' => 'number',
				'label' => __('Top padding','profit-builder'),
				'std' => 36,
				'max' => 300,
				'unit' => 'px'
			),
			'padding_bot' => array(
				'type' => 'number',
				'label' => __('Bottom padding','profit-builder'),
				'std' => 36,
				'max' => 300,
				'unit' => 'px'
			),
			'column_padding' => array(
				'type' => 'number',
				'label' => __('Column padding','profit-builder'),
				'std' => 0,
				'unit' => 'px'
			),
            'row_style' => array(
				'type' => 'select',
				'label' => __('Row Style','profit-builder'),
				'label_width' => 0.5,
				'control_width' => 0.5,
				'std' => 'normal',
				'options' => array(
					'normal' => __('Normal Row','profit-builder'),
					'sticktop' => __('Stick to Top','profit-builder'),
					'stickbottom' => __('Stick to Bottom','profit-builder'),
				),
			),
            'timed_row' => array(
				'type' => 'checkbox',
				'label' => __('Timed row','profit-builder'),
				'std' => 'false'
				
			),
			'timed_row_min' => array(
				'type' => 'number',
				'label' => __('Minutes','profit-builder'),
				'std' => 0,
				'max' => 60,
                'hide_if' => array(
					'timed_row' => array('false'),
				),
			),
			'timed_row_sec' => array(
				'type' => 'number',
				'label' => __('Seconds','profit-builder'),
				'std' => 0,
				'max' => 60,
                'hide_if' => array(
					'timed_row' => array('false'),
				),
			),
		)
	),
    'border_group' => array(
		'type' => 'collapsible',
		'label' => __('Border','profit-builder'),
		'open' => 'true',
		'options' => array(
			'border_color' => array(
				'type' => 'color',
				'label' => __('Border Color','profit-builder'),
				'label_width' => 0.5,
				'control_width' => 0.5,
				'std' => $this->option('row_border_color')->value,				
			),
            'border_width' => array(
				'type' => 'number',
				'label' => __('Width','profit-builder'),
				'std' => 0,
				'max' => 100,
				'unit' => 'px'
			),
            'border_style' => array(
				'type' => 'select',
				'label' => __('Border Style','profit-builder'),
				'label_width' => 0.5,
				'control_width' => 0.5,
				'std' => 'none',
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
			),
            'border_round' => array(
				'type' => 'number',
				'label' => __('Roundness','profit-builder'),
				'std' => 0,
				'max' => 100,
				'unit' => 'px'
			),
		)
	),
    'shadow_group' => array(
		'type' => 'collapsible',
		'label' => __('Shadow','profit-builder'),
		'open' => 'true',
		'options' => array(
			'shadow_color' => array(
				'type' => 'color',
				'label' => __('Color','profit-builder'),
				'label_width' => 0.5,
				'control_width' => 0.5,
				'std' => $this->option('row_shadow_color')->value,				
			),
            'shadow_h_shadow' => array(
				'type' => 'number',
				'label' => __('Horizontal Shadow','profit-builder'),
				'std' => 0,
				'max' => 100,
				'unit' => 'px'
			),
            'shadow_v_shadow' => array(
				'type' => 'number',
				'label' => __('Vertical Shadow','profit-builder'),
				'std' => 0,
				'max' => 100,
				'unit' => 'px'
			),
            'shadow_blur' => array(
				'type' => 'number',
				'label' => __('Blur','profit-builder'),
				'std' => 0,
				'max' => 100,
				'unit' => 'px'
			),
		)
	),
	'group_background' => array(
		'type' => 'collapsible',
		'label' => __('Background','profit-builder'),
		'open' => 'true',
		'options' => array(
			'back_type' => array(
				'type' => 'select',
				'label' => __('Type','profit-builder'),
				'label_width' => 0.25,
				'control_width' => 0.75,
				'std' => 'static',
				'options' => array(
					'static' => __('Static','profit-builder'),
					'parallax' => __('Fixed','profit-builder'),
					'parallax_animated' => __('Parallax','profit-builder'),
					'video' => __('Video','profit-builder'),
				),
			),
			'back_color' => array(
				'type' => 'color',
				'label' => __('Color','profit-builder'),
				'label_width' => 0.25,
				'control_width' => 0.75,
				'std' => $this->option('row_back_color')->value,
				'hide_if' => array(
					'back_type' => array('video', 'video_fixed', 'video_parallax')
				)
				
			),
            'back_color2' => array(
				'type' => 'color',
				'label' => __('2nd Color','profit-builder'),
				'label_width' => 0.25,
				'control_width' => 0.75,
				'std' => $this->option('row_back_color2')->value,
				'hide_if' => array(
					'back_type' => array('video', 'video_fixed', 'video_parallax')
				)
				
			),
            'gradient_type' => array(
				'type' => 'select',
				'label' => __('Gradient Type','profit-builder'),
				'label_width' => 0.25,
				'control_width' => 0.75,
				'std' => 'linear',
				'options' => array(
					'linear' => __('Linear','profit-builder'),
					'radial' => __('Radial','profit-builder'),
				),
                'hide_if' => array(
					'back_type' => array('video', 'video_fixed', 'video_parallax')
				)
			),
			'back_image' => array(
				'type' => 'image',
				'label' => __('Image','profit-builder'),
				'label_width' => 0.25,
				'control_width' => 0.75,
				'std' => '',
				'hide_if' => array(
					'back_type' => array('video', 'video_fixed', 'video_parallax')
				)
			),
			'back_repeat' => array(
				'type' => 'select',
				'label' => __('Style','profit-builder'),
				'label_width' => 0.25,
				'control_width' => 0.75,
				'std' => 'center',
				'options' => array(
					'center' => __('Center','profit-builder'),
					'repeat' => __('Repeat','profit-builder'),
					'repeatx' => __('Repeat Horizontal','profit-builder'),
					'stretched' => __('Stretched','profit-builder')
				),
				'hide_if' => array(
					'back_type' => array('video', 'video_fixed', 'video_parallax')
				)
					
			),
			/*
			'back_repeat' => array(
				'type' => 'checkbox',
				'label' => __('Repeat image','profit-builder'),
				'std' => 'true',
				'hide_if' => array(
					'back_type' => array('video', 'video_fixed', 'video_parallax')
				)
			)*/
		)
	),
	'group_column_back' => array(
		'type' => 'collapsible',
		'label' => __('Column Background','profit-builder'),
		'open' => 'true',
		'options' => array(
			'column_back' => array(
				'type' => 'color',
				'label' => __('Color','profit-builder'),
				'std' => $this->option('column_back_color')->value
			),
			'column_back_opacity' => array(
				'type' => 'number',
				'label' => __('Opacity','profit-builder'),
				'std' => 100,
				'unit' => '%'
			)
		)
	),
	'group_video_back' => array(
		'type' => 'collapsible',
		'label' => __('Video Background','profit-builder'),
		'options' => array(
			'back_video_source' => array(
				'type' => 'select',
				'label' => __('Source','profit-builder'),
				'std' => 'youtube',
				'options' => array(
					'youtube' => __('Youtube','profit-builder'),
					'vimeo' => __('Vimeo','profit-builder'),
					'html5' => __('HTML5','profit-builder')
				),
				'hide_if' => array(
					'back_type' => array('static', 'parallax', 'parallax_animated')
				)
			),
			'back_video_youtube_id' => array(
				'type' => 'input',
				'label' => __('Youtube video ID','profit-builder'),
				'desc' => 'example: tDvBwPzJ7dY',
				'std' => '',
				'hide_if' => array(
					'back_type' => array('static', 'parallax', 'parallax_animated'),
					'back_video_source' => array('vimeo', 'html5')
				)
			),
			'back_video_vimeo_id' => array(
				'type' => 'input',
				'label' => __('Vimeo video ID','profit-builder'),
				'desc' => 'example: 30300114',
				'std' => '',
				'hide_if' => array(
					'back_type' => array('static', 'parallax', 'parallax_animated'),
					'back_video_source' => array('youtube', 'html5')
				)
			),
			'back_video_html5_img' => array(
				'type' => 'input',
				'label' => __('Image poster url','profit-builder'),
				'desc' => 'If no video is supported',
				'std' => '',
				'hide_if' => array(
					'back_type' => array('static', 'parallax', 'parallax_animated'),
					'back_video_source' => array('youtube', 'vimeo')
				)
			),
			'back_video_html5_mp4' => array(
				'type' => 'input',
				'label' => __('MP4 url','profit-builder'),
				'std' => '',
				'hide_if' => array(
					'back_type' => array('static', 'parallax', 'parallax_animated'),
					'back_video_source' => array('youtube', 'vimeo')
				)
			),
			'back_video_html5_webm' => array(
				'type' => 'input',
				'label' => __('WEBM url','profit-builder'),
				'std' => '',
				'hide_if' => array(
					'back_type' => array('static', 'parallax', 'parallax_animated'),
					'back_video_source' => array('youtube', 'vimeo')
				)
			),
			'back_video_html5_ogv' => array(
				'type' => 'input',
				'label' => __('OGV url','profit-builder'),
				'std' => '',
				'hide_if' => array(
					'back_type' => array('static', 'parallax', 'parallax_animated'),
					'back_video_source' => array('youtube', 'vimeo')
				)
			),
			'back_video_loop' => array(
				'type' => 'checkbox',
				'label' => __('Loop video','profit-builder'),
				'std' => 'true',
				'hide_if' => array(
					'back_type' => array('static', 'parallax', 'parallax_animated')
				)
			)
		)
	),
	'group_css' => array(
		'type' => 'collapsible',
		'label' => __('ID & Custom CSS','profit-builder'),
		'options' => array(
			'id' => array(
				'type' => 'input',
				'label' => __('ID','profit-builder'),
				'desc' => __('For linking via hashtags','profit-builder'),
				'std' => ''
			),
			'class' => array(
				'type' => 'input',
				'label' => __('Class','profit-builder'),
				'desc' => __('For custom css','profit-builder'),
				'std' => ''
			)
		)
	)

	
);


if($this->option('css_classes')->value == 'true') {
	$classControl = array(

	);
	$pbuilder_controls = array_merge($classControl, $pbuilder_controls);
	
}
$output = $pbuilder_controls;
?>