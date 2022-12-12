<?php
add_action('init', 'create_post_type_medicalpro_bookings');
if(!function_exists('create_post_type_medicalpro_bookings')){
    function create_post_type_medicalpro_bookings(){
        $labels = array(
            'name' => _x('Appointments', 'post type general name', 'listingpro-bookings'),
            'singular_name' => _x('Booking', 'post type singular name', 'listingpro-bookings'),
            'menu_name' => _x('Appointments', 'admin menu', 'listingpro-bookings'),
            'name_admin_bar' => _x('Booking', 'add new on admin bar', 'listingpro-bookings'),
            'add_new' => _x('Add New', 'review', 'listingpro-bookings'),
            'add_new_item' => __('Add New Booking', 'listingpro-bookings'),
            'new_item' => __('New Booking', 'listingpro-bookings'),
            'edit_item' => __('Edit Booking', 'listingpro-bookings'),
            'view_item' => __('View Booking', 'listingpro-bookings'),
            'all_items' => __('All Bookings', 'listingpro-bookings'),
            'search_items' => __('Search Bookings', 'listingpro-bookings'),
            'parent_item_colon' => __('Parent Bookings:', 'listingpro-bookings'),
            'not_found' => __('No Bookings found.', 'listingpro-bookings'),
            'not_found_in_trash' => __('No Booking found in Trash.', 'listingpro-bookings')
        );

        $args = array(
            'labels' => $labels,
            'menu_icon' => 'dashicons-media-spreadsheet',
            'description' => __('Description.', 'listingpro-bookings'),
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'medicalpro-bookings'
            ),
            'capabilities' => array(
                'create_posts' => 'do_not_allow',
            ),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => true,
            'menu_position' => 30,
            'supports' => array('title', 'editor', 'thumbnail'),
        );

        register_post_type('medicalpro-bookings', $args);
    }
}


add_action('admin_menu', 'medicalpro_disable_new_booking_posts');
if(!function_exists('medicalpro_disable_new_booking_posts')){
    function medicalpro_disable_new_booking_posts() {
        // Hide sidebar link
        global $submenu;
        unset($submenu['edit.php?post_type=listingpro-bookings'][10]);

        // Hide link on listing page
        if (isset($_GET['post_type']) && $_GET['post_type'] == 'listingpro-bookings') {
            echo '<style type="text/css">
            a.page-title-action { display:none; }
            </style>';
        }
    }
}

add_filter('manage_medicalpro-bookings_posts_columns', 'medicalpro_bookings_columns');
if(!function_exists('medicalpro_bookings_columns')){
    function medicalpro_bookings_columns($columns){
        return array(
            'cb' => '<input type="checkbox" />',
            'title' => esc_html__('Title', 'medicalpro'),
            'hospital' => esc_html__('Hospital', 'medicalpro'),
            'booking_type' => esc_html__('Booking Type', 'medicalpro'),
            'email' => esc_html__('Email', 'medicalpro'),
            'phone' => esc_html__('Phone', 'medicalpro'),
            'Booking-Date' => esc_html__('Booking Date', 'medicalpro'),
            'startTime' => esc_html__('Booking Time', 'medicalpro'),
            'Booking-Status' => esc_html__('Booking Status', 'medicalpro'),
            'date' => esc_html__('Date', 'medicalpro'),
        );
    }
}

add_action('manage_medicalpro-bookings_posts_custom_column', 'medicalpro_booking_columns_content', 10, 2);
if(!function_exists('medicalpro_booking_columns_content')){
    function medicalpro_booking_columns_content($column, $post_id){
        if ($column == 'hospital') {
            $booking_hospital_id = get_post_meta($post_id, 'booking_hospital_id', true);
            $hospital_term = get_term_by('id', $booking_hospital_id, 'medicalpro-hospital');
            echo isset($hospital_term->name) ? $hospital_term->name : '';
        }
        if ($column == 'booking_type') {
            $booking_type = get_post_meta($post_id, 'booking_type', true);
            echo medicalpro_booking_types($booking_type);
        }
        if ($column == 'email') {
            $booking_email = get_post_meta($post_id, 'booking_email', true);
            echo $booking_email;
        }
        if ($column == 'phone') {
            $booking_phone = get_post_meta($post_id, 'booking_phone', true);
            echo $booking_phone;
        }
        if ($column == 'Booking-Date') {
            $booking_booking_date = get_post_meta($post_id, 'booking_date', true);
            if (!empty($booking_booking_date)) {
                echo date_i18n(get_option('date_format'), $booking_booking_date);
            }
        }
        if ($column == 'startTime') {
            $booking_start_time = get_post_meta($post_id, 'booking_slot_start_time', true);
            echo date_i18n(get_option('time_format'), $booking_start_time);
        }
        if ($column == 'Booking-Status') {
            $lp_booking_status = get_post_meta($post_id, 'booking_status', true);
            echo $lp_booking_status;
        }
    }
}

