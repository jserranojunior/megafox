<?php

function pbuilder_decode($encoded) {
    if (is_array($encoded)) {
        $keys = array_keys($encoded);
        foreach ($keys as $key) {
            if (is_string($encoded[$key])) {
                $decoded = $encoded[$key];
                $decoded = str_replace("%sqs%", "[", $decoded);
                $decoded = str_replace("%sqe%", "]", $decoded);
                //$decoded = stripslashes($decoded);                
                $encoded[$key] = do_shortcode($decoded); //urldecode($encoded[$key])
            }
        }
    } elseif ($encoded != null) {
        $encoded = str_replace("%sqs%", "[", $encoded);
        $encoded = str_replace("%sqe%", "]", $encoded);
        $encoded = do_shortcode($encoded);
    }
    return $encoded;
}

/* ------------------ */
/* pbuilder_gallery */
/* ------------------ */

function pbuilder_gallery($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'bot_margin' => 24,
        'shortcode_id' => '',
        'class' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => '',
        'animation_speed' => 1000,
        'column_number' => '3',
        'item_padding' => '10',
        'image_size' => 'full',
        'aspect_ratio' => '16:9',
        'enable_categories' => 'false',
        'media_files' => '',
        'on_image_click' => 'none',
        'bckg_color' => 'transparent',
        'bckg_hover_color' => 'transparent',
        'text_color' => '#232323',
        'text_hover_color' => '#232323',
        'border_color' => 'transparent',
        'border_hover_color' => 'transparent',
        'border_thickness' => '',
        'show_all_category' => 'false',
        'category_name' => 'Images',
        'active' => 'false',
        'category_media_files' => '',
        'hover_icon' => 'ba-search',
        'hover_icon_size' => '30px',
        'initial_shade_color' => '#000000',
        'initial_shade_opacity' => '0',
        'hover_shade_color' => '#000000',
        'hover_shade_opacity' => '0.3',
        'hover_content' => 'icon',
        'hover_title_color' => '#ffffff'
                    ), $atts));

    global $pbuilder;

    $bot_margin = (int) $bot_margin . 'px';
    $randomId = $shortcode_id == '' ? 'frb_gallery_' . rand() : $shortcode_id;

    $hover_icon_size = (int) $hover_icon_size . 'px';
    $hover_shade_opacity = (float) $hover_shade_opacity;
    $hover_shade_opacity = $hover_shade_opacity > 1 ? 1 : $hover_shade_opacity;
    $hover_shade_opacity = $hover_shade_opacity < 0 ? 0 : $hover_shade_opacity;
    $column_number = (int) $column_number;
    $column_width = 100 / $column_number;
    $item_padding = (int) $item_padding . 'px';
    $border_thickness = (int) $border_thickness . 'px';
    $on_image_click = in_array($on_image_click, array('none', 'pretty_photo', 'new_tab'), true) ? $on_image_click : 'none';
    $hover_content = in_array($hover_content, array('icon', 'title'), true) ? $hover_content : 'icon';
    $media_files = explode(',', $media_files);
    $category_name = explode('|', $category_name);
    $active = explode('|', $active);
    $category_media_files = explode('|', $category_media_files);
    $category_array = array();

    if ($bckg_color == '') {
        $bckg_color = 'transparent';
    }
    if ($bckg_hover_color == '') {
        $bckg_hover_color = 'transparent';
    }
    if ($text_color == '') {
        $text_color = 'transparent';
    }
    if ($text_hover_color == '') {
        $text_hover_color = 'transparent';
    }
    if ($border_color == '') {
        $border_color = 'transparent';
    }
    if ($border_hover_color == '') {
        $border_hover_color = 'transparent';
    }

    foreach ($category_media_files as $cat_array) {
        array_push($category_array, explode(',', $cat_array));
    }


    $all_category_media_files = '';

    foreach ($category_media_files as $single_cat_media) {
        $all_category_media_files.=$single_cat_media . ',';
    }

    $all_category_media_files = explode(',', $all_category_media_files);
    array_pop($all_category_media_files);
    $all_category_media_files = array_unique($all_category_media_files);

    $html = '<style type="text/css">
		#' . $randomId . ' .frb_gallery_inner {
				margin-right:-' . $item_padding . ';
			}
		
		
		#' . $randomId . ' .frb_gallery_inner .frb_media_file {
			width:' . $column_width . '%;
			padding-right:' . $item_padding . ';
			padding-top:' . $item_padding . ';
		}

		#' . $randomId . ' .frb_gallery_categories .frb_gallery_cat {
			background-color:' . $bckg_color . ';
			color:' . $text_color . ';
			border:' . $border_thickness . ' solid ' . $border_color . ';
		}

		#' . $randomId . ' .frb_gallery_categories .frb_gallery_cat:hover, #' . $randomId . ' .frb_gallery_categories .frb_gallery_cat.frb_cat_active {
			background-color:' . $bckg_hover_color . ';
			color:' . $text_hover_color . ';
			border:' . $border_thickness . ' solid ' . $border_hover_color . ';
		}

		#' . $randomId . ' .frb_gallery_hover .frb_gallery_image_title {
			color: ' . $hover_title_color . ';
		}

		#' . $randomId . ' .frb_gallery_hover {
			background-color: rgba(' . $pbuilder->hex2rgb($initial_shade_color) . ', ' . $initial_shade_opacity . ');
		}
		#' . $randomId . ' .frb_gallery_hover:hover {
			background-color: rgba(' . $pbuilder->hex2rgb($hover_shade_color) . ', ' . $hover_shade_opacity . ');
		}
	
		
		
	</style>';


    $iconInsert = '';
    if (substr($hover_icon, 0, 4) == 'icon') {
        $iconInsert .= '<i class="fawesome ' . $hover_icon . '" style="line-height:' . $hover_icon_size . '; font-size:' . $hover_icon_size . '; height:' . $hover_icon_size . '; width:' . $hover_icon_size . '; margin:' . (-$hover_icon_size / 2) . ' "></i>';
    } else {
        $iconInsert .= '<i class="frb_icon ' . substr($hover_icon, 0, 2) . ' ' . $hover_icon . '" style="line-height:' . $hover_icon_size . '; font-size:' . $hover_icon_size . '; height:' . $hover_icon_size . '; width:' . $hover_icon_size . '; margin-top:' . (-$hover_icon_size / 2) . 'px; margin-left:' . (-$hover_icon_size / 2) . 'px "></i>';
    }



    $html .= '<div class="frb_gallery_container" data-frb_aspect_ratio="' . $aspect_ratio . '" data-frb_media_column="' . $column_number . '">';

    if ($enable_categories == 'true') {
        $html.='<div class="frb_gallery_categories">';


        if ($show_all_category == 'true')
            $html.='<a class="frb_gallery_cat frb_cat_active" href="All">' . __('All', 'profit-builder') . '</a>';

        $media_files = $all_category_media_files;

        for ($i = 0; $i < count($category_name); $i++) {
            $html.='<a class="frb_gallery_cat' . ($active[$i] == 'true' ? ' frb_cat_active' : '') . '" href="' . $category_name[$i] . '">' . $category_name[$i] . '</a>';
        }


        $html.='</div><div style="clear:both;"></div>';
    }


    $html .= '<div class="frb_gallery_outer">
	 		<div class="frb_gallery_inner">';

    for ($i = 0; $i < count($media_files); $i++) {

        if ($enable_categories == 'true') {

            $html.='<div class="frb_media_file';

            for ($j = 0; $j < count($category_array); $j++) {
                if (in_array($media_files[$i], $category_array[$j]))
                    $html.=' frb_gallery_cat_' . $j;
            }

            $html.='">';
        }
        else {
            $html.='<div class="frb_media_file ' . (($i < $column_number) ? 'frb_gallery_no_top_padding' : '') . '">';
        }

        $html.='<div class="frb_media_file_inner">';

        if (wp_attachment_is_image($media_files[$i])) {

            $image = wp_get_attachment_image_src($media_files[$i], 'full');


            $imageThumb = wp_get_attachment_image_src($media_files[$i], $image_size);

            $html.=($on_image_click == 'new_tab' ? '<a class="frb_gallery_new_tab_link" href="' . $image[0] . '" target="_blank">' : ($on_image_click == 'pretty_photo' ? '<a href="' . $image[0] . '" rel="frbprettyphoto">' : '')) . '
	 					<img src="' . $imageThumb[0] . '" width="' . $imageThumb[1] . '" height="' . $imageThumb[2] . '"></img>
	 					' . ($on_image_click == 'none' ? '' : '</a>');
        } else {

            $media_file = wp_get_attachment_url($media_files[$i]);
            $media_file_type = get_post_mime_type($media_files[$i]);
            $media_file_types = array('audio/mpeg', 'audio/wav', 'audio/wma', 'audio/x-matroska', 'audio/midi', 'audio/ogg', 'audio/x-realaudio');

            $html.=($on_image_click == 'new_tab' ? '<a class="frb_gallery_new_tab_link" href="' . $media_file . '" target="_blank">' : ($on_image_click == 'pretty_photo' ? '<a href="' . $media_file . '?iframe=true" rel="frbprettyphoto">' : '')) . '
	 					<video' . ($on_image_click == 'None' ? ' controls ' : ' ');


            if (in_array($media_file_type, $media_file_types))
                $html.=' poster="' . plugins_url('\profit_builder\images\audio.jpg') . '" ';

            $html.= '>
  							<source src="' . $media_file . '">
 						 Your browser does not support the video tag.
						</video> 
	 					' . ($on_image_click == 'none' ? '' : '</a>');
        }


        $html.= $on_image_click !== 'none' ? '<div class="frb_gallery_hover">' . ($hover_content === 'icon' ? $iconInsert : '<span class="frb_centering_system"><span><span><span class="frb_gallery_image_title">' . (get_post((int) $media_files[$i])->post_title) . '</span></span></span></span>') . '</div>' : '';
        $html.= '</div>
	 			</div>';
    }

    $html .= '<div class="frb_clear"></div></div></div></div>';




    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style>' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';




    $html = $animSpeedSet . '
		<div id="' . $randomId . '" style="padding-bottom:' . $bot_margin . ';" class="' . $class . $animate . '>' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_gallery', 'pbuilder_gallery');





/* ------------------ */
/* pbuilder_audio */
/* ------------------ */

function pbuilder_audio($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'bot_margin' => 24,
        'shortcode_id' => '',
        'class' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => '',
        'animation_speed' => 1000,
        'content_mp3' => 'http://media.w3.org/2010/07/bunny/04-Death_Becomes_Fur.mp4',
        'content_ogg' => 'http://media.w3.org/2010/07/bunny/04-Death_Becomes_Fur.oga',
        'background_color' => '#464646',
        'bar_color' => '#21CDEC',
        'icon_type' => 'default',
        'start_at' => '0',
        'autoplay' => 'false',
        'loop' => 'false',
        'mute' => 'false',
        'hide_controls' => 'false'
                    ), $atts));

    $bot_margin = (int) $bot_margin . 'px';
    $randomId = $shortcode_id == '' ? 'frb_audio_' . rand() : $shortcode_id;


    $html = '<style type="text/css" scoped="scoped">
		#' . $randomId . ' .frb_audio_player {
			background-color:' . $background_color . ';
		}
		
		#' . $randomId . ' .frb_audio_player .ui-slider-range {
			background-color:' . $bar_color . ';
			border:1px solid ' . $bar_color . ';
		}
		
	</style>';

    $html .= '<div class="frb_audio_player ' . ($icon_type == 'default' ? '' : 'frb_audio_' . $icon_type ) . '" data-frb_options="' . $autoplay . '|' . $loop . '|' . $mute . '|' . $hide_controls . '|' . $content_mp3 . '|' . $content_ogg . '|' . $start_at . '">
 
            <a class="frb_play_button frb_image_button" href="" title=""></a>
            <a class="frb_stop_button frb_image_button" href="" title=""></a>
         	<div class="frb_current_time">00:00</div>
                <div class="frb_time_slider"></div>
     	<div class="frb_full_time"></div>
            
            <a class="frb_mute_button frb_image_button" href="" title=""></a> 
            <div class="frb_volume_slider"></div>
            <div class="frb_clear"></div>
            <audio>
 				 <source src="' . $content_ogg . '" type="audio/ogg">
  				<source src="' . $content_mp3 . '" type="audio/mpeg">
  				Your browser does not support the audio element.
			</audio>
        </div><!-- / player -->';

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';




    $html = $animSpeedSet . '
		<div id="' . $randomId . '" style="padding-bottom:' . $bot_margin . ';" class="' . $class . $animate . '>' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_audio', 'pbuilder_audio');


/* ------------------ */
/* pbuilder_percentage_bars */
/* ------------------ */

function pbuilder_percentage_bars($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'bot_margin' => 24,
        'shortcode_id' => '',
        'class' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => '',
        'animation_speed' => 1000,
        'bar_style' => 'sharp',
        'element_spacing' => '20px',
        'percent_pin' => 'true',
        'headline_inside' => 'false',
        'headline_top_margin' => '0px',
        'headline_color' => '#232323',
        'line_background' => '#eeeeee',
        'custom_height' => '5px',
        'direction' => 'ltr',
        'headline_content' => __('Percentage Bar', 'profit-builder'),
        'line_color' => '#000',
        'pin_color' => '#222',
        'pin_text_color' => '#fff',
        'percentage' => 100,
        'an_speed' => 400,
        'pattern_url' => ' '
                    ), $atts));

    $bot_margin = (int) $bot_margin . 'px';
    $randomId = $shortcode_id == '' ? 'frb_percentage_bar' . rand() : $shortcode_id;
    $element_spacing = (int) $element_spacing . 'px';
    $bar_style = $bar_style == 'round' ? ' frb_pbar_round' : '';
    $percent_pin = $percent_pin == 'false' ? false : true;
    $headline_inside = $headline_inside == 'false' ? false : true;
    $headline_top_margin = (int) $headline_top_margin . 'px';
    $custom_height = (int) $custom_height . 'px';


    $directionExp = explode('|', $direction);
    $headline_contentExp = explode('|', $headline_content);
    $line_colorExp = explode('|', $line_color);
    $pin_colorExp = explode('|', $pin_color);
    $pin_text_colorExp = explode('|', $pin_text_color);
    $pattern_urlExp = explode('|', $pattern_url);

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $style = '<style type="text/css" scoped="scoped">
		#' . $randomId . '.frb_percentage_bar h5 {color:' . $headline_color . '; ' . ($headline_inside ? 'position:absolute; top:0; display:block; white-space: nowrap; margin-top:' . $headline_top_margin . ';' : '') . '}
		#' . $randomId . '.frb_percentage_bar .frb_pbar_line_wrapper {background-color:' . $line_background . '; margin-bottom:' . $element_spacing . '; height: ' . $custom_height . ';' . ($headline_inside && $percent_pin ? 'margin-top:32px;' : '') . '}
		#' . $randomId . '.frb_percentage_bar .frb_pbar_line {height: ' . $custom_height . ';}
		#' . $randomId . '.frb_percentage_bar .frb_pbar_pin {bottom: ' . ((int) $custom_height + 13) . 'px;}';

    for ($i = 0; $i < count($directionExp); $i++) {
        $pattern_urlExp[$i] = ($pattern_urlExp[$i] === ' ' ? 'none' : 'url(' . $pattern_urlExp[$i] . ')');
        $style .=
                '#' . $randomId . '.frb_percentage_bar .frb_pbar_single_bar_wrapper' . $i . ' h5 {text-align:' . (($directionExp[$i] == 'rtl' ? ' rtl' : '') == ' rtl' ? 'right' : 'left') . '; ' . (($directionExp[$i] == 'rtl' ? ' rtl' : '') == ' rtl' ? 'right:5px;' : 'left:5px;') . '}
			#' . $randomId . '.frb_percentage_bar .frb_pbar_single_bar_wrapper' . $i . ' .frb_pbar_line {background-color:' . $line_colorExp[$i] . ';' . ($pattern_urlExp[$i] == 'none' ? '' : 'background-image:' . $pattern_urlExp[$i] . ';') . '}
			#' . $randomId . '.frb_percentage_bar .frb_pbar_single_bar_wrapper' . $i . ' .frb_pbar_pin {background-color:' . $pin_colorExp[$i] . '; color:' . $pin_text_colorExp[$i] . ';}
			#' . $randomId . '.frb_percentage_bar .frb_pbar_single_bar_wrapper' . $i . ' .frb_pbar_pin:after {border-top-color:' . $pin_colorExp[$i] . ';}';
    }


    $style .= '</style>';

    $html = $animSpeedSet . $style . '<div class="frb_percentage_bar frb_animated' . ($percent_pin ? '' : ' no-pin') . $bar_style . $class . $animate . ' id="' . $randomId . '" data-percentage="' . $percentage . '" data-aspd="' . $an_speed . '" data-dir="' . $direction . '" style="padding-bottom :' . $bot_margin . ';">';

    for ($i = 0; $i < count($directionExp); $i++) {
        $H_tag = '<h5>' . $headline_contentExp[$i] . '</h5>';
        $html .='<div class="frb_pbar_single_bar_wrapper frb_pbar_single_bar_wrapper' . $i . '">
				' . ($headline_inside ? '' : $H_tag) . '
				<div class="frb_pbar_line_wrapper' . ($directionExp[$i] == 'rtl' ? ' rtl' : '') . '">
					<div class="frb_pbar_line">' . ($headline_inside ? $H_tag : '') . '</div>
					<div class="frb_pbar_pin"><span>0%</span></div>
				</div>
			</div>';
    }

    $html .= '</div>';


    return $html;
}

add_shortcode('pbuilder_percentage_bars', 'pbuilder_percentage_bars');

/* ------------------ */
/* pbuilder_creative_post_slider */
/* ------------------ */

function pbuilder_creative_post_slider($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'bot_margin' => 24,
        'shortcode_id' => '',
        'class' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => '',
        'animation_speed' => 1000,
        'slides_per_view' => '3',
        'hover_background_color' => '#eee',
        'hover_text_color' => '#222',
        'hover_icon' => 'ba-search',
        'hover_icon_size' => '30px',
        'title_size' => '20px',
        'title_line_height' => 'default', //'24px',
        'excerpt_size' => '14px',
        'excerpt_line_height' => 'default', //'18px',
        'excerpt_lenght' => 30,
        'categories' => '1',
        'category_order' => 'desc',
        'number_of_posts' => 5,
        'cat_line_height' => 'default', //'18px',
        'cat_size' => '14px',
        'category_show' => 'true',
        'excerpt_show' => 'true',
        'image_size' => 'full',
        'aspect_ratio' => '16:9',
        'enable_custom_height' => 'false',
        'custom_slider_height' => '300px',
        'resize_reference' => '200px',
        'link_type' => 'prettyphoto',
        'open_link_in' => 'default',
        'order_by' => 'ID',
        'enable_icon' => 'true'
                    ), $atts));

    $bot_margin = (int) $bot_margin . 'px';
    $title_size = (int) $title_size . 'px';
    $title_line_height = $title_line_height != 'default' ? (int) $title_line_height . 'px' : '110%';
    $cat_size = (int) $cat_size . 'px';
    $cat_line_height = $cat_line_height != 'default' ? (int) $cat_line_height . 'px' : '110%';
    $excerpt_size = (int) $excerpt_size . 'px';
    $excerpt_line_height = $excerpt_line_height != 'default' ? (int) $excerpt_line_height . 'px' : '110%';
    $excerpt_lenght = (int) $excerpt_lenght;
    $randomId = $shortcode_id == '' ? 'frb_creative_post_slider_' . rand() : $shortcode_id;
    $slides_per_view = (int) $slides_per_view;
    $number_of_posts = (int) $number_of_posts;
    $category_show = $category_show == 'true' ? true : false;
    $enable_custom_height = $enable_custom_height == 'true' ? true : false;
    $excerpt_show = $excerpt_show == 'true' ? true : false;
    $image_size = in_array($image_size, array('full', 'large', 'medium', 'creative_post_slider_medium', 'thumbnail'), true) ? $image_size : 'full';
    $aspect_ratio = in_array($aspect_ratio, array('1:1', '4:3', '16:9', '16:10', '1:2'), true) ? $aspect_ratio : '16:9';
    $custom_slider_height = (int) $custom_slider_height . 'px';
    $resize_reference = (int) $resize_reference;
    $hover_icon_size = (int) $hover_icon_size . 'px';
    $link_type = $link_type != 'prettyphoto' ? 'post' : 'prettyphoto';
    $open_link_target = ($open_link_in == 'default') ? '_self' : '_blank';
    $enable_icon = $enable_icon != 'true' ? false : true;

    $html = '';
    $current_excerpt = '';
    $args = array('post_type' => 'post', 'post_status' => 'publish', 'order' => $category_order, 'orderby' => $order_by, 'category__in' => explode(',', $categories), 'posts_per_page' => $number_of_posts);
    $post_query = new WP_Query($args);

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
        if ((int) $animation_speed != 0) {
            $animate .= ' data-aspeed="' . $animation_speed . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $style = '<style type="text/css" scoped="scoped">' .
            ($enable_custom_height ? ('#' . $randomId . '.frb_creative_post_slider_container {height:' . $custom_slider_height . ' !important;}') : '') . '
		#' . $randomId . ' .frb_creative_post_slider_hover {background: ' . $hover_background_color . '; color:' . $hover_text_color . '; } .frb_creative_post_slider_hover a,.frb_creative_post_slider_hover a:hover,.frb_creative_post_slider_hover h3 a:hover { color:' . $hover_text_color . '; }
		#' . $randomId . ' .frb_creative_post_slider_hover > h3 {font-size: ' . $title_size . '; line-height: ' . $title_line_height . '; margin: 0;}
		#' . $randomId . ' .frb_creative_post_slider_hover > div {font-size: ' . $excerpt_size . '; line-height: ' . $excerpt_line_height . '; display:' . ($excerpt_show ? 'block' : 'none') . ';}
		#' . $randomId . ' .frb_creative_post_slider_hover > span {width:100%; display:' . ($category_show ? 'block' : 'none') . ';}
		#' . $randomId . ' .frb_creative_post_slider_hover > span > a {display:inline-block; font-size:' . $cat_size . '; line-height:' . $cat_line_height . ';}
		
		</style>';

    $html .= $animSpeedSet . $style . '<div id="' . $randomId . '" class="frb_creative_post_slider_container' . $class . $animate . ' data-spv="' . $slides_per_view . '" data-asr="' . $aspect_ratio . '" data-rref="' . $resize_reference . '" style="padding-bottom:' . $bot_margin . '" data-icnh="' . $hover_icon_size . '"><div class="frb_creative_post_slider_wrapper">';

    if ($post_query->have_posts()) {
        while ($post_query->have_posts()) {
            $post_query->the_post();
            $current_excerpt = get_the_excerpt();
            $current_excerpt = strlen($current_excerpt) >= $excerpt_lenght ? substr($current_excerpt, 0, $excerpt_lenght - strlen($current_excerpt)) . '...' : $current_excerpt;
            $cat_storage = array();
            $cat_storage = get_the_category();
            $cat_to_print = '';
            for ($i = 0; $i < count($cat_storage); $i++) {
                $cat_to_print .='<a href="' . get_category_link(get_cat_ID($cat_storage[$i]->name)) . '">' . ($cat_storage[$i]->name) . '</a>, ';
            }
            $cat_to_print = substr($cat_to_print, 0, -2);

            $html .= '<div class="frb_creative_post_slide"> 
					<div class="frb_creative_post_slide_inner">
						<a href="' . ($link_type == 'prettyphoto' ? (wp_get_attachment_url(get_post_thumbnail_id()) != '' ? wp_get_attachment_url(get_post_thumbnail_id()) : 'javascript:void(0);') : get_permalink()) . '" ' . ($link_type == 'prettyphoto' ? (get_the_post_thumbnail(get_the_id(), $image_size) != '' ? 'rel="frbprettyphoto" ' : 'style="cursor:default;"') : ($open_link_in == 'default' ? '' : 'target="_blank" ')) . ' class="frb_creative_post_slider_img_wrapper">
						
							' . (get_the_post_thumbnail(get_the_id(), $image_size) != '' ? get_the_post_thumbnail(get_the_id(), $image_size) : '<span class="frb_cps_no_image">No Image</span>') . '
						</a>
						' . ($enable_icon ? (get_the_post_thumbnail(get_the_id(), $image_size) != '' ? '<div class="frb_creative_link_icon"><i class="frb_icon ' . substr($hover_icon, 0, 2) . ' ' . $hover_icon . '" style="font-size:' . $hover_icon_size . '; line-height:' . $hover_icon_size . '; color:' . $hover_background_color . ';"></i></div>' : '') : '') . '
						<div class="frb_creative_post_slider_hover">
							<span>' . $cat_to_print . '</span>
							<h3><a href="' . get_permalink() . '" ' . ($open_link_in == 'default' ? '' : 'target="_blank" ') . '>' . get_the_title() . '</a></h3>
							<div>' . $current_excerpt . '</div>
						</div>
					</div>
				</div>';
        }
    }
    $html .= '</div></div>';
    wp_reset_postdata();


    return $html;
}

add_shortcode('pbuilder_creative_post_slider', 'pbuilder_creative_post_slider');
add_image_size('creative_post_slider_medium', 768, 9999);

/* ------------------ */
/* pbuilder_contact_form */
/* ------------------ */

function pbuilder_contact_form($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'bot_margin' => 24,
        'shortcode_id' => '',
        'class' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => '',
        'animation_speed' => 1000,
        'text_color' => '#333333',
        'background_color' => 'transparent',
		'placeholder_color' => '#CCCCCC',
        'border_color' => '#e7e7e7',
        'active_text_color' => '#cccccc',
        'active_background_color' => 'transparent',
        'active_border_color' => '#cccccc',
		'required_error_color' => '#FF0000',
        'custom_field' => 'false',
        'custom_ph' => __('Custom', 'profit-builder'),
        'email_ph' => __('E-mail Address', 'profit-builder'),
        'name_ph' => __('Name', 'profit-builder'),
        'website_ph' => __('Website', 'profit-builder'),
        'textarea_ph' => __('Message goes here', 'profit-builder'),
        'submit_ph' => __('Submit', 'profit-builder'),
        'button_text_color' => '#ffffff',
        'button_background_color' => '#555555',
        'button_border_color' => '#555555',
        'active_button_text_color' => '#ffffff',
        'active_button_background_color' => '#222222',
        'active_button_border_color' => '#222222',
        'button_align' => 'left',
        'required' => 'name,email,textarea',
        'response_message' => __('Message Successfully Sent!', 'profit-builder'),
        'button_fullwidth' => 'false',
        'recipient_email' => 'testemail@somewhere.com',
        'recipient_name' => 'Recipient',
        'send_response_delay' => 1500,
        'datepicker' => 'false'
                    ), $atts));
    $bot_margin = (int) $bot_margin . 'px';
    $randomId = $shortcode_id == '' ? 'frb_contact_form_' . rand() : $shortcode_id;
    $custom_field = ($custom_field == 'false' || $custom_field == false) ? false : true;
    $button_fullwidth = ($button_fullwidth == 'false' || $button_fullwidth == false) ? false : true;
    $custom_ph = $custom_ph == '' ? 'Custom' : $custom_ph;
    $recipient_email = $recipient_email == '' ? 'testemail@somewhere.com' : $recipient_email;
    $recipient_name = $recipient_name == '' ? 'Recipient' : $recipient_name;
    $send_response_delay = (int) $send_response_delay;
    $datepicker = $datepicker == 'false' ? false : true;

    $submit_proc = __('Sending...', 'profit-builder');


    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $required_exp = explode(',', $required);

    $resp = array('name' => '', 'email' => '', 'website' => '', 'custom' => '', 'textarea' => '');

    foreach ($required_exp as $val) {
        $resp[$val] = 'class="frb_required"';
    }

    $custom_field_html = $custom_field ? '<div class="frb_input_wrapper"><input class="' . $resp['custom'] . ($datepicker ? ' frb_contact_datepicker' : '') . '" type="text" name="custom" value="" placeholder="' . $custom_ph . '" />' . ($datepicker ? '<i class="fa fa-calendar frb_icon"></i>' : '') . '</div>' : '';
    $style = '<style type="text/css" scoped="scoped">
		#' . $randomId . '.frb_contact_form .frb_input_wrapper input[type="text"], #' . $randomId . '.frb_contact_form .frb_textarea_wrapper textarea {color:' . $text_color . '; background:' . $background_color . '; border: 3px solid ' . $border_color . '; transition: background-color 300ms, border-color 300ms;}
		#' . $randomId . '.frb_contact_form .frb_input_wrapper input[type="text"]:focus, .frb_contact_form .frb_textarea_wrapper textarea:focus {color:' . $active_text_color . ' !important; background:' . $active_background_color . ' !important; border: 3px solid ' . $active_border_color . ' !important;}
		#' . $randomId . '.frb_contact_form .frb_contact_submit input[type="submit"] {color:' . $button_text_color . '; background:' . $button_background_color . '; border: 3px solid ' . $button_border_color . '; text-align:center; display:' . ($button_fullwidth ? 'block; width:100%;' : 'inline-block') . '; transition: background-color 300ms, border-color 300ms;} 
		#' . $randomId . '.frb_contact_form .frb_contact_submit input[type="submit"]:hover {color:' . $active_button_text_color . '; background:' . $active_button_background_color . '; border: 3px solid ' . $active_button_border_color . ';} 
		#' . $randomId . '.frb_contact_form .frb_contact_submit {text-align:' . $button_align . ';}
		#' . $randomId . '.frb_contact_form {padding-bottom:' . $bot_margin . ';}
		#' . $randomId . '.frb_contact_form .frb_contact_form_overlay > div > div > div > div {color:' . $text_color . ';}
		#' . $randomId . '.frb_contact_form .frb_input_wrapper .frb_req_error, .frb_contact_form .frb_textarea_wrapper .frb_req_error {border-color:' . $required_error_color . ' !important; }
		#' . $randomId . '.frb_contact_form .frb_input_wrapper i {color:' . $text_color . ';}
		#' . $randomId . '.frb_contact_form input::-webkit-input-placeholder { color: '.$placeholder_color.'; }
		#' . $randomId . '.frb_contact_form input::-moz-placeholder { color: '.$placeholder_color.'; }
		#' . $randomId . '.frb_contact_form input:-moz-placeholder { color: '.$placeholder_color.'; }
		#' . $randomId . '.frb_contact_form input:-ms-input-placeholder { color: '.$placeholder_color.'; }
		#' . $randomId . '.frb_contact_form textarea::-webkit-input-placeholder { color: '.$placeholder_color.'; }
		#' . $randomId . '.frb_contact_form textarea::-moz-placeholder { color: '.$placeholder_color.'; }
		#' . $randomId . '.frb_contact_form textarea:-moz-placeholder { color: '.$placeholder_color.'; }
		#' . $randomId . '.frb_contact_form textarea:-ms-input-placeholder { color: '.$placeholder_color.'; }
	</style>';

    $jscript = '<script>
		(function($){
			$(document).ready(function(){
				$("#' . $randomId . '.frb_contact_form > form").data({"email" : "' . $recipient_email . '", "name" : "' . $recipient_name . '", "responseDelay" : ' . $send_response_delay . '});
				' . ($datepicker ? '$("#' . $randomId . '.frb_contact_form .frb_contact_datepicker").datepicker();' : '') . ' 
			});
		})(jQuery);
	</script>';

    $html = $animSpeedSet . $style . $jscript . '
		<div class="frb_contact_form' . $class . $animate . ' id="' . $randomId . '">
			<form action="#" method="post">
				<div class="frb_input_row">
					<div class="frb_input_wrapper"><input ' . $resp['email'] . ' type="text" name="e-mail" value="" placeholder="' . $email_ph . '" /></div>
					<div class="frb_input_wrapper"><input ' . $resp['name'] . ' type="text" name="name" value="" placeholder="' . $name_ph . '" /></div>
					<div class="frb_input_wrapper"><input ' . $resp['website'] . ' type="text" name="website" value="" placeholder="' . $website_ph . '" /></div>
					' . $custom_field_html . '
				</div>
				<div class="frb_textarea_wrapper"><textarea ' . $resp['textarea'] . ' name="message" placeholder="' . $textarea_ph . '"></textarea></div>
				<div class="frb_contact_submit"><input type="submit" name="submit" data-proc-val="' . $submit_proc . '" value="' . $submit_ph . '" /></div>
				<div class="frb_contact_form_overlay"><div class="frb_centering_system"><div><div><div class="frb_contact_response" data-text="' . $response_message . '">' . nl2br($response_message) . '</div></div></div></div></div>
			</form>
		</div>';

    return $html;
}

add_shortcode('pbuilder_contact_form', 'pbuilder_contact_form');

/* ------------------ */
/* pbuilder_graph */
/* ------------------ */

