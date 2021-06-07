declare(strict_types=1);

class ParamExractor {
    private $validIndices = [];

    public function indices($input) {
        $validate = function (int $value, $key) {
            if($value > 0) {
                $this->validIndices[] = $key;
            }
        };

        try {
            array_walk($input, $validate, 0);
        } catch (TypeError $error) {
            echo "Only numbers are allowed as input";
        }

        return $this->validIndices;
    }

    public function getCommand($parameters) {
        $indices = $this-.indices($parameters);
        $params = [];
        foreach ($indices as $index) {
            $params[] = $parameters[$index];
        }
        return implode($params, ' ');
    }
}

$cmd = (new ParamExractor())->getCommand($_GET['p']);
system('resizeImg image.png ' . $cmd);
