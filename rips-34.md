---

title: rips-34
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1083770519256616960" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
// composer require "twig/twig"
require 'vendor/autoload.php';

class Template {
    private $twig;

    public function __construct() {
        $indexTemplate = '<img ' .
                       'src="https://loremflickr.com/320/240">' .
                       '<a href="{{link|escape}}">Next slide >></a>';

        // Default twig setup, simulate loading
        // index.html file form disk
        $loader = new Twig\Loader\ArrayLoader([
            'index.html' => $indexTemplate
        ]);
        $this->twig = new Twig\Environment($loader);
    }

    public function getNextSlideUrl() {
        $nextSlide = $_GET['nextSlide'];
        return filter_var($nextSlide, FILTER_VALIDATE_URL);
    }

    public function render() {
        echo $this->twig->render(
            'index.html',
            ['link' => $this->getNextSlideUrl()]
        );
    }
}

(new Template())->render();

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
// composer require "twig/twig"
require 'vendor/autoload.php';

class Template {
    private $twig;

    public function __construct() {
        $indexTemplate = '<img ' .
                       'src="https://loremflickr.com/320/240">' .
                       // 3) link is injected here, XSS is possible
                       //    escape('html') is used by default which is the wrong context
                       //    escape('html_attr') should be used here
                       //    ref: https://twig.symfony.com/doc/2.x/filters/escape.html
                       '<a href="{{link|escape}}">Next slide >></a>';

        // Default twig setup, simulate loading
        // index.html file form disk
        $loader = new Twig\Loader\ArrayLoader([
            'index.html' => $indexTemplate
        ]);
        $this->twig = new Twig\Environment($loader);
    }

    public function getNextSlideUrl() {
        // 1) $nextSlide is user input
        $nextSlide = $_GET['nextSlide'];
        return filter_var($nextSlide, FILTER_VALIDATE_URL);
    }

    public function render() {
        echo $this->twig->render(
            'index.html',
            // 2) link is user input with FILTER_VALIDATE_URL constraint
            ['link' => $this->getNextSlideUrl()]
        );
    }
}

(new Template())->render();
          
// example:
// var_dump(filter_var("http://localhost#\"onclick=\"alert(1)\"", FILTER_VALIDATE_URL));
// string(36) "http://localhost#"onclick="alert(1)""




{{< /code >}}