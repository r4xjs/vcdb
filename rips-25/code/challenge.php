if (isset($_POST['password'])) {
    setcookie('hash', md5($_POST['password'])); 
    header("Refresh: 0");
    exit;
}

$password = '0e836584205638841937695747769655';
if (!isset($_COOKIE['hash'])) {
    echo '... login prompt...';
    exit; 
} elseif (md5($ COOKIE['hash']) == $password) {
    echo 'Login succeeded';
} else {
    echo 'Login failed';
}
