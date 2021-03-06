<?php
/**
 * Template for shortcode: isha_wc_cats_on_products
 * @since 1.0.0
 */

if(!defined("ABSPATH")) {
	die();
}

$categories = [];
if( !empty( $atts['cats'] ) ) {
	$cats = explode(",", trim($atts['cats']));
	foreach($cats as $cat) {
		$term = get_term_by('slug', $cat, 'product_cat');
		if( $term instanceof WP_Term ) {
			$categories[] = $term;
		}
	}
} else {
	// Get categories

	// Show uncategorized?
	if( !isha_wcpg_is_falsy($atts['show_uncat'])) {
		$exclude = [get_term_by("slug", "uncategorized", "product_cat")->term_id];
	} else {
		$exclude = "all";
	}

	// Cats orderby
	$cats_orderby = 'count';
	if( !empty($atts['cats_orderby']) && in_array( $atts['cats_orderby'], ['name', 'count'] ) ) {
		$cats_orderby = $atts['cats_orderby'];
	}

	$categories = get_terms([
		'taxonomy' => 'product_cat', 
		'number' => (int) $atts['cats_count'],
		'exclude' => $exclude,
		'orderby' => $cats_orderby,
		'order' => 'desc'
	]);
}
$meta_query = [];
if( !isha_wcpg_is_falsy( $atts['show_product_with_image_only'] ) ) {
	$meta_query[] = [
		'key' => '_thumbnail_id',
		'compare' => 'EXISTS'
	];
}

$args = [
	'post_type' => 'product',
	'post_status' => 'publish',
	'meta_query' => $meta_query,
	'tax_query' => [[
		'taxonomy' => 'product_cat',
		'field' => 'slug',
		'terms' => [$categories[0]->slug]
	]]
];

$query = new WP_Query($args);
?>
<div class="isha_wcpg_wrapper isha_cats_on_prod_wrapper">
	<?php if(count($categories) > 0): ?>
		<ul class="isha_cats_list">
			<?php foreach($categories as $index => $cat): ?>
				<li class="<?php echo $index===0 ? 'isha-active': "" ?>">
					<span data-cat_id="<?php echo $cat->term_id ?>" class="isha_wcpg_cat"><?php echo $cat->name ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<div class="isha_cats_products_wrapper">
		<ul class="isha_products_list isha-products isha-active-list" data-cat_id='<?php echo $categories[0]->term_id ?>'>
			<?php if(count($query->posts) > 0) : ?>
				<?php foreach( $query->posts as $_product ): $product = wc_get_product($_product); ?>
					<?php require ISHA_WCPG_DIR . "inc/product_card.php" ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>
</div>