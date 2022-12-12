<?php

/* ====================== for booking wire===================== */
if (!function_exists('get_booking_wire_invoice')) {
    function get_booking_wire_invoice($booking_id) {
        global $wpdb, $listingpro_options;
        
        $logo                = $listingpro_options['invoice_logo']['url'];
        $company             = $listingpro_options['invoice_company_name'];
        $address             = $listingpro_options['invoice_address'];
        $additional          = $listingpro_options['invoice_additional_info'];
        $thanku_text         = $listingpro_options['invoice_thankyou'];
        
        $current_user        = wp_get_current_user();
        $usermail            = $current_user->user_email;
        $user_name           = $current_user->display_name;
        
        $currency_sign       = listingpro_currency_sign();
        $currency_position   = lp_theme_option('pricingplan_currency_position');
        
        $table         = $wpdb->prefix.'booking_orders';
        $booking_order = $wpdb->get_row( "SELECT * FROM $table WHERE booking_id=' $booking_id ' ORDER BY main_id DESC", ARRAY_A );
	    $invoice       = isset($booking_order['order_id']) ? $booking_order['order_id'] : '';
        $paid_price    = isset($booking_order['paid_price']) ? $booking_order['paid_price'] : '';
        $taxprice      = isset($booking_order['taxprice']) ? $booking_order['taxprice'] : '';
        
        $total_price     = $currency_sign.$paid_price;
        $sub_total_price = $currency_sign.($paid_price-$taxprice);
        $tax_price       = $currency_sign.$taxprice;
        if( $currency_position == 'right'){
            $total_price     = $paid_price.$currency_sign;
            $sub_total_price = ($paid_price-$taxprice).$currency_sign;
            $tax_price       = $taxprice.$currency_sign;
        }
        
        $output = '
            <div class="checkout-invoice-area">
                <div class="top-heading-area">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <img src="' . esc_attr($logo) . '" alt="medicalpro" style="width:122px" width="122" class="CToWUd">
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <p>' . esc_html__('Receipt', 'medicalpro') . '</p>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12"></div>
                    </div>
                </div>
                <div class="invoice-area">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h4>' . esc_html__('Billed to :', 'medicalpro') . '</h4>
                            <ul>
                                <li>' . $user_name . '</li>
                                <li>' . $usermail . '</li>
                            </ul>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <p>
                                <strong>' . esc_html__('Invoice :', 'medicalpro') . '</strong>
                                #' . $invoice . '<br>
                                <strong>' . esc_html__('Process With: Direct / Wire method', 'medicalpro') . '</strong>
                            </p>
                        </div>
                    </div>
                    <div class="row heading-area">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <p><strong>' . esc_html__('Description', 'medicalpro') . '</strong></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <p><strong>' . esc_html__('Payment instructions', 'medicalpro') . '</strong></p>
                        </div>					
                        <div class="col-md-2 col-sm-2 col-xs-12"></div>
                    </div>
                    <div class="row invoices-company-details">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <a href="#" target="_blank">' . $company . '</a> <br>
                            <p>' . $address . ' ' . '<span class="aBn" data-term="goog_1120388248" tabindex="0"><span class="aQJ">' . current_time('mysql') . '</span></span></b></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <p>' . $listingpro_options["direct_payment_instruction"] . '</p>
                        </div>					
                        <div class="col-md-2 col-sm-2 col-xs-12">
                        </div>
                    </div>
                    <div class="row invoice-price-details">
                        <div class="col-md-6 col-sm-6 col-xs-12"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <ul class="clearfix">
                                <li>' . esc_html__('Subtotal :', 'medicalpro') . '</li>
                                <li>' . $sub_total_price . '</li>
                            </ul>
                            <ul class="clearfix">
                                <li>' . esc_html__('Tax :', 'medicalpro') . '</li>
                                <li>' . $tax_price . '</li>
                            </ul>
                            <ul class="clearfix">
                                <li>' . esc_html__('Amount Paid :', 'medicalpro') . '</li>
                                <li>0.00</li>
                            </ul>
                            <ul class="clearfix">
                                <li>' . esc_html__('Balance due :', 'medicalpro') . '</li>
                                <li>' . $total_price . '</li>
                            </ul>
                        </div>
                    </div>
                    <div class="thankyou-text text-center">
                        <p>' . $thanku_text . '</p>
                    </div>
                </div>
                <div class="checkout-bottom-area">
                    ' . $additional . '
                </div>
            </div>';
        $output .= '
        <div class="col-md-12">
            <a href="' . lp_theme_option("listing-author") . '" class="checkout-dashboard-bt">' . esc_html__("Go Back To Dashboard", "medicalpro") . '</a>
        </div>';
        return $output;
    }

}