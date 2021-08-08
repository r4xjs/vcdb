---

title: rips-06
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import java.io.*;
import java.nio.file.*;
import javax.servlet.http.*;

public class ReadFile extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) throws IOException {
    try {
      String url = request.getParameter("url");
      String data = new String(Files.readAllBytes(Paths.get(url)));
    } catch (IOException e) {
      PrintWriter out = response.getWriter();
      out.print("File not found");
      out.flush();
    }
    //proceed with code
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}