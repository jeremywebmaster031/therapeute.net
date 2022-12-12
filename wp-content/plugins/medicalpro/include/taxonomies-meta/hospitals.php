<?php
function medicalpro_hospital_add_meta_fields($term) {
    ?>
    <div class="form-field">
	<div class="business-logo-holder">
            <a href="javascript:void(0);" class="button button-primary" id="business_logo"><?php esc_html_e('Upload Business Logo', 'medicalpro'); ?></a>
            <div class="business-logo-image"></div>
        </div>
    </div>
    <div class="form-field form-required">
	<label for="hospitalAddress"><?php esc_html_e('Address', 'medicalpro'); ?></label>
	<input name="lp_hospital[address]" id="hospitalAddress" type="text" value="" size="40" aria-required="true" autocomplete="off">
        <input type="hidden" name="lp_hospital[latitude]" id="latitude" value="">
        <input type="hidden" name="lp_hospital[longitude]" id="longitude" value="">
    </div>
    <div class="form-field form-required">
	<label for="hospital-phone"><?php esc_html_e('Phone Number', 'medicalpro'); ?></label>
	<input name="lp_hospital[phone]" id="hospital-phone" type="text" value="" size="40" aria-required="true" autocomplete="off">
    </div>
    <div class="form-field">
	<label for="emergency_number"><?php esc_html_e('Emergency Number', 'medicalpro'); ?></label>
	<input name="lp_hospital[emergency_number]" id="emergency_number" type="text" value="" size="40" aria-required="true" autocomplete="off">
    </div>
    <div class="form-field">
	<label for="beds"><?php esc_html_e('Availabe Beds', 'medicalpro'); ?></label>
	<input name="lp_hospital[beds]" id="beds" type="number" value="" size="40" aria-required="true" autocomplete="off">
    </div>
    <div class="form-field">
	<label for="wheelchair_accessibility"><?php esc_html_e('Wheelchair Accessibility', 'medicalpro'); ?></label>
	<select name="lp_hospital[wheelchair_accessibility]" id="wheelchair_accessibility" width="40">
            <option value="yes"><?php esc_html_e('Yes', 'medicalpro'); ?></option>
            <option value="no"><?php esc_html_e('No', 'medicalpro'); ?></option>
        </select>
    </div>
    <div class="form-field">
	<label for="ambulances"><?php esc_html_e('Ambulances', 'medicalpro'); ?></label>
	<input name="lp_hospital[ambulances]" id="ambulances" type="number" value="" size="40" aria-required="true" autocomplete="off">
    </div>
    <div class="form-field">
	<label for="timings"><?php esc_html_e('Timings', 'medicalpro'); ?></label>
	<input name="lp_hospital[timings]" id="timings" type="text" value="" size="40" aria-required="true" autocomplete="off">
    </div>
    <div class="form-field">
	<label for="established"><?php esc_html_e('Established', 'medicalpro'); ?></label>
	<input name="lp_hospital[established]" id="established" type="text" value="" size="40" aria-required="true" autocomplete="off">
    </div>
    <div class="form-field">
	<label for="hospital-phone"><?php esc_html_e('Gallery', 'medicalpro'); ?></label>
        <div class="medicalpro-term-gallery-field">
            <div class="term-gallery">
                <ul class="term-gallery-list">
                    <?php if (isset($attachments_list)) echo $attachments_list; ?>
                </ul>
            </div>
            <a href="javascript:void(0);" class="button button-primary" id="medicalpro-add-term-gallery"><?php esc_html_e('Add Gallery Images', 'medicalpro'); ?></a>
        </div>
    </div>

    <div class="form-field">
        <label for="hospital_locations"><?php esc_html_e('Select Location', 'medicalpro'); ?></label>
        <select class="medium multiple-select-options" name="lp_hospital[hospital_locations]" id="hospital_locations" width="40">
            <?php
            $cat = array();
            $ucat = array(
                'post_type' => 'listing',
                'hide_empty' => false,
                'orderby' => 'count',
                'order' => 'ASC',
            );
            $features = get_terms( 'location', $ucat);
            foreach($features as $feature) {
                echo '<option value="' . $feature->term_id . '">' . $feature->name . '</option>';
            }
            ?>
        </select>
    </div>

    <?php
}

add_action('medicalpro-hospital_add_form_fields', 'medicalpro_hospital_add_meta_fields', 10, 2);

