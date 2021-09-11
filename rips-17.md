---

title: rips-17
author: raxjs
tags: [java]

---

Setting environment variables in a safe way.

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
{{< code language="java" highlight="16-20,26,27,30,31,40,41,51,54-56" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
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
    // 3) split $key by "." and validate the first element with
    //    ab blacklist. Note: whitelist would be better here.
    //    Also something like this $key = ".java.xzy" will bypass the validation
    //    because ".java.xzy".split('.') = ["", "java", "xzy"]
    //    This means an empty string would be passt to the blacklist check.
    if (isInBlacklist(values[0])) {
      return;
    }

    List<String> list = new ArrayList<>(Arrays.asList(values));
    // 4) empty strings are filtered out and joined again.
    //    e.g.: ["", "java", "xyz"] ==> '.'.join(["java", "xyz"]) ==> "java.xyz"
    list.removeAll(Arrays.asList("", null));
    String property = String.join(".", list);
    // 5) then the rejoined $property string is used to set an env variable,
    //    with $property and $value under user control.
    System.setProperty(property, value);
  }

  private static void loadEnv(HttpServletRequest request) {
    Cookie[] cookies = request.getCookies();
    for (int i = 0; i < cookies.length; i++)
      if (cookies[i].getName().equals("env")) {
        String[] tmp = cookies[i].getValue().split("@", 2);
	// 2) iter over the cookies of the user and pass the env
	//    cookie to setEnv, $tmp[0] and $tmp[1] is under user control
        setEnv(tmp[0], tmp[1]);
      }
    }

  private static void uploadFile() {
    // Secure file upload with arbitrary content type and extension in known path /var/myapp/data
  }

  protected void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException {
    // 1) pass user input $request to loadEnv
    loadEnv(request);
    try {
      // 6) when sys_paths was set to "/var/myapp/data" and a attacker controlled lib named
      //    DEOBFUSCATION_LIB.so was uploaded there before, the attacker will gain code execution.
      //    Was not tested, so this may be wrong.
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
