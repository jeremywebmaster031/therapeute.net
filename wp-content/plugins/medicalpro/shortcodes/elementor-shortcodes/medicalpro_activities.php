<?php
namespace ElementorMedicalPro\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class MedicalPro_Activities extends Widget_Base {

    public function get_name() {
        return 'medicalpro-activities';
    }

    public function get_title() {
        return __( 'Medicalpro Activities', 'elementor-listingpro' );
    }

    public function get_icon() {
        return 'eicon-posts-ticker';
    }

    public function get_categories() {
        return [ 'medicalpro' ];
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'elementor-listingpro' ),
            ]
        );
        $this->add_control(
            'number_posts',
            [
                'label' => __('Posts per page', 'elementor-medicalpro'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '3' => esc_html__('3 Posts', 'js_composer'),
                    '4' => esc_html__('4 Posts', 'js_composer'),
                    '5' => esc_html__('5 Posts', 'js_composer'),
                ],
                'default' => '3'
            ]
        );
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        echo elementor_shortcode_medicalpro_activities( $settings );
    }
    protected function content_template() {}
    public function render_plain_content() {}
}

if (!function_exists('elementor_shortcode_medicalpro_activities')) {
    function elementor_shortcode_medicalpro_activities($atts, $content = null) {

        extract(shortcode_atts(array(
            'number_posts'   => '5',
            'activity_placeholder' => ''
        ), $atts));
        require_once (THEME_PATH . "/include/aq_resizer.php");
        $output = null;

        $args   =   array(
            'post_type' => 'lp-reviews',
            'post_status' => 'publish',
            'posts_per_page' => $number_posts,
        );
        $activities  =   new \WP_Query( $args );
        $img_url    = '';
        $img_url2   = '';
        $img_url3   = '';
        $img_url4   = '';
        global $listingpro_options;
        $placeholder_img    =   '';
        $use_listing_img    =   $listingpro_options['lp_review_img_from_listing'];
        if( $use_listing_img == 'off' )
        {
            $placeholder_img    =   $listingpro_options['lp_review_placeholder'];
            $placeholder_img    =   $placeholder_img['url'];
        }

        if( $activities->have_posts() ) :
            $counter    =   1;
            $output .=  '<div class="lp-activities"><div class="lp-section-content-container"> ';
            $output .=  '    <div class="row">';
            while ( $activities->have_posts() ) : $activities->the_post();
                global $post;
                $r_meta     =   get_post_meta( get_the_ID(), 'lp_listingpro_options', true );
                $LID        =   $r_meta['listing_id'];
                $rating     =   $r_meta['rating'];

                $adStatus = get_post_meta( $LID, 'campaign_status', true );
                $CHeckAd = '';
                $adClass = '';
                if($adStatus == 'active'){
                    $CHeckAd = '<span>'.esc_html__('Ad','medicalpro').'</span>';
                    $adClass = 'promoted';
                }
                $author_avatar_url = get_user_meta( $post->post_author, "listingpro_author_img_url", true);
                $avatar;
                if( !empty( $author_avatar_url ) )
                {
                    $avatar =  $author_avatar_url;

                }
                else
                {
                    $avatar_url = listingpro_get_avatar_url ( $post->post_author, $size = '55' );
                    $avatar =  $avatar_url;
                }

                $lp_liting_title    =   get_the_title( $LID );
                if( strlen( $lp_liting_title ) > 35 )
                {
                    $lp_liting_title    =   mb_substr( $lp_liting_title, 0, 35 ).'...';
                }

                $rating_num_bg  =   '';
                $rating_num_clr  =   '';

                if( $rating < 2 ){ $rating_num_bg  =   'num-level1'; $rating_num_clr  =   'level1'; }
                if( $rating < 3 ){ $rating_num_bg  =   'num-level2'; $rating_num_clr  =   'level2'; }
                if( $rating < 4 ){ $rating_num_bg  =   'num-level3'; $rating_num_clr  =   'level3'; }
                if( $rating >= 4 ){ $rating_num_bg  =   'num-level4'; $rating_num_clr  =   'level4'; }


                $output .=  '
					<div class="col-md-3 col-sm-6"> 
						<div class="lp-activity lp-activity-new ">
							<div class="lp-activity-top-new">
								<a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'" class=""><img src="'. esc_attr($avatar) .'" alt="'. get_the_title() .'"></a>
								
							</div>
							<div class="lp-activity-bottom md-activity-bottom">
								<div class="lp-activity-review-writer ">
									<a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'">'. get_the_author() .'</a> 
									
								</div>
								<div class="md-listing-stars clearfix">
							   <div class="md-rating-stars-outer">
							   <span class="lp-rating-num rating-with-colors '. review_rating_color_class($rating) .'">'. round($rating, 2) .'</span>
									<span class="lp-star-box ';
                if( $rating > 0 ){ $output .= 'filled'.' '.$rating_num_clr; }
                $output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
									<span class="lp-star-box ';
                if( $rating > 1 ){ $output .= 'filled'.' '.$rating_num_clr; }
                $output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
									<span class="lp-star-box ';
                if( $rating > 2  ){ $output .= 'filled'.' '.$rating_num_clr; }
                $output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
									<span class="lp-star-box ';
                if( $rating > 3 ){ $output .= 'filled'.' '.$rating_num_clr; }
                $output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
									<span class="lp-star-box ';
                if( $rating > 4 ){ $output .= 'filled'.' '.$rating_num_clr; }
                $output .=  '"><i class="fa fa-star" aria-hidden="true"></i></span>
	</div>
									
								</div>
								
								
								
							</div>
							<div class="md-activity-description">
									<p>'. mb_substr( $post->post_content, '0', '70' ) .' <a href="'. get_permalink( $LID ) .'">'.esc_html__('Read More', 'medicalpro' ).'</a></p>
									
								</div>
								<p class="lp-new-activity-title"><span>Review For</span><a href="'. get_permalink( $LID ) .'">'. $lp_liting_title .'</a></p>
						</div>
					</div>';

                $counter++;
            endwhile; wp_reset_postdata();
            $output .=  '   </div></div>';
            $output .=  '</div>';
        endif;

        return $output;
    }
}