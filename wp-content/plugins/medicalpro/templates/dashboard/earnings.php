<?php

global $wpdb, $listingpro_options;
$currencySymbol = listingpro_currency_sign();
$table = $wpdb->prefix . 'booking_orders';
$userID = get_current_user_id();
$orders = array();
$Porders = array();
$orderCount = 0;
$startFrom = 0;
$QpostPerPage = 10;
if ($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
    $query = "";
    $query = "SELECT * from $table WHERE doctor_id = $userID ORDER BY main_id DESC";
    $Pquery = "SELECT * from $table WHERE doctor_id = $userID ORDER BY main_id DESC LIMIT $startFrom,$QpostPerPage";
    $orders = $wpdb->get_results($query);
    $Porders = $wpdb->get_results($Pquery);
    $orderCount = count($orders);
}

$countWithdrawals = 0;
$args = array(
    'post_type' => 'lp-withdrawal',
    'author'    => $userID,
    'posts_per_page'    => -1,
    'fields' => 'ids',
    'meta_query' => array(
        array(
            'key'       =>  'lp_withdrawal_requested_status',
            'value'     =>  'rejected',
            'compare'   =>  '!='
        )
    )
);
$the_query = new WP_Query($args);
if ($the_query->have_posts()) {
    while ($the_query->have_posts()) {
        $the_query->the_post();
        $value = get_post_meta(get_the_ID(), 'lp_withdrawal_requested_amount', true);
        if (!empty($value) && is_numeric($value)) {
            $countWithdrawals = $countWithdrawals + $value;
        }
    }
}
$totalEarnings = 0;
if (!empty($orders) && is_array($orders)) {
    foreach ($orders as $key => $value) {
        if (!empty($value->sub_total) && is_numeric($value->sub_total)) {
            $totalEarnings = $totalEarnings + $value->sub_total;
        }
    }
}
$availableBalance = 0;
if (is_numeric($totalEarnings) && is_numeric($countWithdrawals)) {
    $availableBalance = $totalEarnings - $countWithdrawals;
}

$currentURL = '';
$perma = '';
$dashQuery = 'dashboard=';
$currentURL = $listingpro_options['listing-author'];
$CURL = explode('?', $currentURL);
if (is_array($CURL) && !empty($CURL) && isset($CURL[0])) {
    $currentURL = $CURL[0];
}
global $wp_rewrite;
if ($wp_rewrite->permalink_structure == '') {
    $perma = "&";
} else {
    $perma = "?";
}
?>

