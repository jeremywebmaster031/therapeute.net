<?php
if (!function_exists('mp_get_template_part')) {
    function mp_get_template_part($slug, $name = null)
    {

        $templates = array();
        if (isset($name))
            $templates[] = "{$slug}-{$name}.php";

        $templates[] = "{$slug}.php";
        mp_get_template_path($templates, true, false);
    }
}
if (!function_exists('medicalpro_get_template_part')) {
    function medicalpro_get_template_part($slug, $name = null)
    {

        $templates = array();
        if (isset($name))
            $templates[] = "{$slug}-{$name}.php";

        $templates[] = "{$slug}.php";
        mp_get_template_path($templates, true, false);
    }
}

if (!function_exists('mp_get_template_path')) {
    function mp_get_template_path($template_names, $load = false, $require_once = true)
    {
        $located = '';
        foreach ((array)$template_names as $template_name) {
            if (!$template_name)
                continue;
            if (file_exists(MP_PLUGIN_PATH . $template_name)) {
                $located = MP_PLUGIN_PATH . $template_name;
                break;
            }
        }

        if ($load && '' != $located)
            load_template($located, $require_once);

        return $located;
    }
}

add_filter('body_class', 'medicalpro_body_class');
if (!function_exists('medicalpro_body_class')) {
    function medicalpro_body_class($classes)
    {
        if (is_front_page()) {
            $classes[] = 'mp-home';
            return $classes;
        }
    }
}

if (!function_exists('medicalpro_business_working_days')) {
    function medicalpro_business_working_days($day = '')
    {

        $days_arr = array(
            'Monday' => esc_html__('Monday', 'medicalpro'),
            'Tuesday' => esc_html__('Tuesday', 'medicalpro'),
            'Wednesday' => esc_html__('Wednesday', 'medicalpro'),
            'Thursday' => esc_html__('Thursday', 'medicalpro'),
            'Friday' => esc_html__('Friday', 'medicalpro'),
            'Saturday' => esc_html__('Saturday', 'medicalpro'),
            'Sunday' => esc_html__('Sunday', 'medicalpro'),
        );

        if (isset($day) && !empty($day)) {
            return isset($days_arr[$day]) ? $days_arr[$day] : $day;
        }

        return $days_arr;
    }
}

if (!function_exists('medicalpro_booking_types')) {
    function medicalpro_booking_types($booking_type = '')
    {

        $booking_types = array(
            'in-person' => esc_html__('In-person', 'medicalpro'),
            'video-consultation' => esc_html__('Video Consultation', 'medicalpro'),
        );

        if (isset($booking_type) && !empty($booking_type)) {
            return isset($booking_types[$booking_type]) ? $booking_types[$booking_type] : $booking_type;
        }

        return $booking_types;
    }
}

if (!function_exists('medicalpro_booking_payment_statuses')) {
    function medicalpro_booking_payment_statuses($booking_payment_status = '')
    {

        $booking_payment_statuses = array(
            'pending' => esc_html__('Pending', 'medicalpro'),
            'paid' => esc_html__('Paid', 'medicalpro'),
        );

        if (isset($booking_payment_status) && !empty($booking_payment_status)) {
            return isset($booking_payment_statuses[$booking_payment_status]) ? $booking_payment_statuses[$booking_payment_status] : $booking_payment_status;
        }

        return $booking_payment_statuses;
    }
}


add_action('wp_ajax_medicalpro_hospital_doctors_content', 'medicalpro_hospital_doctors_content_callback');
add_action('wp_ajax_nopriv_medicalpro_hospital_doctors_content', 'medicalpro_hospital_doctors_content_callback');
if (!function_exists('medicalpro_hospital_doctors_content_callback')) {
    function medicalpro_hospital_doctors_content_callback()
    {
        global $wpdb;

        $hospital_id = isset($_POST['hospital_id']) ? $_POST['hospital_id'] : 0;
        $letter = isset($_POST['letter']) ? sanitize_text_field($_POST['letter']) : '';
        $cat_id = isset($_POST['cat_id']) ? $_POST['cat_id'] : 0;

        $listing_ids = array();
        if (isset($letter) && $letter != '') {
            $listings = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_title LIKE '$letter%' AND post_type = 'listing' AND post_status = 'publish';", ARRAY_A);
            if (isset($listings) && !empty($listings)) {
                foreach ($listings as $listing) {
                    $listing_ids[] = $listing['ID'];
                }
            } else {
                $listing_ids = array(0);
            }
        }

        $tax_filters = array();
        $tax_filters[] = array(
            'taxonomy' => 'medicalpro-hospital',
            'field' => 'term_id',
            'terms' => array($hospital_id),
        );

        if (isset($cat_id) && $cat_id > 0) {
            $tax_filters[] = array(
                'taxonomy' => 'listing-category',
                'field' => 'term_id',
                'terms' => array($cat_id),
            );
        }

        $doctors_query_args = array(
            'post_type' => 'listing',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'paged' => 1,
            'fields' => 'ids',
            'tax_query' => array(
                $tax_filters
            )
        );
        if (isset($listing_ids) && !empty($listing_ids)) {
            $doctors_query_args['post__in'] = $listing_ids;
        }
        $doctors = get_posts($doctors_query_args);
        if (isset($doctors) && !empty($doctors)) {
            foreach ($doctors as $doctor) {
                set_query_var('doctor_id', $doctor);
                medicalpro_get_template_part('templates/hospital/doctor-loop-content');
            }
        }
        wp_reset_postdata();
        die();
    }
}

if (!function_exists('medicalpro_all_extra_fields')) {
    function medicalpro_all_extra_fields($postid)
    {

        $output = '';
        $count = 0;
        $metaboxes = get_post_meta($postid, 'lp_' . strtolower(THEMENAME) . '_options_fields', true);

        if (!empty($metaboxes)) {
            unset($metaboxes['lp_feature']);
            if (!empty($metaboxes)) {
                $numberOF = count($metaboxes);
                $output = null;
                $output .= '';

                $output .= '<div class="clearfix">';

                foreach ($metaboxes as $slug => $value) {
                    if ($count <= 5) {
                        $queried_post = get_page_by_path($slug, OBJECT, 'form-fields');
                        if (!empty($queried_post)) {
                            $dieldsID = $queried_post->ID;

                            if (is_array($value)) {
                                $value = "<span class='col-md-6'>" . implode('</span><span class="col-md-6">', $value) . "</span>";
                            }
                            if ($value == '0') {
                                $value = 'No';
                            }
                            if (!empty($value)) {

                                $output .= '<div class="mp-services-content-single margin-bottom-40"><div class="mp-services-heading"> <h1>' . get_the_title($dieldsID) . '</div> </h1>
									
									
											
											<div class="mp-services-content"><div class="mp-services-content-single-name ">
                                                ' . $value . '
                                            </div></div>
											
									</div>';
                            }
                        }
                    }
                    $count++;
                }

                $output .= '</div>';
                // closing
            }
            return $output;
        }
    }
}

if (!function_exists('medicalpro_hex2rgba2')) {
    function medicalpro_hex2rgba2($color, $opacity = false)
    {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) == 1)
                $opacity = 1.0;
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }
}

if (!function_exists('listingpro_get_campaigns_listing_v2')) {
    function listingpro_get_campaigns_listing_v2($campaign_type, $IDSonly, $taxQuery = array(), $searchQuery = array(), $priceQuery = array(), $s = null, $noOfListings = null, $posts_in = null)
    {

        $Clistingid = '';
        if (is_singular('listing')) {
            global $post;
            $Clistingid = $post->ID;
        }
        global $listingpro_options;
        $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];

        $adsType = array(
            'lp_random_ads',
            'lp_detail_page_ads',
            'lp_top_in_search_page_ads'
        );

        global $listingpro_options;
        $listing_style = '';
        $listing_style = $listingpro_options['listing_style'];
        $postNumber = '';
        if ($listing_style == '3' && !is_front_page()) {
            if (empty($noOfListings)) {
                $postNumber = 2;
            } else {
                $postNumber = $noOfListings;
            }
        } elseif ($listing_style == '4' && !is_front_page()) {
            if (empty($noOfListings)) {
                $postNumber = 2;
            } else {
                $postNumber = $noOfListings;
            }
        } else {
            if (empty($noOfListings)) {
                $postNumber = 2;
            } else {
                $postNumber = $noOfListings;
            }
        }


        if (!empty($campaign_type)) {
            if (in_array($campaign_type, $adsType, true)) {

                $TxQuery = array();
                if (!empty($taxQuery) && is_array($taxQuery)) {
                    $TxQuery = $taxQuery;
                } elseif (!empty($searchQuery) && is_array($searchQuery)) {
                    $TxQuery = $searchQuery;
                }
                $args = array(
                    'orderby' => 'rand',
                    'post_type' => 'listing',
                    'post_status' => 'publish',
                    'posts_per_page' => $postNumber,
                    'post__not_in' => array($Clistingid),
                    'tax_query' => $TxQuery,
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => 'campaign_status',
                            'value' => array('active'),
                            'compare' => 'IN',
                        ),
                        $priceQuery,
                    ),
                );
                if (!empty($s)) {
                    $args = array(
                        'orderby' => 'rand',
                        'post_type' => 'listing',
                        'post_status' => 'publish',
                        's' => $s,
                        'posts_per_page' => $postNumber,
                        'tax_query' => $TxQuery,
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'campaign_status',
                                'value' => array('active'),
                                'compare' => 'IN',
                            ),
                            $priceQuery,
                        ),
                    );
                }

                if (!empty($posts_in)) {
                    $args['post__in'] = $posts_in;
                }

                $idsArray = array();
                $the_query = new WP_Query($args);
                if ($the_query->have_posts()) {
                    while ($the_query->have_posts()) {
                        $the_query->the_post();
                        if ($IDSonly == TRUE) {
                            $idsArray[] = get_the_ID();
                        } else {
                            if (is_singular('listing')) {
                                if ($listing_mobile_view == 'app_view' && wp_is_mobile()) {
                                    echo '<div class="row app-view-ads lp-row-app">';
                                    get_template_part('mobile/listing-loop-app-view');
                                    echo '</div>';
                                } else {
                                    get_template_part('templates/details-page-ads');
                                }
                            } elseif ((is_page() || is_home() || is_singular('post')) && (is_active_sidebar('default-sidebar') || is_active_sidebar('listing_archive_sidebar'))) {
                                get_template_part('templates/details-page-ads');
                            } elseif (is_singular('post')) {
                                get_template_part('templates/details-page-ads');
                            } else {
                                $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];
                                if ($listing_mobile_view == 'app_view' && wp_is_mobile()) {
                                    get_template_part('mobile/listing-loop-app-view');
                                } elseif ($listing_mobile_view == 'app_view2' && wp_is_mobile()) {
                                    get_template_part('mobile/listing-loop-app-view-adds');
                                } else {
                                    if (isset($GLOBALS['sidebar_add_loop']) && $GLOBALS['sidebar_add_loop'] == 'yes') {
                                        get_template_part('templates/details-page-ads');
                                    } else {
                                        include(MP_PLUGIN_PATH . "templates/loop/loop2-list.php");
                                    }
                                }
                            }
                        }

                        wp_reset_postdata();
                    }
                    if ($IDSonly == TRUE) {
                        if (!empty($idsArray)) {
                            return $idsArray;
                        }
                    }
                }
            }
        }
    }
}
if (!function_exists('listingpro_get_campaigns_listing')) {
    function listingpro_get_campaigns_listing($campaign_type, $IDSonly, $taxQuery = array(), $searchQuery = array(), $priceQuery = array(), $s = null, $noOfListings = null, $posts_in = null)
    {

        $Clistingid = '';
        if (is_singular('listing')) {
            global $post;
            $Clistingid = $post->ID;
        }
        global $listingpro_options;
        $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];

        $adsType = array(
            'lp_random_ads',
            'lp_detail_page_ads',
            'lp_top_in_search_page_ads'
        );

        global $listingpro_options;
        $listing_style = '';
        $listing_style = $listingpro_options['listing_style'];
        $postNumber = '';
        if ($listing_style == '3' && !is_front_page()) {
            if (empty($noOfListings)) {
                $postNumber = 2;
            } else {
                $postNumber = $noOfListings;
            }
        } elseif ($listing_style == '4' && !is_front_page()) {
            if (empty($noOfListings)) {
                $postNumber = 2;
            } else {
                $postNumber = $noOfListings;
            }
        } else {
            if (empty($noOfListings)) {
                $postNumber = 2;
            } else {
                $postNumber = $noOfListings;
            }
        }


        if (!empty($campaign_type)) {
            if (in_array($campaign_type, $adsType, true)) {

                $TxQuery = array();
                if (!empty($taxQuery) && is_array($taxQuery)) {
                    $TxQuery = $taxQuery;
                } elseif (!empty($searchQuery) && is_array($searchQuery)) {
                    $TxQuery = $searchQuery;
                }
                $args = array(
                    'orderby' => 'rand',
                    'post_type' => 'listing',
                    'post_status' => 'publish',
                    'posts_per_page' => $postNumber,
                    'post__not_in' => array($Clistingid),
                    'tax_query' => $TxQuery,
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => 'campaign_status',
                            'value' => array('active'),
                            'compare' => 'IN',
                        ),
                        $priceQuery,
                    ),
                );
                if (!empty($s)) {
                    $args = array(
                        'orderby' => 'rand',
                        'post_type' => 'listing',
                        'post_status' => 'publish',
                        's' => $s,
                        'posts_per_page' => $postNumber,
                        'tax_query' => $TxQuery,
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'campaign_status',
                                'value' => array('active'),
                                'compare' => 'IN',
                            ),
                            $priceQuery,
                        ),
                    );
                }

                if (!empty($posts_in)) {
                    $args['post__in'] = $posts_in;
                }

                $idsArray = array();
                $the_query = new WP_Query($args);
                if ($the_query->have_posts()) {
                    while ($the_query->have_posts()) {
                        $the_query->the_post();
                        if ($IDSonly == TRUE) {
                            $idsArray[] = get_the_ID();
                        } else {
                            if (is_singular('listing')) {
                                if ($listing_mobile_view == 'app_view' && wp_is_mobile()) {
                                    echo '<div class="row app-view-ads lp-row-app">';
                                    get_template_part('mobile/listing-loop-app-view');
                                    echo '</div>';
                                } else {
                                    get_template_part('templates/details-page-ads');
                                }
                            } elseif ((is_page() || is_home() || is_singular('post')) && (is_active_sidebar('default-sidebar') || is_active_sidebar('listing_archive_sidebar'))) {
                                get_template_part('templates/details-page-ads');
                            } elseif (is_singular('post')) {
                                get_template_part('templates/details-page-ads');
                            } else {
                                $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];
                                if ($listing_mobile_view == 'app_view' && wp_is_mobile()) {
                                    include(MP_PLUGIN_PATH . "templates/loop/appview-grid.php");
                                } elseif ($listing_mobile_view == 'app_view2' && wp_is_mobile()) {
                                    get_template_part('mobile/listing-loop-app-view-adds');
                                } else {
                                    if (isset($GLOBALS['sidebar_add_loop']) && $GLOBALS['sidebar_add_loop'] == 'yes') {
                                        get_template_part('templates/details-page-ads');
                                    } else {

                                        $viewStyle = 'list';
                                        if (isset($_POST['list_style']) && !empty($_POST['list_style'])) {
                                            $viewStyle = $_POST['list_style'];
                                        }

                                        $listShowHide = 'show';
                                        $gridShowHide = 'hide';
                                        if ($viewStyle == 'list' && !wp_is_mobile()) {
                                            $listShowHide = 'show';
                                            $gridShowHide = 'hide';
                                        } else {
                                            $listShowHide = 'hide';
                                            $gridShowHide = 'show';
                                        }

                                        ?>
                                        <div class="md-list-view <?php echo $listShowHide; ?>">
                                            <?php
                                            include(MP_PLUGIN_PATH . "templates/loop/loop2-list.php");
                                            ?>
                                        </div>
                                        <div class="md-grid-view <?php echo $gridShowHide; ?>">
                                            <?php
                                            include(MP_PLUGIN_PATH . "templates/loop/loop2.php");
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }

                        wp_reset_postdata();
                    }
                    if ($IDSonly == TRUE) {
                        if (!empty($idsArray)) {
                            return $idsArray;
                        }
                    }
                }
            }
        }
    }
}

