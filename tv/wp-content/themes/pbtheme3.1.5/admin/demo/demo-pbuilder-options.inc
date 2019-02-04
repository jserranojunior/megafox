<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	divdemo_pbuilder_options();

	function divdemo_pbuilder_options() {
		global $wpdb, $wp_rewrite;
		if (!get_option("blogname", false)) {
			return;
		}
	ob_start();
?>SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

DROP TABLE IF EXISTS `@@TABLE_PREFIX@@profit_builder_options`;
CREATE TABLE IF NOT EXISTS `@@TABLE_PREFIX@@profit_builder_options` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=164 ;

INSERT INTO `@@TABLE_PREFIX@@profit_builder_options` (`id`, `name`, `value`) VALUES
(1, 'save_overwrite', 'true'),
(2, 'css_classes', 'flase'),
(3, 'bottom_margin', '36'),
(4, 'high_rezolution_width', '1200'),
(5, 'high_rezolution_margin', '36'),
(6, 'med_rezolution_width', '768'),
(7, 'med_rezolution_margin', '18'),
(8, 'med_rezolution_hide_sidebar', 'false'),
(9, 'low_rezolution_width', '640'),
(10, 'low_rezolution_margin', '12'),
(11, 'low_rezolution_hide_sidebar', 'true'),
(12, 'mob_rezolution_hide_sidebar', 'true'),
(13, 'h1_font_family', 'default'),
(14, 'h1_font_style', 'default'),
(15, 'h1_font_size', '16 px'),
(16, 'h1_line_height', '16 px'),
(17, 'h2_font_family', 'default'),
(18, 'h2_font_style', 'default'),
(19, 'h2_font_size', '16 px'),
(20, 'h2_line_height', '16 px'),
(21, 'h3_font_family', 'default'),
(22, 'h3_font_style', 'default'),
(23, 'h3_font_size', '16 px'),
(24, 'h3_line_height', '16 px'),
(25, 'h4_font_family', 'default'),
(26, 'h4_font_style', 'default'),
(27, 'h4_font_size', '16 px'),
(28, 'h4_line_height', '16 px'),
(29, 'h5_font_family', 'default'),
(30, 'h5_font_style', 'default'),
(31, 'h5_font_size', '16 px'),
(32, 'h5_line_height', '16 px'),
(33, 'h6_font_family', 'default'),
(34, 'h6_font_style', 'default'),
(35, 'h6_font_size', '16 px'),
(36, 'h6_line_height', '16 px'),
(37, 'button_font_family', 'Sanchez'),
(38, 'button_font_style', 'regular'),
(39, 'button_font_size', '16 px'),
(40, 'button_line_height', '16 px'),
(41, 'slider_font_family', 'Sanchez'),
(42, 'slider_font_style', 'italic'),
(43, 'slider_font_size', '26 px'),
(44, 'slider_line_height', '34 px'),
(45, 'testimonial_name_font_family', 'Noto+Serif'),
(46, 'testimonial_name_font_style', 'regular'),
(47, 'testimonial_name_font_size', '16 px'),
(48, 'testimonial_name_line_height', '24 px'),
(49, 'testimonial_profession_font_family', 'default'),
(50, 'testimonial_profession_font_style', 'default'),
(51, 'testimonial_profession_font_size', '16 px'),
(52, 'testimonial_profession_line_height', '16 px'),
(53, 'testimonial_quote_font_family', 'PT+Sans'),
(54, 'testimonial_quote_font_style', 'italic'),
(55, 'testimonial_quote_font_size', '14 px'),
(56, 'testimonial_quote_line_height', '24 px'),
(57, 'tabs_title_font_family', 'Sanchez'),
(58, 'tabs_title_font_style', 'regular'),
(59, 'tabs_title_font_size', '14 px'),
(60, 'tabs_title_line_height', '22 px'),
(61, 'tabs_content_font_family', 'default'),
(62, 'tabs_content_font_style', 'default'),
(63, 'tabs_content_font_size', '16 px'),
(64, 'tabs_content_line_height', '16 px'),
(65, 'accordion_title_font_family', 'Noto+Sans'),
(66, 'accordion_title_font_style', 'regular'),
(67, 'accordion_title_font_size', '16 px'),
(68, 'accordion_title_line_height', '16 px'),
(69, 'accordion_content_font_family', 'PT+Sans'),
(70, 'accordion_content_font_style', 'regular'),
(71, 'accordion_content_font_size', '14 px'),
(72, 'accordion_content_line_height', '24 px'),
(73, 'alert_text_font_family', 'default'),
(74, 'alert_text_font_style', 'default'),
(75, 'alert_text_font_size', '16 px'),
(76, 'alert_text_line_height', '16 px'),
(77, 'menu_main_font_family', 'default'),
(78, 'menu_main_font_style', 'default'),
(79, 'menu_main_font_size', '16 px'),
(80, 'menu_main_line_height', '16 px'),
(81, 'menu_submenu_font_family', 'default'),
(82, 'menu_submenu_font_style', 'default'),
(83, 'menu_submenu_font_size', '16 px'),
(84, 'menu_submenu_line_height', '16 px'),
(85, 'features_title_font_family', 'Noto+Sans'),
(86, 'features_title_font_style', 'regular'),
(87, 'features_title_font_size', '18 px'),
(88, 'features_title_line_height', '26 px'),
(89, 'features_content_font_family', 'default'),
(90, 'features_content_font_style', 'default'),
(91, 'features_content_font_size', '16 px'),
(92, 'features_content_line_height', '16 px'),
(93, 'searchbox_font_family', 'default'),
(94, 'searchbox_font_style', 'default'),
(95, 'searchbox_font_size', '16 px'),
(96, 'searchbox_line_height', '16 px'),
(97, 'image_desc_font_family', 'default'),
(98, 'image_desc_font_style', 'default'),
(99, 'image_desc_font_size', '16 px'),
(100, 'image_desc_line_height', '16 px'),
(101, 'pricing_table_title_font_family', 'default'),
(102, 'pricing_table_title_font_style', 'default'),
(103, 'pricing_table_title_font_size', '16 px'),
(104, 'pricing_table_title_line_height', '16 px'),
(105, 'pricing_table_price_font_family', 'default'),
(106, 'pricing_table_price_font_style', 'default'),
(107, 'pricing_table_price_font_size', '16 px'),
(108, 'pricing_table_price_line_height', '16 px'),
(109, 'pricing_table_button_font_family', 'default'),
(110, 'pricing_table_button_font_style', 'default'),
(111, 'pricing_table_button_font_size', '60 px'),
(112, 'pricing_table_button_line_height', '60 px'),
(113, 'pricing_table_text_font_family', 'default'),
(114, 'pricing_table_text_font_style', 'default'),
(115, 'pricing_table_text_font_size', '16 px'),
(116, 'pricing_table_text_line_height', '16 px'),
(117, 'featured_post_title_font_family', 'default'),
(118, 'featured_post_title_font_style', 'default'),
(119, 'featured_post_title_font_size', '16 px'),
(120, 'featured_post_title_line_height', '16 px'),
(121, 'featured_post_meta_font_family', 'default'),
(122, 'featured_post_meta_font_style', 'default'),
(123, 'featured_post_meta_font_size', '16 px'),
(124, 'featured_post_meta_line_height', '16 px'),
(125, 'featured_post_excerpt_font_family', 'default'),
(126, 'featured_post_excerpt_font_style', 'default'),
(127, 'featured_post_excerpt_font_size', '16 px'),
(128, 'featured_post_excerpt_line_height', '16 px'),
(129, 'featured_post_button_font_family', 'default'),
(130, 'featured_post_button_font_style', 'default'),
(131, 'featured_post_button_font_size', '16 px'),
(132, 'featured_post_button_line_height', '16 px'),
(133, 'main_color', '#82b440'),
(134, 'light_main_color', '#82b440'),
(135, 'dark_back_color', '#222222'),
(136, 'light_back_color', '#cccccc'),
(137, 'dark_border_color', '#222222'),
(138, 'light_border_color', '#cccccc'),
(139, 'title_color', '#222222'),
(140, 'text_color', '#444444'),
(141, 'main_back_text_color', '#ffffff'),
(142, 'column_back_opacity', '100'),
(143, 'templates', '{}'),
(144, 'bullets_font_family', 'default'),
(145, 'bullets_font_style', 'default'),
(146, 'bullets_font_size', '16 px'),
(147, 'bullets_line_height', '16 px'),
(148, 'counter_font_family', 'default'),
(149, 'counter_font_style', 'default'),
(150, 'counter_font_size', '26 px'),
(151, 'counter_line_height', '34 px'),
(152, 'percentage_chart_font_family', 'default'),
(153, 'percentage_chart_font_style', 'default'),
(154, 'percentage_chart_font_size', '16 px'),
(155, 'percentage_chart_line_height', '16 px'),
(156, 'piechart_font_family', 'default'),
(157, 'piechart_font_style', 'default'),
(158, 'piechart_font_size', '16'),
(159, 'piechart_line_height', '16'),
(160, 'graph_font_family', 'default'),
(161, 'graph_font_style', 'default'),
(162, 'graph_font_size', '16'),
(163, 'graph_line_height', '16');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
<?php
	$sql   =  ob_get_clean();

	$sql   = str_replace(array("@@TABLE_PREFIX@@", "@@SITE_URL@@"),array($wpdb->prefix, get_option("siteurl")),$sql);
	$lines = explode("\n", $sql);
	$query = "";

	foreach ($lines as $line) {
		$line = trim($line);
		if (strlen($line)==0) continue;
		if (substr($line,-1)==";") {
			$query.=" ".$line;
			$wpdb->query($query);
			$query = "";
		} else {
			$query.=" ".$line;
		}
	}

	$wp_rewrite->flush_rules();
	wp_cache_flush();
}
