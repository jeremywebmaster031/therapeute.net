<?php
/*
  Plugin Name: MedicalPro
  Plugin URI: https://listingprowp.com/downloads/medicalpro/
  Description: This plugin Only compatible With <a href="https://listingprowp.com">listingpro</a> Theme By CridioStudio.
  Version: 1.4
  Author: CridioStudio (Dev Team)
  Author URI: http://www.cridio.com
  Author Email: support@cridio.com
  Copyright 2021 CridioStudio
 */

if (!defined('ABSPATH')) {
    exit;
}
define('MEDICALPRO_PLUGIN_PATH', plugin_dir_path(__FILE__));
add_action('wp_enqueue_scripts', 'LP_dynamic_php_css_enqueue', 11);
if (!function_exists('LP_dynamic_php_css_enqueue')) {
    function LP_dynamic_php_css_enqueue()
    {
        wp_enqueue_style('LP_dynamic_php_css', get_template_directory_uri() . '/assets/css/dynamic-css.php', '');
    }
}
function medicalpro_load_textdomain()
{
    load_plugin_textdomain('medicalpro', false, basename(dirname(__FILE__)) . '/languages');
}
add_action('init', 'medicalpro_load_textdomain');

class MedicalPro
{
    public function __construct()
    {
        $medpro_status   =   get_option('lp-medpro');
        if ($medpro_status != 'active') {
            add_action('admin_notices', array($this, 'lp_activate_medpro'));
        } else {
            add_action('admin_notices', array($this, 'medicalpro_feature_request'));
        }
        add_action('admin_post_activate_medpro', array($this, 'activate_medpro_cb'));
        add_action('admin_post_nopriv_activate_medpro', array($this, 'activate_medpro_cb'));

        define('MP_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('MP_PLUGIN_DIR', plugin_dir_url(__FILE__));
        add_action('admin_enqueue_scripts', array($this, 'medicalpro_admin_enqueue_scripts'), 11);
        add_action('wp_enqueue_scripts', array($this, 'medicalpro_frontend_enqueue_scripts'), 11);
        add_filter('template_include', array($this, 'medicalpro_redirect_plugin_listing_template'), 11, 1);
        add_action('init', array($this, 'medicalpro_register_taxonomies'), 11);
        add_action('init', array($this, 'medicalpro_register_vc_shortcodes'), 12);

        // add_action('init', array($this, 'mp_setup_redirect'), 10);

        $this->medicalpro_includes();
    }

    function medicalpro_feature_request()
    {
?>
        <script type="text/javascript">
            window.$sleek = [];
            window.SLEEK_PRODUCT_ID = 24828331;
            (function() {
                d = document;
                s = d.createElement("script");
                s.src = "https://client.sleekplan.com/sdk/e.js";
                s.async = 1;
                d.getElementsByTagName("head")[0].appendChild(s);
            })();
        </script>
    <?php
    }

    //  function mp_setup_redirect() {
    //      $redirect = get_option( 'mp_setup_redirect' );
    //      if ( empty($redirect) || !$redirect ) {
    //          update_option( 'mp_setup_redirect', true );
    //          wp_safe_redirect( admin_url( 'admin.php?page=' . rawurlencode( 'mp-setup' ) ) );
    //      }
    //  }

    public function medicalpro_admin_enqueue_scripts()
    {
        global $listingpro_options;

        wp_enqueue_style('medicalpro-admin-style', MP_PLUGIN_DIR . 'assets/css/admin-style.css');
        wp_enqueue_style('medicalpro-import', MP_PLUGIN_DIR . 'assets/css/mp-import.css');

        $mapAPI = $listingpro_options['google_map_api'];
        if (empty($mapAPI)) {
            $mapAPI = 'AIzaSyDQIbsz2wFeL42Dp9KaL4o4cJKJu4r8Tvg';
        }
        wp_enqueue_script('mapsjs', 'https://maps.googleapis.com/maps/api/js?key=' . $mapAPI . '&libraries=places', 'jquery', '', false);
        wp_enqueue_script('medicalpro-admin-functions', MP_PLUGIN_DIR . 'assets/js/admin-functions.js');
        wp_localize_script('medicalpro-admin-functions', 'ajax_search_term_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));
        wp_enqueue_script('medicalpro-import', MP_PLUGIN_DIR . 'assets/js/mp-import.js');
        wp_localize_script('medicalpro-import', 'mp_import', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));
    }

    public function medicalpro_frontend_enqueue_scripts()
    {

        if (is_singular('listing') || isset($_GET['dashboard']) && ($_GET['dashboard'] == 'manage-booking' || $_GET['dashboard'] == 'my-bookings')) {

            $lp_wp_lang    =   get_option('WPLANG');
            $available_locales  =   array(
                'de_DE',
                'af',
                'ar_DZ',
                'ar',
                'az',
                'be',
                'bg',
                'cs',
                'bs',
                'ca',
                'cy_GB',
                'da',
                'de',
                'el',
                'en_AU',
                'en_GB',
                'en_NZ',
                'eo',
                'es',
                'et',
                'eu',
                'fa',
                'fi',
                'fo',
                'fr_CA',
                'fr_CH',
                'fr',
                'gl',
                'he',
                'hi',
                'hr',
                'hu',
                'hy',
                'id',
                'is',
                'it_CH',
                'it',
                'js',
                'ka',
                'kk',
                'km',
                'ko',
                'ky',
                'lb',
                'lt',
                'lv',
                'mk',
                'ml',
                'ms',
                'nb',
                'nl_BE',
                'nl',
                'nn',
                'no',
                'pl',
                'pt',
                'pt_BR',
                'rm',
                'ro',
                'ru',
                'sk',
                'sl',
                'sq',
                'sr',
                'sr_SR',
                'sv',
                'ta',
                'tj',
                'th',
                'tr',
                'uk',
                'vi',
                'zh_CN',
                'zh_HK',
                'zh_TW'
            );
            if (!empty($lp_wp_lang) && in_array($lp_wp_lang, $available_locales)) {
                wp_register_script('datelocale', 'https://sandbox.listingprowp.com/datepicker-locales/datepicker-' . $lp_wp_lang . '.js', array('jquery-ui'));
            }
            wp_enqueue_script('datelocale');
        }

        wp_enqueue_style('mp_dynamic_php_css', MP_PLUGIN_DIR . 'assets/css/dynamic-css.php');

        wp_enqueue_style('medicalpro-style', MP_PLUGIN_DIR . 'assets/css/style.css');
        wp_enqueue_style('medicalpro-booking', MP_PLUGIN_DIR . 'assets/css/booking.css');

        wp_register_script('medicalpro-fixed-sidebar-lib', MP_PLUGIN_DIR . 'assets/js/jquery.sticky-kit.js');

        wp_enqueue_script('medicalpro-script', MP_PLUGIN_DIR . 'assets/js/script.js');
        wp_enqueue_script('medicalpro-booking', MP_PLUGIN_DIR . 'assets/js/booking.js');

        wp_dequeue_script('Main');
        wp_enqueue_script('medicalpro-main', MP_PLUGIN_DIR . 'assets/js/main.js');

        wp_dequeue_script('search-ajax-script');
        wp_enqueue_script('medicalpro-search-ajax-script', MP_PLUGIN_DIR . 'assets/js/search-ajax.js');
        wp_localize_script('medicalpro-search-ajax-script', 'ajax_search_term_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'empty_fields_error' => esc_html__('Please fill all required fields', 'medicalpro'),
            'duplicate_hospital' => esc_html__('Sorry! Hospital is already added.', 'medicalpro')
        ));
    }

