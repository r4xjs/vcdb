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

