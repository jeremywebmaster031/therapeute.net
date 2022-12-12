<?php
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$current_offset = get_option('gmt_offset');
$tzstring = get_option('timezone_string');
$check_zone_info = true;
if (false !== strpos($tzstring, 'Etc/GMT')) {
    $tzstring = '';
}

if (empty($tzstring)) { // Create a UTC+- zone if no timezone string exists
    $check_zone_info = false;
    if (0 == $current_offset) {
        $tzstring = 'UTC+0';
    } elseif ($current_offset < 0) {
        $tzstring = 'UTC' . $current_offset;
    } else {
        $tzstring = 'UTC+' . $current_offset;
    }
}

$b_args = array(
    'post_type' => 'medicalpro-bookings',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'author' => $user_id,
);
$lp_bookings = new WP_Query($b_args);
$lp_bookings_arr = array();
if ($lp_bookings->have_posts()) : while ($lp_bookings->have_posts()) : $lp_bookings->the_post();
        $booking_start_time = get_post_meta(get_the_ID(), 'booking_slot_start_time', true);
        $lp_bookings_arr[] = get_the_ID();
    endwhile;
    wp_reset_postdata();
endif;
ksort($lp_bookings_arr);


$first_booking_id = reset($lp_bookings_arr);
?>

<input type="hidden" id="lp_booking_get_time_zone_val" value="<?php echo $tzstring; ?>">
<div class="lp-dashboard-booking-calander-header clearfix">
    <p class="grid-btn back-to-all-bookings"><i class="fa fa-chevron-left"></i><?php echo esc_html__('My Bookings', 'listingpro-bookings'); ?></p>
    <br>
    <div class="pull-left lp-dashboard-booking-calander-header-title"><?php echo esc_html__('Calendar', 'listingpro-bookings'); ?></div>
    <div class="pull-right lp-dashboard-booking-calander-header-status"><div class="booking-status approved pull-left"></div><?php echo esc_html__('Calendar only displays the approved Appointments.', 'listingpro-bookings'); ?></div>
</div>
<div class="back-to-bookings">
    <a href="" data-toggle="tab" aria-expanded="true">
        <button class="btn bookings-back-btn">
            <span><?php echo esc_html__(' Back To Bookings', 'listingpro-bookings'); ?></span></button>
    </a>