if(!function_exists('medicalpro_create_time_range')){
    function medicalpro_create_time_range($listing_id, $hospital_id, $timeString, $interval , $format, $timeZon_check) {
        
        //new code 1.4
        if(empty($interval)){
            $interval = '30 mins';
        }
        if(empty($format)){
            $format = '12';
        }
        //new code 1.4

        $slots = null;
        $booking_date           = strtotime(date('Y-m-d', $timeString));
        $business_hours         = listing_get_metabox_by_ID('business_hours', $listing_id);
        $listing_hospitals_data = get_post_meta( $listing_id, 'medicalpro_listing_hospitals', true );
        $business_hours         = isset($listing_hospitals_data[$hospital_id]['business_hours']) ? $listing_hospitals_data[$hospital_id]['business_hours'] : array();
        $day                    = strtolower(date_i18n('l', $booking_date));
        
        if (is_array($business_hours)) {
            $business_hours = array_change_key_case($business_hours, CASE_LOWER);
        }

        $times = '';
        if (isset($business_hours[$day])) {
            if (isset($business_hours[$day]['open']) && !empty($business_hours[$day]['open']) && isset($business_hours[$day]['close']) && !empty($business_hours[$day]['close'])) {
                $start = $business_hours[$day]['open'];
                $end = $business_hours[$day]['close'];
            } else {
                $start = '12:00am';
                $end = '11:59pm';
            }

            $startTime        = strtotime($start);
            $endTime          = strtotime($end);
            $returnTimeFormat = ($format == '24') ? 'g:i A' : 'G:i';

            $current = time();
            $addTime = strtotime('+' . $interval, $current);
            $diff = $addTime - $current;

            $intervalEnd = $startTime + $diff;


            $listing_bookings_details = get_post_meta($listing_id, 'listing-booking-details', true);


            $b_args = array(
                'post_type'        => 'medicalpro-bookings',
                'post_status'      => 'publish',
                'posts_per_page'   => -1,
                'meta_query'       => array(
                    'relation'     => 'AND',
                    array(
                        'key'       => 'booking_listing_id',
                        'value'     => $listing_id,
                        'compare'   => '='
                    ),
                    array(
                        'key'       => 'booking_hospital_id',
                        'value'     => $hospital_id,
                        'compare'   => '='
                    ),
                    array(
                        'key'       => 'booking_date',
                        'value'     => $booking_date,
                        'compare'   => '='
                    ),
                    array(
                        'key'       => 'booking_status',
                        'value'     => 'CANCELED',
                        'compare'   => '!='
                    )
                )
            );
            $lp_bookings = new WP_Query($b_args);
            $lp_bookings_start_time_arr = array();
            if ($lp_bookings->have_posts()) :
                while ($lp_bookings->have_posts()) : $lp_bookings->the_post();
                    $bookings_start_time = get_post_meta(get_the_ID(), 'booking_slot_start_time', true);
                    $lp_bookings_start_time_arr[] = $bookings_start_time;
                endwhile;
                wp_reset_postdata();
            endif;

            $lpDisableDate = '';

            $slots .= '<ul class="available-booking-slots medical-booking-time-pill-container">';
            $lp_current_listing_bookings = array();
            if (is_array($listing_bookings_details)) {
                foreach ($listing_bookings_details as $k => $v) {
                    $listing_booking_date = date_i18n('l', (int) $k);
                    $listing_booking_Stime = date_i18n(get_option('time_format'), (int) $v[0]);
                    $listing_booking_Etime = date_i18n(get_option('time_format'), (int) $v[1]);
                    $listing_booking_slot = $listing_booking_Stime . ' - ' . $listing_booking_Etime;

                    $k_arr = explode('-', $k);


                    $lp_current_listing_bookings[$k_arr[1]] = $k_arr[0];
                }
            }
            $timezone = get_option('gmt_offset');
            $time_now = gmdate("H:i", time() + 3600 * ($timezone + date("I")));
            $timeZone_str = strtotime($time_now);

            if ($timeZone_str > $endTime && $timeZon_check == 'yes') {
                $times .= 'Closed Now';
            }

            $available_slots = 0;
            $disableCount = 0;
            while ($startTime <= $endTime) {

                $slot_start_time            = date_i18n(get_option('time_format'), (int) $startTime);
                $slot_end_time              = date_i18n(get_option('time_format'), (int) $intervalEnd);
                $timeString_with_time       = strtotime(date('Y-m-d', $booking_date) . " " . $slot_start_time);
                $slot_start_time_offset = strtotime(date(get_option('time_format'), (int) $startTime));

                if( strtotime(date('Y-m-d', $booking_date)) <= strtotime(date('Y-m-d')) ){
                    $data_string = esc_html__('Today', 'medicalpro');
                }else if( strtotime(date('Y-m-d', medicalpro_add_days_to_data(current_time(get_option('date_format')), '+1 day'))) == medicalpro_add_days_to_data($booking_date) ){
                    $data_string = esc_html__('Tomorrow', 'medicalpro');
                }else{
                    $data_string = date(get_option('date_format'), $booking_date);
                }

                $disable = '';
                if (in_array($timeString_with_time, $lp_bookings_start_time_arr)) {
                    $disable = "lp-booking-disable";
                    $disableCount++;
                }elseif (in_array($slot_start_time_offset, $lp_bookings_start_time_arr)) {
                    $disable = "lp-booking-disable";
                    $disableCount++;
                }

                if ($timeZon_check == 'yes') {
                    if(strtotime($slot_start_time) > $timeZone_str) {
                        $available_slots++;
                        $slots .= '
                        <li class="' . $disable . ' ' . $lpDisableDate . '" data-date_string="'. $data_string .'" data-booking-slot-date="' . $booking_date . '" data-booking-slot-start="' . strtotime($slot_start_time) . '" data-booking-slot-end="' . strtotime($slot_end_time) . '">
                            <p class="medical-booking-time-pill-hover">
                                <span class="lp-booking-time-pill-1">' . $slot_start_time . '</span>
                            </p>
                        </li>';
                    }
                } else {
                    $available_slots++;
                    $slots .= '
                        <li class="' . $disable . ' ' . $lpDisableDate . '" data-date_string="'. $data_string .'" data-booking-slot-date="' . $booking_date . '" data-booking-slot-start="' . strtotime($slot_start_time) . '" data-booking-slot-end="' . strtotime($slot_end_time) . '">
                            <p class="medical-booking-time-pill-hover">
                                <span class="lp-booking-time-pill-1">' . $slot_start_time . '</span>
                            </p>
                        </li>';
                }

                $startTime += $diff;
                $intervalEnd += $diff;
            }
            $available_slots = $available_slots - $disableCount;
            if( $available_slots > 0 ){
                $times .= '<strong class="available-slots">'. $available_slots .' Slots Available</strong><div class="clearfix"></div>';
            }
            $times .= $slots;
            $times .= '</ul>';
        } else {
            $times .= '<strong>' . esc_html__('DAY OFF', 'listingpro-bookings') . '</strong>';
        }

        return $times;
    }
}