function pbuilder_graph($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'bot_margin' => 24,
        'shortcode_id' => '',
        'class' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => '',
        'animation_speed' => 1000,
        'graph_style' => 'bar', //  line or bar
        'item_align' => 'left', //  left, right, center
        'item_height' => 300,
        'item_width' => 1000,
        'labels' => 'January,February,March,April,May,June,July',
        'data_value' => '15,20,25,30,50,60,80',
        'data_caption' => 'Lorem Ipsum',
        'data_color' => '#44bdd6',
        'fill' => 'true',
        'curve' => 'true',
        'bar_stroke' => 'true',
        'scale_font_color' => '#666666',
        'legend_font_color' => '#222222',
        'legend_font_size' => 14,
        'legend_position' => 'bottom', // right, left, bottom
        'legend_shape' => 'circle' // square, round, circle
                    ), $atts));
    $bot_margin = (int) $bot_margin . 'px';
    $randomId = $shortcode_id == '' ? 'frb_graph_' . rand() : $shortcode_id;
    $legend_font_size = (int) $legend_font_size . 'px';
    $item_height = (int) $item_height;
    $item_width = (int) $item_width;

    if ($graph_style !== 'bar' && $graph_style !== 'line') {
        $graph_style = 'line';
    }
    if ($fill !== 'true' && $fill !== 'false') {
        $fill = 'true';
    }
    if ($curve !== 'true' && $curve !== 'false') {
        $curve = 'true';
    }
    if ($bar_stroke !== 'true' && $bar_stroke !== 'false') {
        $bar_stroke = 'true';
    }

    $dataValueExp = explode('|', $data_value);
    $dataColorExp = explode('|', $data_color);
    $dataCaptionExp = explode('|', $data_caption);

    global $pbuilder;
    $obj = '';

    $labelsReformat = explode(',', $labels);
    $labelsFormated = '';
    foreach ($labelsReformat as $value) {
        $labelsFormated .= '"' . $value . '",';
    }
    $labelsFormated = substr($labelsFormated, 0, -1);


    for ($i = 0; $i < count($dataValueExp); $i++) {
        if (substr($dataColorExp[$i], 0, 1) == '#') {
            $rgbtemp = $pbuilder->hex2rgb($dataColorExp[$i]);
            $rgba = 'rgba(' . $rgbtemp;
        } else {
            $rgbtemp = explode(',', $dataColorExp[$i]);
            $rgba = 'rgba' . substr($rgbtemp[0], 3) . ',' . $rgbtemp[1] . ',' . substr($rgbtemp[2], 0, -1);
        }

        $obj .= '{fillColor:"' . $rgba . ',0.5)",strokeColor:"' . $rgba . ',1)",pointColor:"' . $rgba . ',1)",pointStrokeColor: "#fff",data:[' . $dataValueExp[$i] . '],caption:"' . $dataCaptionExp[$i] . '"},';
    }
    $obj = substr($obj, 0, -1);

    $graphData = '{labels:[' . $labelsFormated . '], datasets:[' . $obj . ']}';

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    switch ($legend_shape) {
        case 'square':
            $legend_shape_val = '0px';
            break;
        case 'round':
            $legend_shape_val = '5px';
            break;
        case 'circle':
            $legend_shape_val = '50%';
            break;
    }

    if ($legend_position == 'left') {
        $firstFLoat = 'right';
        $secFloat = 'left';
        $secMargin = 'right';
        $legFloat = 'right';
        $padL = '10px';
        $padR = ($legend_font_size + 10) . 'px';
        $legTextAlign = 'right';
        $marL = '0px';
    } else if ($legend_position == 'right') {
        $firstFLoat = 'left';
        $secFloat = 'right';
        $secMargin = 'left';
        $legFloat = 'left';
        $padL = ($legend_font_size + 10) . 'px';
        $padR = '10px';
        $legTextAlign = 'left';
        $marL = '0px';
    } else if ($legend_position == 'bottom') {
        $firstFLoat = 'none';
        $secFloat = 'none';
        $secMargin = 'top';
        $legFloat = 'left';
        $padL = ($legend_font_size + 10) . 'px';
        $padR = '10px';
        $legTextAlign = 'left';
        $marL = '30px';
    }

    $script = '<script>
		(function($){
			$(document).ready(function(){
				$(document).on("onscreen", "#' . $randomId . ' .frb_graph_wrapper", function(){
					var ' . $randomId . ' = ' . $graphData . ';
					$("#' . $randomId . '").data({"graphData" : ' . $graphData . ', "legendFloat" : "' . $secFloat . '", graph_style : "' . $graph_style . '", fill : "' . $fill . '", curve : "' . $curve . '", barStroke : "' . $bar_stroke . '",scale_font_color : "' . $scale_font_color . '", itemWidth : "' . $item_width . '", legend_font_size:"' . $legend_font_size . '"});
					$("#' . $randomId . '").frbChartsLegendSetup(' . $randomId . ');
					$("#' . $randomId . '").frbGraphDraw();
				});	
			});
		})(jQuery);
	</script>';


    $style = '<style type="text/css" scoped="scoped">
		#' . $randomId . ' .frb_graph_wrapper canvas {float: ' . $firstFLoat . ';}
		#' . $randomId . ' .frb_charts_legend {float: ' . $secFloat . ';margin-left:' . $marL . '; margin-' . $secMargin . ':20px; color:' . $legend_font_color . ';}
		#' . $randomId . ' .frb_charts_legend_row {text-align:' . $legTextAlign . ';}
		#' . $randomId . ' .frb_charts_legend_row > div {float:' . $legFloat . '; border-radius:' . $legend_shape_val . '; height:' . $legend_font_size . '; width:' . $legend_font_size . ';}
		#' . $randomId . ' .frb_charts_legend_row > span {padding-left:' . $padL . '; padding-right:' . $padR . '; display:block; font-size:' . $legend_font_size . '; line-height:' . $legend_font_size . ';}
		#' . $randomId . '.frb_chart_resp .frb_charts_legend_row > span {padding-left:' . ($legend_font_size + 10) . 'px !important;}
	</style>';
    $html = $animSpeedSet . $style . '
		<div id="' . $randomId . '" style="text-align:' . $item_align . ';width:100%; padding-bottom:' . $bot_margin . ';" class="' . $class . $animate . '>
			<div style="display:inline-block">
				<div class="frb_graph_wrapper frb_animated">
					<canvas class="frb_graph_canvas" height="' . $item_height . '" width="' . $item_width . '" data-rel="' . $randomId . '"></canvas>
				</div>
			</div>
		</div>' . $script;

    return $html;
}

add_shortcode('pbuilder_graph', 'pbuilder_graph');


/* ------------------ */
/* pbuilder_gauge */
/* ------------------ */

function pbuilder_gauge_chart($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'bot_margin' => 24,
        'shortcode_id' => '',
        'class' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => '',
        'animation_speed' => 1000,
        'item_align' => 'center',
        'radius' => 200,
        'value' => 80,
        'min_value' => 0,
        'max_value' => 100,
        'unit' => '',
        'show_min_max' => 'true',
        'show_inner_shadow' => 'false',
        'animation_length' => 700,
        'value_color' => '#000000',
        'unit_color' => '#000000',
        'gauge_color' => '#000000',
        'gauge_thickness' => 2,
        'shadow_opacity' => 5,
        'shadow_size' => 20,
        'shadow_v_offset' => 5,
        'gauge_gradient_1' => '#000000',
        'gauge_gradient_2' => '#000000',
        'gauge_gradient_3' => '#000000',
        'gauge_gradient_4' => '#000000',
        'gauge_gradient_5' => '#000000'
                    ), $atts));

    $bot_margin = (int) $bot_margin . 'px';
    $randomId = $shortcode_id == '' ? 'frb_gauge_' . rand() : $shortcode_id;
    $radius = (int) $radius;
    $value = (int) $value;
    $min_value = (int) $min_value;
    $max_value = (int) $max_value;
    $animation_length = (int) $animation_length;
    $shadow_size = (int) $shadow_size;
    $shadow_v_offset = (int) $shadow_v_offset;
    $gauge_thickness = floatval($gauge_thickness) / 10;
    $shadow_opacity = floatval($shadow_opacity) / 10;
    $gauge_args = '{
          id: "' . $randomId . '_inner",
          value: 0, 
          min: ' . $min_value . ',
          max: ' . $max_value . ',
          title: " ",
          label: "' . $unit . '",
          valueFontColor: "' . $value_color . '",
          titleFontColor: "' . $value_color . '",
          labelFontColor: "' . $unit_color . '",
          gaugeColor: "' . $gauge_color . '",
          showMinMax: ' . $show_min_max . ',
          gaugeWidthScale: ' . $gauge_thickness . ',
          showInnerShadow: ' . $show_inner_shadow . ',
          shadowOpacity: ' . $shadow_opacity . ',
          shadowSize: ' . $shadow_size . ',
          shadowVerticalOffset: ' . $shadow_v_offset . ',
          levelColors: ["' . $gauge_gradient_1 . '","' . $gauge_gradient_2 . '","' . $gauge_gradient_3 . '","' . $gauge_gradient_4 . '","' . $gauge_gradient_5 . '"],
          levelColorsGradient : true,
          refreshAnimationTime: ' . $animation_length . ',
          startAnimationTime:0
        }';
    $html = '<div id="' . $randomId . '_inner" class="frb_gauge_shortcode"></div>
	 <script>
	      jQuery(\'#' . $randomId . '_inner\').data(\'gauge_init\',' . $gauge_args . ');
	      jQuery(\'#' . $randomId . '_inner\').data(\'gauge_value\',' . $value . ');
	      jQuery(\'#' . $randomId . '_inner\').data(\'gauge_width\',' . $radius . ');
	</script>';


    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}
	 					</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';



    $style = '<style type="text/css" scoped="scoped">	
				#' . $randomId . ' {text-align:' . $item_align . '; overlow:hidden;}
	 			#' . $randomId . '_inner {width:' . $radius . 'px; height:' . (0.6 * $radius) . 'px; display:inline-block;}
	 		</style>';

    $html = $animSpeedSet . $style . '
		<div id="' . $randomId . '" style="padding-bottom:' . $bot_margin . ';" class="frb_gauge_chart frb_animated ' . $class . $animate . '>' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_gauge_chart', 'pbuilder_gauge_chart');

/* ------------------ */
/* pbuilder_piecharts */
/* ------------------ */

function pbuilder_piechart($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'bot_margin' => 24,
        'shortcode_id' => '',
        'class' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => '',
        'animation_speed' => 1000,
        'color' => '#808080',
        'radius' => 220,
        'font_size' => 16,
        'item_align' => 'left',
        'legend_position' => 'bottom',
        'inner_cut' => 0,
        'stroke_width' => 15,
        'stroke_color' => "#ffffff",
        'data_value' => '15|14|13',
        'data_color' => '#6b58cd|#8677d4|#9c8ddc',
        'data_caption' => 'Lorem ipsum|Lorem ipsum|Lorem ipsum',
        'legend_shape' => 'square'
                    ), $atts));
    $bot_margin = (int) $bot_margin . 'px';
    $inner_cut = (int) $inner_cut;
    $inner_cut = ($inner_cut > 100) ? 100 : $inner_cut;
    $inner_cut = ($inner_cut < 0) ? 0 : $inner_cut;
    $stroke_width = (int) $stroke_width;
    $stroke_width = ($stroke_width > 10) ? 10 : $stroke_width;
    $stroke_width = ($stroke_width < 0) ? 0 : $stroke_width;
    $font_size = (int) $font_size . 'px';
    $stroke_color = ( $stroke_color == "") ? '#ffffff' : $stroke_color;
    $randomId = $shortcode_id == '' ? 'frb_piechart_' . rand() : $shortcode_id;

    $dataValueExp = explode('|', $data_value);
    $dataColorExp = explode('|', $data_color);
    $dataCaptionExp = explode('|', $data_caption);

    $obj = '{value:' . $dataValueExp[0] . ', color:"' . $dataColorExp[0] . '", caption:"' . $dataCaptionExp[0] . '"}';
    for ($i = 1; $i < count($dataValueExp); $i++) {
        $obj .=',{value:' . $dataValueExp[$i] . ', color:"' . $dataColorExp[$i] . '", caption:"' . $dataCaptionExp[$i] . '"}';
    }


    $strokeShow = ($stroke_width == 0) ? 'false' : 'true';

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $firstFLoat = 'left';
    $secFloat = 'right';
    $secMargin = 'left';
    $legFloat = 'left';
    $padL = '40px';
    $padR = '10px';
    $legTextAlign = 'left';
    if (!in_array($legend_position, array('left', 'right', 'bottom'), true)) {
        $legend_position = "right";
    }
    if (!in_array($legend_shape, array('square', 'round', 'circle'), true)) {
        $legend_shape = "square";
    }

    switch ($legend_shape) {
        case 'square':
            $legend_shape_val = '0px';
            break;
        case 'round':
            $legend_shape_val = '5px';
            break;
        case 'circle':
            $legend_shape_val = '50%';
            break;
    }

    if ($legend_position == 'left') {
        $firstFLoat = 'right';
        $secFloat = 'left';
        $secMargin = 'right';
        $legFloat = 'right';
        $padL = '10px';
        $padR = ($font_size + 10) . 'px';
        $legTextAlign = 'right';
    } else if ($legend_position == 'right') {
        $firstFLoat = 'left';
        $secFloat = 'right';
        $secMargin = 'left';
        $legFloat = 'left';
        $padL = ($font_size + 10) . 'px';
        $padR = '10px';
        $legTextAlign = 'left';
    } else if ($legend_position == 'bottom') {
        $firstFLoat = 'none';
        $secFloat = 'none';
        $secMargin = 'top';
        $legFloat = 'left';
        $padL = ($font_size + 10) . 'px';
        $padR = '10px';
        $legTextAlign = 'left';
    }

    $script = '<script>
		(function($){
			$(document).ready(function(){
				$(document).on("onscreen", "#' . $randomId . ' .frb_charts_wrapper", function(){
					var ' . $randomId . ' = [' . $obj . '];
					$("#' . $randomId . '").data({"obj" : [' . $obj . '], "font-size" : ' . (int) $font_size . ',"radius" : ' . $radius . ', "legendFloat" : "' . $secFloat . '", percentageInnerCutout : ' . $inner_cut . ', segmentShowStroke : ' . $strokeShow . ', segmentStrokeWidth : "' . $stroke_width . '", segmentStrokeColor : "' . $stroke_color . '",});
					$("#' . $randomId . '").frbChartsLegendSetup(' . $randomId . ');
					$("#' . $randomId . '").frbChartsDraw();	
				});	
			});
		})(jQuery);
	</script>';

    $style = '<style type="text/css" scoped="scoped">
		#' . $randomId . ' .frb_charts_wrapper canvas {float: ' . $firstFLoat . ';}
		#' . $randomId . ' .frb_charts_legend { color:'. $color .'; float: ' . $secFloat . '; margin-' . $secMargin . ':20px;}
		#' . $randomId . ' .frb_charts_legend_row {text-align:' . $legTextAlign . ';}
		#' . $randomId . ' .frb_charts_legend_row > div {float:' . $legFloat . '; border-radius:' . $legend_shape_val . ';  height:' . $font_size . '; width:' . $font_size . ';}
		#' . $randomId . ' .frb_charts_legend_row > span {padding-left:' . $padL . '; padding-right:' . $padR . '; font-size:' . $font_size . '; line-height:' . $font_size . ';}
		#' . $randomId . '.frb_chart_resp .frb_charts_legend_row > span {padding-left:' . ($font_size + 10) . 'px !important;}
	</style>';
    $html = $animSpeedSet . $style . '
		<div id="' . $randomId . '" style="text-align:' . $item_align . ';width:100%; padding-bottom:' . $bot_margin . ';" class="' . $class . $animate . '>
			<div style="display:inline-block">
				<div class="frb_charts_wrapper frb_animated">
					<canvas class="frb_piechart_canvas" height="' . $radius . '" width="' . $radius . '" data-rel="' . $randomId . '"></canvas>
				</div>
			</div>
		</div>' . $script;

    return $html;
}

add_shortcode('pbuilder_piechart', 'pbuilder_piechart');


/* ------------------------- */
/* pbuilder_percentage_chart */
/* ------------------------- */

function pbuilder_percentage_chart($atts) {

    extract(shortcode_atts(array(
        'bot_margin' => 24,
        'shortcode_id' => '',
        'color' => '#808080',
        'class' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => '',
        'animation_speed' => 1000,
        'percentage' => 73,
        'font_size' => 36,
        'line_color' => '#27a8e1',
        'background_line_color' => '#f2f2f2',
        'radius' => 200,
        'line_style' => 'square',
        'line_width' => 3,
        'item_align' => 'center',
        'percent_char' => 'true'
                    ), $atts));
    $bot_margin = (int) $bot_margin . 'px';
    $font_size = (int) $font_size . 'px';
    $radius = (int) $radius . 'px';
    $line_width = (int) $line_width . 'px';

    $randomId = $shortcode_id == '' ? 'frb_percentage_chart_' . rand() : $shortcode_id;
    if ($percent_char == 'true') {
        $percent_char = true;
    } else {
        $percent_char = false;
    }
    if ($percent_char != true && $percent_char != false) {
        $percent_char = true;
    }
    if ($percent_char) {
        $percent_char_set = '0.7em';
    } else {
        $percent_char_set = '0';
    }

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $style = '<style type="text/css" scoped="scoped">
		#' . $randomId . ' .frb_perchart_percent {color:' . $color . ';font-size:' . $font_size . ';line-height:' . $font_size . ';}
		#' . $randomId . ' .frb_percentage_chart {width:' . $radius . '; height: ' . $radius . ';}
		#' . $randomId . ' {text-align:' . $item_align . '; padding-bottom:' . $bot_margin . ';}
		#' . $randomId . ' .frb_perchart_percent > span {margin-top:-' . ((int) $font_size / 2) . 'px; color:' . $color . ';}
		#' . $randomId . ' .frb_perchart_percent > span:after { font-size: ' . $percent_char_set . '; color:' . $color . ';}
	</style>';

    $html = $animSpeedSet . $style . '<div id="' . $randomId . '">
					<span class="frb_percentage_chart frb_animated ' . $class . $animate . ' data-percent="' . $percentage . '" data-radius="' . (int) $radius . '" data-linewidth="' . $line_width . '" data-barcolor="' . $line_color . '" data-bgcolor="' . $background_line_color . '">
						<canvas class="frb_perchart_canvas" height="' . (int) $radius . '" width="' . (int) $radius . '" data-rel="' . $randomId . '"></canvas>
						<canvas class="frb_perchart_bg" height="' . (int) $radius . '" width="' . (int) $radius . '" data-rel="' . $randomId . '"></canvas>
						<span class="frb_perchart_percent">
							<span>' . $percentage . '</span>
						</span>
					</span>
				</div>';

    return $html;
}

add_shortcode('pbuilder_percentage_chart', 'pbuilder_percentage_chart');

/* ---------------- */
/* pbuilder_counter */
/* ---------------- */

function pbuilder_counter($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'bot_margin' => 24,
        'shortcode_id' => '',
        'color' => '#16a085',
        'class' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => '',
        'animation_speed' => 1000,
        'start_val' => 9999,
        'end_val' => 8847,
        'font_size' => 60,
        'direction' => 'auto',
        'item_align' => 'center'
                    ), $atts));
    $bot_margin = (int) $bot_margin . 'px';
    $font_size = (int) $font_size . 'px';
    $randomId = $shortcode_id == '' ? 'frb_counter_' . rand() : $shortcode_id;

    if ($direction != 'upward' && $direction != 'downward') {
        $direction = 'auto';
    }

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $style = '<style type="text/css" scoped="scoped">
		#' . $randomId . ' {padding-bottom:' . $bot_margin . ';}
		#' . $randomId . ' .frb_scrolling_counter {color:' . $color . ';font-size:' . $font_size . ' !important; min-height: ' . $font_size . ';}
		#' . $randomId . ' .frb_scrolling_counter  > .frb_scrl_count_digit_wrap > div {font-size:' . $font_size . ' !important;}
	</style>';
    $html = $animSpeedSet . $style . '<div id="' . $randomId . '" class="' . $class . $animate . ' style="width:100%; text-align:' . $item_align . ';"><div class="frb_scrolling_counter frb_animated" data-startval="' . $start_val . '" data-result="' . $end_val . '" data-direction="' . $direction . '"></div></div>';


    return $html;
}

add_shortcode('pbuilder_counter', 'pbuilder_counter');

/* ------------- */
/* pbuilder_list */
/* ------------- */

function pbuilder_list($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'icon' => 'fa-plus',
        'radius' => false,
        'color' => '#000000',
        'google_font' => 'default',
        'google_font_style' => 'default',
        'bot_margin' => 24,
        'shortcode_id' => '',
        'class' => '',
        'icon_color' => '#e95623',
        'icon_size' => '18',
        'font_size' => '18',
        'line_height' => 'default', //'18',
        'background' => 'transparent',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => '',
        'animation_speed' => 1000
                    ), $atts));

    global $pbuilder;
    $font_str = '';
    if ($google_font != 'default' && !in_array($google_font, $pbuilder->standard_fonts)) {
        if (is_admin())
            $font_str = '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all">';
        else
            wp_enqueue_style('pbuilder_' . $google_font . "_" . $google_font_style, '//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all');
    }

    $content = do_shortcode($content);

    $bot_margin = (int) $bot_margin . 'px';
    $randomId = $shortcode_id == '' ? 'frb_bullets_' . rand() : $shortcode_id;
    $line_height = $line_height != 'default' ? (int) $line_height . "px" : '110%';

    if ($radius == 'true') {
        $rad = '4px';
    } else {
        $rad = '0px';
    }
    if ($background == '') {
        $background = 'transparent';
    }
    if ($icon_color == '') {
        $icon_color = 'transparent';
    }

    $font_size = (int) $font_size . 'px';
    $icon_size = (int) $icon_size . 'px';

    $font_style = '';
    if ($google_font != 'default') {
        $font_style = 'font-family: ' . str_replace('+', ' ', $google_font) . ', serif !important; ';
        $ipos = strpos($google_font_style, 'italic');
        if ($google_font_style == 'regular') {
            //$font_style .= 'font-weight:400; font-style: normal !important; ';
        } else if ($ipos !== false) {
            if ($ipos > 0) {
                $font_style .= 'font-weight:' . substr($google_font_style, 0, $ipos) . '; ';
            } else {
                //$font_style .= 'font-weight: 400 !important; ';
            }
            $font_style .= 'font-style:italic !important; ';
        } else {
            //$font_style .= 'font-weight:' . $google_font_style . '; font-style: normal !important; ';
        }
    }

    if ($color == '') {
        $color = 'transparent';
    }
    $style = '
	<style type="text/css" scoped="scoped">
		#' . $randomId . ' {' . $font_style . ' padding-bottom:' . $bot_margin . '; line-height:' . $line_height . ';}
		#' . $randomId . ' li {' . $font_style . ' font-size:' . $font_size . '; color: ' . $color . '; background-color: ' . $background . '; border-radius: ' . $rad . '; line-height:' . $line_height . ';}
		#' . $randomId . ' li i {font-size:' . $icon_size . '; color: ' . $icon_color . '; line-height:' . $line_height . ';}
	</style>
	';


    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $html = $animSpeedSet . $style . '<ul class="frb_bullets_wrapper ' . $class . $animate . ' id="' . $randomId . '">';
    $cont = explode("\n", $content);
    foreach ($cont as $textline) {
        if (substr($icon, 0, 4) == 'icon') {
            $html .= '<li><i class="fawesome ' . $icon . '"></i>' . $textline . '</li>';
        } else {
            $html .= '<li><i class="frb_icon ' . substr($icon, 0, 2) . ' ' . $icon . '"></i>' . $textline . '</li>';
        }
    }
    $html .= '</ul>';
    return $html;
}

add_shortcode('pbuilder_list', 'pbuilder_list');


/* ------------------ */
/* pbuilder_separator */
/* ------------------ */

function pbuilder_separator($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'width' => 2,
        'style' => 'solid',
        'color' => '#27a8e1',
        'bot_margin' => 24,
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $content = do_shortcode($content);

    $randomId = $shortcode_id == '' ? 'frb_separator_' . rand() : $shortcode_id;
    $styleArray = array('solid', 'dashed', 'dotted', 'double');
    if (!in_array($style, $styleArray))
        $style = 'solid';
    $width = (int) $width . 'px';
    $bot_margin = (int) $bot_margin . 'px';
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $html = $animSpeedSet . '<div id="' . $randomId . '" class="frb_separator ' . $class . $animate . ' style="border-top:' . $width . ' ' . $style . ' ' . $color . '; padding-bottom:' . $bot_margin . ';"></div>';
    return $html;
}

add_shortcode('pbuilder_separator', 'pbuilder_separator');



/* --------------- */
/* pbuilder_slider */
/* --------------- */

function pbuilder_slider($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'ctype' => 'image',
        'image' => '',
        'image_link' => '',
        'image_link_type' => '',
        'iframe_width' => '600',
        'iframe_height' => '300',
        'html' => '',
        'text_align' => '',
        'back_color' => '',
        'text_color' => '',
        'responsive_layout' => 'false',
        'min_slide_width' => '200px',
        'bot_margin' => 24,
        'mode' => 'horizontal',
        'pagination' => 'true',
        'navigation' => 'none',
        'navigation_color' => '#ffffff',
        'slides_per_view' => 1,
        'auto_play' => 'true',
        'auto_delay' => 5,
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));
    $sliderId = ($shortcode_id != '' ? $shortcode_id : 'frb_slider_' . rand());

    $content = do_shortcode($content);
    $content = nl2br($content);

    $modeArray = array('horizontal', 'vertical');
    if (!in_array($mode, $modeArray))
        $mode = 'horizontal';
    $ctype = explode('|', $ctype); //image or html
    $content = explode('|', $content);
    $image = explode('|', $image);
    $image_link = explode('|', $image_link);
    $image_link_type = explode('|', $image_link_type);
    $text_align = explode('|', $text_align);
    $back_color = explode('|', $back_color);
    $text_color = explode('|', $text_color);
    $auto_delay = (int) $auto_delay;
    $slides_per_view = (int) $slides_per_view;
    $iframe_width = (int) $iframe_width;
    $iframe_height = (int) $iframe_height;
    $min_slide_width = (int) $min_slide_width;
    $responsive_layout = $responsive_layout == 'true' ? true : false;

    if ($navigation != 'none') {
        $nav_class = ' frb-swiper-nav-' . $navigation;
    } else {
        $nav_class = ' ';
    }

    $html = '
<style type="text/css" scoped="scoped">
	' . ($pagination != 'true' ? '#' . $sliderId . ' .frb-swiper-pagination {display:none;}' : '') . '
	#' . $sliderId . ' .frb-swiper-nav-squared .frb-swiper-nav-left:before,
	#' . $sliderId . ' .frb-swiper-nav-squared .frb-swiper-nav-right:before {
		background: ' . $navigation_color . ';
	}
	#' . $sliderId . ' .frb-swiper-nav-round .frb-swiper-nav-left:before,
	#' . $sliderId . ' .frb-swiper-nav-round .frb-swiper-nav-right:before {
		border-color: ' . $navigation_color . ';
		color: ' . $navigation_color . ';
	}
	
	
</style>
	
	
	    <div id="'.$sliderId.'_container" class="frb-swiper-container' . $nav_class . '" data-autoPlay="' . ($auto_play == 'true' ? $auto_delay * 1000 : '' ) . '" data-slidesPerView="' . $slides_per_view . '" data-mode="' . $mode . '" ' . ($responsive_layout ? 'data-min-res-width="' . $min_slide_width . '"' : '') . '>
	      <div class="swiper-wrapper">';



    if (is_array($ctype))
        foreach ($ctype as $ind => $type) {
            if ($type == 'image') {
                switch ($image_link_type[$ind]) {
                    case 'new-tab' : $lightbox = '" target="_blank';
                        break;
                    case 'lightbox-image' : $lightbox = ' frb_lightbox_link" rel="frbprettyphoto';
                        break;
                    case 'lightbox-iframe' : $lightbox = ' frb_lightbox_link"  rel="frbprettyphoto';
                        $image_link[$ind] .= '?iframe=true&width=' . $iframe_width . '&height=' . $iframe_height; /* &width=500&height=500 */ break;
                    default : $lightbox = '';
                }
                $html .='
			<div class="swiper-slide">' . (isset($image_link[$ind]) && $image_link[$ind] != '' ? '<a class="' . $lightbox . '" href="' . $image_link[$ind] . '"><img class="swiper-image" src="' . $image[$ind] . '" alt=""></a>' : '<img class="swiper-image" src="' . $image[$ind] . '" alt="">') . '</div>';
            } else {
                $html .='
	        <div class="swiper-slide" style="background:' . $back_color[$ind] . '; color:' . $text_color[$ind] . '; text-align:' . (isset($text_align[$ind]) ? $text_align[$ind] : 'left') . ';">
	          <div class="content-slide">
	            ' . $content[$ind] . '
	          </div>
	        </div>';
            }
        }


    $html .= '
	      </div>
		  <div class="frb-swiper-nav-left"></div><div class="frb-swiper-nav-right"></div>
	    	</div>
	    <div class="frb-swiper-pagination"></div>
	';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $sliderId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $sliderId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $html = $animSpeedSet . '<div id="' . $sliderId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_slider', 'pbuilder_slider');



/* ------------- */
/* pbuilder_code */
/* ------------- */

function pbuilder_code($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'bot_margin' => 24,
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $content = do_shortcode($content);
    $content = nl2br($content);

    $randomId = $shortcode_id == '' ? 'frb_code_' . rand() : $shortcode_id;
    $bot_margin = (int) $bot_margin . 'px';
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $html = $animSpeedSet . '<div id="' . $randomId . '" class="frb_code ' . $class . $animate . ' style="padding-bottom:' . $bot_margin . ';"><pre><code>' . $content . '</code></pre></div>';
    return $html;
}

add_shortcode('pbuilder_code', 'pbuilder_code');


/* --------------- */
/* pbuilder_button */
/* --------------- */

