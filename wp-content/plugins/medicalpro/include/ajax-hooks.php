<?php
add_action('wp_ajax_medicalpro_save_booking_stripe', 'medicalpro_save_booking_stripe_callback');
add_action('wp_ajax_nopriv_medicalpro_save_booking_stripe', 'medicalpro_save_booking_stripe_callback');
function medicalpro_save_booking_stripe_callback(){
    global $wpdb, $listingpro_options;
    
    require_once LISTINGPRO_PLUGIN_PATH . '/inc/stripe/stripe-php/init.php';
    
    $token         = isset($_POST['token'])          ? $_POST['token']           : '';
    $email         = isset($_POST['email'])          ? $_POST['email']           : '';
    $booking_id    = isset($_POST['booking_id'])     ? $_POST['booking_id']      : '';
    $currency      = isset($_POST['currency'])       ? $_POST['currency']        : '';
    $paid_price    = isset($_POST['paid_price'])     ? $_POST['paid_price']      : '';
    $price         = isset($_POST['price'])          ? $_POST['price']           : '';
    $taxrate       = isset($_POST['taxrate'])        ? $_POST['taxrate']         : '';
    $tax_price     = isset($_POST['tax_price'])      ? $_POST['tax_price']       : '';
    $coupon        = isset($_POST['coupon'])         ? $_POST['coupon']          : '';
    
    $current_user  = wp_get_current_user();
    
    try {
        
        $paid_price         = (float) $paid_price * 100;
        $paid_price         = round($paid_price, 2);
        $paid_price         = (int) $paid_price;
        $paid_price_invoice = number_format(($paid_price / 100), 2, '.', '');
        
        $secritKey = $listingpro_options['stripe_secrit_key'];
        \Stripe\Stripe::setApiKey($secritKey);
        
        $customer = \Stripe\Customer::create(array(
            "email" => $email,
            "source" => $token,
            'description' => isset($current_user->display_name) ? $current_user->display_name : ''
        ));

        $charge = \Stripe\Charge::create(array(
            "amount"        => $paid_price,
            "currency"      => $currency,
            "description"   => "Booking Payment payment",
            "customer"      => $customer->id,
            "receipt_email" => $email
        ));
    } catch (\Stripe\Error\Card $e) {
        wp_send_json(array('status' => 'fail', 'msg' => esc_html__('Sorry! There is some problem in your stripe payment', 'medicalpro')));
    }
    
    if ($charge['amount_refunded'] == 0 && $charge['failure_code'] == null && $charge['captured'] == true) {
        medicalpro_save_booking_order( $token, 'stripe', $current_user->ID, $email, $booking_id, $currency, $price, $paid_price_invoice, $taxrate, $tax_price, $coupon );
        $redirectURL = add_query_arg( 'dashboard', 'my-bookings', $listingpro_options['listing-author'] );
        wp_send_json(array('status' => 'success', 'redirectURL' => $redirectURL));
    }else{
        wp_send_json(array('status' => 'fail', 'msg' => esc_html__('Sorry! Error in transaction', 'medicalpro')));
    }
    
}


add_action('wp_ajax_mp_request_withdrawal', 'mp_request_withdrawal');
if (!function_exists('mp_request_withdrawal')) {
    function mp_request_withdrawal() {
        check_ajax_referer( 'lp_ajax_nonce', 'lpNonce' );
        // Nonce is checked, get the POST data and sign user on
        if( !wp_verify_nonce(sanitize_text_field($_POST['lpNonce']), 'lp_ajax_nonce')) {
            $res = json_encode(array('nonceerror'=>'yes'));
            die($res);
        }

        if (is_user_logged_in()) {
            $userID = get_current_user_id();
            $userData = get_userdata($userID);
            $amount = sanitize_text_field($_POST['amount']);
            $details = sanitize_textarea_field($_POST['details']);
            $payout = sanitize_text_field($_POST['payout']);
            $currencySymbol = listingpro_currency_sign();
            if ((!empty($amount) && is_numeric($amount)) && !empty($details)){
                $args = array(
                    'post_type'     => 'lp-withdrawal',
                    'post_title'    => $userData->user_login.' '.esc_html__('Requested', 'medicalpro').' '.$currencySymbol.$amount,
                    'post_content'  => $details,
                    'post_status'   => 'publish',
                    'post_author'   => $userID
                );
                $withdrawalID = wp_insert_post( $args );

                update_post_meta($withdrawalID, 'lp_withdrawal_requested_status', 'unpaid');
                update_post_meta($withdrawalID, 'lp_withdrawal_payout_method', $payout);
                update_post_meta($withdrawalID, 'lp_withdrawal_requested_amount', $amount);

                $res = json_encode(array('MSG'=>'Success'));
            }else {
                $res = json_encode(array('MSG'=>'Invalid Data'));
            }
            die($res);
        }else {
            $res = json_encode(array('MSG'=>'Invalid Session'));
            die($res);
        }
    }
}


