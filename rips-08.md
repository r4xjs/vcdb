---

title: rips-08
author: raxjs
tags: [java]

---

File handling in java.

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import java.io.File;
import javax.servlet.http.*;

public class GetPath extends HttpServlet {
  protected void doGet(HttpServletRequest request,
                       HttpServletResponse response) throws IOException {
    try {
      String icons = request.getParameter("icons");
      String filename = request.getParameter("filename");

      File f_icons = new File(icons);
      File f_filename = new File(filename);

      if (!icons.equals(f_icons.getName())) {
        throw new Exception("File not within target directory!");
      }

      if (!filename.equals(f_filename.getName())) {
        throw new Exception("File not within target directory!");
      }

      String toDir = "/var/myapp/data/" + f_icons.getName() + "/";
      File file = new File(toDir, filename);

      // Download file...
    } catch(Exception e) {
      response.sendRedirect("/");
    }
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="22,23,26,27" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import java.io.File;
import javax.servlet.http.*;

public class GetPath extends HttpServlet {
  protected void doGet(HttpServletRequest request,
                       HttpServletResponse response) throws IOException {
    try {
      String icons = request.getParameter("icons");
      String filename = request.getParameter("filename");

      File f_icons = new File(icons);
      File f_filename = new File(filename);

      if (!icons.equals(f_icons.getName())) {
        throw new Exception("File not within target directory!");
      }

      if (!filename.equals(f_filename.getName())) {
        throw new Exception("File not within target directory!");
      }
      // 1) if $icons = ".." then $f_icons.getName() = ".."
      //    then $toDir = /var/myapp/
      String toDir = "/var/myapp/data/" + f_icons.getName() + "/";
      File file = new File(toDir, filename);
      // 2) then $file path = /var/myapp/$filename
      //    --> we can escape out of the `data` directory

      // Download file...
    } catch(Exception e) {
      response.sendRedirect("/");
    }
  }
}
{{< /code >}}
