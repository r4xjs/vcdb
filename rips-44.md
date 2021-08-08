---

title: rips-44
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1114181397470531584" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
class Carrot {
    const EXTERNAL_DIRECTORY = '/tmp/';
    private $id;
    private $lost = 0;
    private $bought = 0;

    public function __construct($input) {
        $this->id = rand(1, 1000);

        foreach($input as $field => $count) {
            $this->$field = $count++;
        }
    }

    public function __destruct() {
        file_put_contents(
            self::EXTERNAL_DIRECTORY . $this->id,
            var_export(get_object_vars($this), true)
        );
    }
}
$carrot = new Carrot($_GET);

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
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


{{< /code >}}