if(!function_exists('medicalpro_add_days_to_data')){
    function medicalpro_add_days_to_data( $date = '', $days = ''){
        if( $date == '' ){
            $date = current_time(get_option('date_format'));
        }

        if( is_numeric($date) ){
            $date = date('Y-m-d', $date);
        }else{
            $date = date('Y-m-d', strtotime($date));
        }
        return strtotime(date('Y-m-d', strtotime($date. $days)));
    }
}

add_action('wp_ajax_nopriv_medicalpro_booking_slots', 'medicalpro_booking_slots');
add_action('wp_ajax_medicalpro_booking_slots', 'medicalpro_booking_slots');
if(!function_exists('medicalpro_booking_slots')){
    function medicalpro_booking_slots(){

        $selected_date       = isset($_POST['selected_date'])       ? $_POST['selected_date']        : '';
        $listing_id          = isset($_POST['listing_id'])          ? $_POST['listing_id']           : '';
        $hospital_id         = isset($_POST['hospital_id'])         ? $_POST['hospital_id']          : '';

        if( !is_numeric($selected_date) ){
            $selected_date = strtotime($selected_date);
        }
        $output  = '<label class="margin-bottom-10">'.esc_html("Select Date & Slot", "medicalpro").'</label>';
        $output  .= '<div class="date-slider">';
            $output  .= '<ul class="date-slider-list" data-lid="'. $listing_id .'">';
                if( strtotime(date('Y-m-d', $selected_date)) <= strtotime(current_time('Y-m-d H:i:s')) ){
                    $output  .= '<li class="booking-slider-arrow-left DisableArrow" data-date="'. strtotime(current_time('Y-m-d H:i:s')." -1 day") .'"><i class="fa fa-angle-left" aria-hidden="true"></i></li>';
                    $output  .= '<li class="booking-date active" data-date="'. strtotime(current_time('Y-m-d H:i:s')) .'"><div class="booking-day">'. esc_html__('Today', 'medicalpro') .'</div>'. date_i18n('D,d M', strtotime(current_time('Y-m-d H:i:s'))) .'</li>';
                    $output  .= '<li class="booking-date" data-date="'. strtotime(current_time('Y-m-d H:i:s')." +1 day") .'"><div class="booking-day">'. esc_html__('Tomorrow', 'medicalpro') .'</div>'. date_i18n('D,d M', strtotime(current_time('Y-m-d H:i:s')." +1 day")) .'</li>';
                    $output  .= '<li class="booking-date" data-date="'. strtotime(current_time('Y-m-d H:i:s')." +2 day") .'"><div class="booking-day">'. date_i18n('l', strtotime(current_time('Y-m-d H:i:s')."+2 day")) .'</div>'. date_i18n('d-M-Y', strtotime(current_time('Y-m-d H:i:s')." +2 day")) .'</li>';
                    $output  .= '<li class="booking-slider-arrow-right" data-date="'. strtotime("+1 day") .'"><i class="fa fa-angle-right" aria-hidden="true"></i></li>';
                }else{
                    $output  .= '<li class="booking-slider-arrow-left" data-date="'. medicalpro_add_days_to_data($selected_date, '-1 day') .'"><i class="fa fa-angle-left" aria-hidden="true"></i></li>';

                    $output  .= '<li class="booking-date" data-date="'. medicalpro_add_days_to_data($selected_date, '-1 day') .'">';
                        if( strtotime(date('Y-m-d', strtotime(current_time(get_option('date_format'))))) == medicalpro_add_days_to_data($selected_date, '-1 day') ){
                            $output  .= '<div class="booking-day">'. esc_html__('Today', 'medicalpro') .'</div>';
                        }else if( strtotime(date('Y-m-d', medicalpro_add_days_to_data(current_time(get_option('date_format')), '+1 day'))) == medicalpro_add_days_to_data($selected_date, '-1 day') ){
                            $output  .= '<div class="booking-day">'. esc_html__('Tomorrow', 'medicalpro') .'</div>';
                        }else{
                            $output  .= '<div class="booking-day">'. date_i18n('l', medicalpro_add_days_to_data($selected_date, '-1 day')) .'</div>';
                        }
                        $output  .= date_i18n('D,d M', medicalpro_add_days_to_data($selected_date, '-1 day'));
                    $output  .= '</li>';

                    $output  .= '<li class="booking-date active" data-date="'. $selected_date .'">';
                        if( strtotime(date('Y-m-d', strtotime(current_time(get_option('date_format'))))) == medicalpro_add_days_to_data($selected_date) ){
                            $output  .= '<div class="booking-day">'. esc_html__('Today', 'medicalpro') .'</div>';
                        }else if( strtotime(date('Y-m-d', medicalpro_add_days_to_data(current_time(get_option('date_format')), '+1 day'))) == medicalpro_add_days_to_data($selected_date) ){
                            $output  .= '<div class="booking-day">'. esc_html__('Tomorrow', 'medicalpro') .'</div>';
                        }else{
                            $output  .= '<div class="booking-day">'. date_i18n('l', medicalpro_add_days_to_data($selected_date)) .'</div>';
                        }
                        $output  .= date_i18n('D,d M', medicalpro_add_days_to_data($selected_date));
                    $output  .= '</li>';

                    $output  .= '<li class="booking-date" data-date="'. medicalpro_add_days_to_data($selected_date, '+1 day') .'">';
                        $output  .= '<div class="booking-day">'. date_i18n('l', medicalpro_add_days_to_data($selected_date, '+1 day')) .'</div>';
                        $output  .= date_i18n('d-M-Y', medicalpro_add_days_to_data($selected_date, '+1 day'));
                    $output  .= '</li>';

                    $output  .= '<li class="booking-slider-arrow-right" data-date="'. medicalpro_add_days_to_data($selected_date, '+1 day') .'"><i class="fa fa-angle-right" aria-hidden="true"></i></li>';
                }
            $output  .= '</ul>';
        $output  .= '</div>';


        $output  .= '<div class="medical-booking-slots-outer-wrap">';
    
            $post_author_id = get_post_field( 'post_author', $listing_id );
            $lp_booking_timeslot_duration = get_user_meta($post_author_id, 'lp_booking_timeslot_duration', true);
            
            if (empty($lp_booking_timeslot_duration)) {
                $lp_booking_timeslot_duration = 30;
            }
            if( strtotime(date('Y-m-d', $selected_date)) <= strtotime(date('Y-m-d')) ){
                $output .= medicalpro_create_time_range($listing_id, $hospital_id, $selected_date, $lp_booking_timeslot_duration . " mins", 12, 'yes');
            }else{
                $output .= medicalpro_create_time_range($listing_id, $hospital_id, $selected_date, $lp_booking_timeslot_duration . " mins", 12, 'no');
            }
        $output  .= '</div>';

        echo $output;


        wp_die();
    }
}

