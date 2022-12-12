<?php
get_header();
wp_enqueue_script('medicalpro-fixed-sidebar-lib');
/* The loop starts here. */
global $post, $listingpro_options;

setPostViews($post->ID);
$plan_id = listing_get_metabox_by_ID('Plan_id', $post->ID);
if (!empty($plan_id)) {
    $plan_id = $plan_id;
} else {
    $plan_id = 'none';
}

$faqs_show = get_post_meta($plan_id, 'listingproc_faq', true);
$tags_show = get_post_meta($plan_id, 'listingproc_tag_key', true);
if ($plan_id == "none") {
    $faqs_show = 'true';
    $tags_show = 'true';
}

$mp_booking_section = false;
if(isset($listingpro_options['lp-detail-page-layout-sidebar']['sidebar']['mp_booking_section']) && !empty($listingpro_options['lp-detail-page-layout-sidebar']['sidebar']['mp_booking_section'])){
    $mp_booking_section = true;
}
$showLeadform = false;
$showwithcertifieddoc = $listingpro_options['booking_for_certified_docs'];

//by Abbas 4 july 2022
$poststatus = get_post_status( $post->ID );
if ($poststatus == 'publish'){
    $mp_booking_section = true;
    $showLeadform = false;
}else{
    $mp_booking_section = false;
    $showLeadform = true;
}
//End by Abbas 4 july 2022

