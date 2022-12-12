<?php
global $post, $listingpro_options;

$listing_id             = $post->ID;
$listing_hospitals      = wp_get_post_terms( $listing_id, 'medicalpro-hospital' );
$listing_hospitals_data = get_post_meta( $listing_id, 'medicalpro_listing_hospitals', true );
if(isset($listing_hospitals) && !empty($listing_hospitals)){ ?>
    <div id="mp-location-tab" class="mp-location-tab margin-bottom-70">
        <div class="mp-profile-locations">
            <div class="mp-clearfix"></div>
            <?php 
            $counter = 0;
            foreach($listing_hospitals as $listing_hospital){ 
                $counter++;
                $business_logo      =    get_term_meta($listing_hospital->term_id, 'business_logo', true);
                $address            =    get_term_meta($listing_hospital->term_id, 'address', true);
                $phone              =    get_term_meta($listing_hospital->term_id, 'phone', true);
                $price              =    isset($listing_hospitals_data[$listing_hospital->term_id]['price']) ? $listing_hospitals_data[$listing_hospital->term_id]['price'] : '';
                $business_hours     =    isset($listing_hospitals_data[$listing_hospital->term_id]['business_hours']) ? $listing_hospitals_data[$listing_hospital->term_id]['business_hours'] : '';
                $days_arr           = medicalpro_business_working_days();

                $current_time       = current_time(get_option('time_format'));
                //New by abbas
                $current_day        = date_i18n('l');
                //End new by abbas
                $current_closed     = '';
                if(isset($business_hours[$current_day]) && !empty($business_hours[$current_day])){
                    if(empty($business_hours[$current_day]['open']) && empty($business_hours[$current_day]['close'])){
                        $todayTimeStatus = esc_html__('24 hours open', 'medicalpro');
                    }else{
                        if( strtotime($current_time) >= strtotime($business_hours[$current_day]['open']) && strtotime($current_time) <= strtotime($business_hours[$current_day]['close']) ){
                            $todayTimeStatus = $business_hours[$current_day]['open'] .' - '. $business_hours[$current_day]['close'];
                        }else{
                            $todayTimeStatus = esc_html__('Closed', 'medicalpro');
                            $current_closed = 'closed';
                        }
                    }
                }else{
                    $todayTimeStatus =  esc_html__('Closed', 'medicalpro');
                    $current_closed = 'closed';
                }
                ?>
                <div class="mp-profile-location <?php echo $counter > 5 ? 'view-more' : ''; ?>" <?php echo $counter > 5 ? 'style="display:none;"' : ''; ?>>
                    <div class="mp-profile-location-header">
                        <div class="mp-clearfix"></div>
                        <div class="display-inline-block mp-heading">
                            <div class="display-inline-flex">
                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/location/title.svg'; ?>" alt="Medical Center">
                                <a href="<?php echo esc_url(get_term_link($listing_hospital->term_id)); ?>"><h2><?php echo esc_html($listing_hospital->name); ?></h2></a>
                            </div>
                        </div>
                        <div class="display-inline-block pull-right mp-timings">
                            <div class="mp-clearfix"></div>
                            <div class="mp-timings-container pull-left display-inline-block">
                                <div class="mp-profile-timing-single-day display-block">
                                    <p class="mp-timing-day-name"><?php echo esc_html__('Today', 'medicalpro'); ?></p>
                                    <p class="mp-timing-day-timing-status <?php echo $current_closed; ?>"><?php echo $todayTimeStatus; ?></p>
                                </div>
                                <div class="mp-timings-other-days-container mp-hide">
                                    <?php foreach($days_arr as $day){ ?>
                                        <div class="mp-profile-timing-single-day display-block">
                                            <p class="mp-timing-day-name"><?php echo $day; ?></p>
                                            <?php if(isset($business_hours[$day]) && !empty($business_hours[$day])){ ?>
                                                <?php if(empty($business_hours[$day]['open']) && empty($business_hours[$day]['open'])){ ?>
                                                    <p class="mp-timing-day-timing-status"><?php echo esc_html__('24 hours open', 'medicalpro'); ?></p>
                                                <?php }else{ ?>
                                                    <p class="mp-timing-day-timing-status"><?php echo $business_hours[$day]['open']; ?> - <?php echo $business_hours[$day]['close']; ?></p>
                                                <?php } ?>
                                            <?php }else{ ?>
                                                <p class="mp-timing-day-timing-status closed"><?php echo esc_html__('Closed', 'medicalpro'); ?></p>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <span class="mp-timing-day-view-all-timings pull-right display-inline-block"><i class="fa fa-caret-down"></i></span>
                            <div class="mp-clearfix"></div>
                        </div>
                        <div class="mp-clearfix"></div>
                    </div>
                    <?php if(isset($price) && !empty($price)){ ?>
                        <div class="mp-profile-location-pricing display-flex">
                            <div class="mp-profile-location-pricing-icon display-inline-block">
                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/location/pricing.svg'; ?>" alt="Pricing">
                            </div>
                            <div class="mp-profile-location-pricing-price display-inline-block">
                                <p><?php echo $price; ?></p>
                            </div>
                            <div class="mp-profile-location-pricing-for display-inline-block">
                                <p><?php esc_html_e('(For Video Consultation)', 'medicalpro'); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="mp-profile-location-details">
                        <div class="mp-clearfix"></div>
                        <div class="display-inline-block mp-profile-location-detail">
                            <div class="mp-clearfix"></div>
                            <?php
                            if (isset($business_logo) && is_numeric($business_logo)) {
                                $image = wp_get_attachment_image_src($business_logo, 'lp-sidebar-thumb-v2');
                                if (isset($image[0]) && !empty($image[0])) { ?>
                                    <div class="mp-profile-location-detail-map-container display-inline-block pull-left">
                                        <div class="mp-profile-location-detail-map">
                                            <img src="<?php echo esc_url($image[0]); ?>"
                                                 alt="<?php echo esc_attr($listing_hospital->name); ?>">
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <?php if (!empty($address) || !empty($phone)) : ?>
                            <div class="mp-profile-location-detail-address-container display-inline-block">
                                <?php if (!empty($address)) : ?>
                                <p class="mp-profile-location-detail-address"><?php echo $address; ?></p>
                                <div class="mp-clearfix"></div>
                                <a class="mp-profile-location-detail-address-action pull-left" href="https://www.google.com/maps/place/<?php echo urlencode( str_replace( '% ', ' ' , $address ) ); ?>" target="_blank"><?php esc_html_e('Get Direction', 'medicalpro'); ?></a>
                                <div class="mp-profile-location-detail-address-vl mp-vertical-line pull-left"></div>
                                <?php endif; ?>
                                <?php if (!empty($phone)) : ?>
                                <a class="mp-profile-location-detail-address-action pull-left" href="tel:<?php echo preg_replace('/[\s\-\/]/', '', $phone); ?>"><?php esc_html_e('Call Now', 'medicalpro'); ?></a>
                                <div class="mp-clearfix"></div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            <div class="mp-clearfix"></div>
                        </div>

                        <?php
                        $showLeadform = false;
                        $showwithcertifieddoc = $listingpro_options['booking_for_certified_docs'];
                        if ($showwithcertifieddoc) {
                            $certified_doctor = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_certified_doctor', true);
                            if ($certified_doctor != 'Yes'){
                                $showLeadform = true;
                            }
                        }
                        if ($showLeadform === false) :
                        ?>
                        <div class="display-inline-block mp-profile-location-booking pull-right">
                            <div class="mp-profile-location-booking-container">
                                <button class="mp-profile-location-book book-appoinment-btn" data-id="<?php echo $listing_hospital->term_id; ?>" data-date="<?php echo strtotime("now"); ?>"><img src="<?php echo MP_PLUGIN_DIR . 'assets/images/location/book.svg'; ?>" alt="<?php echo esc_attr($listing_hospital->name); ?>"> <?php esc_html_e('Book Appointment', 'medicalpro'); ?></button>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="mp-clearfix"></div>
                    </div>
                </div>
            <?php } ?>
            <?php if(isset($listing_hospitals) && count($listing_hospitals) > 5 ){ ?>
                <div class="mp-clearfix"></div>
                <div class="mp-view-all-profile-locations" data-viewmore="<?php esc_html_e('View More', 'medicalpro'); ?>" data-viewless="<?php esc_html_e('View Less', 'medicalpro'); ?>"><i class="fa fa-angle-down"></i> <span><?php esc_html_e('View More', 'medicalpro'); ?></span></div>
            <?php } ?>
        </div>
    </div>
<?php } ?>