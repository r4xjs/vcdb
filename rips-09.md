---

title: rips-09
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import java.io.*;
import java.util.regex.*;
import javax.servlet.http.*;

public class Validator extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) throws IOException {
    response.setContentType("text/plain");
    response.setCharacterEncoding("UTF-8");

    PrintWriter out = response.getWriter();
    if (isInWhiteList(request.getParameter("whitelist"), request.getParameter("value"))) {
      out.print("Value is in whitelist.");
    } else {
      out.print("Value is not in whitelist.");
    }
    out.flush();
  }

  public static boolean isInWhiteList(String whitelist, String value) {
    Pattern pattern = Pattern.compile("^[" + whitelist + "]+");
    Matcher matcher = pattern.matcher(value);
    return matcher.matches();
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}