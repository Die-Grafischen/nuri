<?php

// Enqueue Masonry js for shop/archive
add_action('pre_get_posts','isotope');
function isotope(){

	if( is_product_category() || is_shop()) {

    	$theme_version = wp_get_theme()->get( 'Version' );

    	wp_enqueue_script( 'isotope', get_template_directory_uri() . '/assets/js/isotope.min.js', array('jquery'), $theme_version, false );

    }

}

// Remove Breadcrumbs from Shop
add_action('template_redirect', 'remove_shop_breadcrumbs' );
function remove_shop_breadcrumbs(){
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
}

// Remove Shop Title
add_filter( 'woocommerce_show_page_title', 'bbloomer_hide_shop_page_title' );
function bbloomer_hide_shop_page_title( $title ) {
   if ( is_shop() ) $title = false;
   return $title;
}

// Remove Sidaber
add_action('init', 'disable_woo_commerce_sidebar');
function disable_woo_commerce_sidebar() {
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
}

// Remove add to cart button from shop page
add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );
function remove_add_to_cart_buttons() {
	if( is_product_category() || is_shop()) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	}
}

// Remove ordering
add_action( 'before_woocommerce_init', function() {
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
} );

// Remove product count
add_action( 'after_setup_theme', 'my_remove_product_result_count', 99 );
function my_remove_product_result_count() {
    remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_after_shop_loop' , 'woocommerce_result_count', 20 );
}

// Add filter to shop pages
add_action('woocommerce_before_shop_loop', 'woo_custom_filter');
function woo_custom_filter() {
	echo '<div class="woo-custom-filter">';
	woo_categories_filter();
		echo '<span class="clear-filter">Filter zurücksetzen</span>
	</div>';
}

function woo_categories_filter() {

	$args = array(
		'taxonomy' => 'product_cat',
		'hide_empty' => false, //temp
		'parent'   => 0
	);

	$product_cat = get_terms( $args );

	echo '<ul>';
	foreach ($product_cat as $parent_product_cat) {

		echo'<li class="filter-parent-cat">

			<span data-filter=".product_cat-'. esc_attr($parent_product_cat->slug) .'" class="product-parent-selector">
				'. esc_attr($parent_product_cat->name) .'<i class="filter-icon"></i>
			</span>
			<ul class="filter-child-cat">';

				$child_args = array(
					'taxonomy' => 'product_cat',
					'hide_empty' => false,
					'parent'   => $parent_product_cat->term_id
				);

				$child_product_cats = get_terms( $child_args );

				foreach ($child_product_cats as $child_product_cat) {
					echo '<li>
						<div class="pretty p-default p-fill p-svg p-tada">
							<input type="checkbox" data-filter=".product_cat-'. esc_attr($child_product_cat->slug) .'" />
							<div class="state">
								<!-- svg path -->
								<svg class="svg svg-icon" viewBox="0 0 20 20">
								<path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
								</svg>
								<label>'.$child_product_cat->name.'</label>
							</div>
						</div>
					</li>';
				}

			echo '</ul>

		</li>';
	}

	echo '</ul>';
}

// Change 'add to cart' text
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' );
function woocommerce_custom_single_add_to_cart_text() {
    return __( 'In den Einkaufswagen', 'woocommerce' );
}

// Wrap single product thumbnail + description in div
add_filter( 'woocommerce_before_single_product_summary', 'wrap_product_start');
function wrap_product_start() {
	echo '<div class="product-wrap">';
}

add_filter( 'woocommerce_after_single_product_summary', 'wrap_product_end');
function wrap_product_end() {
	// display default single product content
	echo '<div class="single-product-content">';
		the_content();
	echo '</div>';

	// display product tags
	global $products;
    $product_id = $product->id;
    $product_tags = get_the_term_list($product_id, 'product_tag', '', '' );

    echo '<div class="single-product-tags">
		<strong>'. __( 'Passend als: ', 'nuri' ) .'</strong>
		'. $product_tags .'
	</div>';

	echo '</div>';
}

// Remove Woo Tabs in single
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

// Remove description heading in single
add_filter('woocommerce_product_description_heading', '__return_null');

// Change related products title in single
add_filter( 'woocommerce_product_related_products_heading', 'change_related_title' );
function change_related_title() {
   return __( 'Ebenfalls sehr lecker:', 'woocommerce' );
}


?>
