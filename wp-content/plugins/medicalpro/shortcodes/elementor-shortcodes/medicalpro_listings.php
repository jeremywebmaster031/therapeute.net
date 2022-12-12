<?php

namespace ElementorMedicalPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MedicalPro_listings extends Widget_Base
{

    public function get_name()
    {
        return 'medicalpro-listings';
    }

    public function get_title()
    {
        return __('Medicalpro Listings', 'elementor-listingpro');
    }

    public function get_icon()
    {
        return 'eicon-posts-ticker';
    }

    public function get_categories()
    {
        return ['medicalpro'];
    }

    public function render_plain_content()
    {
    }

    protected function _register_controls()
    {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementor-medicalpro'),
            ]
        );
        $this->add_control(
            'number_posts',
            [
                'label' => __('Posts per page', 'elementor-medicalpro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '3' => esc_html__('3 Posts', 'js_composer'),
                    '6' => esc_html__('6 Posts', 'js_composer'),
                    '9' => esc_html__('9 Posts', 'js_composer'),
                    '12' => esc_html__('12 Posts', 'js_composer'),
                    '15' => esc_html__('15 Posts', 'js_composer'),
                ],
                'default' => '3'
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo elementor_shortcode_medicalpro_listings($settings);
    }

    protected function content_template()
    {
    }
}

