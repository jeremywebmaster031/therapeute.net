<?php
get_header();
$type = 'listing';
$term_id = '';
$taxName = '';
$termID = '';
$term_ID = '';
global $paged, $listingpro_options;

$lporderby = 'date';
$lporders = 'DESC';
if (isset($listingpro_options['lp_archivepage_listingorder'])) {
    $lporders = $listingpro_options['lp_archivepage_listingorder'];
}
$MtKey = '';
if (!empty(lp_theme_option('lp_archivepage_listingorderby'))) {
    $lporderby = lp_theme_option('lp_archivepage_listingorderby');
}
if ($lporderby == "post_views_count" || $lporderby == "listing_reviewed" || $lporderby == "listing_rate" || $lporderby == "claimed") {
    $MtKey = $lporderby;
    $lporderby = 'meta_value_num';
}

$defSquery = '';

if ($lporderby == "rand") {
    $lporders = '';
}

$includeChildren = true;
if (lp_theme_option('lp_children_in_tax')) {
    if (lp_theme_option('lp_children_in_tax') == "no") {
        $includeChildren = false;
    }
}

$taxTaxDisplay = true;
$TxQuery = '';
$tagQuery = '';
$catQuery = '';
$locQuery = '';
$taxQuery = '';
$searchQuery = '';
$sKeyword = '';
$tagKeyword = '';
$priceQuery = '';
$postsonpage = '';
if (isset($listingpro_options['listing_per_page'])) {
    $postsonpage = $listingpro_options['listing_per_page'];
} else {
    $postsonpage = 10;
}


if (!empty($_GET['s']) && isset($_GET['s']) && $_GET['s'] == "home") {
    if (!empty($_GET['lp_s_tag']) && isset($_GET['lp_s_tag'])) {
        $lpsTag = sanitize_text_field($_GET['lp_s_tag']);
        $tagQuery = array(
            'taxonomy' => 'list-tags',
            'field' => 'id',
            'terms' => $lpsTag,
            'operator' => 'IN' //Or 'AND' or 'NOT IN'
        );
    }

    if (!empty($_GET['lp_s_cat']) && isset($_GET['lp_s_cat'])) {
        $lpsCat = sanitize_text_field($_GET['lp_s_cat']);
        $catQuery = array(
            'taxonomy' => 'listing-category',
            'field' => 'id',
            'terms' => $lpsCat,
            'operator' => 'IN', //Or 'AND' or 'NOT IN'
        );
        if ($includeChildren == false) {
            $catQuery['include_children'] = $includeChildren;
        }
        $taxName = 'listing-category';
    }

    if (!empty($_GET['lp_s_loc']) && isset($_GET['lp_s_loc'])) {
        $lpsLoc = sanitize_text_field($_GET['lp_s_loc']);
        if (is_numeric($lpsLoc)) {
            $lpsLoc = $lpsLoc;
        } else {
            $term = listingpro_term_exist($lpsLoc, 'location');
            if (!empty($term)) {
                $lpsLoc = $term['term_id'];
            } else {
                $lpsLoc = '';
            }
        }
        $locQuery = array(
            'taxonomy' => 'location',
            'field' => 'id',
            'terms' => $lpsLoc,
            'operator' => 'IN', //Or 'AND' or 'NOT IN'
        );
        if ($includeChildren == false) {
            $locQuery['include_children'] = $includeChildren;
        }
    }
    /* Search default result priority- Keyword then title */
    if (empty($_GET['lp_s_tag']) && empty($_GET['lp_s_cat']) && !empty($_GET['select'])) {

        $sKeyword = sanitize_text_field($_GET['select']);
        $defSquery = $sKeyword;
        $termExist = term_exists($sKeyword, 'list-tags');

        if ($termExist !== 0 && $termExist !== null) {
            $tagQuery = array(
                'taxonomy' => 'list-tags',
                'field' => 'name',
                'terms' => $sKeyword,
                'operator' => 'IN' //Or 'AND' or 'NOT IN'
            );
            $sKeyword = '';
            $tagKeyword = sanitize_text_field($_GET['select']);
            $defSquery = $tagKeyword;
        }
    }

    $TxQuery = array(
        'relation' => 'AND',
        $tagQuery,
        $catQuery,
        $locQuery,
    );

    $ad_campaignsIDS = listingpro_get_campaigns_listing('lp_top_in_search_page_ads', TRUE, $taxQuery, $TxQuery, $priceQuery, $sKeyword, 2, null);
} else {
    $queried_object = get_queried_object();
    $term_id = $queried_object->term_id;
    $taxName = $queried_object->taxonomy;
    if (!empty($term_id)) {
        $termID = get_term_by('id', $term_id, $taxName);
        $termName = $termID->name;
        $term_ID = $termID->term_id;
    }

    $TxQuery = array(
        array(
            'taxonomy' => $taxName,
            'field' => 'id',
            'terms' => $termID->term_id,
            'operator' => 'IN' //Or 'AND' or 'NOT IN'
        ),
    );

    if ($includeChildren == false) {
        $TxQuery[0]['include_children'] = $includeChildren;
    } else {
        $TxQuery[0]['include_children'] = true;
    }

    $ad_campaignsIDS = listingpro_get_campaigns_listing('lp_top_in_search_page_ads', TRUE, $TxQuery, $searchQuery, $priceQuery, $sKeyword, 2, null);
}

