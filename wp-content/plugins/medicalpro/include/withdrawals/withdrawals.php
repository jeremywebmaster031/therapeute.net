<?php
class MedicalPro_Withdrawals {
    public function __construct() {
        add_action('init', array($this, 'medicalpro_register_withdrawals'), 31);
    }
    
    public function medicalpro_register_withdrawals(){
        
        $labels = array(
            'name' => _x('Withdrawals', 'post type general name', 'medicalpro'),
            'singular_name' => _x('Withdrawal', 'post type singular name', 'medicalpro'),
            'menu_name' => _x('Withdrawals', 'admin menu', 'medicalpro'),
            'name_admin_bar' => _x('Withdrawal', 'add new on admin bar', 'medicalpro'),
            'add_new' => _x('Add New', 'Withdrawal', 'medicalpro'),
            'add_new_item' => __('Add New Withdrawal', 'medicalpro'),
            'new_item' => __('New Withdrawal', 'medicalpro'),
            'edit_item' => __('Edit Withdrawal', 'medicalpro'),
            'view_item' => __('View Withdrawal', 'medicalpro'),
            'all_items' => __('Withdrawals', 'medicalpro'),
            'search_items' => __('Search Withdrawals', 'medicalpro'),
            'parent_item_colon' => __('Parent Withdrawal:', 'medicalpro'),
            'not_found' => __('No Withdrawals found.', 'medicalpro'),
            'not_found_in_trash' => __('No Withdrawal found in Trash.', 'medicalpro')
        );
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => 'edit.php?post_type=medicalpro-bookings',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'LP-Withdrawal' ),
            'capability_type'    => 'post',
            'capabilities' => array(
                'create_posts' => 'do_not_allow',
            ),
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 2,
            'supports'           => array( 'title', 'editor', 'author' ),
        );
        register_post_type( 'lp-withdrawal', $args );
    }
}
new MedicalPro_Withdrawals();