add_action('wp_ajax_create_medical_booking', 'create_medical_booking');
add_action('wp_ajax_nopriv_create_medical_booking', 'create_medical_booking');
if(!function_exists('create_medical_booking')){
    function create_medical_booking() {
        global $listingpro_options;
                
        $response           =         array();
        $current_user       =         wp_get_current_user();

        $booking_type       =         isset( $_POST['booking_type'] )       ?     sanitize_text_field($_POST['booking_type'])        : '';
        $listing_id         =         isset( $_POST['listing_id'] )         ?     sanitize_text_field($_POST['listing_id'])          : '';
        $hospital_id        =         isset( $_POST['hospital_id'] )        ?     sanitize_text_field($_POST['hospital_id'])         : '';
        $insurance          =         isset( $_POST['insurance'] )          ?     sanitize_text_field($_POST['insurance'])           : '';
        $booking_date       =         isset( $_POST['booking_date'] )       ?     sanitize_text_field($_POST['booking_date'])        : '';
        $slot_start_time    =         isset( $_POST['slot_start_time'] )    ?     sanitize_text_field($_POST['slot_start_time'])     : '';
        $slot_end_time      =         isset( $_POST['slot_end_time'] )      ?     sanitize_text_field($_POST['slot_end_time'])       : '';
        $fname              =         isset( $_POST['fname'] )              ?     sanitize_text_field($_POST['fname'])               : '';
        $lname              =         isset( $_POST['lname'] )              ?     sanitize_text_field($_POST['lname'])               : '';
        $email              =         isset( $_POST['email'] )              ?     sanitize_text_field($_POST['email'])               : '';
        $phone              =         isset( $_POST['phone'] )              ?     sanitize_text_field($_POST['phone'])               : '';
        $comment            =         isset( $_POST['comment'] )            ?     sanitize_text_field($_POST['comment'])             : '';
        $booking_status     =         "PENDING";
        $user_id            =         $current_user->ID;


        /* ***************  FOR SUCCESSFUL BOOKING EMAIL  *************** */
        $author_id                = get_post_field('post_author', $listing_id);
        $author_mail              = get_the_author_meta('user_email', $author_id);
        $mail_to                  = $email;
        $mail_subject             = esc_html__('New Appointment For','medicalpro') . ' ' . get_the_title($listing_id);
        $mail_msg                 = $fname . ' ' . $lname . ' ' . $phone . ' ' . date(get_option('date_format'), $booking_date) . ' ' . $time_slot_start . ' ' . $comment;

        $response["Author Mail"]  = $author_mail;
        $response["to"]           = $mail_to;
        $response["Subject"]      = $mail_subject;
        $response["Msg"]          = $mail_msg;
        $response['redirectURL']  = '';

        $args = array(
            'post_content'  => $comment,
            'post_status'   => 'publish',
            'post_title'    => $fname . ' ' . $lname . ' ' . '(' . get_the_title($listing_id) . ')',
            'post_type'     => 'medicalpro-bookings',
            'post_author'   => $user_id
        );
        $medicalpro_booking_id = wp_insert_post($args);

        if (!is_wp_error($medicalpro_booking_id)) {
            
            if( $booking_type == 'video-consultation' ){
                
                $currency               = lp_theme_option('currency_paid_submission');
                $listing_hospitals_data = get_post_meta($listing_id, 'medicalpro_listing_hospitals', true );
                $price =  isset($listing_hospitals_data[$hospital_id]['price']) ? $listing_hospitals_data[$hospital_id]['price'] : 0;
                update_post_meta($medicalpro_booking_id, 'booking_video_consult_fee', $price);
                update_post_meta($medicalpro_booking_id, 'booking_currency', $currency);
                update_post_meta($medicalpro_booking_id, 'booking_payment_status', 'pending');
                
                if( $price > 0 ){
                    $response['redirectURL'] = add_query_arg(array('booking_id' => $medicalpro_booking_id, 'user_id' => $user_id), get_permalink($listingpro_options['payment-checkout']));
                }
            }
            
            update_post_meta($medicalpro_booking_id, 'booking_user_id', $user_id);
            update_post_meta($medicalpro_booking_id, 'booking_type', $booking_type);
            update_post_meta($medicalpro_booking_id, 'booking_hospital_id', $hospital_id);
            update_post_meta($medicalpro_booking_id, 'booking_insurane_id', $insurance);
            update_post_meta($medicalpro_booking_id, 'booking_slot_start_time', $slot_start_time);
            update_post_meta($medicalpro_booking_id, 'booking_slot_end_time', $slot_end_time);
            update_post_meta($medicalpro_booking_id, 'booking_fname', $fname);
            update_post_meta($medicalpro_booking_id, 'booking_lname', $lname);
            update_post_meta($medicalpro_booking_id, 'booking_email', $email);
            update_post_meta($medicalpro_booking_id, 'booking_phone', $phone);
            update_post_meta($medicalpro_booking_id, 'booking_date', $booking_date);
            update_post_meta($medicalpro_booking_id, 'booking_status', $booking_status);
            update_post_meta($medicalpro_booking_id, 'booking_listing_author', $author_id);
            update_post_meta($medicalpro_booking_id, 'booking_listing_id', $listing_id);

            $user_bookings_list = get_user_meta($user_id, 'lp_user_booking_ids_list', true);
            $user_bookings_list = $user_bookings_list == '' ? array() : $user_bookings_list;
            $user_bookings_list_arr = array($medicalpro_booking_id);
            $user_bookings_list[$medicalpro_booking_id] = $user_bookings_list_arr;
            update_user_meta($user_id, 'lp_user_booking_ids_list', $user_bookings_list);
            $lid = $listing_id;
            $current_listing_bookings = get_post_meta($lid, 'listing-booking-details', true);
            $current_listing_bookings = $current_listing_bookings == '' ? array() : $current_listing_bookings;
            $booking_detail_arr = array(strtotime(date('H:i', $slot_start_time) . ' ' . date('Y-m-d', $booking_date)), strtotime(date('H:i', $slot_end_time) . ' ' . date('Y-m-d', $booking_date)), $booking_status);
            $current_listing_bookings[date('H:i', $slot_start_time) .'-'. date('H:i', $slot_end_time) . '-' . $medicalpro_booking_id] = $booking_detail_arr;
            update_post_meta($lid, 'listing-booking-details', $current_listing_bookings);

            medicalpro_sent_booking_email($medicalpro_booking_id, 'create', 'booker');
            medicalpro_sent_booking_email($medicalpro_booking_id, 'create', 'listing_author');

            $response['status'] = 'success';

            if (!isset($listingpro_options['send_booking_gcal']) || $listingpro_options['send_booking_gcal']) {
                $booking_user_id        =   get_userdata(get_post_meta($medicalpro_booking_id, 'booking_user_id', true));
                $eventStart = date('d-m-Y H:i', $slot_start_time);
                $eventEnd = date('d-m-Y H:i', $slot_end_time);
                $hospitalMeta = get_term_meta($hospital_id, 'address', true);
                $hospital_info = get_term_by('id', $hospital_id, 'medicalpro-hospital');
                $args = array(
                    'from_name' => get_the_title($listing_id),
                    'to_name' => $booking_user_id->display_name,
                    'from_email' => $listingpro_options['listingpro_general_email_address'],
                    'to_email' => $booking_user_id->user_email,
                    'start_time' => $eventStart,
                    'end_time' => $eventEnd,
                    'event_title' => get_the_title($listing_id),
                    'location' => $hospital_info->name . ' ' . $hospitalMeta
                );
                mp_event_on_google_calendar($args);
            }
        } else {
            $response['status'] = 'error';
            $response['msg']    = esc_html__('Something wend wrong. Please try again.');
        }
        wp_send_json($response);

    }
}

