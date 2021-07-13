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