    public function medicalpro_redirect_plugin_listing_template($template)
    {
        if (is_singular('listing')) :
            $template = MP_PLUGIN_PATH . 'templates/single-listing.php';
        endif;
        if (is_tax('medicalpro-hospital')) :
            $template = MP_PLUGIN_PATH . 'templates/archive-hospital.php';
        endif;
        if (is_search() || is_post_type_archive('listing') || is_tax('listing-category') || is_tax('location') || is_tax('features') || is_tax('list-tags')) :
            $template = MP_PLUGIN_PATH . 'templates/listing-with-sidebar-filters.php';
        endif;
        if (is_author()) :
            $template = MP_PLUGIN_PATH . 'templates/author.php';
        endif;

        return $template;
    }

    public function medicalpro_register_taxonomies()
    {
        $labels = array(
            'name'              => __('Hospitals', 'taxonomy general name', 'medicalpro'),
            'singular_name'     => __('Hospital', 'taxonomy singular name', 'medicalpro'),
            'search_items'      => __('Search Hospitals', 'medicalpro'),
            'all_items'         => __('All Hospitals', 'medicalpro'),
            'parent_item'       => __('Parent Hospitals', 'medicalpro'),
            'parent_item_colon' => __('Parent Hospitals:', 'medicalpro'),
            'edit_item'         => __('Edit Hospital', 'medicalpro'),
            'update_item'       => __('Update Hospital', 'medicalpro'),
            'add_new_item'      => __('Add New Hospital', 'medicalpro'),
            'new_item_name'     => __('New Hospital Name', 'medicalpro'),
            'menu_name'         => __('Hospitals', 'medicalpro'),
        );
        register_taxonomy(
            'medicalpro-hospital',
            'listing',
            array(
                'hierarchical' => false,
                'labels' => $labels,
                'singular_name'    => __('Hospital', 'medicalpro'),
                'show_ui'          => true,
                'rewrite'          => true,
                'query_var'        => true,
                'public'           => true,
                'show_in_rest'     => true,
            )
        );
        $labels = array(
            'name'              => __('Insurance', 'taxonomy general name', 'medicalpro'),
            'singular_name'     => __('Insurance', 'taxonomy singular name', 'medicalpro'),
            'search_items'      => __('Search Insurance', 'medicalpro'),
            'all_items'         => __('All Insurances', 'medicalpro'),
            'parent_item'       => __('Parent Insurance', 'medicalpro'),
            'parent_item_colon' => __('Parent Insurance:', 'medicalpro'),
            'edit_item'         => __('Edit Insurance', 'medicalpro'),
            'update_item'       => __('Update Insurance', 'medicalpro'),
            'add_new_item'      => __('Add New Insurance', 'medicalpro'),
            'new_item_name'     => __('New Insurance Name', 'medicalpro'),
            'menu_name'         => __('Insurance', 'medicalpro'),
        );
        register_taxonomy(
            'medicalpro-insurance',
            'listing',
            array(
                'hierarchical' => false,
                'labels' => $labels,
                'singular_name'  => "Insurance",
                'show_ui'        => true,
                'rewrite'        => true,
                'query_var'      => true,
                'public'         => true,
                'show_in_rest'   => true,
            )
        );
        $labels = array(
            'name'              => __('Awards', 'taxonomy general name', 'medicalpro'),
            'singular_name'     => __('Award', 'taxonomy singular name', 'medicalpro'),
            'search_items'      => __('Search Awards', 'medicalpro'),
            'all_items'         => __('All Awards', 'medicalpro'),
            'parent_item'       => __('Parent Awards', 'medicalpro'),
            'parent_item_colon' => __('Parent Awards:', 'medicalpro'),
            'edit_item'         => __('Edit Award', 'medicalpro'),
            'update_item'       => __('Update Award', 'medicalpro'),
            'add_new_item'      => __('Add New Award', 'medicalpro'),
            'new_item_name'     => __('New Award Name', 'medicalpro'),
            'menu_name'         => __('Awards', 'medicalpro'),
        );
        register_taxonomy(
            'medicalpro-award',
            'listing',
            array(
                'hierarchical'  => false,
                'labels' => $labels,
                'singular_name' => "Award",
                'show_ui'       => true,
                'rewrite'       => true,
                'query_var'     => true,
                'public'        => true,
                'show_in_rest'  => true,
            )
        );
    }

