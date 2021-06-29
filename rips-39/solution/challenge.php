function getUser($id) {
    // 3) $config and $db can be user input from parse_str
    global $config, $db;
    if(!is_resource($db)) {
        $db = new MySQLi(
            // 4) the database connection can be hijacked
            $config['dbhost'],
            $config['dbuser'],
            $config['dbpass'],
            $config['dbname'],
        );
    }
    $sql = "SELECT username FROM users WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->bind_result($name);
    $stmt->execute();
    $stmt->fetch();
    return $name;
}

// 1) $var is user input
$var = parse_url($_SERVER['HTTP_REFERER']);
parse_str($var['query']);
// 2) $id can be user input coming from parse_str
$currentUser = getUser($id);
echo '<h1>'.htmlspecialchars($currentuser).'</h1>';



// example
// php > parse_str("config[dbhost]=1.1.1.1&config[dbuser]=f00");
// php > function test_global() {
//     global $config;
//     var_dump($config);
// }
// php > test_global();
// array(2) {
//   ["dbhost"]=>
//   string(7) "1.1.1.1"
//   ["dbuser"]=>
//   string(3) "f00"
// }