<?php
namespace ElementorMedicalPro\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class MedicalPro_Columns extends Widget_Base {

    public function get_name() {
        return 'medicalpro-columns';
    }

    public function get_title() {
        return __( 'Medicalpro Columns', 'elementor-listingpro' );
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
            'listing_cols_left_img',
            [
                'label' => __('Column Left Image', 'elementor-medicalpro'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
					'url' => get_template_directory_uri()."/assets/images/columns.png",
				],
            ]
        );
        $this->add_control(
            'listing_first_col_title',
            [
                'label' => __('First Column Title', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXT,
                'default' => "1- Claimed"
            ]
        );
        $this->add_control(
            'listing_first_col_desc',
            [
                'label' => __('First Column Description', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => "Best way to start managing your business listing is by claiming it so you can update."
            ]
        );
        $this->add_control(
            'listing_cols_first_img',
            [
                'label' => __('First Column icon', 'elementor-medicalpro'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
					'url' =>  get_template_directory_uri()."/assets/images/interesting.png",
				],
            ]
        );
        $this->add_control(
            'listing_second_col_title',
            [
                'label' => __('Second Column Title', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXT,
                'default' => "2- Promote"
            ]
        );
        $this->add_control(
            'listing_second_col_desc',
            [
                'label' => __('Second Column Description', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => "Promote your business to target customers who need your services or products."
            ]
        );
        $this->add_control(
            'listing_cols_second_img',
            [
                'label' => __('Second Column icon', 'elementor-medicalpro'),
                'type' => Controls_Manager::MEDIA,
                'show_label' => true,
            ]
        );
        $this->add_control(
            'listing_third_col_title',
            [
                'label' => __('Third Column Title', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXT,
                'default' => "3- Convert"
            ]
        );
        $this->add_control(
            'listing_third_col_desc',
            [
                'label' => __('Third Column Description', 'elementor-medicalpro'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => "Turn your visitors into paying customers with exciting offers and services on your page."
            ]
        );
        $this->add_control(
            'listing_cols_third_img',
            [
                'label' => __('Third Column icon', 'elementor-medicalpro'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
					'url' => get_template_directory_uri()."/assets/images/interesting.png",
				],
            ]
        );
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        echo elementor_shortcode_medicalpro_columns( $settings );
    }
    protected function content_template() {}
    public function render_plain_content() {}
}

if (!function_exists('elementor_shortcode_medicalpro_columns')) {
    function elementor_shortcode_medicalpro_columns($atts, $content = null) {

        extract(shortcode_atts(array(
            'listing_cols_left_img'      => get_template_directory_uri()."/assets/images/columns.png",
            'listing_cols_first_img'      => '',
            'listing_cols_second_img'      => '',
            'listing_cols_third_img'      => '',
            'listing_first_col_title'    => '1- Claimed',
            'listing_first_col_desc'     => 'Best way to start managing your business listing is by claiming it so you can update.',
            'listing_second_col_title' 	 => '2- Promote',
            'listing_second_col_desc' 	 => 'Promote your business to target customers who need your services or products.',
            'listing_third_col_title' 	 => '3- Convert',
            'listing_third_col_desc' 	 => 'Turn your visitors into paying customers with exciting offers and services on your page.',
        ), $atts));

        $output = null;
        $colimage1=null;
        $colimage2=null;
        $colimage3=null;
        $leftImg = '';
        if (!empty($listing_cols_left_img)) {
            if( is_array( $listing_cols_left_img ) )
            {
                $listing_cols_left_img  =   $listing_cols_left_img['id'];
            }
            $bgImage = wp_get_attachment_image_src( $listing_cols_left_img, 'full' );
            $leftImg = '<img src="'.$bgImage[0].'" alt="">';
        }else{
            $leftImg = '';
        }
        if ( $listing_cols_first_img ) {
            if( is_array( $listing_cols_first_img ) )
            {
                $listing_cols_first_img  =   $listing_cols_first_img['id'];
            }
            $imgurl = wp_get_attachment_image_src( $listing_cols_first_img, 'full');
            if($imgurl){
                $colimage1 = $imgurl[0];
            }else{
                $colimage1 = plugin_dir_url( __FILE__ ) . "/assets/images/icons/col1.png";
            }
        }
        if ( !empty($listing_cols_second_img) ) {
            if( is_array( $listing_cols_second_img ) )
            {
                $listing_cols_second_img  =   $listing_cols_second_img['id'];
            }
            $imgurl = wp_get_attachment_image_src( $listing_cols_second_img, 'full');
            $colimage2 = '<img src="'.$imgurl[0].'" alt="">';
        }else{

            $colimage2 = '<img src="'.plugin_dir_url( __FILE__ ) . "/assets/images/icons/col2.png".'" alt="">';
        }
        if ( !empty($listing_cols_third_img )) {
            if( is_array( $listing_cols_third_img ) )
            {
                $listing_cols_third_img  =   $listing_cols_third_img['id'];
            }
            $imgurl = wp_get_attachment_image_src( $listing_cols_third_img, 'full');
            $colimage3 = '<img src="'. $imgurl[0] .'">';
        }else{

            $colimage3 = '<img src="'.plugin_dir_url( __FILE__ ) . "/assets/images/icons/col3.png".'" alt="">';
        }

        $output .='
			<div class="promotional-element listingpro-columns listingpro-columns-style2">
				<div class="listingpro-row padding-top-60 padding-bottom-60">
					<div class="promotiona-col-left">
						'.$leftImg.'
					</div>
					<div class="promotiona-col-right">
						<article>
							<div class="listingpro-columns-icons listingpro-columns-icons1"><img src="'.$colimage1.'" alt=""/></div>
							<div class="listingpro-columns-style3-content">	
								<h3> '.$listing_first_col_title.'</h3>
								<p>'.$listing_first_col_desc.'</p>
							</div>		
						</article>
						<article>
							<div class="listingpro-columns-icons listingpro-columns-icons2">'.$colimage2.'</div>
							
							<div class="listingpro-columns-style3-content">	
								<h3> '.$listing_second_col_title.'</h3>
								<p>'.$listing_second_col_desc.'</p>
							</div>
						</article>
						<article>
							<div class="listingpro-columns-icons listingpro-columns-icons3">'.$colimage3.'</div>
							<div class="listingpro-columns-style3-content">	
								<h3> '.$listing_third_col_title.'</h3>
								<p>'.$listing_third_col_desc.'</p>
							</div>
							
						</article>
					</div>
				</div>
			</div>';


        return $output;
    }
}