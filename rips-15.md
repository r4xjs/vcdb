---

title: rips-15
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import org.apache.commons.io.IOUtils;
import javax.servlet.http.*;
import java.io.*;
import java.util.*;

public class FindOnSystem extends HttpServlet {
  protected void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException {
    try {
      String[] binary = {"find", ".", "-type", "d"};
      ArrayList<String> cmd = new ArrayList<>(Arrays.asList(binary));

      String[] options = request.getParameter("options").split(" ");
      for (String i : options) {
        cmd.add(i);
      }

      ProcessBuilder processBuilder = new ProcessBuilder(cmd);
      Process process = processBuilder.start();
      IOUtils.copy(process.getInputStream(),response.getOutputStream());
    } catch(Exception e) {
      response.sendRedirect("/");
    }
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}