---

title: rips-04
author: raxjs
tags: [java]

---

Please redirect me to java.

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import javax.servlet.http.*;

public class Login extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) {
    String url = request.getParameter("url");
    //only relative urls are allowed!
    if (url.startsWith("/")) {
      response.sendRedirect(url);
    }
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="8-11" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import javax.servlet.http.*;

public class Login extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) {
    String url = request.getParameter("url");
	// 1) $url is under user control and is used
	//    in HttpServletResponse.sendRedirect.
	//    If $url starts with "//" the $url is interpeted as uri, see [1].
	//    (not tested)
    //only relative urls are allowed!
    if (url.startsWith("/")) {
      response.sendRedirect(url);
    }
  }
}
// [1] https://docs.oracle.com/javaee/7/api/javax/servlet/http/HttpServletResponse.html
{{< /code >}}
