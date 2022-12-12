<?php
global $post, $listingpro_options;


$plan_id = listing_get_metabox_by_ID('Plan_id', $post->ID);
if(!empty($plan_id)){
    $plan_id = $plan_id;
}else{
    $plan_id = 'none';
}
$gallery_show = get_post_meta( $plan_id, 'gallery_show', true );
if($plan_id=="none"){
    $gallery_show = 'true';
}

$claim = '';
$showClaim = true;
$claimed_section = listing_get_metabox('claimed_section');
if(empty($claimed_section)){
    $showClaim = true;
}
elseif($claimed_section == 'claimed') {
    $showClaim = false;
}
elseif($claimed_section == 'not_claimed') {
    $showClaim = true;
}
$showHTML = false;

$certified_doctor    = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_certified_doctor', true);

if ($showClaim == true || $certified_doctor != 'Yes'){
    if ($showClaim == true && $certified_doctor == 'Yes'){
        listing_set_metabox('claimed_section', 'claimed', get_the_ID());
        $showClaim = false;
    }
    if ($showClaim == false && $certified_doctor != 'Yes') {
        update_post_meta(get_the_ID(), 'mp_listing_extra_fields_certified_doctor', 'Yes');
        $certified_doctor = 'Yes';
    }
}

if ($showClaim == false) {
    $certified_doctor = 'Yes';
}
if ($certified_doctor == 'Yes') {
    $claim = '<div class="mp-claimed-profile mp-tooltip"><span class="mp-tooltiptext"> ' . esc_html__('Certified Doctor', 'medicalpro') . '</span>
        <img src="' . MP_PLUGIN_DIR . 'assets/images/claimed/claimed.svg' . '" alt="Claimed Profile">
    </div>';
}else {
    $showHTML = true;
}

?>

<div class="row">
    <div class="mp-breadcrumb mp-p-15-fmd">
        <?php if (function_exists('listingpro_breadcrumbs')) listingpro_breadcrumbs(); ?>
    </div>
