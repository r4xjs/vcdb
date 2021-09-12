---

title: rips-18
author: raxjs
tags: [java]

---

Java Servlet that plays with cookies and session to implement a LoadConfig class.

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import org.apache.tomcat.util.http.fileupload.IOUtils;
import javax.servlet.http.*;
import java.util.HashMap;

public class LoadConfig extends HttpServlet {
  public static HashMap<String, String> parseRequest(String value) {
    HashMap<String, String> result = new HashMap<String, String>();
    if (value != null) {
      String tmp[] = value.split("@");
      for (int i = 0; i < tmp.length; i = i + 2) {
        result.put(tmp[i], tmp[i + 1]);
      }
    }
    return result;
  }

  protected void doGet(HttpServletRequest request, HttpServletResponse response) {
    if (request.getParameter("home") != null) {
      HttpSession session = request.getSession(true);
      if (!session.isNew()){
        if (validBasicAuthHeader()) { // Checks the Basic Authorization header (password check)
          // Execute last command:
          ProcessBuilder processBuilder = new ProcessBuilder();
          processBuilder.command((String)session.getAttribute("last_command"));
          try {
            Process process = processBuilder.start();
            IOUtils.copy(process.getInputStream(), response.getOutputStream());
          }
          catch (Exception e){
            return;
          }
        }
      }
    } else if (request.getParameter("save_session") != null) {
      String value = request.getParameter("config");
      HashMap<String, String> config = parseRequest(value);
      for (String i : config.keySet()) {
        Cookie settings = new Cookie(i, config.get(i));
        response.addCookie(settings);
      }
    } else {
      HttpSession session = request.getSession(true);
      if (session.isNew()) {
        HashMap<String, String> whitelist = new HashMap<String, String>();
        whitelist.put("home", "yes");
        whitelist.put("role", "frontend");

        String value = request.getParameter("config");
        HashMap<String, String> config = parseRequest(value);

        whitelist.putAll(config);
        for (String i : whitelist.keySet()) {
          session.setAttribute(i, whitelist.get(i));
        }
      }
    }
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="19,26,29,30,52,55,58-60" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import org.apache.tomcat.util.http.fileupload.IOUtils;
import javax.servlet.http.*;
import java.util.HashMap;

public class LoadConfig extends HttpServlet {
  public static HashMap<String, String> parseRequest(String value) {
    HashMap<String, String> result = new HashMap<String, String>();
    if (value != null) {
      String tmp[] = value.split("@");
      for (int i = 0; i < tmp.length; i = i + 2) {
        result.put(tmp[i], tmp[i + 1]);
      }
    }
    return result;
  }

  protected void doGet(HttpServletRequest request, HttpServletResponse response) {
    // 1) parms: home, save_session, config
    if (request.getParameter("home") != null) {
      HttpSession session = request.getSession(true);
      if (!session.isNew()){
        if (validBasicAuthHeader()) { // Checks the Basic Authorization header (password check)
          // Execute last command:
          ProcessBuilder processBuilder = new ProcessBuilder();
	  // 5) $last_command could be under cuser controll, see step 4.
          processBuilder.command((String)session.getAttribute("last_command"));
          try {
            // 6) command execution with user controlled input, user needs to be authenticated
	    //    via basic auth before.
            Process process = processBuilder.start();
            IOUtils.copy(process.getInputStream(), response.getOutputStream());
          }
          catch (Exception e){
            return;
          }
        }
      }
    } else if (request.getParameter("save_session") != null) {
      String value = request.getParameter("config");
      HashMap<String, String> config = parseRequest(value);
      for (String i : config.keySet()) {
        Cookie settings = new Cookie(i, config.get(i));
        response.addCookie(settings);
      }
    } else {
      HttpSession session = request.getSession(true);
      if (session.isNew()) {
        HashMap<String, String> whitelist = new HashMap<String, String>();
        whitelist.put("home", "yes");
        whitelist.put("role", "frontend");
	// 2) $value and $config is under user control
        String value = request.getParameter("config");
        HashMap<String, String> config = parseRequest(value);
	// 3) merge user controlled $config into $whitelist
        whitelist.putAll(config);
        for (String i : whitelist.keySet()) {
          // 4) set user controlled key and value into the session.
	  //    It should be possible to set i=last_command here to
	  //    gain command execution later on.
          session.setAttribute(i, whitelist.get(i));
        }
      }
    }
  }
}
{{< /code >}}
