<?php

class MAX_BOL_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

        wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );

	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name,   plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );

	}
	/**
	 * Crone job time Account 1.
	 *
	 * @since    1.0.0
	 */
    public function max_bol_shedule_cron_job_account_1( $schedules ) {
        $schedules['tenminute'] = array(
            'display' => __( 'Every 10 minute', 'woocommerce' ),
            'interval' => 600,
        );
        $schedules['thirtyminute'] = array(
            'display' => __( 'Every 30 minute', 'woocommerce' ),
            'interval' => 1800,
        );
        $schedules['onehour'] = array(
            'display' => __( 'Every 1 Hour', 'woocommerce' ),
            'interval' => 3600,
        );
        $schedules['fifteendays'] = array(
            'display' => __( 'Every 15 Days', 'woocommerce' ),
            'interval' => 1296000,
        );
        $schedules['monthly'] = array(
            'display' => __( 'Monthly', 'woocommerce' ),
            'interval' => 2592000 ,
        );
        return $schedules;
    }
	/**
	 * Crone job time Account 2.
	 *
	 * @since    1.0.0
	 */
    public function max_bol_shedule_cron_job_account_2( $schedules ) {
        $schedules['tenminute'] = array(
            'display' => __( 'Every 10 minute', 'woocommerce' ),
            'interval' => 600,
        );
        $schedules['thirtyminute'] = array(
            'display' => __( 'Every 30 minute', 'woocommerce' ),
            'interval' => 1800,
        );
        $schedules['onehour'] = array(
            'display' => __( 'Every 1 Hour', 'woocommerce' ),
            'interval' => 3600,
        );
        $schedules['fifteendays'] = array(
            'display' => __( 'Every 15 Days', 'woocommerce' ),
            'interval' => 1296000,
        );
        $schedules['monthly'] = array(
            'display' => __( 'Monthly', 'woocommerce' ),
            'interval' => 2592000 ,
        );
        return $schedules;
    }
    /**
     * Schedule Cron Job Event account 1
     *
     * @since    1.0.0
     */
    public function max_get_bol_orders_list_cron_job_callback_account_1() {

        $access_token   = '';
        $user_details   = [];
        $option         = get_option( 'max_bol_plugin_settings' );
        $account_id     = $option['max_bol_plugin_account1_option'];
        $user_name      = $option['max_bol_plugin_client_id_option'];
        $user_pass      = $option['max_bol_plugin_client_secret_key_option'];
        $token_url      = $option['max_bol_plugin_api_link_option']."/token?grant_type=client_credentials";
        $orders_url     = $option['max_bol_plugin_api_link_option1']."/retailer/orders?fulfilment-method=FBR&state=ALL";
        $single_url     = $option['max_bol_plugin_api_link_option1'];

        $this->max_get_bol_orders_list_function($access_token, $account_id, $user_name, $user_pass, $token_url, $orders_url, $single_url);

    }
    /**
     * Schedule Cron Job Event account 2
     *
     * @since    1.0.0
     */
    public function max_get_bol_orders_list_cron_job_callback_account_2() {

        $access_token   = '';
        $option         = get_option( 'max_bol_plugin_settings' );
        $account_id     = $option['max_bol_plugin_account2_option'];
        $user_name      = $option['max_bol_plugin_client_id1_option'];
        $user_pass      = $option['max_bol_plugin_client_secret_key1_option'];
        $token_url      = $option['max_bol_plugin_api_link_option']."/token?grant_type=client_credentials";
        $orders_url     = $option['max_bol_plugin_api_link_option1']."/retailer/orders?fulfilment-method=FBR&state=ALL";
        $single_url     = $option['max_bol_plugin_api_link_option1'];
        $res = $this->max_get_bol_orders_list_function($access_token, $account_id, $user_name, $user_pass, $token_url, $orders_url, $single_url);
    }
    /**
     * get api response and add orders
     *
     * @since    1.0.0
     */
    public function max_get_bol_orders_list_function($access_token, $account_id, $user_name, $user_pass, $token_url, $orders_url, $single_url) {


        global $wpdb;
        $token_response         = $this->max_get_curl_api_response($token_url, $user_name, $user_pass, 'POST', $access_token);
        $token_response         = json_decode($token_response, true);
        $access_token           = (isset($token_response['access_token']) && !empty($token_response['access_token'])) ? $token_response['access_token'] : '';
        $url                    = $single_url . "/retailer/orders/";
        if(!empty($access_token)) {
            $api_res    = 200;
            $orders     = $this->max_get_curl_api_response($orders_url, $user_name, $user_pass, 'GET', $access_token);
            $orders_res = json_decode($orders, true);
            if(!empty($orders_res)) {
                $access_token = '';
                $token_response         = $this->max_get_curl_api_response($token_url, $user_name, $user_pass, 'POST', $access_token);
                $token_response         = json_decode($token_response, true);
                $access_token           = (isset($token_response['access_token']) && !empty($token_response['access_token'])) ? $token_response['access_token'] : '';
                foreach ($orders_res['orders'] as $order_res) {
                    $bol_order_id   = $order_res['orderId'];
                    $query          = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'bol_order_id' AND meta_value = $bol_order_id";
                    $ids            = $wpdb->get_results($query);
                    $single_url     = $url . $bol_order_id;
                    $response       = $this->max_get_curl_api_response($single_url, $user_name, $user_pass, 'GET', $access_token);

                    $response = json_decode($response, true);
                    $product_details = [];
                    if (empty($ids)) {
                        $billing = array(
                            'first_name'    => $response['billingDetails']['firstName'],
                            'last_name'     => $response['billingDetails']['surname'],
                            'email'         => $response['billingDetails']['email'],
                            'address_1'     => $response['billingDetails']['streetName'].' '.$response['billingDetails']['houseNumber'].' '.$response['billingDetails']['houseNumberExtension'],
                            'city'          => $response['billingDetails']['city'],
                            'postcode'      => $response['billingDetails']['zipCode'],
                            'country'       => $response['billingDetails']['countryCode'],
                            'company'       => $response['billingDetails']['company']
                        );
                        $shipping = array(
                            'first_name'    => $response['shipmentDetails']['firstName'],
                            'last_name'     => $response['shipmentDetails']['surname'],
                            'email'         => $response['shipmentDetails']['email'],
                            'address_1'     => $response['shipmentDetails']['streetName'].' '.$response['shipmentDetails']['houseNumber'].' '.$response['shipmentDetails']['houseNumberExtension'],
                            'city'          => $response['shipmentDetails']['city'],
                            'postcode'      => $response['shipmentDetails']['zipCode'],
                            'country'       => $response['shipmentDetails']['countryCode'],
                            'company'       => $response['shipmentDetails']['company'],
                        );

                        $order      = wc_create_order();
                        $order_id   = $order->get_id();
                        $order->set_address($billing, 'billing');
                        $order->set_address($shipping, 'shipping');
                        update_post_meta($order_id, 'bol_order_id', $bol_order_id);
                        update_post_meta($order_id, 'bol_account_id', $account_id);
                        update_post_meta($order_id, 'wpml_language', $response['shipmentDetails']['language']);
                        if(empty($response['billingDetails']) && empty($response['shipmentDetails'])){
                            $order->add_order_note( 'The user details are empty ');
                        }
                        foreach ($response['orderItems'] as $single) {
							$product_id 	= '';
                            $bol_ean_code   = $single['product']['ean'];
                            $reference      = $single['offer']['reference'];
                            $ean_query      = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_wpm_gtin_code' AND meta_value = $bol_ean_code";
                            $ean_ids        = $wpdb->get_results($ean_query);
                            $sku_ids        = wc_get_product_id_by_sku( $reference );
                            $arg            = array();
                            if (!empty($ean_ids)) {
                                $product_id = $ean_ids[0]->post_id;
                            }
                            else if (!empty($sku_ids)) {
                                $product_id = $sku_ids;
                            }
                            else {
                                $order->add_order_note( 'The product with ean '.$bol_ean_code.' or reference '.$reference.' not found' );
                                $api_res = 203;//item not found
                            }
                            if(!empty($product_id)){
                            	$product = wc_get_product( $product_id );
                                $price = $single['unitPrice'];
                                $product->set_price( $price );
                                $order->add_product($product, $single['quantity'], $arg);
                                $api_res = 200;
                            }
                        }
                        $order->set_prices_include_tax(1);
                        $order->calculate_totals();
						$order->update_status('processing');
                        $order->save();
                        clean_post_cache( $order->get_id() );
                        wc_delete_shop_order_transients( $order );
                        wp_cache_delete( 'order-items-' . $order->get_id(), 'orders' );
                    }
//                    else {
//                        $order_id = $ids[0]->post_id;
//                        $order = wc_get_order( $order_id );
//                        update_post_meta($order_id, 'bol_order_id', $bol_order_id);
//                        update_post_meta($order_id, 'bol_account_id', $account_id);
                        //update_post_meta($order_id, 'wpml_language', $response['shipmentDetails']['language']);
//                        foreach ($order->get_items() as $item_id => $item) {
//                            wc_delete_order_item($item_id);
//                        }
//                        $order->set_prices_include_tax(1);
//                        $order->calculate_totals();
//                        foreach ($response['orderItems'] as $single) {
//                            $bol_ean_code = $single['product']['ean'];
//                            $ean_query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_wpm_gtin_code' AND meta_value = $bol_ean_code";
//                            $ean_ids = $wpdb->get_results($ean_query);
//                            if (!empty($ean_ids)) {
//                                $product_id = $ean_ids[0]->post_id;
//                                $product_details['id'] = $product_id;
//                                $product_details['quan'] = $single['quantity'];
//                                $product_details['price'] = $single['unitPrice'];
//                                $product = wc_get_product( $product_id );
////                                $price = $single['unitPrice'] - $single['commission'];
//                                $price = $single['unitPrice'];
//                                $product->set_price( $price );
//                                $arg= array();
//                                $arg['subtotal'] =  $price *  $single['quantity'] ;
//                                $arg['total'] =  $price *  $single['quantity'] ;
//                                $order->add_product($product, $single['quantity'], $arg);
//                                //$order->set_tax_class(0);
//                                $api_res = 200;
//                            } else {
//                                $order->add_order_note( 'The product with ean '.$bol_ean_code.' not found' );
//                                $api_res = 203;//item not found
//                            }
//                        }
//                    }
//                    $order_id = 809; // Static order Id (can be removed to get a dynamic order ID from $order_id variable)
//
//                    $order = wc_get_order( $order_id ); // The WC_Order object instance
//
//// Loop through Order items ("line_item" type)
//                    foreach( $order->get_items() as $item_id => $item ){
//                        $new_product_price = 50; // A static replacement product price
//                        $product_quantity = (int) $item->get_quantity(); // product Quantity
//
//                        // The new line item price
//                        $new_line_item_price = $new_product_price * $product_quantity;
//
//                        // Set the new price
//                        $item->set_subtotal( $new_line_item_price );
//                        $item->set_total( $new_line_item_price );
//
//                        // Make new taxes calculations
//                        $item->calculate_taxes();
//
//                        $item->save(); // Save line item data
//                    }
//// Make the calculations  for the order and SAVE
//                    $order->calculate_totals();
                }
            }
            else{
                $api_res = 202; //orders are empty
            }
        }
        else{
            $api_res = 201; //access token missing
        }
        return $api_res;

    }
    /**
     * Schedule Cron Job Event Ajax Call
     *
     * @since    1.0.0
     */
    public function max_import_orders_from_api() {
        $access_token   = '';
        $token_url      = $_POST['token_url'];
        $account_id     = $_POST['account_id'];
        $user_name      = $_POST['user_name'];
        $user_pass      = $_POST['password'];
        $orders_url     = $_POST['orders_url'];
        $single_url     = $_POST['single_url'];
        $account        = $_POST['account'];
//        echo $this->max_get_bol_orders_list_function($access_token, $account_id, $user_name, $user_pass, $token_url, $orders_url, $single_url);
//        die();
        $arg= array(false);
        if($account == 1){
            wp_schedule_single_event( time()+30 , 'max_bol_shedule_cron_job_account_1_as' , $arg );
        }
        if($account == 2){
            wp_schedule_single_event( time()+50 , 'max_bol_shedule_cron_job_account_2_as', $arg );
        }
        echo 200;
        die();
    }
    /**
     * Schedule Cron Job Event Account 1
     *
     * @since    1.0.0
     */
    public function max_get_bol_orders_list_cron_job_account_1() {

        $options = get_option( 'max_bol_plugin_settings' );
        $schedules = $options['max_bol_plugin_crone_time_option'];
        if ( ! wp_next_scheduled( 'max_bol_shedule_cron_job_account_1_as' ) ) {
            wp_schedule_event( time(), $schedules ,   'max_bol_shedule_cron_job_account_1_as' );
        }

        if ( ! wp_next_scheduled( 'max_bol_shedule_cron_job_account_2_as' ) ) {
            wp_schedule_event( time(), $schedules ,   'max_bol_shedule_cron_job_account_2_as' );
        }

    }
    /**
     * Schedule Cron Job Event Account 2
     *
     * @since    1.0.0
     */
    public function max_get_bol_orders_list_cron_job_account_2() {



    }
    /**
     * Add bol account id column in shop order table
     *
     * @since    1.0.0
     */
    public function max_add_column_order_table_function( $columns ) {

        $new_columns = [];
        foreach ($columns as $key => $column){
            $new_columns[$key]  = $column;
            if($key == 'order_status'){
                $new_columns['bol_account_id'] = 'Bol Account ID ';
            }
        }
		if (!array_key_exists("bol_imported",$new_columns)){
            $columns = $new_columns;
            $new_columns = [];
            foreach ($columns as $key => $column){
                $new_columns[$key]  = $column;
                if($key == 'order_status'){
                    $new_columns['max_bol_imported'] = 'Bol.com';
                }
            }
        }
        return $new_columns;

    }
    /**
     * get data of bol account id column in shop order table
     *
     * @since    1.0.0
     */
    public function max_add_column_data_order_table_function( $column ) {

        global $post;
        $order = wc_get_order( $post->ID );
        if ( 'bol_account_id' === $column ) {
            echo (!empty(get_post_meta($post->ID, 'bol_account_id', true))) ? get_post_meta($post->ID, 'bol_account_id', true) : '';
        }
		if ( 'max_bol_imported' === $column ) {
            echo (!empty(get_post_meta($post->ID, 'bol_order_id', true))) ? '#'.get_post_meta($post->ID, 'bol_order_id', true) : '';
        }

    }
    /**
     * get orders details
     *
     * @since    1.0.0
     */
    public function max_get_curl_api_response($url, $user_name, $user_pass, $type, $access_token) {
        $curl   = curl_init();
        if(!empty($access_token)){
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $user_name . ':' . $user_pass,
                CURLOPT_CUSTOMREQUEST => $type,
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/vnd.retailer.v6+json",
                    "Authorization: Bearer ".$access_token
                ),
            ));
        }
        else{
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $user_name . ':' . $user_pass,
                CURLOPT_CUSTOMREQUEST => $type,
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/vnd.retailer.v6+json"
                ),
            ));
        }
        $response              = curl_exec($curl);
        $orders                = json_decode($response, true);
        $err = curl_error($curl);
        curl_close($curl);
        return ($response);
    }

}