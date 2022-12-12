<?php

if (!function_exists('mp_listingpro_ratings_stars')) {
    function mp_listingpro_ratings_stars($metaboxID, $postID)
    {
        $rating = listing_get_metabox_by_ID($metaboxID, $postID);
        if (!empty($rating)) {
            $blankstars = 5;
            while ($rating > 0) {
                if ($rating < 1) {
                    echo '<i class="fa fa-star"></i>';
                    $rating--;
                    $blankstars--;
                } else if ($rating >= 1 && $rating < 2) {
                    echo '<i class="fa fa-star"></i>';
                    $rating--;
                    $blankstars--;
                } else if ($rating >= 2 && $rating < 3.5) {
                    echo '<i class="fa fa-star"></i>';
                    $rating--;
                    $blankstars--;
                } else if ($rating >= 3.5 && $rating <= 5) {
                    echo '<i class="fa fa-star"></i>';
                    $rating--;
                    $blankstars--;
                }
            }
            while ($blankstars > 0) {
                echo '<i class="fa fa-star-o"></i>';
                $blankstars--;
            }
        } else {
            $blankstars = 5;
            while ($blankstars > 0) {
                echo '<i class="fa fa-star-o"></i>';
                $blankstars--;
            }
        }
    }
}


if (!function_exists('medicalpro_get_all_reviews')) {

    function medicalpro_get_all_reviews($postid)
    {

        global $listingpro_options;
        $showReport = true;
        if (isset($listingpro_options['lp_detail_page_review_report_button'])) {
            if ($listingpro_options['lp_detail_page_review_report_button'] == 'off') {
                $showReport = false;
            }
        }
        $lp_multi_rating_state = $listingpro_options['lp_multirating_switch'];

        if ($lp_multi_rating_state == 1 && !empty($lp_multi_rating_state)) {
            $lp_multi_rating_fields_active = array();
            for ($x = 1; $x <= 5; $x++) {
                $lp_multi_rating_fields = get_listing_multi_ratings_fields($postid);
            }
        }

?>

        <?php
        $currentUserId = get_current_user_id();
        $key = 'reviews_ids';
        $review_idss = listing_get_metabox_by_ID($key, $postid);

        $review_ids = '';
        if (!empty($review_idss)) {
            $review_ids = explode(",", $review_idss);
        }

        $active_reviews_ids = array();
        if (!empty($review_ids) && is_array($review_ids)) {
            $review_ids = array_unique($review_ids);
            foreach ($review_ids as $reviewID) {
                if (get_post_status($reviewID) == "publish") {
                    $active_reviews_ids[] = $reviewID;
                }
            }
            if (count($active_reviews_ids) == 1) {
                $label = esc_html__('Review for ', 'medicalpro') . get_the_title($postid);
            } else {
                $label = esc_html__('Reviews for ', 'medicalpro') . get_the_title($postid);
            }
            $colclass = 'col-md-12';
            $reviewFilter = false;
            if (lp_theme_option('lp_listing_reviews_orderby') == 'on') {
                $colclass = 'col-md-8';
                $reviewFilter = true;
            }
        ?>
            <div class="row">
                <div class="mp-review-heading col-md-12">
                    <?php
                    echo '<h2 class="">' . count($active_reviews_ids) . ' ' . $label . '</h2>';
                    ?>
                </div>
                <?php if (!empty($reviewFilter)) { ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sel1"><?php echo esc_html__('Filter By : ', 'medicalpro'); ?></label>
                            <select class="form-control" id="lp_reivew_drop_filter">
                                <option value="DESC"><?php echo esc_html__('Newest', 'medicalpro'); ?></option>
                                <option value="ASC"><?php echo esc_html__('Oldest', 'medicalpro'); ?></option>
                                <option value="listing_rate"><?php echo esc_html__('Highest Rated', 'medicalpro'); ?></option>
                                <option value="listing_rate_lowest"><?php echo esc_html__('Lowest Rated', 'medicalpro'); ?></option>

                            </select>
                        </div>
                        <div class="review-filter-loader">
                            <img src="<?php echo THEME_DIR . '/assets/images/search-load.gif' ?>">
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
            <?php
        } else {
        }

        $reviewOrder = 'DESC';
        if (!empty($review_ids) && count($review_ids) > 0) {
            $review_ids = array_reverse($review_ids, true);
            echo '<div class="md-reviews-section reviews-section clearfix">';
            //foreach( $review_ids as $key=>$review_id ){
            $args = array(
                'post_type' => 'lp-reviews',
                'orderby' => 'date',
                'order' => $reviewOrder,
                'post__in' => $review_ids,
                'post_status' => 'publish',
                'posts_per_page' => -1
            );
            $query = new WP_Query($args);
            if ($query->have_posts()) {
                echo '';
                while ($query->have_posts()) {
                    $query->the_post();
                    global $post;
                    echo '<article class="review-post">';
                    // moin here strt
                    $review_reply = '';
                    $review_reply = listing_get_metabox_by_ID('review_reply', get_the_ID());

                    $review_reply_time = '';
                    $review_reply_time = listing_get_metabox_by_ID('review_reply_time', get_the_ID());
                    $review_reply_time = date_create($review_reply_time);
                    $review_reply_time = date_format($review_reply_time, "F j, Y h:i:s a");
                    // moin here ends

                    $rating = listing_get_metabox_by_ID('rating', get_the_ID());
                    $exRating = get_post_meta(get_the_ID(), 'rating', true);
                    if (empty($exRating)) {
                        update_post_meta(get_the_ID(), 'rating', $rating);
                    }
                    $rate = $rating;
                    $gallery = get_post_meta(get_the_ID(), 'gallery_image_ids', true);
                    $author_id = $post->post_author;

                    $author_avatar_url = get_user_meta($author_id, "listingpro_author_img_url", true);
                    $avatar;
                    if (!empty($author_avatar_url)) {
                        $avatar = $author_avatar_url;
                    } else {
                        $avatar_url = listingpro_get_avatar_url($author_id, $size = '94');
                        $avatar = $avatar_url;
                    }
                    $user_reviews_count = count_user_posts($author_id, 'lp-reviews');
            ?>
                    <figure>
                        <div class="review-thumbnail">
                            <a href="<?php echo get_author_posts_url($author_id); ?>">
                                <img src="<?php echo esc_attr($avatar); ?>" alt="image">
                            </a>
                        </div>

                    </figure>
                    <section class="details">
                        <div class="top-section">
                            <h3><?php the_title(); ?></h3>
                            <time><?php echo get_the_time('F j, Y g:i a'); ?></time>
                            <div class="review-count">
                                <?php
                                if ($lp_multi_rating_state == 1 && !empty($lp_multi_rating_state)) {
                                    $post_rating_data = get_post_meta($post->ID, 'lp_listingpro_options', true);
                                    $lp_multi_rating_fields_count = 0;
                                    $show_multi_rate_drop = false;
                                    if (is_array($lp_multi_rating_fields) || is_object($lp_multi_rating_fields)) {
                                        $lp_multi_rating_fields_count = count($lp_multi_rating_fields);
                                    }
                                    if ($lp_multi_rating_fields_count > 0) {
                                        if (array_key_exists(0, $post_rating_data)) {
                                            $show_multi_rate_drop = true;
                                        }
                                    }
                                    if ($show_multi_rate_drop) {
                                        echo '<a href="#" data-rate-box="multi-box-' . $post->ID . '" class="open-multi-rate-box"><i class="fa fa-chevron-down" aria-hidden="true"></i>' . esc_html__('View All', 'medicalpro') . '</a>';
                                ?>
                                        <div class="lp-multi-star-wrap" id="multi-box-<?php echo $post->ID; ?>">
                                            <?php
                                            if (count($lp_multi_rating_fields) > 0) {
                                                if (isset($lp_multi_rating_fields['default'])) {
                                                    foreach ($lp_multi_rating_fields['default'] as $k => $v) {
                                                        $field_rating_val = '';
                                                        if (isset($post_rating_data[$k])) {
                                                            $field_rating_val = $post_rating_data[$k];
                                                        }
                                            ?>
                                                        <div class="lp-multi-star-field rating-with-colors">
                                                            <label><?php echo $v; ?></label>
                                                            <p>
                                                                <i class="fa <?php
                                                                                if ($field_rating_val > 0) {
                                                                                    echo 'fa-star';
                                                                                } else {
                                                                                    echo 'fa-star-o';
                                                                                }
                                                                                ?>" aria-hidden="true"></i>
                                                                <i class="fa <?php
                                                                                if ($field_rating_val > 1) {
                                                                                    echo 'fa-star';
                                                                                } else {
                                                                                    echo 'fa-star-o';
                                                                                }
                                                                                ?>" aria-hidden="true"></i>
                                                                <i class="fa <?php
                                                                                if ($field_rating_val > 2) {
                                                                                    echo 'fa-star';
                                                                                } else {
                                                                                    echo 'fa-star-o';
                                                                                }
                                                                                ?>" aria-hidden="true"></i>
                                                                <i class="fa <?php
                                                                                if ($field_rating_val > 3) {
                                                                                    echo 'fa-star';
                                                                                } else {
                                                                                    echo 'fa-star-o';
                                                                                }
                                                                                ?>" aria-hidden="true"></i>
                                                                <i class="fa <?php
                                                                                if ($field_rating_val > 4) {
                                                                                    echo 'fa-star';
                                                                                } else {
                                                                                    echo 'fa-star-o';
                                                                                }
                                                                                ?>" aria-hidden="true"></i>
                                                            </p>
                                                        </div>
                                                    <?php
                                                    }
                                                } else {
                                                    foreach ($lp_multi_rating_fields as $k => $v) {
                                                        $field_rating_val = '';
                                                        if (isset($post_rating_data[$k])) {
                                                            $field_rating_val = $post_rating_data[$k];
                                                        }
                                                    ?>
                                                        <div class="lp-multi-star-field rating-with-colors">
                                                            <label><?php echo $v; ?></label>
                                                            <p>
                                                                <i class="fa <?php
                                                                                if ($field_rating_val > 0) {
                                                                                    echo 'fa-star';
                                                                                } else {
                                                                                    echo 'fa-star-o';
                                                                                }
                                                                                ?>" aria-hidden="true"></i>
                                                                <i class="fa <?php
                                                                                if ($field_rating_val > 1) {
                                                                                    echo 'fa-star';
                                                                                } else {
                                                                                    echo 'fa-star-o';
                                                                                }
                                                                                ?>" aria-hidden="true"></i>
                                                                <i class="fa <?php
                                                                                if ($field_rating_val > 2) {
                                                                                    echo 'fa-star';
                                                                                } else {
                                                                                    echo 'fa-star-o';
                                                                                }
                                                                                ?>" aria-hidden="true"></i>
                                                                <i class="fa <?php
                                                                                if ($field_rating_val > 3) {
                                                                                    echo 'fa-star';
                                                                                } else {
                                                                                    echo 'fa-star-o';
                                                                                }
                                                                                ?>" aria-hidden="true"></i>
                                                                <i class="fa <?php
                                                                                if ($field_rating_val > 4) {
                                                                                    echo 'fa-star';
                                                                                } else {
                                                                                    echo 'fa-star-o';
                                                                                }
                                                                                ?>" aria-hidden="true"></i>
                                                            </p>
                                                        </div>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                $review_rating = listing_get_metabox_by_ID('rating', get_the_ID());
                                ?>
                                <div class="rating rating-with-colors">
                                    <?php
                                    mp_listingpro_ratings_stars('rating', get_the_ID());
                                    ?>
                                </div>

                            </div>
                        </div>

                        <div class="content-section">

                            <?php if (!empty($gallery)) { ?>
                                <div class="images-gal-section">
                                    <div class="row">
                                        <div class="img-col review-img-slider">
                                            <?php
                                            //image gallery
                                            $imagearray = explode(',', $gallery);
                                            foreach ($imagearray as $image) {
                                                $imgGal = wp_get_attachment_image($image, 'listingpro-review-gallery-thumb', '', '');
                                                $imgGalFull = wp_get_attachment_image_src($image, 'full');
                                                echo '<a class="galImgFull" href="' . $imgGalFull[0] . '">' . $imgGal . '</a>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                    </section>
                    <div class="clearfix"></div>
                    <div class="mp-review-content-single-review-detail"><?php the_content(); ?></div>
                    <?php
                    $interests = '';
                    $Lols = '';
                    $loves = '';
                    $interVal = esc_html__('Interesting', 'medicalpro');
                    $lolVal = esc_html__('Lol', 'medicalpro');
                    $loveVal = esc_html__('Love', 'medicalpro');

                    $interests = listing_get_metabox_by_ID('review_' . $interVal . '', get_the_ID());
                    $Lols = listing_get_metabox_by_ID('review_' . $lolVal . '', get_the_ID());
                    $loves = listing_get_metabox_by_ID('review_' . $loveVal . '', get_the_ID());


                    if (empty($interests)) {
                        $interests = 0;
                    }
                    if (empty($Lols)) {
                        $Lols = 0;
                    }
                    if (empty($loves)) {
                        $loves = 0;
                    }
                    ?>
                    <div class="bottom-section">
                        <form action="#">
                            <span><?php echo esc_html__('Was this helpful?', 'medicalpro'); ?></span>
                            <ul>
                                <li>
                                    <a class="instresting reviewRes" href="#" data-reacted="<?php echo esc_html__('You already reacted', 'medicalpro'); ?>" data-restype='<?php echo $interVal; ?>' data-id='<?php the_ID(); ?>' data-score='<?php echo esc_attr($interests); ?>'>
                                        <i class="fa fa-thumbs-up" aria-hidden="true"></i><span class="interests-score"><?php if (!empty($interests)) echo $interests; ?></span>
                                        <span class="lp_state"></span>
                                    </a>

                                </li>
                                <li>
                                    <a class="instresting reviewRes" href="#" data-reacted="<?php echo esc_html__('You already reacted', 'medicalpro'); ?>" data-restype='<?php echo $lolVal; ?>' data-id='<?php the_ID(); ?>' data-score='<?php echo esc_attr($Lols); ?>'>
                                        <i class="fa fa-thumbs-down" aria-hidden="true"></i><span class="interests-score"><?php if (!empty($Lols)) echo $Lols; ?></span>
                                        <span class="lp_state"></span>
                                    </a>

                                </li>


                            </ul>
                        </form>
                    </div>
                    <?php if (!empty($review_reply)) { ?>
                        <section class="details detail-sec">
                            <div class="owner-response">
                                <h3><?php esc_html_e('Owner Response', 'medicalpro'); ?></h3>
                                <?php if (!empty($review_reply_time)) { ?>
                                    <time><?php echo $review_reply_time; ?></time>
                                <?php } ?>
                                <p><?php echo $review_reply; ?></p>

                            </div>
                        </section>
                    <?php } ?>
                    <!-- moin here ends-->
    <?php
                    echo '</article>';
                }
                echo '';
                wp_reset_postdata();
            } else {
            }
            //}
            echo '</div>';
        }
    }
}


function medicalpro_get_listing_overall_ratings($postid = 0)
{
    global $listingpro_options;

    $listing_reviewed = get_post_meta($postid, 'listing_reviewed', true);
    $listing_rate     = get_post_meta($postid, 'listing_rate', true);

    $review_idss    = listing_get_metabox_by_ID('reviews_ids', $postid);
    $review_ids_arr = isset($review_idss) && !empty($review_idss) ? explode(',', $review_idss) : '';

    $listing_total_reviews = 0;
    $review_fields_ratings = $fields_average_ragings = array();
    $lp_multi_rating_state = $listingpro_options['lp_multirating_switch'];
    if ($lp_multi_rating_state == 1 && !empty($lp_multi_rating_state)) {
        $lp_multi_rating_fields = get_listing_multi_ratings_fields($postid);
        if (isset($review_ids_arr) && !empty($review_ids_arr)) {
            foreach ($review_ids_arr as $review_id) {
                if (get_post_status($review_id) == "publish") {
                    $listing_total_reviews++;
                    $review_meta_options = get_post_meta($review_id, 'lp_listingpro_options', true);
                    if (isset($lp_multi_rating_fields) && !empty($lp_multi_rating_fields) && is_array($lp_multi_rating_fields)) {
                        foreach ($lp_multi_rating_fields as $key => $val) {
                            if (isset($review_meta_options[$key])) {
                                $review_fields_ratings[$val][] = $review_meta_options[$key];
                            }
                        }
                    }
                }
            }
        }
        if (isset($review_fields_ratings) && !empty($review_fields_ratings)) {
            foreach ($review_fields_ratings as $key => $review_field_ratings) {
                $field_total_rating = 0;
                foreach ($review_field_ratings as $review_field_rating) {
                    if (is_numeric($review_field_rating)) {
                        $field_total_rating += $review_field_rating;
                    }
                }

                $average_rating = round($field_total_rating / (count($review_field_ratings)), 1);
                if (strpos($average_rating, ".") == false) {
                    $average_rating = $average_rating . '.0';
                }

                $fields_average_ragings[$key]['total_rating']   = $field_total_rating;
                $fields_average_ragings[$key]['average_rating'] = $average_rating;
                $fields_average_ragings[$key]['rating_count']   = count($review_field_ratings);
            }
        }
    } else {
        $NumberRating = listingpro_ratings_numbers($postid);
        $listing_total_reviews = $NumberRating;
    }
    ?>

    <div class="mp-experiences-content">
        <div class="row">
            <div class="col-md-12">
                <div class="mp-experiences-content-container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mp-experiences-content-overall">
                                <div class="mp-experiences-content-overall-heading">
                                    <p><?php esc_html_e('Overall Rating', 'medicalpro') ?></p>
                                </div>
                                <div class="mp-experiences-content-overall-content">
                                    <div class="mp-clearfix"></div>
                                    <div class="display-inline-block mp-experiences-content-overall-content-profile-rating pull-left">
                                        <p><?php
                                            $NumberRating = listingpro_ratings_numbers($postid);
                                            if ($NumberRating != 0) {
                                                echo lp_cal_listing_rate($postid);
                                            } else {
                                                echo lp_cal_listing_rate($postid);
                                            }
                                            if ($listing_total_reviews < 1) {
                                                $listing_total_reviews = 1;
                                            }
                                            ?>
                                        </p>
                                    </div>
                                    <div class="display-inline-block mp-experiences-content-overall-content-profile-rating-details pull-left">
                                        <div class="mp-experiences-content-overall-content-profile-rating-stars">
                                            <?php
                                            $rating = get_post_meta($postid, 'listing_rate', true);
                                            $average_rating = apply_filters('lp_rating_number_format', $rating);
                                            ?>
                                            <script type="application/ld+json">
                                            {"@context":"https://schema.org/","@type":"EmployerAggregateRating","itemReviewed":{"@type":"Organization","name":"<?php echo mb_substr(get_the_title(), 0, 40); ?>","sameAs":"<?php echo get_the_permalink(); ?>"},"ratingValue":"<?php echo $average_rating; ?>","bestRating":"5","worstRating":"1","ratingCount":"<?php echo $listing_total_reviews; ?>"}
                                            </script>
                                            <div class="mp-experiences-content-overall-content-profile-rating-stars md-rating-stars-outer">
                                                <span class="lp-star-box <?php echo $average_rating >= 1 ? 'filled level4' : ''; ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                                                <span class="lp-star-box <?php echo $average_rating >= 2 ? 'filled level4' : ''; ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                                                <span class="lp-star-box <?php echo $average_rating >= 3 ? 'filled level4' : ''; ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                                                <span class="lp-star-box <?php echo $average_rating >= 4 ? 'filled level4' : ''; ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                                                <span class="lp-star-box <?php echo $average_rating >= 5 ? 'filled level4' : ''; ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                        <div class="mp-experiences-content-overall-content-profile-rating-detail">
                                            <p><?php printf(esc_html__('Based on %u Experiences', 'medicalpro'), $listing_total_reviews); ?></p>
                                        </div>
                                    </div>
                                    <div class="mp-clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mp-experiences-content-feedback">
                                <div class="mp-experiences-content-feedback-heading">
                                    <p><?php esc_html_e('Rate your experience', 'medicalpro'); ?></p>
                                </div>
                                <div class="mp-experiences-content-feedback-subtext">
                                    <p><?php esc_html_e('How likely are you to recommend us?', 'medicalpro'); ?></p>
                                </div>
                                <div class="mp-experiences-content-feedback-stars">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26.281" height="24.994" viewBox="0 0 26.281 24.994">
                                        <path class="a" d="M15.63,22.534l-8.121,4.96,2.208-9.257L2.49,12.047l9.485-.76L15.63,2.5l3.655,8.787,9.487.76-7.228,6.19,2.208,9.257Z" transform="translate(-2.49 -2.5)" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26.281" height="24.994" viewBox="0 0 26.281 24.994">
                                        <path class="a" d="M15.63,22.534l-8.121,4.96,2.208-9.257L2.49,12.047l9.485-.76L15.63,2.5l3.655,8.787,9.487.76-7.228,6.19,2.208,9.257Z" transform="translate(-2.49 -2.5)" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26.281" height="24.994" viewBox="0 0 26.281 24.994">
                                        <path class="a" d="M15.63,22.534l-8.121,4.96,2.208-9.257L2.49,12.047l9.485-.76L15.63,2.5l3.655,8.787,9.487.76-7.228,6.19,2.208,9.257Z" transform="translate(-2.49 -2.5)" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26.281" height="24.994" viewBox="0 0 26.281 24.994">
                                        <path class="a" d="M15.63,22.534l-8.121,4.96,2.208-9.257L2.49,12.047l9.485-.76L15.63,2.5l3.655,8.787,9.487.76-7.228,6.19,2.208,9.257Z" transform="translate(-2.49 -2.5)" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="26.281" height="24.994" viewBox="0 0 26.281 24.994">
                                        <path class="a" d="M15.63,22.534l-8.121,4.96,2.208-9.257L2.49,12.047l9.485-.76L15.63,2.5l3.655,8.787,9.487.76-7.228,6.19,2.208,9.257Z" transform="translate(-2.49 -2.5)" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($fields_average_ragings) && !empty($fields_average_ragings)) { ?>
                            <div class="col-md-12">
                                <div class="mp-experiences-content-criteria">
                                    <div class="mp-clearfix"></div>
                                    <?php foreach ($fields_average_ragings as $key => $fields_average_raging) {
                                        $average_rating = round($fields_average_raging['average_rating']);
                                    ?>
                                        <div class="mp-experiences-content-criteria-single col-md-6 horizontal display-flex">
                                            <div class="mp-experiences-content-criteria-single-title vertical display-flex">
                                                <p><?php echo esc_html($key); ?></p>
                                            </div>
                                            <div class="mp-experiences-content-criteria-single-rating vertical display-flex">
                                                <div class="md-listing-stars clearfix">
                                                    <div class="md-rating-stars-outer">
                                                        <span class="lp-star-box <?php echo $average_rating >= 1 ? 'filled level4' : ''; ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                                                        <span class="lp-star-box <?php echo $average_rating >= 2 ? 'filled level4' : ''; ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                                                        <span class="lp-star-box <?php echo $average_rating >= 3 ? 'filled level4' : ''; ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                                                        <span class="lp-star-box <?php echo $average_rating >= 4 ? 'filled level4' : ''; ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                                                        <span class="lp-star-box <?php echo $average_rating >= 5 ? 'filled level4' : ''; ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mp-experiences-content-criteria-single-rate vertical display-flex">
                                                <div><?php echo $fields_average_raging['average_rating']; ?></div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="mp-clearfix"></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

}

if (!function_exists('mp_activity_reviews')) {
    function mp_activity_reviews($review_id, $author_id)
    {
        global $listingpro_options;
        $showReport = true;
        if (isset($listingpro_options['lp_detail_page_review_report_button'])) {
            if ($listingpro_options['lp_detail_page_review_report_button'] == 'off') {
                $showReport = false;
            }
        }
        $lp_multi_rating_state        =   $listingpro_options['lp_multirating_switch'];
        if ($lp_multi_rating_state == 1 && !empty($lp_multi_rating_state)) {
            $lp_multi_rating_fields =   get_listing_multi_ratings_fields($review_id);
        }
        $currentUserId = get_current_user_id();
        $review_reply = '';
        $review_reply = listing_get_metabox_by_ID('review_reply', $review_id);
        $review_reply_time = '';
        $review_reply_time = listing_get_metabox_by_ID('review_reply_time', $review_id);
        $rating = listing_get_metabox_by_ID('rating', $review_id);
        $rate = $rating;
        $gallery = get_post_meta($review_id, 'gallery_image_ids', true);
        $author_avatar_url = get_user_meta($author_id, "listingpro_author_img_url", true);
        $avatar = '';
        if (!empty($author_avatar_url)) {
            $avatar =  $author_avatar_url;
        } else {
            $avatar_url = listingpro_get_avatar_url($author_id, $size = '94');
            $avatar =  $avatar_url;
        }
        $user_reviews_count = count_user_posts($author_id, 'lp-reviews');
        $interests = '';
        $Lols = '';
        $loves = '';
        $interVal = esc_html__('Interesting', 'medicalpro');
        $lolVal = esc_html__('Lol', 'medicalpro');
        $loveVal = esc_html__('Love', 'medicalpro');
        $interests = listing_get_metabox_by_ID('review_' . $interVal . '', $review_id);
        $Lols = listing_get_metabox_by_ID('review_' . $lolVal . '', $review_id);
        $loves = listing_get_metabox_by_ID('review_' . $loveVal . '', $review_id);
        if (empty($interests)) {
            $interests = 0;
        }
        if (empty($Lols)) {
            $Lols = 0;
        }
        if (empty($loves)) {
            $loves = 0;
        }
        $reacted_msg    =   esc_html__('You already reacted', 'medicalpro');
        $rating_num_bg  =   '';
        $rating_num_clr  =   '';
        if ($rating < 3) {
            $rating_num_bg  =   'num-level1';
            $rating_num_clr  =   'level1';
        }
        if ($rating < 4) {
            $rating_num_bg  =   'num-level2';
            $rating_num_clr  =   'level2';
        }
        if ($rating < 5) {
            $rating_num_bg  =   'num-level3';
            $rating_num_clr  =   'level3';
        }
        if ($rating >= 5) {
            $rating_num_bg  =   'num-level4';
            $rating_num_clr  =   'level4';
        }
        echo '<article class="review-post">';
        // moin here strt
        $review_reply = '';
        $review_reply = listing_get_metabox_by_ID('review_reply', get_the_ID());

        $review_reply_time = '';
        $review_reply_time = listing_get_metabox_by_ID('review_reply_time', get_the_ID());
        $review_reply_time = date_create($review_reply_time);
        $review_reply_time = date_format($review_reply_time, "F j, Y h:i:s a");
        // moin here ends

        $rating = listing_get_metabox_by_ID('rating', get_the_ID());
        $exRating = get_post_meta(get_the_ID(), 'rating', true);
        if (empty($exRating)) {
            update_post_meta(get_the_ID(), 'rating', $rating);
        }
        $rate = $rating;
        $gallery = get_post_meta(get_the_ID(), 'gallery_image_ids', true);
        $post = get_post($review_id, OBJECT);
        $author_id = $post->post_author;

        $author_avatar_url = get_user_meta($author_id, "listingpro_author_img_url", true);
        $avatar;
        if (!empty($author_avatar_url)) {
            $avatar = $author_avatar_url;
        } else {
            $avatar_url = listingpro_get_avatar_url($author_id, $size = '94');
            $avatar = $avatar_url;
        }
        $user_reviews_count = count_user_posts($author_id, 'lp-reviews');
    ?>
        <figure>
            <div class="review-thumbnail">
                <a href="<?php echo get_author_posts_url($author_id); ?>">
                    <img src="<?php echo esc_attr($avatar); ?>" alt="image">
                </a>
            </div>

        </figure>
        <section class="details">
            <div class="top-section">
                <?php
                $listID = listing_get_metabox_by_ID('listing_id', get_the_ID());
                ?>
                <h3><?php the_title(); ?> &nbsp; <small><a href="<?php echo get_permalink($listID); ?>">(<?php echo get_the_title($listID); ?>)</a></small></h3>
                <time><?php echo get_the_time('F j, Y g:i a'); ?></time>
                <div class="review-count">
                    <?php
                    if ($lp_multi_rating_state == 1 && !empty($lp_multi_rating_state)) {
                        $post_rating_data = get_post_meta($review_id, 'lp_listingpro_options', true);
                        $lp_multi_rating_fields_count = 0;
                        $show_multi_rate_drop = false;
                        if (is_array($lp_multi_rating_fields) || is_object($lp_multi_rating_fields)) {
                            $lp_multi_rating_fields_count = count($lp_multi_rating_fields);
                        }
                        if ($lp_multi_rating_fields_count > 0) {
                            if (array_key_exists(0, $post_rating_data)) {
                                $show_multi_rate_drop = true;
                            }
                        }
                        if ($show_multi_rate_drop) {
                            echo '<a href="#" data-rate-box="multi-box-' . $post->ID . '" class="open-multi-rate-box"><i class="fa fa-chevron-down" aria-hidden="true"></i>' . esc_html__('View All', 'medicalpro') . '</a>';
                    ?>
                            <div class="lp-multi-star-wrap" id="multi-box-<?php echo $post->ID; ?>">
                                <?php
                                if (count($lp_multi_rating_fields) > 0) {
                                    foreach ($lp_multi_rating_fields as $k => $v) {
                                        $field_rating_val = '';
                                        if (isset($post_rating_data[$k])) {
                                            $field_rating_val = $post_rating_data[$k];
                                        }
                                ?>
                                        <div class="lp-multi-star-field rating-with-colors">
                                            <label><?php echo $v; ?></label>
                                            <p>
                                                <i class="fa <?php
                                                                if ($field_rating_val > 0) {
                                                                    echo 'fa-star';
                                                                } else {
                                                                    echo 'fa-star-o';
                                                                }
                                                                ?>" aria-hidden="true"></i>
                                                <i class="fa <?php
                                                                if ($field_rating_val > 1) {
                                                                    echo 'fa-star';
                                                                } else {
                                                                    echo 'fa-star-o';
                                                                }
                                                                ?>" aria-hidden="true"></i>
                                                <i class="fa <?php
                                                                if ($field_rating_val > 2) {
                                                                    echo 'fa-star';
                                                                } else {
                                                                    echo 'fa-star-o';
                                                                }
                                                                ?>" aria-hidden="true"></i>
                                                <i class="fa <?php
                                                                if ($field_rating_val > 3) {
                                                                    echo 'fa-star';
                                                                } else {
                                                                    echo 'fa-star-o';
                                                                }
                                                                ?>" aria-hidden="true"></i>
                                                <i class="fa <?php
                                                                if ($field_rating_val > 4) {
                                                                    echo 'fa-star';
                                                                } else {
                                                                    echo 'fa-star-o';
                                                                }
                                                                ?>" aria-hidden="true"></i>
                                            </p>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <?php
                    $review_rating = listing_get_metabox_by_ID('rating', get_the_ID());
                    ?>
                    <div class="rating rating-with-colors">
                        <?php
                        mp_listingpro_ratings_stars('rating', get_the_ID());
                        ?>
                    </div>

                </div>
            </div>

            <div class="content-section">

                <?php if (!empty($gallery)) { ?>
                    <div class="images-gal-section">
                        <div class="row">
                            <div class="img-col review-img-slider">
                                <?php
                                //image gallery
                                $imagearray = explode(',', $gallery);
                                foreach ($imagearray as $image) {
                                    $imgGal = wp_get_attachment_image($image, 'listingpro-review-gallery-thumb', '', '');
                                    $imgGalFull = wp_get_attachment_image_src($image, 'full');
                                    echo '<a class="galImgFull" href="' . $imgGalFull[0] . '">' . $imgGal . '</a>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </section>
        <div class="clearfix"></div>
        <div class="mp-review-content-single-review-detail"><?php the_content(); ?></div>
        <?php
        $interests = '';
        $Lols = '';
        $loves = '';
        $interVal = esc_html__('Interesting', 'medicalpro');
        $lolVal = esc_html__('Lol', 'medicalpro');
        $loveVal = esc_html__('Love', 'medicalpro');

        $interests = listing_get_metabox_by_ID('review_' . $interVal . '', get_the_ID());
        $Lols = listing_get_metabox_by_ID('review_' . $lolVal . '', get_the_ID());
        $loves = listing_get_metabox_by_ID('review_' . $loveVal . '', get_the_ID());


        if (empty($interests)) {
            $interests = 0;
        }
        if (empty($Lols)) {
            $Lols = 0;
        }
        if (empty($loves)) {
            $loves = 0;
        }
        ?>
        <div class="bottom-section">
            <form action="#">
                <span><?php echo esc_html__('Was this helpful?', 'medicalpro'); ?></span>
                <ul>
                    <li>
                        <a class="instresting reviewRes" href="#" data-reacted="<?php echo esc_html__('You already reacted', 'medicalpro'); ?>" data-restype='<?php echo $interVal; ?>' data-id='<?php the_ID(); ?>' data-score='<?php echo esc_attr($interests); ?>'>
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i><span class="interests-score"><?php if (!empty($interests)) echo $interests; ?></span>
                            <span class="lp_state"></span>
                        </a>

                    </li>
                    <li>
                        <a class="instresting reviewRes" href="#" data-reacted="<?php echo esc_html__('You already reacted', 'medicalpro'); ?>" data-restype='<?php echo $lolVal; ?>' data-id='<?php the_ID(); ?>' data-score='<?php echo esc_attr($Lols); ?>'>
                            <i class="fa fa-thumbs-down" aria-hidden="true"></i><span class="interests-score"><?php if (!empty($Lols)) echo $Lols; ?></span>
                            <span class="lp_state"></span>
                        </a>

                    </li>


                </ul>
            </form>
        </div>
        <?php if (!empty($review_reply)) { ?>
            <section class="details detail-sec">
                <div class="owner-response">
                    <h3><?php esc_html_e('Owner Response', 'medicalpro'); ?></h3>
                    <?php if (!empty($review_reply_time)) { ?>
                        <time><?php echo $review_reply_time; ?></time>
                    <?php } ?>
                    <p><?php echo $review_reply; ?></p>

                </div>
            </section>
        <?php } ?>
        <!-- moin here ends-->
<?php
        echo '</article>';
    }
}

?>