add_filter('manage_lp-withdrawal_posts_columns', 'lp_withdrawal_admin_columns');
if(!function_exists('lp_withdrawal_admin_columns')){
    function lp_withdrawal_admin_columns($columns){
        unset($columns['cb']);
        unset($columns['title']);
        unset($columns['author']);
        unset($columns['date']);
        $columns['doctor'] = esc_html__('Requested By', 'medicalpro');
        $columns['amount'] = esc_html__('Requested Amount', 'medicalpro');
        $columns['status'] = esc_html__('Status', 'medicalpro');
        $columns['action'] = esc_html__('Action', 'medicalpro');
        $columns['date'] = esc_html__('Date', 'medicalpro');
        return $columns;
    }
}
add_action('manage_lp-withdrawal_posts_custom_column', 'lp_withdrawal_columns_callback', 10, 2);
if(!function_exists('lp_withdrawal_columns_callback')){
    function lp_withdrawal_columns_callback($column, $post_id){
        if ($column == 'doctor') {
            $userID = get_post_field('post_author', $post_id);
            echo '<a href="'.get_author_posts_url( $userID ).'" target="_blank">'.get_userdata($userID)->user_login.'</a>';
        }elseif ($column == 'amount') {
            $currencySymbol = listingpro_currency_sign();
            $value = get_post_meta(get_the_ID(), 'lp_withdrawal_requested_amount', true);
            echo $currencySymbol.$value;
        }elseif ($column == 'status') {
            $status = get_post_meta($post_id, 'lp_withdrawal_requested_status', true);
            if (empty($status)){
                $status = 'unpaid';
            }
            echo '<div class="lp_single_withdrawal_status lp_single_withdrawal_status_'.$status.'">'.$status.'</div>';
        }elseif ($column == 'action'){
            global $wpdb, $listingpro_options;
            $currencySymbol = listingpro_currency_sign();
            $table = $wpdb->prefix.'booking_orders';
            $userID = get_post_field('post_author', $post_id);
            $orders = array();
            $orderCount = 0;
            if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
                $query = "";
                $query = "SELECT * from $table WHERE doctor_id = $userID ORDER BY main_id DESC";
                $orders = $wpdb->get_results($query);
                $orderCount = count($orders);
            }
            $userData = mp_get_user_wallet_data($userID);
            $countWithdrawals = 0;
            $args = array(
                'post_type' => 'lp-withdrawal',
                'meta_query' => array(
                    array(
                        'key'     => 'lp_withdrawal_requested_status',
                        'value'   => 'paid',
                        'compare' => '=',
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
            $status = get_post_meta($post_id, 'lp_withdrawal_requested_status', true);
            if (empty($status)){
                $status = 'unpaid';
            }

            $dataArr = get_user_meta($userID, 'mp_user_payouts', true);
            $accountType = null;
            $accountTitle = null;
            $accountNumber = null;
            $accountDetail = null;
            if (!empty($dataArr) && is_array($dataArr)) {
                $accountType = $dataArr['accountType'];
                $accountTitle = $dataArr['accountTitle'];
                $accountNumber = $dataArr['accountNumber'];
                $accountDetail = $dataArr['accountDetail'];
            }

            if ($status == 'unpaid'){
                $actionHTML = null;
                $actionHTML .= '
                    <div class="lp_withdrawal_action_modal_content">
                        <button class="lp_withdrawal_action_btn">'.esc_html__('Take Action', 'medicalpro').'</button>
                        <div class="lp_withdrawal_action_modal_container">
                            <div class="lp_withdrawal_action_modal">
                                <div class="lp_withdrawal_action_modal_content">
                                    <h1 class="lp_withdrawal_action_modal_content_title">'.esc_html__('Complete This Request', 'medicalpro').'</h1>
                                    <div class="lp_withdrawal_action_modal_content_detail_input">
                                        <label for="lp_withdrawal_available_balance">'.esc_html__('User Available Balance', 'medicalpro').'</label>
                                        <input type="text" id="lp_withdrawal_available_balance" disabled value="'.$currencySymbol.$userData['availableBalance'].'">
                                    </div> 
                                    <div class="lp_withdrawal_action_modal_content_detail_input">
                                        <label for="lp_withdrawal_available_balance">'.esc_html__('Requested Amount By User', 'medicalpro').'</label>
                                        <input type="text" id="lp_withdrawal_available_balance" disabled value="'.$currencySymbol.get_post_meta($post_id, "lp_withdrawal_requested_amount", "true").'">
                                    </div>
                                    <div class="lp_withdrawal_action_modal_content_detail_input">
                                        <label for="lp_withdrawal_available_balance">'.esc_html__('Payout Details', 'medicalpro').'</label>
                                        <div class="lp_withdrawal_action_modal_payout_detail">
                                            '.$accountType.' <br>
                                            '.$accountTitle.' <br>
                                            '.$accountNumber.' <br>
                                            '.$accountDetail.'
                                        </div>
                                    </div>
                                    <div class="lp_withdrawal_action_modal_content_detail_input">
                                        <label for="lp_withdrawal_available_balance">'.esc_html__('Request Detail', 'medicalpro').'</label>
                                        <div class="lp_withdrawal_action_modal_content_detail">
                                            '.get_the_content('', '', $post_id).'
                                        </div>
                                    </div>
                                    <button class="lp_withdrawal_action_modal_confirm" data-postid="'.$post_id.'"><i class="fa fa-credit-card" aria-hidden="true"></i> '.esc_html__('Complete Request', 'medicalpro').'</button>
                                    <button class="lp_withdrawal_action_modal_reject" data-postid="'.$post_id.'"><i class="fa fa-remove" aria-hidden="true"></i> '.esc_html__('Reject Request', 'medicalpro').'</button>
                                </div>
                            </div>                            
                        </div>
                    </div>
                ';
                echo $actionHTML;
            }else{
                echo esc_html__('Action already taken', 'medicalpro');
            }
        }
    }
}