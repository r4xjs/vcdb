class Carrot {
    const EXTERNAL_DIRECTORY = '/tmp/';
    private $id;
    private $lost = 0;
    private $bought = 0;

    public function __construct($input) {
        $this->id = rand(1, 1000);

        foreach($input as $field => $count) {
            // 2) overwrite arbitrary attributes with user input
            $this->$field = $count++;
        }
    }

    public function __destruct() {
        file_put_contents(
            // 3) $this->id can be user input (see 2)
            //    therefore the first arguement ($filename) is vulnerable to
            //    directory traversal
            self::EXTERNAL_DIRECTORY . $this->id,
            var_export(get_object_vars($this), true)
        );
    }
}
// 1) Carot constructor receives user input
$carrot = new Carrot($_GET);


// example:
// $_GET = [ "id" => "../f00"];

