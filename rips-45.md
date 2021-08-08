---

title: rips-45
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1116715847358091264" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
class Redirect {
    private $websiteHost = 'www.example.com';

    private function setHeaders($url) {
        $url = urldecode($url);
        header("Locatoin: $url");
    }

    public function startRedirect($params) {
        $parts = explode('/', $_SERVER['PHP_SELF']);
        $baseFile = end($parts);
        $url = sprintf(
            "%s?%s",
            $baseFile,
            http_build_query($params)
        );
        $this->setHeaders($url);
    }
}

if($_GET['redirect']) {
    (new Redirect())->startRedirect($_GET['params']);
}

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
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

{{< /code >}}