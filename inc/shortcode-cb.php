<?php 
/**
 * This file includes the layout of the shortcode [isha_wc_products]
 * @since 1.0.0
 */

if(!defined("ABSPATH")) {
	die();
}
// Classes of the ul
$classes = ['isha-products'];

// Get products
$args = [
	'post_type' => 'product',
	'post_status' => 'publish'
];

// Number of the products
$count = ((int) $atts['count']) === 0 ? 10 : (int) $atts['count'];
$args['posts_per_page'] = $count;

// Featured products
$tax_query = [];
if(!isha_wcpg_is_falsy($atts['featured'])) {
	// $args['post__in'] = wc_get_featured_product_ids();
	$tax_query[] = array(
		'taxonomy' => 'product_visibility',
		'field'    => 'name',
		'terms'    => 'featured',
		'operator' => 'IN', // or 'NOT IN' to exclude feature products
	);
	if(count(wc_get_featured_product_ids()) > 0) {
		$classes[] = "isha-featured-products";
	}
}

// On sale products
if( !isha_wcpg_is_falsy($atts['onsale']) ) {
	$args['meta_query'] = [
		'relation' => 'OR',
		[ // Simple products
			'key' => '_sale_price',
			'value' => 0,
			'compare' => '>',
			'type' => 'numeric'
		],
		[ // Variable products
			'key' => '_min_variation_sale_price',
			'value' => 0,
			'compare' => '>',
			'type' => 'numeric'
		]
	];
	$classes[] = "isha-onsale-products";
}

// Order of the products
$order = trim( strtolower( $atts['order'] ) );
if( in_array( $order, ["asc", "desc"]) ) {
	$args['order'] = $order;
}

// Orderby of the products
$orderby = trim( strtolower( $atts['orderby'] ) );
if( in_array( $orderby, ['date', 'rand'] ) ) {
	$args['orderby'] = $orderby;
}

// Get products by category
if( !empty( $atts['cats'] ) ) {
	$tax_query[] = [
		'taxonomy' => 'product_cat',
		'field' => 'slug',
		'terms' => explode(",", $atts['cats'])
	];
}

// Get products by tags
if( !empty( $atts['tags'] ) ) {
	$tax_query[] = [
		'taxonomy' => 'product_tag',
		'field' => 'slug',
		'terms' => explode(",", $atts['tags'])
	 ];
}

// Get best sellers
if( !isha_wcpg_is_falsy($atts['best_seller']) ) {
	$args['meta_key'] = 'total_sales';
	$args['orderby'] = 'meta_value_num';
	$args['meta_query'][] =  [
		'key' => 'total_sales',
		'value' => 0,
		'compare' => '>'
	];
	$classes[] = "isha-products-bestseller";
}

if( count($tax_query) > 0 ) {
	$tax_query['relation'] = "AND";
}

$args['tax_query'] = $tax_query;

$query = new WP_Query($args);

?>

<div class="isha-wcpg-wrapper">
	<?php if( count($query->posts) > 0 ) : ?>
		<ul class="<?php echo implode(" ", $classes); ?>">
			<?php foreach( $query->posts as $_product ) : $product = wc_get_product($_product->ID); ?>
				<li class="isha-product isha-product-<?php echo $product->get_id() ?>">
					<div class="isha-product-head">
						<a href="<?php echo get_permalink($product->get_id()) ?>">
							<?php echo $product->get_image("woocommerce_thumbnail") ?>
						</a>
					</div>
					<div class="isha-product-body">
						<?php if(!empty(isha_wcpg_get_product_cat_html($product))): ?>
							<div class="isha-product-category"><?php echo isha_wcpg_get_product_cat_html($product) ?></div>
						<?php endif; ?>
						<div class="isha-product-title"><a href="<?php echo get_permalink($product->get_id()) ?>"><?php echo $product->get_name() ?></a></div>
						<div class="isha-product-price">
							<?php echo isha_wcpg_get_product_price_html($product) ?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>