<?php

class CademiWoocommerceAdmin 
{
	public function __construct($loader) 
	{
		$this->options = CademiWoocommerceSettings::get();

        $loader->add_action("admin_enqueue_scripts", $this, "enqueue_scripts", 10);
        $loader->add_action("admin_menu", $this, "add_plugin_admin_menu");
	}

	public function enqueue_scripts() 
	{
        if( get_current_screen()->id == 'toplevel_page_cademi-woocommerce' ){
            wp_register_style('Bootstrap4.6.3', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' );
            wp_enqueue_style('Bootstrap4.6.3');
        }
	}

	public function add_plugin_admin_menu() 
	{
        add_menu_page(
            '',
            'Cademí - WooCommerce',
            'manage_options',
            'cademi-woocommerce',
            array( $this, 'display_plugin_admin_page' ),
            'dashicons-randomize',
            90
        );   
    }

    public function display_plugin_admin_page()
    {
        if(!empty($_POST))
            $this->options = CademiWoocommerceSettings::set($_POST);

    	include_once( plugin_dir_path(__FILE__) . 'partials/admin.php' );
    }
}