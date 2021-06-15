// 1) user input can overwrit arbitrary vars
extract($_POST);

function goAway() {
    error_log("Hacking attempt.");
    header('Location: /error/');
    // 3) php will continue to execute an return
}

// 2) if we enter a string we will execute goAway() function
if(!isset($pi) || !is_numeric($pi)) {
    goAway();
}

// 3) code exection inside assert string
if(!assert("(int)$pi == 3")) {
    echo "This is not pi.";
} else {
    echo "This might be pi.";
}

// example:
//POST data: pi=exec('echo 123 > /tmp/out')
//
// php.ini
// zend.assertions = 1
