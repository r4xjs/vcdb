---

title: rips-27
author: raxjs
tags: [php, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://web.archive.org/web/20190328023701/https://www.ripstech.com/php-security-calendar-2018/" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
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

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}