if(!function_exists('medicalpro_sent_booking_email')) {
    function medicalpro_sent_booking_email( $booking_id, $booking_action, $mail_for ){
        global $listingpro_options;
        
        $listing_id             =   get_post_meta($booking_id, 'booking_listing_id', true);
        $booking_user_id        =   get_post_meta($booking_id, 'booking_user_id', true);
        $booking_listing_user   =   get_post_meta($booking_id, 'booking_listing_author', true);
        
        $booking_hospital_id    =   get_post_meta($booking_id, 'booking_hospital_id', true);
        $hospital_term          =   get_term_by('id', $booking_hospital_id, 'medicalpro-hospital');
        
        $booking_date           =   date_i18n(get_option('date_format'), get_post_meta($booking_id, 'booking_date', true) );
        $booking_start          =   date_i18n(get_option('time_format'), get_post_meta($booking_id, 'booking_slot_start_time', true) );
        $booking_ends           =   date_i18n(get_option('time_format'), get_post_meta($booking_id, 'booking_slot_end_time', true) );
        $booking_status         =   get_post_meta($booking_id, 'booking_status', true);
        
        
        $website_url            = site_url();
        $website_name           = get_option('blogname');
        $current_user           = wp_get_current_user();
        $user_name              = isset($current_user->display_name) ? $current_user->display_name: '';
        
        $listing_user_data      = get_userdata($booking_listing_user);
        $booking_user_data      = get_userdata($booking_user_id);
        
        if( $booking_action == 'create' && $mail_for == 'booker' ){
            $mail_subject = $listingpro_options['mp_submit_booking_subject'];
            $mail_content = $listingpro_options['mp_submit_booking_content'];
            $email        = isset($booking_user_data->user_email) ? $booking_user_data->user_email: '';
        }
        if( $booking_action == 'create' && $mail_for == 'listing_author' ){
            $mail_subject = $listingpro_options['mp_submit_booing_subject_author'];
            $mail_content = $listingpro_options['mp_submit_booking_content_author'];
            $userdata     = get_userdata($booking_listing_user);
            $email        = isset($listing_user_data->user_email) ? $listing_user_data->user_email: '';
        }
        if( $booking_action == 'approved' && $mail_for == 'booker' ){
            $mail_subject = $listingpro_options['mp_approved_booking_subject'];
            $mail_content = $listingpro_options['mp_approved_booking_content'];
            $userdata     = get_userdata($booking_user_id);
            $email        = isset($booking_user_data->user_email) ? $booking_user_data->user_email: '';
        }
        if( $booking_action == 'cancelled' && $mail_for == 'booker' ){
            $mail_subject = $listingpro_options['mp_cancelled_booking_subject'];
            $mail_content = $listingpro_options['mp_cancelled_booking_content'];
            $userdata     = get_userdata($booking_user_id);
            $email        = isset($booking_user_data->user_email) ? $booking_user_data->user_email: '';
        }
        
        if( isset($email) && !empty($email)){
            
            $formated_mail_subject = lp_sprintf2($mail_subject, array(
                'website_url'  => $website_url,
                'website_name' => $website_name,
                'user_name'    => $user_name,
            ));

            $formated_mail_content = lp_sprintf2($mail_content, 
                array(
                    'website_url'          => $website_url,
                    'website_name'         => $website_name,
                    'user_name'            => $user_name,
                    'listing_title'        => esc_html(get_the_title($listing_id)),
                    'listing_url'          => esc_url(get_permalink($listing_id)),
                    'hospital_name'        => isset($hospital_term->name)    ? esc_html($hospital_term->name) : '',
                    'hospital_url'         => isset($hospital_term->term_id) ? esc_url(get_term_link($hospital_term->term_id)) : '',
                    'appointment_date'     => $booking_date,
                    'appointment_time'     => $booking_start,
                    'appointment_status'   => $booking_status,
                    'listing_author_name'  => isset($listing_user_data->display_name) ? $listing_user_data->display_name: '',
                    'listing_author_url'   => isset($listing_user_data->ID) ? get_author_posts_url($listing_user_data->ID): '',
                    'booker_name'          => isset($booking_user_data->display_name) ? $booking_user_data->display_name: '',
                    'booker_url'           => isset($booking_user_data->ID) ? get_author_posts_url($booking_user_data->ID): '',
                    'approved_date'        => current_time(get_option('date_format')),
                    'cancelled_date'       => current_time(get_option('date_format')),
                )
            );
            
            lp_mail_headers_append();
                $headers1[] = 'Content-Type: text/html; charset=UTF-8';
                LP_send_mail($email, $formated_mail_subject, $formated_mail_content, $headers1);
            lp_mail_headers_remove();
        }
    }
}


