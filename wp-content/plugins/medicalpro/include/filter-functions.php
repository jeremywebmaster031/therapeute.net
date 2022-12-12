<?php

if (!function_exists('lp_get_extrafield_in_filterAjax')){
function lp_get_extrafield_in_filterAjax($term_id)
    {
        $output = null;
        $dataNeedle = false;
        ob_start();
        ?>
    
        <div class="lp_all_page_overflow">
            <div class="col-md-12">
    
                <?php
    
                echo '<h2>' . esc_html__("More Filter", "medicalpro") . '</h2>';
                /* check on off */
                $getSwithButtonFieldsFilter = lp_get_extrafields_filter('checkbox', $term_id, false);
                if (!empty($getSwithButtonFieldsFilter)) {
                    $dataNeedle = true;
                    ?>
                    <div class="lp_more_filter_data_section lp_extrafields_select">
    
                        <?php
                        echo '<ul class="filter_data_switch_on_off lp-filter_data_switch_on_off">';
                        foreach ($getSwithButtonFieldsFilter as $fieldPostID => $fieldVal) {
                            $fieldSlug = get_post_field('post_name', $fieldPostID);
                            echo '
    									<li>
    									
    									
    									
    										<h3 class="filter_checkbox_container">' . $fieldVal . '</h3>	
    										
    										<input class="lp-more-filter-vals" data-mfilterkey="' . $fieldVal . '" data-mfilterval="' . $fieldVal . '" type="hidden" data-key="' . $fieldSlug . '" value="' . __('Yes', 'medicalpro') . '" name="lp_extrafields_select[]">
    										<label class="switch lp-label-for-switcher-btn">
    												
    										<input class="lp-more-filter-vals" data-mfilterkey="' . $fieldVal . '" data-mfilterval="' . $fieldVal . '" type="checkbox" data-key="' . $fieldSlug . '" value="' . __('Yes', 'medicalpro') . '" name="lp_extrafields_select[]">										
    										 <div class="slider round"></div>
    										</label>					
    									
    									
    									</li>
    								';
                        }
                        echo '</ul>';
                        ?>
    
                    </div>
                    <?php
                }
                /* checkbox */
                $getSwithButtonFieldsFilter = lp_get_extrafields_filter('check', $term_id, false);
                if (!empty($getSwithButtonFieldsFilter)) {
                    $dataNeedle = true;
                    ?>
                    <div class="lp_more_filter_data_section lp_extrafields_select lp_extrafields_select-border">
    
                        <?php
    
                        foreach ($getSwithButtonFieldsFilter as $fieldPostID => $fieldVal) {
                            $fieldSlug = get_post_field('post_name', $fieldPostID);
                            echo '<div class="lp-more-filters-outer lp-lp-more-filters-outer lp-filter_data_checkbox">';
                            echo '<div class="clearfix"></div>';
                            echo '<h3 class="pull-left display-inline-block filter_checkbox_container">' . $fieldVal . '</h3>';
                            echo '<i class="fa fa-angle-down mp-expand-all-filters mp-expand-all-filters-trigger pull-right"></i>';
                            echo '<div class="clearfix"></div>';
                            echo '<ul class="lp_filter_checkbox" style="display:none;">';
    
                            echo '
    									<li>
    										
    										<label class="filter_checkbox_container">
    											<input class="lp-more-filter-vals " data-mfilterkey="' . $fieldVal . '" data-mfilterval="' . $fieldVal . '" type="checkbox" data-key="' . $fieldSlug . '" value="' . __('Yes', 'medicalpro') . '" name="lp_extrafields_select[]">
    											<span class="filter_checkbox_checkmark"></span>
    										</label>
    									</li>
    								';
                            echo '</ul>';
                            echo '</div>';
                        }
    
                        ?>
    
                    </div>
                    <?php
                }
    
                ?>
                <!-- for multicheck -->
                <?php
                $getExtraFieldsFilter = lp_get_extrafields_filter('checkboxes', $term_id, false);
                if (!empty($getExtraFieldsFilter)) {
                    $dataNeedle = true;
    
                    ?>
                    <div class="lp_more_filter_data_section lp_extrafields_select lp_extrafields_select-border2">
    
                        <?php
                        foreach ($getExtraFieldsFilter as $fieldPostID => $fieldVal) {
                            $fieldSlug = get_post_field('post_name', $fieldPostID);
                            echo '<div class="lp-more-filters-outer">';
                            echo '<div class="clearfix"></div>';
                            echo '<h3 class="pull-left display-inline-block">' . $fieldVal . '</h3>';
                            echo '<i class="fa fa-angle-down mp-expand-all-filters mp-expand-all-filters-trigger pull-right"></i>';
                            echo '<div class="clearfix"></div>';
                            echo '<ul class="lp_filter_checkbox" style="display:none;">';
                            $getFieldsValue = listing_get_metabox_by_ID('multicheck-options', $fieldPostID);
                            if (!empty($getFieldsValue)) {
                                $getFieldsArray = explode(",", $getFieldsValue);
                                if (!empty($getFieldsArray)) {
                                    foreach ($getFieldsArray as $optionVal) {
                                        $optionVal = trim($optionVal);
                                        $max_length = 30;
                                        $s = $optionVal;
                                        if (strlen($s) > $max_length)
                                        {
                                            $offset = $max_length - strlen($s);
                                            $s = substr($s, 0, strrpos($s, ' ', $offset)) . '...';
                                        }
                                        
                                        echo '
                                                <li>
                                                    <label class="filter_checkbox_container" title="' . $optionVal . '">' . $s . '
                                                        <input type="checkbox" data-key="' . $fieldSlug . '" value="' . $optionVal . '" name="lp_extrafields_select[]">
                                                        <span class="filter_checkbox_checkmark"></span>
                                                    </label>
                                                </li>
                                            ';
                                    }
                                }
                            }
                            echo '</ul>';
                            echo '</div>';
                        }
                        ?>
    
                    </div>
                <?php } ?>
    
                <!-- for radio -->
                <?php
                $getRadioFieldsFilter = lp_get_extrafields_filter('radio', $term_id, false);
                if (!empty($getRadioFieldsFilter)) {
                    $dataNeedle = true;
    
                    ?>
    
                    <div class="lp_more_filter_data_section lp_extrafields_select lp_extrafields_select-border2">
                        <?php
                        foreach ($getRadioFieldsFilter as $fieldPostID => $fieldVal) {
                            $fieldSlug = get_post_field('post_name', $fieldPostID);
                            echo '<div class="lp-more-filters-outer">';
                            echo '<div class="clearfix"></div>';
                            echo '<h3 class="pull-left display-inline-block">' . $fieldVal . '</h3>';
                            echo '<i class="fa fa-angle-down mp-expand-all-filters mp-expand-all-filters-trigger pull-right"></i>';
                            echo '<div class="clearfix"></div>';
                            echo '<ul class="lp_filter_checkbox" style="display:none;">';
    
    
                            $getFieldsValue = listing_get_metabox_by_ID('radio-options', $fieldPostID);
                            if (!empty($getFieldsValue)) {
                                $getFieldsArray = explode(",", $getFieldsValue);
                                if (!empty($getFieldsArray)) {
                                    foreach ($getFieldsArray as $optionVal) {
                                        $optionVal = trim($optionVal);
    
                                        echo '
    														<li>
    															<label class="filter_radiobox_container">' . $optionVal . '
    															  <input type="radio" name="' . $fieldSlug . '-radio" data-key="' . $fieldSlug . '" value="' . $optionVal . '" name="lp_extrafields_select[]">
    															  <span class="filter_radio_select"></span>
    															</label>
    														</li>
    													';
                                    }
                                }
                            }
    
                            echo '</ul>';
                            echo '</div>';
                        }
                        ?>
    
                    </div>
                    <?php
                }
                ?>
    
    
                <!-- for Dropdown -->
                <?php
                $getRadioFieldsFilter = lp_get_extrafields_filter('select', $term_id, false);
                if (!empty($getRadioFieldsFilter)) {
                    $dataNeedle = true;
                    ?>
    
                    <div class="lp_more_filter_data_section lp_extrafields_select lp_extrafields_select-border2">
                        <?php
                        foreach ($getRadioFieldsFilter as $fieldPostID => $fieldVal) {
                            $fieldSlug = get_post_field('post_name', $fieldPostID);
                            echo '<div class="lp-more-filters-outer">';
                            echo '<div class="clearfix"></div>';
                            echo '<h3 class="pull-left display-inline-block">' . $fieldVal . '</h3>';
                            echo '<i class="fa fa-angle-down mp-expand-all-filters mp-expand-all-filters-trigger pull-right"></i>';
                            echo '<div class="clearfix"></div>';
                            echo '<ul class="lp_filter_checkbox" style="display:none;">';
    
    
                            $getFieldsValue = listing_get_metabox_by_ID('select-options', $fieldPostID);
                            if (!empty($getFieldsValue)) {
                                $getFieldsArray = explode(",", $getFieldsValue);
                                if (!empty($getFieldsArray)) {
                                    foreach ($getFieldsArray as $optionVal) {
                                        $optionVal = trim($optionVal);
    
                                        echo '
                                                    <li>
                                                        <label class="filter_radiobox_container">' . $optionVal . '
                                                          <input type="radio" data-key="' . $fieldSlug . '" name="' . $fieldSlug . '-radio" value="' . $optionVal . '" name="lp_extrafields_select[]">
                                                          <span class="filter_radio_select"></span>
                                                        </label>
                                                    </li>
                                                ';
                                    }
                                }
                            }
    
                            echo '</ul>';
                            echo '</div>';
                        }
                        ?>
    
                    </div>
                    <?php
                }
    
                if (empty($dataNeedle)) {
                    ?>
                    <div class="lp_more_filter_data_section lp_extrafields_select">
                        <p><?php echo esc_html__('Sorry! No more filter found for current selections', 'medicalpro'); ?></p>
                    </div>
                    <?php
                }
    
                ?>
    
                <?php
                $mobile_view = lp_theme_option('single_listing_mobile_view');
                if (wp_is_mobile() && ($mobile_view == 'app_view' || $mobile_view == 'app_view2')) {
                    ?>
                    <div class="outer_filter_show_result_cancel">
                        <div class="filter_show_result_cancel">
                            <span id="filter_cancel_all"><?php echo esc_html__('Cancel', 'medicalpro'); ?></span>
    
                            <input id="filter_result" type="submit"
                                   value="<?php echo esc_html__('Show Results', 'medicalpro'); ?>">
    
                        </div>
                    </div>
                    <?php
                }
                ?>
    
    
            </div>
        </div>
    
        <?php
    
        $output .= ob_get_contents();
        ob_end_clean();
        ob_flush();
        return $output;
    }
}