    public function medicalpro_register_vc_shortcodes()
    {
        if (class_exists('WPBakeryVisualComposerAbstract')) {
            include_once(MP_PLUGIN_PATH . '/shortcodes/vc-shortcodes/vc_special_elements.php');
            require_once(MP_PLUGIN_PATH . '/shortcodes/vc-shortcodes/submit.php');
            require_once(MP_PLUGIN_PATH . '/shortcodes/vc-shortcodes/edit.php');
        }
    }

    public function medicalpro_includes()
    {
        require_once MP_PLUGIN_PATH . "/include/functions.php";
        require_once MP_PLUGIN_PATH . "/include/filter-functions.php";
        require_once MP_PLUGIN_PATH . "/include/options-config.php";
        require_once MP_PLUGIN_PATH . "/include/ajax-hooks.php";
        require_once MP_PLUGIN_PATH . "/include/invoices/invoice-functions.php";
        require_once MP_PLUGIN_PATH . "/include/withdrawals/withdrawals.php";
        if (!class_exists('WP_List_Table')) {
            require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
        }
        require_once MP_PLUGIN_PATH . "/include/earnings/earnings.php";
        require_once MP_PLUGIN_PATH . "/include/earnings/earnings-table.php";
        require_once MP_PLUGIN_PATH . "/include/reviews/reviews-form.php";
        require_once MP_PLUGIN_PATH . "/include/reviews/all-reviews.php";
        require_once MP_PLUGIN_PATH . "/include/reviews/review-submit.php";
        require_once MP_PLUGIN_PATH . "/include/taxonomies-meta/hospitals.php";
        require_once MP_PLUGIN_PATH . "/include/taxonomies-meta/insurance.php";
        require_once MP_PLUGIN_PATH . "/include/taxonomies-meta/awards.php";
        require_once MP_PLUGIN_PATH . "/include/hospital-business-hours.php";
        require_once MP_PLUGIN_PATH . "/include/bookings/booking-checkout.php";
        require_once MP_PLUGIN_PATH . "/include/bookings/booking-functions.php";
        require_once MP_PLUGIN_PATH . "/include/bookings/save-booking-order.php";
        require_once(MP_PLUGIN_PATH . '/include/submit-ajax.php');
        require_once MP_PLUGIN_PATH . "/include/import/class-mp-import.php";
    }


