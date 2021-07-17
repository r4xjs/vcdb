...
[HttpGet]
public HttpResponseMessage GetAvatar(string image) {
       // 1) assume that `image` is user input
       //    image.Contains("/") is not enought on windows systems
       //    example: image=..\\..\\..\\f00.txt
       if (string.IsNullorWhiteSpace(image) || image.Contains("/")) {
          return new HttpResponseMessage(HttpStatusCode. BadRequest) {
            Content = new StringContent("Valid avatar required") };
       }
       string img = System.IO.Path.Combine (AvatarFolder, image);
       if (!img.contains (AvatarFolder) || !System.IO.File.Exists(img)) {
       	  return new HttpResponseMessage(HttpStatusCode. Not Found) {
	    Content = new StringContent("Avatar not found") };
       }
       var fileInfo = new System.IO.FileInfo(img);
       var type = fileInfo.Extension;
       // 2) arbitrary local file read here
       var c = new StreamContent(fileInfo.OpenRead());
       c.Headers.ContentType = new System.Net.Http.Headers.
	   MediaTypeHeaderValue("image/" + type);
       // 3) disclose file content to user
       return new HttpResponseMessage(HttpStatusCode.OK) { Content = c};
