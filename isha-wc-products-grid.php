<?php
/**
 * Plugin Name: Isha WC Products Grid
 * Author: Sharjeel Ahmad
 * Author URI: https://github.com/leo8545
 * Description: Shows products in a sleek grid layout.
 */

if(!defined("ABSPATH")) {
	die();
}

define("ISHA_WCPG_DIR", plugin_dir_path(__FILE__));
define("ISHA_WCPG_URI", plugin_dir_url(__FILE__));
define("ISHA_WCPG_VERSION", "1.0.0");

final class Isha_WCPG
{
	public function __construct()
	{
		$this->define_public_hooks();
	}

	public function define_public_hooks()
	{
		add_action("wp_enqueue_scripts", [$this, "enqueue_public_scripts"]);

		// Shortcode
		add_shortcode("isha_wc_products", [$this, "shortcode_callback"]);
	}

	public function enqueue_public_scripts()
	{
		$dir = ISHA_WCPG_URI . "/assets/";
		wp_enqueue_style("isha-wcpg-style", $dir . "css/public-style.min.css", [], ISHA_WCPG_VERSION);
	}

	public function shortcode_callback($atts)
	{
		/**
		 * Attributes
		 * count, featured, onsale, latest, random
		 */
		$atts = shortcode_atts([
			"count" 		=> 	10,
			"featured" 	=> 	false,
			"onsale" 	=> 	false,
			"latest" 	=> 	false,
			"random" 	=> 	false
		], $atts);

		ob_start();
		require ISHA_WCPG_DIR . "inc/shortcode-cb.php";
		$output = ob_get_clean();
		return $output;
	}
}
new Isha_WCPG;