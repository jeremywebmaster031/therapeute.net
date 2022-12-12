<?php
global $post;

$plan_id = listing_get_metabox_by_ID('Plan_id', $post->ID);
$announcements_show = 'true';
if(empty($plan_id)){
    $plan_id = 'none';
}
if( $plan_id != 'none' ){
    $announcements_show = get_post_meta( $plan_id, 'listingproc_plan_announcment', true );
}
if( $announcements_show == 'false' ) return false;  

$listing_announcements  = get_post_meta( $post->ID, 'lp_listing_announcements', true );
if(isset($listing_announcements) && !empty($listing_announcements)){ ?>
    <?php foreach($listing_announcements as $listing_announcement){
        if( isset($listing_announcement['annLI']) && $listing_announcement['annLI'] == $post->ID && isset($listing_announcement['annStatus']) && $listing_announcement['annStatus'] == 1){ ?>
            <div class="mp-vircon-notice">
                <div class="alert alert-dismissible fade in">
                    <div class="mp-clearfix"></div>
                    <div class="display-inline-block margin-right-10 pull-left">
                        <i class="<?php echo isset($listing_announcement['annIC']) ? $listing_announcement['annIC'] : 'fa fa-bullhorn'; ?>" aria-hidden="true"></i>
                    </div>
                    <div class="display-inline-block">
                        <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong><?php echo $listing_announcement['annTI']; ?></strong>
                        <p><?php echo $listing_announcement['annMsg']; ?></p>
                        <?php
                        if(isset($listing_announcement['annBT']) && !empty( $listing_announcement['annBT']) ){ ?>
                            <a target="_blank" href="<?php echo $listing_announcement['annBL']; ?>" class="announcement-btn"><?php echo $listing_announcement['annBT']; ?></a>
                        <?php } ?>
                    </div>
                    <div class="mp-clearfix"></div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>