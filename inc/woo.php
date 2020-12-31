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

// Remove Sidebar
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
	global $product;
	$product_id = $product->get_id();
	$additional_info = get_field('zusatzliche_info', $product_id);
    $product_tags = get_the_term_list($product_id, 'product_tag', '', '' );

	if($product_tags) {
		echo '<div class="single-product-tags">
			<strong>'. __( 'Passend als: ', 'nuri' ) .'</strong>
			'. $product_tags .'
		</div>';
	}

	if($additional_info) {
		echo '<div class="single-product-info">'. wp_kses_post($additional_info) .'</div>';
	}

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

// cart icon
function cart_svg() { ?>
	<svg xmlns="http://www.w3.org/2000/svg" width="52.246" height="33.949" viewBox="0 0 52.246 33.949">
		<g id="Group_8" data-name="Group 8" transform="translate(-634.19 -228.381)">
		<path id="Path_7" data-name="Path 7" d="M634.19,228.881h10.468l8.463,22.786h24.727l7.812-17.178H653.121" fill="none" stroke="#000" stroke-miterlimit="10" stroke-width="1"/>
		<circle id="Ellipse_2" data-name="Ellipse 2" cx="3.496" cy="3.496" r="3.496" transform="translate(670.879 254.839)" fill="none" stroke="#000" stroke-miterlimit="10" stroke-width="1"/>
		<circle id="Ellipse_3" data-name="Ellipse 3" cx="3.496" cy="3.496" r="3.496" transform="translate(649.621 254.839)" fill="none" stroke="#000" stroke-miterlimit="10" stroke-width="1"/>
		</g>
	</svg>
<?php }

// Add cart contents in header
function add_cart_link() { ?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'Einkaufswagen ansehen', 'nuri' ); ?>">


		<?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?> <span class="count"><?php echo wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'storefront' ), WC()->cart->get_cart_contents_count() ) );  ?></span>
		</a>

<?php }

// Add cart to header
function header_cart() { ?>
	<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'Einkaufswagen ansehen', 'nuri' ); ?>">
		<?php cart_svg(); ?>
		<?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?>
		<span class="count">
			<?php echo wp_kses_data( sprintf( _n( '%d Artikel', '%d Artikeln', WC()->cart->get_cart_contents_count(), 'nuri' ), WC()->cart->get_cart_contents_count() ) );  ?>
		</span>
	</a>
<?php }

// Update cart contents in header with Ajax
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();

	?>
	<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'Einkaufswagen ansehen', 'nuri' ); ?>">
		<?php cart_svg(); ?>

		<?php echo $woocommerce->cart->get_cart_total(); ?>
		<span class="count">
			<?php echo wp_kses_data( sprintf( _n( '%d Artikel', '%d Artikeln', $woocommerce->cart->cart_contents_count, 'nuri' ), $woocommerce->cart->cart_contents_count ) );  ?>
		</span>

	</a>
	<?php
	$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
}

// Remove WOO pagination
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );



/********************* AJAX SHOP ****************/



// Insert 'Mehr anzeigen' button after product loop
add_action('woocommerce_after_shop_loop', 'more_button');
function more_button() {
	global $wp_query;
	$post_count = $wp_query->post_count;
	$total_posts = $wp_query->found_posts;

	if( $post_count < $total_posts ) {
		echo '<div class="load-more-wrapper">
	        <div id="ajax-load-more-products" class="more-btn button" >Mehr anzeigen</div>
	    </div>';
	}
}

// create custom rest api route
add_action('rest_api_init', 'custom_api_get_products');
function custom_api_get_products(){
  register_rest_route( 'products', '/all', array(
    'methods' => 'GET',
    'callback' => 'custom_api_get_products_callback',
	'permission_callback' => '__return_true'
  ));
}

// callback function for the rest api route
function custom_api_get_products_callback($request){
    $posts_data = array();
    $paged = $request->get_param('page');
    $paged = (isset($paged) || !(empty($paged))) ? $paged : 1;
    $args = array(
      'status'          => 'publish',
      'page'           => $paged
    );

	$products = wc_get_products( $args );
	foreach ($products as $product) {
		$product_id = $product->get_id();
		$product_title = $product->get_title();
		$product_price = $product->get_price();
		$product_thumbnail = $product->get_image();
		$terms = get_the_terms( $product_id, 'product_cat' );
		$product_categories = '';
		if($terms) {

			foreach($terms as $term) {
				$product_categories .= 'product_cat_'. $term->slug .' ';
			}
		}

		$posts_data[] = (object)array(
			'product_id' => $product_id,
			'product_title' => $product_title,
			'product_price' => $product_price,
			'product_terms' => $product_categories,
			'product_thumbnail' => $product_thumbnail
		);
	}



	wp_reset_postdata();

	return $posts_data;

}


?>
