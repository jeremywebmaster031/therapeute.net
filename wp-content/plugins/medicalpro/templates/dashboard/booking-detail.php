<?php
if (!empty($c_booking_id)) {
    $currency_position = lp_theme_option('pricingplan_currency_position');
    $booking_type              = get_post_meta($c_booking_id, 'booking_type', true);
    $booking_video_consult_fee = get_post_meta($c_booking_id, 'booking_video_consult_fee', true);
    $booking_currency          = get_post_meta($c_booking_id, 'booking_currency', true);
    $booking_payment_status    = get_post_meta($c_booking_id, 'booking_payment_status', true);

    $cb_date_str = get_post_meta($c_booking_id, 'booking_date', true);
    $cb_date = date_i18n(get_option('date_format'), $cb_date_str);

    $cb_day = date_i18n('l', $cb_date_str);
    $cb_start_time_str = get_post_meta($c_booking_id, 'booking_slot_start_time', true);
    $cb_start_time = date_i18n(get_option('time_format'), $cb_start_time_str);
    $cb_end_time_str = get_post_meta($c_booking_id, 'booking_slot_end_time', true);
    $cb_end_time = date_i18n(get_option('time_format'), $cb_end_time_str);
    $cb_fname = get_post_meta($c_booking_id, 'booking_fname', true);
    $cb_lname = get_post_meta($c_booking_id, 'booking_lname', true);
    $cb_email = get_post_meta($c_booking_id, 'booking_email', true);
    $cb_phone = get_post_meta($c_booking_id, 'booking_phone', true);
    $cb_msg = get_post_field('post_content', $c_booking_id);
    $booking_listing_id = get_post_meta($c_booking_id, 'booking_listing_id', true);

    $current_offset = get_option( 'gmt_offset' );
    $tzstring       = get_option( 'timezone_string' );
    $check_zone_info = true;
    // Remove old Etc mappings. Fallback to gmt_offset.
    if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
    	$tzstring = '';
    }
    if ( empty( $tzstring ) ) { // Create a UTC+- zone if no timezone string exists.
    	$check_zone_info = false;
    	if ( 0 == $current_offset ) {
    		$tzstring = 'UTC+0';
    	} elseif ( $current_offset < 0 ) {
    		$tzstring = 'UTC' . $current_offset;
    	} else {
    		$tzstring = 'UTC+' . $current_offset;
    	}
    }

    $booking_hospital_id = get_post_meta($c_booking_id, 'booking_hospital_id', true);
    $hospital_term = get_term_by('id', $booking_hospital_id, 'medicalpro-hospital');

    $booking_insurane_id = get_post_meta($c_booking_id, 'booking_insurane_id', true);
    if ($booking_insurane_id != '-')
    $insurance_term = get_term_by('id', $booking_insurane_id, 'medicalpro-insurance');

    $gAddress = get_post_meta($booking_listing_id, 'lp_listingpro_options', true);
    $gAddress = $gAddress['gAddress'];
    
    if($my_bookings == 'true'){ ?>
        <h4 class="booking-action-header"><?php echo esc_html__('Doctor Detail', 'medicalpro'); ?> </h4>
        <div class="user-booking-detail">
            <span class="user-booking-detail-name"><?php echo esc_html__('Doctor Name', 'medicalpro'); ?> </span>
            <span class="user-booking-detail-name-detail pull-right" title="<?php echo get_the_title($booking_listing_id); ?>"><?php echo get_the_title($booking_listing_id); ?></span>
            <br>
            <span class="user-booking-detail-name"><?php echo esc_html__('Hospital Name', 'medicalpro'); ?> </span>
            <span class="user-booking-detail-name-detail pull-right" title="<?php echo isset($hospital_term->name) ? $hospital_term->name : ''; ?>"><?php echo isset($hospital_term->name) ? $hospital_term->name : ''; ?></span>
            <br>
            <?php if(isset($gAddress) && !empty($gAddress)){ ?>
                <span class="user-booking-detail-name"><?php echo esc_html__('Address', 'medicalpro'); ?></span>
                <span class="user-booking-detail-name-detail pull-right underline" title="<?php echo $gAddress; ?>"><?php echo $gAddress; ?></span>
            <?php } ?>
        </div>
    <?php }else{
        $booker_id = get_post_field('post_author', $c_booking_id);
        $booker_data = get_user_by('ID', $booker_id);
        $author_avatar_url = get_user_meta($booker_id, "listingpro_author_img_url", true);
        if (!empty($author_avatar_url)) {
            $avatar = $author_avatar_url;
        } else {
            $avatar_url = listingpro_get_avatar_url($booker_id, $size = '94');
            $avatar = $avatar_url;
        }
        ?>
        <div class="user-detail">
            <div class="user-sidebar-avatar">
                <img src="<?php echo $avatar; ?>">
            </div>
            <p class="user-name"><?php echo $booker_data->display_name; ?></p>
            <p class="user-status"><?php echo esc_html__('Registered User', 'medicalpro'); ?></p>
        </div>
    <?php } ?>
    <h4 class="booking-action-header"><?php echo esc_html__('Appointment Detail', 'medicalpro'); ?> </h4>
    <div class="user-booking-detail">
        <span class="user-booking-detail-name"><?php echo esc_html__('Type', 'medicalpro'); ?> </span>
        <span class="user-booking-detail-name-detail pull-right" title="<?php echo medicalpro_booking_types($booking_type); ?>"><?php echo medicalpro_booking_types($booking_type); ?></span>
        <br>
        <span class="user-booking-detail-name"><?php echo esc_html__('Insurance', 'medicalpro'); ?> </span>
        <span class="user-booking-detail-name-detail pull-right" title="<?php echo isset($insurance_term->name) ? $insurance_term->name : esc_html__('I am paying for myself', 'medicalpro'); ?>"><?php echo isset($insurance_term->name) ? $insurance_term->name : esc_html__('MySelf', 'medicalpro'); ?></span>
        <?php if($my_bookings != 'true'){ ?>
        <br>
        <span class="user-booking-detail-name"><?php echo esc_html__('Hospital Name', 'medicalpro'); ?> </span>
        <span class="user-booking-detail-name-detail pull-right" title="<?php echo isset($hospital_term->name) ? $hospital_term->name : ''; ?>"><?php echo isset($hospital_term->name) ? $hospital_term->name : ''; ?></span>
        <?php } ?>
        <br>
        <span class="user-booking-detail-name"><?php echo esc_html__('Full Name', 'medicalpro'); ?> </span>
        <span class="user-booking-detail-name-detail pull-right" title="<?php echo $cb_fname, ' ', $cb_lname ?>"><?php echo $cb_fname, ' ', $cb_lname ?></span>
        <br>
        <span class="user-booking-detail-name"><?php echo esc_html__('Date', 'medicalpro'); ?> </span>
        <span class="user-booking-detail-name-detail pull-right" title="<?php echo $cb_day, ' , ', $cb_date ?>"><?php echo $cb_day, ' , ', $cb_date ?></span>
        <br>
        <span class="user-booking-detail-name"><?php echo esc_html__('Time', 'medicalpro'); ?></span>
        <span class="user-booking-detail-name-detail pull-right" title="<?php echo $cb_start_time ?> - <?php echo $cb_end_time ?> <?php echo $tzstring; ?>"><?php echo $cb_start_time ?> - <?php echo $cb_end_time ?> <?php echo $tzstring; ?></span>
        <br>
        <span class="user-booking-detail-name"><?php echo esc_html__('Email', 'medicalpro'); ?></span>
        <a href="mailto:<?php echo $cb_email ?>"><span class="user-booking-detail-name-detail pull-right" title="<?php echo $cb_email ?>"><?php echo $cb_email ?></span></a><br>
        <span class="user-booking-detail-name"><?php echo esc_html__('Phone', 'medicalpro'); ?></span>
        <a href="tel:<?php echo $cb_phone ?>"><span class="user-booking-detail-name-detail pull-right" title="<?php echo $cb_phone ?>"><?php echo $cb_phone ?></span></a><br>
        <?php if(isset($cb_msg) && !empty($cb_msg)){ ?>
            <span class="user-booking-detail-name"><?php echo esc_html__('Message', 'medicalpro'); ?></span><br>
            <span class="user-booking-detail-name-detail mp-width-100-block"><?php echo $cb_msg ?></span>
        <?php } ?>
    </div>
    <?php if($booking_type == 'video-consultation' && $booking_video_consult_fee > 0){
        global $wpdb;
        $table         = $wpdb->prefix.'booking_orders';
        $booking_order = $wpdb->get_row( "SELECT * FROM $table WHERE booking_id=' $c_booking_id ' ORDER BY main_id DESC", ARRAY_A );
        $invoice               = isset($booking_order['order_id']) ? $booking_order['order_id'] : '';
        $paid_price            = isset($booking_order['paid_price']) ? $booking_order['paid_price'] : 0;
        $taxprice              = isset($booking_order['taxprice']) ? $booking_order['taxprice'] : 0;
        $status                = isset($booking_order['status']) ? $booking_order['status'] : '';
        $payment_method        = isset($booking_order['status']) ? $booking_order['payment_method'] : '';
        $paid_date             = isset($booking_order['paid_date']) ? $booking_order['paid_date'] : '';

        $currency_sign            = isset($booking_order['currency']) ? $booking_order['currency'] : listingpro_currency_sign();

        $total_price     = $currency_sign.$paid_price;
        if (is_numeric($paid_price) && is_numeric($taxprice)){
             $sub_total_price = $currency_sign.($paid_price-$taxprice);   
        }else{
            $sub_total_price = $currency_sign.$paid_price;
        }
        if (is_numeric($taxprice) && !empty($taxprice)){
            $tax_price       = $currency_sign.$taxprice;
        }
        if( $currency_position == 'right'){
            $total_price     = $paid_price.$currency_sign;
            $sub_total_price = ($paid_price-$taxprice).$currency_sign;
            $tax_price       = $taxprice.$currency_sign;
        }
        ?>
        <h4 class="booking-action-header"><?php echo esc_html__('Invoice Detail', 'medicalpro'); ?> </h4>
        <div class="user-booking-detail">
            <?php if (!empty($invoice)){ ?>
            <span class="user-booking-detail-name"><?php echo esc_html__('Invoice #', 'medicalpro'); ?> </span>
            <span class="user-booking-detail-name-detail pull-right"><?php echo $invoice; ?></span>
            <br>
            <?php } ?>
            <?php if (!empty($paid_date)){ ?>
            <span class="user-booking-detail-name"><?php echo esc_html__('Date', 'medicalpro'); ?> </span>
            <span class="user-booking-detail-name-detail pull-right"><?php echo date_i18n(get_option('date_format'), $paid_date); ?></span>
            <br>
            <?php } ?>
            <?php if (!empty($tax_price)){ ?>
            <span class="user-booking-detail-name"><?php echo esc_html__('Tax Price', 'medicalpro'); ?> </span>
            <span class="user-booking-detail-name-detail pull-right"><?php echo $tax_price; ?></span>
            <br>
            <?php } ?>
            <?php if (!empty($sub_total_price)){ ?>
            <span class="user-booking-detail-name"><?php echo esc_html__('Video Consultation Fee', 'medicalpro'); ?> </span>
            <span class="user-booking-detail-name-detail pull-right"><?php echo $sub_total_price; ?></span>
            <br>
            <?php } ?>
            <?php if (!empty($total_price)){ ?>
            <span class="user-booking-detail-name"><?php echo esc_html__('Total', 'medicalpro'); ?> </span>
            <span class="user-booking-detail-name-detail pull-right"><?php echo $total_price; ?></span>
            <br>
            <?php } ?>
            <?php if (!empty($booking_payment_status)){ ?>
            <span class="user-booking-detail-name"><?php echo esc_html__('Payment Status', 'medicalpro'); ?> </span>
            <span class="user-booking-detail-name-detail pull-right"><?php echo medicalpro_booking_payment_statuses($booking_payment_status); ?></span>
            <br>
            <?php } ?>
            <?php if (!empty($payment_method)){ ?>
            <span class="user-booking-detail-name"><?php echo esc_html__('Method', 'medicalpro'); ?> </span>
            <span class="user-booking-detail-name-detail pull-right"><?php echo $payment_method; ?></span>
            <br>
            <?php } ?>
        </div>
    <?php } ?>
    <?php
} else {
    ?>
    <p><strong><?php echo esc_html__('No Appointment Selected', 'medicalpro'); ?></strong></p>
    <?php
}