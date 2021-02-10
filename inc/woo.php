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
add_filter( 'woocommerce_show_page_title', 'hide_page_title' );
function hide_page_title( $title ) {
   if ( is_shop() || is_archive() ) $title = false;
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
    $size = 'shop_catalog';

    $image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );

    echo $product ? $product->get_image( $image_size ) : '';
}

// Add filter to shop pages
add_action('woocommerce_before_shop_loop', 'woo_custom_filter');
function woo_custom_filter() {

	global $wp_query;
	//var_dump($wp_query);
	$query_cat = isset($wp_query->query_vars['product_cat']) ? $wp_query->query_vars['product_cat'] : 0; // the category slug if this is category page

	$query_cat_term = get_term_by( 'slug', $query_cat, 'product_cat' ) ?: '';
	$query_cat_id = $query_cat_term ? $query_cat_term->term_id : '';

	//do_action( 'qm/debug', $wp_query );
	$query_cat_parent = isset($wp_query->query['product_cat']) ? $wp_query->query['product_cat'] : 0;

	$query_parent_slug = $query_cat_parent ? strtok($query_cat_parent, '/') : 0;

	$postCount = $wp_query->post_count;

	if( isset( $wp_query->query['product_tag'] ) ) {
		woo_tags_filter($wp_query->query['product_tag']);
	} else {
		echo '<div class="woo-custom-filter" data-postcount="'. esc_attr($postCount) .'" data-query-cat="'. esc_attr($query_cat).'" data-query-cat-id="'. esc_attr($query_cat_id) .'">';
			woo_categories_filter($query_cat, $query_parent_slug);
		echo '<span class="clear-filter">Filter zurücksetzen</span>
		</div>';
	}


}

function woo_tags_filter($query_tag_slug) {

	$args = array(
		'taxonomy' => 'product_tag',
		'hide_empty' => true,
		'parent'   => 0
	);

	$product_tags = get_terms( $args );

	echo '<div class="product-tags">';

	foreach ($product_tags as $product_tag) {
		do_action( 'qm/debug', $product_tag );
		$current_tag = ($query_tag_slug === $product_tag->slug) ? 'filter-current-tag' : '';
		echo '<a href="'. get_tag_link($product_tag) .'" rel="tag" class="product-tag '. esc_attr($current_tag) .'">'. $product_tag->name .'</a>';
	}

	echo '</div>';
}

