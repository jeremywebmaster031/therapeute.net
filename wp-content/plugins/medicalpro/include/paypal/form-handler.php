<?php
/**
 * Form posting handler
 */
session_start();
require_once( dirname(dirname( dirname( dirname( dirname( dirname( __FILE__ )))))) . '/wp-load.php' );
global $listingpro_options;

if( !empty($_POST['method']) && $_POST['method'] == "wire" ){
    
    $booking_id           = isset($_POST['booking_id'])     ? $_POST['booking_id']      : '';
    $currency             = isset($_POST['currency'])       ? $_POST['currency']        : '';
    $paid_price           = isset($_POST['paid_price'])     ? $_POST['paid_price']      : '';
    $price                = isset($_POST['booking_id'])     ? $_POST['price']           : '';
    $taxrate              = isset($_POST['booking_id'])     ? $_POST['taxrate']         : '';
    $tax_price            = isset($_POST['booking_id'])     ? $_POST['tax_price']       : '';
    $coupon               = isset($_POST['coupon'])         ? $_POST['coupon']          : '';
    
    $current_user    = wp_get_current_user();
    medicalpro_save_booking_order( '', 'wire', $current_user->ID, $current_user->user_email, $booking_id, $currency, $price, $paid_price, $taxrate, $tax_price, $coupon );
    $_SESSION['booking_id'] = $booking_id;
    wp_redirect(add_query_arg( 'booking_checkout', 'wire', get_permalink($listingpro_options['payment-checkout']) ));
    exit;
}

