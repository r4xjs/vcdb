using System;
using System.Collections;
using System.Globalization;
using System.Text.RegularExpressions;
using Microsoft.AspNetCore.Mvc;
using System.Linq;
namespace core_api.Controllers
{
    public class BlogPost {
        public DateTime ReleaseDate { get; set; }
        public string Content { get; set; }
    }
    public class BlogController : Controller
    {
        ArrayList Posts = new ArrayList();
        string Keyword = "[highlight]";
        
        public void init() {
            Posts.Add(new BlogPost{ReleaseDate = new DateTime(2009, 8, 1, 0, 0, 0), Content="[highlight]"});
        }
       
        [Route("api/search")]
        [HttpGet]
        public ArrayList search(string search, string since) {
            DateTime.TryParseExact(since, "MM-dd-yy", null,
                DateTimeStyles.None, out var parsedDate);
            var blogposts = from BlogPost blog in Posts
                where DateTime.Compare(blog.ReleaseDate, parsedDate) > 0
                select blog.Content;
            ArrayList result = new ArrayList();
            foreach (var content in blogposts) {
                String tmp = content.Replace(Keyword, search);
                Regex rx = new Regex(search);
                Match match = rx.Match(tmp);
                if(match.Success) {
                    result.Add(match.Value);
                }
            }
            return result;
        }
    }
}
