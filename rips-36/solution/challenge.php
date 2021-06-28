class Login {
    public function __construct($user, $pass) {
        $this->loginViaXml($user, $pass);
    }

    public funciton loginViaXml($user, $pass) {
        // 2) this check can be bypassed when '<' or '>' is
        //    found at pos 0, strpos will return 0 ==> false
        //    and the if condition will eval to !0 = !false = true
        if(
            (!strpos($user, '<') || !strpos($user, '>')) &&
            (!strpos($pass, '<') || !strpos($pass, '>')) 
        ) {
            $format = '<?xml version="1.0"?>' .
                    '<user v="%s"/><pass v="%s"/>';
            $xml = sprintf($format, $user, $pass);
            // 3) arbitrary xml injection here
            $xmlElement = new SimpleXMLElement($xml);
            // Perform the actual login
            $this->login($xmlElement);
        }
    }
}
// 1) Login receives two parameter with user input
new Login($_POST(['username'], $_POST['password']);



// var_dump(!strpos("<bcd", '<') == true);
// bool(true)
// var_dump(!strpos("<bcd\"><aaa>bbb</aaa><--", '<') == true);
// bool(true)