add_action('wp_ajax_lp_withdrawal_complete_request', 'lp_withdrawal_complete_request');
if (!function_exists('lp_withdrawal_complete_request')) {
    function lp_withdrawal_complete_request() {
        if (is_user_logged_in() && is_admin()) {
            $postID = sanitize_text_field($_POST['postID']);
            $request = sanitize_text_field($_POST['request']);

            if ($request == 'reject') {
                update_post_meta($postID, 'lp_withdrawal_requested_status', 'rejected');
            }elseif ($request == 'confirm') {
                update_post_meta($postID, 'lp_withdrawal_requested_status', 'paid');
            }

            $res = json_encode(array('status' => 'Success'));
            die($res);
        }else {
            $res = json_encode(array('status' => 'Invalid Session'));
            die($res);
        }
    }
}

add_action('wp_ajax_mp_payout_form_submit', 'mp_payout_form_submit');
if (!function_exists('mp_payout_form_submit')) {
    function mp_payout_form_submit() {
        check_ajax_referer( 'lp_ajax_nonce', 'lpNonce' );
        // Nonce is checked, get the POST data and sign user on
        if( !wp_verify_nonce(sanitize_text_field($_POST['lpNonce']), 'lp_ajax_nonce')) {
            $res = json_encode(array('nonceerror'=>'yes'));
            die($res);
        }
        $userID = get_current_user_id();
        $type = sanitize_text_field($_POST['target']);
        $accountTitle = sanitize_text_field($_POST['accountTitle']);
        $accountNumber = sanitize_text_field($_POST['accountNumber']);
        $accountDetail = sanitize_textarea_field($_POST['accountDetail']);

        $dataArr = array(
            'accountType' => $type,
            'accountTitle' => $accountTitle,
            'accountNumber' => $accountNumber,
            'accountDetail' => $accountDetail,
        );

        update_user_meta($userID, 'mp_user_payouts', $dataArr);

        $res = json_encode(array('status' => 'Success'));
        die($res);
    }
}

