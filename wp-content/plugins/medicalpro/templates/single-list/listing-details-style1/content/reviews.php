<?php global $post;
$lp_review_switch   =   lp_theme_option('lp_review_switch');
if (!$lp_review_switch) return;
?>
<div id="mp-experiences-tab" class="mp-experiences-tab margin-bottom-60">
    <div class="mp-experiences-heading">
        <h1><?php esc_html_e('Patient Experiences', 'medicalpro'); ?></h1>
    </div>
    <?php medicalpro_get_listing_overall_ratings($post->ID); ?>
</div>    
<div id="mp-review-tab" class="mp-review-tab margin-bottom-60">
    <?php medicalpro_get_all_reviews($post->ID); ?>
</div>