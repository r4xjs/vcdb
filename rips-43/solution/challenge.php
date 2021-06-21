class LoginManager {
    private $em;
    private $user;
    private $password;

    public function __construct($user, $pasword) {
        $this->em = DoctrineManager::getEntityManager();
        $this->user = $user;             // 2) usre input
        $this->password = $password;     //    user input
    }

    public function isValid() {
        // 3) sanitizatoin of user input
        $user = $this->sanitizeInput($this->user);
        $pass = $this->sanitizeInput($this->password);

        $queryBuilder = $this->em->createQueryBuilder()
                               ->select("COUNT(p)")
                               ->from("user", "u")
                      // 5) break out with $user and inject with $pass
                               ->where("user = '$user' AND password = '$pass'");
        $query = $queryBuilder->getQuery();
        return boolval($query->getSingleScalarResult());
    }

    public function sanitizeInput($input, $length = 20) {
        $input = addslashes($input);
        if(strlen($input) > $length) {
            // 4) this can be exploited when we add a ' at pos 20
            //    e.g. aaaaaaaaaaaaaaaaaaa' was changed above to
            //    aaaaaaaaaaaaaaaaaaaa\' and will be changed here to
            //    aaaaaaaaaaaaaaaaaaaa\. This way we can break out (see 5)
            $input = substr($input, 0, $length);
        }
        return $input;
    }
}

// 1) both params of LoginManager constructor is user input
$auth = new LoginManager($_POST['user'], $_POST['passwd']);
if(!$auth->isValid()) {
    exit;
}