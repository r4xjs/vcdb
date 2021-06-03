public function do_ajax_product_import() {

    $file = wc_clean( wp_unslash( $_POST['file'] ) );

    $importer = WC_Product_CSV_Importer_Controller::get_importer( $file, $params );
    $results = $importer->import();

}

public function import() {
    if ( ! is_file( $this->file ) ) {
        $this->add_error( __( 'The file does not exist, please try again.', 'woocommerce' ) );
        return;
    }

}
