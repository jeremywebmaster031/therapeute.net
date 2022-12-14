<?php
global $listingpro_options;
$max_length = 30;
$type = 'listing';
$term_id = '';
$taxName = '';
$termID = '';
$term_ID = '';
$termName = '';
$sterm = '';
$sloc = '';
$termName = '';
$locName = '';
$catterm = '';
$parent = '';
$loc_ID = '';
$feature_ID = '';
$lpstag = '';
$locationID = '';
$catsOPT = '';
$searchfilter = '';
$countExtFilter = '';
global $paged;
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
            $lpstag = $termID->term_id;
        }
    }
} elseif (isset($_GET['lp_s_cat']) || isset($_GET['lp_s_tag']) || isset($_GET['lp_s_loc'])) {

    if (isset($_GET['lp_s_cat']) && !empty($_GET['lp_s_cat'])) {
        $sterm = wp_kses_post($_GET['lp_s_cat']);
        $term_ID = wp_kses_post($_GET['lp_s_cat']);
        $termo = get_term_by('id', $sterm, 'listing-category');
        $termName = esc_html__('Results For', 'medicalpro') . ' <span class="font-bold term-name">' . $termo->name . '</span>';
        $parent = $termo->parent;
    }
    if (isset($_GET['lp_s_cat']) && empty($_GET['lp_s_cat']) && isset($_GET['lp_s_tag']) && !empty($_GET['lp_s_tag'])) {
        $sterm = wp_kses_post($_GET['lp_s_tag']);
        $lpstag = $sterm;
        $termo = get_term_by('id', $sterm, 'list-tags');
        $termName = esc_html__('Results For', 'medicalpro') . ' <span class="font-bold">' . $termo->name . '</span>';
    }

    if (isset($_GET['lp_s_cat']) && !empty($_GET['lp_s_cat']) && isset($_GET['lp_s_tag']) && !empty($_GET['lp_s_tag'])) {
        $sterm = wp_kses_post($_GET['lp_s_tag']);
        $lpstag = $sterm;

        $termo = get_term_by('id', $sterm, 'list-tags');
        $termName = esc_html__('Results For', 'medicalpro') . ' <span class="font-bold">' . $termo->name . '</span>';
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
                    $locName = esc_html__('In ', 'medicalpro') . '<span class="font-bold">' . $locTerm->name . '</span>';
                }
            } else {
                $locName = esc_html__('In ', 'medicalpro') . '<span class="font-bold">' . $sloc . '</span>';
            }
        }
    }
}

$emptySearchTitle = '';
if (empty($_GET['lp_s_tag']) && isset($_GET['lp_s_tag']) && empty($_GET['lp_s_cat']) && isset($_GET['lp_s_cat']) && empty($_GET['lp_s_loc']) && isset($_GET['lp_s_loc'])) {
    $emptySearchTitle = esc_html__('Most recent ', 'medicalpro');
}