add_action('wp_ajax_ajax_search_tags', 'ajax_search_tags');
add_action('wp_ajax_nopriv_ajax_search_tags', 'ajax_search_tags');
if (!function_exists('ajax_search_tags')) {
    function ajax_search_tags()
    {
        check_ajax_referer('lp_ajax_nonce', 'lpNonce');
        // Nonce is checked, get the POST data and sign user on
        if (!wp_verify_nonce(sanitize_text_field($_POST['lpNonce']), 'lp_ajax_nonce')) {
            $res = json_encode(array('nonceerror' => 'yes'));
            die($res);
        }
        global $listingpro_options;
        $info = array('');
        $metakeyOrderBy = 'date';
        $lporders = 'DESC';
        if (isset($listingpro_options['lp_archivepage_listingorder'])) {
            $lporders = $listingpro_options['lp_archivepage_listingorder'];
        }
        if (isset($listingpro_options['lp_archivepage_listingorderby'])) {
            $metakeyOrderBy = $listingpro_options['lp_archivepage_listingorderby'];
        }

        $includeChildren = true;
        if (lp_theme_option('lp_children_in_tax')) {
            if (lp_theme_option('lp_children_in_tax') == "no") {
                $includeChildren = false;
            }
        }


        $defSquery = '';
        $lpDefaultSearchBy = 'title';
        if (isset($listingpro_options['lp_default_search_by'])) {
            $lpDefaultSearchBy = $listingpro_options['lp_default_search_by'];
        }

        $pageno = '';
        if (isset($_POST['pageno'])) {
            $pageno = $_POST['pageno'];
        }
        /* for version 2.0 */
        $formFieldsMetaArray = array();
        $formFieldsMetaArray['relation'] = 'AND';
        $lp_formFIelds = array();
        if (isset($_POST['formfields'])) {
            $lp_formFIelds = $_POST['formfields'];
        }

        /* for radious filter */
        $sloc_address = (isset($_POST['sloc_address'])) ? $_POST['sloc_address'] : '';
        $my_bounds_ne_lat = (isset($_POST['my_bounds_ne_lat'])) ? $_POST['my_bounds_ne_lat'] : '';
        $my_bounds_ne_lng = (isset($_POST['my_bounds_ne_lng'])) ? $_POST['my_bounds_ne_lng'] : '';
        $my_bounds_sw_lat = (isset($_POST['my_bounds_sw_lat'])) ? $_POST['my_bounds_sw_lat'] : '';
        $my_bounds_sw_lng = (isset($_POST['my_bounds_sw_lng'])) ? $_POST['my_bounds_sw_lng'] : '';

        $units = $listingpro_options['lp_nearme_filter_param'];
        if (empty($units)) {
            $units = 'km';
        }


        $squery = '';

        $latlongfilter = false;
        $latlongArray = array();
        $openNowArray = array();
        $clat = '';
        $clong = '';
        if (isset($_POST['clat'])) {
            $clat = sanitize_text_field($_POST['clat']);
        }
        if (isset($_POST['clong'])) {
            $clong = sanitize_text_field($_POST['clong']);
        }

        $info['tag_name'] = $_POST['tag_name'];
        $info['cat_id'] = sanitize_text_field($_POST['cat_id']);

        /* for dynamic search result title */

        $searchtitles = array();
        if (isset($_POST['cat_id'])) {
            if (!empty($_POST['cat_id'])) {
                $categoryID = $_POST['cat_id'];
                if (is_numeric($categoryID)) {
                    $categoryTerm = get_term_by('id', $categoryID, 'listing-category');
                    $term_Name = $categoryTerm->name;
                    $searchtitles['category'] = $term_Name;
                } else {
                    $searchtitles['category'] = $categoryID;
                }
            }
        }

        if (isset($_POST['loc_id'])) {
            if (!empty($_POST['loc_id'])) {
                $locationID = $_POST['loc_id'];
                if (is_numeric($locationID)) {
                    if ($listingpro_options['lp_listing_search_locations_type'] == 'auto_loc') {
                        $url = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $locationID . '&key=' . $listingpro_options['google_map_api']);
                        $array = json_decode($url, TRUE);
                        if (isset($array['results'][0]['address_components'][1]['long_name'])) {
                            if (!empty($array['results'][0]['address_components'][1]['long_name'])) {
                                $loc_name = $array['results'][0]['address_components'][1]['long_name'];
                                $term = listingpro_term_exist($loc_name, 'location');
                                if (!empty($term)) {
                                    $locationID = $term['term_id'];
                                    $locationTerm = get_term_by('id', $locationID, 'location');
                                    $term_Name = $locationTerm->name;
                                    $searchtitles['location'] = $term_Name;
                                } else {
                                    $locationTerm = get_term_by('id', $locationID, 'location');
                                    $term_Name = $locationTerm->name;
                                    $searchtitles['location'] = $term_Name;
                                }
                            } else {
                                $locationTerm = get_term_by('id', $locationID, 'location');
                                $term_Name = $locationTerm->name;
                                $searchtitles['location'] = $term_Name;
                            }
                        } else {
                            $locationTerm = get_term_by('id', $locationID, 'location');
                            $term_Name = $locationTerm->name;
                            $searchtitles['location'] = $term_Name;
                        }
                    } else {
                        $locationTerm = get_term_by('id', $locationID, 'location');
                        $term_Name = $locationTerm->name;
                        $searchtitles['location'] = $term_Name;
                    }
                } else {
                    $searchtitles['location'] = $locationID;
                }
            }
        }

        if (!empty($searchtitles)) {
            $searchtitles['website'] = get_option('blogname');
            if (count($searchtitles) > 2) {
                $searchtitles['in'] = esc_html__('in', 'medicalpro');
            }
            $searchtitles['for'] = esc_html__('for', 'medicalpro');
        }

        /* end for dynamic search result title */
        if (isset($_POST['loc_id'])) {
            if (is_numeric($_POST['loc_id'])) {
                if ($listingpro_options['lp_listing_search_locations_type'] == 'auto_loc') {
                    $url = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $_POST['loc_id'] . '&key=' . $listingpro_options['google_map_api']);
                    $array = json_decode($url, TRUE);
                    if (isset($array['results'][0]['address_components'][1]['long_name'])) {
                        if (!empty($array['results'][0]['address_components'][1]['long_name'])) {
                            $loc_name = $array['results'][0]['address_components'][1]['long_name'];
                            $term = listingpro_term_exist($loc_name, 'location');
                            if (!empty($term)) {
                                $locationID = $term['term_id'];
                                $info['loc_id'] = ($sloc_address != '' || $my_bounds_ne_lat != '') ? '' : sanitize_text_field($locationID);
                            } else {
                                $locationID = $_POST['loc_id'];
                                $info['loc_id'] = ($sloc_address != '' || $my_bounds_ne_lat != '') ? '' : sanitize_text_field($locationID);
                            }
                        } else {
                            $locationID = $_POST['loc_id'];
                            $info['loc_id'] = ($sloc_address != '' || $my_bounds_ne_lat != '') ? '' : sanitize_text_field($locationID);
                        }
                    } else {
                        $locationID = $_POST['loc_id'];
                        $info['loc_id'] = ($sloc_address != '' || $my_bounds_ne_lat != '') ? '' : sanitize_text_field($locationID);
                    }
                } else {
                    $locationID = $_POST['loc_id'];
                    $info['loc_id'] = ($sloc_address != '' || $my_bounds_ne_lat != '') ? '' : sanitize_text_field($locationID);
                }
            } else {
                $locTerm = get_term_by('name', $_POST['loc_id'], 'location');
                if (!empty($locTerm)) {
                    $loc_ID = $locTerm->term_id;
                    $info['loc_id'] = ($sloc_address != '' || $my_bounds_ne_lat != '') ? '' : $loc_ID;
                } elseif (!empty($_POST['loc_id'])) {
                    $info['loc_id'] = 1;
                }
            }
        }

        $info['listStyle'] = sanitize_text_field($_POST['list_style']);
        $info['inexpensive'] = sanitize_text_field($_POST['inexpensive']);
        $info['moderate'] = sanitize_text_field($_POST['moderate']);
        $info['pricey'] = sanitize_text_field($_POST['pricey']);
        $info['ultra'] = sanitize_text_field($_POST['ultra']);
        $info['averageRate'] = sanitize_text_field($_POST['averageRate']);
        $info['mostRewvied'] = sanitize_text_field($_POST['mostRewvied']);
        $info['listing_openTime'] = sanitize_text_field($_POST['listing_openTime']);
        $info['mostviewed'] = sanitize_text_field($_POST['mostviewed']);
        $info['lp_s_tag'] = sanitize_text_field($_POST['lpstag']);
        $info['coupons'] = sanitize_text_field($_POST['coupons']);
        $tagQuery = '';
        $catQuery = '';
        $searchtagQuery = '';
        $listing_time = '';
        $opentimeswitch = false;
        $opentimefilter = false;
        $sFeatures = '';
        $sFeatures = $_POST['tag_name'];
        if (!empty($info['listing_openTime'])) {
            $listing_time = $info['listing_openTime'];
            $opentimeswitch = true;
        }
        global $paged;
        if (!empty($pageno)) {
            $paged = $pageno;
        }
        $priceQuery = array();
        $categoryName = '';
        $LocationName = '';
        $locQuery = '';
        $currentTax = '';
        if (!empty($info['tag_name'])) {
            $tagQuery = array(
                'taxonomy' => 'features',
                'field' => 'id',
                'terms' => $info['tag_name'],
                'operator' => 'IN',
            );
        }


        if (!empty($info['cat_id'])) {
            $categoryName = get_term_by('id', $info['cat_id'], 'listing-category');
            $categoryName = $categoryName->name;
            $catQuery = array(
                'taxonomy' => 'listing-category',
                'field' => 'id',
                'terms' => $info['cat_id'],
            );
            if ($includeChildren == false) {
                $catQuery['include_children'] = $includeChildren;
            }
        }

        if (!empty($info['loc_id'])) {
            $LocationName = get_term_by('id', $info['loc_id'], 'location');
            $LocationName = $LocationName->name;
            $locQuery = array(
                'taxonomy' => 'location',
                'field' => 'id',
                'terms' => $info['loc_id'],
            );
            if ($includeChildren == false) {
                $locQuery['include_children'] = $includeChildren;
            }
        }
        if (!empty($info['lp_s_tag']) && isset($info['lp_s_tag'])) {
            $lpsTag = $info['lp_s_tag'];
            $searchtagQuery = array(
                'taxonomy' => 'list-tags',
                'field' => 'id',
                'terms' => $lpsTag,
                'operator' => 'IN' //Or 'AND' or 'NOT IN'
            );
        }

        if (isset($_POST['skeyword'])) {
            if ((empty($info['lp_s_tag']) || !isset($info['lp_s_tag'])) && (empty($info['cat_id']) || !isset($info['cat_id']))) {
                $squery = esc_attr($_POST['skeyword']);
                if (!empty($squery)) {
                    $defSquery = $squery;
                    $termExist = term_exists($squery, 'list-tags');

                    if ($termExist !== 0 && $termExist !== null) {
                        $tagQuery = array(
                            'taxonomy' => 'list-tags',
                            'field' => 'name',
                            'terms' => $squery,
                            'operator' => 'IN' //Or 'AND' or 'NOT IN'
                        );
                        $squery = '';
                        $squeryp = esc_attr($_POST['skeyword']);
                        $defSquery = $squeryp;
                    }
                }
            }
        }

        $rateArray = array();
        $reviewedArray = array();
        $viewedArray = array();
        $orderBy = $metakeyOrderBy;
        $sortBy = '';
        if (!empty($info['averageRate'])) {
            $sortBy = array(
                'key' => 'listing_rate',
                'compare' => 'IN'
            );
            $orderBy = 'meta_value_num';
        } elseif (!empty($info['mostRewvied'])) {
            $sortBy = array(
                'key' => 'listing_reviewed',
                'compare' => 'IN'
            );
            $orderBy = 'meta_value_num';
        } elseif (!empty($info['mostviewed'])) {
            $sortBy = array(
                'key' => 'post_views_count',
                'compare' => 'IN'
            );
            $orderBy = 'meta_value_num';
        } elseif ($metakeyOrderBy == "post_views_count" || $metakeyOrderBy == "listing_reviewed" || $metakeyOrderBy == "listing_rate" || $metakeyOrderBy == "claimed") {
            $sortBy = array(
                'key' => $metakeyOrderBy,
                'compare' => 'IN'
            );
            $orderBy = 'meta_value_num';
        } elseif ($metakeyOrderBy == "rand") {
            $lporders = '';
        }


        $statusArray = array();
        $optenTimeArray = array();
        $couponsArray = array();
        $lpcountwhile = 1;
        $relation = 'OR';

        if (!empty($info['inexpensive'])) {
            $inexArray = array(
                'key' => 'lp_listingpro_options',
                'value' => 'inexpensive',
                'compare' => 'LIKE'
            );
        }
        if (!empty($info['moderate'])) {
            $moderArray = array(
                'key' => 'lp_listingpro_options',
                'value' => 'moderate',
                'compare' => 'LIKE'
            );
        }
        if (!empty($info['pricey'])) {
            $pricyArray = array(
                'key' => 'lp_listingpro_options',
                'value' => 'pricey',
                'compare' => 'LIKE'
            );
        }
        if (!empty($info['ultra'])) {
            $ultrArray = array(
                'key' => 'lp_listingpro_options',
                'value' => 'ultra_high_end',
                'compare' => 'LIKE'
            );
        }
        if (!empty($info['coupons'])) {
            $couponsArray = array(
                'key' => 'listing_discount_data',
                'compare' => 'EXISTS'
            );
        }


        $formFieldsMetaArray = array();
        $fieldsArry = array();

        $lp_formFIelds = array();
        if (isset($_POST['formfields'])) {
            $lp_formFIelds = $_POST['formfields'];
        }
        $fieldsArryy = array();
        $hospitals = array();
        $mpefilters = array();
        if (!empty($lp_formFIelds)) {
            foreach ($lp_formFIelds as $lp_singleField) {
                foreach ($lp_singleField as $k => $v) {

                    if ($k == 'mp_hospitals_tax_filter') {
                        $hospitals[] = $v;
                        continue;
                    }
                    if ($k == 'mp_mpe_feature_filter') {
                        $mpefilters[] = $v;
                        continue;
                    }

                    $kn = $k . '-mfilter';
                    $v = $k . '-' . $v;

                    if (!empty($v) && !empty($k)) {
                        $fieldsArryy[] = array('key' => 'lp_listingpro_options_fields', 'value' => $kn, 'compare' => 'LIKE');
                        $fieldsArryy2[] = array('key' => 'lp_listingpro_options_fields', 'value' => $v, 'compare' => 'LIKE');
                    }
                }
            }
        }

        $hosQuery = null;
        if (!empty($hospitals)) {
            $hosQuery = array(
                'taxonomy' => 'medicalpro-hospital',
                'field' => 'id',
                'terms' => $hospitals,
            );
            if ($includeChildren == false) {
                $hosQuery['include_children'] = $includeChildren;
            }
        }

        $featureQuery = array();
        if (!empty($mpefilters)) {
            foreach ($mpefilters as $k => $v) {
                $featureQuery[] = array(
                    'key' => 'mp_listing_extra_fields_' . $v,
                    'value' => 'Yes',
                    'compare' => 'LIKE'
                );
            }
        }


        $fieldsArry['relation'] = 'OR';
        $fieldsArry2['relation'] = 'OR';
        $n = 0;
        $n2 = 0;
        if (!empty($fieldsArryy)) {
            foreach ($fieldsArryy as $val) {
                $fieldsArry[$n] = $val;
                $n++;
            }
            foreach ($fieldsArryy2 as $val2) {
                $fieldsArry2[$n2] = $val2;
                $n2++;
            }
            $relation = "AND";
        }


        if (!empty($info['inexpensive']) || !empty($info['moderate']) || !empty($info['pricey']) || !empty($info['ultra'])) {
            $statusArray = array(
                'key' => 'lp_listingpro_options',
                'value' => 'price_status',
                'compare' => 'LIKE'
            );
            $relation = "AND";
        }
        if (!empty($info['inexpensive']) || !empty($info['moderate']) || !empty($info['pricey']) || !empty($info['ultra']) || !empty($info['averageRate']) || !empty($info['mostRewvied']) || !empty($info['mostviewed']) || !empty($_POST['formfields']) || !empty($info['coupons']) || !empty($sortBy)) {
            $priceQuery = array(
                'relation' => $relation, // Optional, defaults to "AND"
                $statusArray,
                array(
                    'relation' => 'OR',
                    $inexArray,
                    $moderArray,
                    $pricyArray,
                    $ultrArray
                ),
                $featureQuery,
                $sortBy,
                $couponsArray,
                $fieldsArry,
                $fieldsArry2,
            );
        }

        $listingperpage = '';
        if (isset($listingpro_options['listing_per_page']) && !empty($listingpro_options['listing_per_page'])) {
            $listingperpage = $listingpro_options['listing_per_page'];
        } else {
            $listingperpage = 10;
        }

        /* if nearme is on */
        $listingperpageMain = '';
        if ((!empty($clat) && !empty($clong)) || $listing_time == "open") {

            $listingperpageMain = -1;
        } else {
            $listingperpageMain = $listingperpage;
        }
        /* end if nearme is on */

        /* added by zaheer on 13 march */
        $searchQuery = '';
        $TxQuery = array(
            $searchtagQuery,
            $tagQuery,
            $catQuery,
            $locQuery,
            $hosQuery
        );
        if (empty($TxQuery)) {
            $TxQuery = array();
        }
        $ad_campaignsIDS = listingpro_get_campaigns_listing('lp_top_in_search_page_ads', true, $TxQuery, $searchQuery, $priceQuery, null, null, null);
        $type = 'listing';

        $keysOpenThatDay = array();
        $OpenThatDay = array();
        if (isset($_POST['by_date']) && !empty($_POST['by_date'])) {
            $dateArgs = array(
                'post_type' => $type,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'post__not_in' => $ad_campaignsIDS,
            );
            $dateQuery = null;
            $dateQuery = new WP_Query($dateArgs);
            if ($dateQuery->have_posts()) {
                while ($dateQuery->have_posts()) :
                    $dateQuery->the_post();
                    if (mp_get_listing_open_status(get_the_ID(), $_POST['by_date']) === 'opened') $OpenThatDay[] = get_the_ID();
                endwhile;
            }
            if (!empty($OpenThatDay) && is_array($OpenThatDay)) {
                foreach ($OpenThatDay as $key => $val) {
                    $keysOpenThatDay[] = $val;
                }
            }
        }

        $args = array(
            'post_type' => $type,
            'post_status' => 'publish',
            'posts_per_page' => $listingperpageMain,
            'paged' => $paged,
            's' => $squery,
            'post__not_in' => $ad_campaignsIDS,
            'tax_query' => $TxQuery,
            'post__in' => $keysOpenThatDay,
            'meta_query' => $priceQuery,
            'orderby' => $orderBy,
            'order' => $lporders,
        );


        //die(json_encode($_POST['formfields']));

        $lp_lat = '';
        $lp_lng = '';

        $my_query = null;
        $output = null;
        $result = null;
        $found = null;
        $my_query = new WP_Query($args);
        $found = $my_query->found_posts;
        $output .= '<div class="promoted-listings">';
        $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];
        if ($listing_mobile_view == 'app_view2' && wp_is_mobile()) {
            $output .= '<div class="app-view-new-ads-slider">';
        }
        ob_start();
        if (!empty(listingpro_get_campaigns_listing('lp_top_in_search_page_ads', true, $TxQuery, $searchQuery, false, $s = null, $noOFListing = null, $ad_campaignsIDS))) {
            $output .= '<p class="mp-archive-result-type"><i class="fa fa-info-circle" aria-hidden="true"></i>' . esc_html__("Sponsored", "medicalpro") . '</p>';
        }
        $output .= listingpro_get_campaigns_listing('lp_top_in_search_page_ads', false, $TxQuery, $searchQuery, false, $s = null, $noOFListing = null, $ad_campaignsIDS);
        $output .= ob_get_contents();
        ob_end_clean();
        ob_flush();
        if ($listing_mobile_view == 'app_view2' && wp_is_mobile()) {
            $output .= '</div>';
        }
        $output .= '</div>';
        if ($my_query->have_posts()) {
            $output .= '<p class="mp-archive-result-type">' . esc_html__("All Results", "medicalpro") . '</p>';
            while ($my_query->have_posts()):
                $my_query->the_post();

                $proceeditnow = true;


                /* ///////radious filter starts//////// */
                $flag = true;
                if ((isset($_POST['sloc_address']) && $_POST['sloc_address'] != '') || (!empty($clat) && !empty($clong))) {

                    if (!empty($clat) && !empty($clong)) {
                        $my_bounds_ne_lat = $clat;
                        $my_bounds_ne_lng = $clong;

                        $my_bounds_sw_lat = $clat;
                        $my_bounds_sw_lng = $clong;
                    }

                    $lp_lat = listing_get_metabox_by_ID('latitude', get_the_ID());
                    $lp_lng = listing_get_metabox_by_ID('longitude', get_the_ID());

                    $lp_my_distance_range = (int)$_POST['distance_range'];
                    if ($units == 'mil') {
                        $lp_my_distance_range = $lp_my_distance_range * 1.6023;
                    }
                    $ne_distance = haversineGreatCircleDistance($lp_lat, $lp_lng, $my_bounds_ne_lat, $my_bounds_ne_lng);
                    $sw_distance = haversineGreatCircleDistance($lp_lat, $lp_lng, $my_bounds_sw_lat, $my_bounds_sw_lng);

                    $flag1 = ($lp_my_distance_range >= $ne_distance || $lp_my_distance_range >= $sw_distance) ? true : false;
                    if (!$flag1) {
                        $flag = $flag1;
                    }

                    //$latlongfilter = true;
                }

                //Zoom Search
                $lp_data_zoom = (isset($_POST['data_zoom'])) ? $_POST['data_zoom'] : '';
                if (!$flag || $lp_data_zoom == 'yes') {
                    $flag2 = listingproc_inBounds($lp_lat, $lp_lng, $my_bounds_ne_lat, $my_bounds_ne_lng, $my_bounds_sw_lat, $my_bounds_sw_lng);
                    if (!$flag2) {
                        continue;
                    }
                }
                /* ///////radious filter end//////// */

                if ($listing_time == 'open') {
                    $openStatus = listingpro_check_time(get_the_ID(), true);
                    if ($openStatus == 'open') {

                        $this_lat = listing_get_metabox_by_ID('latitude', get_the_ID());
                        $this_long = listing_get_metabox_by_ID('longitude', get_the_ID());
                        if (!empty($clat) && !empty($clong)) {
                            if (!empty($this_lat) && !empty($this_long)) {
                                $latlongfilter = true;
                                $calDistance = GetDrivingDistance($clat, $this_lat, $clong, $this_long, $units);
                                if (!empty($calDistance['distance']) && !empty($proceeditnow)) {
                                    $latlongArray[get_the_ID()] = $calDistance['distance'];
                                }
                            }
                        }

                        if ($latlongfilter == false && !empty($proceeditnow)) {
                            $optenTimeArray[get_the_ID()] = get_the_ID();
                        }
                    }
                } else {
                    $this_lat = listing_get_metabox_by_ID('latitude', get_the_ID());
                    $this_long = listing_get_metabox_by_ID('longitude', get_the_ID());
                    if (!empty($clat) && !empty($clong)) {
                        if (!empty($this_lat) && !empty($this_long)) {
                            $latlongfilter = true;
                            $calDistance = GetDrivingDistance($clat, $this_lat, $clong, $this_long, $units);
                            if (!empty($calDistance['distance']) && !empty($proceeditnow)) {
                                $latlongArray[get_the_ID()] = $calDistance['distance'];
                            }
                        }
                    }
                    if ($latlongfilter == false) {
                        ob_start();
                        global $listingpro_options;
                        $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];
                        if ($listing_mobile_view == 'app_view' && wp_is_mobile() && !empty($proceeditnow)) {

                            mp_get_template_part('templates/loop/loop2');

                        } elseif ($listing_mobile_view == 'app_view2' && wp_is_mobile() && !empty($proceeditnow)) {

                            mp_get_template_part('templates/loop/loop2');

                        } else {
                            if (!empty($proceeditnow)) {

                                $viewStyle = 'list';
                                if (isset($_POST['list_style']) && !empty($_POST['list_style'])) {
                                    $viewStyle = $_POST['list_style'];
                                }


                                $listShowHide = 'show';
                                $gridShowHide = 'hide';
                                if ($viewStyle == 'list' && !wp_is_mobile()) {
                                    $listShowHide = 'show';
                                    $gridShowHide = 'hide';
                                } else {
                                    $listShowHide = 'hide';
                                    $gridShowHide = 'show';
                                }


                                ?>
                                <div class="md-list-view <?php echo $listShowHide; ?>">
                                    <?php
                                    include(MP_PLUGIN_PATH . "templates/loop/loop2-list.php");
                                    ?>
                                </div>


                                <div class="md-grid-view <?php echo $gridShowHide; ?>">
                                    <?php
                                    include(MP_PLUGIN_PATH . "templates/loop/loop2.php");
                                    ?>
                                </div>
                                <?php
                            }
                        }
                        $htmlOutput .= ob_get_contents();
                        ob_end_clean();
                        ob_flush();
                    }
                }
            endwhile;
            wp_reset_query();


            if ($latlongfilter == true) {
                $keysArrray = array();
                if (!empty($latlongArray)) {
                    asort($latlongArray);
                    foreach ($latlongArray as $key => $val) {
                        $keysArrray [] = $key;
                    }
                }


                $argss = array(
                    'post_type' => $type,
                    'posts_per_page' => $listingperpage,
                    'paged' => $paged,
                    'post__in' => $keysArrray,
                    'orderby' => 'post__in',
                    'order' => 'ASC'
                );
                $my_query = null;
                $my_query = new WP_Query($argss);
                $found = $my_query->found_posts;
                if ($my_query->have_posts()) {
                    $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];
                    if (($listing_mobile_view == 'app_view' || $listing_mobile_view == 'app_view2') && wp_is_mobile()) {
                        $htmlOutput .= '<div class="map-view-list-container">';
                        while ($my_query->have_posts()):
                            $my_query->the_post();
                            ob_start();
                            if ($listing_mobile_view == 'app_view2') {
                                get_template_part('mobile/listing-loop-app-view-new');
                            } else {
                                get_template_part('mobile/listing-loop-app-view');
                            }
                            $htmlOutput .= ob_get_contents();
                            ob_end_clean();
                        endwhile;
                        wp_reset_query();
                        $htmlOutput .= '</div>';
                    }

                    while ($my_query->have_posts()):

                        $my_query->the_post();
                        $this_lat = listing_get_metabox_by_ID('latitude', get_the_ID());
                        $this_long = listing_get_metabox_by_ID('longitude', get_the_ID());

                        $calDistance = GetDrivingDistance($clat, $this_lat, $clong, $this_long, $units);

                        if (isset($_POST['sloc_address']) && $_POST['sloc_address'] != '') {

                        } else {
                            if (!empty($calDistance['distance'])) {
                                $nearbydata = $calDistance['distance'] . ' ' . $units;
                                $htmlOutput .= '<div class="lp-nearby-dist-data" data-lpnearbydist = "' . $nearbydata . '"></div>';
                            }
                        }
                        ob_start();
                        global $listingpro_options;
                        $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];
                        if ($listing_mobile_view == 'app_view' && wp_is_mobile()) {
                            get_template_part('mobile/listing-loop-app-view');
                        } elseif ($listing_mobile_view == 'app_view2' && wp_is_mobile()) {
                            get_template_part('mobile/listing-loop-app-view2');
                        } else {
                            mp_get_template_part('templates/loop/loop2-list');
                        }
                        $htmlOutput .= ob_get_contents();
                        ob_end_clean();
                        ob_flush();
                        if (!empty($calDistance['distance'])) {
                            //$htmlOutput.='</div>';
                        }

                    endwhile;
                    wp_reset_query();
                }
            }
            if (!empty($optenTimeArray)) {
                $keysArrray = array();
                if (!empty($optenTimeArray)) {
                    asort($optenTimeArray);
                    foreach ($optenTimeArray as $key => $val) {
                        $keysArrray [] = $key;
                    }
                }


                $argss = array(
                    'post_type' => $type,
                    'posts_per_page' => $listingperpage,
                    'paged' => $paged,
                    'post__in' => $keysArrray,
                    'orderby' => 'post__in',
                    'order' => 'ASC'
                );
                $my_query = null;
                $my_query = new WP_Query($argss);
                $found = $my_query->found_posts;
                if ($my_query->have_posts()) {
                    $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];
                    if (($listing_mobile_view == 'app_view' || $listing_mobile_view == 'app_view2') && wp_is_mobile()) {
                        $htmlOutput .= '<div class="map-view-list-container">';
                        while ($my_query->have_posts()):
                            $my_query->the_post();
                            ob_start();
                            global $listingpro_options;
                            $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];
                            if ($listing_mobile_view == 'app_view' && wp_is_mobile()) {
                                get_template_part('mobile/listing-loop-app-view');
                            } elseif ($listing_mobile_view == 'app_view2' && wp_is_mobile()) {
                                get_template_part('mobile/listing-loop-app-view-new');
                            } else {
                                include(MP_PLUGIN_PATH . "templates/loop/loop2.php");
                            }
                            $htmlOutput .= ob_get_contents();
                            ob_end_clean();
                        endwhile;
                        wp_reset_query();
                        $htmlOutput .= '</div>';
                    }

                    while ($my_query->have_posts()):

                        $my_query->the_post();

                        ob_start();
                        global $listingpro_options;
                        $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];
                        if ($listing_mobile_view == 'app_view' && wp_is_mobile()) {
                            get_template_part('mobile/listing-loop-app-view');
                        } elseif ($listing_mobile_view == 'app_view2' && wp_is_mobile()) {
                            get_template_part('mobile/listing-loop-app-view-new');
                        } else {
                            include(MP_PLUGIN_PATH . "templates/loop/loop2.php");
                        }
                        $htmlOutput .= ob_get_contents();
                        ob_end_clean();
                        ob_flush();


                    endwhile;
                    wp_reset_query();
                }
            }

            if (empty($htmlOutput)) {

                $output .= '


							<div class="text-center margin-top-80 margin-bottom-80">

                                <img src="' . MP_PLUGIN_DIR . 'assets/images/temp/looking.svg" class="no-result-avaliable-img">
								<h2 class="no-result-avaliable-heading">' . esc_html__('No Results', 'medicalpro') . '</h2>


								<p class="no-result-avaliable-desc">' . esc_html__('Sorry! There are no listings matching your search.', 'medicalpro') . '</p>


								<p class="no-result-avaliable-desc">' . esc_html__('Try changing your search filters or ', 'medicalpro') . '<a href="' . $currentURL . '">' . esc_html__('Reset Filter', 'medicalpro') . '</a></p>


							</div>


							';
            } else {
                $output .= $htmlOutput;
            }
        } elseif (empty($ad_campaignsIDS)) {
            $output .= '


                            <div class="text-center margin-top-80 margin-bottom-80">

                                <img src="' . MP_PLUGIN_DIR . 'assets/images/temp/looking.svg" class="no-result-avaliable-img">
								<h2 class="no-result-avaliable-heading">' . esc_html__('No Results', 'medicalpro') . '</h2>


								<p class="no-result-avaliable-desc">' . esc_html__('Sorry! There are no listings matching your search.', 'medicalpro') . '</p>


								<p class="no-result-avaliable-desc">' . esc_html__('Try changing your search filters or ', 'medicalpro') . '<a href="' . $currentURL . '">' . esc_html__('Reset Filter', 'medicalpro') . '</a></p>


							</div>


							';
        }
        if (($found > 0)) {
            $foundtext = 'Results';
        } else {
            $foundtext = 'Result';
        }
        if (!empty($htmlOutput)) {
            $output .= listingpro_load_more_filter($my_query, $pageno, $defSquery);
        }
        $output = utf8_encode($output);

        $showingResult = null;
        if (!empty($pageno)) {
            $fromPosts = (($pageno - 1) * $listingperpage) + 1;
        } else {
            $fromPosts = 1;
        }
        if (!empty($pageno)) {
            $toPosts = $listingperpage * $pageno;
        } else {
            $toPosts = $listingperpage;
        }
        if ($found == 0) {
            $showingResult = esc_html__('No Result Found', 'medicalpro');
        } else {
            if ($toPosts > $found) {
                $toPosts = $found;
            }
            $showingResult = esc_html__('Showing', 'medicalpro') . ' ' . $fromPosts . '-' . $toPosts . ' ' . esc_html__('of', 'medicalpro') . ' ' . $found;
        }

        $term_group_result = json_encode(array(
            "foundtext" => $foundtext,
            "found" => $found,
            "tags" => $info['tag_name'],
            "cat" => $categoryName,
            "city" => $LocationName,
            "html" => $output,
            "opentime" => $listing_time,
            "dfdfdfdf" => $latlongArray,
            "latlongfilter" => $latlongfilter,
            "opentimefilter" => $opentimefilter,
            "searchtitles" => $searchtitles,
            "showingString" => $showingResult,
        ));
        die($term_group_result);
    }
}


