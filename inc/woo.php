<?php

/**
 * Optimize WooCommerce Scripts
 * Remove WooCommerce Generator tag, styles, and scripts from non WooCommerce pages.
 */
add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );

function child_manage_woocommerce_styles() {
	//remove generator meta tag
	remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

	//first check that woo exists to prevent fatal errors
	if ( function_exists( 'is_woocommerce' ) ) {
		//dequeue scripts and styles
		if ( ! is_woocommerce() && ! is_cart() && ! is_checkout()  ) {
			wp_dequeue_style( 'woocommerce_frontend_styles' );
			wp_dequeue_style( 'woocommerce_fancybox_styles' );
			wp_dequeue_style( 'woocommerce_chosen_styles' );
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_script( 'wc_price_slider' );
			wp_dequeue_script( 'wc-single-product' );
			wp_dequeue_script( 'wc-add-to-cart' );
			wp_dequeue_script( 'wc-cart-fragments' );
			wp_dequeue_script( 'wc-checkout' );
			wp_dequeue_script( 'wc-add-to-cart-variation' );
			wp_dequeue_script( 'wc-cart' );
			wp_dequeue_script( 'wc-chosen' );
			wp_dequeue_script( 'woocommerce' );
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
			wp_dequeue_script( 'jquery-blockui' );
			wp_dequeue_script( 'jquery-placeholder' );
			wp_dequeue_script( 'fancybox' );
			wp_dequeue_script( 'jqueryui' );
		}
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

// Add product id to product class in SHOP
add_filter( 'woocommerce_post_class', 'remove_post_class', 21, 3 ); //woocommerce use priority 20, so if you want to do something after they finish be more lazy
function remove_post_class( $classes ) {
    if ( 'product' == get_post_type() ) {
		global $product;
		$productClasses = '';
		$terms = get_the_terms( $product->get_id(), 'product_cat' );
		foreach ($terms as $term) {
		    $product_cat_id = $term->term_id;
		    $productClasses .=  ' product_cat-'.$product_cat_id;
		}
		$classes[] .= $productClasses;
    }
    return $classes;
}

// Customize product thumbnail in loop
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'custom_loop_product_thumbnail', 10 );
function custom_loop_product_thumbnail() {
    global $product;
    $size = 'woocommerce_single';

    $image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );

    echo $product ? $product->get_image( $image_size ) : '';
}

