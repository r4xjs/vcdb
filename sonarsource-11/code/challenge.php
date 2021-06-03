<?php 
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
class LoginController extends AbstractController {
    private DOMDocument $doc;
    private $authFile = 'employees.xml';
    
    private function auth($userId, $passwd) {
        $this->doc->load($this->authFile);
        $xpath = new DOMXPath($this->doc);
        $filter = "[loginID=$userId and passwd='$passwd'][position()<=1]";
        $employee = $xpath->query("/employees/employee$filter");
        return ($employee->length == 0) ? false : true;
    }    
    public function index(Request $request) {
        $userId = (int)$request->request->get('userId');
        $password = $request->request->get('password');
        if ($request->request->get('submit') !== null) {
            try {
                if (!$this->auth($userId, $password)) {
                    return $this->json(['error' => "Wrong $userId."]);
                }
                else {
                    $this->loginCompleted(true);
                    $this->loadUserInformation($employee);
                }
            } catch (Exception $e) {
                return $this->json(['error' => "Login Failed."]);
            }
        }
        return $this->json(['error' => "Login Succeeded."]);
    }
}
