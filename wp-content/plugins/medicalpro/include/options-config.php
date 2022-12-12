<?php
$autoUpdate = get_option('auto_update_redux_google_fonts');
if (!isset($autoUpdate) || !$autoUpdate) update_option('auto_update_redux_google_fonts', true);

add_filter('redux/options/listingpro_options/sections', 'medicalpro_dynamic_section_callback');
function medicalpro_dynamic_section_callback($sections)
{
    $new_sections = array();

    foreach ($sections as $section_id => $section) {
        if (isset($section['id']) && ($section['id'] == 'listing_submit_edit_locations' || $section['id'] == 'listing_nearby_loc')) {
            unset($sections[$section_id]);
        }
    }

    foreach ($sections as $section_id => $section) {
        if (isset($section['id']) && $section['id'] == 'Header') {
            foreach ($section['fields'] as $field_id => $field) {
                //by Abbas 4 july 2022
                if (isset($field['id']) && $field['id'] == 'header_views') {
                    $section['fields'][$field_id]['options'] = array();
                    $section['fields'][$field_id]['options']['mp_header_style1'] = array(
                        'alt' => 'MP Header Style 1',
                        'img' => MP_PLUGIN_DIR . '/assets/images/admin/mp-header-style1.png'
                    );
                    $section['fields'][$field_id]['desc'] = esc_html__("Select from 1 of 1 Header Style", 'listingpro');
                    $section['fields'][$field_id]['default'] = esc_html__("header_view", 'listingpro');
                }

                if (isset($field['id']) && $field['id'] == 'header_bgcolor') {
                    $section['fields'][$field_id]['subtitle'] = esc_html__("", 'listingpro');
                }

                if (isset($field['id']) && $field['id'] == 'header_bgcolor_inner_pages') {
                    $section['fields'][$field_id]['subtitle'] = esc_html__("", 'listingpro');
                }

                if (isset($field['id']) && $field['id'] == 'header_textcolor') {
                    $section['fields'][$field_id]['subtitle'] = esc_html__("", 'listingpro');
                }

                if (isset($field['id']) && $field['id'] == 'page_header') {
                    $section['fields'][$field_id]['default'] = array('url' => MP_PLUGIN_DIR . 'assets/images/Group-772.png');
                }

                //End by Abbas 4 july 2022
            }
        }

        if (isset($section['id']) && $section['id'] == 'dashboard-settings') {
            foreach ($section['fields'] as $field_id => $field) {
                if (isset($field['id']) && $field['id'] == 'booking_dashoard') {
                    $section['fields'][] = array(
                        'id' => 'earnings_dashoard',
                        'type' => 'switch',
                        'title' => __('Earnings', 'medicalpro'),
                        'desc' => __('Enable to show Earnings within the dashboard. (Available only for business listing owners)', 'medicalpro'),
                        'default' => true,
                    );
                }
            }
        }


        // die();


        if (isset($section['id']) && $section['id'] == 'search_settings') {
            foreach ($section['fields'] as $field_id => $field) {

                if (isset($field['id']) && $field['id'] == 'top_banner_styles') {
                    $section['fields'][$field_id]['options'] = array();
                    $section['fields'][$field_id]['options']['mp_banner_side_search_view1'] = array(
                        'alt' => 'Banner with side-search Style 1',
                        'img' => MP_PLUGIN_DIR . '/assets/images/admin/mp-banner-style1.jpg'
                    );
                    $section['fields'][$field_id]['desc'] = esc_html__("Select from 1 of 1 Banner Style", 'listingpro');
                }

                if (
                    isset($field['id']) && $field['id'] == 'banner_opacity' ||
                    isset($field['id']) && $field['id'] == 'courtesy_switcher' ||
                    isset($field['id']) && $field['id'] == 'lp_video_banner_on'
                ) {
                    $section['fields'][$field_id]['required'] = array(
                        '0' => 'top_banner_styles',
                        '1' => '!=',
                        '2' => 'mp_banner_side_search_view1'
                    );
                }
                if (isset($field['id']) && $field['id'] == 'banner_height2') {
                    $section['fields'][] = array(
                        'id' => 'home_banner_search_position',
                        'type' => 'select',
                        'title' => __('Home Banner Search Position', 'medicalpro'),
                        'subtitle' => __('Select Your Home Page Search Position.', 'medicalpro'),
                        'options' => array(
                            'right' => esc_html__('Right', 'medicalpro'),
                            'bottom' => esc_html__('Bottom', 'medicalpro')
                        ),
                        'required' => array(
                            array('top_banner_styles', '=', 'mp_banner_side_search_view1')
                        ),
                        'default' => 'right',
                    );
                    $section['fields'][] = array(
                        'id' => 'home_banner_forground',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Home Banner Forground Image', 'medicalpro'),
                        'compiler' => 'true',
                        'subtitle' => __('Upload image for homepage banner forground', 'medicalpro'),
                        'required' => array(
                            array('top_banner_styles', '=', 'mp_banner_side_search_view1')
                        ),
                        'default' => array('url' => ''),
                    );
                    $section['fields'][] = array(
                        'id' => 'home_banner_post-count',
                        'type' => 'text',
                        'title' => __('Home Banner Available Doctor Text', 'medicalpro'),
                        'desc' => __('Use %s For Doctors Count', 'medicalpro'),
                        'default' => __('%s+ Doctors are available just for you.', 'medicalpro'),
                        'required' => array(
                            array('top_banner_styles', '=', 'mp_banner_side_search_view1')
                        ),
                    );
                    $section['fields'][] = array(
                        'id' => 'home_banner_tagline',
                        'type' => 'text',
                        'title' => __('Home Banner Achievement Text', 'medicalpro'),
                        'default' => __('# 1 Best Rated Medical Directory in USA and Canada.', 'medicalpro'),
                        'required' => array(
                            array('top_banner_styles', '=', 'mp_banner_side_search_view1')
                        ),
                    );
                }

                if (isset($field['id']) && $field['id'] == 'top_main_title') {
                    $section['fields'][$field_id]['title'] = esc_html__("Banner Main Text", "medicalpro");
                    $section['fields'][$field_id]['default'] = 'Find the right <span class="lp-dyn-city">doctor</span>';
                }

                if (isset($field['id']) && $field['id'] == 'top_title') {
                    $section['fields'][$field_id]['required'] = array(
                        '0' => 'home_banner_search_position',
                        '1' => '!=',
                        '2' => 'bottom'
                    );
                }

                //by Abbas 4 july 2022
                if (isset($field['id']) && $field['id'] == 'search_placeholder') {
                    $section['fields'][$field_id]['default'] = esc_html__("Ex: doctors, conditions, or", 'listingpro');
                }

                if (isset($field['id']) && $field['id'] == 'location_default_text') {
                    $section['fields'][$field_id]['default'] = esc_html__("City.", 'listingpro');
                }

                if (isset($field['id']) && $field['id'] == 'main_text') {
                    $section['fields'][$field_id]['default'] = esc_html__("The Largest online database of patient reviews for doctors, facilities and online Appointment", 'listingpro');
                }

                //End by Abbas 4 july 2022

            }
        }

        if (isset($section['id']) && $section['id'] == 'search-filter-options') {
            $section['fields'][] = array(
                'id' => 'enable_hospital_filter',
                'type' => 'switch',
                'title' => esc_html__('Hospital Filter', 'medicalpro'),
                'desc' => '',
                'subtitle' => '',
                'default' => 1,
                'on' => esc_html__('Enabled', 'medicalpro'),
                'off' => esc_html__('Disabled', 'medicalpro'),
            );
            $section['fields'][] = array(
                'id' => 'enable_date_filter',
                'type' => 'switch',
                'title' => esc_html__('Date Filter', 'medicalpro'),
                'desc' => '',
                'subtitle' => '',
                'default' => 1,
                'on' => esc_html__('Enabled', 'medicalpro'),
                'off' => esc_html__('Disabled', 'medicalpro'),
            );
            $section['fields'][] = array(
                'id' => 'enable_mpe_feature_filter',
                'type' => 'switch',
                'title' => esc_html__('Listing Features Filter', 'medicalpro'),
                'desc' => '',
                'subtitle' => '',
                'default' => 1,
                'on' => esc_html__('Enabled', 'medicalpro'),
                'off' => esc_html__('Disabled', 'medicalpro'),
            );
        }

        if (isset($section['id']) && $section['id'] == 'lp-detail-page-manager') {
            $section['fields'] = array(
                array(
                    'id' => 'lp_detail_page_styles',
                    'type' => 'select',
                    'title' => __('Select listing detail page Style', 'medicalpro'),
                    'desc' => '',
                    'options' => array(
                        'lp_detail_page_styles4' => 'Listing Detail Page Style 1',
                    ),
                    'default' => 'lp_detail_page_styles4',
                    'required' => array('lp_detail_page_styles', 'equals', 'lp_detail_page_styles4'),
                ),
                array(
                    'id' => 'lp-detail-page-layout-content',
                    'type' => 'sorter',
                    'title' => 'Content Layout',
                    //                    'required' => array('lp_detail_page_styles', 'equals', 'lp_detail_page_styles4'),
                    'desc' => 'Shuffle elements within Listing Detail Content',
                    'compiler' => 'true',
                    'options' => array(
                        'general' => array(
                            'mp_announcements_section' =>  esc_html__('Announcements', 'medicalpro'),
                            'mp_hospitals_section'     =>  esc_html__('Hospitals', 'medicalpro'),
                            'mp_features_section'      =>  esc_html__('Listing Features', 'medicalpro'),
                            'mp_insurances_section'    =>  esc_html__('Insurances', 'medicalpro'),
                            'mp_additional_section'    =>  esc_html__('Additional Details', 'medicalpro'),
                            'mp_awards_section'        =>  esc_html__('Awards', 'medicalpro'),
                            'mp_faqs_section'          =>  esc_html__('FAQs', 'medicalpro'),
                            'mp_reviews_section'       =>  esc_html__('Reviews', 'medicalpro'),
                            'mp_reviewform_section'    =>  esc_html__('Review Form', 'medicalpro'),
                            'mp_leadform'              =>  esc_html__('Lead Form', 'medicalpro'),
                        ),
                        'disabled' => array(
                            ''  => '',
                        ),
                    ),
                ),
                array(
                    'id' => 'lp-detail-page-layout-sidebar',
                    'type' => 'sorter',
                    'title' => 'Sidebar Layout',
                    //                    'required' => array('lp_detail_page_styles', 'equals', 'lp_detail_page_styles4'),
                    'desc' => 'Shuffle elements within Listing SideBar',
                    'compiler' => 'true',
                    'options' => array(
                        'sidebar' => array(
                            'mp_booking_section'    =>  esc_html__('Booking Form', 'medicalpro'),
                        ),
                        'disabled' => array(
                            '' => '',
                        ),
                    ),
                ),
                array(
                    'id' => 'booking_for_certified_docs',
                    'type' => 'switch',
                    'title' => __('Booking For Certified Doctors', 'medicalpro'),
                    'subtitle' => __('Show Booking Only With Certified Doctors', 'medicalpro'),
                    'desc' => __('If The Doctor Is Certified Only Then Show Booking Else Show Leadform', 'medicalpro'),
                    'default' => 0,
                ),
            );
        }

        if (isset($section['id']) && $section['id'] == 'footer_section_information') {
            foreach ($section['fields'] as $field_id => $field) {
                if (isset($field['id']) && $field['id'] == 'footer_style') {
                    $section['fields'][$field_id]['options'] = array(); //by abbas 
                    $section['fields'][$field_id]['options']['mp_footer1'] = array(
                        'alt' => 'mp footer 1',
                        'img' => MP_PLUGIN_DIR . '/assets/images/admin/mp-footer-style1.png'
                    );
                    $section['fields'][$field_id]['desc'] = esc_html__("Select from 1 of 1 Footer Style", 'listingpro'); //by abbas 
                }
            }
        }

        if (isset($section['id']) && $section['id'] == 'listing_view') {
            foreach ($section['fields'] as $field_id => $field) {
                if (isset($field['id']) && $field['id'] == 'listing_style') {
                    $section['fields'][$field_id]['options'] = array();
                    $section['fields'][$field_id]['options']['5'] = array(
                        'alt' => 'Listing with sidebar filters',
                        'img' => MP_PLUGIN_DIR . '/assets/images/themeoptionarchive.jpg'
                    );
                }
            }
        }
        if (isset($section['id']) && $section['id'] == 'listing_view') {
            foreach ($section['fields'] as $field_id => $field) {
                if (isset($field['id']) && $field['id'] == 'listing_views') {
                    $section['fields'][$field_id]['options'] = array();
                    $section['fields'][$field_id]['options']['grid_view'] = array(
                        'alt' => 'Listing detail layout',
                        'img' => MP_PLUGIN_DIR . '/assets/images/themeoptionlist.jpg'
                    );
                }
            }
        }
        if (isset($section['id']) && $section['id'] == 'author_listings') {
            foreach ($section['fields'] as $field_id => $field) {
                if (isset($field['id']) && $field['id'] == 'my_listing_views') {
                    $section['fields'][$field_id]['options'] = array();
                    $section['fields'][$field_id]['options']['grid_view'] = array(
                        'alt' => 'Listing detail layout',
                        'img' => MP_PLUGIN_DIR . '/assets/images/themeoptionlist.jpg'
                    );
                }
            }
        }
        if (isset($section['id']) && $section['id'] == 'listing_submit_settings') {
            foreach ($section['fields'] as $field_id => $field) {
                if (isset($field['id']) && $field['id'] == 'listing_submit_page_style') {
                    $section['fields'][$field_id]['options'] = array();
                    $section['fields'][$field_id]['options']['style2'] = 'Page Style 2';
                }
            }
        }

        if (isset($section['id']) && $section['id'] == 'lp_pricing_plans') {
            $new_sections[] = array(
                'title' => __('Hospital Archive', 'medicalpro'),
                'id' => 'hospital_general',
                'customizer_width' => '400px',
                'icon' => 'el el-list-alt',
                'fields' => array(
                    array(
                        'id' => 'enable_doctors',
                        'type' => 'switch',
                        'title' => esc_html__('Doctors', 'medicalpro'),
                        'desc' => 'Enable to show which doctors are connected to the hospital.',
                        'subtitle' => '',
                        'default' => 1,
                        'on' => esc_html__('Enabled', 'medicalpro'),
                        'off' => esc_html__('Disabled', 'medicalpro'),
                    ),
                    array(
                        'id' => 'doctors_filters',
                        'type' => 'switch',
                        'title' => esc_html__('Doctors Filter', 'medicalpro'),
                        'desc' => '',
                        'subtitle' => '',
                        'default' => 1,
                        'required' => array('enable_doctors', 'equals', '1'),
                        'on' => esc_html__('Enabled', 'medicalpro'),
                        'off' => esc_html__('Disabled', 'medicalpro'),
                    ),
                    array(
                        'id' => 'alphabetical_filters',
                        'type' => 'switch',
                        'title' => esc_html__('Alphabetical', 'medicalpro'),
                        'desc' => '',
                        'subtitle' => '',
                        'required' => array('doctors_filters', 'equals', '1'),
                        'default' => 1,
                        'on' => esc_html__('Enabled', 'medicalpro'),
                        'off' => esc_html__('Disabled', 'medicalpro'),
                    ),
                    array(
                        'id' => 'categories_filters',
                        'type' => 'switch',
                        'title' => esc_html__('Categories', 'medicalpro'),
                        'desc' => '',
                        'subtitle' => '',
                        'required' => array('doctors_filters', 'equals', '1'),
                        'default' => 1,
                        'on' => esc_html__('Enabled', 'medicalpro'),
                        'off' => esc_html__('Disabled', 'medicalpro'),
                    ),
                )
            );
        }

        if (isset($section['id']) && $section['id'] == 'listingpro-email-management') {
            $allowed_html_array = array(
                'i' => array(
                    'class' => array()
                ),
                'span' => array(
                    'class' => array()
                ),
                'a' => array(
                    'href' => array(),
                    'title' => array(),
                    'target' => array()
                )
            );
            $section['fields'][] = array(
                'id' => 'send_booking_gcal',
                'type' => 'switch',
                'title' => __('Add To Calendar Email', 'medicalpro'),
                'desc' => __('Enable to Send An Email For Add Appointment To Google Or Other Calendar Apps.', 'medicalpro'),
                'default' => true,
            );
            $section['fields'][] = array(
                'id' => 'mp-new-booking-submit-info',
                'type' => 'info',
                'notice' => false,
                'style' => 'info',
                'title' => wp_kses(__('<span class="font24">Submit Booking</span>', 'medicalpro'), $allowed_html_array),
                'desc' => esc_html__('Use these shortcodes only for booking cancelled email. %listing_title as Listing title, %listing_url as Listing URL, %hospital_name as Hospital Name, %hospital_url as Hospital URL, %appointment_date as Appointment Date, %appointment_time as Appointment Time, %appointment_status as Appointment Status, %listing_author_name as Listing Author Name, %listing_author_url as Listing Author URL, %booker_name as Booker Name, %booker_url as Booker URL', 'medicalpro')
            );
            $section['fields'][] = array(
                'id' => 'mp_submit_booking_subject',
                'type' => 'text',
                'title' => esc_html__('New Booking Submission Subject', 'medicalpro'),
                'subtitle' => esc_html__('Email subject', 'medicalpro'),
                'desc' => '',
                'default' => esc_html__('You have created a new Appointment', 'medicalpro'),
            );
            $section['fields'][] = array(
                'id' => 'mp_submit_booking_content',
                'type' => 'editor',
                'title' => esc_html__('New Booking Submission Content', 'medicalpro'),
                'subtitle' => esc_html__('Email content', 'medicalpro'),
                'desc' => '',
                'default' => '<div style="width: 100%; background: #f0f1f3; padding: 50px 0px;"><a style="width: 45%; margin: 0 auto; text-align: center; display: block; padding-bottom: 25px; padding-left: 30px; padding-right: 30px;"><img src="images/logo.png" /></a>
                <div style="width: 45%; background: #fff; padding: 50px 30px; margin: 0 auto;">
                <div style="padding: 30px 0px 15px 0px;">
                <h3 style="margin: 0px 0px 5px; font-size: 16px;">Appointment Details:</h3>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Doctor Name:</strong><a href="%listing_url">%listing_title</a></p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Hospital Name:</strong><a href="%hospital_url">%hospital_name</a></p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Date:</strong>%appointment_date</p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Time:</strong>%appointment_time</p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Status:</strong>%appointment_status</p>
                <p style="margin-top: 10px; font-size: 14px;">You will be notified if your Appointment is APPROVED or CANCELED.</p>
                </div>
                </div>
                </div>',
                'args' => array(
                    'teeny' => false,
                    'textarea_rows' => 10,
                    'wpautop' => false
                )
            );
            $section['fields'][] = array(
                'id' => 'mp_submit_booing_subject_author',
                'type' => 'text',
                'title' => esc_html__('New Booking Submission Subject(for Listing Author)', 'medicalpro'),
                'subtitle' => esc_html__('Email subject', 'medicalpro'),
                'desc' => '',
                'default' => esc_html__('You have received a new Appointment', 'medicalpro'),
            );
            $section['fields'][] = array(
                'id' => 'mp_submit_booking_content_author',
                'type' => 'editor',
                'title' => esc_html__('New Booking Submission Content(for Listing Author)', 'medicalpro'),
                'subtitle' => esc_html__('Email content', 'medicalpro'),
                'desc' => '',
                'default' => '<div style="width: 100%; background: #f0f1f3; padding: 50px 0px;"><a style="width: 45%; margin: 0 auto; text-align: center; display: block; padding-bottom: 25px; padding-left: 30px; padding-right: 30px;"><img src="images/logo.png" /></a>
                <div style="width: 45%; background: #fff; padding: 50px 30px; margin: 0 auto;">
                <div style="padding: 30px 0px 15px 0px;">
                <h3 style="margin: 0px 0px 5px; font-size: 16px;">Appointment Details:</h3>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Customer Name:</strong><a href="%customer_url">%customer_name</a></p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Hospital Name:</strong><a href="%hospital_url">%hospital_name</a></p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Date:</strong>%appointment_date</p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Time:</strong>%appointment_time</p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Status:</strong>%appointment_status</p>
                </div>
                </div>
                </div>',
                'args' => array(
                    'teeny' => false,
                    'textarea_rows' => 10,
                    'wpautop' => false
                )
            );



            $section['fields'][] = array(
                'id' => 'mp_send_booking_room_subject',
                'type' => 'text',
                'title' => esc_html__('Booking Room Url Subject', 'medicalpro'),
                'subtitle' => esc_html__('Email subject', 'medicalpro'),
                'desc' => '',
                'default' => esc_html__('Your Appointment Room Link', 'medicalpro'),
            );
            $section['fields'][] = array(
                'id' => 'mp_send_booking_room_content',
                'type' => 'editor',
                'title' => esc_html__('New Booking Submission Content', 'medicalpro'),
                'subtitle' => esc_html__('Email content', 'medicalpro'),
                'desc' => '',
                'default' => '<div style="width: 100%; background: #f0f1f3; padding: 50px 0px;"><a style="width: 45%; margin: 0 auto; text-align: center; display: block; padding-bottom: 25px; padding-left: 30px; padding-right: 30px;"><img src="images/logo.png" /></a>
                <div style="width: 45%; background: #fff; padding: 50px 30px; margin: 0 auto;">
                <div style="padding: 30px 0px 15px 0px;">
                <h3 style="margin: 0px 0px 5px; font-size: 16px;">Appointment Details:</h3>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Doctor Name:</strong><a href="%listing_url">%listing_title</a></p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Hospital Name:</strong><a href="%hospital_url">%hospital_name</a></p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Date:</strong>%appointment_date</p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Time:</strong>%appointment_time</p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Status:</strong>%appointment_status</p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Room URL:</strong>%room_url</p>
                </div>
                </div>
                </div>',
                'args' => array(
                    'teeny' => false,
                    'textarea_rows' => 10,
                    'wpautop' => false
                )
            );




            $section['fields'][] = array(
                'id' => 'mp-approved-booking-info',
                'type' => 'info',
                'notice' => false,
                'style' => 'info',
                'title' => wp_kses(__('<span class="font24">Approved Booking</span>', 'medicalpro'), $allowed_html_array),
                'desc' => esc_html__('Use these shortcodes only for booking cancelled email. %listing_title as Listing title, %listing_url as Listing URL, %hospital_name as Hospital Name, %hospital_url as Hospital URL, %appointment_date as Appointment Date, %appointment_time as Appointment Time, %appointment_status as Appointment Status, %listing_author_name as Listing Author Name, %listing_author_url as Listing Author URL, %booker_name as Booker Name, %booker_url as Booker URL, %approved_date as Approved Date', 'medicalpro')
            );
            $section['fields'][] = array(
                'id' => 'mp_approved_booking_subject',
                'type' => 'text',
                'title' => esc_html__('Approved Booking Subject', 'medicalpro'),
                'subtitle' => esc_html__('Email subject', 'medicalpro'),
                'desc' => '',
                'default' => esc_html__('Your Appointment has been approved', 'medicalpro'),
            );
            $section['fields'][] = array(
                'id' => 'mp_approved_booking_content',
                'type' => 'editor',
                'title' => esc_html__('Approved Booking Content', 'medicalpro'),
                'subtitle' => esc_html__('Email content', 'medicalpro'),
                'desc' => '',
                'default' => '<div style="width: 100%; background: #f0f1f3; padding: 50px 0px;"><a style="width: 45%; margin: 0 auto; text-align: center; display: block; padding-bottom: 25px; padding-left: 30px; padding-right: 30px;"><img src="images/logo.png" /></a>
                <div style="width: 45%; background: #fff; padding: 50px 30px; margin: 0 auto;">
                <div style="padding: 30px 0px 15px 0px;">
                <h3 style="margin: 0px 0px 5px; font-size: 16px;">Appointment Details:</h3>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Doctor Name:</strong><a href="%listing_url">%listing_title</a></p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Hospital Name:</strong><a href="%hospital_url">%hospital_name</a></p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Date:</strong>%appointment_date</p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Time:</strong>%appointment_time</p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Status:</strong>%appointment_status</p>
                </div>
                </div>
                </div>',
                'args' => array(
                    'teeny' => false,
                    'textarea_rows' => 10,
                    'wpautop' => false
                )
            );

            $section['fields'][] = array(
                'id' => 'mp-cancelled-booking-info',
                'type' => 'info',
                'notice' => false,
                'style' => 'info',
                'title' => wp_kses(__('<span class="font24">Cancelled Booking</span>', 'medicalpro'), $allowed_html_array),
                'desc' => esc_html__('Use these shortcodes only for booking cancelled email. %listing_title as Listing title, %listing_url as Listing URL, %hospital_name as Hospital Name, %hospital_url as Hospital URL, %appointment_date as Appointment Date, %appointment_time as Appointment Time, %appointment_status as Appointment Status, %listing_author_name as Listing Author Name, %listing_author_url as Listing Author URL, %booker_name as Booker Name, %booker_url as Booker URL, %cancelled_date as Cancelled Date', 'medicalpro')
            );
            $section['fields'][] = array(
                'id' => 'mp_cancelled_booking_subject',
                'type' => 'text',
                'title' => esc_html__('Cancelled Booking Subject', 'medicalpro'),
                'subtitle' => esc_html__('Email subject', 'medicalpro'),
                'desc' => '',
                'default' => esc_html__('Your Appointment has been cancelled', 'medicalpro'),
            );
            $section['fields'][] = array(
                'id' => 'mp_cancelled_booking_content',
                'type' => 'editor',
                'title' => esc_html__('Cancelled Booking Content', 'medicalpro'),
                'subtitle' => esc_html__('Email content', 'medicalpro'),
                'desc' => '',
                'default' => '<div style="width: 100%; background: #f0f1f3; padding: 50px 0px;"><a style="width: 45%; margin: 0 auto; text-align: center; display: block; padding-bottom: 25px; padding-left: 30px; padding-right: 30px;"><img src="images/logo.png" /></a>
                <div style="width: 45%; background: #fff; padding: 50px 30px; margin: 0 auto;">
                <div style="padding: 30px 0px 15px 0px;">
                <h3 style="margin: 0px 0px 5px; font-size: 16px;">Appointment Details:</h3>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Doctor Name:</strong><a href="%listing_url">%listing_title</a></p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Hospital Name:</strong><a href="%hospital_url">%hospital_name</a></p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Date:</strong>%appointment_date</p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Time:</strong>%appointment_time</p>
                <p style="margin: 0px; font-size: 14px;"><strong style="padding-right: 10px;">Appointment Status:</strong>%appointment_status</p>
                </div>
                </div>
                </div>',
                'args' => array(
                    'teeny' => false,
                    'textarea_rows' => 10,
                    'wpautop' => false
                )
            );
        }

        if (isset($section['id']) && $section['id'] == 'lp_tax_setting') {
            $new_sections[] = array(
                'title' => __('Commision', 'medicalpro'),
                'id' => 'mp_commision_setting',
                'desc' => '',
                'subsection' => true,
                'fields' => array(
                    array(
                        'id' => 'lp_commision_swtich',
                        'type' => 'switch',
                        'title' => esc_html__('Commision', 'medicalpro'),
                        'desc' => 'Enable to admin Commision',
                        'subtitle' => '',
                        'on' => esc_html__('Enabled', 'medicalpro'),
                        'off' => esc_html__('Disabled', 'medicalpro'),
                        'default' => 0,
                    ),
                    array(
                        'id' => 'lp_commision_percent',
                        'type' => 'text',
                        'required' => array('lp_commision_swtich', '=', '1'),
                        'title' => esc_html__('Commision Percentage', 'medicalpro'),
                        'subtitle' => esc_html__('Add percentage without % sign', 'medicalpro'),
                        'desc' => esc_html__('Enter commision without percentage sign', 'medicalpro'),
                        'validate_callback' => 'lp_option_taxrate_validate_callback',
                        'default' => '10',
                    )
                )
            );
        }

        if (isset($section['id']) && $section['id'] == 'lp_tax_setting') {
            $new_sections[] = array(
                'title' => __('Payout Settings', 'medicalpro'),
                'id' => 'mp_payout_setting',
                'desc' => '',
                'subsection' => true,
                'fields' => array(
                    array(
                        'id' => 'mp_payout_setting_paypal',
                        'type' => 'switch',
                        'title' => esc_html__('Paypal', 'medicalpro'),
                        'desc' => 'Allow Users To Get Payouts Via Paypal',
                        'subtitle' => '',
                        'on' => esc_html__('Enabled', 'medicalpro'),
                        'off' => esc_html__('Disabled', 'medicalpro'),
                        'default' => 1,
                    ),
                    array(
                        'id' => 'mp_payout_setting_payoneer',
                        'type' => 'switch',
                        'title' => esc_html__('Payoneer', 'medicalpro'),
                        'desc' => 'Allow Users To Get Payouts Via Payoneer',
                        'subtitle' => '',
                        'on' => esc_html__('Enabled', 'medicalpro'),
                        'off' => esc_html__('Disabled', 'medicalpro'),
                        'default' => 1,
                    ),
                    array(
                        'id' => 'mp_payout_setting_stripe',
                        'type' => 'switch',
                        'title' => esc_html__('Stripe', 'medicalpro'),
                        'desc' => 'Allow Users To Get Payouts Via Stripe',
                        'subtitle' => '',
                        'on' => esc_html__('Enabled', 'medicalpro'),
                        'off' => esc_html__('Disabled', 'medicalpro'),
                        'default' => 1,
                    ),
                    array(
                        'id' => 'mp_payout_setting_bank',
                        'type' => 'switch',
                        'title' => esc_html__('Bank Transfer', 'medicalpro'),
                        'desc' => 'Allow Users To Get Payouts Via Bank Transfer',
                        'subtitle' => '',
                        'on' => esc_html__('Enabled', 'medicalpro'),
                        'off' => esc_html__('Disabled', 'medicalpro'),
                        'default' => 1,
                    ),
                    array(
                        'id' => 'mp_payout_setting_other',
                        'type' => 'switch',
                        'title' => esc_html__('Other', 'medicalpro'),
                        'desc' => 'Allow Users To Get Payouts Via Other Method Than Listed Methods',
                        'subtitle' => '',
                        'on' => esc_html__('Enabled', 'medicalpro'),
                        'off' => esc_html__('Disabled', 'medicalpro'),
                        'default' => 1,
                    ),
                )
            );
        }

        $new_sections[] = $section;
    }



    return $new_sections;
}