if( isset($_POST['func']) && $_POST['func'] == 'start' && isset($_POST['method']) && $_POST['method'] == 'paypal' ) {
    
    $paypal_api_environment  = $listingpro_options['paypal_api'];
    $paypal_api_username     = $listingpro_options['paypal_api_username'];
    $paypal_api_password     = $listingpro_options['paypal_api_password'];
    $paypal_api_signature    = $listingpro_options['paypal_api_signature'];
    
    $booking_id           = isset($_POST['booking_id'])     ? $_POST['booking_id']      : '';
    $currency             = isset($_POST['currency'])       ? $_POST['currency']        : '';
    $paid_price           = isset($_POST['paid_price'])     ? $_POST['paid_price']      : '';
    $price                = isset($_POST['booking_id'])     ? $_POST['price']           : '';
    $taxrate              = isset($_POST['booking_id'])     ? $_POST['taxrate']         : '';
    $tax_price            = isset($_POST['booking_id'])     ? $_POST['tax_price']       : '';
    $coupon               = isset($_POST['coupon'])         ? $_POST['coupon']          : '';
    $AMT                  = $paid_price;
    
    $paypal_fail         = add_query_arg('lpcheckstatus', 'fail', $listingpro_options['payment-checkout']);
    
    $fields = array(
        'USER'                              => urlencode($paypal_api_username),
        'PWD'                               => urlencode($paypal_api_password),
        'SIGNATURE'                         => urlencode($paypal_api_signature),
        'VERSION'                           => urlencode('72.0'),
        'PAYMENTREQUEST_0_PAYMENTACTION'    => urlencode('Sale'),
        'PAYMENTREQUEST_0_AMT0'             => urlencode($AMT),
        'PAYMENTREQUEST_0_CUSTOM'           => urlencode($booking_id),
        'PAYMENTREQUEST_0_AMT'              => urlencode($AMT),
        'PAYMENTREQUEST_0_ITEMAMT'          => urlencode($AMT),
        'ITEMAMT'                           => urlencode($AMT),
        'PAYMENTREQUEST_0_CURRENCYCODE'     => urlencode($currency),
        'RETURNURL'                         => urlencode(MP_PLUGIN_DIR . '/include/paypal/form-handler.php?func=confirm'),
        'CANCELURL'                         => urlencode($paypal_fail),
        'METHOD'                            => urlencode('SetExpressCheckout'),
        'POSTID'                            => urlencode('postid'),
        'PAYMENTREQUEST_0_CUSTOM'           => $booking_id,
        'PAYMENTREQUEST_0_DESC'             => 'Booking Payment payment',
        'PAYMENTREQUEST_0_QTY0'             => 1
    );
    

    $fields_string = '';
    foreach ($fields as $key => $value)
        $fields_string .= $key . '=' . $value . '&';
    rtrim($fields_string, '&');
    
    // CURL
    $ch = curl_init();

    if ($paypal_api_environment == 'sandbox')
        curl_setopt($ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
    elseif ($paypal_api_environment == 'live')
        curl_setopt($ch, CURLOPT_URL, 'https://api-3t.paypal.com/nvp');

    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6); //6 is for TLSV1.2
    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);
    parse_str($result, $result);
    
    if ($result['ACK'] == 'Success') {
        $_SESSION['form_fields'] = json_encode($_POST);
        if ($paypal_api_environment == 'sandbox')
            header('Location: https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=' . $result['TOKEN']);
        elseif ($paypal_api_environment == 'live')
            header('Location: https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=' . $result['TOKEN']);
        exit;
    } else {
        print_r($result);
    }
    
    
}
if( isset($_GET['func']) && $_GET['func'] == 'confirm' && isset($_GET['token']) && isset($_GET['PayerID']) ) {
    
    $paypal_api_environment  = $listingpro_options['paypal_api'];
    $paypal_api_username     = $listingpro_options['paypal_api_username'];
    $paypal_api_password     = $listingpro_options['paypal_api_password'];
    $paypal_api_signature    = $listingpro_options['paypal_api_signature'];
    
    $fields = array(
        'USER'                           => urlencode($paypal_api_username),
        'PWD'                            => urlencode($paypal_api_password),
        'SIGNATURE'                      => urlencode($paypal_api_signature),
        'PAYMENTREQUEST_0_PAYMENTACTION' => urlencode('Sale'),
        'VERSION'                        => urlencode('72.0'),
        'TOKEN'                          => urlencode($_GET['token']),
        'METHOD'                         => urlencode('GetExpressCheckoutDetails')
    );

    $fields_string = '';
    foreach ($fields as $key => $value)
        $fields_string .= $key . '=' . $value . '&';
    rtrim($fields_string, '&');

    // CURL
    $ch = curl_init();
    
    if ($paypal_api_environment == 'sandbox')
        curl_setopt($ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
    elseif ($paypal_api_environment == 'live')
        curl_setopt($ch, CURLOPT_URL, 'https://api-3t.paypal.com/nvp');

    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6); //6 is for TLSV1.2
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    parse_str($result, $result);
    
    if ($result['ACK'] == 'Success') {
        $booking_id  = isset($result['CUSTOM']) ? $result['CUSTOM'] : 0;
        if (isset($_SESSION['form_fields'])) {
            $form_fields = json_decode($_SESSION['form_fields'], true);
            unset($_SESSION['form_fields']);
            
            $currency      = isset($form_fields['currency'])       ? $form_fields['currency']        : '';
            $paid_price    = isset($form_fields['paid_price'])     ? $form_fields['paid_price']      : '';
            $price         = isset($form_fields['price'])          ? $form_fields['price']           : '';
            $taxrate       = isset($form_fields['taxrate'])        ? $form_fields['taxrate']         : '';
            $tax_price     = isset($form_fields['tax_price'])      ? $form_fields['tax_price']       : '';
            $coupon        = isset($form_fields['coupon'])         ? $form_fields['coupon']          : '';
            
            $current_user    = wp_get_current_user();
            medicalpro_save_booking_order( $_GET['PayerID'], 'paypal', $current_user->ID, $current_user->user_email, $booking_id, $currency, $price, $paid_price, $taxrate, $tax_price, $coupon );
            wp_redirect(add_query_arg( 'dashboard', 'my-bookings', $listingpro_options['listing-author'] ));
            exit;
        }
    }else{
        wp_redirect(add_query_arg( 'lpcheckstatus', 'fail', get_permalink($listingpro_options['payment-checkout'])));
        exit;
    }
    
}