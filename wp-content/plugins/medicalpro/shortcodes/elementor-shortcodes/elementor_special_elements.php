<?php

namespace ElementorMedicalPro;

class Plugin {

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private function include_widgets_files() {
        require_once(MP_PLUGIN_PATH . '/shortcodes/elementor-shortcodes/submit-listing.php');
        require_once(MP_PLUGIN_PATH . '/shortcodes/elementor-shortcodes/edit-listing.php');
        require_once(MP_PLUGIN_PATH . '/shortcodes/elementor-shortcodes/medicalpro_partners.php');
        require_once(MP_PLUGIN_PATH . '/shortcodes/elementor-shortcodes/medicalpro_listings.php');
        require_once(MP_PLUGIN_PATH . '/shortcodes/elementor-shortcodes/medicalpro_cat_loc.php');
        require_once(MP_PLUGIN_PATH . '/shortcodes/elementor-shortcodes/medicalpro_contentboxes.php');
        require_once(MP_PLUGIN_PATH . '/shortcodes/elementor-shortcodes/medicalpro_activities.php');
        require_once(MP_PLUGIN_PATH . '/shortcodes/elementor-shortcodes/medicalpro_columns.php');
        require_once(MP_PLUGIN_PATH . '/shortcodes/elementor-shortcodes/medicalpro_facts.php');
    }
    public function register_widgets() {
        // Its is now safe to include Widgets files
        $this->include_widgets_files();
        // Register Widgets
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\MedicalPro_Submit_Listing() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\MedicalPro_Edit_Listing() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\MedicalPro_partners() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\MedicalPro_listings() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\MedicalPro_CatLoc() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\MedicalPro_contentboxes() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\MedicalPro_Activities() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\MedicalPro_Columns() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\MedicalPro_Facts() );
    }
    public function add_elementor_medicalpro_widget_category( $elements_manager ) {
        $elements_manager->add_category(
            'medicalpro',
            [
                'title' => __( 'MedicalPro', 'elementor-medicalpro' ),
                'icon' => 'fa fa-plug',
            ]
        );
    }
    public function __construct() {
        // Register categories
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_medicalpro_widget_category'] );

        // Register widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
    }
}
// Instantiate Plugin Class
Plugin::instance();