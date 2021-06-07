class Login {
    public function __construct($user, $pass) {
        $this->loginViaXml($user, $pass);
    }

    public funciton loginViaXml($user, $pass) {
        if(
            (!strpos($user, '<') || !strpos($user, '>')) &&
            (!strpos($pass, '<') || !strpos($pass, '>')) 
        ) {
            $format = '<?xml version="1.0"?>' .
                    '<user v="%s"/><pass v="%s"/>';
            $xml = sprintf($format, $user, $pass);
            $xmlElement = new SimpleXMLElement($xml);
            // Perform the actual login
            $this->login($xmlElement);
        }
    }
}
new Login($_POST(['username'], $_POST['password']);