<div class="lp_mp_earnings_dashboard container">
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-4">
                <div class="lp_mp_earnings_dashboard_card_current_balance">
                    <div class="lp_mp_earnings_dashboard_card_current_balance_title">
                        <div><img src="<?php echo MP_PLUGIN_DIR . '/assets/images/earnings/Group_2098.svg' ?>" alt="SVG Icons"></div>
                        <p><?php esc_html_e('Current Balance', 'medicalpro') ?></p>
                    </div>
                    <h2><?php echo $currencySymbol . round($availableBalance, 2); ?></h2>
                    <button <?php if ($availableBalance < 5) {
                                echo 'disabled="disabled" title="' . esc_html__('Your Current Balance Is Low.', 'medicalpro') . '"';
                            } ?> type="button" data-toggle="modal" data-target="#withdrawalRequestModal">
                        <img src="<?php echo MP_PLUGIN_DIR . '/assets/images/earnings/Group_2152.svg' ?>" alt="SVG Icons">
                        <?php esc_html_e('Withdraw Now', 'medicalpro') ?>
                    </button>
                </div>
            </div>

            <div class="col-md-3">
                <div class="lp_mp_earnings_dashboard_card lp_mp_earnings_dashboard_card_earnings">
                    <div>
                        <img src="<?php echo MP_PLUGIN_DIR . '/assets/images/earnings/Group_2153.svg' ?>" alt="SVG Icons">
                    </div>
                    <h4><?php esc_html_e('Total Earnings', 'medicalpro') ?></h4>
                    <h3><?php echo $currencySymbol . round($totalEarnings, 2); ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="lp_mp_earnings_dashboard_card lp_mp_earnings_dashboard_card_withdrawls">
                    <div>
                        <img src="<?php echo MP_PLUGIN_DIR . '/assets/images/earnings/Group_2126.svg' ?>" alt="SVG Icons">
                    </div>
                    <h4><?php esc_html_e('Total Withdrawals', 'medicalpro') ?></h4>
                    <h3><?php echo $currencySymbol . round($countWithdrawals, 2); ?></h3>
                </div>
            </div>
        </div>
        <div class="lp_mp_earnings_dashboard_transaction_head">
            <div class="lp_mp_earnings_dashboard_transaction_heading">
                <h5 class="lp_mp_earnings_dashboard_transaction_title"><?php esc_html_e('Transaction History', 'medicalpro') ?></h5>
                <p class="lp_mp_earnings_dashboard_transaction_desc"><?php esc_html_e('You can sort the table content by clicking sort icon or use the filters to refine your Details', 'medicalpro') ?></p>
            </div>
            <div class="lp_mp_earnings_dashboard_transaction_filters">
                <button>
                    <img src="<?php echo MP_PLUGIN_DIR . '/assets/images/earnings/Group_2110.svg' ?>" alt="SVG Icons">
                    <?php esc_html_e('Filter', 'medicalpro') ?>
                </button>
            </div>
        </div>
        <div class="lp_mp_earnings_dashboard_transaction_filters_container">
            <form class="form-inline row" id="lp_mp_earnings_dashboard_transaction_filters">
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mp-form-group">
                        <label for="minAmount"><?php esc_html_e('Min Amount', 'medicalpro') ?></label>
                        <input autocomplete="off" type="number" min="1" class="form-control" name="minAmount" id="minAmount">
                    </div>
                    <div class="mp-form-group">
                        <label for="maxAmount"><?php esc_html_e('Max Amount', 'medicalpro') ?></label>
                        <input autocomplete="off" type="number" min="1" class="form-control" name="maxAmount" id="maxAmount">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mp-form-group">
                        <label for="fromDate"><?php esc_html_e('From Date', 'medicalpro') ?></label>
                        <input autocomplete="off" type="date" class="form-control" name="fromDate" id="fromDate">
                    </div>
                    <div class="mp-form-group">
                        <label for="toDate"><?php esc_html_e('To Date', 'medicalpro') ?></label>
                        <input autocomplete="off" type="date" class="form-control" name="toDate" id="toDate">
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="lp_mp_earnings_dashboard_transaction_filters_reset" type="reset"><?php esc_html_e('Reset Filters', 'medicalpro') ?></button>
                    <button class="lp_mp_earnings_dashboard_transaction_filters_submit"><?php esc_html_e('Get Results', 'medicalpro') ?></button>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
        <?php
        if (!empty($Porders) && is_array($Porders)) {
        ?>
            <div class="lp_mp_earnings_dashboard_transactions_container">
                <div class="lp_mp_earnings_dashboard_transactions table-responsive">
                    <table class="lp_mp_earnings_dashboard_transactions_tables table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Doctor', 'medicalpro') ?></th>
                                <th><?php esc_html_e('Username', 'medicalpro') ?></th>
                                <th><?php esc_html_e('Amount', 'medicalpro') ?></th>
                                <th><?php esc_html_e('METHOD', 'medicalpro') ?></th>
                                <th><?php esc_html_e('web fee', 'medicalpro') ?></th>
                                <th><?php esc_html_e('Received', 'medicalpro') ?></th>
                                <th><?php esc_html_e('Date', 'medicalpro') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($Porders as $key => $data) {
                                $postID = $data->post_id;
                                $bookingID = $data->booking_id;
                                $userID = $data->customer_id;
                                $currency = $data->currency;
                                $currency = medicalpro_currency_sign($currency);
                                $price = $data->sub_total;
                                $Tprice = $data->paid_price;
                                $adminCom = $data->commision_price;
                                $adminPer = $data->commision_rate;
                                $status = $data->status;
                                $date = $data->date;
                                $paymentMethod = $data->payment_method;
                                $transaction_ID = $data->transaction_id;
                                $hospital_name = $data->hospital_name;
                                $doctor_name = $data->doctor_name;
                                $customer = get_userdata($data->customer_id)->display_name;
                                if (empty($customer) || $customer == null || !$customer) {
                                    $customer = get_userdata($data->customer_id)->user_login;
                                }

                            ?>
                                <tr>
                                    <td><a href="<?php echo get_permalink($postID); ?>" target="_blank"><?php echo get_the_title($postID); ?></a></td>
                                    <td><?php echo $customer; ?></td>
                                    <td><?php echo $currency . $Tprice; ?></td>
                                    <td><?php echo $paymentMethod; ?></td>
                                    <td><?php echo $currency . $adminCom . ' (' . $adminPer . '%)'; ?></td>
                                    <td><?php echo $currency . $price; ?></td>
                                    <td><?php echo date_i18n(get_option('date_format'), $date); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="lp_mp_earnings_dashboard_invoice_pagination">
                    <?php
                    echo mp_SQL_pagination($QpostPerPage, $orderCount, 1, $startFrom);
                    ?>
                </div>
            </div>
        <?php
        }
        echo "<input autocomplete='off' type='hidden' id='Queryargs' data-maxpostperpage='" . $QpostPerPage . "' data-totalposts='" . $orderCount . "' data-paged='1' data-startfrom='" . $startFrom . "'>";
        ?>
    </div>

    <div class="col-md-3">
        <?php
        global $paged;
        $args = null;
        $the_query = null;
        $args = array(
            'post_type' => 'lp-withdrawal',
            'author' => get_current_user_id(),
            'posts_per_page' => 4,
            'fields' => 'ids',
            'orderby' => 'date',
            'paged' => $paged,
            'meta_query' => array(
                array(
                    'key' => 'lp_withdrawal_requested_status'
                )
            )
        );
        $the_query = new WP_Query($args);
        ?>
        <div class="lp_mp_earnings_dashboard_withdrawal_container">
            <div class="lp_mp_earnings_dashboard_withdrawal_method">
                <div class="lp_mp_earnings_dashboard_withdrawal_method_icon">
                    <img src="<?php echo MP_PLUGIN_DIR . '/assets/images/earnings/Group_2175.svg' ?>" alt="SVG Icons">
                </div>
                <div class="lp_mp_earnings_dashboard_withdrawal_method_text">
                    <h6 class="clearfix"><?php esc_html_e('Payout Setting', 'medicalpro') ?> <a href="#PayoutMethodModal" type="button" data-toggle="modal" data-target="#PayoutMethodModal"> <?php esc_html_e('Change', 'medicalpro') ?></a></h6>
                    <p><?php esc_html_e('Change Your Payout Method From Here.', 'medicalpro') ?></p>
                </div>
            </div>
            <?php
            if ($the_query->have_posts()) {
            ?>
                <div class="lp_mp_earnings_dashboard_withdrawal_history_container">
                    <div class="lp_mp_earnings_dashboard_withdrawal_history_container_heading">
                        <h4><?php esc_html_e('Recent Withdrawals', 'medicalpro') ?></h4>
                    </div>
                    <div class="lp_mp_earnings_dashboard_withdrawal_history_cards">
                        <?php
                        $date = '';
                        $counter = 0;
                        while ($the_query->have_posts()) {
                            $the_query->the_post();
                            $amount = get_post_meta(get_the_ID(), 'lp_withdrawal_requested_amount', true);
                            $status = get_post_meta(get_the_ID(), 'lp_withdrawal_requested_status', true);
                            $payout = get_post_meta(get_the_ID(), 'lp_withdrawal_payout_method', true);
                            if (empty($payout)) {
                                $payout = esc_html__('N/A', 'medicalpro');
                            }
                            $class = 'success';
                            $src = MP_PLUGIN_DIR . '/assets/images/earnings/Path_1271.svg';
                            $textHTML = '<p class="lp_mp_earnings_dashboard_withdrawal_history_card_head_icon_text">' . esc_html__('Successful', 'medicalpro') . '</p>';
                            if ($status == 'paid') {
                                $class = 'mp-success';
                                $src = MP_PLUGIN_DIR . '/assets/images/earnings/Path_1271.svg';
                                $textHTML = '<p class="lp_mp_earnings_dashboard_withdrawal_history_card_head_icon_text">' . esc_html__('Successful', 'medicalpro') . '</p>';
                            } elseif ($status == 'unpaid') {
                                $class = 'mp-pending';
                                $src = MP_PLUGIN_DIR . '/assets/images/earnings/Group_2126.svg';
                                $textHTML = '<p class="lp_mp_earnings_dashboard_withdrawal_history_card_head_icon_text">' . esc_html__('Pending', 'medicalpro') . '</p>';
                            } else {
                                $class = 'mp-danger';
                                $src = MP_PLUGIN_DIR . '/assets/images/earnings/Group_2172.svg';
                                $textHTML = '<a href="#withdrawalRequestModal" type="button" data-toggle="modal" data-target="#withdrawalRequestModal" class="lp_mp_earnings_dashboard_withdrawal_history_card_head_icon_text">' . esc_html__('Try Again', 'medicalpro') . '</a>';
                            }
                        ?>

                            <div class="lp_mp_earnings_dashboard_withdrawal_history_card">
                                <div class="lp_mp_earnings_dashboard_withdrawal_history_card_head">
                                    <div class="lp_mp_earnings_dashboard_withdrawal_history_card_head_text">
                                        <h4><?php esc_html_e('Withdrawal Amount', 'medicalpro') ?></h4>
                                        <h3><?php echo $currencySymbol . $amount . '/-'; ?></h3>
                                    </div>
                                    <div class="lp_mp_earnings_dashboard_withdrawal_history_card_head_icon <?php echo $class; ?>">
                                        <div>
                                            <img src="<?php echo $src; ?>" alt="SVG Icons">
                                        </div>
                                        <?php echo $textHTML ?>
                                    </div>
                                </div>
                                <div class="lp_mp_earnings_dashboard_withdrawal_history_card_body">
                                    <div class="clearfix"></div>
                                    <h5 class="pull-left margin-bottom-15"><?php esc_html_e('Payout Method', 'medicalpro') ?></h5>
                                    <p class="pull-right margin-bottom-15"><?php echo $payout; ?></p>
                                    <div class="clearfix"></div>
                                    <h5 class="pull-left"><?php esc_html_e('Date', 'medicalpro') ?></h5>
                                    <p class="pull-right"><?php echo get_the_date(); ?></p>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                            <?php continue; ?>
                            <div class="lp_mp_earnings_dashboard_withdrawal_history">
                                <div class="clearfix"></div>
                                <div class="pull-left">
                                    <p class="lp_mp_earnings_dashboard_withdrawal_history_label text-left"><?php esc_html_e('Amount', 'medicalpro') ?></p>
                                    <h4 class="lp_mp_earnings_dashboard_withdrawal_history_value text-left"><?php echo $currencySymbol . $amount; ?></h4>
                                </div>
                                <div class="pull-right">
                                    <p class="lp_mp_earnings_dashboard_withdrawal_history_label text-right"><?php esc_html_e('Status', 'medicalpro') ?></p>
                                    <h4 class="lp_mp_earnings_dashboard_withdrawal_history_value text-right lp_mp_earnings_dashboard_withdrawal_<?php echo $statusText; ?>"><?php echo $statusText; ?></h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="lp_mp_earnings_dashboard_withdrawal_history_pagination">
                        <?php
                        mp_pagination($the_query, $the_query->max_num_pages, 1);
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php if ($availableBalance >= 5) { ?>
    <!--Withdrawal Modal-->
    <?php
    $userID = get_current_user_id();
    $dataArr = get_user_meta($userID, 'mp_user_payouts', true);
    $type = null;
    $title = null;
    $value = null;
    $class = null;
    if (!empty($dataArr) && is_array($dataArr)) {
        $type = $dataArr['accountType'];
        $title = $dataArr['accountTitle'];
        if (!empty($type) && !empty($title)) {
            $value = $type . ' (' . $title . ')';
        } else {
            $value = esc_html__('No Payout Method Configured.');
            $class = 'disabled';
        }
    } else {
        $value = esc_html__('No Payout Method Configured.');
        $class = 'disabled';
    }
    ?>
    <div class="modal fade" id="withdrawalRequestModal" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="withdrawalRequestModalIcon">
                        <img src="<?php echo MP_PLUGIN_DIR . '/assets/images/earningsmodal.png' ?>" alt="Icon">
                    </div>
                    <h3><?php esc_html_e('Request for Withdrawal', 'medicalpro') ?></h3>
                    <p><?php esc_html_e('You can send a request to admin if you see any balance in your account', 'medicalpro') ?></p>
                    <form class="withdrawalRequestModal">
                        <div class="form-group">
                            <label for="withdrawalMethod" class="col-form-label clearfix"><?php esc_html_e('Payout Method', 'medicalpro') ?> <a href="#PayoutMethodModal" type="button" data-toggle="modal" data-target="#PayoutMethodModal"> <?php esc_html_e('Payout Settings', 'medicalpro') ?></a></label>
                            <input autocomplete="off" type="text" class="form-control" id="withdrawalMethod" value="<?php echo $value; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="withdrawalamount" class="col-form-label"><?php esc_html_e('Amount: ( Without ' . $currencySymbol . ' )', 'medicalpro') ?></label>
                            <input autocomplete="off" type="number" min="5" max="<?php echo $availableBalance; ?>" class="form-control" id="withdrawalamount" placeholder="<?php esc_html_e('50', 'medicalpro') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="withdrawalmessage" class="col-form-label"><?php esc_html_e('Message', 'medicalpro') ?>:</label>
                            <textarea class="form-control" id="withdrawalmessage" placeholder="<?php esc_html_e('Enter Your Message For Website Admin.', 'medicalpro') ?>" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-default btn-lg <?php echo $class; ?>" <?php echo $class; ?>><?php esc_html_e('Send Request', 'medicalpro') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!--Payout Method -->
<div class="modal fade" id="PayoutMethodModal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <?php
                $mp_payout_setting_paypal = $listingpro_options['mp_payout_setting_paypal'];
                $mp_payout_setting_payoneer = $listingpro_options['mp_payout_setting_payoneer'];
                $mp_payout_setting_stripe = $listingpro_options['mp_payout_setting_stripe'];
                $mp_payout_setting_bank = $listingpro_options['mp_payout_setting_bank'];
                $mp_payout_setting_other = $listingpro_options['mp_payout_setting_other'];
                $userID = get_current_user_id();
                $dataArr = get_user_meta($userID, 'mp_user_payouts', true);
                $type = null;
                $title = null;
                if (!empty($dataArr) && is_array($dataArr)) {
                    $type = $dataArr['accountType'];
                    $title = $dataArr['accountTitle'];
                }
                ?>
                <div class="PayoutMethods">

                    <!-- Updated by abbas -->
                    <?php if ($mp_payout_setting_paypal == 1) { ?>
                        <div class="PayoutMethod_container <?php if ($type == 'Paypal') {
                                                                echo 'active';
                                                            } ?>" data-value="Paypal">

                            <div class="PayoutMethod_container_selected">

                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/earnings/Path_1271.svg' ?>" alt="Icon">

                            </div>

                            <div class="PayoutMethod">

                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/earnings/payoutsmethod/Group_2196.svg' ?>" alt="Icon">

                            </div>

                            <p><?php esc_html_e('Payout with Paypal', 'medicalpro'); ?></p>

                        </div>
                    <?php } ?>

                    <?php if ($mp_payout_setting_payoneer == 1) { ?>
                        <div class="PayoutMethod_container <?php if ($type == 'Payoneer') {
                                                                echo 'active';
                                                            } ?>" data-value="Payoneer">

                            <div class="PayoutMethod_container_selected">

                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/earnings/Path_1271.svg' ?>" alt="Icon">

                            </div>

                            <div class="PayoutMethod">

                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/earnings/payoutsmethod/38996.svg' ?>" alt="Icon">

                            </div>

                            <p><?php esc_html_e('Payout with Payoneer', 'medicalpro'); ?></p>

                        </div>
                    <?php } ?>

                    <?php if ($mp_payout_setting_stripe == 1) { ?>
                        <div class="PayoutMethod_container <?php if ($type == 'Stripe') {
                                                                echo 'active';
                                                            } ?>" data-value="Stripe">

                            <div class="PayoutMethod_container_selected">

                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/earnings/Path_1271.svg' ?>" alt="Icon">

                            </div>

                            <div class="PayoutMethod">

                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/earnings/payoutsmethod/stripe.svg' ?>" alt="Icon">

                            </div>

                            <p><?php esc_html_e('Payout with Stripe', 'medicalpro'); ?></p>

                        </div>
                    <?php } ?>

                    <?php if ($mp_payout_setting_bank == 1) { ?>
                        <div class="PayoutMethod_container <?php if ($type == 'Bank') {
                                                                echo 'active';
                                                            } ?>" data-value="Bank">

                            <div class="PayoutMethod_container_selected">

                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/earnings/Path_1271.svg' ?>" alt="Icon">

                            </div>

                            <div class="PayoutMethod">

                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/earnings/payoutsmethod/776510.svg' ?>" alt="Icon">

                            </div>

                            <p><?php esc_html_e('Payout with Bank', 'medicalpro'); ?></p>

                        </div>
                    <?php } ?>

                    <?php if ($mp_payout_setting_other == 1) { ?>
                        <div class="PayoutMethod_container <?php if ($type == 'Other') {
                                                                echo 'active';
                                                            } ?>" data-value="Other">

                            <div class="PayoutMethod_container_selected">

                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/earnings/Path_1271.svg' ?>" alt="Icon">

                            </div>

                            <div class="PayoutMethod">

                                <img src="<?php echo MP_PLUGIN_DIR . 'assets/images/earnings/payoutsmethod/776510.svg' ?>" alt="Icon">

                            </div>

                            <p><?php esc_html_e('Payout with Other method', 'medicalpro'); ?></p>

                        </div>
                    <?php } ?>
                    <!-- End Updated by abbas -->

                </div>
                <form class="PayoutMethodModalForm"></form>
            </div>
        </div>
    </div>
</div>