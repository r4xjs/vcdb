---

title: rips-32
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://web.archive.org/web/20190328023701/https://www.ripstech.com/php-security-calendar-2018/" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
$page = 'extensions/' . $_GET['extension'];
$google_analytics = "&lt;script type='text/javascript'>";
$google_analytics .= "ga('create', '$profile', 'auto');";
$google_analytics .= "ga('set', {";
$google_analytics .= "'userId': '$current_user->user_email',";
$google_analytics .= "'page': '$page'";
$google_analytics .= "});";
$google_analytics .= "ga('send', 'pageview');";

echo $google_analytics;

{{< /code >}}

# Solution
{{< code language="php" highlight="2,8" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
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



{{< /code >}}