add_action('wp_ajax_mp_payout_form_genrate', 'mp_payout_form_genrate');
if (!function_exists('mp_payout_form_genrate')) {
    function mp_payout_form_genrate() {
        check_ajax_referer( 'lp_ajax_nonce', 'lpNonce' );
        // Nonce is checked, get the POST data and sign user on
        if( !wp_verify_nonce(sanitize_text_field($_POST['lpNonce']), 'lp_ajax_nonce')) {
            $res = json_encode(array('nonceerror'=>'yes'));
            die($res);
        }
        $type = null;
        $accountTitle = null;
        $accountNumber = null;
        $accountDetail = null;
        $userID = get_current_user_id();
        $dataArr = get_user_meta($userID, 'mp_user_payouts', true);
        if (!empty($dataArr) && is_array($dataArr)) {
            $accountTitle = $dataArr['accountTitle'];
            $accountNumber = $dataArr['accountNumber'];
            $accountDetail = $dataArr['accountDetail'];
        }
        $type = sanitize_text_field($_POST['target']);
        $html = null;
        $html .= '<div class="PayoutMethodModalFormContainer">
            <div class="row">';
                $html .= '<div class="col-md-6">
                    <div class="form-group">
                        <label for="accountTitle" class="col-form-label">'.esc_html__("Enter Your Account Title", "medicalpro").'</label>
                        <input type="text" class="form-control" name="accountTitle" value="'.$accountTitle.'" id="accountTitle" placeholder="'.esc_html__("John Doe", "medicalpro").'" required>
                    </div>
                </div>';
                $html .= '<div class="col-md-6">
                    <div class="form-group">
                        <label for="accountNumber" class="col-form-label">'.esc_html__("Enter Your Account ID Or Email", "medicalpro").'</label>
                        <input type="text" class="form-control" name="accountNumber" value="'.$accountNumber.'" id="accountNumber" placeholder="'.esc_html__("#090878601 | johndoe@example.com", "medicalpro").'" required>
                    </div>
                </div>';
                if ($type == 'Other' || $type == 'Bank') {
                    $html .= '<div class="col-md-12">
                        <div class="form-group">
                            <label for="accountDetail" class="col-form-label">' . esc_html__("Enter Your Account Details", "medicalpro") . '</label>
                            <textarea class="form-control" name="accountDetail" id="accountDetail" rows="10" style="width: 100%; resize: none" placeholder="' . esc_html__("EG: Bank Name Or Branch Code Or Any Special Not For Site Admin...", "medicalpro") . '">'.$accountDetail.'</textarea>
                        </div>
                    </div>';
                }
            $html .= '</div>
        </div>
        <button class="PayoutMethodModalFormSubmit">Save Changes</button>
        ';

        $res = json_encode(array('html'=>$html));
        die($res);
    }
}

