<?php
/**
 * @package WordPress
 * @subpackage PBTheme Theme
 * @author IM Success Center (http://www.imsuccesscenter.com)
 */
require_once 'theme-update-checker.php';
$imscpbtheme_uc = new ThemeUpdateChecker(
        'pbtheme', 'http://wpprofitbuilder.com/download/pbtheme/pbtheme.json'
);
//print_r($imscpb_uc);
//$imscpb_uc->checkForUpdates();

global $pbtheme_skip;

//$pbtheme_installed=true;

function pbtheme_after_switch_theme($oldname, $oldtheme = false) {
    global $pbtheme_installed;
    if ($oldtheme) {
        $themename = $oldtheme->get_stylesheet();
        if ($themename) {
            if (!add_option("pbtheme_admin_notice_message", ""))
                update_option("pbtheme_admin_notice_message", "");

            require_once(ABSPATH . 'wp-admin/includes/admin.php');
            $path = 'profit_builder/profit_builder.php';
            //print_r(get_option('active_plugins', array()));
            if (0 && !is_plugin_active(plugin_basename($path))) {
                if (!add_option("pbtheme_skiped", "true"))
                    update_option("pbtheme_skiped", "true");

                add_action('admin_notices', 'pbtheme_admin_notices');
                switch_theme($themename);
                //echo "test1";
            } else {
                global $imscpb_lc;
                if ($imscpb_lc && !$imscpb_lc->CheckLicense()) {
                    if (!add_option("pbtheme_skiped", "true"))
                        update_option("pbtheme_skiped", "true");

                    add_action('admin_notices', 'pbtheme_admin_notices');
                    switch_theme($themename);
                    //echo "test2";
                }else {
                    if (!add_option("pbtheme_skiped", "false"))
                        update_option("pbtheme_skiped", "false");

                    wp_redirect("themes.php?activated=true");
                }
            }
        }
    }
}

add_action("after_switch_theme", "pbtheme_after_switch_theme", 10, 2);

function pbtheme_admin_notices() {
    $pbtheme_admin_notice_message = get_option('pbtheme_admin_notice_message', '');
    if ($pbtheme_admin_notice_message == "") {
        echo '
        <style type="text/css">
        .pbtheme_plugins_message{
            background-color: #fdfdfd;
            border: 1px solid orange;
            padding: 10px;
            font-size:16px;
            font-weight:bold;
        }
        </style>
        <p class="pbtheme_plugins_message">
            <img src="' . IMSCPBT_URL . '/images/alert.png">
            Please Install & Activate Profit Builder Plugin First....
        </p>';
    } else {
        echo $pbtheme_admin_notice_message;
    }
}

function pbtheme_switch_theme($newname, $newtheme) {
    if (!add_option("pbtheme_skiped", "true"))
        update_option("pbtheme_skiped", "true");
}

add_action("switch_theme", "pbtheme_switch_theme", 10, 2);

//-----------------------------------------------------------
// Make sure plugin paths and definitions are in place
//-----------------------------------------------------------

if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if (!defined('WP_CONTENT_URL'))
    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if (!defined('WP_THEME_DIR'))
    define('WP_THEME_DIR', WP_CONTENT_DIR . '/themes');
if (!defined('WP_THEME_URL'))
    define('WP_THEME_URL', WP_CONTENT_URL . '/themes');

$wp_dir = __FILE__;
$wp_dir = str_replace("\\", "/", $wp_dir);
$wp_dir = explode("/", $wp_dir);
$index = count($wp_dir) - 2;
$themefolder = $wp_dir[$index];

define('IMSCPBT_SLUG', $themefolder);
define('IMSCPBT_DIR', WP_THEME_DIR . "/" . $themefolder);
define('IMSCPBT_URL', WP_THEME_URL . "/" . $themefolder);


/**
 * Acive Plugins
 */
define("DIVWP_MULTISITE", ( is_multisite() ? true : false));
$using_woo = false;
if (DIVWP_MULTISITE === true) {
    if (array_key_exists('woocommerce/woocommerce.php', maybe_unserialize(get_site_option('active_sitewide_plugins')))) {
        $using_woo = true;
    } elseif (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ? 'active' : '') {
        $using_woo = true;
    }
} elseif (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ? 'active' : '') {
    $using_woo = true;
}
define("DIVWP_WOOCOMMERCE", $using_woo);

$using_pbuilder = false;
if (DIVWP_MULTISITE === true) {
    if (array_key_exists('profit_builder/profit_builder.php', maybe_unserialize(get_site_option('active_sitewide_plugins')))) {
        $using_pbuilder = true;
    } elseif (in_array('profit_builder/profit_builder.php', apply_filters('active_plugins', get_option('active_plugins'))) ? 'active' : '') {
        $using_pbuilder = true;
    }
} elseif (in_array('profit_builder/profit_builder.php', apply_filters('active_plugins', get_option('active_plugins'))) ? 'active' : '') {
    $using_pbuilder = true;
}
define("DIVWP_FBUILDER", $using_pbuilder);

$using_revslider = false;
if (DIVWP_MULTISITE === true) {
    if (array_key_exists('revslider/revslider.php', maybe_unserialize(get_site_option('active_sitewide_plugins')))) {
        $using_revslider = true;
    } elseif (in_array('revslider/revslider.php', apply_filters('active_plugins', get_option('active_plugins'))) ? 'active' : '') {
        $using_revslider = true;
    }
} elseif (in_array('revslider/revslider.php', apply_filters('active_plugins', get_option('active_plugins'))) ? 'active' : '') {
    $using_revslider = true;
}
define("DIVWP_REVSLIDER", $using_revslider);

$using_ctimeline = false;
if (DIVWP_MULTISITE === true) {
    if (array_key_exists('ctimeline/ctimeline.php', maybe_unserialize(get_site_option('active_sitewide_plugins')))) {
        $using_ctimeline = true;
    } elseif (in_array('ctimeline/ctimeline.php', apply_filters('active_plugins', get_option('active_plugins'))) ? 'active' : '') {
        $using_ctimeline = true;
    }
} elseif (in_array('ctimeline/ctimeline.php', apply_filters('active_plugins', get_option('active_plugins'))) ? 'active' : '') {
    $using_ctimeline = true;
}
define("DIVWP_CTIMELINE", $using_ctimeline);

$using_bbpress = false;
if (DIVWP_MULTISITE === true) {
    if (array_key_exists('bbpress/bbpress.php', maybe_unserialize(get_site_option('active_sitewide_plugins')))) {
        $using_bbpress = true;
    } elseif (in_array('bbpress/bbpress.php', apply_filters('active_plugins', get_option('active_plugins'))) ? 'active' : '') {
        $using_bbpress = true;
    }
} elseif (in_array('bbpress/bbpress.php', apply_filters('active_plugins', get_option('active_plugins'))) ? 'active' : '') {
    $using_bbpress = true;
}
define("DIVWP_BBPRESS", $using_bbpress);


/**
 * SMOF
 */
require_once ('admin/index.php');

/*
 * After Setup PBTheme Theme
 */
if (!function_exists('pbtheme_setup_theme')) :

    function pbtheme_setup_theme() {

        global $pbtheme_data;

        load_theme_textdomain('pbtheme', get_template_directory() . '/languages');

        add_theme_support('automatic-feed-links');

        register_nav_menu('pbtheme_primary', __('Primary Header Menu', 'pbtheme'));

        add_theme_support('post-thumbnails');


        add_image_size('pbtheme-blog', 640, 400, true);
        add_image_size('pbtheme-portfolio', 640, 640, true);
        add_image_size('pbtheme-grid-large', 806, 594, true);
        add_image_size('pbtheme-grid-medium', 394, 394, true);
        add_image_size('pbtheme-grid-small', 394, 197, true);

        $feat_width = ( isset($pbtheme_data['fimage_width']) ? $pbtheme_data['fimage_width'] : 960 );
        $feat_height = ( isset($pbtheme_data['fimage_height']) ? $pbtheme_data['fimage_height'] : 600 );
        add_image_size('pbtheme-fullblog', $feat_width, $feat_height, true);

        add_image_size('pbtheme-square', 200, 200, true);

        add_theme_support('custom-background');

        // Add support for Post Formats
        add_theme_support('post-formats', array('audio', 'gallery', 'link', 'video', 'image', 'quote'));

        // Adds editor style
        add_editor_style();
    }

endif;
add_action('after_setup_theme', 'pbtheme_setup_theme');

/**
 * Load Cores
 */
include_once('shortcodes.php');
require_once('lib/wp-less/lessc.inc.php');
require_once('lib/wp-less/wp-less.php');
include_once('lib/twitteroauth/twitteroauth.php');
require_once dirname(__FILE__) . '/lib/tgm-plugin-activation/class-tgm-plugin-activation.php';

if (!function_exists('pbtheme_register_required_plugins')) :

    function pbtheme_register_required_plugins() {

        $plugins = array(
            array(
                'name' => 'Profit Builder',
                'slug' => 'profit_builder',
                'source' => get_template_directory() . '/lib/plugins/profit_builder.zip',
                'required' => true,
                'version' => '1.0.1',
                'force_activation' => false,
                'force_deactivation' => false,
                'external_url' => 'http://www.imsuccesscenter.com/pbuilder/wp-admin/admin-ajax.php?action=pbuilder_edit&p=2'
            ),
            array(
                'name' => 'WordPress Visual Icon Fonts',
                'slug' => 'wp-visual-icon-fonts',
                'required' => true,
                'version' => '0.5.7'
            ),
            array(
                'name' => 'Revolution Slider',
                'slug' => 'revslider',
                'source' => get_template_directory() . '/lib/plugins/revslider.zip',
                'required' => false,
                'version' => '4.5.01',
                'force_activation' => false,
                'force_deactivation' => false,
                'external_url' => 'https://revolution.themepunch.com/',
            ),
            /* array(
              'name'					=> 'AllAround Slider',
              'slug'					=> 'all_around',
              'source'				=> get_template_directory() . '/lib/plugins/div_all_around.zip',
              'required'				=> false,
              'version'				=> '1.41',
              'force_activation'		=> false,
              'force_deactivation'		=> false,
              'external_url'			=> 'http://www.imsuccesscenter.com/demo/?item=All%20Around%20Slider_Wordpress',
              ),
              array(
              'name'					=> 'Content Timeline',
              'slug'					=> 'content_timeline',
              'source'				=> get_template_directory() . '/lib/plugins/content_timeline.zip',
              'required'				=> false,
              'version'				=> '2.35',
              'force_activation'		=> false,
              'force_deactivation'		=> false,
              'external_url'			=> 'http://www.imsuccesscenter.com/demo/?item=Content%20Timeline_Wordpress',
              ),
              array(
              'name'					=> 'kk I Like It',
              'slug'					=> 'kk-i-like-it',
              'required'				=> false,
              'version'				=> '1.7.5.2'
              ), */
            array(
                'name' => 'bbPress',
                'slug' => 'bbpress',
                'required' => false,
                'version' => '2.5.4'
            ),
            array(
                'name' => 'WooCommerce',
                'slug' => 'woocommerce',
                'required' => false,
                'version' => '2.1.12'
            ),
            array(
                'name' => 'Transposh Translation Filter',
                'slug' => 'transposh-translation-filter-for-wordpress',
                'required' => false,
                'version' => '0.9.6',
                //'source' => get_template_directory() . '/lib/plugins/transposh-translation-filter-for-wordpress.zip',
                //'external_url' => 'https://wordpress.org/plugins/transposh-translation-filter-for-wordpress/',
            )
        );

        $theme_text_domain = 'pbtheme';


        $config = array(
            'domain' => $theme_text_domain,
            'default_path' => '',
            'parent_menu_slug' => 'themes.php',
            'parent_url_slug' => 'themes.php',
            'menu' => 'install-required-plugins',
            'has_notices' => true,
            'is_automatic' => true,
            'message' => '',
            'strings' => array(
                'page_title' => __('Install Required Plugins', $theme_text_domain),
                'menu_title' => __('Install Plugins', $theme_text_domain),
                'installing' => __('Installing Plugin: %s', $theme_text_domain), // %1$s = plugin name
                'oops' => __('Something went wrong with the plugin API.', $theme_text_domain),
                'notice_can_install_required' => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.'),
                'notice_can_install_recommended' => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.'),
                'notice_cannot_install' => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator 	of this site for help on getting the plugins installed.'),
                'notice_can_activate_required' => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.'),
                'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.'),
                'notice_cannot_activate' => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.'),
                'notice_ask_to_update' => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.'),
                'notice_cannot_update' => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.'),
                'install_link' => _n_noop('Begin installing plugin', 'Begin installing plugins'),
                'activate_link' => _n_noop('Activate installed plugin', 'Activate installed plugins'),
                'return' => __('Return to Required Plugins Installer', $theme_text_domain),
                'plugin_activated' => __('Plugin activated successfully.', $theme_text_domain),
                'complete' => __('All plugins installed and activated successfully. %s', $theme_text_domain)
            )
        );

        tgmpa($plugins, $config);
    }

endif;

add_action('tgmpa_register', 'pbtheme_register_required_plugins');

/*
 * Load Scripts
 */
