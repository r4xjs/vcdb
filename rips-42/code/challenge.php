$sanitized = [];

foreach ($_GET as $key => $value) {
    $sanitized[$key] = intval($value);
}

$queryParts = array_map(function ($key, $value) {
    return $key . '=' . $value;
}, array_keys($sanaitized), array_values($sanitized));

$query = implode('&', $queryParts);

echo "<a href='/images/sized.php?" .
    htmlentities($query) . "'>link</a>";