if(!function_exists('medicalpro_booking_get_email_content')) {
    function medicalpro_booking_get_email_content( $booking_id, $booking_action, $mail_for ) {
        ob_start();
        
        $listing_id         =   get_post_meta($booking_id, 'booking_listing_id', true);
        $listing_title      =   get_the_title($listing_id);
        $listing_url        =   get_permalink($listing_id);

        $booking_date       =   date_i18n(get_option('date_format'), get_post_meta($booking_id, 'booking_date', true) );
        $booking_start      =   date_i18n(get_option('time_format'), get_post_meta($booking_id, 'booking_slot_start_time', true) );
        $booking_ends       =   date_i18n(get_option('time_format'), get_post_meta($booking_id, 'booking_slot_end_time', true) );
        $booking_status     =   get_post_meta($booking_id, 'booking_status', true);
        
        $booking_hospital_id = get_post_meta($booking_id, 'booking_hospital_id', true);
        $hospital_term = get_term_by('id', $booking_hospital_id, 'medicalpro-hospital');

        if($booking_action == 'create' && $mail_for == 'booker'){ ?>
            <p><strong><?php echo esc_html__('You have created a new Appointment','medicalpro'); ?></strong></p>
            <?php
        }
        if($booking_action == 'approved' && $mail_for == 'booker') { ?>
            <p><strong><?php echo esc_html__('Your Appointment has been approved','medicalpro'); ?></strong></p>
            <?php
        }
        if($booking_action == 'canceled' && $mail_for == 'booker') { ?>
            <p><strong><?php echo esc_html__('Your Appointment has been canceled','medicalpro'); ?></strong></p>
            <?php
        }
        if($booking_action == 'create' && $mail_for == 'listing_author') { ?>
            <p><strong><?php echo esc_html__('You have received a new Appointment','medicalpro'); ?></strong></p>
            <?php
        }
        ?>
        <h3><?php echo esc_html__('Appointment Details','medicalpro'); ?></h3>
        <p><strong><?php echo esc_html__('Doctor Name:','medicalpro'); ?></strong> <a href="<?php echo $listing_url; ?>"><?php echo $listing_title; ?></a></p>
        <?php if(isset($hospital_term) && !empty($hospital_term)){ ?>
            <p><strong><?php echo esc_html__('Hospital Name:','medicalpro'); ?></strong> <a href="<?php echo get_term_link($hospital_term->term_id); ?>"><?php echo $hospital_term->name; ?></a></p>
        <?php } ?>
        <p><strong><?php echo esc_html__('Appointment Date:','medicalpro'); ?></strong> <?php echo $booking_date; ?></p>
        <p><strong><?php echo esc_html__('Appointment Time:','medicalpro'); ?></strong> <?php echo $booking_start; ?></p>
        <p><strong><?php echo esc_html__('Appointment Status:','medicalpro'); ?></strong> <?php echo $booking_status ?></p>
        <?php if($booking_action == 'create' && $mail_for == 'booker') { ?>
            <p><?php echo esc_html__('you will be notified if your Appointment is APPROVED or CANCELED.','medicalpro'); ?></p>
            <?php
        }
        return ob_get_clean();
    }
}

