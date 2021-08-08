---

title: rips-38
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1096479505328287747" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
class TokenStorage {
    public function performAction($action, $data) {
        switch($action) {
            case 'create':
                $this->createToken($data);
                break;
            case 'delete':
                $this->clearToken($data);
                break;
            default:
                throw new Exeception('Unknown action');
        }
    }

    public function createToken($seed) {
        $token = md5($seed);
        file_put_contents('/tmp/tokens/' . $token, '...data');
    }

    public function clearToken($token) {
        $file = preg_replace("/[^a-z.-_]/", "", $token);
        unlink('/tmp/tokens/' . $file);
    }
}

$storage = new TokenStorage();
$storage->performAction($_GET(['action'], $_GET['data']);

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
class TokenStorage {
    public function performAction($action, $data) {
        switch($action) {
            case 'create':
                $this->createToken($data);
                break;
            case 'delete':
                $this->clearToken($data);
                break;
            default:
                throw new Exeception('Unknown action');
        }
    }

    public function createToken($seed) {
        $token = md5($seed);
        file_put_contents('/tmp/tokens/' . $token, '...data');
    }

    public function clearToken($token) {
        // 2) $token is user controlled
        $file = preg_replace("/[^a-z.-_]/", "", $token);
        // 3) $file can still contain ../, therefore arbitrary files can
        //    be unlinked. The problem is ".-_" which defines a range
        //    of characters (see ascii table) and includes, among others "/"
        unlink('/tmp/tokens/' . $file);
    }
}

$storage = new TokenStorage();
// 1) performAction receives two argumetns with user input
$storage->performAction($_GET(['action'], $_GET['data']);

// example:
// var_dump(preg_replace("/[^a-z.-_]/", "", "../../etc/passwd"));
// string(16) "../../etc/passwd"
//
// var_dump(preg_replace("/[.-_]/", "X", "abcAbc5"));
// string(7) "abcXbcX"

{{< /code >}}