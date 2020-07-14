<?php
/**
 * Template for shortcode: isha_wc_categories
 * @since 1.0.0
 */
if(!defined("ABSPATH")) {
	die();
}

$args = [
	'taxonomy' => 'product_cat',
	'hide_empty' => true
];

// Number of the categories to get
$count = ((int) $atts['count']) === 0 ? 8 : (int) $atts['count'];
$args['number'] = $count;

// Orderby
if( in_array($atts['orderby'], ['name', 'count']) ) {
	$args['orderby'] = $atts['orderby'];
}

// Order
$order = trim( strtolower( $atts['order'] ) );
if( in_array( $order, ['asc', 'desc'] ) ) {
	$args['order'] = $order;
}

// Show uncategorized?
if( !isha_wcpg_is_falsy( $atts['show_uncat'] ) ) {
	$args['exclude'][] = get_term_by('name','Uncategorized', 'product_cat')->term_id;
}

$terms = get_terms($args);
?>

<div class="isha-wcpg-wrapper">
	<?php if( count($terms) > 0 ) : ?>
		<ul class="isha-cats">
			<?php foreach( $terms as $term ): ?>
				<li class="isha-cat isha-cat-<?php echo $term->term_id ?>">
					<a href="<?php echo get_term_link($term); ?>">
					<div class="isha-cat-head">
						<?php echo isha_wcpg_get_cat_image($term) ?>
					</div>
					<div class="isha-cat-body">
						<div class="isha-cat-title">
							<span><?php echo $term->name ?></span>
						</div>
					</div>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>