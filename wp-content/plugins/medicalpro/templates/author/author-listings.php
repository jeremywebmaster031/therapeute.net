<?php
$defSquery = null;
global $listingpro_options;
$author_list_view   =   'grid_view_v2';
if( isset( $listingpro_options['my_listing_views'] ) )
    $author_list_view   =   $listingpro_options['my_listing_views'];
$type = 'listing';
if( isset( $GLOBALS['pageno'] ) && !empty( $GLOBALS['pageno'] ) )
{
    $paged  =   $GLOBALS['pageno'];
}
else
{
    $paged = 1;
}
$postsonpage = '';
if(isset($listingpro_options['my_listing_per_page'])){
    $postsonpage = $listingpro_options['my_listing_per_page'];
}
else{
    $postsonpage = 9;
}
$args=array(
    'post_type' => $type,
    'post_status' => 'publish',
    'posts_per_page' => $postsonpage,
    'order' => 'ASC',
    'paged'       => $paged,
    'author' => $GLOBALS['authorID'],
);
$my_query = null;
$my_query = new WP_Query($args);
?>
<div class="lp-author-listings-wrap">
    <div class="row lp-list-page-grid" id="content-grids" >
        <?php
        if( $my_query->have_posts() ) {
            while ($my_query->have_posts()) : $my_query->the_post();
                mp_get_template_part( 'templates/loop/loop2-list' );
            endwhile;
        }
        ?>
        <div class="md-overlay"></div>
    </div>
    <div class="clearfix"></div>
    <?php
    echo lsitingpro_pagination_author($my_query, $paged, $defSquery);
    ?>
</div>