function medicalpro_hospital_edit_meta_fields($term) {

    $business_logo             = get_term_meta($term->term_id, 'business_logo', true);
    $address                   = get_term_meta($term->term_id, 'address', true);
    $latitude                  = get_term_meta($term->term_id, 'latitude', true);
    $longitude                 = get_term_meta($term->term_id, 'longitude', true);
    $phone                     = get_term_meta($term->term_id, 'phone', true);
    $emergency_number          = get_term_meta($term->term_id, 'emergency_number', true);
    $beds                      = get_term_meta($term->term_id, 'beds', true);
    $wheelchair_accessibility  = get_term_meta($term->term_id, 'wheelchair_accessibility', true);
    $ambulances                = get_term_meta($term->term_id, 'ambulances', true);
    $timings                   = get_term_meta($term->term_id, 'timings', true);
    $established               = get_term_meta($term->term_id, 'established', true);
    $attachments               = get_term_meta($term->term_id, 'gallery', true);

    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="business_logo"><?php esc_html_e('Business Logo', 'medicalpro'); ?></label>
        </th>
        <td>
            <div class="business-logo-holder">
                <a href="javascript:void(0);" class="button button-primary" id="business_logo"><?php esc_html_e('Upload Business Logo', 'medicalpro'); ?></a>
                <div class="business-logo-image">
                    <?php if(isset($business_logo) && !empty($business_logo) && is_numeric($business_logo)){ ?>
                        <img src="<?php echo wp_get_attachment_url($business_logo); ?>">
                        <a class="remove-business-logo" href="javascript:void(0);"><i class="fa fa-remove"></i></a>
                    <?php } ?>
                </div>
            </div>
        </td>
    </tr>

    <tr class="form-field form-required">
        <th scope="row">
            <label for="hospitalAddress"><?php esc_html_e('Address', 'medicalpro'); ?></label>
        </th>
        <td>
            <input name="lp_hospital[address]" id="hospitalAddress" type="text" value="<?php echo $address; ?>" size="40" aria-required="true" autocomplete="off">
            <input type="hidden" name="lp_hospital[latitude]" id="latitude" value="<?php echo $latitude; ?>">
            <input type="hidden" name="lp_hospital[longitude]" id="longitude" value="<?php echo $longitude; ?>">
        </td>
    </tr>
    <tr class="form-field form-required">
        <th scope="row">
            <label for="hospital-phone"><?php esc_html_e('Phone Number', 'medicalpro'); ?></label>
        </th>
        <td>
            <input name="lp_hospital[phone]" id="hospital-phone" type="text" value="<?php echo $phone; ?>" size="40" aria-required="true" autocomplete="off">
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="emergency_number"><?php esc_html_e('Emergency Number', 'medicalpro'); ?></label>
        </th>
        <td>
            <input name="lp_hospital[emergency_number]" id="emergency_number" type="text" value="<?php echo $emergency_number; ?>" size="40" aria-required="true" autocomplete="off">
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="beds"><?php esc_html_e('Availabe Beds', 'medicalpro'); ?></label>
        </th>
        <td>
            <input name="lp_hospital[beds]" id="beds" type="number" value="<?php echo $beds; ?>" size="40" aria-required="true" autocomplete="off">
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="wheelchair_accessibility"><?php esc_html_e('Wheelchair Accessibility', 'medicalpro'); ?></label>
        </th>
        <td>
            <select name="lp_hospital[wheelchair_accessibility]" id="wheelchair_accessibility" width="40">
                <option value="yes" <?php echo $wheelchair_accessibility == 'yes' ? 'selected' : '' ?>><?php esc_html_e('Yes', 'medicalpro'); ?></option>
                <option value="no" <?php echo $wheelchair_accessibility == 'no' ? 'selected' : '' ?>><?php esc_html_e('No', 'medicalpro'); ?></option>
            </select>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="ambulances"><?php esc_html_e('Ambulances', 'medicalpro'); ?></label>
        </th>
        <td>
            <input name="lp_hospital[ambulances]" id="ambulances" type="number" value="<?php echo $ambulances; ?>" size="40" aria-required="true" autocomplete="off">
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="timings"><?php esc_html_e('Timings', 'medicalpro'); ?></label>
        </th>
        <td>
            <input name="lp_hospital[timings]" id="timings" type="text" value="<?php echo $timings; ?>" size="40" aria-required="true" autocomplete="off">
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="established"><?php esc_html_e('Established', 'medicalpro'); ?></label>
        </th>
        <td>
            <input name="lp_hospital[established]" id="established" type="text" value="<?php echo $established; ?>" size="40" aria-required="true" autocomplete="off">
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label><?php esc_html_e('Gallery', 'medicalpro'); ?></label>
        </th>
        <td>
            <?php
            $attachments      = isset($attachments) && is_array($attachments) ? array_filter($attachments) : $attachments;
            $attachments_list = '';
            if(isset($attachments) && !empty($attachments)){
                foreach($attachments as $attachment_id){
                    $attachments_list .= '<li class="gallery-item" data-id="'. $attachment_id .'">
                        <input type="hidden" name="lp_hospital[gallery][]" value="'. $attachment_id .'">
                        <div class="thumbnail">
                            <img src="'. wp_get_attachment_url($attachment_id) .'" alt="">
                        </div>
                        <div class="gallery-actions">
                            <a class="remove-gallery-item" href="javascript:void(0);"><i class="fa fa-remove"></i></a>
                        </div>
                    </li>';
                }
            }
            ?>
            <div class="medicalpro-term-gallery-field">
                <div class="term-gallery">
                    <ul class="term-gallery-list">
                        <input type="hidden" name="lp_hospital[gallery][]" value="">
                        <?php echo $attachments_list; ?>
                    </ul>
                </div>
                <a href="javascript:void(0);" class="button button-primary" id="medicalpro-add-term-gallery"><?php esc_html_e('Add Gallery Images', 'medicalpro'); ?></a>
            </div>
        </td>
    </tr>


    <tr class="form-field">
        <th scope="row">
            <label for="hospital_locations"><?php esc_html_e('Select Location', 'medicalpro'); ?></label>
        </th>
        <td>
            <select class="medium multiple-select-options" name="lp_hospital[hospital_locations]" id="hospital_locations" width="40">
                <?php
                $location = get_term_meta($term->term_id, 'hospital_locations', true);
                $cat = array();
                $ucat = array(
                    'post_type' => 'listing',
                    'hide_empty' => false,
                    'orderby' => 'count',
                    'order' => 'ASC',
                );
                $features = get_terms( 'location',$ucat);
                foreach($features as $feature) {
                    echo '<option ' . selected($location, $feature->term_id) . ' value="' . $feature->term_id . '">' . $feature->name . '</option>';
                }
                ?>
            </select>
        </td>
    </tr>

    <?php
}
add_action('medicalpro-hospital_edit_form_fields', 'medicalpro_hospital_edit_meta_fields', 10);

