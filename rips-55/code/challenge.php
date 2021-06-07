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