add_action('wp_ajax_mp_earning_invoice_filters', 'mp_earning_invoice_filters');
if (!function_exists('mp_earning_invoice_filters')) {
    function mp_earning_invoice_filters() {
        check_ajax_referer( 'lp_ajax_nonce', 'lpNonce' );
        // Nonce is checked, get the POST data and sign user on
        if( !wp_verify_nonce(sanitize_text_field($_POST['lpNonce']), 'lp_ajax_nonce')) {
            $res = json_encode(array('nonceerror'=>'yes'));
            die($res);
        }
        global $wpdb;
        $targetPage = sanitize_text_field($_POST['targetPage']);
        $maxpostperpage = sanitize_text_field($_POST['maxpostperpage']);
        $totalposts = sanitize_text_field($_POST['totalposts']);
        $start = ($targetPage * $maxpostperpage) - $maxpostperpage;
        $Porders = array();
        $currencySymbol = listingpro_currency_sign();
        $table = $wpdb->prefix.'booking_orders';
        $userID = get_current_user_id();
        $minAmount = sanitize_text_field($_POST['minAmount']);
        $maxAmount = sanitize_text_field($_POST['maxAmount']);
        $fromDate = strtotime(sanitize_text_field($_POST['fromDate']));
        $toDate = strtotime(sanitize_text_field($_POST['toDate']));

        if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
            $dateQuery = null;
            if (!empty($fromDate)) {
                $dateQuery = "AND date >= $fromDate";
            }
            if (!empty($toDate)) {
                $dateQuery = "AND date <= $toDate";
            }
            if (!empty($fromDate) && !empty($toDate)) {
                $dateQuery = "AND date >= $fromDate AND date <= $toDate";
            }
            $priceQuery = null;
            if (!empty($minAmount) && is_numeric($minAmount)) {
                $priceQuery = "AND sub_total >= $minAmount";
            }
            if (!empty($maxAmount) && is_numeric($maxAmount)) {
                $priceQuery = "AND sub_total <= $maxAmount";
            }
            if ((!empty($minAmount) && !empty($maxAmount)) && (is_numeric($minAmount) && is_numeric($maxAmount))) {
                $priceQuery = "AND sub_total >= $minAmount AND sub_total <= $maxAmount";
            }
            $Pquery = "SELECT * from $table WHERE doctor_id = $userID $dateQuery $priceQuery ORDER BY main_id DESC LIMIT $start,$maxpostperpage";
            $Porders = $wpdb->get_results($Pquery);

            if (!empty($fromDate) || !empty($toDate) || !empty($maxAmount) || !empty($minAmount) || empty($totalposts)) {
                $query = "SELECT * from $table WHERE doctor_id = $userID $dateQuery $priceQuery ORDER BY main_id DESC";
                $orders = $wpdb->get_results($query);
                $totalposts = count($orders);
            }
        }
        $html = null;
        $Porderss = null;

        if (!empty($Porders) && is_array($Porders)) {
            $html .= '
            <div class="lp_mp_earnings_dashboard_transactions_container">
                <div class="lp_mp_earnings_dashboard_transactions table-responsive">
                        <table class="lp_mp_earnings_dashboard_transactions_tables table">
                        <tr>
                            <th>' . esc_html__("Doctor", "medicalpro") . '</th>
                            <th>' . esc_html__("Username", "medicalpro") . '</th>
                            <th>' . esc_html__("Amount", "medicalpro") . '</th>
                            <th>' . esc_html__("METHOD", "medicalpro") . '</th>
                            <th>' . esc_html__("web fee", "medicalpro") . '</th>
                            <th>' . esc_html__("Received", "medicalpro") . '</th>
                            <th>' . esc_html__("Date", "medicalpro") . '</th>
                        </tr>';

            foreach ($Porders as $key => $data) {
                $postID = $data->post_id;
                $bookingID = $data->booking_id;
                $userID = $data->customer_id;
                $currency = $data->currency;
                $price = $data->sub_total;
                $Tprice = $data->paid_price;
                $adminCom = $data->commision_price;
                $adminPer = $data->commision_rate;
                $status = $data->status;
                $date = $data->date;
                $paymentMethod = $data->payment_method;
                $transaction_ID = $data->transaction_id;
                $hospital_name = $data->hospital_name;
                $doctor_name = $data->doctor_name;
                $customer = get_userdata($data->customer_id)->user_login;
                $html .= '
                <tr>
                    <td><a href="'. get_permalink($postID).'"
                           target="_blank">'. get_the_title($postID).'</a></td>
                    <td>'. $customer.'</td>
                    <td>'. $currency . ' ' . $Tprice.'</td>
                    <td>'. $paymentMethod.'</td>
                    <td>'. $currency . ' ' . $adminCom . ' (' . $adminPer . '%)'.'</td>
                    <td>'. $currency . ' ' . $price.'</td>
                    <td>'. date_i18n(get_option('date_format'), $date).'</td>
                </tr>
                ';
            }
            $html .= '
                    </table>
                </div>
                <div class="lp_mp_earnings_dashboard_invoice_pagination">
                    '.mp_SQL_pagination($maxpostperpage, $totalposts, $targetPage, $start).'
                </div>
            </div>';
        }else {
	        $html .= esc_html__("No Result Found", "medicalpro");
        }

        $html .= "<input type='hidden' id='Queryargs' data-maxpostperpage='".$maxpostperpage."' data-totalposts='".$totalposts."' data-paged='".$targetPage."' data-startfrom='".$start."'";

        $res = json_encode(array('html'=>$html));
        die($res);
    }
}