if (!function_exists('medicalpro_get_lat_long_from_address')) {
    function medicalpro_get_lat_long_from_address($address, $listing_id)
    {
        $mapkey = lp_theme_option('google_map_api');

        $response = array();
        if (!empty($address) && !empty($listing_id)) {
            $address = urlencode($address);

            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&key=" . $mapkey;
            global $wp_filesystem;
            if (empty($wp_filesystem)) {
                require_once(ABSPATH . '/wp-admin/includes/file.php');
                WP_Filesystem();
            }
            $resp = json_decode($wp_filesystem->get_contents($url), true);
            if ($resp['status'] === 'OK') {
                $formatted_address = ($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : '';
                $lat = ($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : '';
                $long = ($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : '';
                $response['lat'] = $lat;
                $response['lng'] = $long;
            }

        }
        return $response;
    }
}


add_filter('lp_header_signin_button_ui', 'lp_header_signin_button_ui_callback', 30, 1);
if (!function_exists('lp_header_signin_button_ui_callback')) {
    function lp_header_signin_button_ui_callback($button_ui = '')
    {
        global $listingpro_options;

        $popup_style = $listingpro_options['login_popup_style'];
        ob_start();
        if ($popup_style == 'style2') { ?>
            <a class="app-view-popup-style" data-target="#app-view-login-popup"><i class="fa fa-user"
                                                                                   aria-hidden="true"></i> <?php esc_html_e('Sign In', 'medicalpro'); ?>
            </a>
        <?php } else { ?>
            <a class="md-trigger" data-modal="modal-3"><i class="fa fa-user"
                                                          aria-hidden="true"></i> <?php esc_html_e('Sign In', 'medicalpro'); ?>
            </a>
            <?php
        }
        $signin_btn = ob_get_contents();
        ob_end_clean();
        return $signin_btn;
    }
}
if (!function_exists('create_mp_listing_extra_feild_meta')) {
    function create_mp_listing_extra_feild_meta()
    {
        $virtual_consult = Array(
            'name' => esc_html__('Video Consultation', 'medicalpro'),
            'id' => 'virtual_consult',
            'type' => 'check',
            'desc' => ''
        );
        $certified_doctor = Array(
            'name' => esc_html__('Certified Doctor', 'medicalpro'),
            'id' => 'certified_doctor',
            'type' => 'check',
            'desc' => ''
        );
        $online_prescription = Array(
            'name' => esc_html__('Online Prescription', 'medicalpro'),
            'id' => 'online_prescription',
            'type' => 'check',
            'desc' => ''

        );
        $taking_new_patient = Array(
            'name' => esc_html__('Taking New Patients', 'medicalpro'),
            'id' => 'taking_new_patient',
            'type' => 'check',
            'desc' => ''
        );
        $mplistingextrafields = Array(
            $virtual_consult,
            $certified_doctor,
            $online_prescription,
            $taking_new_patient
        );
        foreach ($mplistingextrafields as $k => $field) {
            $metabox = null;
            if ($field['id'] == 'virtual_consult') {
                $metabox = $virtual_consult;
            } elseif ($field['id'] == 'certified_doctor') {
                $metabox = $certified_doctor;
            } elseif ($field['id'] == 'online_prescription') {
                $metabox = $online_prescription;
            } elseif ($field['id'] == 'taking_new_patient') {
                $metabox = $taking_new_patient;
            }

            add_meta_box('mp_listing_extra_fields_' . $field['id'], esc_html__($field['name'], 'medicalpro'), 'mp_listing_extra_fields_render', 'listing', 'normal', 'high', array($metabox));
        }
    }
}
add_action('add_meta_boxes', 'create_mp_listing_extra_feild_meta');
if (!function_exists('mp_listing_extra_fields_render')) {
    function mp_listing_extra_fields_render($post, $metabox)
    {
        global $post;
        ?>
        <input type="hidden" name="mp_meta_box_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>"/>
        <table class="form-table lp-metaboxes">
            <tbody>
            <?php
            foreach ($metabox['args'] as $settings) {
                $options = get_post_meta($post->ID, 'mp_listing_extra_fields_' . $settings['id'], true);
                $settings['value'] = isset($options) ? $options : (isset($settings['std']) ? $settings['std'] : '');
                call_user_func('settings_' . $settings['type'], $settings);
            }
            ?>
            </tbody>
        </table>
        <?php
    }
}
add_action('save_post', 'savemplistingsextrafields');
if (!function_exists('savemplistingsextrafields')) {
    function savemplistingsextrafields($post_id)
    {
        if (!isset($_GET['action'])) {
            return $post_id;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        $virtual_consult = Array(
            'name' => esc_html__('Video Consultation', 'medicalpro'),
            'id' => 'virtual_consult',
            'type' => 'check',
            'desc' => ''
        );
        $certified_doctor = Array(
            'name' => esc_html__('Certified Doctor', 'medicalpro'),
            'id' => 'certified_doctor',
            'type' => 'check',
            'desc' => ''
        );
        $online_prescription = Array(
            'name' => esc_html__('Online Prescription', 'medicalpro'),
            'id' => 'online_prescription',
            'type' => 'check',
            'desc' => ''

        );
        $taking_new_patient = Array(
            'name' => esc_html__('Taking New Patients', 'medicalpro'),
            'id' => 'taking_new_patient',
            'type' => 'check',
            'desc' => ''
        );
        $mplistingextrafields = Array(
            $virtual_consult,
            $certified_doctor,
            $online_prescription,
            $taking_new_patient
        );
        $meta = null;
        foreach ($mplistingextrafields as $k => $field) {
            $meta = 'mp_listing_extra_fields_' . $field['id'];
            if ($_POST['post_type'] == 'listing') {
                $metabox2 = null;
                if ($field['id'] == 'virtual_consult') {
                    $metabox2 = $virtual_consult;
                } elseif ($field['id'] == 'certified_doctor') {
                    $metabox2 = $certified_doctor;
                } elseif ($field['id'] == 'online_prescription') {
                    $metabox2 = $online_prescription;
                } elseif ($field['id'] == 'taking_new_patient') {
                    $metabox2 = $taking_new_patient;
                }
                $metaboxes_reviews = array($metabox2);
                if (!empty($metaboxes_reviews)) {
                    $myMeta = null;
                    foreach ($metaboxes_reviews as $metabox) {
                        $myMeta = isset($_POST[$metabox['id']]) ? $_POST[$metabox['id']] : "";
                    }
                    update_post_meta($post_id, $meta, $myMeta);
                }
            }
        }
        return true;
    }
}
if (!function_exists('mp_get_listing_status')) {
    function mp_get_listing_status($listing_id)
    {
        $return = 'closed';
        global $post, $listingpro_options;
        $listing_hospitals = wp_get_post_terms($listing_id, 'medicalpro-hospital');
        $listing_hospitals_data = get_post_meta($listing_id, 'medicalpro_listing_hospitals', true);
        if (isset($listing_hospitals) && !empty($listing_hospitals)) {
            $counter = 0;
            foreach ($listing_hospitals as $listing_hospital) {
                $counter++;
                $business_hours = isset($listing_hospitals_data[$listing_hospital->term_id]['business_hours']) ? $listing_hospitals_data[$listing_hospital->term_id]['business_hours'] : '';
                $days_arr = medicalpro_business_working_days();
                $current_day = current_time('l');
                if (!empty($business_hours[$current_day])) {
                    return 'opened';
                } else {
                    $current_day = date_i18n('l', strtotime(' +1 day'));
                    if (!empty($business_hours[$current_day])) {
                        return 'opened_next';
                    }
                    $return = 'closed';
                }
            }
        } else {
            $return = 'closed';
        }
        return $return;
    }
}
if (!function_exists('mp_get_listing_open_status')) {
    function mp_get_listing_open_status($listing_id, $date)
    {
        $return = 'closed';
        global $post, $listingpro_options;
        $listing_hospitals = wp_get_post_terms($listing_id, 'medicalpro-hospital');
        $listing_hospitals_data = get_post_meta($listing_id, 'medicalpro_listing_hospitals', true);
        if (isset($listing_hospitals) && !empty($listing_hospitals)) {
            $counter = 0;
            foreach ($listing_hospitals as $listing_hospital) {
                $counter++;
                $business_hours = isset($listing_hospitals_data[$listing_hospital->term_id]['business_hours']) ? $listing_hospitals_data[$listing_hospital->term_id]['business_hours'] : '';
                $days_arr = medicalpro_business_working_days();
                $current_day = date_i18n('l', strtotime($date));
                if (!empty($business_hours[$current_day])) {
                    return 'opened';
                } else {
                    $return = 'closed';
                }
            }
        } else {
            $return = 'closed';
        }
        return $return;
    }
}
if (!function_exists('mp_pagination')) {
    function mp_pagination($wp_query, $pages = '', $range = 2)
    {
        $showitems = ($range * 2) + 1;
        global $paged;
        if (empty($paged)) $paged = 1;
        if ($pages == '') {
            if (empty($wp_query)) {
                global $wp_query;
            }
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }
        if (1 != $pages) {
            echo "<div class='pagination'>";
            if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) echo "<a href='" . get_pagenum_link(1) . "'>&laquo;</a>";
            if ($paged > 1 && $showitems < $pages) echo "<a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo;</a>";
            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                    echo ($paged == $i) ? "<span class='current'>" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class='inactive' >" . $i . "</a>";
                }
            }
            if ($paged < $pages && $showitems < $pages) echo "<a href='" . get_pagenum_link($paged + 1) . "'>&rsaquo;</a>";
            if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) echo "<a href='" . get_pagenum_link($pages) . "'>&raquo;</a>";
            echo "</div>\n";
        }
    }
}

