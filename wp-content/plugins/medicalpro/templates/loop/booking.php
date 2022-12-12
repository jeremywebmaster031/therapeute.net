<?php
global $listingpro_options;

$booking_user_id = get_post_meta($booking_id, 'booking_user_id', true);
$listing_id      = get_post_meta($booking_id, 'booking_listing_id', true);
$hospital_id     = get_post_meta($booking_id, 'booking_hospital_id', true);
$hospital_info   = get_term_by('id', $hospital_id, 'medicalpro-hospital');

$booking_date    = date_i18n(get_option('date_format'), (int) get_post_meta($booking_id, 'booking_date', true));
$booking_start   = date_i18n(get_option('time_format'), (int) get_post_meta($booking_id, 'booking_slot_start_time', true));
$booking_end     = date_i18n(get_option('time_format'), (int) get_post_meta($booking_id, 'booking_slot_end_time', true));
$booking_status  = get_post_meta($booking_id, 'booking_status', true);
$payment_status  = get_post_meta($booking_id, 'booking_payment_status', true);
if (!isset($c_booking_id)) {
    $c_booking_id = get_the_ID();
}
$booker_id       = get_post_field('post_author', $c_booking_id);
$booker_data     = get_user_by('ID', $booker_id);

if ($booking_status == "APPROVED") {
    $status_color = "approved";
} else if ($booking_status == "CANCELED") {
    $status_color = "canceled";
} else {
    $status_color = "pending";
}
$activeClass = null;
if ($counter == 1) {
    $activeClass = 'active';
}

?>
<ul class="booking-action-content <?php echo $activeClass; ?>">
    <li class="cell">
        <label id="<?php echo $booking_id; ?>" data-mybookings="<?php echo $my_bookings;?>" class="radio-container-box">
            <input type="radio" name="radio" >
            <span class="checkmark<?php
            if ($counter == 1) {
                echo ' active';
            };
            ?>"></span>
        </label>
    </li>
    <?php if( $my_bookings != 'true'){ ?>
        <li class="cell"><?php echo isset($booker_data->display_name) ? $booker_data->display_name : ''; ?> </li>
    <?php } ?>
    <li class="cell"><a href="<?php echo get_permalink($listing_id); ?>" target="_blank" class="underline color-a"><?php echo get_the_title($listing_id); ?></a></li>
    <?php if( $my_bookings == 'true'){ ?>
        <li class="cell"><?php echo isset($hospital_info->name) ? $hospital_info->name : ''; ?> </li>
    <?php } ?>
    <li class="cell"><?php echo $booking_date; ?></li>
    <li class="cell"><?php echo $booking_start ?></li>

    <li class="cell" align="center">
        <div class="booking-status 
            <?php echo $status_color; ?>">
        </div>
        <?php if( $my_bookings == 'true' && $payment_status == 'pending' && $booking_status != 'CANCELED' && $booking_status != 'APPROVED' ){ ?>
            <a href="<?php echo add_query_arg(array('booking_id' => $booking_id, 'user_id' => $booking_user_id), get_permalink($listingpro_options['payment-checkout'])); ?>" class="booking-pay-btn"><span><i class="fa fa-credit-card" aria-hidden="true"></i></span> <?php esc_html_e('Pay', 'medicalpro'); ?></a>
        <?php } ?>
    </li>
    <li class="cell">
        <?php 
        if($my_bookings == 'true'){
            if( $booking_status == 'PENDING' && ($payment_status == 'pending' || $payment_status == '') ){ ?>
                <a class="mp-my-booking-cancel-action" data-status="CANCELED" data-id="<?php echo $booking_id; ?>" href="#"><?php echo esc_html__('CANCEL', 'listingpro-bookings'); ?></a>
            <?php }else{ ?>
                -
            <?php } ?>
        <?php } ?>
        <?php 
        if($my_bookings != 'true'){
            if( $booking_status == 'PENDING'){ ?>
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"> <?php echo $booking_status; ?>&nbsp;&nbsp;<i class="fa fa-angle-down"></i></button>
                    <ul class="dropdown-menu">
                        <li><a data-status="APPROVED" data-id="<?php echo $booking_id; ?>" href="#"><?php echo esc_html__('APPROVE', 'listingpro-bookings'); ?></a>
                        </li>
                        <?php if( $payment_status != 'paid' ){ ?>
                        <li><a data-status="CANCELED" data-id="<?php echo $booking_id; ?>" href="#"><?php echo esc_html__('CANCEL', 'listingpro-bookings'); ?></a>
                        <?php } ?>
                        </li>
                    </ul>
                </div>
            <?php }else{ ?>
                    -
            <?php }
        }
        ?>
    </li>
    <li class="cell"><i class="fa fa-angle-right noticefi_er"></i></li>
</ul>