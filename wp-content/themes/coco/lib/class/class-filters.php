<?php


class CC_Filters {


	public static $filters = array(
		'woocommerce_add_to_cart_validation' => array( 'cc_validate_add_cart_item', 15, 3 ),
		'woocommerce_add_cart_item_data' => array( 'cc_add_cart_item_data', 15, 2 ),
		'woocommerce_email_classes' => 'cc_add_dry_cleaner_notifications'
	);

	/**
	 * Constructor
	 */
	public function __construct() {
		foreach ( CC_Filters::$filters as $filter => $cbdata ) {
			if ( is_array($cbdata ) ) {
				add_filter( $filter, array( $this, $cbdata[0] ), $cbdata[1], $cbdata[2] );
			} else {
				add_filter( $filter, array( $this, $cbdata ) );
			}
		}
	}

	/**
	 * When a reservation is added to the cart, validate it based on price...
	 */
	public function cc_validate_add_cart_item( $passed, $product_id, $qty ) {
		global $woocommerce;

		$product = get_product( $product_id );

		if ( $product->product_type !== 'booking' ) {
			return $passed;
		}

		if ( $passed == false ) {

			wc_clear_notices();

			$booking_form = new CC_Make_Reservation_Form( $product, $_POST['reservation_type'] ); // gotta make sure this value is planted
			$data = $booking_form->get_posted_data();
			$validate = $booking_form->is_bookable( $data );

			if ( is_wp_error( $validate ) ) {
				wc_add_notice( $validate->get_error_message(), 'error' );
				return false;
			}

			return true; // this ignores the frame condition... potentially problematic.
		}
		return $passed;	
	}

	/**
	 * Added posted data to the specified cart item
	 */
	public function cc_add_cart_item_data( $cart_item_meta, $product_id ) {
		$product = get_product( $product_id );

		if ( 'booking' !== $product->product_type ) {
			return $cart_item_meta;
		}

		if ( !isset( $_POST['reservation_type'] ) ) return $cart_item_meta;

		$booking_form = new CC_Make_Reservation_Form( $product, $_POST['reservation_type'] );
		$cart_item_meta['booking'] = $booking_form->get_posted_data( $_POST );
		$cart_item_meta['booking']['_cost'] = $booking_form->calculate_booking_cost( $_POST );

		return $cart_item_meta;
	}

	/**
	 * @param $email_classes array( WC_Email )
	 * @return array( WC_Email )
	 */
	public function cc_add_dry_cleaner_notifications( $email_classes ) {
		// require internal the filter, so that it's loaded after WC_Email
		require( realpath(dirname(__FILE__) . '/../emails/init.php') );

		$email_classes['CC_Dry_Cleaning_Email'] = new CC_Dry_Cleaning_Email();
		//$email_classes['CC_Cancel_Dry_Cleaning_Email'] = new CC_Cancel_Dry_Cleaning_Email();

		return $email_classes;
	}

}

new CC_Filters();


?>