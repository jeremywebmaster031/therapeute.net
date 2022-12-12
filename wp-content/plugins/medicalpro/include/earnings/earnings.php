<?php
class MedicalPro_Earnings {
    public function __construct() {
        add_action('admin_menu', array($this, 'medicalpro_booking_earnings_submenu'), 30);
    }

    public function medicalpro_booking_earnings_submenu(){
        add_submenu_page(
            'edit.php?post_type=medicalpro-bookings',
            esc_html__('Earnings', 'medicalpro'),
            esc_html__('Earnings', 'medicalpro'),
            'manage_options',
            'lp-earnings',
            array($this, 'medicalpro_booking_earnings_callback'),
            1
        );
    }

    public function medicalpro_booking_earnings_callback(){

        $mpEarningsTable = new MP_Earnings_Table();
        $mpEarningsTable->prepare_items();
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline" style="margin-bottom: 25px"><?php esc_html_e('Earnings', 'medicalpro'); ?></h1>
            <?php $this->medicalpro_booking_earnings_callback2(); ?>
            <?php $mpEarningsTable->display(); ?>
        </div>
        <?php
    }

    public function medicalpro_booking_earnings_callback2(){
        global $wpdb, $listingpro_options;
        $currencySymbol = listingpro_currency_sign();
        $table = $wpdb->prefix.'booking_orders';
        $orders = array();
        $orderCount = 0;
        if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
            $query = "";
            $query = "SELECT * from $table ORDER BY main_id DESC";
            $orders = $wpdb->get_results($query);
            $orderCount = count($orders);
        }
        $countWithdrawals = 0;
        $args = array(
            'post_type' => 'lp-withdrawal',
            'meta_query' => array(
                array(
                    'key'     => 'lp_withdrawal_requested_status',
                    'value'     =>  'rejected',
                    'compare'   =>  '!='
                ),
            ),
        );
        $the_query = new WP_Query( $args );
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $value = get_post_meta(get_the_ID(), 'lp_withdrawal_requested_amount', true);
                if (!empty($value) && is_numeric($value)){
                    $countWithdrawals = $countWithdrawals + $value;
                }
            }
        }
        $totalEarnings = 0;
        if (!empty($orders) && is_array($orders)) {
            foreach ($orders as $key => $value) {
                if (!empty($value->paid_price) && is_numeric($value->paid_price)) {
                    $totalEarnings = $totalEarnings + $value->paid_price;
                }
            }
        }

        $availableBalance = 0;
        if (is_numeric($totalEarnings) && is_numeric($countWithdrawals)) {
            $availableBalance = $totalEarnings - $countWithdrawals;
        }


        ?>

        <div class="lp_mp_earnings_dashboard_total_booking_container">
            <div class="lp_mp_earnings_dashboard_total_bookings">
                <div class="clearfix"></div>
                <div class="display-inline-block pull-left">
                    <h3><?php esc_html_e('Total Online Bookings', 'medicalpro') ?></h3>
                    <p><?php esc_html_e('This section only contains video consultation appointments.', 'medicalpro') ?></p>
                    <h1><?php echo esc_html($orderCount) ?></h1>
                </div>
                <div class="display-inline-block pull-right">
                    <img src="<?php echo MP_PLUGIN_DIR.'/assets/images/earnings.png' ?>" alt="Doctor" width="100%" height="100%">
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="lp_mp_earnings_dashboard_cards_container row">
                <div class="col-md-4">
                    <div class="lp_mp_earnings_dashboard_card lp_mp_earnings_dashboard_avaliable_balance">
                        <div class="lp_mp_earnings_dashboard_card_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
                                <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499L12.136.326zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484L5.562 3zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z"/>
                            </svg>
                        </div>
                        <h3><?php esc_html_e('Available Balance', 'medicalpro') ?></h3>
                        <h2><?php echo $currencySymbol . $availableBalance; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="lp_mp_earnings_dashboard_card lp_mp_earnings_dashboard_total_earnings">
                        <div class="lp_mp_earnings_dashboard_card_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash-stack" viewBox="0 0 16 16">
                                <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                                <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z"/>
                            </svg>
                        </div>
                        <h3><?php esc_html_e('Total Earnings', 'medicalpro') ?></h3>
                        <h2><?php echo $currencySymbol . $totalEarnings; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="lp_mp_earnings_dashboard_card lp_mp_earnings_dashboard_total_withdrawls">
                        <div class="lp_mp_earnings_dashboard_card_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash-stack" viewBox="0 0 16 16">
                                <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                                <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z"/>
                            </svg>
                        </div>
                        <h3><?php esc_html_e('Total Withdrawls', 'medicalpro') ?></h3>
                        <h2><?php echo $currencySymbol . $countWithdrawals; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <h1 class="wp-heading-inline" style="float: left;margin-bottom: -35px;"><?php esc_html_e('Recent Earnings', 'medicalpro'); ?></h1>

        <?php

        return;
    }

}
new MedicalPro_Earnings();
