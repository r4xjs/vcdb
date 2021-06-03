<?php
use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
class Authenticate{
    private function getEmail($email_field, $user_data) {
        foreach($email_field as $field) {
            if (isset($user_data[0][$field][0])) {
                return $user_data[0][$field][0];
            }
        }
        return NULL;
    }
    public function findUsername() {
        $envvar = $this->settings['fields']['envvar'];
        $ldapdn = Config::read('WebApp.ldapDN');
        $ldapSearchFilter = Config::read('WebApp.ldapSearchFilter');
        $ldapEmailField = Config::read('WebApp.ldapEmailField');
        $ldapconn = ldap_connect(Config::read('WebApp.ldapServer')) 
            or die('LDAP server connection failed');
        if (!($ldapbind = ldap_bind($ldapconn))) {
            die("LDAP bind failed");
        }
        if (!empty($ldapSearchFilter)) {
            $filter = '(&' . $ldapSearchFilter . '(' .
            Config::read('WebApp.ldapSearchAttribut') . '=' .
            Request::input($envvar) . '))';
        }
        $getLdapUserInfo = Config::read('WebApp.ldapFilter');
        $result = ldap_search($ldapconn, $ldapdn, $filter, $getLdapUserInfo)
            or die("LDAP Error: " . ldap_error($ldapconn));
        $ldapUserData = ldap_get_entries($ldapconn, $result);
        if (!isset($ldapEmailField) && isset($ldapUserData[0]['mail'][0])) {
            $username = $ldapUserData[0]['mail'][0];
        } else if (isset($ldapEmailField)) {
            $username = $this->getEmail($ldapEmailField, $ldapUserData);
        } else {
            die("User not found in LDAP");
        }
        ldap_close($ldapconn);
        return $username;
    }
}
