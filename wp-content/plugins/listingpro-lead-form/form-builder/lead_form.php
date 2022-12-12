<div class="wrap lp-customizer-wrap">
    <input type="hidden" value="<?php echo admin_url('admin-ajax.php'); ?>" id="lead-ajax-url">
    <?php
    include WP_PLUGIN_DIR . '/listingpro-lead-form/customizer-popups.php';
    ?>
    <div class="lp-customizer-outer-wrap active">
        <!--Start fixed top bar for customized elements and save button-->
        <div class="lp-fixed-customized-topbar">
            <!--Start Fixed selection bar-->
            <div class="lp-customzier-topbar-title"><?php echo esc_html( __('Lead Form','listingpro-lead-form')); ?> <span><?php echo esc_html( __('1.0','listingpro-lead-form')); ?></span></div>
            <!--End Fixed selection bar-->
            <?php echo lp_top_bar_save_reset(); ?>
        </div>

        <div class="lp-template-selection">
            <input type="hidden" id="lp-active-template" value="<?php echo $active_template; ?>">
            <input type="hidden" id="lp-template-type" value="form_builder">
        </div>
        <div class="template-customizer-wrap">
            <div class="template-customizer-wrap-inner wrap-lead_form active-style">
                <div class="lp-composer-wrap-outer">
                    <div class="lp-composer-wrap" id="lp-lead-form-outer-lead_form">
                        <input type="hidden" class="lp-composer-result-lead_form" value="lead_form">
                        <?php
                        $lead_form_admin    =   get_option('lead_form_admin');
                        //delete icon
                        $lp_el_remove = '<span class="lp-el-remove"><i class="fa fa-trash-o"></i></span>';
                        ?>
                        <?php echo popup_notification(); ?>
                        <div class="row">
                            <div class="lp-dashboard-switch-with-image">
                                <div class="lp-activate-compser-user">
                                    <div class="col-md-5">
                                        <?php
                                        $lead_form_user_dashboard =    get_option('lead_form_user_dashboard');
                                        ?>
                                        <table class="form-table">
                                            <tbody>
                                            <tr>
                                                <th scope="row"><label><?php echo esc_html( __('Enable/Disable form on dashboard','listingpro-lead-form')); ?></label></th>
                                                <td><a href="#" class="switch-user-customizer <?php if( $lead_form_user_dashboard == 1 ){echo 'active';} ?>"></a></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <p><?php echo esc_html( __('Disable/enable the ability for users to create there own lead forms from the dashboard.','listingpro-lead-form')); ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/form-builder/lead-form-dashboard.png'; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-outer-relative">
                                <div class="lp-form-builder-left">
                                    <h4><?php echo esc_html( __('Default Fields','listingpro-lead-form')); ?></h4>
                                    <ul class="default-fields">
                                        <li data-name="name7" data-shortcode="[lp-customizer-field type='text' name='name7' placeholder='Name:' class='myclass' label='Name']"><i class="fa fa-bars" aria-hidden="true"></i> <?php echo esc_html( __('Name','listingpro-lead-form')); ?> <?php echo $lp_el_remove; ?></li>
                                        <li data-name="email7" data-shortcode="[lp-customizer-field type='email' name='email7' placeholder='Email:' class='myclass' label='Email']"><i class="fa fa-bars" aria-hidden="true"></i> <?php echo esc_html( __('Email','listingpro-lead-form')); ?> <?php echo $lp_el_remove; ?></li>
                                        <li data-name="phone7" data-shortcode="[lp-customizer-field type='text' name='phone7' placeholder='Phone:' class='myclass' label='Phone']"><i class="fa fa-bars" aria-hidden="true"></i> <?php echo esc_html( __('Phone','listingpro-lead-form')); ?> <?php echo $lp_el_remove; ?></li>
                                        <li data-name="message7" data-shortcode="[lp-customizer-field type='textarea' name='message7' placeholder='Message:' class='myclass' label='Message']"><i class="fa fa-bars" aria-hidden="true"></i> <?php echo esc_html( __('Message','listingpro-lead-form')); ?> <?php echo $lp_el_remove; ?></li>
                                    </ul>
                                    <button class="btn btn-primary add-new-form-field front-field-btn" data-target="#fieldModal"><i class="fa fa-plus"></i><?php echo esc_html( __('Add New Form Fields','listingpro-lead-form')); ?></button>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-md-8 col-md-offset-3">
                                    <div class="lead-form-wrap form-dd-wrap">
                                        <div class="lp-form-builder-right">
                                            <div class="fields-list">
                                                <h4><?php echo esc_html( __('Lead Form','listingpro-lead-form')); ?></h4>
                                                <ul class="fields-sroter clearfix">
                                                    <?php
                                                    if( empty ( $lead_form_admin ) )
                                                    {
                                                        echo do_shortcode( "[lead-form][lp-customizer-field type='text' name='name7' placeholder='Name:' class='myclass' label='Name'][lp-customizer-field type='email' name='email7' placeholder='Email:' class='myclass' label='Email'][lp-customizer-field type='text' name='phone7' placeholder='Phone:' class='myclass' label='Phone'][lp-customizer-field type='textarea' name='message7' placeholder='Message:' class='myclass' label='Message'][/lead-form]" );
                                                    }
                                                    else
                                                    {
                                                        echo do_shortcode( $lead_form_admin );
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                            <div class="lp-menu-form-outer background-white lp-lead-form-outer lp-lead-form-outer-wraper" style="display: none;">
                                                <div class="lp-cutmizer-lead-add-field">
                                                    <form class="row lp-lead-form" id="lp-lead-form">
                                                        <div class="lp-listing-selecter clearfix">

                                                            <div class="form-group col-sm-6 ">
                                                                <div class="lp-listing-selecter-content">
                                                                    <h5><?php echo esc_html( __( 'Select a Field Type', 'listingpro-lead-form' )); ?></h5>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-sm-6 ">
                                                                <div class="lp-listing-selecter-drop">
                                                                    <select class="form-control select2 field-type" name="field-type" id="field-type">
                                                                        <option value="text"><?php echo esc_html(__('Text', 'listingpro-lead-form')) ;?></option>
                                                                        <option value="email"><?php echo esc_html(__('Email', 'listingpro-lead-form')) ;?></option>
                                                                        <option value="tel"><?php echo esc_html(__('Phone', 'listingpro-lead-form')) ;?></option>
                                                                        <option value="url"><?php echo esc_html(__('Url', 'listingpro-lead-form')) ;?></option>
                                                                        <option value="date"><?php echo esc_html(__('Date', 'listingpro-lead-form')) ;?></option>
                                                                        <option value="time"><?php echo esc_html(__('Time', 'listingpro-lead-form')) ;?></option>
                                                                        <option value="datetime-local"><?php echo esc_html(__('Date-Time Local', 'listingpro-lead-form')) ;?></option>
                                                                        <option value="radio"><?php echo esc_html(__('Radio', 'listingpro-lead-form')) ;?></option>
                                                                        <option value="checkbox"><?php echo esc_html(__('Checkbox', 'listingpro-lead-form')) ;?></option>
                                                                        <option value="dropdown"><?php echo esc_html(__('Dropdown', 'listingpro-lead-form')) ;?></option>
                                                                        <option value="range"><?php echo esc_html(__('Range', 'listingpro-lead-form')) ;?></option>
                                                                        <option disabled value="file"><?php echo esc_html(__('File (coming soon)', 'listingpro-lead-form')) ;?></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label for="field-label"><?php echo esc_html( __('Label', 'listingpro-lead-form')); ?></label>
                                                            <input name="field-label" id="field-label" class="form-control" type="text" placeholder="Full Name">
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label for="field-name"><?php echo esc_html( __('Name', 'listingpro-lead-form')); ?></label>
                                                            <input name="field-name" id="field-name" class="form-control" type="text" placeholder="Name">
                                                        </div>
                                                        <div class="form-group col-sm-12 options-field" style="display: none;">
                                                            <label for="field-options"><?php echo esc_html( __('Options', 'listingpro-lead-form')); ?></label>
                                                            <textarea name="field-options" id="field-options" class="form-control"></textarea>
                                                        </div>
                                                        <div class="form-group col-sm-12 multiselect-field" style="display: none">
                                                            <label for="field-multi"><?php echo esc_html( __('Multi Select', 'listingpro-lead-form')); ?></label>
                                                            <textarea name="field-multi" name="field-multi" class="form-control"></textarea>
                                                        </div>
                                                        <div class="form-group col-sm-12 field-placeholder">
                                                            <label for="field-placeholder"><?php echo esc_html( __('Placeholder', 'listingpro-lead-form')); ?></label>
                                                            <input name="field-placeholder" id="field-placeholder" class="form-control" type="text" placeholder="Placeholder">
                                                        </div>
                                                        <div class="clearfix range-extra" style="display: none;">
                                                            <div class="form-group col-sm-6">
                                                                <label for="min-val"><?php echo esc_html( __('Minimum Value', 'listingpro-lead-form')); ?></label>
                                                                <input name="min-val" id="min-val" class="form-control" type="text" placeholder="eg.1">
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label for="max-val"><?php echo esc_html( __('Maximum Value', 'listingpro-lead-form')); ?></label>
                                                                <input name="max-val" id="max-val" class="form-control" type="text" placeholder="eg.100">
                                                            </div>
                                                        </div>
                                                        <div class="clearfix range-extra" style="display: none;">
                                                            <div class="form-group col-sm-6">
                                                                <label for="def-val"><?php echo esc_html( __('Default Value', 'listingpro-lead-form')); ?></label>
                                                                <input  name="def-val" id="def-val" class="form-control" type="text" placeholder="eg.1">
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label for="step-val"><?php echo esc_html( __('Step Value', 'listingpro-lead-form')); ?></label>
                                                                <input name="step-val" id="step-val" class="form-control" type="text" placeholder="eg.100">
                                                            </div>
                                                        </div>
                                                        <div class="clearfix margin-top-40">
                                                            <div class="form-group col-sm-5 margin-0 padding-top-15">
                                                                <div class="lp-invoices-all-stats-on-off lp-form-all-stats-on-off">
                                                                    <span><?php echo esc_html( __('required field', 'listingpro-lead-form')); ?></span>
                                                                    <label class="switch">
                                                                        <input class="form-control switch-checkbox" type="checkbox" id="field-required" name="field-required">
                                                                        <div class="slider round"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-sm-6 text-right margin-0">
                                                                <button class="lp-coupns-btns cancel-new-field"><?php echo esc_html( __( 'Cancel', 'listingpro-lead-form' )); ?></button>
                                                                <button class="lp-coupns-btns add-form-field form-field-front" data-pid=""><?php echo esc_html( __( 'save', 'listingpro-lead-form' )); ?></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Start saved notice-->
<?php echo lp_notice_bar_saved(); ?>
<!--End saved notice-->