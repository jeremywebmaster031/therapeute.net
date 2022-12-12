<?php

namespace ElementorMedicalPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MedicalPro_CatLoc extends Widget_Base
{

    public function get_name()
    {
        return 'medicalpro-catloc';
    }

    public function get_title()
    {
        return __('MedicalPro Taxonomy List', 'elementor-listingpro');
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
        $location_terms = get_terms('location', array('hide_empty' => false));
        $locations = array();
        if(isset($location_terms) && !empty($location_terms)){
            foreach($location_terms as $location_term) {
                $locations[$location_term->term_id] = $location_term->name;
            }
        }
        $category_terms = get_terms('listing-category', array('hide_empty' => false));
        $cats = array();
        if(isset($category_terms) && !empty($category_terms)){
            foreach($category_terms as $category_term) {
                $cats[$category_term->term_id] = $category_term->name;
            }
        }
        $feature_terms = get_terms('features', array('hide_empty' => false));
        $features = array();
        if(isset($feature_terms) && !empty($feature_terms)){
            foreach($feature_terms as $feature_term) {
                $features[$feature_term->term_id] = $feature_term->name;
            }
        }
        $hospital_terms = get_terms('medicalpro-hospital', array('hide_empty' => false));
        $hospitals = array();
        if(isset($hospital_terms) && !empty($hospital_terms)){
            foreach($hospital_terms as $hospital_term) {
                $hospitals[$hospital_term->term_id] = $hospital_term->name;
            }
        }


        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementor-medicalpro'),
            ]
        );
        $this->add_control(
            'location_title',
            [
                'label' => __( 'Title for Location', 'elementor-medicalpro' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Location', 'js_composer' ),
            ]
        );
        $this->add_control(
            'location_ids',
            [
                'label' => __( 'Select Locations', 'elementor-listingpro' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $locations,
            ]
        );
        $this->add_control(
            'category_title',
            [
                'label' => __( 'Title for Category', 'elementor-medicalpro' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Category', 'js_composer' ),
            ]
        );
        $this->add_control(
            'category_ids',
            [
                'label' => __( 'Select Category', 'elementor-listingpro' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $cats,
            ]
        );
        $this->add_control(
            'feature_title',
            [
                'label' => __( 'Title for Feature', 'elementor-medicalpro' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Feature', 'js_composer' ),
            ]
        );
        $this->add_control(
            'feature_ids',
            [
                'label' => __( 'Select Feature', 'elementor-listingpro' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $features,
            ]
        );
        $this->add_control(
            'hospital_title',
            [
                'label' => __( 'Title for Hospital', 'elementor-medicalpro' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Hospital', 'js_composer' ),
            ]
        );
        $this->add_control(
            'hospital_ids',
            [
                'label' => __( 'Select Hospital', 'elementor-listingpro' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $hospitals,
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo elementor_shortcode_medicalpro_catloc($settings);
    }

    protected function content_template()
    {
    }
}

if (!function_exists('elementor_shortcode_medicalpro_catloc')) {
    function elementor_shortcode_medicalpro_catloc($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'location_title'    => esc_html__( 'Location', 'js_composer' ),
            'location_ids'      => '',
            'category_title'    => esc_html__( 'Category', 'js_composer' ),
            'category_ids'      => '',
            'feature_title'     => esc_html__( 'Feature', 'js_composer' ),
            'feature_ids'       => '',
            'hospital_title'    => esc_html__( 'Hospital', 'js_composer' ),
            'hospital_ids'      => '',
            'order_by'          => 'ASC',

        ), $atts));

        $cat_columns = array(
            'col-1' => array(
                'title' => $location_title,
                'slugs' => $location_ids,
                'class' => 'lp-new-location-outer-title-loc3',
                'tax_slug' => 'location',
                'icon_class' => 'fa-map-marker',
                'see_all_class' => 'show-all-feture-loc'
            ),
            'col-2' => array(
                'title' => $category_title,
                'slugs' => $category_ids,
                'class' => 'lp-new-location-outer-title-cat',
                'tax_slug' => 'listing-category',
                'icon_class' => 'fa-user-md',
                'see_all_class' => 'show-all-feture-cat'
            ),


            'col-3' => array(
                'title' => $feature_title,
                'slugs' => $feature_ids,
                'class' => 'lp-new-location-outer-title-loc',
                'tax_slug' => 'features',
                'icon_class' => 'fa-user-md',
                'see_all_class' => 'show-all-feture-fec'
            ),
            'col-4' => array(
                'title' => $hospital_title,
                'slugs' => $hospital_ids,
                'class' => 'lp-new-location-outer-title-loc4',
                'tax_slug' => 'medicalpro-hospital',
                'icon_class' => 'fa-bed',
                'see_all_class' => 'show-all-feture-loc4'
            ),
            
        );
        $output = '';
        $output .= '<div class="row padding-top-40">';
        foreach($cat_columns as $cat_column){
            $terms = (isset($cat_column['slugs']) && !empty($cat_column['slugs'])) ?  $cat_column['slugs'] : array();
            if(isset($terms) && !empty($terms)){
                $output .= '<div class="col-md-3 col-sm-4">';
                $output .= '<div class="lp-new-location-outer text-center">
                        <h4 class="lp-new-location-outer-title '. $cat_column['class'] .'">
                            <span><i class="fa '. $cat_column['icon_class'] .'" aria-hidden="true"></i></span>'. sprintf(esc_html__('BROWSE BY %s', 'medicalpro'), $cat_column['title']) .'
                        </h4>';
                $output .= '<div class="lp-new-outer">';
                $catCount = 1;
                foreach ( $terms as $k => $term_slug ){
                    $term = get_term_by('term_id', $term_slug, $cat_column['tax_slug']);
                    $catCount++;
                    $class = '';
                    $style = '';
                    if( $catCount  > 5 ){
                        $class = 'show-more';
                        $style = 'style="display:none;"';
                    }
                    if (isset($term) && is_object($term)) {
                    $output .= '<div class="'. esc_attr($class).'" '. $style .'>
                                <div class="lp-new-grid-style-inner">
                                    <div class="text-center">
                                        <a href="'. esc_url( get_term_link( $term->term_id)) .'">'. esc_attr($term->name) .'</a>
                                    </div>
                                </div>
                            </div>';
                    }
                }
                $output .= '</div>';
                if(isset($terms) && count($terms) > 5){
                    $output .= '<a href="javascript:void(0);" class="show-all-feature '. $cat_column['see_all_class'] .'" data-show_more="'.esc_html__('Show More','medicalpro').'" data-less_more="'.esc_html__('Show Less','medicalpro').'">'.esc_html__('Show More','medicalpro').'</a>';
                }
                $output .= '</div>';
                $output .= '</div>';
            }
        }
        $output .= '</div>';
        return $output;
    }
}