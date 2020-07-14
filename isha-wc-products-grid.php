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
		$this->load_dep();
		$this->define_public_hooks();
	}

	public function load_dep()
	{
		require ISHA_WCPG_DIR . 'inc/helpers.php';
	}

	public function define_public_hooks()
	{
		add_action("wp_enqueue_scripts", [$this, "enqueue_public_scripts"]);

		// Shortcode to show products
		add_shortcode("isha_wc_products", [$this, "shortcode_callback"]);

		// Shortcode to show categories
		add_shortcode("isha_wc_categories", [$this, "shortcode_categories_callback"]);

		// Shortcode for click on cat to show products
		add_shortcode("isha_wc_cats_on_products", [$this, 'shortcode_cats_on_products_callback']);

		// Ajax functions
		add_action("wp_ajax_getProductsByCat", [$this, 'getProductsByCat']);
		add_action("wp_ajax_nopriv_getProductsByCat", [$this, 'getProductsByCat']);
	}

	public function enqueue_public_scripts()
	{
		$dir = ISHA_WCPG_URI . "/assets/";
		wp_enqueue_style("isha-wcpg-style", $dir . "css/public-style.min.css", [], ISHA_WCPG_VERSION);
		wp_enqueue_script("isha-wcpg-script", $dir . "js/public-main.js", ['jquery'], ISHA_WCPG_VERSION);
		wp_localize_script("isha-wcpg-script", "ajax_object", [
			'ajax_url' => admin_url( 'admin-ajax.php' )
		]);
	}

	public function shortcode_callback($atts)
	{
		/**
		 * Attributes
		 * count, featured, onsale, random
		 */
		$atts = shortcode_atts([
			"count" 		=> 	10,
			"featured" 	=> 	false,
			"onsale" 	=> 	false,
			"order" 		=> 	'desc',
			"orderby" 	=> 	'date',
			"cats"		=>		'',
			"tags"		=>		'',
			"best_seller" => false
		], $atts);

		ob_start();
		require ISHA_WCPG_DIR . "inc/shortcode-cb.php";
		$output = ob_get_clean();
		return $output;
	}

	public function shortcode_categories_callback($atts)
	{
		$atts = shortcode_atts([
			"count" => 8,
			'orderby' => 'count',
			'order' => 'desc',
			'show_uncat' => true
		], $atts);

		ob_start();
		require ISHA_WCPG_DIR . "inc/shortcode_categories_cb.php";
		$output = ob_get_clean();
		return $output;
	}

	public function shortcode_cats_on_products_callback($atts)
	{
		$atts = shortcode_atts([
			'cats_count' => 5,
			'product_count' => 5,
			'cats' => '',
			"show_uncat" => true
		], $atts);
		ob_start();
		require ISHA_WCPG_DIR . "inc/shortcode_cats_on_products_cb.php";
		$output = ob_get_clean();
		return $output;
	}

	public function getProductsByCat()
	{
		$cat_id = (int) $_POST['catId'];
		$data = [];
		if($cat_id > 0) {
			$query = new WP_Query([
				'post_type' => 'product',
				'tax_query' => [[
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => [$cat_id]
				]]
			]);
			foreach( $query->posts as $index => $post ) {
				$product = wc_get_product($post);
				$data['posts'][$index] = $post;
				$data['posts'][$index]->permalink = get_permalink($post->ID);
				$data['posts'][$index]->thumbnail = $product->get_image();
				$data['posts'][$index]->cat_html = isha_wcpg_get_product_cat_html($product);
				$data['posts'][$index]->price_html = isha_wcpg_get_product_price_html($product);
			}
			echo json_encode($data);
			wp_reset_query();
		}
		wp_die();
	}
}
new Isha_WCPG;