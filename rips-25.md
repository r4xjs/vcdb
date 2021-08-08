---

title: rips-25
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1200434742815907840" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
if(isset($_POST['password'])) {
    setcookie('hash', md5($_POST['password'])); 
    header("Refresh: 0");
    exit;
}

$password = '0e836584205638841937695747769655';
if(!isset($_COOKIE['hash'])) {
    echo '<form><input type="password" name="password" />'
        . '<input type="submit" value="Login"></form>';
    exit;
} elseif(md5($_COOKIE['hash']) == $password) {
    echo 'Login successed';
} else {
    echo 'Login failed';
}

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
if(isset($_POST['password'])) {
    setcookie('hash', md5($_POST['password'])); 
    header("Refresh: 0");
    exit;
}

$password = '0e836584205638841937695747769655';
if(!isset($_COOKIE['hash'])) {
    echo '<form><input type="password" name="password" />'
        . '<input type="submit" value="Login"></form>';
    exit;
} elseif(md5($_COOKIE['hash']) == $password) {              // 1) hash is user input. php type juggling
    echo 'Login successed';                                 //    will increase collisions and can be used to pass the check
} else {
    echo 'Login failed';
}


// example:
var_dump('0e836584205638841937695747769655' == '0e0');
// bool(true)
var_dump('0e836584205638841937695747769655' == '0e1');
// bool(true)
var_dump('0e836584205638841937695747769655' == '0e2');
// bool(true)
//...

var_dump('0e836584205638841937695747769655' == md5('240610708'));
// bool(true)
var_dump(md5('240610708'));
// string(32) "0e462097431906509019562988736854"

// src: https://github.com/swisskyrepo/PayloadsAllTheThings/blob/master/Type%20Juggling/README.md


{{< /code >}}