---

title: sonarsource-07
author: raxjs
tags: [php]

---

Authenticating employees from an XML file.

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1334527819402145792" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
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
    $userId = (int) $request->request->get('userId');
    $password = $request->request->get('password');
    if ($request->request->get('submit') !== null) {
	try {
	    if (!$this->auth($userId, $password)) {
		return $this->json(['error' => "Wrong $userId."]);

{{< /code >}}

# Solution
{{< code language="php" highlight="8-11,19,22" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
private DOMDocument $doc;
private $authFile = 'employees.xml';

private function auth($userId, $passwd) {
    $this->doc->load($this->authFile);
    $xpath = new DOMXPath($this->doc);
    // 3) xpath injection via $passwd here
    //    for example: $passwd = "a' or 'a'=='a"
    //    will result in:
    //    "[loginID=$userId and passwd='a' or 'a'=='a'][position()<=1]"
    $filter = "[loginID=$userId and passwd='$passwd'][position()<=1]";
    $employee = $xpath->query("/employees/employee$filter");
    return ($employee->length == 0) ? false : true;
}
public function index(Request $request) {
    $userId = (int) $request->request->get('userId');
    $password = $request->request->get('password');
    // 1) $userId and $password is user input
    if ($request->request->get('submit') !== null) {
	try {
        // 2) passed to auth here
	    if (!$this->auth($userId, $password)) {
		return $this->json(['error' => "Wrong $userId."]);


{{< /code >}}
