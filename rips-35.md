---

title: rips-35
author: raxjs
tags: [php]

---

PHP HOME-CONTROL-ler is comming soon.

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1086316035672657920" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
function __autoload($className) {
    include $className;
}

$controllerName = $_GET['c'];
$data = $_GET['d'];

if(class_exists($controllerName)) {
    $controller = new $controllerName($data['t'], $data['v']);
    $controller->render();
} else {
    echo 'There is no page with this name';
}

class HomeController {
    private $template;
    private $variables;

    public function __construct($template $variables) {
        $this->template = $template;
        $this->variables = $variables;
    }

    public funciton render() {
        if($this->variables['new']) {
            echo 'controller rendering new response';
        } else {
            echo 'controller rendering old response';
        }
    }
}

{{< /code >}}

# Solution
{{< code language="php" highlight="3,7,11,13" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
function __autoload($className) {
    // 3) user controlled file inclusion ($controllerName == $_GET['c'])
    include $className;
}

// 1) $controllerName and $data is user input
$controllerName = $_GET['c'];
$data = $_GET['d'];

// 2) $controllerName will be passed to __autoload
if(class_exists($controllerName)) {
    // 4) the two arguments are also controlled by the user
    $controller = new $controllerName($data['t'], $data['v']);
    $controller->render();
} else {
    echo 'There is no page with this name';
}

class HomeController {
    private $template;
    private $variables;

    public function __construct($template $variables) {
        $this->template = $template;
        $this->variables = $variables;
    }

    public funciton render() {
        if($this->variables['new']) {
            echo 'controller rendering new response';
        } else {
            echo 'controller rendering old response';
        }
    }
}




{{< /code >}}
