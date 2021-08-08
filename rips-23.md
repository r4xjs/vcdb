---

title: rips-23
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import javax.servlet.http.*;
import java.io.*;
import java.text.*;
import java.util.*;
import org.apache.commons.lang3.StringEscapeUtils;

public class ShowCalendar extends HttpServlet {
  protected void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException {
    try {
      response.setContentType("text/html");
      GregorianCalendar calendar = new GregorianCalendar();
      SimpleTimeZone x = new SimpleTimeZone(0, request.getParameter("id"));
      SimpleDateFormat parser=new SimpleDateFormat("EEE MMM d HH:mm:ss zzz yyyy");
      calendar.setTime(parser.parse(request.getParameter("current_time")));
      calendar.setTimeZone(x);
      Formatter formatter = new Formatter();
      String name = StringEscapeUtils.escapeHtml4(request.getParameter("name"));
      formatter.format("Name of your calendar: " + name + " and your current date is: %1$te.%1$tm.%1$tY", calendar);
      PrintWriter pw = response.getWriter();
      pw.print(formatter.toString());
    } catch(ParseException e) {
      response.sendRedirect("/");
    }
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}