if (!function_exists('pbtheme_scripts')) :

    function pbtheme_scripts() {
        global $pbtheme_data;

        if (is_singular() && comments_open() && get_option('thread_comments'))
            wp_enqueue_script('comment-reply');

        wp_enqueue_script('jquery');
        wp_enqueue_script('pbtheme-tween', get_template_directory_uri() . '/js/TweenMax.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('pbtheme-scrolltoplugin', get_template_directory_uri() . '/js/scrolltoplugin.js', array('jquery'), '1.0', true);
        wp_enqueue_script('pbtheme-swipebox', get_template_directory_uri() . '/js/swipebox/source/jquery.swipebox.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('pbtheme-idangerous', get_template_directory_uri() . '/js/idangerous.swiper-2.4.2.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('pbtheme-ba-dotimeout', get_template_directory_uri() . '/js/jquery.ba-dotimeout.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('pbtheme-ssc', get_template_directory_uri() . '/js/smoothscroll.js', array('jquery'), '1.0', true);
        wp_enqueue_script('pbtheme-main-js', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0', true);


        require_once 'Mobile_Detect.php';
        $detect = new Mobile_Detect;

        $entry = 'none';
        $entry_mute = 'none';
        $entry_loop = 'none';
        $entry_hd = 'none';
        $entry_fallback = 'none';
        $is_mobile = ($detect->isMobile() || $detect->isTablet()) ? "yes" : "no";

        if (is_page()) {
            $page_bg = get_post_meta(get_the_ID(), 'pbtheme_page_bg', true);
            if ($page_bg !== '' && $page_bg !== 'none') {
                switch ($page_bg) :
                    case 'videoembed' :
                        $entry = do_shortcode(get_post_meta(get_the_ID(), 'pbtheme_pagevideo_embed', true));
                        $entry_mute = get_post_meta(get_the_ID(), 'pbtheme_pagevideo_embed_mute', true);
                        $entry_loop = get_post_meta(get_the_ID(), 'pbtheme_pagevideo_embed_loop', true);
                        $entry_hd = get_post_meta(get_the_ID(), 'pbtheme_pagevideo_embed_hd', true);
                        $entry_fallback = get_post_meta(get_the_ID(), 'pbtheme_page_image', true); //wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );

                        break;
                    case 'html5video' :
                        $entry = 'none';
                        $entry_mute = 'none';
                        $entry_loop = 'none';
                        $entry_hd = 'none';
                        $entry_fallback = 'none';
                        break;
                /* default :
                  die( __('Invalid options.', 'pbtheme') ); */
                endswitch;
            }
        }
		
		if(!isset($page_bg)) $page_bg="";
        wp_localize_script(
                'pbtheme-main-js', 'pbtheme', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'ts_viewmore' => __('VIEW DETAILS', 'pbtheme'),
            'page_bg' => $page_bg,
            'video_bg' => $entry,
            'video_mute' => $entry_mute,
            'video_loop' => $entry_loop,
            'video_hd' => $entry_hd,
            'go_responsive' => $pbtheme_data['fb_lres_w'],
            'video_fallback' => $entry_fallback,
            'is_mobile_tablet' => $is_mobile,
                )
        );

    }

endif;
add_action('wp_enqueue_scripts', 'pbtheme_scripts');

/*
 * Load Styles
 */
if (!function_exists('pbtheme_styles_css')) :

    function pbtheme_styles_css() {

        global $pbtheme_data;

        if (!isset($content_width))
            $content_width = $pbtheme_data['content_width'];

        wp_enqueue_style('pbtheme-style', get_stylesheet_uri());
        wp_enqueue_style('pbtheme-less', get_template_directory_uri() . '/less/pbtheme.less');
        wp_enqueue_style('pbtheme-divicon', get_template_directory_uri() . '/fonts/pbtheme/styles.css');
        wp_enqueue_style('pbtheme-swipe', get_template_directory_uri() . '/js/swipebox/source/swipebox.css');
		
        
        if (DIVWP_FBUILDER === false) {
            wp_enqueue_style('pbtheme-frontend', get_template_directory_uri() . '/lib/frontend.css');
        }
    }

endif;
add_action('wp_enqueue_scripts', 'pbtheme_styles_css');

if (!function_exists('pbtheme_styles')) :

    function pbtheme_styles() {

        global $pbtheme_data;

        echo "<style id='pbtheme_styles' type='text/css'>\n";

        $pbtheme_rgb = implode(',', pbtheme_hex2rgb($pbtheme_data['theme_color']));
        $pbtheme_light_rgb = implode(',', pbtheme_hex2rgb($pbtheme_data['theme_color_light']));
        $pbtheme_dark_rgb = implode(',', pbtheme_hex2rgb($pbtheme_data['theme_color_dark']));
        $pbtheme_pale_rgb = implode(',', pbtheme_hex2rgb($pbtheme_data['theme_color_palee']));
        $pbtheme_footer = implode(',', pbtheme_hex2rgb($pbtheme_data['theme_color_footer_textt']));
	
        printf('#comments li.comment, #comments ul.children,.pbtheme_nav_element a{border-color:%5$s;}.active.div_main_bg {background:%1$s !important;}body.div-nobgvideo #pbtheme_wrapper{background:%4$s;}.div_main_bg{background:%1$s}.contact_form.comment_form .input_wrapper_select>div{border-color:%5$s}.header_wrapper{background:%4$s}.footer_wrapper{color:%10$s;color:rgba(%12$s,.8);background:%11$s}.footer_wrapper .blog_header_title{color:%10$s}.footer_wrapper .blog_header_title .blog_header_line{border-color:%10$s!important}.pbtheme_boxed #pbtheme_wrapper{border-color:%5$s}.sticky-header{background:%4$s}body{color:%3$s;background:%4$s}.blog_content h3 a:hover,.menu_wrapper a:hover,.menu_wrapper>ul>li>a:hover,a,a:hover,.pbtheme_nav_element a:hover,.pbtheme_empty_cart a{color:%1$s}.div_breadcrumbs{border-color:%5$s;background:%9$s}.hover_transparent.not-transparent h4,.menu_wrapper>ul>li>a,.tabs-nav .text_color_default,.pbtheme_nav_element a{color:%3$s}.pbtheme_border{border-color:%1$s!important}#pbtheme_sticky,.pbtheme_background{background:%4$s}.menu_wrapper>ul>li.menu-item ul.sub-menu,.menu_wrapper>ul>li>ul.sub-menu{background:%3$s;}.pbtheme_shopping_cart{background:%4$s;color:%3$s;border-color:%5$s}.pbtheme_background_dark,.pbtheme_separator,.small_separator{background:%3$s}.pbtheme_dark_border,.header_holder,.header_wrapper{border-color:%3$s!important}.single-tags-list a,.tagcloud a{background-color:%3$s;color:%4$s}.single-tags-list a:hover,.tagcloud a:hover{background:%1$s!important}.widget.woocommerce li,.widget_archive li,.widget_categories li,.widget_meta li,.widget_nav_menu li,.widget_pages li,.widget_recent_comments li,.widget_recent_entries li,.widget-pbtheme-catthree ul li{border-color:%5$s!important}.blog_content .post.format-quote .headline_highlighted_column_block .div_feat_quote{color:%4$s;background:%3$s;background:rgba(%3$s,.5)}.small_separator_pale{background-color:%5$s!important}.div_responsive #div_header_menu,.div_responsive #div_header_menu>ul>li,.pbtheme_pale_border,.posts_meta>div,body.artranspchive .a.kklike-box{border-color:%4$s;color:%4$s}a.kklike-box:hover{border-color:rgba(%7$s,.87);color:rgba(%7$s,.87)}a.kklike-box {background-color:%3$s;color:%4$s;}a.kklike-box:hover {background-color:%1$s;}format-quote .div_feat_quote, .single .singlepost-navigation div a{border-color:%5$s!important}.div_responsive_icons a{border-color:%5$s;color:%5$s}.div_responsive_icons a:hover{border-color:%1$s;color:%1$s}.background-color-main{background:%3$s}#respond input#submit,.widget.sendpress input[type=submit],input[type=submit]{background:%3$s;color:%4$s}.menu_wrapper ul ul{color:%4$s}.menu_wrapper>ul>li.has_children.hovered>a:after{border-top-color:%4$s}.menu_wrapper .is_fullwidth aside .pbtheme_dark_border{border-color:%4$s!important}.div_clac{background:%4$s;background:rgba(%6$s,.87)}.div_ctext,.frb_text,.text{color:%8$s}#respond input#submit:hover,.hover-background-color-lighter-main:hover,.widget.sendpress input[type=submit]:hover,.yop_poll_vote_button:hover,input[type=submit]:hover{background:%1$s}.frb_sidebar aside,.sidebar_wrapper aside{color:%8$s}.div_dtext,h1,h2,h3,h4,h5,h6{color:%3$s}h1 a:hover,h2 a:hover,h3 a:hover,h4 a:hover,h5 a:hover,h6 a:hover{color:%1$s}.inline-arrow-left.not-active,.inline-arrow-right.not-active{color:%5$s}.pbtheme_image_hover{background-color:%3$s;background-color:rgba(%7$s,.4)}.pbtheme_image_hover a.pbtheme_image_hover_button, .pbtheme_image_hover a.pbtheme_image_hover_button:hover, .a-inherit li>a:hover{color:%1$s}.progers-bars-wrapper .progress-full{background:%5$s;color:%4$s}.progers-bars-wrapper .progress-done{background:%1$s}.progers-bars-wrapper span.tag-place{background-color:%3$s}.progers-bars-wrapper .progress-tag span.tag-place:after{border-top-color:%3$s}.pbtheme_search,.element-language-bar ul{color:%3$s;background:%4$s;border-color:%5$s}.pbtheme_search form button{background:%1$s;color:%4$s}.pbtheme_header_widgets,body.single-format-quote .div_feat_quote,.pbtheme_hover_over .div_buttons a{background:%3$s;color:%4$s}.pbtheme_header_widgets .pbtheme_dark_border{border-color:%4$s!important}.pbtheme_shopping_cart .div-cart-remove a,.pbtheme_shopping_cart .pbtheme_cart_item{border-color:%5$s}.pbtheme_shopping_cart .div-cart-remove a:hover{border-color:%1$s}.pbtheme_search:after{border-bottom-color:%4$s}.element-woo-cart.hovered a.cart-contents:after{border-top-color:%4$s}nav.social_bar{background:%4$s}.pbtheme_shopping_cart .pbtheme_cart_button{background:%3$s;color:%4$s;border-color:%4$s}.pbtheme_shopping_cart .pbtheme_cart_button.float_left{background:%5$s}.pbtheme_shopping_cart .pbtheme_cart_button:hover{background:%1$s}.widget.sendpress input{border-color:%5$s!important}.pbtheme_slider_content .pbtheme_slider_meta{background:%4$s}.pagination_wrapper li a.current{color:%1$s}.pbtheme_hover_over{background:%3$s;background:rgba(%7$s,.87)}.pbtheme_hover_over .div_buttons a:hover{background:%1$s}.blog_content_infinite .div_feat_quote,.blog_content_infinite .hover_transparent.not-farent{background:%4$s}.blog_content_infinite .div_featarea.div_feat_quote{border-color:%5$s}.tweets-list li,.widget-pbtheme-cat li,.single .singlepost-navigation{border-color:%5$s!important}.pbtheme_hover_over .portslider_meta a,.pbtheme_hover_over .portslider_meta{color:%4$s;}.div_ajax_port_selected {background:%1$s;}.div_ajax_port_selected a{color:%4$s !important;}.pbtheme_hover_over .portslider_meta a:hover {color:%1$s;}.pbtheme_header_widgets .div_dtext,.pbtheme_header_widgets h1,.pbtheme_header_widgets h2,.pbtheme_header_widgets h3,.pbtheme_header_widgets h4,.pbtheme_header_widgets h5,.pbtheme_header_widgets h6{color:%4$s;}.footer_container aside h1,.footer_container aside h2,.footer_container aside h3,.footer_container aside h4,.footer_container aside h5,.footer_container aside h6{color:%10$s;}.footer_wrapper .div_dtext{color:%10$s;color:rgba(%12$s,.8);}.menu_wrapper li.menu-item .sub-menu li,.menu_wrapper ul li.is_fullwidth.hasno_sidebar > ul > li{border-color:%4$s;border-color:rgba(%6$s,0.1);}li.menu-item.hovered > a {background:%3$s;background:rgba(%6$s,0.05);}.infinite-load-button,.infinite-load-button-no-more{border-color:%5$s;color:%5$s;}.infinite-load-button:hover{border-color:%3$s;color:%3$s;}li.product li.product .element-network-icons > a:before{border-top-color:%3$s;}.div_portfolio_slider ul.div_top_nav_cat li.div_ajax_port_selected a,.div_portfolio_slider ul.div_top_nav_cat li a:hover{color:%1$s !important;}.div_portfolio_slider ul.div_top_nav_cat li a{color:%4$s}.footer_wrapper .element-network-icons > a:before{border-top-color:%4$s;}', $pbtheme_data['theme_color'], $pbtheme_rgb, $pbtheme_data['theme_color_dark'], $pbtheme_data['theme_color_light'], $pbtheme_data['theme_color_palee'], $pbtheme_light_rgb, $pbtheme_dark_rgb, $pbtheme_data['theme_color_textt'], ( $pbtheme_data['breadcrumbs-style'] == 'none' ? 'rgba(' . $pbtheme_dark_rgb . ',0.1)' : 'url(' . get_template_directory_uri() . '/images/breadcrumbs/' . $pbtheme_data['breadcrumbs-style'] . ')'), $pbtheme_data['theme_color_footer_textt'], $pbtheme_data['theme_color_footer_bg'], $pbtheme_footer, $pbtheme_pale_rgb
        );
		if( isset($pbtheme_data['theme_color_top_header']) ) echo ".header_alt_nav{background-color:".$pbtheme_data['theme_color_top_header']."}";
		if( isset($pbtheme_data['theme_color_header']) ) echo ".header_main_nav{background-color:".$pbtheme_data['theme_color_header']."}";
		
        if (DIVWP_WOOCOMMERCE === true) {
            printf('.woocommerce #content input.button,.woocommerce #content input.button.alt,.woocommerce #respond input#submit,.woocommerce #respond input#submit.alt,.woocommerce a.button,.woocommerce a.button.alt,.woocommerce button.button,.woocommerce button.button.alt,.woocommerce input.button,.woocommerce input.button.alt,.woocommerce-page #content input.button,.woocommerce-page #content input.button.alt,.woocommerce-page #respond input#submit,.woocommerce-page #respond input#submit.alt,.woocommerce-page a.button,.woocommerce-page a.button.alt,.woocommerce-page button.button,.woocommerce-page button.button.alt,.woocommerce-page input.button,.woocommerce-page input.button.alt{background:%3$s}.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active,.woocommerce #content input.button.alt:hover,.woocommerce #content input.button:hover,.woocommerce #respond input#submit.alt:hover,.woocommerce #respond input#submit:hover,.woocommerce a.button.alt:hover,.woocommerce a.button:hover,.woocommerce button.button.alt:hover,.woocommerce button.button:hover,.woocommerce div.product .woocommerce-tabs ul.tabs li.active,.woocommerce input.button.alt:hover,.woocommerce input.button:hover,.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active,.woocommerce-page #content input.button.alt:hover,.woocommerce-page #content input.button:hover,.woocommerce-page #respond input#submit.alt:hover,.woocommerce-page #respond input#submit:hover,.woocommerce-page a.button.alt:hover,.woocommerce-page a.button:hover,.woocommerce-page button.button.alt:hover,.woocommerce-page button.button:hover,.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active,.woocommerce-page input.button.alt:hover,.woocommerce-page input.button:hover{background:%1$s}.woocommerce #content div.product .woocommerce-tabs ul.tabs:before,.woocommerce div.product .woocommerce-tabs ul.tabs:before,.woocommerce-page #content div.product .woocommerce-tabs ul.tabs:before,.woocommerce-page div.product .woocommerce-tabs ul.tabs:before{border-color:%1$s}.woocommerce #content div.product .woocommerce-tabs ul.tabs li a:hover,.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover,.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a:hover,.woocommerce-page div.product .woocommerce-tabs ul.tabs li a:hover{color:%1$s}.woocommerce ul.product_list_widget li a{color:%3$s}.woocommerce .star-rating,.woocommerce ul.product_list_widget li a:hover,.woocommerce-page .star-rating{color:%1$s}.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle{background:%3$s}.woocommerce-error,.woocommerce-info,.woocommerce-message{background:%1$s}.woocommerce ul.cart_list li,.woocommerce ul.product_list_widget li,.woocommerce-page ul.cart_list li,.woocommerce-page ul.product_list_widget li{border-color:%5$s}.widget.woocommerce span.amount{color:%3$s}.woocommerce #content .quantity .minus,.woocommerce #content .quantity .plus,.woocommerce #content .quantity input.qty,.woocommerce .quantity .minus,.woocommerce .quantity .plus,.woocommerce .quantity input.qty,.woocommerce-page #content .quantity .minus,.woocommerce-page #content .quantity .plus,.woocommerce-page #content .quantity input.qty,.woocommerce-page .quantity .minus,.woocommerce-page .quantity .plus,.woocommerce-page .quantity input.qty{background-color:%3$s;color:%4$s;border-color:%3$s}.woocommerce #content .quantity .minus:hover,.woocommerce #content .quantity .plus:hover,.woocommerce .quantity .minus:hover,.woocommerce .quantity .plus:hover,.woocommerce-page #content .quantity .minus:hover,.woocommerce-page #content .quantity .plus:hover,.woocommerce-page .quantity .minus:hover,.woocommerce-page .quantity .plus:hover{background-color:%1$s;border-color:%1$s}.woocommerce form .form-row,.woocommerce-page form .form-row{border-color:%3$s;background:%4$s}.woocommerce #content table.cart a.remove,.woocommerce table.cart a.remove,.woocommerce-error,.woocommerce-info,.woocommerce-message,.woocommerce-page #content table.cart a.remove,.woocommerce-page table.cart a.remove{background-color:%3$s;color:%4$s}.shop_table.cart tbody tr,td{border-color:%5$s}.product_meta > span{border-color:%5$s;}.woocommerce-ordering select, .woocommerce-ordering select * {color:%3$s !important;}div.product div.images a img {border-color:%3$s !important;}.pbtheme_header_widgets .woocommerce ul.product_list_widget li a,.pbtheme_header_widgets .widget.woocommerce span.amount,.sidebar_holder .woocommerce ul.product_list_widget li a,.sidebar_holder .widget.woocommerce span.amount{color:%10$s;}.pbtheme_header_widgets .woocommerce ul.product_list_widget li a:hover,.sidebar_holder .woocommerce ul.product_list_widget li a:hover{color:%1$s;}.product-category.product h3.divwoo_cat{background:%1$s;background:rgba(%2$s,0.9);color:%4$s;}.product-category.product h3.divwoo_cat mark span {background:%3$s;color:%4$s;}a.added_to_cart{background-color:%3$s;}a.added_to_cart:hover{background-color:%1$s;}', $pbtheme_data['theme_color'], $pbtheme_rgb, $pbtheme_data['theme_color_dark'], $pbtheme_data['theme_color_light'], $pbtheme_data['theme_color_palee'], $pbtheme_light_rgb, $pbtheme_dark_rgb, $pbtheme_data['theme_color_textt'], ( $pbtheme_data['breadcrumbs-style'] == 'none' ? 'rgba(' . $pbtheme_dark_rgb . ',0.1)' : 'url(' . get_template_directory_uri() . '/images/breadcrumbs/' . $pbtheme_data['breadcrumbs-style'] . ')'), $pbtheme_data['theme_color_footer_textt'], $pbtheme_data['theme_color_footer_bg'], $pbtheme_footer, $pbtheme_pale_rgb
            );

            printf('.woocommerce ul.products li.product h3.product_title, .woocommerce-page ul.products li.product h3.product_title {height: %1$spx;overflow: hidden;width: 100%;}', $pbtheme_data['woo_title_height']);
        }
        if (DIVWP_BBPRESS === true) {
            printf('#bbpress-forums .bbp-forum-title,.bbp-topic-permalink{color:%3$s}#bbpress-forums .bbp-forum-title:hover,.bbp-topic-permalink:hover{color:%1$s}.bbp-forum-content ul.sticky,.bbp-topics ul.sticky,.bbp-topics ul.super-sticky,.bbp-topics-front ul.super-sticky{background:%1$s;background:rgba(%2$s,.1)!important}#bbpress-forums fieldset.bbp-form label,.bbp-topic-freshness *,.bbp-topic-started-by,div.bbp-template-notice{color:%8$s!important}#bbpress-forums .bbp-pagination-count:after,#bbpress-forums .bbp_select_wrapper>.select_menu,#bbpress-forums .bbp_select_wrapper>.select_menu>ul,#bbpress-forums fieldset.bbp-form input,#bbpress-forums fieldset.bbp-form textarea,#bbpress-forums input[type=checkbox]+label:after,#bbpress-forums li.bbp-body,#bbpress-forums li.bbp-body ul.forum,#bbpress-forums li.bbp-body ul.topic,#bbpress-forums li.bbp-header,.bbp-topic-form .quicktags-toolbar input,.bbp-topic-form textarea{border-color:%5$s}#bbpress-forums .bbp_select_wrapper>.select_menu a.drop_button>i,#bbpress-forums .bbp_select_wrapper>.select_menu>span,#bbpress-forums .bbp_select_wrapper>.select_menu>ul>li>a{color:%8$s}#bbpress-forums .bbp-pagination-links a:hover{color:%1$s}#bbpress-forums .bbp-pagination-links a,#bbpress-forums .bbp-pagination-links span.current{font-family:Sanchez,serif}#bbpress-forums img.avatar,.bbp-logged-in img.avatar{border-color:%5$s}#bbpress-forums .bbp-forum-freshness a{color:%3$s}#bbpress-forums .bbp-forum-freshness a:hover{color:%1$s}#bbpress-forums .bbp-forum-content,#bbpress-forums p{color:%8%s}.widget_display_forums li,.widget_display_replies li,.widget_display_topics li,.widget_display_views li,div.bbp-forum-header,div.bbp-reply-header,div.bbp-topic-header,li.bbp-body div.hentry{border-color:%5$s}.bbp-reply-content a,.bbp-reply-content a:hover{color:%1$s}a.bbp-author-name{color:%3$s}a.bbp-author-name:hover{color:%1$s}', $pbtheme_data['theme_color'], $pbtheme_rgb, $pbtheme_data['theme_color_dark'], $pbtheme_data['theme_color_light'], $pbtheme_data['theme_color_palee'], $pbtheme_light_rgb, $pbtheme_dark_rgb, $pbtheme_data['theme_color_textt'], ( $pbtheme_data['breadcrumbs-style'] == 'none' ? 'rgba(' . $pbtheme_dark_rgb . ',0.1)' : 'url(' . get_template_directory_uri() . '/images/breadcrumbs/' . $pbtheme_data['breadcrumbs-style'] . ')'), $pbtheme_data['theme_color_footer_textt'], $pbtheme_data['theme_color_footer_bg'], $pbtheme_footer, $pbtheme_pale_rgb
            );
        }
        if ($pbtheme_data['pbtheme_layout_color'] == 1) {
            printf('.footer_container .header_pbtheme_bottom,.header_holder,.header_wrapper,.header_wrapper .pbtheme_dark_border{border-color:%1$s!important}.footer_container>.small_separator{background:%1$s!important}.input_wrapper input,input.pbtheme_dark_border,select,select.pbtheme_dark_border,.comment_form.contact_form textarea,textarea.pbtheme_dark_border{border-color:%1$s!important}', $pbtheme_data['theme_color_palee']);
        }
        if ($pbtheme_data['custom-css'] !== '') :
            echo $pbtheme_data['custom-css'];
        endif;
        echo "</style>\n";
    }

endif;
add_action('wp_head', 'pbtheme_styles');


/*
 * LESS CSS
 */
if (!function_exists('pbtheme_less')) :

    function pbtheme_less($vars, $handle) {
        global $pbtheme_data;

        $vars['content_width'] = $pbtheme_data['content_width'] . 'px';
        $vars['fb_hres_c'] = $pbtheme_data['fb_hres_c'] . 'px';
        $vars['fb_mres_w'] = $pbtheme_data['fb_mres_w'] . 'px';
        $vars['fb_mres_c'] = $pbtheme_data['fb_mres_c'] . 'px';
        $vars['fb_lres_w'] = $pbtheme_data['fb_lres_w'] . 'px';
        $vars['fb_lres_c'] = $pbtheme_data['fb_lres_c'] . 'px';


        if ($pbtheme_data['font_ggl_on'] !== '1') {
			$vars['font_face'] = $pbtheme_data['font'];
            $vars['font_style'] = 'normal';
            $vars['font_weight'] = 'normal';
        } else {
            $vars['font_face'] = $pbtheme_data['font_ggl']['face'];
            $vars['font_style'] = $pbtheme_data['font_ggl']['style'];
            $vars['font_weight'] = $pbtheme_data['font_ggl']['weight'];
        }

        if ($pbtheme_data['font_header_ggl_on'] !== '1') {
            $vars['font_header_face'] = $pbtheme_data['font_header'];
            $vars['font_header_style'] = 'normal';
            $vars['font_header_weight'] = 'normal';
        } else {
            $vars['font_header_face'] = $pbtheme_data['font_header_ggl']['face'];
            $vars['font_header_style'] = $pbtheme_data['font_header_ggl']['style'];
            $vars['font_header_weight'] = $pbtheme_data['font_header_ggl']['weight'];
        }

        return $vars;
    }

endif;
add_filter('less_vars', 'pbtheme_less', 10, 2);


/*
 * Google Fonts
 */
if (!function_exists('pbtheme_fonts')) :

    function pbtheme_fonts() {

        global $pbtheme_data;

        if ($pbtheme_data['font_ggl_on'] !== '1' && $pbtheme_data['font_header_ggl_on'] !== '1') {
            return;
        }

        $font = array();
        if ($pbtheme_data['font_ggl_on'] == '1')
            $font[] = $pbtheme_data['font_ggl']['face'];
        if ($pbtheme_data['font_header_ggl_on'] == '1')
            $font[] = $pbtheme_data['font_header_ggl']['face'];

        $protocol = is_ssl() ? 'https' : 'http';

        $i = 0;
        foreach (array_unique($font) as $cf) {
            $i++;
            $scf = str_replace(' ', '+', $cf);
            wp_enqueue_style("pbtheme-font-$i", "//fonts.googleapis.com/css?family=$scf%3A100%2C200%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C700%2C700italic%2C800&amp;subset=all");
        }
      
    }

endif;
add_action('wp_print_styles', 'pbtheme_fonts');

/*
 * Layout Init
 */
if (!function_exists('pbtheme_create_layout')) :

    function pbtheme_create_layout() {
        global $pbtheme_data, $pbtheme_layout;

        $pbtheme_layout['widget_title_before'] = '<h3 class="blog_header_title pbtheme_header_font text-left margin-bottom24"><span class="title_container"><span class="title_holder float_left">';
        $pbtheme_layout['widget_title_after'] = '</span><span class="blog_header_line left_line pbtheme_dark_border float_left"></span><div class="clearfix"></div></span></h3>';
        $pbtheme_layout['footer_widget_title_before'] = '<h3 class="blog_header_title pbtheme_header_font text-left margin-bottom24"><span class="title_container"><span class="title_holder float_left">';
        $pbtheme_layout['footer_widget_title_after'] = '</span><span class="blog_header_line left_line pbtheme_dark_border float_left"></span><div class="clearfix"></div></span></h3>';
        $pbtheme_layout['depth'] = 4;
    }

endif;
add_action('widgets_init', 'pbtheme_create_layout');


/*
 * WooCommerce Init
 */

if (DIVWP_WOOCOMMERCE === true) {
    global $pbtheme_data;

    add_theme_support('woocommerce');
    add_filter('woocommerce_enqueue_styles', '__return_false');
    add_filter('loop_shop_per_page', create_function('$cols', 'return ' . ( isset($pbtheme_data['woo_per_page']) ? $pbtheme_data['woo_per_page'] : '9' ) . ';'), 20);

    if (!function_exists('div_enqueue_woocommerce_style')) :

        function div_enqueue_woocommerce_style() {
            wp_register_style('woocommerce', get_template_directory_uri() . '/woocommerce/style.css');
            if (class_exists('woocommerce')) {
                wp_enqueue_style('woocommerce');
            }
        }

    endif;
    add_action('wp_enqueue_scripts', 'div_enqueue_woocommerce_style');
}


/*
 * kkStars Init
 */
if (in_array('kk-i-like-it/admin.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    if (!function_exists('div_enqueue_kkstars_style')) :

        function div_enqueue_kkstars_style() {

            wp_deregister_style('kklike-front-css');
        }

    endif;
    add_action('wp_enqueue_scripts', 'div_enqueue_kkstars_style');
}

/*
 * Widgets Init
 */
if (!function_exists('pbtheme_sidebars_init')) :

    function pbtheme_sidebars_init() {

        global $pbtheme_data, $pbtheme_layout;

        register_sidebar(array(
            'name' => __('Blog Archives Sidebar', 'pbtheme'),
            'id' => 'sidebar-blog',
            'before_widget' => '<aside id="%1$s" class="widget margin-bottom36 %2$s">',
            'after_widget' => "</aside>",
            'before_title' => $pbtheme_layout['widget_title_before'],
            'after_title' => $pbtheme_layout['widget_title_after'],
            'description' => __('This sidebar appears on Blog Archive pages.', 'pbtheme')
        ));

        register_sidebar(array(
            'name' => __('Single Posts Sidebar', 'pbtheme'),
            'id' => 'sidebar-single',
            'before_widget' => '<aside id="%1$s" class="widget margin-bottom36 %2$s">',
            'after_widget' => "</aside>",
            'before_title' => $pbtheme_layout['widget_title_before'],
            'after_title' => $pbtheme_layout['widget_title_after'],
            'description' => __('This sidebar appears on Single Posts.', 'pbtheme')
        ));

        if (DIVWP_WOOCOMMERCE === true) {
            register_sidebar(array(
                'name' => __('Woocommerce Archive Sidebar', 'pbtheme'),
                'id' => 'sidebar-woo',
                'before_widget' => '<aside id="%1$s" class="widget margin-bottom36 %2$s">',
                'after_widget' => "</aside>",
                'before_title' => $pbtheme_layout['widget_title_before'],
                'after_title' => $pbtheme_layout['widget_title_after'],
                'description' => __('This sidebar appears on Woocommerce archive and shop pages.', 'pbtheme')
            ));

            register_sidebar(array(
                'name' => __('Woocommerce Posts Sidebar', 'pbtheme'),
                'id' => 'sidebar-woo-single',
                'before_widget' => '<aside id="%1$s" class="widget margin-bottom36 %2$s">',
                'after_widget' => "</aside>",
                'before_title' => $pbtheme_layout['widget_title_before'],
                'after_title' => $pbtheme_layout['widget_title_after'],
                'description' => __('This sidebar appears on Woocommerce single posts.', 'pbtheme')
            ));
        }

        if (DIVWP_BBPRESS === true) {
            register_sidebar(array(
                'name' => __('bbPress Forum Sidebar', 'pbtheme'),
                'id' => 'sidebar-bbpress',
                'before_widget' => '<aside id="%1$s" class="widget margin-bottom36 %2$s">',
                'after_widget' => "</aside>",
                'before_title' => $pbtheme_layout['widget_title_before'],
                'after_title' => $pbtheme_layout['widget_title_after'],
                'description' => __('This sidebar appears on Forum pages.', 'pbtheme')
            ));
        }

        register_sidebar(array(
            'name' => __('Footer 1', 'pbtheme'),
            'id' => 'footer-1',
            'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
            'after_widget' => "</aside>",
            'before_title' => $pbtheme_layout['footer_widget_title_before'],
            'after_title' => $pbtheme_layout['footer_widget_title_after']
        ));

        register_sidebar(array(
            'name' => __('Footer 2', 'pbtheme'),
            'id' => 'footer-2',
            'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
            'after_widget' => "</aside>",
            'before_title' => $pbtheme_layout['footer_widget_title_before'],
            'after_title' => $pbtheme_layout['footer_widget_title_after']
        ));

        register_sidebar(array(
            'name' => __('Footer 3', 'pbtheme'),
            'id' => 'footer-3',
            'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
            'after_widget' => "</aside>",
            'before_title' => $pbtheme_layout['footer_widget_title_before'],
            'after_title' => $pbtheme_layout['footer_widget_title_after']
        ));

        register_sidebar(array(
            'name' => __('Footer 4', 'pbtheme'),
            'id' => 'footer-4',
            'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
            'after_widget' => "</aside>",
            'before_title' => $pbtheme_layout['footer_widget_title_before'],
            'after_title' => $pbtheme_layout['footer_widget_title_after']
        ));

        $sidebars = ( isset($pbtheme_data['sidebar']) ? $pbtheme_data['sidebar'] : array() );
        foreach ($sidebars as $sidebar) {
            $title = sanitize_title($sidebar['title']);
            register_sidebar(array(
                'name' => $sidebar['title'],
                'id' => $title,
                'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                'after_widget' => "</aside>",
                'before_title' => $pbtheme_layout['widget_title_before'],
                'after_title' => $pbtheme_layout['widget_title_after']
            ));
        }

        $header_widgets_before = ( isset($pbtheme_data['header-widgets-before']) ? $pbtheme_data['header-widgets-before'] : 'none' );
        if ($header_widgets_before !== 'none') :
            for ($i = 1; $i <= $header_widgets_before; $i++) {
                register_sidebar(array(
                    'name' => __('Top Header Widgets ' . $i, 'pbtheme'),
                    'id' => 'header-widgets-before-' . $i,
                    'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                    'after_widget' => "</aside>",
                    'before_title' => $pbtheme_layout['widget_title_before'],
                    'after_title' => $pbtheme_layout['widget_title_after']
                ));
            }
        endif;

        $blog_widgets_before = ( isset($pbtheme_data['blog-widgets-before']) ? $pbtheme_data['blog-widgets-before'] : 'none' );
        if ($blog_widgets_before !== 'none') :
            for ($i = 1; $i <= $blog_widgets_before; $i++) {
                register_sidebar(array(
                    'name' => __('Blog Archives Widgets Before ' . $i, 'pbtheme'),
                    'id' => 'blog-widgets-before-' . $i,
                    'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                    'after_widget' => "</aside>",
                    'before_title' => $pbtheme_layout['widget_title_before'],
                    'after_title' => $pbtheme_layout['widget_title_after']
                ));
            }
        endif;

        $blog_widgets_after = ( isset($pbtheme_data['blog-widgets-after']) ? $pbtheme_data['blog-widgets-after'] : 'none' );
        if ($blog_widgets_after !== 'none') :
            for ($i = 1; $i <= $blog_widgets_after; $i++) {
                register_sidebar(array(
                    'name' => __('Blog Archives Widgets After ' . $i, 'pbtheme'),
                    'id' => 'blog-widgets-after-' . $i,
                    'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                    'after_widget' => "</aside>",
                    'before_title' => $pbtheme_layout['widget_title_before'],
                    'after_title' => $pbtheme_layout['widget_title_after']
                ));
            }
        endif;

        $single_widgets_before = ( isset($pbtheme_data['single-widgets-before']) ? $pbtheme_data['single-widgets-before'] : 'none' );
        if ($single_widgets_before !== 'none') :
            for ($i = 1; $i <= $single_widgets_before; $i++) {
                register_sidebar(array(
                    'name' => __('Single Posts Widgets Before ' . $i, 'pbtheme'),
                    'id' => 'single-widgets-before-' . $i,
                    'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                    'after_widget' => "</aside>",
                    'before_title' => $pbtheme_layout['widget_title_before'],
                    'after_title' => $pbtheme_layout['widget_title_after']
                ));
            }
        endif;

        $single_widgets_after = ( isset($pbtheme_data['single-widgets-after']) ? $pbtheme_data['single-widgets-after'] : 'none' );
        if ($single_widgets_after !== 'none') :
            for ($i = 1; $i <= $single_widgets_after; $i++) {
                register_sidebar(array(
                    'name' => __('Single Posts Widgets After ' . $i, 'pbtheme'),
                    'id' => 'single-widgets-after-' . $i,
                    'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                    'after_widget' => "</aside>",
                    'before_title' => $pbtheme_layout['widget_title_before'],
                    'after_title' => $pbtheme_layout['widget_title_after']
                ));
            }
        endif;

        if (DIVWP_WOOCOMMERCE === true) {
            $shop_widgets_before = ( isset($pbtheme_data['shop-widgets-before']) ? $pbtheme_data['shop-widgets-before'] : 'none' );
            if ($shop_widgets_before !== 'none') :
                for ($i = 1; $i <= $shop_widgets_before; $i++) {
                    register_sidebar(array(
                        'name' => __('Shop Archives Widgets Before ' . $i, 'pbtheme'),
                        'id' => 'shop-widgets-before-' . $i,
                        'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                        'after_widget' => "</aside>",
                        'before_title' => $pbtheme_layout['widget_title_before'],
                        'after_title' => $pbtheme_layout['widget_title_after']
                    ));
                }
            endif;

            $shop_widgets_after = ( isset($pbtheme_data['shop-widgets-after']) ? $pbtheme_data['shop-widgets-after'] : 'none' );
            if ($shop_widgets_after !== 'none') :
                for ($i = 1; $i <= $shop_widgets_after; $i++) {
                    register_sidebar(array(
                        'name' => __('Shop Archives Widgets After ' . $i, 'pbtheme'),
                        'id' => 'shop-widgets-after-' . $i,
                        'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                        'after_widget' => "</aside>",
                        'before_title' => $pbtheme_layout['widget_title_before'],
                        'after_title' => $pbtheme_layout['widget_title_after']
                    ));
                }
            endif;

            $product_widgets_before = ( isset($pbtheme_data['product-widgets-before']) ? $pbtheme_data['product-widgets-before'] : 'none' );
            if ($product_widgets_before !== 'none') :
                for ($i = 1; $i <= $product_widgets_before; $i++) {
                    register_sidebar(array(
                        'name' => __('Single Products Widgets Before ' . $i, 'pbtheme'),
                        'id' => 'product-widgets-before-' . $i,
                        'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                        'after_widget' => "</aside>",
                        'before_title' => $pbtheme_layout['widget_title_before'],
                        'after_title' => $pbtheme_layout['widget_title_after']
                    ));
                }
            endif;

            $product_widgets_after = ( isset($pbtheme_data['product-widgets-after']) ? $pbtheme_data['product-widgets-after'] : 'none' );
            if ($product_widgets_after !== 'none') :
                for ($i = 1; $i <= $product_widgets_after; $i++) {
                    register_sidebar(array(
                        'name' => __('Single Products Widgets After ' . $i, 'pbtheme'),
                        'id' => 'product-widgets-after-' . $i,
                        'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                        'after_widget' => "</aside>",
                        'before_title' => $pbtheme_layout['widget_title_before'],
                        'after_title' => $pbtheme_layout['widget_title_after']
                    ));
                }
            endif;
        }

        if (DIVWP_BBPRESS === true) {
            $bbpress_widgets_before = ( isset($pbtheme_data['bbpress-widgets-before']) ? $pbtheme_data['bbpress-widgets-before'] : 'none' );
            if ($bbpress_widgets_before !== 'none') :
                for ($i = 1; $i <= $bbpress_widgets_before; $i++) {
                    register_sidebar(array(
                        'name' => __('bbPress Forum Widgets Before ' . $i, 'pbtheme'),
                        'id' => 'bbpress-widgets-before-' . $i,
                        'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                        'after_widget' => "</aside>",
                        'before_title' => $pbtheme_layout['widget_title_before'],
                        'after_title' => $pbtheme_layout['widget_title_after']
                    ));
                }
            endif;

            $bbpress_widgets_after = ( isset($pbtheme_data['bbpress-widgets-after']) ? $pbtheme_data['bbpress-widgets-after'] : 'none' );
            if ($bbpress_widgets_after !== 'none') :
                for ($i = 1; $i <= $bbpress_widgets_after; $i++) {
                    register_sidebar(array(
                        'name' => __('bbPress Forum Widgets After ' . $i, 'pbtheme'),
                        'id' => 'bbpress-widgets-after-' . $i,
                        'before_widget' => '<aside id="%1$s" class="widget %2$s margin-bottom36">',
                        'after_widget' => "</aside>",
                        'before_title' => $pbtheme_layout['widget_title_before'],
                        'after_title' => $pbtheme_layout['widget_title_after']
                    ));
                }
            endif;
        }
    }

endif;
add_action('widgets_init', 'pbtheme_sidebars_init');


/**
 * Read More
 */
if (!function_exists('pbtheme_remove_more_link')) :

    function pbtheme_remove_more_link($link) {
        return sprintf('<div class="clearfix"></div><a href="%1$s" rel="nofollow" class="read_more button background-color-main hover-background-color-lighter-main">%2$s</a>', get_permalink(), __('Read More', 'pbtheme'));
    }

endif;
add_filter('the_content_more_link', 'pbtheme_remove_more_link');


/**
 * Pagination
 */
if (!function_exists('pbtheme_pagination')) :

    function pbtheme_pagination($pages = '', $page = '', $range = 3, $ajax = '') {
        if ($page == '') {
            global $paged;
            if (empty($paged))
                $paged = 1;
        }
        else {
            $paged = $page;
        }
        $next_page = $paged + 1;
        $prev_page = $paged - 1;
        $showitems = $range;
        $out = '';

        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }

        if ($ajax !== '' && $ajax !== 'no') {
            $ajaxload = 'onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;"';
        } else {
            $ajaxload = '';
        }

        if (1 != $pages) {

            $out .= "<nav class='pagination_wrapper text-center fullwidth pbtheme_header_font a-inherit'><div class='pagination_container'><span class='blog_header_line left_line pbtheme_pale_border inline-block'></span><div class=\"inline-block\"><ul class='pbtheme_background'>";

            if ($paged > 3 && $paged > $range && $showitems < $pages)
                $out .= "<li><a title=\"" . __('View First Page', 'pbtheme') . "\" href='" . get_pagenum_link(1) . "' class=\"lose_width first\" " . $ajaxload . ">" . __('First', 'pbtheme') . "<span class='display_none pbtheme_page'>1</span></a></li>";

            if ($paged > 1)
                $out .= "<li><a title=\"" . __('View newer posts', 'pbtheme') . "\" href=\"" . get_pagenum_link($paged - 1) . "\" class=\"lose_width previous\" " . $ajaxload . ">" . __('Previous', 'pbtheme') . "<span class='display_none pbtheme_page'>" . ($paged - 1) . "</span></a></li>";

            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!( $i >= $paged + 3 || $i <= $paged - 3) || $pages <= $showitems )) {
                    $out .= ( $paged == $i ) ? "<li><a class=\"current\">" . $i . "</a></li>" : "<li><a title='" . __('View page number', 'pbtheme') . " " . $i . "' href='" . get_pagenum_link($i) . "' class=\"inactive\" " . $ajaxload . ">" . $i . "<span class='display_none pbtheme_page'>" . $i . "</span></a></li>";
                } elseif ($i == $paged + 3) {
                    $out .= '<li><a class="current">...</a></li>';
                } elseif ($i == $paged - 3) {
                    $out .= '<li><a class="current">...</a></li>';
                }
            }
            if ($paged < $pages)
                $out .= "<li><a title=\"" . __('View earlier posts', 'pbtheme') . "\" href=\"" . get_pagenum_link($paged + 1) . "\" class=\"lose_width next\" " . $ajaxload . ">" . __('Next', 'pbtheme') . "<span class='display_none pbtheme_page'>" . ( $paged + 1 ) . "</span></a></li>";
            if ($paged <= $pages - 3 && $paged + $range <= $pages && $showitems < $pages)
                $out .= "<li><a title=\"" . __('View Last Page', 'pbtheme') . "\" href='" . get_pagenum_link($pages) . "' class=\"lose_width last\" " . $ajaxload . ">" . __('Last', 'pbtheme') . "<span class='display_none pbtheme_page'>" . $pages . "</span></a></li>";
            $out .= "<div class=\"clearfix\"></div></ul></div><span class='blog_header_line left_line pbtheme_pale_border inline-block'></span></div></nav>";
        }
        return $out;
    }