function medicalpro_save_hospital_fields($term_id) {
    if (isset($_POST['lp_hospital'])) {
        $lp_hospital_fields = $_POST['lp_hospital'];
        foreach($lp_hospital_fields as $key => $val){
            update_term_meta($term_id, $key, $val);
        }
    }
}
add_action('edited_medicalpro-hospital', 'medicalpro_save_hospital_fields');
add_action('create_medicalpro-hospital', 'medicalpro_save_hospital_fields');


add_filter('medicalpro_hospital_submission', 'medicalpro_hospital_submission_callback', 30, 4);
function medicalpro_hospital_submission_callback( $output = '', $style_wrap = '', $listing_id = 0, $page_style = '' ){

    $listing_hospitals      = wp_get_post_terms( $listing_id, 'medicalpro-hospital', array( 'fields' => 'ids' ) );
    $listing_hospitals_data = get_post_meta( $listing_id, 'medicalpro_listing_hospitals', true );

    $class  =  'white-section border-bottom ';
    if( $page_style == 'style1'){
        $class  =  'form-group clearfix ';
    }

    $output .= '<div class=" '. $class . $style_wrap . '">';
        $output .=  '<h4 class="white-section-heading">'. esc_html__( 'Hospitals', 'medicalpro' ) .'</h4>';
        $output .=  '<div class="row">';
            $output .= '<div class="form-group clearfix margin-bottom-0 col-md-12">
                <div class="lsiting-submit-hospitals-tabs clearfix pos-relative">
                    <div class="mp-hospital-tabber-tabs">';
                        $rand_id = rand(123456, 987654);
                        if(isset($listing_hospitals) && !empty($listing_hospitals)) {
                            $counter = 1;
                            foreach ($listing_hospitals as $listing_hospital_id) {
                                $hospital_name = get_term($listing_hospital_id, 'medicalpro-hospital')->name;
                                $hospital_address = get_term_meta($listing_hospital_id, 'address', true);
                                if (!empty($hospital_address)) $hospital_address = ' / ' . $hospital_address;
                                $class = null;
                                if ($counter == 1) $class = 'active';
                                $output .= '<div class="mp-hospital-tabber-tab '. $class .'" id="tab-mp-hospital-tabber-tab-content-id-' . $counter . '" data-content-id="mp-hospital-tabber-tab-content-id-' . $counter . '">
                                        <p>'. $hospital_name . $hospital_address . '</p>
                                        <i class="fa fa-times removethishospital"></i>
                                    </div>';
                                $counter++;
                            }
                        }else {
                            $output .= '<div class="mp-hospital-tabber-tab active" id="tab-mp-hospital-tabber-tab-content-id-' . $rand_id . '" data-content-id="mp-hospital-tabber-tab-content-id-' . $rand_id . '">
                                        <p>'. esc_html__('Select Hospital', 'medicalpro') . '</p>
                                        <i class="fa fa-times removethishospital"></i>
                                    </div>';
                        }
                    $output .= '</div>
                    <div class="mp-hospital-tabber-tabs-content">';
                        if(isset($listing_hospitals) && !empty($listing_hospitals)){
                            $counter = 1;
                            foreach($listing_hospitals as $listing_hospital_id){
                                $hospital_fields = array(
                                    'hospital_id'             => $listing_hospital_id,
                                    'hospital_address'        => get_term_meta($listing_hospital_id, 'address', true),
                                    'hospital_phone'          => get_term_meta($listing_hospital_id, 'phone', true),
                                    'hospital_price'          => isset($listing_hospitals_data[$listing_hospital_id]['price']) ? $listing_hospitals_data[$listing_hospital_id]['price'] : '',
                                    'hospital_business_hours' => isset($listing_hospitals_data[$listing_hospital_id]['business_hours']) ? $listing_hospitals_data[$listing_hospital_id]['business_hours'] : array(),
                                );
                                $class = null;
                                if ($counter == 1) $class = 'active';
                                $output .= '<div class="mp-hospital-tabber-tab-content '. $class .'" id="mp-hospital-tabber-tab-content-id-' . $counter . '">' . medicalpro_hospital_fields( $listing_id, $hospital_fields ) . '</div>';
                                $counter++;
                            }
                        }else{
                            $output .= '<div class="mp-hospital-tabber-tab-content active" id="mp-hospital-tabber-tab-content-id-' . $rand_id . '">' . medicalpro_hospital_fields( $listing_id ) . '</div>';
                        }
                    $output .= '</div>
                </div>
                <div class="btn-container faq-btns clearfix">	
                    <a id="hospital_btn" data-listing_id="'. $listing_id .'" class="lp-secondary-btn btn-first-hover style2-tabsbtn"><i class="fa fa-plus-square"></i> '. esc_html__( 'add new', 'medicalpro' ) .'</a>
                </div>
            </div>';
        $output .=  '</div>';
    $output .=  '</div>';



    return $output;
}