$args = array(
    'post_type' => $type,
    'post_status' => 'publish',
    'posts_per_page' => $postsonpage,
    's' => $sKeyword,
    'paged' => $paged,
    'post__not_in' => $ad_campaignsIDS,
    'tax_query' => $TxQuery,
    'meta_key' => $MtKey,
    'orderby' => $lporderby,
    'order' => $lporders,
);

$my_query = null;
$my_query = new WP_Query($args);
$found = $my_query->found_posts;

if (($found > 1)) {
    $foundtext = esc_html__('Results', 'medicalpro');
} else {
    $foundtext = esc_html__('Result', 'medicalpro');
}

$listing_layout = $listingpro_options['listing_views'];
$addClassListing = '';
if ($listing_layout == 'list_view' || $listing_layout == 'list_view3') {
    $addClassListing = 'listing_list_view';
}
$addClasscompact = '';
if ($listing_layout == 'lp-list-view-compact') {
    $addClasscompact = 'lp-compact-view-outer clearfix';
}



$listing_style = $listingpro_options['listing_style'];
$listing_style_class = '';
if ($listing_style == 1) {
    $listing_style_class = 'listing-simple';
}
if ($listing_style == 2) {
    $listing_style_class = 'listing-with-sidebar';
}
if ($listing_style == 3) {
    $listing_style_class = 'listing-with-map';
}


$taxTaxDisplay = true;

if (!isset($_GET['s'])) {

    $queried_object = get_queried_object();

    $term_id = $queried_object->term_id;

    $taxName = $queried_object->taxonomy;

    if (!empty($term_id)) {

        $termID = get_term_by('id', $term_id, $taxName);

        $termName = $termID->name;

        $parent = $termID->parent;

        $term_ID = $termID->term_id;

        if (is_tax('location')) {

            $loc_ID = $termID->term_id;
        } elseif (is_tax('features')) {

            $feature_ID = $termID->term_id;
        } elseif (is_tax('list-tags')) {
            $lpstag = $termID->name;
        }
    }
} elseif (isset($_GET['lp_s_cat']) || isset($_GET['lp_s_tag']) || isset($_GET['lp_s_loc'])) {

    if (isset($_GET['lp_s_cat']) && !empty($_GET['lp_s_cat'])) {
        $sterm = wp_kses_post($_GET['lp_s_cat']);

        $term_ID = wp_kses_post($_GET['lp_s_cat']);

        $termo = get_term_by('id', $sterm, 'listing-category');

        $termName = esc_html__('Results For', 'medicalpro') . ' ' . $termo->name;

        $parent = $termo->parent;
    }

    if (isset($_GET['lp_s_cat']) && empty($_GET['lp_s_cat']) && isset($_GET['lp_s_tag']) && !empty($_GET['lp_s_tag'])) {

        $sterm = wp_kses_post($_GET['lp_s_tag']);

        $lpstag = $sterm;

        $termo = get_term_by('id', $sterm, 'list-tags');

        $termName = esc_html__('Results For', 'medicalpro') . ' ' . $termo->name;
    }

    if (isset($_GET['lp_s_cat']) && !empty($_GET['lp_s_cat']) && isset($_GET['lp_s_tag']) && !empty($_GET['lp_s_tag'])) {

        $sterm = wp_kses_post($_GET['lp_s_tag']);

        $lpstag = $sterm;
        $termo = get_term_by('id', $sterm, 'list-tags');

        $termName = esc_html__('Results For', 'medicalpro') . ' ' . $termo->name;
    }

    if (isset($_GET['lp_s_loc']) && !empty($_GET['lp_s_loc'])) {

        $sloc = wp_kses_post($_GET['lp_s_loc']);

        $loc_ID = wp_kses_post($_GET['lp_s_loc']);

        if (is_numeric($sloc)) {

            $sloc = $sloc;

            $termo = get_term_by('id', $sloc, 'location');

            if (!empty($termo)) {

                $locName = esc_html__('In ', 'medicalpro') . $termo->name;
            }
        } else {

            $checkTerm = listingpro_term_exist($sloc, 'location');

            if (!empty($checkTerm)) {

                $locTerm = get_term_by('name', $sloc, 'location');

                if (!empty($locTerm)) {

                    $loc_ID = $locTerm->term_id;

                    $locName = esc_html__('In ', 'medicalpro') . ' ' . $locTerm->name;
                }
            } else {
                $locName = esc_html__('In ', 'medicalpro') . ' ' . $sloc;
            }
        }
    }
}
// Harry Code