add_action('wp_ajax_getsinglelistgallery', 'getsinglelistgallery');
add_action('wp_ajax_nopriv_getsinglelistgallery', 'getsinglelistgallery');
if (!function_exists('getsinglelistgallery')) {
    function getsinglelistgallery(){
        check_ajax_referer( 'lp_ajax_nonce', 'lpNonce' );
        // Nonce is checked, get the POST data and sign user on
        if( !wp_verify_nonce(sanitize_text_field($_POST['lpNonce']), 'lp_ajax_nonce')) {
            $res = json_encode(array('nonceerror'=>'yes'));
            die($res);
        }
        
        $ID = isset($_POST['targetlist']) ? $_POST['targetlist'] : '';
        
        $plan_id = get_post_meta( $post->ID, 'Plan_id', true );
        if (!empty($plan_id)) {
            $plan_id = $plan_id;
        } else {
            $plan_id = 'none';
        }
        $IDs = get_post_meta( $ID, 'gallery_image_ids', true );
        $gallery_show = get_post_meta($plan_id, 'gallery_show', true);
        
        if($gallery_show == "true"){
            $res = json_encode(array('return'=> esc_html__('Issue With Pricing Plan...', 'medicalpro')));
            die($res);
        }
        
        $ximgIDs = array();
        $imgIDs = array();
        $numImages = 0;
        if (!empty($IDs)) {
            $ximgIDs = explode(',',$IDs);
        }else {
            $res = json_encode(array('return'=> esc_html__('No Image Found In Gallery...', 'medicalpro')));
            die($res);
        }
        if (!empty($ximgIDs) && is_array($ximgIDs)) {
            foreach ($ximgIDs as $value) {
                if (!empty(get_post_type($value)) && get_post_type($value) == 'attachment') {
                    $imgIDs[] = $value;
                }
            }
            $numImages = count($imgIDs);
        }else {
            $res = json_encode(array('return'=> esc_html__('No Image Found In Gallery...', 'medicalpro')));
            die($res);
        }
        
        $html = null;
        
        foreach($imgIDs as $imgID){
            $img_url = wp_get_attachment_image_src( $imgID, 'full');
            $imgSrc = $img_url[0];
            $html .= '<a href="'. $imgSrc .'" rel="prettyPhoto[gallery1]"></a>';
        }
    
    	$res = json_encode(array('return'=> $html ));
        die($res);
    }
}

add_action('wp_ajax_mp_booking_timeSlot_duration', 'mp_booking_timeSlot_duration');
function mp_booking_timeSlot_duration()
{
    if (isset($_REQUEST)) {
        $selectedSlot = sanitize_text_field($_REQUEST['selectedSlot']);
        update_user_meta(get_current_user_id(), 'lp_booking_timeslot_duration', $selectedSlot);
    }
    die(json_encode($selectedSlot));
}

add_action('wp_ajax_author_archive_tabs_cb', 'author_archive_tabs_cb');
add_action('wp_ajax_nopriv_author_archive_tabs_cb', 'author_archive_tabs_cb');
if( !function_exists( 'author_archive_tabs_cb' ) )
{
    function author_archive_tabs_cb()
    {
        check_ajax_referer( 'lp_ajax_nonce', 'lpNonce' );
        // Nonce is checked, get the POST data and sign user on
        if( !wp_verify_nonce(sanitize_text_field($_POST['lpNonce']), 'lp_ajax_nonce')) {
            $res = json_encode(array('nonceerror'=>'yes'));
            die($res);
        }

        if( isset( $_POST['authorPagin'] ) )
        {
            $GLOBALS['my_listing_views']    =  sanitize_text_field( $_POST['listingLayout']);
            $GLOBALS['pageno']  =   sanitize_text_field($_POST['pageNo']);
            $GLOBALS['authorID']  =   sanitize_text_field($_POST['authorID']);
            mp_get_template_part( 'templates/author/author-listings' );
        }
        else
        {
            $tabType        =   sanitize_text_field($_POST['tabType']);
            $reviewStyle    =  sanitize_text_field( $_POST['reviewStyle']);
            $authorID       =   sanitize_text_field($_POST['authorID']);
            $listingLayout       =   sanitize_text_field($_POST['listingLayout']);
            $GLOBALS['authorID']  =   $authorID;
            if( $tabType == 'reviews' )
            {
                if( $reviewStyle == 'style1' )
                {
                    mp_get_template_part( 'templates/author/author-reviews-style1' );
                }
                elseif ( $reviewStyle == 'style2' )
                {
                    mp_get_template_part( 'templates/author/author-reviews-style2' );
                }
            }
            elseif ( $tabType == 'photos' )
            {
                mp_get_template_part( 'templates/author/author-photos' );
            }
            elseif ( $tabType == 'aboutme' )
            {
                mp_get_template_part( 'templates/author/author-about' );
            }
            elseif ( $tabType == 'contact' )
            {
                mp_get_template_part( 'templates/author/author-contact' );
            }
            elseif ( $tabType   ==  'mylistings' )
            {
                $GLOBALS['my_listing_views']    =   $listingLayout;
                mp_get_template_part( 'templates/author/author-listings' );
            }
        }
        die();
    }

}