if (!function_exists('mp_SQL_pagination')) {
    function mp_SQL_pagination($maxPostsPerPage = 0, $totalPosts = 0, $paged = 1, $startFrom = 0, $range = 999999999999)
    {
        $showitems = ($range * 2) + 1;
        $pages = ceil($totalPosts / $maxPostsPerPage);
        $return = null;
        if (1 != $pages) {
            $return .= "<div class='lp_mp_earnings_dashboard_withdrawal_history_pagination'>
                <div class='pagination'>";
            // if($paged > 2 && $paged > $range+1 && $showitems < $pages) $return .= "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
            // if ($paged > 1 && $showitems < $pages) $return .= "<a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo;</a>";
            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                    $return .= ($paged == $i) ? "<span class='current'>" . $i . "</span>" : "<a href='' class='inactive' >" . $i . "</a>";
                }
            }
            // if ($paged < $pages && $showitems < $pages) $return .= "<a href='" . get_pagenum_link($paged + 1) . "'>&rsaquo;</a>";
            // if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) $return .= "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
            $return .= "</div>
            </div>";
        }

        return $return;
    }
}

if (!function_exists('mp_get_user_wallet_data')) {
    function mp_get_user_wallet_data($userID)
    {
        global $wpdb, $listingpro_options;
        $currencySymbol = listingpro_currency_sign();
        $table = $wpdb->prefix . 'booking_orders';
        $orders = array();
        $Porders = array();
        $orderCount = 0;
        $startFrom = 0;
        $QpostPerPage = 3;
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
            $query = "";
            $query = "SELECT * from $table WHERE doctor_id = $userID ORDER BY main_id DESC";
            $orders = $wpdb->get_results($query);
            $orderCount = count($orders);
        }
        $countWithdrawals = 0;
        $args = array(
            'post_type' => 'lp-withdrawal',
            'author' => $userID,
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => 'lp_withdrawal_requested_status',
                    'value' => 'paid',
                    'compare' => '='
                )
            )
        );
        $the_query = new WP_Query($args);
        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $value = get_post_meta(get_the_ID(), 'lp_withdrawal_requested_amount', true);
                if (!empty($value) && is_numeric($value)) {
                    $countWithdrawals = $countWithdrawals + $value;
                }
            }
        }
        $totalEarnings = 0;
        if (!empty($orders) && is_array($orders)) {
            foreach ($orders as $key => $value) {
                if (!empty($value->sub_total) && is_numeric($value->sub_total)) {
                    $totalEarnings = $totalEarnings + $value->sub_total;
                }
            }
        }
        $availableBalance = 0;
        if (is_numeric($totalEarnings) && is_numeric($countWithdrawals)) {
            $availableBalance = $totalEarnings - $countWithdrawals;
        }
        return array(
            'countWithdrawals' => $countWithdrawals,
            'totalEarnings' => $totalEarnings,
            'availableBalance' => $availableBalance
        );
    }
}
if (!function_exists('medicalpro_currency_sign')) {
    function medicalpro_currency_sign($currency_code = null)
    {
        global $listingpro_options;
        if ($currency_code == null) {
            $currency_code = $listingpro_options['currency_paid_submission'];
        }
        $currencycode = null;
        if ($currency_code == "USD") {
            $currencycode = "$";
        } elseif ($currency_code == "BDT") {
            $currencycode = "৳";
        } elseif ($currency_code == "TTD") {
            $currencycode = "TT$";
        } elseif ($currency_code == "AUD") {
            $currencycode = "$";
        } elseif ($currency_code == "AED") {
            $currencycode = "د.إ";
        } elseif ($currency_code == "CAD") {
            $currencycode = "$";
        } elseif ($currency_code == "CZK") {
            $currencycode = "K�?";
        } elseif ($currency_code == "DKK") {
            $currencycode = "kr";
        } elseif ($currency_code == "EUR") {
            $currencycode = "€";
        } elseif ($currency_code == "EGP") {
            $currencycode = "E£";
        } elseif ($currency_code == "HKD") {
            $currencycode = "$";
        } elseif ($currency_code == "HUF") {
            $currencycode = "Ft";
        } elseif ($currency_code == "JPY") {
            $currencycode = "¥";
        } elseif ($currency_code == "NOK") {
            $currencycode = "kr";
        } elseif ($currency_code == "NZD") {
            $currencycode = "$";
        } elseif ($currency_code == "PLN") {
            $currencycode = "zł";
        } elseif ($currency_code == "GBP") {
            $currencycode = "£";
        } elseif ($currency_code == "SEK") {
            $currencycode = "kr";
        } elseif ($currency_code == "SGD") {
            $currencycode = "$";
        } elseif ($currency_code == "CHF") {
            $currencycode = "CHF";
        } elseif ($currency_code == "BRL") {
            $currencycode = "R$";
        } elseif ($currency_code == "IDR") {
            $currencycode = "Rp";
        } elseif ($currency_code == "ILS") {
            $currencycode = "₪";
        } elseif ($currency_code == "INR") {
            $currencycode = "INR";
        } elseif ($currency_code == "KOR") {
            $currencycode = "₩";
        } elseif ($currency_code == "KSH") {
            $currencycode = "KSh";
        } elseif ($currency_code == "MYR") {
            $currencycode = "RM";
        } elseif ($currency_code == "MXN") {
            $currencycode = "$";
        } elseif ($currency_code == "PHP") {
            $currencycode = "₱";
        } elseif ($currency_code == "TWD") {
            $currencycode = "NT$";
        } elseif ($currency_code == "THB") {
            $currencycode = "฿";
        } elseif ($currency_code == "VND") {
            $currencycode = "₫";
        } elseif ($currency_code == "ALL") {
            $currencycode = "Lek";
        } elseif ($currency_code == "AFN") {
            $currencycode = "؋";
        } elseif ($currency_code == "ARS") {
            $currencycode = "$";
        } elseif ($currency_code == "AWG") {
            $currencycode = "ƒ";
        } elseif ($currency_code == "AZN") {
            $currencycode = "ман";
        } elseif ($currency_code == "BYN") {
            $currencycode = "Br";
        } elseif ($currency_code == "BZD") {
            $currencycode = "BZ$";
        } elseif ($currency_code == "BMD") {
            $currencycode = "$";
        } elseif ($currency_code == "BOB") {
            $currencycode = "$b";
        } elseif ($currency_code == "BAM") {
            $currencycode = "KM";
        } elseif ($currency_code == "BWP") {
            $currencycode = "P";
        } elseif ($currency_code == "BGN") {
            $currencycode = "лв";
        } elseif ($currency_code == "BRL") {
            $currencycode = "R$";
        } elseif ($currency_code == "BND") {
            $currencycode = "BND";
        } elseif ($currency_code == "KHR") {
            $currencycode = "KHR";
        } elseif ($currency_code == "KYD") {
            $currencycode = "$";
        } elseif ($currency_code == "CLP") {
            $currencycode = "$";
        } elseif ($currency_code == "CNY") {
            $currencycode = "¥";
        } elseif ($currency_code == "COP") {
            $currencycode = "$";
        } elseif ($currency_code == "CRC") {
            $currencycode = "₡";
        } elseif ($currency_code == "HRK") {
            $currencycode = "kn";
        } elseif ($currency_code == "CUP") {
            $currencycode = "₱";
        } elseif ($currency_code == "DOP") {
            $currencycode = "RD$";
        } elseif ($currency_code == "XCD") {
            $currencycode = "$";
        } elseif ($currency_code == "EGP") {
            $currencycode = "£";
        } elseif ($currency_code == "SVC") {
            $currencycode = "$";
        } elseif ($currency_code == "FKP") {
            $currencycode = "£";
        } elseif ($currency_code == "FJD") {
            $currencycode = "$";
        } elseif ($currency_code == "GHS") {
            $currencycode = "GH₵";
        } elseif ($currency_code == "GIP") {
            $currencycode = "£";
        } elseif ($currency_code == "GTQ") {
            $currencycode = "Q";
        } elseif ($currency_code == "GGP") {
            $currencycode = "£";
        } elseif ($currency_code == "GYD") {
            $currencycode = "$";
        } elseif ($currency_code == "HNL") {
            $currencycode = "L";
        } elseif ($currency_code == "IMP") {
            $currencycode = "£";
        } elseif ($currency_code == "JEP") {
            $currencycode = "£";
        } elseif ($currency_code == "KZT") {
            $currencycode = "лв";
        } elseif ($currency_code == "KPW") {
            $currencycode = "₩";
        } elseif ($currency_code == "KRW") {
            $currencycode = "₩";
        } elseif ($currency_code == "KGS") {
            $currencycode = "лв";
        } elseif ($currency_code == "LAK") {
            $currencycode = "₭";
        } elseif ($currency_code == "LBP") {
            $currencycode = "£";
        } elseif ($currency_code == "LRD") {
            $currencycode = "$";
        } elseif ($currency_code == "MKD") {
            $currencycode = "ден";
        } elseif ($currency_code == "MUR") {
            $currencycode = "₨";
        } elseif ($currency_code == "MXN") {
            $currencycode = "$";
        } elseif ($currency_code == "MNT") {
            $currencycode = "₮";
        } elseif ($currency_code == "MZN") {
            $currencycode = "MT";
        } elseif ($currency_code == "NAD") {
            $currencycode = "$";
        } elseif ($currency_code == "NPR") {
            $currencycode = "₨";
        } elseif ($currency_code == "ANG") {
            $currencycode = "ƒ";
        } elseif ($currency_code == "NIO") {
            $currencycode = "C$";
        } elseif ($currency_code == "NGN") {
            $currencycode = "₦";
        } elseif ($currency_code == "NOK") {
            $currencycode = "kr";
        } elseif ($currency_code == "OMR") {
            $currencycode = "﷼";
        } elseif ($currency_code == "PKR") {
            $currencycode = "₨";
        } elseif ($currency_code == "PAB") {
            $currencycode = "B/.";
        } elseif ($currency_code == "PYG") {
            $currencycode = "Gs";
        } elseif ($currency_code == "PEN") {
            $currencycode = "S/.";
        } elseif ($currency_code == "QAR") {
            $currencycode = "﷼";
        } elseif ($currency_code == "RON") {
            $currencycode = "lei";
        } elseif ($currency_code == "RUB") {
            $currencycode = "₽";
        } elseif ($currency_code == "SHP") {
            $currencycode = "£";
        } elseif ($currency_code == "SAR") {
            $currencycode = "﷼";
        } elseif ($currency_code == "RSD") {
            $currencycode = "Дин.";
        } elseif ($currency_code == "SCR") {
            $currencycode = "₨";
        } elseif ($currency_code == "SGD") {
            $currencycode = "$";
        } elseif ($currency_code == "SBD") {
            $currencycode = "$";
        } elseif ($currency_code == "SOS") {
            $currencycode = "S";
        } elseif ($currency_code == "ZAR") {
            $currencycode = "R";
        } elseif ($currency_code == "LKR") {
            $currencycode = "₨";
        } elseif ($currency_code == "SRD") {
            $currencycode = "$";
        } elseif ($currency_code == "SYP") {
            $currencycode = "£";
        } elseif ($currency_code == "TTD") {
            $currencycode = "TT$";
        } elseif ($currency_code == "TVD") {
            $currencycode = "$";
        } elseif ($currency_code == "UAH") {
            $currencycode = "₴";
        } elseif ($currency_code == "UYU") {
            $currencycode = "$U";
        } elseif ($currency_code == "UZS") {
            $currencycode = "лв";
        } elseif ($currency_code == "VEF") {
            $currencycode = "Bs";
        } elseif ($currency_code == "VND") {
            $currencycode = "₫";
        } elseif ($currency_code == "YER") {
            $currencycode = "﷼";
        } elseif ($currency_code == "ZWD") {
            $currencycode = "Z$";
        } elseif ($currency_code == "TRY") {
            $currencycode = "&#8378;";
        }

        return $currencycode;
    }
}


