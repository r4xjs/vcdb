---

title: sonarsource-16
author: raxjs
tags: [csharp]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1337789307386359812" >}}

# Code
{{< code language="csharp"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
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

{{< /code >}}

# Solution
{{< code language="csharp" highlight="26,34,36,40,41" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
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
	    // 1) search and since is user input
            DateTime.TryParseExact(since, "MM-dd-yy", null,
                DateTimeStyles.None, out var parsedDate);
            var blogposts = from BlogPost blog in Posts
                where DateTime.Compare(blog.ReleaseDate, parsedDate) > 0
                select blog.Content;
            ArrayList result = new ArrayList();
            foreach (var content in blogposts) {
		// 2) tmp now also contains some user input
                String tmp = content.Replace(Keyword, search);
		// 3) user input as regex can lead to DoS
                Regex rx = new Regex(search);
                Match match = rx.Match(tmp);
                if(match.Success) {
		    // 4) result contains user input.
		    //    depending on the front-end xss may be possibe
                    result.Add(match.Value);
                }
            }
            return result;
        }
    }
}


{{< /code >}}
