document.addEventListener("DOMContentLoaded", (e) => {
	const cats = document.querySelectorAll(".isha_wcpg_cat");
	if (cats) {
		[].forEach.call(cats, (cat) => {
			cat.addEventListener("click", (e) => {
				e.preventDefault();
				[].forEach.call(cat.parentNode.parentNode.children, (child) => {
					child.classList.remove("isha-active");
				});
				cat.parentNode.classList.add("isha-active", "isha-loading");
				const catId = parseInt(cat.getAttribute("data-cat_id"));
				const productsList = document.querySelector(".isha_products_list");
				productsList.innerHTML = "<span class='isha-loading'>Loading...</span>";
				(function ($) {
					$.ajax({
						url: ajax_object.ajax_url,
						method: "post",
						data: {
							action: "getProductsByCat",
							catId,
						},
					}).success((_res) => {
						res = JSON.parse(_res);
						html = "";
						res.posts.forEach((product) => {
							cat_html = "";
							if (product.cat_html.length) {
								cat_html = `<div class="isha-product-category">${product.cat_html}</div>`;
							}
							html += `<li class="isha-product isha-product-${product.ID}">
								<div class="isha-product-head">
									<a href="${product.permalink}">${product.thumbnail}</a>
								</div>
								<div class="isha-product-body">
									${cat_html}
									<div class="isha-product-title"><a href="${product.permalink}">${product.post_title}</a></div>
									<div class="isha-product-price">${product.price_html}</div>
								</div>
							</li>`;
						});
						productsList.innerHTML = html;
						console.log(res);
					});
				})(jQuery);
			});
		});
	}
});
