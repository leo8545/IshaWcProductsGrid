<?php
/**
 * This file includes helper functions
 * @since 1.0.0
 */

if(!defined("ABSPATH")) {
	die();
}

if( !function_exists('isha_wcpg_get_product_price_html') ) {
	/**
	 * Returns html of the price for the product
	 *
	 * @param WC_Product $product
	 * @return string
	 */
	function isha_wcpg_get_product_price_html($product) {
		$html = '<span class="isha-product-gen-price">' . get_woocommerce_currency_symbol() . $product->get_price() . '</span>';
		if($product->is_on_sale()) {
			// Get percentage of discount
			$perc = (($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100;
			$html = '<span class="isha-product-sale-price">' . get_woocommerce_currency_symbol() . $product->get_sale_price() . '</span>';
			$html .= '<span class="isha-product-reg-price"><s>' . get_woocommerce_currency_symbol() . $product->get_regular_price() . '</s></span>';
			$html .= " <span class='isha-product-percentage'>-$perc%</span>";
		}
		return $html;
	}
}

if( !function_exists( "isha_wcpg_get_product_cat" ) ) {
	/**
	 * Returns first product category (WP_Term instance) if found
	 *
	 * @param WC_Product $product
	 * @param boolean $include_uncat Whether to include Uncategorized or not.
	 * @return mixed WP_Term if found, null otherwise
	 */
	function isha_wcpg_get_product_cat($product, $include_uncat = false) {
		$ids = $product->get_category_ids();
		$term = null;
		if(count($ids) > 0) {
			$term = get_term($ids[0], "product_cat");
			if($term->slug === "uncategorized" && $include_uncat === false) {
				$term = null;
			}
		}
		return $term;
	}
}

if(!function_exists("isha_wcpg_get_product_cat_html")) {
	/**
	 * Returns first product category anchor tag
	 *
	 * @param WC_Product $product
	 * @return string
	 */
	function isha_wcpg_get_product_cat_html($product) {
		$html = "";
		$term = isha_wcpg_get_product_cat($product);
		if($term && $term instanceof WP_Term) {
			$html = '<a href="' . get_term_link($term->term_id) . '">' . $term->name . '</a>';
		}
		return $html;
	}
}

if( !function_exists("isha_wcpg_is_falsy") ) {
	/**
	 * Checks if the argument is falsy or not
	 *
	 * @param mixed $arg
	 * @return boolean
	 */
	function isha_wcpg_is_falsy($arg) {
		$type = gettype($arg);
		if($type === "boolean" && $arg === false) return true;
		if($type === "string" && (empty($arg) || $arg === "false" || $arg === "0" || $arg === "null")) return true;
		if($type === "integer" && $arg === 0) return true;
		if($type === "array" && count($arg) === 0) return true;
		if($type === "NULL") return true;
		return false;
	}
}