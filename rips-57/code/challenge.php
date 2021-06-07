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
