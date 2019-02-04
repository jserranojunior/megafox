<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	divdemo_woo();

	function divdemo_woo() {
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

CREATE TABLE IF NOT EXISTS `@@TABLE_PREFIX@@woocommerce_attribute_taxonomies` (
  `attribute_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `attribute_name` varchar(200) NOT NULL,
  `attribute_label` longtext,
  `attribute_type` varchar(200) NOT NULL,
  `attribute_orderby` varchar(200) NOT NULL,
  PRIMARY KEY (`attribute_id`),
  KEY `attribute_name` (`attribute_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `@@TABLE_PREFIX@@woocommerce_attribute_taxonomies` (`attribute_id`, `attribute_name`, `attribute_label`, `attribute_type`, `attribute_orderby`) VALUES
(1, 'size', 'Size', 'text', 'menu_order'),
(2, 'color', 'Color', 'text', 'menu_order');

CREATE TABLE IF NOT EXISTS `@@TABLE_PREFIX@@woocommerce_termmeta` (
  `meta_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `woocommerce_term_id` bigint(20) NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `woocommerce_term_id` (`woocommerce_term_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

INSERT INTO `@@TABLE_PREFIX@@woocommerce_termmeta` (`meta_id`, `woocommerce_term_id`, `meta_key`, `meta_value`) VALUES
(1, 16, 'order', '0'),
(2, 16, 'product_count_product_cat', '0'),
(3, 17, 'product_count_product_tag', '0'),
(4, 57, 'order', '0'),
(5, 57, 'product_count_product_cat', '5'),
(6, 58, 'product_count_product_tag', '1'),
(7, 59, 'product_count_product_tag', '5'),
(8, 60, 'product_count_product_tag', '0'),
(9, 61, 'order_pa_size', '0'),
(10, 62, 'order_pa_size', '0'),
(11, 63, 'order_pa_size', '0'),
(12, 64, 'order_pa_size', '0'),
(13, 65, 'order_pa_size', '0'),
(14, 66, 'order_pa_size', '0'),
(15, 67, 'order_pa_color', '0'),
(16, 68, 'order_pa_color', '0'),
(17, 69, 'order_pa_color', '0'),
(18, 70, 'order_pa_color', '0'),
(19, 71, 'order_pa_color', '0'),
(20, 72, 'order_pa_color', '0'),
(21, 73, 'order_pa_color', '0'),
(22, 74, 'order_pa_size', '0'),
(23, 75, 'order_pa_color', '0'),
(24, 76, 'product_count_product_tag', '0'),
(25, 77, 'product_count_product_tag', '1'),
(26, 78, 'product_count_product_tag', '0'),
(27, 79, 'product_count_product_tag', '0'),
(28, 80, 'product_count_product_tag', '1'),
(29, 57, 'display_type', ''),
(30, 57, 'thumbnail_id', '431'),
(31, 81, 'order', '0'),
(32, 81, 'display_type', ''),
(33, 81, 'thumbnail_id', '471'),
(34, 81, 'product_count_product_cat', '6'),
(35, 82, 'product_count_product_tag', '0'),
(36, 83, 'product_count_product_tag', '5'),
(37, 84, 'product_count_product_tag', '0'),
(38, 85, 'product_count_product_tag', '0'),
(39, 86, 'product_count_product_tag', '1'),
(40, 87, 'product_count_product_tag', '1'),
(41, 88, 'product_count_product_tag', '0'),
(42, 89, 'product_count_product_tag', '1'),
(43, 90, 'product_count_product_tag', '1'),
(44, 91, 'product_count_product_tag', '1'),
(45, 92, 'product_count_product_tag', '1'),
(46, 93, 'product_count_product_tag', '1'),
(47, 94, 'product_count_product_tag', '1'),
(48, 95, 'product_count_product_tag', '1'),
(49, 96, 'product_count_product_tag', '1'),
(50, 97, 'product_count_product_tag', '1'),
(51, 98, 'order', '0'),
(52, 98, 'product_count_product_cat', '6'),
(53, 99, 'product_count_product_tag', '6'),
(54, 100, 'product_count_product_tag', '6'),
(55, 101, 'product_count_product_tag', '6'),
(56, 98, 'display_type', ''),
(57, 98, 'thumbnail_id', '486'),
(58, 102, 'order', '0'),
(59, 102, 'product_count_product_cat', '5'),
(60, 103, 'product_count_product_tag', '5'),
(61, 104, 'product_count_product_tag', '5'),
(62, 105, 'product_count_product_tag', '5'),
(63, 102, 'display_type', ''),
(64, 102, 'thumbnail_id', '507');

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
