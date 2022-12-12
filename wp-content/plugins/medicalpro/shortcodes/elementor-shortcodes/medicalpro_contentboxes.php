<?php
namespace ElementorMedicalPro\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class MedicalPro_contentboxes extends Widget_Base {

    public function get_name() {
        return 'medicalpro-contentboxes';
    }

    public function get_title() {
        return __( 'Medicalpro Content Boxes', 'elementor-listingpro' );
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
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'content_title', [
                'label' => __( 'Title', 'elementor-listingpro' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'PLANNING' , 'elementor-listingpro' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'content_desc', [
                'label' => __( 'Content', 'elementor-listingpro' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Sed ut perspiciatis unde omnis iste natus error sit v oluptatem accusantium or sit v oluptatem accusantiumor sit v oluptatem ' , 'elementor-listingpro' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'content_img', [
                'label' => __( 'Upload Content Icon Image', 'elementor-listingpro' ),
                'type' => Controls_Manager::MEDIA,
                'show_label' => true,
            ]
        );
        $repeater->add_control(
            'icon_background_color', [
                'label' => __( 'icon background color', 'elementor-listingpro' ),
                'type' => Controls_Manager::COLOR,
                'show_label' => true,
                'default' => '#000',
            ]
        );
        $this->add_control(
            'content_boxes',
            [
                'label' => __( 'Repeater List', 'elementor-listingpro' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'content_title' => __( 'PLANNING' , 'elementor-listingpro' ),
                        'content_desc' =>  __( 'Sed ut perspiciatis unde omnis iste natus error sit v oluptatem accusantium or sit v oluptatem accusantiumor sit v oluptatem ' , 'elementor-listingpro' ),
                        'content_img' => '',
                        'icon_background_color' => '#000',
                    ],
                ],
                'title_field' => '{{{ content_title }}}',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'elementor-listingpro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'text_transform',
            [
                'label' => __( 'Text Transform', 'elementor-listingpro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'None', 'elementor-hello-world' ),
                    'uppercase' => __( 'UPPERCASE', 'elementor-listingpro' ),
                    'lowercase' => __( 'lowercase', 'elementor-listingpro' ),
                    'capitalize' => __( 'Capitalize', 'elementor-listingpro' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'text-transform: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="about-box-container">
            <div class="lp-section-content-container clearfix">
                <?php
                if( $settings['content_boxes'] )
                {
                    foreach ( $settings['content_boxes'] as $item )
                    {
                        $box_settings   =   array(
                            'content_title' =>  $item['content_title'],
                            'content_desc' => $item['content_desc'],
                            'content_img' => $item['content_img'],
                            'icon_background_color' => $item['icon_background_color'],
                        );
                        echo elementor_shortcode_medicalpro_contentboxes( $box_settings );
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
    protected function content_template() {}
    public function render_plain_content() {}
}

if (!function_exists('elementor_shortcode_medicalpro_contentboxes')) {
    function elementor_shortcode_medicalpro_contentboxes($atts, $content = null) {
        extract(shortcode_atts(array(

            'content_title'   => 'PLANNING',
            'content_desc'   => 'Sed ut perspiciatis unde omnis iste natus error sit v oluptatem accusantium or sit v oluptatem accusantiumor sit v oluptatem',
            'content_img'   => plugin_dir_url( __FILE__ ) . "/assets/images/icons/con3.png",
            'icon_background_color'   => '#FEE0F6',
        ), $atts));

        $FimageURL=null;
        $con=null;
        if ( !empty($content_img )) {
            if( is_array( $content_img ) )
            {
                $content_img  =   $content_img['id'];
            }
            $imgurl = wp_get_attachment_image_src( $content_img, 'full');
            if($imgurl){
                $con = $imgurl[0];
            }else{
                $con = plugin_dir_url( __FILE__ ) . "/assets/images/icons/con3.png";
            }
        }
        $color = $icon_background_color;
        $rgb = medicalpro_hex2rgba2($color);
        $rgba = medicalpro_hex2rgba2($color, 0.9);
        $output = null;

        $output .= '<div class="col-md-4 col-sm-6 about-box about-box-style3">
						<div class="about-box-inner">
							<div class="about-box-slide">
								<div class="about-box-icon-style2" style="background:'.medicalpro_hex2rgba2($icon_background_color, 1). ';">
									<img src="'.$con.'" alt="" />
								</div>
								<div class="listingpro-columns-style2-content">
									<div class="about-box-title-style2 clearfix">
										<h4>'.$content_title.'</h4>
									</div>
									<div class="about-box-description-style2">
										<p class="paragraph-small">
											
											'.mb_substr($content_desc, 0, 90).'
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>';

        return $output;
    }
}