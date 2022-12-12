<?php
get_header();
wp_enqueue_script('medicalpro-fixed-sidebar-lib');
$hospital_info  = get_queried_object();
$term_id        = $hospital_info->term_id;

$business_logo              = get_term_meta($term_id, 'business_logo', true);
$address                    = get_term_meta($term_id, 'address', true);
$latitude                   = get_term_meta($term_id, 'latitude', true);
$longitude                  = get_term_meta($term_id, 'longitude', true);
$phone                      = get_term_meta($term_id, 'phone', true);
$emergency_number           = get_term_meta($term_id, 'emergency_number', true);
$beds                       = get_term_meta($term_id, 'beds', true);
$wheelchair_accessibility   = get_term_meta($term_id, 'wheelchair_accessibility', true);
$ambulances                 = get_term_meta($term_id, 'ambulances', true);
$timings                    = get_term_meta($term_id, 'timings', true);
$established                = get_term_meta($term_id, 'established', true);
$attachments                = get_term_meta($term_id, 'gallery', true);
$attachments                = (isset($attachments) && !empty($attachments)) ? array_filter($attachments) : array();
$lp_map_pin                 = $listingpro_options['lp_map_pin']['url'];

if( $latitude == '' && $longitude = '' ){
   $response  = medicalpro_get_lat_long_from_address($address);
   $latitude  = isset($response['lat'])  ? $response['lat'] : '';
   $longitude = isset($response['lng']) ? $response['lng'] : '';
}

$doctors_query_args = array(
    'post_type'      => 'listing',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'paged'          => 1,
    'fields'         => 'ids',
    'tax_query' => array(
        array(
            'taxonomy' => 'medicalpro-hospital',
            'field'    => 'term_id',
            'terms'    => array( $term_id ),
        )
    )
);

$doctors       = get_posts( $doctors_query_args );
$doctors_count = isset($doctors) ? count($doctors) : 0;

if (isset($attachments) && !empty($attachments)) {
    wp_enqueue_style('css-prettyphoto', THEME_DIR . '/assets/css/prettyphoto.css');
    wp_enqueue_script('jquery-prettyPhoto', THEME_DIR. '/assets/js/jquery.prettyPhoto.js', 'jquery', '', true);
}
?>

