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
	if( !isha_wcpg_is_falsy($atts['show_uncat'])) {
		$exclude = [get_term_by("slug", "uncategorized", "product_cat")->term_id];
	} else {
		$exclude = "all";
	}
	$categories = get_terms([
		'taxonomy' => 'product_cat', 
		'number' => (int) $atts['cats_count'],
		'exclude' => $exclude
	]);
}

$args = [
	'post_type' => 'product',
	'post_status' => 'publish',
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
		<ul class="isha_products_list isha-products">
			<?php if(count($query->posts) > 0) : ?>
				<?php foreach( $query->posts as $_product ): $product = wc_get_product($_product); ?>
					<?php require ISHA_WCPG_DIR . "inc/product_card.php" ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>
</div>