<?php

$plan_id = get_post_meta( $post->ID, 'Plan_id', true );
if (!empty($plan_id)) {
    $plan_id = $plan_id;
} else {
    $plan_id = 'none';
}
$IDs = get_post_meta( $post->ID, 'gallery_image_ids', true );
$gallery_show = get_post_meta($plan_id, 'gallery_show', true);

if($gallery_show == "true"){
    return;
}

$ximgIDs = array();
$imgIDs = array();
$numImages = 0;
if (!empty($IDs)) {
    $ximgIDs = explode(',',$IDs);
}else {
    return;
}
if (!empty($ximgIDs) && is_array($ximgIDs)) {
    foreach ($ximgIDs as $value) {
        if (!empty(get_post_type($value)) && get_post_type($value) == 'attachment') {
            $imgIDs[] = $value;
        }
    }
    $numImages = count($imgIDs);
}else {
    return;
}
?>


<div id="mp-single-list-gallery-container">
    
    <a href="#" data-target="<?php echo $post->ID; ?>" id="mp-single-list-gallery-trigger"><?php echo (int) $numImages .' '. esc_html__('More Photos', 'medicalpro'); ?></a>
    
    <div id="mp-single-list-gallery"></div>
    
</div>


<?php
return;

    if (!empty($IDs)) {
        if($gallery_show=="true"){

            $imgIDs = array();
            $numImages = 0;
            $ximgIDs = explode(',',$IDs);
            if(!empty($ximgIDs)){
                foreach ($ximgIDs as $value) {
                    if (!empty(get_post_type($value)) && get_post_type($value) == 'attachment') {
                        $imgIDs[] = $value;
                    }
                }

                if(!empty($imgIDs)){
                    $numImages = count($imgIDs);
                }
            }

            if($numImages >= 1 ){ ?>
                <div class="pos-relative">
                    <div class="spinner">
                        <div class="double-bounce1"></div>
                        <div class="double-bounce2"></div>
                    </div>
                    <div class="single-page-slider-container style1">
                        <div class="row">
                            <div class="">
                                <div class="listing-slide img_<?php echo esc_attr($numImages); ?>" data-images-num="<?php echo esc_attr($numImages); ?>">
                                    <?php
                                    //$imgSize = 'listingpro-gal';
                                    require_once (THEME_PATH . "/include/aq_resizer.php");
                                    $imgSize = 'listingpro-detail_gallery';

                                    foreach($imgIDs as $imgID){

                                        if($numImages == 3){
                                            $img_url = wp_get_attachment_image_src( $imgID, 'full');
                                            $imgurl = aq_resize( $img_url[0], '550', '420', true, true, true);
                                            $imgSrc = $imgurl;
                                        }elseif($numImages == 2){
                                            $img_url = wp_get_attachment_image_src( $imgID, 'full');
                                            $imgurl = aq_resize( $img_url[0], '800', '400', true, true, true);
                                            $imgSrc = $imgurl;
                                        }elseif($numImages == 1){
                                            $img_url = wp_get_attachment_image_src( $imgID, 'full');
                                            $imgurl = aq_resize( $img_url[0], '1170', '400', true, true, true);
                                            $imgSrc = $imgurl;
                                        }elseif($numImages == 4){
                                            $img_url = wp_get_attachment_image_src( $imgID, 'full');
                                            $imgurl = aq_resize( $img_url[0], '400', '400', true, true, true);
                                            $imgSrc = $imgurl;
                                        }else {
                                            /* $imgurl = wp_get_attachment_image_src( $imgID, $imgSize);
                                            $imgSrc = $imgurl[0]; */
                                            $img_url = wp_get_attachment_image_src( $imgID, 'full');
                                            $imgurl = aq_resize( $img_url[0], '350', '450', true, true, true);
                                            $imgSrc = $imgurl;
                                        }
                                        $imgFull = wp_get_attachment_image_src( $imgID, 'full');
                                        if(!empty($imgurl[0])){
                                            echo '
															<div class="slide">
																<a href="'. $imgFull[0] .'" rel="prettyPhoto[gallery1]">
																	<img src="'. $imgSrc .'" alt="'.get_the_title().'" />
																</a>
															</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else{
                if(isset($imgIDs[0])){
                    $imgurl = wp_get_attachment_image_src( $imgIDs[0], 'listingpro-listing-gallery');
                    $imgFull = wp_get_attachment_image_src( $imgID, 'full');
                    if(!empty($imgurl[0])){
                        echo '
                        <div class="slide_ban text-center">
                                <a href="'. $imgFull[0] .'" rel="prettyPhoto[gallery1]">
                                        <img src="'. $imgurl[0] .'" alt="'.get_the_title().'" />
                                </a>
                        </div>';
                    }
                }
            }
        }
    }