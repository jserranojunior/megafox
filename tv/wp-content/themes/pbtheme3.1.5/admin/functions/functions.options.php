<?php

/**
 * SMOF Modified / PBTheme
 *
 * @package WordPress
 * @subpackage  SMOF
 * @theme pbtheme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */
add_action('init', 'of_options');
if (!function_exists('of_options')) {

    function of_options() {
        global $pbtheme_data;
        $curr_fonts = array(
            'Arial' => 'Arial',
            'Verdana, Geneva' => 'Verdana, Geneva',
            'Trebuchet' => 'Trebuchet',
            'Georgia' => 'Georgia',
            'Times New Roman' => 'Times New Roman',
            'Tahoma, Geneva' => 'Tahoma, Geneva',
            'Palatino' => 'Palatino',
            'Helvetica' => 'Helvetica'
        );
        //Header Elements
        $header_top_elements = array(
            'disabled' => array(
                'login-link' => 'login-link',
                'language-bar' => 'language-bar',
                'menu' => 'menu',
                'network-icons' => 'network-icons',
                'tagline' => 'tagline',
                'tagline-alt' => 'tagline-alt',
                'woo-login-link' => 'woo-login-link',
                'woo-cart' => 'woo-cart'
            ),
            "enabled" => array(
                'placebo' => 'placebo'
            )
        );
        //Footer Elements
        $footer_elements = array(
            'disabled' => array(
                'login-link' => 'login-link',
                'menu' => 'menu',
                'network-icons' => 'network-icons',
                'tagline' => 'tagline',
                'tagline-alt' => 'tagline-alt',
                'to-the-top' => 'to-the-top'
            ),
            "enabled" => array(
                'placebo' => 'placebo'
            )
        );
        //Ready Backgrounds
        $fixed_breadcrumbes = array('none' => 'none');
        $breadcrumbs = array();
        $breadcrumbs = glob(get_template_directory() . '/images/breadcrumbs/*');
        $breadcrumbs = array_filter($breadcrumbs, 'is_file');
        $breadcrumbs = array_map('basename', $breadcrumbs);
        $breadcrumbs = array_combine($breadcrumbs, $breadcrumbs);
        $fixed_breadcrumbes = (!empty($breadcrumbs) ? $fixed_breadcrumbes + $breadcrumbs : $fixed_breadcrumbes );
        //Ready Contacts
        $pbtheme_ready_contacts = array('none' => 'none');
        $counter = 0;
        if (isset($pbtheme_data['contact'])) {
            $pbtheme_contacts = $pbtheme_data['contact'];
            foreach ($pbtheme_contacts as $contact) {
                $counter++;
                $pbtheme_ready_contacts = $pbtheme_ready_contacts + array($counter => $contact['name']);
            }
        }
        //Create Menus
        $menus = get_terms('nav_menu', array('hide_empty' => false));
        $pbtheme_ready_menus = array('none' => 'none');
        foreach ($menus as $menu) {
            $pbtheme_ready_menus[$menu->slug] = $menu->name;
        }
        /* ----------------------------------------------------------------------------------- */
        /* The Options Array */
        /* ----------------------------------------------------------------------------------- */
        global $of_options;
        $of_options = array();
        $of_options[] = array("name" => __('General Settings', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_general",
            "icon" => "imscadmin-admin-logo"
        );
        $of_options[] = array("name" => __('General Settings', 'pbtheme'),
            "desc" => "",
            "id" => "generalsettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Welcome to the Theme Options', 'pbtheme') . "</h3>
						" . __('Use this Theme Options page to setup your site. Save your option once you customize your PBTheme Theme.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Upload Logo', 'pbtheme'),
            "desc" => __('Upload your logo using the native media uploader, or define the URL directly.', 'pbtheme'),
            "id" => "logo",
            "std" => get_template_directory_uri() . '/images/logo.png',
            "type" => "media",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Upload Sticky Logo', 'pbtheme'),
            "desc" => __('Upload your sticky logo using the native media uploader, or define the URL directly.', 'pbtheme'),
            "id" => "logo_sticky",
            "std" => get_template_directory_uri() . '/images/logo_sticky.png',
            "type" => "media",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Logo Size', 'pbtheme'),
            "desc" => __('Set your logo size. Normal real height 40px (Retina images 80px in height), Bigger real height 60px (Retina images 120px in height), Biggest real height 80px (Retina images 160px in height)', 'pbtheme'),
            "id" => "logo_size",
            "std" => "normal",
            "type" => "select",
            "options" => array(
                "normal" => __('Normal', 'pbtheme'),
                "bigger" => __('Bigger', 'pbtheme'),
                "biggest" => __('Biggest', 'pbtheme')
            )
        );
        $of_options[] = array("name" => __('Upload Favicon', 'pbtheme'),
            "desc" => __('Upload your sites favorites icons. Please use .png transparent image files (128x128px).', 'pbtheme'),
            "id" => "favicon",
            "std" => get_template_directory_uri() . '/images/favicon.png',
            "type" => "media",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Upload Icon - iPad, iPhone', 'pbtheme'),
            "desc" => __('Upload your sites iPad and iPhone icons. Please use .png transparent image files (57x57px).', 'pbtheme'),
            "id" => "apple_ti57",
            "std" => get_template_directory_uri() . '/images/favicon.png',
            "type" => "media",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Upload Icon - iPad, iPhone', 'pbtheme'),
            "desc" => __('Upload your sites iPad and iPhone Retina icons. Please use .png transparent image files (72x72px).', 'pbtheme'),
            "id" => "apple_ti72",
            "std" => get_template_directory_uri() . '/images/favicon.png',
            "type" => "media",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Upload Icon - iPad, iPhone', 'pbtheme'),
            "desc" => __('Upload your sites iPad and iPhone icons. Please use .png transparent image files (114x114px).', 'pbtheme'),
            "id" => "apple_ti114",
            "std" => get_template_directory_uri() . '/images/favicon.png',
            "type" => "media",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Upload Icon - iPad, iPhone', 'pbtheme'),
            "desc" => __('Upload your sites iPad and iPhone Retina icons. Please use .png transparent image files (144x144px).', 'pbtheme'),
            "id" => "apple_ti144",
            "std" => get_template_directory_uri() . '/images/favicon.png',
            "type" => "media",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Header Settings', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_layout",
            "icon" => "imscadmin-admin-header"
        );
        $of_options[] = array("name" => __('Header Settings', 'pbtheme'),
            "desc" => "",
            "id" => "header-settings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Header Settings', 'pbtheme') . "</h3>
						" . __('Set your smart header. Setup current header layout and elements that will be included in the top header. Also you can set your widgetized areas before the header.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
		
		
		$of_options[] = array("name" => __('Disable Top Header', 'pbtheme'),
            "desc" => __('Hide the top header bar globally.', 'pbtheme'),
            "id" => "disable-top-header",
            "std" => 0,
            "type" => "switch"
        );
		
				
        $of_options[] = array("name" => __('Header Layout', 'pbtheme'),
            "desc" => __('Select header layout.', 'pbtheme'),
            "id" => "header_layout",
            "std" => "small-right",
            "type" => "select",
            "options" => array(
                "news-central" => 'news-central',
                "small-left" => "small-left",
                "small-right" => "small-right"
            ),
            "class" => "of-group-small"
        );
		
		
		
        $of_options[] = array("name" => __('Top Header Widgets', 'pbtheme'),
            "desc" => __('Select number of widget areas before header.', 'pbtheme'),
            "id" => "header-widgets-before",
            "std" => "none",
            "type" => "select",
            "options" => array(
                "none" => "none",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Top Header Left', 'pbtheme'),
            "desc" => __('Select top header left elements.', 'pbtheme'),
            "id" => "header-top-left",
            "std" => $header_top_elements,
            "type" => "sorter"
        );
        $of_options[] = array("name" => __('Top Header Right', 'pbtheme'),
            "desc" => __('Select top header right elements.', 'pbtheme'),
            "id" => "header-top-right",
            "std" => $header_top_elements,
            "type" => "sorter"
        );
        $of_options[] = array("name" => __('Top Header Menu', 'pbtheme'),
            "desc" => __('Select top header custom menu.', 'pbtheme'),
            "id" => "header_menu",
            "std" => "none",
            "type" => "select",
            "options" => $pbtheme_ready_menus,
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Top Header Networks', 'pbtheme'),
            "desc" => __('Select contact option for the header network icons.', 'pbtheme'),
            "id" => "header_networks",
            "std" => "none",
            "type" => "select",
            "options" => $pbtheme_ready_contacts,
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Header Tagline", "pbtheme"),
            "desc" => __("Enter header tagline text.", "pbtheme"),
            "id" => "header_tagline",
            "std" => 'PBTheme',
            "type" => "textarea",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Header Tagline Alt", "pbtheme"),
            "desc" => __("Enter header tagline alternative text.", "pbtheme"),
            "id" => "header_tagline_alt",
            "std" => 'WordPress',
            "type" => "textarea",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Breadcrumbs', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_layout",
            "icon" => "imscadmin-admin-breadcrumbs"
        );
        $of_options[] = array("name" => __('Breadcrumbs', 'pbtheme'),
            "desc" => "",
            "id" => "bloglayout",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Breadcrumbs', 'pbtheme') . "</h3>
						" . __('Setup basic breadcrumbs style.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Breadcrumbs Background', 'pbtheme'),
            "desc" => __('Select breadcrumbs background. You can add more backgrounds to your /wp-content/themes/pbtheme/images/breadcrumbs/ folder.', 'pbtheme'),
            "id" => "breadcrumbs-style",
            "std" => "abstract-white.jpg",
            "type" => "select",
            "options" => $fixed_breadcrumbes
        );
        $of_options[] = array("name" => __("Breadcrumbs Line", "pbtheme"),
            "desc" => __("Enter before breadcrumbs text line.", "pbtheme"),
            "id" => "breadcrumbs_line",
            "std" => '',
            "type" => "textarea"
        );
        $of_options[] = array("name" => __('Single Post and Blog', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_layout",
            "icon" => "imscadmin-admin-single"
        );
        $of_options[] = array("name" => __('Blog Settings', 'pbtheme'),
            "desc" => "",
            "id" => "bloglayout",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Blog Settings', 'pbtheme') . "</h3>
						" . __('Select default blog style.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Override Blog Layout', 'pbtheme'),
            "desc" => __('Override default blog layout.', 'pbtheme'),
            "id" => "blog_layout",
            "std" => "6",
            "type" => "select",
            "options" => array(
                "1" => "Blocks",
                "2" => "2 Columns",
                "3" => "3 Columns",
                "4" => "4 Columns",
                "5" => "5 Columns",
                "6" => "Fullwidth Feat Area",
                "7" => "Small Image"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Single Post Settings', 'pbtheme'),
            "desc" => "",
            "id" => "singlepost-settings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Single Post Settings', 'pbtheme') . "</h3>
						" . __('Setup your single posts.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Hide Featured Image', 'pbtheme'),
            "desc" => __('Hide featured images on Single Posts.', 'pbtheme'),
            "id" => "pbtheme_hide_featarea",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Hide Post Title', 'pbtheme'),
            "desc" => __('Hide post titles on Single Posts.', 'pbtheme'),
            "id" => "pbtheme_hide_title",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        /* $of_options[] = array( "name" 		=> __('Hide Post Footer', 'pbtheme'),
          "desc" 		=> __('Hide post footer on Single Posts.', 'pbtheme'),
          "id" 		=> "pbtheme_hide_footer",
          "std" 		=> 0,
          "type" 		=> "switch",
          "class" 	=> "of-group-small"
          ); */
        $of_options[] = array("name" => __('Hide Post Meta', 'pbtheme'),
            "desc" => __('Hide post information on Single Posts. This info is shown just bellow Featured Image.', 'pbtheme'),
            "id" => "pbtheme_hide_meta",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Hide Post Tags", "pbtheme"),
            "desc" => __("Hide post tags at the end of Single Posts.", "pbtheme"),
            "id" => "pbtheme_hide_tags",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Hide Post Share", "pbtheme"),
            "desc" => __("Hide post share bar at the end of Single Posts.", "pbtheme"),
            "id" => "pbtheme_hide_share",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Hide Author Information", "pbtheme"),
            "desc" => __("Hide author information on Single Posts.", "pbtheme"),
            "id" => "pbtheme_hide_author",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Hide Post Navigation", "pbtheme"),
            "desc" => __("Hide post navigation at the end of Single Posts.", "pbtheme"),
            "id" => "pbtheme_hide_navigation",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Footer Settings', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_layout",
            "icon" => "imscadmin-admin-footer"
        );
        $of_options[] = array("name" => __('Footer Settings', 'pbtheme'),
            "desc" => "",
            "id" => "footersettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Footer Settings', 'pbtheme') . "</h3>
						" . __('Choose footer columns. Set up the copyright text.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __("Disable Footer Widget Areas", "pbtheme"),
            "desc" => __("This option will disable footer widget areas.", "pbtheme"),
            "id" => "footer_widgets",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Footer Widget Areas', 'pbtheme'),
            "desc" => __('Select number of footer widget areas.', 'pbtheme'),
            "id" => "footer_sidebar",
            "std" => "4",
            "type" => "select",
            "options" => array(
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Footer Left', 'pbtheme'),
            "desc" => __('Select bottom footer left elements.', 'pbtheme'),
            "id" => "footer-top-left",
            "std" => $footer_elements,
            "type" => "sorter"
        );
        $of_options[] = array("name" => __('Footer Right', 'pbtheme'),
            "desc" => __('Select bottom footer right elements.', 'pbtheme'),
            "id" => "footer-top-right",
            "std" => $footer_elements,
            "type" => "sorter"
        );
        $of_options[] = array("name" => __('Footer Menu', 'pbtheme'),
            "desc" => __('Select top header custom menu.', 'pbtheme'),
            "id" => "footer_menu",
            "std" => "none",
            "type" => "select",
            "options" => $pbtheme_ready_menus,
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Footer Networks', 'pbtheme'),
            "desc" => __('Select contact option for the header network icons.', 'pbtheme'),
            "id" => "footer_networks",
            "std" => "none",
            "type" => "select",
            "options" => $pbtheme_ready_contacts,
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Footer Tagline", "pbtheme"),
            "desc" => __("Enter header tagline text.", "pbtheme"),
            "id" => "footer_tagline",
            "std" => 'PBTheme',
            "type" => "textarea",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Footer Tagline Alt", "pbtheme"),
            "desc" => __("Enter header tagline alternative text.", "pbtheme"),
            "id" => "footer_tagline_alt",
            "std" => 'WordPress',
            "type" => "textarea",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Footer Up Arrow", "pbtheme"),
            "desc" => __("Enter text or FontAwesome icon code for the Up Arrow.", "pbtheme"),
            "id" => "footer_up_text",
            "std" => '',
            "type" => "textarea",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Style Settings', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_general",
            "icon" => "imscadmin-admin-style"
        );
        $of_options[] = array("name" => __('Style Settings', 'pbtheme'),
            "desc" => "",
            "id" => "stylesettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Style Settings', 'pbtheme') . "</h3>
						" . __('Setup your PBTheme Style. Choose colors and fonts.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Select Body Font', 'pbtheme'),
            "desc" => __('Selected main font is used for text on pages and posts.', 'pbtheme'),
            "id" => "font",
            "std" => "Arial",
            "type" => "select",
            "options" => $curr_fonts,
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Use Google Font For Body', 'pbtheme'),
            "desc" => __('Override body font with a Google font from Google Fonts Directory.', 'pbtheme'),
            "id" => "font_ggl_on",
            "std" => 1,
            "type" => "switch",
            "folds" => 1,
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Select Body Google Font', 'pbtheme'),
            "desc" => __('Selected Google font will be used for body text.', 'pbtheme'),
            "id" => "font_ggl",
            "std" => array("face" => "Open Sans", "style" => "normal", "weight" => "400"),
            "type" => "typography",
            "fold" => 'font_ggl_on'
        );
        $of_options[] = array("name" => __('Select Header Font', 'pbtheme'),
            "desc" => __('Selected font used for H1,H2,H3,H4,H5,H6 tags.', 'pbtheme'),
            "id" => "font_header",
            "std" => "Arial",
            "type" => "select",
            "options" => $curr_fonts,
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Use Google Font For Headings', 'pbtheme'),
            "desc" => __('Override headings font with a Google font from Google Fonts Directory.', 'pbtheme'),
            "id" => "font_header_ggl_on",
            "std" => 1,
            "type" => "switch",
            "folds" => 1,
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Select Headings Google Font', 'pbtheme'),
            "desc" => __('Selected Google font will be used for H1,H2,H3,H4,H5,H6 tags.', 'pbtheme'),
            "id" => "font_header_ggl",
            "std" => array("face" => "Roboto", "style" => "normal", "weight" => "400"),
            "type" => "typography",
            "fold" => 'font_header_ggl_on'
        );
        $of_options[] = array("name" => __('Colors', 'pbtheme'),
            "desc" => "",
            "id" => "stylesettingscolors",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Color Settings', 'pbtheme') . "</h3>
						" . __('Setup the colors used with PBTheme Theme. Achieve any style which fits your style.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Theme Color', 'pbtheme'),
            "desc" => __('Select Theme Color. Default color code is #82b440.', 'pbtheme'),
            "id" => "theme_color",
            "std" => "#82b440",
            "type" => "color",
            "class" => "of-group-smaller"
        );
        $of_options[] = array("name" => __('Theme Color #1', 'pbtheme'),
            "desc" => __('Select Theme Color #1. This color is used for text, borders and elements. Default color code is #111111.', 'pbtheme'),
            "id" => "theme_color_dark",
            "std" => "#111111",
            "type" => "color",
            "class" => "of-group-smaller"
        );
        $of_options[] = array("name" => __('Theme Color #2', 'pbtheme'),
            "desc" => __('Select Theme Color #2. This color is used for all the backgrounds. Default color code is #ffffff.', 'pbtheme'),
            "id" => "theme_color_light",
            "std" => "#ffffff",
            "type" => "color",
            "class" => "of-group-smaller"
        );
        $of_options[] = array("name" => __('Pale Theme Color', 'pbtheme'),
            "desc" => __('Select Pale Theme Color. This color is used for pale elements. Default color code is #cccccc.', 'pbtheme'),
            "id" => "theme_color_palee",
            "std" => "#cccccc",
            "type" => "color",
            "class" => "of-group-smaller"
        );
        $of_options[] = array("name" => __('Text Color', 'pbtheme'),
            "desc" => __('Select Text Color. Default color code is #444444.', 'pbtheme'),
            "id" => "theme_color_textt",
            "std" => "#444444",
            "type" => "color",
            "class" => "of-group-smaller"
        );
		
		$of_options[] = array("name" => __('Top Header Color', 'pbtheme'),
            "desc" => __('Select the background color of the top header bar. Default color code is #ffffff.', 'pbtheme'),
            "id" => "theme_color_top_header",
            "std" => "#ffffff",
            "type" => "color",
            "class" => "of-group-smaller"
        );
		
		
		$of_options[] = array("name" => __('Header Color', 'pbtheme'),
            "desc" => __('Select the background color of the header bar. Default color code is #ffffff.', 'pbtheme'),
            "id" => "theme_color_header",
            "std" => "#ffffff",
            "type" => "color",
            "class" => "of-group-smaller"
        );
		
        $of_options[] = array("name" => __('Footer Text Color', 'pbtheme'),
            "desc" => __('Select footer text color. Default color code is #ffffff.', 'pbtheme'),
            "id" => "theme_color_footer_textt",
            "std" => "#ffffff",
            "type" => "color",
            "class" => "of-group-smaller"
        );
        $of_options[] = array("name" => __('Footer Background Color', 'pbtheme'),
            "desc" => __('Select footer background text color. Default color code is #222222.', 'pbtheme'),
            "id" => "theme_color_footer_bg",
            "std" => "#1b1b1b",
            "type" => "color",
            "class" => "of-group-smaller"
        );
        $of_options[] = array("name" => __('Additional Style Settings', 'pbtheme'),
            "desc" => "",
            "id" => "addstylesettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Additional Style Settings', 'pbtheme') . "</h3>
						" . __('Setup your additional pbtheme style.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __("Header Shadow", "pbtheme"),
            "desc" => __("Choose header style. With shadow of plain flat design.", "pbtheme"),
            "id" => "header_shadow",
            "std" => 1,
            "on" => __("Shadow", 'pbtheme'),
            "off" => __("Flat", 'pbtheme'),
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Header/Footer Borders", "pbtheme"),
            "desc" => __("Select pale or dark styled borders in header and footer.", "pbtheme"),
            "id" => "pbtheme_layout_color",
            "std" => 1,
            "on" => __("Pale", 'pbtheme'),
            "off" => __("Dark", 'pbtheme'),
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Sidebar and Widgetized areas', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_layout",
            "icon" => "imscadmin-admin-sidebar"
        );
        $of_options[] = array("name" => __('Sidebar Settings', 'pbtheme'),
            "desc" => "",
            "id" => "sidebarsettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Sidebar Settings', 'pbtheme') . "</h3>
						" . __('Setup default post/product archive sidebar. Create custom sidebars to use on pages.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Sidebar Width', 'pbtheme'),
            "desc" => __('Choose your sidebars width.', 'pbtheme'),
            "id" => "sidebar-size",
            "std" => "3",
            "type" => "select",
            "options" => array(
                "3" => "Third",
                "4" => "Fourth",
                "5" => "Fifth"
            )
        );
        $of_options[] = array("name" => __('Blog Archive Sidebar', 'pbtheme'),
            "desc" => __('Enable Blog Archive sidebar. This sidebar appears on Blog Archive pages.', 'pbtheme'),
            "id" => "sidebar-blog",
            "std" => 1,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Sidebar Position", "pbtheme"),
            "desc" => __("Use left or right sidebar.", "pbtheme"),
            "id" => "sidebar-position",
            "std" => 0,
            "on" => "Left",
            "off" => "Right",
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Single Posts Sidebar', 'pbtheme'),
            "desc" => __('Enable Single Posts sidebar. This sidebar appears on Single Posts.', 'pbtheme'),
            "id" => "sidebar-single",
            "std" => 1,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Sidebar Position", "pbtheme"),
            "desc" => __("Use left or right sidebar.", "pbtheme"),
            "id" => "sidebar-single-position",
            "std" => 0,
            "on" => "Left",
            "off" => "Right",
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Widgetized Areas', 'pbtheme'),
            "desc" => "",
            "id" => "sidebarsettingswidgets",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Widgetized Areas', 'pbtheme') . "</h3>
						" . __('Use special widgetized areas. Before and after blog archives and single posts.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Before Archive', 'pbtheme'),
            "desc" => __('Select number of widget areas before blog archives.', 'pbtheme'),
            "id" => "blog-widgets-before",
            "std" => "none",
            "type" => "select",
            "options" => array(
                "none" => "none",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('After Archive', 'pbtheme'),
            "desc" => __('Select number of widget areas after blog archives.', 'pbtheme'),
            "id" => "blog-widgets-after",
            "std" => "none",
            "type" => "select",
            "options" => array(
                "none" => "none",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Before Single Post', 'pbtheme'),
            "desc" => __('Select number of widget areas before single post.', 'pbtheme'),
            "id" => "single-widgets-before",
            "std" => "none",
            "type" => "select",
            "options" => array(
                "none" => "none",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('After Single Post', 'pbtheme'),
            "desc" => __('Select number of widget areas after single post.', 'pbtheme'),
            "id" => "single-widgets-after",
            "std" => "none",
            "type" => "select",
            "options" => array(
                "none" => "none",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Sidebar Manager', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_layout",
            "icon" => "imscadmin-admin-sidebar"
        );
        $of_options[] = array("name" => __('Sidebars', 'pbtheme'),
            "desc" => "",
            "id" => "sidebarsettingssidebars",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Sidebar Manager', 'pbtheme') . "</h3>
						" . __('Create new sidebars to use in your pages and posts. Create unlimited number of sidebars and use them in Profit Builder via Sidebar element.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Sidebars', 'pbtheme'),
            "desc" => __('Unlimited sidebars for your pages/posts.', 'pbtheme'),
            "id" => "sidebar",
            "std" => array(
                1 => array(
                    'order' => 1,
                    'title' => 'Your first Sidebar!'
                )
            ),
            "type" => "sidebar"
        );
        $of_options[] = array("name" => __('Contact Settings', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_general",
            "icon" => "imscadmin-admin-contact"
        );
        $of_options[] = array("name" => __('Contact Form Custom Message', 'pbtheme'),
            "desc" => "",
            "id" => "contactsettingsemail",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Contact Form Custom Message', 'pbtheme') . "</h3>" .
            __('Set your Contact Form custom message. This message will appear once the E-Mail is sent.', 'pbtheme'),
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Contact Form Custom Message', 'pbtheme'),
            "desc" => __('Enter custom HTML/Text.', 'pbtheme'),
            "id" => "contactform_message",
            "std" => "",
            "type" => "textarea"
        );
        $of_options[] = array("name" => __('Contact Settings', 'pbtheme'),
            "desc" => "",
            "id" => "contactsettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Contact Settings', 'pbtheme') . "</h3>" .
            __('Setup your team members.', 'pbtheme'),
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Contact Settings / Team Members', 'pbtheme'),
            "desc" => __('Add or remove contact options/team members. You can use this entries later in your content as Team element or Contact Form element.', 'pbtheme'),
            "id" => "contact",
            "std" => array(
                1 => array(
                    'order' => 1,
                    'name' => 'Your first Contact!',
                    'url' => get_template_directory_uri() . '/images/logo.png',
                    'job' => 'designer',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    'email' => 'google@gmail.com',
                    'contact' => array(
                        1 => array(
                            'socialnetworksurl' => '#',
                            'socialnetworks' => 'white_facebook.png'
                        )
                    )
                )
            ),
            "type" => "contact"
        );
        $of_options[] = array("name" => __('Custom CSS', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_general",
            "icon" => "imscadmin-admin-css"
        );
        $of_options[] = array("name" => __('Custom CSS', 'pbtheme'),
            "desc" => "",
            "id" => "csssettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Custom CSS', 'pbtheme') . "</h3>
						" . __('Write some custom CSS for your site.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Custom CSS', 'pbtheme'),
            "desc" => __('Quickly add some CSS to your theme by adding it to this block.', 'pbtheme'),
            "id" => "custom-css",
            "std" => "",
            "type" => "textarea"
        );
        $of_options[] = array("name" => __('bbPress Settings', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_bbpress",
            "icon" => "imscadmin-admin-bbpress"
        );
        $of_options[] = array("name" => __('bbPress Settings', 'pbtheme'),
            "desc" => "",
            "id" => "bbpresssettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('General - <span>bbPress</span>', 'pbtheme') . "</h3>
							" . __('Setup you bbPress Forums.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __("bbPress Forum Prefix", "pbtheme"),
            "desc" => __("Enter forum prefix to be used in the breadcrumbs.", "pbtheme"),
            "id" => "bbpress_forum",
            "std" => 'PBTheme Forum',
            "type" => "text",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('bbPress Settings', 'pbtheme'),
            "desc" => "",
            "id" => "bbpresssid",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Sidebars - <span>bbPress</span>', 'pbtheme') . "</h3>
							" . __('Setup you bbPress forum sidebars.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Sidebar Width', 'pbtheme'),
            "desc" => __('Choose your sidebars width.', 'pbtheme'),
            "id" => "sidebar-bbpress-size",
            "std" => "3",
            "type" => "select",
            "options" => array(
                "3" => "Third",
                "4" => "Fourth",
                "5" => "Fifth"
            )
        );
        $of_options[] = array("name" => __('bbPress Forum Sidebar', 'pbtheme'),
            "desc" => __('Enable Forum sidebar. This sidebar appears on bbPress Forum pages.', 'pbtheme'),
            "id" => "sidebar-bbpress",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Sidebar Position", "pbtheme"),
            "desc" => __("Use left or right sidebar.", "pbtheme"),
            "id" => "sidebar-bbpress-position",
            "std" => 0,
            "on" => "Left",
            "off" => "Right",
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('bbPress Widgetized Areas', 'pbtheme'),
            "desc" => "",
            "id" => "bbpresssid",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Widgetized Areas - <span>bbPress</span>', 'pbtheme') . "</h3>
							" . __('Setup you bbPress forum widgetized areas.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Before Forum', 'pbtheme'),
            "desc" => __('Select number of widget areas before forum.', 'pbtheme'),
            "id" => "bbpress-widgets-before",
            "std" => "none",
            "type" => "select",
            "options" => array(
                "none" => "none",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('After Forum', 'pbtheme'),
            "desc" => __('Select number of widget areas after forum products.', 'pbtheme'),
            "id" => "bbpress-widgets-after",
            "std" => "none",
            "type" => "select",
            "options" => array(
                "none" => "none",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('WooCommerce General', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_woo",
            "icon" => "imscadmin-admin-woo"
        );
        $of_options[] = array("name" => __('WooCommerce General', 'pbtheme'),
            "desc" => "",
            "id" => "woosettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('General - <span>WooCommerce</span>', 'pbtheme') . "</h3>
							" . __('Setup basic Woocommerce settings.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Products Per Page', 'pbtheme'),
            "desc" => __('Set number of products per page for the shop and product archives.', 'pbtheme'),
            "id" => "woo_per_page",
            "std" => "16",
            "min" => "1",
            "step" => "1",
            "max" => "50",
            "type" => "sliderui",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Woocommerce Columns', 'pbtheme'),
            "desc" => __('Default number of Woocommerce columns on Shop and Archive pages.', 'pbtheme'),
            "id" => "woo-columns",
            "std" => "4",
            "type" => "select",
            "options" => array(
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Woocommerce Related Columns', 'pbtheme'),
            "desc" => __('Default number of Woocommerce columns for related and upsells.', 'pbtheme'),
            "id" => "woo-columns-rel",
            "std" => "4",
            "type" => "select",
            "options" => array(
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
//new options start
        $of_options[] = array("name" => __('Products Title Height', 'pbtheme'),
            "id" => "woo_title_height",
            "desc" => __('This determines the height in pixels of the title container for products shown in featured areas such as related items and archive view', 'pbtheme'),
            "std" => "60",
            "min" => "1",
            "step" => "1",
            "max" => "100",
            "type" => "sliderui",
            "class" => "of-group-small"
        );
		
		
		$of_options[] = array("name" => __('Disable Add to Cart Icon', 'pbtheme'),
            "id" => "woo_disable_carticon",
            "desc" => __('Disable the add to cart icon on product thumbnails', 'pbtheme'),
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
		
		$of_options[] = array("name" => __('Force Product Thumbnail Image Flip', 'pbtheme'),
            "id" => "woo_force_imageflip",
            "desc" => __('Enables the product thumbnail image flip even if the product has only one image', 'pbtheme'),
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
		
		$of_options[] = array("name" => __('Hide products count for categories', 'pbtheme'),
            "id" => "woo_disable_catcount",
            "desc" => __('Disable the add to cart icon on product thumbnails', 'pbtheme'),
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
		
		
        $of_options[] = array("name" => __('Enable Facebook', 'pbtheme'),
            "id" => "woo_enable_fb",
            "desc" => __('Allow products to be shared to Facebook', 'pbtheme'),
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
		
        $of_options[] = array("name" => __('Enable Twitter', 'pbtheme'),
            "id" => "woo_enable_tw",
            "desc" => __('Allow products to be shared to Twitter', 'pbtheme'),
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Enable Pinterest', 'pbtheme'),
            "id" => "woo_enable_pin",
            "desc" => __('Allow products to be shared to Pinterest', 'pbtheme'),
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
		$of_options[] = array("name" => __('Enable Google Plus', 'pbtheme'),
            "id" => "woo_enable_gplus",
            "desc" => __('Allow products to be shared to Google Plus', 'pbtheme'),
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
		
     /*$of_options[] = array("name" => __('Enable Email', 'pbtheme'),
            "id" => "woo_enable_email",
            "desc" => __('Allow products to be shared by Email', 'pbtheme'),
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );*/
//new options end
        $of_options[] = array("name" => __('WooCommerce Sidebars', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_woo",
            "icon" => "imscadmin-admin-woo"
        );
        $of_options[] = array("name" => __('WooCommerce Sidebars', 'pbtheme'),
            "desc" => "",
            "id" => "woosid",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Sidebars - <span>WooCommerce</span>', 'pbtheme') . "</h3>
							" . __('Setup Woocommerce sidebar settings.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Woocommerce Sidebar Width', 'pbtheme'),
            "desc" => __('Choose your Woocommerce sidebars width.', 'pbtheme'),
            "id" => "sidebar-size-woo",
            "std" => "Third",
            "type" => "select",
            "options" => array(
                "3" => "Third",
                "4" => "Fourth",
                "5" => "Fifth"
            )
        );
        $of_options[] = array("name" => __('Woocommerce Archive Sidebar', 'pbtheme'),
            "desc" => __('Enable Woocommerce sidebar on Archive and Shop pages.', 'pbtheme'),
            "id" => "sidebar-woo",
            "std" => 1,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Woocommerce Archive Sidebar Position", "pbtheme"),
            "desc" => __("Use left or right sidebar.", "pbtheme"),
            "id" => "woo-sidebar-position",
            "std" => 1,
            "on" => "Left",
            "off" => "Right",
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Woocommerce Post Sidebar', 'pbtheme'),
            "desc" => __('Enable Woocommerce sidebar on Single Posts.', 'pbtheme'),
            "id" => "sidebar-woo-single",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Woocommerce Post Sidebar Position", "pbtheme"),
            "desc" => __("Use left or right sidebar.", "pbtheme"),
            "id" => "woo-sidebar-position-single",
            "std" => 1,
            "on" => "Left",
            "off" => "Right",
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('WooCommerce Widgetized Areas', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_woo",
            "icon" => "imscadmin-admin-woo"
        );
        $of_options[] = array("name" => __('WooCommerce Widgetized Areas', 'pbtheme'),
            "desc" => "",
            "id" => "woowid",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Widgetized Areas - <span>WooCommerce</span>', 'pbtheme') . "</h3>
							" . __('Setup Woocommerce widgetized areas before and after content.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Woocommerce Widgetized Areas', 'pbtheme'),
            "desc" => "",
            "id" => "woowidgets",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Widgetized Areas', 'pbtheme') . "</h3>
							" . __('Use special widgetized areas. Before and after shop archives and single products.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Before Shop Archive', 'pbtheme'),
            "desc" => __('Select number of widget areas before shop archives.', 'pbtheme'),
            "id" => "shop-widgets-before",
            "std" => "none",
            "type" => "select",
            "options" => array(
                "none" => "none",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('After Shop Archive', 'pbtheme'),
            "desc" => __('Select number of widget areas after shop archives.', 'pbtheme'),
            "id" => "shop-widgets-after",
            "std" => "none",
            "type" => "select",
            "options" => array(
                "none" => "none",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Before Product', 'pbtheme'),
            "desc" => __('Select number of widget areas before single products.', 'pbtheme'),
            "id" => "product-widgets-before",
            "std" => "none",
            "type" => "select",
            "options" => array(
                "none" => "none",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('After Product', 'pbtheme'),
            "desc" => __('Select number of widget areas after single products.', 'pbtheme'),
            "id" => "product-widgets-after",
            "std" => "none",
            "type" => "select",
            "options" => array(
                "none" => "none",
                "1" => "1",
                "2" => "2",
                "3" => "3",
                "4" => "4"
            ),
            "class" => "of-group-small"
        );
        /*
          if ( !get_transient('PBTheme_Demo_Remove') ) {
          $of_options[] = array( 	"name" 		=> __('Demo Installation', 'pbtheme'),
          "type" 		=> "heading",
          "group" 	=> "div_grp_demo",
          "icon" 		=> "imscadmin-admin-demo"
          );
          $of_options[] = array( 	"name" 		=> __('Demo Installation', 'pbtheme'),
          "desc" 		=> "",
          "id" 		=> "demoinstallation",
          "std" 		=> "<h3 style=\"margin: 0 0 10px;\">".__('Install PBTheme Demo - <span>Demo</span>', 'pbtheme')."</h3><h5><span class='red'>".__("IMPORTANT",'pbtheme')."</span></h5>
          ".__('Demo content can only be installed on a clean Wordpress installation. If you have any posts/pages do not install demo as it will overwrite all your posts and pages. Make sure that your Wordpress version is 3.8 or newer.', 'pbtheme'),
          "icon" 		=> true,
          "type" 		=> "info"
          );
          $of_options[] = array( 	"name" 		=> __('Step 1', 'pbtheme'),
          "desc" 		=> "",
          "id" 		=> "demo_plugins_h",
          "std" 		=> "<h3 style=\"margin: 0 0 10px;\">".__('Step 1 - Plugins', 'pbtheme')."</h3>
          ".__('Install and activate all the plugins used by PBTheme Theme.', 'pbtheme')."",
          "icon" 		=> true,
          "type" 		=> "info"
          );
          $of_options[] = array( 	"name" 		=> __('Installed Plugins', 'pbtheme'),
          "desc" 		=> __('This list shows needed plugins. Please install and activate all the plugins before you continue with the demo installation.', 'pbtheme'),
          "id" 		=> "demo_plugins",
          "std" 		=> "",
          "type" 		=> "demoplugins"
          );
          $of_options[] = array( 	"name" 		=> __('Step 2', 'pbtheme'),
          "desc" 		=> "",
          "id" 		=> "demo_images_h",
          "std" 		=> "<h3 style=\"margin: 0 0 10px;\">".__('Step 2 - Images', 'pbtheme')."</h3>
          ".__('Download images used in the demo content.', 'pbtheme')."",
          "icon" 		=> true,
          "type" 		=> "info"
          );
          $of_options[] = array( 	"name" 		=> __('Download Images', 'pbtheme'),
          "desc" 		=> __('If you cannot see all of the images on left then your installation has failed due to an error caused by max_execution_time of your server. Either contact your service provider or try uploading the file again and again until you can see all the images.', 'pbtheme'),
          "id" 		=> "demo_images",
          "std" 		=> "",
          "type" 		=> "demoimages"
          );
          $of_options[] = array( 	"name" 		=> __('Step 3', 'pbtheme'),
          "desc" 		=> "",
          "id" 		=> "demo_content_h",
          "std" 		=> "<h3 style=\"margin: 0 0 10px;\">".__('Step 3 - Demo Content', 'pbtheme')."</h3>
          ".__('One click demo installation. Please make sure you have completed the previous two steps!', 'pbtheme')."",
          "icon" 		=> true,
          "type" 		=> "info"
          );
          $of_options[] = array( 	"name" 		=> __('Demo Content', 'pbtheme'),
          "desc" 		=> __('Click the Install Demo Content button to make your site look just like our PBTheme Demo', 'pbtheme').' <a href="http://www.imsuccesscenter.com/demo/?item=PBTheme_Wordpress">'.__('LINK', 'pbtheme').'</a>. '.__('Please make sure you regenerate your thumbnails upon the demo installation.', 'pbtheme'),
          "id" 		=> "demo_content",
          "std" 		=> "",
          "type" 		=> "democontent"
          );
          $of_options[] = array( 	"name" 		=> __('Remove Demo', 'pbtheme'),
          "desc" 		=> "",
          "id" 		=> "demoinstallation",
          "std" 		=> "<h3 style=\"margin: 0 0 10px;\">".__('Notice', 'pbtheme')."</h3><h5><span class='red'>".__("IMPORTANT",'pbtheme')."</span></h5>
          ".__('If you do not want to use the Demo Content and you wish this option to be removed from the PBTheme Theme options panel click the Remove Demo Tab button.', 'pbtheme')."<br/><br/><a href='#' id='demo_remove' class='button-primary'>".__('Remove Demo Tab', 'pbtheme')."</a>",
          "icon" 		=> true,
          "type" 		=> "info"
          );
          }
         */
        $of_options[] = array("name" => __('General', 'pbtheme'),
            "id" => "div_grp_general",
            "type" => "group"
        );
        $of_options[] = array("name" => __('Layout', 'pbtheme'),
            "id" => "div_grp_layout",
            "type" => "group"
        );
        $of_options[] = array("name" => __('Advanced', 'pbtheme'),
            "id" => "div_grp_advanced",
            "type" => "group"
        );
        /* 				
          if ( !get_transient('PBTheme_Demo_Remove') ) {
          $of_options[] = array( 	"name" 		=> __('Demo', 'pbtheme'),
          "id"			=> "div_grp_demo",
          "type" 		=> "group"
          );
          }
         */
        if (DIVWP_WOOCOMMERCE === true) {
            $of_options[] = array("name" => __('WooCommerce', 'pbtheme'),
                "id" => "div_grp_woo",
                "type" => "group"
            );
        }
        if (DIVWP_BBPRESS === true) {
            $of_options[] = array("name" => __('bbPress', 'pbtheme'),
                "id" => "div_grp_bbpress",
                "type" => "group"
            );
        }
        $of_options[] = array("name" => __('Advanced General', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_advanced",
            "icon" => "imscadmin-admin-advanced"
        );
        $of_options[] = array("name" => __('Advanced General', 'pbtheme'),
            "desc" => "",
            "id" => "advgen",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Advanced General', 'pbtheme') . "</h3>
						" . __('Set custom CSS classes, Google Analytics code.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __("Responsive / Fixed Layout", "pbtheme"),
            "desc" => __("Enable Responsive layout or use Fixed layout insted.", "pbtheme"),
            "id" => "responsive",
            "std" => 1,
            "on" => "Responsive",
            "off" => "Fixed",
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Disable PBTheme Mega Menu", "pbtheme"),
            "desc" => __("Activate this option to disable PBTheme Mega Menu. This option can be usefull if you want to use plugins such as UberMenu and other mega menu plugins.", "pbtheme"),
            "id" => "disable_menu",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Enable Comments on Pages", "pbtheme"),
            "desc" => __("If you need comments on pages please enable this switch.", "pbtheme"),
            "id" => "enable_comments",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        
        $of_options[] = array("name" => __('Tracking Code', 'pbtheme'),
            "desc" => __('Paste your Google Analytics (or other) tracking code here. This will be added into the header of your site.', 'pbtheme'),
            "id" => "tracking-code",
            "std" => "",
            "type" => "textarea"
        );
        $of_options[] = array("name" => __('Advanced Layout', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_advanced",
            "icon" => "imscadmin-admin-advlay"
        );
        $of_options[] = array("name" => __('Advanced Layout', 'pbtheme'),
            "desc" => "",
            "id" => "advlaysettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Advanced Layout', 'pbtheme') . "</h3>
						" . __('Set your layout. Set up your default margins used in the PBTheme Theme.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __("Boxed / Wide Layout", "pbtheme"),
            "desc" => __("Enable Boxed layout or use Wide layout insted.", "pbtheme"),
            "id" => "boxed",
            "std" => 0,
            "on" => "Boxed",
            "off" => "Wide",
            "type" => "switch"
        );
        $of_options[] = array("name" => __('Advanced Layout', 'pbtheme'),
            "desc" => "",
            "id" => "layhigh",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Advanced Layout Settings - <span>High Resolution</span>', 'pbtheme') . "</h3>
						" . __('Set your advanced layout settings for high resolution devices.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Content Width / High Resolution', 'pbtheme'),
            "desc" => __('Responsive width for high resolutions. Default value 1200px.', 'pbtheme'),
            "id" => "content_width",
            "std" => "1200",
            "min" => "960",
            "step" => "1",
            "max" => "1200",
            "type" => "sliderui",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Column Margins', 'pbtheme'),
            "desc" => __('Column margins for high resolutions. Default value 36px.', 'pbtheme'),
            "id" => "fb_hres_c",
            "std" => "36",
            "min" => "1",
            "step" => "1",
            "max" => "60",
            "type" => "sliderui",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Default Bottom Margin', 'pbtheme'),
            "desc" => __('Set the default bottom margin on all elements. Default 36px.', 'pbtheme'),
            "id" => "fb_bmargin",
            "std" => "36",
            "min" => "1",
            "step" => "1",
            "max" => "100",
            "type" => "sliderui",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Advanced Layout', 'pbtheme'),
            "desc" => "",
            "id" => "laymed",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Advanced Layout Settings - <span>Medium Resolution</span>', 'pbtheme') . "</h3>
						" . __('Set your advanced layout settings for medium resolution devices.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Medium Resolution', 'pbtheme'),
            "desc" => __('Responsive width for medium resolutions. Default value 768px.', 'pbtheme'),
            "id" => "fb_mres_w",
            "std" => "768",
            "min" => "480",
            "step" => "1",
            "max" => "1200",
            "type" => "sliderui",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Column Margins', 'pbtheme'),
            "desc" => __('Column margins for medium resolutions. Default value 10px.', 'pbtheme'),
            "id" => "fb_mres_c",
            "std" => "18",
            "min" => "1",
            "step" => "1",
            "max" => "60",
            "type" => "sliderui",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Hide Sidebars", "pbtheme"),
            "desc" => __("Hide sidebars after current width.", "pbtheme"),
            "id" => "fb_mres_s",
            "std" => 0,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Advanced Layout', 'pbtheme'),
            "desc" => "",
            "id" => "laylow",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Advanced Layout Settings - <span>Low Resolution</span>', 'pbtheme') . "</h3>
						" . __('Set your advanced layout settings for low resolution devices.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Low Resolution', 'pbtheme'),
            "desc" => __('Responsive width for low resolutions. Default value 640px.', 'pbtheme'),
            "id" => "fb_lres_w",
            "std" => "640",
            "min" => "320",
            "step" => "1",
            "max" => "1200",
            "type" => "sliderui",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Column Margins', 'pbtheme'),
            "desc" => __('Column margins for low resolutions. Default value 5px.', 'pbtheme'),
            "id" => "fb_lres_c",
            "std" => "12",
            "min" => "1",
            "step" => "1",
            "max" => "60",
            "type" => "sliderui",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Hide Sidebars", "pbtheme"),
            "desc" => __("Hide sidebars after current width.", "pbtheme"),
            "id" => "fb_lres_s",
            "std" => 1,
            "type" => "switch",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Featured Images', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_advanced",
            "icon" => "imscadmin-admin-fimage"
        );
        $of_options[] = array("name" => __('Setup Featured Images', 'pbtheme'),
            "desc" => "",
            "id" => "advancedfimage",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Setup Featured Images', 'pbtheme') . "</h3>
						" . __('Set your default featured image resolution. These settings control the featured image size on fullwidth elements, fullwidth blog and single posts featured area. If you alter these default settings please regenerate your thumbnails.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Featured Image Width', 'pbtheme'),
            "desc" => __('Set fullwidth elements featured image height.', 'pbtheme'),
            "id" => "fimage_width",
            "std" => "960",
            "min" => "640",
            "step" => "1",
            "max" => "1200",
            "type" => "sliderui",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Featured Image Height', 'pbtheme'),
            "desc" => __('Set fullwidth elements featured image height.', 'pbtheme'),
            "id" => "fimage_height",
            "std" => "600",
            "min" => "400",
            "step" => "1",
            "max" => "1200",
            "type" => "sliderui",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Language Settings', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_advanced",
            "icon" => "imscadmin-admin-languages"
        );
        $of_options[] = array("name" => __('Language Settings', 'pbtheme'),
            "desc" => "",
            "id" => "languagesettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Languages Manager', 'pbtheme') . "</h3>
						" . __('Add languages and URLs to the translated version. You can use WPML, qTranslate or other tools to translate your site.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        require_once(ABSPATH . 'wp-admin/includes/admin.php');
        $path = 'transposh-translation-filter-for-wordpress/transposh.php';
        if (is_plugin_active(plugin_basename($path))) {
            $of_options[] = array("name" => __("Enable Transposh", "pbtheme"),
                "desc" => __("We detected the Transposh plugin, would you like to use the widget in place of the languages dropdown?", "pbtheme"),
                "id" => "transposh_enable",
                "std" => 0,
                "type" => "switch",
                "class" => "div_grp_advanced"
            );
        }
        $of_options[] = array("name" => __('Languages', 'pbtheme'),
            "desc" => __('Unlimited sidebars for your pages/posts.', 'pbtheme'),
            "id" => "language",
            "std" => array(
                1 => array(
                    'order' => 1,
                    'flag' => 'france.png',
                    'langurl' => '#'
                )
            ),
            "type" => "language"
        );
        $of_options[] = array("name" => __('Twitter Settings', 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_advanced",
            "icon" => "imscadmin-admin-twitter"
        );
        $of_options[] = array("name" => __('Twitter Settings', 'pbtheme'),
            "desc" => "",
            "id" => "advancedsettings",
            "std" => "<h3 style=\"margin: 0 0 10px;\">" . __('Twitter Settings', 'pbtheme') . "</h3>
						" . __('Set custom Twitter API keys. Go to', 'divison') . '<a href="http://dev.twitter.com/" target="_blank">' . __('Twitter Developer pages', 'pbtheme') . '</a>' . __('to get your secure keys.', 'pbtheme') . "",
            "icon" => true,
            "type" => "info"
        );
        $of_options[] = array("name" => __('Consumer Key', 'pbtheme'),
            "desc" => __('Consumer key provided by dev.twitter.com', 'pbtheme'),
            "id" => "twitter_ck",
            "std" => "GxayQKDHXgVXRriYSQnrxA",
            "type" => "text",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Consumer Secret', 'pbtheme'),
            "desc" => __('Consumer secret provided by dev.twitter.com', 'pbtheme'),
            "id" => "twitter_cs",
            "std" => "0tzhz6qpGc9S2eheWXDY1UZLJE0OQJ6ZqCnyeArekkw",
            "type" => "text",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Access token', 'pbtheme'),
            "desc" => __('Access token provided by dev.twitter.com', 'pbtheme'),
            "id" => "twitter_at",
            "std" => "966576138-o7EYr6hqQCGC3OhBLY7TdGV5x7U0EboaQayVqT9I",
            "type" => "text",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __('Access token secret', 'pbtheme'),
            "desc" => __('Access token secret provided by dev.twitter.com', 'pbtheme'),
            "id" => "twitter_ats",
            "std" => "rlaYNo2hpc7ndlQwPIM3aVidomdx8LMxEPNzla9RdZvTT",
            "type" => "text",
            "class" => "of-group-small"
        );
        $of_options[] = array("name" => __("Backup Options", 'pbtheme'),
            "type" => "heading",
            "group" => "div_grp_advanced",
            "icon" => "imscadmin-admin-backup"
        );
        $of_options[] = array("name" => __("Backup and Restore Options", 'pbtheme'),
            "id" => "of_backup",
            "std" => "",
            "type" => "backup",
            "desc" => __('You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.', 'pbtheme')
        );
        $of_options[] = array("name" => __("Transfer Theme Options Data", 'pbtheme'),
            "id" => "of_transfer",
            "std" => "",
            "type" => "transfer",
            "desc" => __("You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click \"Import Options\"", 'pbtheme')
        );
    }

//End function: of_options()
}//End chack if function exists: of_options()
?>