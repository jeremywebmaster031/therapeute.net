<?php
global $post, $listingpro_options;

$listing_id             = $post->ID;
$listing_hospitals      = wp_get_post_terms($listing_id, 'medicalpro-hospital');
$listing_insurances     = wp_get_post_terms($listing_id, 'medicalpro-insurance');
$listing_hospitals_data = get_post_meta($listing_id, 'medicalpro_listing_hospitals', true );
$virtual_consult     = get_post_meta($listing_id, 'mp_listing_extra_fields_virtual_consult', true);
if (empty($virtual_consult)) {
    $virtual_consult = 'no';
}
if ($virtual_consult == 'Yes') {
    $ColClass = 'col-md-6';
} else {
    $ColClass = 'col-md-12';
}

$data_target = '';
$class = '';
if (!is_user_logged_in()) {
    $popup_style = $listingpro_options['login_popup_style'];
    $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];
    if ( $popup_style == 'style1' && !wp_is_mobile() ) {
        $class = 'mp-booking-bar-login md-trigger';
        $data_target = 'data-modal="modal-3"';
    } elseif ( $popup_style == 'style2' && !wp_is_mobile() ) {
        $class =  'mp-booking-bar-login app-view-popup-style';
        $data_target = 'data-target="#app-view-login-popup"';
    }
}
?>
<input type="hidden" id="datepicker-lang" value="<?php echo get_option('WPLANG'); ?>">
<div class="clearfix"></div>