    public function lp_activate_medpro()
    {
    ?>
        <script>
            jQuery(document).ready(function() {
                jQuery('.colose-activation-popup').click(function(e) {
                    e.preventDefault();
                    jQuery('.medpro-activation-wrap').fadeOut();
                });
                jQuery('.activate-medpro-addon').click(function(e) {
                    e.preventDefault();
                    jQuery('.medpro-activation-wrap').fadeIn();
                });
            });
            jQuery(document).on('keyup', ".key-enter-bar-medpro", function() {
                var clenght = jQuery('.key-enter-bar-medpro').val();
                if (clenght.length >= 32) {
                    jQuery(".button-success").removeAttr("disabled");
                } else {
                    jQuery(".button-success").attr("disabled", "");
                }
            });
        </script>
        <div class="medpro-activation-wrap">
            <div class="medpro-activation-wrap-inner">
                <div class="e-blaster-activation license-verification-form">
                    <div class="colored-top-bar"></div>
                    <div class="license-verification-form-header">
                        <span class="colose-activation-popup">x</span>
                        <span class="license-verification-form-header-title">MedicalPro License Activation</span>
                    </div>
                    <div class="license-verification-form-box-content">
                        <p class="license-verification-form-content-box-des"><?php esc_html_e('When you purchase a single license of', 'medicalpro'); ?> <a href="" target="_blank"><?php esc_html_e('MedicalPro', 'medicalpro'); ?></a>, <?php esc_html_e('you are allowed to use the plugin on one single finished directory site.', 'medicalpro'); ?></p>
                    </div>
                    <form class="license-verification-form-container" id="activate-medpro" action="<?php echo esc_attr('admin-post.php'); ?>" method="post">
                        <input type="hidden" name="action" value="activate_medpro">
                        <input type="hidden" name="item_id" value="15762">
                        <span style="position: relative;">
                            <span class="input-caption-left"><?php esc_html_e('ENTER YOUR ITEM PURCHASE CODE (KEY)', 'medicalpro'); ?></span>
                            <input id="license_key" class="key-enter-bar-medpro" required="" placeholder="E.G : XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" name="license_key" maxlength="32" autocomplete="off" type="text">
                        </span>
                        <?php echo wp_nonce_field('medpro_nonce', 'medpro_nonce_field', true, false); ?>
                        <input type="submit" name="submit" disabled class="button button-success button-hero" value="<?php esc_html_e('Activate', 'medicalpro'); ?>">
                    </form>
                </div>
            </div>
        </div>
        <?php if (!isset($_GET['license-res'])) { ?>
            <div class="notice notice-warning bg-red">
                <p><strong><?php esc_html_e('MedicalPro', 'medicalpro'); ?></strong></p> <?php esc_html_e('is currently inactive.', 'medicalpro'); ?>
                <a href="#" class="activate-medpro-addon"><?php esc_html_e('Activate', 'medicalpro'); ?></a> <?php esc_html_e('your license key or get one', 'medicalpro'); ?> <a target="_blank" href="https://listingprowp.com/plugins/medpro"><?php esc_html_e('here', 'medicalpro'); ?></a>.
            </div><?php
                } else { ?>
            <div class="notice notice-warning bg-red">
                <p><strong><?php esc_html_e('MedicalPro', 'medicalpro'); ?></strong></p> <?php esc_html_e('wrong purchase code.', 'medicalpro'); ?>
                <a href="#" class="activate-medpro-addon"><?php esc_html_e('Try Again', 'medicalpro'); ?></a> <?php esc_html_e('your license key or get one', 'medicalpro'); ?> <a target="_blank" href="https://listingprowp.com/plugins/medpro"><?php esc_html_e('here', 'medicalpro'); ?></a>.
            </div><?php
                }
            }

