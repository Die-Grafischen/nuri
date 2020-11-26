<?php

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

				<span>'.$parent_product_cat->name.'</span>

				<ul class="filter-child-cat">';

				$child_args = array(
					'taxonomy' => 'product_cat',
					'hide_empty' => false,
					'parent'   => $parent_product_cat->term_id
				);

				$child_product_cats = get_terms( $child_args );

				foreach ($child_product_cats as $child_product_cat) {

					echo '<li>'.$child_product_cat->name.'</li>';
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
	echo '</div>';
}

// Change related products title
add_filter( 'woocommerce_product_related_products_heading', 'change_related_title' );
function change_related_title() {
   return __( 'Ebenfalls sehr lecker:', 'woocommerce' );
}


?>
