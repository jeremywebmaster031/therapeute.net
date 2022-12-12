<?php

if (!function_exists('medicalpro_hospital_business_hours_form')) {

    function medicalpro_hospital_business_hours_form( $postID, $rand_id, $buisness_hours = array() ) {

        //new code 1.4
        if(empty($postID)){
            $postID = 0;
        }
        //end new code 1.4
        
        $output = '';
        $MondayOpen = '';
        $MondayClose = '';
        $TusedayOpen = '';
        $TusedayClose = '';
        $WednesdayOpen = '';
        $WednesdayClose = '';
        $ThursdayOpen = '';
        $ThursdayClose = '';
        $FridayOpen = '';
        $FridayClose = '';
        $SaturdayOpen = '';
        $SaturdayClose = '';
        $SundayOpen = '';
        $SundayClose = '';

        $MondayEnabled = 'disabled';
        $Mondaychecked = '';
        $TusedayEnabled = 'disabled';
        $Tusedaychecked = '';
        $WednesdayEnabled = 'disabled';
        $Wednesdaychecked = '';
        $ThursdayEnabled = 'disabled';
        $Thursdaychecked = '';
        $FridayEnabled = 'disabled';
        $Fridaychecked = '';
        $SaturdayEnabled = 'disabled';
        $Saturdaychecked = '';
        $SundayEnabled = 'disabled';
        $Sundaychecked = '';
        global $listingpro_options;

        // added for style2 button text
        $page_style  = $listingpro_options['listing_submit_page_style'];
        $add_hour_st = '';
        $removeStr   = esc_html__('Remove', 'medicalpro');
        $removeData  = esc_html__('Remove', 'medicalpro');
        if ($page_style == 'style2') {
            $add_hour_st = 'lp-add-hours-st';
            $removeStr = '<i class="fa fa-times"></i>';
        }
        
        $listing2timeslots = $listingpro_options['lp_hours_slot2'];
        $output .= '<label for="operationalHours">' . esc_html__('Working Hours', 'medicalpro') . '</label>';
       
        $output .= '		
        <div class="day-hours" data-id="'. $rand_id .'">
            <div class="hours-display">';
                if (isset($postID) && $postID > 0 ) {
                    if (!empty($buisness_hours)) {
                        foreach ($buisness_hours as $key => $value) {
                            $output .= '<div class="hours">';
                            if (!empty($value['open']) && !empty($value['close'])) {
                                if (is_array($value['open']) && is_array($value['close'])) {
                                    $output .= '<span class="weekday">' . $key . '</span>';
                                    if (isset($value['open'][0]) && isset($value['close'][0])) {
                                        $output .= '<span class="start">' . listing_time_format($value['open'][0], null) . '</span>
                                        <input name="medicalpro_hospitals['. $rand_id .'][business_hours][' . $key . '][open][0]" value="' . $value['open'][0] . '" type="hidden">';
                                        $output .= '<span>-</span>';
                                        $output .= '<span class="end">' . listing_time_format($value['close'][0], null) . '</span>
                                        <input name="medicalpro_hospitals['. $rand_id .'][business_hours][' . $key . '][close][0]" value="' . $value['close'][0] . '" type="hidden">';
                                    }
                                    if (isset($value['open'][1]) && isset($value['close'][1])) {
                                        $output .= '<span class="start">' . listing_time_format($value['open'][1], null) . '</span>
                                        <input name="medicalpro_hospitals['. $rand_id .'][business_hours][' . $key . '][open][1]" value="' . $value['open'][1] . '" type="hidden">';
                                        $output .= '<span>-</span>';
                                        $output .= '<span class="end">' . listing_time_format($value['close'][1], null) . '</span>
                                        <input name="medicalpro_hospitals['. $rand_id .'][business_hours][' . $key . '][close][1]" value="' . $value['close'][1] . '" type="hidden">';
                                    }
                                    $output .= '<a class="remove-hours" href="#">' . $removeStr . '</a>';
                                    $output .= '</div>';
                                } else {
                                    $output .= '
                                    <span class="weekday">' . $key . '</span>
                                    <span class="start">' . listing_time_format($value['open'], null) . ' </span>
                                    <span>-</span>
                                    <span class="end">' . listing_time_format($value['close'], null) . '</span>
                                    <input name="medicalpro_hospitals['. $rand_id .'][business_hours][' . $key . '][open]" value="' . $value['open'] . '" type="hidden">
                                    <input name="medicalpro_hospitals['. $rand_id .'][business_hours][' . $key . '][close]" value="' . $value['close'] . '" type="hidden">
                                    <a class="remove-hours" href="#">' . $removeStr . '</a>
                                    </div>';
                                }
                            } else {
                                $output .= '
                                <span class="weekday">' . $key . '</span>
                                <span class="start-end fullday">
                                ' . esc_html__('24 hours open', 'medicalpro') . '
                                <input name="medicalpro_hospitals['. $rand_id .'][business_hours][' . $key . '][open]" value="" type="hidden">
                                <input name="medicalpro_hospitals['. $rand_id .'][business_hours][' . $key . '][close]" value="" type="hidden">
                                </span>';
                                $output .= '<a class="remove-hours" href="javascript:void(0);">' . $removeStr . '</i></a>';
                                $output .= '</div>';
                            }
                        }
                    }
                }
            $output .= '</div>';
            $output .= '<ul class="hours-select clearfix inline-layout up-4">';
                $output .= '<li>
                    <select class="weekday select2">
                        <option value="' . esc_html__('Monday', 'medicalpro') . '" selected="">' . esc_html__('Monday', 'medicalpro') . '</option>
                        <option value="' . esc_html__('Tuesday', 'medicalpro') . '">' . esc_html__('Tuesday', 'medicalpro') . '</option>
                        <option value="' . esc_html__('Wednesday', 'medicalpro') . '">' . esc_html__('Wednesday', 'medicalpro') . '</option>
                        <option value="' . esc_html__('Thursday', 'medicalpro') . '">' . esc_html__('Thursday', 'medicalpro') . '</option>
                        <option value="' . esc_html__('Friday', 'medicalpro') . '">' . esc_html__('Friday', 'medicalpro') . '</option>
                        <option value="' . esc_html__('Saturday', 'medicalpro') . '">' . esc_html__('Saturday', 'medicalpro') . '</option>
                        <option value="' . esc_html__('Sunday', 'medicalpro') . '">' . esc_html__('Sunday', 'medicalpro') . '</option>
                    </select>
                </li>';
                
                $startTime     = strtotime('00:00');
                $endTime       = strtotime('23:30');
                $current_time  = current_time(get_option('time_format'));
                $diff          = strtotime('+30 minutes', strtotime($current_time)) - strtotime($current_time);
                $output .= '<li>
                    <select class="hours-start select2">';
                        while ($startTime <= $endTime) {
                            $selected = '';
                            if( strtotime('09:00 am') == strtotime(date('h:i a', (int)$startTime)) ){
                                $selected = 'selected';
                            }
                            $output .= '<option '. $selected .' value="' . listing_time_format(null, date('H:i', (int)$startTime)) . '">' . listing_time_format(date('H:i', (int)$startTime), null) . '</option>';
                            $startTime +=  $diff;
                        }
                    $output .= '</select>
                </li>';
                    
                $startTime     = strtotime('00:00');
                $endTime       = strtotime('23:30');
                $current_time  = current_time(get_option('time_format'));
                $diff          = strtotime('+30 minutes', strtotime($current_time)) - strtotime($current_time);
                $output .= '<li>
                    <select class="hours-end select2">';
                        while ($startTime <= $endTime) {
                            $selected = '';
                            if( strtotime('05:00 pm') == strtotime(date('h:i a', (int)$startTime)) ){
                                $selected = 'selected';
                            }
                            $output .= '<option '. $selected .' value="' . listing_time_format(null, date('H:i', (int)$startTime)) . '">' . listing_time_format(date('H:i', (int)$startTime), null) . '</option>';
                            $startTime +=  $diff;
                        }
                    $output .= '</select>
                </li>';
                    
                $output .= '<li>
                    <div class="checkbox form-group fulldayopen-wrap">
                        <input type="checkbox" name="fulldayopen" id="hospitalfulldayopen_'. $rand_id .'" class="hospitalfulldayopen">
                        <label for="hospitalfulldayopen_'. $rand_id .'">' . esc_html__('24 Hours', 'medicalpro') . '</label>
                    </div>
                    <button data-fullday = "' . esc_html__('24 hours open', 'medicalpro') . '" data-remove="' . $removeData . '" data-sorrymsg="' . esc_html__('Sorry', 'medicalpro') . '" data-alreadyadded="' . esc_html__('Already Added', 'medicalpro') . '" type="button" value="submit" class="ybtn ybtn--small add-hospital-hours ' . $add_hour_st . '">';
                    $output .= '<span><i class="fa fa-plus-square"></i> </span>';
                    $output .= '</button>
                </li>';
            $output .= '</ul>
        </div>';

        return $output;
    }

}