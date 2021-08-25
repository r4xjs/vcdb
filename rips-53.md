---

title: rips-53
author: raxjs
tags: [php]

---

The LDAPAuthenticator left the building.

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1137012839896539136" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
class LDAPAuthenticator {
    public $conn;
    public $host;

    functin __construct($hots = "localhost") {
        $this->host = $host;
    }

    function authenticate($user, $pass) {
        $result = [];
        $this->conn = ldap_connect($this->host);
        ldap_set_option(
            $this->conn,
            LDAP_OPT_PROTOCOL_VERSION,
            3
        );
        if(!@ldap_bind($this->conn))
            return -1;

        $user = ldap_escape($user, null, LDAP_ESCAPE_DN);
        $pass = ldap_escape($pass, null, LDAP_ESCAPE_DN);
        $result = ldap_search(
            $this->conn,
            "",
            "(&(uid=$user)(userPassword=$pass))"
        );
        $result = ldap_get_entries($this->conn, $result);
        return ($result["count"] > 0 ? 1 : 0);
    }
}

if(isset($_GET["u"]) && isset($_GET["p"])) {
    $ldap = new LDAPAuthenticator();
    if($ldap->authenticate($_GET["u"], $_GET["p"])) {
        echo "You are now logged in !";
    } else {
        echo "Username or password unkonwn!";
    }
}

{{< /code >}}

# Solution
{{< code language="php" highlight="21,22,28,38" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
class LDAPAuthenticator {
    public $conn;
    public $host;

    functin __construct($hots = "localhost") {
        $this->host = $host;
    }

    function authenticate($user, $pass) {
        $result = [];
        $this->conn = ldap_connect($this->host);
        ldap_set_option(
            $this->conn,
            LDAP_OPT_PROTOCOL_VERSION,
            3
        );
        if(!@ldap_bind($this->conn))
            return -1;

		// 2) LDAP_ESCAPE_DN should be replaced to LDAP_ESCAPE_FILTER
		//    because $user and $pass are used in ldap_search in the filter argument
        $user = ldap_escape($user, null, LDAP_ESCAPE_DN);
        $pass = ldap_escape($pass, null, LDAP_ESCAPE_DN);
        $result = ldap_search(
            $this->conn,
            "",
			// 3) ldap injection here
            "(&(uid=$user)(userPassword=$pass))"
        );
        $result = ldap_get_entries($this->conn, $result);
        return ($result["count"] > 0 ? 1 : 0);
    }
}

if(isset($_GET["u"]) && isset($_GET["p"])) {
    $ldap = new LDAPAuthenticator();
	// 1) u and p = user input
    if($ldap->authenticate($_GET["u"], $_GET["p"])) {
        echo "You are now logged in !";
    } else {
        echo "Username or password unkonwn!";
    }
}


// example:
function test($u, $p) {
    $u = ldap_escape($u, null, LDAP_ESCAPE_DN);
    $p = ldap_escape($p, null, LDAP_ESCAPE_DN);
    var_dump("(&(uid=$u)(userPassword=$p))");
}
test('user1)(&)', 'f00');
// string(36) "(&(uid=user1)(&))(userPassword=f00))"


function test2($u, $p) {
    $u = ldap_escape($u, null, LDAP_ESCAPE_FILTER);
    $p = ldap_escape($p, null, LDAP_ESCAPE_FILTER);
    var_dump("(&(uid=$u)(userPassword=$p))");
}
test2('user1)(&)', 'f00');
//string(42) "(&(uid=user1\29\28&\29)(userPassword=f00))"



// https://www.php.net/manual/en/function.ldap-escape.php
// https://www.php.net/manual/en/function.ldap-search.php



{{< /code >}}