</div>
<div id="lp-dashboard-booking-calander"></div>
<div class="clear"></div>
<div class="booking-grid-wrapper">
    <div class="custom-col-width col-md-9 booking-left-section">
        <div class="clearfix lp-dashboard-panel-outer lp-new-dashboard-panel-outer margin-top-20" style="display: none;">
            <div class="notices-area">
                <div class="notice warning">
                    <a href="#" class="close"><i class="fa fa-times"></i></a>
                    <div class="notice-icon">
                        <i class="fa fa-info-circle"></i>
                    </div>
                    <div class="notice-text">
                        <h2>
                            <span><?php esc_html__('  Appointments are pending approval. Pending Appointments will only show in calendar upon approval.', 'listingpro-bookings'); ?></span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="bookings">
            <div class="booking-heading">
                <h1><?php echo esc_html__('My Bookings', 'listingpro-bookings'); ?></h1>
            </div>
            <div class="panel with-nav-tabs panel-default lp-dashboard-tabs col-md-12 lp-left-panel-height lp-left-panel-height-outer padding-bottom0">
                <div class="lp-menu-step-one margin-top-20">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#all-bookings" data-toggle="tab" aria-expanded="true"><?php echo esc_html__('All', 'listingpro-bookings'); ?></a>
                            </li>
                            <li class="">
                                <a href="#pending-bookings" data-toggle="tab" aria-expanded="false"><?php echo esc_html__('PENDING', 'listingpro-bookings'); ?></a>
                            </li>
                            <li class="">
                                <a href="#approved-bookings" data-toggle="tab" aria-expanded="false"><?php echo esc_html__('APPROVED', 'listingpro-bookings'); ?></a>
                            </li>
                            <li class="">
                                <a href="#canceled-bookings" data-toggle="tab" aria-expanded="false"><?php echo esc_html__('CANCELED', 'listingpro-bookings'); ?></a>
                            </li>
                            <li class="">
                                <a href="#expired-bookings" data-toggle="tab" aria-expanded="false"><?php echo esc_html__('Expired', 'listingpro-bookings'); ?></a>
                            </li>
                            <li class="pull-right">
                                <a href="" data-toggle="tab" aria-expanded="false">
                                    <button class="btn calendar-btn" data-bookings_type="my-bookings" data-last-day="<?php echo date('t F Y'); ?>" data-first-day="<?php echo '1 ' . date('F Y'); ?>"><i class="fa fa-calendar" aria-hidden="true"></i>
                                        <span> <?php echo esc_html__('CALENDAR', 'listingpro-bookings'); ?></span>
                                    </button>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="clearfix"></div>
                <ue= class="tab-content lp-tab-content-outer clearfix">
                    <div class="tab-pane fade active in" id="all-bookings">
                        <div class="booking-content">
                            <ul class="booking-action-header">
                                <li class="cell"><input type="radio" style="visibility: hidden;" name=""></li>
                                <li class="cell"><?php echo esc_html__('LISTING', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('Hospital', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('DATE', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('TIME', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('STATUS', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('ACTION', 'medicalpro'); ?></li>
                            </ul>
                            <?php
                            $booking_couner = 0;
                            if (is_array($lp_bookings_arr) && !empty($lp_bookings_arr) && count($lp_bookings_arr) > 0) {
                                foreach ($lp_bookings_arr as $k => $v) {
                                    $booking_couner++;
                                    set_query_var('booking_id', $v);
                                    set_query_var('counter', $booking_couner);
                                    set_query_var('my_bookings', 'true');
                                    mp_get_template_part('templates/loop/booking');
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pending-bookings">
                        <div class="booking-content">
                            <?php
                            $pending_listing_Arr = 0;
                            ?>
                            <ul class="booking-action-header">
                                <li class="cell"><input type="radio" style="visibility: hidden;" name=""></li>
                                <li class="cell"><?php echo esc_html__('CUSTOMER', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('LISTING', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('DATE', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('TIME', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('STATUS', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('ACTION', 'medicalpro'); ?></li>
                            </ul>
                            <?php
                            $booking_couner = 0;
                            if (is_array($lp_bookings_arr) && !empty($lp_bookings_arr) && count($lp_bookings_arr) > 0) {
                                foreach ($lp_bookings_arr as $k => $v) {
                                    $booking_couner++;
                                    
                                    $booking_status = get_post_meta($v, 'booking_status', true);
                                    if($booking_status == 'PENDING'){
                                        set_query_var('booking_id', $v);
                                        set_query_var('counter', $booking_couner);
                                        set_query_var('my_bookings', 'true');
                                        mp_get_template_part('templates/loop/booking');
                                    }
                                }
                            }
                            ?>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="approved-bookings">
                        <div class="booking-content">
                            <ul class="booking-action-header">
                                <li class="cell"><input type="radio" style="visibility: hidden;" name=""></li>
                                <li class="cell"><?php echo esc_html__('CUSTOMER', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('LISTING', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('DATE', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('TIME', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('STATUS', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('ACTION', 'medicalpro'); ?></li>
                            </ul>
                            <?php
                            $booking_couner = 0;
                            if (is_array($lp_bookings_arr) && !empty($lp_bookings_arr) && count($lp_bookings_arr) > 0) {
                                foreach ($lp_bookings_arr as $k => $v) {
                                    $booking_couner++;
                                    
                                    $booking_status = get_post_meta($v, 'booking_status', true);
                                    if($booking_status == 'APPROVED'){
                                        set_query_var('booking_id', $v);
                                        set_query_var('counter', $booking_couner);
                                        set_query_var('my_bookings', 'true');
                                        mp_get_template_part('templates/loop/booking');
                                    }
                                }
                            }
                            ?>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="canceled-bookings">
                        <div class="booking-content">
                            <ul class="booking-action-header">
                                <li class="cell"><input type="radio" style="visibility: hidden;" name=""></li>
                                <li class="cell"><?php echo esc_html__('CUSTOMER', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('LISTING', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('DATE', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('TIME', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('STATUS', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('ACTION', 'medicalpro'); ?></li>
                            </ul>
                            <?php
                            $booking_couner = 0;
                            if (is_array($lp_bookings_arr) && !empty($lp_bookings_arr) && count($lp_bookings_arr) > 0) {
                                foreach ($lp_bookings_arr as $k => $v) {
                                    $booking_couner++;
                                    
                                    $booking_status = get_post_meta($v, 'booking_status', true);
                                    if($booking_status == 'CANCELED'){
                                        set_query_var('booking_id', $v);
                                        set_query_var('counter', $booking_couner);
                                        set_query_var('my_bookings', 'true');
                                        mp_get_template_part('templates/loop/booking');
                                    }
                                }
                            }
                            ?>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="expired-bookings">
                        <div class="booking-content">
                            
                            <ul class="booking-action-header">
                                <li class="cell"><input type="radio" style="visibility: hidden;" name=""></li>
                                <li class="cell"><?php echo esc_html__('CUSTOMER', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('LISTING', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('DATE', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('TIME', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('STATUS', 'medicalpro'); ?></li>
                                <li class="cell"><?php echo esc_html__('ACTION', 'medicalpro'); ?></li>
                            </ul>
                            <?php
                            $booking_couner = 0;
                            if (is_array($lp_bookings_arr) && !empty($lp_bookings_arr) && count($lp_bookings_arr) > 0) {
                                foreach ($lp_bookings_arr as $k => $v) {
                                    $booking_couner++;
                                    
                                    $timezone = get_option('gmt_offset');
                                    $time_now = gmdate("H:i", time() + 3600 * ($timezone + date("I")));
                                    $timeZone_str = strtotime($time_now);
                                    
                                    $booking_status = get_post_meta($v, 'booking_status', true);
                                    if($booking_status == 'APPROVED' && $timeZone_str >= get_post_meta($v, 'booking_slot_end_time', true)){
                                        set_query_var('booking_id', $v);
                                        set_query_var('counter', $booking_couner);
                                        mp_get_template_part('templates/loop/booking');
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <input class="pending_listing_Arr" type="hidden" value="<?php echo $pending_listing_Arr . ' ' . esc_html__('Appointments are pending approval. Pending appointments will only show in calendar upon approval.', 'listingpro-bookings'); ?>">
                    </div>
                    </div>
                    </div>

                    <div class="custom-col-width col-md-3 tab-content lp-tab-content-outer">
                        <div class="bookings-sidebar tab-pane fade active in" id="booking-details-sidebar">
                            <?php
                            set_query_var('c_booking_id', $first_booking_id);
                            set_query_var('my_bookings', 'true');
                            mp_get_template_part('templates/dashboard/booking-detail'); 
                            ?>
                        </div>
                    </div>
                    <div class="clear"></div>
            </div>

        </div>