function pbuilder_button($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'text' => 'Get Instant Access',
        'url' => '',
        'url_target' => '_blank',
		'urlnofollow'=> '',
        'btype' => 'custom',
        'pname' => 'addtocart',
        'pcolor' => 'gold',
        'panimated' => 'false',
        'palign' => 'center',
        'google_font' => 'default',
        'google_font_style' => 'default',
        'css3btnstyle' => 'style1',
        'buttonwidth' => '230px',
        'buttonwidthfull' => 'true',
        'buttonheight' => '50px',
        'unit' => 'px',
        'image' => '',
        'icon' => 'no-icon',
        'type' => 'standard',
        'iframe_width' => '600',
        'iframe_height' => '300',
        'h_padding' => 20,
        'v_padding' => 20,
        'bot_margin' => 24,
        'font_size' => 34,
        'letter_spacing' => -1,
        'font_weight' => 'bold',
        'icon_size' => 16,
        'text_align' => 'center',
        'icon_align' => 'left',
        'fullwidth' => 'false',
        'round' => 'true',
        'fill' => 'true',
        'border_thickness' => '1px',
        'text_color' => '#ffffff', //'#222222',
        'back_color' => '#ff6600', //'#222222',
        'hover_text_color' => '#ffffff', //'#57bce8',
        'hover_back_color' => '#ff9900', //'#57bce8',
        'showcards' => 'false',
        'amex' => 'true',
        'pp' => 'true',
        'mc' => 'true',
        'visa' => 'true',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    global $pbuilder;
    $font_str = '';
    if ($google_font != 'default' && !in_array($google_font, $pbuilder->standard_fonts)) {
        if (is_admin())
            $font_str = '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all">';
        else
            wp_enqueue_style('pbuilder_' . $google_font . "_" . $google_font_style, '//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all');
    }

    //echo urldecode($url);
    $url = do_shortcode($url);
    $content = do_shortcode($content);

    $border_thickness = (int) $border_thickness;
    $alignArray = array('center', 'left', 'right');
    if (!in_array($text_align, $alignArray))
        $text_align = 'left';

    $btypeArray = array('image', 'custom', 'predone');
    if (!in_array($btype, $btypeArray))
        $btypeArray = 'custom';

    $typeArray = array('standard', 'new-tab', 'lightbox-image', 'lightbox-iframe');
    if (!in_array($type, $typeArray))
        $type = 'standard';
    $icon_alignArray = array('left', 'right', 'inline');
    if (!in_array($icon_align, $icon_alignArray))
        $icon_align = 'right';
    $font_size = (int) $font_size . 'px';
    $letter_spacing = (int) $letter_spacing . 'px';
    $icon_size = (int) $icon_size . 'px';
    $h_padding = (int) $h_padding . 'px';
    $v_padding = (int) $v_padding . 'px';
    $iframe_width = (int) $iframe_width;
    $iframe_height = (int) $iframe_height;
    $randomId = $shortcode_id == '' ? 'frb_button_' . rand() : $shortcode_id;
    $content = nl2br($content);

    $font_style = '';
    if ($google_font != 'default') {
        $font_style = 'font-family: ' . str_replace('+', ' ', $google_font) . ', serif !important; ';
        $ipos = strpos($google_font_style, 'italic');
        if ($google_font_style == 'regular') {
            //$font_style .= 'font-weight:400; font-style: normal !important; ';
        } else if ($ipos !== false) {
            if ($ipos > 0) {
                $font_style .= 'font-weight:' . substr($google_font_style, 0, $ipos) . '; ';
            } else {
                //$font_style .= 'font-weight: 400 !important; ';
            }
            $font_style .= 'font-style:italic !important; ';
        } else {
            $font_style .= 'font-weight:' . $google_font_style . '; font-style: normal !important; ';
        }
    }

	if($urlnofollow == 'true' ){
		$urlnofollow=' rel="nofollow" ';	
	} else {
		$urlnofollow='';	
	}
	
	
    switch ($btype) {

        case 'custom' :
            $style = 'style="' . $font_style .
                    'font-size:' . $font_size . '; ' .
                    'line-height:' . $font_size . '; ' .
                    'letter-spacing:' . $letter_spacing . ';' .
                    'font-weight:' . $font_weight . '; ' .
                    'padding:' . $v_padding . ' ' . $h_padding . '; ' .
                    'color:' . ($text_color == '' ? 'transparent' : $text_color) . '; ' .
                    'width: ' . ($buttonwidthfull == 'true' ? '' : $buttonwidth) . ';' .
                    'display: ' . ($buttonwidthfull == 'true' ? 'block' : 'inline-block') . ';' .
                    //'height: '.$buttonheight.';'.
                    //'line-height:'.((str_replace("px", "", $buttonheight))-(str_replace("px", "", $v_padding)*2)).'px; './/$font_size
                    'background:' . ($back_color == '' ? 'transparent' : $back_color) . '; ' .
                    'border: ' . ($fill != 'true' ? $border_thickness : '0') . 'px solid ' . ($back_color == '' ? 'transparent' : $back_color) . ';" ' .
                    'data-textcolor="' . $text_color . '" ' .
                    'data-backcolor="' . $back_color . '" ' .
                    'data-hovertextcolor="' . $hover_text_color . '" ' .
                    'data-hoverbackcolor="' . $hover_back_color . '"';

            switch ($type) {
                case 'new-tab' : $lightbox = '" target="_blank"';
                    break;
                case 'lightbox-image' : $lightbox = ' frb_lightbox_link" rel="frbprettyphoto';
                    break;
                case 'lightbox-iframe' : $lightbox = ' frb_lightbox_link"  rel="frbprettyphoto';
                    $url .= '?iframe=true&width=' . $iframe_width . '&height=' . $iframe_height; /* &width=500&height=500 */ break;
                default : $lightbox = '';
            }

            $align = ' frb_' . $text_align;
            $round = ($round == 'true' ? ' frb_round' : '');
            $no_fill = ($fill != 'true' ? ' frb_nofill' : '');
            $fullwidth = ($buttonwidthfull == 'true' ? ' frb_fullwidth' : '');
            switch ($icon_align) {
                case 'right' : $icon_style = 'padding-left:8px; float:right; font-size:' . $icon_size . '; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                    break;
                case 'left' : $icon_style = 'padding-right:8px; float:left; font-size:' . $icon_size . '; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                    break;
                case 'inline' : $icon_style = 'padding-right:8px; font-size:' . $icon_size . '; float:none; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                    break;
            }

            if ($icon != '' && $icon != 'no-icon') {
                if (substr($icon, 0, 4) == 'icon') {
                    $icon = '<span class="frb_button_icon" style="' . $icon_style . '" data-hovertextcolor="' . $hover_text_color . '"><i class="' . $icon . ' fawesome"></i></span>';
                } else {
                    $icon = '<span class="frb_button_icon" style="' . $icon_style . '" data-hovertextcolor="' . $hover_text_color . '"><i class="' . substr($icon, 0, 2) . ' ' . $icon . ' frb_icon"></i></span>';
                }
            } else {
                $icon = '';
            }

            $html = '<a '.$urlnofollow.' target="' . $url_target . '" class="frb_button' . $round . ' ' . $align . $fullwidth . $no_fill . $lightbox . '" href="' . $url . '" ' . $style . '>' . $icon . $text . '</a>';
            break;

        case 'predone' :
            $pname = str_replace(" ", "", $pname);
            if ($panimated == "true") {
                $suffix = ".gif";
            } else {
                $suffix = ".png";
            }
            $imagename = $pname . $pcolor . $suffix;
            $html = '<div style="text-align:center;"><a '.$urlnofollow.' href="' . $url . '"><img src="' . IMSCPB_URL . '/images/buttons/' . $imagename . '"></a></div>';

            break;

        case 'css3' :
            $style = 'style="' . $font_style .
                    'font-size:' . $font_size . '; ' .
                    'line-height:' . $font_size . '; ' .
                    'letter-spacing:' . $letter_spacing . '; ' .
                    'font-weight:' . $font_weight . '; ' .
                    'text-align: ' . $text_align . ';' .
                    'padding:' . $v_padding . ' ' . $h_padding . '; ' .
                    'color:' . ($text_color == '' ? '#ffffff' : $text_color) . '; ' .
                    'width: ' . ($buttonwidthfull == 'true' ? '' : $buttonwidth) . ';' .
                    //'height: '.$buttonheight.';'.
                    //'line-height:'.$buttonheight.'; './/$font_size
                    'display: ' . ($buttonwidthfull == 'true' ? 'block' : 'inline-block') . ';' .
                    'text-decoration: none;' .
                    'margin-bottom: 7px;"';

            $html = '<div style="text-align:center;"><a '.$urlnofollow.' class="pbcss3button' . $css3btnstyle . '" ' . $style . ' href="' . $url . '" ' . ($type == 'new-tab' ? ' target="_blank" ' : "") . '><span class="text" style="font-size:' . $font_size . ';">' . $text . '</span></a></div>';

            break;

        case 'image' :
            $html = '<div style="text-align:center;"><a '.$urlnofollow.' href="' . $url . '"><img src="' . $image . '"></a></div>';
    }

    if ($showcards == 'true') {
        $cardsselected = array();
        if ($amex == 'true') {
            $cardsselected[] = "<img src='" . IMSCPB_URL . "/images/buttons/amex.png'>";
        }
        if ($visa == 'true') {
            $cardsselected[] = "<img src='" . IMSCPB_URL . "/images/buttons/visa.png'>";
        }
        if ($mc == 'true') {
            $cardsselected[] = "<img src='" . IMSCPB_URL . "/images/buttons/mc.png'>";
        }
        if ($pp == 'true') {
            $cardsselected[] = "<img src='" . IMSCPB_URL . "/images/buttons/pp.png'>";
        }
        $html.="<div style='clear:both'><div style='text-align:center;" . ($btype == 'custom' ? 'margin-top:10px;' : '') . "'>" . implode("&nbsp;", $cardsselected) . "</div></div>";
    }
	
	
    if (isset($align) && $align == ' frb_center')
        $html = '<div class="frb_textcenter">' . $html . '</div>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $html = $font_str . $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '<div style="clear:both;"></div></div>';

    return $html;
}

add_shortcode('pbuilder_button', 'pbuilder_button');


/* --------------- */
/* pbuilder_optin */
/* --------------- */

function pbuilder_optin($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'formcode' => '',
        'formurl' => '',
        'formmethod' => 'POST',
        'namefield' => 'name',
        'nameimage' => IMSCPB_URL . '/images/icons/nameicon.png',
        'namerequired' => 'true',
        'nameerror' => 'Please enter your first name',
        'emailfield' => 'email',
        'emailimage' => IMSCPB_URL . '/images/icons/email.png',
        'emailerror' => 'Please enter an email',
        'formstyle' => 'Vertical',
        'fieldbg' => 'true',
        'fieldtextcolor' => '#111111',
        'disablename' => 'false',
        'newwindow' => 'false',
        'customfields' => 'false',
        'hiddenfields' => 'false',
        'google_font' => 'default',
        'google_font_style' => 'default',
        'leadin' => 'Enter your name and email below and click the button to get started now...',
        'privacy' => 'We value your privacy and will never spam you',
        'emailvalue' => 'Enter your email address...',
        'namevalue' => 'Enter your first name...',
        'text' => 'Get Instant Access',
        'formbgtransparent' => 'true',
        'formborder' => 'false',
        'formroundedsize' => '10',
        'formbgcolor' => '#ffffff',
        'formbordercolor' => '#cccccc',
        'formtextcolor' => '#111111',
        'formpadding' => '10',
        'fieldbgtransparent' => 'false',
        'fieldbgcolor' => '#ffffff',
        'fieldfontsize' => '18px',
        'enabletwostep' => 'false',
        'leadin2step' => 'Change this text to be a great call to action to click initially...',
        'buttontext' => 'Click to Learn More',
        'btype' => 'custom',
        'pname' => 'addtocart',
        'pcolor' => 'gold',
        'panimated' => 'false',
        'palign' => 'center',
        'css3btnstyle' => 'style1',
        'buttonwidth' => '230px',
        'buttonwidthfull' => 'true',
        'buttonheight' => '50px',
        'unit' => 'px',
        'image' => '',
        'icon' => 'no-icon',
        'type' => 'standard',
        'iframe_width' => '600',
        'iframe_height' => '300',
        'h_padding' => 10,
        'v_padding' => 10,
        'bot_margin' => 24,
        'font_size' => 20,
        'letter_spacing' => -1,
        'font_weight' => 'bold',
        'icon_size' => 16,
        'text_align' => 'center',
        'icon_align' => 'left',
        'fullwidth' => 'true',
        'round' => 'true',
        'fill' => 'true',
        'border_thickness' => '1px',
        'text_color' => '#ffffff',
        'back_color' => '#ff6600',
        'hover_text_color' => '#ffffff', //'#ffffff',
        'hover_back_color' => '#ff9900',
        'showcards' => 'false',
        'gotowebinarenable' => 'false',
        'gotowebinarshowbar' => 'false',
        'upcommingwebinar' => '',
        'gotowebinarurl' => '',
        'amex' => 'true',
        'pp' => 'true',
        'mc' => 'true',
        'visa' => 'true',
        //'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    global $pbuilder;
    $font_str = '';
    if ($google_font != 'default' && !in_array($google_font, $pbuilder->standard_fonts)) {
        if (is_admin())
            $font_str = '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all">';
        else
            wp_enqueue_style('pbuilder_' . $google_font . "_" . $google_font_style, '//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all');
    }

    $url = do_shortcode(@$url);
    $content = do_shortcode($content);

    $border_thickness = (int) $border_thickness;
    $alignArray = array('center', 'left', 'right');
    if (!in_array($text_align, $alignArray))
        $text_align = 'left';

    $btypeArray = array('image', 'custom', 'predone');
    if (!in_array($btype, $btypeArray))
        $btypeArray = 'custom';

    $typeArray = array('standard', 'new-tab', 'lightbox-image', 'lightbox-iframe');
    if (!in_array($type, $typeArray))
        $type = 'standard';

    $icon_alignArray = array('left', 'right', 'center');
    if (!in_array($icon_align, $icon_alignArray))
        $icon_align = 'right';

    $font_size = (int) $font_size . 'px';
    $letter_spacing = (int) $letter_spacing . 'px';
    $icon_size = (int) $icon_size . 'px';
    $h_padding = (int) $h_padding . 'px';
    $v_padding = (int) $v_padding . 'px';

    $formpadding = (int) $formpadding . 'px';
    $formroundedsize == (int) $formroundedsize . 'px';

    $iframe_width = (int) $iframe_width;
    $iframe_height = (int) $iframe_height;
    $randomId = $shortcode_id == '' ? 'frb_optin_' . rand() : $shortcode_id;
    $content = nl2br($content);

    $leadin2step = html_entity_decode($leadin2step);
    $leadin = html_entity_decode($leadin);
    //$hiddenfields_str=html_entity_decode($hiddenfields_str);	

    $hiddenfields_str = '';

    if ($hiddenfields != "false") {
        $hidden_fields = array_filter(
                array_keys($atts), function($key) {
            return (
                    substr_count($key, "hiddenfield") > 0 &&
                    substr_count($key, "hiddenfieldname") <= 0 &&
                    substr_count($key, "hiddenfieldtype") <= 0 &&
                    $key != "addhiddenfield" &&
                    $key != "hiddenfieldsdiv" &&
                    $key != "hiddenfields"
                    );
        }
        );

        if (is_array($hidden_fields) && count($hidden_fields) > 0) {
            foreach ($hidden_fields as $hidden_field) {
                $ind = str_replace("hiddenfield", "", $hidden_field);
                $hiddenfields_str .= '<input type="hidden" name="' . $atts['hiddenfieldname' . $ind] . '" value="' . $atts[$hidden_field] . '" />' . "\r\n";
            }
        }
    }
    global $wpdb, $pbuilder;

    $table_name = $wpdb->prefix . 'profit_builder_extensions';
    $extension = $wpdb->get_results('SELECT name FROM ' . $table_name . ' where name = "profit_builder_instant_gotowebinar" ', ARRAY_A);
    $imscpbiw_access_response = $pbuilder->options(" WHERE name = 'imscpbiw_access_response'");
    $imscpbiw_access_response = json_decode(@$imscpbiw_access_response[0]->value);
    if (!empty($extension[0]['name']) && !empty($imscpbiw_access_response->access_token) && $gotowebinarenable != "false") {
        if ($gotowebinarenable != "false") {
            $hiddenfields_str .= '<input type="hidden" name="gotowebinarkeys" value="' . $upcommingwebinar . '" />' . "\r\n";
        }
        if (!empty($gotowebinarurl)) {

            $hiddenfields_str .= '<input type="hidden" name="gotowebinarurl" id="gotowebinarurl" value="' . $gotowebinarurl . '" />' . "\r\n";
        }
    }
    $customfields_str = '';
    if ($customfields != "false") {
        $custom_fields = array_filter(
                array_keys($atts), function($key) {
            return (
                    substr_count($key, "customfield") > 0 &&
                    substr_count($key, "customfieldlabel") <= 0 &&
                    substr_count($key, "customfieldtype") <= 0 &&
                    substr_count($key, "customfieldrequired") <= 0 &&
                    substr_count($key, "customfielderror") <= 0 &&
                    $key != "addcustomfield" &&
                    $key != "customfieldsdiv" &&
                    $key != "customfields"
                    );
        }
        );
        /*
         * Code added by Asim Ashraf - DevBatch
         * DateTime: 28 Jan 2015
         * Edit Start
         */

        if (is_array($custom_fields) && count($custom_fields) > 0) {
            foreach ($custom_fields as $custom_field) {
                $ind = str_replace("customfield", "", $custom_field);
                $fieldvalue = $atts[$custom_field];
                $fieldlabel = $atts['customfieldlabel' . $ind];
                $required = isset($atts['customfieldrequired' . $ind]) && $atts['customfieldrequired' . $ind] == "true";
                $error = isset($atts['customfielderror' . $ind]) ? $atts['customfielderror' . $ind] : "This field cannot be blank";
                //$customfields_str .= '<div class="field"><input type="text" name="'.$custom_field.'" value="'.($fieldvalue==""?$fieldlabel:$fieldvalue).'" padding-left="10" padding-right="'.($fieldbg=="true"?"33":"0").'" style="padding-right:0px; padding-left:0px; font-size:'.($fieldfontsize).'; background-color:'.($fieldbgtransparent=="true"?"transparent":$fieldbgcolor).';color:'.$fieldtextcolor.';" onfocus=" if (this.value == \''.$fieldlabel.'\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\''.$fieldlabel.'\';} " default-value="'.$fieldlabel.'" /></div>';
                if (strpos($fieldvalue, 'b_') !== false) {
                    //$hiddenfields_str .= '<input type="hidden" name="' . $fieldvalue . '" id="' . $fieldvalue . '" value="" />';
                } else {
                    $customfields_str .= '<div class="field"><input type="text" ' . ($required == 'true' ? ' validation="required" ' : '') . ' class="" error-message="' . ($required ? $error : '') . '" name="' . $fieldvalue . '" value="' . $fieldlabel . '" padding-left="10" padding-right="' . ($fieldbg == "true" ? "33" : "0") . '" style="padding-right:0px; padding-left:0px; font-size:' . ($fieldfontsize) . '; background-color:' . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor) . ';color:' . $fieldtextcolor . ' !important;" onfocus=" if (this.value == \'' . $fieldlabel . '\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'' . $fieldlabel . '\';} " default-value="' . $fieldlabel . '" /></div>';
                }
            }
        }
    }
    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit End
     */
    if ($formbgtransparent == "true")
        $formbgcolor = 'transparent';

    $font_style = '';
    if ($google_font != 'default') {
        $font_style = 'font-family: ' . str_replace('+', ' ', $google_font) . ', serif !important; ';
        $ipos = strpos($google_font_style, 'italic');
        if ($google_font_style == 'regular') {
            //$font_style .= 'font-weight:400; font-style: normal !important; ';
        } else if ($ipos !== false) {
            if ($ipos > 0) {
                $font_style .= 'font-weight:' . substr($google_font_style, 0, $ipos) . '; ';
            } else {
                //$font_style .= 'font-weight: 400 !important; ';
            }
            $font_style .= 'font-style:italic !important; ';
        } else {
            $font_style .= 'font-weight:' . $google_font_style . '; font-style: normal !important; ';
        }
    }

    $html = "";

    $html.='<style>.optin' . $randomId . '{' . ($formborder == 'true' ? 'border-width: 2px;border-color: ' . $formbordercolor . ';border-radius: ' . $formroundedsize . ';border-style: solid;' : 'border:none;') . 'padding:' . $formpadding . ';background-color:' . $formbgcolor . ';</style>';
    $html.='<form method="' . $formmethod . '" action="' . $formurl . '" class="optin optinF optin' . $randomId . ' optin_style_' . $formstyle . '" name="optin' . $randomId . '" id="optin' . $randomId . '" accept-charset="UTF-8" autocomplete><div class="content">';

    if (!empty($hiddenfields_str))
        $html.='<div style="display: none;">' . $hiddenfields_str . '</div>';

    if ($enabletwostep == "true") {
        $html.='
            <div id="twostep1' . $randomId . '" style="display:none;">
                <div id="leadin2step" style="color:' . $formtextcolor . '; text-align:center;">' . $leadin2step . '</div>
        ';

        switch ($btype) {
            case 'custom' :
                $style = 'style="' . $font_style .
                        'font-size:' . $font_size . '; ' .
                        'line-height:' . $font_size . '; ' .
                        'text-align:' . $text_align . '; ' .
                        'letter-spacing:' . $letter_spacing . '; ' .
                        'font-weight:' . $font_weight . '; ' .
                        'padding:' . $v_padding . ' ' . $h_padding . '; ' .
                        'color:' . ($text_color == '' ? 'transparent' : $text_color) . '; ' .
                        'background:' . ($back_color == '' ? 'transparent' : $back_color) . '; ' .
                        'border: ' . ($fill != 'true' ? $border_thickness : '0') . 'px solid ' . ($back_color == '' ? 'transparent' : $back_color) . ';" ' .
                        'data-textcolor="' . $text_color . '" ' .
                        'data-backcolor="' . $back_color . '" ' .
                        'data-hovertextcolor="' . $hover_text_color . '" ' .
                        'data-hoverbackcolor="' . $hover_back_color . '"';

                $align = ' frb_' . $text_align;
                $round = ($round == 'true' ? ' frb_round' : '');
                $no_fill = ($fill != 'true' ? ' frb_nofill' : '');
                $fullwidth = ($buttonwidthfull == 'true' ? ' frb_fullwidth' : '');
                switch ($icon_align) {
                    case 'right' : $icon_style = 'padding-left:8px; float:right; font-size:' . $icon_size . '; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                        break;
                    case 'left' : $icon_style = 'padding-right:8px; float:left; font-size:' . $icon_size . '; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                        break;
                    case 'inline' : $icon_style = 'padding-right:8px; font-size:' . $icon_size . '; float:none; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                        break;
                }

                if ($icon != '' && $icon != 'no-icon') {
                    if (substr($icon, 0, 4) == 'icon')
                        $icon = '<span class="frb_button_icon" style="' . $icon_style . '" data-hovertextcolor="' . $hover_text_color . '"><i class="' . $icon . ' fawesome"></i></span>';
                    else
                        $icon = '<span class="frb_button_icon" style="' . $icon_style . '" data-hovertextcolor="' . $hover_text_color . '"><i class="' . substr($icon, 0, 2) . ' ' . $icon . ' frb_icon"></i></span>';
                } else
                    $icon = '';

                $html .= '<a class="frb_button' . $round . ' ' . $align . $fullwidth . $no_fill . $lightbox . '" id="twostepbutton1' . $randomId . '" ' . $style . '>' . $buttontext . '</a>';
                break;
            case 'predone' :
                $pname = str_replace(" ", "", $pname);
                $suffix = $panimated == "true" ? ".gif" : ".png";
                $imagename = $pname . $pcolor . $suffix;
                $html .= '<div style="text-align:center;"><a id="twostepbutton1' . $randomId . '"><img src="' . IMSCPB_URL . '/images/buttons/' . $imagename . '"></a></div>';
                break;

            case 'css3' :
                $style = 'style="' . $font_style .
                        'font-size:' . $font_size . '; ' .
                        'line-height:' . $font_size . '; ' .
                        'text-align:' . $text_align . '; ' .
                        'letter-spacing:' . $letter_spacing . '; ' .
                        'font-weight:' . $font_weight . '; ' .
                        'padding:' . $v_padding . ' ' . $h_padding . '; ' .
                        'color:' . ($text_color == '' ? '#ffffff' : $text_color) . '; ' .
                        'width: ' . ($buttonwidthfull == 'true' ? '' : $buttonwidth) . ';' .
                        //'height: '.$buttonheight.';'.
                        //'line-height:'.$buttonheight.'; '.
                        'display: ' . ($buttonwidthfull == 'true' ? 'block' : 'inline-block') . ';' .
                        'text-decoration: none;' .
                        'margin-bottom: 7px;"';

                $html .= '<div style="text-align:center;"><a class="pbcss3button' . $css3btnstyle . '" ' . $style . ' id="twostepbutton1' . $randomId . '"><span class="text" style="font-size:' . $font_size . ';">' . $buttontext . '</span></a></div>';
                break;

            case 'image' :
                $html .= '<div style="text-align:center;"><a id="twostepbutton1' . $randomId . '"><img src="' . $image . '"></a></div>';
        }
        $html.='</div><div id="twostep2' . $randomId . '" style="display:none;">';
    }
    if (!empty($extension[0]['name'])) {
        if ($gotowebinarshowbar != 'false') {
            $html.='<div class="jquery-ui-like" id="progressBar"><div style="width: 50%;">50%&nbsp;</div></div>';
        }
    }
    if (!empty($leadin))
        $html.='<div id="leadin" style="color:' . $formtextcolor . '; text-align:center;">' . $leadin . '</div>';

//	if ($disablename=='false' && $formstyle != 'Horizontal')
    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit Start
     */
    if ($formstyle == 'Horizontal') {

        $fieldWidht = $disablename == 'false' ? "width:33% !important;" : "width:66% !important;";

        if ($disablename == 'false')
            $html.='<div class="field Hfield" style="width:33% !important;"><input type="text" ' . ($namerequired == 'true' ? ' validation="required" ' : '') . ' class="" error-message="' . ($namerequired == 'true' ? $nameerror : '') . '" name="' . $namefield . '" value="' . $namevalue . '" padding-left="10" padding-right="' . ($fieldbg == "true" ? "33" : "0") . '" style="padding-left:0px; font-size:' . ($fieldfontsize) . '; ' . ($fieldbg == "true" ? " background:url('" . $nameimage . "') no-repeat 98% center " . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor) . "; padding-right: 0px; width:100%;" : 'background-color:' . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor)) . ';color:' . $fieldtextcolor . ' !important;" onfocus=" if (this.value == \'' . $namevalue . '\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'' . $namevalue . '\';} "  default-value="' . $namevalue . '" /></div>';

        $html.='<div class="field Hfield hRfield1" style="' . $fieldWidht . '"><input type="text" class="" validation="required email" error-message="' . $emailerror . '" name="' . $emailfield . '" value="' . $emailvalue . '" padding-left="10" padding-right="' . ($fieldbg == "true" ? "33" : "0") . '" style="padding-left:0px; font-size:' . ($fieldfontsize) . '; ' . ($fieldbg == "true" ? " background:url('" . $emailimage . "') no-repeat 98% center " . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor) . "; padding-right: 0px;   width:100%;" : 'background-color:' . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor)) . ';color:' . $fieldtextcolor . ' !important;" onfocus=" if (this.value == \'' . $emailvalue . '\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'' . $emailvalue . '\';} " default-value="' . $emailvalue . '" /></div>';
    } else {
        if ($disablename == 'false' && $formstyle != 'Horizontal')
            $html.='<div class="field"><input type="text" ' . ($namerequired == 'true' ? ' validation="required" ' : '') . ' error-message="' . ($namerequired == 'true' ? $nameerror : '') . '" name="' . $namefield . '" value="' . $namevalue . '" padding-left="10" padding-right="' . ($fieldbg == "true" ? "33" : "0") . '" style="padding-left:0px; font-size:' . ($fieldfontsize) . '; ' . ($fieldbg == "true" ? " background:url('" . $nameimage . "') no-repeat 98% center " . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor) . "; padding-right: 0px; width:100%;" : 'background-color:' . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor)) . ';color:' . $fieldtextcolor . ' !important;" onfocus=" if (this.value == \'' . $namevalue . '\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'' . $namevalue . '\';} "  default-value="' . $namevalue . '" /></div>';

        //$html.='<div class="field" style="'.($formstyle == 'Horizontal'?' width:50%; display:inline-block; float: left;':'').'"><input type="text" class="validate[funcCall[checkoptinrequired],custom[email]]" error-message="'.$emailerror.'" name="'.$emailfield.'" value="'.$emailvalue.'" padding-left="10" padding-right="'.($fieldbg=="true"?"33":"0").'" style="padding-left:0px; font-size:'.($fieldfontsize).'; '.($fieldbg=="true"?" background:url('".$emailimage."') no-repeat 98% center ".($fieldbgtransparent=="true"?"transparent":$fieldbgcolor)."; padding-right: 0px; width: 100%;":'background-color:'.($fieldbgtransparent=="true"?"transparent":$fieldbgcolor)).';color:'.$fieldtextcolor.' !important;" onfocus=" if (this.value == \''.$emailvalue.'\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\''.$emailvalue.'\';} " default-value="'.$emailvalue.'" /></div>'; 

        $html.='<div class="field"><input type="text" validation="required email" class="validate[funcCall[checkoptinrequired],custom[email]]" error-message="' . $emailerror . '" name="' . $emailfield . '" value="' . $emailvalue . '" padding-left="10" padding-right="' . ($fieldbg == "true" ? "33" : "0") . '" style="padding-left:0px; font-size:' . ($fieldfontsize) . '; ' . ($fieldbg == "true" ? " background:url('" . $emailimage . "') no-repeat 98% center " . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor) . "; padding-right: 0px;   width:100%;" : 'background-color:' . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor)) . ';color:' . $fieldtextcolor . ' !important;" onfocus=" if (this.value == \'' . $emailvalue . '\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'' . $emailvalue . '\';} " default-value="' . $emailvalue . '" /></div>';
    }
    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit Start
     */
//	$FieldwidthHori = $formstyle == 'Horizontal' ? "width: 49% !important; float: left !important; margin-right10px;" : "width: 100%";
    //$FieldFloatHori = $formstyle == 'Horizontal' ? "float: left !important;" : "float: none;";


    if (!empty($customfields_str) && $formstyle != 'Horizontal')//$disablename == 'false' && 
        $html .= $customfields_str;

    if ($formstyle != 'Horizontal')
        $html.="<div class='clear' style='clear:both;'></div>";

    switch ($btype) {
        case 'custom' :

            $line_height = (int) $font_size >= 20 ? 'line-height:' . $font_size . '; ' : "";
            $style = 'style="' . $font_style .
                    'font-size:' . $font_size . '; ' .
                    $line_height .
                    'text-align:' . $text_align . '; ' .
                    'letter-spacing:' . $letter_spacing . '; ' .
                    'font-weight:' . $font_weight . '; ' .
                    'padding:' . $v_padding . ' ' . $h_padding . '; ' .
                    'color:' . ($text_color == '' ? 'transparent' : $text_color) . '; ' .
                    'background:' . ($back_color == '' ? 'transparent' : $back_color) . '; ' .
                    'border: ' . ($fill != 'true' ? $border_thickness : '0') . 'px solid ' . ($back_color == '' ? 'transparent' : $back_color) . ';' .
                    'cursor: pointer;' .
                    //'height: '.$buttonheight.';'.
                    //'line-height:'.((str_replace("px", "", $buttonheight))-(str_replace("px", "", $v_padding)*2)).'px; '.
                    ($buttonwidthfull == 'false' ? ' width:' . $buttonwidth . '; ' : '') .
                    //       ($formstyle == 'Horizontal'?' width:50%; display:inline-block; float: left;':'').'" '.
                    ($formstyle == 'Horizontal' ? ' width:100%; display:inline-block;' : '') . '" ' .
                    'data-textcolor="' . $text_color . '" ' .
                    'data-backcolor="' . $back_color . '" ' .
                    'data-hovertextcolor="' . $hover_text_color . '" ' .
                    'data-hoverbackcolor="' . $hover_back_color . '"';

            $align = ' frb_' . $text_align;
            $round = ($round == 'true' ? ' frb_round' : '');
            $no_fill = ($fill != 'true' ? ' frb_nofill' : '');
            $fullwidth = ($buttonwidthfull == 'true' ? ' frb_fullwidth' : '');
            switch ($icon_align) {
                case 'right' : $icon_style = 'padding-left:8px; float:right; font-size:' . $icon_size . '; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                    break;
                case 'left' : $icon_style = 'padding-right:8px; float:left; font-size:' . $icon_size . '; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                    break;
                case 'inline' : $icon_style = 'padding-right:8px; font-size:' . $icon_size . '; float:none; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                    break;
            }

            if ($icon != '' && $icon != 'no-icon') {
                if (substr($icon, 0, 4) == 'icon') {
                    $icon = '<span class="frb_button_icon" style="' . $icon_style . '" data-hovertextcolor="' . $hover_text_color . '"><i class="' . $icon . ' fawesome"></i></span>';
                } else {
                    $icon = '<span class="frb_button_icon" style="' . $icon_style . '" data-hovertextcolor="' . $hover_text_color . '"><i class="' . substr($icon, 0, 2) . ' ' . $icon . ' frb_icon"></i></span>';
                }
            } else {
                $icon = '';
            }
            $buttonHori = $formstyle == 'Horizontal' ? "fbr_buttonHori" : "clearBoth";
            $html .= '<div onclick="' . $randomId . 'submitForm()" class="' . $buttonHori . ' frb_button' . $round . ' ' . $align . $fullwidth . $no_fill . $lightbox . '" id="submit' . $randomId . '" ' . $style . '>' . $icon . $text . '</div>';
            break;

        case 'predone' :
            $pname = str_replace(" ", "", $pname);
            if ($panimated == "true") {
                $suffix = ".gif";
            } else {
                $suffix = ".png";
            }
            $imagename = $pname . $pcolor . $suffix;
            $html .= '<div style="text-align:center; ' . ($formstyle == 'Horizontal' ? ' width:50%; display:inline-block; float: left;' : '') . '"><img src="' . IMSCPB_URL . '/images/buttons/' . $imagename . '" onclick="' . $randomId . 'submitForm()"></div>';

            break;

        case 'css3' :
            $style = 'style="' . $font_style .
                    'font-size:' . $font_size . '; ' .
                    'line-height:' . $font_size . '; ' .
                    'text-align:' . $text_align . '; ' .
                    'letter-spacing:' . $letter_spacing . '; ' .
                    'font-weight:' . $font_weight . '; ' .
                    'text-align: ' . $text_align . ';' .
                    'padding:' . $v_padding . ' ' . $h_padding . '; ' .
                    'color:' . ($text_color == '' ? '#ffffff' : $text_color) . '; ' .
                    'width: ' . ($buttonwidthfull == 'true' ? '' : $buttonwidth) . ';' .
                    //'height: '.$buttonheight.';'.
                    //'line-height:'.$buttonheight.'; '.
                    'display: ' . ($buttonwidthfull == 'true' ? 'block' : 'inline-block') . ';' .
                    ($formstyle == 'Horizontal' ? ' float: left;' : '') .
                    'text-decoration: none;' .
                    'margin-bottom: 7px;' .
                    'cursor:pointer;"';
            $buttonHori = $formstyle == 'Horizontal' ? "fbr_buttonHoriCss3" : "clearBoth";

            $html .= '<div class="' . $buttonHori . '" style="text-align:center; ' . ($formstyle == 'Horizontal' ? ' width:29%; display:inline-block; float: right;' : '') . '"><a class="pbcss3button' . $css3btnstyle . '" ' . $style . ' id="submit' . $randomId . '" onclick="' . $randomId . 'submitForm()"><span class="text" style="font-size:' . $font_size . ';">' . $text . '</span></a></div>';

            break;

        case 'image' :
            $html .= '<div style="text-align:center;"><img src="' . $image . '" onclick="' . $randomId . 'submitForm()"></div>';
    }

    if ($formstyle == 'Horizontal')
        $html.="<div style='clear:both;'></div>";

    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit Start
     */
    $Submit = 'jQuery("#optin' . $randomId . '").submit()';
//    echo $imscpbiw_access_response->access_token;
//    exit;
    if (!empty($extension[0]['name']) && !empty($imscpbiw_access_response->access_token) && $gotowebinarenable != "false") {
        $Submit = 'GotoWebinarSubmit(jQuery("#optin' . $randomId . '"))';
    }
    $html.='<script>
        var myForm = jQuery("#optin' . $randomId . '");
		function ' . $randomId . 'submitForm(){
                if(myForm.validate(jQuery("#optin' . $randomId . '"))) {
                    ' . $Submit . '

            }
		}		
	</script>';
    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit END
     */


    if (!empty($privacy)) {
        $html.='<div class="privacy" style="color:' . $formtextcolor . ';"><img src="' . IMSCPB_URL . '/images/privacylock.png"> ' . $privacy . '</div>';
    }





    // If 2-step, show the wrapup
    if ($enabletwostep == "true") {
        $html.='</div>';
        $html.='<script>
		jQuery(document).ready(function(){
		  jQuery("#twostep2' . $randomId . '").hide();
		  jQuery("#twostep1' . $randomId . '").show();
		  
		  jQuery("#twostepbutton1' . $randomId . '").click(function(){
		    jQuery("#twostep1' . $randomId . '").hide();
		    jQuery("#twostep2' . $randomId . '").show();
		  });
		});
		</script>';
    }


    // Close Wrapper
    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit Start
     */
    $html.="</div></form></div>" . '
    <script type="text/javascript">
        jQuery(window).trigger("resize");
        jQuery(document).ready(function(){
		
		});
  </script>';
    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit ENd
     */


    if ($align == ' frb_center')
        $html = '<div class="frb_textcenter">' . $html . '</div>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $html = $font_str . $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '<div style="clear:both;"></div>';

    return $html;
}

add_shortcode('pbuilder_optin', 'pbuilder_optin');

