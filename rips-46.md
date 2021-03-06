---

title: rips-46
author: raxjs
tags: [php]

---

A PHP FTP class.

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1120702868825055232" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
class FTP {
    public $sock;

    public function __construct($host, $port, $user, $pass) {
        $this->sock = fsockopen($host, $port);

        $this->login($user, $pass);
        $this->cleanInput();
        $this->mode($_REQUEST['mode']);
        $this->send($_FILES['file']);
    }

    private function cleanInput() {
        $_GET = array_map('intval', $_GET);
        $_POST = array_map('intval', $_POST);
        $_COOKIE = array_map('intval', $_COOKIE);
    }

    public function login($username, $password) {
        fwrite($this->sock, "USER " . $username . "\n");
        fwrite($this->sock, "PASS " . $password. "\n");
    }

    public function mode($mode) {
        if($mode == 1 || $mode == 2 || $mode == 3) {
            fpus($this->sock, "MODE $mode\n");
        }
    }

    public function send($data) {
        fputs($this->sock, $data);
    }
}

new FTP('localhost', 21, 'user', 'password');

{{< /code >}}

# Solution
{{< code language="php" highlight="10,16,28,29,36,37" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
class FTP {
    public $sock;

    public function __construct($host, $port, $user, $pass) {
        $this->sock = fsockopen($host, $port);

        $this->login($user, $pass);
        $this->cleanInput();
        // 2) $_REQUEST is used here which is not sanitized
        $this->mode($_REQUEST['mode']);
        $this->send($_FILES['file']);
    }

    private function cleanInput() {
        // 1) user input is sanitized here but only $_GET, $_POST and $_COOKIE
        $_GET = array_map('intval', $_GET);
        $_POST = array_map('intval', $_POST);
        $_COOKIE = array_map('intval', $_COOKIE);
    }

    public function login($username, $password) {
        fwrite($this->sock, "USER " . $username . "\n");
        fwrite($this->sock, "PASS " . $password. "\n");
    }

    public function mode($mode) {
        // 3) type juggeling is possible here because $mode can be anything
        //    e.g. "1\nf00 bar". this way ftp commands can be injected
        if($mode == 1 || $mode == 2 || $mode == 3) {
            fputs($this->sock, "MODE $mode\n");
        }
    }

    public function send($data) {
        // 4) if $mode is not 1, 2 or 3 the mode setting is ignored
        //    the file $data can contain arbitrary ftp commands
        fputs($this->sock, $data);
    }
}

new FTP('localhost', 21, 'user', 'password');

{{< /code >}}