function medicalpro_hospital_fields( $listing_id = 0, $hospital_fields = array() ){
    if (!isset($output)) {
        $output = null;
    }
    $defaults = array(
        'hospital_id'               => '',
        'hospital_address'          => '',
        'hospital_phone'            => '',
        'hospital_price'            => '',
        'hospital_business_hours'   => array(),
    );
    $hospital_fields_data = wp_parse_args( $hospital_fields, $defaults );

    $hospitals              =      get_terms( 'medicalpro-hospital', array( 'hide_empty' => false ) );
    $hospitals_options = '<option value="">'. esc_html__('Select Hospital') .'</option>';
    if(isset($hospitals) && !empty($hospitals)){
        foreach( $hospitals as $hospital ){
            $address  = get_term_meta( $hospital->term_id, 'address', true );
            if (!empty($address)) $address = ' / ' . $address;
            $phone    = get_term_meta( $hospital->term_id, 'phone', true );
            $selected = $hospital->term_id == $hospital_fields_data['hospital_id'] ? 'selected' : '';
            $hospitals_options .= '<option '. $selected .' value="'. $hospital->term_id .'" data-phone="'. $phone .'" data-id="'. $hospital->term_id .'">'. $hospital->name . $address .'</option>';
        }
    }

    $primaryHospital = listing_get_metabox_by_ID('mp_primary_hospital', $listing_id);
    $selectedPrimaryHospital = $primaryHospital == $hospital_fields_data['hospital_id'] ? 'checked' : '';
    $rand_id = rand(123456, 987654);
    $suggest = false;
    if ( !isset($hospital_fields) || empty($hospital_fields) || !is_array($hospital_fields) ) $suggest = true;
    $output .= '
            <div class="tab-content pos-relative mp_hospital_edit_form">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mp_suggest_hospital_name_dd clearfix">
                        <label for="hospital_name_' . $rand_id . '">' . esc_html__('Hospital/Clinic Name', 'medicalpro') . '</label>
                        <select name="medicalpro_hospitals[' . $rand_id . '][name]" id="hospital_name_' . $rand_id . '" class="form-control select2 hospital-name">
                            ' . $hospitals_options . '
                        </select>
                    </div>';
                    if ($suggest) {
                        $location_terms = get_terms('location', array('hide_empty' => false));
                        $locationsHTML = null;
                        if(isset($location_terms) && !empty($location_terms)){
                            foreach($location_terms as $location_term) {
                                $locationsHTML .= '<option value="' . $location_term->term_id . '">' . $location_term->name . '</option>';
                            }
                        }
                        $output .= '<div class="form-group mp_suggest_hospital_name clearfix" style="display: none;">
                            <label for="medicalpro_hospitals[' . $rand_id . '][text]">' . esc_html__('Hospital/Clinic Name', 'medicalpro') . '</label>
                            <input type="text" placeholder="' . esc_html__('Type Here EG: John Doe Hospital/Clinic', 'medicalpro') . '" name="medicalpro_hospitals[' . $rand_id . '][text]" id="medicalpro_hospitals[' . $rand_id . '][text]" value="' .$hospital_fields_data['hospital_id']. '" class="form-control">
                        </div>
                        <div class="form-group mp_suggest_hospital_name clearfix" style="display: none;">
                            <label for="medicalpro_hospitals[' . $rand_id . '][location]">' . esc_html__('Hospital/Clinic Location', 'medicalpro') . '</label>
                            <select name="medicalpro_hospitals[' . $rand_id . '][location]" id="medicalpro_hospitals[' . $rand_id . '][location]" class="form-control select2">
                                <option value="">' . esc_html__("Select Hospital Location", "medicalpro") . '</option>
                                ' . $locationsHTML . '
                            </select>
                        </div>
                        
                        <div class="checkbox form-group mp-suggest-hospital clearfix">
                            <input type="checkbox" name="medicalpro_hospitals[' . $rand_id . '][suggest]" id="medicalpro_hospitals[' . $rand_id . '][suggest]" class="mp_suggest_hospital">
                            <label for="medicalpro_hospitals[' . $rand_id . '][suggest]">' . esc_html__('Suggest a Hospital/Clinic', 'medicalpro') . '</label>
                        </div>';
                    }
                $output .= '</div>
                <div class="col-md-6 hospital_phone_class">
                    <div class="form-group">
                        <label for="hospital_phone_' . $rand_id . '">' . esc_html__('Phone', 'medicalpro') . '</label>
                        <input type="text" name="medicalpro_hospitals[' . $rand_id . '][phone]" id="hospital_phone_' . $rand_id . '" class="form-control hospital-phone" value="' . $hospital_fields_data['hospital_phone'] . '" readonly>
                    </div>
                </div>
                <div class="col-md-6 hospital_price_class">
                    <div class="form-group">
                        <label for="hospital_price_' . $rand_id . '">' . esc_html__('Video Consultation Fee', 'medicalpro') . '</label>
                        <div class="help-text">
						    <a href="#" class="help"><i class="fa fa-question"></i></a>
							<div class="help-tooltip">
							    <p>'. esc_html__('By entering a fee amount for video consultation, a user will have to pay online to complete the appointment request. The amount can be requested from your digital wallet.', 'medicalpro') .'</p>
							</div>
						</div>
                        <input type="text" name="medicalpro_hospitals[' . $rand_id . '][price]" id="hospital_price_' . $rand_id . '" value="' . $hospital_fields_data['hospital_price'] . '" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group clearfix">
                        <div class="hospital-business-hours">
                            ' . medicalpro_hospital_business_hours_form($listing_id, $rand_id, $hospital_fields_data['hospital_business_hours']) . '
                        </div>
                    </div>
                </div>
                <div class="col-md-12 hospital_primary_class">
                    <div class="form-group clearfix">
                        <div class="hospital-level">
                            <label for="hospital_level_' . $rand_id . '">' . esc_html__('Make This Hospital Primary', 'medicalpro') . '</label>
                            <input type="radio" ' . $selectedPrimaryHospital . ' name="medicalpro_primary" id="hospital_level_' . $rand_id . '" value="' . $hospital_fields_data['hospital_id'] . '" class="mark-this-primary">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';

    return $output;
}