function woo_categories_filter($query_cat, $query_parent_slug) {

	$args = array(
		'taxonomy' => 'product_cat',
		'hide_empty' => true,
		'parent'   => 0
	);

	$product_cat = get_terms( $args );

	echo '<ul>';
	foreach ($product_cat as $parent_product_cat) {


		$parent_slug = $parent_product_cat->slug;
		$parent_current = ($query_parent_slug === $parent_slug) ? 'filter-current-parent' : '';
		echo'<li class="filter-parent-cat '. esc_attr($parent_current) .'" data-term="term-'. esc_attr($parent_slug) .'">

			<span data-filter=".product_cat-'. esc_attr($parent_product_cat->term_id) .'" class="product-parent-selector">'. esc_attr($parent_product_cat->name) .'<i class="filter-icon"></i></span>
			<ul class="filter-child-cat">';

				$child_args = array(
					'taxonomy' => 'product_cat',
					'hide_empty' => true,
					'parent'   => $parent_product_cat->term_id
				);

				$child_product_cats = get_terms( $child_args );

				foreach ($child_product_cats as $child_product_cat) {

					$child_slug = $child_product_cat->slug;
					$checked = ( $query_cat === $child_slug ) ? 'checked' : '';
					echo '<li>
						<div class="pretty p-default p-fill p-svg p-tada">
							<input type="checkbox" data-filter=".product_cat-'. esc_attr($child_product_cat->term_id) .'" data-term="term-'. esc_attr($child_slug) .'" '. esc_attr($checked) .'/>
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

// Remove sale flash from single product
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

// Change position of sale flash in single product
add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 15 );

// Wrap single product thumbnail + description in div
add_filter( 'woocommerce_before_single_product_summary', 'wrap_product_start');
function wrap_product_start() {
	echo '<div class="product-wrap">';

	global $product;

	$categories_ids = $product->get_category_ids();
	$term = get_term($categories_ids[0]);
	$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="22.121" height="41.414" viewBox="0 0 22.121 41.414">
	  <path id="Path_39" data-name="Path 39" d="M9771.529,561.7l-20,20,20,20" transform="translate(-9750.115 -560.99)" fill="none" stroke="#000" stroke-width="2"/>
	</svg>';

	$categories = get_the_terms( get_the_ID(), 'product_cat' );
	$category_link = '#';
	$category_name = '';

	// wrapper to hide any errors from top level categories or products without category
	if ( $categories ) :

	    // loop through each cat
	    foreach($categories as $category) :
	      // get the children (if any) of the current cat
	      $children = get_categories( array ('taxonomy' => 'product_cat', 'parent' => $category->term_id ));

	      if ( count($children) == 0 ) {
	          // if no children, then echo the category name.
	          $category_name = $category->name;
			  $category_link = get_term_link($category);
	      }
	    endforeach;

	endif;

	echo '<div class="woo-back">
		<a href="'. esc_url($category_link) .'" class="woo-back-link">'. $svg .'</a>
		<div class="woo-back-name">'. esc_html($category_name) .'</div>
	</div>';
}

// insert product attributes after price for simple products
add_filter( 'woocommerce_get_price_html', 'show_attributes' );
function show_attributes($price){
	if ( 'product' == get_post_type() ) {

		global $product;

		if( $product->is_type( 'simple' ) && $product->get_attributes() ){
			$attributes = $product->get_attributes();
			$attributes_string = '<span class="attributes-string">';

			foreach ( $attributes as $attribute ):
				if($attribute->get_visible()) {

					$attribute_terms = $attribute->get_options(); // The terms

					foreach ($attribute_terms as $attribute_term) :
						$term_name = get_term($attribute_term)->name;
						$attributes_string .=  $term_name . ' ';
					endforeach;

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

//register acf form for the special category products
add_action('acf/init', 'my_acf_form_init');
function my_acf_form_init() {

    // Check function exists.
    if( function_exists('acf_register_form') ) {

        // Register form.
        acf_register_form(array(
            'id'       => 'special-product',
            'post_id'  => 'new_post',
            'new_post' => array(
                'post_type'   => 'event',
                'post_status' => 'publish'
            ),
            'post_title'  => true,
            'post_content'=> true,
        ));
    }
}

add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart_button_func' );
/*
 * Content below "Add to cart" Button.
 */
function add_content_after_addtocart_button_func() {
		// if(!function_exists('woocommerce_wp_text_input') && !is_admin()) {
		// 	include_once(WC()->plugin_path() . '/includes/admin/wc-meta-box-functions.php');
		// }
        // // Echo content.
		// woocommerce_wp_textarea_input(
		// 	array(
		// 		'id' => '_custom_product_adresse_field',
		// 		'class' => 'custom_product_adresse',
		// 		'label' => __('Adresse', 'nuri'),
		// 	)
		// );
		//
		// woocommerce_wp_textarea_input(
		// 	array(
		// 		'id' => '_custom_product_botschaft_field',
		// 		'class' => 'custom_product_botschaft',
		// 		'label' => __('Botschaft', 'nuri'),
		// 	)
		// );

}

/**/

// Add custom input field to product page
add_action( 'woocommerce_after_add_to_cart_button', 'extra_product_fields', 9 );
function extra_product_fields() {
	echo '<div class="extra-product-fields">';

		$extra_adresse = isset( $_POST['extra_adresse'] ) ? sanitize_text_field( $_POST['extra_adresse'] ) : '';
		printf( '<textarea name="extra_adresse" value="" placeholder="%s" rows="3"></textarea>', __( 'Adresse *' ), esc_attr( $extra_adresse ) );

		$extra_botschaft = isset( $_POST['extra_botschaft'] ) ? sanitize_text_field( $_POST['extra_botschaft'] ) : '';
		printf( '<textarea name="extra_botschaft" value="" placeholder="%s" rows="5"></textarea>', __( 'Botschaft' ), esc_attr( $extra_botschaft ) );

	echo '</div>';
}

// validate when add to cart
function d_extra_field_validation($passed, $product_id, $qty){

  if( isset( $_POST['extra_adresse'] ) && sanitize_text_field( $_POST['extra_adresse'] ) == '' ){
	  $product = wc_get_product( $product_id );
	  wc_add_notice( sprintf( __( 'Bitte die Adresse angeben!' ), $product->get_title() ), 'error' );
	  return false;
  }

  return $passed;

}
add_filter( 'woocommerce_add_to_cart_validation', 'd_extra_field_validation', 10, 3 );

   // add custom field data in to cart
  function d_add_cart_item_data( $cart_item, $product_id ){

	  if( isset( $_POST['extra_adresse'] ) ) {
		  $cart_item['extra_adresse'] = sanitize_text_field( $_POST['extra_adresse'] );
	  }

	  return $cart_item;

  }
  add_filter( 'woocommerce_add_cart_item_data', 'd_add_cart_item_data', 10, 2 );

  // load data from session
  function d_get_cart_data_f_session( $cart_item, $values ) {

	  if ( isset( $values['extra_adresse'] ) ){
		  $cart_item['extra_adresse'] = $values['extra_adresse'];
	  }

	  return $cart_item;

  }
  add_filter( 'woocommerce_get_cart_item_from_session', 'd_get_cart_data_f_session', 20, 2 );


  //add meta to order
  function d_add_order_meta( $item_id, $values ) {

	  if ( ! empty( $values['extra_adresse'] ) ) {
		  woocommerce_add_order_item_meta( $item_id, 'extra_adresse', $values['extra_adresse'] );
	  }
  }
  add_action( 'woocommerce_add_order_item_meta', 'd_add_order_meta', 10, 2 );

  // display data in cart
  function d_get_itemdata( $other_data, $cart_item ) {

	  if ( isset( $cart_item['extra_adresse'] ) ){

		  $other_data[] = array(
			  'name' => __( 'Your extra field text' ),
			  'value' => sanitize_text_field( $cart_item['extra_adresse'] )
		  );

	  }

	  return $other_data;

  }
  add_filter( 'woocommerce_get_item_data', 'd_get_itemdata', 10, 2 );


  // display custom field data in order view
  function d_dis_metadata_order( $cart_item, $order_item ){

	  if( isset( $order_item['extra_adresse'] ) ){
		  $cart_item_meta['extra_adresse'] = $order_item['extra_adresse'];
	  }

	  return $cart_item;

  }
  add_filter( 'woocommerce_order_item_product', 'd_dis_metadata_order', 10, 2 );


  // add field data in email
  function d_order_email_data( $fields ) {
	  $fields['extra_adresse'] = __( 'Your extra field text' );
	  return $fields;
  }
  add_filter('woocommerce_email_order_meta_fields', 'd_order_email_data');

  // again order
  function d_order_again_meta_data( $cart_item, $order_item, $order ){

	  if( isset( $order_item['extra_adresse'] ) ){
		  $cart_item_meta['extra_adresse'] = $order_item['extra_adresse'];
	  }

	  return $cart_item;

  }
  add_filter( 'woocommerce_order_again_cart_item_data', 'd_order_again_meta_data', 10, 3 );

/**/

// extend single product
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
	$special_category = get_field('spezielle_kategorie', 'options');

	do_action( 'qm/debug', $product);
	//check if product is in the special category
	if( has_term($special_category, 'product_cat', $product_id) ) {
		echo '<div class="single-product-info special-info">
			<div class="single-product-info-title">
				'. __('Weitere Produktinformationen', 'nuri') .'
				<i class="filter-icon"></i>
			</div>
			<div class="single-product-info-wrapper">';
			extra_product_fields();
			if(!function_exists('woocommerce_wp_text_input') && !is_admin()) {
				include_once(WC()->plugin_path() . '/includes/admin/wc-meta-box-functions.php');
			}

			// woocommerce_wp_textarea_input(
			// 	array(
			// 		'id' => '_custom_product_adresse_field',
			// 		'class' => 'custom_product_adresse',
			// 		'label' => __('Adresse', 'nuri'),
			// 	)
			// );
			//
			// woocommerce_wp_textarea_input(
			// 	array(
			// 		'id' => '_custom_product_botschaft_field',
			// 		'class' => 'custom_product_botschaft',
			// 		'label' => __('Botschaft', 'nuri'),
			// 	)
			// );

			// $value = isset( $_POST['extra_adresse'] ) ? sanitize_text_field( $_POST['extra_adresse'] ) : '';
			// printf( '<label>%s</label><input name="extra_adresse" value="%s" />', __( 'Enter your custom text' ), esc_attr( $value ) );

			echo '</div>
		</div>';
	}

	if($product_tags) {
		echo '<div class="single-product-tags">
			<strong>'. __( 'Passend als: ', 'nuri' ) .'</strong>
			'. $product_tags .'
		</div>';
	}

	if($additional_info) {
		echo '<div class="single-product-info">
			<div class="single-product-info-title">
				'. __('Zusätzliche Informationen zum Produkt', 'nuri') .'
				<i class="filter-icon"></i>
			</div>
			<div class="single-product-info-wrapper">
				'. wp_kses_post($additional_info) .'
			</div>
		</div>';
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
	echo '<div class="load-more-wrapper is-style-outline">
		<div class="lds-ellipsis">
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>
    </div>';
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
add_filter( 'woocommerce_default_address_fields', 'remove_state_field' );
function remove_state_field( $fields ) {
	unset( $fields['state'] );
    $fields['address_2']['placeholder'] = 'Zusätzliche Adressangaben (optional)';

	return $fields;
}

// Conditional gateway/shipping
add_filter( 'woocommerce_available_payment_gateways', 'gateway_disable_shipping' );
function gateway_disable_shipping( $available_gateways ) {

   if ( ! is_admin() ) {

      $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

      $chosen_shipping = $chosen_methods[0];

      if ( isset( $available_gateways['cod'] ) && 0 === strpos( $chosen_shipping, 'flat_rate' ) ) {
         unset( $available_gateways['cod'] );
      }

	  if ( isset( $available_gateways['stripe'] ) && 0 === strpos( $chosen_shipping, 'local_pickup' ) ) {
		 unset( $available_gateways['stripe'] );
	  }

   }

   return $available_gateways;

}

// Style stripe
function my_theme_modify_stripe_fields_styles( $styles ) {
    return array(
        'base' => array(
            'iconColor'     => '#000',
            'color'         => '#000',
            'fontSize'      => '22px',
			'fontFamily'    => 'Maison Neue, Helvetica, Arial, sans-serif',
            '::placeholder' => array(
                'color' => '#000',
            ),
        ),
    );
}

add_filter( 'wc_stripe_elements_styling', 'my_theme_modify_stripe_fields_styles' );

// remove cross sells from cart
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );


?>
