<?php

add_shortcode( 'lead-form', 'customizer_lead_form' );
add_shortcode( 'lp-customizer-field', 'lp_customizer_field' );
if(!function_exists('lp_customizer_field_options')) {
    function lp_customizer_field_options($placeholder, $label, $type, $name) {
        $options    =   '';
        ob_start();
        ?>
        <div class="lp-lead-form-options-wrap lp-lead-form-options-wraper">
            <?php
            if(
                ($type != 'date' && $type != 'datetime-local' && $type != 'time' && $type != 'dropdown' && $name != 'name7' && $name != 'phone7' && $name != 'email7' && $name != 'message7')
            ) {
                if($type == 'radio' || $type == 'checkbox') {
                    ?>
                    <div class="lp-option-wrap">
                        <label>Label</label>
                        <input type="text" value="<?php echo $label; ?>" class="opt-label">
                    </div>
                    <?php
                }
            }
            if($type != 'checkbox' && $type != 'radio') {
                if($type == 'dropdown' || $type == 'time' || $type == 'datetime-local' || $type == 'date'){
                    ?>
                    <div class="lp-option-wrap">
                        <label>Placeholder</label>
                        <input type="text" value="<?php echo $label; ?>" class="opt-label">
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="lp-option-wrap">
                        <label>Placeholder</label>
                        <input type="text" value="<?php echo $placeholder; ?>" class="lp-opt-placeholder">
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
        $options    .=  ob_get_contents();
        ob_get_clean();

        return $options;
    }
}

if( !is_admin() && !isset( $_GET['dashboard'] ) )
{
    function customizer_lead_form( $atts, $content = null )
    {
        global $listingpro_options, $post;

        $gSiteKey = lp_theme_option('lp_recaptcha_site_key');
        $enableCaptcha = lp_check_receptcha('lp_recaptcha_lead');

        // listing detail page 
        $lp_detail_page_styles = $listingpro_options['lp_detail_page_styles'];

        $showleadform = false;
        $lp_leadForm = $listingpro_options['lp_lead_form_switch'];
        if($lp_leadForm=="1"){
            $claimed_section = listing_get_metabox('claimed_section');
            $show_leadform_only_claimed = $listingpro_options['lp_lead_form_switch_claim'];
            $showleadform = true;
            if($show_leadform_only_claimed== true){
                if($claimed_section == 'claimed') {
                    $showleadform = true;
                }
                else{
                    $showleadform = false;
                }
            }
        }

        $user_id = get_current_user_id();
        $user_facebook = get_the_author_meta('facebook', $user_id);
        $user_google = get_the_author_meta('google', $user_id);
        $user_linkedin = get_the_author_meta('linkedin', $user_id);
        $user_instagram = get_the_author_meta('instagram', $user_id);
        $user_twitter = get_the_author_meta('twitter', $user_id);
        $user_pinterest = get_the_author_meta('pinterest', $user_id);

        if($showleadform == true)
        {
            ob_start();
            ?>
            <style>
                .range-wraper{
                    position: relative;
                }
                .range-wraper .range-c{
                    position: absolute;
                    width: 100px;
                    text-align: center;
                    left: 50%;
                    margin-left: -50px;
                    bottom: -5px;
                }
                .range-wraper input{
                    display: inline-block;
                    width: 76%;
                }
                .range-wraper span{
                    display: inline-block;
                }
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    if( jQuery('.range-wraper').length != 0 )
                    {
                        jQuery('.range-wraper').each(function (index) {
                            var $this   =   jQuery(this),
                                inputT  =   $this.find('input'),
                                rangeT  =   $this.find('.range-c');

                            jQuery(this).on('change', function () {
                                //alert(jQuery(this).val());
                            });
                        });
                    }
                });
            </script>
            <?php if($lp_detail_page_styles == 'lp_detail_page_styles1'){ ?>
            <div class="widget-box business-contact lp-lead-form-st">
                <div class="user_text">
                    <?php
                    $author_avatar_url = get_user_meta(get_the_author_meta( 'ID' ), "listingpro_author_img_url", true);
                    $avatar ='';
                    if(!empty($author_avatar_url))
                    {
                        $avatar =  $author_avatar_url;

                    } else {
                        $avatar_url = listingpro_get_avatar_url (get_the_author_meta( 'ID' ), $size = '94' );
                        $avatar =  $avatar_url;

                    }
                    ?>
                    <div class="author-img">
                        <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><img src="<?php echo esc_url($avatar); ?>" alt=""></a>
                    </div>
                    <div class="author-social">
                        <div class="status">
                            <span class="online"><a ><?php echo get_the_author_meta('display_name'); ?></a></span>
                        </div>
                        <ul class="social-icons post-socials">
                            <?php if(!empty($user_facebook)) { ?>
                                <li>
                                    <a href="<?php echo esc_url($user_facebook); ?>">
                                        <?php echo listingpro_icons('fbGrey'); ?>
                                    </a>
                                </li>
                            <?php } if(!empty($user_google)) { ?>
                                <li>
                                    <a href="<?php echo esc_url($user_google); ?>">
                                        <?php echo listingpro_icons('googleGrey'); ?>
                                    </a>
                                </li>
                            <?php } if(!empty($user_instagram)) { ?>
                                <li>
                                    <a href="<?php echo esc_url($user_instagram); ?>">
                                        <?php echo listingpro_icons('instaGrey'); ?>
                                    </a>
                                </li>
                            <?php } if(!empty($user_twitter)) { ?>
                                <li>
                                    <a href="<?php echo esc_url($user_twitter); ?>">
                                        <?php echo listingpro_icons('tmblrGrey'); ?>
                                    </a>
                                </li>
                            <?php } if(!empty($user_linkedin)) { ?>
                                <li>
                                    <a href="<?php echo esc_url($user_linkedin); ?>">
                                        <?php echo listingpro_icons('clinkedin'); ?>
                                    </a>
                                </li>
                            <?php } if(!empty($user_pinterest)) { ?>
                                <li>
                                    <a href="<?php echo esc_url($user_pinterest); ?>">
                                        <?php echo listingpro_icons('cinterest'); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="contact-form quickform">
                    <form data-lp-recaptcha="<?php echo $enableCaptcha; ?>" data-lp-recaptcha-sitekey="<?php echo $gSiteKey; ?>" class="form-horizontal hidding-form-feilds margin-top-20"  method="post" id="contactOwner">
                        <?php

                        $author_id = '';
                        $author_email = '';
                        $author_email = get_the_author_meta( 'user_email' );
                        $author_id = get_the_author_meta( 'ID' );
                        $gSiteKey = '';
                        $gSiteKey = $listingpro_options['lp_recaptcha_site_key'];
                        $enableCaptcha = lp_check_receptcha('lp_recaptcha_lead');
                        ?>
                        <?php
                        echo do_shortcode( $content );
                        ?>
                        <div class="form-group">
                            <?php
                            if($enableCaptcha==true){
                                if ( class_exists( 'cridio_Recaptcha' ) ){
                                    if ( cridio_Recaptcha_Logic::is_recaptcha_enabled() ) {
                                        echo  '<div id="recaptcha-'.get_the_ID().'" class="g-recaptcha" data-sitekey="'.$gSiteKey.'"></div>';
                                    }
                                }
                            }

                            ?>

                        </div>
                        <?php

                        $privacy_policy = $listingpro_options['payment_terms_condition'];
                        $privacy_lead = $listingpro_options['listingpro_privacy_leadform'];

                        if (!empty($privacy_policy) && $privacy_lead == "yes") {
                            ?>

                            <div class="form-group lp_privacy_policy_Wrap">
                                <input class="lpprivacycheckboxopt" id="reviewpolicycheck" type="checkbox"
                                       name="reviewpolicycheck" value="true">
                                <label for="reviewpolicycheck"><a target="_blank"
                                                                  href="<?php echo get_the_permalink($privacy_policy); ?>"
                                                                  class="help"
                                                                  target="_blank"><?php echo esc_html(__('I Agree', 'listingpro-lead-form')); ?></a></label>
                                <div class="help-text">
                                    <a class="help" target="_blank"><i class="fa fa-question"></i></a>
                                    <div class="help-tooltip">
                                        <p><?php echo esc_html(__('You agree & accept our Terms & Conditions for posting this information?.', 'listingpro-lead-form')); ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group margin-bottom-0 pos-relative">
                                <input type="submit" value="<?php echo esc_html(__('Send', 'listingpro-lead-form')); ?>" class="lp-review-btn btn-second-hover" disabled>
                                <input type="hidden" value="<?php the_ID(); ?>" name="post_id">
                                <input type="hidden" value="<?php echo esc_attr($author_email); ?>"
                                       name="author_email">
                                <input type="hidden" value="<?php echo esc_attr($author_id); ?>" name="author_id">
                                <i class="lp-search-icon fa fa-send"></i>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="form-group margin-bottom-0 pos-relative">
                                <input type="submit" value="<?php echo esc_html(__('Send', 'listingpro-lead-form')); ?>" class="lp-review-btn btn-second-hover">
                                <input type="hidden" value="<?php the_ID(); ?>" name="post_id">
                                <input type="hidden" value="<?php echo esc_attr($author_email); ?>"
                                       name="author_email">
                                <input type="hidden" value="<?php echo esc_attr($author_id); ?>" name="author_id">
                                <i class="lp-search-icon fa fa-send"></i>
                            </div>
                            <?php
                        }
                        ?>

                    </form>
                    <!--start lead form success msg section-->
                    <div class="lp-lead-success-msg-outer">
                        <div class="lp-lead-success-msg">
                            <p><img src="<?php echo listingpro_icons_url('lp_lead_success')?>"><?php echo esc_html( __('Your request has been submitted successfully.','listingpro-lead-form')); ?></p>
                        </div>
                        <span class="lp-cross-suces-layout"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </div>
                    <!--end lead form success msg section-->

                </div>
            </div>
            <?php } ?>
            
            <?php if($lp_detail_page_styles == 'lp_detail_page_styles2'){ ?>
            <div class="widget-box business-contact business-contact2">
                    <div class="contact-form quickform">                                        
                        <div class="user_text">
                            <?php
                            $author_avatar_url = get_user_meta(get_the_author_meta( 'ID' ), "listingpro_author_img_url", true); 
                            $avatar ='';
                            if(!empty($author_avatar_url)) {
                                $avatar =  $author_avatar_url;

                            } else {            
                                $avatar_url = listingpro_get_avatar_url (get_the_author_meta( 'ID' ), $size = '94' );
                                $avatar =  $avatar_url;

                            }
                        ?>
                            <div class="author-img">
                                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><img src="<?php echo esc_url($avatar); ?>" alt=""></a>
                            </div>
                            <div class="author-social">
                                <div class="status">
                                    <span class="online"><a ><?php echo get_the_author_meta('display_name'); ?></a></span>                                                  
                                </div>
                                <ul class="social-icons post-socials">
                                    <?php if(!empty($user_facebook)) { ?>
                                    <li>
                                        <a href="<?php echo esc_url($user_facebook); ?>">
                                            <?php echo listingpro_icons('fbGrey'); ?>
                                        </a>
                                    </li>
                                    <?php } if(!empty($user_google)) { ?>
                                    <li>
                                        <a href="<?php echo esc_url($user_google); ?>">
                                            <?php echo listingpro_icons('googleGrey'); ?>
                                        </a>
                                    </li>
                                    <?php } if(!empty($user_instagram)) { ?>
                                    <li>
                                        <a href="<?php echo esc_url($user_instagram); ?>">
                                            <?php echo listingpro_icons('instaGrey'); ?>
                                        </a>
                                    </li>
                                    <?php } if(!empty($user_twitter)) { ?>
                                    <li>
                                        <a href="<?php echo esc_url($user_twitter); ?>">
                                            <?php echo listingpro_icons('tmblrGrey'); ?>
                                        </a>
                                    </li>
                                    <?php } if(!empty($user_linkedin)) { ?>
                                    <li>
                                        <a href="<?php echo esc_url($user_linkedin); ?>">
                                            <?php echo listingpro_icons('clinkedin'); ?>
                                        </a>
                                    </li>
                                    <?php } if(!empty($user_pinterest)) { ?>
                                    <li>
                                        <a href="<?php echo esc_url($user_pinterest); ?>">
                                            <?php echo listingpro_icons('cinterest'); ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        
                            <form data-lp-recaptcha="<?php echo $enableCaptcha; ?>" data-lp-recaptcha-sitekey="<?php echo $gSiteKey; ?>" class="form-horizontal hidding-form-feilds margin-top-20"  method="post" id="contactOwner">
                                <?php
                                
                                $author_id = '';
                                $author_email = '';
                                $author_email = get_the_author_meta( 'user_email' );
                                $author_id = get_the_author_meta( 'ID' );
                                $gSiteKey = '';
                                $gSiteKey = $listingpro_options['lp_recaptcha_site_key'];
                                $enableCaptcha = lp_check_receptcha('lp_recaptcha_lead');
                                
                                ?>
                               <?php echo do_shortcode( $content ); ?>
                                <div class="form-group">
                                <?php
                                    if($enableCaptcha==true){
                                        if ( class_exists( 'cridio_Recaptcha' ) ){ 
                                            if ( cridio_Recaptcha_Logic::is_recaptcha_enabled() ) { 
                                            echo  '<div id="recaptcha-'.get_the_ID().'" class="g-recaptcha" data-sitekey="'.$gSiteKey.'"></div>';
                                            }
                                        }
                                    }

                                ?>
                                
                                </div>
                                
                                <?php
                                $privacy_policy = $listingpro_options['payment_terms_condition'];
                                $privacy_lead = $listingpro_options['listingpro_privacy_leadform'];
                                    if(!empty($privacy_policy) && $privacy_lead=="yes"){
                                ?>
                                
                                    <div class="form-group lp_privacy_policy_Wrap">
                                        <input class="lpprivacycheckboxopt" id="reviewpolicycheck" type="checkbox" name="reviewpolicycheck" value="true">
                                                <label for="reviewpolicycheck"><a target="_blank" href="<?php echo get_the_permalink($privacy_policy); ?>" class="help" target="_blank"><?php echo esc_html( __('I Agree', 'listingpro-lead-form')); ?></a></label>
                                            <div class="help-text">
                                                <a class="help" target="_blank"><i class="fa fa-question"></i></a>
                                                <div class="help-tooltip">
                                                    <p><?php echo esc_html( __('You agree & accept our Terms & Conditions for posting this information?.', 'listingpro-lead-form')); ?></p>
                                                </div>
                                            </div>
                                    </div>
                                    
                                    <div class="form-group margin-bottom-0 pos-relative">
                                        <input type="submit" value="<?php echo esc_html__('Send', 'listingpro-lead-form'); ?>" class="lp-review-btn btn-second-hover" disabled>
                                        <input type="hidden" value="<?php the_ID(); ?>" name="post_id">
                                        <input type="hidden" value="<?php echo esc_attr($author_email); ?>" name="author_email">
                                        <input type="hidden" value="<?php echo esc_attr($author_id); ?>" name="author_id">
                                        <i class="lp-search-icon fa fa-send"></i>
                                    </div>
                                <?php
                                    }else{
                                ?>
                                    <div class="form-group margin-bottom-0 pos-relative">
                                        <input type="submit" value="<?php echo esc_html__('Send', 'listingpro-lead-form'); ?>" class="lp-review-btn btn-second-hover">
                                        <input type="hidden" value="<?php the_ID(); ?>" name="post_id">
                                        <input type="hidden" value="<?php echo esc_attr($author_email); ?>" name="author_email">
                                        <input type="hidden" value="<?php echo esc_attr($author_id); ?>" name="author_id">
                                        <i class="lp-search-icon fa fa-send"></i>
                                    </div>
                                <?php
                                    }
                                ?>
                            </form>
                            <!--start lead form success msg section-->
                            <div class="lp-lead-success-msg-outer">
                                <div class="lp-lead-success-msg">
                                    <p><img src="<?php echo listingpro_icons_url('lp_lead_success')?>"><?php echo esc_html( __('Your request has been submitted successfully.','listingpro-lead-form')); ?></p>
                                </div>
                                <span class="lp-cross-suces-layout"><i class="fa fa-times" aria-hidden="true"></i></span>
                            </div>
                            <!--end lead form success msg section-->

                    </div>
            </div>
            <?php } ?>

            <?php if($lp_detail_page_styles == 'lp_detail_page_styles3' || $lp_detail_page_styles == 'lp_detail_page_styles4'){ ?>
            <div class="lp-listing-leadform lp-widget-inner-wrap">
                <h4><?php echo esc_html__( 'Contact with business owner', 'listingpro-lead-form' ); ?></h4>
                <div class="lp-listing-leadform-inner">

                    <form data-lp-recaptcha="<?php echo $enableCaptcha; ?>" data-lp-recaptcha-sitekey="<?php echo $gSiteKey; ?>" class="form-horizontal hidding-form-feilds margin-top-20"  method="post" id="contactOwner">

                        <?php

                        $author_id = '';
                        $author_email = '';
                        $author_email = get_the_author_meta( 'user_email' );
                        $author_id = get_the_author_meta( 'ID' );
                        $gSiteKey = '';
                        $gSiteKey = $listingpro_options['lp_recaptcha_site_key'];
                        $enableCaptcha = lp_check_receptcha('lp_recaptcha_lead');
                        ?>
                        <div class="lp-leadform-customizer-st3">
                        <?php
                            echo do_shortcode( $content );
                        ?>
                        </div>
                        <?php

                        if($enableCaptcha==true){

                            if ( class_exists( 'cridio_Recaptcha' ) ){

                                echo '<div class="form-group">';

                                if ( cridio_Recaptcha_Logic::is_recaptcha_enabled() ) {

                                    echo  '<div id="recaptcha-'.get_the_ID().'" class="g-recaptcha" data-sitekey="'.$gSiteKey.'"></div>';

                                }

                                echo '</div>';

                            }

                        }

                        ?>

                        <?php
                        $privacy_policy = $listingpro_options['payment_terms_condition'];
                        $privacy_lead = $listingpro_options['listingpro_privacy_leadform'];

                        if (!empty($privacy_policy) && $privacy_lead == "yes") {
                            ?>

                            <div class="form-group lp_privacy_policy_Wrap">
                                <input class="lpprivacycheckboxopt" id="reviewpolicycheck" type="checkbox"
                                       name="reviewpolicycheck" value="true">
                                <label for="reviewpolicycheck"><a target="_blank"
                                                                  href="<?php echo get_the_permalink($privacy_policy); ?>"
                                                                  class="help"
                                                                  target="_blank"><?php echo esc_html(__('I Agree', 'listingpro-lead-form')); ?></a></label>
                                <div class="help-text">
                                    <a class="help" target="_blank"><i class="fa fa-question"></i></a>
                                    <div class="help-tooltip">
                                        <p><?php echo esc_html(__('You agree & accept our Terms & Conditions for posting this information?.', 'listingpro-lead-form')); ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group margin-bottom-0 pos-relative">
                                <input type="submit" value="<?php echo esc_html(__('Send', 'listingpro-lead-form')); ?>" disabled class="lp-review-btn btn-second-hover">
                                <input type="hidden" value="<?php the_ID(); ?>" name="post_id">
                                <input type="hidden" value="<?php echo esc_attr($author_email); ?>"
                                       name="author_email">
                                <input type="hidden" value="<?php echo esc_attr($author_id); ?>" name="author_id">
                                <i class="lp-search-icon fa fa-send"></i>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="form-group margin-bottom-0 pos-relative">
                                <input type="submit" value="<?php echo esc_html(__('Send', 'listingpro-lead-form')); ?>" class="lp-review-btn btn-second-hover">
                                <input type="hidden" value="<?php the_ID(); ?>" name="post_id">
                                <input type="hidden" value="<?php echo esc_attr($author_email); ?>"
                                       name="author_email">
                                <input type="hidden" value="<?php echo esc_attr($author_id); ?>" name="author_id">
                                <i class="lp-search-icon fa fa-send"></i>
                            </div>
                            <?php
                        }
                        ?>

                    </form>
                    <!--start lead form success msg section-->
                    <div class="lp-lead-success-msg-outer">
                        <div class="lp-lead-success-msg">
                            <p><img src="<?php echo listingpro_icons_url('lp_lead_success')?>"><?php echo esc_html( __('Your request has been submitted successfully.','listingpro-lead-form')); ?></p>
                        </div>
                        <span class="lp-cross-suces-layout"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </div>
                    <!--end lead form success msg section-->
                </div>
            </div>
            <?php } ?>

            <?php
            return ob_get_clean();
        }
    }

    function lp_customizer_field( $atts, $cotent = null )
    {
        extract(shortcode_atts(array(
            'type'   	=> 'text',
            'name'      =>  'name7',
            'placeholder' => '',
            'class' => '',
            'options' => '',
            'required' => '',
            'min' => '',
            'max' => '',
            'step' => '',
            'def' => '',
            'label' => '',
            'multiselect' => 'no'
        ), $atts));

        if( $required == 'yes' )
        {
            $required   =   'lp-required-field';
        }
        else
        {
            $required   =   '';
        }
        ob_start();
        if( $type == 'text' )
        {
            echo lp_customizer_text_field(  $name, $placeholder, $class, $required, $label );
        }
        if( $type == 'url' )
        {
            echo lp_customizer_url_field(  $name, $placeholder, $class, $required, $label );
        }
        if( $type == 'tel' )
        {
            echo lp_customizer_tel_field(  $name, $placeholder, $class, $required, $label );
        }
        if( $type == 'range' )
        {
            echo lp_customizer_range_field(  $name, $placeholder, $class, $required, $min, $max, $step, $def, $label );
        }
        if( $type == 'date' )
        {
            echo lp_customizer_date_field(  $name, $placeholder, $class, $required, $label );
        }
        if( $type == 'time' )
        {
            echo lp_customizer_time_field(  $name, $placeholder, $class, $required, $label );
        }
        if( $type == 'datetime-local' )
        {
            echo lp_customizer_datetime_local_field(  $name, $placeholder, $class, $required, $label );
        }
        if( $type == 'file' )
        {
            echo lp_customizer_file_field(  $name, $placeholder, $class, $required, $label );
        }
        if( $type == 'email' )
        {
            echo lp_customizer_email_field(  $name, $placeholder, $class, $required, $label );
        }
        if( $type == 'textarea' )
        {
            echo lp_customizer_textarea_field( $name, $placeholder, $class, $required, $label );
        }
        if( $type == 'dropdown' )
        {
            echo lp_customizer_dropdown_field( $name, $options, $class, $required, $label, $multiselect );
        }
        if( $type == 'checkbox' )
        {
            echo lp_customizer_checkbox_field( $name, $options, $class, $required, $label );
        }
        if( $type == 'radio' )
        {
            echo lp_customizer_radio_field( $name, $options, $class, $required, $label );
        }

        return ob_get_clean();
    }

    function lp_customizer_text_field( $name, $placeholder, $class, $required, $label )
    {
        ob_start();
        ?>
        <div class="form-group">
            <?php
            if( $name != 'name7' && $name != 'phone7' )
            {
                ?>
                <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
                <?php
            }
            ?>

            <input type="text" class="form-control <?php echo $class; ?> <?php echo $required; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $placeholder; ?>">
            <span id="<?php echo $name; ?>"></span>
        </div>
        <?php
        return ob_get_clean();
    }
    function lp_customizer_url_field( $name, $placeholder, $class, $required, $label )
    {
        ob_start();
        ?>
        <div class="form-group">
            <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
            <input type="url" class="form-control <?php echo $class; ?> <?php echo $required; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $placeholder; ?>">
            <span id="<?php echo $name; ?>"></span>
        </div>
        <?php
        return ob_get_clean();
    }
    function lp_customizer_tel_field( $name, $placeholder, $class, $required, $label )
    {
        ob_start();
        ?>
        <div class="form-group">
            <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
            <input type="tel" class="form-control <?php echo $class; ?> <?php echo $required; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $placeholder; ?>">
            <span id="<?php echo $name; ?>"></span>
        </div>
        <?php
        return ob_get_clean();
    }
    function lp_customizer_range_field( $name, $placeholder, $class, $required, $min, $max, $step, $def, $label )
    {
        ob_start();
        ?>
        <div class="form-group">
            <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
            <span class="lp-lead-select-text"><?php echo $label; ?></span>
           <div class="range-wraper lp-lead-range-wraper">
                <span class="range-c">Default Range: <?php echo $def; ?></span>
                <span class="range-start"><?php echo $min; ?></span>
                <div class="range-slidecontainer">
                <input value="<?php echo $def; ?>" min="<?php echo $min; ?>" max="<?php echo $max; ?>" step="<?php echo $step; ?>" type="range" class="<?php echo $required; ?> lp-range-slide range-set form-control <?php echo $class; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $placeholder; ?>">
                </div>
                <span class="range-end"><?php echo $max; ?></span>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    function lp_customizer_date_field( $name, $placeholder, $class, $required, $label )
    {
        ob_start();
        ?>
        <div class="form-group  input-group date datetimepicker2">
            <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
            <span class="input-group-addon">
            <input type="text" class="date1 form-control <?php echo $required; ?> <?php echo $class; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $label; ?>">
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </span>
        </div>
        <?php
        return ob_get_clean();
    }
    function lp_customizer_time_field( $name, $placeholder, $class, $required, $label )
    {
        ob_start();
        ?>
        <div class="form-group input-group date datetimepicker1">
            <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
            <span class="input-group-addon">
            <input type="text" class="form-control <?php echo $required; ?> <?php echo $class; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $label; ?>">
            
                <i class="fa fa-clock-o" aria-hidden="true"></i>
            </span>
        </div>
        <?php
        return ob_get_clean();
    }
    function lp_customizer_datetime_local_field( $name, $placeholder, $class, $required, $label )
    {
        ob_start();
        ?>
        <div class="form-group input-group date datetimepicker3">
            <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
            <span class="input-group-addon">
            <input type="text" class="form-control <?php echo $required; ?> <?php echo $class; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $label; ?>">
            
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </span>
        </div>
        <?php
        return ob_get_clean();
    }
    function lp_customizer_file_field( $name, $placeholder, $class, $required, $label )
    {
        ob_start();

        ?>
        <div class="form-group">
            <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
        <span class="lp-lead-select-text"><?php echo $label; ?></span>
        <div class="custom-file lp-lead-custom-file">
            <input style="display:none;" type="file" class="<?php echo $required; ?> inputfile inputfile-4 form-control <?php echo $class; ?>" name="<?php echo $name; ?>" id="business_logo" placeholder="<?php echo $placeholder; ?>">
            <label class="b-logo-img-label" for="business_logo" data-quick-tip="quick tip for business logo"><p><?php echo esc_html( __('Browse','listingpro-lead-form')); ?></p><span><?php echo $placeholder; ?></span></label>
        </div>
        </div>
        <?php
        return ob_get_clean();
    }
    function lp_customizer_email_field( $name, $placeholder, $class, $required, $label )
    {
        ob_start();
        ?>
         <div class="form-group form-group-icon">
             <?php
             if( $name != 'email7' )
             {
                 ?>
                 <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
                 <?php
             }
             ?>
            <i class="fa fa-envelope" aria-hidden="true"></i>
            <input type="email" class="form-control <?php echo $class; ?> <?php echo $required; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $placeholder; ?>">
        </div>
        <?php
        return ob_get_clean();
    }
    function lp_customizer_textarea_field( $name, $placeholder, $class, $required, $label )
    {
        ob_start();
        ?>
        <div class="form-group">
            <?php
            if( $name != 'message7' )
            {
                ?>
                <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
                <?php
            }
            ?>
            <textarea class="form-control <?php echo $required; ?> <?php echo $class; ?>" rows="5" name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $placeholder; ?>"></textarea>
        </div>
        <?php
        return ob_get_clean();
    }

    function lp_customizer_dropdown_field( $name, $options, $class, $required, $label, $multiselect )
    {
        $options_arr    =   explode( ',', $options );
        $multiple   =   '';
        if($multiselect == 'yes') {
            $multiple   =   'multiple';
        }
        ob_start();
        ?>
        <div class="form-group <?php echo $class; ?>">
            <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
            <select <?php echo $multiple; ?> class="form-control <?php echo $required; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
                <option value="0"><?php echo $label; ?></option>
                <?php
                foreach ( $options_arr as $value )
                {
                    if( !empty( $value ) )
                    {
                        echo '<option>'. $value .'</option>';
                    }
                }
                ?>
            </select>
        </div>
        <?php
        return ob_get_clean();
    }
    function lp_customizer_checkbox_field( $name, $options, $class, $required, $label )
    {
        $options_arr    =   explode( ',', $options );
        ob_start();
        ?>
       <div class="form-group <?php echo $class; ?>" id="<?php echo $name; ?>" <?php echo $required; ?>>
           <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
            <span class="lp-lead-select-text"><?php echo $label; ?></span>
            <?php
            foreach ( $options_arr as $value )
            {
                if( !empty( $value ) )
                {
                    echo '<label class="lp-lead-check-container"><input type="checkbox" name="'.$name .'" class="form-control '. $required .' '.$class.'"> '. $value .'<span class="lp-lead-check-checkmark"></span></label>';
                }
            }
            ?>

        </div>
        <?php
        return ob_get_clean();
    }
    function lp_customizer_radio_field( $name, $options, $class, $required, $label )
    {
        $options_arr    =   explode( ',', $options );
        ob_start();
        ?>
        <div class="form-group <?php echo $class; ?>" id="<?php echo $name; ?>" <?php echo $required; ?>>
            <input type="hidden" name="<?php echo $name; ?>_label" value="<?php echo $label; ?>">
            <span class="lp-lead-select-text"><?php echo $label; ?></span>
            <?php
            foreach ( $options_arr as $value )
            {
                if( !empty( $value ) )
                {
                    echo '<label class="lp-lead-radio-container"><input value="'.$value.'" type="radio" name="'.$name .'" class="form-control '. $required .' '.$class.'"> '. $value .'<span class="lp-lead-checkmark"></span></label>';
                }
            }
            ?>
        </div>
        <?php
        return ob_get_clean();
    }
}

if( is_admin() )
{

    function customizer_lead_form( $atts, $content = null )
    {
        global $listingpro_customizer_options, $listingpro_options, $post;

        ob_start();
        echo do_shortcode( $content );
        return ob_get_clean();
    }

    function lp_customizer_field( $atts, $content = null )
    {
        extract(shortcode_atts(array(
            'type'   	=> 'text',
            'name'      =>  '',
            'placeholder' => '',
            'class' => '',
            'label' => '',
            'options' => '',
            'required' => ''
        ), $atts));

        $optionsAttr    =   '';
        $attrs  =   "data-type='$type' ";
        $attrs  .=  "data-class='$class' ";
        $attrs  .=  "data-placeholder='$placeholder' ";
        $attrs  .=  "data-name='$name' ";
        $attrs  .=  "data-label='$label' ";
        $attrs  .=  "data-required='$required' ";
        ob_start();
        if( $type == 'dropdown' || $type == 'checkbox' || $type == 'radio' )
        {
            $attrs  .=  "data-options='$options'";
        }
        echo '<li '.$attrs.' data-shortcode="[lp-customizer-field]">';
        echo '<i class="fa fa-bars" aria-hidden="true"></i> '. $label;
        if($name != 'name7' && $name != 'phone7' && $name != 'message7' && $name != 'email7') {
            echo '<span class="lp-el-remove"><i class="fa fa-trash-o" aria-hidden="true"></i></span>';
        }
        echo   lp_customizer_field_options($placeholder, $label, $type, $name);
        echo '</li>';
        return ob_get_clean();
    }
}

if( isset( $_GET['dashboard'] ) && $_GET['dashboard'] == 'lead_form' )
{
    function customizer_lead_form( $atts, $content = null )
    {
        global $listingpro_customizer_options, $listingpro_options, $post;

        ob_start();
        echo do_shortcode( $content );
        return ob_get_clean();
    }

    function lp_customizer_field( $atts, $content = null )
    {
        extract(shortcode_atts(array(
            'type'   	=> 'text',
            'name'      =>  '',
            'placeholder' => '',
            'class' => '',
            'label' => '',
            'options' => ''
        ), $atts));

        $optionsAttr    =   '';
        $attrs  =   "data-type='$type' ";
        $attrs  .=  "data-class='$class' ";
        $attrs  .=  "data-placeholder='$placeholder' ";
        $attrs  .=  "data-name='$name' ";
        $attrs  .=  "data-label='$label' ";
        ob_start();
        if( $type == 'dropdown' || $type == 'checkbox' || $type == 'radio' )
        {
            $attrs  .=  "data-options='$options'";
        }
        ?>
        <li <?php echo $attrs; ?> data-shortcode="[lp-customizer-field]">
            <div class="lp-menu-close-outer lp-leadeform-close-outer">
                <div class="lp-menu-closed clearfix ">
                    <span><i class="fa fa-bars" aria-hidden="true"></i></span>
                    <span class="lp-right-side-title"><?php echo $label; ?></span>
                    <span class="pull-right lp-remove-form-field"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
                </div>
            </div>
            <?php echo lp_customizer_field_options($placeholder, $label, $type, $name); ?>
        </li>
        <?php
        return ob_get_clean();
    }
}
?>