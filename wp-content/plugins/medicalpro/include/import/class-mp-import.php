<?php
/**
 * MedicalPro Import Class
 *
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class mp_import {

	public function __construct() {
		add_action('admin_menu', array($this, 'mp_admin_menus'));
		add_action('wp_ajax_mp_import_content', array($this, 'mp_import_content_callback'));
		add_action('wp_ajax_mp_import_theme_options', array($this, 'mp_import_theme_options_callback'));
	}

	public function mp_admin_menus() {
		add_theme_page(esc_html__('Import MedicalPro', 'medicalpro'), esc_html__('Import MedicalPro', 'medicalpro'), 'manage_options', 'mp-setup', array(
			$this,
			'mp_import_demo_data',
		));
	}

	public function mp_import_demo_data() {
		?>
        <div class="mp-setup">
            <div class="mp-setup-heading">
                <h1>Let us make you feel Relax</h1>
                <h2>with dummy content</h2>
            </div>
            <div class="mp-setup-data-removal-toast">
                <div class="mp-setup-data-removal-toast-icon">
                    <img src="<?php echo MP_PLUGIN_DIR ?>assets/images/mp-setup-info.svg" alt="Info">
                </div>
                <div class="mp-setup-data-removal-toast-content">
                    <h4>Warning</h4>
                    <p>Please be sure if you go ahead your previous data will be overwritten</p>
                </div>
            </div>
            <div class="mp-setup-illustration">
                <img src="<?php echo MP_PLUGIN_DIR ?>assets/images/mp-setup.png" alt="illustration">
            </div>
            <div class="mp-setup-data-removal-toast mp-plugin-not-found">
                <div class="mp-setup-data-removal-toast-icon">
                    <img src="<?php echo MP_PLUGIN_DIR ?>assets/images/mp-setup-info.svg" alt="Info">
                </div>
                <div class="mp-setup-data-removal-toast-content">
                    <h4>Notice</h4>
                    <p>Please be sure that the selected page builder is installed and activated.</p>
                </div>
            </div>
			<?php if (ini_get('max_execution_time') < 300) { ?>
                <div class="mp-setup-data-removal-toast mp-server">
                    <div class="mp-setup-data-removal-toast-content">
                        <h4>Warning</h4>
                        <p>Please be sure that your server <code>max_execution_time</code> limit is more then
                            <code>300</code>. And worpress <code>WP_MAX_MEMORY_LIMIT</code> is more then
                            <code>512M</code></p>
                    </div>
                </div>
			<?php } ?>
            <form id="import_form" method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="mp_import_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>">
                <div class="mp-setup-form-group" title="Please Select A Page Builder To Get Started">
                    <div class="mp-setup-input-group">
                        <input value="wpbakery" type="radio" id="wpbakery" name="mp-page-builder" checked>
                        <label for="wpbakery">WP Bakery</label>
                    </div>
                    <div class="mp-setup-input-group">
                        <input value="elementor" type="radio" id="elementor" name="mp-page-builder">
                        <label for="elementor">Elementor</label>
                    </div>
                </div>
                <div class="mp-setup-progress-container">
                    <div id="import-content-response">
                        <span class="res-text">PlaceHolder</span>
                        <img class="loadinerSearch" width="30px"
                             src="<?php echo get_template_directory_uri(); ?>/assets/images/ajax-load.gif">
                        <img class="checkImg" width="30px"
                             src="<?php echo get_template_directory_uri(); ?>/assets/images/check-img.png">
                    </div>
                    <div id="import-themeoptions-response" class="clear pos-relative">
                        <span class="res-text">PlaceHolder</span>
                        <img class="loadinerSearch" width="30px"
                             src="<?php echo get_template_directory_uri(); ?>/assets/images/ajax-load.gif">
                        <img class="checkImg" width="30px"
                             src="<?php echo get_template_directory_uri(); ?>/assets/images/check-img.png">
                    </div>
                </div>
				<?php
				$class         = null;
				$medpro_status = get_option('lp-medpro');
				if ($medpro_status != 'active') {
					$class = 'no-license';
				}
				?>
                <div class="mp-setup-action">
                    <button href="javascript:void(0);" id="mp-import-content"
                            class="<?php echo $class; ?>"><?php esc_html_e("Import Content", "medicalpro"); ?></button>
                </div>
	            <?php
	            $theme = wp_get_theme();
	            $theme = $theme->get('Name');
	            ?>
                <p class="mp-import-success" style="display: none; text-align: center;">Head To <a href="<?php echo home_url(); ?>">Front Page</a>. You May Need To <a target="_blank" title="* Required" href="<?php menu_page_url( $theme, true ); ?>">Save Theme Option</a> Or Clear Cache In Case Of Design Issue.</p>
            </form>
        </div>
		<?php if ($medpro_status != 'active') { ?>
            <div class="mp-license-notactive-modal-container">
                <div class="mp-license-notactive-modal">
                    <div class="mp-license-notactive-modal-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30.098" height="33.253"
                             viewBox="0 0 30.098 33.253">
                            <defs><style>.a, .b {fill: #b46dd7;}.b {stroke: #b46dd7;}.c {fill: #2d3e50;stroke: #2d3e50;}</style></defs>
                            <g transform="translate(0.5 0.5)">
                                <path class="a"
                                      d="M84.671,63.033a6.351,6.351,0,1,0-10.554,4.757v7.19l3.962-2.661a.432.432,0,0,1,.481,0l3.962,2.661v-7.19A6.336,6.336,0,0,0,84.671,63.033ZM78.319,59.08a3.952,3.952,0,1,1-3.952,3.952A3.952,3.952,0,0,1,78.319,59.08Z"
                                      transform="translate(-55.072 -42.225)"/>
                                <path class="b" d="M41.84,27.7H30.605a.75.75,0,1,0,0,1.5H41.84a.75.75,0,0,0,0-1.5Z"
                                      transform="translate(-24.04 -20.873)"/>
                                <path class="b" d="M41.84,47.773H30.605a.75.75,0,1,0,0,1.5H41.84a.75.75,0,1,0,0-1.5Z"
                                      transform="translate(-24.04 -34.276)"/>
                                <path class="b" d="M36.153,67.848H30.605a.75.75,0,0,0,0,1.5h5.548a.75.75,0,0,0,0-1.5Z"
                                      transform="translate(-24.04 -47.68)"/>
                                <path class="c"
                                      d="M31,1.755H8.869A1.118,1.118,0,0,0,7.75,2.871V29.547a1.118,1.118,0,0,0,1.119,1.119h16.6V28.43H9.988V3.99H29.879V14.966a7.719,7.719,0,0,1,2.238,0V2.871A1.118,1.118,0,0,0,31,1.755Z"
                                      transform="translate(-7.75 -1.755)"/>
                            </g>
                        </svg>
                    </div>
                    <h1>License Activated ?</h1>
                    <p>Please be sure before importing demo content that your license is activated?</p>
                    <div class="mp-license-notactive-modal-icon-action">
                        <button class="activate-medpro-addon">Activate Now</button>
                        <a href="https://help.listingprowp.com" target="_blank">
                            <button>Get Help</button>
                        </a>
                    </div>
                </div>
            </div>
			<?php
		}
	}

	public function mp_import_content_callback() {

		if ( ! is_user_logged_in()) {
			wp_die(esc_html__("Sorry! You can't import data without login.", "medicalpro"));
		} else if ( ! is_admin()) {
			wp_die(esc_html__("Sorry, You are not allowed to access this page", "medicalpro"));
		}

		$medpro_status = get_option('lp-medpro');
		if ($medpro_status != 'active') {
			wp_die(esc_html__("Sorry, Your MedicalPro License Is Not Activated.", "medicalpro"));
		}

		$builder = null;
		if (isset($_POST['mp-page-builder']) && ! empty($_POST['mp-page-builder'])) {
			$builder = $_POST['mp-page-builder'];
		} else {
			wp_die(esc_html__("Error! Please Select One Form Builder To Get Started.", "medicalpro"));
		}

		if ( !is_plugin_active( 'js_composer/js_composer.php' ) && $builder == 'wpbakery' ) {
			$array_with_values = array( 'type'=> 'error' , 'msg'=>esc_html__("Error!, Please install and activate this plugin before running Wizard,", "blackpro") );
			wp_send_json($array_with_values);
			
		}
		
		if ( !is_plugin_active( 'elementor/elementor.php' ) && $builder == 'elementor' ) {
			$array_with_values = array( 'type'=> 'error' , 'msg'=> esc_html__("Error!, Please install and activate this plugin before running Wizard,", "blackpro") );
			wp_send_json($array_with_values);
		}

		if (isset($_POST['mp_import_nonce']) && wp_verify_nonce($_POST['mp_import_nonce'], basename(__FILE__))) {

			if ($builder == 'wpbakery') {
				$file = MP_PLUGIN_PATH . '/include/import/content/wp_bakery_content.xml';
			} else if ($builder == 'elementor') {
				$file = MP_PLUGIN_PATH . '/include/import/content/elementor_content.xml';
			} else {
				$file = '';
				wp_die(esc_html__("Error! Please Select One Form Builder To Get Started.", "medicalpro"));
			}

			if ( ! defined('WP_LOAD_IMPORTERS')) {
				define('WP_LOAD_IMPORTERS', true);
			}

			$importer_error = false;
			if ( ! class_exists('WP_Importer')) {
				$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
				if (file_exists($class_wp_importer)) {
					require_once($class_wp_importer);
				} else {
					$importer_error = true;
				}
			}

			if ( ! class_exists('WP_Import')) {
				$class_wp_import = get_template_directory() . '/include/setup/importer/importer/wordpress-importer.php';
				if (file_exists($class_wp_import)) {
					require_once($class_wp_import);
				} else {
					$importer_error = true;
				}
			}

			if ($importer_error) {
				ob_start();
				$msg = esc_html__("Error on import", "medicalpro");
				$msg = ob_get_contents();
				ob_end_clean();
			} else {
				if ( ! is_file($file)) {
					ob_start();
					$msg = esc_html__("Something went wrong", "medicalpro");
					$msg = ob_get_contents();
					ob_end_clean();
				} else {

					$post_types = array('page', 'nav_menu_item');
					foreach (
						get_post_types(array(
							'_builtin'   => false,
							'can_export' => true
						), 'objects') as $post_type
					) {
						$post_types[] = $post_type->name;
					}
					if (isset($post_types)) {
						foreach ($post_types as $post_type) {
							$this->mp_delete_custom_posts($post_type);
						}
					}

					$taxonomies = array(
						'listing-category',
						'features',
						'list-tags',
						'location',
						'medicalpro-hospital',
						'medicalpro-insurance',
						'medicalpro-award'
					);
					if (isset($taxonomies) && ! empty($taxonomies)) {
						foreach ($taxonomies as $taxonomy) {
							$this->mp_delete_terms($taxonomy);
						}
					}

					$wp_import                    = new WP_Import();
					$wp_import->fetch_attachments = true;
					ob_start();
					$res = $wp_import->import($file);
					$res = ob_get_contents();
					ob_end_clean();
					$msg = esc_html__("Content imported successfully", "medicalpro");

					$home_page_menu   = get_term_by('name', 'Home Page Menu', 'nav_menu');
					$home_footer_menu = get_term_by('name', 'Home Footer', 'nav_menu');
					set_theme_mod('nav_menu_locations', array(
						'primary'       => $home_page_menu->term_id,
						'primary_inner' => $home_page_menu->term_id,
						'footer_menu'   => $home_footer_menu->term_id,
					));

					$homepage = get_page_by_title('Home');
					if ($homepage != null) {
						$homepageID = $homepage->ID;
						update_option('show_on_front', 'page');
						update_option('page_on_front', $homepageID);
						update_option('page_for_posts', 35);
					}

				}
			}
		} else {
			$msg = esc_html__("Something went wrong", "medicalpro");
		}

		wp_die($msg);
	}

	private function mp_delete_custom_posts($post_type = 'page') {
		global $wpdb;

		$result = $wpdb->query($wpdb->prepare("
                DELETE posts,pt,pm
                FROM " . $wpdb->prefix . "posts posts
                LEFT JOIN " . $wpdb->prefix . "term_relationships pt ON pt.object_id = posts.ID
                LEFT JOIN " . $wpdb->prefix . "postmeta pm ON pm.post_id = posts.ID
                WHERE posts.post_type = %s
                ", $post_type));

		return $result !== false;
	}

	private function mp_delete_terms($taxonomy) {
		if (is_admin()) {
			$terms = get_terms($taxonomy, array('fields' => 'ids', 'hide_empty' => false));
			foreach ($terms as $value) {
				wp_delete_term($value, $taxonomy);
			}
		}
	}

	public function mp_import_theme_options_callback() {
		global $wp_filesystem;

		if ( ! is_user_logged_in()) {
			wp_die(esc_html__("Sorry! You can't import data without login.", "medicalpro"));
		} else if ( ! is_admin()) {
			wp_die(esc_html__("Sorry, You are not allowed to access this page", "medicalpro"));
		}

		$medpro_status = get_option('lp-medpro');
		if ($medpro_status != 'active') {
			wp_die(esc_html__("Sorry, Your MedicalPro License Is Not Activated.", "medicalpro"));
		}

		if (isset($_POST['mp_import_nonce']) && wp_verify_nonce($_POST['mp_import_nonce'], basename(__FILE__))) {
			$file = MP_PLUGIN_PATH . '/include/import/content/mp_themeOptions.json';
			if (file_exists($file)) {

				if (empty($wp_filesystem)) {
					require_once(ABSPATH . '/wp-admin/includes/file.php');
					WP_Filesystem();
				}
				$data = $wp_filesystem->get_contents($file);
				$data = json_decode($data, true);

				if (is_array($data) && ! empty($data)) {
					$data = apply_filters('medicalpro_import_theme_options', $data);
					update_option('listingpro_options', $data);
					wp_die(esc_html__("Theme options imported successfully", "medicalpro"));
				} else {
					wp_die(esc_html__("Error in theme option", "medicalpro"));
				}
			} else {
				wp_die(esc_html__("Error in theme option", "medicalpro"));
			}

		} else {
			wp_die(esc_html__("Something went wrong", "medicalpro"));
		}
	}

}

new mp_import();