<div class="MedicalPro-Detail-Page MedicalPro-Detail-Page-hospital">
    <div class="mp-detail-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="mp-breadcrumb mp-p-15-fmd">
                            <?php if (function_exists('listingpro_breadcrumbs')) listingpro_breadcrumbs(); ?>
                        </div>
                    </div>
                    <div class="mp-profile-detail">
                        <div class="row">
                            <?php
                            if (isset($business_logo) && !empty($business_logo) && is_numeric($business_logo)) {
                                $image = wp_get_attachment_image_src($business_logo, 'listingpro-detail_gallery');
                                ?>
                                <div class="col-md-3 mp-p-0-md text-center md-hospital-logo">
                                    <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_html($hospital_info->name); ?>">
                                </div>
                            <?php } ?>
                            <div class="col-md-9 mp-p-0-md">
                                <div class="mp-profile-content">
                                    <div class="mp-profile-content-title">
                                        <div class="mp-clearfix"></div>
                                        <h1><?php echo esc_html($hospital_info->name); ?></h1>
                                        <div class="mp-clearfix"></div>
                                    </div>
                                    <?php if (isset($established) && !empty($established)) { ?>
                                        <div class="mp-profile-content-rating">
                                            <div class="mp-clearfix"></div>
                                            <h2><?php echo $established; ?></h2>                                                
                                            <div class="mp-clearfix"></div>
                                        </div>
                                    <?php } ?>
                                    <div class="mp-profile-content-features">
                                        <div class="row">
                                            <?php if(isset($beds) && $beds != '' ){ ?>
                                                <div class="col-md-3">
                                                    <div class="mp-profile-content-feature-icon">
                                                        <i class="fa fa-bed" aria-hidden="true"></i>
                                                    </div>
                                                    <span class="mp-profile-content-feature-text"><?php echo $beds; ?>&nbsp;<?php esc_html_e('Beds', 'medicalpro'); ?></span>
                                                </div>
                                            <?php } ?>
                                            <div class="col-md-3">
                                                <div class="mp-profile-content-feature-icon">
                                                    <i class="fa fa-user-md" aria-hidden="true"></i>
                                                </div>
                                                <span class="mp-profile-content-feature-text"><?php echo $doctors_count; ?>&nbsp;<?php esc_html_e('Doctors', 'medicalpro'); ?></span>
                                            </div>
                                            <?php if(isset($wheelchair_accessibility) && $wheelchair_accessibility == 'yes'){ ?>
                                                <div class="col-md-3">
                                                    <div class="mp-profile-content-feature-icon">
                                                        <i class="fa fa-wheelchair-alt" aria-hidden="true"></i>
                                                    </div>
                                                    <span class="mp-profile-content-feature-text"><?php esc_html_e('Wheelchair Accessibility', 'medicalpro'); ?></span>
                                                </div>
                                            <?php } ?>
                                            <?php if(isset($ambulances) && $ambulances != '' ){ ?>
                                                <div class="col-md-3">
                                                    <div class="mp-profile-content-feature-icon">
                                                        <i class="fa fa-ambulance" aria-hidden="true"></i>
                                                    </div>
                                                    <span class="mp-profile-content-feature-text"><?php echo $ambulances; ?>&nbsp;<?php esc_html_e('Ambulances', 'medicalpro'); ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php if(isset($phone) && $phone != ''){ ?>
                                        <div class="mp-profile-content-details mp-profile-content-details-call-now-btn">
                                            <a href="tel:<?php echo preg_replace('/[\s\-\/]/', '', $phone); ?>"><?php esc_html_e('Call Now', 'medicalpro'); ?></a>
                                        </div>
                                    
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!wp_is_mobile() && (!empty($address) || !empty($timings) || !empty($emergency_number) || ( !empty($latitude) && !empty($longitude) ) )) : ?>
                    <div class="col-md-4 max-height-1-overflow-visible">
                        <div class="mp-profile-sidebar">
                            <div class="mp-profile-sidebar-fixed archive-page-fixed-sidebar-true <?php
                            if (is_admin_bar_showing()) {
                                echo 'mp-profile-sidebar-fixed-adminbar';
                            }
                            ?>" id="mp-profile-sidebar-fixed">
                                <div class="mp-profile-sidebar-fixed-container mp-profile-booking-fixed-container">
                                    <div class="md-hospital-detail-page-sidebar">
                                        <?php if(isset($address) && $address != ''){ ?>
                                            
                                            <div class="md-hospital-detail-page-sidebar-map-outer margin-bottom-20">
                                                <h3><i class="fa fa-map-marker" aria-hidden="true"></i> <?php esc_html_e('Address & Location', 'medicalpro'); ?></h3>
                                                <div class="md-hospital-map-container margin-bottom-20">
                                                    <?php if($latitude && $longitude){ ?>
                                                        <div class="cp-lat" data-lat="<?php echo esc_attr($latitude); ?>"></div>
                                                        <div class="cp-lan" data-lan="<?php echo esc_attr($longitude); ?>"></div>
                                                        <div id="cpmap" class="contactmap" data-pinicon="<?php echo esc_attr($lp_map_pin); ?>"></div>
                                                    <?php } ?>
                                                </div>
                                                <p><?php echo $address; ?></p>
                                                <a href="https://www.google.com/maps/place/<?php echo urlencode( str_replace( '% ', ' ' , $address ) ); ?>" target="_blank"><?php esc_html_e('Get Directions', 'medicalpro'); ?></a>
                                            </div>
                                        <?php } ?>
                                        <?php if(isset($timings) && $timings != ''){ ?>
                                            <div class="md-hospital-detail-page-sidebar-outer margin-bottom-20 clearfix">
                                                <h3><i class="fa fa-clock-o" aria-hidden="true"></i> <?php esc_html_e('clock', 'medicalpro'); ?></h3>
                                                <p class="md-sidebar-time"><?php echo $timings; ?></p>
                                            </div>
                                        <?php } ?>
<!--                                        <div class="md-hospital-detail-page-sidebar-outer margin-bottom-20 clearfix">
                                            <h3><i class="fa fa-credit-card-alt" aria-hidden="true"></i> Payments</h3>
                                            <p class="md-payements-mathord">
                                                <span>credit card</span>
                                                <span>cash</span>
                                                <span>online payment</span>
                                            </p>
                                        </div>-->
                                        <?php if(isset($emergency_number) && $emergency_number != ''){ ?>
                                            <div class="md-hospital-detail-page-sidebar-outer margin-bottom-20 clearfix">
                                                <h3><i class="fa fa-eercast" aria-hidden="true"></i> <?php esc_html_e('Emergency Number', 'medicalpro'); ?></h3>
                                                <p><a href="<?php echo preg_replace('/[\s\-\/]/', '', $emergency_number); ?>"><?php echo $emergency_number; ?></a></p>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="mp-detail-content padding-top-40 mp-hospital-detail-content">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mp-p-0-md">
                    <?php if((isset($hospital_info->description) && !empty($hospital_info->description)) || (isset($attachments) && !empty($attachments))){ ?>
                        <div class="mp-location-tab margin-bottom-30">
                            <?php if(isset($hospital_info->description) && !empty($hospital_info->description)){ ?>
                                <div class="mp-hospital-about">
                                    <h4><?php esc_html_e('About Hospital', 'medicalpro'); ?></h4>
                                    <?php echo apply_filters('the_content', $hospital_info->description); ?>
                                </div>
                            <?php } ?>
                            <?php if (isset($attachments) && !empty($attachments)) { ?>
                                <div class="mp-hospital-gallery">
                                    <h4><?php esc_html_e('Photos', 'medicalpro'); ?></h4>
                                    <ul class="mp-gallery-list">
                                        <?php
                                        foreach ($attachments as $attachment) {
                                            $image_alt = get_post_meta($attachment, '_wp_attachment_image_alt', true);
                                            $image = wp_get_attachment_image_src($attachment, 'listingpro-author-thumb');
                                            echo '<li><a href="'. esc_url(wp_get_attachment_url($attachment)) .'" rel="prettyPhoto[gallery1]"><img src="' . esc_url($image[0]) . '" alt="' . esc_attr($image_alt) . '"></a></li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if(isset($listingpro_options['enable_doctors']) && $listingpro_options['enable_doctors'] == '1' && $doctors_count > 0){ ?>
                        <div class="mp-location-tab margin-bottom-70 mp-hospital-listing">
                            <div class="mp-profile-locations clearfix">
                                <?php 
                                if(isset($listingpro_options['doctors_filters']) && $listingpro_options['doctors_filters'] == '1'){
                                    $letters_arr  = array( 
                                        'a' => esc_html__('A', 'medicalpro'),
                                        'b' => esc_html__('B', 'medicalpro'),
                                        'c' => esc_html__('C', 'medicalpro'),
                                        'd' => esc_html__('D', 'medicalpro'),
                                        'e' => esc_html__('E', 'medicalpro'),
                                        'f' => esc_html__('F', 'medicalpro'),
                                        'g' => esc_html__('G', 'medicalpro'),
                                        'h' => esc_html__('H', 'medicalpro'),
                                        'i' => esc_html__('I', 'medicalpro'),
                                        'j' => esc_html__('J', 'medicalpro'),
                                        'k' => esc_html__('K', 'medicalpro'),
                                        'l' => esc_html__('L', 'medicalpro'),
                                        'm' => esc_html__('M', 'medicalpro'),
                                        'n' => esc_html__('N', 'medicalpro'),
                                        'o' => esc_html__('O', 'medicalpro'),
                                        'p' => esc_html__('P', 'medicalpro'),
                                        'q' => esc_html__('Q', 'medicalpro'),
                                        'r' => esc_html__('R', 'medicalpro'),
                                        's' => esc_html__('S', 'medicalpro'),
                                        't' => esc_html__('T', 'medicalpro'),
                                        'u' => esc_html__('U', 'medicalpro'),
                                        'v' => esc_html__('V', 'medicalpro'),
                                        'w' => esc_html__('W', 'medicalpro'),
                                        'x' => esc_html__('X', 'medicalpro'),
                                        'y' => esc_html__('Y', 'medicalpro'),
                                        'z' => esc_html__('Z', 'medicalpro'),
                                    );
                                    $doctor_cats = get_terms( 'listing-category', array( 'orderby' => 'orderby', 'order' => 'ASC' ) ); ?>
                                    <div class="md-hospital-listing-filters margin-bottom-40" data-id="<?php echo absint($term_id); ?>">
                                        <?php if(isset($listingpro_options['alphabetical_filters']) && $listingpro_options['alphabetical_filters'] == '1'){ ?>
                                            <ul class="md-listing-alpha margin-bottom-25">
                                                <?php foreach($letters_arr as $key => $val){ ?>
                                                    <li class="search-by-letter" data-letter="<?php echo esc_attr($key); ?>"><a href="javascript:void(0);"><?php echo esc_html($key); ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                        <?php 
                                        if(isset($listingpro_options['categories_filters']) && $listingpro_options['categories_filters'] == '1'){
                                            if(isset($doctor_cats) && !empty($doctor_cats)){ ?>
                                                <ul class="md-listing-sorter">
                                                    <li class="search-by-cat active" data-id="all"><a href="javascript:void(0);"><?php esc_html_e('All Specialty', 'medicalpro'); ?></a></li>
                                                    <?php 
                                                    $counter = 0;    
                                                    foreach($doctor_cats as $doctor_cat){ ?>
                                                        <li class="search-by-cat" <?php if( $counter > 5) echo 'style="display:none;"'; ?> data-id="<?php echo absint($doctor_cat->term_id); ?>"><a href="javascript:void(0);"><?php echo esc_html($doctor_cat->name); ?></a></li>
                                                        <?php $counter++; ?>
                                                    <?php } ?>
                                                    <?php if($counter > 6 ){ ?>
                                                        <li class="view-all-cats"><a href="javascript:void(0);"><?php esc_html_e('View All', 'medicalpro'); ?></a></li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="mp-clearfix"></div>
                                <div class="mp-hospital-doctors-holder">
                                    <?php
                                    if(isset($doctors) && !empty($doctors)){
                                        foreach($doctors as $doctor){
                                            set_query_var('doctor_id', $doctor);
                                            medicalpro_get_template_part('templates/hospital/doctor-loop-content');
                                        }
                                    }
                                    wp_reset_postdata();
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
				
            </div>
        </div>
    </div>

</div>
<?php
get_footer();