add_action('wp', 'medpro_send_booking_room_url_wp');
if (!function_exists('medpro_send_booking_room_url_wp')) {
    function medpro_send_booking_room_url_wp()
    {
        if (!wp_next_scheduled('medpro_send_booking_room_url_hook')) {
            $timestamp = time();
            wp_schedule_event($timestamp, 'hourly', 'medpro_send_booking_room_url_hook');
        }
    }
}
add_action('medpro_send_booking_room_url_hook', 'medpro_send_booking_room_url');
if (!function_exists('medpro_send_booking_room_url')) {
    function medpro_send_booking_room_url()
    {
        $args = array(
            'post_type' => 'medicalpro-bookings',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'mp_room_email_sent',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => 'booking_type',
                    'value' => 'video-consultation',
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'booking_status',
                    'value' => 'APPROVED',
                    'compare' => 'LIKE'
                ),
            ),
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $booking_id = get_the_ID();
                $patient = get_post_meta($booking_id, 'booking_user_id', true);
                $patientEmail = get_post_meta($booking_id, 'booking_email', true);
                $patientFNAME = get_post_meta($booking_id, 'booking_fname', true);
                $patientLNAME = get_post_meta($booking_id, 'booking_lname', true);
                $listing_id = get_post_meta($booking_id, 'booking_listing_id', true);
                $booking_date = (int)get_post_meta($booking_id, 'booking_date', true);
                $booking_start = (int)get_post_meta($booking_id, 'booking_slot_start_time', true);
                $booking_end = (int)get_post_meta($booking_id, 'booking_slot_end_time', true);
                $booking_hospital_id = get_post_meta($booking_id, 'booking_hospital_id', true);
                $hospital_term = get_term_by('id', $booking_hospital_id, 'medicalpro-hospital');
                $booking_status = get_post_meta($booking_id, 'booking_status', true);
                $val = date('Y-m-d', $booking_date);
                $val1 = date('H:i', $booking_start);
                $datetime1 = new DateTime('now');
                $datetime2 = new DateTime($val . ' ' . $val1);
                // if($datetime1 < $datetime2){
                $diff = $datetime1->diff($datetime2);
                if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h <= 24) {
                    if (isset($patientEmail) && !empty($patientEmail)) {
                        global $listingpro_options;
                        $mail_subject = $listingpro_options['mp_send_booking_room_subject'];
                        $mail_content = $listingpro_options['mp_send_booking_room_content'];
                        $userdata = get_userdata($patient);
                        $website_url = site_url();
                        $website_name = get_option('blogname');
                        $current_user = get_userdata($patient);
                        $user_name = isset($current_user->display_name) ? $current_user->display_name : '';
                        $formated_mail_subject = lp_sprintf2($mail_subject, array(
                            'website_url' => $website_url,
                            'website_name' => $website_name,
                            'user_name' => $user_name,
                        ));
                        $room_url = listing_get_metabox_by_ID('videoconsult', $listing_id);
                        if (empty($room_url)) $room_url = esc_html__('No Room Url Found Please Contact Listing Owner For Your Appointment Room Url.', 'medicalpro');
                        $formated_mail_content = lp_sprintf2($mail_content,
                            array(
                                'room_url' => $room_url,
                                'website_url' => $website_url,
                                'website_name' => $website_name,
                                'user_name' => $user_name,
                                'listing_title' => esc_html(get_the_title($listing_id)),
                                'listing_url' => esc_url(get_permalink($listing_id)),
                                'hospital_name' => isset($hospital_term->name) ? esc_html($hospital_term->name) : '',
                                'hospital_url' => isset($hospital_term->term_id) ? esc_url(get_term_link($hospital_term->term_id)) : '',
                                'appointment_date' => $val,
                                'appointment_time' => $val1,
                                'appointment_status' => $booking_status,
                                'listing_author_name' => isset($listing_user_data->display_name) ? $listing_user_data->display_name : '',
                                'listing_author_url' => isset($listing_user_data->ID) ? get_author_posts_url($listing_user_data->ID) : '',
                                'booker_name' => isset($booking_user_data->display_name) ? $booking_user_data->display_name : '',
                                'booker_url' => isset($booking_user_data->ID) ? get_author_posts_url($booking_user_data->ID) : '',
                                'approved_date' => current_time(get_option('date_format')),
                                'cancelled_date' => current_time(get_option('date_format')),
                            )
                        );

                        lp_mail_headers_append();
                        $headers1[] = 'Content-Type: text/html; charset=UTF-8';
                        wp_mail($patientEmail, $formated_mail_subject, $formated_mail_content, $headers1);
                        lp_mail_headers_remove();
                        update_post_meta($booking_id, 'mp_room_email_sent', date('Y-m-d H:i'));
                    }
                }
                // }
            }
        }
        wp_reset_postdata();
    }
}