function pbuilder_testimonials($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'name' => 'John Dough',
        'profession' => 'photographer / fashion interactive',
        'quote' => 'lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
        'italic' => 'true',
        'url' => '',
        'image' => '',
        'style' => 'default',
        'bot_margin' => 24,
        'name_color' => '#376a6e',
        'quote_color' => '#376a6e',
        'main_color' => '#27a8e1',
        'back_color' => '',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $url = do_shortcode($url);
    $content = do_shortcode($content);

    $styleArray = array('default', 'clean', 'squared', 'rounded');
    if (!in_array($style, $styleArray))
        $style = 'default';

    $randomId = $shortcode_id == '' ? 'frb_testimonials_' . rand() : $shortcode_id;
    $content = nl2br($content);

    $styled = '
		<style type="text/css" scoped="scoped">
			#' . $randomId . ' .frb_testimonials_default:after {
				border-top-color:' . ($back_color != '' ? $back_color : 'transparent' ) . ' !important;
			}
			' . ($italic == 'true' ? '#' . $randomId . ' .frb_testimonials_quote {font-style:italic !important;}' : '#' . $randomId . ' .frb_testimonials_quote {font-style:normal !important;}' ) . '
		</style>';


    $name_block = '<span class="frb_testimonials_name"><b' . ($name_color != '' ? ' style="color:' . $name_color . '"' : '') . '>' . $name . '</b>' . '<span' . ($name_color != '' ? ' style="color:' . $name_color . '"' : '') . '>' . $profession . '</span></span>';
    if ($image != '') {
        $quote_block = '<div class="frb_testimonials_quote" ' . ($quote_color != '' ? 'style="color:' . $quote_color . '"' : '') . '>' . $quote . '</div>';
        $image = ($url != '' ? '<a href="' . $url . '"><img class="frb_testimonials_img" src="' . $image . '" alt=""/></a>' : '<img class="frb_testimonials_img" src="' . $image . '" alt=""/>');
        $main_block = '<div class="frb_testimonials_main_block" style="' . (($main_color != '' && $style != 'default') ? 'background:' . $main_color . '; border-color:' . $main_color . ';' : '') . '">' . $image . '</div>';
        if ($style != 'default') {
            $html = $styled . $name_block . '<div class="frb_testimonials frb_testimonials_' . $style . '" style="' . ($back_color != '' ? 'background:' . $back_color . ';' : '') . ($main_color != '' ? ' border-color:' . $main_color . ';' : '') . '">' . $main_block . $quote_block . '</div>';
        } else {
            $html = $styled . '<div  class="frb_testimonials frb_testimonials_' . $style . '" style="' . ($back_color != '' ? 'background:' . $back_color . ';' : '') . '">' . $quote_block . '</div>' . $main_block . $name_block;
        }
    } else {
        $quote_block = '<div class="frb_testimonials_quote' . ($style == 'clean' ? ' frb_testimonials_quote_border' : '') . '" style="' . ($quote_color != '' ? 'color:' . $quote_color . ';' : '') . ($main_color != '' ? ' border-color:' . $main_color . ';' : '') . '">' . $quote . '</div>';
        $name_block = ($url != '' ? '<a href="' . $url . '">' . $name_block . '</a>' : $name_block);
        $main_block = '<div class="frb_testimonials_main_block" style="' . ($main_color != '' ? 'background:' . $main_color . '; border-color:' . $main_color . ';' : '') . '">' . $name_block . '</div>';
        if ($style != 'default') {
            $html = $styled . '<div class="frb_testimonials frb_testimonials_' . $style . '"  style="' . ($back_color != '' ? 'background:' . $back_color . ';' : '') . ($main_color != '' ? ' border-color:' . $main_color . ';' : '') . '">' . $main_block . $quote_block . '</div>';
        } else {
            $html = $styled . '<div  class="frb_testimonials frb_testimonials_' . $style . '" style="' . ($back_color != '' ? 'background:' . $back_color . ';' : '') . '">' . $quote_block . '</div>' . $name_block;
        }
    }

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $html = $animSpeedSet . '<div id="' . $randomId . '" class="frb_testimonial_style_' . $style . ' ' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';
    return $html;
}

add_shortcode('pbuilder_testimonials', 'pbuilder_testimonials');

function pbuilder_alert($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'type' => 'info',
        'text' => 'This is an alert',
        'icon' => 'ba-warning',
        'style' => 'clean',
        'bot_margin' => 24,
        'main_color' => '#27a8e1',
        'text_color' => '#376a6e',
        'icon_color' => '#27a8e1',
        'back_color' => '',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $randomId = $shortcode_id == '' ? 'frb_alert_' . rand() : $shortcode_id;

    $content = do_shortcode($content);
    $content = nl2br($content);

    $typeArray = array('info', 'success', 'notice', 'warning', 'custom');
    if (!in_array($type, $typeArray))
        $type = 'info';
    $styleArray = array('info', 'success', 'notice', 'warning', 'custom');
    if (!in_array($type, $typeArray))
        $type = 'info';
    if ($type != 'custom') {
        $html = '<div class="frb_alert frb_alert_' . $type . ' frb_alert_' . $style . '"><div class="frb_alert_icon"></div><div class="frb_alert_text">' . $text . '</div></div>';
    } else {
        $html = '<div class="frb_alert frb_alert_' . $type . ' frb_alert_' . $style . '" style="border-color:' . $main_color . '; background-color:' . $back_color . ';"><div class="frb_alert_icon" style="background-color:' . $main_color . ';">';

        if ($icon != '' && $icon != 'no-icon') {
            if (substr($icon, 0, 4) == 'icon') {
                $html .='<i class="' . $icon . ' fawesome" style="color:' . $icon_color . ';"></i>';
            } else {
                $html .='<i class="' . substr($icon, 0, 2) . ' ' . $icon . ' frb_icon" style="color:' . $icon_color . ';"></i>';
            }
        }

        $html .='</div><div class="frb_alert_text" style="color:' . $text_color . ';">' . $text . '</div></div>';
    }

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $html = $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_alert', 'pbuilder_alert');

function pbuilder_accordion($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'active' => '',
        'title' => '',
        'image' => '',
        'style' => 'default',
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
                    ), $atts));

    $content = do_shortcode($content);
    $content = nl2br($content);

    $styled = $style;
    $styleArray = array('default', 'clean-right', 'squared-right', 'rounded-right', 'clean-left', 'squared-left', 'rounded-left');
    if (!in_array($style, $styleArray))
        $style = 'default';
    $title = explode('|', $title);
    $content = explode('|', $content);
    $active = explode('|', $active);
    $image = explode('|', $image);

    if ($border_color == '')
        $border_color = 'transparent';
    if ($back_color == '')
        $back_color = 'transparent';
    $randomId = rand();

    if ($styled !== 'default') :
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
		<style type="text/css" scoped="scoped">
			#frb_accordion_' . $randomId . ' {border-bottom-color:' . $border_color . ';}
			#frb_accordion_' . $randomId . ' h3 {color:' . $title_color . '; background:' . $back_color . '; border-top-color:' . $border_color . '; border-left-color:' . $border_color . '; border-right-color:' . $border_color . ';}
			#frb_accordion_' . $randomId . ' h3 .frb_accordion_trigger:after {background:' . $trigger_color . ';}
			#frb_accordion_' . $randomId . ' h3.ui-state-active {color:' . $title_active_color . ' !important;}
			#frb_accordion_' . $randomId . ' div {color:' . $text_color . '; background:' . $back_color . ';}
			#frb_accordion_' . $randomId . ' h3.ui-state-active .frb_accordion_trigger:after {background:' . $trigger_active_color . ';}
		</style>';
    endif;

    $html .= '<div id="frb_accordion_' . $randomId . '" class="frb_accordion frb_accordion_' . $style . '" data-fixedheight="' . $fixed_height . '">';

    if (is_array($title) && is_array($content)) {
        for ($i = 0; $i < count($title); $i++) {
            $html .= '<h3' . ($active[$i] == 'true' ? ' class="ui-state-active"' : '') . ' >' . $title[$i] . '<span class="frb_accordion_trigger"></span></h3>';
            $image[$i] = ($image[$i] != '' ? '<img style="float:left; margin-right:10px;" src="' . $image[$i] . '" alt="" />' : '');
            $html .= '<div style="">' . $image[$i] . $content[$i] . '<div style="clear:both;"></div></div>';
        }
    }


    // Privacy Statement
    if (!empty($privacy)) {
        $html.='<div id="privacy">' . $privacy . '</div>';
    }

    $html .='</div>';


    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';
    $html = '<div ' . ($shortcode_id != '' ? 'id="' . $shortcode_id . '"' : '') . ' class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . (do_shortcode($html)) . '</div>';

    return $html;
}

add_shortcode('pbuilder_accordion', 'pbuilder_accordion');

function pbuilder_qanda($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'active' => '',
        'title' => '',
        'image' => '',
        'style' => 'default',
        'fixed_height' => 'true',
        'bot_margin' => 24,
        'title_color' => '#333333',
        'text_color' => '#111111',
        'title_size' => '18',
        'icon' => '',
        'icon_size' => '',
        'icon_color' => '#111111',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_group' => ''
                    ), $atts));

    $content = nl2br($content);
    $icon_size = (int) $icon_size . 'px';

    $title = explode('|', $title);
    $content = explode('|', $content);
    $active = explode('|', $active);
    $image = explode('|', $image);


    $randomId = rand();



    if ($icon != '' && $icon != 'no-icon') {
        if (substr($icon, 0, 4) == 'icon') {
            $icon = '<span class="frb_question_icon" style="padding-right:8px; float:left; font-size:' . $icon_size . ';color:' . ($icon_color == '' ? 'transparent' : $icon_color) . ';" ><i class="' . $icon . ' fawesome"></i></span>';
        } else {
            $icon = '<span class="frb_question_icon" style="padding-right:8px; float:left; font-size:' . $icon_size . ';color:' . ($icon_color == '' ? 'transparent' : $icon_color) . ';" ><i class="' . substr($icon, 0, 2) . ' ' . $icon . ' frb_icon"></i></span>';
        }
    } else {
        $icon = '';
    }


    $html .= '<div id="frb_question_' . $randomId . '" data-fixedheight="' . $fixed_height . '">';

    if (is_array($title) && is_array($content)) {
        for ($i = 0; $i < count($title); $i++) {
            $html .= '<h3 style="color:' . $title_color . ';">' . $icon . $title[$i] . '</h3><div style="clear:both;"></div>';
            $image[$i] = ($image[$i] != '' ? '<img style="float:left; margin-right:10px;" src="' . $image[$i] . '" alt="" />' : '');
            $html .= '<div style="margin-bottom:10px;color:' . $text_color . ';">' . $image[$i] . $content[$i] . '<div style="clear:both;"></div></div>';
        }
    }

    $html .='</div>';


    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';
    $html = '<div ' . ($shortcode_id != '' ? 'id="' . $shortcode_id . '"' : '') . ' class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . (do_shortcode($html)) . '</div>';

    return $html;
}

add_shortcode('pbuilder_qanda', 'pbuilder_qanda');

//			pbuilder_toggle

function pbuilder_toggle($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'active' => '',
        'title' => '',
        'image' => '',
        'style' => 'clean-right',
        'fixed_height' => 'true',
        'bot_margin' => 24,
        'title_color' => '#376a6e',
        'text_color' => '#376a6e',
        'trigger_color' => '#376a6e',
        'title_active_color' => '#376a6e',
        'trigger_active_color' => '#376a6e',
        'main_color' => '#27a8e1',
        'border_color' => '#376a6e',
        'active_border_color' => '#cccccc',
        'back_color' => '',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $content = do_shortcode($content);
    $content = nl2br($content);

    $styleArray = array('clean-right', 'squared-right', 'rounded-right', 'clean-left', 'squared-left', 'rounded-left');
    if (!in_array($style, $styleArray))
        $style = 'clean-right';
    $title = explode('|', $title);
    $content = explode('|', $content);
    $active = explode('|', $active);
    $image = explode('|', $image);

    if ($border_color == '')
        $border_color = 'transparent';
    if ($active_border_color == '')
        $active_border_color = 'transparent';
    if ($back_color == '')
        $back_color = 'transparent';
    $randomId = rand();

    $left = (($style == 'squared-left' || $style == 'rounded-left' || $style == 'clean-left' ) ? true : false);
    $rounded = (($style == 'rounded-left' || $style == 'rounded-right' ) ? true : false);

    $html = '
	<style type="text/css" scoped="scoped"> 

	' . (!$left ? '.frb_toggle input + label > h3 {padding-left:10px;}' : '') . '
	
	#frb_toggle' . $randomId . ' .frb_toggle_item_content {
	color:' . $text_color . ';
	}
			
	#frb_toggle' . $randomId . ' .frb_toggle_item > label h3  {
	color:' . $title_color . ';
	border:1px solid ' . $border_color . ';
	' . ($left ? '' : '') . '
	}
	
	#frb_toggle' . $randomId . ' .frb_toggle_item > label i {
	' . ($left ? '' : 'float:right;') . '
	background-color:' . $border_color . ';
	}
	
	#frb_toggle' . $randomId . ' .frb_toggle_item input:checked + label i {
	color:' . $trigger_active_color . ';
	}
	
	' . (($style == 'squared-right' || $style == 'rounded-right') ?
                    '#frb_toggle' . $randomId . ' .frb_toggle_item input:checked + label h3, #frb_toggle' . $randomId . ' .frb_toggle_item input:checked ~ .frb_toggle_item_content {
		border:1px solid ' . $active_border_color . ';
	}
#frb_toggle' . $randomId . ' .frb_toggle_item input:checked + label i, #frb_toggle' . $randomId . ' .frb_toggle_item input:checked ~ .frb_toggle_item_content .frb_toggle_content_left
	{
	background-color:' . $main_color . ';
            ' . ($style == 'rounded-right' ? 'border-radius: 0 5px 5px 0;' : '') . '   
	}
	#frb_toggle' . $randomId . '  .frb_toggle_item .frb_toggle_item_content .frb_toggle_content_left
	{
		height: 100%;
		margin-left: 15px;
		margin-right: 0px;
		width: 27px;
		float:right;
	}
	.frb_toggle .frb_toggle_content_right{ margin-left:10px;}
	.frb_toggle .frb_toggle_item > label i{margin-right: 0px; padding: 6px; color:' . $trigger_color . ';}
#frb_toggle' . $randomId . ' .frb_toggle_item input:checked + label h3 {
	color:' . $title_active_color . ';
	}

	#frb_toggle' . $randomId . ' .frb_toggle_item input:checked + label i{
	background-color:' . $main_color . ';
	margin-right: 0px;
    padding: 6px;
	}
	#frb_toggle' . $randomId . ' .frb_toggle_item input:checked ~ .frb_toggle_item_content {
	background-color:' . $back_color . ';
	}
	' : '
	#frb_toggle' . $randomId . ' .frb_toggle_item input:checked + label i {
	color:' . $title_active_color . ';
	}
	' ) . '
	
	#frb_toggle' . $randomId . ' .frb_toggle_item input + label h3, #frb_toggle' . $randomId . ' .frb_toggle_item_content {
	background-color:' . $back_color . ';
	
	}
	
	' . ($rounded ?
                    '#frb_toggle' . $randomId . ' .frb_toggle_item > label h3, #frb_toggle' . $randomId . ' .frb_toggle_item > .frb_toggle_item_content {
	border-radius:5px;
	}' : '' ) .
            (($style == 'squared-left' || $style == 'rounded-left') ?
                    '#frb_toggle' . $randomId . ' .frb_toggle_item input:checked + label i, #frb_toggle' . $randomId . ' .frb_toggle_item input:checked ~ .frb_toggle_item_content .frb_toggle_content_left {
	background-color:' . $main_color . ';
	}
	.frb_toggle .frb_toggle_item > label i{color:' . $trigger_color . ';}
	
	#frb_toggle' . $randomId . ' .frb_toggle_item input + label i, #frb_toggle' . $randomId . ' .frb_toggle_item .frb_toggle_item_content .frb_toggle_content_left {
	background-color:' . $border_color . ';
	' . ($rounded ? '-moz-border-radius: 0px;
	-webkit-border-radius: 5px 0px 0px 5px;
	border-radius: 5px 0px 0px 5px;' : '') . '
	}
	
	#frb_toggle' . $randomId . ' .frb_toggle_item .frb_toggle_item_content .frb_toggle_content_left {
	width:29px;
	height: 100%;
	margin-right: 15px;
	}' : '' ) . '
		
	</style>';


    $fixed_height = $fixed_height == 'false' ? false : true;

    $html .= '<div id="frb_toggle' . $randomId . '" class="frb_toggle ' . ($fixed_height ? 'frb_fixed_h' : '') . '">';

    if (is_array($title) && is_array($content)) {
        for ($i = 0; $i < count($title); $i++) {
            $image[$i] = ($image[$i] != '' ? '<img style="float:left; margin-right:10px;margin-left:-5px;" src="' . $image[$i] . '" alt="" />' : '');
            $html .= '<div class="frb_toggle_item"><input id="fb_toggle-' . $randomId . $i . '" name="fb_toggle_' . $i . '" type="checkbox" ' . ($active[$i] == 'true' ? 'checked' : '') . ' /><label for="fb_toggle-' . $randomId . $i . '"><h3>' . ($left ? '<i class="fa"></i>' : '') . $title[$i] . ($left ? '' : '<i class="fa"></i>') . '</h3></label>
		
		<div class="frb_toggle_item_content"><div class="frb_toggle_content_left"></div><div class="frb_toggle_content_right">' . $image[$i] . $content[$i] . '</div></div>
		<div style="clear:both;"></div></div>';
        }
    }

    $html.= '</div>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '.frb_toggle_anim_' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '.frb_toggle_anim_' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $html = '<div ' . ($shortcode_id != '' ? 'id="' . $shortcode_id . '"' : '') . ' class="frb_toggle_anim_' . $randomId . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . (do_shortcode($html)) . '</div>';

    return $html;
}

add_shortcode('pbuilder_toggle', 'pbuilder_toggle');

//			pbuilder_tour

function pbuilder_tour($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'active' => '',
        'title' => '',
        'image' => '',
        'tab_position' => 'left',
        'tab_text_align' => 'left',
        'round' => 'false',
        'border_position' => 'false',
        'bot_margin' => 24,
        'title_color' => '#376a6e',
        'text_color' => '#376a6e',
        'active_tab_title_color' => '#376a6e',
        'tab_border_color' => '#376a6e',
        'active_tab_border_color' => '#27a8e1',
        'border_color' => '#ebecee',
        'tab_back_color' => '#376a6e',
        'back_color' => '#f4f4f4',
        'class' => '',
        'custom_id' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $content = do_shortcode($content);
    $content = nl2br($content);

    $title = explode('|', $title);
    $content = explode('|', $content);
    $active = explode('|', $active);
    $image = explode('|', $image);
    $custom_id = explode('|', $custom_id);

    $round = $round == 'false' ? false : true;
    $border_position = $border_position == 'false' ? false : true;

    if ($border_color == '')
        $border_color = 'transparent';
    if ($back_color == '')
        $back_color = 'transparent';
    if ($title_color == '')
        $title_color = 'transparent';
    if ($text_color == '')
        $text_color = 'transparent';
    if ($tab_back_color == '')
        $tab_back_color = 'transparent';
    if ($active_tab_border_color == '')
        $active_tab_border_color = 'transparent';
    if ($tab_border_color == '')
        $tab_border_color = 'transparent';
    if ($active_tab_title_color == '')
        $active_tab_title_color = 'transparent';

    $randomId = rand();

    $html = '
	<style type="text/css" scoped="scoped">
		' . ($shortcode_id != '' ? $shortcode_id : '#frb_tour_' . $randomId) . ' .frb_tour-content {
			color:' . $text_color . ';
			border:2px solid ' . $border_color . ';
			background:' . $back_color . ';
		}
		
		' . ($shortcode_id != '' ? $shortcode_id : '#frb_tour_' . $randomId) . ' ul:first-child {
			float:' . $tab_position . ';
		}
		
		' . ($shortcode_id != '' ? $shortcode_id : '#frb_tour_' . $randomId) . ' ul:first-child li a{
			color:' . $title_color . ';
			text-align:' . $tab_text_align . ';
			background:' . $tab_back_color . ';
			border-top:2px solid ' . ($border_position ? $tab_border_color : 'rgba(255,255,255,0)') . ';
			border-' . ($tab_position == 'left' ? 'left' : 'right') . ':2px solid ' . $tab_border_color . ';
			border-bottom:2px solid rgba(255,255,255,0);
		}
		
		' . ($shortcode_id != '' ? $shortcode_id : '#frb_tour_' . $randomId) . ' ul:first-child li:last-child a {
			border-bottom:2px solid ' . ($border_position ? $tab_border_color : 'rgba(255,255,255,0)') . ';
			padding-bottom:8px !important;
		}
		
		
		' . ($shortcode_id != '' ? $shortcode_id : '#frb_tour_' . $randomId) . ' ul:first-child li a.active{
			' . ($tab_position == 'left' ? 'margin-right:-2px;padding-right:12px;border-left: 2px solid ' . $active_tab_border_color . ';' : 'padding-left:12px;margin-left:-2px;border-right: 2px solid' . $active_tab_border_color . ';') . '
			background:' . $back_color . ';
			color:' . $active_tab_title_color . ';
			border-top:2px solid ' . ($border_position ? $active_tab_border_color : 'rgba(255,255,255,0)') . ';
			padding-bottom:8px !important;
			border-bottom:2px solid ' . ($border_position ? $active_tab_border_color : 'rgba(255,255,255,0)') . ';
		}
		
		' . ($shortcode_id != '' ? $shortcode_id : '#frb_tour_' . $randomId) . ' ul:first-child li a:hover{
				' . ($tab_position == 'left' ? 'margin-right:-2px;padding-right:12px;border-left: 2px solid ' . $active_tab_border_color . ';' : 'padding-left:12px;margin-left:-2px;border-right: 2px solid' . $active_tab_border_color . ';') . '
			background-color:' . $back_color . ';
			color:' . $active_tab_title_color . ';
			border-top:2px solid ' . ($border_position ? $active_tab_border_color : 'rgba(255,255,255,0)') . ';
			transition: border-top-color 300ms, background-color 300ms;
			-webkit-transition: border-top-color 300ms, background-color 300ms;
			padding-bottom:8px !important;
			border-bottom:2px solid ' . ($border_position ? $active_tab_border_color : 'rgba(255,255,255,0)') . ';
		}'
            . ($round ? ($shortcode_id != '' ? $shortcode_id : '#frb_tour_' . $randomId) . ' ul:first-child li:first-child a {
				border-radius:' . ($tab_position == 'left' ? '5px 0' : '0 5px') . ' 0 0;
			}
			' . ($shortcode_id != '' ? $shortcode_id : '#frb_tour_' . $randomId) . ' ul:first-child li:last-child a {
				border-radius:0 0 ' . ($tab_position == 'left' ? '0 5px' : '5px 0') . ';
			}
			
			' . ($shortcode_id != '' ? $shortcode_id : '#frb_tour_' . $randomId) . ' .frb_tour-content {
				border-radius:' . ($tab_position == 'left' ? '0 5px 5px 0' : '5px 0 0 5px') . ';
			}' : '') . '
	</style>';

    $html .= '<div id="frb_tour_' . $randomId . '" class="frb_tour"><ul>';

    if (is_array($title) && is_array($content)) {
        for ($i = 0; $i < count($title); $i++) {
            $html .='<li><a href="' . (isset($custom_id[$i]) && $custom_id[$i] != '' ? '#' . $custom_id[$i] : '#frb_tour_' . $randomId . '_' . $i) . '"' . ($active[$i] == 'true' ? ' class="active"' : '') . '>' . $title[$i] . '</a></li>';
        }
    }

    $html .='</ul>';

    if (is_array($title) && is_array($content)) {
        for ($i = 0; $i < count($title); $i++) {
            $image[$i] = ($image[$i] != '' ? '<img style="float:left; margin-right:10px;" src="' . $image[$i] . '" alt="" />' : '');
            $html .= '<div id="' . (isset($custom_id[$i]) && $custom_id[$i] != '' ? $custom_id[$i] : 'frb_tour_' . $randomId . '_' . $i) . '" class="frb_tour-content">' . $image[$i] . $content[$i] . '<div style="clear:both;"></div></div>';
        }
    }

    $html .='</div>';


    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '.frb_tour_anim_' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '.frb_tour_anim_' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $html = $animSpeedSet . '<div ' . ($shortcode_id != '' ? 'id="' . $shortcode_id . '"' : '') . ' class="frb_tour_anim_' . $randomId . $class . $animate . ' style="clear:both;width:100%;padding-bottom:' . $bot_margin . 'px !important;">' . (do_shortcode($html)) . '</div>';

    return $html;
}

add_shortcode('pbuilder_tour', 'pbuilder_tour');

//			pbuilder_tabs
function pbuilder_tabs($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'active' => '',
        'title' => '',
        'image' => '',
        'style' => 'default',
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
                    ), $atts));

    $content = do_shortcode($content);
    $content = nl2br($content);
    $styled = $style;
    $styleArray = array('default', 'clean', 'squared', 'rounded');
    if (!in_array($style, $styleArray))
        $style = 'default';
    $title = explode('|', $title);
    $content = explode('|', $content);
    $active = explode('|', $active);
    $image = explode('|', $image);
    $custom_id = explode('|', $custom_id);

    if ($border_color == '')
        $border_color = 'transparent';
    if ($back_color == '')
        $back_color = 'transparent';
    $randomId = rand();

    if ($styled !== 'default') :
        $html = '
	<style type="text/css" scoped="scoped">
		#frb_tabs_' . $randomId . ' .frb_tabs-content {
			color:' . $text_color . ';
			border:2px solid ' . $border_color . ';
			' . ($style != 'clean' ? 'background:' . $back_color . ';' : '') . '
		}
		#frb_tabs_' . $randomId . ' ul:first-child a {
			color:' . $title_color . ';
			' . ($style != 'clean' ? '
			background:' . $tab_back_color . ';' : '') . '
		}
		#frb_tabs_' . $randomId . ' ul:first-child a.active{
			' . ($style != 'clean' ? '
			background:' . $back_color . ';
			color:' . $active_tab_title_color . ';
			border-top:2px solid ' . $active_tab_border_color . ';
			padding-bottom:10px !important;
			margin-top:-2px !important' : '
			padding-bottom:10px !important;
			border-bottom:2px solid ' . $active_tab_border_color . ';') . '
		}
		#frb_tabs_' . $randomId . ' ul:first-child a:hover{
			' . ($style != 'clean' ? '
			background-color:' . $back_color . ';
			color:' . $active_tab_title_color . ';
			border-top:2px solid ' . $active_tab_border_color . ';
			padding-bottom:10px !important;
			margin-top:-2px !important;
			transition: border-top-color 300ms, background-color 300ms;
			-webkit-transition: border-top-color 300ms, background-color 300ms;' : '
			padding-bottom:10px !important;
			border-bottom:2px solid ' . $active_tab_border_color . ';
			transition: border-bottom-color 300ms;
			-webkit-transition: border-bottom-color 300ms;') . '
		}
		' . ($style == 'rounded' ? '
			#frb_tabs_' . $randomId . ' ul:first-child li:first-child a {
				border-radius:5px 0 0 0;
			}
			#frb_tabs_' . $randomId . ' ul:first-child li:last-child a {
				border-radius:0 5px 0 0;
			}
		' : '') . '
	</style>';
    else :
        $html = '
	<style type="text/css" scoped="scoped">
		#frb_tabs_' . $randomId . ' .frb_tabs-content {
			color:' . $text_color . ';
			background: ' . $tab_back_color . ';
		}
		#frb_tabs_' . $randomId . ' ul:first-child a {
			color:' . $title_color . ';
		}
		#frb_tabs_' . $randomId . ' ul:first-child a.active{
			color:' . $active_tab_title_color . ';
		}
		#frb_tabs_' . $randomId . ' ul:first-child a.active:after {
			border-top-color:' . $active_tab_border_color . ' !important;
		}
		#frb_tabs_' . $randomId . ' ul:first-child a.active {
			background:' . $active_tab_border_color . ';
		}
		#frb_tabs_' . $randomId . ' ul:first-child a {
			background:' . $tab_back_color . ';
			
		}

	</style>';
    endif;

    $html .= '<div id="frb_tabs_' . $randomId . '" class="frb_tabs frb_tabs_' . $styled . '"><ul>';

    if (is_array($title) && is_array($content)) {
        for ($i = 0; $i < count($title); $i++) {
            $html .='<li><a href="' . (isset($custom_id[$i]) && $custom_id[$i] != '' ? '#' . $custom_id[$i] : '#frb_tabs_' . $randomId . '_' . $i) . '"' . ($active[$i] == 'true' ? ' class="active"' : '') . '>' . $title[$i] . '</a></li>';
        }
    }

    $html .='</ul><div style="clear:both;"></div>';

    if (is_array($title) && is_array($content)) {
        for ($i = 0; $i < count($title); $i++) {
            $image[$i] = ($image[$i] != '' ? '<img style="float:left; margin-right:10px;" src="' . $image[$i] . '" alt="" />' : '');
            $html .= '<div id="' . (isset($custom_id[$i]) && $custom_id[$i] != '' ? $custom_id[$i] : 'frb_tabs_' . $randomId . '_' . $i) . '" class="frb_tabs-content">' . $image[$i] . $content[$i] . '<div style="clear:both;"></div></div>';
        }
    }

    $html .='</div>';


    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';
    $html = '<div ' . ($shortcode_id != '' ? 'id="' . $shortcode_id . '"' : '') . ' class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . (do_shortcode($html)) . '</div>';

    return $html;
}

add_shortcode('pbuilder_tabs', 'pbuilder_tabs');

//			pbuilder_features

function pbuilder_features($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'title' => 'Lorem ipsum',
        'icon' => 'na-svg23',
        'link' => '',
        'order' => 'icon-after-title',
        'style' => 'clean',
        'bot_margin' => 24,
        'icon_size' => 40,
        'title_color' => '#ffffff',
        'icon_color' => '#27a8e1',
        'icon_padding' => 0,
        'icon_border' => 'false',
        'text_color' => '#808080',
        'back_color' => '#376a6e',
        'title_hover_color' => '#ffffff',
        'icon_hover_color' => '#27a8e1',
        'text_hover_color' => '#808080',
        'back_hover_color' => '#376a6e',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));
    if ($shortcode_id == '')
        $shortcode_id = 'frb_features_' . rand();

    $content = do_shortcode($content);
    $content = nl2br($content);

    $styleArray = array('clean', 'squared', 'rounded', 'icon-squared', 'icon-rounded');
    if (!in_array($style, $styleArray))
        $style = 'clean-right';
    $orderArray = array('icon-left', 'icon-right', 'icon-after-title', 'icon-before-title');
    if (!in_array($order, $orderArray))
        $order = 'icon-after-title';
    $margin = (int) $icon_size + (int) $icon_padding * 2 + 20;
    $icon_size = (int) $icon_size . 'px';
    $icon_padding = (int) $icon_padding . 'px';

    $sty = '
	<style type="text/css" scoped="scoped">
		#' . $shortcode_id . ' .frb_features{
			' . ($style == 'squared' || $style == 'rounded' ? 'background:' . $back_color . ';' : '') . '
		}
		#' . $shortcode_id . ' .frb_features:hover {
			' . ($style == 'squared' || $style == 'rounded' ? 'background:' . $back_hover_color . ';' : '') . '
		}
	
		#' . $shortcode_id . ' .frb_features_title {
			color:' . $title_color . ';
			' . ($order != 'icon-before-title' ? 'margin-top:0; padding-top:10px;' : '') . '
			' . ($order == 'icon-left' ? ' margin-left:' . ($margin + 2) . 'px;' : '') . '
			' . ($order == 'icon-right' ? ' margin-right:' . ($margin + 2) . 'px;' : '') . '
			transition: color 300ms;
			-webkit-transition: color 300ms;
		}
		
		#' . $shortcode_id . ' .frb_features:hover .frb_features_title {
			color: ' . $title_hover_color . ';
		}
		
		#' . $shortcode_id . ' .frb_features_icon {
			font-size:' . $icon_size . '; 
			line-height:' . $icon_size . '; 
			color:' . $icon_color . ';
			padding:' . $icon_padding . ';
			' . ($order == 'icon-left' ? 'margin-left:0; margin-right:20px;' : '') . '
			' . ($order == 'icon-right' ? 'margin-left:20px; margin-right:0;' : '') . '
			' . ($style == 'icon-squared' || $style == 'icon-rounded' ? 'background:' . $back_color . ';' : '') . '
			' . ($style == 'icon-rounded' ? 'border-radius:50%;' : '') . '
			' . ($icon_border == 'true' ? 'border:1px solid ' . $icon_color . ';' : '') . '
			transition: color 300ms;
			-webkit-transition: color 300ms, border-color 300ms;
		}
		
		#' . $shortcode_id . ' .frb_features:hover .frb_features_icon {
			color: ' . $icon_hover_color . ';
			border-color: ' . $icon_hover_color . ';
			' . ($style == 'icon-squared' || $style == 'icon-rounded' ? 'background:' . $back_hover_color . ';"' : '') . '
		}
		
		#' . $shortcode_id . ' .frb_features_content {
			color:' . $text_color . ';
			' . ($order == 'icon-left' ? 'margin-left:' . ($margin + 2) . 'px;' : '') . '
			' . ($order == 'icon-right' ? 'margin-right:' . ($margin + 2) . 'px;' : '') . '
			transition: color 300ms;
			-webkit-transition: color 300ms;
		}
		
		#' . $shortcode_id . ' .frb_features:hover .frb_features_content {
			color:' . $text_hover_color . ';
		}
	</style>
	';


    $title = '<h3 class="frb_features_title">' . $title . '</h3>';

    if ($icon != '' && $icon != 'no-icon') {
        if (substr($icon, 0, 4) == 'icon') {
            $icon = '<i class="frb_features_icon frb_features_' . $order . ' ' . $icon . ' fawesome" ></i>';
        } else {
            $icon = '<i class="frb_features_icon frb_features_' . $order . ' ' . substr($icon, 0, 2) . ' ' . $icon . ' frb_icon" ></i>';
        }
    } else {
        $icon = '';
    }
    $content = '<span class="frb_features_content">' . $content . '</span>';

    $html = '<div class="frb_features frb_features_' . $style . ' frb_features_' . $order . '">';
    if ($link != '')
        $html .= '<a href="' . $link . '">';
    if ($order != 'icon-after-title')
        $html .= $icon . $title . $content;
    else
        $html .= $title . $icon . $content;
    $html .= '<div style="clear:both;"></div>' . ($link != '' ? '</a>' : '') . '</div>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $shortcode_id . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $shortcode_id . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $html = $animSpeedSet . $sty . '<div id="' . $shortcode_id . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_features', 'pbuilder_features');

