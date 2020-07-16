console.log(
	`######### Website Revamped by:: isharjeelahmad | isharjeelahmad@gmail.com | https://github.com/leo8545 #########`
);
document.addEventListener("DOMContentLoaded", (e) => {
	const cats = document.querySelectorAll(".isha_wcpg_cat");
	const wrapper = document.querySelector(".isha_cats_products_wrapper");
	if (cats) {
		[].forEach.call(cats, (cat) => {
			cat.addEventListener("click", (e) => {
				e.preventDefault();
				[].forEach.call(cat.parentNode.parentNode.children, (child) => {
					child.classList.remove("isha-active");
				});
				cat.parentNode.classList.add("isha-active", "isha-loading");
				const catId = parseInt(cat.getAttribute("data-cat_id"));
				const productsList = document.querySelector(
					`.isha_products_list[data-cat_id="${catId}"]`
				);
				if (!productsList) {
					const productsList = document.createElement("ul");
					[].forEach.call(wrapper.children, (child) => {
						child.classList.remove("isha-active-list");
					});
					productsList.classList.add(
						"isha_products_list",
						"isha-products",
						"isha-active-list"
					);
					productsList.setAttribute("data-cat_id", catId);
					productsList.innerHTML =
						"<span class='isha-loading'>Loading...</span>";
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
							if (res.posts) {
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
							} else {
								html = "No such products found.";
							}
							productsList.innerHTML = html;
							console.log(res);
						});
					})(jQuery);
					wrapper.appendChild(productsList);
				} else {
					[].forEach.call(wrapper.children, (child) => {
						child.classList.remove("isha-active-list");
					});
					productsList.classList.add("isha-active-list");
				}
			});
		});
	}
});