endif;


/**
 * Pagination Top Mini
 */
if (!function_exists('pbtheme_mini_pagination')) :

    function pbtheme_mini_pagination($pages = '', $page = '', $range = 3, $ajax = '', $title = '') {
        if ($page == '') {
            global $paged;
            if (empty($paged))
                $paged = 1;
        }
        else {
            $paged = $page;
        }
        $next_page = $paged + 1;
        $prev_page = $paged - 1;
        $showitems = 5;
        $out = '';

        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }

        if ($ajax !== '' && $ajax !== 'no') {
            $ajaxload = 'onclick="pbtheme_ajaxload(jQuery(this)); return false;"';
        } else {
            $ajaxload = '';
        }

        if (1 != $pages) {
            $out .= "<nav class='blog_top_pagination pbtheme_header_font a-inherit margin-bottom24'><span class='title_container'><span class='another_con'><span class='blog_header_line left_line pbtheme_dark_border inline-block'></span><ul class='inline-block'>";

            if ($paged > 1) {
                $out .= "<li class='inline-block inline-arrow-left'><a title=\"" . __('View newer posts', 'pbtheme') . "\" href=\"" . get_pagenum_link($paged - 1) . "\" class=\"previous bold_font\" " . $ajaxload . "><i class='fa fa-angle-left'></i><span class='display_none pbtheme_page'>" . ($paged - 1) . "</span></a></li>";
            } else {
                $out .= "<li class='inline-block inline-arrow-left not-active'><a title=\"" . __('View newer posts', 'pbtheme') . "\" class=\"previous bold_font\"><i class='fa fa-angle-left'></i><span class='display_none pbtheme_page'>" . ($paged - 1) . "</span></a></li>";
            }

            $out .= sprintf('<li class="inline-block pagination_title">%1$s</li>', $title);

            if ($paged < $pages) {
                $out .= "<li class='inline-block inline-arrow-right'><a title=\"" . __('View earlier posts', 'pbtheme') . "\" href=\"" . get_pagenum_link($paged + 1) . "\" class=\"next bold_font\" " . $ajaxload . "><i class='fa fa-angle-right'></i><span class='display_none pbtheme_page'>" . ( $paged + 1 ) . "</span></a></li>";
            } else {
                $out .= "<li class='inline-block inline-arrow-right not-active'><a title=\"" . __('View earlier posts', 'pbtheme') . "\" class=\"next bold_font\"><i class='fa fa-angle-right'></i><span class='display_none pbtheme_page'>" . ( $paged + 1 ) . "</span></a></li>";
            }

            $out .= "</ul><span class='blog_header_line left_line pbtheme_dark_border inline-block'></span></span></span></nav>";
        }
        return $out;
    }

endif;


/**
 * Pagination Top WooCommerce
 */
if (!function_exists('pbtheme_mini_woo_pagination')) :

    function pbtheme_mini_woo_pagination($pages = '', $page = '', $range = 3, $ajax = '', $title = '') {
        if ($page == '') {
            global $paged;
            if (empty($paged))
                $paged = 1;
        }
        else {
            $paged = $page;
        }
        $next_page = $paged + 1;
        $prev_page = $paged - 1;
        $showitems = 5;
        $out = '';

        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 2;
            }
        }

        if ($ajax !== '' && $ajax !== 'no') {
            $ajaxload = 'onclick="pbtheme_ajaxload_send_woo(jQuery(this)); return false;"';
        } else {
            $ajaxload = '';
        }

        if (1 != $pages) {
            $out .= "<nav class='blog_top_pagination pbtheme_header_font a-inherit margin-bottom24'><span class='title_container'><span class='another_con'><span class='blog_header_line left_line pbtheme_dark_border inline-block'></span><ul class='inline-block'>";

            if ($paged > 1) {
                $out .= "<li class='inline-block inline-arrow-left'><a title=\"" . __('View newer posts', 'pbtheme') . "\" href=\"" . get_pagenum_link($paged - 1) . "\" class=\"previous bold_font\" " . $ajaxload . "><i class='fa fa-angle-left'></i><span class='display_none pbtheme_page'>" . ($paged - 1) . "</span></a></li>";
            } else {
                $out .= "<li class='inline-block inline-arrow-left not-active'><a title=\"" . __('View newer posts', 'pbtheme') . "\" class=\"previous bold_font\"><i class='fa fa-angle-left'></i><span class='display_none pbtheme_page'>" . ($paged - 1) . "</span></a></li>";
            }

            $out .= sprintf('<li class="inline-block pagination_title">%1$s</li>', $title);

            if ($paged < $pages) {
                $out .= "<li class='inline-block inline-arrow-right'><a title=\"" . __('View earlier posts', 'pbtheme') . "\" href=\"" . get_pagenum_link($paged + 1) . "\" class=\"next bold_font\" " . $ajaxload . "><i class='fa fa-angle-right'></i><span class='display_none pbtheme_page'>" . ( $paged + 1 ) . "</span></a></li>";
            } else {
                $out .= "<li class='inline-block inline-arrow-right not-active'><a title=\"" . __('View earlier posts', 'pbtheme') . "\" class=\"next bold_font\"><i class='fa fa-angle-right'></i><span class='display_none pbtheme_page'>" . ( $paged + 1 ) . "</span></a></li>";
            }

            $out .= "</ul><span class='blog_header_line left_line pbtheme_dark_border inline-block'></span></span></span></nav>";
        }
        return $out;
    }

endif;


/**
 * Pagination WooCommerce
 */
if (!function_exists('pbtheme_mini_woo_pagination_cat')) :

    function pbtheme_mini_woo_pagination_cat($pages = '', $paged = '', $offset = '', $ajax = '', $title = '') {

        $out = '';
        $pages = ceil($pages / $offset);

        if ($ajax !== '' && $ajax !== 'no') {
            $ajaxload = 'onclick="pbtheme_ajaxload_send_woo_cat(jQuery(this)); return false;"';
        } else {
            $ajaxload = '';
        }

        if (1 != $pages) {
            $out .= "<nav class='blog_top_pagination pbtheme_header_font a-inherit margin-bottom24'><span class='title_container'><span class='another_con'><span class='blog_header_line left_line pbtheme_dark_border inline-block'></span><ul class='inline-block'>";

            if ($paged > 1) {
                $out .= "<li class='inline-block inline-arrow-left'><a title=\"" . __('View newer posts', 'pbtheme') . "\" href=\"" . get_pagenum_link($paged - 1) . "\" class=\"previous bold_font\" " . $ajaxload . "><i class='fa fa-angle-left'></i><span class='display_none pbtheme_page'>" . ($paged - 1) . "</span></a></li>";
            } else {
                $out .= "<li class='inline-block inline-arrow-left not-active'><a title=\"" . __('View newer posts', 'pbtheme') . "\" class=\"previous bold_font\"><i class='fa fa-angle-left'></i><span class='display_none pbtheme_page'>" . ($paged - 1) . "</span></a></li>";
            }

            $out .= sprintf('<li class="inline-block pagination_title">%1$s</li>', $title);

            if ($paged < $pages) {
                $out .= "<li class='inline-block inline-arrow-right'><a title=\"" . __('View earlier posts', 'pbtheme') . "\" href=\"" . get_pagenum_link($paged + 1) . "\" class=\"next bold_font\" " . $ajaxload . "><i class='fa fa-angle-right'></i><span class='display_none pbtheme_page'>" . ( $paged + 1 ) . "</span></a></li>";
            } else {
                $out .= "<li class='inline-block inline-arrow-right not-active'><a title=\"" . __('View earlier posts', 'pbtheme') . "\" class=\"next bold_font\"><i class='fa fa-angle-right'></i><span class='display_none pbtheme_page'>" . ( $paged + 1 ) . "</span></a></li>";
            }

            $out .= "</ul><span class='blog_header_line left_line pbtheme_dark_border inline-block'></span></span></span></nav>";
        }
        return $out;
    }

endif;


/**
 * PBTheme Comments, Pingbacks, Trackbacks
 */
if (!function_exists('pbtheme_comment')) :

    function pbtheme_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :

            case '' :
                ?>
                <li <?php comment_class('single_comment'); ?> id="li-comment-<?php comment_ID(); ?>">
                    <div id="comment-<?php comment_ID(); ?>" class="div_comment">
                <?php echo get_avatar($comment, 100); ?>

                        <div class="comment_meta margin-bottom12 pbtheme_header_font a-inherit">
                            <div class="author_meta"><?php echo get_comment_author_link(); ?></div>
                            <div class="date_meta"><?php echo get_comment_date(); ?></div>
                        </div>

                        <div class="comment_text">
                <?php
                comment_text();
                if ($comment->comment_approved == '0') :
                    ?>
                                <p class="moderation">
                    <?php _e('Your comment is awaiting moderation.', 'pbtheme'); ?>
                                </p>
                <?php endif; ?>
                        </div>

                        <div class="comment_edit margin-bottom36 pbtheme_header_font">
                            <?php
                            comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
                            edit_comment_link(__('Edit', 'pbtheme'), ' ');
                            ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                            <?php
                            break;
                        case 'pingback' :
                            ?>
                <li class="post pingback">
                    <p>
                            <?php
                            _e('Pingback:', 'pbtheme');
                            comment_author_link();
                            edit_comment_link(__('(Edit)', 'pbtheme'), ' ');
                            ?>
                    </p>
                    <?php
                    break;
                case 'trackback' :
                    ?>
                <li class="post pingback">
                    <p>
                        <?php
                        _e('Pingback:', 'pbtheme');
                        comment_author_link();
                        edit_comment_link(__('(Edit)', 'pbtheme'), ' ');
                        ?>
                    </p>
                    <?php
                    break;
            endswitch;
        }

    endif;
    /*
     * Code added by Asim Ashraf - DevBatch
     * DateTime: 10 Feb 2015
     * Edit Start
     */
    if (!function_exists('pbtheme_continue_reading_link')) :

        function pbtheme_continue_reading_link() {
            return '';
        }

    endif;
    //add_filter('excerpt_more', 'pbtheme_continue_reading_link');
    add_filter('the_content_more_link', 'pbtheme_continue_reading_link');
    /*
     * Edit End
     */