function medicalpro_add_listing_hospital(){
    $listing_id = isset($_POST['listing_id']) ? $_POST['listing_id'] : 0;
    $rand_id = rand(123456, 987654);
    $tabHTML = '<div class="mp-hospital-tabber-tab active" id="tab-mp-hospital-tabber-tab-content-id-' . $rand_id . '" data-content-id="mp-hospital-tabber-tab-content-id-' . $rand_id . '"><p>'. esc_html__('Select Hospital', 'medicalpro') . '</p><i class="fa fa-times removethishospital"></i></div>';
    $tabContent = '<div class="mp-hospital-tabber-tab-content active" id="mp-hospital-tabber-tab-content-id-' . $rand_id . '">' . medicalpro_hospital_fields( $listing_id ) . '</div>';
    $return = array(
        'tabHTML'       =>  $tabHTML,
        'tabContent'    =>  $tabContent
    );
    die(json_encode($return));
}
add_action( 'wp_ajax_medicalpro_add_listing_hospital', 'medicalpro_add_listing_hospital' );
add_action( 'wp_ajax_nopriv_medicalpro_add_listing_hospital', 'medicalpro_add_listing_hospital' );

function medicalpro_save_listing_submission_callback( $post_id = 0, $post_fields  = array(), $files = array() ){
    if(isset($post_fields['medicalpro_hospitals']) && !empty($post_fields['medicalpro_hospitals'])){
        $arr = get_post_meta($post_id, 'mp_suggested_hospitals', true);
        $listing_hospitals = $listing_hospitals_ids = array();
        $listingLocTax = array();
        foreach( $post_fields['medicalpro_hospitals'] as $medicalpro_hospital ){
            $hospital_id          =     isset($medicalpro_hospital['name']) ? $medicalpro_hospital['name'] : '';
            $business_hours       =     isset($medicalpro_hospital['business_hours']) ? $medicalpro_hospital['business_hours'] : '';
            $price                =     isset($medicalpro_hospital['price']) ? $medicalpro_hospital['price'] : '';
            $suggest              =     isset($medicalpro_hospital['suggest']) ? $medicalpro_hospital['suggest'] : '';
            $text                 =     isset($medicalpro_hospital['text']) ? $medicalpro_hospital['text'] : '';
            $location             =     isset($medicalpro_hospital['location']) ? $medicalpro_hospital['location'] : '';

            if ( $suggest == 'on' ) {
                if (!isset($arr) || !is_array($arr)) $arr = array();
                $suggestion = array(
                    'name' => $text,
                    'price' => $price,
                    'business_hours' => $business_hours,
                    'location' => $location,
                    'viewed' => false
                );
                if (!empty($text)) {
	                $arr[] = $suggestion;
                }
            }else {
                $listing_hospitals[$hospital_id]['price'] = $price;
                $listing_hospitals[$hospital_id]['business_hours'] = $business_hours;
                $listing_hospitals_ids[] = $hospital_id;
                $location = get_term_meta($hospital_id, 'hospital_locations', true);
                if (!empty($location) || $location != '' || $location != null || $location != ' ') $listingLocTax[] = $location;
            }
        }

        wp_set_post_terms($post_id, $listingLocTax, 'location');

        update_post_meta($post_id, 'mp_suggested_hospitals', $arr);

        if(isset($listing_hospitals_ids) && !empty($listing_hospitals_ids)){
            $listing_hospitals_ids = array_map('intval', $listing_hospitals_ids);
            $listing_hospitals_ids = array_unique($listing_hospitals_ids);
            $listing_hospitals_ids = wp_set_post_terms( $post_id, $listing_hospitals_ids, 'medicalpro-hospital' );
            update_post_meta( $post_id, 'medicalpro_listing_hospitals', $listing_hospitals );
        }else{
            wp_delete_object_term_relationships( $post_id, 'medicalpro-hospital' );
            delete_post_meta( $post_id, 'medicalpro_listing_hospitals' );
        }
    }else{
        wp_delete_object_term_relationships( $post_id, 'medicalpro-hospital' );
        delete_post_meta( $post_id, 'medicalpro_listing_hospitals' );
    }

}
add_action( 'medicalpro_save_listing_submission', 'medicalpro_save_listing_submission_callback', 30, 3 );