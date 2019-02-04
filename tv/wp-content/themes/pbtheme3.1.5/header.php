<?php
/**
 * @package WordPress
 * @subpackage PBTheme Theme
 * @author IM Success Center (http://www.imsuccesscenter.com) 
 */
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
?>
<!DOCTYPE HTML>
<?php global $pbtheme_data, $pbtheme_layout; ?>
<html <?php language_attributes(); ?>>
    <head>
        <title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="description" content="<?php
if (is_single()) {
    single_post_title('', true);
} else {
    bloginfo('name');
    echo " - ";
    bloginfo('description');
}
?>" />
              <?php
              $user_info = get_userdata(1);
              ?>
        <meta name="author" content="<?php echo $user_info->first_name . " " . $user_info->last_name; ?>" />
        <meta name="contact" content="<?php echo $user_info->user_email; ?>" />
        <?php if ($pbtheme_data['responsive'] == 1) : ?>
            <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php else : ?>
            <meta name="viewport" content="width=1200, maximum-scale=3, user-scalable=1, target-densitydpi=device-dpi">
        <?php endif; ?>
        <?php if ($pbtheme_data['favicon'] !== '') : ?>
            <link id="pbtheme_favicon" rel="shortcut icon" href="<?php echo $pbtheme_data['favicon']; ?>" type='image/x-icon'>
        <?php endif; ?>
        <?php if ($pbtheme_data['apple_ti57'] !== '') : ?>
            <link rel="apple-touch-icon" href="<?php echo $pbtheme_data['apple_ti57']; ?>" type="image/png">
        <?php endif; ?>
        <?php if ($pbtheme_data['apple_ti72'] !== '') : ?>
            <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $pbtheme_data['apple_ti72']; ?>" type="image/png">
        <?php endif; ?>
        <?php if ($pbtheme_data['apple_ti114'] !== '') : ?>
            <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $pbtheme_data['apple_ti114']; ?>" type="image/png">
        <?php endif; ?>
        <?php if ($pbtheme_data['apple_ti144'] !== '') : ?>
            <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $pbtheme_data['apple_ti144']; ?>" type="image/png">
