<?php
/*
Plugin Name: ListingPro Lead Form
Plugin URI:
Description: This plugin allows easy customization of Archive & Listing Pages.Also giving the ability to create custom Lead Forms. Only compatible With products By CridioStudio.
Version: 1.0.5
Author: CridioStudio (Dev Team)
Author URI: http://www.cridio.io
Author Email: support@cridio.com
Text Domain: listingpro-lead-form

  Copyright 2016 CridioStudio
*/


if (!defined('ABSPATH')) return;

if (!defined('PLUGIN_DIR_PATH')) define('PLUGIN_DIR_PATH', plugins_url('', __FILE__));

class Listingpro_lead_form{

}

function load_listingpro_visualizer_textdomain() {
    load_plugin_textdomain( 'listingpro-lead-form', FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'init', 'load_listingpro_visualizer_textdomain' );

$listingpro_customizer_options  =   get_option('listingpro_customizer_options');
global $listingpro_customizer_options;

function listingpro_customizer_admin_script( $hook )
{
    if( is_admin() && $hook == 'listingpro-cc_page_listingpro_visualizer_form_builder')
    {
        wp_enqueue_script('bootstrap', THEME_DIR . '/assets/lib/bootstrap/js/bootstrap.min.js', 'jquery', '', true);
        wp_enqueue_script( 'customizer-js-sortable', plugins_url( '/assets/js/sortable.js', __FILE__ ));
        wp_enqueue_script( 'customizer-js-ply', plugins_url( '/assets/js/ply.min.js', __FILE__ ));
        wp_enqueue_script( 'customizer-js-app', plugins_url( '/assets/js/app.js', __FILE__ ));
        wp_enqueue_script( 'customizer-js-front', plugins_url( '/assets/js/form-builder.js', __FILE__ ));
        wp_enqueue_script( 'customizer-js', plugins_url( '/assets/js/customizer.js', __FILE__ ));

        wp_enqueue_style('bootstrap', THEME_DIR . '/assets/lib/bootstrap/css/bootstrap.min.css');
        wp_enqueue_style('customizer-css', plugin_dir_url( dirname( __FILE__ ) ).'/listingpro-lead-form/assets/css/customizer.css', '', '1.0', 'all');
        wp_enqueue_style('customizer-ply-css', plugin_dir_url( dirname( __FILE__ ) ).'/listingpro-lead-form/assets/css/ply.css', '', '1.0', 'all');
    }
}
add_action( 'admin_enqueue_scripts', 'listingpro_customizer_admin_script' );


//enqueue script for frontend

add_action( 'wp_enqueue_scripts', 'listingpro_customizer_front_script' );

function listingpro_customizer_front_script( $hook )
{

    if( !is_admin() && isset( $_GET['dashboard'] ) && $_GET['dashboard'] == 'lead_form' )
    {
        wp_enqueue_script( 'customizer-js-sortable', plugins_url( '/assets/js/sortable.js', __FILE__ ), '', '', true);
        wp_enqueue_script( 'customizer-form-builder', plugins_url( '/assets/js/form-builder.js', __FILE__ ), '', '', true );
        wp_enqueue_script( 'customizer-js-front', plugins_url( '/assets/js/customizer-front.js', __FILE__ ), array('Main'), '', true );
    }

    wp_enqueue_style('bootstrap-datetimepicker-css', plugin_dir_url( dirname( __FILE__ ) ).'/listingpro-lead-form/assets/css/bootstrap-datetimepicker.min.css', '', '', 'all');
    wp_enqueue_script( 'bootstrap-moment', plugins_url( '/assets/js/moment.js', __FILE__ ), '', '', true );
    wp_enqueue_script( 'bootstrap-datetimepicker', plugins_url( '/assets/js/bootstrap-datetimepicker.min.js', __FILE__ ), '', '', true );
}

add_action('admin_menu', 'lp_customizer_pages', 30);
function lp_customizer_pages(){
    $lead_form_check    =   get_option('lead-form-active');
    if($lead_form_check == 'yes') {
        add_submenu_page('listingpro', __('Lead Form', 'listingpro-lead-form'), __('Lead Form', 'listingpro-lead-form'), 'manage_options', 'listingpro_visualizer_form_builder', 'listingpro_customizer_form_builder' );
    } else {
        add_submenu_page('listingpro', __('Lead Form', 'listingpro-lead-form'), __('Lead Form', 'listingpro-lead-form'), 'manage_options', 'lp-cc-visualizer', 'lp_cc_Visualizer' );
    }
}

if( !function_exists( 'listingpro_customizer_form_builder' ) )
{
    function listingpro_customizer_form_builder()
    {
        require_once(WP_PLUGIN_DIR . '/listingpro-lead-form/form-builder/lead_form.php');
    }
}

add_action('wp_ajax_enable_cusomizer', 'enable_cusomizer');
add_action('wp_ajax_nopriv_enable_cusomizer', 'enable_cusomizer');
if( !function_exists( 'enable_cusomizer' ) )
{
    function enable_cusomizer()
    {
        $enable_type    =   $_POST['enable_type'];
        $enable_data    =   $_POST['enable_data'];

        $listingpro_customizer_options  =   get_option( 'listingpro_customizer_options' );
        if( empty( $listingpro_customizer_options ) )
        {

            $listingpro_customizer_options  =   array(
                'form_builder' => array(
                    'active' => 0,
                    'active_template' => 'lead_form',
                    'lead_form_code' => '',
                )
            );
            if( $enable_type == 'form_builder' ) $listingpro_customizer_options['form_builder']['active'] =   $enable_data;
            update_option( 'listingpro_customizer_options', $listingpro_customizer_options );
        }
        else
        {
            if( $enable_type == 'form_builder' ) $listingpro_customizer_options['form_builder']['active'] =   $enable_data;
            update_option( 'listingpro_customizer_options', $listingpro_customizer_options );
        }
        die();
    }
}

add_action('wp_ajax_enable_user_cusomizer', 'enable_user_cusomizer');
add_action('wp_ajax_nopriv_enable_user_cusomizer', 'enable_user_cusomizer');
if( !function_exists( 'enable_user_cusomizer' ) )
{
    function enable_user_cusomizer()
    {
        $enable_data    =   $_POST['enable_data'];

        update_option( 'lead_form_user_dashboard', $enable_data );
        die();
    }
}

add_action('wp_ajax_save_cusomizer_template', 'save_cusomizer_template');
add_action('wp_ajax_nopriv_save_cusomizer_template', 'save_cusomizer_template');
if( !function_exists( 'save_cusomizer_template' ) )
{
    function save_cusomizer_template()
    {
        $template_code      =   wp_unslash( $_POST['template_code'] );
        $front_end          =   $_POST['front_end'];
        if( isset( $_POST['front_end'] ) && $_POST['front_end'] == 'yes' )
        {
            if( is_array( $_POST['listing_ID'] ) )
            {
                foreach ( $_POST['listing_ID'] as $listing_id )
                {
                    update_post_meta( $listing_id, 'lp_lead_form', $template_code );
                }
            }
            else
            {
                update_post_meta( $_POST['listing_ID'], 'lp_lead_form', $template_code );
            }

        }
        else
        {
            update_option('lead_form_admin', $template_code);
        }

        die();
    }
}

add_action('wp_ajax_remove_lead_form', 'remove_lead_form');
add_action('wp_ajax_nopriv_remove_lead_form', 'remove_lead_form');

if( !function_exists( 'remove_lead_form' ) )
{
    function remove_lead_form()
    {

        $PID    =   wp_unslash( $_POST['PID'] );
        $UID    =   $_POST['UID'];

        if( !empty( $PID ) )
        {
            delete_post_meta( $PID, 'lp_lead_form' );
        }
        die( json_encode( array( 'status' => 'success' ) ) );

    }
}

add_action('wp_ajax_reset_customizer', 'reset_customizer');
add_action('wp_ajax_nopriv_reset_customizer', 'reset_customizer');
if( !function_exists( 'reset_customizer' ) )
{
    function reset_customizer()
    {
        delete_option('lead_form_admin');
        die();
    }
}


add_action('wp_ajax_select2_ajax_listings', 'select2_ajax_listings');
add_action('wp_ajax_nopriv_select2_ajax_listings', 'select2_ajax_listings');
if( !function_exists( 'select2_ajax_listings' ) )
{
    function select2_ajax_listings()
    {
        $return = array();
        $search_results = new WP_Query( array(
            's'=> $_GET['q'], // the search query
            'post_status' => 'publish', // if you don't want drafts to be returned
            'ignore_sticky_posts' => 1,
            'posts_per_page' => 50, // how much to show at once
            'post_type' => 'listing'
        ) );

        if( $search_results->have_posts() ) :
            while( $search_results->have_posts() ) : $search_results->the_post();
                // shorten the title a little
                $title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
                $return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
            endwhile;
        endif;
        echo json_encode( $return );
        die;

    }
}


function form_fields_popup()
{
    ob_start();
    ?>
    <div class="modal fade lp-alerts-customizer" id="fieldModal" tabindex="-1" role="dialog" aria-labelledby="fieldModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id=""><?php echo esc_html( __('Add New Form Field','listingpro-lead-form')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="add-new-field-popup">
                        <div class="form-group">
                            <label for="field-required" class="lp-required-label"><?php echo esc_html( __('Required','listingpro-lead-form')); ?></label>
                            <input class="form-control" type="checkbox" id="field-required" name="field-required">
                        </div>
                        <div class="form-group">
                            <label for="field-name"><?php echo esc_html( __('Label','listingpro-lead-form')); ?></label>
                            <input class="form-control" type="text" id="field-label" name="field-label" value="">
                        </div>
                        <div class="form-group">
                            <label for="field-name"><?php echo esc_html( __('Name','listingpro-lead-form')); ?></label>
                            <input class="form-control" type="text" id="field-name" name="field-name" value="">
                        </div>
                        <div class="form-group">
                            <label for="field-type"><?php echo esc_html( __('Type','listingpro-lead-form')); ?></label>
                            <select id="field-type" name="field-type" class="form-control">
                                <option value="text"><?php echo esc_html( __('Text','listingpro-lead-form')); ?></option>
                                <option value="email"><?php echo esc_html( __('Email','listingpro-lead-form')); ?></option>
                                <option value="tel"><?php echo esc_html( __('Phone','listingpro-lead-form')); ?></option>
                                <option value="url"><?php echo esc_html( __('Url','listingpro-lead-form')); ?></option>
                                <option value="date"><?php echo esc_html( __('Date','listingpro-lead-form')); ?></option>
                                <option value="time"><?php echo esc_html( __('Time','listingpro-lead-form')); ?></option>
                                <option value="datetime-local"><?php echo esc_html( __('Date-Time Local','listingpro-lead-form')); ?></option>
                                <option value="radio"><?php echo esc_html( __('Radio','listingpro-lead-form')); ?></option>
                                <option value="checkbox"><?php echo esc_html( __('Checkbox','listingpro-lead-form')); ?></option>
                                <option value="dropdown"><?php echo esc_html( __('Dropdown','listingpro-lead-form')); ?></option>
                                <option value="range"><?php echo esc_html( __('Range','listingpro-lead-form')); ?></option>
                            </select>
                        </div>
                        <div class="form-group field-placeholder">
                            <label for="field-placeholder"><?php echo esc_html( __('Placeholder','listingpro-lead-form')); ?></label>
                            <input type="text" name="field-placeholder" class="form-control" id="field-placeholder">
                        </div>
                        <div class="form-group multiselect-field" style="display: none;">
                            <label for="field-multi"><?php echo esc_html( __('Multi Select','listingpro-lead-form')); ?></label>
                            <input type="checkbox" name="field-multi" id="field-multi">
                        </div>
                        <div class="form-group options-field" style="display: none;">
                            <label for="field-options"><?php echo esc_html( __('Options','listingpro-lead-form')); ?></label>
                            <textarea name="field-options" id="field-options" class="form-control"></textarea>
                            <small class="form-text text-muted"><?php echo esc_html( __('one per line in this format value|name','listingpro-lead-form')); ?></small>
                        </div>
                        <div class="form-group range-extra" style="display: none">
                            <label for="min-val"><?php echo esc_html( __('Minimum Value','listingpro-lead-form')); ?></label>
                            <input type="text" name="min-val" class="form-control" id="min-val">
                        </div>
                        <div class="form-group range-extra" style="display: none">
                            <label for="max-val"><?php echo esc_html( __('Maximum Value','listingpro-lead-form')); ?></label>
                            <input type="text" name="max-val" class="form-control" id="max-val">
                        </div>
                        <div class="form-group range-extra" style="display: none">
                            <label for="def-val"><?php echo esc_html( __('Default Value','listingpro-lead-form')); ?></label>
                            <input type="text" name="def-val" class="form-control" id="def-val">
                        </div>
                        <div class="form-group range-extra" style="display: none">
                            <label for="step-val"><?php echo esc_html( __('Step Value','listingpro-lead-form')); ?></label>
                            <input type="text" name="step-val" class="form-control" id="step-val">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary lp-btn-primary-cust" data-dismiss="modal"><?php echo esc_html( __('Cancel & Close','listingpro-lead-form')); ?></button>
                    <button type="button" class="btn btn-primary add-form-field" data-pid=""><?php echo esc_html( __('Save Field','listingpro-lead-form')); ?></button>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

if( !function_exists('popup_notification' ) )
{
    function popup_notification()
    {
        ob_start();
        ?>
        <!--Start delete and cancel popup-->
        <div class="modal fade lp-alerts-customizer" id="lp-el-notification" tabindex="-1" role="dialog" aria-labelledby="attributesModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content lp-delete-wrap">
                    <div class="lp-delete-box-text">
                        <p class="message-text"></p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary lp-btn-primary-cust" data-dismiss="modal"><?php echo esc_html( __('OK!','listingpro-lead-form')); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <!--End delete and cencel popup-->
        <?php
        return ob_get_clean();
    }
}

// Notice bar Saved Function
function lp_notice_bar_saved(){
    ?>
    <div class="lp-customizer-saved-notice" id="lp-notice-customizer">
        <strong><?php echo esc_html( __('Settings Saved!','listingpro-lead-form')); ?></strong>
    </div>
    <div class="lp-customizer-saved-notice lp-notice-wraning" id="lp-notice-customizer-warning">
        <strong><?php echo esc_html( __('Template has changed. You should save it!','listingpro-lead-form')); ?></strong>
    </div>
    <?php
}

//top bar save changes and reset
function lp_top_bar_save_reset(){
    ?>
    <div class="lp-save-reset-btn">
        <button class="lp-help-btn"><span>?</span> <?php echo esc_html( __('Help','listingpro-lead-form')); ?></button>
        <button class="lp-save-template lp-save-btns"><i class="fa fa-save"></i><?php echo esc_html( __('Save Changes','listingpro-lead-form')); ?></button>
        <button class="lp-reset-btns"><i class="fa fa-refresh"></i><?php echo esc_html( __('Reset','listingpro-lead-form')); ?></button>
    </div>
    <?php
}
include WP_PLUGIN_DIR . '/listingpro-lead-form/form-builder/form-field-functions.php';