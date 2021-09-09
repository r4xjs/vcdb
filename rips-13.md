---

title: rips-13
author: raxjs
tags: [java]

---

Uploading some files via Java Servlet and org.apache.commons.fileuplad.*.

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import javax.servlet.http.*;
import java.io.*;
import java.util.List;
import org.apache.commons.fileupload.FileItem;
import org.apache.commons.fileupload.disk.DiskFileItemFactory;
import org.apache.commons.fileupload.servlet.ServletFileUpload;

public class UploadFileController extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) throws IOException {
    DiskFileItemFactory factory = new DiskFileItemFactory();
    factory.setRepository(new File(System.getProperty("java.io.tmpdir")));
    ServletFileUpload upload = new ServletFileUpload(factory);

    String uploadPath = getServletContext().getRealPath("") + "upload";
    File uploadDir = new File(uploadPath);
    if (!uploadDir.exists()) {
      uploadDir.mkdir();
    }
    try {
      List<FileItem> items = upload.parseRequest(request);
      if (items != null && items.size() > 0) {
        for (FileItem item : items) {
          if (!item.isFormField()) {
            if (!(item.getContentType().equals("text/plain"))) {
              throw new Exception("ContentType mismatch");
            }
            String file = uploadPath + File.separator + item.getName();
            File storeFile = new File(file);
            item.write(storeFile);
          }
        }
      }
    } catch (Exception ex) {
      response.sendRedirect("/");
    }
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="22,30,31" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import javax.servlet.http.*;
import java.io.*;
import java.util.List;
import org.apache.commons.fileupload.FileItem;
import org.apache.commons.fileupload.disk.DiskFileItemFactory;
import org.apache.commons.fileupload.servlet.ServletFileUpload;

public class UploadFileController extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) throws IOException {
    DiskFileItemFactory factory = new DiskFileItemFactory();
    factory.setRepository(new File(System.getProperty("java.io.tmpdir")));
    ServletFileUpload upload = new ServletFileUpload(factory);

    String uploadPath = getServletContext().getRealPath("") + "upload";
    File uploadDir = new File(uploadPath);
    if (!uploadDir.exists()) {
      uploadDir.mkdir();
    }
    try {
      // 1) $items is under user control
      List<FileItem> items = upload.parseRequest(request);
      if (items != null && items.size() > 0) {
        for (FileItem item : items) {
          if (!item.isFormField()) {
            if (!(item.getContentType().equals("text/plain"))) {
              throw new Exception("ContentType mismatch");
            }
	    // 2) item.getName() can contain path infos according to [1]
	    //    therefore directory traversal should be possible here.
            String file = uploadPath + File.separator + item.getName();
            File storeFile = new File(file);
            item.write(storeFile);
          }
        }
      }
    } catch (Exception ex) {
      response.sendRedirect("/");
    }
  }
}

// [1] http://commons.apache.org/proper/commons-fileupload/apidocs/org/apache/commons/fileupload/FileItem.html#getName--
{{< /code >}}
