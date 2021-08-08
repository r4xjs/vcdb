---

title: rips-30
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://web.archive.org/web/20190328023701/https://www.ripstech.com/php-security-calendar-2018/" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
if (!empty( $_GET['date'])) {
    $dates = explode('-', $_GET['date']);
    if ( is_array($dates) && count($dates) === 2) {
   $default_date = 'defaultDate: ["' . $dates[0] . '", "' . $dates[1] . '"],';
    }
}


$js_code = '&lt;script>jQuery(".wpforms-filter-date-selector").flatpickr({';
$js_code .= 'altInput: true,';
$js_code .= 'altFormat: "M j, Y",';
$js_code .= 'dateFormat: "Y-m-d",';
$js_code .= 'defaultDate: ' . $default_date . "});";

echo $js_code;

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
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

{{< /code >}}