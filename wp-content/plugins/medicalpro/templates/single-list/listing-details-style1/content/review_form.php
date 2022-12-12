<?php 
global $post, $listingpro_options; 

$allowedReviews = $listingpro_options['lp_review_switch'];
if (!empty($allowedReviews) && $allowedReviews == "1" && get_post_status($post->ID) == "publish") { ?>
<div class="mp-add-new-review" id="mp-add-new-review">
    <div class="mp-add-new-review-heading">
        <p><?php esc_html_e('Rate us and Write a Review', 'medicalpro'); ?></p>
    </div>
    <?php medicalpro_get_reviews_form($post->ID); ?>
    <div class="clearfix"></div>
</div>
<?php } ?>