$listing_layout = $listingpro_options['listing_views'];
$addClassListing = '';
if ($listing_layout == 'list_view' || $listing_layout == 'list_view3') {
    $addClassListing = 'listing_list_view';
}

$cat_name = '';
$loc_name = '';
if (isset($_GET['lp_s_cat']) && !empty($_GET['lp_s_cat'])) {
    $term_obj = get_term_by('id', $_GET['lp_s_cat'], 'listing-category');
    $cat_name = $term_obj->name;
}
if (isset($_GET['lp_s_loc']) && !empty($_GET['lp_s_loc'])) {
    $term_obj = get_term_by('id', $_GET['lp_s_loc'], 'location');
    $loc_name = ', ' . $term_obj->name;
}

$current_search_text = esc_html__('Recent Listings', 'medicalpro');
if (!empty($cat_name) || !empty($loc_name)) {
    $current_search_text = $cat_name . '' . $loc_name;
}
$showing_results = '';
if ($found != 0) {
    if ($found > $postsonpage) {
        $showing_results = esc_html__('Showing', 'medicalpro') . ' 1-' . $postsonpage . ' ' . esc_html__('of', 'medicalpro') . ' ' . $found;
    } else {
        $showing_results = esc_html__('Showing', 'medicalpro') . ' ' . $found . ' ' . esc_html__('of', 'medicalpro') . ' ' . $found;
    }
}
?>

<!--==================================Section Open=================================-->

