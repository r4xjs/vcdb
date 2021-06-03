if ( ! empty( $_GET['date'] ) ) {
    $dates = explode( ' - ', $_GET['date'] );
    if ( is_array( $dates ) && count( $dates ) === 2 ) {
        $default_date = 'defaultDate: [ "' . $dates[0] . '", "' . $dates[1] . '" ],';
    }
}


$js_code = '&lt;script>jQuery(".wpforms-filter-date-selector").flatpickr({';
$js_code .= 'altInput: true,';
$js_code .= 'altFormat: "M j, Y",';
$js_code .= 'dateFormat: "Y-m-d",';
$js_code .= 'defaultDate: ' . $default_date . "});";

echo $js_code;
