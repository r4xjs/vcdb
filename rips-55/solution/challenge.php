class Challenge {
    const UPLOAD_DIRECTORY = './solutions/';
    private $file;
    private $whitelist;

    public function __construct($file) {
        $this->file = $file;                          // 2) $this->file = user input
        $this->whitelist = range(1, 24);
    }

    public function __destruct() {
        if(in_array($this->file['name'], $this->whitelist)){ // 3) type juggling is possible here
            move_uploaded_file(
                $this->file['tmp_name'],
                self::UPLOAD_DIRECOTRY . $this->file['name']  // 4) e.g.: name = 1/../../../home/user/.bashrc
            );
        }
    }
}

$challenge = new Challenge($_FILES['solution']);        // 1) user input $_FILES['solution']


// does not work in php <= 8.0: 
// - https://www.php.net/releases/8.0/en.php
// - https://wiki.php.net/rfc/string_to_number_comparison
// 
// example:
// $whitelist = range(1, 24);
// var_dump(in_array("1/../../../../home/user/.bashrc", $whitelist));
// bool(true)