// Add filter to shop pages
add_action('woocommerce_before_shop_loop', 'woo_custom_filter');
function woo_custom_filter() {

	global $wp_query;
	$postCount = $wp_query->post_count;

	echo '<div class="woo-custom-filter" data-postcount="'. esc_attr($postCount) .'">';
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

			<span data-filter=".product_cat-'. esc_attr($parent_product_cat->term_id) .'" class="product-parent-selector">
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
					if(!$child_product_cat->count) {
						echo '<li>
							<div class="pretty p-default p-fill p-svg p-tada">
								<input type="checkbox" data-filter=".product_cat-'. esc_attr($child_product_cat->term_id) .'" />
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

// insert product attributes after price for simple products
add_filter( 'woocommerce_get_price_html', 'show_attributes' );
function show_attributes($price){
	if ( 'product' == get_post_type() ) {

		global $product;

		if( $product->is_type( 'simple' ) ){
			$attributes = $product->get_attributes();
			$attributes_string = '<span class="attributes-string">';

			foreach ( $attributes as $attribute ):

				if($attribute->get_visible()) {
					$attribute_terms = $attribute->get_terms(); // The terms
					$attributes_string .=  $attribute_terms[0]->name . ' ';
				}

			endforeach;

			$attributes_string .='</span>';

			return $price .   $attributes_string;
		} else {
			return $price;
		}

	} else {
		return $price;
	}

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

	//combine cross sell products & related products
	$cross_sell_ids = $product->get_cross_sell_ids() ? $product->get_cross_sell_ids() : [];
	$related =  wc_get_related_products($product_id, 4);
	$combined_ids = array_merge($cross_sell_ids,$related);
	$combined_ids = array_slice($combined_ids,0,4);

	echo '<section class="related related-custom products">
		<h2>Passend dazu:</h2>';
		woocommerce_product_loop_start();
		foreach ( $combined_ids as $combined_id ) :

			$post_object = get_post( $combined_id );
			setup_postdata( $GLOBALS['post'] =& $post_object );
			wc_get_template_part( 'content', 'product' );

		endforeach;
		woocommerce_product_loop_end();
	echo '</section>';

}

// Remove default upsells and cross sells from single product
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );


// Remove Woo Tabs in single
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

// Remove description heading in single
add_filter('woocommerce_product_description_heading', '__return_null');

// Change related products title in single
add_filter( 'woocommerce_product_related_products_heading', 'change_related_title' );
function change_related_title() {
   return __( 'Passend dazu:', 'woocommerce' );
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

		<?php cart_svg(); ?>

		<span class="count">
			<?php $count = $woocommerce->cart->cart_contents_count;
			if($count) {
				echo intval($count);
			} else {
				 echo ' ';
			}?>
		</span>

<?php }

// Add slider navigation
add_filter( 'woocommerce_single_product_carousel_options', 'cuswoo_update_woo_flexslider_options' );

function cuswoo_update_woo_flexslider_options( $options ) {
	$options['controlNav'] = true;
    return $options;
}

// Add cart to header
function header_cart() {  global $woocommerce;?>
	<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'Einkaufswagen ansehen', 'nuri' ); ?>">
		<?php cart_svg(); ?>

		<span class="count">
			<?php $count = $woocommerce->cart->cart_contents_count;
			if($count) {
				echo intval($count);
			} else {
				 echo ' ';
			}?>
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

		<span class="count">
			<?php $count = $woocommerce->cart->cart_contents_count;
			if($count) {
				echo intval($count);
			} else {
				 echo ' ';
			}?>
		</span>

	</a>
	<?php
	$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
}

// Remove WOO pagination
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

// Add all image sizes to rest endpoint
function prepare_product_images($response, $post, $request) {
    global $_wp_additional_image_sizes;

    if (empty($response->data)) {
        return $response;
    }

    foreach ($response->data['images'] as $key => $image) {
        $image_urls = [];
        foreach ($_wp_additional_image_sizes as $size => $value) {
            $image_info = wp_get_attachment_image_src($image['id'], $size);
            $response->data['images'][$key][$size] = $image_info[0];
        }
    }
    return $response;

}

add_filter("woocommerce_rest_prepare_product_object", "prepare_product_images", 10, 3);

/********************* AJAX SHOP ****************/



// Insert 'Mehr anzeigen' button after product loop
add_action('woocommerce_after_shop_loop', 'more_button');
function more_button() {
	global $wp_query;
	$post_count = $wp_query->post_count;
	$total_posts = $wp_query->found_posts;

	if( $post_count < $total_posts ) {
		echo '<div class="load-more-wrapper is-style-outline">
			<div class="lds-ellipsis">
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
	    </div>';
	}
}
//<div id="ajax-load-more-products" class="more-btn wp-block-button__link button no-border-radius" >Mehr anzeigen</div>

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

// Remove reset variations button on single product
add_filter('woocommerce_reset_variations_link', '__return_empty_string');


// Change Sale/Angebot text to Aktion
add_filter('woocommerce_sale_flash', 'ds_change_sale_text');
function ds_change_sale_text() {
	return '<span class="onsale">Aktion!</span>';
}

// Style variation with default woo library / select2
add_action( 'wp_enqueue_scripts', 'style_select' );
function style_select() {
	if ( 'product' == get_post_type() ) {

		$product = new WC_Product( get_the_ID() );
		wp_enqueue_script('selectWoo');
		wp_enqueue_style('select2');

	}
}

// Remove state/kanton from checkout, edit placeholders
function remove_state_field( $fields ) {
	unset( $fields['state'] );
    $fields['address_2']['placeholder'] = 'Zusätzliche Adressangaben (optional)';

	return $fields;
}
add_filter( 'woocommerce_default_address_fields', 'remove_state_field' );


?>