<?php endif; ?>
        <!--[if lt IE 9]>
        <script>
                document.createElement('header');
                document.createElement('nav');
                document.createElement('section');
                document.createElement('article');
                document.createElement('aside');
                document.createElement('footer');
                document.createElement('video');
                document.createElement('audio');
        </script>
        <![endif]-->
        <?php
        if ($pbtheme_data['tracking-code'] != '') :
            echo $pbtheme_data['tracking-code'];
        endif;
        ?>
        <?php
        $pbtheme_class = 'pbtheme-layout pbtheme_' . ( $pbtheme_data['boxed'] == 1 ? 'boxed' : 'wide' ) . ' pbtheme-rrs blog_layout_' . $pbtheme_data['blog_layout'];
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
            $pbtheme_class .= ' woocommerce';
        if (is_single() || is_page()) {
            $padding = get_post_meta(get_the_ID(), 'pbtheme_padding', true);
            if ($padding == '1')
                $pbtheme_class .= ' pbtheme_remove_padding';
			
			echo "<meta property='og:title' content='".get_the_title($post->ID)."' />";	
			echo "<meta property='og:url' content='".get_permalink($post->ID)."' />";	
			echo "<meta property='og:site_name' content='".get_bloginfo('name')."' />";	
						
			if(has_post_thumbnail(get_the_ID())){
				$page_image=wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
				echo "<meta property='og:image' content='".$page_image[0]."' />";	
			}
        }
        if (is_page()) {
            $page_bg = get_post_meta(get_the_ID(), 'pbtheme_page_bg', true);
            if ($page_bg == '' || $page_bg == 'none') {
                $pbtheme_class .= ' div-nobgvideo';
            }
        } else {
            $pbtheme_class .= ' div-nobgvideo';
        }
        wp_head();
		
		$hidemenu = get_post_meta(get_the_ID(), 'pbtheme_hidemenu', true);
		
		$hidetopbar = get_post_meta(get_the_ID(), 'pbtheme_hidetopbar', true);
		
		if (isset($pbtheme_data['disable-top-header']) && $pbtheme_data['disable-top-header'] == 1 ) $hidetopbar = '1';
		?>
        <script type="text/javascript">
            var hidetopbar = '<?php echo $hidetopbar ?>';
        </script>
        
        
        


    </head>
    <body <?php body_class($pbtheme_class); ?> >
            
            
      
        <div id="pbtheme_wrapper">
        	    
        
            <?php
            if (is_page()) {

                

                if ($page_bg !== '' && $page_bg !== 'none') {
                    switch ($page_bg) :
                        case 'bgimage' :
                            $image = get_post_meta(get_the_ID(), 'pbtheme_page_image', true);
                            $size = getimagesize($image);
                            printf('<div id="pbtheme_page_bg" class="pbtheme_page_bg"><div id="pbtheme_page_bg_inner"><img src="' . $image . '" id="bg" alt="" style="min-width:' . $size['width'] . ';min-height:' . $size['height'] . ';" /></div></div>');
                            break;
                        case 'videoembed' :
                            if ($detect->isMobile() || $detect->isTablet()) {
                                $image = get_post_meta(get_the_ID(), 'pbtheme_page_image', true);
                                if (!empty($image) && $image != "") {
                                    $size = getimagesize($image);
                                    printf('<div id="pbtheme_page_bg" class="pbtheme_page_bg"><div id="pbtheme_page_bg_inner"><img src="' . $image . '" id="bg" alt="" style="min-width:' . $size['width'] . ';min-height:' . $size['height'] . ';" /></div></div>');
                                }
                            } else {
                                printf('<div id="pbtheme_page_bg" class="pbtheme_page_bg"><div id="pbtheme_page_bg_inner"></div></div>');
                            }
                            break;
                        case 'html5video' :
                            $add_poster = ( has_post_thumbnail() ? wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full') : '' );
                            $mp4 = get_post_meta(get_the_ID(), 'pbtheme_pagevideo_mp4', true);
                            $ogv = get_post_meta(get_the_ID(), 'pbtheme_pagevideo_ogv', true);
                            $entry = sprintf('<video class="fullwidth block" preload="auto" loop="loop" autoplay%4$s>
								<source src="%1$s" type="video/mp4">
								<source src="%2$s" type="video/ogg">
								%3$s
						</video>', $mp4, $ogv, __('Your browser does not support the video tag.', 'pbtheme'), ( $add_poster !== '' ? ' poster="' . $add_poster[0] . '"  data-image-replacement="' . $add_poster[0] . '"' : ''));
                            if ($detect->isMobile() || $detect->isTablet()) {
                                $image = get_post_meta(get_the_ID(), 'pbtheme_page_image', true);
                                if (!empty($image) && $image != "") {
                                    $size = getimagesize($image);
                                    printf('<div id="pbtheme_page_bg" class="pbtheme_page_bg"><div id="pbtheme_page_bg_inner"><img src="' . $image . '" id="bg" alt="" style="min-width:' . $size['width'] . ';min-height:' . $size['height'] . ';" /></div></div>');
                                }
                            } else {
                                printf('<div id="pbtheme_page_bg" class="pbtheme_page_bg">%1$s</div>', $entry);
                            }
                            break;
                    endswitch;
                }
            }

            // Do we hide the menu
            if (!($hidemenu === '1' && $hidetopbar === '1')) {
                ?>
                <div class="header_wrapper<?php printf(' layout-%1$s%2$s div_logo_%3$s', $pbtheme_data['header_layout'], ( $pbtheme_data['header_shadow'] == 1 ? ' div_header_shadow' : ''), $pbtheme_data['logo_size']); ?>">

							<?php if ($hidetopbar !== '1') { ?>
        
                                <?php if (isset($pbtheme_data['header-widgets-before']) || $pbtheme_data['header-widgets-before'] !== 'none') : ?>
        
                                    <div class="pbtheme_header_widgets">
                                        <div class="pbtheme_container">
                                            <div class="anivia_row pbuilder_row">
                                                <div>
                                                    <?php
                                                    $blog_widgets_before = $pbtheme_data['header-widgets-before'];
                                                    for ($i = 1; $i <= $blog_widgets_before; $i++) {
                                                        printf('<div class="pbuilder_column pbuilder_column-1-%1$s">', $blog_widgets_before);
                                                        dynamic_sidebar('header-widgets-before-' . $i);
                                                        printf('</div><!-- pbuilder_column pbuilder_column-1-%1$s -->', $blog_widgets_before);
                                                    }
                                                    ?>
                                                </div>
                                            </div><!-- row -->
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="pbtheme_container_max header_alt_nav">
                                    <div class="pbtheme_top pbtheme_header_font">
                                        <div class="pbtheme_top_left float_left">
                                            <?php
                                            pbtheme_header_elements('left');
                                            ?>
                                        </div>
                                        <div class="pbtheme_top_right float_right">
                                            <?php
                                            pbtheme_header_elements('right');
                                            ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="header_pbtheme pbtheme_dark_border"></div>
                         <?php } ?>

                    <div class="header_main_nav">
                        <div class="header_holder">
                            <a href="<?php echo home_url(); ?>" class="logo">
                                <img class="block div_mainlogoimg" src="<?php echo $pbtheme_data['logo']; ?>" alt="<?php bloginfo('name') ?>" />
                            </a>
                            <div class="div_responsive_icons">
                                <a id="div_rtrigger" href="#"><i class="divicon-bar"></i></a>
                                <a id="div_rwidgetized" href="#"><i class="divicon-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="header_pbtheme_bottom pbtheme_dark_border"></div>
                    
                    
                    <?php if ($hidemenu !== '1') { ?>
                    
                    <div class="pbtheme_container_max header_main_nav">
                        <?php
                        $menu_slug = 'pbtheme_primary';
                        $locations = get_nav_menu_locations();
                        
                        if(array_key_exists($menu_slug,$locations)) $menu_id = $locations[$menu_slug];
                        else $menu_id = reset($locations);
						
                        $menu_items = wp_get_nav_menu_object($menu_id);
                        
                        //if (!has_nav_menu( 'primary' )) {
                        //$Menu = wp_nav_menu(array('echo' => false, 'theme_location' => 'pbtheme_primary', 'depth' => 3, 'walker' => '', 'fallback_cb' => '', 'container' => false, 'menu_id' => '', 'menu_class' => 'pbtheme_menu'));
                        if (isset($menu_items->count) && $menu_items->count == 0) {
                            $navStyle = "padding: 32px 8px;";
                            $logoStyle = "padding: 32px 8px;";
                        }else{
                           $Menu = wp_nav_menu(array('echo' => false,'theme_location' => 'pbtheme_primary', 'depth' => 5, 'walker' => ( $pbtheme_data['disable_menu'] == 0 ? new Walker_Nav_Menu_Widgets() : '' ), 'fallback_cb' => 'pbtheme_list_pages', 'container' => false, 'menu_id' => '', 'menu_class' => 'pbtheme_menu'));
						   $navStyle = "";
                           $logoStyle = "";
                        }
                        //}
                        ?>
                        <nav id="div_header_menu" class="menu_wrapper relative solid a-inherit" style=" <?php echo $navStyle; ?>">


                            <a href="<?php echo home_url(); ?>" class="logo_sticky" style=" <?php echo $logoStyle; ?>">
                                <img class="block div_mainlogoimg" src="<?php echo $pbtheme_data['logo']; ?>" alt="" />
                                <img class="block div_sticky_logoimg" src="<?php echo $pbtheme_data['logo_sticky']; ?>" alt="<?php bloginfo('name') ?>" />
                            </a>
                            <?php
                            if (!has_nav_menu('primary')) {
                                //wp_nav_menu(array('theme_location' => 'pbtheme_primary', 'depth' => 3, 'walker' => ( $pbtheme_data['disable_menu'] == 0 ? new Walker_Nav_Menu_Widgets() : '' ), 'fallback_cb' => 'pbtheme_list_pages', 'container' => false, 'menu_id' => '', 'menu_class' => 'pbtheme_menu'));
                            }
                            if (!empty($Menu)) {
                                echo $Menu;
                            }
                            ?>

                            <div class="pbtheme_search">
                                <form action="<?php echo home_url('/'); ?>" method="get">
                                    <input name="s" type="text" placeholder="<?php _e('Search the website', 'pbtheme'); ?>"/>
                                    <button type="submit"><i class="divicon-search"></i></button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </nav><!-- menu_wrapper -->
                    </div>
                    
                    <?php } ?>
                </div><!-- header_wrapper -->

                <?php
            } // End Hide Menu Check


            if (is_page()) {
                $rs_full = get_post_meta(get_the_ID(), 'pbtheme_revolution', true);
                $crumbs = get_post_meta(get_the_ID(), 'pbtheme_breadcrumbs', true);
                if ($rs_full == 'none' || $rs_full == '') {
                    if ($crumbs !== '1') {
                        echo pbtheme_breadcrumbs();
                    }
                } else {
                    echo do_shortcode(sprintf('[rev_slider %1$s]', $rs_full));
                }
            } elseif (is_single()) {
                $crumbs = get_post_meta(get_the_ID(), 'pbtheme_breadcrumbs', true);
                if ($crumbs !== '1') {
                    echo pbtheme_breadcrumbs();
                }
            } else {
                echo pbtheme_breadcrumbs();
            }
            ?>