// String limit by char
    if (!function_exists('pbtheme_string_limit_words')) :

        function pbtheme_string_limit_words($str, $n = 500, $end_char = '&#8230;') {
            /*
             * Code added by Asim Ashraf - DevBatch
             * DateTime: 10 Feb 2015
             * Edit Start
             */
            global $post;
            if (!empty($post->post_excerpt)) {
                return $str;
            }
            if (strpos($post->post_content, '<!--more-->') !== FALSE) {
                $content = apply_filters('the_content', get_the_content());
                add_filter('the_content_more_link', 'pbtheme_continue_reading_link');
                return $content = str_replace(']]>', ']]&gt;', $content);
                //return $moreContent;
            }


            /*
             * Edit End
             */
            if ($n == 0)
                return;
            if (strlen($str) < $n) {
                return $str;
            }
            $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));
            if (strlen($str) <= $n) {
                return $str;
            }
            $out = "";
            foreach (explode(' ', trim($str)) as $val) {
                $out .= $val . ' ';
                if (strlen($out) >= $n) {
                    $out = trim($out);
                    return (strlen($out) == strlen($str)) ? $out : $out . $end_char;
                }
            }
        }

    endif;


    /**
     * Ajax Load Posts
     */
    if (!function_exists('pbtheme_ajaxload_send')) :

        function pbtheme_ajaxload_send() {
            $out = '';
            $post_counter = 0;
            $add_class = '';

            $query_string = $_POST['data'];
            $current_page = pbtheme_get_between($query_string, 'paged=', '&');
            $type = $_POST['type'];
            $page = $_POST['page'];
            $ajax = $_POST['ajax'];
            $words = $_POST['excerpt'];
            $bot_margin = $_POST['bot_margin'];
            $title = stripslashes($_POST['title']);

            switch ($type) {
                case 'pbtheme_type_0' :
                    $columns = 1;
                    $image_size = 'pbtheme-blog';
                    break;
                case 'pbtheme_type_1' :
                    $columns = 2;
                    $image_size = 'pbtheme-blog';
                    break;
                case 'pbtheme_type_2' :
                    $columns = 3;
                    $image_size = 'pbtheme-blog';
                    break;
                case 'pbtheme_type_3' :
                    $columns = 4;
                    $image_size = 'pbtheme-blog';
                    break;
                case 'pbtheme_type_4' :
                    $columns = 5;
                    $image_size = 'pbtheme-blog';
                    break;
                default :
                    $columns = 1;
                    $image_size = 'pbtheme-square';
                    break;
            }

            $query_string = str_replace('paged=' . $current_page . '&', 'paged=' . $page . '&', $query_string);
            $pbtheme_posts = new WP_Query($query_string);
            if ($pbtheme_posts->have_posts()) :
                $out .= pbtheme_mini_pagination($pbtheme_posts->max_num_pages, $page, 3, $ajax, $title);

                $out .= "@@@!SPLIT!@@@<div class='blog_content {$type}' data-string='{$query_string}' data-shortcode='{$words}|{$bot_margin}|{$title}' style='margin-bottom:{$bot_margin}px'>";
                $out .= '<div class="separate-post-column anivia_row margin-top36 pbuilder_row"><div>';
                while ($pbtheme_posts->have_posts()): $pbtheme_posts->the_post();

                    $feat_area = '';
                    $heading = '';
                    $post_counter++;
                    if ($add_class !== '')
                        $out .= '</div></div><div class="separate-post-column anivia_row margin-top36 pbuilder_row"><div>';
                    $out .= '<div class=" ' . implode(' ', get_post_class()) . ' pbuilder_column pbuilder_column-1-' . $columns . '"><div class="headline_highlighted_column_block">';

                    if ($type !== 'pbtheme_type_small') {
                        $out .= pbtheme_get_featarea($image_size);
                        $timecode = get_the_date();
                        $heading .= '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                        $heading .= '<div class="posts_meta"><div class="date_meta inline-block">' . $timecode . '</div><div class="category_meta inline-block a-inherit">' . __('in', 'pbtheme') . ' ' . get_the_category_list(', ') . '</div></div>';

                        $out .= ( $type == 0 ? $heading . $feat_area : $feat_area . $heading );
                        if ($words !== '0 ') {
                            $excerpt = get_the_excerpt();
                            $out .= '<div class="text margin-top6 margin-bottom6">' . pbtheme_string_limit_words($excerpt, $words) . '</div>';
                        }
                        $out .= '<div class="div_readmore">' . do_shortcode(sprintf('[pbtheme_title color="small_separator_pale" link="%2$s" type="h5" align="left" bot_margin="0"]%1$s[/pbtheme_title]', __('Read more', 'pbtheme'), get_permalink())) . '</div>';
                        $out .= '</div></div>';
                    } else {
                        if (get_post_format() == 'quote') {
                            $feat_area .= '<div class="div_featarea div_feat_quote pbtheme_header_font margin-bottom36">' . get_the_content() . '</div>';
                        } elseif (has_post_thumbnail()) {
                            $feat_area .= '<div class="div_featarea div_feat_small">' . get_the_post_thumbnail(get_the_ID(), 'pbtheme-square');
                            $feat_area .= sprintf('<div class="pbtheme_image_hover"><div><a href="%1$s" class="pbtheme_image_hover_button" rel="bookmark"><i class="divicon-plus"></i></a></div></div></div>', get_permalink());
                        }

                        $heading .= '<h3><a href="' . get_permalink() . '" rel="bookmark">' . get_the_title() . '</a></h3>';
                        if (get_post_format() !== 'quote') {
                            $timecode = get_the_date() . ' @ ' . get_the_time();
                            $num_comments = get_comments_number();
                            if (comments_open()) {
                                if ($num_comments == 0) {
                                    $comments = __('Leave a comment', 'pbtheme');
                                } elseif ($num_comments > 1) {
                                    $comments = $num_comments . __(' Comments', 'pbtheme');
                                } else {
                                    $comments = __('1 Comment', 'pbtheme');
                                }
                                $write_comments = '<a href="' . get_comments_link() . '">' . $comments . '</a>';
                            } else {
                                $write_comments = __('Comments are off for this post.', 'pbtheme');
                            }

                            $heading .= '<div class="posts_meta"><div class="div_date_meta inline-block">' . $timecode . '</div><div class="div_author_meta inline-block a-inherit">' . __('by', 'anivia') . ' ' . get_the_author_link() . '</div><div class="div_category_meta inline-block a-inherit">' . __('in', 'pbtheme') . ' ' . get_the_category_list(', ') . '</div><div class="div_comment_meta inline-block a-inherit">' . $write_comments . '</div></div>';
                        }

                        $out .= $feat_area . $heading;
                        $excerpt = get_the_excerpt();
                        $out .= '<div class="text margin-top6 margin-bottom24">' . pbtheme_string_limit_words($excerpt, $words) . '</div>';
                        $out .= '<div class="clearfix"></div>';
                        $out .= do_shortcode(sprintf('[pbtheme_title color="small_separator_pale" link="%2$s" type="h5" align="%3$s" bot_margin="0"]%1$s[/pbtheme_title]', __('Read more', 'pbtheme'), get_permalink(), 'right'));
                        $out .= '</div></div>';
                    }


                    if ($post_counter == $columns) {
                        $post_counter = 0;
                        $add_class = 'new_row';
                    } else {
                        $add_class = '';
                    }

                endwhile;
                $out .= '</div></div>';

                $out .= '<div class="clearfix"></div>';

                $out .= '</div>';

            endif;

            die($out);
            exit;
        }

    endif;
    add_action('wp_ajax_nopriv_pbtheme_ajaxload_send', 'pbtheme_ajaxload_send');
    add_action('wp_ajax_pbtheme_ajaxload_send', 'pbtheme_ajaxload_send');


    /**
     * Ajax Load WooCommerce Products
     */
    if (!function_exists('pbtheme_ajaxload_send_woo')) :

        function pbtheme_ajaxload_send_woo() {
            global $woocommerce, $woocommerce_loop;
            $query_string = $_POST['data'];
            $current_page = pbtheme_get_between($query_string, 'paged=', '&');
            $page = $_POST['page'];
            $ajax = $_POST['ajax'];
            $bot_margin = $_POST['bot_margin'];
            $title = stripslashes($_POST['title']);
            $columns = $_POST['columns'];
            $args = str_replace('paged=' . $current_page . '&', 'paged=' . $page . '&', $query_string);

            $woocommerce_loop['columns'] = $columns;

            ob_start();

            $products = new WP_Query($args);

            if ($products->have_posts()) :
                ?>

                <?php woocommerce_product_loop_start(); ?>

                <?php while ($products->have_posts()) : $products->the_post(); ?>


                    <?php woocommerce_get_template_part('content', 'product'); ?>

                <?php endwhile; // end of the loop. ?>

                <?php woocommerce_product_loop_end(); ?>

            <?php
            endif;

            wp_reset_postdata();

            die(pbtheme_mini_woo_pagination($products->max_num_pages, $page, 1, 'yes', $title) . '@@@!SPLIT!@@@' . '<div class="woocommerce">' . ob_get_clean() . '</div>');
        }

    endif;
    add_action('wp_ajax_nopriv_pbtheme_ajaxload_send_woo', 'pbtheme_ajaxload_send_woo');
    add_action('wp_ajax_pbtheme_ajaxload_send_woo', 'pbtheme_ajaxload_send_woo');


    /**
     * Ajax Load WooCommerce Categories
     */
    if (!function_exists('pbtheme_ajaxload_send_woo_cat')) :

        function pbtheme_ajaxload_send_woo_cat() {
            global $woocommerce, $woocommerce_loop;
            $current_page = $_POST['page'];
            $page = $_POST['page'];
            $ajax = $_POST['ajax'];
            $bot_margin = $_POST['bot_margin'];
            $title = stripslashes($_POST['title']);
            $columns = $_POST['columns'];
            $per_page = $_POST['per_page'];
            $order = $_POST['order'];
            $orderby = $_POST['orderby'];
            $ids = $_POST['ids'];
            $hide_empty = 1;
            $parent = '';

            $ids = explode(',', $ids);
            $ids = array_map('trim', $ids);

            $args = array(
                'orderby' => $orderby,
                'order' => $order,
                'hide_empty' => 1,
                'include' => $ids,
                'pad_counts' => true,
                'child_of' => $parent,
                'parent' => '',
                'offset' => $per_page * ($page - 1)
            );

            $product_categories = get_terms('product_cat', $args);
            $cat_num = count($product_categories);
            $product_categories = array_slice($product_categories, $per_page * ($page - 1), $per_page);

            $pagination = pbtheme_mini_woo_pagination_cat($cat_num, $current_page, $per_page, 'yes', $title);

            if ($parent !== "") {
                $product_categories = wp_list_filter($product_categories, array('parent' => $parent));
            }

            if ($hide_empty) {
                foreach ($product_categories as $key => $category) {
                    if ($category->count == 0) {
                        unset($product_categories[$key]);
                    }
                }
            }

            $woocommerce_loop['columns'] = $columns;

            ob_start();

            // Reset loop/columns globals when starting a new loop
            $woocommerce_loop['loop'] = $woocommerce_loop['column'] = '';

            if ($product_categories) {

                woocommerce_product_loop_start();

                foreach ($product_categories as $category) {

                    wc_get_template('content-product_cat.php', array(
                        'category' => $category
                    ));
                }

                woocommerce_product_loop_end();
            }

            woocommerce_reset_loop();

            $shortcode = ob_get_clean();

            die(pbtheme_mini_woo_pagination_cat($cat_num, $page, $per_page, 'yes', $title) . '@@@!SPLIT!@@@' . '<div class="woocommerce">' . $shortcode . '</div>');
        }

    endif;
    add_action('wp_ajax_nopriv_pbtheme_ajaxload_send_woo_cat', 'pbtheme_ajaxload_send_woo_cat');
    add_action('wp_ajax_pbtheme_ajaxload_send_woo_cat', 'pbtheme_ajaxload_send_woo_cat');


    /**
     * Ajax Load Portfolio
     */
    if (!function_exists('pbtheme_ajaxload_send_portfolio')) :

        function pbtheme_ajaxload_send_portfolio() {
            $out = '';
            $post_counter = 0;
            $add_class = '';

            $query_string = $_POST['data'];
            $current_page = pbtheme_get_between($query_string, 'paged=', '&');
            $type = $_POST['type'];
            $page = $_POST['page'];
            $ajax = $_POST['ajax'];
            $category = $_POST['category'];
            $bot_margin = $_POST['margin'];
            $top_pagination = $_POST['top_pagination'];
            $top_align = $_POST['top_align'];
            $trans_effect = $_POST['trans_effect'];
            $data_cat = $_POST['data_cat'];
            $pagination = $_POST['pagination'];

            switch ($type) {
                case 'pbtheme_portfolio_1' :
                    $columns = 2;
                    $image_size = 'pbtheme-portfolio';
                    break;
                case 'pbtheme_portfolio_2' :
                    $columns = 3;
                    $image_size = 'pbtheme-portfolio';
                    break;
                case 'pbtheme_portfolio_3' :
                    $columns = 4;
                    $image_size = 'pbtheme-portfolio';
                    break;
                case 'pbtheme_portfolio_4' :
                    $columns = 5;
                    $image_size = 'pbtheme-portfolio';
                    break;
            }

            $query_string = str_replace('paged=' . $current_page . '&', 'paged=' . $page . '&', $query_string);
            $pbtheme_posts = new WP_Query($query_string);
            if ($pbtheme_posts->have_posts()) :

                $out .= "<div class='portfolio_content {$type}' data-string='{$query_string}' data-shortcode='{$bot_margin}|{$category}|{$top_pagination}|{$top_align}|{$trans_effect}|{$pagination}' data-columns='{$columns}' style='margin-bottom:{$bot_margin}px'>";

                if ($top_pagination == 'yes') {
                    $separate_categories = explode(',', $category);
                    $out .= '<div class="div_top_nav_wrap"><ul class="pbtheme_container div_top_nav_cat a-inherit pbtheme_anim_' . $trans_effect . ' text-' . $top_align . '">';
                    $sms_cnt = 0;
                    $selected_class = '';
                    if (count($separate_categories) > 1 && !array_search('-1', $separate_categories, true)) {
                        if ($data_cat == '-1') {
                            $selected_class = ' class="div_ajax_port_selected"';
                        }
                        $out .= sprintf('<li%3$s><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', __('All', 'pbtheme'), '-1', $selected_class);
                    }
                    foreach ($separate_categories as $category_loop) {
                        $sms_cnt++;
                        $selected_class = ( $data_cat == $category_loop ? ' class="div_ajax_port_selected"' : '' );
                        if ($category_loop == '-1') {
                            $category_unique = __('All', 'pbtheme');
                            $queried_category = 'all';
                            $out .= sprintf('<li%3$s><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', $category_unique, $category_loop, $selected_class);
                        } else {
                            $category_unique = get_cat_name($category_loop);
                            $queried_category = sanitize_title($category_unique);
                            $out .= sprintf('<li%3$s><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', $category_unique, $category_loop, $selected_class);
                        }
                    }
                    $out .= '</ul></div>';
                }

                $out .= '<div class="separate-portfolio-column anivia_row pbuilder_row text-left"><div>';
                while ($pbtheme_posts->have_posts()): $pbtheme_posts->the_post();

                    $out .= '<div class="div_ajax_col pbuilder_column pbuilder_column-1-' . $columns . '"><div class="headline_highlighted_column_block margin-bottom18">';
                    if (has_post_thumbnail()) {
                        $out .= sprintf('<div href="%1$s" class="margin-bottom12 pbtheme_hover">', get_permalink());
                        $out .= get_the_post_thumbnail(get_the_ID(), $image_size, array('class' => sprintf('block')));
                        $kklike = '';
                        if (in_array('kk-i-like-it/admin.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                            $kklike = '<div class="pbtheme_image_hover_button div_button_like inline-block">' . do_shortcode('[kklike_button]') . '</div>';
                        }
                        $out .= sprintf('<div class="pbtheme_hover_over"><div class="div_buttons"><a href="%3$s" class="div_hov_link"><i class="divicon-plus"></i></a><a href="%1$s" class="div_hov_zoom" rel="lightbox"><i class="divicon-search"></i></a>%4$s</div></div>', wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())), get_the_title(), get_permalink(), $kklike);
                        $out .= sprintf('</div>');
                    }
                    $out .= '<div class="portfolio_meta pbtheme_header_font">
				<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>
				<div class="category_meta inline-block a-inherit">' . get_the_category_list(', ') . '</div>
				</div>';
                    $out .= '</div></div>';
                endwhile;
                $out .= '</div></div>';
                $out .= '<div class="clearfix"></div>';

                if ($pagination == 'yes') {
                    $out .= pbtheme_pagination($pbtheme_posts->max_num_pages, $page, 3, $ajax);
                }
                $out .= '</div>';
            endif;

            die($out);
            exit;
        }

    endif;
    add_action('wp_ajax_nopriv_pbtheme_ajaxload_send_portfolio', 'pbtheme_ajaxload_send_portfolio');
    add_action('wp_ajax_pbtheme_ajaxload_send_portfolio', 'pbtheme_ajaxload_send_portfolio');


    /**
     * Ajax Load Portfolio Alt
     */
    if (!function_exists('pbtheme_ajaxload_send_portfolio_alt')) :

        function pbtheme_ajaxload_send_portfolio_alt() {
            $out = '';
            $post_counter = 0;
            $add_class = '';

            $query_string = $_POST['data'];
            $current_page = pbtheme_get_between($query_string, 'paged=', '&');
            $type = $_POST['type'];
            $page = $_POST['page'];
            $ajax = $_POST['ajax'];
            $category = $_POST['category'];
            $bot_margin = $_POST['margin'];
            $top_pagination = $_POST['top_pagination'];
            $top_align = $_POST['top_align'];
            $trans_effect = $_POST['trans_effect'];
            $data_cat = $_POST['data_cat'];
            $pagination = $_POST['pagination'];

            switch ($type) {
                case 'pbtheme_portslider_1' :
                    $columns = 2;
                    $image_size = 'pbtheme-portfolio';
                    break;
                case 'pbtheme_portslider_2' :
                    $columns = 3;
                    $image_size = 'pbtheme-portfolio';
                    break;
                case 'pbtheme_portslider_3' :
                    $columns = 4;
                    $image_size = 'pbtheme-portfolio';
                    break;
                case 'pbtheme_portslider_4' :
                    $columns = 5;
                    $image_size = 'pbtheme-portfolio';
                    break;
            }

            $query_string = str_replace('paged=' . $current_page . '&', 'paged=' . $page . '&', $query_string);
            $pbtheme_posts = new WP_Query($query_string);
            if ($pbtheme_posts->have_posts()) :

                $out .= "<div class='div_portfolio_slider {$type}' data-string='{$query_string}' data-shortcode='{$bot_margin}|{$category}|{$top_pagination}|{$top_align}|{$trans_effect}|{$pagination}' data-columns='{$columns}'>";

                if ($top_pagination == 'yes') {
                    $separate_categories = explode(',', $category);
                    $out .= '<div class="div_top_nav_wrap pbtheme_background_dark"><ul class="pbtheme_container div_top_nav_cat a-inherit pbtheme_anim_' . $trans_effect . ' text-' . $top_align . '">';
                    $sms_cnt = 0;
                    $selected_class = '';
                    if (count($separate_categories) > 1 && !array_search('-1', $separate_categories, true)) {
                        if ($data_cat == '-1') {
                            $selected_class = ' class="div_ajax_port_selected"';
                        }
                        $out .= sprintf('<li%3$s><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', __('All', 'pbtheme'), '-1', $selected_class);
                    }
                    foreach ($separate_categories as $category_loop) {
                        $sms_cnt++;
                        $selected_class = ( $data_cat == $category_loop ? ' class="div_ajax_port_selected"' : '' );
                        if ($category_loop == '-1') {
                            $category_unique = __('All', 'pbtheme');
                            $queried_category = 'all';
                            $out .= sprintf('<li%3$s><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', $category_unique, $category_loop, $selected_class);
                        } else {
                            $category_unique = get_cat_name($category_loop);
                            $queried_category = sanitize_title($category_unique);
                            $out .= sprintf('<li%3$s><a href="#" onclick="pbtheme_ajaxload_portfolio(jQuery(this)); return false;" data-cat="%2$s">%1$s</a></li>', $category_unique, $category_loop, $selected_class);
                        }
                    }
                    $out .= '</ul></div>';
                }

                $out .= "<div class='div_portfolio_slides'>";

                while ($pbtheme_posts->have_posts()): $pbtheme_posts->the_post();
                    $post_counter++;
                    $out .= '<div class="div_ajax_col portslider_column divslider-column-1-' . $columns . '"><div class="headline_highlighted_column_block">';
                    if (has_post_thumbnail()) {
                        $out .= sprintf('<div href="%1$s" class="pbtheme_hover">', get_permalink());
                        $out .= get_the_post_thumbnail(get_the_ID(), $image_size, array('class' => sprintf('block')));
                        $kklike = '';
                        if (in_array('kk-i-like-it/admin.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                            $kklike = '<div class="pbtheme_image_hover_button div_button_like inline-block">' . do_shortcode('[kklike_button]') . '</div>';
                        }
                        $out .= sprintf('<div class="pbtheme_hover_over"><div class="div_buttons"><a href="%3$s" class="div_hov_link"><i class="divicon-plus"></i></a><a href="%1$s" class="div_hov_zoom" rel="lightbox"><i class="divicon-search"></i></a>%4$s</div>', wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())), get_the_title(), get_permalink(), $kklike);
                        $out .= '<div class="portslider_meta pbtheme_header_font">
					<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>
					<div class="category_meta inline-block a-inherit">' . get_the_category_list(', ') . '</div>
					</div></div>';
                        $out .= sprintf('</div>');
                    }

                    $out .= '</div></div>';
                    if ($post_counter == $columns) {
                        $post_counter = 0;
                    } else {
                        $add_class = '';
                    }
                endwhile;
                $out .= '<div class="clearfix"></div></div>';

                if ($pagination == 'yes') {
                    $out .= pbtheme_pagination($pbtheme_posts->max_num_pages, $page, 3, $ajax);
                }
                $out .= '</div></div>';

            endif;

            die($out);
            exit;
        }

    endif;
    add_action('wp_ajax_nopriv_pbtheme_ajaxload_send_portfolio_alt', 'pbtheme_ajaxload_send_portfolio_alt');
    add_action('wp_ajax_pbtheme_ajaxload_send_portfolio_alt', 'pbtheme_ajaxload_send_portfolio_alt');

    /**
     * Product Cat extended
     */
    function product_category_extended($atts, $content = null) {
        extract(shortcode_atts(array(
            'args' => ''
                        ), $atts));

        global $woocommerce;

        if (empty($args))
            return;

        ob_start();

        $products = new WP_Query($args);

        if ($products->have_posts()) :
            ?>

            <?php woocommerce_product_loop_start(); ?>

            <?php while ($products->have_posts()) : $products->the_post(); ?>


                <?php woocommerce_get_template_part('content', 'product'); ?>

            <?php endwhile; // end of the loop.  ?>

            <?php woocommerce_product_loop_end(); ?>

        <?php
        endif;

        wp_reset_postdata();

        return '<div class="woocommerce">' . ob_get_clean() . '</div>';
    }

    add_shortcode('product_category_extended', 'product_category_extended');


    /**
     * Ajax Infinite Send
     */
    if (!function_exists('pbtheme_ajaxinfinite_send')) :

        function pbtheme_ajaxinfinite_send() {

            global $pbtheme_data;
            $out = '';
            $post_counter = 0;
            $query_string = $_POST['data'];
            $current_page = pbtheme_get_between($query_string, 'paged=', '&');
            $page = $_POST['page'];
            $new_page = $_POST['page'] + 1;
            $query_string = str_replace('paged=' . $current_page . '&', 'paged=' . $new_page . '&', $query_string);
            $colored = $_POST['colored'];

            if ($colored == 'false') {
                $tranparent = '';
            } else {
                $transparent = ' not-transparent pbtheme_pale_border';
            }

            $pbtheme_posts = new WP_Query($query_string);
            if ($pbtheme_posts->have_posts()) :

                while ($pbtheme_posts->have_posts()): $pbtheme_posts->the_post();

                    $post_counter++;
                    $category = get_the_category();
                    if ($category[0]) {
                        $cat = $category[0]->term_id;
                    }
                    $prod_extras = get_option(Category_Extras);
                    if (isset($prod_extras[$cat]['catcolor']))
                        $category_color = $prod_extras[$cat]['catcolor'];
                    else
                        $category_color = '#888888';

                    $rgb_category_color = implode(',', pbtheme_hex2rgb($category_color));

                    if (has_post_thumbnail())
                        $magazine = array('class' => 'magazine_image_column pbtheme_ultimate_fix magazine_image_column_item fullmaxwidth relative float_left margin-bottom20', 'style' => 'style="background:rgb(' . $rgb_category_color . ');"', 'style-no-image' => '');
                    else
                        $magazine = array('class' => 'magazine_image_column pbtheme_ultimate_fix magazine_no_image_column_item fullwidth float_left margin-bottom20', 'style' => 'style="background:rgba(' . $rgb_category_color . ',0.85);"', 'style-no-image' => 'style="background:rgb(' . $rgb_category_color . ')"');

                    $out .= sprintf('<li><div class="%1$s" %2$s>', $magazine['class'], $magazine['style-no-image']);
                    $out .= pbtheme_get_featarea('large');
                    $tags = get_the_tags();
                    $mag_tags = '';
                    if ($tags) {
                        shuffle($tags);
                        foreach ($tags as $tag) {
                            $tag_link = get_tag_link($tag->term_id);

                            $mag_tags .= "<div class='mag_tag'><a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>";
                            $mag_tags .= "{$tag->name}</a><span class='tag_block' {$magazine["style"]}></span></div>";
                        }
                    }
                    $timecode = '<div class="time_code pbtheme_header_font"><div class="float_left a-inherit">' . get_the_date() . ' ' . __('in', 'pbtheme') . ' ' . get_the_category_list(', ') . '</div><div class="clearfix"></div></div>';
                    $out .= sprintf('<div class="category_tag">%2$s</div><!-- category_tag -->
			<div class="hover_effect_wrapper" %1$s><div class="hover_transparent%8$s">%7$s<h4 class="margin-bottom6 a-inherit"><a href="%3$s" rel="bookmark" title="%4$s">%6$s%4$s</a></h4><div>%5$s</div></div><!-- hover_effect_wrapper --><div class="clearfix"></div></div></div><!-- magazine_image_column_item --></li>', $magazine['style'], $mag_tags, get_permalink(), get_the_title(), pbtheme_string_limit_words(get_the_excerpt(), 196), '', $timecode, $transparent);
                    $out .= '--||--';

                endwhile;

            endif;

            die($out);
            exit;
        }

    endif;
    add_action('wp_ajax_nopriv_pbtheme_ajaxinfinite_send', 'pbtheme_ajaxinfinite_send');
    add_action('wp_ajax_pbtheme_ajaxinfinite_send', 'pbtheme_ajaxinfinite_send');


    /**
     * PBTheme Widgets Init
     */
// Social Bro widget
    include_once('widgets/socialbro/socialbro.php');

// Tweeter Widget
    class PBTheme_Twitter_Widget extends WP_Widget {

        function PBTheme_Twitter_Widget() {
            $widget_ops = array(
                'classname' => 'widget-pbtheme-twitter twitter_module',
                'description' => __('Show your twitter feeds', 'pbtheme')
            );
            parent::__construct('pbtheme_twitter', '+ PBTheme Twitter', $widget_ops);
        }

        function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            echo $before_widget;
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $user = empty($instance['user']) ? '' : apply_filters('widget_user', $instance['user']);
            $count = empty($instance['count']) ? '' : apply_filters('widget_count', $instance['count']);
            if (!empty($title)) {
                echo $before_title . $title . $after_title;
            }

            echo pbtheme_twitter_feed($user, $count);
            echo $after_widget;
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['user'] = strip_tags($new_instance['user']);
            $instance['count'] = strip_tags($new_instance['count']);
            return $instance;
        }

        function form($instance) {
            $instance = wp_parse_args(
                    (array) $instance, array(
                'title' => '',
                'user' => '',
                'count' => 5
                    ));
            $title = strip_tags($instance['title']);
            $user = strip_tags($instance['user']);
            $count = strip_tags($instance['count']);
            ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'pbtheme'); ?> :</label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
            <p><label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User', 'pbtheme'); ?> :</label>
                <input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo esc_attr($user); ?>" /></p>
            <p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count', 'pbtheme'); ?> :</label>
                <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($count); ?>" /></p>
        <?php
        }

    }

    add_action('widgets_init', create_function('', 'return register_widget("PBTheme_Twitter_Widget");'));

// Social Widget
    class PBTheme_Social_Widget extends WP_Widget {

        function PBTheme_Social_Widget() {
            $widget_ops = array(
                'classname' => 'widget-pbtheme-social square_social_feed margin-bottom20',
                'description' => __('Social subscribers', 'pbtheme')
            );
            parent::__construct('pbtheme_social', '+ PBTheme Social', $widget_ops);
        }

        function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            echo $before_widget;
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $facebook_id = empty($instance['facebook']) ? '' : apply_filters('widget_facebook', $instance['facebook']);
            $twitter_id = empty($instance['twitter']) ? '' : apply_filters('widget_twitter', $instance['twitter']);
            $list_id = empty($instance['list']) ? '' : apply_filters('widget_list', $instance['list']);
            if (!empty($title)) {
                echo $before_title . $title . $after_title;
            }
            echo '<div>';
            if ($facebook_id !== '') {
                if (!get_transient('pbtheme_facebook_users')) {
                    $facebook_curl_url = sprintf('http://graph.facebook.com/%1$s', $facebook_id);
                    $c = curl_init($facebook_curl_url);

                    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

                    $result = curl_exec($c);
                    curl_close($c);

                    $facebookData = json_decode($result);

                    $transient = sprintf('<div class="single_block_wrapper"><div class="single_block"><a href="%2$s" class="block fb margin-bottom6"></a><div class="border-box pbtheme_header_font div_dtext">%1$s<div>%3$s</div></div></div></div>', $facebookData->likes, $facebookData->link, __('FANS', 'pbtheme'));
                    set_transient('pbtheme_facebook_users', $transient, 1800);
                    echo $transient;
                } else {
                    echo get_transient('pbtheme_facebook_users');
                }
            }
            if ($twitter_id !== '') {
                if (!get_transient('pbtheme_twitter_users')) {
                    global $pbtheme_data;
                    $consumerkey = $pbtheme_data['twitter_ck'];
                    $consumersecret = $pbtheme_data['twitter_cs'];
                    $accesstoken = $pbtheme_data['twitter_at'];
                    $accesstokensecret = $pbtheme_data['twitter_ats'];

                    $twitter_url = 'http://www.twitter.com/' . $twitter_id;

                    $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
                    $data_connection = $connection->get("https://api.twitter.com/1.1/users/show.json?screen_name=" . $twitter_id . "&include_entities=true");
                    $twitterData = json_decode(json_encode($data_connection));

                    $transient = sprintf('<div class="single_block_wrapper"><div class="single_block"><a href="%2$s" class="block tw margin-bottom6"></a><div class="border-box pbtheme_header_font div_dtext">%1$s<div>%3$s</div></div></div></div>', $twitterData->followers_count, $twitter_url, __('FOLLOWERS', 'pbtheme'));
                    set_transient('pbtheme_twitter_users', $transient, 1800);
                    echo $transient;
                } else {
                    echo get_transient('pbtheme_twitter_users');
                }

                if (in_array('sendpress/sendpress.php', apply_filters('active_plugins', get_option('active_plugins'))) && $list_id !== '') {
                    global $wpdb;
                    $table = SendPress_Data::list_subcribers_table();

                    $query = "SELECT COUNT(*) FROM " . SendPress_Data::subscriber_table() . " as t1," . SendPress_Data::list_subcribers_table() . " as t2," . SendPress_Data::subscriber_status_table() . " as t3";
                    $query .= " WHERE (t1.subscriberID = t2.subscriberID) AND (t2.status = t3.statusid ) AND(t2.status = %d) AND (t2.listID =  %d)";

                    $count = $wpdb->prepare($query, 2, $list_id);

                    $type = 'get_var';
                    $result = $wpdb->$type($count);

                    printf('<div class="single_block_wrapper"><div class="single_block last"><a href="%2$s" class="block rss margin-bottom6"></a><div class="border-box pbtheme_header_font div_dtext">%1$s<div>%3$s</div></div></div></div>', $result, home_url(), __('SUBSCRIBERS', 'pbtheme'));
                }
            }
            echo '<div class="clearfix"></div>';
            echo '</div>';

            echo $after_widget;
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['facebook'] = strip_tags($new_instance['facebook']);
            $instance['twitter'] = strip_tags($new_instance['twitter']);
            $instance['list'] = strip_tags($new_instance['list']);
            return $instance;
        }

        function form($instance) {
            $instance = wp_parse_args(
                    (array) $instance, array(
                'title' => '',
                'facebook' => '',
                'twitter' => '',
                'list' => ''
                    ));
            $title = strip_tags($instance['title']);
            $facebook = strip_tags($instance['facebook']);
            $twitter = strip_tags($instance['twitter']);
            $list = strip_tags($instance['list']);
            ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'pbtheme'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
            <p><label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook Page ID', 'pbtheme'); ?> : </label>
                <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo esc_attr($facebook); ?>" /></p>
            <p><label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter ID', 'pbtheme'); ?> :</label>
                <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo esc_attr($twitter); ?>" /></p>
            <p><label for="<?php echo $this->get_field_id('list'); ?>"><?php _e('SendPress list ID', 'pbtheme'); ?> :</label>
                <input class="widefat" id="<?php echo $this->get_field_id('list'); ?>" name="<?php echo $this->get_field_name('list'); ?>" type="text" value="<?php echo esc_attr($list); ?>" /></p>
        <?php
        }

    }

    add_action('widgets_init', create_function('', 'return register_widget("PBTheme_Social_Widget");'));

