<?php
namespace ElementorMedicalPro\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class MedicalPro_Facts extends Widget_Base {

    public function get_name() {
        return 'medicalpro-facts';
    }

    public function get_title() {
        return __( 'Medicalpro Facts Element', 'elementor-listingpro' );
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
            'listing_first_fac_title',
            [
                'label' => __('First fact Title', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXT,
                'default' => "256-bit"
            ]
        );
        $this->add_control(
            'listing_first_fac_desc',
            [
                'label' => __('First fact Title2', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXT,
                'default' => "encryption"
            ]
        );
        $this->add_control(
            'listing_fac_first_img',
            [
                'label' => __('First fact Img', 'elementor-medicalpro'),
                'type' => Controls_Manager::MEDIA,
            ]
        );
        $this->add_control(
            'listing_second_fac_title',
            [
                'label' => __('Second fact Title', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXT,
                'default' => "ISO 27001"
            ]
        );
        $this->add_control(
            'listing_second_fac_desc',
            [
                'label' => __('Second fact Title2', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXT,
                'default' => "certified"
            ]
        );
        $this->add_control(
            'listing_fac_sec_img',
            [
                'label' => __('Second fact icon', 'elementor-medicalpro'),
                'type' => Controls_Manager::MEDIA,
            ]
        );
        $this->add_control(
            'listing_third_fac_title',
            [
                'label' => __('Third fact Title', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXT,
                'default' => "HIPAA"
            ]
        );
        $this->add_control(
            'listing_third_fac_desc',
            [
                'label' => __('Third fact Title2', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXT,
                'default' => "compliant"
            ]
        );
        $this->add_control(
            'listing_fac_third_img',
            [
                'label' => __('Third fact icon', 'elementor-medicalpro'),
                'type' => Controls_Manager::MEDIA,
            ]
        );
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        echo elementor_shortcode_medicalpro_facts( $settings );
    }
    protected function content_template() {}
    public function render_plain_content() {}
}

if (!function_exists('elementor_shortcode_medicalpro_facts')) {
    function elementor_shortcode_medicalpro_facts($atts, $content = null) {

        extract(shortcode_atts(array(


            'listing_first_fac_title'    => '256-bit',
            'listing_fac_first_img'    => '',
            'listing_fac_sec_img'    => '',
            'listing_fac_third_img'    => '',

            'listing_first_fac_desc'     => 'encryption',
            'listing_second_fac_title' 	 => 'ISO 27001',
            'listing_second_fac_desc' 	 => 'certified',
            'listing_third_fac_title' 	 => 'HIPAA',
            'listing_third_fac_desc' 	 => 'compliant',


        ), $atts));

        $output = null;
        $facimage1=null;
        $facimage2=null;
        $facimage3=null;
        if ( !empty($listing_fac_first_img )) {
            if( is_array( $listing_fac_first_img ) )
            {
                $listing_fac_first_img  =   $listing_fac_first_img['id'];
            }
            $imgurl = wp_get_attachment_image_src( $listing_fac_first_img, 'full');

            $facimage1 = '<img src="'.$imgurl[0].'" alt="">';
        }else{

            $facimage1 = '<img src="'.plugin_dir_url( __FILE__ ) . "/assets/images/icons/fc3.png".'" alt="">';

        }
        if (!empty($listing_fac_sec_img )) {
            if( is_array( $listing_fac_sec_img ) )
            {
                $listing_fac_sec_img  =   $listing_fac_sec_img['id'];
            }
            $imgurl = wp_get_attachment_image_src( $listing_fac_sec_img, 'full');
            $facimage2 = '<img src="'.$imgurl[0].'" alt="">';
        }else{

            $facimage2 = '<img src="'.plugin_dir_url( __FILE__ ) . "/assets/images/icons/fc2.png".'" alt="">';

        }
        if ( !empty($listing_fac_third_img )) {
            if( is_array( $listing_fac_third_img ) )
            {
                $listing_fac_third_img  =   $listing_fac_third_img['id'];
            }
            $imgurl = wp_get_attachment_image_src( $listing_fac_third_img, 'full');
            $facimage3 = '<img src="'.$imgurl[0].'" alt="">';
        }else{

            $facimage3 = '<img src="'.plugin_dir_url( __FILE__ ) . "/assets/images/icons/fc1.png".'" alt="">';

        }


        $output .='
			<div class="md-facts-outer">
				<div class="row padding-top-60 padding-bottom-60">
					<div class="col-md-4 text-center">
    					<div class="md-fact-container">
    						<div class="md-fact-icone">
    							
    							'.$facimage1.'
    						
    						</div>
    						<div class="md-facts-content">
    							<p>'.$listing_first_fac_title.'</p>
    							<p>'.$listing_first_fac_desc.'</p>
    						</div>
    					</div>
					</div>
					<div class="col-md-4 text-center">
    					<div class="md-fact-container">
    						<div class="md-fact-icone">
    							'.$facimage2.'
    						
    						</div>
    						<div class="md-facts-content">
    							<p>'.$listing_second_fac_title.'</p>
    							<p>'.$listing_second_fac_desc.'</p>
    						</div>
						</div>
					</div>
					<div class="col-md-4 text-center">
					    <div class="md-fact-container">
    						<div class="md-fact-icone">
	    						'.$facimage3.'
    						</div>
	    					<div class="md-facts-content">
		    					<p>'.$listing_third_fac_title.'</p>
			    				<p>'.$listing_third_fac_desc.'</p>
				    		</div>
					    </div>
					</div>
				</div>
			</div>';


        return $output;
    }
}