<section class="lp-sidebar-filters-style sidebar-filters page-container clearfix section-fixed listing-with-map pos-relative taxonomy lp-grid-width1 " id="<?php echo esc_attr($taxName); ?>">

    <?php
    $v2_map_class = '';
    if ($listing_layout == 'list_view_v2' || $listing_layout == 'grid_view_v2'):
        $header_style_v2 = '';
        $v2_map_class = 'v2-map-load';
        $layout_class = '';
        $listing_style = $listingpro_options['listing_style'];
        if ($listing_style == 4) {
            $header_style_v2 = 'header-style-v2';
        }
        if ($listing_layout == 'list_view_v2') {
            $layout_class = 'list';
        }
        if ($listing_layout == 'grid_view_v2') {
            $layout_class = 'grid';
        }
        ?>
        <div data-layout-class="<?php echo $layout_class; ?>" id="list-grid-view-v2" class=" <?php echo $header_style_v2; ?> <?php echo $v2_map_class; ?> <?php echo $listing_layout; ?>"></div>
    <?php endif; ?>

    <div class="sidemap-container pull-right sidemap-fixed md-sidemap-container">
        <div class="overlay_on_map_for_filter"></div>
        <div class="map-pop map-container3" id="map-section">
            <div id='map' class="mapSidebar"></div>
        </div>
        <a href="#" class="open-img-view"><i class="fa fa-file-image-o"></i></a>
        <?php
        if (!wp_is_mobile()) {
            ?><div id="map-section-expand">
                <i class="fa fa-expand" aria-hidden="true"></i>
            </div><?php
        }
        ?>
    </div>
    <div class="all-list-map"></div>


    <div class=" pull-left post-with-map-container-right md-post-with-map-container-right">
        <div class="post-with-map-container pull-left">				


            <!-- archive adsense space before filter -->
            <?php
            //show google ads
            apply_filters('listingpro_show_google_ads', 'archive', '');
            ?>

            <div class="sidebar-filters-wrap md-sidebar-filters-wrap">


                <?php include( MP_PLUGIN_PATH . "templates/sidebar-filter.php"); ?>
            </div>
            <div class="mobile-map-space">

                <!-- Popup Open -->

                <div class="md-modal md-effect-3 mobilemap" id="modal-listing">
                    <div class="md-content mapbilemap-content">
                        <div class="map-pop">							
                            <div id='map' class="listingmap"></div>							
                        </div>
                        <a class="md-close mapbilemap-close"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <!-- Popup Close -->
                <div class="md-overlay md-overlayi"></div> <!-- Overlay for Popup -->
            </div>
            <div class="md-overlay md-overlayi"></div> <!-- Overlay for Popup -->
            <div class="content-grids-wraps md-content-grids-wraps">
                <div class="lp-title clearfix mp-detail-header lp-title-new-style">
                    <div class="pull-left">
                        <div class="mp-breadcrumb mp-p-15-fmd">
                            <?php if (function_exists('listingpro_breadcrumbs')) listingpro_breadcrumbs(); ?>
                        </div>
                        <div class="lp-filter-name sidebar-filter-process">
                            <h2><?php echo $showing_results; ?></h2>
                        </div>
                    </div>
                    <?php 
                    $high_rated_switch    = $listingpro_options['enable_high_rated_search_filter'];
                    $most_reviewed_switch = $listingpro_options['enable_most_reviewed_search_filter'];
                    $most_viewed_switch   = $listingpro_options['enable_most_viewed_search_filter'];
                    $opentime_switch      = $listingpro_options['enable_opentime_search_filter'];
                    if( 
                        isset($high_rated_switch) && $high_rated_switch == "1" ||
                        isset($most_reviewed_switch) && $most_reviewed_switch == "1" ||
                        isset($most_viewed_switch) && $most_viewed_switch == "1" ||
                        isset($opentime_switch) && $opentime_switch == "1"
                            ){ ?>
                        <div class="md-selectdiv mp-archive-sort-filters pull-right">
                            <select class="chosen-select2-with-icon mp-sorting-filter-options">
                                <option selected value=""><?php echo esc_html__('Sort By', 'medicalpro'); ?></option>
                                <?php if(isset($most_reviewed_switch) && $most_reviewed_switch == "1"){ ?>
                                    <option data-value="listing_reviewed"><i class="fa fa-comments" aria-hidden="true"></i> <?php echo esc_html__('Most Reviewed', 'medicalpro'); ?></option>
                                <?php } ?>
                                <?php if(isset($high_rated_switch) && $high_rated_switch == "1"){ ?>
                                    <option data-value="listing_rate"><i class="fa fa-star-o" aria-hidden="true"></i> <?php echo esc_html__('Highest Rated', 'medicalpro'); ?></option>
                                <?php } ?>
                                <?php if(isset($most_viewed_switch) && $most_viewed_switch == "1"){ ?>
                                    <option data-value="mostviewed"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo esc_html__('Most Viewed', 'medicalpro'); ?></option>
                                <?php } ?>
                                <?php if(isset($opentime_switch) && $opentime_switch == "1"){ ?>
                                    <option data-value="close"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo esc_html__('Open Now', 'medicalpro'); ?></option>
                                <?php } ?>
    <!--                            <option><i class="fa fa-random" aria-hidden="true"></i> <?php //echo esc_html__('Best Match', 'medicalpro'); ?></option>-->
                            </select>
                        </div>
                        
                <?php } ?>
                    <div class="pull-right margin-right-0 col-md-2 col-sm-2 clearfix">
                        <div class="md-listing-view-layout">
                            <ul>
                                <li><a class="md-grid" href="#"><i class="fa fa-th-large"></i></a></li>
                                <li class="active"><a class="md-list" href="#"><i class="fa fa-list-ul"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="clearfix lp-list-page-grid" id="content-grids" >						
                    <?php
                    if ($listing_layout == 'list_view_v2') {
                        echo '<div class="lp-listings list-style active-view">
                                    <div class="search-filter-response">
                                        <div class="lp-listings-inner-wrap">';
                    }
                    if ($listing_layout == 'grid_view_v2' || $listing_layout == 'grid_view_v3' || $listing_layout == 'grid_view2' || $listing_layout == 'grid_view') {
                        echo '<div class="lp-listings grid-style active-view">
                                    <div class="search-filter-response">
                                        <div class="lp-listings-inner-wrap">';
                    }
                    ?>
                    <?php
                    $array['features'] = '';
                    if (!empty(listingpro_get_campaigns_listing('lp_top_in_search_page_ads', true, $taxQuery, $TxQuery, $priceQuery, $sKeyword, 2, $ad_campaignsIDS)) || !empty(listingpro_get_campaigns_listing('lp_top_in_search_page_ads', true, $TxQuery, $searchQuery, $priceQuery, $sKeyword, 2, $ad_campaignsIDS))) {
                    ?> 
                    <p class="mp-archive-result-type"><i class="fa fa-info-circle" aria-hidden="true"></i><?php echo esc_html__('Sponsored', 'medicalpro') ?></p>
                    <?php } ?>
                    <div class="promoted-listings">
                        <?php
                        if (!empty($_GET['s']) && isset($_GET['s']) && $_GET['s'] == "home") {
                            echo listingpro_get_campaigns_listing('lp_top_in_search_page_ads', false, $taxQuery, $TxQuery, $priceQuery, $sKeyword, 2, $ad_campaignsIDS);
                        } else {
                            echo listingpro_get_campaigns_listing('lp_top_in_search_page_ads', false, $TxQuery, $searchQuery, $priceQuery, $sKeyword, 2, $ad_campaignsIDS);
                        }
                        ?> 

                        <div class="md-overlay"></div>
                    </div>
                    <?php if ($my_query->have_posts()) { ?>
                    <p class="mp-archive-result-type"><?php echo esc_html__('All Results', 'medicalpro') ?></p>
                    <?php } ?>
                    <div class="md-grid-view hide">
                        <?php
                        if ($my_query->have_posts()) {
                            while ($my_query->have_posts()) : $my_query->the_post();
                                include( MP_PLUGIN_PATH . "templates/loop/loop2.php");
                            endwhile;
                            wp_reset_query();
                        }elseif (empty($ad_campaignsIDS)) {
                            ?>						
                            <div class="text-center margin-top-80 margin-bottom-80">
                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/temp/looking.svg'; ?>" class="no-result-avaliable-img">
                                <h2 class="no-result-avaliable-heading"><?php esc_html_e('No Results', 'medicalpro'); ?></h2>
                                <p class="no-result-avaliable-desc"><?php esc_html_e('Sorry! There are no listings matching your search.', 'medicalpro'); ?></p>
                                <p class="no-result-avaliable-desc"><?php esc_html_e('Try changing your search filters or', 'medicalpro'); ?>
                                    <?php
                                    $currentURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                    ?>
                                    <a href="<?php echo esc_url($currentURL); ?>"><?php esc_html_e('Reset Filter', 'medicalpro'); ?></a>
                                </p>
                            </div>									
                            <?php
                        }
                        ?>

                    </div>
                    <div class="md-list-view show">
                        <?php
                        if ($my_query->have_posts()) {
                            while ($my_query->have_posts()) : $my_query->the_post();
                                if (wp_is_mobile()) {
                                    //get_template_part('mobile/listing-loop-app-view');
                                    include( MP_PLUGIN_PATH . "templates/loop/loop2.php");
                                }else {
                                    include( MP_PLUGIN_PATH . "templates/loop/loop2-list.php");
                                }
                            endwhile;
                            wp_reset_query();
                        }elseif (empty($ad_campaignsIDS)) {
                            ?>						
                            <div class="text-center margin-top-80 margin-bottom-80">
                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/temp/looking.svg'; ?>" class="no-result-avaliable-img">
                                <h2 class="no-result-avaliable-heading"><?php esc_html_e('No Results', 'medicalpro'); ?></h2>
                                <p class="no-result-avaliable-desc"><?php esc_html_e('Sorry! There are no listings matching your search.', 'medicalpro'); ?></p>
                                <p class="no-result-avaliable-desc"><?php esc_html_e('Try changing your search filters or', 'medicalpro'); ?>
                                    <?php
                                    $currentURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                    ?>
                                    <a href="<?php echo esc_url($currentURL); ?>"><?php esc_html_e('Reset Filter', 'medicalpro'); ?></a>
                                </p>
                            </div>									
                            <?php
                        }
                        ?>

                    </div>





                    <div class="md-overlay"></div>
                    <?php
                    if ($listing_layout == 'list_view_v2' || $listing_layout == 'grid_view' || $listing_layout == 'grid_view2' || $listing_layout == 'grid_view_v2' || $listing_layout == 'grid_view_v3') {
                        echo '   <div class="clearfix"></div> <div>
                                <div>
                              <div><div class="clearfix"></div>';
                    }
                    ?>

                </div>
            </div>

            <?php
            echo '<div id="lp-pages-in-cats">';
            echo listingpro_load_more_filter($my_query, '1', $defSquery);
            echo '</div>';
            ?>
            <div class="lp-pagination pagination lp-filter-pagination-ajx"></div>
        </div>
        <input type="hidden" id="lp_current_query" value="<?php echo $defSquery; ?>">
    </div>

</section>
<?php get_footer(); ?>