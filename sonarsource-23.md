---

title: sonarsource-23
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1340326027206209539" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\RedirectResponse;
class LessonsController6 extends ApiController {
    public function load(): RedirectResponse {
        if (!empty(Request::cookie('site'))) {
            $site_id = Request::cookie('site');
        } else if (!empty(Request::getHost())) {
            $site_id = Request::getHost();
        } else {
            $site_id = 'default';
        }
        if (empty($site_id) || preg_match('/[^A-Za-z0-9.-_]/', $site_id)) {
            abort(403, 'Invalid ID ' . htmlspecialchars($site_id, ENT_NOQUOTES));
        }
        require_once "sites/$site_id.php";
        if ($config == 1) {
            return redirect()->route('login', ['site' => $site_id]);
        } else {
            return redirect()->route('setup', ['site' => $site_id]);
        }
    }
}

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\RedirectResponse;
class LessonsController6 extends ApiController {
    public function load(): RedirectResponse {
        // 1) $site_id is user input
        if (!empty(Request::cookie('site'))) {
            $site_id = Request::cookie('site');
        } else if (!empty(Request::getHost())) {
            $site_id = Request::getHost();
        } else {
            $site_id = 'default';
        }
        // 2) The regex has a range '.-_' which is probably not the intended pattern
        //    this will include also the '/' character which can be used for file tarversal attacks:
        //    php > var_dump(preg_match('/[^A-Za-z0-9.-_]/', "/../../f00.php"));
        //    php > int(0)
        if (empty($site_id) || preg_match('/[^A-Za-z0-9.-_]/', $site_id)) {
            abort(403, 'Invalid ID ' . htmlspecialchars($site_id, ENT_NOQUOTES));
        }
        // 3) LFI here
        require_once "sites/$site_id.php";
        if ($config == 1) {
            return redirect()->route('login', ['site' => $site_id]);
        } else {
            return redirect()->route('setup', ['site' => $site_id]);
        }
    }
}

{{< /code >}}