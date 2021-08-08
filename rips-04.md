---

title: rips-04
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

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
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}