---

title: rips-05
author: raxjs
tags: [java]

---

Java Servlet toString implementation.

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import javax.servlet.http.HttpServletRequest;
import java.util.Enumeration;

public class Request {
  public static String toString(HttpServletRequest req) {
    StringBuilder sb = new StringBuilder();
    String delimiter = req.getParameter("delim");
    Enumeration<String> names = req.getParameterNames();
    while (names.hasMoreElements()) {
      String name = names.nextElement();
      if (!name.equals("delim")) {
        sb.append("<b>" + name + "</b>:<br>");
        String[] values = req.getParameterValues(name);
        for (String val : values) {
          sb.append(val);
          sb.append(delimiter);
          sb.append("<br>");
        }
      }
    }
    return sb.toString();
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="7,14,24,25" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import javax.servlet.http.HttpServletRequest;
import java.util.Enumeration;

public class Request {
  public static String toString(HttpServletRequest req) {
    // 1) $req is user input
    StringBuilder sb = new StringBuilder();
    String delimiter = req.getParameter("delim");
    Enumeration<String> names = req.getParameterNames();
    while (names.hasMoreElements()) {
      String name = names.nextElement();
      if (!name.equals("delim")) {
        // 2) building up a string that contains user input
        sb.append("<b>" + name + "</b>:<br>");
        String[] values = req.getParameterValues(name);
        for (String val : values) {
          sb.append(val);
          sb.append(delimiter);
          sb.append("<br>");
        }
      }
    }
    // 3) if the returned string is used in the clients browser
    //    then xss is possible
    return sb.toString();
  }
}
{{< /code >}}
