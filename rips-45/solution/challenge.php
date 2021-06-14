class Redirect {
    private $websiteHost = 'www.example.com';

    private function setHeaders($url) {
        // $url = user input gets url decoded
        $url = urldecode($url);
        // html header injection here if %0d%0a is used in params
        header("Locatoin: $url");
    }

    public function startRedirect($params) {
        $parts = explode('/', $_SERVER['PHP_SELF']);
        $baseFile = end($parts);
        $url = sprintf(
            "%s?%s",
            $baseFile,
            // 2) $params is user input
            http_build_query($params)
        );
        $this->setHeaders($url);
    }
}

if($_GET['redirect']) {
    // params is user input
    (new Redirect())->startRedirect($_GET['params']);
}


// example:
// https://www.example.com/challenge.php?redirect=1&params=a%3d1%0d%0af000%20bar