            public function activate_medpro_cb()
            {
                if (isset($_POST['medpro_nonce_field']) &&  wp_verify_nonce($_POST['medpro_nonce_field'], 'medpro_nonce') && !empty($_POST['license_key'])) {
                    $product_site   =   'https://listingprowp.com/';
                    $action         =   '?edd_action=activate_license';
                    $item_id        =   '&item_id=15762';
                    $license        =   '&license=' . sanitize_text_field($_POST['license_key']);
                    $site_url       =   '&url=' . urlencode(get_site_url());
                    $api_url        =   $product_site . $action . $item_id . $license . $site_url;

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_URL, $api_url);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

                    $response = curl_exec($ch);
                    curl_close($ch);

                    $response = json_decode($response);
                    $redirect_url = null;
                    if ($response->license == 'valid') {
                        update_option('lp-medpro', 'active');
                        $redirect_url   =   admin_url();
                        $redirect_url   .=   'admin.php?page=mp-setup&license-res=success';
                    }
                    if ($response->license == 'invalid') {
                        $redirect_url   =   admin_url();
                        $redirect_url   .=   '?license-res=failed';
                    }
                    wp_redirect($redirect_url);
                }
            }
        }
        $verification_check = get_option('theme_activation');
        if ($verification_check == 'activated') {
            new MedicalPro();
        } else {
            if (!function_exists('ep_theme_license_check')) {
                function ep_theme_license_check()
                {
                    ?>
            <div class="notice notice-error">
                <p><b><?php _e('MedicalPro!', 'medicalpro'); ?></b></p>
                <p><?php _e('Please Check If Listingpro Plugin And Theme License Is Activated Correctly.', 'medicalpro'); ?></p>
                <a type="button" class="button button-primary" href="<?php menu_page_url('lp-cc-license', true); ?>"><?php _e('Activate Now', 'medicalpro'); ?></a>
            </div>
<?php
                }
            }
            add_action('admin_notices', 'ep_theme_license_check');
        }


        //elementor initialize class
        final class Elementor_MedicalPro
        {
            const VERSION = '1.2.0';
            const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
            const MINIMUM_PHP_VERSION = '7.0';
            public function __construct()
            {
                // Load translation
                add_action('init', array($this, 'i18n'));
                // Init Plugin
                add_action('plugins_loaded', array($this, 'init'));
            }

            public function i18n()
            {
                load_plugin_textdomain('elementor-hello-world');
            }
            public function init()
            {
                // Check if Elementor installed and activated
                if (!did_action('elementor/loaded')) {
                    add_action('admin_notices', array($this, 'admin_notice_missing_main_plugin'));
                    return;
                }
                // Check for required Elementor version
                if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
                    add_action('admin_notices', array($this, 'admin_notice_minimum_elementor_version'));
                    return;
                }
                // Check for required PHP version
                if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
                    add_action('admin_notices', array($this, 'admin_notice_minimum_php_version'));
                    return;
                }
                // Once we get here, We have passed all validation checks so we can safely include our plugin
                require_once(MP_PLUGIN_PATH . '/shortcodes/elementor-shortcodes/elementor_special_elements.php');
            }
            public function admin_notice_missing_main_plugin()
            {
                if (isset($_GET['activate'])) {
                    unset($_GET['activate']);
                }
                $message = sprintf(
                    /* translators: 1: Plugin name 2: Elementor */
                    esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-hello-world'),
                    '<strong>' . esc_html__('Elementor Hello World', 'elementor-hello-world') . '</strong>',
                    '<strong>' . esc_html__('Elementor', 'elementor-hello-world') . '</strong>'
                );
                //printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
            }
            public function admin_notice_minimum_elementor_version()
            {
                if (isset($_GET['activate'])) {
                    unset($_GET['activate']);
                }
                $message = sprintf(
                    /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
                    esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'medicalpro'),
                    '<strong>' . esc_html__('Elementor Listingpro', 'medicalpro') . '</strong>',
                    '<strong>' . esc_html__('Elementor', 'medicalpro') . '</strong>',
                    self::MINIMUM_ELEMENTOR_VERSION
                );
                printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
            }
            public function admin_notice_minimum_php_version()
            {
                if (isset($_GET['activate'])) {
                    unset($_GET['activate']);
                }
                $message = sprintf(
                    /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
                    esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'medicalpro'),
                    '<strong>' . esc_html__('Elementor Listingpro', 'medicalpro') . '</strong>',
                    '<strong>' . esc_html__('PHP', 'medicalpro') . '</strong>',
                    self::MINIMUM_PHP_VERSION
                );
                printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
            }
        }
        ob_start();
        new Elementor_MedicalPro();