// Post widgets
    class PBTheme_Categories_Widget extends WP_Widget {

        function PBTheme_Categories_Widget() {
            $widget_ops = array(
                'classname' => 'widget-pbtheme-cat',
                'description' => __('Show category posts', 'pbtheme'));
            parent::__construct('pbtheme_category', '+ PBTheme Category Posts', $widget_ops);
        }

        function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            echo $before_widget;
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            if (isset($instance['order'])) : $order = $instance['order'];
            else : $order = 'date';
            endif;
            if (isset($instance['category'])) : $category = $instance['category'];
            else : $category = '-1';
            endif;
            if (isset($instance['number'])) : $number = $instance['number'];
            else : $number = '5';
            endif;
            if (!empty($title)) {
                echo $before_title . $title . $after_title;
            }
            echo '<ul>';

            $out = '';
            $query_string = array(
                'post_type' => 'post',
                'posts_per_page' => $number,
                'ignore_sticky_posts' => true,
                'orderby' => $order
            );
            if ($category !== "-1") {
                $query_string = $query_string + array(
                    'cat' => $category
                );
            }

            $pbtheme_posts = new WP_Query($query_string);
            if ($pbtheme_posts->have_posts()) :
                while ($pbtheme_posts->have_posts()): $pbtheme_posts->the_post();
                    $out .= '<li>';
                    if (has_post_thumbnail()) {
                        $out .= '<a class="float_left margin-right12 smaller_image pbtheme_hover" href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'pbtheme-square') . '</a>';
                    }
                    $timecode = get_the_date();
                    $out .= '<div class="posts_meta margin-bottom6 pbtheme_header_font overflow_ellipsis div_dtext"><div class="category_meta inline-block a-inherit">' . get_the_category_list(', ') . '</div><div class="date_meta inline-block">' . $timecode . '</div></div>';
                    $out .= '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
                    $out .= '<div class="clearfix"></div>';

                    $out .= '</li>';
                endwhile;
            endif;
            wp_reset_query();
            echo $out;

            echo '</ul>';
            echo $after_widget;
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['order'] = strip_tags($new_instance['order']);
            $instance['category'] = strip_tags($new_instance['category']);
            $instance['number'] = strip_tags($new_instance['number']);

            return $instance;
        }

        function form($instance) {
            $instance = wp_parse_args(
                    (array) $instance, array(
                'title' => '',
                'order' => 'date',
                'category' => '34',
                'number' => '5'
                    ));
            $title = strip_tags($instance['title']);
            $number = strip_tags($instance['number']);
            ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'pbtheme'); ?> : <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order', 'pbtheme'); ?> : <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
                        <option value="date" <?php $selected = ( $instance['order'] === 'date') ? 'selected = "selected"' : '';
        echo $selected; ?>><?php _e('Recent', 'pbtheme'); ?></option>
                        <option value="comment_count" <?php $selected = ( $instance['order'] === 'comment_count') ? 'selected = "selected"' : '';
        echo $selected; ?>><?php _e('Popular', 'pbtheme'); ?></option>
                        <option value="rand" <?php $selected = ( $instance['order'] === 'rand') ? 'selected = "selected"' : '';
        echo $selected; ?>><?php _e('Random', 'pbtheme'); ?></option>
                        <option value="author" <?php $selected = ( $instance['order'] === 'author') ? 'selected = "selected"' : '';
        echo $selected; ?>><?php _e('Author', 'pbtheme'); ?></option>
                        <option value="title" <?php $selected = ( $instance['order'] === 'title') ? 'selected = "selected"' : '';
        echo $selected; ?>><?php _e('Title', 'pbtheme'); ?></option>
                    </select></label></p>
            <p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category', 'pbtheme');
        wp_dropdown_categories('show_option_none=All&show_count=1&orderby=name&echo=1&name=' . $this->get_field_name('category') . '&id=' . $this->get_field_id('category') . '&selected=' . $instance['category'] . ''); ?></label></p>
            <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to display', 'pbtheme'); ?> : <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" /></label></p>
            <?php
        }

    }

    add_action('widgets_init', create_function('', 'return register_widget("PBTheme_Categories_Widget");'));

// Post widgets
    class PBTheme_ThreeCat_Widget extends WP_Widget {

        function PBTheme_ThreeCat_Widget() {
            $widget_ops = array(
                'classname' => 'widget-pbtheme-catthree',
                'description' => __('Show category posts in tabs', 'pbtheme'));
            parent::__construct('pbtheme_catthree', '+ PBTheme Three Categories', $widget_ops);
        }

        function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            echo $before_widget;
			
			
			
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            if (isset($instance['number'])) : $number = $instance['number'];
            else : $number = '3';
            endif;

            for ($i = 0; $i <= 2; $i++) {
                if (isset($instance['order' . $i])) : $order[$i] = $instance['order' . $i];
                else : $order[$i] = 'date';
                endif;
                if (isset($instance['category' . $i])) : $category[$i] = $instance['category' . $i];
                else : $category[$i] = '-1';
                endif;
            }
			
			
            if (!empty($title)) {
                echo $before_title . $title . $after_title;
            }
			
            for ($i = 0; $i <= 2; $i++) {
                $out = do_shortcode(sprintf('[pbtheme_title link="#" type="h3" align="left" bot_margin="12"]%1$s <i class="fa fa-angle-down"></i>[/pbtheme_title]', ( $category[$i] !== "-1" ? get_cat_name($category[$i]) : __('All Categories', 'pbtheme'))));
                $out .= sprintf('<ul%1$s>', ( $i == 0 ? ' class="div_first_child"' : ''));
                $query_string = array(
                    'post_type' => 'post',
                    'posts_per_page' => $number,
                    'ignore_sticky_posts' => true,
                    'orderby' => $order[$i]
                );
                if ($category !== "-1") {
                    $query_string['cat']=$category[$i];
                }
				
				
                $pbtheme_posts = new WP_Query($query_string);
				
                if ($pbtheme_posts->have_posts()) :
                    while ($pbtheme_posts->have_posts()): $pbtheme_posts->the_post();
                        $out .= '<li>';
                        if (has_post_thumbnail()) {
                            $out .= '<a class="float_left margin-right12 smaller_image pbtheme_hover" href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'pbtheme-square') . '</a>';
                        }
                        $timecode = get_the_date();
                        $out .= '<div class="posts_meta margin-bottom6 pbtheme_header_font overflow_ellipsis div_dtext"><div class="category_meta inline-block a-inherit">' . get_the_category_list(', ') . '</div><div class="date_meta inline-block">' . $timecode . '</div></div>';
                        $out .= '<h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
                        $out .= '<div class="clearfix"></div>';
                        $out .= '</li>';
                    endwhile;
                endif;

                wp_reset_query();
                $out .= '</ul>';
                echo $out;
            }

            echo $after_widget;
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['number'] = strip_tags($new_instance['number']);

            for ($i = 0; $i <= 2; $i++) {
                $instance['order' . $i] = strip_tags($new_instance['order' . $i]);
                $instance['category' . $i] = strip_tags($new_instance['category' . $i]);
            }

            return $instance;
        }

        function form($instance) {
            $instance = wp_parse_args(
                    (array) $instance, array(
                'title' => '',
                'number' => '3',
                'order0' => 'date',
                'category0' => '-1',
                'order1' => 'date',
                'category1' => '-1',
                'order2' => 'date',
                'category2' => '-1',
                    ));
            $title = strip_tags($instance['title']);
            $number = $instance['number'];
            ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'pbtheme'); ?> : <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to display', 'pbtheme'); ?> : <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" /></label></p>
        <?php
        for ($i = 0; $i <= 2; $i++) {
            ?>
                <p><?php printf('%1$s %2$s', __('Tab', 'pbtheme'), $i + 1); ?><hr/></p>
            <p><label for="<?php echo $this->get_field_id('order' . $i); ?>"><?php _e('Order', 'pbtheme'); ?> : <select id="<?php echo $this->get_field_id('order' . $i); ?>" name="<?php echo $this->get_field_name('order' . $i); ?>">
                        <option value="date" <?php $selected = ( $instance['order' . $i] === 'date') ? 'selected = "selected"' : '';
            echo $selected; ?>><?php _e('Recent', 'pbtheme'); ?></option>
                        <option value="comment_count" <?php $selected = ( $instance['order' . $i] === 'comment_count') ? 'selected = "selected"' : '';
            echo $selected; ?>><?php _e('Popular', 'pbtheme'); ?></option>
                        <option value="rand" <?php $selected = ( $instance['order' . $i] === 'rand') ? 'selected = "selected"' : '';
            echo $selected; ?>><?php _e('Random', 'pbtheme'); ?></option>
                        <option value="author" <?php $selected = ( $instance['order' . $i] === 'author') ? 'selected = "selected"' : '';
            echo $selected; ?>><?php _e('Author', 'pbtheme'); ?></option>
                        <option value="title" <?php $selected = ( $instance['order' . $i] === 'title') ? 'selected = "selected"' : '';
            echo $selected; ?>><?php _e('Title', 'pbtheme'); ?></option>
                    </select></label></p>
            <p><label for="<?php echo $this->get_field_id('category' . $i); ?>"><?php _e('Category', 'pbtheme'); ?> <?php wp_dropdown_categories('show_option_none=All&show_count=1&orderby=name&echo=1&name=' . $this->get_field_name('category' . $i) . '&id=' . $this->get_field_id('category' . $i) . '&selected=' . $instance['category' . $i] . ''); ?></label></p>
            <?php
        }
    }

}

add_action('widgets_init', create_function('', 'return register_widget("PBTheme_ThreeCat_Widget");'));

/**
 * Twitter Feed / OAuth
 */
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
    $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
    return $connection;
}

if (!function_exists('pbtheme_twitter_feed')) :

    function pbtheme_twitter_feed($user = 'twitter', $count = '5') {
        $transient_key = $user . "_twitter_" . $count;
        $cached = get_transient($transient_key);

        if (false !== $cached) {
            return $cached .= "\n" . '<!-- Returned from cache -->';
        }

        global $pbtheme_data;
        $output = '';
        $i = 1;

        $twitteruser = $user;
        $notweets = $count;

        $consumerkey = $pbtheme_data['twitter_ck'];
        $consumersecret = $pbtheme_data['twitter_cs'];
        $accesstoken = $pbtheme_data['twitter_at'];
        $accesstokensecret = $pbtheme_data['twitter_ats'];

        $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
        $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $twitteruser . "&count=" . $notweets);
        $data = json_decode(json_encode($tweets));
        if (is_array($data)) :
            $output .= '<ul class="tweets-list list_null">';
            while ($i <= $count) {
                if (isset($data[$i - 1])) {
                    $feed = $data[( $i - 1 )]->text;
                    $feed = str_pad($feed, 3, ' ', STR_PAD_LEFT);
                    $startat = stripos($feed, '@');
                    $numat = substr_count($feed, '@');
                    $numhash = substr_count($feed, '#');
                    $numhttp = substr_count($feed, 'http');
                    $feed = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $feed);
                    $feed = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $feed);
                    $feed = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $feed);
                    $feed = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $feed);
                    $output .= sprintf('
			<li class="relative"><a href="http://www.twitter.com/%3$s" title="%4$s"><i class="fa fa-twitter"></i></a><div class="tweet-post padding-left48">%1$s</div>%2$s</li>', $feed, pbtheme_time_ago(strtotime($data[($i - 1)]->created_at)), $user, __('Visit us on twitter.com', 'pbtheme'));
                }
                $i++;
            }
            $output .= '</ul>';
            set_transient($transient_key, $output, 1800);
            set_transient($transient_key . '_backup', $output);
            return $output;
        else :
            $cached = get_transient($transient_key . '_backup');
            if (false !== $cached) {
                return $cached .= "\n" . '<!-- Returned from backup cache -->';
            } else {
                return __('Twitter unaviable', 'pbtheme');
            }
        endif;
    }

endif;

/**
 * PBTheme Contact Form
 */
if (!function_exists('pbtheme_contact_form')) :

    function pbtheme_contact_form($users = '1', $margin = ' style="margin-bottom:20px"') {
        global $pbtheme_data, $pbtheme_contact_form_id;

        if (isset($pbtheme_data['contact']))
            $contact_options = $pbtheme_data['contact'];
        if (isset($pbtheme_contact_form_id) == false) {
            global $pbtheme_contact_form_id;
            $pbtheme_contact_form_id = 1;
        }
        if (isset($_POST['submitted-' . $pbtheme_contact_form_id])) {
            if (trim($_POST['contactName']) === '') {
                $nameError = '<small>' . __('Please enter your name', 'pbtheme') . '</small>';
                $hasError = true;
            } else {
                $name = trim($_POST['contactName']);
            }
            if (trim($_POST['contactEmail']) === '') {
                $emailError = '<small>' . __('Please enter your email address', 'pbtheme') . '</small>';
                $hasError = true;
            } else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['contactEmail']))) {
                $emailError = '<small>' . __('You entered an invalid email address', 'pbtheme') . '</small>';
                $hasError = true;
            } else {
                $email = trim($_POST['contactEmail']);
            }
            if (trim($_POST['contactWebsite']) !== '') {
                $website = trim($_POST['contactWebsite']);
            }
            if (trim($_POST['commentsText']) === '') {
                $commentError = '<small>' . __('Please enter a message', 'pbtheme') . '</small>';
                $hasError = true;
            } else {
                if (function_exists('stripslashes')) {
                    $comments = stripslashes(trim($_POST['commentsText']));
                } else {
                    $comments = trim($_POST['commentsText']);
                }
            }
            if (!isset($hasError)) {
                if ($_POST['contactEmailSend'] == 'main') : $emailTo = $pbtheme_data['main_contact']['email'];
                else : $emailTo = $contact_options[$_POST['contactEmailSend']]['email'];
                endif;
                $subject = get_bloginfo('name') . ' / From ' . $name;
                $body = "Name: $name \n\nEmail: $email \n\nWebsite: $website \n\nComments: $comments";
                $headers = 'From: ' . $name . ' <' . $emailTo . '>' . "\r\n" . 'Reply-To: ' . $email;
                wp_mail($emailTo, $subject, $body, $headers);
                $emailSent = true;
            }
        }
        $output = '';
        $contactName = '';
        $contactEmail = '';
        $contactWebsite = '';
        $commentsText = '';

        $output .= sprintf('<div class="contact_form_wrapper"%1$s>', $margin);
        if (isset($emailSent) && $emailSent == true) {
            if ($pbtheme_data['contactform_message'] == '') {
                $output .= '<div class="success"><div class="margin-bottom20"><img src="' . $pbtheme_data['logo'] . '"/></div><i class="fa fa-thumbs-up"></i> ' . __('Thanks, your email was sent successfully!', 'pbtheme') . '</div>';
            } else
                $output .= $pbtheme_data['contactform_message'];
        }
        else {

            $permlink = get_permalink();
            $output .= sprintf('<form action="%1$s" class="comment_form contact_form" method="post">', $permlink);

            $output .= '<div class="input_wrapper_select margin-bottom20"><div>';
            if (isset($hasError) || isset($captchaError)) {
                $output .= '<span class="error send block"><i class="fa fa-exclamation color-red"></i> ' . __('Sorry, an error occured', 'pbtheme') . '</span>';
            }
            $output .= '<select name="contactEmailSend" class="input_field_select block" >';
            if ($pbtheme_data['contact']) :
                $i = 1;
                $users_array = explode(',', $users);
                if (!is_array($users_array)) {
                    $users_array[] = $users;
                }
                foreach ($contact_options as $option) {
                    if (in_array($i, $users_array)) {
                        $output .= '<option value="' . $i . '">' . $option['name'] . '</option>';
                    }
                    $i++;
                }
            endif;
            $output .= '</select>';
            $output .= '</div></div>';

            if (isset($_POST['contactEmail']))
                $contactEmail = $_POST['contactEmail'];

            $output .= '<div class="input_wrapper">';
            if (isset($emailError)) {
                $output .= '<span class="error block"><i class="fa fa-exclamation color-red"></i> ' . $emailError . '</span>';
            }
            $output .= sprintf('<input type="text" name="contactEmail" class="input_field block margin-bottom20" value="%1$s" placeholder="%2$s"/>', $contactEmail, __('EMAIL ADDRESS', 'pbtheme'));

            $output .= '</div>';

            if (isset($_POST['contactName']))
                $contactName = $_POST['contactName'];

            $output .= '<div class="input_wrapper">';
            if (isset($nameError)) {
                $output .= '<span class="error block"><i class="fa fa-exclamation color-red"></i> ' . $nameError . '</span>';
            }
            $output .= sprintf('<input type="text" name="contactName" class="input_field block margin-bottom20" value="%1$s" placeholder="%2$s"/>', $contactName, __('NAME', 'pbtheme'));

            $output .= '</div>';

            if (isset($_POST['contactEmail']))
                $contactEmail = $_POST['contactEmail'];

            $output .= '<div class="input_wrapper">';
            if (isset($nameError)) {
                $output .= '<span class="error block"><i class="fa fa-blank"></i></span>';
            }
            $output .= sprintf('<input type="text" name="contactWebsite" class="input_field block margin-bottom20" value="%1$s" placeholder="%2$s"/>', $contactWebsite, __('WEBSITE', 'pbtheme'));
            $output .= '</div>';

            if (isset($_POST['commentsText'])) {
                if (function_exists('stripslashes')) {
                    $commentsText = stripslashes($_POST['commentsText']);
                } else {
                    $commentsText = $_POST['commentsText'];
                }
            }

            $output .= '<textarea name="commentsText" class="textarea_field block margin-bottom20"  placeholder="' . __('MESSAGE GOES HERE (MAX 300 CHARS)', 'pbtheme') . '">' . $commentsText . '</textarea>';
            if (isset($commentError)) {
                $output .= '<span class="error block"><i class="fa fa-exclamation color-red"></i> ' . $commentError . '</span>';
            }

            $output .= '<input type="hidden" name="submitted-' . $pbtheme_contact_form_id . '" value="true" /><input class="pbtheme_button block bg_color_default bg_color_main_hover color_white float_right" type="submit" value="' . __('Send Email', 'pbtheme') . '" />';
            $output .= '<div class="clearfix"></div>';
            $output .= '</form>';
        }
        $output .= '</div>';
        $pbtheme_contact_form_id++;
        return $output;
    }

endif;


/**
 * Breadcrumbs
 */
if (!function_exists('pbtheme_breadcrumbs')) :

    function pbtheme_breadcrumbs() {
        if (is_front_page())
            return;
        $showOnHome = 0;
        $delimiter = '/';
        $homeLink = home_url('/');
        $home = __('Home', 'pbtheme');
        $showCurrent = 1;
        $before = '<h1 class="div-breadcrumb-current">';
        $after = '</h1 >';
        global $post, $pbtheme_data;
        $breadcrumbs_line = $pbtheme_data['breadcrumbs_line'];
        if (DIVWP_BBPRESS === true && is_bbpress()) {
            $delimiter = '';
        }

        $blog_string = __('Article', 'pbtheme');

        echo '<div class="div_breadcrumbs pbtheme_header_font"><div class="pbtheme_container a-inherit">' . $breadcrumbs_line . '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if (function_exists('is_bbpress') && is_bbpress()) {
            $bbpress_crumbfix = array(
                'before' => '',
                'after' => '',
                'sep' => is_rtl() ? __('\\', 'bbpress') : __('/', 'bbpress'),
                'pad_sep' => 1,
                'sep_before' => '<span class="bbp-breadcrumb-sep">',
                'sep_after' => '</span>',
                'crumb_before' => '',
                'crumb_after' => '',
                'include_home' => false,
                'home_text' => '',
                'include_root' => true,
                'root_text' => $pbtheme_data['bbpress_forum'],
                'include_current' => true,
                'current_text' => get_the_title(),
                'current_before' => '<h1 class="div-breadcrumb-current">',
                'current_after' => '</h1>',
            );
            echo bbp_get_breadcrumb($bbpress_crumbfix);
        } elseif (DIVWP_WOOCOMMERCE === true && is_product()) {
            echo '<a href="' . get_permalink(woocommerce_get_page_id('shop')) . '">' . __('Shop', 'pbtheme') . '</a> ' . $delimiter . ' ' . $before . get_the_title() . $after;
        } elseif (DIVWP_WOOCOMMERCE === true && is_shop()) {
            $_name = woocommerce_get_page_id('shop') ? get_the_title(woocommerce_get_page_id('shop')) : '';
            echo $before . $_name . $after;
        } elseif (DIVWP_WOOCOMMERCE === true && ( is_product_category() or is_product_tag() )) {
            $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            echo $before . $current_term->name . $after;
        } elseif (is_singular()) {
            if (!$post->post_parent) {
                if ($post->post_type == 'post') {
                    $category = get_the_category();
                    $blog_string = '<a href="' . get_category_link($category[0]->term_id) . '">' . $category[0]->cat_name . '</a>';
                    echo $blog_string . ' ' . $delimiter . ' ';
                }
                if ($showCurrent == 1)
                    echo $before . get_the_title() . $after;
            } elseif ($post->post_parent) {
                $parent_id = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs) - 1)
                        echo ' ' . $delimiter . ' ';
                }
                if ($showCurrent == 1)
                    echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        }
        elseif (is_category() OR is_tag()) {
            echo __('Archives', 'pbtheme') . ' ' . $delimiter . ' ' . $before . single_cat_title('', false) . $after;
        } elseif (is_month()) {
            echo __('Month', 'pbtheme') . ' ' . $delimiter . ' ' . $before . get_the_date('F') . $after;
        } elseif (is_year()) {
            echo __('Year', 'pbtheme') . ' ' . $delimiter . ' ' . $before . get_the_date('Y') . $after;
        } elseif (is_date()) {
            echo __('Day', 'pbtheme') . ' ' . $delimiter . ' ' . $before . get_the_date('l') . $after;
        } elseif (is_search()) {
            global $wp_query;
            $number_of_posts = $wp_query->found_posts;
            printf('%1$s %4$s %5$s %2$s %3$s', __('Search', 'pbtheme'), $delimiter, get_search_query(), $number_of_posts, __('posts found', 'pbtheme'));
        } elseif (is_single()) {
            if (!$post->post_parent) {
                if ($post->post_type == 'post')
                    echo $blog_string . ' ' . $delimiter . ' ';
                if ($showCurrent == 1)
                    echo $before . get_the_title() . $after;
            } elseif ($post->post_parent) {
                $parent_id = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs) - 1)
                        echo ' ' . $delimiter . ' ';
                }
                if ($showCurrent == 1)
                    echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        }
        elseif (is_404()) {
            echo $before . __('404 Page', 'pbtheme') . $after;
        } elseif (is_page()) {
            echo $before . get_the_title() . $after;
        } else {
            echo $before . $blog_string . $after;
        }
        echo '</div></div>';
    }

endif;

/**
 * Posts metaboxes
 */
if (!function_exists('pbtheme_page_meta_boxes_setup')) :

    function pbtheme_page_meta_boxes_setup() {
        add_action('add_meta_boxes', 'pbtheme_add_page_meta_boxes');
        add_action('save_post', 'pbtheme_save_page_meta_boxes', 10, 2);
    }

endif;
add_action('load-post.php', 'pbtheme_page_meta_boxes_setup');
add_action('load-post-new.php', 'pbtheme_page_meta_boxes_setup');

if (!function_exists('pbtheme_add_page_meta_boxes')) :

    function pbtheme_add_page_meta_boxes() {
        add_meta_box(
                'pbtheme-revolution', esc_html__('PBTheme Full Width Revolution Slider', 'pbtheme'), 'pbtheme_revolution', 'page', 'normal', 'high'
        );

        add_meta_box(
                'pbtheme-page-options', esc_html__('PBTheme Page Options', 'pbtheme'), 'pbtheme_page_options', 'page', 'side', 'default'
        );

        add_meta_box(
                'pbtheme-post-options', esc_html__('PBTheme Post Options', 'pbtheme'), 'pbtheme_post_options', 'post', 'side', 'default'
        );

        add_meta_box(
                'pbtheme-post-type', __('PBTheme Post Type Settings', 'pbtheme'), 'pbtheme_post_type', 'post', 'side', 'default'
        );
    }

endif;

if (!function_exists('pbtheme_post_type')) :

    function pbtheme_post_type($object, $box) {
        ?>
        <p>
        <h4><?php _e('Video', 'pbtheme'); ?></h4>
        <label for="pbtheme-video-override"><?php _e("Set featured area video embed (MP4)", 'pbtheme'); ?></label>
        <br />
        <textarea class="widefat" type="text" name="pbtheme-video-override" id="pbtheme-video-override"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_video_override', true)); ?></textarea>
        </p>
        <p>
            <label for="pbtheme-video-override"><?php _e("Set featured area video embed (OGG)", 'pbtheme'); ?></label>
            <br />
            <textarea class="widefat" type="text" name="pbtheme-video-override-ogg" id="pbtheme-video-override-ogg"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_video_override_ogg', true)); ?></textarea>
        </p>
        <p>
            <label for="pbtheme-video-override"><?php _e("Set featured area video embed from site", 'pbtheme'); ?></label>
            <br />
            <textarea class="widefat" type="text" name="pbtheme-video-override-site" id="pbtheme-video-override-site"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_video_override_site', true)); ?></textarea>
        </p>
        <p>
        <h4><?php _e('Audio', 'pbtheme'); ?></h4>
        <label for="pbtheme-audio-override"><?php _e("Set featured area audio embed", 'pbtheme'); ?></label>
        <br />
        <textarea class="widefat" type="text" name="pbtheme-audio-override" id="pbtheme-audio-override"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_audio_override', true)); ?></textarea>
        </p>
        <p>
            <label for="pbtheme-audio-override"><?php _e("Set featured area audio embed (ogg)", 'pbtheme'); ?></label>
            <br />
            <textarea class="widefat" type="text" name="pbtheme-audio-override-ogg" id="pbtheme-audio-override-ogg"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_audio_override_ogg', true)); ?></textarea>
        </p>
        <p>
            <label for="pbtheme-audio-override"><?php _e("Set featured area audio embed from site", 'pbtheme'); ?></label>
            <br />
            <textarea class="widefat" type="text" name="pbtheme-audio-override-site" id="pbtheme-audio-override-site"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_audio_override_site', true)); ?></textarea>
        </p>
        <p>
        <h4><?php _e('Gallery', 'pbtheme'); ?></h4>
        <label for="pbtheme-gallery-override"><?php _e("Override default post gallery", 'pbtheme'); ?></label>
        <br />
        <textarea class="widefat" type="text" name="pbtheme-gallery-override" id="pbtheme-gallery-override"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_gallery_override', true)); ?></textarea>
        </p>
        <p>
        <h4><?php _e('Link', 'pbtheme'); ?></h4>
        <label for="pbtheme-link-override"><?php _e("Set featured area link", 'pbtheme'); ?></label>
        <br />
        <textarea class="widefat" type="text" name="pbtheme-link-override" id="pbtheme-link-override"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_link_override', true)); ?></textarea>
        </p>
    <?php
    }

