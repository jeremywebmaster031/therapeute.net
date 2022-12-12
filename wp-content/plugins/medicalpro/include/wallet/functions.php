<?php


if (!function_exists('medicalpro_payment_wallet_callback')) {
    function medicalpro_payment_wallet_callback() {
        global $wpdb, $listingpro_options;
        $currencySymbol = listingpro_currency_sign();
        $table = $wpdb->prefix.'booking_orders';
        $orders = array();
        $orderCount = 0;
        if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
            $query = "";
            $query = "SELECT * from $table ORDER BY main_id DESC";
            $orders = $wpdb->get_results($query);
            $orderCount = count($orders);
        }
        $countWithdrawals = 0;
        $args = array(
            'post_type' => 'lp-withdrawal',
            'meta_query' => array(
                array(
                    'key'     => 'lp_withdrawal_requested_status',
                    'value'   => 'paid',
                    'compare' => '=',
                ),
            ),
        );
        $the_query = new WP_Query( $args );
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $value = get_post_meta(get_the_ID(), 'lp_withdrawal_requested_amount', true);
                if (!empty($value) && is_numeric($value)){
                    $countWithdrawals = $countWithdrawals + $value;
                }
            }
        }
        $totalEarnings = 0;
        if (!empty($orders) && is_array($orders)) {
            foreach ($orders as $key => $value) {
                if (!empty($value->paid_price) && is_numeric($value->paid_price)) {
                    $totalEarnings = $totalEarnings + $value->paid_price;
                }
            }
        }


        echo '<h1>Total Orders: '.$orderCount.'</h1>';
        echo '<h1>Total Earnings: '.$currencySymbol.$totalEarnings.'</h1>';
        echo '<h1>Total Withdrawals: '.$currencySymbol.$countWithdrawals.'</h1>';
        echo '<h1>Available Balance: '.$currencySymbol.($totalEarnings - $countWithdrawals).'</h1>';

        echo '<br>';
        echo '<br>';
        echo '<br>';

        if (!empty($orders) && is_array($orders)) {
            foreach ($orders as $key => $data) {
                $postID = $data->post_id;
                $bookingID = $data->booking_id;
                $userID = $data->customer_id;
                $currency =  $data->currency;
                $price =  $data->price;
                $status = $data->status;
                $date  = $data->date;
                $paymentMethod = $data->payment_method;
                $transaction_ID = $data->transaction_id;

                echo '<h3>Doctor Name: '.get_the_title($postID).'</h3>';
                echo '<h3>Patient Name: '.get_userdata($userID)->user_login.'</h3>';
                echo '<h3>Transaction ID: '.$transaction_ID.'</h3>';
                echo '<h3>Amount Paid: '.$currency.$price.'</h3>';
                echo '<h3>Payment Status: '.$status.'</h3>';
                echo '<h3>Paid With: '.$paymentMethod.'</h3>';
                echo '<h3>Paid On: '.date_i18n(get_option("date_format"), $date).'</h3>';
                echo '<br>';
                echo '<br>';
            }
        }

        return;
    }
}