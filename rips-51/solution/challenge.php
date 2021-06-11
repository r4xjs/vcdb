declare(strict_types=1);

class ParamExractor {
    private $validIndices = [];

    public function indices($input) {
        $validate = function (int $value, $key) {            // 3) $value and $key is user input
            if($value > 0) {                                 //    the type check is vulnerable against php type juggling
                $this->validIndices[] = $key;                //    in php version < 8.0
            }
        };

        try {
            array_walk($input, $validate, 0);                // 2) $input is user input
        } catch (TypeError $error) {
            echo "Only numbers are allowed as input";
        }

        return $this->validIndices;
    }

    public function getCommand($parameters) {
        $indices = $this->indices($parameters);
        $params = [];
        foreach ($indices as $index) {
            $params[] = $parameters[$index];
        }
        return implode($params, ' ');                        // 4) the $params array can have arbitrary strings because of
    }                                                        //    the type juggling problem above
}

$cmd = (new ParamExractor())->getCommand($_GET['p']);        // 1) argument to getCommand is user input
system('resizeImg image.png ' . $cmd);                       // 5) os command injection here




# example:

var_dump((new ParamExractor())->getCommand(array(1, 2, "1 ;cat /etc/passwd")));
#PHP Notice:  A non well formed numeric value encountered in php shell code on line 7
#string(22) "1 2 1 ;cat /etc/passwd"

