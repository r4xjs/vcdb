---

title: rips-14
author: raxjs
tags: [java]

---

Java Servlet to generate/export CSVs.

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import java.io.PrintWriter;
import java.util.*;
import javax.servlet.http.*;

public class Export extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) throws IOException {
    response.setContentType("text/csv");
    response.setCharacterEncoding("UTF-8");
    PrintWriter out = response.getWriter();

    String content = buildCSV(request);
    out.print(content);
    out.flush();
  }

  public String buildCSV(HttpServletRequest request) {
    {
      StringBuilder str = new StringBuilder();

      List<List<String>> rows = Arrays.asList(
        Arrays.asList("Scott", "editor", request.getParameter("description"))
      );

      str.append("Name");
      str.append(",");
      str.append("Role");
      str.append(",");
      str.append("Description");
      str.append("\n");

      for (List<String> rowData : rows) {
        str.append(String.join(",", rowData));
        str.append("\n");
      }

      return str.toString();
    }
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="12,23,35,36" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import java.io.PrintWriter;
import java.util.*;
import javax.servlet.http.*;

public class Export extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) throws IOException {
    response.setContentType("text/csv");
    response.setCharacterEncoding("UTF-8");
    PrintWriter out = response.getWriter();
    // 1) user input $request is passed to buildCSV
    String content = buildCSV(request);
    out.print(content);
    out.flush();
  }

  public String buildCSV(HttpServletRequest request) {
    {
      StringBuilder str = new StringBuilder();

      List<List<String>> rows = Arrays.asList(
        // 2) request['description'] is user input as well and is not restricted.
        Arrays.asList("Scott", "editor", request.getParameter("description"))
      );

      str.append("Name");
      str.append(",");
      str.append("Role");
      str.append(",");
      str.append("Description");
      str.append("\n");

      for (List<String> rowData : rows) {
	    //  3) It is possible to insert new csv rows via:
        //     $description = "desc-scott\nSmith,Admin,Le-boss"
        str.append(String.join(",", rowData));
        str.append("\n");
      }

      return str.toString();
    }
  }
}
{{< /code >}}
