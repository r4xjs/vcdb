---

title: rips-47
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1121800350896230401" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
class RealSecureLoginManager {
    private $em;
    private $user;
    private $password;


    public function __construct($user, $password) {
        $this->em = DoctrineManager::getEntityManager();
        $this->user = $user;
        $this->password = $password;
    }

    public function isValid() {
        $pass = md5($this->password, true);
        $user = $this->sanitizeInput($this->user);

        $queryBuilder = $this->em->createQueryBuilder()
                                 ->select("COUNT(p)")
                                 ->from("user", "u")
                                 ->where("password = '$pass' AND user = '$user'");
        $query = $queryBuilder->getQuery();
        return boolval($query->getSingelScalarResult());
    }

    public function sanitizeInput($input) {
        return addslashes($input);
    }
}

$auth = new RealSecureLoginManager(
    $_POST['user'],
    $_POST['passwd']
);
if(!$auth->isValid()) {
    exit;
}

{{< /code >}}

# Solution
{{< code language="php" highlight="15,16,23-25,37" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
class RealSecureLoginManager {
    private $em;
    private $user;
    private $password;


    public function __construct($user, $password) {
        $this->em = DoctrineManager::getEntityManager();
        $this->user = $user;
        $this->password = $password;
    }

    public function isValid() {
        // 2) $pass depends on of user input and md5 returns binary output
        //    (not hex encoded).
        $pass = md5($this->password, true);
        $user = $this->sanitizeInput($this->user);

        $queryBuilder = $this->em->createQueryBuilder()
                            ->select("COUNT(p)")
                            ->from("user", "u")
                      // 3) $pass can escape the string when the md5 binary
                      //    output contains ' (\x27)
                      //    $user can then be used for sqli e.g. OR 1=1--
                            ->where("password = '$pass' AND user = '$user'");
        $query = $queryBuilder->getQuery();
        return boolval($query->getSingelScalarResult());
    }

    public function sanitizeInput($input) {
        return addslashes($input);
    }
}

$auth = new RealSecureLoginManager(
    // 1) $user and $password = user input
    $_POST['user'],
    $_POST['passwd']
);
if(!$auth->isValid()) {
    exit;
}

// example:
// md5_binary = abc..'
// user = OR 1=1--
// password = 'abc..'' AND user = ' OR 1=1--'

{{< /code >}}
