<?php
add_filter('medicalpro_booking_checkout', 'medicalpro_booking_checkout_callback');
function medicalpro_booking_checkout_callback() {
	global $listingpro_options;

	$redirect          = MP_PLUGIN_DIR . 'include/paypal/form-handler.php?func=addrow';
	$paid_mode         = lp_theme_option('enable_paid_submission');
	$taxButton         = lp_theme_option('lp_tax_swtich');
	$pubilshableKey    = $listingpro_options['stripe_pubishable_key'];
	$currency_symbol   = listingpro_currency_sign();
	$currency_position = $listingpro_options['pricingplan_currency_position'];

	$Taxrate   = '';
	$enableTax = false;
	if ($listingpro_options['lp_tax_swtich'] == "1") {
		$enableTax = true;
		$Taxrate   = $listingpro_options['lp_tax_amount'];
	}

	$output = '<div class="page-container-four clearfix">';
	$output .= '<div class="col-md-10 col-md-offset-1">';

	if ( ! empty($paid_mode) && $paid_mode == "no") {
		$output .= '<p class="text-center">' . esc_html__('Sorry! Currently Free mode is activated', 'medicalpro') . '</p>';
	} else {
		/* for steps */
		ob_start();
		include_once(WP_PLUGIN_DIR . '/listingpro-plugin/templates/payment-steps.php');
		$output .= ob_get_contents();
		ob_end_clean();
		ob_flush();


		$output .= '<form autocomplete="off" id="mp_booking_checkout_form" class="lp-listing-form" name ="mp_booking_checkout_form" action="' . $redirect . '" method="post" data-currency_sign="' . $currency_symbol . '" data-currency_position="' . $currency_position . '" data-taxenable = "' . $enableTax . '" data-taxrate = "' . $Taxrate . '">';
		$output .= '<div class="row">';

		// Content Section
		$output .= '<div class="col-md-8">';
		$output .= medicalpro_booking_details();
		// section selected listing details and coupons.
		$output        .= '<div class="lp-checkout-coupon-outer">';
		$couponsSwitch = lp_theme_option('listingpro_coupons_switch');
		if ($couponsSwitch == "yes") {
			$output .= '
                            <div class="col-md-12 checkout-padding-top-bottom">
                                <div class="col-md-6">
                                    <div class="lp-checkout-coupon-code">
                                        <div class="lp-onoff-switch-checkbox">
                                            <label class="switch-checkbox-label">
                                                <input type="checkbox" name="mp_checkbox_coupon" value="couponON">
                                                <span class="switch-checkbox-styling">
                                                </span>
                                            </label>
                                        </div>
                                        <span class="lp-text-switch-checkbox">' . esc_html__("Coupon Code", "medicalpro") . '</span>
                                    </div>
                                </div>
                                <div class="col-md-6 apply-coupon-text-field">
                                    <input type="text" class="coupon-text-field" name="coupon-text-field" placeholder="' . esc_html__('Type Here', 'medicalpro') . '" disabled>
                                    <button type="button" class="coupon-apply-btn" disabled>' . esc_html__('APPLY CODE', 'medicalpro') . '</button>
                                </div>
                            </div>';
		}
		$output .= '<ul class="checkout-item-price-total">
                            <li>
                                <span class="item-price-total-left"><b>' . esc_html__('ITEM', 'medicalpro') . '</b></span>
                                <span class="item-price-total-right"><b>' . esc_html__('PRICE', 'medicalpro') . '</b></span>
                            </li>
                            <li>
                                <span class="item-price-total-left lp-subtotal-plan">' . esc_html__('Pro', 'medicalpro') . '</span>
                                <span class="item-price-total-right lp-subtotal-p-price"></span>
                            </li>';
		if ( ! empty($taxButton)) {
			$output .= '<li>
                                    <span class="item-price-total-left">' . esc_html__('Tax(Value Added Tax)', 'medicalpro') . '</span>
                                    <span class="item-price-total-right lp-subtotal-taxamount"></span>
                                </li>';
		}
		$output .= '<li>
                                <span class="item-price-total-left"><b>' . esc_html__('Total', 'medicalpro') . '</b></span>
                                <span class="item-price-total-right lp-subtotal-total-price"><b></b></span>
                            </li>
                        </ul>
                    </div>';
		$output .= '</div>';
		// End Content Section
		// Sidebar Section
		$output .= '<div class="col-md-4 lp-col-outer">';
		ob_start();
		include_once(WP_PLUGIN_DIR . '/listingpro-plugin/templates/payment-methods.php');
		$output .= ob_get_contents();
		ob_end_clean();
		ob_flush();
		// checkbox term and conditions
		$termsCondition = lp_theme_option('payment_terms_condition');
		if ( ! empty($termsCondition)) {
			$output .= '<div class="lp-new-term-style clearfix">
                            <label class="filter_checkbox_container terms-checkbox-container">
                                <input type="checkbox">
                                <span class="filter_checkbox_checkmark"></span>
                            </label>
                            <a class="lpcheckouttac" target="_blank" href="' . get_the_permalink($termsCondition) . '">' . esc_html__('Terms And Conditions', 'medicalpro') . '</a>
                        </div>';
		}
		$output .= '<button type="button" class="lp_payment_step_next booking_firstStep" disabled>' . esc_html__('PROCEED TO NEXT', 'medicalpro') . '</button>';
		$output .= '</div>';
		// End Sidebar Section
		$output .= '</div>';
		$output .= '</form>';
		$output .= medicalpro_booking_stripe_button();
	}

	return $output;
}