add_action('wp_ajax_medicalpro_get_booking_details', 'medicalpro_get_booking_details');
add_action('wp_ajax_nopriv_medicalpro_get_booking_details', 'medicalpro_get_booking_details');
function medicalpro_get_booking_details() {
    $markup = '';
    if (isset($_REQUEST)) {
        $cbid = sanitize_text_field($_REQUEST['cbid']);
        $mybookings = sanitize_text_field($_REQUEST['mybookings']);
        
        ob_start();
            set_query_var('c_booking_id', $cbid);
            set_query_var('my_bookings', $mybookings);
            mp_get_template_part('templates/dashboard/booking-detail');
        $contents = ob_get_contents();
        ob_end_clean();
        $markup = $contents;
    }
    echo $markup;
    wp_die();
}

add_action('wp_ajax_medicalpro_update_booking_status', 'medicalpro_update_booking_status');
add_action('wp_ajax_nopriv_medicalpro_update_booking_status', 'medicalpro_update_booking_status');
function medicalpro_update_booking_status(){
    if (isset($_REQUEST)) {
        
        $cBstatus = sanitize_text_field($_REQUEST['cBstatus']);
        $cbid     = sanitize_text_field($_REQUEST['cbid']);

        update_post_meta($cbid, 'booking_status', $cBstatus);
        
        $subject    =   esc_html__('Appointment Approved','listingpro-bookings');
        $booking_a  =   'approved';
        if($cBstatus == 'CANCELED') {
            $subject    =   esc_html__('Appointment Canceled','listingpro-bookings');
            $booking_a  =   'cancelled';
        }
        medicalpro_sent_booking_email($cbid, $booking_a, 'booker');
        $cb_updated_status = get_post_meta($cbid, 'booking_status', true);
    }
    echo $cb_updated_status . ' &nbsp;&nbsp;<span class="caret"></span>';
    wp_die();
}

