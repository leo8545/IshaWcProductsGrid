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