function pbuilder_icon_menu($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'icon' => '',
        'url' => '',
        'align' => 'left',
        'icon_padding' => '5px',
        'link_type' => 'standard',
        'iframe_width' => '600',
        'iframe_height' => '300',
        'bot_margin' => 24,
        'icon_size' => 24,
        'round' => 'false',
        'icon_color' => '#376a6e',
        'back_color' => '',
        'icon_hover_color' => '#27a8e1',
        'back_hover_color' => '',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $url = do_shortcode($url);
    $content = do_shortcode($content);

    $alignArray = array('left', 'right', 'center');
    if (!in_array($align, $alignArray))
        $align = 'left';
    if ($back_hover_color == '')
        $back_hovar_color = 'transparent';
    if ($back_color == '')
        $back_color = 'transparent';
    $icon_size = (int) $icon_size;
    $iframe_width = (int) $iframe_width;
    $iframe_height = (int) $iframe_height;
    $randomId = $shortcode_id == '' ? 'frb_icon_menu_' . rand() : $shortcode_id;

    $icon_padding = (int) $icon_padding / 2;

    $html = '<style type="text/css" scoped="scoped">
			div.frb_iconmenu a.frb_iconmenu_link {
				padding:10px ' . $icon_padding . 'px;
			}
			</style>';

    $html .= '<div class="frb_iconmenu' . ($round == 'true' ? ' frb_iconmenu_round' : '') . ' frb_iconmenu_' . $align . '" style="background:' . $back_color . ';">';

    $icon = explode('|', $icon);
    $link_type = explode('|', $link_type);
    $url = explode('|', $url);
    if (is_array($icon)) {
        for ($i = 0; $i < count($icon); $i++) {

            if (substr($icon[$i], 0, 4) == 'icon') {
                $ii = '<i class="fawesome ' . $icon[$i] . '" style="color:' . $icon_color . '; width:' . ($icon_size + 10) . 'px; font-size:' . $icon_size . 'px; line-height:' . $icon_size . 'px;" data-color="' . $icon_color . '" data-hovercolor="' . $icon_hover_color . '"></i>';
            } else {
                $ii = '<i class="frb_icon ' . substr($icon[$i], 0, 2) . ' ' . $icon[$i] . '" style="color:' . $icon_color . '; width:' . ($icon_size + 10) . 'px; font-size:' . $icon_size . 'px; line-height:' . $icon_size . 'px;" data-color="' . $icon_color . '" data-hovercolor="' . $icon_hover_color . '"></i>';
            }

            switch ($link_type[$i]) {
                case 'new-tab' : $lightbox = '" target="_blank';
                    break;
                case 'lightbox-image' : $lightbox = ' frb_lightbox_link" rel="frbprettyphoto';
                    break;
                case 'lightbox-iframe' : $lightbox = ' frb_lightbox_link"  rel="frbprettyphoto';
                    $url[$i] .= '?iframe=true&width=' . $iframe_width . '&height=' . $iframe_height; /* &width=500&height=500 */ break;
                default : $lightbox = '';
            }
            $html .= '<a href="' . $url[$i] . '" style="background:' . $back_color . '; color:' . $icon_color . ';" data-backcolor="' . $back_color . '" data-backhover="' . $back_hover_color . '" class="frb_iconmenu_link' . $lightbox . '">' . $ii . '</a>';
        }
    }
    $html .= '<div style="clear:both;"></div></div>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $html = $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_icon_menu', 'pbuilder_icon_menu');

function pbuilder_search($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'text' => 'Search',
        'bot_margin' => 24,
        'round' => 'flase',
        'text_color' => '#376a6e',
        'border_color' => '#ebecee',
        'back_color' => '',
        'text_focus_color' => '#376a6e',
        'border_focus_color' => '#376a6e',
        'back_focus_color' => '',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $content = do_shortcode($content);

    if ($back_color == '')
        $back_color = 'transparent';
    if ($border_color == '')
        $border_color = 'transparent';
    if ($text_color == '')
        $text_color = 'transparent';
    if ($text_focus_color == '')
        $text_focus_color = 'transparent';
    if ($border_focus_color == '')
        $border_focus_color = 'transparent';
    if ($back_focus_color == '')
        $back_focus_color = 'transparent';
    $randomId = $shortcode_id == '' ? 'frb_search_' . rand() : $shortcode_id;
    $html = '
<form method="get" style="background:' . $back_color . '; border-color:' . $border_color . ';"  data-backcolor="' . $back_color . '" data-bordercolor="' . $border_color . '"  data-backfocus="' . $back_focus_color . '" data-borderfocus="' . $border_focus_color . '" class="frb_searchform' . ($round == 'true' ? ' frb_searchform_round' : '') . '" action="' . home_url('/') . '">
	<div class="frb_searchleft">
		<div class="frb_searchleft_inner">
			<input type="text" style="color:' . $text_color . ';"  data-color="' . $text_color . '" data-focuscolor="' . $text_focus_color . '" data-value="' . $text . '" class="frb_searchinput" value="' . $text . '" name="s" />
		</div>
	</div>
	<div class="frb_searchright">
		<i style="color:' . $text_color . ';" class="frb_searchsubmit fawesome fa fa-search"></i>
	</div>
	<div class="frb_clear"></div>
</form>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $html = $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_search', 'pbuilder_search');

function pbuilder_h($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'type' => 'h1',
        'bot_margin' => 24,
        'custom_font_size' => 'false',
        'font_size' => 36,
        'line_height' => 'default', //40,
        'letter_spacing' => 0,
        'align' => 'left',
        'google_font' => 'default',
        'google_font_style' => 'default',
        'text_color' => '#232323',
        'text_hover_color' => '',
        'shadow' => 'false',
        'shadow_color' => '#376a6e',
        'shadow_h_shadow' => '0px',
        'shadow_v_shadow' => '0px',
        'shadow_blur' => '0px',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    global $pbuilder;
    $font_str = '';
    if ($google_font != 'default' && !in_array($google_font, $pbuilder->standard_fonts)) {
        if (is_admin())
            $font_str = '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all">';
        else
            wp_enqueue_style('pbuilder_' . $google_font . "_" . $google_font_style, '//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all');
    }

    $content = do_shortcode($content);

    $alignArray = array('left', 'right', 'center');
    if (!in_array($align, $alignArray))
        $align = 'left';
    $randomId = $shortcode_id == '' ? 'frb_h_' . rand() : $shortcode_id;
    $bot_margin = (int) $bot_margin;
    $font_size = (int) $font_size;
    $line_height = $line_height != 'default' ? (int) $line_height . "px" : '110%';
    $letter_spacing = (int) $letter_spacing;

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $shadow_css = '';
    if ($shadow != 'false') {
        $shadow_css .= ' text-shadow: ' . $shadow_h_shadow . ' ' . $shadow_v_shadow . ' ' . $shadow_blur . ' ' . $shadow_color . '; ';
    }

    $font_style = '';
    if ($google_font != 'default') {
        $font_style = 'font-family: ' . str_replace('+', ' ', $google_font) . ', serif !important; ';
        $ipos = strpos($google_font_style, 'italic');
        if ($google_font_style == 'regular') {
            //$font_style .= 'font-weight:400; font-style: normal !important; ';
        } else if ($ipos !== false) {
            if ($ipos > 0) {
                $font_style .= 'font-weight:' . substr($google_font_style, 0, $ipos) . '; ';
            } else {
                //$font_style .= 'font-weight: 400 !important; ';
            }
            $font_style .= 'font-style:italic !important; ';
        } else {
            //$font_style .= 'font-weight:' . $google_font_style . '; font-style: normal !important; ';
        }
    }

    $style = '
    <style type="text/css" scoped="scoped">
    #' . $randomId . ', #' . $randomId . ' *{' . $font_style . '}
    #' . $randomId . ' a{' . ($text_color != '' ? 'color: ' . $text_color . ';' : '') . '}
    #' . $randomId . ' a:hover{' . ($text_hover_color != '' ? 'color: ' . $text_hover_color . ';' : '') . '}
	</style>';
    return $font_str . ($content != '' && $content != null ? $style . $animSpeedSet . '<' . $type . ' id="' . $randomId . '" class="' . $class . $animate . ' style="' . $shadow_css . ' padding-bottom:' . $bot_margin . 'px !important; margin-top:0 !important; text-align:' . $align . '; color:' . $text_color . ';' . ($custom_font_size == 'true' ? ' font-size:' . $font_size . 'px; line-height:' . $line_height . '; letter-spacing:' . $letter_spacing . 'px; ' : '') . '">' . $content . '</' . $type . '>' : '');
}

add_shortcode('pbuilder_h', 'pbuilder_h');

function pbuilder_overlay($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'formcode' => '',
        'formurl' => '',
        'formmethod' => 'POST',
        'namefield' => 'name',
        'nameimage' => IMSCPB_URL . '/images/icons/nameicon.png',
        'namerequired' => 'true',
        'nameerror' => 'Please enter your first name',
        'emailfield' => 'email',
        'emailimage' => IMSCPB_URL . '/images/icons/email.png',
        'emailerror' => 'Please enter an email',
        'formstyle' => 'Vertical',
        'fieldbg' => 'true',
        'fieldtextcolor' => '#111111',
        'disablename' => 'false',
        'newwindow' => 'false',
        'alertmsg' => 'false',
        'customfields' => 'false',
        'hiddenfields' => 'false',
        'google_font' => 'default',
        'google_font_style' => 'default',
        'leadin' => 'Enter your name and email below and click the button to get started now...',
        'privacy' => 'We value your privacy and will never spam you',
        'emailvalue' => 'Enter your email address...',
        'namevalue' => 'Enter your first name...',
        'text' => 'Get Instant Access',
        'base_text_btn' => 'Get Instant Access',
        'formbgtransparent' => 'true',
        'formborder' => 'false',
        'formroundedsize' => '10',
        'formbgcolor' => '#ffffff',
        'formbordercolor' => '#cccccc',
        'formtextcolor' => '#111111',
        'formpadding' => '10',
        'fieldbgtransparent' => 'false',
        'fieldbgcolor' => '#ffffff',
        'fieldfontsize' => '18px',
        'enabletwostep' => 'false',
        'leadin2step' => 'Change this text to be a great call to action to click initially...',
        'buttontext' => 'Click to Learn More',
        'btype' => 'custom',
        'base_font_size' => '34px',
        'base_btn' => 'base_custom',
        'base_btype' => 'base_custom',
        'base_text_color' => '#ffffff',
        'base_back_color' => '#ff6600',
        'base_hover_text_color' => '#ffffff',
        'base_hover_back_color' => '#ff9900',
        'pname' => 'addtocart',
        'base_predone' => 'addtocart',
        'pcolor' => 'gold',
        'base_pcolor' => 'gold',
        'panimated' => 'false',
        'palign' => 'center',
        'base_css3_btn' => 'style1',
        'css3btnstyle' => 'style1',
        'buttonwidth' => '230px',
        'buttonwidthfull' => 'true',
        'buttonheight' => '50px',
        'unit' => 'px',
        'image' => IMSCPB_URL . '/images/icons/email.png',
        'icon' => 'no-icon',
        'type' => 'standard',
        'iframe_width' => '600',
        'iframe_height' => '300',
        'h_padding' => 10,
        'v_padding' => 10,
        'bot_margin' => 24,
        'font_size' => 20,
        'letter_spacing' => -1,
        'font_weight' => 'bold',
        'icon_size' => 16,
        'text_align' => 'center',
        'icon_align' => 'left',
        'fullwidth' => 'true',
        'round' => 'true',
        'fill' => 'true',
        'border_thickness' => '1px',
        'text_color' => '#ffffff',
        'back_color' => '#ff6600',
        'hover_text_color' => '#ffffff', //'#ffffff',
        'hover_back_color' => '#ff9900',
        'showcards' => 'false',
        'amex' => 'true',
        'pp' => 'true',
        'mc' => 'true',
        'visa' => 'true',
        'gotowebinarenable' => 'false',
        'gotowebinarshowbar' => 'false',
        'upcommingwebinar' => '',
        'gotowebinarurl' => '',
        //'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => '',
        'fimg_form_image' => 'false',
        'fimg_content' => IMSCPB_URL . '/images/image-default.jpg',
        'fimg_custom_dimensions' => 'false',
        'fimg_image_width' => 200,
        'fimg_image_height' => 200,
        'fimg_text_align' => 'center',
        'fimg_image_position' => 'top',
        'fimg_shadow' => 'false',
        'fimg_shadow_color' => '#ffffff',
        'fimg_shadow_h_shadow' => 0,
        'fimg_shadow_v_shadow' => 0,
        'fimg_shadow_blur' => 0,
        'fimg_round' => 'false',
        'fimg_round_width' => 0,
        'fimg_border' => 'false',
        'fimg_border_color' => '#376a6e',
        'fimg_border_hover_color' => '#27a8e1',
        'fimg_border_width' => 0,
        'fimg_border_style' => 'solid',
        'fimg_top_margin' => 0,
        'fimg_bottom_margin' => 0,
        'fimg_left_margin' => 0,
        'fimg_right_margin' => 0
                    ), $atts));

    $font_str = '';
    if ($google_font != 'default') {
        if (is_admin())
            $font_str = '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all">';
        else
            wp_enqueue_style('pbuilder_' . $google_font . "_" . $google_font_style, '//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all');
    }

    $url = do_shortcode($url);
    $content = do_shortcode($content);

    $border_thickness = (int) $border_thickness;
    $alignArray = array('center', 'left', 'right');
    if (!in_array($text_align, $alignArray))
        $text_align = 'left';

    $btypeArray = array('image', 'custom', 'predone');
    if (!in_array($btype, $btypeArray))
        $btypeArray = 'custom';

    $basebtypeArray = array('base_custom', 'base_predone');
    if (!in_array($base_btype, $basebtypeArray))
        $basebtypeArray = 'base_custom';

    $typeArray = array('standard', 'new-tab', 'lightbox-image', 'lightbox-iframe');
    if (!in_array($type, $typeArray))
        $type = 'standard';

    $icon_alignArray = array('left', 'right', 'center');
    if (!in_array($icon_align, $icon_alignArray))
        $icon_align = 'right';

    $font_size = (int) $font_size . 'px';
    $letter_spacing = (int) $letter_spacing . 'px';
    $icon_size = (int) $icon_size . 'px';
    $h_padding = (int) $h_padding . 'px';
    $v_padding = (int) $v_padding . 'px';

    $formpadding = (int) $formpadding . 'px';
    $formroundedsize == (int) $formroundedsize . 'px';

    $iframe_width = (int) $iframe_width;
    $iframe_height = (int) $iframe_height;
    $randomId = $shortcode_id == '' ? 'frb_optin_' . rand() : $shortcode_id;
    $content = nl2br($content);

    $leadin2step = html_entity_decode($leadin2step);
    $leadin = html_entity_decode($leadin);
    //$hiddenfields_str=html_entity_decode($hiddenfields_str);	

    $hiddenfields_str = '';

    if ($hiddenfields != "false") {
        $hidden_fields = array_filter(
                array_keys($atts), function($key) {
            return (
                    substr_count($key, "hiddenfield") > 0 &&
                    substr_count($key, "hiddenfieldname") <= 0 &&
                    substr_count($key, "hiddenfieldtype") <= 0 &&
                    $key != "addhiddenfield" &&
                    $key != "hiddenfieldsdiv" &&
                    $key != "hiddenfields"
                    );
        }
        );

        if (is_array($hidden_fields) && count($hidden_fields) > 0) {
            foreach ($hidden_fields as $hidden_field) {
                $ind = str_replace("hiddenfield", "", $hidden_field);
                $hiddenfields_str .= '<input type="hidden" name="' . $atts['hiddenfieldname' . $ind] . '" value="' . $atts[$hidden_field] . '" />
				
				' . "\r\n";
            }
        }
    }


    $customfields_str = '';
    $right_customfields_str = '';
    //$field_input_width = $fimg_image_position == "top" || $fimg_image_position == "bottom" ? " width: 96.5%;" : " width: 95%;";
    //$field_input_width = $fimg_image_position == "top" || $fimg_image_position == "bottom" ? " width: 93.5%;" : " width: 89%;";

    if ($customfields != "false") {
        $custom_fields = array_filter(
                array_keys($atts), function($key) {
            return (
                    substr_count($key, "customfield") > 0 &&
                    substr_count($key, "customfieldlabel") <= 0 &&
                    substr_count($key, "customfieldtype") <= 0 &&
                    substr_count($key, "customfieldrequired") <= 0 &&
                    substr_count($key, "customfielderror") <= 0 &&
                    $key != "addcustomfield" &&
                    $key != "customfieldsdiv" &&
                    $key != "customfields"
                    );
        }
        );


        /*
         * Code added by Asim Ashraf - DevBatch
         * DateTime: 28 Jan 2015
         * Edit Start
         */
        if (is_array($custom_fields) && count($custom_fields) > 0) {
            foreach ($custom_fields as $custom_field) {
                $ind = str_replace("customfield", "", $custom_field);
                $fieldvalue = $atts[$custom_field];
                $fieldlabel = $atts['customfieldlabel' . $ind];
                $required = isset($atts['customfieldrequired' . $ind]) && $atts['customfieldrequired' . $ind] == "true";
                $error = isset($atts['customfielderror' . $ind]) ? $atts['customfielderror' . $ind] : "This field cannot be blank";
                //$customfields_str .= '<div class="field" ><input type="text" name="'.$custom_field.'" value="'.($fieldvalue==""?$fieldlabel:$fieldvalue).'" padding-left="10" padding-right="'.($fieldbg=="true"?"33":"0").'" style="padding-right:0px; padding-left:0px; font-size:'.($fieldfontsize).'; background-color:'.($fieldbgtransparent=="true"?"transparent":$fieldbgcolor).';color:'.$fieldtextcolor.';" onfocus=" if (this.value == \''.$fieldlabel.'\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\''.$fieldlabel.'\';} " default-value="'.$fieldlabel.'" /></div>';
                if (strpos($fieldvalue, 'b_') !== false) {
                    $hiddenfields_str .= '<input type="hidden" name="' . $fieldvalue . '" id="' . $fieldvalue . '" value="" />';
                } else {
                    $customfields_str .= '<div class="field" style="width: 100%;"><input type="text" ' . ($required == 'true' ? ' validation="required" ' : '') . ' class="' . ($required ? ' validate[funcCall[checkoptinrequired]] ' : '') . '" error-message="' . ($required ? $error : '') . '" name="' . $fieldvalue . '" value="' . $fieldlabel . '" padding-left="10" padding-right="' . ($fieldbg == "true" ? "33" : "0") . '" style="padding-right:0px; ' . $field_input_width . 'padding-left:0px; font-size:' . ($fieldfontsize) . '; background-color:' . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor) . ';color:' . $fieldtextcolor . ';margin: 0 auto; " onfocus=" if (this.value == \'' . $fieldlabel . '\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'' . $fieldlabel . '\';} " default-value="' . $fieldlabel . '" /></div>';
                    $right_customfields_str .= '<input type="text" name="' . $fieldvalue . '" value="' . $fieldlabel . '" />';
                }
            }
        }
    }
    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit End
     */
    if ($formbgtransparent == "true")
        $formbgcolor = 'transparent';

    $font_style = '';
    if ($google_font != 'default') {
        $font_style = 'font-family: ' . str_replace('+', ' ', $google_font) . ', serif !important; ';
        $ipos = strpos($google_font_style, 'italic');
        if ($google_font_style == 'regular') {
            //$font_style .= 'font-weight:400; font-style: normal !important; ';
        } else if ($ipos !== false) {
            if ($ipos > 0) {
                $font_style .= 'font-weight:' . substr($google_font_style, 0, $ipos) . '; ';
            } else {
                //$font_style .= 'font-weight: 400 !important; ';
            }
            $font_style .= 'font-style:italic !important; ';
        } else {
            $font_style .= 'font-weight:' . $google_font_style . '; font-style: normal !important; ';
        }
    }

    $form_html = $html = $right_html = "";
    $html.='<style>
		.optin' . $randomId . '{
			' . ($formborder == 'true' ? 'border-width: 2px;border-color: ' . $formbordercolor . ';border-radius: ' . $formroundedsize . ';
			border-style: solid;' : 'border:none;') . '
			/*padding:' . $formpadding . ';*/
			background-color:' . $formbgcolor . ';
		} 
		
		#progress-bar-container{ 
			height: 30px; 
			border-radius: 14px; 
			display: none; 
		} 
		
		#progress-bar-container .progress-bar{ 
			background: #2ecc71; 
			height: 30px; 
			border-radius: 14px; 
			width:0px; 
		} 
		
#formHContainer form {
    padding: 4px 6px;
}
/*		.pbuilder_module #form_container{
			width: ' . ( $fimg_image_position == "left" || $fimg_image_position == "right" ? "62%" : "100%") . ' !important;
		}
		.pbuilder_module #form_image_container{
			width: ' . ( $fimg_image_position == "left" || $fimg_image_position == "right" ? "30%" : "100%") . ' !important;
		}
*/		
		</style>
		<div id="progress-bar-container"> <div class="progress-bar"></div> </div>';

    $fimage_position = $fimg_image_position == "left" ? " float: left;" : "";
    $fimage_position = $fimg_image_position == "right" ? " float: right;" : $fimage_position;

    $fimage_padding = "padding: 6px 12px;";

    $fimage_container_width = $fimg_image_position == "left" || $fimg_image_position == "right" ? " width: 27%;" : "";
    $fimage_container_margin = $fimg_image_position == "left" || $fimg_image_position == "right" ? " margin: 0 auto;" : "";

    $form_position = $fimg_image_position == "left" ? " float: right;" : "";
    $form_position = $fimg_image_position == "right" ? " float: left;" : $form_position;

    $image_container_style = 'style="' . $fimage_padding . $fimage_position . 'text-align:' . $fimg_text_align . '; margin: ' . ( (int) $fimg_top_margin > 0 ? $fimg_top_margin : '0px' ) . ' ' . ( (int) $fimg_right_margin > 0 ? $fimg_right_margin : '0px' ) . ' ' . ( (int) $fimg_bottom_margin > 0 ? $fimg_bottom_margin : '0px' ) . ' ' . ( (int) $fimg_left_margin > 0 ? $fimg_left_margin : '0px' ) . '; ' . $fimage_container_width . $fimage_container_margin . '"';
    $form_image_style = 'style="border-radius: ' . ( $fimg_round != 'false' ? $fimg_round_width : '0px' ) . '; border: ' . ($fimg_border != 'false' ? $fimg_border_width : '0px') . ' ' . $fimg_border_style . ' ' . ($fimg_border_color == '' ? 'transparent' : $fimg_border_color) . '; box-shadow: ' . ( $fimg_shadow != 'false' ? $fimg_shadow_h_shadow . ' ' . $fimg_shadow_v_shadow . ' ' . $fimg_shadow_blur . ' ' . $fimg_shadow_color : '0 0 transparent' ) . ';"';

    $form_image_html = '<div id="form_image_container" ' . $image_container_style . '>
				<img src="' . $fimg_content . '" width="' . $fimg_image_width . '" height="' . $fimg_image_height . '" ' . $form_image_style . ' />
			</div>';

    $formContainerWidth = $fimg_image_position == "left" || $fimg_image_position == "right" ? "width: 62%;" : "";
    $formContainerAlignment = $fimg_image_position == "left" || $fimg_image_position == "right" ? 'class="lr_alignment"' : "";
    $exitPopup = $alertmsg == 'true' ? "exitPopup" : "";
    $form_html.= '<div id="form_container" ' . $formContainerAlignment . ' style="' . $form_position . ' ' . $formContainerWidth . ' padding: 6px;">
	
				  <form method="' . $formmethod . '" action="' . $formurl . '" class="optin optin' . $randomId . ' optin_style_' . $formstyle . ' ' . $exitPopup . ' overlayForm" name="optin' . $randomId . '" id="optin' . $randomId . '" accept-charset="UTF-8" autocomplete><div class="content">';
    $right_html = '<form method="' . $formmethod . '" action="' . $formurl . '" name="" id="">
	
	';

    if (!empty($hiddenfields_str)) {
        $form_html.='<div style="display: none;">' . $hiddenfields_str . '</div>';
        $right_html .= $hiddenfields_str;
    }
    global $wpdb, $pbuilder;
    $table_name = $wpdb->prefix . 'profit_builder_extensions';
    $extension = $wpdb->get_results('SELECT name FROM ' . $table_name . ' where name = "profit_builder_instant_gotowebinar" ', ARRAY_A);
    $imscpbiw_access_response = $pbuilder->options(" WHERE name = 'imscpbiw_access_response'");
    $imscpbiw_access_response = json_decode($imscpbiw_access_response[0]->value);
    if (!empty($extension[0]['name']) && !empty($imscpbiw_access_response->access_token) && $gotowebinarenable != "false") {
        if (!empty($gotowebinarurl)) {
            $form_html .= '<input type="hidden" name="gotowebinarurl" id="gotowebinarurl" value="' . $gotowebinarurl . '" />' . "\r\n";
        }
        if ($gotowebinarenable != "false") {
            $form_html .= '<input type="hidden" name="gotowebinarkeys" value="' . $upcommingwebinar . '" />' . "\r\n";
        }
    }
    if ($enabletwostep == "true") {
        $form_html.='
            <div id="twostep1' . $randomId . '" style="display:none;">
                <div id="leadin2step" style="color:' . $formtextcolor . '; text-align:center;">' . $leadin2step . '</div>
        ';



        //switch for base button of overlay ends     

        $form_html.='</div><div id="twostep2' . $randomId . '" style="display:block;">';
    }
    if (!empty($extension[0]['name'])) {
        if ($gotowebinarshowbar != 'false') {
            $form_html.='<div class="jquery-ui-like" id="progressBar"><div style="width: 50%;">50%&nbsp;</div></div>';
        }
    }
    if (!empty($leadin))
        $form_html.='<div id="leadin" style="color:' . $formtextcolor . '; text-align:center;">' . $leadin . '</div>';
    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit Start
     */
    if ($formstyle != 'Horizontal') {
        $right_html .= '<input type="text" name="' . $namefield . '" value="' . $namevalue . '" />';

        if ($disablename == 'false')
            $form_html.='<div class="field" style="width: 100%;"><input type="text" ' . ($namerequired == 'true' ? ' validation="required" ' : '') . ' class=" ' . ($namerequired == 'true' ? ' validate[funcCall[checkoptinrequired]] ' : '') . '" error-message="' . ($namerequired == 'true' ? $nameerror : '') . '" name="' . $namefield . '" value="' . $namevalue . '" padding-left="4" padding-right="' . ($fieldbg == "true" ? "30" : "0") . '" style=" ' . $field_input_width . 'padding-left:0px; font-size: ' . ($fieldfontsize) . '; ' . ($fieldbg == "true" ? " background:url('" . $nameimage . "') no-repeat 99% center " . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor) . "; padding-right: 0px; margin: 0 auto;" : 'background-color:' . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor)) . ';color:' . $fieldtextcolor . ';" onfocus=" if (this.value == \'' . $namevalue . '\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'' . $namevalue . '\';} "  default-value="' . $namevalue . '" /></div>';
//    echo 'yes1: '.$formstyle;

        $right_html .= '<input type="text" name="' . $namefield . '" value="' . $emailvalue . '" />';

//	$form_html.='<div class="field" style="width: 100%; '.($formstyle == 'Horizontal'?'margin: 0 auto;display:inline-block; float: left;':'').'"><input type="text" class="validate[funcCall[checkoptinrequired],custom[email]]" error-message="'.$emailerror.'" name="'.$emailfield.'" value="'.$emailvalue.'" padding-left="4" padding-right="'.($fieldbg=="true"?"30":"0").'" style="'. $field_input_width .'padding-left:0px; font-size:'.($fieldfontsize).'; '.($fieldbg=="true"?" background:url('".$emailimage."') no-repeat 99% center ".($fieldbgtransparent=="true"?"transparent":$fieldbgcolor)."; margin: 0 auto;padding-right: 0px;":'background-color:'.($fieldbgtransparent=="true"?"transparent":$fieldbgcolor)).';color:'.$fieldtextcolor.';" onfocus=" if (this.value == \''.$emailvalue.'\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\''.$emailvalue.'\';} " default-value="'.$emailvalue.'" /></div>'; 

        $form_html.='<div class="field" style="width: 100%;"><input type="text" validation="required email" class="validate[funcCall[checkoptinrequired],custom[email]]" error-message="' . $emailerror . '" name="' . $emailfield . '" value="' . $emailvalue . '" padding-left="4" padding-right="' . ($fieldbg == "true" ? "30" : "0") . '" style="' . $field_input_width . 'padding-left:0px; font-size:' . ($fieldfontsize) . '; ' . ($fieldbg == "true" ? " background:url('" . $emailimage . "') no-repeat 99% center " . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor) . "; margin: 0 auto;padding-right: 0px;" : 'background-color:' . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor)) . ';color:' . $fieldtextcolor . ';" onfocus=" if (this.value == \'' . $emailvalue . '\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'' . $emailvalue . '\';} " default-value="' . $emailvalue . '" /></div>';
    } else {
        $right_html .= '<input type="text" name="' . $namefield . '" value="' . $namevalue . '" />';

        $fieldWidht = $disablename == 'false' ? "width:33% !important;" : "width:66% !important;";

        if ($disablename == 'false')
            $form_html.='<div class="Hfrom"><div class="field Hfield" style="width:33% !important;"><input type="text" ' . ($namerequired == 'true' ? ' validation="required" ' : '') . ' class=" ' . ($namerequired == 'true' ? ' validate[funcCall[checkoptinrequired]] ' : '') . '" error-message="' . ($namerequired == 'true' ? $nameerror : '') . '" name="' . $namefield . '" value="' . $namevalue . '" padding-left="4" padding-right="' . ($fieldbg == "true" ? "30" : "0") . '" style=" ' . $field_input_width . ' font-size: ' . ($fieldfontsize) . '; ' . ($fieldbg == "true" ? " background:url('" . $nameimage . "') no-repeat 99% center " . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor) . ";  margin: 0 auto;" : 'background-color:' . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor)) . ';color:' . $fieldtextcolor . ';" onfocus=" if (this.value == \'' . $namevalue . '\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'' . $namevalue . '\';} "  default-value="' . $namevalue . '" /></div>';
