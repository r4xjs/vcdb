using System.IO;
using System.Net;
using System.Net.Http;
using Microsoft.AspNetCore.Mvc;
namespace core_api.Controllers
{
    public class DataDownloadController : Controller {
        public readonly string AvatarFolder = "images/avatars/";
        [HttpGet]
        public HttpResponseMessage GetAvatar(string image) {
            if (string.IsNullOrWhiteSpace(image) || image.Contains("/")) {
                return new HttpResponseMessage(HttpStatusCode.BadRequest) {
                    Content =  new StringContent("Valid avatar required")};    
            }
            string img = System.IO.Path.Combine(AvatarFolder, image);
            if (!img.Contains(AvatarFolder) || !System.IO.File.Exists(img)) { 
                return new HttpResponseMessage(HttpStatusCode.NotFound) {
                    Content =  new StringContent("Avatar not found")};
            }
            var fileInfo = new System.IO.FileInfo(img);
            var type = fileInfo.Extension;
            var c = new StreamContent(fileInfo.OpenRead());
            c.Headers.ContentType = new System.Net.Http.Headers.
                MediaTypeHeaderValue("image/" + type);
            return new HttpResponseMessage(HttpStatusCode.OK){Content = c};
        }
    }
}
