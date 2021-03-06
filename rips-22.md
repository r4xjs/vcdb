---

title: rips-22
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import org.apache.commons.io.IOUtils;
import java.net.*;
import javax.servlet.http.*;

public class ReadExternalUrl extends HttpServlet {

  private static URLConnection getUrl(String target) {
    try{
      // Don't allow redirects:
      HttpURLConnection.setFollowRedirects(false);

      URL url = new URL(target);
      if(!url.getProtocol().startsWith("http"))
        throw new Exception("Must start with http!.");

      InetAddress inetAddress = InetAddress.getByName(url.getHost());
      if (inetAddress.isAnyLocalAddress() || inetAddress.isLoopbackAddress() || inetAddress.isLinkLocalAddress())
        throw new Exception("No local urls allowed!");

      HttpURLConnection conn = (HttpURLConnection) url.openConnection();
      return conn;
    }
    catch (Exception e) {
      return null;
    }
  }

  protected void doGet(HttpServletRequest request,
                       HttpServletResponse response) {
    try{
      URLConnection conn = getUrl(request.getParameter("url"));
      conn.connect();
      String redirect = conn.getHeaderField("Location");
      if(redirect != null) {
        URL url = new URL(redirect);
        if(redirect.indexOf("http://") == -1) {
          throw new Exception("No http found!");
        }
        if(getUrl(redirect.substring(redirect.indexOf("http://"))) != null) {
          conn = url.openConnection();
          conn.connect();
        }
      }
      // Output content of url
      IOUtils.copy(conn.getInputStream(),response.getOutputStream());
    }
    catch (Exception e) {
      System.exit(-1);
    }
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}