</div>
<div class="mp-profile-detail">
    <div class="row">
        <div class="col-md-3 mp-p-0-md text-center">
            <?php
            
            // $IDs = get_post_meta($post->ID, 'gallery_image_ids', true);
            // if (!empty($IDs)) {
            //     if ($gallery_show == "true") {
            //         $imgIDs = array();
            //         $numImages = 0;
            //         $ximgIDs = explode(',', $IDs);
            //         if (!empty($ximgIDs)) {
            //             foreach ($ximgIDs as $value) {
            //                 if (!empty(get_post_status($value))) {
            //                     $imgIDs[] = $value;
            //                 }
            //             }
            //             if (!empty($imgIDs)) {
            //                 $numImages = count($imgIDs);
            //             }
            //         }
            //         require_once (THEME_PATH . "/include/aq_resizer.php");
            //         $imgurl = wp_get_attachment_image_src($imgIDs[0], 'full');
            //         $imgSr = aq_resize($imgurl[0], '200', '220', true, true, true);
            //         if (!empty($imgurl[0])) {
            //             $output = '
            //             <div class="mp-profile-image slide_ban text-center">
            //                 <a href="' . $imgurl[0] . '" rel="prettyPhoto[gallery1]">
            //                     <img src="' . $imgSrc . '" alt="' . get_the_title() . '" />
            //                 </a>
            //             </div>';
            //         }
            //     }
            // }
            if (!empty(get_the_post_thumbnail_url())){
                echo '<div class="mp-profile-image slide_ban text-center">
                    <a href="' . get_the_post_thumbnail_url() . '" rel="prettyPhoto[gallery1]">
                        <img src="' . get_the_post_thumbnail_url() . '" alt="' . get_the_title() . '" />
                    </a>
                </div>';
            }else{
                $defimg = 'https://restaurantpro.listingprowp.com/wp-content/themes/listingpro/assets/images/default/placeholder.png';
                $themeDefimg = $listingpro_options['lp_def_featured_image']['url'];
                if (!empty($themeDefimg)){
                    $defimg = $themeDefimg;
                }
                echo '<div class="mp-profile-image slide_ban text-center">
                    <a href="' . $defimg . '" rel="prettyPhoto[gallery1]">
                        <img src="' . $defimg . '" alt="' . get_the_title() . '" />
                    </a>
                </div>';
            }
            
            mp_get_template_part('templates/single-list/listing-details-style1/content/gallery');
            
            ?>
        </div>
        <script type="application/ld+json">
        {"@context":"https://schema.org/","@type":"ImageObject","contentUrl":"<?php echo get_the_post_thumbnail_url(); ?>","license":"https://therapeute.net/license","acquireLicensePage":"https://therapeute.net/terms","creditText":"Redacteur therapeute .net","creator":{"@type":"Person","name":"<?php echo mb_substr(get_the_title(), 0, 40); ?>"},"copyrightNotice":"therapeute .net"}
        </script>
        <script type="application/ld+json">
        {"@context":"https://schema.org","@type":"Organization","url":"https://www.therapeute.net","logo":"https://www.therapeute.net/images/logo.png"}
        </script>
        <div class="col-md-9 mp-p-0-md">
            <div class="mp-profile-content">
                <div class="mp-profile-content-title">
                    <div class="mp-clearfix"></div>
                    <h1><?php the_title(); ?> <?php echo $claim; ?></h1>
                    <div class="mp-clearfix"></div>
                </div>
                <div class="mp-profile-content-rating">
                    <div class="mp-clearfix"></div>
                    <div class="mp-rate 88888888888">
                        <?php
                        $NumberRating = listingpro_ratings_numbers($post->ID);
                        if ($NumberRating != 0) {
                            echo lp_cal_listing_rate($post->ID);
                            ?>

                            <?php
                        } else {
                            echo lp_cal_listing_rate($post->ID);
                        }
                        ?>
                        <i class="fa fa-star"></i>
                    </div>
                    <?php
                    $cats = get_the_terms($post->ID, 'listing-category');
                    if(isset($cats[0]->name) && $cats[0]->name != ''){
                        echo '<h2><a href="'. get_term_link($cats[0]->term_id).'">' . $cats[0]->name . '</a></h2>';
                    }
                    if(isset($cats[0]->name) && $cats[0]->name != ''){
                        $gAddress = 'France';
                        if (true) { 
                            $split = explode(" ", get_the_title());
                            $gAddress = $split[count($split)-1];
                        }
                        ?>
                        <script type="application/ld+json">
                        {"@context":"https://schema.org","@type":"ItemList","itemListElement":[{"@type":"ListItem","position":1,"item":{"@type":"Course","url":"<?php echo get_term_link($cats[0]->term_id); ?>","name":"Trouver les 10 meilleur <?php echo $cats[0]->name; ?> a <?php echo $gAddress; ?>","description":"<?php echo $cats[0]->name; ?>  <?php echo $gAddress; ?> <?php echo mb_substr(get_the_title(), 0, 40); ?>","provider":{"@type":"Organization","name":"<?php echo mb_substr(get_the_title(), 0, 40); ?> - Therapeute ","sameAs":"<?php echo get_the_permalink(); ?>"}}},{"@type":"ListItem","position":2,"item":{"@type":"Course","url":"<?php echo get_the_permalink(); ?>","name":"<?php echo $cats[0]->name; ?> <?php echo $gAddress; ?>","description":"<?php echo mb_substr(get_the_title(), 0, 40); ?> <?php echo $cats[0]->name; ?> <?php echo $gAddress; ?>","provider":{"@type":"Organization","name":"<?php echo mb_substr(get_the_title(), 0, 40); ?> - Therapeute ","sameAs":"<?php echo get_the_permalink(); ?>"}}}]}
                        </script>
                        <script type="application/ld+json">
                        {"@type":"MedicalWebPage","audience":{"@type":"Patient","@context":"https://schema.org"},"specialty":{"@type":"MedicalSpecialty","@context":"https://schema.org","name":"<?php echo $cats[0]->name; ?>"},"@context":"https://schema.org","@id":"#/MedicalWebPage"}
                        </script>
                        <script type="application/ld+json">
                        {"@context":"https://schema.org/","@type":"Quiz","about":{"@type":"Thing","name":"<?php echo $cats[0]->name; ?> a <?php echo $gAddress; ?>"},"educationalAlignment":[{"@type":"AlignmentObject","alignmentType":"educationalSubject","targetName":"Therapeute"}],"hasPart":[{"@context":"https://schema.org/","@type":"Question","eduQuestionType":"Flashcard","text":"Ou trouver un <?php echo $cats[0]->name; ?> a <?php echo $gAddress; ?>.","acceptedAnswer":{"@type":"Answer","text":"<?php echo mb_substr(get_the_title(), 0, 20); ?> Therapeute"}},{"@context":"https://schema.org/","@type":"Question","eduQuestionType":"Flashcard","text":"Comment prendre rendez-vous avec un <?php echo $cats[0]->name; ?> a <?php echo $gAddress; ?>","acceptedAnswer":{"@type":"Answer","text":"<?php echo mb_substr(get_the_title(), 0, 40); ?>"}}]}
                        </script>
                        <?php
                        $facebook = listing_get_metabox('facebook');
                        $twitter = listing_get_metabox('twitter');
                        $linkedin = listing_get_metabox('linkedin');
                        $youtube = listing_get_metabox('youtube');
                        $instagram = listing_get_metabox('instagram');
                        $phone = listing_get_metabox('phone');
                        $website = listing_get_metabox('website');
                        $gmapAddress = listing_get_metabox('gAddress');
                        $latitude = listing_get_metabox('latitude');
                        $longitude = listing_get_metabox('longitude');
                        $whatsapp = listing_get_metabox('whatsapp');
                        
                        ?>
                        <script type="application/ld+json">
                        { "@context": "http://schema.org", "@id": "<?php echo get_the_permalink(); ?>/#MedicalBusiness", "@type": "MedicalBusiness", "name": "<?php echo mb_substr(get_the_title(), 0, 40); ?>", "alternateName": "Therapeute .net", "medicalSpecialty": "<?php echo $cats[0]->name; ?>", "telephone": "<?php echo $phone; ?>", "priceRange": "$$$", "image": "<?php echo get_the_post_thumbnail_url(); ?>", "url": "<?php echo get_the_permalink(); ?>", "sameAs": ["<?php echo $website; ?>", "<?php echo $facebook; ?>", "<?php echo $twitter; ?>", "<?php echo $linkedin; ?>", "<?php echo $youtube; ?>", "<?php echo $instagram; ?>"], "logo": { "@type": "ImageObject", "url": "<?php echo get_the_post_thumbnail_url(); ?>", "width": 600, "height": 60 }, "contactPoint": { "@id": "<?php echo get_the_permalink(); ?>/#mp-services-tab", "@type": "ContactPoint", "contactType": "customer service", "name": "Contact Us", "url": "<?php echo get_the_permalink(); ?>/#mp-add-new-review" }, "address": { "@id": "<?php echo get_the_permalink(); ?>/#mp-location-tab", "@type": "PostalAddress", "streetAddress": "<?php echo $gmapAddress; ?>", "addressLocality": "<?php echo $gAddress; ?>", "postalCode": "<?php echo preg_replace('/[^0-9]/', '', $gmapAddress); ?>", "addressRegion": "FR", "addressCountry": { "@type": "Country", "name": "FR" } }, "makesOffer": { "@id": "<?php echo get_the_permalink(); ?>/#Offer", "@type": "Offer", "businessFunction": "https://www.therapeute.net/listing-category/therapeute/" }, "hasOfferCatalog": { "@type": "OfferCatalog", "url": "https://www.therapeute.net/therapeute/" }, "department": { "@id": "<?php echo get_the_permalink(); ?>/#mp-insurances-tab" }, "founder": { "@id": "#mp-single-list-gallery" } }
                        </script>
                    <?php } ?>
                     
                    <div class="mp-clearfix"></div>
                    
                </div>
                <div class="mp-profile-content-features">
                    <div class="row">
                        <?php
                            $listingExtraOptions = get_post_meta(get_the_ID(), 'mp_listing_extra_fields', true);
                            $virtual_consult     = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_virtual_consult', true);
                            $certified_doctor    = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_certified_doctor', true);
                            $online_prescription = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_online_prescription', true);
                            $taking_new_patient  = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_taking_new_patient', true);
                            
                        if ($virtual_consult == 'Yes') :
                        ?>
                        <div class="col-md-4 margin-bottom-5">
                            <div class="mp-profile-content-feature-icon">
                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/temp/feature1.svg'; ?>" alt="">
                            </div>
                            <span class="mp-profile-content-feature-text"><?php esc_html_e('Video Consultation', 'medicalpro'); ?></span>
                        </div>
                        <?php
                        endif;
                        if ($taking_new_patient == 'Yes') :
                        ?>
                        <div class="col-md-4 margin-bottom-5">
                            <div class="mp-profile-content-feature-icon">
                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/temp/feature2.svg'; ?>" alt="">
                            </div>
                            <span class="mp-profile-content-feature-text"><?php esc_html_e('Taking New Patient', 'medicalpro'); ?></span>
                        </div>
                        <?php
                        endif;
                        if ($online_prescription == 'Yes') :
                        ?>
                        <div class="col-md-4 margin-bottom-5">
                            <div class="mp-profile-content-feature-icon">
                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/temp/feature3.svg'; ?>" alt="">
                            </div>
                            <span class="mp-profile-content-feature-text"><?php esc_html_e('Online Prescription', 'medicalpro'); ?></span>
                        </div>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
               <div class="mp-profile-content-details margin-bottom-30">
                    <?php
                    $listingContent = get_post($post->ID);
                    if (isset($listingContent->post_content) && $listingContent->post_content !== "") { ?>
                        <p class="mp-profile-content-detail" data-readmore="<?php esc_html_e('Read More', 'medicalpro'); ?>" data-readless="<?php esc_html_e('Read Less', 'medicalpro'); ?>">
                            <?php 
                            $content = apply_filters('the_content', $listingContent->post_content);
                            $content = str_replace(']]>', ']]&gt;', $content);
                            echo $content;
                            ?>
                        </p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row post-meta-right-box md-post-meta-right-box ">
        <div class="mp-profile-social-actions  post-stat  mp-p-15-fmd">
            <div class="mp-clearfix"></div>
            <div class="mp-profile-social-action mp-profile-social-action-share pull-left reviews sbutton">
                <?php listingpro_sharing(); ?>
            </div>
            <div class="mp-profile-social-action mp-vertical-line pull-left"></div>
            <div class="mp-profile-social-action mp-profile-social-action-save pull-left">
                <?php
                $favrt = listingpro_is_favourite_v2($post->ID);
                ?>
                <div id="fav-container">
                    <a href="" class="<?php
                    if ($favrt == 'yes') {
                        echo 'remove-fav-v2';
                    } else {
                        echo 'add-to-fav-v2';
                    }
                    ?>" data-post-id="<?php echo $post->ID; ?>" data-post-type="detail">

                        <i class="fa <?php
                        if ($favrt == 'yes') {
                            echo 'fa-bookmark';
                        } else {
                            echo 'fa-bookmark-o';
                        }
                        ?>" aria-hidden="true"></i>

                        <?php
                        if ($favrt == 'yes') {
                            echo esc_html__('Saved', 'medicalpro');
                        } else {
                            echo esc_html__('Save', 'medicalpro');
                        }
                        ?>
                    </a>
                </div>
            </div>
                <div class="mp-profile-social-action mp-profile-social-action-feedback pull-right">
	                <?php if(isset($listingpro_options['lp_review_switch']) && $listingpro_options['lp_review_switch'] == "1"){ ?>
                    <a class="mp-event-scroll" href="#mp-experiences-tab">
                        <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/social/thumb.svg'; ?>" alt="Feedback Icon">
                        <span><?php esc_html_e('Share Your Experience', 'medicalpro'); ?></span>
                    </a>
	                <?php } ?>
                    <?php
                    if ($showHTML == true){
                        mp_get_template_part('templates/single-list/listing-details-style1/claim');
                    }
                    ?>
                </div>
            <div class="mp-clearfix"></div>
        </div>
    </div>
</div>