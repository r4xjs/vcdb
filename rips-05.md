---

title: rips-05
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

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
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}