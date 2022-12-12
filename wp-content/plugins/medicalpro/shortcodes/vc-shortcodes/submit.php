<?php
ob_start();

/*------------------------------------------------------*/
/* Submit Listing
/*------------------------------------------------------*/
vc_map(array(
    "name"                      => __("Submit Listing", "js_composer"),
    "base"                      => 'medpro_submit',
    "category"                  => __('Medicalpro', 'js_composer'),
    "icon" => get_template_directory_uri() . "/assets/images/vcicon.png",
    "description"               => '',
    "params"                    => array(

        array(
            "type"            => "textfield",
            "class"            => "",
            "heading"        => __("Title", "js_composer"),
            "param_name"    => "title",
            "value"            => ""
        ),
        array(
            'type'        => 'textfield',
            'heading'     => __('Subtitle', 'js_composer'),
            'param_name'  => 'subtitle',
            'value'       => ''
        ),
    ),
));
if (!function_exists('medpro_shortcode_submit')) {
    function medpro_shortcode_submit($atts, $content = null)
    {

        extract(shortcode_atts(array(
            'title'   => '',
            'subtitle'   => ''
        ), $atts));

        do_action('lp_call_maps_scripts');
        /* PRIVACY URL */
        global $listingpro_options;
        $listing_access_only_users = $listingpro_options['lp_allow_vistor_submit'];
        $showAddListing = true;
        if (isset($listing_access_only_users) && $listing_access_only_users == 1) {
            $showAddListing = false;
            if (is_user_logged_in()) {
                $showAddListing = true;
            }
        }
        if ($showAddListing == false) {
            wp_redirect(home_url());
            exit;
        }

        $gSiteKey = '';
        $gSiteKey = $listingpro_options['lp_recaptcha_site_key'];
        $enableCaptcha = lp_check_receptcha('lp_recaptcha_listing_submission');

        $listing_mobile_view  = $listingpro_options['single_listing_mobile_view'];

        $lp_paid_mode = $listingpro_options['enable_paid_submission'];
        $privacy_policy = $listingpro_options['payment_terms_condition'];

        $paidmode = '';
        $paidmode = $listingpro_options['enable_paid_submission'];

        $enableUsernameField = false;
        if (isset($listingpro_options['lp_register_username'])) {
            if ($listingpro_options['lp_register_username'] == true) {
                $enableUsernameField = true;
            }
        }

        /* EDIT LIST */
        $quicktip_image = '';
        $lp_post = '';
        $form_field = '';
        $faq = '';
        $faqans = '';
        $gAddress = '';
        $latitude = '';
        $longitude = '';
        $timings = '';
        $phone = '';
        $email = '';
        $website = '';
        $twitter = '';
        $facebook = '';
        $linkedin = '';
        $listingcurrency = '';
        $listingprice = '';
        $listingptext = '';
        $video = '';

        /* MODE CHECK */
        if ($lp_paid_mode == "yes") {

            if (!isset($_POST['plan_id'])) {
                $lp_plans_url = $listingpro_options['pricing-plan'];
                if (!empty($lp_plans_url)) {
                    wp_redirect($lp_plans_url);
                    exit;
                } else {
                    wp_redirect(site_url());
                    exit;
                }
            }
        }


        /* PLAN ID */
        $plan_id = '';
        $cat_plan_id = '';
        if (isset($_POST['plan_id'])) {
            $plan_id = sanitize_text_field($_POST['plan_id']);
            if (!get_post_status($plan_id) || 'price_plan' != get_post_type($plan_id)) {
                $lp_plans_url = $listingpro_options['pricing-plan'];
                if (!empty($lp_plans_url)) {
                    wp_redirect($lp_plans_url);
                    exit;
                } else {
                    wp_redirect(site_url());
                    exit;
                }
            }
            if (isset($_POST['lp_pre_selected_cats'])) {
                $cat_plan_id = $_POST['lp_pre_selected_cats'];
            }
        } else {
            $plan_id = 'none';
        }


        $GLOBALS['plan_id_builder'] =   $plan_id;

        $contact_show = get_post_meta($plan_id, 'contact_show', true);
        $map_show = get_post_meta($plan_id, 'map_show', true);
        $video_show = get_post_meta($plan_id, 'video_show', true);
        $gallery_show = get_post_meta($plan_id, 'gallery_show', true);
        $featuredimg_show = get_post_meta($plan_id, 'featuredimg_show', true);
        $tagline_show = get_post_meta($plan_id, 'listingproc_tagline', true);
        $location_show = get_post_meta($plan_id, 'listingproc_location', true);
        $website_show = get_post_meta($plan_id, 'listingproc_website', true);
        $social_show = get_post_meta($plan_id, 'listingproc_social', true);
        $faqs_show = get_post_meta($plan_id, 'listingproc_faq', true);
        $price_show = get_post_meta($plan_id, 'listingproc_price', true);
        $tags_show = get_post_meta($plan_id, 'listingproc_tag_key', true);
        $plan_noOfIMG = get_post_meta($plan_id, 'plan_no_of_img', true);
        $plan_IMGSize = get_post_meta($plan_id, 'plan_img_lmt', true);
        $hours_show = get_post_meta($plan_id, 'listingproc_bhours', true);

        $b_logo = lp_theme_option('business_logo_switch');

        if ($plan_id == "none") {
            $contact_show = 'true';
            $map_show = 'true';
            $video_show = 'true';
            $gallery_show = 'true';
            $tagline_show = 'true';
            $location_show = 'true';
            $website_show = 'true';
            $social_show = 'true';
            $faqs_show = 'true';
            $price_show = 'true';
            $tags_show = 'true';
            $hours_show = 'true';
            $featuredimg_show = 'true';
            $b_logo = 'true';
        }
        $social_show_switch =   lp_theme_option('listin_social_switch');
        $ShowTitleTabs = false;
        /* $enableCustom = lp_theme_option('lp_listing_listing_by_custom'); */
        $enableGoogle = lp_theme_option('lp_listing_listing_by_google');

        // MedicalPro
        $insurances_show = get_post_meta($plan_id, 'insurances_show', true);
        $awards_show = get_post_meta($plan_id, 'awards_show', true);
        $video_consult_show = get_post_meta($plan_id, 'video_consult_show', true);
        if ($plan_id == "none") {
            $insurances_show = 'true';
            $awards_show = 'true';
            $video_consult_show = 'true';
        }
        // MedicalPro

        if ($enableGoogle == "yes") {
            $ShowTitleTabs = true;
        }

        /* SUBMIT FORM OUTPUT */
        $output = null;
        $page_style =   'style1';
        if (isset($listingpro_options['listing_submit_page_style']) && !empty($listingpro_options['listing_submit_page_style'])) {
            $page_style =   $listingpro_options['listing_submit_page_style'];
        }

        $form_page_heading_style    =   '';
        $style2_content_class       =   'col-md-6';
        $style_wrap                    =    '';
        $sidebar_sticky                =    '';
        $upload_icon                =    '';
        $lp_submit_sidebar_top      =    '';
        if ($page_style == 'style2') {
            $style2_content_class       =   'col-md-12';
            $form_page_heading_style    =   'form-page-heading_style2';
            $style_wrap                    =    'lp-style-wrap-border';
            $sidebar_sticky                =    'lp-submit-sidebar-sticky';
            $upload_icon                =    '<i class="fa fa-upload" aria-hidden="true"></i>';
        }
        if (is_user_logged_in() && $page_style == 'style2') {
            $lp_submit_sidebar_top  =    '33px';
        }

        $quicktip_title = lp_theme_option_url('submit_ad_img_title');
        $quicktip_adress = lp_theme_option_url('submit_ad_img_faddress');
        $quicktip_city = lp_theme_option_url('submit_ad_img_city');
        $quicktip_phone = lp_theme_option_url('submit_ad_img_phone');
        $quicktip_website = lp_theme_option_url('submit_ad_img_website');
        $quicktip_social = lp_theme_option_url('submit_ad_img_socialmedia');
        $quicktip_cat = lp_theme_option_url('submit_ad_img_categories');
        $quicktip_price = lp_theme_option_url('submit_ad_img_pricerange');
        $quicktip_faq = lp_theme_option_url('submit_ad_img_faq');
        $quicktip_video = lp_theme_option_url('submit_ad_img_video');
        $quicktip_gallery = lp_theme_option_url('submit_ad_img_gallery');
        $quicktip_desc = lp_theme_option_url('submit_ad_img_desc');
        $quicktip_biz = lp_theme_option_url('submit_ad_img_busincesshours');
        $quicktip_b_logo = lp_theme_option_url('submit_ad_img_b_logo');



        $lp_images_count = '555';
        $lp_images_size = '999999999999999999999999999999999999999999999999999';
        $lp_imagecount_notice = '';
        $lp_imagesize_notice = '';
        if (lp_theme_option('lp_listing_images_count_switch') == 'yes' && empty($plan_noOfIMG)) {
            $lp_images_count = lp_theme_option('lp_listing_images_counter');
            $lp_imagecount_notice = esc_html__("Max. allowed images are ", 'medicalpro');
            $lp_imagecount_notice .= $lp_images_count;
        }
        if (lp_theme_option('lp_listing_images_size_switch') == 'yes') {
            $lp_images_size = lp_theme_option('lp_listing_images_sizes');
            $lp_imagesize_notice = esc_html__('Max. allowed images size is ', 'medicalpro');
            $lp_imagesize_notice .= $lp_images_size . esc_html__(' Mb', 'medicalpro');
            $lp_images_sizeD = $lp_images_size;
            $lp_images_size = $lp_images_size * 1000000;
        }


        //plan img limit
        if (!empty($plan_noOfIMG)) {
            $lp_images_count = $plan_noOfIMG;
            $lp_imagecount_notice = esc_html__("Max. allowed images are ", 'medicalpro');
            $lp_imagecount_notice .= $lp_images_count;
        }
        if (!empty($plan_IMGSize)) {
            $lp_images_sizeD = $plan_IMGSize;
            $lp_images_size = $plan_IMGSize * 1000000;
            $lp_imagesize_notice = esc_html__('Max. allowed images size is ', 'medicalpro');
            $lp_imagesize_notice .= $lp_images_sizeD . esc_html__(' Mb', 'medicalpro');
        }
        //end plan img limit

        $whatsappButton = false;
        if (lp_theme_option('lp_detail_page_whatsapp_button') == "on") {
            $whatsappButton = true;
        }



        $output .= '
		<div class="page-container-four clearfix submit_new_style submit_new_style-outer">
			<div class="col-md-12 col-sm-12">
				<div class="form-page-heading ' . $form_page_heading_style . '">
					<h3>' . $title . '</h3>
					<p>' . $subtitle . '</p>
				</div>
				<div class="post-submit">';

        if (is_user_logged_in()) {
            $output .= '
						<div class="author-section border-bottom lp-form-row clearfix lp-border-bottom padding-bottom-40">
							<div class="lp-form-row-left text-left pull-left not-logged-in-msg">
								<img class="avatar-circle" src="' . listingpro_author_image() . '" />
								<p>' . esc_html__('You are currently signed in as', 'medicalpro') . ' <strong>' . listingpro_author_name() . ',</strong> <a href="' . wp_logout_url(esc_url(home_url('/'))) . '" class="">' . esc_html__('Sign out', 'medicalpro') . '</a> ' . esc_html__('or continue below and start submission.', 'medicalpro') . '</p>
							</div>
						</div>';
        } else {
            if ($listing_mobile_view == 'app_view' && wp_is_mobile()) {
                $output .=
                    '<div class="author-section border-bottom lp-form-row clearfix lp-border-bottom padding-bottom-40">
							<div class="lp-form-row-left text-left pull-left not-logged-in-msg">
								<!-- <img class="avatar-circle" src="' . plugins_url('/images/author.jpg', dirname(__FILE__)) . '" /> -->
								<p><strong>' . esc_html__('Returning User? Please', 'medicalpro') . '</strong> <a class="md-trigger" data-toggle="modal" data-target="#app-view-login-popup">' . esc_html__('Sign In', 'medicalpro') . '</a> ' . esc_html__('and if you are a ', 'medicalpro') . ' <strong>' . esc_html__('New User, continue below ', 'medicalpro') . '</strong>' . esc_html__('and register along with this submission.', 'medicalpro') . '</p>
							</div>                        
						</div>';
            } else {
                $output .=
                    '<div class="author-section border-bottom lp-form-row clearfix lp-border-bottom padding-bottom-40">
							<div class="lp-form-row-left text-left pull-left not-logged-in-msg">
								<!-- <img class="avatar-circle" src="' . plugins_url('/images/author.jpg', dirname(__FILE__)) . '" /> -->
								<p><strong>' . esc_html__('Returning User? Please', 'medicalpro') . '</strong> <a class=" md-trigger" data-modal="modal-3">' . esc_html__('Sign In', 'medicalpro') . '</a> ' . esc_html__('and if you are a ', 'medicalpro') . ' <strong>' . esc_html__('New User, continue below ', 'medicalpro') . '</strong>' . esc_html__('and register along with this submission.', 'medicalpro') . '</p>
							</div>						
						</div>';
            }
        }

        $quickTipTitle = $listingpro_options['quick_tip_title'];
        $quickTipText = $listingpro_options['quick_tip_text'];
        $submitImg = $listingpro_options['submit_ad_img']['url'];
        $submitImg1 = $listingpro_options['submit_ad_img1']['url'];
        $submitImg2 = $listingpro_options['submit_ad_img2']['url'];
        $submitImg3 = $listingpro_options['submit_ad_img3']['url'];

        /* Submit Fields ON/OFF */
        $listing_title_text = $listingpro_options['listing_title_text'];
        $listingCityText = $listingpro_options['listing_city_text'];
        $listingGaddText = $listingpro_options['listing_gadd_text'];
        $listingGaddcustomText = $listingpro_options['listing_custom_cordn'];
        $addressSwitch = $listingpro_options['lp_showhide_address'];
        $phoneSwitch = $listingpro_options['phone_switch'];
        $listingPhText = $listingpro_options['listing_ph_text'];
        $webSwitch = $listingpro_options['web_switch'];
        $listingWebText = $listingpro_options['listing_web_text'];
        $ophSwitch = $listingpro_options['oph_switch'];
        $listing_cat_text = $listingpro_options['listing_cat_text'];
        $listing_features_text = $listingpro_options['listing_features_text'];
        $currencySwitch = $listingpro_options['currency_switch'];
        $listingCurrText = $listingpro_options['listing_curr_text'];
        $digitPriceSwitch = $listingpro_options['digit_price_switch'];
        $listingDigitText = $listingpro_options['listing_digit_text'];
        $priceSwitch = $listingpro_options['price_switch'];
        $listingPriceText = $listingpro_options['listing_price_text'];
        $listing_desc_text = $listingpro_options['listing_desc_text'];
        $faq_switch = $listingpro_options['faq_switch'];
        $listing_faq_text = $listingpro_options['listing_faq_text'];
        $listing_faq_tabs_text = $listingpro_options['listing_faq_tabs_text'];
        $twSwitch = $listingpro_options['tw_switch'];
        $fbSwitch = $listingpro_options['fb_switch'];
        $lnkSwitch = $listingpro_options['lnk_switch'];
        $ytSwitch = $listingpro_options['yt_switch'];
        $instaSwitch = $listingpro_options['insta_switch'];
        $tags_switch = $listingpro_options['tags_switch'];
        $listingTagsText = $listingpro_options['listing_tags_text'];
        $vdoSwitch = $listingpro_options['vdo_switch'];
        $listingVdoText = $listingpro_options['listing_vdo_text'];
        $fileSwitch = $listingpro_options['file_switch'];
        $listingEmailText = $listingpro_options['listing_email_text'];
        $listingUserNameText = '';
        if (isset($listingpro_options['listing_username_text'])) {
            $listingUserNameText = $listingpro_options['listing_username_text'];
        }

        $submit_ad_img_switch = $listingpro_options['submit_ad_img_switch'];
        $submit_ad_img1_switch = $listingpro_options['submit_ad_img1_switch'];
        $submit_ad_img2_switch = $listingpro_options['submit_ad_img2_switch'];
        $submit_ad_img3_switch = $listingpro_options['submit_ad_img3_switch'];
        $quick_tip_switch = $listingpro_options['quick_tip_switch'];

        $listing_btn_text = $listingpro_options['listing_btn_text'];
        $showLocation = $listingpro_options['location_switch'];

        //        Disabled With Medicalpro;
        $showLocation = 0;
        $location_show = false;
        $singleLocMode = true;

        if (isset($listingpro_options['lp_listing_location_mode'])) {
            if ($listingpro_options['lp_listing_location_mode'] == "multi") {
                $singleLocMode = false;
            }
        }

        $singleCatMode = true;
        if (isset($listingpro_options['lp_listing_category_mode'])) {
            if ($listingpro_options['lp_listing_category_mode'] == "multi") {
                $singleCatMode = false;
            }
        }


        $btnText = '';
        if (!empty($listing_btn_text)) {
            $btnText = $listing_btn_text;
        } else {
            $btnText = esc_html__('Save & Preview', 'medicalpro');
        }

        if(isset($listingpro_options['lp_listing_locations_options'])){
            $locations_type = $listingpro_options['lp_listing_locations_options'];
        }
        
        $locArea = '';
        if (!empty($locations_type) && $locations_type == "auto_loc") {
            $locArea = $listingpro_options['lp_listing_locations_range'];
        }

        $output .= '<div class="clearfix"></div>
					<form data-lp-recaptcha="' . $enableCaptcha . '" data-lp-recaptcha-sitekey="' . $gSiteKey . '" method="post" enctype=multipart/form-data id="lp-submit-form" name="lp-submit-form" data-imgcount="' . $lp_images_count . '" data-imgsize="' . $lp_images_size . '" data-countnotice="' . $lp_imagecount_notice . '" data-sizenotice="' . $lp_imagesize_notice . '">';

        if ($enableGoogle == 'yes') {
            $output .=  '<div class="fill-o-bot-wrap">
									<div class="row">
										<div class="col-md-2">
											<img src="' . get_template_directory_uri() . '/assets/images/bot-icon.png" alt="">
										</div>
										<div class="col-md-7">
											<strong>' . esc_html__('Feeling lazy? Say HELLO! to Fill-O-Bot! ', 'medicalpro') . '</strong>
											<p>' . esc_html__('Don’t worry Fill-o-BOT will help you OUT! Just enter any business on google places and Fill-O-Bot automatically fills in the data that it can grab.', 'medicalpro') . '</p>
										</div>
										<div class="col-md-3">
											<div class="bot-on-tag-wrap">
												<span class="bot-on-tag">' . esc_html__('Turn Me On', 'medicalpro') . '</span>
												<label class="switch">									
													<input id="fill-o-bot-check" class="form-control switch-checkbox" type="checkbox" name="fil-o-bot-check">										
													<div class="slider round"></div>
												</label>
											</div>
										</div>
									</div>
									
								</div>';
        }
        $submit_form_builder_state  =   get_option('listing_submit_form_state');
        $listing_submit_form_data   =   get_option('listing_submit_form_data');
        if (isset($submit_form_builder_state) && $submit_form_builder_state == 1 && isset($listing_submit_form_data) && !empty($listing_submit_form_data)) {
            $output .=  '<div class="row">
                <div class="col-md-8 page-style2-content-wrap">';
            $output .= do_shortcode($listing_submit_form_data);
            if (!is_user_logged_in()) {
                $output .= '
								<div class="white-section border-bottom ' . $style_wrap . '">
									<div class="row">
										<div class="form-group col-md-12 margin-bottom-0">';
                $output .= '
												<div class="lp-submit-accoutn lp-submit-accoutn-wrap">
													<div class="lp-submit-no-account">';
                if (!empty($enableUsernameField)) {
                    $output .= '<div class="row"><div class="col-md-6">';
                }
                $output .= '<label for="inputEmail">' . $listingEmailText . '</label>
														<input type="email" class="form-control" name="email" id="inputEmail" placeholder="' . esc_html__('your contact email', 'medicalpro') . '">';

                if (!empty($enableUsernameField)) {
                    $output .= '</div><div class="col-md-6">';
                    $output .= '<label for="customUname">' . $listingUserNameText . '</label>
														<input type="text" class="form-control" name="customUname" id="customUname" placeholder="' . esc_html__('user name', 'medicalpro') . '">';
                    $output .= '</div></div>';
                }
                $output .= '</div>
													<div class="lp-submit-have-account row" style="display: none;">
														<div class="col-md-6"><label for="inputUsername">' . esc_html__('Email', 'medicalpro') . '</label>
														<input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="' . esc_html__('enter username', 'medicalpro') . '"></div>
														<div class="col-md-6"><label for="inputUserpass">' . esc_html__('Password', 'medicalpro') . '</label>
														<input type="password" class="form-control" name="inputUserpass" id="inputUserpass" placeholder="' . esc_html__('enter password', 'medicalpro') . '"></div>
						</div>
												</div>
												<div class="checkbox already-account-checkbox"> <input type="checkbox" id="already-account" value=""><label for="already-account" class="already-account">' . esc_html__('Already Have Account?', 'medicalpro') . '</label></div>
			
												';
                $output .=  '</div>
									</div>
								</div>';
            }
            if (!empty($privacy_policy)) {
                $output .= '
								<div class="white-section">
									<div class="row">';
                if (!empty($privacy_policy) && lp_theme_option('listingpro_privacy_listing') == 'yes') {
                    $output .= ' 			<div class="form-group col-md-12 margin-bottom-0">
													<div class="checkbox form-group col-md-4 check_policy termpolicy">
														<input id="policycheck" type="checkbox" name="policycheck" value="true">
														<label for="policycheck"><a target="_blank" href="' . get_the_permalink($privacy_policy) . '" class="help" target="_blank">' . esc_html__('I Agree', 'medicalpro') . '</a></label>
														<div class="help-text">
															<a class="help" target="_blank"><i class="fa fa-question"></i></a>
															<div class="help-tooltip">
																<p>' . esc_html__('You agree you accept our Terms & Conditions for posting this ad.', 'medicalpro') . '</p>
															</div>
														</div>
													</div>
											  </div>';
                }

                $output .=  '</div>
									</div>';
            }
            $output .= '<div class="submitbutton-wraper submitbutton-wraper-style2">';
            $output .= ' <div class="success_box">' . esc_html__('All of the fields were successfully validated!', 'medicalpro') . '</div>
									<div class="error_box"></div>
									<input type="hidden" name="plan_id" value="' . $plan_id . '" /> 
									<input type="hidden" name="errorrmsg" value="' . esc_html__('Something is missing! Please fill out all fields highlighted in red.', 'medicalpro') . '" /> 
									<input type="submit" id="listingsubmitBTN" name="listingpost" value="' . $btnText . '" class="lp-secondary-btn btn-first-hover" />
									<i class="fa bottomofbutton lpsubmitloading"></i>
								
								</div>';
            $output .= wp_nonce_field('post_nonce', 'post_nonce_field', true, false);

            $output .=  '</div>';
            $output .=  '<div class="col-md-4 page-style2-sidebar-wrap ' . $sidebar_sticky . '" style="top: ' . $lp_submit_sidebar_top . ';">
										<div class="page-style2-sidebar">';
            if ($quick_tip_switch == 1) {
                $output .= '
										<div class="quick_tip quick_tip_style2 ' . $style_wrap . '">
											<div class="quick-tip-inner">
											<h2>' . $quickTipTitle . '</h2>
												<p>' . $quickTipText . '</p>';
                if ($submit_ad_img_switch == 1) {
                    $output .= '
													<div class="submit-img">
														<img src="' . $submitImg . '" alt="">
													</div>';
                }
                $output .=  '</div>';
                $output .=  '</div>';
            }

            $output .=  '</div>
	</div>
	<div class="clearfix"></div> </div>';
            $output .=  '</div>';
        } else {
            if ($page_style == 'style2') {
                $output .=  '<div class="row">';
                $output .=  '<div class="col-md-8 page-style2-content-wrap">';
            }

            if ($page_style == 'style2') {
                $output .= '<div class="white-section border-bottom ' . $style_wrap . '">';
                $output .=  '<h4 class="white-section-heading">' . esc_html__('primary listing details', 'medicalpro') . '</h4>';
                $output .=  '   <div class="row">';

                $output .= '        <div class="form-group ' . $style2_content_class . ' col-xs-12">';
                $output .= '            <div id="lp_custom_title" class="tab-pane fade in active">
													<label for="usr">' . $listing_title_text . ' <small>*</small></label>
													<div class="help-text">
														<a href="#" class="help"><i class="fa fa-question"></i></a>
														<div class="help-tooltip">
															<p>' . esc_html__('Put your listing title here and tell the name of your business to the world.', 'medicalpro') . '</p>
														</div>
													</div>
													<input data-img_src="' . $quicktip_title . '" data-quick-tip="<h2>' . esc_html__('Title', 'medicalpro') . '</h2><p>' . esc_html__('Enter your complete business name for when people who know your business by name and are looking you up.', 'medicalpro') . '</p>" type="text" name="postTitle" class="form-control margin-bottom-10" id="lptitle" placeholder="' . esc_html__('Dr. John Doe', 'medicalpro') . '">';
                if ($enableGoogle == "yes") {

                    $output .= '<input data-img_src="' . $quicktip_title . '" data-quick-tip="<h2>' . esc_html__('Title', 'medicalpro') . '</h2><p>' . esc_html__('Enter your complete business name for when people who know your business by name and are looking you up.', 'medicalpro') . '</p>" type="hidden" id="lptitleGoogle" name="" class="form-control margin-bottom-10 lptitle" placeholder="' . esc_html__('Dr. John Doe', 'medicalpro') . '">
														<div id="lp_listing_map"></div>';
                }
                $output .= '
												</div>';
                $output .= '        </div>';

                if ($tagline_show == "true") {
                    $output .= '<div class="lp-tagline-submit-tagline">
											<label>' . esc_html__('Does Your Profile have a tagline?', 'medicalpro') . '
											  <input type="checkbox">
											  <span class="lp-sbt-checkmark"></span>
											</label>
											</div>
							<div class="form-group ' . $style2_content_class . ' col-xs-12 with-title-cond">';
                    $output .= '            <label for="usr">' . esc_html__('Tagline', 'medicalpro') . '</label>';
                    $output .= '            <input data-img_src="' . $quicktip_title . '" data-quick-tip="<h2>' . esc_html__('Tagline', 'medicalpro') . '</h2><p>' . esc_html__('For businesses, taglines are of importance as they help business convey what they want to do and their goals to the customers.', 'medicalpro') . '</p>" type="text" name="tagline_text" class="form-control margin-bottom-10" id="lptagline" placeholder="' . esc_html__('10 Years+ Experiance', 'medicalpro') . '">';
                    $output .= '        </div>';
                }
                if ($addressSwitch == 1) {
                    $output .= '
									<div class="form-group ' . $style2_content_class . ' col-xs-12">
										<div class="lp-coordinates">
											<a data-type="gaddress" class="btn-link googleAddressbtn active">' . esc_html__('Search By Google', 'medicalpro') . '</a>
											<a data-type="gaddresscustom" class="btn-link googleAddressbtn">' . esc_html__('Manual Coordinates', 'medicalpro') . '</a>';
                    if ((is_ssl()) || (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')))) {
                        $output .= '
													<a data-type="gaddresscustom" class="btn-link googledroppin" data-toggle="modal" data-target="#modal-doppin"><i class="fa fa-map-pin"></i> ' . esc_html__('Drop Pin', 'medicalpro') . '</a>';
                    }
                    $output .= '
										</div>
										<label for="inputAddress" class="googlefulladdress">' . $listingGaddText . '</label>
										
										<div class="help-text googlefulladdress">
											<a href="#" class="help"><i class="fa fa-question"></i></a>
											<div class="help-tooltip">
												<p>' . esc_html__('Start typing and select your google location from google suggestions. This is for the map and also for locating your business.', 'medicalpro') . '</p>
											</div>
										</div>
										
										<input data-img_addresssrc="' . $quicktip_adress . '"  data-quick-tip="<h2>' . esc_html__('Full Address', 'medicalpro') . '</h2><p>' . esc_html__('Provide your full address for your business to show up on the map and your customer can get direction.', 'medicalpro') . '</p>" type="text" class="form-control form-control-st" name="gAddress" id="inputAddress" placeholder="' . esc_html__('Start typing and find your place in google map', 'medicalpro') . '">
										<div class="lp-custom-lat clearfix">
											<label for="inputAddress">' . $listingGaddcustomText . '</label>
											<input type="text" class="form-control form-control-st" name="gAddresscustom" id="inputAddresss" placeholder="' . esc_html__('Add address here', 'medicalpro') . '">
											<div class="row hiddenlatlong">
												<div class="col-md-6 col-xs-6">
												<label for="latitude">' . esc_html__('Latitude', 'medicalpro') . '</label>
												<input class="form-control" type="hidden" placeholder="' . esc_html__('40.7143528', 'medicalpro') . '" id="latitude" name="latitude">
												</div>
												<div class="col-md-6 col-xs-6">
												<label for="longitude">' . esc_html__('Longitude', 'medicalpro') . '</label>
												<input class="form-control" type="hidden" placeholder="' . esc_html__('-74.0059731', 'medicalpro') . '" id="longitude" name="longitude">
												</div>
											</div>
										</div>
									</div>
									</div>
								<div class="row">';
                }

                if ($showLocation == "1" && $location_show == "true") {
                    if (!empty($locations_type) && $locations_type == "auto_loc") {
                        if ($singleLocMode == true) {
                            $output .= '
											<div class="form-group ' . $style2_content_class . ' col-xs-12 lp-new-cat-wrape">
												<label for="inputTags">' . $listingCityText . '</label>
												<div class="help-text">
													<a href="#" class="help"><i class="fa fa-question"></i></a>
													<div class="help-tooltip">
														<p>' . esc_html__('The city name will help users find you in search filters.', 'medicalpro') . '</p>
													</div>
												</div>';
                            $output .= '<input id="citiess" name="locationn" data-isseleted="false" class="form-control ostsubmitSelect" autocomplete="off" data-country="' . $locArea . '" placeholder="' . esc_html__('select your listing region', 'medicalpro') . '">
												<input type="hidden" name="location">';
                            $output .= '	
											</div>';
                        } else {
                            $output .= '<div class="form-group lp-selected-locs clearfix col-md-12"></div>';
                            $output .= '
											<div class="form-group ' . $style2_content_class . ' col-xs-12 lp-new-cat-wrape">
												<label for="inputTags">' . $listingCityText . '</label>
												<div class="help-text">
													<a href="#" class="help"><i class="fa fa-question"></i></a>
													<div class="help-tooltip">
														<p>' . esc_html__('The city name will help users find you in search filters.', 'medicalpro') . '</p>
													</div>
												</div>';
                            $output .= '<input id="citiess" name="locationn" data-isseleted="false" class="form-control ostsubmitSelect" autocomplete="off" data-country="' . $locArea . '" placeholder="' . esc_html__('select your listing region', 'medicalpro') . '">';
                            $output .= '</div>';
                        }
                    } elseif (!empty($locations_type) && $locations_type == "manual_loc") {
                        $output .= '<div class="form-group ' . $style2_content_class . ' col-xs-12 lp-new-cat-wrape lp-new-cat-wrape">
											<label for="inputTags">' . $listingCityText . '</label>
											<div class="help-text">
												<a href="#" class="help"><i class="fa fa-question"></i></a>
												<div class="help-tooltip">
													<p>' . esc_html__('The city name will help users find you in search filters.', 'medicalpro') . '</p>
												</div>
											</div>';
                        if ($singleLocMode == true) {
                            $output .= '<select data-cityimg="' . $quicktip_city . '" data-quick-tip="<h2>' . esc_html__('City', 'medicalpro') . '</h2><p>' . esc_html__('Provide your city name for your business to show up on the map and your customer can get direction.', 'medicalpro') . '</p>" data-placeholder="' . esc_html__('select your listing region', 'medicalpro') . '" id="inputCity" name="location[]" class="select2 postsubmitSelect" tabindex="5">';
                        } else {
                            $output .= '<select data-cityimg="' . $quicktip_city . '" data-quick-tip="<h2>' . esc_html__('Full Address', 'medicalpro') . '</h2><p>' . esc_html__('Provide your full address for your business to show up on the map and your customer can get direction.', 'medicalpro') . '</p>" data-placeholder="' . esc_html__('select your listing region', 'medicalpro') . '" id="inputCity" name="location[]" class="select2 postsubmitSelect" tabindex="5" multiple="multiple">';
                        }


                        $output .= '<option value="">' . esc_html__('Select City', 'medicalpro') . '</option>';
                        $args = array(
                            'post_type' => 'listing',
                            'order' => 'ASC',
                            'hide_empty' => false,
                            'parent' => 0,
                        );
                        $locations = get_terms('location', $args);
                        if (!empty($locations)) {
                            foreach ($locations as $location) {
                                $output .= '<option value="' . $location->term_id . '">' . $location->name . '</option>';
                                $argsChild = array(
                                    'order' => 'ASC',
                                    'hide_empty' => false,
                                    'hierarchical' => false,
                                    'parent' => $location->term_id,


                                );
                                $childLocs = get_terms('location', $argsChild);
                                if (!empty($childLocs)) {
                                    foreach ($childLocs as $childLoc) {
                                        $output .= '<option value="' . $childLoc->term_id . '">-&nbsp;' . $childLoc->name . '</option>';

                                        $argsChildof = array(
                                            'order' => 'ASC',
                                            'hide_empty' => false,
                                            'hierarchical' => false,
                                            'parent' => $childLoc->term_id,
                                        );
                                        $childLocsof = get_terms('location', $argsChildof);
                                        if (!empty($childLocsof)) {
                                            foreach ($childLocsof as $childLocof) {
                                                $output .= '<option value="' . $childLocof->term_id . '">--&nbsp;' . $childLocof->name . '</option>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $output .= '</select>';
                        $output .= '</div>';
                    }
                }

                if ($phoneSwitch == 1) {
                    if ($contact_show == "true") {
                        $output .= '
										<div class="form-group ' . $style2_content_class . ' col-xs-12">
											<label for="inputPhone">' . $listingPhText . '</label>
											<input data-phoneimg="' . $quicktip_phone . '" data-quick-tip="<h2>' . esc_html__('Phone', 'medicalpro') . '</h2><p>' . esc_html__('Local phone numbers drive 3x more calls than toll-free numbers. Always use a business phone number and avoid personal phone numbers if possible.', 'medicalpro') . '</p>" type="text" class="form-control" name="phone" id="inputPhone" placeholder="' . esc_html__('111-111-1234', 'medicalpro') . '">
										</div>';
                        if (!empty($whatsappButton)) {
                            $whatsappLable = lp_theme_option('lp_whatsapp_label');
                            $output .= '
                                            <div class="form-group ' . $style2_content_class . ' col-xs-12">
                                                <label for="inputWhatsapp">' . $whatsappLable . '</label>
                                                <input data-whatsappimg="' . $quicktip_phone . '" data-quick-tip="<h2>' . esc_html__('Whatsapp no.', 'medicalpro') . '</h2><p>' . esc_html__('Whatsapp no for listing detail page.', 'medicalpro') . '</p>" type="text" class="form-control" name="whatsapp" id="inputWhatsapp" placeholder="' . esc_html__('+44994981258', 'medicalpro') . '">
                                            </div>';
                        }
                    }
                }

                if ($webSwitch == 1 && $website_show == "true") {
                    $output .= '
									<div class="form-group ' . $style2_content_class . ' col-xs-12">
										<label for="inputWebsite">' . $listingWebText . '</label>
										<input data-webimg="' . $quicktip_website . '" data-quick-tip="<h2>' . esc_html__('Website', 'medicalpro') . '</h2><p>' . esc_html__('Its recommended to provide official website url and avoid landing pages designed for a specific campaign', 'medicalpro') . '</p>" type="text" class="form-control" name="website" id="inputWebsite" placeholder="' . esc_html__('http://', 'medicalpro') . '">
									</div>';
                }

                $output .=  '   </div>';
                $output .=  '</div>';

                $output = apply_filters('medicalpro_hospital_submission', $output, $style_wrap, '', $page_style);

                $output .= '<div class="white-section border-bottom ' . $style_wrap . '">';
                $output .=  '<h4 class="white-section-heading">' . esc_html__('Speciality', 'medicalpro') . '</h4>';
                $output .=  '   <div class="row">';
                $output .= '        <div class="form-group clearfix margin-bottom-0 lp-new-cat-wrape col-md-12">
												<label for="inputCategory">' . $listing_cat_text . ' <small>*</small></label>';

                if (!empty($cat_plan_id)) {
                    /* for pre category selection */
                    $output .= '<input type="hidden" name="lppre_plan_cats" value="true" />';
                    $output .= '<select data-catimg="' . $quicktip_cat . '" data-quick-tip="<h2>' . esc_html__('Speciality', 'medicalpro') . '</h2><p>' . esc_html__('The more specific you get with your categories, the better. You do still want to stay relevant to your business, though. If you ever choose to run ads campaign, your ad will be shown on those categories you select.', 'medicalpro') . '</p>" data-placeholder="' . esc_html__('Choose Your Business Category', 'medicalpro') . '" id="inputCategory" name="category[]" class="select2 postsubmitSelect" tabindex="5">';
                    $selectedCatObj = get_term_by('id', $cat_plan_id, 'listing-category');
                    $selectedCatName = $selectedCatObj->name;
                    $doAjax = false;
                    $doAjax = lp_category_has_features($selectedCatObj->term_id);
                    $output .= '<option data-doajax="' . $doAjax . '" value="' . $cat_plan_id . '">' . $selectedCatName . '</option>';
                    $output .= '</select>';
                } else {
                    if ($singleCatMode == true) {
                        $output .= '<select data-catimg="' . $quicktip_cat . '" data-quick-tip="<h2>' . esc_html__('Speciality', 'medicalpro') . '</h2><p>' . esc_html__('The more specific you get with your categories, the better. You do still want to stay relevant to your business, though. If you ever choose to run ads campaign, your ad will be shown on those categories you select.', 'medicalpro') . '</p>" data-placeholder="' . esc_html__('Choose Your Business Category', 'medicalpro') . '" id="inputCategory" name="category[]" class="select2 postsubmitSelect" tabindex="5">';
                    } else {
                        $output .= '<select data-catimg="' . $quicktip_cat . '" data-quick-tip="<h2>' . esc_html__('Speciality', 'medicalpro') . '</h2><p>' . esc_html__('The more specific you get with your categories, the better. You do still want to stay relevant to your business, though. If you ever choose to run ads campaign, your ad will be shown on those categories you select.', 'medicalpro') . '</p>" data-placeholder="' . esc_html__('Choose Your Business Category', 'medicalpro') . '" id="inputCategory" name="category[]" class="select2 postsubmitSelect" tabindex="5" multiple="multiple">';
                    }
                    $output .= '<option value="">' . esc_html__('Select Speciality', 'medicalpro') . '</option>';
                    $args = array(
                        'post_type' => 'listing',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                    );
                    $categories = get_terms('listing-category', $args);
                    if (!empty($categories)) {
                        foreach ($categories as $category) {

                            $doAjax = false;
                            $doAjax = lp_category_has_features($category->term_id);
                            $output .= '<option data-doajax="' . $doAjax . '" value="' . $category->term_id . '">' . $category->name . '</option>';

                            $argscatChild = array(
                                'order' => 'ASC',
                                'hide_empty' => false,
                                'hierarchical' => false,
                                'parent' => $category->term_id,

                            );
                            $childCats = get_terms('listing-category', $argscatChild);
                            if (!empty($childCats)) {
                                foreach ($childCats as $subID) {
                                    $doAjax = false;
                                    $doAjax = lp_category_has_features($subID->term_id);
                                    $output .= '<option data-doajax="' . $doAjax . '"  class="sub_cat" value="' . $subID->term_id . '">-&nbsp;&nbsp;' . $subID->name . '</option>';

                                    $childCatsof = array(
                                        'order' => 'ASC',
                                        'hide_empty' => false,
                                        'hierarchical' => false,
                                        'parent' => $subID->term_id,
                                    );
                                    $childofCats = get_terms('listing-category', $childCatsof);
                                    if (!empty($childofCats)) {
                                        foreach ($childofCats as $subIDD) {
                                            $doAjax = false;
                                            $doAjax = lp_category_has_features($subIDD->term_id);
                                            $output .= '<option data-doajax="' . $doAjax . '"  class="sub_cat" value="' . $subIDD->term_id . '">--&nbsp;&nbsp;' . $subIDD->name . '</option>';

                                            $childCatsoff = array(
                                                'order' => 'ASC',
                                                'hide_empty' => false,
                                                'hierarchical' => false,
                                                'parent' => $subIDD->term_id,
                                            );
                                            $childofCatss = get_terms('listing-category', $childCatsoff);
                                            if (!empty($childofCatss)) {
                                                foreach ($childofCatss as $subIDDD) {
                                                    $doAjax = false;
                                                    $doAjax = lp_category_has_features($subIDDD->term_id);
                                                    $output .= '<option data-doajax="' . $doAjax . '"  class="sub_cat" value="' . $subIDDD->term_id . '">---&nbsp;&nbsp;' . $subIDDD->name . '</option>';
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $output .= '            </select>';
                }
                $output .=  '       </div>';
                $output .=  '   </div>';
                $output .=  '</div>';


                $output .= '<div class="featuresDataContainerOuterSubmit white-section border-bottom ' . $style_wrap . '">';
                $output .=  '   <div class="row">';
                $output .=          listingpro_get_term_openfields(false);
                $output .= '        <div class="form-group clearfix lpfeatures_fields col-md-12">
												<div class="pre-load"></div>
												<div class="featuresDataContainerr lp-nested row" id="tags-by-cat"></div>	
												<div class="featuresDataContainer lp-nested row" id="features-by-cat"></div>
											</div>';
                $output .=  '   </div>';
                $output .=  '</div>';

                $args = array(
                    'post_type'  => 'listing',
                    'order'      => 'ASC',
                    'hide_empty' => false,
                    'parent'     => 0,
                );
                $insurances = get_terms('medicalpro-insurance', $args);

                if ($insurances_show == 'true') {
                    $output .= '<div class="white-section border-bottom ' . $style_wrap . '">';
                    $output .= '<h4 class="white-section-heading">' . esc_html__('Insurances', 'medicalpro') . '</h4>';
                    $output .= '<div class="row">';
                    $output .= '<div class="col-md-12 col-xs-12">';
                    $output .= '<div class="form-group clearfix margin-bottom-0 lp-new-cat-wrape col-md-12">
                                <label for="inputInsurances">' . esc_html__('Insurances', 'medicalpro') . '<small>*</small></label>';
                    $output .= '<select data-catimg="' . $quicktip_cat . '" data-quick-tip="<h2>' . esc_html__('Insurances', 'medicalpro') . '</h2><p>' . esc_html__('The more specific you get with your insurances, the better. You do still want to stay relevant to your business, though. If you ever choose to run ads campaign, your ad will be shown on those insurances you select.', 'medicalpro') . '</p>" data-placeholder="' . esc_html__('Choose Your Insurance', 'medicalpro') . '" id="inputInsurances" name="insurance[]" class="select2 postsubmitSelect" tabindex="5" multiple="multiple">';
                    $output .= '<option value="">' . esc_html__('Select Insurance', 'medicalpro') . '</option>';
                    if (isset($insurances) && !empty($insurances)) {
                        foreach ($insurances as $insurance) {
                            $output .= '<option value="' . esc_attr($insurance->term_id) . '">' . esc_html($insurance->name) . '</option>';
                        }
                    }
                    $output .= '</select>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                }
                $args = array(
                    'post_type'  => 'listing',
                    'order'      => 'ASC',
                    'hide_empty' => false,
                    'parent'     => 0,
                );
                $awards = get_terms('medicalpro-award', $args);
                if ($awards_show == 'true') {
                    $output .= '<div class="white-section border-bottom ' . $style_wrap . '">';
                    $output .= '<h4 class="white-section-heading">' . esc_html__('Awards', 'medicalpro') . '</h4>';
                    $output .= '<div class="row">';
                    $output .= '<div class="col-md-12 col-xs-12">';
                    $output .= '<div class="form-group clearfix margin-bottom-0 lp-new-cat-wrape col-md-12">
                                <label for="inputAwards">' . esc_html__('Awards', 'medicalpro') . '</label>';
                    $output .= '<select data-catimg="' . $quicktip_cat . '" data-quick-tip="<h2>' . esc_html__('Awards', 'medicalpro') . '</h2><p>' . esc_html__('The more specific you get with your awards, the better. You do still want to stay relevant to your business, though. If you ever choose to run ads campaign, your ad will be shown on those awards you select.', 'medicalpro') . '</p>" data-placeholder="' . esc_html__('Choose Your Award', 'medicalpro') . '" id="inputAwards" name="award[]" class="select2 postsubmitSelect" tabindex="5" multiple="multiple">';
                    $output .= '<option value="">' . esc_html__('Select Award', 'medicalpro') . '</option>';
                    if (isset($awards) && !empty($awards)) {
                        foreach ($awards as $award) {
                            $output .= '<option value="' . esc_attr($award->term_id) . '">' . esc_html($award->name) . '</option>';
                        }
                    }
                    $output .= '</select>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                }

                $output .= '<div class="white-section border-bottom ' . $style_wrap . '">';
                $output .=  '<h4 class="white-section-heading">' . esc_html__('Extra Features', 'medicalpro') . '</h4>';
                $output .=  '<div class="row">';
                $output .=  '<div class="col-md-12 col-xs-12">';
                $output .= '<div class="form-group clearfix margin-bottom-0 lp-new-cat-wrape col-md-12">';
                // $output .= '<label>'. esc_html__( 'Select Extra Features', 'medicalpro' ) .'</label>';
                $output .= '<div class="row clearfix">';
                if ($video_consult_show == 'true') {
                    $output .= '<div class="radio-inline checkbox">
                                            <input id="extra-feature-virtual-consult" class="" type="checkbox" name="mp_lp_submit_listing_extra_fields[virtual_consult]" value="Yes">
                                            <label for="extra-feature-virtual-consult">' . esc_html__('Video Consultation', 'medicalpro') . '</label>
                                        </div>';
                }
                $output .= '<div class="radio-inline checkbox">
                                        <input id="extra-feature-online-prescription" class="" type="checkbox" name="mp_lp_submit_listing_extra_fields[online_prescription]" value="Yes">
                                        <label for="extra-feature-online-prescription">' . esc_html__('Online Prescription', 'medicalpro') . '</label>
                                    </div>';
                $output .= '<div class="radio-inline checkbox padding-left-10">
                                        <input id="extra-feature-taking-new-patient" class="" type="checkbox" name="mp_lp_submit_listing_extra_fields[taking_new_patient]" value="Yes">
                                        <label for="extra-feature-taking-new-patient">' . esc_html__('Taking New Patients', 'medicalpro') . '</label>
                                    </div>';
                $output .=  '</div>';
                $output .= '<div class="row clearfix margin-top-20 lp-mp-video-consult">';
                $output .= '<div class="form-group ' . $style2_content_class . ' col-xs-12">
                                        <label for="inputvirtualConsult">' . esc_html__('Video Consultation Room URL', 'medicalpro') . '</label>
                                        <input type="url" class="form-control" name="videoconsult" id="inputvirtualConsult" placeholder="' . esc_html__('https://example.com/live/?roomID=123', 'medicalpro') . '">
                                    </div>';
                $output .=  '</div>';
                $output .=  '</div>';
                $output .=  '</div>';
                $output .=  '</div>';
                $output .=  '</div>';


                if ($price_show == "true" && ($currencySwitch == 1 || $digitPriceSwitch == 1 || $priceSwitch == 1)) {
                    $output .= '<div class="white-section border-bottom ' . $style_wrap . '">';
                    $output .=  '<h4 class="white-section-heading">' . esc_html__('price details', 'medicalpro') . '</h4>';
                    $output .=  '   <div class="row">';
                    if ($currencySwitch == 1 && $price_show == "true") {
                        $lp_priceSymbol = $listingpro_options['listing_pricerange_symbol'];
                        $lp_priceSymbol2 = $lp_priceSymbol . $lp_priceSymbol;
                        $lp_priceSymbol3 = $lp_priceSymbol2 . $lp_priceSymbol;
                        $lp_priceSymbol4 = $lp_priceSymbol3 . $lp_priceSymbol;
                        $output .= '
										<div class="col-md-4 clearfix">
											<label for="price_status">' . $listingCurrText . '</label>
											<select data-priceimg="' . $quicktip_price . '" data-quick-tip="<h2>' . esc_html__('Price Range', 'medicalpro') . '</h2><p>' . esc_html__('Setting a price range can help attract the right targeted audience and will avoid any awkward situations for both customers and the owner.', 'medicalpro') . '</p>" id="price_status" name="price_status" class="chosen-select chosen-select7  postsubmitSelect" tabindex="5">
												<option value="notsay">' . esc_html__('Not to say', 'medicalpro') . '</option>
												<option value="inexpensive"> ' . $lp_priceSymbol . ' - ' . esc_html__('Inexpensive', 'medicalpro') . '</option>
												<option value="moderate"> ' . $lp_priceSymbol2 . ' - ' . esc_html__('Moderate', 'medicalpro') . '</option>
												<option value="pricey"> ' . $lp_priceSymbol3 . ' - ' . esc_html__('Pricey', 'medicalpro') . '</option>
												<option value="ultra_high_end"> ' . $lp_priceSymbol4 . ' - ' . esc_html__('Ultra High', 'medicalpro') . '</option>
											</select>
										</div>';
                    }
                    if ($price_show == "true") {
                        if ($digitPriceSwitch == 1) {
                            $output .= '
											<div class="col-md-4">
												<label for="listingprice">' . $listingDigitText . '</label>
												<input data-priceimg="' . $quicktip_price . '" data-quick-tip="<h2>' . esc_html__('Price From', 'medicalpro') . '</h2><p>' . esc_html__('Being honest with your customers can build a strong relationship. Dont hesitate to include.', 'medicalpro') . '</p>" type="text" name="listingprice" class="form-control" id="listingprice" placeholder="' . esc_html__('Price From', 'medicalpro') . '">
											</div>';
                        }
                        if ($priceSwitch == 1) {
                            $output .= '
											<div class="col-md-4">
												<label for="listingptext">' . $listingPriceText . '</label>
												<input data-priceimg="' . $quicktip_price . '" data-quick-tip="<h2>' . esc_html__('Price To', 'medicalpro') . '</h2><p>' . esc_html__('Being honest with your customers can build a strong relationship. Dont hesitate to include.', 'medicalpro') . '</p>" type="text" name="listingptext" class="form-control" id="listingptext" placeholder="' . esc_html__('Price To', 'medicalpro') . '">
											</div>';
                        }
                    }
                    $output .=  '   </div>';
                    $output .=  '</div>';
                }

                $not_mdpro = false;

                if ($ophSwitch == 1 && $hours_show == "true" && $not_mdpro) {

                    $output .= '<div class="white-section border-bottom ' . $style_wrap . '">';
                    $output .=  '<h4 class="white-section-heading bussin-top" data-hoursimg="' . $quicktip_biz . '" data-quick-tip="<h2>' . esc_html__('Business Hours', 'medicalpro') . '</h2><p>' . esc_html__('You dont want your customers to stop by when you are closed so always try to keep your hour up to date. Keeping your store closed when your business indicate its open on the directory could lead to a negative review.', 'medicalpro') . '</p>">' . esc_html__('business hours', 'medicalpro') . '</h4>';
                    $output .=  '   <div class="row">';
                    $output .= '        <div class="form-group clearfix margin-bottom-0 col-md-12">';
                    $fakeID = '';
                    $output .=              LP_operational_hours_form($fakeID, false);
                    $output .= '        </div>';
                    $output .=  '   </div>';
                    $output .=  '</div>';
                }

                if ($social_show == "true" && $social_show_switch == true) {
                    if ($twSwitch == 1) {
                        $output .=  '<input type="hidden" class="form-control" name="twitter" id="inputTwitter">';
                    }
                    if ($fbSwitch == 1) {
                        $output .=  '<input type="hidden" class="form-control" name="facebook" id="inputFacebook">';
                    }
                    if ($lnkSwitch == 1) {
                        $output .=  '<input type="hidden" class="form-control" name="linkedin" id="inputLinkedIn">';
                    }
                    if ($ytSwitch == 1) {
                        $output .=  '<input type="hidden" class="form-control" name="youtube" id="inputYoutube">';
                    }
                    if ($instaSwitch == 1) {
                        $output .=  '<input type="hidden" class="form-control" name="instagram" id="inputInstagram">';
                    }

                    $output .= '<div class="white-section border-bottom ' . $style_wrap . '">';
                    $output .=  '<h4 class="white-section-heading">' . esc_html__('social media', 'medicalpro') . '</h4>';
                    $output .=  '   <div class="row">';
                    $output .=  '       <div class="style2-social-list-section"></div>';


                    $output .=  '<div class="style2-add-new-social-sec">';
                    $output .=  '    <div class="col-md-2">' . esc_html__('Select', 'medicalpro') . '</div>';
                    $output .=  '    <div class="col-md-3">';
                    $output .=  '       <select data-socialimg="' . $quicktip_social . '"  data-quick-tip="<h2>' . esc_html__('Social Media', 'medicalpro') . '</h2><p>' . esc_html__('Being honest with your customers can build a strong relationship. Dont hesitate to include.', 'medicalpro') . '</p>" class="select2" id="get_media"><option>' . esc_html__('Please Select', 'medicalpro') . '</option>';
                    if ($instaSwitch == 1) {
                        $output .=  '<option>' . esc_html__('Instagram', 'medicalpro') . '</option>';
                    }
                    if ($ytSwitch == 1) {
                        $output .=  '<option>' . esc_html__('Youtube', 'medicalpro') . '</option>';
                    }
                    if ($lnkSwitch == 1) {
                        $output .=  '<option>' . esc_html__('LinkedIn', 'medicalpro') . '</option>';
                    }
                    if ($fbSwitch == 1) {
                        $output .=  '<option>' . esc_html__('Facebook', 'medicalpro') . '</option>';
                    }
                    if ($twSwitch == 1) {
                        $output .=  '<option>' . esc_html__('Twitter', 'medicalpro') . '</option>';
                    }
                    $output .=  '       </select>';
                    $output .=  '    </div>';
                    $output .=  '    <div class="col-md-6">';
                    $output .=  '       <input type="text" placeholder="' . esc_html__('Social Media', 'medicalpro') . '" class="form-control" value="" id="get_media_url">';
                    $output .=  '    </div>';
                    $output .=  '    <div class="col-md-1"><a id="add-new-social-url"><i class="fa fa-plus-square"></i></a></div>';
                    $output .=  '</div>';


                    $output .=  '    </div>';
                    $output .=  '</div>';
                }
                if ($faq_switch == 1 && $faqs_show == "true") {
                    $output .= '<div class="white-section border-bottom ' . $style_wrap . '">';
                    $output .=  '<h4 class="white-section-heading">' . esc_html__('frequently asked questions', 'medicalpro') . '</h4>';
                    $output .=  '   <div class="row">';
                    $output .= '<div class="form-group clearfix margin-bottom-0 col-md-12">
											<div id="tabs" class="lsiting-submit-faq-tabs clearfix pos-relative" data-faqtitle="' . $listing_faq_text . '">
												
												<div id="tabs-1">
													<div class="col-md-2">
														<label for="inpuFaqsLp">' . $listing_faq_text . '</label>
													</div>
													<div class="col-md-10">
														<div class="form-group">
															<input data-faqimg="' . $quicktip_faq . '" data-quick-tip="<h2>' . esc_html__('FAQ', 'medicalpro') . '</h2><p>' . esc_html__('Share some of the most asked question and answers so they know you are serious about your business and truly care for your customers.', 'medicalpro') . '</p>" type="text" class="form-control" data-faqmaintitle="' . $listing_faq_text . '" name="faq[1]" id="inpuFaqsLp" placeholder="' . esc_html__('FAQ', 'medicalpro') . '">
														</div>
														<div class="form-group">												
															<textarea data-faqimg="' . $quicktip_faq . '" data-quick-tip="<h2>' . esc_html__('FAQ Answers', 'medicalpro') . '</h2><p>' . esc_html__('Share some of the most asked question and answers so they know you are serious about your business and truly care for your customers.', 'medicalpro') . '</p>" class="form-control" placeholder="' . esc_html__('Answer', 'medicalpro') . '" name="faqans[1]" rows="8" id="inputDescriptionFaq"></textarea>
														</div>
													</div>
												</div>
												<div class="appendother"></div>
												<div class="btn-container faq-btns clearfix">	
													<ul>
														<li><a href="#tabs-1" data-faq-text="' . $listing_faq_tabs_text . '">' . $listing_faq_tabs_text . '</a></li>
													</ul>
													<a id="tabsbtn" class="lp-secondary-btn btn-first-hover style2-tabsbtn"><i class="fa fa-plus-square"></i> ' . esc_html__('add new', 'medicalpro') . '</a>
												</div>										
											</div>
										</div>';

                    $output .=  '   </div>';
                    $output .=  '</div>';
                }

                $output .= '<div class="white-section border-bottom ' . $style_wrap . '">';
                $output .=  '<h4 class="white-section-heading description-tip"  data-desimg="' . $quicktip_desc . '" data-quick-tip="<h2>' . esc_html__('Description', 'medicalpro') . '</h2><p>' . esc_html__('Tell briefly what your customers what to hear about your business has to offer that is unique and you do better then everyone else.', 'medicalpro') . '</p>">' . esc_html__('more info', 'medicalpro') . '</h4>';
                $output .=  '   <div class="row">';
                $output .=  '       <div class="form-group ' . $style2_content_class . ' col-xs-12">';
                $placeholder_for_decs = esc_html__('Detail description about your listing', 'medicalpro');
                $output .=  '       <label  for="inputDescription">' . $listing_desc_text . ' <small>*</small></label>' . get_textarea_as_editor('inputDescription', 'postContent', $placeholder_for_decs) . '';
                $output .=  '       </div>';
                if ($tags_switch == 1 && $tags_show == "true") {
                    $output .= '<div class="form-group ' . $style2_content_class . ' col-xs-12 lp-social-area">
											<div class="form-group col-md-12 col-xs-12" style="padding:0px;">
												<label for="inputTags"> ' . $listingTagsText . ' </label>
												<div class="help-text">
													<a href="#" class="help"><i class="fa fa-question"></i></a>
													<div class="help-tooltip">
														<p>' . esc_html__('These keywords or tags will help your listing to find in search. Add a comma separated list of keywords related to your business.', 'medicalpro') . '</p>
													</div>
												</div>
												<textarea class="form-control" name="tags" id="inputTags" placeholder="' . esc_html__('Enter tags or keywords comma separated...', 'medicalpro') . '"></textarea>
											</div>
										</div>';
                }
                $output .=  '   </div>';
                $output .=  '</div>';

                if ($video_show == "true" || $gallery_show == "true" || lp_theme_option('lp_featured_file_switch') || $b_logo == 1) {
                    $output .= '<div class="white-section border-bottom ' . $style_wrap . '">';
                    $output .=  '<h4 class="white-section-heading">' . esc_html__('media', 'medicalpro') . '</h4>';
                    $output .=  '   <div class="row">';
                    if ($vdoSwitch == 1) {
                        if ($video_show == "true") {
                            $output .= '<div class="form-group clearfix ' . $style2_content_class . '">
													<label for="postVideo">' . $listingVdoText . '<span>' . esc_html__('(Optional)', 'medicalpro') . '</span></label>
													<input data-videoimg="' . $quicktip_video . '" data-quick-tip="<h2>' . esc_html__('Video', 'medicalpro') . '</h2><p>' . esc_html__('Take it to next level and provide more details about what you have to offer. Select all that applies to you.', 'medicalpro') . '</p>" type="text" class="form-control" name="postVideo" id="postVideo" placeholder="' . esc_html__('ex: https://youtu.be/lY2yjAdbvdQ', 'medicalpro') . '">
												</div>';
                        }
                    }
                    if ($fileSwitch == 1) {
                        if ($gallery_show == "true") {
                            $output .= '<div class="col-md-12 form-group clearfix margin-bottom-0 lp-img-gall-upload-section lplistgallery" data-featureimg="' . $quicktip_gallery . '" data-quick-tip="<h2>' . esc_html__('Gallery', 'medicalpro') . '</h2>" data-savedgallerysize="0" data-savedgallerysize="0" data-savedgallweight="0">
													<div class="col-sm-12 padding-left-0 padding-right-0">
														<label for="postVideo">' . esc_html__('Gallery Images ', 'medicalpro') . '</label>	
														<div class="jFiler-input-dragDrop pos-relative">
															<div class="jFiler-input-inner">
																<div class="jFiler-input-icon">
																	<i class="icon-jfi-cloud-up-o"></i>
																</div>
																	<div class="jFiler-input-text">
																	<h3 style="margin:20px 0px;">' . $upload_icon . '' . esc_html__('Drop files here or click to upload', 'medicalpro') . '</h3>
																</div>
																<a class="jFiler-input-choose-btn blue">' . esc_html__('Browse Files', 'medicalpro') . '</a>
																<div class="filediv">
																	<input type="file" name="listingfiles[]" class="file" multiple>
																</div>
															</div>
														</div>
													</div>
												</div>';
                        }
                    }
                    $output .=  '   </div>';
                    $output .=  '   <div class="row">';
                    if (lp_theme_option('lp_featured_file_switch')) {
                        $output .= '<div class="form-group clearfix margin-bottom-0 margin-top-10 lp-listing-featuredimage col-md-6">
												<label class="margin-top-10">' . esc_html__('Upload Profile Image', 'medicalpro') . '</label>
												<div class="custom-file">
													<input style="display:none;" type="file" name="lp-featuredimage[]" id="lp-featuredimage" class="inputfile inputfile-3" data-multiple-caption="{count} files selected" />
													<label class="featured-img-label" for="lp-featuredimage" data-featureimg="' . $quicktip_gallery . '" data-quick-tip="<h2>' . esc_html__('Profile Image', 'medicalpro') . '</h2><p>' . esc_html__('Quick tip for Profile images', 'medicalpro') . '</p>"><p>' . esc_html__('Browse', 'medicalpro') . '</p><span>' . esc_html__('Choose a file', 'medicalpro') . '&hellip;</span></label>
												</div>
											</div>
											';
                    }

                    $b_logo = $listingpro_options['business_logo_switch'];
                    if ($b_logo == 1) {
                        $output .= '<div class="form-group clearfix margin-bottom-0 margin-top-10 lp-listing-featuredimage col-md-6">
										<label class="margin-top-10">' . esc_html__('Upload Business Logo', 'medicalpro') . '</label>
										
										<div class="custom-file">
											<input style="display:none;" type="file" name="business_logo[]" id="business_logo" class="inputfile inputfile-4" />
											<label class="b-logo-img-label" for="business_logo" data-blogoimg="' . $quicktip_b_logo . '" data-quick-tip="<h2>' . esc_html__('Business Logo', 'medicalpro') . '</h2>"><p>' . esc_html__('Browse', 'medicalpro') . '</p><span>' . esc_html__('Choose a file', 'medicalpro') . '&hellip;</span></label>
								</div>
									</div>';
                    }
                    $output .=  ' </div>';
                    $output .=  '</div>';
                }
            } else {

                $output .= '	<div class="white-section border-bottom ' . $style_wrap . '">
							<div class="row">';


                $output .= '<div class="form-group ' . $style2_content_class . ' col-xs-12">';

                if ($quick_tip_switch == 1 && $page_style != 'style2') {
                    $output .= '
										<div class="quick_tip">
											<h2>' . $quickTipTitle . '</h2>
											<p>' . $quickTipText . '</p>
										</div>';
                }


                $output .= '
									<div id="lp_custom_title" class="tab-pane fade in active">
									<label for="usr">' . $listing_title_text . ' <small>*</small></label>
									<div class="help-text">
										<a href="#" class="help"><i class="fa fa-question"></i></a>
										<div class="help-tooltip">
											<p>' . esc_html__('Put your listing title here and tell the name of your business to the world.', 'medicalpro') . '</p>
										</div>
									</div>
											<input data-quick-tip="<p>' . esc_html__('this is test data for quick tip for title field', 'medicalpro') . '</p>' . $quicktip_image . '" type="text" name="postTitle" class="form-control margin-bottom-10" id="lptitle" placeholder="' . esc_html__('Dr. John Doe', 'medicalpro') . '">';
                if ($enableGoogle == "yes") {

                    $output .= '<input data-quick-tip="<p>' . esc_html__('this is test data for quick tip for title field', 'medicalpro') . '</p>' . $quicktip_image . '" type="hidden" id="lptitleGoogle" name="" class="form-control margin-bottom-10 lptitle"  placeholder="' . esc_html__('Dr. John Doe', 'medicalpro') . '">
												<div id="lp_listing_map"></div>';
                }

                $output .= '
											
									</div>';


                if ($tagline_show == "true") {
                    $output .= '
													<input data-quick-tip="<p>' . esc_html__('this is test data for quick tip for tagline field', 'medicalpro') . '</p>' . $quicktip_image . '" type="text" name="tagline_text" class="form-control margin-bottom-10" id="lptagline" placeholder="' . esc_html__('10 Years+ Experiance', 'medicalpro') . '">';
                }
                $output .= '
								</div>';


                if ($page_style != 'style2') {
                    $output .= '<div class="form-group col-md-6 col-xs-12">';
                    if ($submit_ad_img_switch == 1) {
                        $output .= '
										<div class="submit-img">
											<img src="' . $submitImg . '" alt="">
										</div>';
                    }
                    $output .= '
									</div>';
                }
                $output .= '</div>
							<div class="row">';
                if ($showLocation == "1" && $location_show == "true") {
                    if (!empty($locations_type) && $locations_type == "auto_loc") {
                        if ($singleLocMode == true) {

                            $output .= '
											<div class="form-group ' . $style2_content_class . ' col-xs-12 lp-new-cat-wrape">
												<label for="inputTags">' . $listingCityText . '</label>
												<div class="help-text">
													<a href="#" class="help"><i class="fa fa-question"></i></a>
													<div class="help-tooltip">
														<p>' . esc_html__('The city name will help users find you in search filters.', 'medicalpro') . '</p>
													</div>
												</div>';


                            $output .= '
															<input id="citiess" name="locationn" data-isseleted="false" class="form-control ostsubmitSelect" autocomplete="off" data-country="' . $locArea . '" placeholder="' . esc_html__('select your listing region', 'medicalpro') . '">
															<input type="hidden" name="location">
													';

                            $output .= '	
											</div>';
                        } else {
                            $output .= '
											<div class="form-group lp-selected-locs clearfix col-md-12"></div>';


                            $output .= '
											<div class="form-group ' . $style2_content_class . ' col-xs-12 lp-new-cat-wrape">
												<label for="inputTags">' . $listingCityText . '</label>
												<div class="help-text">
													<a href="#" class="help"><i class="fa fa-question"></i></a>
													<div class="help-tooltip">
														<p>' . esc_html__('The city name will help users find you in search filters.', 'medicalpro') . '</p>
													</div>
												</div>';


                            $output .= '
															<input id="citiess" name="locationn" data-isseleted="false" class="form-control ostsubmitSelect" autocomplete="off" data-country="' . $locArea . '" placeholder="' . esc_html__('select your listing region', 'medicalpro') . '">
													';

                            $output .= '	
											</div>';
                        }
                    } elseif (!empty($locations_type) && $locations_type == "manual_loc") {

                        $output .= '
										<div class="form-group ' . $style2_content_class . ' col-xs-12 lp-new-cat-wrape lp-new-cat-wrape">
											<label for="inputTags">' . $listingCityText . '</label>
											<div class="help-text">
												<a href="#" class="help"><i class="fa fa-question"></i></a>
												<div class="help-tooltip">
													<p>' . esc_html__('The city name will help users find you in search filters.', 'medicalpro') . '</p>
												</div>
											</div>';


                        if ($singleLocMode == true) {
                            $output .= '<select data-quick-tip="<p>' . esc_html__('quick tip data for location field', 'medicalpro') . '</p>' . $quicktip_image . '" data-placeholder="' . esc_html__('select your listing region', 'medicalpro') . '" id="inputCity" name="location[]" class="select2 postsubmitSelect" tabindex="5">';
                        } else {
                            $output .= '<select data-quick-tip="<p>' . esc_html__('quick tip data for location field', 'medicalpro') . '</p>' . $quicktip_image . '" data-placeholder="' . esc_html__('select your listing region', 'medicalpro') . '" id="inputCity" name="location[]" class="select2 postsubmitSelect" tabindex="5" multiple="multiple">';
                        }


                        $output .= '<option value="">' . esc_html__('Select City', 'medicalpro') . '</option>';
                        $args = array(
                            'post_type' => 'listing',
                            'order' => 'ASC',
                            'hide_empty' => false,
                            'parent' => 0,
                        );
                        $locations = get_terms('location', $args);
                        if (!empty($locations)) {
                            foreach ($locations as $location) {
                                $output .= '<option value="' . $location->term_id . '">' . $location->name . '</option>';
                                $argsChild = array(
                                    'order' => 'ASC',
                                    'hide_empty' => false,
                                    'hierarchical' => false,
                                    'parent' => $location->term_id,


                                );
                                $childLocs = get_terms('location', $argsChild);
                                if (!empty($childLocs)) {
                                    foreach ($childLocs as $childLoc) {
                                        $output .= '<option value="' . $childLoc->term_id . '">-&nbsp;' . $childLoc->name . '</option>';

                                        $argsChildof = array(
                                            'order' => 'ASC',
                                            'hide_empty' => false,
                                            'hierarchical' => false,
                                            'parent' => $childLoc->term_id,
                                        );
                                        $childLocsof = get_terms('location', $argsChildof);
                                        if (!empty($childLocsof)) {
                                            foreach ($childLocsof as $childLocof) {
                                                $output .= '<option value="' . $childLocof->term_id . '">--&nbsp;' . $childLocof->name . '</option>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $output .= '
												</select>';

                        $output .= '	
										</div>';
                    }
                }
                if ($addressSwitch == 1) {
                    $output .= '
									<div class="form-group ' . $style2_content_class . ' col-xs-12">
										<div class="lp-coordinates">
											<a data-type="gaddress" class="btn-link googleAddressbtn active">' . esc_html__('Search By Google', 'medicalpro') . '</a>
											<a data-type="gaddresscustom" class="btn-link googleAddressbtn">' . esc_html__('Manual Coordinates', 'medicalpro') . '</a>';
                    if ((is_ssl()) || (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')))) {
                        $output .= '
													<a data-type="gaddresscustom" class="btn-link googledroppin" data-toggle="modal" data-target="#modal-doppin"><i class="fa fa-map-pin"></i> ' . esc_html__('Drop Pin', 'medicalpro') . '</a>';
                    }
                    $output .= '
										</div>
										<label for="inputAddress" class="googlefulladdress">' . $listingGaddText . '</label>
										
										<div class="help-text googlefulladdress">
											<a href="#" class="help"><i class="fa fa-question"></i></a>
											<div class="help-tooltip">
												<p>' . esc_html__('Start typing and select your google location from google suggestions. This is for the map and also for locating your business.', 'medicalpro') . '</p>
											</div>
										</div>
										
										<input data-quick-tip="<p>' . esc_html__('this is test infor address field', 'medicalpro') . '</p>' . $quicktip_image . '" type="text" class="form-control" name="gAddress" id="inputAddress" placeholder="' . esc_html__('Start typing and find your place in google map', 'medicalpro') . '">
										<div class="lp-custom-lat clearfix">
											<label for="inputAddress">' . $listingGaddcustomText . '</label>
											<input type="text" class="form-control" name="gAddresscustom" id="inputAddresss" placeholder="' . esc_html__('Add address here', 'medicalpro') . '">
											<div class="row hiddenlatlong">
												<div class="col-md-6 col-xs-6">
												<label for="latitude">' . esc_html__('Latitude', 'medicalpro') . '</label>
												<input class="form-control" type="hidden" placeholder="' . esc_html__('40.7143528', 'medicalpro') . '" id="latitude" name="latitude">
												</div>
												<div class="col-md-6 col-xs-6">
												<label for="longitude">' . esc_html__('Longitude', 'medicalpro') . '</label>
												<input class="form-control" type="hidden" placeholder="' . esc_html__('-74.0059731', 'medicalpro') . '" id="longitude" name="longitude">
												</div>
											</div>
										</div>
									</div>
									</div>
								<div class="row">';
                }
                if ($phoneSwitch == 1) {
                    if ($contact_show == "true") {
                        $output .= '
										<div class="form-group ' . $style2_content_class . ' col-xs-12">
											<label for="inputPhone">' . $listingPhText . '</label>
											<input data-quick-tip="<p>' . esc_html__('this is quick tip info for phone field', 'medicalpro') . '</p>' . $quicktip_image . '" type="text" class="form-control" name="phone" id="inputPhone" placeholder="' . esc_html__('111-111-1234', 'medicalpro') . '">
										</div>';
                        if (!empty($whatsappButton)) {
                            $whatsappLable = lp_theme_option('lp_whatsapp_label');
                            $output .= '
                                        <div class="form-group ' . $style2_content_class . ' col-xs-12">
                                            <label for="inputWhatsapp">' . $whatsappLable . '</label>
                                            <input data-whatsappimg="' . $quicktip_adress . '" data-quick-tip="<h2>' . esc_html__('Whatsapp no.', 'medicalpro') . '</h2><p>' . esc_html__('Whatsapp no for listing detail page.', 'medicalpro') . '</p>" type="text" class="form-control" name="whatsapp" id="inputWhatsapp" placeholder="' . esc_html__('+44994981258', 'medicalpro') . '">
                                        </div>';
                        }
                    }
                }

                if ($webSwitch == 1 && $website_show == "true") {
                    $output .= '
									<div class="form-group ' . $style2_content_class . ' col-xs-12">
										<label for="inputWebsite">' . $listingWebText . '</label>
										<input data-quick-tip="<p>' . esc_html__('this is quick tip info for website field', 'medicalpro') . '</p>' . $quicktip_image . '" type="text" class="form-control" name="website" id="inputWebsite" placeholder="' . esc_html__('http://', 'medicalpro') . '">
									</div>';
                }
                $output .= '
							</div>
						</div>
						<div class="white-section border-bottom ' . $style_wrap . '">
							<div class="row">
								<div class="form-group ' . $style2_content_class . ' col-xs-12">';

                if ($ophSwitch == 1 && $hours_show == "true") {
                    $output .= '
										<div class="form-group clearfix margin-bottom-0">';
                    $fakeID = '';
                    $output .= LP_operational_hours_form($fakeID, false);
                    $output .= '
										</div>';
                }
                $output .= '
									<div class="form-group clearfix margin-bottom-0 lp-new-cat-wrape">
										<label for="inputCategory">' . $listing_cat_text . ' <small>*</small></label>';

                if (!empty($cat_plan_id)) {
                    /* for pre category selection */
                    $output .= '<input type="hidden" name="lppre_plan_cats" value="true" />';
                    $output .= '
						<select data-placeholder="' . esc_html__('Choose Your Business Category', 'medicalpro') . '" id="inputCategory" name="category[]" class="select2 postsubmitSelect" tabindex="5">';
                    $selectedCatObj = get_term_by('id', $cat_plan_id, 'listing-category');
                    $selectedCatName = $selectedCatObj->name;
                    $output .= '<option value="' . $cat_plan_id . '">' . $selectedCatName . '</option>';
                    $output .= '</select>';
                } else {

                    if ($singleCatMode == true) {
                        $output .= '
												<select data-placeholder="' . esc_html__('Choose Your Business Category', 'medicalpro') . '" id="inputCategory" name="category[]" class="select2 postsubmitSelect" tabindex="5">';
                    } else {
                        $output .= '
												<select data-placeholder="' . esc_html__('Choose Your Business Category', 'medicalpro') . '" id="inputCategory" name="category[]" class="select2 postsubmitSelect" tabindex="5" multiple="multiple">';
                    }
                    $output .= '<option value="">' . esc_html__('Select Category', 'medicalpro') . '</option>';
                    $args = array(
                        'post_type' => 'listing',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                    );
                    $categories = get_terms('listing-category', $args);
                    if (!empty($categories)) {
                        foreach ($categories as $category) {
                            $doAjax = false;
                            $doAjax = lp_category_has_features($category->term_id);
                            $output .= '<option data-doajax="' . $doAjax . '" value="' . $category->term_id . '">' . $category->name . '</option>';

                            $argscatChild = array(
                                'order' => 'ASC',
                                'hide_empty' => false,
                                'hierarchical' => false,
                                'parent' => $category->term_id,

                            );
                            $childCats = get_terms('listing-category', $argscatChild);
                            if (!empty($childCats)) {

                                foreach ($childCats as $subID) {
                                    $doAjax = false;
                                    $doAjax = lp_category_has_features($subID->term_id);
                                    $output .= '<option data-doajax="' . $doAjax . '"  class="sub_cat" value="' . $subID->term_id . '">-&nbsp;&nbsp;' . $subID->name . '</option>';

                                    $childCatsof = array(
                                        'order' => 'ASC',
                                        'hide_empty' => false,
                                        'hierarchical' => false,
                                        'parent' => $subID->term_id,
                                    );
                                    $childofCats = get_terms('listing-category', $childCatsof);
                                    if (!empty($childofCats)) {
                                        foreach ($childofCats as $subIDD) {
                                            $doAjax = false;
                                            $doAjax = lp_category_has_features($subIDD->term_id);
                                            $output .= '<option data-doajax="' . $doAjax . '"  class="sub_cat" value="' . $subIDD->term_id . '">--&nbsp;&nbsp;' . $subIDD->name . '</option>';

                                            $childCatsoff = array(
                                                'order' => 'ASC',
                                                'hide_empty' => false,
                                                'hierarchical' => false,
                                                'parent' => $subIDD->term_id,
                                            );
                                            $childofCatss = get_terms('listing-category', $childCatsoff);
                                            if (!empty($childofCatss)) {
                                                foreach ($childofCatss as $subIDDD) {
                                                    $doAjax = false;
                                                    $doAjax = lp_category_has_features($subIDDD->term_id);
                                                    $output .= '<option data-doajax="' . $doAjax . '"  class="sub_cat" value="' . $subIDDD->term_id . '">---&nbsp;&nbsp;' . $subIDDD->name . '</option>';
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $output .= '</select>';
                }

                $output .= '</div>';
                $output .= '
								</div>';
                if ($page_style != 'style2') {
                    $output .= '
								<div class="form-group col-md-6 col-xs-12">';
                    if ($submit_ad_img1_switch == 1) {
                        $output .= '
										<div class="submit-img">
											<img src="' . $submitImg1 . '" alt="">
										</div>';
                    }
                    $output .= '
								</div>';
                }

                $output .= listingpro_get_term_openfields(false);
                $output .= '
							</div>';
                $output .= '
								<div class="form-group clearfix lpfeatures_fields">
									<div class="pre-load"></div>
									<div class="featuresDataContainerr lp-nested row" id="tags-by-cat"></div>	
									<div class="featuresDataContainer lp-nested row" id="features-by-cat"></div>
								</div>';

                $output .= '<div class="form-group clearfix">
                    <div class="row">';

                $args = array(
                    'post_type'  => 'listing',
                    'order'      => 'ASC',
                    'hide_empty' => false,
                    'parent'     => 0,
                );
                $insurances = get_terms('medicalpro-insurance', $args);
                $output .= '<div class="col-md-6 clearfix">
                            <label for="inputInsurances">' . esc_html__('Insurances', 'medicalpro') . ' <small>*</small></label>';
                $output .= '<select data-placeholder="' . esc_html__('Choose Your Insurance', 'medicalpro') . '" id="inputInsurances" name="insurance[]" class="select2 postsubmitSelect" multiple="multiple">';
                $output .= '<option value="">' . esc_html__('Select Insurance', 'medicalpro') . '</option>';
                if (isset($insurances) && !empty($insurances)) {
                    foreach ($insurances as $insurance) {
                        $output .= '<option value="' . esc_attr($insurance->term_id) . '">' . esc_html($insurance->name) . '</option>';
                    }
                }
                $output .= '</select>';
                $output .= '</div>';

                $args = array(
                    'post_type'  => 'listing',
                    'order'      => 'ASC',
                    'hide_empty' => false,
                    'parent'     => 0,
                );
                $awards = get_terms('medicalpro-award', $args);
                $output .= '<div class="col-md-6 clearfix">
                            <label for="inputAwards">' . esc_html__('Awards', 'medicalpro') . ' <small>*</small></label>';
                $output .= '<select data-placeholder="' . esc_html__('Choose Your Award', 'medicalpro') . '" id="inputAwards" name="award[]" class="select2 postsubmitSelect" multiple="multiple">';
                $output .= '<option value="">' . esc_html__('Select Award', 'medicalpro') . '</option>';
                if (isset($awards) && !empty($awards)) {
                    foreach ($awards as $award) {
                        $output .= '<option value="' . esc_attr($award->term_id) . '">' . esc_html($award->name) . '</option>';
                    }
                }
                $output .= '</select>';
                $output .= '</div>';

                $output .= '</div>
                </div>';


                if ($price_show == "true" && ($currencySwitch == 1 || $digitPriceSwitch == 1 || $priceSwitch == 1)) {
                    $output .= '
							<div class="form-group clearfix">
								<div class="row">';
                    if ($currencySwitch == 1 && $price_show == "true") {
                        $lp_priceSymbol = $listingpro_options['listing_pricerange_symbol'];
                        $lp_priceSymbol2 = $lp_priceSymbol . $lp_priceSymbol;
                        $lp_priceSymbol3 = $lp_priceSymbol2 . $lp_priceSymbol;
                        $lp_priceSymbol4 = $lp_priceSymbol3 . $lp_priceSymbol;
                        $output .= '
										<div class="col-md-4 clearfix">
											<label for="price_status">' . $listingCurrText . '</label>
											<select id="price_status" name="price_status" class="chosen-select chosen-select7  postsubmitSelect" tabindex="5">
												<option value="notsay">' . esc_html__('Not to say', 'medicalpro') . '</option>
												<option value="inexpensive"> ' . $lp_priceSymbol . ' - ' . esc_html__('Inexpensive', 'medicalpro') . '</option>
												<option value="moderate"> ' . $lp_priceSymbol2 . ' - ' . esc_html__('Moderate', 'medicalpro') . '</option>
												<option value="pricey"> ' . $lp_priceSymbol3 . ' - ' . esc_html__('Pricey', 'medicalpro') . '</option>
												<option value="ultra_high_end"> ' . $lp_priceSymbol4 . ' - ' . esc_html__('Ultra High', 'medicalpro') . '</option>
											</select>
										</div>';
                    }
                    if ($price_show == "true") {
                        if ($digitPriceSwitch == 1) {
                            $output .= '
											<div class="col-md-4">
												<label for="listingprice">' . $listingDigitText . '</label>
												<input type="text" name="listingprice" class="form-control" id="listingprice" placeholder="' . esc_html__('Price From', 'medicalpro') . '">
											</div>';
                        }
                        if ($priceSwitch == 1) {
                            $output .= '
											<div class="col-md-4">
												<label for="listingptext">' . $listingPriceText . '</label>
												<input type="text" name="listingptext" class="form-control" id="listingptext" placeholder="' . esc_html__('Price To', 'medicalpro') . '">
											</div>';
                        }
                    }
                    $output .= '
								</div>
							</div>';
                }


                $output = apply_filters('medicalpro_hospital_submission', $output, $style_wrap, '', $page_style);

                $output .= '</div>
						
			<div class="white-section border-bottom ' . $style_wrap . '">
							<div class="row">
								<div class="form-group ' . $style2_content_class . ' col-xs-12">';
                $placeholder_for_decs = esc_html__('Detail description about your listing', 'medicalpro');
                $output .= '
									<div class="form-group clearfix">
										<label for="inputDescription">' . $listing_desc_text . ' <small>*</small></label>' . get_textarea_as_editor('inputDescription', 'postContent', $placeholder_for_decs) . '
									</div>';
                if ($faq_switch == 1 && $faqs_show == "true") {
                    $output .= ' <div class="form-group clearfix margin-bottom-0">
											<div id="tabs" class="clearfix pos-relative" data-faqtitle="' . $listing_faq_text . '">
												
												<div id="tabs-1">
													<div class="form-group">
														<label for="inpuFaqsLp">' . $listing_faq_text . '</label>
														<input data-quick-tip="<p>' . esc_html__('Quick tip on FAQ question', 'medicalpro') . '</p>' . $quicktip_image . '" type="text" class="form-control" data-faqmaintitle="' . $listing_faq_text . '" name="faq[1]" id="inpuFaqsLp" placeholder="' . esc_html__('FAQ', 'medicalpro') . '">
													</div>
													<div class="form-group">												
														<textarea data-quick-tip="<p>' . esc_html__('this is test data for quick tip info of FAQ answer field', 'medicalpro') . '</p>' . $quicktip_image . '" class="form-control" placeholder="' . esc_html__('Answer', 'medicalpro') . '" name="faqans[1]" rows="8" id="inputDescriptionFaq"></textarea>
													</div>
												</div>	
												<div class="appendother"></div>									
											<div class="btn-container faq-btns clearfix">	
													<ul>
														<li><a href="#tabs-1" data-faq-text="' . $listing_faq_tabs_text . '">' . $listing_faq_tabs_text . '</a></li>
													</ul>
													<a id="tabsbtn" class="lp-secondary-btn btn-first-hover">+</a>
												</div>
											</div>
											
										</div>';
                }
                $output .= '
								</div>';

                if ($page_style != 'style2') {
                    $output .= '<div class="form-group col-md-6 col-xs-12">';
                    if ($submit_ad_img2_switch == 1) {
                        $output .= '
										<div class="submit-img">
											<img src="' . $submitImg2 . '" alt="">
										</div>';
                    }
                    $output .= '
								</div>';
                }

                $output .= '</div>
							<div class="row">
								<div class="form-group ' . $style2_content_class . ' col-xs-12 lp-social-area">';
                if ($social_show == "true" && $social_show_switch == true) {
                    if ($twSwitch == 1) {
                        $output .= '
										<div class="form-group col-md-6 col-xs-12">
											<label for="inputTwitter">' . esc_html__('Twitter', 'medicalpro') . '</label>
											<input type="text" class="form-control" name="twitter" id="inputTwitter" placeholder="' . esc_html__('Your Twitter URL', 'medicalpro') . '">
										</div>';
                    }
                    if ($fbSwitch == 1) {
                        $output .= '
										<div class="form-group col-md-6 col-xs-12">
											<label for="inputFacebook">' . esc_html__('Facebook', 'medicalpro') . '</label>
											<input type="text" class="form-control" name="facebook" id="inputFacebook" placeholder="' . esc_html__('Your Facebook URL', 'medicalpro') . '">
										</div>';
                    }
                    if ($lnkSwitch == 1) {
                        $output .= '
										<div class="form-group col-md-6 col-xs-12">
											<label for="inputLinkedIn">' . esc_html__('LinkedIn', 'medicalpro') . '</label>
											<input type="text" class="form-control" name="linkedin" id="inputLinkedIn" placeholder="' . esc_html__('Your LinkedIn URL', 'medicalpro') . '">
										</div>';
                    }
                    if ($ytSwitch == 1) {
                        $output .= '
										<div class="form-group col-md-6 col-xs-12">
											<label for="inputYoutube">' . esc_html__('Youtube', 'medicalpro') . '</label>
											<input type="text" class="form-control" name="youtube" id="inputYoutube" placeholder="' . esc_html__('Your Youtube URL', 'medicalpro') . '">
										</div>';
                    }
                    if ($instaSwitch == 1) {
                        $output .= '
										<div class="form-group col-md-6 col-xs-12">
											<label for="inputInstagram">' . esc_html__('Instagram', 'medicalpro') . '</label>
											<input type="text" class="form-control" name="instagram" id="inputInstagram" placeholder="' . esc_html__('Your Instagram URL', 'medicalpro') . '">
										</div>';
                    }
                }
                $output .= '
								</div>';
                if ($tags_switch == 1 && $tags_show == "true") {
                    $output .= '
									<div class="form-group ' . $style2_content_class . ' col-xs-12 lp-social-area">
										<div class="form-group col-md-12 col-xs-12" style="padding-left:0px;">
											<label for="inputTags">' . $listingTagsText . '</label>
											<div class="help-text">
												<a href="#" class="help"><i class="fa fa-question"></i></a>
												<div class="help-tooltip">
													<p>' . esc_html__('These keywords or tags will help your listing to find in search. Add a comma separated list of keywords related to your business.', 'medicalpro') . '</p>
												</div>
											</div>
											<textarea class="form-control" name="tags" id="inputTags" placeholder="' . esc_html__('Enter tags or keywords comma separated...', 'medicalpro') . '"></textarea>
										</div>
									</div>';
                }
                $output .= '
							</div>
						</div>';

                if ($video_show == "true" || $gallery_show == "true" || lp_theme_option('lp_featured_file_switch') || $b_logo == 1) {
                    $output .= '
						<div class="white-section border-bottom ' . $style_wrap . '">
							<div class="row">
								<div class="form-group ' . $style2_content_class . ' col-xs-12">';
                    if ($vdoSwitch == 1) {
                        if ($video_show == "true") {
                            $output .= '
											<div class="form-group clearfix">
												<label for="postVideo">' . $listingVdoText . '<span>' . esc_html__('(Optional)', 'medicalpro') . '</span></label>
												<input data-quick-tip="<p>' . esc_html__('this is test data for quick tip info of Video field', 'medicalpro') . '</p>' . $quicktip_image . '" type="text" class="form-control" name="postVideo" id="postVideo" placeholder="' . esc_html__('ex: https://youtu.be/lY2yjAdbvdQ', 'medicalpro') . '">
											</div>';
                        }
                    }
                    if ($fileSwitch == 1) {
                        if ($gallery_show == "true") {
                            $output .= '
											<div class="form-group clearfix margin-bottom-0 lp-img-gall-upload-section lplistgallery" data-quick-tip="<p>' . esc_html__('quick tip info for gallery', 'medicalpro') . '</p>' . $quicktip_image . '"  data-savedgallerysize="0" data-savedgallweight="0">
												<div class="col-sm-12 padding-left-0 padding-right-0">
													<label for="postVideo">' . esc_html__('Images ', 'medicalpro') . '</label>	
													<div class="jFiler-input-dragDrop pos-relative">
														<div class="jFiler-input-inner">
															<div class="jFiler-input-icon">
																<i class="icon-jfi-cloud-up-o"></i>
															</div>
																<div class="jFiler-input-text">
																<h3 style="margin:20px 0px;">' . $upload_icon . '' . esc_html__('Drop files here or click to upload', 'medicalpro') . '</h3>
															</div>
															<a class="jFiler-input-choose-btn blue">' . esc_html__('Browse Files', 'medicalpro') . '</a>
															<div class="filediv">
																<input type="file" name="listingfiles[]" class="file" multiple>
															</div>
														</div>
													</div>
												</div>
											</div>';
                        }
                    }
                    if (lp_theme_option('lp_featured_file_switch')) {
                        $output .= '
												<div class="form-group clearfix margin-bottom-0 margin-top-10 lp-listing-featuredimage">
													<label class="margin-top-10">' . esc_html__('Upload Feature Image', 'medicalpro') . '</label>
													
													<div class="custom-file">
														<input style="display:none;" type="file" name="lp-featuredimage[]" id="lp-featuredimage" class="inputfile inputfile-3" data-multiple-caption="{count} files selected" multiple />
														<label class="featured-img-label" for="lp-featuredimage" data-quick-tip="<p>' . esc_html__('quick tip for profile img', 'medicalpro') . '</p>' . $quicktip_image . '"><p>' . esc_html__('Browse', 'medicalpro') . '</p><span>' . esc_html__('Choose a file', 'medicalpro') . '&hellip;</span></label>
													</div>
												</div>
												';
                    }

                    $b_logo = $listingpro_options['business_logo_switch'];
                    if ($b_logo == 1) :
                        $output .= '<div class="form-group clearfix margin-bottom-0 margin-top-10 lp-listing-featuredimage">
										<label class="margin-top-10">' . esc_html__('Upload Business Logo', 'medicalpro') . '</label>
										
										<div class="custom-file">
											<input style="display:none;" type="file" name="business_logo[]" id="business_logo" class="inputfile inputfile-4" />
											<label class="b-logo-img-label" for="business_logo" data-quick-tip="' . esc_html__('quick tip for business logo', 'medicalpro') . '"><p>' . esc_html__('Browse', 'medicalpro') . '</p><span>' . esc_html__('Choose a file', 'medicalpro') . '&hellip;</span></label>
								</div>
									</div>';
                    endif;
                    $output .= '</div>';
                    if ($page_style != 'style2') {
                        $output .= '<div class="form-group col-md-6 col-xs-12">';
                        if ($submit_ad_img3_switch == 1) {
                            $output .= '
										<div class="submit-img">
											<img src="' . $submitImg3 . '" alt="">
										</div>';
                        }
                        $output .= '
						</div>';
                    }
                    $output .= '</div>
						</div>';
                }
            }
            if ($page_style != 'style2') {
                $output .= '
						<div class="blue-section">
							<div class="row">
								<div class="form-group col-md-6 margin-bottom-0">';
                if (!is_user_logged_in()) {

                    $output .= '
										<div class="lp-submit-accoutn lp-submit-accoutn-wrap" data-quick-tip="' . esc_html__('quick tip for account section', 'medicalpro') . '">
											<div class="lp-submit-no-account">';
                    if (!empty($enableUsernameField)) {
                        $output .= '<div class="row"><div class="col-md-6">';
                    }
                    $output .= '<label for="inputEmail">' . $listingEmailText . '</label>
												<input type="email" class="form-control" name="email" id="inputEmail" placeholder="' . esc_html__('your contact email', 'medicalpro') . '">';

                    if (!empty($enableUsernameField)) {
                        $output .= '</div><div class="col-md-6">';
                        $output .= '<label for="customUname">' . $listingUserNameText . '</label>
												<input type="text" class="form-control" name="customUname" id="customUname" placeholder="' . esc_html__('user name', 'medicalpro') . '">';
                        $output .= '</div></div>';
                    }
                    $output .= '</div>
											<div class="lp-submit-have-account row" style="display: none;">
												<div class="col-md-6"><label for="inputUsername">' . esc_html__('Email', 'medicalpro') . '</label>
												<input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="' . esc_html__('enter username', 'medicalpro') . '"></div>
												<div class="col-md-6"><label for="inputUserpass">' . esc_html__('Password', 'medicalpro') . '</label>
												<input type="password" class="form-control" name="inputUserpass" id="inputUserpass" placeholder="' . esc_html__('enter password', 'medicalpro') . '"></div>
											</div>
										</div>
										<div class="checkbox already-account-checkbox"> <input type="checkbox" id="already-account" value=""><label for="already-account" class="already-account">' . esc_html__('Already Have Account?', 'medicalpro') . '</label></div>

										';
                } else {
                    $output .= '<div id="inputEmail"></div>';
                }
                $output .= '
								</div>
								<div class="form-group col-md-6 margin-bottom-0 preview-section-caption clearfix">';

                if (!empty($privacy_policy) && lp_theme_option('listingpro_privacy_listing') == 'yes') {

                    $output .= '
										<div class="checkbox form-group col-md-4 check_policy termpolicy">
												<input id="policycheck" type="checkbox" name="policycheck" value="true">
												<label for="policycheck"><a target="_blank" href="' . get_the_permalink($privacy_policy) . '" class="help" target="_blank">' . esc_html__('I Agree', 'medicalpro') . '</a></label>
												<div class="help-text">
													<a class="help" target="_blank"><i class="fa fa-question"></i></a>
													<div class="help-tooltip">
													<p>' . esc_html__('You agree & accept our Terms & Conditions for submitting listing', 'medicalpro') . '</p>
													</div>
												</div>
											</div>';
                }


                $output .= '
									<div class="form-group clearfix margin-bottom-0 preview-section pos-relative col-md-8 pull-right">';
                //$output .= 'fdf';

                $output .= '<div class="clearfix"></div>';
                $output .= '<div class="submitbutton-wraper">';
                $output .= '
												<label for="previewListing">' . esc_html__('Click below to review your listing.', 'medicalpro') . '</label>
												<div class="success_box">' . esc_html__('All of the fields were successfully validated!', 'medicalpro') . '</div>
												<div class="error_box"></div>
												<input type="hidden" name="plan_id" value="' . $plan_id . '" /> 
												<input type="hidden" name="errorrmsg" value="' . esc_html__('Something is missing! Please fill out all fields highlighted in red.', 'medicalpro') . '" /> 
												<input type="submit" id="listingsubmitBTN" name="listingpost" value="' . $btnText . '" class="lp-secondary-btn btn-first-hover" />
												<i class="fa fa-angle-right lpsubmitloading"></i>
											</div>';
                $output .= '</div>';
                $output .= wp_nonce_field('post_nonce', 'post_nonce_field', true, false);
                $output .= '
								</div>
							</div>
						</div>';
            } else {
                if (!is_user_logged_in()) {
                    $output .= '
								<div class="white-section border-bottom ' . $style_wrap . '">
									<div class="row">
										<div class="form-group col-md-12 margin-bottom-0">';
                    $output .= '
												<div class="lp-submit-accoutn lp-submit-accoutn-wrap">
													<div class="lp-submit-no-account">';
                    if (!empty($enableUsernameField)) {
                        $output .= '<div class="row"><div class="col-md-6">';
                    }
                    $output .= '<label for="inputEmail">' . $listingEmailText . '</label>
														<input type="email" class="form-control" name="email" id="inputEmail" placeholder="' . esc_html__('your contact email', 'medicalpro') . '">';

                    if (!empty($enableUsernameField)) {
                        $output .= '</div><div class="col-md-6">';
                        $output .= '<label for="customUname">' . $listingUserNameText . '</label>
														<input type="text" class="form-control" name="customUname" id="customUname" placeholder="' . esc_html__('user name', 'medicalpro') . '">';
                        $output .= '</div></div>';
                    }
                    $output .= '</div>
													<div class="lp-submit-have-account row" style="display: none;">
														<div class="col-md-6"><label for="inputUsername">' . esc_html__('Email', 'medicalpro') . '</label>
														<input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="' . esc_html__('enter username', 'medicalpro') . '"></div>
														<div class="col-md-6"><label for="inputUserpass">' . esc_html__('Password', 'medicalpro') . '</label>
														<input type="password" class="form-control" name="inputUserpass" id="inputUserpass" placeholder="' . esc_html__('enter password', 'medicalpro') . '"></div>
						</div>
												</div>
												<div class="checkbox already-account-checkbox"> <input type="checkbox" id="already-account" value=""><label for="already-account" class="already-account">' . esc_html__('Already Have Account?', 'medicalpro') . '</label></div>
			
												';
                    $output .=  '</div>
									</div>
								</div>';
                }
                if (!empty($privacy_policy)) {
                    $output .= '
								<div class="white-section">
									<div class="row">';
                    if (!empty($privacy_policy) && lp_theme_option('listingpro_privacy_listing') == 'yes') {
                        $output .= ' 			<div class="form-group col-md-12 margin-bottom-0">
													<div class="checkbox form-group col-md-4 check_policy termpolicy">
														<input id="policycheck" type="checkbox" name="policycheck" value="true">
														<label for="policycheck"><a target="_blank" href="' . get_the_permalink($privacy_policy) . '" class="help" target="_blank">' . esc_html__('I Agree', 'medicalpro') . '</a></label>
														<div class="help-text">
															<a class="help" target="_blank"><i class="fa fa-question"></i></a>
															<div class="help-tooltip">
																<p>' . esc_html__('You agree you accept our Terms & Conditions for posting this ad.', 'medicalpro') . '</p>
															</div>
														</div>
													</div>
											  </div>';
                    }


                    $output .=  '</div>
									</div>';
                }
                $output .= '<div class="submitbutton-wraper submitbutton-wraper-style2">';
                $output .= ' <div class="success_box">' . esc_html__('All of the fields were successfully validated!', 'medicalpro') . '</div>
									<div class="error_box"></div>
									<input type="hidden" name="plan_id" value="' . $plan_id . '" /> 
									<input type="hidden" name="errorrmsg" value="' . esc_html__('Something is missing! Please fill out all fields highlighted in red.', 'medicalpro') . '" /> 
									<input type="submit" id="listingsubmitBTN" name="listingpost" value="' . $btnText . '" class="lp-secondary-btn btn-first-hover" />
									<i class="fa bottomofbutton lpsubmitloading"></i>
								
								</div>';
                $output .= wp_nonce_field('post_nonce', 'post_nonce_field', true, false);
            }

            if ($page_style == 'style2') {
                $output .=  '</div><div class="col-md-4 page-style2-sidebar-wrap ' . $sidebar_sticky . '" style="top: ' . $lp_submit_sidebar_top . ';">
										<div class="page-style2-sidebar">';
                if ($quick_tip_switch == 1) {
                    $output .= '
										<div class="quick_tip quick_tip_style2 ' . $style_wrap . '">
											<div class="quick-tip-inner">
											<h2>' . $quickTipTitle . '</h2>
												<p>' . $quickTipText . '</p>';
                    if ($submit_ad_img_switch == 1) {
                        $output .= '
													<div class="submit-img">
														<img src="' . $submitImg . '" alt="">
													</div>';
                    }
                    $output .=  '</div>';
                    $output .=  '</div>';
                }

                $output .=  '</div>
	</div>
	<div class="clearfix"></div> </div>';
            }
        }


        $output .=  '</form>
				</div>
			</div>
		</div>';

        $output .= ajax_response_markup(true);

        ob_end_clean();
        ob_flush();
        return $output;
    }
}
add_shortcode('medpro_submit', 'medpro_shortcode_submit');