endif;

if (!function_exists('pbtheme_revolution')) :

    function pbtheme_revolution($object, $box) {
        ?>
            <?php wp_nonce_field(basename(__FILE__), 'pbtheme_nonce'); ?>
            <?php
            //Revsliders

            if (in_array('revslider/revslider.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                global $wpdb;
                $revsliders = array('none');
                $current = get_post_meta($object->ID, 'pbtheme_revolution', true);
                if ($current == '') {
                    $current = 'none';
                }
                $get_sliders = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'revslider_sliders');
                if ($get_sliders) {
                    foreach ($get_sliders as $slider) {
                        $revsliders[$slider->alias] = $slider->alias;
                    }
                } else {
                    $revsliders = array('none' => 'none');
                }
            } else {
                $revsliders = array('none' => 'none');
            }
            ?>
        <p>
            <label for="pbtheme-revolution"><?php _e("Select Revolution Slider template to use one this page. This slider will be shown just bellow main navigation in full width.", 'pbtheme'); ?></label>
            <br /><br />
            <select name="pbtheme-revolution" id="pbtheme-revolution">
        <?php
        foreach ($revsliders as $slider) :
            printf('<option value="%1$s" %2$s>%1$s</option>', $slider, ( ( $slider == $current ) ? 'selected' : ''));
        endforeach;
        ?>
            </select>
        </p>
    <?php }

    function pbtheme_page_options($object, $box) {
        ?>
        <?php wp_nonce_field(basename(__FILE__), 'pbtheme_nonce'); ?>
        <p>
            <label for="pbtheme_page_width"><?php _e("Page Width"); ?></label><br />
            <input type="text" class="widefat" name="pbtheme_page_width" id="pbtheme_page_width" value="<?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_page_width', true)); ?>" /><br />
        <?php _e("Container width in px or %"); ?>
        </p>
        <p>
            <label for="pbtheme-padding"><input value="" type="checkbox" name="pbtheme-padding" id="pbtheme-padding" <?php if (1 == get_post_meta($object->ID, 'pbtheme_padding', true)) echo 'checked="checked"'; ?>> <?php _e("Remove page padding", 'pbtheme'); ?></label>
        </p>
        <p>
            <label for="pbtheme-breadcrumbs"><input value="" type="checkbox" name="pbtheme-breadcrumbs" id="pbtheme-breadcrumbs" <?php if (1 == get_post_meta($object->ID, 'pbtheme_breadcrumbs', true)) echo 'checked="checked"'; ?>> <?php _e("Hide breadcrumbs", 'pbtheme'); ?></label>
        </p>
        <p>
            <label for="pbtheme-hidemenu"><input value="" type="checkbox" name="pbtheme-hidemenu" id="pbtheme-hidemenu" <?php if (1 == get_post_meta($object->ID, 'pbtheme_hidemenu', true)) echo 'checked="checked"'; ?>> <?php _e("Hide menu", 'pbtheme'); ?></label>
        </p>
        <p>
            <label for="pbtheme-hidetopbar"><input value="" type="checkbox" name="pbtheme-hidetopbar" id="pbtheme-hidetopbar" <?php if (1 == get_post_meta($object->ID, 'pbtheme_hidetopbar', true)) echo 'checked="checked"'; ?>> <?php _e("Hide top bar", 'pbtheme'); ?></label>
        </p>
        <p>
            <label for="pbtheme-hidefooter"><input value="" type="checkbox" name="pbtheme-hidefooter" id="pbtheme-hidefooter" <?php if (1 == get_post_meta($object->ID, 'pbtheme_hidefooter', true)) echo 'checked="checked"'; ?>> <?php _e("Hide footer", 'pbtheme'); ?></label>
        </p>
        <p>
        <hr/>
        <?php _e('Override default page background', 'pbtheme'); ?>
        </p>
        <p>
            <label for="pbtheme-page-bg"><?php _e("Select background type", 'pbtheme'); ?> :</label>
        <?php
        $feat_areas = array(
            'none' => __('None', 'pbtheme'),
            'bgimage' => __('Image'),
            'videoembed' => __('Video Embed', 'pbtheme'),
            'html5video' => __('HTML5 Video', 'pbtheme'),
        );
        $current = get_post_meta($object->ID, 'pbtheme_page_bg', true);
        if ($current == '') {
            $current = 'none';
        }
        foreach ($feat_areas as $s => $v) :
            ?>
                <br />
                <input type="radio" name="pbtheme-page-bg" id="pbtheme-page-bg" value="<?php echo $s; ?>" <?php echo ( ( $s == $current ) ? 'checked' : '' ); ?>/> <?php echo $v; ?>
        <?php endforeach; ?>
        </p>
        <p>
            <label for="pbtheme-page-image"><?php _e("Enter image URL."); ?></label>
            <br />
            <textarea class="widefat" type="text" name="pbtheme-page-image" id="pbtheme-page-image"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_page_image', true)); ?></textarea><br />
            <a href="media-upload.php?post_id=0&amp;TB_iframe=1" id="upload-pbtheme-page-image" class="thickbox button upload-button-select" title="Add Media">Select Image</a>
        </p>
        <p>
            <label for="pbtheme-pagevideo-mp4"><?php _e("Enter video URL (MP4).", 'pbtheme'); ?></label>
            <br />
            <textarea class="widefat" type="text" name="pbtheme-pagevideo-mp4" id="pbtheme-pagevideo-mp4"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_pagevideo_mp4', true)); ?></textarea>
        </p>
        <p>
            <label for="pbtheme-pagevideo-ogv"><?php _e("Enter video URL (OGV).", 'pbtheme'); ?></label>
            <br />
            <textarea class="widefat" type="text" name="pbtheme-pagevideo-ogv" id="pbtheme-pagevideo-ogv"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_pagevideo_ogv', true)); ?></textarea>
        </p>
        <p>
            <label for="pbtheme-pagevideo-embed"><?php _e("Enter Youtube Video ID.", 'pbtheme'); ?></label>
            <br />
            <input class="widefat" type="text" name="pbtheme-pagevideo-embed" id="pbtheme-pagevideo-embed" value="<?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_pagevideo_embed', true)); ?>" / >
        </p>
        <?php
        $mute = get_post_meta($object->ID, 'pbtheme_pagevideo_embed_mute', true);
        ?>
        <p>
            <label for="pbtheme-pagevideo-embed-mute"><input value="" type="checkbox" name="pbtheme-pagevideo-embed-mute" id="pbtheme-pagevideo-embed-mute" <?php if (1 == $mute) echo 'checked="checked"'; ?>> <?php _e("Mute Youtube Video", 'pbtheme'); ?></label>
        </p>
        <?php
        $loop = get_post_meta($object->ID, 'pbtheme_pagevideo_embed_loop', true);
        ?>
        <p>
            <label for="pbtheme-pagevideo-embed-loop"><input value="" type="checkbox" name="pbtheme-pagevideo-embed-loop" id="pbtheme-pagevideo-embed-loop" <?php if (1 == $loop) echo 'checked="checked"'; ?>> <?php _e("Loop Youtube Video", 'pbtheme'); ?></label>
        </p>
        <?php
        $hd = get_post_meta($object->ID, 'pbtheme_pagevideo_embed_hd', true);
        ?>
        <p>
            <label for="pbtheme-pagevideo-embed-hd"><input value="" type="checkbox" name="pbtheme-pagevideo-embed-hd" id="pbtheme-pagevideo-embed-hd" <?php if (1 == $hd) echo 'checked="checked"'; ?>> <?php _e("HD Youtube Video", 'pbtheme'); ?></label>
        </p>


        <p>
        <hr/>
        <?php _e('Retargeting Pixel for Page', 'pbtheme'); ?>
        </p>
        <p>
            <label for="pbtheme-retargetpixel"><?php _e("Put your retargeting pixel code here", 'pbtheme'); ?></label>
            <br />
            <textarea class="widefat" type="text" name="pbtheme-retargetpixel" id="pbtheme-retargetpixel"><?php echo esc_attr(get_post_meta($object->ID, 'pbtheme_retargetpixel', true)); ?></textarea>
        </p>
        <script type="text/javascript">
            jQuery(document).ready(function(jQuery){
			  var _custom_media = true,
			  _orig_send_attachment = wp.media.editor.send.attachment;
			
			  jQuery("#upload-pbtheme-page-image").click(function(e) {
				var send_attachment_bkp = wp.media.editor.send.attachment;
				var button = jQuery(this);
				_custom_media = true;
				wp.media.editor.send.attachment = function(props, attachment){
				  if ( _custom_media ) {
					jQuery('#pbtheme-page-image').val(attachment.url);
				  } else {
					return _orig_send_attachment.apply( this, [props, attachment] );
				  };
				}
			
				wp.media.editor.open(button);
				return false;
			  });
			
			  jQuery(".add_media").on("click", function(){
				_custom_media = false;
			  });
			});		
			
			
        </script>
        <?php
    }

endif;

if (!function_exists('pbtheme_post_options')) :

    function pbtheme_post_options($object, $box) {
        global $pbtheme_data;
        ?>
        <?php wp_nonce_field(basename(__FILE__), 'pbtheme_nonce'); ?>
        <?php
        $hide_feat = get_post_meta($object->ID, 'pbtheme_hide_featarea', true);
        $hide_feat = ( $hide_feat == '' ? $pbtheme_data['pbtheme_hide_featarea'] : $hide_feat );
        ?>
        <p>
            <label for="pbtheme-hide-featarea"><input value="" type="checkbox" name="pbtheme-hide-featarea" id="pbtheme-hide-featarea" <?php if (1 == $hide_feat) echo 'checked="checked"'; ?>> <?php _e("Hide featured area", 'pbtheme'); ?></label>
        </p>
        <?php
        $hide_title = get_post_meta($object->ID, 'pbtheme_hide_title', true);
        $hide_title = ( $hide_title == '' ? $pbtheme_data['pbtheme_hide_title'] : $hide_title );
        ?>
        <p>
            <label for="pbtheme-hide-title"><input value="" type="checkbox" name="pbtheme-hide-title" id="pbtheme-hide-title" <?php if (1 == $hide_title) echo 'checked="checked"'; ?>> <?php _e("Hide title", 'pbtheme'); ?></label>
        </p>
        <?php
        $hide_footer = get_post_meta($object->ID, 'pbtheme_hidefooter', true);
        $hide_footer = ( $hide_footer == '' ? $pbtheme_data['pbtheme_hidefooter'] : $hide_footer );
        ?>
        <p>
            <label for="pbtheme-hidefooter"><input value="" type="checkbox" name="pbtheme-hidefooter" id="pbtheme-hidefooter" <?php if (1 == $hide_footer) echo 'checked="checked"'; ?>> <?php _e("Hide footer", 'pbtheme'); ?></label>
        </p>
        <?php
        $hide_meta = get_post_meta($object->ID, 'pbtheme_hide_meta', true);
        $hide_meta = ( $hide_meta == '' ? $pbtheme_data['pbtheme_hide_meta'] : $hide_meta );
        ?>
        <p>
            <label for="pbtheme-hide-meta"><input value="" type="checkbox" name="pbtheme-hide-meta" id="pbtheme-hide-meta" <?php if (1 == $hide_meta) echo 'checked="checked"'; ?>> <?php _e("Hide postmeta", 'pbtheme'); ?></label>
        </p>
        <?php
        $hide_tags = get_post_meta($object->ID, 'pbtheme_hide_tags', true);
        $hide_tags = ( $hide_tags == '' ? $pbtheme_data['pbtheme_hide_tags'] : $hide_tags );
        ?>
        <p>
            <label for="pbtheme-hide-tags"><input value="" type="checkbox" name="pbtheme-hide-tags" id="pbtheme-hide-tags" <?php if (1 == $hide_tags) echo 'checked="checked"'; ?>> <?php _e("Hide tags", 'pbtheme'); ?></label>
        </p>
        <?php
        $hide_author = get_post_meta($object->ID, 'pbtheme_hide_author', true);
        $hide_author = ( $hide_author == '' ? $pbtheme_data['pbtheme_hide_author'] : $hide_author );
        ?>
        <p>
            <label for="pbtheme-hide-author"><input value="" type="checkbox" name="pbtheme-hide-author" id="pbtheme-hide-author" <?php if (1 == $hide_author) echo 'checked="checked"'; ?>> <?php _e("Hide author", 'pbtheme'); ?></label>
        </p>
        <?php
        $hide_share = get_post_meta($object->ID, 'pbtheme_hide_share', true);
        $hide_share = ( $hide_share == '' ? $pbtheme_data['pbtheme_hide_share'] : $hide_share );
        ?>
        <p>
            <label for="pbtheme-hide-share"><input value="" type="checkbox" name="pbtheme-hide-share" id="pbtheme-hide-share" <?php if (1 == $hide_share) echo 'checked="checked"'; ?>> <?php _e("Hide social share", 'pbtheme'); ?></label>
        </p>
        <?php
        $hide_nav = get_post_meta($object->ID, 'pbtheme_hide_navigation', true);
        $hide_nav = ( $hide_nav == '' ? $pbtheme_data['pbtheme_hide_navigation'] : $hide_nav );
        ?>
        <p>
            <label for="pbtheme-hide-navigation"><input value="" type="checkbox" name="pbtheme-hide-navigation" id="pbtheme-hide-navigation" <?php if (1 == $hide_nav) echo 'checked="checked"'; ?>> <?php _e("Hide post navigation", 'pbtheme'); ?></label>
        </p>
        <p>
            <label for="pbtheme-padding"><input value="" type="checkbox" name="pbtheme-padding" id="pbtheme-padding" <?php if (1 == get_post_meta($object->ID, 'pbtheme_padding', true)) echo 'checked="checked"'; ?>> <?php _e("Remove page top padding", 'pbtheme'); ?></label>
        </p>
        <p>
            <label for="pbtheme-breadcrumbs"><input value="" type="checkbox" name="pbtheme-breadcrumbs" id="pbtheme-breadcrumbs" <?php if (1 == get_post_meta($object->ID, 'pbtheme_breadcrumbs', true)) echo 'checked="checked"'; ?>> <?php _e("Hide breadcrumbs", 'pbtheme'); ?></label>
        </p>
        <p>
            <label for="pbtheme-hide-sidebar"><input value="" type="checkbox" name="pbtheme-hide-sidebar" id="pbtheme-hide-sidebar" <?php if (1 == get_post_meta($object->ID, 'pbtheme_hide_sidebar', true)) echo 'checked="checked"'; ?>> <?php _e("Hide default single post sidebar", 'pbtheme'); ?></label>
        </p>

    <?php
    }

endif;

