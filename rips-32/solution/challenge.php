// 1) $page has user input
$page = 'extensions/' . $_GET['extension'];
$google_analytics = "&lt;script type='text/javascript'>";
$google_analytics .= "ga('create', '$profile', 'auto');";
$google_analytics .= "ga('set', {";
$google_analytics .= "'userId': '$current_user->user_email',";
// 2) $page is not sanitized ==> XSS
$google_analytics .= "'page': '$page'";
$google_analytics .= "});";
$google_analytics .= "ga('send', 'pageview');";

echo $google_analytics;
          
// example
// $_GET['extension'] = aaa.html'});alert(1)/*
// extensions/aaa.html'});alert(1)/*


