$sanitized = [];

// 1) The key and value of sanitized is user input
//    The value must be an int but the key has no such limitation
foreach ($_GET as $key => $value) {
    $sanitized[$key] = intval($value);
}

$queryParts = array_map(function ($key, $value) {
    return $key . '=' . $value;
}, array_keys($sanitized), array_values($sanitized));

$query = implode('&', $queryParts);

// 2) The key can be used for xss
echo "<a href='/images/sized.php?" .
    htmlentities($query) . "'>link</a>";

// example:
// key = "aaa' onclick='alert(1)' x='"
// value = 1
// --> <a href='/images/sized.php?aaa' onclick='alert(1)' x='=5'>link</a>