function medicalpro_booking_stripe_button() {
	global $listingpro_options;
	$output         = null;
	$pubilshableKey = $listingpro_options['stripe_pubishable_key'];
	$output         .= '
    <button id="stripe-submit">' . esc_html__('Purchase', 'medicalpro') . '</button>
    <script>
        var token_email, token_id;
        var handler = StripeCheckout.configure({
            key: "' . $pubilshableKey . '",
            image: "https://stripe.com/img/documentation/checkout/marketplace.png",
            locale: "auto",
            token: function(token) {
                token_id = token.id;
                token_email = token.email;
                jQuery("body").addClass("listingpro-loading");
                jQuery.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "' . admin_url('admin-ajax.php') . '",
                    data: { 
                        "action": "medicalpro_save_booking_stripe", 
                        "token": token_id, 
                        "email": token_email, 
                        "booking_id": jQuery("#mp_booking_checkout_form input[name=booking_id]").val(),
                        "currency": jQuery("#mp_booking_checkout_form input[name=currency]").val(),
                        "paid_price": jQuery("#mp_booking_checkout_form input[name=paid_price]").val(),
                        "price": jQuery("#mp_booking_checkout_form input[name=price]").val(),
                        "taxrate": jQuery("#mp_booking_checkout_form input[name=tax_rate]").val(),
                        "tax_price": jQuery("#mp_booking_checkout_form input[name=tax_price]").val(),
                        "coupon" : jQuery("#mp_booking_checkout_form input[name=coupon-text-field]").val(),						
                        "recurring" : "no",						
                    },   
                    success: function(res){
                        if(res.status=="success"){
                            if(res.status=="success"){
                                window.location.href = res.redirectURL;
                                jQuery("body").removeClass("listingpro-loading");
                            }
                        }
                        if(res.status=="fail"){
                            alert(res.msg);
                            jQuery("body").removeClass("listingpro-loading");
                        }
                    },
                    error: function(errorThrown){
                        alert(errorThrown);
                        jQuery("body").removeClass("listingpro-loading");
                    } 
                });
            }
        });
        // Close Checkout on page navigation:
        window.addEventListener("popstate", function() {
          handler.close();
        });
    </script>';

	return $output;
}

add_filter('lp_wire_payment_method', function ($default){
	$booking_id     = $_GET['booking_id'];
	$user_id        = $_GET['user_id'];
	if (!empty($booking_id) && !empty($user_id)) {
		return false;
	}

	return $default;
});

