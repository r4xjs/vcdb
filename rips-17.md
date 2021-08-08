---

title: rips-17
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import java.io.IOException;
import java.lang.reflect.Field;
import java.util.*;
import java.util.regex.Pattern;
import javax.servlet.http.*;

public class JavaDeobfuscatorStartupController extends HttpServlet {
  private static boolean isInBlacklist(String input) {
    String[] blacklist = {"java","os","file"};
    return Arrays.asList(blacklist).contains(input);
  }

  private static void setEnv(String key, String value) {
    String[] values = key.split(Pattern.quote("."));
    if (isInBlacklist(values[0])) {
      return;
    }

    List<String> list = new ArrayList<>(Arrays.asList(values));
    list.removeAll(Arrays.asList("", null));
    String property = String.join(".", list);
    System.setProperty(property, value);
  }

  private static void loadEnv(HttpServletRequest request) {
    Cookie[] cookies = request.getCookies();
    for (int i = 0; i < cookies.length; i++)
      if (cookies[i].getName().equals("env")) {
        String[] tmp = cookies[i].getValue().split("@", 2);
        setEnv(tmp[0], tmp[1]);
      }
    }

  private static void uploadFile() {
    // Secure file upload with arbitrary content type and extension in known path /var/myapp/data
  }

  protected void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException {
    loadEnv(request);
    try {
      final Field sysPathsField = ClassLoader.class.getDeclaredField("sys_paths");
      sysPathsField.setAccessible(true);
      sysPathsField.set(null, null);
      System.loadLibrary("DEOBFUSCATION_LIB");
    } catch (Exception e) {
      response.sendRedirect("/");
    }
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}