add_action('wp_ajax_medicalpro_calendar_bookings_listing', 'medicalpro_calendar_bookings_listing');
add_action('wp_ajax_nopriv_medicalpro_calendar_bookings_listing', 'medicalpro_calendar_bookings_listing');
function medicalpro_calendar_bookings_listing(){
    if (isset($_REQUEST)) {
        
        $lastDay = sanitize_text_field($_REQUEST['lastDay']);
        $firstDay = sanitize_text_field($_REQUEST['firstDay']);
        $bookings_type = sanitize_text_field($_REQUEST['bookings_type']);

        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        
        if( $bookings_type == 'my-bookings' ){
            $b_args = array(
                'post_type'      => 'medicalpro-bookings',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'author'   => $user_id,
                'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'booking_status',
                        'value'   => 'APPROVED',
                        'compare' => '='
                    ),
                    array(
                        'key'     => 'booking_date',
                        'value'   => array(strtotime($firstDay), strtotime($lastDay)),
                        'compare' => 'BETWEEN',
                        'type'    => 'numeric'
                    )
                )
            );
        }else{
            $b_args = array(
                'post_type'      => 'medicalpro-bookings',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'booking_listing_author',
                        'value'   => $user_id,
                        'compare' => '='
                    ),
                    array(
                        'key'     => 'booking_status',
                        'value'   => 'APPROVED',
                        'compare' => '='
                    ),
                    array(
                        'key'     => 'booking_date',
                        'value'   => array(strtotime($firstDay), strtotime($lastDay)),
                        'compare' => 'BETWEEN',
                        'type'    => 'numeric'
                    )
                )
            );
        }
        
        $current_disable_booking = get_option('lp_booking_settings');
        
        $lp_bookings = new WP_Query($b_args);
        $lp_bookings_arr = array();
        if ($lp_bookings->have_posts()) : while ($lp_bookings->have_posts()) : $lp_bookings->the_post();
            $booking_start_time = get_post_meta(get_the_ID(), 'booking_slot_start_time', true);
            $lp_bookings_arr[get_the_ID()] = get_the_ID();
        endwhile;
            wp_reset_postdata(); endif;
        ksort($lp_bookings_arr);

        $timezone       =   get_option('gmt_offset');
        $time_now       =   gmdate("H:i", time() + 3600*($timezone+date("I")));
        $timeZone_str   =   strtotime($time_now);

        
        if (is_array($lp_bookings_arr) && count($lp_bookings_arr) > 0) {    //updated by Abbas
            foreach ($lp_bookings_arr as $k => $v) {

                $listing_id = get_post_meta($v, 'booking_listing_id', true);


                $gAddress = get_post_meta($listing_id, 'lp_listingpro_options', true);
                $listing_addr = $gAddress['gAddress'];

                $listing_title = get_the_title($listing_id);

                $booking_id = $v;

                $booker_id = get_post_field('post_author', $booking_id);
                $booker_data = get_user_by('ID', $booker_id);
                $booker_name = $booker_data->user_login;

                $booking_phone = get_post_meta($booking_id, 'booking_phone', true);
                $booking_msg = get_post_field('post_content', $booking_id);

                $booking_date = date('F j, Y', (int)get_post_meta($booking_id, 'booking_date', true));
                $booking_start = date_i18n(get_option('time_format'), (int)get_post_meta($booking_id, 'booking_slot_start_time', true));
                $booking_end = date_i18n(get_option('time_format'), (int)get_post_meta($booking_id, 'booking_slot_end_time', true));

                $current_month_number = date('m');
                $booking_date_str = strtotime($booking_date);

                $nextMonthFirstDate = date_create($lastDay . 'first day of next month')->format('1 F Y');
                $nextMonthLastDate = date_create($lastDay . 'first day of next month')->format('t F Y');

                $PrevMonthLastDate = date_create($lastDay . 'first day of last month')->format('t F Y');
                $PrevMonthStartDate = date_create($firstDay . 'last day of last month')->format('1 F Y');


                $current_month_start_date = strtotime($firstDay);
                $current_month_end_date = strtotime($lastDay);
                
                $_booking_date   = date('Y-m-d', get_post_meta($booking_id, 'booking_date', true));
                $_slot_end_time  = date('H:i', get_post_meta($booking_id, 'booking_slot_end_time', true));
                $booking_full_date = $_booking_date .' '. $_slot_end_time;
                $current_titme     = current_time('Y-m-d') .' '. $time_now;
                
                if( strtotime($booking_full_date) >= strtotime($current_titme) ){
                    if ($booking_date_str >= $current_month_start_date && $booking_date_str <= $current_month_end_date) {
                        $booking_data_by_date[$booking_date][$booking_id] = array('Booker Name' => $booker_name, 'Prev Last' => $PrevMonthLastDate, 'Prev Start' => $PrevMonthStartDate, 'Next Start' => $nextMonthFirstDate, 'Next Last' => $nextMonthLastDate, 'addr' => $listing_addr, 'Listing Title' => $listing_title, 'Booking Date' => $booking_date, 'Month Start Date' => $current_month_start_date, 'Current Month' => $current_month_number, 'Month End Date' => $current_month_end_date, 'Start Time' => $booking_start, 'End Time' => $booking_end, 'Booking Status' => $booking_status, 'Booking Phone' => $booking_phone, 'Booking Message' => $booking_msg);
                    }
                }
                
            }
        }
    }

    $nextMonthFirstDate = date_create($lastDay . 'first day of next month')->format('1 F Y');
    $nextMonthLastDate = date_create($lastDay . 'first day of next month')->format('t F Y');

    $PrevMonthLastDate = date_create($lastDay . 'first day of last month')->format('t F Y');
    $PrevMonthStartDate = date_create($firstDay . 'last day of last month')->format('1 F Y');


    $booking_data_by_date['lp_booking_settings'] = $current_disable_booking;
    $booking_data_by_date['booking_arry'] = $lp_bookings_arr;
    $booking_data_by_date['next_first'] = $nextMonthFirstDate;
    $booking_data_by_date['next_last'] = $nextMonthLastDate;
    $booking_data_by_date['prev_first'] = $PrevMonthStartDate;
    $booking_data_by_date['prev_last'] = $PrevMonthLastDate;
    die(json_encode($booking_data_by_date));

}