if (!function_exists('pbtheme_save_page_meta_boxes')) :

    function pbtheme_save_page_meta_boxes($post_id, $post) {
        if (!isset($_POST['pbtheme_nonce']) || !wp_verify_nonce($_POST['pbtheme_nonce'], basename(__FILE__)))
            return $post_id;

        $post_type = get_post_type_object($post->post_type);

        if (!current_user_can($post_type->cap->edit_post, $post_id))
            return $post_id;

        $new_meta_values = array();

        $new_meta_values[] = ( isset($_POST['pbtheme-revolution']) ? $_POST['pbtheme-revolution'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme_page_width']) ? $_POST['pbtheme_page_width'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-padding']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-breadcrumbs']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hidemenu']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hidetopbar']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hidefooter']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hide-featarea']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hide-title']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hidefooter']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hide-meta']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hide-tags']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hide-author']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hide-share']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hide-navigation']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-hide-sidebar']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-video-override']) ? $_POST['pbtheme-video-override'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-video-override-ogg']) ? $_POST['pbtheme-video-override-ogg'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-video-override-site']) ? $_POST['pbtheme-video-override-site'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-audio-override']) ? $_POST['pbtheme-audio-override'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-audio-override-ogg']) ? $_POST['pbtheme-audio-override-ogg'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-audio-override-site']) ? $_POST['pbtheme-audio-override-site'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-gallery-override']) ? $_POST['pbtheme-gallery-override'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-link-override']) ? $_POST['pbtheme-link-override'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-page-bg']) ? $_POST['pbtheme-page-bg'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-page-image']) ? $_POST['pbtheme-page-image'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-pagevideo-mp4']) ? $_POST['pbtheme-pagevideo-mp4'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-pagevideo-ogv']) ? $_POST['pbtheme-pagevideo-ogv'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-pagevideo-embed']) ? $_POST['pbtheme-pagevideo-embed'] : '' );
        $new_meta_values[] = ( isset($_POST['pbtheme-retargetpixel']) ? $_POST['pbtheme-retargetpixel'] : '' );

        $new_meta_values[] = ( isset($_POST['pbtheme-pagevideo-embed-mute']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-pagevideo-embed-loop']) ? 1 : 0 );
        $new_meta_values[] = ( isset($_POST['pbtheme-pagevideo-embed-hd']) ? 1 : 0 );


        $meta_keys = array();
        $meta_keys[] = 'pbtheme_revolution';
        $meta_keys[] = 'pbtheme_page_width';
        $meta_keys[] = 'pbtheme_padding';
        $meta_keys[] = 'pbtheme_breadcrumbs';
        $meta_keys[] = 'pbtheme_hidemenu';
        $meta_keys[] = 'pbtheme_hidetopbar';
        $meta_keys[] = 'pbtheme_hidefooter';
        $meta_keys[] = 'pbtheme_hide_featarea';
        $meta_keys[] = 'pbtheme_hide_title';
        $meta_keys[] = 'pbtheme_hidefooter';
        $meta_keys[] = 'pbtheme_hide_meta';
        $meta_keys[] = 'pbtheme_hide_tags';
        $meta_keys[] = 'pbtheme_hide_author';
        $meta_keys[] = 'pbtheme_hide_share';
        $meta_keys[] = 'pbtheme_hide_navigation';
        $meta_keys[] = 'pbtheme_hide_sidebar';
        $meta_keys[] = 'pbtheme_video_override';
        $meta_keys[] = 'pbtheme_video_override_ogg';
        $meta_keys[] = 'pbtheme_video_override_site';
        $meta_keys[] = 'pbtheme_audio_override';
        $meta_keys[] = 'pbtheme_audio_override_ogg';
        $meta_keys[] = 'pbtheme_audio_override_site';
        $meta_keys[] = 'pbtheme_gallery_override';
        $meta_keys[] = 'pbtheme_link_override';
        $meta_keys[] = 'pbtheme_page_bg';
        $meta_keys[] = 'pbtheme_page_image';
        $meta_keys[] = 'pbtheme_pagevideo_mp4';
        $meta_keys[] = 'pbtheme_pagevideo_ogv';
        $meta_keys[] = 'pbtheme_pagevideo_embed';
        $meta_keys[] = 'pbtheme_retargetpixel';
        $meta_keys[] = 'pbtheme_pagevideo_embed_mute';
        $meta_keys[] = 'pbtheme_pagevideo_embed_loop';
        $meta_keys[] = 'pbtheme_pagevideo_embed_hd';

        $meta_values = array();

        $i = 0;

        foreach ($meta_keys as $meta_key) {

            $meta_value = get_post_meta($post_id, $meta_key, true);

            if ($new_meta_values[$i] && '' == $meta_value)
                add_post_meta($post_id, $meta_key, $new_meta_values[$i], true);

            elseif ($new_meta_values[$i] && $new_meta_values[$i] != $meta_value)
                update_post_meta($post_id, $meta_key, $new_meta_values[$i]);

            elseif ('' == $new_meta_values[$i] && $meta_value)
                delete_post_meta($post_id, $meta_key, $meta_value);

            $i++;
        }
    }

endif;


if (DIVWP_FBUILDER === true) {

    /**
     * Profit Builder Activate
     */
    add_action('pbuilder_activate', 'pbtheme_factive');

    function pbtheme_factive() {
        global $pbuilder, $pbtheme_data;

        $options = array(
            'bottom_margin' => $pbtheme_data['fb_bmargin'],
            'high_rezolution_width' => $pbtheme_data['content_width'],
            'high_rezolution_margin' => $pbtheme_data['fb_hres_c'],
            'med_rezolution_width' => $pbtheme_data['fb_mres_w'],
            'med_rezolution_margin' => $data['fb_mres_c'],
            'med_rezolution_hide_sidebar' => ( $pbtheme_data['fb_mres_s'] == 1 ) ? 'true' : 'false',
            'low_rezolution_width' => $pbtheme_data['fb_lres_w'],
            'low_rezolution_margin' => $pbtheme_data['fb_lres_c'],
            'low_rezolution_hide_sidebar' => ( $pbtheme_data['fb_lres_s'] == 1 ) ? 'true' : 'false',
            'main_color' => $pbtheme_data['theme_color'],
            'light_main_color' => $pbtheme_data['theme_color'],
            'text_color' => $pbtheme_data['theme_color_textt'],
            'title_color' => $pbtheme_data['theme_color_dark'],
            'dark_back_color' => $pbtheme_data['theme_color_dark'],
            'light_back_color' => $pbtheme_data['theme_color_palee'],
            'dark_border_color' => $pbtheme_data['theme_color_dark'],
            'light_border_color' => $pbtheme_data['theme_color_palee']
        );

        $pbuilder->set_options($options);
    }

    /**
     * Profit Builder Activate
     */
    if (!function_exists('pbtheme_addgroups')) :

        function pbtheme_addgroups() {
            global $pbuilder;

            $curr = array(
                'id' => 'Link Lists',
                'label' => __('Link Lists Elements', 'pbtheme'),
                'img' => get_template_directory_uri() . '/images/pbuilder/list-shortcodes.png'
            );
            array_push($pbuilder->groups, $curr);

            if (DIVWP_WOOCOMMERCE === true) {
                $curr = array(
                    'id' => 'WooCommerce',
                    'label' => __('PBTheme WooCommerce', 'pbtheme'),
                    'img' => get_template_directory_uri() . '/images/pbuilder/woo-commerce.png'
                );
                array_push($pbuilder->groups, $curr);
            }
        }

    endif;
    add_action('pbuilder_groups', 'pbtheme_addgroups');
}

/**
 * Custom menu with widgets
 */
function pbtheme_mega_menu() {

    class Walker_Nav_Menu_Widgets extends Walker {

        var $tree_type = array('post_type', 'taxonomy', 'custom');
        var $db_fields = array('parent' => 'menu_item_parent', 'id' => 'db_id');

        function start_lvl(&$output, $depth = 0, $args = array()) {

            global $current_menu_id;
            if ($current_menu_id['sidebar'] !== 'none') {
                return;
            }
            if (( $depth > 1 && $current_menu_id['fullwidth'] == 'fullwidth')) {
                return;
            }

            $indent = str_repeat("\t", $depth);
            if ($depth == 0) {
                $output .= sprintf('%1$s<ul class="sub-menu navmenu_%2$s navmenu_columns_%3$s pbtheme_dark_border">', $indent, $current_menu_id['fullwidth'], $current_menu_id['columns']);
            } else {
                $output .= "\n$indent<ul class=\"sub-menu\">\n";
            }
        }

        function end_lvl(&$output, $depth = 0, $args = array()) {

            global $current_menu_id;
            if ($current_menu_id['sidebar'] !== 'none') {
                return;
            }
            if (( $depth > 1 && $current_menu_id['fullwidth'] == 'fullwidth')) {
                return;
            } elseif ($depth > 1 && $current_menu_id['fullwidth'] == 'fullwidth') {
                $output .= '<div class="clearfix"></div>';
            }

            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ul>\n";
        }

        function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {

            global $current_menu_id;
            if ($depth > 0 && $current_menu_id['sidebar'] !== 'none') {
                return;
            }
            if (( $depth > 2 && $current_menu_id['fullwidth'] == 'fullwidth')) {
                return;
            }

            if ($depth == 0) {
                $current_menu_id['id'] = $item->ID;
                $current_menu_id['fullwidth'] = ( isset($item->fullwidth) && $item->fullwidth !== '' ? $item->fullwidth : 'nofullwidth' );
                $current_menu_id['columns'] = ( isset($item->columns) && $item->columns !== '' ? $item->columns : 'none' );
                $current_menu_id['sidebar'] = ( isset($item->sidebar) && $item->sidebar !== '' ? $item->sidebar : 'none' );
            }

            $indent = ( $depth ) ? str_repeat("\t", $depth) : '';

            $class_names = $value = '';

            $classes = empty($item->classes) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            if ($depth == 0 && $current_menu_id['sidebar'] !== 'none') {
                $classes[] = 'has_sidebar';
                if (($key = array_search('menu-item', $classes)) !== false) {
                    unset($classes[$key]);
                }
            } else {
                $classes[] = 'hasno_sidebar a-inherit';
                if ($current_menu_id['fullwidth'] == 'fullwidth') {
                    if (($key = array_search('menu-item', $classes)) !== false) {
                        unset($classes[$key]);
                    }
                }
            }
            if ($depth == 0 && $current_menu_id['fullwidth'] == 'fullwidth') {
                $classes[] = 'is_fullwidth';
                $classes[] = 'is_columns-' . $current_menu_id['columns'];
            } else {
                $classes[] = 'hasno_fullwidth';
            }

            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

            $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
            $id = $id ? ' id="' . esc_attr($id) . '"' : '';

            $output .= $indent . '<li' . $id . $value . $class_names . '>';

            $atts = array();
            $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
            $atts['target'] = !empty($item->target) ? $item->target : '';
            $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
            $atts['href'] = !empty($item->url) ? $item->url : '';

            $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

            $attributes = '';
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $value = ( 'href' === $attr ) ? esc_url($value) : esc_attr($value);
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            if ($current_menu_id['sidebar'] == 'none') :
                $item_output = $args->before;
                $item_output .= '<a' . $attributes . '>';
                $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
                $item_output .= '</a>';
                $item_output .= $args->after;
            elseif ($depth == 0 && $current_menu_id['sidebar'] !== 'none') :
                ob_start();
                dynamic_sidebar($current_menu_id['sidebar']);
                $sidebar = ob_get_contents();
                ob_end_clean();
                $item_output = $args->before;
                $item_output .= '<a' . $attributes . '>';
                $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
                $item_output .= '</a>';
                $item_output .= sprintf('<ul class="sub-menu navmenu_sidebar navmenu_columns_%3$s navmenu_%2$s pbtheme_dark_border"><li class="sidebar_holder">%1$s</li></ul>', $sidebar, $current_menu_id['fullwidth'], $current_menu_id['columns']);
                $item_output .= $args->after;
            else :
                $item_output = $args->before;
                $item_output .= '<a' . $attributes . '>';
                $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
                $item_output .= '</a>';
                $item_output .= $args->after;
            endif;

            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }

        function end_el(&$output, $item, $depth = 0, $args = array()) {
            global $current_menu_id;

            if ($depth > 0 && $current_menu_id['sidebar'] !== 'none') {
                return;
            }
            if (( $depth > 2 && $current_menu_id['fullwidth'] == 'fullwidth')) {
                return;
            }

            $output .= "</li>\n";
        }

    }

    add_action('wp_update_nav_menu_item', 'custom_nav_update', 10, 3);

    function custom_nav_update($menu_id, $menu_item_db_id, $args) {

        if ($args['menu-item-parent-id'] == 0) {

            if (isset($_REQUEST['menu-item-fullwidth']) && is_array($_REQUEST['menu-item-fullwidth']) && array_key_exists($menu_item_db_id, $_REQUEST['menu-item-fullwidth'])) {
                $custom_value = $_REQUEST['menu-item-fullwidth'][$menu_item_db_id];
                update_post_meta($menu_item_db_id, '_menu_item_fullwidth', $custom_value);
            } else {
                update_post_meta($menu_item_db_id, '_menu_item_fullwidth', '0');
            }

            if (isset($_REQUEST['menu-item-columns']) && is_array($_REQUEST['menu-item-columns'])) {
                $custom_value = $_REQUEST['menu-item-columns'][$menu_item_db_id];
                update_post_meta($menu_item_db_id, '_menu_item_columns', $custom_value);
            }

            if (isset($_REQUEST['menu-item-sidebar']) && is_array($_REQUEST['menu-item-sidebar'])) {
                $custom_value = $_REQUEST['menu-item-sidebar'][$menu_item_db_id];
                update_post_meta($menu_item_db_id, '_menu_item_sidebar', $custom_value);
            }
        }
    }

    add_filter('wp_setup_nav_menu_item', 'custom_nav_item');

    function custom_nav_item($menu_item) {
        $menu_item->fullwidth = get_post_meta($menu_item->ID, '_menu_item_fullwidth', true);
        $menu_item->columns = get_post_meta($menu_item->ID, '_menu_item_columns', true);
        $menu_item->sidebar = get_post_meta($menu_item->ID, '_menu_item_sidebar', true);

        return $menu_item;
    }

    add_filter('wp_edit_nav_menu_walker', 'pbtheme_custom_nav_edit_walker');

    function pbtheme_custom_nav_edit_walker($walker) {
        return 'PBTheme_Walker_Nav_Menu_Edit_Custom';
    }

    class PBTheme_Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu {

        function start_lvl(&$output, $depth = 0, $args = array()) {

        }

        function end_lvl(&$output, $depth = 0, $args = array()) {

        }

        function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
            global $_wp_nav_menu_max_depth;
            $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

            ob_start();
            $item_id = esc_attr($item->ID);
            $removed_args = array(
                'action',
                'customlink-tab',
                'edit-menu-item',
                'menu-item',
                'page-tab',
                '_wpnonce',
            );

            $original_title = '';
            if ('taxonomy' == $item->type) {
                $original_title = get_term_field('name', $item->object_id, $item->object, 'raw');
                if (is_wp_error($original_title))
                    $original_title = false;
            } elseif ('post_type' == $item->type) {
                $original_object = get_post($item->object_id);
                $original_title = get_the_title($original_object->ID);
            }

            $classes = array(
                'menu-item menu-item-depth-' . $depth,
                'menu-item-' . esc_attr($item->object),
                'menu-item-edit-' . ( ( isset($_GET['edit-menu-item']) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
            );

            $title = $item->title;

            if (!empty($item->_invalid)) {
                $classes[] = 'menu-item-invalid';
                /* translators: %s: title of menu item which is invalid */
                $title = sprintf(__('%s (Invalid)', 'pbtheme'), $item->title);
            } elseif (isset($item->post_status) && 'draft' == $item->post_status) {
                $classes[] = 'pending';
                /* translators: %s: title of menu item in draft status */
                $title = sprintf(__('%s (Pending)', 'pbtheme'), $item->title);
            }

            $title = (!isset($item->label) || '' == $item->label ) ? $title : $item->label;

            $submenu_text = '';
            if (0 == $depth)
                $submenu_text = 'style="display: none;"';
            ?>
            <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes); ?>">
                <dl class="menu-item-bar">
                    <dt class="menu-item-handle">
                    <span class="item-title"><span class="menu-item-title"><?php echo esc_html($title); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e('sub item', 'pbtheme'); ?></span></span>
                    <span class="item-controls">
                        <span class="item-type"><?php echo esc_html($item->type_label); ?></span>
                        <span class="item-order hide-if-js">
                            <a href="<?php
            echo wp_nonce_url(
                    add_query_arg(
                            array(
                'action' => 'move-up-menu-item',
                'menu-item' => $item_id,
                            ), remove_query_arg($removed_args, admin_url('nav-menus.php'))
                    ), 'move-menu_item'
            );
            ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
                            |
                            <a href="<?php
                            echo wp_nonce_url(
                                    add_query_arg(
                                            array(
                                'action' => 'move-down-menu-item',
                                'menu-item' => $item_id,
                                            ), remove_query_arg($removed_args, admin_url('nav-menus.php'))
                                    ), 'move-menu_item'
                            );
                            ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
                        </span>
                        <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
                echo ( isset($_GET['edit-menu-item']) && $item_id == $_GET['edit-menu-item'] ) ? admin_url('nav-menus.php') : add_query_arg('edit-menu-item', $item_id, remove_query_arg($removed_args, admin_url('nav-menus.php#menu-item-settings-' . $item_id)));
                ?>"><?php _e('Edit Menu Item', 'pbtheme'); ?></a>
                    </span>
                    </dt>
                </dl>

                <div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
                                <?php if ('custom' == $item->type) : ?>
                        <p class="field-url description description-wide">
                            <label for="edit-menu-item-url-<?php echo $item_id; ?>">
                                    <?php _e('URL', 'pbtheme'); ?><br />
                                <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->url); ?>" />
                            </label>
                        </p>
            <?php endif; ?>
                    <p class="description description-thin">
                        <label for="edit-menu-item-title-<?php echo $item_id; ?>">
                            <?php _e('Navigation Label', 'pbtheme'); ?><br />
                            <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->title); ?>" />
                        </label>
                    </p>
                    <p class="description description-thin">
                        <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
                                <?php _e('Title Attribute', 'pbtheme'); ?><br />
                            <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->post_excerpt); ?>" />
                        </label>
                    </p>
                    <p class="field-link-target description">
                        <label for="edit-menu-item-target-<?php echo $item_id; ?>">
                            <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked($item->target, '_blank'); ?> />
                                <?php _e('Open link in a new window/tab', 'pbtheme'); ?>
                        </label>
                    </p>
                    <p class="field-css-classes description description-thin">
                        <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
                                <?php _e('CSS Classes (optional)', 'pbtheme'); ?><br />
                            <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr(implode(' ', $item->classes)); ?>" />
                        </label>
                    </p>
                    <p class="field-xfn description description-thin">
                        <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
            <?php _e('Link Relationship (XFN)', 'pbtheme'); ?><br />
                            <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->xfn); ?>" />
                        </label>
                    </p>
                    <p class="field-description description description-wide">
                        <label for="edit-menu-item-description-<?php echo $item_id; ?>">
            <?php _e('Description', 'pbtheme'); ?><br />
                            <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html($item->description); // textarea_escaped  ?></textarea>
                            <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', 'pbtheme'); ?></span>
                        </label>
                    </p>
                        <?php if ($depth == 0) : ?>

                        <p class="field-custom fullwidth_checkbox">
                            <label for="edit-menu-item-fullwidth-<?php echo $item_id; ?>">
                                <input type="checkbox" id="edit-menu-item-fullwidth-<?php echo $item_id; ?>" value="fullwidth" name="menu-item-fullwidth[<?php echo $item_id; ?>]"<?php checked($item->fullwidth, 'fullwidth'); ?> />
                            <?php _e('Full width menu container', 'pbtheme'); ?><br/><small><?php _e('Makes a menu full-width.', 'pbtheme'); ?></small>
                            </label>
                        </p>
                            <?php $current = esc_attr($item->columns); ?>
                        <p class="field-custom columns_number">
                            <label for="edit-menu-item-columns-<?php echo $item_id; ?>">
                            <?php _e('Columns', 'pbtheme'); ?>
                                <select name="menu-item-columns[<?php echo $item_id; ?>]" id="edit-menu-item-columns-<?php echo $item_id; ?>"  class="widefat code edit-menu-item-columns">
                            <?php
                            for ($i = 1; $i <= 5; $i++) :
                                printf('<option value="%1$s"%2$s>%1$s</option>', $i, ( ( $i == $current ) ? ' selected' : ''));
                            endfor;
                            ?>
                                </select>
                            </label>
                        </p>

                        <p class="field-custom sidebar">
                            <label for="edit-menu-item-sidebar-<?php echo $item_id; ?>">
                <?php _e('Sidebar', 'pbtheme'); ?><br />
                                <select name="menu-item-sidebar[<?php echo $item_id; ?>]" id="edit-menu-item-sidebar-<?php echo $item_id; ?>"  class="widefat code edit-menu-item-sidebar">
                <?php
                $current = esc_attr($item->sidebar);
                global $pbtheme_data;
                $sidebars = array();
                $sidebar = $pbtheme_data['sidebar'];

                $sidebars[] = 'none';
                foreach ($sidebar as $single_sidebar) {
                    $title = sanitize_title($single_sidebar['title']);
                    if ($title !== '')
                        $sidebars[] = $title;
                }

                foreach ($sidebars as $sidebar) :
                    printf('<option value="%1$s" %2$s>%1$s</option>', $sidebar, ( ( $sidebar == $current ) ? 'selected' : ''));
                endforeach;
                ?>
                                </select>

                            </label>
                        </p>
                <?php endif; ?>
                    <p class="field-move hide-if-no-js description description-wide">
                        <label>
                            <span><?php _e('Move', 'pbtheme'); ?></span>
                            <a href="#" class="menus-move-up"><?php _e('Up one', 'pbtheme'); ?></a>
                            <a href="#" class="menus-move-down"><?php _e('Down one', 'pbtheme'); ?></a>
                            <a href="#" class="menus-move-left"></a>
                            <a href="#" class="menus-move-right"></a>
                            <a href="#" class="menus-move-top"><?php _e('To the top', 'pbtheme'); ?></a>
                        </label>
                    </p>

                    <div class="menu-item-actions description-wide submitbox">
                <?php if ('custom' != $item->type && $original_title !== false) : ?>
                            <p class="link-to-original">
                    <?php printf(__('Original: %s', 'pbtheme'), '<a href="' . esc_attr($item->url) . '">' . esc_html($original_title) . '</a>'); ?>
                            </p>
                <?php endif; ?>
                        <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
                echo wp_nonce_url(
                        add_query_arg(
                                array(
                    'action' => 'delete-menu-item',
                    'menu-item' => $item_id,
                                ), admin_url('nav-menus.php')
                        ), 'delete-menu_item_' . $item_id
                );
                ?>"><?php _e('Remove', 'pbtheme'); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url(add_query_arg(array('edit-menu-item' => $item_id, 'cancel' => time()), admin_url('nav-menus.php')));
                ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel', 'pbtheme'); ?></a>
                    </div>

                    <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
                    <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->object_id); ?>" />
                    <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->object); ?>" />
                    <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->menu_item_parent); ?>" />
                    <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->menu_order); ?>" />
                    <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->type); ?>" />
                </div><!-- .menu-item-settings-->
                <ul class="menu-item-transport"></ul>
                <?php
                $output .= ob_get_clean();
            }

            function end_el(&$output, $item, $depth = 0, $args = array()) {
                $output .= "</li>\n";
            }

        }

        add_action("admin_print_styles", 'pbtheme_menus');

        function pbtheme_menus() {
            global $pagenow;

            if ($pagenow != 'nav-menus.php')
                return;
            wp_enqueue_style('nav-menu', get_template_directory_uri() . '/admin/assets/css/nav-menus.css');
        }

    }

    global $pbtheme_data;
    if (isset($pbtheme_data['disable_menu']) && $pbtheme_data['disable_menu'] == 0) {
        add_action("init", "pbtheme_mega_menu");
    }

// PBTheme Header Elements
    if (!function_exists('pbtheme_header_elements')) :

        function pbtheme_header_elements($position = '', $location = 'header') {
            global $pbtheme_data;
            foreach ($pbtheme_data[$location . '-top-' . $position]['enabled'] as $n => $t) :
                if ($n == 'placebo')
                    continue;
                printf('<div class="float_%2$s element-%1$s a-inherit pbtheme_pale_border">', $n, $position);
                switch ($n) :
                    case 'login-link':
                        printf('<a href="%1$s" title="%2$s">%2$s</a>', wp_login_url(get_permalink()), __('Login', 'pbtheme'));
                        break;
                    case 'language-bar':
                        require_once(ABSPATH . 'wp-admin/includes/admin.php');
                        $path = 'transposh-translation-filter-for-wordpress/transposh.php';
                        if(isset($pbtheme_data['transposh_enable']) && $pbtheme_data['transposh_enable'] == 1 && is_plugin_active(plugin_basename($path))){
                            $transposh = &$GLOBALS['my_transposh_plugin'];
                            $plugpath = parse_url($transposh->transposh_plugin_url, PHP_URL_PATH);
                            //include_once $transposh->transposh_plugin_dir."transposh.php";

                            $clean_page_url = $transposh->get_clean_url();
                            $page_url = '';
                            if (is_404()) {
                                $clean_page_url = transposh_utils::cleanup_url($transposh->home_url, $transposh->home_url, true);
                            }

                            $languages = $transposh->options->get_sorted_langs();
                            $langrecord = $languages[$transposh->target_language];
                            list ($langname, $language, $flag) = explode(',', $langrecord);
                            printf('<a href="#" class="language_selected"><span>%1$s</span>&nbsp;<img src="%2$s.png" /><i class="fa fa-angle-down"></i></a><ul>', $langname, $plugpath."/img/flags/".$flag);

                            foreach ($languages as $code => $langrecord) {
                                list ($langname, $language, $flag) = explode(',', $langrecord);

                                // Only send languages which are active
                                if ($transposh->options->is_active_language($code) || ($transposh->options->is_default_language($code))) {
                                    // now we alway do this... maybe cache this to APC/Memcache
                                    if ($transposh->options->enable_url_translate && !$transposh->options->is_default_language($code)) {
                                        $page_url = transposh_utils::translate_url($clean_page_url, '', $code, array(&$transposh->database, 'fetch_translation'));
                                    } else {
                                        $page_url = $clean_page_url;
                                    }

                                    // clean $code in default lanaguge
                                    $page_url = transposh_utils::rewrite_url_lang_param($page_url, $transposh->home_url, $transposh->enable_permalinks_rewrite, $transposh->options->is_default_language($code) ? '' : $code, $transposh->edit_mode);

                                    printf('<li><a href="%2$s">%1$s</a><img src="%3$s.png" /></li>', $langname, htmlentities($page_url), $plugpath."/img/flags/".$flag);
//
//                                    $widget_args[] = array(
//                                        'lang' => $langname,
//                                        'langorig' => $language,
//                                        'flag' => $flag,
//                                        'isocode' => $code,
//                                        'url' => htmlentities($page_url), // fix that XSS
//                                        'active' => ($transposh->target_language == $code));
                                }
                            }
                            echo '</ul>';
                            //the_widget('transposh_plugin_widget');
                        }else{
                            $languages = $pbtheme_data['language'];
                            printf('<a href="#" class="language_selected"><span>%1$s</span><i class="fa fa-angle-down"></i></a><ul>', $languages[1]['language']);
                            foreach ($languages as $language) {
                                $flag = (isset($language['flag']) ? $language['flag'] : '' );
                                $lang = $language['language'];
                                $langurl = $language['langurl'];
                                printf('<li><a href="%2$s">%1$s</a><img src="%3$s" /></li>', $lang, $langurl, get_template_directory_uri() . '/images/flags/' . $flag);
                            }
                            echo '</ul>';
                        }
                        break;
                    case 'network-icons':
                        $networks = @$pbtheme_data['contact'][$pbtheme_data[$location . '_networks']]['contact'];
						if(is_array($networks)) foreach ($networks as $network) {
							printf('<a href="%1$s" class="inline-block"><img width="24" height="24" src="%2$s/images/socialnetworks/%3$s" class="block" /></a>', $network['socialnetworksurl'], get_bloginfo('template_directory'), $network['socialnetworks'], $position);
						}

                        break;
                    case 'tagline':
                        echo $pbtheme_data[$location . '_tagline'];
                        break;
                    case 'to-the-top':
                        printf('<a href="#pbtheme_wrapper" title="%1$s">%2$s</a>', __('UP!', 'pbtheme'), ( $pbtheme_data['footer_up_text'] !== '' ? $pbtheme_data['footer_up_text'] : '<i class="fa fa-angle-up"></i>'));
                        break;
                    case 'tagline-alt':
                        echo $pbtheme_data[$location . '_tagline_alt'];
                        break;
                    case 'menu':
                        if ($pbtheme_data[$location . '_menu'] !== 'none') {
                            ?>
                            <nav class="a-inherit">
                                    <?php echo wp_nav_menu(array('menu' => $pbtheme_data[$location . '_menu'], 'depth' => '1', 'fallback_cb' => 'pbtheme_list_pages', 'container' => false, 'menu_id' => '', 'menu_class' => 'list_style')); ?>
                            </nav>
                                    <?php
                        }
						break;
					case 'woo-login-link':
						if (DIVWP_WOOCOMMERCE === true) :
							global $woocommerce;
							$myaccount_page_id = get_option('woocommerce_myaccount_page_id');
							if ($myaccount_page_id) {
								$myaccount_page_url = get_permalink($myaccount_page_id);
							} else {
								$myaccount_page_url = get_permalink();
							}
							if (is_user_logged_in()) {
								printf('<a href="%1$s" title="%2$s">%2$s</a>', $myaccount_page_url, __('My Account', 'pbtheme'));
							} else {
								printf('<a href="%1$s" title="%2$s">%2$s</a>', $myaccount_page_url, __('Log in', 'pbtheme'));
							}
						endif;
						break;
					case 'woo-cart':
						if (DIVWP_WOOCOMMERCE === true) :
							global $woocommerce;
							printf('<div class="woo_shopping woo_shopping_cart float_right"><a class="cart-contents" href="%1$s" title="%2$s"><i class="divicon-cart"></i> %3$s - %4$s</a>', $woocommerce->cart->get_cart_url(), __('View your shopping cart', 'pbtheme'), sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'pbtheme'), $woocommerce->cart->cart_contents_count), $woocommerce->cart->get_cart_total());

							if (sizeof($woocommerce->cart->get_cart()) > 0) {
								echo '<div id="div_woocart" class="pbtheme_shopping_cart">';
								foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
									$_product = $values['data'];
									if ($_product->exists() && $values['quantity'] > 0) {
										?>
								<div class = "pbtheme_cart_item">

									<div class="div-cart-remove a-inherit">
								<?php
								echo apply_filters('woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url($woocommerce->cart->get_remove_url($cart_item_key)), __('Remove this item', 'pbtheme')), $cart_item_key);
								?>
									</div>

									<div class="div-cart-thumbnail">
								<?php
								$thumbnail = apply_filters('pbtheme-square', $_product->get_image(), $values, $cart_item_key);

								if (!$_product->is_visible() || (!empty($_product->variation_id) && !$_product->parent_is_visible() ))
									echo $thumbnail;
								else
									printf('<a href="%s">%s</a>', esc_url(get_permalink(apply_filters('woocommerce_in_cart_product_id', $values['product_id']))), $thumbnail);
								?>
									</div>

									<div class="div-cart-name a-inherit pbtheme_header_font">
								<?php
								if (!$_product->is_visible() || (!empty($_product->variation_id) && !$_product->parent_is_visible() ))
									echo apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key);
								else
									printf('<a href="%s">%s</a>', esc_url(get_permalink(apply_filters('woocommerce_in_cart_product_id', $values['product_id']))), apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key));

								// Meta data
								echo $woocommerce->cart->get_item_data($values);

								// Backorder notification
								if ($_product->backorders_require_notification() && $_product->is_on_backorder($values['quantity']))
									echo '<p class="backorder_notification">' . __('Available on backorder', 'pbtheme') . '</p>';
								?>
									</div>


									<div class="div-cart-subtotal pbtheme_header_font">
										<?php
										echo apply_filters('woocommerce_cart_item_subtotal', $woocommerce->cart->get_product_subtotal($_product, $values['quantity']), $values, $cart_item_key);
										?>
									</div>

									<div class="div-cart-quantity">
										<?php
										if ($_product->is_sold_individually()) {
											$product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
										} else {

											$product_quantity = __('Quantity', 'pbtheme') . ' : ' . esc_attr($values['quantity']);
										}

										echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key);
										?>
									</div>

								</div>
										<?php
									}
								}
								printf('<a href="%2$s" class="pbtheme_cart_button div_view_cart float_left">%1$s</a>', __('View cart', 'pbtheme'), $woocommerce->cart->get_cart_url());
								printf('<a href="%2$s" class="pbtheme_cart_button div_checkout float_right">%1$s</a>', __('Checkout', 'pbtheme'), $woocommerce->cart->get_checkout_url());
								echo '</div></div>';
							} else {
								$shop = woocommerce_get_page_id('shop') ? get_the_title(woocommerce_get_page_id('shop')) : '';
								echo '<div id="div_woocart" class="pbtheme_shopping_cart pbtheme_empty_cart">
					<h3>' . __('Your shopping cart is empty', 'pbtheme') . '</h3>
					<span>' . __('Why not add some items in our', 'pbtheme') . ' <a href="' . get_permalink(woocommerce_get_page_id('shop')) . '">' . __('Shop', 'pbtheme') . '</a></span>
					</div></div>';
							}
						endif;
						break;
				endswitch;
			  echo '</div>';
		  endforeach;
	  }

  endif;

