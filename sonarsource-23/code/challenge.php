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