//Hospitals Suggestions
if (!function_exists('mp_suggested_hospital_menu_page')) {
    function mp_suggested_hospital_menu_page()
    {
        $counter = 0;
        $args = array(
            'post_type' => 'listing',
            'meta_query' => array(
                array(
                    'key' => 'mp_suggested_hospitals',
                    'compare' => 'EXISTS',
                ),
            ),
        );
        $the_query = new WP_Query($args);
        if ($the_query->have_posts()) :
            while ($the_query->have_posts()) :
                $the_query->the_post();
                $request = get_post_meta(get_the_ID(), 'mp_suggested_hospitals', true);
                if (is_array($request)) :
                    foreach ($request as $k => $single):
                        if ($single['viewed'] !== true) {
                            $counter++;
                        }
                    endforeach;
                endif;
            endwhile;
            wp_reset_postdata();
        endif;
        $class = null;
        if ($counter >= 1) $class = 'mp_suggested_have_result';
        add_menu_page(
            'Suggested Hospitals',
            '<span class="' . $class . '">Suggested Hospitals</span>',
            'manage_options',
            'mp_suggested_hospital',
            'mp_suggested_hospital_callback',
            '',
            25
        );
    }
}
add_action('admin_menu', 'mp_suggested_hospital_menu_page');
if (!function_exists('mp_suggested_hospital_callback')) {
    function mp_suggested_hospital_callback()
    {
        require_once MP_PLUGIN_PATH . "/include/suggestedhospitals.php";
    }
}
//Pricing Plans Metaboxes Overide
if (!function_exists('plan_contact_content')) {
    function plan_contact_content($post)
    {
        ?>
        <div style="border-bottom: 1px solid #222;padding: 10px 0px; margin-bottom:30px">
            <div style="width:100%;float:left">
                <?php
                echo '<label class="switch">';
                echo '<input type="checkbox" id="bulk_enable_price_options" value="';
                echo wp_kses_post('Enable All Fields');
                echo '">';
                echo '<span class="slider round"></span>';
                echo '</label>';
                echo __('<label for="bulk_enable_price_options"><b>Enable All Following</b></label>', 'listingpro-plugin');
                ?>
            </div>
            <br clear="all"/>
        </div>
        <!--MedicalPro-->
        <div style="border-bottom: 1px solid #ccc;padding: 10px 0px;">
            <div style="width:60%;float:left">
                <?php
                $insurances_show = get_post_meta($post->ID, 'insurances_show', true);
                $checked = '';
                if ($insurances_show == 'true') {
                    $checked = 'checked';
                }
                echo '<label class="switch">';
                echo '<input ' . $checked . ' type="checkbox" id="insurances_show" name="insurances_show" value="';
                echo wp_kses_post($insurances_show);
                echo '">';
                echo '<span class="slider round"></span>';
                echo '</label>';
                echo __('<label for="insurances_show"><b>Insurances</b></label>', 'listingpro-plugin');
                $checked = get_post_meta($post->ID, 'insurances_show_hide', 'true');
                if (empty($checked)) {
                    $checked = 'checked';
                }
                ?>
            </div>
            <div style="width:40%;float:left">
                <?php
                echo '<label class="switch">';
                echo ' <input type="checkbox" id="insurances_show_hide" name="insurances_show_hide" ' . $checked . '  />';
                echo '<span class="slider round slider2"></span>';
                echo '</label>';
                ?>
            </div>
            <br clear="all"/>
        </div>
        <div style="border-bottom: 1px solid #ccc;padding: 10px 0px;">
            <div style="width:60%;float:left">
                <?php
                $awards_show = get_post_meta($post->ID, 'awards_show', true);
                $checked = '';
                if ($awards_show == 'true') {
                    $checked = 'checked';
                }
                echo '<label class="switch">';
                echo '<input ' . $checked . ' type="checkbox" id="awards_show" name="awards_show" value="';
                echo wp_kses_post($awards_show);
                echo '">';
                echo '<span class="slider round"></span>';
                echo '</label>';
                echo __('<label for="awards_show"><b>Awards</b></label>', 'listingpro-plugin');
                $checked = get_post_meta($post->ID, 'awards_show_hide', 'true');
                if (empty($checked)) {
                    $checked = 'checked';
                }
                ?>
            </div>
            <div style="width:40%;float:left">
                <?php
                echo '<label class="switch">';
                echo ' <input type="checkbox" id="awards_show_hide" name="awards_show_hide" ' . $checked . '  />';
                echo '<span class="slider round slider2"></span>';
                echo '</label>';
                ?>
            </div>
            <br clear="all"/>
        </div>
        <div style="border-bottom: 1px solid #ccc;padding: 10px 0px;">
            <div style="width:60%;float:left">
                <?php
                $video_consult_show = get_post_meta($post->ID, 'video_consult_show', true);
                $checked = '';
                if ($video_consult_show == 'true') {
                    $checked = 'checked';
                }
                echo '<label class="switch">';
                echo '<input ' . $checked . ' type="checkbox" id="video_consult_show" name="video_consult_show" value="';
                echo wp_kses_post($video_consult_show);
                echo '">';
                echo '<span class="slider round"></span>';
                echo '</label>';
                echo __('<label for="video_consult_show"><b>Video Consultation</b></label>', 'listingpro-plugin');
                $checked = get_post_meta($post->ID, 'video_consult_show_hide', 'true');
                if (empty($checked)) {
                    $checked = 'checked';
                }
                ?>
            </div>
            <div style="width:40%;float:left">
                <?php
                echo '<label class="switch">';
                echo ' <input type="checkbox" id="video_consult_show_hide" name="video_consult_show_hide" ' . $checked . '  />';
                echo '<span class="slider round slider2"></span>';
                echo '</label>';
                ?>
            </div>
            <br clear="all"/>
        </div>
        <!--MedicalPro-->
        <div style="border-bottom: 1px solid #ccc;padding: 10px 0px;">
            <div style="width:60%;float:left">
                <?php
                $gallery_show = get_post_meta($post->ID, 'gallery_show', true);
                $checked = '';
                if ($gallery_show == 'true') {
                    $checked = 'checked';
                }
                echo '<label class="switch">';
                echo '<input ' . $checked . ' type="checkbox" id="gallery_show" name="gallery_show" value="';
                echo wp_kses_post($gallery_show);
                echo '">';
                echo '<span class="slider round"></span>';
                echo '</label>';
                echo __('<label for="gallery_show"><b>Gallery</b></label>', 'listingpro-plugin');
                $checked = get_post_meta($post->ID, 'gall_show_hide', 'true');
                if (empty($checked)) {
                    $checked = 'checked';
                }
                ?>
            </div>
            <div style="width:40%;float:left">
                <?php
                echo '<label class="switch">';
                echo ' <input type="checkbox" id="gall_show_hide" name="gall_show_hide" ' . $checked . '  />';
                echo '<span class="slider round slider2"></span>';
                echo '</label>';
                ?>
            </div>
            <br clear="all"/>
        </div>
        <?php
        $meta_value_tagline = get_post_meta($post->ID, 'listingproc_tagline', true);
        $checked = '';
        if ($meta_value_tagline == 'true') {
            $checked = 'checked';
        }
        ?>
        <div style="border-bottom: 1px solid #ccc;padding: 10px 0px;">
            <div style="width:60%;float:left">
                <label class="switch">
                    <input <?php echo $checked; ?> type="checkbox" id="listingproc_tagline" name="listingproc_tagline"
                                                   value="<?php echo wp_kses_post($meta_value_tagline); ?>"/>
                    <span class="slider round"></span>
                </label>
                <label for="listingproc_tagline"> <?php echo __('<b>Tagline</b>', 'listingpro-plugin'); ?></label>
                <?php
                $checked = get_post_meta($post->ID, 'tagline_show_hide', 'true');
                if (empty($checked)) {
                    $checked = 'checked';
                }
                ?>
            </div>
            <div style="width:40%;float:left">
                <?php
                echo '<label class="switch">';
                echo ' <input type="checkbox" id="tagline_show_hide" name="tagline_show_hide" ' . $checked . '  />';
                echo '<span class="slider round slider2"></span>';
                echo '</label>';
                ?>
            </div>
            <br clear="all"/>
        </div>
        <div style="border-bottom: 1px solid #ccc;padding: 10px 0px;">
            <div style="width:60%;float:left">
                <?php
                $meta_value_bookings = get_post_meta($post->ID, 'listingproc_bookings', true);
                $checked = '';
                if ($meta_value_bookings == 'true') {
                    $checked = 'checked';
                }
                ?>
                <label class="switch">
                    <input <?php echo $checked ?> type="checkbox" id="listingproc_bookings" name="listingproc_bookings"
                                                  value="<?php echo wp_kses_post($meta_value_bookings); ?>"/>
                    <span class="slider round"></span>
                </label>
                <label for="listingproc_bookings"><?php echo __('<b>Appointments.</b>', 'listingpro-plugin'); ?></label>
                <?php
                $checked = get_post_meta($post->ID, 'bookings_show_hide', 'true');
                if (empty($checked)) {
                    $checked = 'checked';
                }
                ?>
            </div>
            <div style="width:40%;float:left">
                <?php
                echo '<label class="switch">';
                echo ' <input type="checkbox" id="bookings_show_hide" name="bookings_show_hide" ' . $checked . '  />';
                echo '<span class="slider round slider2"></span>';
                echo '</label>';
                ?>
            </div>
            <br clear="all"/>
        </div>
        <div style="border-bottom: 1px solid #ccc;padding: 10px 0px;">
            <div style="width:60%;float:left">
                <?php
                $meta_value_leadform = get_post_meta($post->ID, 'listingproc_leadform', true);
                $checked = '';
                if ($meta_value_leadform == 'true') {
                    $checked = 'checked';
                }
                ?>
                <label class="switch">
                    <input <?php echo $checked ?> type="checkbox" id="listingproc_leadform" name="listingproc_leadform"
                                                  value="<?php echo wp_kses_post($meta_value_leadform); ?>"/>
                    <span class="slider round"></span>
                </label>
                <label for="listingproc_leadform"><?php echo __('<b>Lead Form.</b>', 'listingpro-plugin'); ?></label>
                <?php
                $checked = get_post_meta($post->ID, 'leadform_show_hide', 'true');
                if (empty($checked)) {
                    $checked = 'checked';
                }
                ?>
            </div>
            <div style="width:40%;float:left">
                <?php
                echo '<label class="switch">';
                echo ' <input type="checkbox" id="leadform_show_hide" name="leadform_show_hide" ' . $checked . '  />';
                echo '<span class="slider round slider2"></span>';
                echo '</label>';
                ?>
            </div>
            <br clear="all"/>
        </div>
        <?php
        $meta_value_faq = get_post_meta($post->ID, 'listingproc_faq', true);
        $checked = '';
        if ($meta_value_faq == 'true') {
            $checked = 'checked';
        }
        ?>
        <div style="border-bottom: 1px solid #ccc;padding: 10px 0px;">
            <div style="width:60%;float:left">

                <label class="switch">
                    <input <?php echo $checked ?> type="checkbox" id="listingproc_faq" name="listingproc_faq"
                                                  value="<?php echo wp_kses_post($meta_value_faq); ?>"/>
                    <span class="slider round"></span>
                </label>
                <label for="listingproc_faq"><?php echo __('<b>FAQs list.</b>', 'listingpro-plugin'); ?></label>
            </div>
            <?php
            $checked = get_post_meta($post->ID, 'faqs_show_hide', 'true');
            if (empty($checked)) {
                $checked = 'checked';
            }
            ?>
            <div style="width:40%;float:left">
                <?php
                echo '<label class="switch">';
                echo ' <input type="checkbox" id="faqs_show_hide" name="faqs_show_hide" ' . $checked . '  />';
                echo '<span class="slider round slider2"></span>';
                echo '</label>';
                ?>
            </div>
            <br clear="all"/>
        </div>
        <div style="border-bottom: 1px solid #ccc;padding: 10px 0px;">
            <div style="width:60%;float:left">
                <?php
                $meta_value_tag_key = get_post_meta($post->ID, 'listingproc_tag_key', true);
                $checked = '';
                if ($meta_value_tag_key == 'true') {
                    $checked = 'checked';
                }
                ?>
                <label class="switch">
                    <input <?php echo $checked ?> type="checkbox" id="listingproc_tag_key" name="listingproc_tag_key"
                                                  value="<?php echo wp_kses_post($meta_value_tag_key); ?>"/>
                    <span class="slider round"></span>
                </label>
                <label for="listingproc_tag_key"><?php echo __('<b>Tags or Keywords.</b>', 'listingpro-plugin'); ?></label>
                <?php
                $checked = get_post_meta($post->ID, 'tags_show_hide', 'true');
                if (empty($checked)) {
                    $checked = 'checked';
                }
                ?>
            </div>
            <div style="width:40%;float:left">
                <?php
                echo '<label class="switch">';
                echo ' <input type="checkbox" id="tags_show_hide" name="tags_show_hide" ' . $checked . '  />';
                echo '<span class="slider round slider2"></span>';
                echo '</label>';
                ?>
            </div>
            <br clear="all"/>
        </div>
        <div style="border-bottom: 1px solid #ccc;padding: 10px 0px;">
            <div style="width:60%;float:left">
                <?php
                $meta_value_announcment = get_post_meta($post->ID, 'listingproc_plan_announcment', true);
                $checked = '';
                if ($meta_value_announcment == 'true') {
                    $checked = 'checked';
                }
                ?>
                <label class="switch">
                    <input <?php echo $checked ?> type="checkbox" id="listingproc_plan_announcment"
                                                  name="listingproc_plan_announcment"
                                                  value="<?php echo wp_kses_post($meta_value_announcment); ?>"/>
                    <span class="slider round"></span>
                </label>
                <label for="listingproc_plan_announcment"><?php echo __('<b>Announcement.</b>', 'listingpro-plugin'); ?></label>

                <?php
                $checked = get_post_meta($post->ID, 'announcment_show_hide', 'true');
                if (empty($checked)) {
                    $checked = 'checked';
                }
                ?>
            </div>
            <div style="width:40%;float:left">
                <?php
                echo '<label class="switch">';
                echo ' <input type="checkbox" id="announcment_show_hide" name="announcment_show_hide" ' . $checked . '  />';
                echo '<span class="slider round slider2"></span>';
                echo '</label>';
                ?>
            </div>
            <br clear="all"/>
        </div>
        <div style="border-bottom: 1px solid #ccc;padding: 10px 0px; display:none">
            <div style="width:60%;float:left">
                <?php
                $lp_featured_imageplan = get_post_meta($post->ID, 'lp_featured_imageplan', true);
                $checked = '';
                if ($lp_featured_imageplan == 'true') {
                    $checked = 'checked';
                }
                ?>
                <label class="switch">
                    <input <?php echo $checked ?> type="checkbox" id="lp_featured_imageplan"
                                                  name="lp_featured_imageplan"
                                                  value="<?php echo wp_kses_post($lp_featured_imageplan); ?>"/>
                    <span class="slider round"></span>
                </label>
                <label for="lp_featured_imageplan"><?php echo __('<b>featured image</b> on Listing Detail Page', 'listingpro-plugin'); ?></label>
                <?php
                $checked = get_post_meta($post->ID, 'featimg_show_hide', 'true');
                if (empty($checked)) {
                    $checked = 'checked';
                }
                ?>
            </div>
            <div style="width:40%;float:left">
                <?php
                echo '<label class="switch">';
                echo ' <input type="checkbox" id="featimg_show_hide" name="featimg_show_hide" ' . $checked . '  />';
                echo '<span class="slider round slider2"></span>';
                echo '</label>';
                ?>
            </div>
            <br clear="all"/>
        </div>
        <?php
        $lp_adswithplan = get_post_meta($post->ID, 'lp_ads_wih_plan', true);
        ?>
        <input type="hidden" id="lp_ads_wih_plan" placeholder="5" name="lp_ads_wih_plan"
               value="<?php echo wp_kses_post($lp_adswithplan); ?>"/>
        <?php
        wp_nonce_field('', 'lp_metaplans_hidden');
    }
}
if (!function_exists('plan_contact_box_save')) {
    function plan_contact_box_save($post_id)
    {
        if (!isset($_POST['lp_metaplans_hidden'])) {
            return;
        }
        $post_type = get_post_type($post_id);
        if ("price_plan" != $post_type) {
            return;
        }
        if (!isset($_POST['lp_metaplans_hidden'])) {
            return;
        } else {
            if (isset($_POST["lp_ads_wih_plan"])) {
                $freeads = $_POST["lp_ads_wih_plan"];
                if (!empty($freeads)) {
                    update_post_meta($post_id, 'lp_ads_wih_plan', $freeads);
                } else {
                    update_post_meta($post_id, 'lp_ads_wih_plan', 0);
                }
            } else {
                update_post_meta($post_id, 'lp_ads_wih_plan', 0);
            }
            if (isset($_POST["lp_hidegooglead"])) {
                update_post_meta($post_id, 'lp_hidegooglead', 'true');
            } else {
                update_post_meta($post_id, 'lp_hidegooglead', 'false');
            }
            if (isset($_POST["lp_eventsplan"])) {
                update_post_meta($post_id, 'lp_eventsplan', 'true');
            } else {
                update_post_meta($post_id, 'lp_eventsplan', 'false');
            }
            if (isset($_POST["lp_featured_imageplan"])) {
                update_post_meta($post_id, 'lp_featured_imageplan', 'true');
            } else {
                update_post_meta($post_id, 'lp_featured_imageplan', 'false');
            }
            if (isset($_POST["listingproc_plan_campaigns"])) {
                update_post_meta($post_id, 'listingproc_plan_campaigns', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_plan_campaigns', 'false');
            }
            if (isset($_POST["listingproc_plan_deals"])) {
                update_post_meta($post_id, 'listingproc_plan_deals', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_plan_deals', 'false');
            }
            if (isset($_POST["listingproc_plan_timekit"])) {
                update_post_meta($post_id, 'listingproc_plan_timekit', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_plan_timekit', 'false');
            }
            if (isset($_POST["listingproc_plan_announcment"])) {
                update_post_meta($post_id, 'listingproc_plan_announcment', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_plan_announcment', 'false');
            }
            if (isset($_POST["listingproc_plan_menu"])) {
                update_post_meta($post_id, 'listingproc_plan_menu', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_plan_menu', 'false');
            }
            if (isset($_POST["listingproc_plan_reservera"])) {
                update_post_meta($post_id, 'listingproc_plan_reservera', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_plan_reservera', 'false');
            }
            if (isset($_POST["contact_show"])) {
                update_post_meta($post_id, 'contact_show', 'true');
            } else {
                update_post_meta($post_id, 'contact_show', 'false');
            }
            if (isset($_POST["map_show"])) {
                update_post_meta($post_id, 'map_show', 'true');
            } else {
                update_post_meta($post_id, 'map_show', 'false');
            }
            if (isset($_POST["video_show"])) {
                update_post_meta($post_id, 'video_show', 'true');
            } else {
                update_post_meta($post_id, 'video_show', 'false');
            }
            if (isset($_POST["gallery_show"])) {
                update_post_meta($post_id, 'gallery_show', 'true');
            } else {
                update_post_meta($post_id, 'gallery_show', 'false');
            }
            if (isset($_POST["gallery_show"])) {
                update_post_meta($post_id, 'gallery_show', 'true');
            } else {
                update_post_meta($post_id, 'gallery_show', 'false');
            }
            if (isset($_POST["listingproc_tagline"])) {
                update_post_meta($post_id, 'listingproc_tagline', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_tagline', 'false');
            }
            if (isset($_POST["listingproc_location"])) {
                update_post_meta($post_id, 'listingproc_location', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_location', 'false');
            }
            if (isset($_POST["listingproc_website"])) {
                update_post_meta($post_id, 'listingproc_website', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_website', 'false');
            }
            if (isset($_POST["listingproc_social"])) {
                update_post_meta($post_id, 'listingproc_social', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_social', 'false');
            }
            if (isset($_POST["listingproc_leadform"])) {
                update_post_meta($post_id, 'listingproc_leadform', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_leadform', 'false');
            }
            if (isset($_POST["listingproc_bookings"])) {
                update_post_meta($post_id, 'listingproc_bookings', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_bookings', 'false');
            }
            if (isset($_POST["listingproc_faq"])) {
                update_post_meta($post_id, 'listingproc_faq', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_faq', 'false');
            }
            if (isset($_POST["listingproc_price"])) {
                update_post_meta($post_id, 'listingproc_price', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_price', 'false');
            }
            if (isset($_POST["listingproc_tag_key"])) {
                update_post_meta($post_id, 'listingproc_tag_key', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_tag_key', 'false');
            }
            if (isset($_POST["listingproc_bhours"])) {
                update_post_meta($post_id, 'listingproc_bhours', 'true');
            } else {
                update_post_meta($post_id, 'listingproc_bhours', 'false');
            }

            // MedicalPro

            if (isset($_POST["insurances_show"])) {
                update_post_meta($post_id, 'insurances_show', 'true');
            } else {
                update_post_meta($post_id, 'insurances_show', 'false');
            }
            if (isset($_POST["awards_show"])) {
                update_post_meta($post_id, 'awards_show', 'true');
            } else {
                update_post_meta($post_id, 'awards_show', 'false');
            }
            if (isset($_POST["video_consult_show"])) {
                update_post_meta($post_id, 'video_consult_show', 'true');
            } else {
                update_post_meta($post_id, 'video_consult_show', 'false');
            }

            if (isset($_POST["insurances_show_hide"])) {
                update_post_meta($post_id, 'insurances_show_hide', '');
            } else {
                update_post_meta($post_id, 'insurances_show_hide', 'true');
            }
            if (isset($_POST["awards_show_hide"])) {
                update_post_meta($post_id, 'awards_show_hide', '');
            } else {
                update_post_meta($post_id, 'awards_show_hide', 'true');
            }
            if (isset($_POST["video_consult_show_hide"])) {
                update_post_meta($post_id, 'video_consult_show_hide', '');
            } else {
                update_post_meta($post_id, 'video_consult_show_hide', 'true');
            }

            // MedicalPro

            if (isset($_POST["contact_show_hide"])) {
                update_post_meta($post_id, 'contact_show_hide', '');
            } else {
                update_post_meta($post_id, 'contact_show_hide', 'true');
            }
            if (isset($_POST["map_show_hide"])) {
                update_post_meta($post_id, 'map_show_hide', '');
            } else {
                update_post_meta($post_id, 'map_show_hide', 'true');
            }
            if (isset($_POST["video_show_hide"])) {
                update_post_meta($post_id, 'video_show_hide', '');
            } else {
                update_post_meta($post_id, 'video_show_hide', 'true');
            }
            if (isset($_POST["gall_show_hide"])) {
                update_post_meta($post_id, 'gall_show_hide', '');
            } else {
                update_post_meta($post_id, 'gall_show_hide', 'true');
            }
            if (isset($_POST["tagline_show_hide"])) {
                update_post_meta($post_id, 'tagline_show_hide', '');
            } else {
                update_post_meta($post_id, 'tagline_show_hide', 'true');
            }
            if (isset($_POST["location_show_hide"])) {
                update_post_meta($post_id, 'location_show_hide', '');
            } else {
                update_post_meta($post_id, 'location_show_hide', 'true');
            }
            if (isset($_POST["website_show_hide"])) {
                update_post_meta($post_id, 'website_show_hide', '');
            } else {
                update_post_meta($post_id, 'website_show_hide', 'true');
            }
            if (isset($_POST["social_show_hide"])) {
                update_post_meta($post_id, 'social_show_hide', '');
            } else {
                update_post_meta($post_id, 'social_show_hide', 'true');
            }
            if (isset($_POST["leadform_show_hide"])) {
                update_post_meta($post_id, 'leadform_show_hide', '');
            } else {
                update_post_meta($post_id, 'leadform_show_hide', 'true');
            }
            if (isset($_POST["faqs_show_hide"])) {
                update_post_meta($post_id, 'faqs_show_hide', '');
            } else {
                update_post_meta($post_id, 'faqs_show_hide', 'true');
            }
            if (isset($_POST["bookings_show_hide"])) {
                update_post_meta($post_id, 'bookings_show_hide', '');
            } else {
                update_post_meta($post_id, 'bookings_show_hide', 'true');
            }
            if (isset($_POST["price_show_hide"])) {
                update_post_meta($post_id, 'price_show_hide', '');
            } else {
                update_post_meta($post_id, 'price_show_hide', 'true');
            }
            if (isset($_POST["tags_show_hide"])) {
                update_post_meta($post_id, 'tags_show_hide', '');
            } else {
                update_post_meta($post_id, 'tags_show_hide', 'true');
            }
            if (isset($_POST["bhours_show_hide"])) {
                update_post_meta($post_id, 'bhours_show_hide', '');
            } else {
                update_post_meta($post_id, 'bhours_show_hide', 'true');
            }
            if (isset($_POST["reserva_show_hide"])) {
                update_post_meta($post_id, 'reserva_show_hide', '');
            } else {
                update_post_meta($post_id, 'reserva_show_hide', 'true');
            }
            if (isset($_POST["timekit_show_hide"])) {
                update_post_meta($post_id, 'timekit_show_hide', '');
            } else {
                update_post_meta($post_id, 'timekit_show_hide', 'true');
            }
            if (isset($_POST["menu_show_hide"])) {
                update_post_meta($post_id, 'menu_show_hide', '');
            } else {
                update_post_meta($post_id, 'menu_show_hide', 'true');
            }
            if (isset($_POST["announcment_show_hide"])) {
                update_post_meta($post_id, 'announcment_show_hide', '');
            } else {
                update_post_meta($post_id, 'announcment_show_hide', 'true');
            }
            if (isset($_POST["deals_show_hide"])) {
                update_post_meta($post_id, 'deals_show_hide', '');
            } else {
                update_post_meta($post_id, 'deals_show_hide', 'true');
            }
            if (isset($_POST["metacampaign_show_hide"])) {
                update_post_meta($post_id, 'metacampaign_show_hide', '');
            } else {
                update_post_meta($post_id, 'metacampaign_show_hide', 'true');
            }
            if (isset($_POST["featimg_show_hide"])) {
                update_post_meta($post_id, 'featimg_show_hide', '');
            } else {
                update_post_meta($post_id, 'featimg_show_hide', 'true');
            }
            if (isset($_POST["events_show_hide"])) {
                update_post_meta($post_id, 'events_show_hide', '');
            } else {
                update_post_meta($post_id, 'events_show_hide', 'true');
            }
            if (isset($_POST["googlead_show_hide"])) {
                update_post_meta($post_id, 'googlead_show_hide', '');
            } else {
                update_post_meta($post_id, 'googlead_show_hide', 'true');
            }
        }
    }
}

if (!function_exists('listingpro_shortcode_pricing')) {
    function listingpro_shortcode_pricing($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'title_subtitle_show' => '',
            'title' => '',
            'subtitle' => '',
            'pricing_views' => 'horizontal_view',
            'pricing_horizontal_view' => 'horizontal_view_1',
            'pricing_vertical_view' => 'vertical_view_1',
            'plan_status' => '',
        ), $atts));
        $output = null;
        global $listingpro_options;
        //set_query_var('pricing_plan_style', $pricing_views);
        $GLOBALS['pricing_views'] = $pricing_views;
        $GLOBALS['pricing_horizontal_view'] = $pricing_horizontal_view;
        $GLOBALS['pricing_vertical_view'] = $pricing_vertical_view;
        $lp_plans_cats = lp_theme_option('listingpro_plans_cats');
        $lp_plans_cats_position = lp_theme_option('listingpro_plans_cats');
        $lp_listing_paid_claim_switchh = lp_theme_option('lp_listing_paid_claim_switch');
        $output .= '<div class="col-md-10 col-md-offset-1 padding-bottom-40 lp-margin-top-case ' . $pricing_views . '">';
        //Title and subtitle field optional
        if ($title_subtitle_show == 'show_hide') {
            $output .= '<div class="page-header">
						<h3>' . $title . '</h3>
						<p>' . $subtitle . '</p>
			</div>';
        } elseif ($lp_plans_cats == 'no') {
            $output .= '<div class="lp-no-title-subtitle">
            </div>';
        }
        if ($lp_listing_paid_claim_switchh == 'yes' && !is_front_page()) {
            $output .= '<div class="lp-no-title-subtitleeeeeeeee">
                ' . esc_html__("Choose a Plan to Claim Your Business", "listingpro-plugin") . '
         </div>';
        }
        if ($plan_status != 'claim') {
            if ($lp_plans_cats == 'yes') {
                ob_start();
                include_once(MP_PLUGIN_PATH . 'templates/pricing/by_category.php');
                $output .= ob_get_contents();
                ob_end_clean();
                ob_flush();
                ob_start();
                include_once(MP_PLUGIN_PATH . "templates/pricing/" . $pricing_views . '.php');
                $output .= ob_get_contents();
                ob_end_clean();
                ob_flush();
            } else {
                ob_start();
                include_once(MP_PLUGIN_PATH . "templates/pricing/" . $pricing_views . '.php');
                $output .= ob_get_contents();
                ob_end_clean();
                ob_flush();
            }
        } else {
            ob_start();
            include_once(MP_PLUGIN_PATH . 'templates/pricing/loop/claim_plans.php');
            $output .= ob_get_contents();
            ob_end_clean();
            ob_flush();
        }
        $output .= '	</div>';
        return $output;
    }
}
if (!function_exists('mp_event_on_google_calendar')) {
    function mp_event_on_google_calendar($event_to_be_created)
    {
        $from_name = $event_to_be_created['from_name'];
        $from_address = $event_to_be_created['from_email'];
        $to_name = $event_to_be_created['to_name'];
        $to_address = $event_to_be_created['to_email'];
        $location = $event_to_be_created['location'];
        $startTime = $event_to_be_created['start_time'];
        $endTime = $event_to_be_created['end_time'];
        $subject =  esc_html__("Appointment Created For Doctor: ", "medicalpro") . $event_to_be_created['event_title'];
        $description = esc_html__("Appointment Created For Doctor: ", "medicalpro") .
            $event_to_be_created['event_title'] .
            "<br> " .
            esc_html__("Add this appointment to your google calendar by clicking on (View on Google Calendar) above,", "medicalpro") .
            "<br> " .
            esc_html__("Or add this appointment to you app by clicking on (download) below", "medicalpro");

        $domain = home_url();
        //Create Email Headers
        $mime_boundary = "----Appointment Booking----" . MD5(TIME());
        $headers = "From: " . $from_name . " <" . $from_address . ">\n";
        $headers .= "Reply-To: " . $to_name . " <" . $to_address . ">\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";
        //Create Email Body (HTML)
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= "<html>\n";
        $message .= "<body>\n";
        $message .= $description;
        $message .= "</body>\n";
        $message .= "</html>\n";
        $message .= "--$mime_boundary\r\n";
        //Event setting
        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
            'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
            'VERSION:2.0' . "\r\n" .
            'METHOD:REQUEST' . "\r\n" .
            'BEGIN:VTIMEZONE' . "\r\n" .
            'TZID:Eastern Time' . "\r\n" .
            'BEGIN:STANDARD' . "\r\n" .
            'DTSTART:' . strtotime($startTime) . "\r\n" .
            'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
            'TZOFFSETFROM:-0400' . "\r\n" .
            'TZOFFSETTO:-0500' . "\r\n" .
            'TZNAME:EST' . "\r\n" .
            'END:STANDARD' . "\r\n" .
            'BEGIN:DAYLIGHT' . "\r\n" .
            'DTEND:' . strtotime($endTime) . "\r\n" .
            'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
            'TZOFFSETFROM:-0500' . "\r\n" .
            'TZOFFSETTO:-0400' . "\r\n" .
            'TZNAME:EDST' . "\r\n" .
            'END:DAYLIGHT' . "\r\n" .
            'END:VTIMEZONE' . "\r\n" .
            'BEGIN:VEVENT' . "\r\n" .
            'ORGANIZER;CN="' . $from_name . '":MAILTO:' . $from_address . "\r\n" .
            'ATTENDEE;CN="' . $to_name . '";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:' . $to_address . "\r\n" .
            'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
            'UID:' . date("Ymd\TGis", strtotime($startTime)) . rand() . "@" . $domain . "\r\n" .
            'DTSTAMP:' . date("Ymd\TGis") . "\r\n" .
            'DTSTART;TZID="Pacific Daylight":' . date("Ymd\THis", strtotime($startTime)) . "\r\n" .
            'DTEND;TZID="Pacific Daylight":' . date("Ymd\THis", strtotime($endTime)) . "\r\n" .
            'TRANSP:OPAQUE' . "\r\n" .
            'SEQUENCE:1' . "\r\n" .
            'SUMMARY:' . $subject . "\r\n" .
            'LOCATION:' . $location . "\r\n" .
            'CLASS:PUBLIC' . "\r\n" .
            'PRIORITY:5' . "\r\n" .
            'BEGIN:VALARM' . "\r\n" .
            'TRIGGER:-PT15M' . "\r\n" .
            'ACTION:DISPLAY' . "\r\n" .
            'DESCRIPTION:Reminder' . "\r\n" .
            'END:VALARM' . "\r\n" .
            'END:VEVENT' . "\r\n" .
            'END:VCALENDAR' . "\r\n";
        $message .= 'Content-Type: text/calendar;name="Appointment.ics";method=REQUEST' . "\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= $ical;
        //$headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        //$headers .= 'From: '.$from_name .' <'. $from_address .'>' ."\r\n". 'Reply-To: '.$from_address."\r\n" .  'X-Mailer: PHP/' . phpversion();
        //$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        //$headers .= "Content-class: urn:content-classes:calendarmessage\n";
        $result = mail($to_address, $subject, $message, $headers);
    }
}
if(!function_exists('listingpro_select_plan_by_cat')){
    function listingpro_select_plan_by_cat(){
        $catTermid = sanitize_text_field($_POST['term_id']);
        $pricing_style_views = sanitize_text_field($_POST['currentStyle']);
        $durationType = sanitize_text_field(stripcslashes($_POST['duration_type']));
        $durationArray = array();
        $planforArray = array();
        $plans_by_cat = lp_theme_option('listingpro_plans_cats');
        if($plans_by_cat=='yes'){
            $planforArray = array(
                'key' => 'plan_usge_for',
                'value' => 'default',
                'compare' => 'NOT LIKE',
            );
        }
        $isMontlyFilter = false;
        /* for switcher */
        $args = null;
        $args = array(
            'post_type' => 'price_plan',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query'=>array(
                array(
                    'key' => 'lp_selected_cats',
                    'value' => ':"'.$catTermid.'"',
                    'compare' => 'LIKE',
                ),
            ),
        );
        $cat_Plan_Query = null;
        $cat_Plan_Query = new WP_Query($args);
        if($cat_Plan_Query->have_posts()){
            while ( $cat_Plan_Query->have_posts() ) {
                $cat_Plan_Query->the_post();
                $durationtype = get_post_meta(get_the_ID(), 'plan_duration_type', true);
                if($durationtype=="monthly" || $durationtype=="yearly" )
                    $isMontlyFilter = true;
            }
        }
        /* end for switcher */
        if(!empty($durationType)){
            $durationArray = array(
                'key' => 'plan_duration_type',
                'value' => $durationType,
                'compare' => 'LIKE',
            );
        }
        if(!empty($catTermid)){
            /* code goes here */
            $output = null;
            $args = null;
            $args = array(
                'post_type' => 'price_plan',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'meta_query'=>array(
                    'relation' => 'AND',
                    array(
                        'key' => 'lp_selected_cats',
                        'value' => $catTermid,
                        'compare' => 'LIKE',
                    ),
                    $durationArray,
                    $planforArray,
                ),
            );
            $cat_Plan_Query = null;
            $gridNumber = 0;
            $cat_Plan_Query = new WP_Query($args);
            $count = $cat_Plan_Query->found_posts;
            $GLOBALS['plans_count'] = $count;
            if($cat_Plan_Query->have_posts()){
                while ( $cat_Plan_Query->have_posts() ) {
                    $cat_Plan_Query->the_post();
                    $durationtype = get_post_meta(get_the_ID(), 'plan_duration_type', true);
                    $gridNumber++;

                    ob_start();

                    include( MEDICALPRO_PLUGIN_PATH . "templates/pricing/loop/".$pricing_style_views.'.php');
                    $output .= ob_get_contents();
                    ob_end_clean();
                    ob_flush();
                    if($gridNumber%3 == 0) {
                        $output.='<div class="clearfix"></div>';
                    }
                }//END WHILE
                wp_reset_postdata();
                $returnData = array('response'=>'success', 'plans'=>$output, 'switcher'=>$isMontlyFilter);
            }else{
                $returnData = array('response'=>'success', 'plans'=> esc_html__('Sorry! There is no plan associated with the category', 'listingpro'), 'switcher'=>$isMontlyFilter);
            }
        }

        die(json_encode($returnData));
    }
}