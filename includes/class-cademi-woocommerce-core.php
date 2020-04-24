<?php

class CademiWoocommerceCore
{
	public function __construct($loader)
	{
		$this->options = CademiWoocommerceSettings::get();
		$loader->add_action('init', $this, 'taxonomyCreate');
		$loader->add_action('pre_insert_term', $this, 'taxonomyBlock', 0, 2);
		$loader->add_action('woocommerce_order_status_changed', $this, 'checkOrder', 0);
	}

	public function checkOrder($order_id)
	{
		$order = wc_get_order( $order_id );

		if( 
			empty($this->options['cademi_woocommerce_status_aprovado']) || 
			empty($this->options['cademi_woocommerce_status_disputa'])
		){
			$order->add_order_note('<font style="color:red">Cademí - Essa compra não foi comunicada; configure seus status corretamente.</font>');
			return;
		}

		if( strpos($this->options['cademi_woocommerce_status_aprovado'], $order->get_status()) !== false )
			$status = "aprovado";

		if( strpos($this->options['cademi_woocommerce_status_disputa'], $order->get_status()) !== false )
			$status = "disputa";

		if( ! isset($status))
			return; 

		foreach( $order->get_items()  as $item ) {
			if(has_term( 'sim', '_cademi_entrega_check', $item->get_product_id() )){

				$meta = array_filter(array_map(function($a){ return $a[0]; }, get_user_meta($order->get_user_id())));

				$response = $this->send($dados = [
					'pagamento'		=> 'woocommerce',
					'status' 		=> $status,
					'codigo'		=> $order_id,
					'produto_id' 	=> $item->get_product_id(),
					'produto_nome' 	=> get_the_title($item->get_product_id()),
					'valor'			=> $item->get_total(),
					'cliente_nome'	=> $meta['billing_first_name'] . ' ' . $meta['billing_last_name'],
					'cliente_email' => $meta['billing_email'],
					
					// cpf opcional - Brazilian Market on WooCommerce - https://br.wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/
					'cliente_doc'	=> isset($meta['billing_cpf']) ? $meta['billing_cpf'] : @$meta['billing_cnpj'],

					'cliente_endereco' 			=> @$meta['billing_address_1'],
					'cliente_endereco_n' 		=> @$meta['billing_number'],
					'cliente_endereco_comp' 	=> @$meta['billing_address_2'],
					'cliente_endereco_bairro' 	=> @$meta['billing_neighborhood'],
					'cliente_endereco_cidade' 	=> @$meta['billing_city'],
					'cliente_endereco_estado' 	=> @$meta['billing_state'],
					'cliente_endereco_cep' 		=> @$meta['billing_postcode'],
					'cliente_telefone' 			=> @$meta['billing_phone']
				]);

				$order->add_order_note(sprintf(
					"Cademí, comunicando status: <b>%s</b>, para o produto ID:%s %s. Resposta: <b>%s</b>",
					$status,
					$dados['produto_id'],
					$dados['produto_nome'],
					$response
				));

			}
		}
		return;
	}

	private function send($data)
	{
		if(empty($this->options['cademi_woocommerce_token']))
			return "Token não configurado";

		if(empty($this->options['cademi_woocommerce_url']))
			return "URL não configurada";

		$data['token'] = $this->options['cademi_woocommerce_token'];

		$curl = curl_init($this->options['cademi_woocommerce_url']);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		switch ($httpcode) {
			case '200':
				$json = json_decode($response);
				return sprintf("Carga enviada - ID %s", $json->data->carga->id);
			break;
			case '409':
				$json = json_decode($response);
				return sprintf("Erro ao enviar carga - %s", $json->msg);
			break;
			default:
				return sprintf("Erro %s", $httpcode);
			break;
		}
	}

	public function taxonomyCreate()
	{
		register_taxonomy(
	        '_cademi_entrega_check',
	        'product',
	        array(
	            'label' 			=> __( 'Entregar na Cademí?' ),
	            'rewrite' 			=> array( 'slug' => 'cademi_entregar' ),
	            'hierarchical' 		=> true,
	            'show_ui'           => true,
            	'show_admin_column' => true,
            	'show_in_menu'		=> false,
            	'show_in_rest' 		=> true
	        )
	    );

	    $sim_exists = term_exists('sim', '_cademi_entrega_check');
	    if( ! $sim_exists )
	    	wp_insert_term('Sim', '_cademi_entrega_check', ['slug' => 'sim']);
    }

    public function taxonomyBlock($term, $taxonomy)
    {
    	if($taxonomy !== '_cademi_entrega_check')
    		return $term;

    	if( ! term_exists('sim', '_cademi_entrega_check'))
    		return $term;

    	return new WP_Error('term_addition_blocked', __('Não é possível adicionar categorias aqui.'));
    }

}