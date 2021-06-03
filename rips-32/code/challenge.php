$page = 'extensions/' . $_GET['extension'];
$google_analytics = "&lt;script type='text/javascript'>";
$google_analytics .= "ga('create', '$profile', 'auto');";
$google_analytics .= "ga('set', {";
$google_analytics .= "'userId': '$current_user->user_email',";
$google_analytics .= "'page': '$page'";
$google_analytics .= "});";
$google_analytics .= "ga('send', 'pageview');";

echo $google_analytics;
