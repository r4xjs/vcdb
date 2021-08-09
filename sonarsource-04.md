---

title: sonarsource-04
author: raxjs
tags: [csharp]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1398247689238704131" >}}

# Code
{{< code language="csharp"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
// Controllers/HomeController.cs
using System.Threading.Tasks;

namespace Core31Demo.Controllers
{
    public class HomeController : Controller {
        [HttpGet]
        public async Task<IActionResult> Logout(string logoutId) {
            ViewBag.Logout = "Please confirm logout &#8230;";
            if (User.Identity.IsAuthenticated == false) {
                return await Logout(new LogoutViewModel {LogoutId = logoutId });
            }
            return View("ConfirmLogout");
        }
        [HttpPost][ValidateAntiForgeryToken]
        public async Task<IActionResult> Logout(LogoutViewModel model) {
            await PerformSignOutAsync(model);
            ViewData["Logout"] = model.LogoutId;
            return View("Logout");
        }

        static async Task PerformSignOutAsync(LogoutViewModel model) {
            // sign out logic
            // throw new NotImplementedException();


// Views/Home/Logout.cshtml
<div class="page-header">
<h1>@Html.Raw(@ViewBag.Logout)</h1></div>
<div class="logout-back"><a asp-controller="Home"
     asp-action="Login" asp-route-id="@ViewData["Logout"]">back</a>
{{< /code >}}

# Solution
{{< code language="csharp" highlight="10,20,34,35" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
// Controllers/HomeController.cs
using System.Threading.Tasks;

namespace Core31Demo.Controllers
{
    public class HomeController : Controller {
        [HttpGet]
        public async Task<IActionResult> Logout(string logoutId) {
	    // 1) assume logoutId is user input
            ViewBag.Logout = "Please confirm logout &#8230;";
            if (User.Identity.IsAuthenticated == false) {
                return await Logout(new LogoutViewModel {LogoutId = logoutId });
            }
            return View("ConfirmLogout");
        }
        [HttpPost][ValidateAntiForgeryToken]
        public async Task<IActionResult> Logout(LogoutViewModel model) {
            await PerformSignOutAsync(model);
	    // 2) then ViewData["Logout"] is also user controlled
            ViewData["Logout"] = model.LogoutId;
            return View("Logout");
        }

        static async Task PerformSignOutAsync(LogoutViewModel model) {
            // sign out logic
            // throw new NotImplementedException();


// Views/Home/Logout.cshtml
<div class="page-header">
<h1>@Html.Raw(@ViewBag.Logout)</h1></div>
<div class="logout-back"><a asp-controller="Home"
// 3) ViewData["Logout"] is embedded into html without sanitization
//    --> XSS
     asp-action="Login" asp-route-id="@ViewData["Logout"]">back</a>


{{< /code >}}