// Ensure cart contents update when products are added to the cart via AJAX
            if (!function_exists('div_woocommerce_header_add_to_cart_fragment')) :

                function div_woocommerce_header_add_to_cart_fragment($fragments) {
                    global $woocommerce;

                    ob_start();

                    printf('<div class="woo_shopping woo_shopping_cart float_right"><a class="cart-contents" href="%1$s" title="%2$s"><i class="divicon-cart"></i> %3$s - %4$s</a>', $woocommerce->cart->get_cart_url(), __('View your shopping cart', 'pbtheme'), sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'pbtheme'), $woocommerce->cart->cart_contents_count), $woocommerce->cart->get_cart_total());

                    if (sizeof($woocommerce->cart->get_cart()) > 0) {
                        echo '<div id="div_woocart" class="pbtheme_shopping_cart">';
                        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
                            $_product = $values['data'];
                            if ($_product->exists() && $values['quantity'] > 0) {
                                ?>
                        <div class = "pbtheme_cart_item">

                            <div class="div-cart-remove a-inherit">
                        <?php
                        echo apply_filters('woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url($woocommerce->cart->get_remove_url($cart_item_key)), __('Remove this item', 'pbtheme')), $cart_item_key);
                        ?>
                            </div>

                            <div class="div-cart-thumbnail">
                        <?php
                        $thumbnail = apply_filters('pbtheme-square', $_product->get_image(), $values, $cart_item_key);

                        if (!$_product->is_visible() || (!empty($_product->variation_id) && !$_product->parent_is_visible() ))
                            echo $thumbnail;
                        else
                            printf('<a href="%s">%s</a>', esc_url(get_permalink(apply_filters('woocommerce_in_cart_product_id', $values['product_id']))), $thumbnail);
                        ?>
                            </div>

                            <div class="div-cart-name a-inherit pbtheme_header_font">
                        <?php
                        if (!$_product->is_visible() || (!empty($_product->variation_id) && !$_product->parent_is_visible() ))
                            echo apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key);
                        else
                            printf('<a href="%s">%s</a>', esc_url(get_permalink(apply_filters('woocommerce_in_cart_product_id', $values['product_id']))), apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key));

                        // Meta data
                        echo $woocommerce->cart->get_item_data($values);

                        // Backorder notification
                        if ($_product->backorders_require_notification() && $_product->is_on_backorder($values['quantity']))
                            echo '<p class="backorder_notification">' . __('Available on backorder', 'pbtheme') . '</p>';
                        ?>
                            </div>


                            <div class="div-cart-subtotal pbtheme_header_font">
                        <?php
                        echo apply_filters('woocommerce_cart_item_subtotal', $woocommerce->cart->get_product_subtotal($_product, $values['quantity']), $values, $cart_item_key);
                        ?>
                            </div>

                            <div class="div-cart-quantity">
                        <?php
                        if ($_product->is_sold_individually()) {
                            $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                        } else {

                            $product_quantity = __('Quantity', 'pbtheme') . ' : ' . esc_attr($values['quantity']);
                        }

                        echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key);
                        ?>
                            </div>

                        </div>
                        <?php
                    }
                }
                printf('<a href="%2$s" class="pbtheme_cart_button div_view_cart float_left">%1$s</a>', __('View cart', 'pbtheme'), $woocommerce->cart->get_cart_url());
                printf('<a href="%2$s" class="pbtheme_cart_button div_checkout float_right">%1$s</a>', __('Checkout', 'pbtheme'), $woocommerce->cart->get_checkout_url());
                echo '</div></div>';
            } else {
                $shop = woocommerce_get_page_id('shop') ? get_the_title(woocommerce_get_page_id('shop')) : '';
                echo '<div id="div_woocart" class="pbtheme_shopping_cart pbtheme_empty_cart">
							<h3>' . __('Your shopping cart is empty', 'pbtheme') . '</h3>
							<span>' . __('Why not add some items in our', 'pbtheme') . ' <a href="' . get_permalink(woocommerce_get_page_id('shop')) . '">' . __('Shop', 'pbtheme') . '</a></span>
							</div></div>';
            }

            $fragments['div.woo_shopping.woo_shopping_cart'] = ob_get_clean();

            return $fragments;
        }

    endif;
    add_filter('add_to_cart_fragments', 'div_woocommerce_header_add_to_cart_fragment');


// Get featured area
    if (!function_exists('pbtheme_get_featarea')) :

        function pbtheme_get_featarea($feat_type) {
            $type = get_post_format();

            $html = '';
            if ($type != '') {
                $result;

                switch ($type) {
                    case 'audio':
                        $pbtheme_audio_override = get_post_meta(get_the_ID(), 'pbtheme_audio_override', true);
                        $pbtheme_audio_override_ogg = get_post_meta(get_the_ID(), 'pbtheme_audio_override_ogg', true);
                        if (($pbtheme_audio_override !== 'none' && $pbtheme_audio_override !== '') || ($pbtheme_audio_override_ogg !== 'none' && $pbtheme_audio_override_ogg !== '')) {
                            $add_poster = ( has_post_thumbnail() ? wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $feat_type) : '' );
                            $html = '<div class="div_featarea div_feat_' . $type . ' margin-bottom24">';
                            $html .= sprintf('<audio class="fullwidth block" preload="auto" loop="loop" controls%4$s>
								<source src="%1$s" type="audio/mpeg">
								<source src="%2$s" type="audio/ogg">
								 %3$s
							</audio>', $pbtheme_audio_override, $pbtheme_audio_override_ogg, __('Your browser does not support the audio tag.', 'pbtheme'), ( $add_poster !== '' ? ' poster="' . $add_poster[0] . '"  data-image-replacement="' . $add_poster[0] . '"' : ''));
                        } else {
                            $pbtheme_audio_override = get_post_meta(get_the_ID(), 'pbtheme_audio_override_site', true);
                            if ($pbtheme_audio_override !== 'none' && $pbtheme_audio_override !== '') {
                                $html = '<div class="div_featarea div_feat_' . $type . ' margin-bottom24">';
                                $html .= sprintf('%1$s', $pbtheme_audio_override);
                            }
                        }
                        break;

                    case 'gallery':
                        if ($feat_type == 'large') {
                            $feat_type = 'pbtheme-fullblog';
                        }
                        $old_gallery_shortcode = get_post_meta(get_the_ID(), 'pbtheme_gallery_override', true);
                        $shortcode = '[pbuilder_slider ';
                        $ctype = 'ctype="';
                        $image_url = 'image="';
                        $image_link = 'image_link="';
                        $image_link_type = 'image_link_type="';
                        $shortcode_html = 'html="';
                        $text_align = 'text_align="';
                        $back_color = 'back_color="';
                        $text_color = 'text_color="';
                        $randId = rand();
                        $html = '<div class="div_featarea div_feat_' . $type . ' margin-bottom24 pbuilder_module" data-modid="' . $randId . '">';
                        if ($old_gallery_shortcode != 'none' && $old_gallery_shortcode != '') {
                            $old_gallery_shortcode = explode(' ', $old_gallery_shortcode);
                            $old_ids;
                            $num = count($old_gallery_shortcode);
                            for ($i = 0; $i < count($old_gallery_shortcode); $i++) {
                                $old_gallery_shortcode[$i] = explode('="', $old_gallery_shortcode[$i]);
                            }
                            $num = count($old_gallery_shortcode);
                            for ($i = 0; $i < count($old_gallery_shortcode); $i++) {
                                if ($old_gallery_shortcode[$i][0] == 'ids')
                                    $old_ids = explode(',', $old_gallery_shortcode[$i][1]);
                            }
                            $i = 0;
                            $last_id = explode('"', $old_ids[count($old_ids) - 1]);
                            $old_ids[count($old_ids) - 1] = $last_id[0];
                            $num_of_images = count($old_ids);
                            foreach ($old_ids as $old_id) {
                                $i++;
                                $ctype .= 'image';
                                $result = wp_get_attachment_image_src(intval($old_id), $feat_type);
                                $image_url .= sprintf('%1$s', $result[0]);
                                $image_link .= sprintf('%1$s', $result[0]);
                                $image_link_type .= 'lightbox-image';
                                $shortcode_html .= '';
                                $text_align .= '';
                                $back_color .= '';
                                $text_color .= '';
                                if ($i < $num_of_images) {
                                    $ctype .= '|';
                                    $image_url .= '|';
                                    $image_link .= '|';
                                    $image_link_type .= '|';
                                    $shortcode_html .= '|';
                                    $text_align .= '|';
                                    $back_color .= '|';
                                    $text_color .= '|';
                                } else {
                                    $ctype .= '" ';
                                    $image_url .= '" ';
                                    $image_link .= '" ';
                                    $image_link_type .= '" ';
                                    $shortcode_html .= '" ';
                                    $text_align .= '" ';
                                    $back_color .= '" ';
                                    $text_color .= '" ';
                                }
                            }
                        } else {
                            $i = 0;

                            $images = get_attached_media('image');
                            $num_of_images = count($images);
                            if (!is_array($images))
                                break;
                            $html = '<div class="div_featarea div_feat_' . $type . ' margin-bottom24 pbuilder_module" data-modid="' . $randId . '">';
                            foreach ($images as $image) {
                                $i++;
                                $ctype .= 'image';
                                $result = wp_get_attachment_image_src($image->ID, $feat_type);
                                $image_url .= sprintf('%1$s', $result[0]);
                                $image_link .= sprintf('%1$s|', $result[0]);
                                $image_link_type .= 'lightbox-image';
                                $shortcode_html .= '';
                                $text_align .= '';
                                $back_color .= '';
                                $text_color .= '';
                                if ($i < $num_of_images) {
                                    $ctype .= '|';
                                    $image_url .= '|';
                                    $image_link .= '|';
                                    $image_link_type .= '|';
                                    $shortcode_html .= '|';
                                    $text_align .= '|';
                                    $back_color .= '|';
                                    $text_color .= '|';
                                } else {
                                    $ctype .= '" ';
                                    $image_url .= '" ';
                                    $image_link .= '" ';
                                    $image_link_type .= '" ';
                                    $shortcode_html .= '" ';
                                    $text_align .= '" ';
                                    $back_color .= '" ';
                                    $text_color .= '" ';
                                }
                            }
                        }
                        $shortcode .= sprintf('%1$s %2$s %3$s %4$s %5$s %6$s %7$s %8$s auto_play="false" bot_margin=24 navigation="squared" pagination="false"][/pbuilder_slider]', $ctype, $image_url, $image_link, $image_link_type, $shortcode_html, $text_align, $back_color, $text_color);
                        $html .= do_shortcode($shortcode);
                        break;

                    case 'image':

                        if (has_post_thumbnail()) {
                            $html = '<div class="div_featarea div_feat_' . $type . ' margin-bottom24">';
                            $html .= get_the_post_thumbnail(get_the_ID(), $feat_type);
                            $kklike = '';
                            if (in_array('kk-i-like-it/admin.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                                $kklike = '<div class="pbtheme_image_hover_button div_button_like inline-block">' . do_shortcode('[kklike_button]') . '</div>';
                            }
                            $html .= sprintf('<div class="pbtheme_image_hover"><div><a href="%1$s" class="pbtheme_image_hover_button div_button_link"><i class="divicon-plus"></i></a><a href="%2$s" class="pbtheme_image_hover_button div_button_zoom" rel="lightbox"><i class="divicon-search"></i></a>%3$s</div></div>', get_permalink(), wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())), $kklike);
                        }
                        break;

                    case 'video':
                        $result = array();
                        $pbtheme_video_override = get_post_meta(get_the_ID(), 'pbtheme_video_override', true);
                        $pbtheme_video_override_ogg = get_post_meta(get_the_ID(), 'pbtheme_video_override_ogg', true);
                        if (($pbtheme_video_override !== 'none' && $pbtheme_video_override !== '') || ($pbtheme_video_override_ogg !== 'none' && $pbtheme_video_override_ogg !== '')) {
                            $add_poster = ( has_post_thumbnail() ? wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $feat_type) : '' );
                            $html = '<div class="div_featarea div_feat_' . $type . ' margin-bottom24">';
                            $html .= sprintf('<div class="video"><video class="fullwidth block" preload="auto" loop="loop" controls%4$s>
								<source src="%1$s" type="video/mp4">
								<source src="%2$s" type="video/ogg">
								%3$s
						</video></div>', $pbtheme_video_override, $pbtheme_video_override_ogg, __('Your browser does not support the video tag.', 'pbtheme'), ( $add_poster !== '' ? ' poster="' . $add_poster[0] . '"  data-image-replacement="' . $add_poster[0] . '"' : ''));
                        } else {
                            $result = get_post_meta(get_the_ID(), 'pbtheme_video_override_site', true);

                            if ($result !== 'none' || $result !== '') {
                                $html = '<div class="div_featarea div_feat_' . $type . ' margin-bottom24">';
                                $html .= sprintf('%1$s', $result);
                            }
                        }
                        break;
                    case 'link':
                        $result = get_post_meta(get_the_ID(), 'pbtheme_link_override', true);
                        if ($result !== 'none' && $result !== '') {
                            $html = '<div class="div_featarea div_feat_link margin-bottom24">';
                            $html .= get_the_post_thumbnail(get_the_ID(), $feat_type);
                            $html .= sprintf('<div class="pbtheme_image_hover"><div><a href="%2$s" class="pbtheme_image_hover_button" rel="bookmark"><i class="divicon-search"></i></a><a href="%1$s" class="pbtheme_image_hover_button"><i class="divicon-plus"></i></a></div></div>', get_permalink(), $result);
                        }
                        break;
                    case 'quote':
                        $html = '<div class="div_featarea div_feat_quote margin-bottom24 pbtheme_header_font">';
                        $html .= sprintf('%1$s', get_the_content());
                        break;
                    default:
                        if (has_post_thumbnail()) {
                            $html = '<div class="div_featarea div_feat_' . $type . ' margin-bottom24">';
                            $html .= get_the_post_thumbnail(get_the_ID(), $feat_type);
                        }
                        break;
                }
            } else {
                if (has_post_thumbnail()) {
                    $html = '<div class="div_featarea div_feat_image margin-bottom24">';
                    $html .= get_the_post_thumbnail(get_the_ID(), $feat_type);
                    $kklike = '';
                    if (in_array('kk-i-like-it/admin.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                        $kklike = '<div class="pbtheme_image_hover_button div_button_like inline-block">' . do_shortcode('[kklike_button]') . '</div>';
                    }
                    $html .= sprintf('<div class="pbtheme_image_hover"><div><a href="%1$s" class="pbtheme_image_hover_button div_button_link"><i class="divicon-plus"></i></a><a href="%2$s" class="pbtheme_image_hover_button div_button_zoom" rel="lightbox"><i class="divicon-search"></i></a>%3$s</div></div>', get_permalink(), wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())), $kklike);
                }
            }
            if ($html !== '')
                $html .= '</div>';
            return $html;
        }

    endif;


    /*
     * PBTheme Custom Functions
     */

// PBTheme List Pages
    function pbtheme_list_pages() {
        $menu = '<ul class="list_style">';
        $menu .= wp_list_pages(array('title_li' => '', 'depth' => '1','echo' => false));
        $menu .='</ul>';
        return $menu;
    }

// PBTheme List Pages
    function pbtheme_add_options_link() {
        global $wp_admin_bar, $imscpbtheme_lc;
        if ($imscpbtheme_lc && !$imscpbtheme_lc->CheckLicense())
            return;

        $wp_admin_bar->add_menu(array(
            'parent' => 'site-name',
            'id' => 'pbtheme_options',
            'title' => __('PBTheme Options', 'pbtheme'),
            'href' => admin_url('themes.php?page=pbtheme_options'),
            'meta' => false
        ));
    }

    add_action('wp_before_admin_bar_render', 'pbtheme_add_options_link');

// Add Categories Color Picker
    function pbtheme_category_color($hook_suffix) {
        if ($hook_suffix !== 'edit-tags.php')
            return;
        wp_enqueue_style('wp-color-picker');

        wp_enqueue_script('pbtheme-category-color', get_template_directory_uri() . '/js/categorycolor.js', array('wp-color-picker'), false, true);
    }

    add_action('admin_enqueue_scripts', 'pbtheme_category_color');

    define('Category_Extras', 'Category_Extras_option');
    add_action('category_edit_form_fields', 'category_edit_form_fields');
    add_action('category_edit_form', 'category_edit_form');
    add_action('category_add_form_fields', 'category_add_form_fields');
    add_action('category_add_form', 'category_edit_form');

    function category_edit_form() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#edittag').attr("enctype", "multipart/form-data").attr("encoding", "multipart/form-data");
            });
        </script>
    <?php
}

function category_add_form_fields() {
    ?>
        <div class="form-field">
            <p><?php _e('PBTheme Grid Layout has support for colored categories. For this option you must edit each category upon creation.', 'pbtheme'); ?></p>
        </div>
    <?php
}

function category_edit_form_fields($tag) {
    $tag_extra_fields = get_option(Category_Extras);
    if (isset($tag_extra_fields[$tag->term_id]['catcolor']))
        $category_color = $tag_extra_fields[$tag->term_id]['catcolor'];
    else
        $category_color = '#888888';
    ?>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="catcolor"><?php _e('Category Color', 'pbtheme'); ?></label>
        </th>
        <td>
            <input type="text" id="catcolor" name="catcolor" value="<?php echo $category_color; ?>" class="wp-color-picker"/><br/>
            <p class="description"><?php _e('Select category color.', 'pbtheme'); ?></p>
        </td>
    </tr>
    <?php
}

add_filter('edited_terms', 'update_Category_Extras');

function update_Category_Extras($term_id) {
    if (isset($_POST['catcolor']) && isset($_POST['taxonomy']) && $_POST['taxonomy'] == 'category'):
        $tag_extra_fields = get_option(Category_Extras);
        $tag_extra_fields[$term_id]['catcolor'] = strip_tags($_POST['catcolor']);
        update_option(Category_Extras, $tag_extra_fields);
    endif;
}

add_filter('deleted_term_taxonomy', 'remove_Category_Extras');

function remove_Category_Extras($term_id) {
    if (isset($_POST['catcolor']) && isset($_POST['taxonomy']) && $_POST['taxonomy'] == 'category'):
        $tag_extra_fields = get_option(Category_Extras);
        unset($tag_extra_fields[$term_id]);
        update_option(Category_Extras, $tag_extra_fields);
    endif;
}

// Convert hex2rgb
function pbtheme_hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $rgb = array($r, $g, $b);
    //return implode(",", $rgb);
    return $rgb;
}

// Adds featured images thumbnails
add_filter('manage_posts_columns', 'pbtheme_posts_columns', 5);
add_action('manage_posts_custom_column', 'pbtheme_posts_custom_columns', 5, 2);

function pbtheme_posts_columns($defaults) {
    $defaults['post_thumbs'] = __('Featured image', 'pbtheme');
    return $defaults;
}

function pbtheme_posts_custom_columns($column_name, $id) {
    if ($column_name === 'post_thumbs') {
        echo the_post_thumbnail(array(60, 60));
    }
}

// get_between. string function
function pbtheme_get_between($content, $start, $end) {
    $r = explode($start, $content);
    if (isset($r[1])) {
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}

// Get content with formatting
function get_the_content_with_formatting($more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}

// Custom excerpt lenght
function custom_excerpt_length($length) {
    return 100;
}

add_filter('excerpt_length', 'custom_excerpt_length', 1024);

// Time difference
function time_difference($time_diff) {
    $args = array(
        'posts_per_page' => 1,
        'offset' => 0,
        'orderby' => 'date',
        'order' => 'ASC',
        'post_type' => 'post',
        'post_status' => 'publish',
        'suppress_filters' => true
    );
    $first_post = get_posts($args);

    $date = $first_post[0]->post_date_gmt;

    $chunks = array(
        'years' => array(60 * 60 * 24 * 365),
        'months' => array(60 * 60 * 24 * 30),
        'weeks' => array(60 * 60 * 24 * 7),
        'days' => array(60 * 60 * 24)
    );


    if (!is_numeric($date)) {
        $time_chunks = explode(':', str_replace(' ', ':', $date));
        $date_chunks = explode('-', str_replace(' ', '-', $date));
        $date = gmmktime((int) $time_chunks[1], (int) $time_chunks[2], (int) $time_chunks[3], (int) $date_chunks[1], (int) $date_chunks[2], (int) $date_chunks[0]);
    }
    $current_time = current_time('mysql', $gmt = 0);

    $newer_date = strtotime($current_time);
    $since = $newer_date - $date;
    if (0 > $since)
        return __('sometime', 'pbtheme');

    $seconds = $chunks[$time_diff][0];
    $count = floor($since / $seconds);

    $output = ( 1 == $count ) ? '1' : $count;

    return $output;
}

// PBTheme Timeago
function pbtheme_time_ago($date) {
    $chunks = array(
        array(60 * 60 * 24 * 365, __('year', 'pbtheme'), __('years', 'pbtheme')),
        array(60 * 60 * 24 * 30, __('month', 'pbtheme'), __('months', 'pbtheme')),
        array(60 * 60 * 24 * 7, __('week', 'pbtheme'), __('weeks', 'pbtheme')),
        array(60 * 60 * 24, __('day', 'pbtheme'), __('days', 'pbtheme')),
        array(60 * 60, __('hour', 'pbtheme'), __('hours', 'pbtheme')),
        array(60, __('minute', 'pbtheme'), __('minutes', 'pbtheme')),
        array(1, __('second', 'pbtheme'), __('seconds', 'pbtheme'))
    );
    if (!is_numeric($date)) {
        $time_chunks = explode(':', str_replace(' ', ':', $date));
        $date_chunks = explode('-', str_replace(' ', '-', $date));
        $date = gmmktime((int) $time_chunks[1], (int) $time_chunks[2], (int) $time_chunks[3], (int) $date_chunks[1], (int) $date_chunks[2], (int) $date_chunks[0]);
    }
    $current_time = current_time('mysql', $gmt = 0);
    $newer_date = strtotime($current_time);
    $since = $newer_date - $date;
    if (0 > $since)
        return __('sometime', 'pbtheme');
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        if (( $count = floor($since / $seconds) ) != 0)
            break;
    }
    $output = ( 1 == $count ) ? '1 <span class="text-ago">' . $chunks[$i][1] : $count . ' <span class="text-ago">' . $chunks[$i][2];
    if (!(int) trim($output)) {
        $output = '0' . __('seconds', 'pbtheme');
    }
    $output .= __(' ago', 'pbtheme') . '</span>';
    return $output;
}

/**
 * PBTheme Update
 */
function pbtheme_update_options() {
    if($_POST && isset($_POST['post_type']) && $_POST['post_type'] == "page")
        $_POST['page_template'] = "";

    /*global $pbtheme_data;
    if($pbtheme_data && is_array($pbtheme_data)){
        if(!isset($pbtheme_data['woo_title_height']))
            $pbtheme_data['woo_title_height'] = 60;

        if(!isset($pbtheme_data['woo_enable_fb']))
            $pbtheme_data['woo_enable_fb'] = 0;

        if(!isset($pbtheme_data['woo_enable_tw']))
            $pbtheme_data['woo_enable_tw'] = 0;

        if(!isset($pbtheme_data['woo_enable_pin']))
            $pbtheme_data['woo_enable_tw'] = 0;

        if(!isset($pbtheme_data['woo_enable_email']))
            $pbtheme_data['woo_enable_tw'] = 0;

        if(!isset($pbtheme_data['transposh_enable']))
            $pbtheme_data['transposh_enable'] = 0;
    }*/

    $old_options = get_option('PBTheme_options_imsc');

    $curr = get_theme_mod('pbtheme_version');

    if (is_array($old_options)) {
        if ($curr === false) {
            foreach ($old_options as $key => $option) {
                $new = str_replace(get_bloginfo('url'), '[site_url]', $option);
                set_theme_mod($key, $new);
                var_dump($key . ' - ' . $new);
            }

            set_theme_mod('font_ggl_on', '1');
            set_theme_mod('font_ggl', array('face' => $old_options['font'], 'style' => 'normal', 'weight' => '400'));
            set_theme_mod('font_header_ggl_on', '1');
            set_theme_mod('font_header_ggl', array('face' => $old_options['font_header'], 'style' => 'normal', 'weight' => '400'));

            set_theme_mod('favicon', '');
            set_theme_mod('apple_ti57', '');
            set_theme_mod('apple_ti72', '');
            set_theme_mod('apple_ti114', '');
            set_theme_mod('apple_ti144', '');

            set_theme_mod('smof_init', date('r'));
            set_theme_mod('pbtheme_version', '2.0');
            die('<p>Version 2.0 Updated / Please refresh your browser!</p>');
        }
    }
}

add_action('init', 'pbtheme_update_options');

function pbtheme_license_message() {
    global $imscpbtheme_lc;
    if ($imscpbtheme_lc && !$imscpbtheme_lc->CheckLicense() && $_GET['page'] != "imscpb_install_license") {
        ?>
        <style type="text/css">
            .pbtheme_license_message{
                background-color: #fdfdfd;
                border: 1px solid orange;
                padding: 10px;
                font-size:16px;
                font-weight:bold;
            }
        </style>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var msg = '<p class="pbtheme_license_message"><img src="<?php echo IMSCPBT_URL . '/images/alert.png' ?>"> This installation of Profit Builder Theme is currently unlicensed. Please enter your API Key to activate your theme... <a href="<?php echo admin_url('admin.php?page=imscpbtheme_install_license'); ?>">Click Here to Activate</a></p>';
                if (jQuery('#wpbody-content').length > 0)
                    jQuery('#wpbody-content').prepend(msg);
                else if (jQuery('#pbtheme_wrapper').length > 0)
                    jQuery('#pbtheme_wrapper').prepend(msg);
                else
                    jQuery('body').prepend(msg);
            })
        </script>
        <?php
    }
}

if (get_option("pbtheme_skiped", "true") != "true") {
    add_action('wp_head', 'pbtheme_license_message');
    add_action('admin_head', 'pbtheme_license_message');
}

// Load in custom functions
include_once("custom_functions.php");


add_filter( 'image_resize_dimensions', 'wpse124009DisableCrop', 10, 6 );
function wpse124009DisableCrop( $enable, $orig_w, $orig_h, $dest_w, $dest_h, $crop )
{
    // Instantly disable this filter after the first run
    remove_filter( current_filter(), __FUNCTION__ );

    return image_resize_dimensions( $orig_w, $orig_h, $dest_w, $dest_h, false );
}

add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		global $pbtheme_data;
		return $pbtheme_data['woo-columns'];
	}
}
?>