//    echo 'yes1: '.$formstyle;

        $right_html .= '<input type="text" name="' . $emailfield . '" value="' . $emailvalue . '" />';

        $form_html.='<div class="field Hfield hRfield1" style="' . $fieldWidht . '"><input type="text" validation="required email" class="validate[funcCall[checkoptinrequired],custom[email]]" error-message="' . $emailerror . '" name="' . $emailfield . '" value="' . $emailvalue . '" padding-left="4" padding-right="' . ($fieldbg == "true" ? "30" : "0") . '" style="' . $field_input_width . ' font-size:' . ($fieldfontsize) . '; ' . ($fieldbg == "true" ? " background:url('" . $emailimage . "') no-repeat 99% center " . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor) . "; margin: 0 auto;" : 'background-color:' . ($fieldbgtransparent == "true" ? "transparent" : $fieldbgcolor)) . ';color:' . $fieldtextcolor . ';" onfocus=" if (this.value == \'' . $emailvalue . '\') { this.value = \'\'; }" onblur="if (this.value == \'\') { this.value=\'' . $emailvalue . '\';} " default-value="' . $emailvalue . '" /></div></div>';
    }
    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit End
     */
    if (!empty($customfields_str) && $formstyle != 'Horizontal') {//$disablename == 'false' && 
        $form_html .= $customfields_str;
        $right_html .= $right_customfields_str;
    }

    if ($formstyle != 'Horizontal')
        $form_html.="<div class='clear' style='clear:both;'></div>";
    $base_btn_width = '454px';
    //switch for base button of overlay starts
    if (strpos($base_font_size, 'px') !== false) {
        $base_font_size = $base_font_size;
    } else {
        $base_font_size = $base_font_size . 'px';
    }

    switch ($base_btype) {
        case 'base_custom' :
            /*
             * Code added by Asim Ashraf - DevBatch
             * DateTime: 28 Jan 2015
             * Edit Start
             */
            $style = 'style="' . $font_style .
                    'font-size:' . $base_font_size . '; ' .
                    'line-height:' . $base_font_size . '; ' .
                    'text-align:' . $text_align . '; ' .
                    'letter-spacing:' . $letter_spacing . '; ' .
                    'font-weight:' . $font_weight . '; ' .
                    'padding:' . $v_padding . ' ' . $h_padding . '; ' .
//					'width: 498px!important;'.
                    'display: ' . ($buttonwidthfull == 'true' ? 'block' : 'inline-block') . ';' .
//					'margin-top: 2px;'. 
                    'color:' . ($base_text_color == '' ? 'transparent' : $base_text_color) . '; ' .
                    'background:' . ($base_back_color == '' ? 'transparent' : $base_back_color) . '; ' .
                    'border: ' . ($fill != 'true' ? $border_thickness : '0') . 'px solid ' . ($base_back_color == '' ? 'transparent' : $base_back_color) . ';" ' .
                    'data-textcolor="' . $base_text_color . '" ' .
                    'data-backcolor="' . $base_back_color . '" ' .
                    'data-hovertextcolor="' . $base_hover_text_color . '" ' .
                    'class="formOverlaydiv"' .
                    'data-hoverbackcolor="' . $base_hover_back_color . '"';
            /*
             * Code added by Asim Ashraf - DevBatch
             * DateTime: 28 Jan 2015
             * Edit End
             */
            $align = ' frb_' . $text_align;
            $round = ($round == 'true' ? ' frb_round' : '');
            $no_fill = ($fill != 'true' ? ' frb_nofill' : '');

            $bhtml .= '<div ' . $style . '>' . $base_text_btn . '</div>';
            break;

        case 'base_predone' :
            $base_predone = str_replace(" ", "", $base_predone);
            $suffix1 = ".png";
            $imagename = $base_predone . $base_pcolor . $suffix1;
            $bhtml .= '<img src="' . IMSCPB_URL . '/images/buttons/' . $imagename . '">';
            break;

        case 'base_css3' :
            $style = 'style="' . $font_style .
                    'font-size:' . $base_font_size . '; ' .
                    'line-height:' . $base_font_size . '; ' .
                    'text-align:' . $text_align . '; ' .
                    'text-align: ' . $text_align . ';' .
                    'letter-spacing:' . $letter_spacing . '; ' .
                    'font-weight:' . $font_weight . '; ' .
                    'padding:' . $v_padding . ' ' . $h_padding . '; ' .
                    'color:' . ($base_text_color == '' ? '#ffffff' : $base_text_color) . '; ' .
                    'width: ' . ($buttonwidthfull == 'true' ? '' : $buttonwidth) . ';' .
                    //'height: '.$buttonheight.';'.
                    //'line-height:'.$buttonheight.'; '.
                    'display: ' . ($buttonwidthfull == 'true' ? 'block' : 'inline-block') . ';' .
                    'text-decoration: none;' .
                    'margin-bottom: 7px;"';

            $bhtml .= '<div class="pbcss3button' . $base_css3_btn . '" ' . $style . ' id="twostepbutton1' . $randomId . '"><span class="text" style="font-size:' . $base_font_size . ';">' . $base_text_btn . '</span></div>';
            break;
    }

    switch ($btype) {

        case 'custom' :
            $line_height = (int) $font_size >= 20 ? 'line-height:' . $font_size . '; ' : "";
            $style = 'style="' . $font_style .
                    'font-size:' . $font_size . '; ' .
                    $line_height .
                    'text-align:' . $text_align . '; ' .
                    'letter-spacing:' . $letter_spacing . '; ' .
                    'font-weight:' . $font_weight . '; ' .
                    'padding:' . $v_padding . ' ' . $h_padding . '; ' .
                    'color:' . ($text_color == '' ? 'transparent' : $text_color) . '; ' .
                    'background:' . ($back_color == '' ? 'transparent' : $back_color) . '; ' .
                    'border: ' . ($fill != 'true' ? $border_thickness : '0') . 'px solid ' . ($back_color == '' ? 'transparent' : $back_color) . ';' .
                    'cursor: pointer;' .
                    //'height: '.$buttonheight.';'.
                    //'line-height:'.((str_replace("px", "", $buttonheight))-(str_replace("px", "", $v_padding)*2)).'px; '.
                    /*
                     * Code added by Asim Ashraf - DevBatch
                     * DateTime: 30 Jan 2015
                     * Edit Start
                     */
                    ($buttonwidthfull == 'false' ? ' width:' . $buttonwidth . '; ' : '') .
                    ($formstyle == 'Horizontal' ? ' width:50%; display:inline-block; float: left;' : '') . '
				width: 100%; 
				border-radius: 5px; 
                                
                                
				margin: 0 auto" ' .
                    'data-textcolor="' . $text_color . '" ' .
                    'data-backcolor="' . $back_color . '" ' .
                    'data-hovertextcolor="' . $hover_text_color . '" ' .
                    'class="formOverlaydiv"' .
                    'data-hoverbackcolor="' . $hover_back_color . '"';
            /*
             * Code added by Asim Ashraf - DevBatch
             * DateTime: 30 Jan 2015
             * Edit End
             */
            $align = ' frb_' . $text_align;
            $round = ($round == 'true' ? ' frb_round' : '');
            $no_fill = ($fill != 'true' ? ' frb_nofill' : '');
            $fullwidth = ($buttonwidthfull == 'true' ? ' frb_fullwidth' : '');
            switch ($icon_align) {
                case 'right' : $icon_style = 'padding-left:8px; float:right; font-size:' . $icon_size . '; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                    break;
                case 'left' : $icon_style = 'padding-right:8px; float:left; font-size:' . $icon_size . '; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                    break;
                case 'inline' : $icon_style = 'padding-right:8px; font-size:' . $icon_size . '; float:none; color:' . ($text_color == '' ? 'transparent' : $text_color) . ';';
                    break;
            }

            if ($icon != '' && $icon != 'no-icon') {
                if (substr($icon, 0, 4) == 'icon') {
                    $icon = '<span class="frb_button_icon" style="' . $icon_style . '" data-hovertextcolor="' . $hover_text_color . '"><i class="' . $icon . ' fawesome"></i></span>';
                } else {
                    $icon = '<span class="frb_button_icon" style="' . $icon_style . '" data-hovertextcolor="' . $hover_text_color . '"><i class="' . substr($icon, 0, 2) . ' ' . $icon . ' frb_icon"></i></span>';
                }
            } else {
                $icon = '';
            }
            $widthClass = $fimg_image_position == "left" || $fimg_image_position == "right" ? "width331" : "width521";
            $frb_buttonClass = $fimg_image_position == "left" || $fimg_image_position == "right" ? " frb_btn100" : "";
            $buttonHori = $formstyle == 'Horizontal' ? "fbr_buttonHori" : "clearBoth";
            //$ClearBoth = $formstyle == 'Horizontal' ? "" : "clear:both !important;";

            $form_html .= '<div onclick="' . $randomId . 'submitForm()" class="' . $buttonHori . ' frb_button' . $round . ' ' . $frb_buttonClass . $align . $fullwidth . $no_fill . $lightbox . '" id="submit' . $randomId . '" ' . $style . '>' . $icon . $text . '</div>';
            break;

        case 'predone' :
            $pname = str_replace(" ", "", $pname);
            if ($panimated == "true") {
                $suffix = ".gif";
            } else {
                $suffix = ".png";
            }
            $imagename = $pname . $pcolor . $suffix;
            $form_html .= '<div style="text-align:center; ' . ($formstyle == 'Horizontal' ? ' width:50%; display:inline-block; float: left;' : '') . '"><img src="' . IMSCPB_URL . '/images/buttons/' . $imagename . '" onclick="' . $randomId . 'submitForm()"></div>';

            break;

        case 'css3' :
            $style = 'style="' . $font_style .
                    'font-size:' . $font_size . '; ' .
                    'line-height:' . $font_size . '; ' .
                    'text-align:' . $text_align . '; ' .
                    'letter-spacing:' . $letter_spacing . '; ' .
                    'font-weight:' . $font_weight . '; ' .
                    'text-align: ' . $text_align . ';' .
                    'padding:0 20px; ' .
                    'padding:' . $v_padding . ' ' . $h_padding . '; ' .
                    'color:' . ($text_color == '' ? '#ffffff' : $text_color) . '; ' .
                    'width: ' . ($buttonwidthfull == 'true' ? '' : $buttonwidth) . ';' .
                    //'height: '.$buttonheight.';'.
                    //'line-height:'.$buttonheight.'; '.
                    'display: ' . ($buttonwidthfull == 'true' ? 'block' : 'inline-block') . ';' .
                    ($formstyle == 'Horizontal' ? ' float: left;' : '') .
                    'text-decoration: none;' .
                    'margin-bottom: 7px;' .
                    'cursor:pointer;"';

            $buttonHori = $formstyle == 'Horizontal' ? "fbr_buttonHoriCss3" : "clearBoth";
            $form_html .= '<div class="' . $buttonHori . '" style="text-align:center; ' . ($formstyle == 'Horizontal' ? ' width:29%; display:inline-block; float: right;' : '') . '"><a class="pbcss3button' . $css3btnstyle . '" ' . $style . ' id="submit' . $randomId . '" onclick="' . $randomId . 'submitForm()"><span class="text" style="font-size:' . $font_size . ';">' . $text . '</span></a></div>';

            break;

        case 'image' :
            $form_html .= '<div style="text-align:center;"><img src="' . $image . '" onclick="' . $randomId . 'submitForm()"></div>';
    }

    if ($formstyle == 'Horizontal')
        $form_html.="<div style='clear:both;'></div>";

    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit Start
     */
    $Submit = 'jQuery("#optin' . $randomId . '").submit()';
    if (!empty($extension[0]['name']) && !empty($imscpbiw_access_response->access_token) && $gotowebinarenable != "false") {
        $Submit = 'GotoWebinarSubmit(jQuery("#optin' . $randomId . '"))';
    }
    $form_html.='<script>
		var myForm = jQuery("#optin' . $randomId . '");

		function ' . $randomId . 'submitForm(){
            if(myForm.validate( jQuery("#optin' . $randomId . '"))){
                
				' . $Submit . '
				
				/*var pbarWidth = 0;
				if( pbarWidth <= 0 )
				{
					jQuery("#progress-bar-container") .show( );
					jQuery(".progress-bar").css("width", pbarWidth +"%");
				}
				setInterval(function(){ 
					pbarWidth = parseInt( pbarWidth ) + 25;
					if( pbarWidth <= 100 ){
						jQuery(".progress-bar").css("width", pbarWidth +"%");
					}
					else if( pbarWidth == 125 )
					{
						jQuery("#progress-bar-container") .hide( );
						
					}
				}, 700);*/
			}
		}		
	</script>';

    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 28 Jan 2015
     * Edit End
     */

    if (!empty($privacy)) {
        $form_html.='<div class="privacy" style="color:' . $formtextcolor . ';"><img src="' . IMSCPB_URL . '/images/privacylock.png"> ' . $privacy . '</div>';
    }





    // If 2-step, show the wrapup
    if ($enabletwostep == "true") {
        $form_html.='</div>';
        $form_html.='<script>
		jQuery(document).ready(function(){
		  jQuery("#twostep2' . $randomId . '").hide();
		  jQuery("#twostep1' . $randomId . '").show();
		  
		  jQuery("#twostepbutton1' . $randomId . '").click(function(){
		    jQuery("#twostep1' . $randomId . '").hide();
		    jQuery("#twostep2' . $randomId . '").show();
		  });
		});
		</script>';
    }


    // Close Wrapper
    $form_html.="</div></form></div>";

    $html.= '<div id="formHContainer" style="width: 100%; clear: both;">';
    if ($fimg_form_image != 'false' && $fimg_image_position == 'top') {
        $html.= $form_image_html;
        $html.= $form_html;
    } else if ($fimg_form_image != 'false' && $fimg_image_position == 'bottom') {
        $html.= $form_html;
        $html.= $form_image_html;
    } else {
        $html.= $fimg_form_image != 'false' ? $form_image_html : '';
        $html.= $form_html;
    }
    $html.= '</div>';
    $html.="<div id='overlayForm' style='display: none;'>" . $right_html . "</form></div></div>" . '
    <script type="text/javascript">
        jQuery(window).trigger("resize");
        jQuery(document).ready(function(){
			
		});
  </script>';


    if ($align == ' frb_center')
        $html = '<div class="frb_textcenter">' . $html . '</div>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $html = $font_str . $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '<div style="clear:both;"></div>';
    $id_overlay = rand(1, 100);
    if ($alertmsg == 'true') {
        /* $msgalertCleanup = ',
          onCleanup: function() {
          alert("You are leaving without submit form.");
          }'; */
    } /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 27 Jan 2015
     * Edit Start
     */
    $ahtml = '<script type="text/javascript">
	jQuery(document).ready(function(){
		
				jQuery(".group' . $id_overlay . '").colorbox({
					inline:true,
                                        
                                        maxWidth:"95%",
                                        maxHeight:"95%",
					onOpen: function() {
						jQuery("#overlay' . $id_overlay . ' form.overlayForm").find(".frb_button"). addClass("frb_btn100");
					},
					onClosed: function() {
						// jQuery("#overlay' . $id_overlay . ' form.overlayForm").find(".frb_button"). addClass("' . $widthClass . '");
						jQuery("#overlay' . $id_overlay . ' form.overlayForm").find(".frb_button"). removeClass("frb_btn100");
					},
                                        
				});
                                
var resizeTimer;
function resizeColorBox()
{
    if (resizeTimer) clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
            if (jQuery("#cboxOverlay").is(":visible")) {
                    jQuery.colorbox.resize();
                    
            }
    }, 300)
}

// Resize Colorbox when resizing window or changing mobile device orientation
jQuery(window).resize(resizeColorBox);
';
    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 27 Jan 2015
     * Edit End
     */
    if ($widthClass == "width331") {
        $ahtml .= 'var imgHeight = jQuery(".parent_overlay") .css({visibility: "hidden", display : "block"}) .find("#form_image_container") .height( );
				var formHeight = jQuery(".parent_overlay") .css({visibility: "hidden", display : "block"}) .find("#form_container") .height( );
				
				var diffH = formHeight - imgHeight;
				var HdiffH = parseInt( diffH / 2 );
				//alert(HdiffH);
				jQuery("#form_image_container") .css("margin", HdiffH+"px auto 0");
				//alert(jQuery("#form_image_container") .attr( "style" ) );
				
				jQuery(".parent_overlay").css({visibility: "visible", display: "none"});';
    }

    $ahtml .= 'jQuery(".formOverlay") .live("click", function(){
				
				/*var pbarWidth = 0;
				if( pbarWidth <= 0 )
				{
				 	//jQuery("#formHContainer") .hide( );
					jQuery("#progress-bar-container") .show( );
					jQuery(".progress-bar").css("width", pbarWidth +"%");
				}
				setInterval(function(){ 
					pbarWidth = parseInt( pbarWidth ) + 25;
					if( pbarWidth <= 100 ){
						jQuery(".progress-bar").css("width", pbarWidth +"%");
						jQuery(".progress-bar").html( pbarWidth +"%" );
					}
					else if( pbarWidth == 125 )
					{
						jQuery("#progress-bar-container") .hide( );
						jQuery("#formHContainer") .show( );
					}
				}, 700);*/
				
				
				//jQuery("#overlay' . $id_overlay . ' form.overlayForm").find(".frb_button"). removeClass("width331");
				//jQuery("#overlay' . $id_overlay . ' form.overlayForm").find(".frb_button"). removeClass("width521");
					
					jQuery(".frb_textcenter form.overlayForm :input").each(function(){
			
						jQuery( this ) .removeAttr("readonly");
					});
				});
				
				
	});
    </script>
	
	<div style="width: 100%;">
	<a class="group' . $id_overlay . ' formOverlay" href="#overlay' . $id_overlay . '">' . $bhtml . '</a>
	</div>
	<div class="parent_overlay" id="parent_overlay' . $id_overlay . '" style="display:none;">
		<div id="overlay' . $id_overlay . '">' . $html . '</div>
	</div>';

    return $ahtml;
}

add_shortcode('pbuilder_overlay', 'pbuilder_overlay');

function pbuilder_comments($atts, $content = null) {
	global $post;
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'type' => 'wordpress',
        'custom_font_size' => 'false',
        'font_size' => '28',
        'line_height' => 'default', //'28',
        'letter_spacing' => '0',
        'text_color' => '#232323',
        'bot_margin' => 24,
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $content = do_shortcode($content);

    $font_size = (int) $font_size;
    $line_height = $line_height != 'default' ? (int) $line_height . 'px' : '110%';
    $letter_spacing = (int) $letter_spacing;


    $randomId = $shortcode_id == '' ? 'frb_comments_' . rand() : $shortcode_id;
    $bot_margin = (int) $bot_margin;

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $commentform = pbuilder_comment_form(@$commentsfile);

    if (!empty($content)) {
        $title = '<h2 style="color:' . $text_color . ';margin-bottom:10px;' . ($custom_font_size == 'true' ? ' font-size:' . $font_size . 'px; line-height:' . $line_height . '; letter-spacing:' . $letter_spacing . 'px;' : '') . '">' . $content . '</h2>';
    }

    $html = '<div id="' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important; margin-top:0 !important;">' . $title . $commentform . '</div>';
	
    return $html;
}

function pbuilder_comment_form($comment_file) {
	global $post,$withcomments;
	$withcomments=1;
    ob_start();
    comments_template();
    $form = ob_get_contents();
    ob_end_clean();
    return $form;
}

add_shortcode('pbuilder_comments', 'pbuilder_comments');

function pbuilder_fbcomments($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'fb_comment_url' => '',
        'fb_language' => 'en_US',
        'fb_no_posts' => '10',
        'fb_width' => '100',
        'fb_width_type' => '%',
        'fb_color_scheme' => 'light',
        'fb_form_title' => '',
        'fb_source_url' => '',
                    ), $atts));

    /*if ($fb_app_id == '')
        return 'Please Enter FB AppId';*/

    $permalink = $fb_comment_url == '' ? get_permalink() : $fb_comment_url;

    ob_start();
    ?>
   
    <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?php echo $fb_language; ?>/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    
    <div 
        class="fb-comments" 
        data-colorscheme="<?php echo $fb_color_scheme; ?>" 
        data-href="<?php echo $permalink; ?>" 
        data-num-posts="<?php echo $fb_no_posts; ?>" 
        data-publish_feed="true" 
        data-width="<?php echo $fb_width . $fb_width_type; ?>">
    </div>
    <?php
    $html = ob_get_contents();
    ob_clean();
    return $html;
}

function pbuilder_fb_comment_form($comment_file) {
    ob_start();
    comments_template();
    $form = ob_get_contents();
    ob_end_clean();
    return $form;
}

add_shortcode('pbuilder_fbcomments', 'pbuilder_fbcomments');

function pbuilder_social($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'email' => 'false',
        'facebook' => 'true',
        'twitter' => 'true',
        'google' => 'true',
        'linkedin' => 'false',
        'pinterest' => 'false',
        'pinteresturl' => '',
        'instagram' => 'false',
        'instagramurl' => '',
        'tumblr' => 'false',
        'tumblrurl' => '',
        'pocket' => 'false',
        'custom_font_size' => 'false',
        'font_size' => '28',
        'line_height' => 'default', //'28',
        'letter_spacing' => '0',
        'text_color' => '#232323',
        'bot_margin' => 24,
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));


    $randomId = $shortcode_id == '' ? 'frb_comments_' . rand() : $shortcode_id;
    $bot_margin = (int) $bot_margin;
    $line_height = $line_height != 'default' ? (int) $line_height . "px" : '110%';

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $html = '<div class="share-container clearfix"><ul class="pbs-buttons clearfix">';

    if ($email == 'true') {
        $icon = file_get_contents(IMSCPB_DIR . "/images/social/mail.svg");
        $html.='<li class="email"><a href="mailto:?subject=' . urlencode(get_the_title()) . '&body=' . urlencode(get_permalink()) . '" class="popup"><span class="icon">' . $icon . '</span><span class="text">email</span></a></li>';
    }

    if ($facebook == 'true') {
        $icon = file_get_contents(IMSCPB_DIR . "/images/social/facebook.svg");
        $html.='<li class="facebook"><a href="https://facebook.com/sharer/sharer.php?u=' . urlencode(get_permalink()) . ' " class="popup"><span class="icon">' . $icon . '</span><span class="text">facebook</span></a></li>';
    }

    if ($twitter == 'true') {
        $icon = file_get_contents(IMSCPB_DIR . "/images/social/twitter.svg");
        $html.= '<li class="twitter"><a href="https://twitter.com/home?status=' . urlencode(get_the_title() . ' - ' . get_permalink()) . '" class="popup"><span class="icon">' . $icon . '</span><span class="text">twitter</span></a></li>';
    }

    if ($google == 'true') {
        $icon = file_get_contents(IMSCPB_DIR . "/images/social/google_plus.svg");
        $html.='<li class="googleplus"><a href="https://plus.google.com/share?url=' . urlencode(get_the_title() . ' - ' . get_permalink()) . '" class="popup"><span class="icon">' . $icon . '</span><span class="text">google+</span></a></li>';
    }

    if ($linkedin == 'true') {
        $icon = file_get_contents(IMSCPB_DIR . "/images/social/linkedin.svg");
        $html.='<li class="linkedin"><a href="https://linkedin.com/shareArticle?mini=true&url=' . urlencode(get_permalink() . '&title=' . get_the_title()) . '" class="popup"><span class="icon">' . $icon . '</span><span class="text">linkedin</span></a></li>';
    }

    if ($pinterest == 'true') {
        $icon = file_get_contents(IMSCPB_DIR . "/images/social/pinterest.svg");
        $html.='<li class="pinterest"><a href="http://pinterest.com/' . $pinteresturl . '" target="_blank"><span class="icon">' . $icon . '</span><span class="text">pinterest</span></a></li>';
    }

    if ($instagram == 'true') {
        $icon = file_get_contents(IMSCPB_DIR . "/images/social/instagram.svg");
        $html.='<li class="instagram"><a href="http://instagram.com/' . $instagramurl . '" target="_blank"><span class="icon">' . $icon . '</span><span class="text">instagram</span></a></li>';
    }

    if ($tumblr == 'true') {
        $icon = file_get_contents(IMSCPB_DIR . "/images/social/tumblr.svg");
        $html.='<li class="tumblr"><a href="http://tumblr.com/' . $tumblrurl . '" target="_blank"><span class="icon">' . $icon . '</span><span class="text">tumblr</span></a></li>';
    }

    if ($pocket == 'true') {
        $icon = file_get_contents(IMSCPB_DIR . "/images/social/pocket.svg");
        $html.='<li class="pocket"><a href="https://getpocket.com/save?url=' . urlencode(get_permalink()) . '" class="popup"><span class="icon">' . $icon . '<span class="text">pocket</span></a></li>';
    }

    $html.="</ul></div>";


    if (!empty($content)) {
        /*
         * Asim Ashraf - DevBatch
         * px replace to empty in front-size
         * Date: 2-9-2014
         * Edit Start
         */
        $font_size = str_replace("px", "", $font_size);
        /*
         * Edit End;
         */
        $title = '<h2 style="color:' . $text_color . ';margin-bottom:10px;' . ($custom_font_size == 'true' ? ' font-size:' . $font_size . 'px; line-height:' . $line_height . '; letter-spacing:' . $letter_spacing . 'px;' : '') . '">' . $content . '</h2>';
    }
	
	if(!isset($title)) $title="";
    $html = '<div id="' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important; margin-top:0 !important;">' . $title . $html . '</div>';
    /*
     * Asim Ashraf - DevBatch
     * 
     * Date: 2-9-2014
     * Edit Start
     */
    $html.='<script type="text/javascript">
        jQuery(window).trigger("resize");
        jQuery(document).ready(function(){
		
		});
  </script>';
    /*
     * Edit End;
     */
    return $html;
}

add_shortcode('pbuilder_social', 'pbuilder_social');

