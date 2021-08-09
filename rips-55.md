---

title: rips-55
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1144657723482677248" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
class Challenge {
    const UPLOAD_DIRECTORY = './solutions/';
    private $file;
    private $whitelist;

    public function __construct($file) {
        $this->file = $file;
        $this->whitelist = range(1, 24);
    }

    public function __destruct() {
        if(in_array($this->file['name'], $this->whitelist)){
            move_uploaded_file(
                $this->file['tmp_name'],
                self::UPLOAD_DIRECOTRY . $this->file['name']
            );
        }
    }
}

$challenge = new Challenge($_FILES['solution']);

{{< /code >}}

# Solution
{{< code language="php" highlight="8,14,18,25" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
class Challenge {
    const UPLOAD_DIRECTORY = './solutions/';
    private $file;
    private $whitelist;

    public function __construct($file) {
		// 2) $this->file = user input
        $this->file = $file;
        $this->whitelist = range(1, 24);
    }

    public function __destruct() {
		// 3) type juggling is possible here
        if(in_array($this->file['name'], $this->whitelist)){
            move_uploaded_file(
                $this->file['tmp_name'],
				// 4) e.g.: name = 1/../../../home/user/.bashrc
                self::UPLOAD_DIRECOTRY . $this->file['name']
            );
        }
    }
}

// 1) user input $_FILES['solution']
$challenge = new Challenge($_FILES['solution']);


// does not work in php <= 8.0: 
// - https://www.php.net/releases/8.0/en.php
// - https://wiki.php.net/rfc/string_to_number_comparison
// 
// example:
// $whitelist = range(1, 24);
// var_dump(in_array("1/../../../../home/user/.bashrc", $whitelist));
// bool(true)


{{< /code >}}