$listing_style = '1';
$listingView = 'grid_view';
$GridClass = '';
$ListClass = '';
$listing_style = $listingpro_options['listing_style'];
if (isset($_GET['list-style']) && !empty($_GET['list-style'])) {
    $listing_style = $_GET['list-style'];
}
$listingView = $listingpro_options['listing_views'];
if ($listingView == 'grid_view') {
    $GridClass = 'active';
} elseif ($listingView == 'list_view') {
    $ListClass = 'active';
}
?>
<div class="row md-listing-filter listing-style-<?php echo esc_attr($listing_style); ?>">
    <div class="col-md-12 search-row">
        <div class="lp-filter-top-section clearfix">
            <ul class="clearfix">
                <li><h3><?php echo esc_html__('Filter', 'medicalpro'); ?></h3></li>
                <?php
                $currentURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                ?>
                <li class="pull-right md-rest-filter"><a href="<?php echo esc_url($currentURL); ?>"><i class="fa fa-refresh" aria-hidden="true"></i> <?php esc_html_e('Reset Filter', 'medicalpro') ?></a></li>

            </ul>

        </div>

        <form autocomplete="off" class="clearfix" method="post" enctype="multipart/form-data" id="searchform" style="margin-right: 15px;">
            <?php
            $searchfilter = $listingpro_options['enable_search_filter'];
            if (!empty($searchfilter) && $searchfilter == '1') {
                ?>
                <div class="form-inline lp-filter-inner" id="pop">

                    <?php
                    $catsOPT = $listingpro_options['enable_cats_search_filter'];
                    if (!empty($catsOPT) && $catsOPT == '1') {
                        ?>
                        <div class="filters-section-wrap">
                            <label class="filters-label"><?php echo esc_html__('Specialty', 'medicalpro'); ?></label>
                            <div class="form-group pull-right margin-right-0 lp-search-cats-filter-dropdown">

                                <div class="input-group border-dropdown">
                                    <div class="input-group-addon lp-border"><i class="fa fa-list"></i></div>
                                    <select class="comboboxCategory chosen-select2 tag-select-four" name="searchcategory" id="searchcategory">
                                        <option value=""><?php echo esc_html__('All Specialty', 'medicalpro'); ?></option>
                                        <?php
                                        $args = array(
                                            'orderby' => 'name',
                                            'order' => 'ASC',
                                            'hide_empty' => false,
                                            'parent' => 0,
                                        );

                                        $locations = get_terms('listing-category', $args);
                                        foreach ($locations as $location) {
                                            if ($term_ID == $location->term_id) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            }
                                            echo '<option ' . $selected . ' value="' . $location->term_id . '">' . $location->name . '</option>';

                                            /* level 2 */
                                            $argscatChild = array(
                                                'order' => 'ASC',
                                                'hide_empty' => false,
                                                'hierarchical' => false,
                                                'parent' => $location->term_id,
                                            );

                                            $childCats = get_terms('listing-category', $argscatChild);
                                            if (!empty($childCats)) {
                                                foreach ($childCats as $subID) {
                                                    if ($term_ID == $subID->term_id) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = '';
                                                    }
                                                    echo '<option ' . $selected . ' class="sub_cat" value="' . $subID->term_id . '">-&nbsp;&nbsp;' . $subID->name . '</option>';
                                                }

                                                /* level 3 */
                                                $argscatChild2 = array(
                                                    'order' => 'ASC',
                                                    'hide_empty' => false,
                                                    'hierarchical' => false,
                                                    'parent' => $subID->term_id,
                                                );

                                                $childCats2 = get_terms('listing-category', $argscatChild2);
                                                if (!empty($childCats2)) {
                                                    foreach ($childCats2 as $subID2) {
                                                        if ($term_ID == $subID2->term_id) {
                                                            $selected = 'selected';
                                                        } else {
                                                            $selected = '';
                                                        }
                                                        echo '<option ' . $selected . ' class="sub_cat" value="' . $subID2->term_id . '">--&nbsp;&nbsp;' . $subID2->name . '</option>';
                                                    }



                                                    /* level 4 */
                                                    $argscatChild3 = array(
                                                        'order' => 'ASC',
                                                        'hide_empty' => false,
                                                        'hierarchical' => false,
                                                        'parent' => $subID2->term_id,
                                                    );

                                                    $childCats3 = get_terms('listing-category', $argscatChild3);
                                                    if (!empty($childCats3)) {
                                                        foreach ($childCats3 as $subID3) {
                                                            if ($term_ID == $subID3->term_id) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            echo '<option ' . $selected . ' class="sub_cat" value="' . $subID3->term_id . '">---&nbsp;&nbsp;' . $subID3->name . '</option>';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    <?php } ?>

                    <?php
                    $dateOPT = $listingpro_options['enable_date_filter'];
                    if (!empty($dateOPT) && $dateOPT == '1') {
                        ?>
                        <div class="filters-section-wrap mp_date_filter">
                            <label class="filters-label"><?php echo esc_html__('By Date', 'medicalpro'); ?></label>
                            <div class="form-group padding-right-0">
                                <input type="date" name="mp_date_filter" id="mp_date_filter" min="<?php echo date('Y-m-d', strtotime('+1day')) ?>" max="<?php echo date('Y-m-d', strtotime('+365day')) ?>" placeholder="<?php echo esc_html__('Select Date', 'medicalpro'); ?>">
                                <a id="mp_date_filter_reset"><?php esc_html_e('Reset Date', 'medicalpro') ?></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    <?php } ?>

                    <?php
                    $priceOPT = $listingpro_options['enable_price_search_filter'];
                    if (!empty($priceOPT) && $priceOPT == '1') {
                        $lp_priceSymbol = $listingpro_options['listing_pricerange_symbol'];
                        $lp_priceSymbol2 = $lp_priceSymbol . $lp_priceSymbol;
                        $lp_priceSymbol3 = $lp_priceSymbol2 . $lp_priceSymbol;
                        $lp_priceSymbol4 = $lp_priceSymbol3 . $lp_priceSymbol;
                        ?>
                        <div class="filters-section-wrap currencty-signs-wrap">
                            <label class="filters-label"><?php echo esc_html__('Price Range', 'medicalpro'); ?></label>
                            <div class="form-group padding-right-0">

                                <div class="search-filters">
                                    <div class="currency-signs search-filter-attr">
                                        <ul class="priceRangeFilter">
                                            <li class="simptip-position-top simptip-movable" data-tooltip="<?php echo esc_html__('Inexpensive', 'medicalpro'); ?>" id="one"><a href="#" data-price="inexpensive"><?php echo $lp_priceSymbol; ?></a></li>
                                            <li class="simptip-position-top simptip-movable" data-tooltip="<?php echo esc_html__('Moderate', 'medicalpro'); ?>" id="two"><a href="#" data-price="moderate"><?php echo $lp_priceSymbol2; ?></a></li>
                                            <li class="simptip-position-top simptip-movable" data-tooltip="<?php echo esc_html__('Pricey', 'medicalpro'); ?>" id="three"><a href="#" data-price="pricey"><?php echo $lp_priceSymbol3; ?></a></li>
                                            <li class="simptip-position-top simptip-movable" data-tooltip="<?php echo esc_html__('Ultra High End', 'medicalpro'); ?>" id="four"><a href="#" data-price="ultra_high_end"><?php echo $lp_priceSymbol4; ?></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    <?php } ?>
                    <?php
                    $highRatOPT = $listingpro_options['enable_high_rated_search_filter'];
                    $highRewOPT = $listingpro_options['enable_most_reviewed_search_filter'];
                    $highViewOPT = $listingpro_options['enable_most_viewed_search_filter'];
                    $enable_coupons_search_filter = false;
                    if (isset($listingpro_options['enable_coupons_search_filter'])) {
                        $enable_coupons_search_filter = $listingpro_options['enable_coupons_search_filter'];
                    }
                    $openTimeOPT = $listingpro_options['enable_opentime_search_filter'];
                    $lp_bestmatch_on = $listingpro_options['enable_best_changed_search_filter'];
                    $nearmeOPT = $listingpro_options['enable_nearme_search_filter'];

                    if (
                            (!empty($lp_bestmatch_on) && $lp_bestmatch_on == '1') ||
                            (!empty($openTimeOPT) && $openTimeOPT == '1' ) ||
                            (!empty($enable_coupons_search_filter) && $enable_coupons_search_filter == '1' ) ||
                            (!empty($highRatOPT) && $highRatOPT == '1') ||
                            (!empty($highRewOPT) && $highRewOPT == '1') ||
                            (!empty($highViewOPT) && $highViewOPT == '1') ||
                            (!empty($nearmeOPT) && $nearmeOPT == '1' )
                    ) {
                        ?>
                        <div class="filters-section-wrap sort-by-section-wrap">
                            <label class="filters-label"><?php echo esc_html__('Sort By', 'medicalpro'); ?></label>
                            <div class="search-filters form-group padding-right-0">
                                <ul class="comboboxCategory clearfix" id="select-lp-more-filter">
                                    <?php if ($highRewOPT == "1") { ?>
                                        <li id="listingReviewed" class="lp-tooltip-outer sortbyfilter"><a href="" data-value="listing_reviewed"><i class="fa fa-comments" aria-hidden="true"></i> <?php echo esc_html__('Most Reviewed', 'medicalpro'); ?></a>
                                            <div class="lp-tooltip-div">
                                                <div class="lp-tooltip-arrow"></div>
                                                <div class="lp-tool-tip-content clearfix">
                                                    <p class="margin-0">
                                                        <?php echo esc_html__('Click To See Your Most Reviewed', 'medicalpro'); ?>
                                                    </p>

                                                </div>

                                            </div>

                                        </li>
                                    <?php } ?>
                                    <?php if ($highViewOPT == "1") { ?>
                                        <li id="mostviewed" class="lp-tooltip-outer sortbyfilter"><a href="" data-value="mostviewed"><i class="fa fa-eye" aria-hidden="true"></i> <?php echo esc_html__('Most Viewed', 'medicalpro'); ?></a>
                                            <div class="lp-tooltip-div">
                                                <div class="lp-tooltip-arrow"></div>
                                                <div class="lp-tool-tip-content clearfix">
                                                    <p class="margin-0">
                                                        <?php echo esc_html__('Click To See Your Most Viewed', 'medicalpro'); ?>
                                                    </p>

                                                </div>

                                            </div>

                                        </li>
                                    <?php } ?>
                                    <?php if ($highRatOPT == "1") { ?>
                                        <li id="listingRate" class="lp-tooltip-outer sortbyfilter"><a href="#" data-value="listing_rate"><i class="fa fa-star-o" aria-hidden="true"></i> <?php echo esc_html__('Highest Rated', 'medicalpro'); ?></a>
                                            <div class="lp-tooltip-div">
                                                <div class="lp-tooltip-arrow"></div>
                                                <div class="lp-tool-tip-content clearfix">
                                                    <p class="margin-0">
                                                        <?php echo esc_html__('Click To See Your Highest Rated', 'medicalpro'); ?>
                                                    </p>

                                                </div>

                                            </div>

                                        </li>
                                    <?php } ?>
                                    <?php if ((!empty($lp_bestmatch_on) && $lp_bestmatch_on == '1')) { ?>
                                        <li data-best ="bestmatch"  class="lp-tooltip-outer lp-search-best-matches">
                                            <a class="btn default"><i class="fa fa-random" aria-hidden="true"></i> <?php echo esc_html__('Best Match', 'medicalpro'); ?></a>
                                            <div class="lp-tooltip-div">
                                                <div class="lp-tooltip-arrow"></div>
                                                <div class="lp-tool-tip-content clearfix">
                                                    <p class="margin-0">
                                                        <?php echo esc_html__('Click To See Your Best Match', 'medicalpro'); ?>
                                                    </p>

                                                </div>

                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (!empty($openTimeOPT) && $openTimeOPT == '1') { ?>
                                        <li class="lp-tooltip-outer listing_openTime">
                                            <a data-time="close"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo esc_html__('Open Now', 'medicalpro'); ?></a>
                                            <div class="lp-tooltip-div">
                                                <div class="lp-tooltip-arrow"></div>
                                                <div class="lp-tool-tip-content clearfix lp-tooltip-outer-responsive">
                                                    <p class="margin-0">
                                                        <?php echo esc_html__('Click To See What Open Now', 'medicalpro'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php if (!empty($enable_coupons_search_filter) && $enable_coupons_search_filter == '1') { ?>
                                        <li class="lp-tooltip-outer listing_coupons">
                                            <a data-value=""><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo esc_html__('Coupons', 'medicalpro'); ?></a>
                                            <div class="lp-tooltip-div">
                                                <div class="lp-tooltip-arrow"></div>
                                                <div class="lp-tool-tip-content clearfix lp-tooltip-outer-responsive">
                                                    <p class="margin-0">
                                                        <?php echo esc_html__('Click To See Listing With Coupons', 'medicalpro'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php if(!empty($nearmeOPT) && $nearmeOPT == '1' ){ ?>
                            <div class="filters-section-wrap location-section-wrap">
                                <?php if (is_ssl() || in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {

                                    $units = $listingpro_options['lp_nearme_filter_param'];
                                    if (empty($units)) {
                                        $units = 'km';
                                    }
                                    ?>
                                    <div data-nearmeunit="<?php echo esc_attr($units); ?>" id="lp-find-near-me" class="search-filters form-group padding-right-0">
                                        <ul>
                                            <li class="lp-tooltip-outer">
                                                <a  class="btn default near-me-btn"><i class="fa fa-map-marker" aria-hidden="true"></i>   <?php echo esc_html__('Near Me', 'medicalpro'); ?></a>
                                                <div class="lp-tooltip-div">

                                                    <div class="lp-tool-tip-content clearfix lp-tooltip-outer-responsive">
                                                        <p class="margin-0">
                                                            <?php echo esc_html__('Click To GET', 'medicalpro'); ?>
                                                        </p>

                                                    </div>

                                                </div>
                                                <div class="lp-tooltip-div-hidden">
                                                    <div class="lp-tooltip-arrow"></div>
                                                    <div class="lp-tool-tip-content clearfix lp-tooltip-outer-responsive">
                                                        <?php
                                                        $minRange = $listingpro_options['enable_readious_search_filter_min'];
                                                        $maxRange = $listingpro_options['enable_readious_search_filter_max'];
                                                        $defVal = 100;
                                                        if (isset($listingpro_options['enable_readious_search_filter_default'])) {
                                                            $defVal = $listingpro_options['enable_readious_search_filter_default'];
                                                        }
                                                        ?>
                                                        <div class="location-filters location-filters-wrapper">

                                                            <div id="pac-container" class="clearfix">
                                                                <div class="clearfix row">
                                                                    <div class="lp-price-range-btnn col-md-1 text-right padding-0">
                                                                        <?php echo $minRange; ?>
                                                                    </div>
                                                                    <div class="col-md-9" id="distance_range_div">
                                                                        <input id="distance_range" name="distance_range" type="text" data-slider-min="<?php echo $minRange; ?>" data-slider-max="<?php echo $maxRange; ?>" data-slider-step="1" data-slider-value="<?php echo $defVal ?>"/>
                                                                    </div>
                                                                    <div class="col-md-2 padding-0 text-left lp-price-range-btnn">
                                                                        <?php echo $maxRange; ?>
                                                                    </div>
                                                                    <div style="display:none" class="col-md-4" id="distance_range_div_btn">
                                                                        <a href=""><?php echo esc_html__('New Location', 'medicalpro'); ?></a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 padding-top-10" style="display:none" >
                                                                    <input id="pac-input" name="pac-input" type="text" placeholder="<?php echo esc_html__('Enter a location', 'medicalpro'); ?>" data-lat="" data-lng="" data-center-lat="" data-center-lng="" data-ne-lat="" data-ne-lng="" data-sw-lat="" data-sw-lng="" data-zoom="">
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                                <?php } ?>
                                <?php
                                //$radiusOPT = $listingpro_options['enable_readious_search_filter'];
                                $radiusOPT = false;
                                if (!empty($radiusOPT) && $radiusOPT == '1') {
                                    if (is_ssl() || in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
                                        ?>
                                        <div class="search-filters form-group padding-right-0 lp-radus-filter-wrap">
                                            <ul>
                                                <li id="lp-filter-radius-wraper" class="lp-tooltip-outer">
                                                    <a class="btn default lp-distancesearchbtn"><?php echo esc_html__('Distance', 'medicalpro'); ?></a>
                                                    <div class="lp-tooltip-div">

                                                        <div class="lp-tool-tip-content clearfix">
                                                            <?php
                                                            $minRange = $listingpro_options['enable_readious_search_filter_min'];
                                                            $maxRange = $listingpro_options['enable_readious_search_filter_max'];
                                                            ?>
                                                            <div class="location-filters location-filters-wrapper">

                                                                <div id="pac-container" class="clearfix">
                                                                    <div>
                                                                        <div id="distance_range_div">
                                                                            <input id="distance_range" name="distance_range" type="text" data-slider-min="<?php echo $minRange; ?>" data-slider-max="<?php echo $maxRange; ?>" data-slider-step="1" data-slider-value="100"/>

                                                                        </div>
                                                                        <div style="display:none" class="col-md-4" id="distance_range_div_btn">
                                                                            <a href=""><?php echo esc_html__('New Location', 'medicalpro'); ?></a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 padding-top-10" style="display:none" >

                                                                        <input id="pac-input" name="pac-input" type="text" placeholder="<?php echo esc_html__('Enter a location', 'medicalpro'); ?>" data-lat="" data-lng="" data-center-lat="" data-center-lng="" data-ne-lat="" data-ne-lng="" data-sw-lat="" data-sw-lng="" data-zoom="">
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <div class="clearfix"></div>
                            </div>
                        <?php } ?>
                    <?php } ?>


                    <a id="see_filter"><?php echo esc_html__('See Filters', 'medicalpro'); ?></a>
                    <div class="more-filter lp-filter-inner-wrapper" id="more_filters">

                        <div class="clearfix lp-show-on-mobile"></div>


                        <!-- end shebi-->

                        <?php
                        $showAdditionalFilter = lp_theme_option('enable_extrafields_filter');
                        if (!empty($showAdditionalFilter)) {
                            $countExtFilter = lp_get_extrafields_filter(false, $term_id, true);
                            ?>
                            <div class="search-filters form-group padding-right-0">
                                <div class="lp_add_more_filter">
                                    <a class="btn default"><i class="fa fa-plus"></i>
                                        <div class="lp_more_filter_tooltip_outer">
                                            <span id="lp_more_filter_tooltip_arrow"></span>
                                            <span id="lp_more_filter_tooltip"><?php echo esc_html__('More Filters', 'medicalpro'); ?></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>			
                    <a href="#" class="open-map-view"><i class="fa fa-map-o"></i></a>

                </div>
            <?php } else { ?>

                <select style = "display:none" class="comboboxCategory tag-select-four" name="searchcategory" id="searchcategory">
                    <option value=""><?php echo esc_html__('All Categories', 'medicalpro'); ?></option>
                    <?php
                    $args = array(
                        'post_type' => 'listing',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                    );

                    $locations = get_terms('listing-category', $args);
                    foreach ($locations as $location) {
                        if ($term_ID == $location->term_id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        echo '<option ' . $selected . ' value="' . $location->term_id . '">' . $location->name . '</option>';
                        /* level 2 */
                        $argscatChild = array(
                            'order' => 'ASC',
                            'hide_empty' => false,
                            'hierarchical' => false,
                            'parent' => $location->term_id,
                        );

                        $childCats = get_terms('listing-category', $argscatChild);
                        if (!empty($childCats)) {
                            foreach ($childCats as $subID) {
                                if ($term_ID == $subID->term_id) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                echo '<option ' . $selected . ' class="sub_cat" value="' . $subID->term_id . '">-&nbsp;&nbsp;' . $subID->name . '</option>';
                            }

                            /* level 3 */
                            $argscatChild2 = array(
                                'order' => 'ASC',
                                'hide_empty' => false,
                                'hierarchical' => false,
                                'parent' => $subID->term_id,
                            );

                            $childCats2 = get_terms('listing-category', $argscatChild2);
                            if (!empty($childCats2)) {
                                foreach ($childCats2 as $subID2) {
                                    if ($term_ID == $subID2->term_id) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    echo '<option ' . $selected . ' class="sub_cat" value="' . $subID2->term_id . '">--&nbsp;&nbsp;' . $subID2->name . '</option>';
                                }



                                /* level 4 */
                                $argscatChild3 = array(
                                    'order' => 'ASC',
                                    'hide_empty' => false,
                                    'hierarchical' => false,
                                    'parent' => $subID2->term_id,
                                );

                                $childCats3 = get_terms('listing-category', $argscatChild3);
                                if (!empty($childCats3)) {
                                    foreach ($childCats3 as $subID3) {
                                        if ($term_ID == $subID3->term_id) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        echo '<option ' . $selected . ' class="sub_cat" value="' . $subID3->term_id . '">---&nbsp;&nbsp;' . $subID3->name . '</option>';
                                    }
                                }
                            }
                        }
                    }
                    ?>	
                </select>


            <?php } ?>

            <input type="hidden" name="lp_search_loc" id="lp_search_loc" value="<?php echo $loc_ID; ?>" />
            <?php if ($taxTaxDisplay == true) { ?>

                <?php
                $count = 1;
                $featureName;
                $hasfeature = false;
                $showdivwrap = true;
                $sortedFeatuers = array();
                $features = listingpro_get_term_meta($term_ID, 'lp_category_tags');
                if (empty($features)) {
                    $features = listingpro_get_term_meta($parent, 'lp_category_tags');
                }

                /* for sorting  creating array */
                if (!empty($features)) {
                    foreach ($features as $feature) {
                        $terms = get_term_by('id', $feature, 'features');
                        if (!empty($terms)) {
                            $sortedFeatuers[$feature] = $terms->name;
                        }
                    }
                }

                if (!empty($sortedFeatuers)) {
                    ?>

                    <?php
                    sort($sortedFeatuers);
                    foreach ($sortedFeatuers as $featureName) {
                        $terms = get_term_by('name', $featureName, 'features');
                        if (!empty($terms)) {

                            $featurCount = lp_count_postcount_taxonomy_term_byID('listing', 'features', $terms->term_id);

                            if (!empty($featurCount)) {
                                $hasfeature = true;
                            }
                            if ($hasfeature == true && $showdivwrap == true) {
                                ?>
                                <div class="form-inline lp-features-filter tags-area">
                                    <div class="form-group lp_extrafields_select-border2 border-margin-padding-top-0  padding-0">
                                        <div class="input-group margin-right-0 lp-more-filters-outer padding-left-10">
                                            
                                            <div class="clearfix"></div>
                                            <h3 style="margin-left: -5px !important;" class="pull-left display-inline-block margin-0"><?php echo esc_html__("Doctor By Feature's", 'medicalpro'); ?></h3>
                                            <i class="fa fa-angle-down mp-expand-all-filters mp-expand-all-filters-trigger pull-right"></i>
                                            <div class="clearfix"></div>
                                            
                                            <ul class="lp_filter_checkbox" style="padding-top: 15px; display: none;">
                                                <?php
                                                $showdivwrap = false;
                                            }

                                            if (!empty($featurCount)) {

                                                echo '<li>';
                                                echo '<div class="pad-bottom-10 checkbox ">';
                                                echo '<input type="checkbox" name="searchtags[]' . $count . '" id="check_' . $count . '" class="searchtags" value="' . $terms->term_id . '">';
                                                echo '<label for="' . $terms->term_id . '">' . $terms->name . '</label>';
                                                echo '</div>';
                                                echo '</li>';
                                            }
                                            $count++;
                                        }
                                    }
                                    ?>
                                    <?php
                                    if ($hasfeature == true) {
                                        ?>
                                    </ul>	
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                    ?>
                    <?php
                }
                ?>

            <?php } ?>
            <input type="submit" style="display:none;">
            <input type="hidden" name="clat">
            <input type="hidden" name="clong">

            <?php
            /* in case if category filter is off and you visit a category */
            $catsOPT = $listingpro_options['enable_cats_search_filter'];
            if ($catsOPT == '0') {
                ?>

                <select style = "display:none" class="comboboxCategory tag-select-four" name="searchcategory" id="searchcategory">
                    <option value=""><?php echo esc_html__('All Categories', 'medicalpro'); ?></option>
                    <?php
                    $args = array(
                        'post_type' => 'listing',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'parent' => 0,
                    );

                    $locations = get_terms('listing-category', $args);
                    foreach ($locations as $location) {
                        if ($term_ID == $location->term_id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        echo '<option ' . $selected . ' value="' . $location->term_id . '">' . $location->name . '</option>';

                        /* level 2 */
                        $argscatChild = array(
                            'order' => 'ASC',
                            'hide_empty' => false,
                            'hierarchical' => false,
                            'parent' => $location->term_id,
                        );

                        $childCats = get_terms('listing-category', $argscatChild);
                        if (!empty($childCats)) {
                            foreach ($childCats as $subID) {
                                if ($term_ID == $subID->term_id) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                echo '<option ' . $selected . ' class="sub_cat" value="' . $subID->term_id . '">-&nbsp;&nbsp;' . $subID->name . '</option>';
                            }

                            /* level 3 */
                            $argscatChild2 = array(
                                'order' => 'ASC',
                                'hide_empty' => false,
                                'hierarchical' => false,
                                'parent' => $subID->term_id,
                            );

                            $childCats2 = get_terms('listing-category', $argscatChild2);
                            if (!empty($childCats2)) {
                                foreach ($childCats2 as $subID2) {
                                    if ($term_ID == $subID2->term_id) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    echo '<option ' . $selected . ' class="sub_cat" value="' . $subID2->term_id . '">--&nbsp;&nbsp;' . $subID2->name . '</option>';
                                }



                                /* level 4 */
                                $argscatChild3 = array(
                                    'order' => 'ASC',
                                    'hide_empty' => false,
                                    'hierarchical' => false,
                                    'parent' => $subID2->term_id,
                                );

                                $childCats3 = get_terms('listing-category', $argscatChild3);
                                if (!empty($childCats3)) {
                                    foreach ($childCats3 as $subID3) {
                                        if ($term_ID == $subID3->term_id) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        echo '<option ' . $selected . ' class="sub_cat" value="' . $subID3->term_id . '">---&nbsp;&nbsp;' . $subID3->name . '</option>';
                                    }
                                }
                            }
                        }
                    }
                    ?>	
                </select>
                <?php
            }
            ?>
        </form>

        <div class="lp-s-hidden-ara hide">
            <?php
            if (!empty($lpstag)) {
                echo '<input type="hidden" id="lpstag" value="' . $lpstag . '">';
            }
            if (!isset($_GET['lp_s_cat']) || empty($_GET['lp_s_cat'])) {
                $lp_current_query = '';
                if (isset($_GET['select'])) {
                    $lp_current_query = sanitize_text_field($_GET['select']);
                }
                echo '<input type="hidden" id="lp_current_query" value="' . $lp_current_query . '">';
            }

            if (empty($features) && !empty($feature_ID)) {
                echo '<input type="checkbox" name="searchtags[]" id="check_featuretax" class="searchtags" value="' . $feature_ID . '" checked>';
            }
            ?>
        </div>
        
        
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="LPtagsContainer "></div>
    </div>
</div>

<?php
if (!empty($showAdditionalFilter)) {
    get_template_part('templates/search/more-filter');
}
$args = array(
	'post_type' => 'listing',
	'order' => 'ASC',
	'hide_empty' => false,
);
$hospitals = get_terms('medicalpro-hospital', $args);
$showhospitalfilter = $listingpro_options['enable_hospital_filter'];
$showmpefeaturefilter = $listingpro_options['enable_mpe_feature_filter'];
$mpefeatures = array(
    'virtual_consult' => esc_html__('Video Consultation', 'medicalpro'),
    'certified_doctor' => esc_html__('Certified Doctor', 'medicalpro'),
    'online_prescription' => esc_html__('Online Prescription', 'medicalpro'),
    'taking_new_patient' => esc_html__('Taking New Patients', 'medicalpro')
);
?>
<?php if ( $showmpefeaturefilter == true ) : ?>
<div class="lp_more_filter_data_section lp_extrafields_select lp_extrafields_select-border2 mp-tx-hospital-filter">
    <div class="lp-more-filters-outer">
        <div class="clearfix"></div>
        <h3 class="border-padding-top pull-left display-inline-block"><?php echo esc_html__('By Consultation', 'medicalpro'); ?></h3>
        <i class="fa <?php if (wp_is_mobile()){ echo 'fa-angle-down'; }else{ echo 'fa-angle-up'; } ?> mp-expand-all-filters mp-expand-all-filters-trigger pull-right"></i>
        <div class="clearfix"></div>
        <ul class="lp_filter_checkbox" <?php if (wp_is_mobile()){ echo 'style="display: none;"'; } ?>>
            <?php foreach ( $mpefeatures as $featurek => $featurev ) : 
                $s = $featurev;
                if (strlen($s) > $max_length)
                {
                    $offset = $max_length - strlen($s);
                    $s = substr($s, 0, strrpos($s, ' ', $offset)) . '...';
                }
                ?>
                <li>
                    <label class="filter_checkbox_container" title="<?php echo $featurev; ?>"><?php echo $s; ?>
                        <input type="checkbox" data-key="mp_mpe_feature_filter" value="<?php echo $featurek; ?>">
                        <span class="filter_checkbox_checkmark"></span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>
<?php if ( !empty($hospitals) && $showhospitalfilter == true ) : ?>
<div class="lp_more_filter_data_section lp_extrafields_select lp_extrafields_select-border2 mp-tx-hospital-filter">
    <div class="lp-more-filters-outer">
        <div class="clearfix"></div>
        <h3 class="border-padding-top pull-left display-inline-block"><?php echo esc_html__('By Hospital Name', 'medicalpro'); ?></h3>
        <i class="fa <?php if (wp_is_mobile()){ echo 'fa-angle-down'; }else{ echo 'fa-angle-up'; } ?> mp-expand-all-filters mp-expand-all-filters-trigger pull-right"></i>
        <div class="clearfix"></div>
        <ul class="lp_filter_checkbox" <?php if (wp_is_mobile()){ echo 'style="display: none;"'; } ?>>
            <?php foreach ( $hospitals as $hospital ) :
                $s = $hospital->name;
                if (strlen($s) > $max_length)
                {
                    $offset = $max_length - strlen($s);
                    $s = substr($s, 0, strrpos($s, ' ', $offset)) . '...';
                }
                ?>
                <li>
                    <label class="filter_checkbox_container" title="<?php echo $hospital->name; ?>"><?php echo $s; ?>
                        <input type="checkbox" data-key="mp_hospitals_tax_filter" value="<?php echo $hospital->term_id; ?>">
                        <span class="filter_checkbox_checkmark"></span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>