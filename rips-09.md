---

title: rips-09
author: raxjs
tags: [java]

---

A whitelist based Validator class.

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
{{< code language="java" highlight="13,23-29" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import java.io.*;
import java.util.regex.*;
import javax.servlet.http.*;

public class Validator extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) throws IOException {
    response.setContentType("text/plain");
    response.setCharacterEncoding("UTF-8");

    PrintWriter out = response.getWriter();
    // 1) Both parameter of isInWhiteList are under user control
    if (isInWhiteList(request.getParameter("whitelist"), request.getParameter("value"))) {
      out.print("Value is in whitelist.");
    } else {
      out.print("Value is not in whitelist.");
    }
    out.flush();
  }

  public static boolean isInWhiteList(String whitelist, String value) {
    // 2) The regex ($whitelist) and the $value is under user control.
    //    Vulnerable against ReDoS:
    //    https://owasp.org/www-community/attacks/Regular_expression_Denial_of_Service_-_ReDoS
	//
	//    Also when both, the $whitelist and $value, is under user control
	//    there is no point in validating the user input ($value) because
	//    all user inputs can be in the $whitelist if e. g. $whitelist = "."
    Pattern pattern = Pattern.compile("^[" + whitelist + "]+");
    Matcher matcher = pattern.matcher(value);
    return matcher.matches();
  }
}
{{< /code >}}
