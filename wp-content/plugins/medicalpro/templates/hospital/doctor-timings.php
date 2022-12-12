<?php
$days_arr          = medicalpro_business_working_days();
$business_hours    = listing_get_metabox_by_ID('business_hours', $doctor_id);
$current_time      = current_time(get_option('time_format'));
//New by abbas
$current_day        = date_i18n('l');
//End new by abbas
$current_closed    = '';
if (isset($business_hours[$current_day]) && !empty($business_hours[$current_day])) {
    if (empty($business_hours[$current_day]['open']) && empty($business_hours[$current_day]['close'])) {
        $todayTimeStatus = esc_html__('24 hours open', 'medicalpro');
    } else {
        if (strtotime($current_time) >= strtotime($business_hours[$current_day]['open']) && strtotime($current_time) <= strtotime($business_hours[$current_day]['close'])) {
            $todayTimeStatus = $business_hours[$current_day]['open'] . ' - ' . $business_hours[$current_day]['close'];
        } else {
            $todayTimeStatus = esc_html__('Closed', 'medicalpro');
            $current_closed = 'closed';
        }
    }
} else {
    $todayTimeStatus =  esc_html__('Closed', 'medicalpro');
    $current_closed = 'closed';
}
if (isset($days_arr) && !empty($days_arr)) { ?>
    <div class="display-inline-block pull-right mp-timings">
        <div class="mp-clearfix"></div>
        <div class="mp-timings-container pull-left display-inline-block">
            <div class="mp-profile-timing-single-day display-block">
                <p class="mp-timing-day-name"><?php echo esc_html__('Today', 'medicalpro'); ?></p>
                <p class="mp-timing-day-timing-status <?php echo $current_closed; ?>"><?php echo $todayTimeStatus; ?></p>
            </div>
            <div class="mp-timings-other-days-container mp-hide">
                <?php foreach ($days_arr as $day) { ?>
                    <div class="mp-profile-timing-single-day display-block">
                        <p class="mp-timing-day-name"><?php echo $day; ?></p>
                        <?php if (isset($business_hours[$day]) && !empty($business_hours[$day])) { ?>
                            <?php if (empty($business_hours[$day]['open']) && empty($business_hours[$day]['open'])) { ?>
                                <p class="mp-timing-day-timing-status"><?php echo esc_html__('24 hours open', 'medicalpro'); ?></p>
                            <?php } else { ?>
                                <p class="mp-timing-day-timing-status"><?php echo $business_hours[$day]['open']; ?> - <?php echo $business_hours[$day]['close']; ?></p>
                            <?php } ?>
                        <?php } else { ?>
                            <p class="mp-timing-day-timing-status closed"><?php echo esc_html__('Closed', 'medicalpro'); ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <span class="mp-timing-day-view-all-timings pull-right display-inline-block"><i class="fa fa-caret-down"></i></span>
        <div class="mp-clearfix"></div>
    </div>
    <div class="mp-clearfix"></div>
<?php } ?>