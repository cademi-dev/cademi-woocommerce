<?php

/**
 * @link              http://cademi.com.br
 * @since             1.0
 * @package           CademiWoocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Cademí - WooCommerce
 * Plugin URI:        https://cademi.com.br
 * Description:       Integre sua loja WooCommerce com a Cademí
 * Version:           1.0.0
 * Author:            Cademí
 * Author URI:        https://cademi.com.br
 * Text Domain:       cademi-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


// add_action('woocommerce_order_status_completed', 'checkOrder', 0);
// add_action('woocommerce_order_status_on-hold', 'checkOrder', 0);
// function checkOrder($order_id)
// {
// 	echo '<pre>';
// 	$order = wc_get_order( $order_id );

// 	foreach( $order->get_items()  as $item ) {

// 		// echo var_dump($item->get_product_id());
// 		// exit;

// 		if(has_term( 'sim', '_cademi_entrega_check', $item->get_product_id() ))
// 			echo "aleluia";

// 		exit;


// 	}



// 	echo var_dump($order);
// 	exit;
// }




// run
require plugin_dir_path( __FILE__ ) . 'includes/class-cademi-woocommerce.php';
function run_cademi_redirect() 
{
	$plugin = new CademiWoocommerce();
	$plugin->run();
}

run_cademi_redirect();