function medicalpro_booking_details() {
	global $wpdb, $listingpro_options;


	$currentuser_ID    = get_current_user_id();
	$currency          = $listingpro_options['currency_paid_submission'];
	$currency_symbol   = listingpro_currency_sign();
	$currency_position = $listingpro_options['pricingplan_currency_position'];
	$enableTax         = false;
	$Taxrate           = '';
	if ($listingpro_options['lp_tax_swtich'] == "1") {
		$enableTax = true;
		$Taxrate   = $listingpro_options['lp_tax_amount'];
	}


	$booking_id     = $_GET['booking_id'];
	$user_id        = $_GET['user_id'];
	$payment_status = get_post_meta($booking_id, 'booking_payment_status', true);
	$booking_order  = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "booking_orders WHERE booking_id = $booking_id AND user_id = $user_id AND status='pending' ORDER BY main_id DESC LIMIT 0, 1", ARRAY_A);

	$invalidBooking       = false;
	$outputbookingListing = '';
	if (get_post_status($booking_id) == 'publish' && isset($payment_status) && $payment_status == 'pending') {

		if ($currentuser_ID == $user_id) {

			$post_id       = get_post_meta($booking_id, 'booking_listing_id', true);
			$hospital_id   = get_post_meta($booking_id, 'booking_hospital_id', true);
			$price         = get_post_meta($booking_id, 'booking_video_consult_fee', true);
			$booking_type  = get_post_meta($booking_id, 'booking_type', true);
			$hospital_info = get_term_by('id', $hospital_id, 'medicalpro-hospital');
			$hospital_name = isset($hospital_info->name) ? $hospital_info->name : '';

			$outputbookingListing .= '<div class="lp-checkout-wrapper lp-checkout-wrapper-new ' . $booking_id . '">';

			$terms           = wp_get_post_terms($post_id, 'listing-category', array());
			$deafaultFeatImg = lp_default_featured_image_listing();

			$catname = '';
			if (count($terms) > 0) {
				$catname = $terms[0]->name;
			}
			if ( ! empty($price)) {
				$outputbookingListing .= '<div class="lp-user-listings active-checkout-listing clearfix"><div class="col-md-12 col-sm-12 col-xs-12 lp-listing-clm lp-checkout-page-outer lp-checkout-page-outer-new">';

				$outputbookingListing .= '<div class="col-md-10 col-sm-6 col-xs-6">';
				/* left side */
				$outputbookingListing .= '<h3 id="lp-checkout-lisiting-heading">' . get_the_title($post_id) . '</h3>';
				$outputbookingListing .= '<div class="col-md-1 col-sm-2 col-xs-6">';
				$outputbookingListing .= '<div class="radio radio-danger lp_price_trigger_checkout">
                                    <input id="booking-' . $booking_id . '" type="radio" name="booking_id" data-listingID="' . $post_id . '" data-price = "' . $price . '" data-title="' . $hospital_name . '" data-post_title="' . esc_attr(get_the_title($post_id)) . '" value="' . $booking_id . '" checked="checked">
                                    <label for="booking-' . $booking_id . '"></label>
                                </div>';
				$outputbookingListing .= '</div>';
				if (has_post_thumbnail($post_id)) {
					$imgurl               = get_the_post_thumbnail_url($post_id, 'listingpro-review-gallery-thumb');
					$outputbookingListing .= '<input type="hidden" name="listing_img" value="' . $imgurl . '">';
					$outputbookingListing .= '<div class="col-md-3">';
					$outputbookingListing .= '<img class="img-responsive" src="' . $imgurl . '" alt="" />';
					$outputbookingListing .= '</div>';

				} else if ( ! empty($deafaultFeatImg)) {
					$outputbookingListing .= '<input type="hidden" name="listing_img" value="' . $deafaultFeatImg . '">';
					$outputbookingListing .= '<div class="col-md-3">';
					$outputbookingListing .= '<img class="img-responsive" src="' . $deafaultFeatImg . '" alt="" />';
					$outputbookingListing .= '</div>';
				} else {
					$outputbookingListing .= '<div class="col-md-3">';
					$outputbookingListing .= '<img class="img-responsive" src="' . esc_url('https://via.placeholder.com/80x80') . '" alt="" />';
					$outputbookingListing .= '</div>';
				}

				$outputbookingListing .= '<div class="col-md-7">';
				$outputbookingListing .= '<span class="lp-booking-dt"><p>' . esc_html__('Date: ', 'medicalpro') . date_i18n(get_option('date_format')) . '</p></span>';
				$outputbookingListing .= '<span class="lp-persons"><p>' . esc_html__('Category: ', 'medicalpro') . $catname . '</p></span>';
				$outputbookingListing .= '<span class="lp-duration"><p>' . medicalpro_booking_types($booking_type) . '</p></span>';
				$outputbookingListing .= '</div>';
				/* left side ends*/
				$outputbookingListing .= '</div>';
				$outputbookingListing .= '<div class="col-md-2 col-sm-6 col-xs-6 lp-checkout-price-currency-outer">';
				/* right side */
				$outputbookingListing .= '<div class="lp-checkout-price-currency">';
				switch ($currency_position) {
					case('left'):
						$outputbookingListing .= $currency_symbol . $price;
						break;

					case('right'):
						$outputbookingListing .= $price . $currency_symbol;
						break;

					default:
						$outputbookingListing .= $currency_symbol . $price;

				}
				$outputbookingListing .= '</div>';
				/* right side ends*/
				$outputbookingListing .= '</div>';
				$outputbookingListing .= '</div>';
				$outputbookingListing .= '</div>';
				$outputbookingListing .= '</div>';
			}

			$outputbookingListing .= '<input type="hidden" name="func" value="start">';
			$outputbookingListing .= '<input type="hidden" name="method" value="">';
			$outputbookingListing .= '<input type="hidden" name="currency" value="' . $currency . '">';
			$outputbookingListing .= '<input type="hidden" name="price" value="">';
			$outputbookingListing .= '<input type="hidden" name="paid_price" value="">';
			$outputbookingListing .= '<input type="hidden" name="tax_price" value="">';
			$outputbookingListing .= '<input type="hidden" name="tax_rate" value="">';
			$outputbookingListing .= '<input type="hidden" name="booking_id" value="' . $booking_id . '">';
			$outputbookingListing .= '<input type="hidden" name="method" value="">';
		} else {
			$invalidBooking = true;
		}
	} else {
		$invalidBooking = true;
	}
	if ( ! empty($invalidBooking)) {
		$outputbookingListing .= '<div class="lp-checkout-wrapper lp-checkout-wrapper-new">';
		$outputbookingListing .= '<p>' . esc_html__('Sorry! No Paid booking request found', 'medicalpro') . '</p>';
		$outputbookingListing .= '</div>';
	}

	return $outputbookingListing;
}