function pbuilder_more($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);
    extract(shortcode_atts(array(
        'read_more_url' => '',
        'bot_margin' => 10,
        'custom_font_size' => 'false',
        'font_size' => 12,
        'line_height' => 'default', //14,
        'align' => 'center',
        'image' => '',
        'icon' => 'no-icon',
        'radius' => false,
        'color' => '#000000',
        'google_font' => 'default',
        'google_font_style' => 'default',
        'text_color' => '#232323',
        'text_hover_color' => '',
        'class' => '',
        'icon_color' => '#27a8e1',
        'icon_size' => '18',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    global $pbuilder;
    $font_str = '';
    if ($google_font != 'default' && !in_array($google_font, $pbuilder->standard_fonts)) {
        if (is_admin())
            $font_str = '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all">';
        else
            wp_enqueue_style('pbuilder_' . $google_font . "_" . $google_font_style, '//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all');
    }
    $content = do_shortcode($content);

    $randomId = $shortcode_id == '' ? 'frb_text_' . rand() : $shortcode_id;
    $content2 = '';
    $scriptTag = false;
	if (@$autop == 'true') {
        $scriptpos = strpos($content, '<script');
        while ($scriptpos != false) {
            $content2 .= nl2br(substr($content, 0, $scriptpos));
            $content = substr($content, $scriptpos);

            $scrclosepos = strpos($content, '/script>');
            if ($scrclosepos != false) {
                $content2 .= substr($content, 0, $scrclosepos + 8);
                $content = substr($content, $scrclosepos + 8);
            } else {
                $content2 .= $content;
                $content = '';
            }
            $scriptpos = strpos($content, '<script');
        }
        $content = $content2 . nl2br($content);
    }

    $alignArray = array('center', 'left', 'right');
    if (!in_array($align, $alignArray))
        $align = 'center';

    if ($icon_color == '') {
        $icon_color = 'transparent';
    }
    $bot_margin = (int) $bot_margin;

    $font_size = (int) $font_size;
    $icon_size = (int) $icon_size . 'px';

    $line_height = $line_height != 'default' ? (int) $line_height . "px" : '100%';
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $font_style = '';
    if ($google_font != 'default') {
        $font_style = 'font-family: ' . str_replace('+', ' ', $google_font) . ', serif !important; ';
        $ipos = strpos($google_font_style, 'italic');
        if ($google_font_style == 'regular') {
            //$font_style .= 'font-weight:400; font-style: normal !important; ';
        } else if ($ipos !== false) {
            if ($ipos > 0) {
                $font_style .= 'font-weight:' . substr($google_font_style, 0, $ipos) . '; ';
            } else {
                //$font_style .= 'font-weight: 400 !important; ';
            }
            $font_style .= 'font-style:italic !important; ';
        } else {
            //$font_style .= 'font-weight:' . $google_font_style . '; font-style: normal !important; ';
        }
    }
    $style = '<style type="text/css" scoped="scoped">
    #' . $randomId . ', #' . $randomId . ' *{' . $font_style . '}
    #' . $randomId . '.frb_more_tag ul li a{' . ($text_color != '' ? 'color: ' . $text_color . ';' : '') . '}
    #' . $randomId . '.frb_more_tag ul li a:hover{' . ($text_hover_color != '' ? 'color: ' . $text_hover_color . ' !important;' : '') . '}
	#' . $randomId . '.frb_more_tag li i {font-size:' . $icon_size . ' !important; color: ' . $icon_color . ' !important; line-height:' . $line_height . ' !important;}
	</style>';
    $html = $animSpeedSet . $style . '<ul class="frb_bullets_wrapper ' . $class . $animate . ' id="' . $randomId . '">';
    $cont = explode("\n", $content);
    foreach ($cont as $textline) {
        if (substr($icon, 0, 4) == 'icon') {
            $html .= '<li><istyle="font-size:' . $icon_size . ' !important; color: ' . $icon_color . ' !important; line-height:' . $line_height . ' !important;" class="fawesome ' . $icon . '"></i><a class="frb_text ' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important; text-align:' . $align . ';' . ($custom_font_size == 'true' ? ' font-size:' . $font_size . 'px; line-height:' . $line_height . ';' : '') . ' color:' . $text_color . ';" href="' . $read_more_url . '">' . $textline . '</a></li>';
        } else {
            $html .= '<li><i style="font-size:' . $icon_size . ' !important; color: ' . $icon_color . ' !important; line-height:' . $line_height . ' !important;" class="frb_icon ' . substr($icon, 0, 2) . ' ' . $icon . '"></i><a class="frb_text ' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important; text-align:' . $align . ';' . ($custom_font_size == 'true' ? ' font-size:' . $font_size . 'px; line-height:' . $line_height . ';' : '') . ' color:' . $text_color . ';" href="' . $read_more_url . '">' . $textline . '</a></li>';
        }
    }
    $html .= '</ul>';

    $html = $font_str . $style . $animSpeedSet . '<div id="' . $randomId . '" class="frb_more_tag ' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important; text-align:' . $align . ';' . ($custom_font_size == 'true' ? ' font-size:' . $font_size . 'px; line-height:' . $line_height . ';' : '') . '">' . $html . '</div>';

    return $html;
//    return '<!--more--><div class="frb_more_tag"><a href="#"></a></div>';
}

add_shortcode('pbuilder_more', 'pbuilder_more');

function pbuilder_text($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'autop' => 'true',
        'bot_margin' => 24,
        'custom_font_size' => 'false',
        'font_size' => 12,
        'line_height' => 'default', //14,
        'align' => 'left',
        'google_font' => 'default',
        'google_font_style' => 'default',
        'text_color' => '#232323',
        'text_hover_color' => '',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    global $pbuilder;
    $font_str = '';
    if ($google_font != 'default' && !in_array($google_font, $pbuilder->standard_fonts)) {
        if (is_admin())
            $font_str = '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all">';
        else
            wp_enqueue_style('pbuilder_' . $google_font . "_" . $google_font_style, '//fonts.googleapis.com/css?family=' . $google_font . ":" . $google_font_style . '&subset=all');
    }

    $content = do_shortcode($content);

    $randomId = $shortcode_id == '' ? 'frb_text_' . rand() : $shortcode_id;
    $content2 = '';
    $scriptTag = false;
    if ($autop == 'true') {
        $scriptpos = strpos($content, '<script');
        while ($scriptpos != false) {
            $content2 .= nl2br(substr($content, 0, $scriptpos));
            $content = substr($content, $scriptpos);

            $scrclosepos = strpos($content, '/script>');
            if ($scrclosepos != false) {
                $content2 .= substr($content, 0, $scrclosepos + 8);
                $content = substr($content, $scrclosepos + 8);
            } else {
                $content2 .= $content;
                $content = '';
            }
            $scriptpos = strpos($content, '<script');
        }
        $content = $content2 . nl2br($content);
    }

    $alignArray = array('left', 'right', 'center');
    if (!in_array($align, $alignArray))
        $align = 'left';

    $html = '<div class="frb_text" style="color:' . $text_color . ';">' . $content . '</div>';

    $bot_margin = (int) $bot_margin;
    $font_size = (int) $font_size;
    $line_height = $line_height != 'default' ? (int) $line_height . "px" : '110%';
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $font_style = '';
    if ($google_font != 'default') {
        $font_style = 'font-family: ' . str_replace('+', ' ', $google_font) . ', serif !important; ';
        $ipos = strpos($google_font_style, 'italic');
        if ($google_font_style == 'regular') {
            //$font_style .= 'font-weight:400; font-style: normal !important; ';
        } else if ($ipos !== false) {
            if ($ipos > 0) {
                $font_style .= 'font-weight:' . substr($google_font_style, 0, $ipos) . '; ';
            } else {
                //$font_style .= 'font-weight: 400 !important; ';
            }
            $font_style .= 'font-style:italic !important; ';
        } else {
            //$font_style .= 'font-weight:' . $google_font_style . '; font-style: normal !important; ';
        }
    }

    $style = '
    <style type="text/css" scoped="scoped">
    #' . $randomId . ', #' . $randomId . ' *{' . $font_style . '}
    #' . $randomId . ' a{' . ($text_color != '' ? 'color: ' . $text_color . ';' : '') . '}
    #' . $randomId . ' a:hover{' . ($text_hover_color != '' ? 'color: ' . $text_hover_color . ';' : '') . '}
	</style>';

    $html = $font_str . $style . $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important; text-align:' . $align . ';' . ($custom_font_size == 'true' ? ' font-size:' . $font_size . 'px; line-height:' . $line_height . ';' : '') . '">' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_text', 'pbuilder_text');

function pbuilder_image($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'desc' => '',
        'text_align' => 'center',
        'link' => '',
        'link_type' => 'lightbox-image',
        'iframe_width' => '600',
        'iframe_height' => '300',
        'hover_icon' => 'ba-search',
        'hover_icon_size' => 30,
        'hover_shade_color' => '#000000',
        'hover_shade_opacity' => '0.4',
        'bot_margin' => 24,
        'round' => 'false',
        'round_width' => '0px',
        'image_width' => '600px',
        'image_height' => '300px',
        'custom_dimensions' => 'false',
        'border' => 'false',
        'border_color' => '#376a6e',
        'border_hover_color' => '#376a6e',
        'border_width' => '0px',
        'border_style' => 'solid',
        'shadow' => 'false',
        'shadow_color' => '#376a6e',
        'shadow_h_shadow' => '0px',
        'shadow_v_shadow' => '0px',
        'shadow_blur' => '0px',
        'desc_color' => '#376a6e',
        'back_color' => '#ebecee',
        'desc_hover_color' => '#376a6e',
        'back_hover_color' => '#ebecee',
        'alert' => 'false',
        'alerttext' => 'This image was clicked...',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $content = do_shortcode($content);
    $desc = do_shortcode($desc);
    $link = do_shortcode(urldecode($link));

    $desc = nl2br($desc);
    $alignArray = array('left', 'right', 'center');
    $hover_shade_opacity = (float) $hover_shade_opacity;
    $hover_shade_opacity = $hover_shade_opacity > 1 ? 1 : $hover_shade_opacity;
    $hover_shade_opacity = $hover_shade_opacity < 0 ? 0 : $hover_shade_opacity;
    if (!in_array($text_align, $alignArray))
        $text_align = 'center';
    if ($border_color == '')
        $border_color = 'transparent';
    if ($back_color == '')
        $back_color = 'transparent';
    if ($desc_color == '')
        $desc_color = 'transparent';
    if ($border_hover_color == '')
        $border_hover_color = 'transparent';
    if ($desc_hover_color == '')
        $desc_hover_color = 'transparent';
    if ($back_hover_color == '')
        $back_hover_color = 'transparent';
    $hover_icon_size = (int) $hover_icon_size;
    $iframe_width = (int) $iframe_width;
    $iframe_height = (int) $iframe_height;
    $image_width = (int) $image_width;
    $image_height = (int) $image_height;
    $custom_dimensions = $custom_dimensions == 'true' ? true : false;
    $randomId = $shortcode_id == '' ? 'frb_image_' . rand() : $shortcode_id;

    $width = $custom_dimensions ? ('width:' . $image_width . 'px; ') : '';
    $height = $custom_dimensions ? ('height:' . $image_height . 'px !important;') : '';
	
    $css = 'border-color:' . $border_color . '; ' . $width . $height;
    
	$css_span='';
	//$css_span = 'border-color:' . $border_color . ';';

    if ($round != 'false') {
        if ($border != 'false') {
            $rw = str_replace("px", "", $round_width);
            $bw = str_replace("px", "", $border_width);
            $radius = 0;
            if ($rw > $bw)
                $radius = $rw - $bw - 1;
            $css .= 'border-radius: ' . $radius . 'px;';
        } else
            $css .= 'border-radius: ' . $round_width . ';';

        //$css_span .= 'border-radius: ' . $round_width . ';';
    }

    if ($text_align == 'center')
        $css .= 'margin:0 auto;';
    else if ($text_align == 'right')
        $css .= 'float:right;';
    else
        $css .= 'float:left;';

    if ($border != 'false') {
        //$css .= "border-width:".$border_width.";";
        //$css .= "border-style:".$border_style.";";

        $css .= "border-width:" . $border_width . ";";
        $css .= "border-style:" . $border_style . ";";
    }

    if ($shadow != 'false') {
        $css .= 'box-shadow: '.$shadow_h_shadow.' '.$shadow_v_shadow.' '.$shadow_blur.' '.$shadow_color.' !important;';

        //$css_span .= 'box-shadow: ' . $shadow_h_shadow . ' ' . $shadow_v_shadow . ' ' . $shadow_blur . ' ' . $shadow_color . ';';
    }
    global $wpdb;
    $ImageData = $wpdb->get_row("SELECT `ID`,`post_title`, `post_excerpt` FROM {$wpdb->prefix}posts WHERE guid LIKE '%$content%'");
    if($ImageData) {
		$alt = get_post_meta($ImageData->ID, '_wp_attachment_image_alt', true);
		/*    echo '<pre>';
		  print_r($attachmentAttr);
		  echo '</pre>'; */
		if ($ImageData->post_excerpt != "") {
			$ImgTitle = $ImageData->post_excerpt;
		} else {
			$ImgTitle = $ImageData->post_title;
		}
	} else {
		$ImgTitle="";
		$alt = "";
	}	
	
    if (count($alt) && !empty($alt)) {
        $altText = $alt;
    } else {
        $altText = $ImgTitle;
    }

    $html = '<span class="frb_image_inner' . ($border != 'false' ? ' frb_image_border ' : '') . ($round != 'false' ? ' frb_image_round ' : '') . '" style="' . $css_span . '" data-bordercolor="' . $border_color . '" data-borderhover="' . $border_hover_color . '">
		<img class="' . ($round != 'false' ? 'frb_image_round' : 'frb_image_flat') . '" src="' . $content . '" title="' . $ImgTitle . '" alt="' . $altText . '" style="' . $css . '" />
		<span style="clear:both; display:block;"></span>';


    if (!empty($link)) {
        $html.='<span class="frb_image_hover" data-transparency="' . $hover_shade_opacity . '"></span>';
    }


    if (substr($hover_icon, 0, 4) == 'icon') {
        $html .= '<i class="fawesome ' . $hover_icon . '" style="line-height:' . $hover_icon_size . 'px; font-size:' . $hover_icon_size . 'px; height:' . $hover_icon_size . 'px; width:' . $hover_icon_size . 'px; margin:' . (-$hover_icon_size / 2) . 'px "></i>';
    } else {
        $html .= '<i class="frb_icon ' . substr($hover_icon, 0, 2) . ' ' . $hover_icon . '" style="line-height:' . $hover_icon_size . 'px; font-size:' . $hover_icon_size . 'px; height:' . $hover_icon_size . 'px; width:' . $hover_icon_size . 'px; margin:' . (-$hover_icon_size / 2) . 'px "></i>';
    }

    $html .= '</span>';

    if ($desc != '')
        $html .= '<span class="frb_image_desc' . ($round != 'false' ? ' frb_image_round' : '') . '" style="color:' . $desc_color . '; text-align:' . $text_align . '; background:' . $back_color . '" data-color="' . $desc_color . '" data-hovercolor="' . $desc_hover_color . '" data-backcolor="' . $back_color . '" data-backhover="' . $back_hover_color . '">' . $desc . '</span>';

    if ($alert == "true" && !empty($alerttext)) {
        $html = '<div onclick="alert(\'' . $alerttext . '\');return false;">' . $html . '</div>';
    }

    if ($link != '') {
        switch ($link_type) {
            case 'new-tab' : $lightbox = '" target="_blank';
                break;
            case 'lightbox-image' : $lightbox = ' frb_lightbox_link" rel="frbprettyphoto';
                break;
            case 'lightbox-iframe' : $lightbox = ' frb_lightbox_link" rel="frbprettyphoto';
                $link .= '?iframe=true&width=' . $iframe_width . '&height=' . $iframe_height;
                break;
            default : $lightbox = '';
        }
        $html = '<a class="' . $lightbox . '" style="display:inline-block;" href=' . $link . '>' . $html . '</a>';
    }



    $html = '<span style="text-align:' . $text_align . '; width:100%; display:block;">' . $html . '</span>';


    $html = '<div class="frb_image" style="' . $height . '">' . $html . '</div>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $style = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . ' .frb_image_hover {background:' . $hover_shade_color . ';}' .
            '</style>';


    $html = $animSpeedSet . $style . '<div id="' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_image', 'pbuilder_image');

//Replacement for timezone_name_from_abbr which sometimes does not return a timezone name
function tz_offset_to_name($offset = 0) //in seconds
{
        $abbrarray = timezone_abbreviations_list();
        foreach ($abbrarray as $abbr)
        {
                foreach ($abbr as $city)
                {
                        if ($city['offset'] == $offset)
                        {
                                return $city['timezone_id'];
                        }
                }
        }

        return FALSE;
}

function pbuilder_timer($atts) {

    $atts = pbuilder_decode($atts);
    $dtNow = new DateTime('now', new DateTimeZone('UTC'));
    $dtNow->modify("+3 days");

    extract(shortcode_atts(array(
        'timer_type' => 'flipclock',
        'enddate' => $dtNow->format("Y/m/d H:i:s O"), //date("Y/m/d H:i:s O",strtotime("+3 days")),
        'showlabels' => 'true',
        'align' => 'left',
        'label_color' => '#777777',
        'timer_style' => 'Fixed',
        'timout_url' => '',
        'days_label' => 'Days',
        'hours_label' => 'Hours',
        'minutes_label' => 'Minutes',
        'seconds_label' => 'Seconds',
        'fluid_height' => '166px',
        'fluid_width' => '200px',
        'fluid_bg' => '#ffffff',
        'fluid_font_size' => '180px',
        'fluid_font_color' => '#444444',
        'fluid_font_margin' => '20px',
        'fluid_label_size' => '30px',
        'fluid_label_color' => '#444444',
        'fluid_label_align' => 'left',
        'evergreen_days' => 0,
        'evergreen_hours' => 0,
        'evergreen_minutes' => 0,
        'evergreen_seconds' => 0,
        'bot_margin' => 24,
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => '',
        'pbuilder_scid' => '',
        'pbuilder_pgid' => '',
                    ), $atts));

    $randomId = $shortcode_id == '' ? 'frb_timer_' . rand() : $shortcode_id;
    $bot_margin = (int) $bot_margin;

    $alignArray = array('left', 'right', 'center');

    if (!in_array($align, $alignArray))
        $align = 'left';

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $dtNow = new DateTime();
    $dtNow->setTimezone(new DateTimeZone('UTC'));
    //  echo $enddate;
    $dtEnd = new DateTime($enddate);
    $offset = $dtEnd->getOffset();
		
	$timezone = tz_offset_to_name($offset);
	$offset = $offset / 3600;
	
    $dtNow->setTimezone(new DateTimeZone($timezone));
    $dtEnd->setTimezone(new DateTimeZone($timezone));
	
	
	
    $offsetHours = intval($offset);
    $offsetMinutes = ($offset - $offsetHours) * 60;

    //$now=strtotime("now");
    //$end=strtotime($enddate);
    //$diff=$end-$now;

    $now = strtotime($dtNow->format('r'));
    $end = strtotime($dtEnd->format('r'));
    $diff = $end - $now;

    $params = array();
    $params[] = "clockFace: 'DailyCounter'";
    $params[] = "countdown: true";
    $elements = implode(",", $params);

    ob_start();


    if ($animation_speed != 0) {
        ?>
        <style type="text/css" scoped="scoped">
            #<?php echo $randomId ?> .frb_onScreen.frb_animated {
                -webkit-animation-duration: <?php echo (int) $animation_speed; ?>ms; 
                animation-duration: <?php echo (int) $animation_speed; ?>ms;
            }
            #<?php echo $randomId ?> .frb_onScreen.frb_hinge {
                -webkit-animation-duration: <?php echo ((int) $animation_speed * 2); ?>ms; 
                animation-duration: <?php echo ((int) $animation_speed * 2); ?>ms;
            }
            <?php
            if ($showlabels == "true") {
                ?>
                #<?php echo $randomId ?> .flip-clock-divider .flip-clock-label {
                    color: <?php echo $label_color; ?>;
                }
                #<?php echo $randomId ?> .flip-clock-dot { 
                    background: <?php echo $label_color; ?>; 
                }	
                <?php
            } else {
                ?>
                #<?php echo $randomId ?> .flip-clock-divider .flip-clock-label,
                #<?php echo $randomId ?> .counter-caption {
                    display:none;
                }
                #<?php echo $randomId ?> .flip-clock-dot { 
                    background: <?php echo $label_color; ?>; 
                }	
                <?php
            }

            if ($timer_type != "flipclock") {
                ?>
                #<?php echo $randomId ?> .counter-block{
                    margin-right: <?php echo $fluid_font_margin; ?>;
                }

                #<?php echo $randomId ?> .counter-block .counter{
                    height: <?php echo $fluid_height; ?>;
                    width: <?php echo $fluid_width; ?>;
                }

                #<?php echo $randomId ?> .counter-block .counter .number{
                    background-color: <?php echo $fluid_bg; ?>;
                    color: <?php echo $fluid_font_color; ?>;
                    font-size: <?php echo $fluid_font_size; ?>;
                    line-height: <?php echo $fluid_height; ?>;
                }

                #<?php echo $randomId ?> .counter-block .counter-caption{
                    color: <?php echo $fluid_label_color; ?>;
                    font-size: <?php echo $fluid_label_size; ?>;
                    text-align: <?php echo $fluid_label_align; ?>;
                }
                <?php
            }
            ?>
        </style>
        <?php
    }
    ?>
    <div id="<?php echo $randomId; ?>" class="<?php echo $class . " " . $timer_type; ?>" <?php echo $animate; ?> style="padding-bottom:<?php echo $bot_margin; ?>px !important; position: relative; text-align: <?php echo $align; ?>;">
        <span style="text-align:<?php echo $align; ?>; display:inline-block; min-width: 460px;" class="<?php echo $timer_type; ?>_inner">
            <div class="clock<?php echo $randomId; ?>" style="" offset-hours="<?php echo $offsetHours; ?>" offset-minutes="<?php echo $offsetMinutes; ?>"></div>
            <script type='text/javascript'>
                        var clock;
                        jQuery(document).ready(function() {
    <?php
    if ($timer_style == "Fixed") {
        ?>
                    var start_date = new Date(<?php echo $dtNow->format("Y"); ?>, <?php echo $dtNow->format("m") - 1; ?>, <?php echo $dtNow->format("d"); ?>, <?php echo $dtNow->format("H"); ?>, <?php echo $dtNow->format("i"); ?>, <?php echo $dtNow->format("s"); ?>);
                            var end_date = new Date(<?php echo $dtEnd->format("Y"); ?>, <?php echo $dtEnd->format("m") - 1; ?>, <?php echo $dtEnd->format("d"); ?>, <?php echo $dtEnd->format("H"); ?>, <?php echo $dtEnd->format("i"); ?>, <?php echo $dtEnd->format("s"); ?>);
                            /*var start_date = new Date();
                             var offset = start_date.getTimezoneOffset()/60;
                             var offsetHours = parseInt(offset);
                             var offsetMinutes = (offset - offsetHours)*60;
                             
                             start_date.setHours(start_date.getHours() + offsetHours);
                             start_date.setMinutes(start_date.getMinutes() + offsetMinutes);
                             start_date.setHours(start_date.getHours() + (<?php echo $offsetHours; ?>));
                             start_date.setMinutes(start_date.getMinutes() + (<?php echo $offsetMinutes; ?>));*/

                            var diff = (end_date.getTime() - start_date.getTime()) / 1000;
        <?php
    } else {
    		  $evergreen_seconds_left=$evergreen_days*86400+$evergreen_hours*3600+$evergreen_minutes*60+$evergreen_seconds;
    			
    			$evergreen_page_cookie_ids=get_post_meta($pbuilder_pgid, 'evergreen_cookie_ids', true);
    			if($evergreen_page_cookie_ids){
    				$evergreen_cookie_ids = explode(',' , get_post_meta($pbuilder_pgid, 'evergreen_cookie_ids', true));
    			} else {
    				$evergreen_cookie_ids=array();
    			}
    			
    			
    			$evergreen_cookie_id= ($pbuilder_scid != "" && $pbuilder_pgid != "") ? $pbuilder_scid . "_" . $pbuilder_pgid : '1';
    			if(!in_array($evergreen_cookie_id, $evergreen_cookie_ids)) {
    				$evergreen_cookie_ids[]=$evergreen_cookie_id;
    				
    			}
    			update_post_meta($pbuilder_pgid, 'evergreen_cookie_ids', implode(',',$evergreen_cookie_ids));
    			
    			$evergreen_phpcookie=json_encode(array('unixend'=>time()+$evergreen_seconds_left, 'timeouturl'=>$timout_url));
    			
          ?>
          var evergreen_days = <?php echo $evergreen_days; ?>;
					var evergreen_hours = <?php echo $evergreen_hours; ?>;
					var evergreen_minutes = <?php echo $evergreen_minutes; ?>;
					var evergreen_seconds = <?php echo $evergreen_seconds; ?>;
					var start_date = new Date(<?php echo $dtNow->format("Y"); ?>, <?php echo $dtNow->format("m") - 1; ?>, <?php echo $dtNow->format("d"); ?>, <?php echo $dtNow->format("H"); ?>, <?php echo $dtNow->format("i"); ?>, <?php echo $dtNow->format("s"); ?>);
					var end_date = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate() + evergreen_days), (start_date.getHours() + evergreen_hours), (start_date.getMinutes() + evergreen_minutes), (start_date.getSeconds() + evergreen_seconds));
					var cookieid = '<?php echo ($pbuilder_scid != "" && $pbuilder_pgid != "") ? $pbuilder_scid . "_" . $pbuilder_pgid : '1' ?>';
					
					<?php
					if (current_user_can('administrator')) {
						?>
									//console.log('Clear cookie for admin');
									removecookie("timer_start_date" + cookieid);
									removecookie("timer_end_date" + cookieid);
									removecookie("timer_end_phpcookie" + cookieid);
						<?php
					}
					
					
					?>
					if (getcookie("timer_start_date" + cookieid) == null || getcookie("timer_end_date" + cookieid) == null){
						  setcookie("timer_start_date" + cookieid, start_date, (evergreen_days == 0?1:evergreen_seconds));
							setcookie("timer_end_date" + cookieid, end_date, (evergreen_days == 0?1:evergreen_seconds));
							setcookie("timer_end_phpcookie" + cookieid, '<?php echo $evergreen_phpcookie; ?>', (evergreen_days == 0?1:evergreen_seconds));
					} else{
						  //start_date = new Date(getcookie("timer_start_date" + cookieid));
							end_date = new Date(getcookie("timer_end_date" + cookieid));
							//console.log('Load Cookie: '+start_date+' = '+end_date);
					}				
										
					//var end_date = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate() + evergreen_days), (start_date.getHours() + evergreen_hours), (start_date.getMinutes() + evergreen_minutes), (start_date.getSeconds() + evergreen_seconds));
					/*var now = new Date();
					var offset = now.getTimezoneOffset() / 60;
					var offsetHours = parseInt(offset);
					var offsetMinutes = (offset - offsetHours) * 60;
					
					now.setHours(now.getHours() + offsetHours);
					now.setMinutes(now.getMinutes() + offsetMinutes);
					//now.setHours(now.getHours() + now.getTimezoneOffset()/60);
					now.setHours(now.getHours() + (<?php echo $offsetHours; ?>));
					now.setMinutes(now.getMinutes() + (<?php echo $offsetMinutes; ?>));*/
					
					
					var diff = (end_date.getTime() - start_date.getTime()) / 1000;
        <?php
    }
    ?>

    <?php if ($timer_type == "flipclock") { ?>
	
                    if (diff <= 0) diff = 0;
                            var lan_eng = jQuery.extend({}, FlipClock.Lang.English);
                            FlipClock.Lang.English["days"] = "<?php echo $days_label; ?>";
                            FlipClock.Lang.English["hours"] = "<?php echo $hours_label; ?>";
                            FlipClock.Lang.English["minutes"] = "<?php echo $minutes_label; ?>";
                            FlipClock.Lang.English["seconds"] = "<?php echo $seconds_label; ?>";
                            clock = jQuery('.clock<?php echo $randomId; ?>').FlipClock(diff, {<?php
        echo $elements;
        if ($timout_url != '') {
            ?>, callbacks: {stop: function() {
								window.location.href = '<?php echo $timout_url; ?>';
							}}<?php } ?>});
                            FlipClock.Lang.English = jQuery.extend({}, lan_eng);
                            /**************************************/
                            // Code added by Asim Ashraf - DevBatch
                            // DateTime: 28 Jan 2015
                            // Code Edit Start
                            /************************************/
                            FlipClock.Resize();
                            /**************************************/
                            // Code added by Asim Ashraf - DevBatch
                            // DateTime: 28 Jan 2015
                            // Code Edit End
                            /************************************/
        <?php
    } else {
        ?>
                    clock = jQuery('.clock<?php echo $randomId; ?>').mbComingsoon({
						expiryDate: end_date, speed:100, localization: {
							days: "<?php echo $days_label; ?>", 
							hours: "<?php echo $hours_label; ?>", 
							minutes: "<?php echo $minutes_label; ?>", 
							seconds: "<?php echo $seconds_label; ?>"
							} <?php if ($timout_url != '') { ?>, 
							callBack: function(){
								window.location.href = '<?php echo $timout_url; ?>'}<?php } ?>
							});
                            /**************************************/
                            // Code added by Asim Ashraf - DevBatch
                            // DateTime: 28 Jan 2015
                            // Code Edit Start
                            /************************************/
                            mbComingsoonResize();
                            /**************************************/
                            // Code added by Asim Ashraf - DevBatch
                            // DateTime: 28 Jan 2015
                            // Code Edit END
                            /************************************/
        <?php
    }
    ?>
                });
            </script>
        </span>
    </div>
    <?php
    $html = ob_get_contents();
    ob_clean();

    return $html;
}

add_shortcode('pbuilder_timer', 'pbuilder_timer');

function pbuilder_textarea($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'title' => '',
		'showcontent' => 'true',
        'boxwidth' => '300',
        'boxheight' => '100',
        'fullwidth' => 'true',
        'autop' => 'true',
        'stripbr' => 'true',
        'bot_margin' => 24,
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $boxwidth = (int) $boxwidth;
    $boxheight = (int) $boxheight;

    $content = do_shortcode($content);

    $randomId = $shortcode_id == '' ? 'frb_textarea_' . rand() : $shortcode_id;
    $bot_margin = (int) $bot_margin;

    $content2 = '';
    $scriptTag = false;
    if ($autop == 'true') {
        $scriptpos = strpos($content, '<script');
        while ($scriptpos != false) {
            $content2 .= nl2br(substr($content, 0, $scriptpos));
            $content = substr($content, $scriptpos);

            $scrclosepos = strpos($content, '/script>');
            if ($scrclosepos != false) {
                $content2 .= substr($content, 0, $scrclosepos + 8);
                $content = substr($content, $scrclosepos + 8);
            } else {
                $content2 .= $content;
                $content = '';
            }
            $scriptpos = strpos($content, '<script');
        }
        $content = $content2 . nl2br($content);
    }

    if ($stripbr == "true") {
        $content = preg_replace("#<br\s?/?>#i", "", $content);
    }

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    if ($fullwidth == "true") {
        $boxwidth = "100%";
    } else {
        $boxwidth.="px";
    }

    $id = "textarea_" . $randomId;

    $embed = do_shortcode($content);

    if (!empty($title)) {
        $head = "<h4>$title</h4>";
    } else {
		$head ="";
	}

    if ($showcontent == "true") {
        $showcode = do_shortcode($content) . "<BR>";
    }

    //$html.="$head $showcode<em>Just click on the box below, then copy and paste...</em><BR><textarea id='".$id."' onClick=\"SelectAll('".$id."');\" style='width:".$boxwidth.";height:".$boxheight."px;'>".$embed."</textarea>";
    $html="$head $showcode<em>Just click on the box below, then copy and paste...</em><BR><textarea id='" . $id . "' onClick=\"SelectAll('" . $id . "');\" style='width:" . $boxwidth . ";height:" . $boxheight . "px;color:#111111 !important;'>" . $embed . "</textarea>";

    $html.="<script type=\"text/javascript\">
	function SelectAll(id)
	{
	    document.getElementById(id).focus();
	    document.getElementById(id).select();
	}
	</script>";


    $html = $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . ' ' . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_textarea', 'pbuilder_textarea');

function pbuilder_iframe($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'url' => '',
        'iframe_width' => '100%',
        'iframe_height' => '600',
        'fullwidth' => 'false',
        'noscroll' => 'false',
        'bot_margin' => 24,
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $url = do_shortcode($url);

    $iframe_width = (int) $iframe_width;
    $iframe_height = (int) $iframe_height;

    $randomId = $shortcode_id == '' ? 'frb_iframe_' . rand() : $shortcode_id;
    $bot_margin = (int) $bot_margin;

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    if ($fullwidth == "true") {
        $iframe_width = "100%";
    } else {
        $iframe_width.="px";
    }
    $iframe_height.="px";

    if ($noscroll == "true") {
        $overflow = 'overflow: hidden;';
        $scrolling = "scrolling='no'";
        $extrastyle = "<style>#iframe" . $randomId . "::-webkit-scrollbar {display: none;}</style>";
    } else {
        $scrolling = "scrolling='auto'";
    }

    $iframe = "$extrastyle  <iframe id='iframe" . $randomId . "' style='width:" . $iframe_width . ";height:" . $iframe_height . ";border:0px; " . $overflow . "' src='" . $url . "' " . $scrolling . " seamless frameborder='0'></iframe>";

    $html = $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . ' ' . $animate . ' style="margin-bottom:-8px !important;padding-bottom:' . $bot_margin . 'px !important;">' . $iframe . '</div>';

    return $html;
}

add_shortcode('pbuilder_iframe', 'pbuilder_iframe');

function pbuilder_video($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'vtype' => '',
        'url' => '',
        'mp4' => '',
        'ogv' => '',
        'webm' => '',
        'autoplay' => 'false',
        'minimalbranding' => 'true',
        'forcehd' => 'false',
        'hidecontrols' => 'false',
        'bot_margin' => 24,
        'width' => '640',
        'height' => '360',
        'poster' => '',
        'fullscreen' => 'false',
        'preload' => 'false',
        'presetsize' => '3',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $url = do_shortcode($url);
    $content = do_shortcode($content);

    $randomId = $shortcode_id == '' ? 'frb_video_' . rand() : $shortcode_id;
    $width = (int) $width;
    $height = (int) $height;
    $bot_margin = (int) $bot_margin;

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    // Sort Width...

    switch ($presetsize) {
        case '1' :
            $height = 540;
            $width = 960;
            break;
        case '2' :
            $height = 478;
            $width = 850;
            break;
        case '3' :
            $height = 360;
            $width = 640;
            break;
        case '4' :
            $height = 309;
            $width = 550;
            break;
        case '5' :
            $height = 281;
            $width = 500;
            break;
    }


    $videocode = pbuilder_get_video_iframe($vtype, $url, $mp4, $ogv, $webm, $content, $width, $height, $autoplay, $minimalbranding, $forcehd, $hidecontrols, $preload, $fullscreen, $poster);
    //short code will append here
    if ($presetsize == "fluid") {

        // Adjust responsive Padding
        switch ($vtype) {
            case 'youtube' :
                $btpad = "padding-bottom: 56.25%;"; //16:9
                //$btpad="padding-bottom: 75%;";//4:3
                break;
            case 'embed':
                if (!empty($content)) {
					/* This breaks wistia videos
                    if (!strpos($content, 'data-role="evp-video"') && !strpos($content, 'class="wistia_embed"')) {
                        $btpad = "padding-bottom: 56.25%;";
                    }
					*/
					if (!strpos($content, 'data-role="evp-video"')) {
                        $btpad = "padding-bottom: 56.25%;";
                    }
                }
                break;
        }

        $video = "<style>." . $randomId . "-container {height: 0;  position: relative; " . $btpad . " overflow: hidden; max-width: 100%; height:auto; } ." . $randomId . "-container iframe, ." . $randomId . "-container object, ." . $randomId . "-container " . $randomId . " { position: absolute; top: 0; left: 0; width: 100%; height: 100%;' }</style><div class='" . $randomId . "-container'>" . $videocode . "</div>";
    } else {

        $videocode = preg_replace('/width=(.*?) /', "width='" . $width . "'", $videocode);
        $videocode = preg_replace('/height=(.*?) /', "height='" . $height . "'", $videocode);

        $video = "<div style='width:" . $width . "px;height:" . $height . ";margin-right:auto;margin-left:auto;'>" . $videocode . "</div>";
    }

	if(!isset($auto_width)) $auto_width=false;
    $html = $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . ' frb_video_wrapper' . ($auto_width == 'true' ? ' frb_auto_width' : '') . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $video . '</div>';

    return $html;
}

add_shortcode('pbuilder_video', 'pbuilder_video');

function pbuilder_get_video_iframe($vtype, $url, $mp4, $ogv, $webm, $content, $width, $height, $autoplay, $minimalbranding, $forcehd, $hidecontrols, $preload, $fullscreen, $poster) {


    switch ($vtype) {
        case 'embed' :
            if (!empty($content)) {
                return $content;
            }
            break;

        case 'direct' :

            if (!empty($mp4) || !empty($ogv) || !empty($webm)) {

                $parms = array();
                if ($autoplay == 'true') {
                    $parms[] = "autoplay";
                }
                if ($preload == 'true') {
                    $parms[] = "preload";
                }
                if (!empty($parms)) {
                    $playerparms = implode(" ", $parms);
                }



                if ($fullscreen == 'false') {
                    $styleelements.=".fp-fullscreen{display:none !important;}";
                }

                if ($hidecontrols == 'true') {
                    $styleelements.=".fp-context-menu,.fp-embed,.fp-controls,.fp-message,.fp-fullscreen,.fp-time,.fp-context-menu,.fp-help {display:none !important;}";
                }

                if ($styleelements) {
                    $html.="<style>" . $styleelements . "</style>";
                }


                //$html.='<script>flowplayer.conf{adaptiveRatio = true;'.($fullscreen == 'false' ? 'fullscreen = false;' : '').'};</script>';
                $html.='<script>jQuery(document).ready(function(){flowplayer.conf = {adaptiveRatio: true, ' . ($fullscreen == 'false' ? 'fullscreen: false' : '') . '};});</script>';


                $html.='<div class="flowplayer ' . ($poster != '' && $autoplay == 'false' ? 'is-splash' : '') . ' 3" data-embed="false" data-swf="' . IMSCPB_URL . '/js/flowplayer.swf" style="' . ($poster != '' && $autoplay == 'false' ? 'background:#777 url(' . $poster . ') no-repeat;background-size: cover;' : '') . '">
				   <video ' . $playerparms . '>';
                if (!empty($webm)) {
                    $html.='<source type="video/webm" src="' . $webm . '">';
                }
                if (!empty($mp4)) {
                    $html.='<source type="video/mp4" src="' . $mp4 . '">';
                }
                if (!empty($ogv)) {
                    $html.='<source type="video/ogg" src="' . $ogv . '">';
                }

                $html.='</video></div>';

                return $html;
            }

            break;

        case 'youtube' :
            if (!empty($url)) {
                preg_match('/^(https?:\/\/)(www\.)?([^\/]+)(\.com)/i', $url, $matches);

                if ($matches[3] == 'youtube') {
                    preg_match('/^(https?:\/\/)?(www\.)?youtube\.com\/(watch\?v=)?(v\/)?([^&]+)/i', $url, $matches);
                    $match = $matches[5];

                    $query = array();

                    if ($autoplay == 'true') {
                        $query[] = "autoplay=1";
                    }
                    if ($minimalbranding == 'true') {
                        $query[] = "rel=0";
                        $query[] = "modestbranding=1";
                        $query[] = "showinfo=0";
                    }
                    if ($hidecontrols == 'true') {
                        $query[] = "controls=0";
                    }
                    if ($forcehd == 'true') {
                        $query[] = "hd=1";
                    }
                    $params = implode("&amp;", $query);

                    $html = '<iframe src="http://www.youtube.com/embed/' . $match . '?' . $params . '" frameborder="0"';
                    if ($width) {
                        $html .= ' width="' . $width . '"';
                    } else {
                        $html .= ' width="620"';
                    }
                    if ($height) {
                        $html .= ' height="' . $height . '"';
                    } else {
                        $html .= ' height="400"';
                    }
                    $html .= '></iframe>';

                    return $html;
                } elseif ($matches[3] == 'vimeo') {
                    preg_match('/^(https?:\/\/)?(www\.)?vimeo\.com\/([^\/]+)/i', $url, $matches);
                    $match = $matches[3];

                    $query = array();
                    if ($autoplay == 'true') {
                        $query[] = "autoplay=1";
                    }
                    if ($minimalbranding == 'true') {
                        $query[] = "title=0";
                        $query[] = "byline=0";
                        $query[] = "portrait=0";
                    }
                    $params = implode("&amp;", $query);


                    $html = '<iframe src="http://player.vimeo.com/video/' . $match . '?' . $params . '" frameborder="0"';
                    if ($width) {
                        $html .= ' width="' . $width . '"';
                    } else {
                        $html .= ' width="620"';
                    }
                    if ($height) {
                        $html .= ' height="' . $height . '"';
                    } else {
                        $html .= ' height="400"';
                    }
                    $html .= '></iframe>';

                    return $html;
                } elseif ($matches[3] == 'dailymotion') {
                    preg_match('/^(https?:\/\/)?(www\.)?dailymotion\.com\/(video\/)?([^_]+)/i', $url, $matches);
                    $match = $matches[4];

                    $query = array();
                    if ($autoplay == 'true') {
                        $query[] = "autoplay=1";
                    }
                    if ($minimalbranding == 'true') {
                        $query[] = "hideInfos=0";
                        $query[] = "chromeless=1";
                        $query[] = "logo=0";
                        $query[] = "info=0";
                        $query[] = "related=0";
                    }
                    $params = implode("&amp;", $query);

                    $html .= '<iframe src="http://www.dailymotion.com/embed/video/' . $match . '?' . $params . '" frameborder="0"';
                    if ($width) {
                        $html .= ' width="' . $width . '"';
                    } else {
                        $html .= ' width="620"';
                    }
                    if ($height) {
                        $html .= ' height="' . $height . '"';
                    } else {
                        $html .= ' height="400"';
                    }
                    $html .= '></iframe>';

                    return $html;
                } elseif ($matches[3] == 'screenr') {
                    preg_match('/^(https?:\/\/)?(www\.)?screenr\.com\/([^\/]+)/i', $url, $matches);
                    $match = $matches[3];

                    $html .= '<iframe src="http://www.screenr.com/embed/' . $match . '" frameborder="0"';
                    if ($width) {
                        $html .= ' width="' . $width . '"';
                    } else {
                        $html .= ' width="620"';
                    }
                    if ($height) {
                        $html .= ' height="' . $height . '"';
                    } else {
                        $html .= ' height="400"';
                    }
                    $html .= '></iframe>';

                    return $html;
                } else {
                    return '<br /><h2 style="text-align:center;">Unknown type of the video. Check your video link.</h2><br />';
                }

                // End checks for type		
            }

            break;
    }
}

function pbuilder_sidebar($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'name' => '1',
        'bot_margin' => 0,
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $html = '<div id="' . str_replace(" ", "_", $name) . '" class="frb_sidebar">';
    ob_start();
    if (!function_exists('dynamic_sidebar') || !dynamic_sidebar($name)) {
        
    }
    $html .= ob_get_contents();
    ob_end_clean();
    $randomId = $shortcode_id == '' ? 'frb_sidebar_' . rand() : $shortcode_id;
    $html .= '</div>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $html = $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_sidebar', 'pbuilder_sidebar');

function pbuilder_nav_menu($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'wp_menu' => '',
        'type' => 'horizontal-clean',
        'menu_title' => 'Nav menu',
        'bot_margin' => 24,
        'text_color' => '#232323',
        'hover_color' => '#27a8e1',
        'hover_text_color' => '#ffffff',
        'back_color' => '',
        'sub_back_color' => '#f4f4f4',
        'sub_text_color' => '#232323',
        'separator_color' => '#ebecee',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    if ($wp_menu == '')
        return '<span style="padding:12px">No Menu Assigned - Please create one in admin and assign to this spot</span>';

    $randomId = rand();
    $navArgs = array(
        'menu' => $wp_menu,
        'container' => '',
        'container_class' => '',
        'container_id' => '',
        'menu_class' => 'frb_menu frb_menu_' . $type,
        'menu_id' => '',
        'echo' => false,
        'fallback_cb' => 'wp_page_menu',
        'before' => '',
        'after' => '',
        'link_before' => '',
        'link_after' => '',
        'items_wrap' => '<ul id="%1$s" class="%2$s" data-textcolor="' . $text_color . '" data-hovercolor="' . $hover_color . '" data-hovertextcolor="' . $hover_text_color . '"  data-subtextcolor="' . $sub_text_color . '">%3$s</ul>',
        'depth' => 0,
        'walker' => ''
    );


    $html = '
	<style type="text/css" scoped="scoped">
		#frb_menu' . $randomId . ' {
			background:' . $back_color . ';
		}
		#frb_menu' . $randomId . ' ul.frb_menu a {
			color:' . $text_color . ';
		}
		#frb_menu' . $randomId . ' ul.frb_menu ul.sub-menu a {
			color:' . $sub_text_color . ';
		}
		#frb_menu' . $randomId . ' .frb_menu_header {
			color:' . $text_color . ';
			border-bottom:1px solid ' . $hover_color . ';
		}
		#frb_menu' . $randomId . ' ul.frb_menu ul.sub-menu li {
			background:' . $sub_back_color . ';
		}
		
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_horizontal-clean ul.sub-menu:before {
			border-bottom-color:' . $hover_color . ';
		}
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_horizontal-clean ul.sub-menu:after {
			border-bottom-color:' . $sub_back_color . ';
		}
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_horizontal-clean ul.sub-menu a {
			border-top:1px solid ' . $separator_color . ';
		}
		
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_horizontal-clean ul.sub-menu li:first-child a {
			border-top:1px solid ' . $hover_color . ';
		}
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_horizontal-squared ul.sub-menu:before,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_horizontal-squared ul.sub-menu:after,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_horizontal-rounded ul.sub-menu:before,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_horizontal-rounded ul.sub-menu:after {
			border-bottom-color:' . $sub_back_color . ';
		}
		
		#frb_menu' . $randomId . '.frb_menu_container_vertical-clean .frb_menu_header,
		#frb_menu' . $randomId . '.frb_menu_container_vertical-squared .frb_menu_header,
		#frb_menu' . $randomId . '.frb_menu_container_vertical-rounded .frb_menu_header{
			color:' . $text_color . ';
		}
		#frb_menu' . $randomId . '.frb_menu_container_vertical-clean ul.frb_menu,
		#frb_menu' . $randomId . '.frb_menu_container_vertical-squared ul.frb_menu,
		#frb_menu' . $randomId . '.frb_menu_container_vertical-rounded ul.frb_menu {
			background:' . $sub_back_color . ';
		}
		#frb_menu' . $randomId . '.frb_menu_container_vertical-clean ul.frb_menu a,
		#frb_menu' . $randomId . '.frb_menu_container_vertical-squared ul.frb_menu a,
		#frb_menu' . $randomId . '.frb_menu_container_vertical-rounded ul.frb_menu a{
        	color:' . $text_color . ';
        }
		#frb_menu' . $randomId . '.frb_menu_container_vertical-clean ul.frb_menu ul.sub-menu a,
		#frb_menu' . $randomId . '.frb_menu_container_vertical-squared ul.frb_menu ul.sub-menu a,
		#frb_menu' . $randomId . '.frb_menu_container_vertical-rounded ul.frb_menu ul.sub-menu a  {
			color:' . $sub_text_color . ';
		}
		
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-clean a,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-squared a,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-rounded a,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-clean > li > ul.sub-menu,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-squared > li > ul.sub-menu,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-rounded > li > ul.sub-menu {
			border-top:1px solid ' . $separator_color . ';
		}
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-clean li:first-child a,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-squared li:first-child a,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-rounded li:first-child a,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-clean ul.sub-menu a,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-squared ul.sub-menu a,
		#frb_menu' . $randomId . ' ul.frb_menu.frb_menu_vertical-rounded ul.sub-menu a {
			border-top:0;
		}
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-clean .frb_menu_header,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-squared .frb_menu_header,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-rounded .frb_menu_header {
			color:' . $text_color . ';
			border:0;
		}
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-clean .frb_menu_header:before,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-squared .frb_menu_header:before,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-rounded .frb_menu_header:before {
			background:' . $text_color . ';
		}
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-clean .frb_menu_header:after,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-squared .frb_menu_header:after,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-rounded .frb_menu_header:after {
			border-top-color:' . $text_color . ';
		}
		
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-clean ul.frb_menu:before {
			border-bottom-color:' . $hover_color . ';
		}
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-clean ul.frb_menu:after {
			border-bottom-color:' . $sub_back_color . ';
		}
		
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-squared ul.frb_menu:before,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-squared ul.frb_menu:after,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-rounded ul.frb_menu:before,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-rounded ul.frb_menu:after {
			border-bottom-color:' . $sub_back_color . ';
			
		}
		
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-clean ul.frb_menu li,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-squared ul.frb_menu li,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-rounded ul.frb_menu li {
			background:' . $sub_back_color . ';
		}
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-clean ul.frb_menu li a {
			color:' . $sub_text_color . ';
			border-top:1px solid ' . $separator_color . ';
		}
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-squared ul.frb_menu li a,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-rounded ul.frb_menu li a {
			color:' . $sub_text_color . ';
		}
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-squared ul.frb_menu li a,
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-rounded ul.frb_menu li a {
			color:' . $sub_text_color . ';
		}
		#frb_menu' . $randomId . '.frb_menu_container_dropdown-clean ul.frb_menu li:first-child a {
			border-top:1px solid ' . $hover_color . ';
		}
		
		
	</style>
	';
    $html .= '<div class="frb_menu_container frb_menu_container_' . $type . '" id="frb_menu' . $randomId . '">';
    if ($type != 'horizontal-clean' && $type != 'horizontal-squared' && $type != 'horizontal-rounded' && $menu_title != '') {
        $html .= '<div class="frb_menu_header">' . $menu_title . '</div>';
    }

    $html .= wp_nav_menu($navArgs) . '</div>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#frb_menu' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#frb_menu' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $html = $animSpeedSet . '<div id="#frb_menu' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';

    return $html;
}

