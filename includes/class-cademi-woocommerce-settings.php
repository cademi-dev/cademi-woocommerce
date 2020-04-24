<?php

class CademiWoocommerceSettings 
{
    static public $option_key = 'cademi_woocommerce_settings';
    static public $defaults = [
        'cademi_woocommerce_url' => '',
        'cademi_woocommerce_token' => '',
        'cademi_woocommerce_status_aprovado' => '',
        'cademi_woocommerce_status_disputa' => ''
    ];

    public static function get()
    {
        $options    = self::$defaults;
        $options_wp = get_option(self::$option_key);

        if( ! $options_wp ) {
            add_option( self::$option_key, $options );
            $options_wp = $options;
        }

        return $options_wp;
    }

    public static function set( $data )
    {
        if( ! isset($data['cademi-woocommerce-nonce']) || wp_verify_nonce($data['cademi-woocommerce-nonce'])) {
            echo '<div class="alert alert-error text-center m-4">'. __( 'Erro ao salvar definições.', 'cademi-woocommerce' ).'</div>';
            return $data['data'];
        }

        update_option(self::$option_key, array_merge(self::get(), $data['data']));
        echo '<div class="alert alert-success text-center m-4">'. __( 'Definições salvas.', 'cademi-woocommerce' ).'</div>';

        return self::get();
    }
}