if ($showwithcertifieddoc) {
    $certified_doctor = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_certified_doctor', true);
    if ($certified_doctor != 'Yes'){
        $showLeadform = true;
        $mp_booking_section = false;
    }
}
echo apply_filters('listingpro_show_google_ads', 'listing', get_the_ID());
?>
<div class="MedicalPro-Detail-Page">
    <div class="mp-detail-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <?php mp_get_template_part('templates/single-list/listing-details-style1/content/title-bar'); ?>
                    <div class="mp-profile-content-tabs">
                        <div class="row">
                            <ul class="nav nav-tabs mp-profile-content-tabs-container <?php if(wp_is_mobile()){ echo 'mp-tabs-in-mobile'; } ?>">
                                <?php 
                                $content_layouts = $listingpro_options['lp-detail-page-layout-content']['general'];
                                foreach($content_layouts as $key => $value){
                                    switch($key) {
                                        case 'mp_hospitals_section': 
                                            echo '<li class="mp-profile-content-tab mp-w-20"><a class="mp-event-scroll" href="#mp-location-tab">'. esc_html__('Locations', 'medicalpro') .'</a></li>';
                                        break;
                                        case 'mp_insurances_section': 
                                            echo '<li class="mp-profile-content-tab mp-w-20"><a class="mp-event-scroll" href="#mp-insurances-tab">'. esc_html__('Insurances', 'medicalpro') .'</a></li>';
                                        break;
                                        case 'mp_additional_section': 
                                            echo '<li class="mp-profile-content-tab mp-w-20"><a class="mp-event-scroll" href="#mp-services-tab">'. esc_html__('About Me', 'medicalpro') .'</a></li>';
                                        break;
                                        case 'mp_faqs_section': 
                                            echo '<li class="mp-profile-content-tab mp-w-20"><a class="mp-event-scroll" href="#mp-faq-tab">'. esc_html__("FAQ's", 'medicalpro') .'</a></li>';
                                        break;
                                        case 'mp_reviews_section': 
                                            echo '<li class="mp-profile-content-tab mp-w-20"><a class="mp-event-scroll" href="#mp-experiences-tab">'. esc_html__('Ratings', 'medicalpro') .'</a></li>';
                                        break;
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php if (!wp_is_mobile() && $mp_booking_section) : ?>
                    <div class="col-md-4 max-height-1-overflow-visible">
                        <div class="mp-profile-sidebar">
                            <div class="mp-profile-sidebar-fixed listing-detail-page-fixed-sidebar-true <?php
                            if (is_admin_bar_showing()) {
                                echo 'mp-profile-sidebar-fixed-adminbar';
                            }
                            ?>" id="mp-profile-sidebar-fixed">
                                <div class="mp-profile-sidebar-fixed-container mp-profile-booking-fixed-container">
                                    <?php mp_get_template_part('templates/single-list/listing-details-style1/sidebar/booking'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif (!wp_is_mobile() && $showLeadform) :
                    $lead_form_customizer   =   null;
                    if( class_exists('Listingpro_lead_form') && get_option( 'lead-form-active' ) == 'yes' )
                    {
                        $lead_form_customizer   =   'lead_form_customizer_enabled';
                    }
                    if (isset($listingpro_options['lp_lead_form_switch']) && $listingpro_options['lp_lead_form_switch']) {
                    ?>
                        <div class="col-md-4 max-height-1-overflow-visible mp-leadform <?php echo $lead_form_customizer; ?>">
                            <div class="mp-profile-sidebar">
                                <div class="mp-profile-sidebar-fixed listing-detail-page-fixed-sidebar-true <?php
                                if (is_admin_bar_showing()) {
                                    echo 'mp-profile-sidebar-fixed-adminbar';
                                }
                                ?>" id="mp-profile-sidebar-fixed">
                                    <div class="mp-profile-sidebar-fixed-container mp-profile-booking-fixed-container">
                                        <?php mp_get_template_part('templates/single-list/listing-details-style1/sidebar/lead-form'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                endif; ?>
            </div>
        </div>
    </div>
    <div class="mp-detail-content padding-top-20">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mp-p-0-md">
                    <?php
                    if(isset($listingpro_options['lp-detail-page-layout-content']['general']) && !empty($listingpro_options['lp-detail-page-layout-content']['general'])){
                        $content_layouts = $listingpro_options['lp-detail-page-layout-content']['general'];
                        foreach($content_layouts as $key => $value){
                            switch($key) {
                                case 'mp_announcements_section': 
                                    mp_get_template_part('templates/single-list/listing-details-style1/content/announcements');
                                break;
                                case 'mp_hospitals_section': 
                                    mp_get_template_part('templates/single-list/listing-details-style1/content/hospitals');
                                break;
                                case 'mp_features_section':
                                    if($tags_show == "true"){
                                        mp_get_template_part('templates/single-list/listing-details-style1/content/features');
                                    }
                                break;
                                case 'mp_insurances_section':
                                    mp_get_template_part('templates/single-list/listing-details-style1/content/insurances');
                                break;
                                case 'mp_additional_section':
                                    mp_get_template_part('templates/single-list/listing-details-style1/content/additional');
                                break;
                                case 'mp_awards_section':
                                    mp_get_template_part('templates/single-list/listing-details-style1/content/awards');
                                break;
                                case 'mp_faqs_section':
                                    if ($faqs_show == "true") {
                                        mp_get_template_part('templates/single-list/listing-details-style1/content/faqs');
                                    }
                                break;
                                case 'mp_reviews_section':
                                    mp_get_template_part('templates/single-list/listing-details-style1/content/reviews');
                                break;
                                case 'mp_leadform':
                                    if (!$showLeadform) {
                                        echo '<div class="mp-leadform-container">
                                            <div class="mp-experiences-heading"><h2>' . esc_html__( "Ask The Doctor", "medicalpro" ) . '</h2></div>
                                            <div class="mp-leadform-content-container">';
                                                mp_get_template_part('templates/single-list/listing-details-style1/sidebar/lead-form');
                                            echo '</div>
                                        </div>';
                                    }
                                    break;
                                case 'mp_reviewform_section':
                                    mp_get_template_part('templates/single-list/listing-details-style1/content/review_form');
                                break;
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <?php if (wp_is_mobile() && $mp_booking_section) : ?>
                <div class="col-md-4 margin-bottom-100">
                    <div class="mp-profile-sidebar-mobile" id="mp-profile-sidebar-mobile">
                        <div class="mp-profile-sidebar-fixed-container">
                            <?php mp_get_template_part('templates/single-list/listing-details-style1/sidebar/booking'); ?>
                        </div>
                    </div>
                </div>
            <?php elseif (wp_is_mobile() && $showLeadform) :
                $lead_form_customizer   =   null;
                if( class_exists('Listingpro_lead_form') && get_option( 'lead-form-active' ) == 'yes' )
                {
                    $lead_form_customizer   =   'lead_form_customizer_enabled';
                }
                ?>
                <div class="col-md-4 margin-bottom-100 mp-leadform <?php echo $lead_form_customizer; ?>">
                    <div class="mp-profile-sidebar-mobile" id="mp-profile-sidebar-mobile">
                        <div class="mp-profile-sidebar-fixed-container">
                            <?php mp_get_template_part('templates/single-list/listing-details-style1/sidebar/lead-form'); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</div>
<?php
echo listingpro_post_confirmation($post);
do_action( 'listing_single_page_content');
get_footer();