<div class="medicalpro-booking-section pos-relative <?php echo $class; ?>" <?php echo $data_target; ?>>
    <form id="medicalpro-booking-form" name="medicalpro-booking-form" method="post">
        <h3 class="md-booking-con-title margin-bottom-20"><?php esc_html_e('Book an Appointment', 'medicalpro'); ?>
            <span class="book-step-1 select-bok-step"></span>
            <span class="book-step-2"></span>
            <span class="book-step-3"></span>
        </h3>
        <div class="md-booking-sidebar-tabs margin-bottom-15">
            <ul class="card-header-tabs clearfix">
                <li class="<?php echo $ColClass; ?> padding-0 active <?php if ($virtual_consult !== 'Yes') echo 'full-width'; ?>">
                    <a class="nav-link" id="in-person" href="javascript:void(0);"><i class="fa fa-medkit"></i> <?php esc_html_e('In-person', 'medicalpro'); ?></a>
                </li>
                <?php if ($virtual_consult == 'Yes') : ?>
                <li class="<?php echo $ColClass; ?> padding-0">
                    <a class="nav-link" id="video-consultation" href="javascript:void(0);"><i class="fa fa-video-camera"></i> <?php esc_html_e('Video Consultation', 'medicalpro'); ?></a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="tab-content md-booking-sidebar-tabs-content" id="myTabContent">
            <div class="tab-pane fade active p-3" id="one" role="tabpanel" aria-labelledby="one-tab">
                <div id="step-1">
                    <input type="hidden" name="listing_id" id="listing_id" value="<?php echo $listing_id; ?>">
                    <input class="required" type="hidden" name="booking_date" id="booking_date" value="">
                    <input class="required" type="hidden" name="slot_start_time" id="slot_start_time" value="">
                    <input class="required" type="hidden" name="slot_end_time" id="slot_end_time" value="">
                    <input class="required" type="hidden" name="booking_type" id="booking_type" value="">
                    <div class="form-group">
                        <label><?php esc_html_e('Select Place', 'medicalpro'); ?></label>
                        <select name="hospital_id" id="place" class="form-control select2 required">
                            <?php foreach ($listing_hospitals as $listing_hospital) { ?>
                                <?php $price =  isset($listing_hospitals_data[$listing_hospital->term_id]['price']) ? $listing_hospitals_data[$listing_hospital->term_id]['price'] : ''; ?>
                                <option value="<?php echo $listing_hospital->term_id; ?>" data-date="<?php echo strtotime("now"); ?>" data-price="<?php echo $price; ?>" data-address="<?php echo get_term_meta($listing_hospital->term_id, 'address', true); ?>"><?php echo $listing_hospital->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if (isset($listing_insurances) && !empty($listing_insurances)) { ?>
                        <div class="form-group">
                            <label><?php esc_html_e('Select Insurance Plan', 'medicalpro'); ?></label>
                            <select name="insurance" id="insurance" class="form-control select2 required">
                                <option value="-"><?php esc_html_e('Select Insurance Plan', 'medicalpro'); ?></option>
                                <?php foreach ($listing_insurances as $listing_insurance) { ?>
                                    <option value="<?php echo $listing_insurance->term_id; ?>"><?php echo $listing_insurance->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                    <div class="booking-date-selection margin-bottom-10">
                        <label class="margin-bottom-10"><?php esc_html_e('Select Date & Slot', 'medicalpro'); ?></label>
                        <div class="date-slider">
                            <ul class="date-slider-list" data-lid="<?php echo $listing_id; ?>">
                                <li class="booking-slider-arrow-left DisableArrow" data-date="<?php echo strtotime(current_time('Y-m-d H:i:s')." -1 day"); ?>"><i class="fa fa-angle-left" aria-hidden="true"></i></li>
                                <li class="booking-date active" data-date="<?php echo strtotime(current_time('Y-m-d H:i:s')); ?>"><div class="booking-day"><?php esc_html_e('Today', 'medicalpro'); ?></div><?php echo date_i18n('D,d M', strtotime(current_time('Y-m-d H:i:s'))); ?></li>
                                <li class="booking-date" data-date="<?php echo strtotime(current_time('Y-m-d H:i:s')." +1 day"); ?>"><div class="booking-day"><?php esc_html_e('Tomorrow', 'medicalpro'); ?></div><?php echo date_i18n('D,d M', strtotime(current_time('Y-m-d H:i:s')." +1 day")); ?></li>
                                <li class="booking-date" data-date="<?php echo strtotime(current_time('Y-m-d H:i:s')." +2 day"); ?>"><div class="booking-day"><?php echo date_i18n('l', strtotime(current_time('Y-m-d H:i:s')."+2 day")); ?></div><?php echo date_i18n('d-M-Y', strtotime(current_time('Y-m-d H:i:s')." +2 day")); ?></li>
                                <li class="booking-slider-arrow-right" data-date="<?php echo strtotime(current_time('Y-m-d H:i:s')." +1 day"); ?>"><i class="fa fa-angle-right" aria-hidden="true"></i></li>
                            </ul>
                        </div>
                        <div class="medical-booking-slots-outer-wrap">
                            <?php
                            $hospital_id = isset($listing_hospitals[0]->term_id) ? $listing_hospitals[0]->term_id : 0;
                            //by Abbas 4 july 2022
                            $post_author_id = get_post_field( 'post_author', $listing_id );
                            $lp_booking_timeslot_duration = get_user_meta($post_author_id, 'lp_booking_timeslot_duration', true);
                            if (empty($lp_booking_timeslot_duration)) {
                                $lp_booking_timeslot_duration = '30 mins';
                            }else{
                                $lp_booking_timeslot_duration = $lp_booking_timeslot_duration .' mins'; 
                            }
                            echo $times = medicalpro_create_time_range(get_the_ID(), $hospital_id, strtotime(current_time('Y-m-d H:i:s')), $lp_booking_timeslot_duration, 12, 'yes');
                            //End by Abbas 4 july 2022
                            ?>
                        </div>
                    </div>
                    <div class="booking-date-calendar margin-bottom-20 clearfix"><div id="booking-calendar-select-date"></div></div>
                    <button id="continue_booking" type="button" disabled=""><?php esc_html_e('Continue Booking', 'medicalpro'); ?></button>

                    <div class="lp-booking-section-footer clearfix margin-top-20 margin-bottom-10">
                        <?php
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
                        ?>
                        <span class="medicalpro-booking-footer-timezone pull-left"><?php echo 'Timezone: ' . $tzstring; ?> </span>
                        <span class="medicalpro-booking-footer-view-switch pull-right"><?php echo esc_html__('Switch to', 'medicalpro'); ?> <i class="fa fa-calendar-o" aria-hidden="true"></i> </span>
                    </div>
                    <div class="row video-consultation-fee" style="display:none;">
                        <div class="col-md-12 margin-top-10">
                            <p class="md-cons-price col-md-12">
                                <span class="currency"><?php echo listingpro_currency_sign(); ?></span><span class="price">0</span><?php esc_html_e('TAX-EXCLUSIVE') ?> &nbsp;&nbsp; <?php esc_html_e('(For Video Consultation)', 'medicalpro'); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div id="step-2">
                    <?php
                    $current_user = wp_get_current_user();
                    $user_phone = get_user_meta($current_user->ID, 'phone', true);
                    ?>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label><?php esc_html_e('Hospital Name', 'medicalpro'); ?></label>
                            <div id="selected_hospital"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="selected_date"><?php esc_html_e('Selected Date', 'medicalpro'); ?></label>
                            <input type="text" name="selected_date" id="selected_date" class="form-control" value="" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="selected_time"><?php esc_html_e('Selected Time', 'medicalpro'); ?></label>
                            <input type="text" name="selected_time" id="selected_time" class="form-control" value="" disabled>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="selected_insurance"><?php esc_html_e('Selected Insurance Plan', 'medicalpro'); ?></label>
                            <select id="selected_insurance" name="selected_insurance" class="custom-form-control select2" disabled="disabled">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fname"><?php esc_html_e('First Name', 'medicalpro'); ?> *</label>
                            <input id="fname" type="text" class="form-control required" required name="fname" value="<?php echo $current_user->first_name; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lname"><?php esc_html_e('Last Name', 'medicalpro'); ?> *</label>
                            <input id="lname" type="text" class="form-control required" required name="lname" value="<?php echo $current_user->last_name; ?>">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="email"><?php esc_html_e('Email', 'medicalpro'); ?> *</label>
                            <input id="email" type="email" class="form-control required" required name="email" value="<?php echo $current_user->user_email; ?>">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="phone"><?php esc_html_e('Phone', 'medicalpro'); ?> *</label>
                            <input id="phone" type="number" class="form-control required" required name="phone" value="<?php echo $user_phone; ?>">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="details"><?php esc_html_e('Details', 'medicalpro'); ?></label>
                            <textarea id="comment" class="form-control" rows="5" name="comment" placeholder="<?php esc_html_e('Describe the reason of appointment', 'medicalpro') ?>"></textarea>
                        </div>
                    </div>	
                    <button class="margin-top-10" id="submit_booking_btn" type="submit"><?php esc_html_e('Submit', 'medicalpro'); ?></button>
                    <div class="row video-consultation-fee" style="display:none;">
                        <div class="col-md-12 margin-top-15">
                            <p class="md-cons-price col-md-12">
                                <span class="currency"><?php echo listingpro_currency_sign(); ?></span><span class="price">0</span><?php esc_html_e('TAX-EXCLUSIVE') ?> &nbsp;&nbsp; <?php esc_html_e('(For Video Consultation)', 'medicalpro'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="booking-success-box" style="display:none;">
        <h3 class="md-booking-con-title margin-bottom-20">
            <span class="active"></span>
            <span class="active"></span>
            <span class="select-bok-step"></span>
        </h3>
        
        <img class="mp-booking-success-img" src="<?php echo MP_PLUGIN_DIR . 'assets/images/booking/booked.svg'; ?>">
        
        <h3><?php esc_html_e('Your appointment booking has been requested', 'medicalpro'); ?></h3>
        <p style="display: none;" class="mp-booking-paid-redirect-notification"><?php esc_html_e('Redirecting To Checkout Please Wait...', 'medicalpro'); ?></p>
        <p><?php esc_html_e('One of our staff will reach you shortly kindly check mailbox for confirmation', 'medicalpro'); ?></p>
        <div class="mp-profile-location-booking-container">
            <a id="book-other-appointment" class="mp-profile-location-book" href="<?php echo esc_url(get_permalink($listing_id)); ?>">
            <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/location/book.svg'; ?>" /> <?php esc_html_e('Book another Appointment', 'medicalpro'); ?></a>
        </div>
    </div>
</div>
