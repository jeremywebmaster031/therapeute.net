<?php
class MP_Earnings_Table extends WP_List_Table{

    public function get_columns(){
        $columns = array(
            'order_id'         => __('Receipt/invoice#', 'medicalpro'),
            'doctor_name'      => __('Doctor Name', 'medicalpro'),
            'doctor_email'     => __('Doctor Email', 'medicalpro'),
            'hospital_name'    => __('Hospital Name', 'medicalpro'),
            'patient_name'     => __('Patient Name', 'medicalpro'),
            'patient_email'    => __('Patient Email', 'medicalpro'),
            'sub_total'        => __('Doctor Payable Amount', 'medicalpro'),
            'commision_price'  => __('Admin Commision', 'medicalpro'),
            'paid_price'       => __('Total Amount', 'medicalpro'),
            'order_date'       => __('Date', 'medicalpro'),
        );
        return $columns;
    }

    function column_default($item, $column_name){
        return isset($item[$column_name]) ? $item[$column_name] : '-';
    }

    function get_sortable_columns(){
        $sortable_columns = array(
            'order_id'   => array('Receipt/invoice#', true),
            'paid_date'  => array('Date', true),
        );
        return $sortable_columns;
    }

    public function prepare_items(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'booking_orders';

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);

        $total_items = $wpdb->get_var("SELECT COUNT(main_id) FROM $table_name");
        $paged       = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby     = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'main_id';
        $order       = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $per_page = 1;
        $items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name  ", $per_page, $paged), ARRAY_A);
        //$this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT 100 OFFSET 10 ", $per_page, $paged), ARRAY_A);
        $_data = array();
        foreach($items as $item){

            $currency          = isset($item['currency']) ? $item['currency'] : listingpro_currency_sign();
            $user_id           = isset($item['user_id']) ? $item['user_id'] : '';
            $post_id           = isset($item['user_id']) ? $item['post_id'] : '';
            $paid_price        = isset($item['paid_price']) ? $item['paid_price'] : '';
            $sub_total         = isset($item['sub_total']) ? $item['sub_total'] : '';
            $commision_rate    = isset($item['commision_rate']) ? $item['commision_rate'] : '';
            $commision_price   = isset($item['commision_price']) ? $item['commision_price'] : '';
            $taxrate           = isset($item['taxrate']) ? $item['taxrate'] : '';
            $taxprice          = isset($item['taxprice']) ? $item['taxprice'] : '';
            $date              = isset($item['date']) ? date(get_option('date_format'), $item['date']) : '';
            $user_data         = get_userdata($user_id);
            $author_id         = get_post_field('post_author', $post_id);
            $author_data       = get_userdata($author_id);
            $currency_position = lp_theme_option('pricingplan_currency_position');

            $_sub_total = $currency.$sub_total;
            $_commision_price = $currency.$commision_price;
            $_paid_price = $currency.$paid_price;
            if($currency_position == 'right'){
                $_sub_total = $sub_total.$currency;
                $_commision_price = $commision_price.$currency;
                $_paid_price = $paid_price.$currency;
            }

            $results['order_id']         = $item['order_id'];
            $results['doctor_name']      = esc_html(get_the_title($post_id));
            $results['doctor_email']     = isset($author_data->user_email) ? esc_html($author_data->user_email) : '-';
            $results['hospital_name']    = esc_html($item['hospital_name']);
            $results['patient_name']     = isset($user_data->display_name) ? esc_html($user_data->display_name) : '-';
            $results['patient_email']    = isset($user_data->user_email) ? esc_html($user_data->user_email) : '-';
            $results['sub_total']        = $_sub_total;
            $results['commision_price']  = (isset($commision_price) && $commision_price > 0) ? $_commision_price .' ('. $commision_rate .'%)' : '-';
            $results['paid_price']       = $_paid_price;
            $results['order_date']       = $date;
            $_data[] = $results;
        }

        $this->items = $_data;

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }

    public function no_items() {
        esc_html_e('No earnings found.', 'medicalpro');
    }

}