if (!empty( $_GET['date'])) {
    // 1) dates is user input
    $dates = explode('-', $_GET['date']);
    if(is_array($dates) && count($dates) === 2) {
        // 2) user input is injected in to a string
        //    to build a js array
   $default_date = 'defaultDate: ["' . $dates[0] . '", "' . $dates[1] . '"],';
    }
}


$js_code = '&lt;script>jQuery(".wpforms-filter-date-selector").flatpickr({';
$js_code .= 'altInput: true,';
$js_code .= 'altFormat: "M j, Y",';
$js_code .= 'dateFormat: "Y-m-d",';
// 3) $default_date is user input and can escape the
//    json code to execute arbitrary js code
$js_code .= 'defaultDate: ' . $default_date . "});";

echo $js_code;

// example:
// $_GET['date'] = 01-2021"],});alert(1)/*
