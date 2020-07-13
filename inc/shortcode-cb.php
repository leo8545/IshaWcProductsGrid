<?php 
/**
 * This file includes the layout of the shortcode [isha_wc_products]
 * @since 1.0.0
 */

if(!defined("ABSPATH")) {
	die();
}
echo (int) $atts['count'];
// Get products
$args = [
	'post_type' => 'product',
	'post_status' => 'publish',
	'posts_per_page' => (int) $atts['count']
];