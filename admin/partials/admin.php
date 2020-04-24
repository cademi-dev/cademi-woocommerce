<div class="row mx-0 px-0">
    <div class="col-12">
        <img src="https://cademi.com.br/wp-content/uploads/2020/04/cropped-cademi.png" class="d-block mx-auto my-5" style="max-width:280px;"/>
        <div class="text-center">
            Cademí WooCommerce v.1.0 instalado com <span class="text-success">sucesso.</span>
        </div>
        <div class="col-6 mt-5 mx-auto">
            <form action="" method="post">
                
                <?php wp_nonce_field('wp_create_nonce', 'cademi-woocommerce-nonce'); ?>
                
                <div class="form-group">
                    <label>Endereço:</label>
                    <input type="text" class="form-control text-center" name="data[cademi_woocommerce_url]" value="<?php echo esc_attr($this->options['cademi_woocommerce_url'] ); ?>"/>
                </div>
                
                <div class="form-group">
                    <label class="mt-4">Token:</label>
                    <input type="text" class="form-control text-center" name="data[cademi_woocommerce_token]" value="<?php echo esc_attr($this->options['cademi_woocommerce_token'] ); ?>"/>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="mt-4">Status - <b>Pagamento Aprovado</b>:</label>
                            <select class="form-control text-center" name="data[cademi_woocommerce_status_aprovado]">
                                <option></option>
                                <?php
                                    foreach(wc_get_order_statuses() as $value => $alias) {
                                        echo sprintf('<option value="%s" %s>%s</option>',
                                            $value,
                                            esc_attr($this->options['cademi_woocommerce_status_aprovado']) == $value ? 'selected="selected"' : '',
                                            $alias
                                        );
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="mt-4">Status - <b>Disputa ou Reembolso</b>:</label>
                            <select class="form-control text-center" name="data[cademi_woocommerce_status_disputa]">
                                <option></option>
                                <?php
                                    foreach(wc_get_order_statuses() as $value => $alias) {
                                        echo sprintf('<option value="%s" %s>%s</option>',
                                            $value,
                                            esc_attr($this->options['cademi_woocommerce_status_disputa']) == $value ? 'selected="selected"' : '',
                                            $alias
                                        );
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-rounded float-right">Salvar</button>

            </form>
        </div>
    </div>
</div>
<style>
    #wpcontent{
        padding:0;
    }
</style>