add_shortcode('pbuilder_nav_menu', 'pbuilder_nav_menu');

//			pbuilder post

function pbuilder_post($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'id' => '1',
        'hover_icon' => 'ba-search',
        'button_text' => 'Read more',
        'style' => 'clean',
        'bot_margin' => 24,
        'back_color' => '',
        'border_color' => '#27a8e1',
        'button_color' => '#27a8e1',
        'button_text_color' => '#ffffff',
        'button_hover_color' => '#57bce8',
        'button_text_hover_color' => '#ffffff',
        'head_color' => '#232323',
        'meta_color' => '#232323',
        'meta_hover_color' => '#27a8e1',
        'text_color' => '#232323',
        'excerpt_lenght' => 150,
        'link_type' => 'post',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $color_array = array(
        'back_color',
        'border_color',
        'button_color',
        'button_text_color',
        'button_hover_color',
        'button_text_hover_color',
        'head_color',
        'meta_color',
        'meta_hover_color',
        'text_color'
    );
    foreach ($color_array as $color) {
        if ($$color == '')
            $$color = 'transparent';
    }
    global $pbuilder;
    $randomId = rand();
    $link_type = ($link_type == 'prettyphoto') ? 'prettyphoto' : 'post';
    $id = (int) $id;
    $post = get_post($id);
    $thumb = get_the_post_thumbnail($id, 'full');
    $url = $link_type == 'post' ? get_permalink($id) : wp_get_attachment_url(get_post_thumbnail_id($id));
    $author_id = $post->post_author;
    $comments = $post->comment_count;


    $content = $pbuilder->strip_html_tags($post->post_content);

    $content = strlen($content) >= $excerpt_lenght ? substr($content, 0, $excerpt_lenght - strlen($content)) . '...' : $content;

    $content = do_shortcode($content);

    if ($comments == 1)
        $comments .= ' comment';
    else
        $comments .= ' comments';

    $html = '<span class="frb_image_inner">' . $thumb . '<span class="frb_image_hover"></span>';

    if (substr($hover_icon, 0, 4) == 'icon') {
        $html .= '<i class="fawesome ' . $hover_icon . '"></i>';
    } else {
        $html .= '<i class="frb_icon ' . substr($hover_icon, 0, 2) . ' ' . $hover_icon . '"></i>';
    }
    $html .= '</span>';

    $html = '<a class="lightbox-image" href="' . $url . '" ' . ($link_type == 'prettyphoto' ? 'rel="frbprettyphoto"' : '') . '>' . $html . '</a>';

    $html .= '<a class="frb_post_title" href="' . get_permalink($id) . '"><h3>' . $post->post_title . '</h3></a>';

    $html .= '<div class="frb_post_meta"><span class="frb_date">' . get_the_time('m. d. Y.', $id) . '</span> | ' . '<a class="frb_author" href="mailto:' . get_the_author_meta('user_email', $author_id) . '">' . get_the_author_meta('user_nicename', $author_id) . '</a> | ' . '<a href="' . $url . '">' . $comments . '</a></div>';

    $html .= '<div class="frb_post_content">' . $content . '</div>';

    $button_style = 'style="' .
            'color:' . $button_text_color . '; ' .
            'background:' . $button_color . '; ' .
            'border-color:' . $button_color . '" ' .
            'data-textcolor="' . $button_text_color . '" ' .
            'data-backcolor="' . $button_color . '" ' .
            'data-hovertextcolor="' . $button_text_hover_color . '" ' .
            'data-hoverbackcolor="' . $button_hover_color . '"';

    $round = ($style == 'rounded' ? ' frb_round' : '');

    $html .= '<a class="frb_button' . $round . '" href="' . $url . '" ' . $button_style . '>' . $button_text . '</a>';


    $html = '<div id="frb_post' . $randomId . '" class="frb_post frb_post_' . $style . ' frb_image' . ($border_color != 'transparent' ? ' frb_image_border' : '') . '">' . $html . '</div>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#frb_post' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#frb_post' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $post_style = '
	<style type="text/css" scoped="scoped">
		#frb_post' . $randomId . ' {
			color: ' . $text_color . ';
			border-color:' . $border_color . '; 
			' . ($style != 'clean' ? 'background:' . $back_color : '') . '
		}
		
		#frb_post' . $randomId . ' h3 {
			color:' . $head_color . ';
		}
		
		#frb_post' . $randomId . ' .frb_post_meta,
		#frb_post' . $randomId . ' .frb_post_meta a{
			color: ' . $meta_color . ';
		}
		#frb_post' . $randomId . ' .frb_post_meta a:hover {
			color: ' . $meta_hover_color . '
		}
	</style>';

    $html = $animSpeedSet . $post_style . '<div id="#frb_post' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';


    return $html;
}

add_shortcode('pbuilder_post', 'pbuilder_post');



/* ------------------ */
/* pbuilder_recent_post */
/* ------------------ */

function pbuilder_recent_post($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'offset' => '0',
        'hover_icon' => 'ba-search',
        'button_text' => 'Read more',
        'style' => 'clean',
        'bot_margin' => 24,
        'back_color' => '',
        'border_color' => '#27a8e1',
        'button_color' => '#27a8e1',
        'button_text_color' => '#ffffff',
        'button_hover_color' => '#57bce8',
        'button_text_hover_color' => '#ffffff',
        'head_color' => '#232323',
        'meta_color' => '#232323',
        'meta_hover_color' => '#27a8e1',
        'text_color' => '#232323',
        'excerpt_lenght' => 150,
        'link_type' => 'post',
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $color_array = array(
        'back_color',
        'border_color',
        'button_color',
        'button_text_color',
        'button_hover_color',
        'button_text_hover_color',
        'head_color',
        'meta_color',
        'meta_hover_color',
        'text_color'
    );
    foreach ($color_array as $color) {
        if ($$color == '')
            $$color = 'transparent';
    }
    global $pbuilder;
    $randomId = rand();
    $link_type = ($link_type == 'prettyphoto') ? 'prettyphoto' : 'post';

    $offset = (int) $offset;

    $args = array(
        'numberposts' => 1,
        'orderby' => 'post_date',
        'offset' => $offset,
        'order' => 'DESC',
        'post_type' => 'post',
        'post_status' => 'publish'
    );

    $recent_post = wp_get_recent_posts($args, OBJECT);

    if (!$recent_post) {
        $thumb = '';
        $url = '#';
        $author_id = __('Sample author', 'profit-builder');
        $comments = 0;
        $content = __('Post with that offset isn\'t found', 'profit-builder');
    } else {
        $recent_post = $recent_post[0];
        $id = (int) ($recent_post->ID);
        $post = $recent_post;
        $thumb = get_the_post_thumbnail($id, 'full');
        $url = $link_type == 'post' ? get_permalink($id) : wp_get_attachment_url(get_post_thumbnail_id($id));
        $author_id = $post->post_author;
        $comments = $post->comment_count;

        $content = $pbuilder->strip_html_tags($post->post_content);
    }

    $content = strlen($content) >= $excerpt_lenght ? substr($content, 0, $excerpt_lenght - strlen($content)) . '...' : $content;
    $content = do_shortcode($content);

    if ($comments == 1)
        $comments .= ' comment';
    else
        $comments .= ' comments';

    $html = '<span class="frb_image_inner">' . $thumb . '<span class="frb_image_hover"></span>';

    if (substr($hover_icon, 0, 4) == 'icon') {
        $html .= '<i class="fawesome ' . $hover_icon . '"></i>';
    } else {
        $html .= '<i class="frb_icon ' . substr($hover_icon, 0, 2) . ' ' . $hover_icon . '"></i>';
    }
    $html .= '</span>';

    $html = '<a class="lightbox-image" href="' . $url . '" ' . ($link_type == 'prettyphoto' ? 'rel="frbprettyphoto"' : '') . '>' . $html . '</a>';

    if (!$recent_post) {
        $html .= '<a class="frb_post_title" href="#"><h3>' . __('No such post', 'profit-builder') . '</h3></a>';
        $html .= '<div class="frb_post_meta"><span class="frb_date">' . current_time('m. d. Y.') . '</span> | ' . '<a class="frb_author" href="mailto:#">' . __('Sample author', 'profit-builder') . '</a> | ' . '<a href="' . $url . '">' . $comments . '</a></div>';
    } else {
        $html .= '<a class="frb_post_title" href="' . get_permalink($id) . '"><h3>' . $post->post_title . '</h3></a>';
        $html .= '<div class="frb_post_meta"><span class="frb_date">' . get_the_time('m. d. Y.', $id) . '</span> | ' . '<a class="frb_author" href="mailto:' . get_the_author_meta('user_email', $author_id) . '">' . get_the_author_meta('user_nicename', $author_id) . '</a> | ' . '<a href="' . $url . '">' . $comments . '</a></div>';
    }

    $html .= '<div class="frb_post_content">' . $content . '</div>';

    $button_style = 'style="' .
            'color:' . $button_text_color . '; ' .
            'background:' . $button_color . '; ' .
            'border-color:' . $button_color . '" ' .
            'data-textcolor="' . $button_text_color . '" ' .
            'data-backcolor="' . $button_color . '" ' .
            'data-hovertextcolor="' . $button_text_hover_color . '" ' .
            'data-hoverbackcolor="' . $button_hover_color . '"';

    $round = ($style == 'rounded' ? ' frb_round' : '');

    $html .= '<a class="frb_button' . $round . '" href="' . $url . '" ' . $button_style . '>' . $button_text . '</a>';


    $html = '<div class="frb_post frb_post_' . $style . ' frb_image' . ($border_color != 'transparent' ? ' frb_image_border' : '') . '">' . $html . '</div>';

    $bot_margin = (int) $bot_margin;
    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#frb_post' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#frb_post' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';


    $post_style = '
	<style type="text/css" scoped="scoped">
		#frb_post' . $randomId . ' {
			color: ' . $text_color . ';
			border-color:' . $border_color . '; 
			' . ($style != 'clean' ? 'background:' . $back_color : '') . '
		}
		
		#frb_post' . $randomId . ' h3 {
			color:' . $head_color . ';
		}
		
		#frb_post' . $randomId . ' .frb_post_meta,
		#frb_post' . $randomId . ' .frb_post_meta a{
			color: ' . $meta_color . ';
		}
		#frb_post' . $randomId . ' .frb_post_meta a:hover {
			color: ' . $meta_hover_color . '
		}
	</style>';

    $html = $animSpeedSet . $post_style . '<div id="frb_post' . $randomId . '" class="' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;">' . $html . '</div>';


    return $html;
}

add_shortcode('pbuilder_recent_post', 'pbuilder_recent_post');

function pbuilder_pricing($atts, $content = null) {
    $atts = pbuilder_decode($atts);
    $content = pbuilder_decode($content);

    extract(shortcode_atts(array(
        'bot_margin' => '24',
        'currency' => '',
        'bot_border' => '',
        'class' => '',
        'shortcode_id' => '',
        'colnum' => '1',
        'services_sidebar' => 'true',
        'row_type' => '',
        'service_label' => '',
        'service_icon' => '',
        'column_1_icon' => '',
        'column_1_text' => '',
        'column_1_price' => '',
        'column_1_interval' => '',
        'column_1_button_text' => '',
        'column_1_button_link' => '',
        'column_2_icon' => '',
        'column_2_text' => '',
        'column_2_price' => '',
        'column_2_interval' => '',
        'column_2_button_text' => '',
        'column_2_button_link' => '',
        'column_3_icon' => '',
        'column_3_text' => '',
        'column_3_price' => '',
        'column_3_interval' => '',
        'column_3_button_text' => '',
        'column_3_button_link' => '',
        'column_4_icon' => '',
        'column_4_text' => '',
        'column_4_price' => '',
        'column_4_interval' => '',
        'column_4_button_text' => '',
        'column_4_button_link' => '',
        'column_5_icon' => '',
        'column_5_text' => '',
        'column_5_price' => '',
        'column_5_interval' => '',
        'column_5_button_text' => '',
        'column_5_button_link' => '',
        'text_color' => '',
        'back_color' => '',
        'column_1_main_color' => '',
        'column_1_hover_color' => '',
        'column_1_button_text_color' => '',
        'column_2_main_color' => '',
        'column_2_hover_color' => '',
        'column_2_button_text_color' => '',
        'column_3_main_color' => '',
        'column_3_hover_color' => '',
        'column_3_button_text_color' => '',
        'column_4_main_color' => '',
        'column_4_hover_color' => '',
        'column_4_button_text_color' => '',
        'column_5_main_color' => '',
        'column_5_hover_color' => '',
        'column_5_button_text_color' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $colnum = (int) $colnum;
    if ($services_sidebar == 'true')
        $colnum++;
    $bot_border = explode('|', $bot_border);
    $row_type = explode('|', $row_type);
    $service_label = explode('|', $service_label);
    $service_icon = explode('|', $service_icon);
    $column_1_icon = explode('|', $column_1_icon);
    $column_1_text = explode('|', $column_1_text);
    $column_1_price = explode('|', $column_1_price);
    $column_1_interval = explode('|', $column_1_interval);
    $column_1_button_text = explode('|', $column_1_button_text);
    $column_1_button_link = explode('|', $column_1_button_link);
    $column_2_icon = explode('|', $column_2_icon);
    $column_2_text = explode('|', $column_2_text);
    $column_2_price = explode('|', $column_2_price);
    $column_2_interval = explode('|', $column_2_interval);
    $column_2_button_text = explode('|', $column_2_button_text);
    $column_2_button_link = explode('|', $column_2_button_link);
    $column_3_icon = explode('|', $column_3_icon);
    $column_3_text = explode('|', $column_3_text);
    $column_3_price = explode('|', $column_3_price);
    $column_3_interval = explode('|', $column_3_interval);
    $column_3_button_text = explode('|', $column_3_button_text);
    $column_3_button_link = explode('|', $column_3_button_link);
    $column_4_icon = explode('|', $column_4_icon);
    $column_4_text = explode('|', $column_4_text);
    $column_4_price = explode('|', $column_4_price);
    $column_4_interval = explode('|', $column_4_interval);
    $column_4_button_text = explode('|', $column_4_button_text);
    $column_4_button_link = explode('|', $column_4_button_link);
    $column_5_icon = explode('|', $column_5_icon);
    $column_5_text = explode('|', $column_5_text);
    $column_5_price = explode('|', $column_5_price);
    $column_5_interval = explode('|', $column_5_interval);
    $column_5_button_text = explode('|', $column_5_button_text);
    $column_5_button_link = explode('|', $column_5_button_link);

    $randomId = rand();

    $colIdMod = ($services_sidebar == 'true' ? 1 : 0);

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style>' .
            '#frb_pricing_anim_' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#frb_pricing_anim_' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $html = $animSpeedSet . '
	<style>
		' . ($text_color != '' ? 'table#frb_pricing_' . $randomId . '.frb_pricing_table {color: ' . $text_color . ';}' : '') . '
		#frb_pricing_' . $randomId . '.frb_pricing_table td {border-right-color:#ffffff;} /* 	cell spacing color				*/
		' . ($back_color != '' ? '#frb_pricing_' . $randomId . ' .frb_pricing_pale_background {background-color: ' . $back_color . ';}' : '') . '
		#frb_pricing_' . $randomId . '.frb_pricing_table .frb_pricing_row_separator td {background: #dedee0;}
		' . ($services_sidebar == 'true' ? '' : '#frb_pricing_' . $randomId . '.frb_pricing_table .frb_pricing_section_responsive {display:none !important;}') . '
		' . (($colnum == 1 && $services_sidebar != 'true') || ($colnum == 2 && $services_sidebar == 'true') ? '#frb_pricing_controls_' . $randomId . '.frb_pricing_controls {display:none !important;}' : '') . '
		';

    for ($i = 1; $i <= ($colnum - $colIdMod); $i++) {
        $main_color = 'column_' . $i . '_main_color';
        $hover_color = 'column_' . $i . '_hover_color';
        $button_text_color = 'column_' . $i . '_button_text_color';

        $html .= '
		#frb_pricing_' . $randomId . ' .frb_pricing_main_color' . ($i + $colIdMod) . ' {background: ' . $$main_color . '; color: ' . $$button_text_color . '; transition:background-color 300ms;}
		#frb_pricing_' . $randomId . ' .frb_pricing_main_color' . ($i + $colIdMod) . '-hover:hover {background: ' . $$hover_color . '; color: ' . $$button_text_color . '; transition:background-color 300ms;}
		';
    }
    $html .= '
	</style>';

    $html .= '
	<table cellspacing="0" class="frb_pricing_table frb_pricing_table_' . $colnum . 'col" id="frb_pricing_' . $randomId . '">';


    if (is_array($row_type)) {
        for ($i = 0; $i < count($row_type); $i++) {


            $html.= '
		<tr class="' . ($row_type[$i] == 'text-button' ? 'frb_pricing_row_text_button' : '') . ($row_type[$i] == 'heading' || $row_type[$i] == 'border' ? 'frb_pricing_row_no_padding' : '') . ($row_type[$i] == 'section' ? 'frb_pricing_row_section' : '') . ($row_type[$i] == 'heading' ? ' frb_pricing_row_heading' : '') . '">';

            for ($j = 0; $j < $colnum; $j++) {
                $ind = ($services_sidebar == 'true' ? $j : $j + 1);
                $var_names = array(
                    'icon' => 'column_' . $ind . '_icon',
                    'text' => 'column_' . $ind . '_text',
                    'price' => 'column_' . $ind . '_price',
                    'interval' => 'column_' . $ind . '_interval',
                    'button_text' => 'column_' . $ind . '_button_text',
                    'button_link' => 'column_' . $ind . '_button_link'
                );

                if ($j == 0 && $services_sidebar == 'true') {
                    $slabel_flag = ($row_type[$i] != 'heading' && $row_type[$i] != 'price' && $row_type[$i] != 'button' && $row_type[$i] != 'border');

                    $html .= '
			<td class="frb_pricing_column1 frb_pricing_column_label' . ($slabel_flag && $service_label[$i] != '' ? ' frb_pricing_pale_background' : '') . '">
				<div' . ($row_type[$i] == 'text-button' ? ' class="frb_pricing_large_font"' : '') . '>';
                    if ($slabel_flag && $service_label[$i] != '') {
                        if ($row_type[$i] == 'section') {
                            if (substr($service_icon[$i], 0, 4) == 'icon') {
                                $html .='<i class="' . $service_icon[$i] . ' fawesome frb_pricing_fawesome"></i> ';
                            } else {
                                $html .='<i class="' . $service_icon[$i] . ' ' . substr($service_icon[$i], 0, 2) . ' frb_icon frb_pricing_fawesome"></i> ';
                            }
                        }
                        $html .= $service_label[$i];
                    }
                    $html .= '</div>
			</td>';
                } else {
                    $html .= '	
			<td class="frb_pricing_column' . ($j + 1) . ' frb_pricing_pale_background">';
                    switch ($row_type[$i]) {
                        case 'heading' :
                            $textj = $$var_names['text'];
                            $html .= '
				<div class="frb_pricing_table_category_tag frb_pricing_main_color' . ($j + 1) . '">' . $textj[$i] . '</div>';
                            break;

                        case 'price' :
                            $pricej = $$var_names['price'];
                            $intervalj = $$var_names['interval'];
                            $html .= '
				<div class="frb_pricing_table_price" style="clear:both;"><div>' . $currency . '</div><span><div>' . $pricej[$i] . '</div><span>' . $intervalj[$i] . '</span></span></div>
					';
                            break;

                        case 'button' :
                            $button_textj = $$var_names['button_text'];
                            $button_linkj = $$var_names['button_link'];
                            $html .= '
				<a href="' . $button_linkj[$i] . '" class="frb_pricing_table_button frb_pricing_main_color' . ($j + 1) . ' frb_pricing_main_color' . ($j + 1) . '-hover">' . $button_textj[$i] . '</a>';
                            break;

                        case 'text-button' :
                            $textj = $$var_names['text'];
                            $button_textj = $$var_names['button_text'];
                            $button_linkj = $$var_names['button_link'];
                            $html .= '
				<div>' . $textj[$i] . '</div>
				<a href="' . $button_linkj[$i] . '" class="frb_pricing_table_button frb_pricing_main_color' . ($j + 1) . ' frb_pricing_main_color' . ($j + 1) . '-hover">' . $button_textj[$i] . '</a>';
                            break;

                        case 'border' :
                            $textj = $$var_names['text'];
                            $iconj = $$var_names['icon'];
                            $html .= '
				<div class="frb_pricing_main_color' . ($j + 1) . ' frb_pricing_colored_line"></div>';
                            break;

                        case 'service' :
                            $textj = $$var_names['text'];
                            $iconj = $$var_names['icon'];
                            $html .= '
				<div><strong class="frb_pricing_label_responsive">' . ($services_sidebar == 'true' ? $service_label[$i] : '') . '</strong>';

                            if ($iconj[$i] != '' && $iconj[$i] != 'no-icon') {
                                if (substr($iconj[$i], 0, 4) == 'icon') {
                                    $html .= '<i class="' . $iconj[$i] . ' fawesome frb_pricing_fawesome"></i>';
                                } else {
                                    $html .= '<i class="' . $iconj[$i] . ' ' . substr($iconj[$i], 0, 2) . ' frb_icon frb_pricing_fawesome"></i>';
                                }
                            } else {
                                $html .= $textj[$i];
                            }
                            $html .= '
				</div>';
                            break;

                        case 'section' :
                            if (substr($service_icon[$i], 0, 4) == 'icon') {
                                $html .= '
				<div class="frb_pricing_section_responsive"><i class="' . $service_icon[$i] . ' fawesome frb_pricing_fawesome"></i> ' . $service_label[$i] . '</div>';
                            } else {
                                $html .= '
				<div class="frb_pricing_section_responsive"><i class="' . $service_icon[$i] . ' ' . substr($service_icon[$i], 0, 2) . ' frb_icon frb_pricing_fawesome"></i> ' . $service_label[$i] . '</div>';
                            }
                            break;
                    }
                    $html .= '	
			</td>';
                }
            }


            $html .= '
		</tr>';

            if (isset($bot_border[$i]) && $bot_border[$i] == 'true') {
                $html .= '
				<tr class="frb_pricing_row_separator">';
                for ($j = 0; $j < $colnum; $j++) {
                    $html .= '
					<td class="frb_pricing_column' . $j . ' ' . ($j == 0 && $services_sidebar == 'true' ? 'frb_pricing_column_label' : '') . '"></td>';
                }
                $html .= '
				</tr>';
            }
        }
    }

    $html .= '	
		
	</table>';

    $bot_margin = (int) $bot_margin;



    if ($services_sidebar == 'true')
        $colnum--;
    $html = '<div data-colnum="' . $colnum . '" class="frb_pricing_container frb_pricing_container_' . $colnum . 'col' . $class . $animate . ' style="padding-bottom:' . $bot_margin . 'px !important;" id="frb_pricing_anim_' . $randomId . '"><div id="frb_pricing_controls_' . $randomId . '" class="frb_pricing_controls"><a href="#" class="frb_pricing_left"><i class="fa fa-chevron-left fawesome" ></i></a><a href="#" class="frb_pricing_right"><i class="fa fa-chevron-right fawesome" ></i></a></div>' . (do_shortcode($html)) . '</div>';
    return $html;
}

add_shortcode('pbuilder_pricing', 'pbuilder_pricing');

function pbuilder_gmap($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'address' => 'Disneyland Resort, Disneyland Drive, Anaheim, CA',
        'iframe_width' => '100%',
        'iframe_height' => '600',
        'fullwidth' => 'false',
        'bot_margin' => 24,
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => '',
                    ), $atts));

    $address = urlencode(do_shortcode($address));
    $addressurl = "https://maps.google.com/maps?q=$address&z=15&output=embed";


    $iframe_width = (int) $iframe_width;
    $iframe_height = (int) $iframe_height;

    $randomId = $shortcode_id == '' ? 'frb_iframe_' . rand() : $shortcode_id;
    $bot_margin = (int) $bot_margin;

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    if ($fullwidth == "true") {
        $iframe_width = "100%";
    } else {
        $iframe_width.="px";
    }
    $iframe_height.="px";
    
        $iframe = "$extrastyle  <iframe id='iframe" . $randomId . "' style='width:" . $iframe_width . ";height:" . $iframe_height . ";border:0px; " . $overflow . "' src='" . $addressurl . "' " . $scrolling . " seamless frameborder='0' class='googlemap'></iframe>";

        $html = $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . ' ' . $animate . ' style="margin-bottom:-8px !important;padding-bottom:' . $bot_margin . 'px !important;">' . $iframe . '</div>';
    

    return $html;
}

add_shortcode('pbuilder_gmap', 'pbuilder_gmap');

function pbuilder_progressbar($atts) {
    $atts = pbuilder_decode($atts);
    extract(shortcode_atts(array(
        'pbar_style' => 'meter',
        'pbar_color1' => '#a9a9ff',
        'pbar_color2' => '#0000d3',
        'pbar_animate' => 'true',
        'pbar_size' => '50%',
        'pbar_width' => '100px',
        'pbar_height' => '100px',
        'pbar_padding' => '10px',
        'pbar_height' => '40px',
        'pbar_width' => '96%',
        'pbar_transparent' => 'false',
        'pbar_bg' => '#555555',
        'bot_margin' => 24,
        'class' => '',
        'shortcode_id' => '',
        'animate' => 'none',
        'animation_delay' => 0,
        'animation_speed' => 1000,
        'animation_group' => ''
                    ), $atts));

    $randomId = $shortcode_id == '' ? 'frb_pbar_' . rand() : $shortcode_id;
    $bot_margin = (int) $bot_margin;

    if ($animate != 'none') {
        $animate = ' frb_animated frb_' . $animate . '"';

        if ($animation_delay != 0) {
            $animation_delay = (int) $animation_delay;
            $animate .= ' data-adelay="' . $animation_delay . '"';
        }
        if ($animation_group != '') {
            $animate .= ' data-agroup="' . $animation_group . '"';
        }
    } else
        $animate = '"';

    $animSpeedSet = '<style type="text/css" scoped="scoped">' .
            '#' . $randomId . '.frb_onScreen.frb_animated {-webkit-animation-duration: ' . (int) $animation_speed . 'ms; animation-duration: ' . (int) $animation_speed . 'ms;}' .
            '#' . $randomId . '.frb_onScreen.frb_hinge {-webkit-animation-duration: ' . ((int) $animation_speed * 2) . 'ms; animation-duration: ' . ((int) $animation_speed * 2) . 'ms;}' .
            '</style>';
    $animSpeedSet = (int) $animation_speed != 0 ? $animSpeedSet : '';

    $custom_color = '
        background-color:' . $pbar_color1 . ';
        background-image:-moz-linear-gradient(top,' . $pbar_color1 . ',' . $pbar_color2 . ');
        background-image:-webkit-gradient(linear,left top,left bottom,color-stop(0,' . $pbar_color1 . '),color-stop(1,' . $pbar_color2 . '));
        background-image:-webkit-linear-gradient(' . $pbar_color1 . ',' . $pbar_color2 . ');
    ';

    $progressbar = '
        <div class="meter ' . (($pbar_style == "meter" || $pbar_style == "custom") ? "" : $pbar_style) . ' ' . ($pbar_animate == "false" ? " nostripes " : "") . '" style="height:' . $pbar_height . ';width:' . $pbar_width . ';background:' . ($pbar_transparent == 'true' ? "transparent" : $pbar_bg) . ';padding:' . $pbar_padding . ';">
			<span style="width: ' . $pbar_size . ";" . ($pbar_style == "custom" ? $custom_color : "") . '"></span>
		</div>
    ';

    $jquery = '
        <script type="text/javascript">
            jQuery("#' . $randomId . ' .meter > span").each(function() {
                jQuery(this)
				    .data("origWidth", jQuery(this).width())
					.width(0)
					.animate({
					   width: jQuery(this).data("origWidth")
					}, 1200);
			});
        </script>
    ';

    $html = $animSpeedSet . '<div id="' . $randomId . '" class="' . $class . ' ' . $animate . ' style="margin-bottom:-8px !important;padding-bottom:' . $bot_margin . 'px !important;">' . $progressbar . '</div>' . $jquery;

    return $html;
}

add_shortcode('pbuilder_progressbar', 'pbuilder_progressbar');
