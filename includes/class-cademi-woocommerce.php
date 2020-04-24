<?php 

class CademiWoocommerce 
{
    protected $loader;
    public function __construct() {

        // if (WP_DEBUG) {
            error_reporting(E_ALL | E_WARNING | E_NOTICE);
            ini_set("display_errors", TRUE);
        // }

        // Loader & Settings
        require_once plugin_dir_path(dirname(__FILE__)) . "includes/class-cademi-woocommerce-loader.php";
        require_once plugin_dir_path(dirname(__FILE__)) . "includes/class-cademi-woocommerce-settings.php";
        $this->loader = new CademiWoocommerceLoader();
        
        // Admin
        require_once plugin_dir_path(dirname(__FILE__)) . "admin/class-cademi-woocommerce-admin.php";
        new CademiWoocommerceAdmin($this->loader);

        // Core
        require_once plugin_dir_path(dirname(__FILE__)) . "includes/class-cademi-woocommerce-core.php";
        new CademiWoocommerceCore($this->loader);
    }
    
    public function run() 
    {
        $this->loader->run();
    }

    public function get_plugin_name() 
    {
        return $this->plugin_name;
    }

    public function get_loader() 
    {
        return $this->loader;
    }

    public function get_version() 
    {
        return $this->version;
    }

}