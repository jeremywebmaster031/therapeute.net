<?php
if(!function_exists('medicalpro_save_booking_order')){
    function medicalpro_save_booking_order( $transaction_id = 0, $payment_method = '', $user_id = 0, $user_email = '', $booking_id = 0, $currency = '', $price = 0, $paid_price = 0, $taxrate = 0, $tax_price = 0, $coupon = '' ) {
        global $listingpro_options;

        $listing_id         = get_post_meta($booking_id, 'booking_listing_id', true);
        $hospital_id        = get_post_meta($booking_id, 'booking_hospital_id', true);
        $hospital_info      = get_term_by('id', $hospital_id, 'medicalpro-hospital');
        $booking_type       = get_post_meta($booking_id, 'booking_type', true);
        $current_user       = get_userdata( $user_id );
        $current_user_email = isset($current_user->first_name) ? $current_user->first_name : '';
        $doctorID           = get_post_meta($booking_id, 'booking_listing_author', true);

        medicalpro_create_booking_order_table();
        $insert_sql = array(
            'order_id'        => random_int(11111111, 999999999),
            'booking_id'      => $booking_id,
            'doctor_id'       => $doctorID,
            'customer_id'     => $current_user->ID,
            'post_id'         => $listing_id,
            'hospital_id'     => $hospital_id,
            'doctor_name'     => esc_html(get_the_title($listing_id)),
            'hospital_name'   => isset($hospital_info->name) ? $hospital_info->name : '',
            'booking_type'    => $booking_type,
            'payment_method'  => $payment_method,
            'price'           => $price,
            'paid_price'      => $paid_price,
            'currency'        => $currency,
            'status'          => 'paid',
            'transaction_id'  => $transaction_id,
            'firstname'       => isset($current_user->first_name) ? $current_user->first_name : '',
            'lastname'        => isset($current_user->last_name) ? $current_user->last_name : '',
            'email'           => $user_email != '' ? $user_email : $current_user_email,
            'taxrate'         => $taxrate,
            'taxprice'        => $tax_price,
            'date'            => strtotime(current_time(get_option('date_format'))),
            'paid_date'       => strtotime(current_time(get_option('date_format')))
        );
        if( $payment_method == 'wire' ){
            $insert_sql['status'] = 'pending';
        }
        if(isset($coupon) && $coupon != '' ){
            $insert_sql['coupon'] = $coupon;
            $insert_sql['discount_price'] = '';
            listingpro_apply_coupon_code_at_payment($coupon, $listing_id, $tax_price, $paid_price_invoice);
        }
        
        $lp_commision_swtich  = isset($listingpro_options['lp_commision_swtich']) ? $listingpro_options['lp_commision_swtich'] : '';
        $lp_commision_percent = isset($listingpro_options['lp_commision_percent']) ? $listingpro_options['lp_commision_percent'] : '';
        if( $lp_commision_swtich == "1" && $lp_commision_percent > 0 ){
            $commision_price = (($paid_price*$lp_commision_percent)/100);
            
            $insert_sql['commision_rate']  = $lp_commision_percent;
            $insert_sql['commision_price'] = $commision_price;
            $insert_sql['sub_total']       = $paid_price - $commision_price;
        }else{
            $insert_sql['sub_total']       = $paid_price;
        }
        
        lp_insert_data_in_db('booking_orders', $insert_sql);
        update_post_meta($booking_id, 'booking_payment_status', 'paid');
    }
}

if(!function_exists('medicalpro_create_booking_order_table')){
    function medicalpro_create_booking_order_table(){
        global $wpdb;

        $wpdb->query("CREATE TABLE IF NOT EXISTS `". $wpdb->prefix ."booking_orders` (
        `main_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `booking_id` int(11) NOT NULL,
        `order_id` text NOT NULL,
        `doctor_id` text NOT NULL,
        `customer_id` text NOT NULL,
        `post_id` text NOT NULL,
        `hospital_id` text NOT NULL,
        `doctor_name` text NOT NULL,
        `hospital_name` text NOT NULL,
        `booking_type` text NOT NULL,
        `payment_method` text NOT NULL,
        `price` float UNSIGNED NOT NULL,
        `paid_price` float UNSIGNED NOT NULL,
        `sub_total` float UNSIGNED NOT NULL,
        `currency` text NOT NULL,
        `status` text NOT NULL,
        `transaction_id` text NOT NULL,
        `firstname` text NOT NULL,
        `lastname` text NOT NULL,
        `email` text NOT NULL,
        `commision_rate` float UNSIGNED NOT NULL,
        `commision_price` float UNSIGNED NOT NULL,
        `taxrate` text NOT NULL,
        `taxprice` text NOT NULL,
        `coupon` text NOT NULL,
        `discount_price` text NOT NULL,
        `date` text NOT NULL,
        `paid_date` text NOT NULL
        ) ENGINE = MYISAM; ");
    }
}