if (!function_exists('elementor_shortcode_medicalpro_listings')) {
    function elementor_shortcode_medicalpro_listings($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'number_posts' => '3'
        ), $atts));

        $output = null;
        $type = 'listing';
        $args = array(
            'post_type' => $type,
            'post_status' => 'publish',
            'posts_per_page' => $number_posts,
        );

        $listingcurrency = '';
        $listingprice = '';
        $listing_query = null;
        $listing_query = new \WP_Query($args);

        global $listingpro_options;
        $listing_mobile_view = $listingpro_options['single_listing_mobile_view'];
        $img_url = $listingpro_options['lp_def_featured_image']['url'];
        if ($listing_mobile_view == 'app_view2' && wp_is_mobile()) {
            ob_start();
            if ($listing_query->have_posts()) {
                $listing_entries_counter = 1;
                while ($listing_query->have_posts()): $listing_query->the_post();
                    if ($listing_entries_counter == 1) {
                        echo '<div class="app-view2-first-recent">';
                        get_template_part('mobile/listing-loop-app-view-adds');
                        echo '</div>';
                    } else {
                        get_template_part('mobile/listing-loop-app-view-new');
                    }
                    $listing_entries_counter++;
                endwhile;
            } else {
                echo 'no listings found';
            }
            $output .= ob_get_contents();
            ob_end_clean();
            ob_flush();
        } else {
            $post_count = 1;
            $output .= '
	<div class="listing-second-view paid-listing lp-section-content-container lp-list-page-grid">
		<div class="listing-post listing-md-slider4">
			';
            if ($listing_query->have_posts()) {
                while ($listing_query->have_posts()) : $listing_query->the_post();
                    $phone = listing_get_metabox('phone');
                    $website = listing_get_metabox('website');
                    $email = listing_get_metabox('email');
                    $latitude = listing_get_metabox('latitude');
                    $longitude = listing_get_metabox('longitude');
                    $gAddress = listing_get_metabox('gAddress');
                    $priceRange = listing_get_metabox('price_status');
                    $listingpTo = listing_get_metabox('list_price_to');
                    $listingprice = listing_get_metabox('list_price');
                    $isfavouriteicon = listingpro_is_favourite_grids(get_the_ID(), $onlyicon = true);
                    $isfavouritetext = listingpro_is_favourite_grids(get_the_ID(), $onlyicon = false);
                    $claimed_section = listing_get_metabox('claimed_section');
                    $rating = get_post_meta(get_the_ID(), 'listing_rate', true);
                    $rating_num_bg = '';
                    $rating_num_clr = '';

                    if ($rating < 2) {
                        $rating_num_bg = 'num-level1';
                        $rating_num_clr = 'level1';
                    }
                    if ($rating < 3) {
                        $rating_num_bg = 'num-level2';
                        $rating_num_clr = 'level2';
                    }
                    if ($rating < 4) {
                        $rating_num_bg = 'num-level3';
                        $rating_num_clr = 'level3';
                    }
                    if ($rating >= 4) {
                        $rating_num_bg = 'num-level4';
                        $rating_num_clr = 'level4';
                    }
                    $output .= '<div class="md-listing-outer"><div class="md-listing-inner">';
                    $output .= '		 <div class="md-listing-img">';

                    $certified_doctor = get_post_meta(get_the_ID(), 'mp_listing_extra_fields_certified_doctor', true);
                    if ($certified_doctor == 'Yes') {
                        $claim = '<div class="mp-claimed-profile mp-tooltip mp-home-grid-claim-badge"><span class="mp-tooltiptext"> ' . esc_html__('Certified Doctor', 'medicalpro') . '</span>
					 <img src="' . MP_PLUGIN_DIR . 'assets/images/claimed/claimed1.svg' . '" alt="Claimed Profile">
					</div>';
                    } else {
                        $claim = '';

                    }


                    if (has_post_thumbnail()) {
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'listingpro-blog-grid');
                        if (!empty($image[0])) {
                            $output .= '
												<a href="' . get_the_permalink() . '" >
													<img src="' . $image[0] . '" class="mp-doc-img" />
													' . $claim . '
												</a>';
                        } else {
                            $output .= '
												<a href="' . get_the_permalink() . '" >
													<img class="mp-doc-img" src="' . esc_html__('https://via.placeholder.com/372x240', 'medicalpro') . '" alt="">
													' . $claim . '
												</a>';
                        }
                    } else {
                        $output .= '
										<a href="' . get_the_permalink() . '" >
											<img class="mp-doc-img" src="' . $img_url . '" alt="">
											' . $claim . '
										</a>';
                    }

                    $output .= '		 </div>';
                    $output .= '		 <div class="md-listing-content">';
                    $output .= '
									<h4 class="margin-0">
										<a href="' . get_the_permalink() . '">
											' . mb_substr(get_the_title(), 0, 18) . '
										</a>
									</h4>
									<div class="listing-cats">';
                    $cats = get_the_terms(get_the_ID(), 'listing-category');
                    if (!empty($cats)) {
                        foreach ($cats as $cat) {
                            $term_link = get_term_link($cat);
                            $output .= '
														<a href="' . $term_link . '">
															' . $cat->name . '
														</a>';
                            break; // For Only First Cat To Show
                        }
                    }
                    $output .= '
											</div>
							<div class="text-left">
								<div class="md-listing-stars clearfix">
								
									<span class="lp-rating-num rating-with-colors ' . review_rating_color_class($rating) . '">' . round((float)$rating, 2) . '</span>
									<div class="md-rating-stars-outer">
																	<span class="lp-star-box ';
                    if ($rating > 0) {
                        $output .= 'filled' . ' ' . $rating_num_clr;
                    }
                    $output .= '"><i class="fa fa-star" aria-hidden="true"></i></span>
																	<span class="lp-star-box ';
                    if ($rating > 1) {
                        $output .= 'filled' . ' ' . $rating_num_clr;
                    }
                    $output .= '"><i class="fa fa-star" aria-hidden="true"></i></span>
																	<span class="lp-star-box ';
                    if ($rating > 2) {
                        $output .= 'filled' . ' ' . $rating_num_clr;
                    }
                    $output .= '"><i class="fa fa-star" aria-hidden="true"></i></span>
																	<span class="lp-star-box ';
                    if ($rating > 3) {
                        $output .= 'filled' . ' ' . $rating_num_clr;
                    }
                    $output .= '"><i class="fa fa-star" aria-hidden="true"></i></span>
																	<span class="lp-star-box ';
                    if ($rating > 4) {
                        $output .= 'filled' . ' ' . $rating_num_clr;
                    }
                    $output .= '"><i class="fa fa-star" aria-hidden="true"></i></span>
									</div>
									
								</div>
							</div>';
                    if (!empty($gAddress)) {
                        $s = $gAddress;
                        $max_length = 25;
                        if (strlen($s) > $max_length) {
                            $offset = $max_length - strlen($s);
                            $s = substr($s, 0, strrpos($s, ' ', $offset)) . '...';
                        }
                        $output .= '
												
													<p class="md-listing-adres" title="' . $gAddress . '">' . $s . '</p>
												';
                    }
                    $output .= '</div>';
                    $output .= '</div></div>';

                endwhile;
            }
            $output .= '
			
		</div>
	</div>';
        }


        return $output;
    }
}