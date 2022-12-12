<?php
global $listingpro_options;
$showClaim = true;


$claimed_section = listing_get_metabox('claimed_section');
//New by abbas
$claimed_option = $listingpro_options['lp_listing_claim_switch'];
if(empty($claimed_section)){
    $showClaim = true;
}
else if($claimed_option == 1 && $claimed_section == 'not_claimed'){
    $showClaim = true;
}
else if($claimed_section == 'claimed'){
   $showClaim = false;
}else{
   $showClaim = false;
}
//End new by abbas

if($listingpro_options['lp_listing_claim_switch']==1){
}else{
    $showClaim = false;
}

if($showClaim==true) {
    
        ?>
        <p class="clearfix mp-claim-this-listing">
            <?php
            if( is_user_logged_in() )
            {
                ?>
                <a href="#" class="md-trigger claimformtrigger3" data-modal="modal-2"><?php echo esc_html__('Claim This Profile !', 'medicalpro'); ?></a>
                <?php
            }
            else
            {
                ?>
                <a class="md-trigger claimformtrigger3" data-modal="modal-2"><?php echo esc_html__('Claim This Profile !', 'medicalpro'); ?></a>
                <?php
            }
            ?>
        </p>
        <?php
    
}
get_template_part('templates/single-list/claim-form' );
?>