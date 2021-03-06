---

title: rips-02
author: raxjs
tags: [java]

---

Java MainController with json user input.

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import org.json.*;

public class MainController{
  private static String[] parseJsonAsArray(String rawJson, String field) {
    JSONObject obj = new JSONObject(rawJson);
    JSONArray arrJson = obj.getJSONArray(field);
    String[] arr = new String[arrJson.length()];
    for (int i = 0; i < arrJson.length(); i++) {
      arr[i] = arrJson.getString(i);
    }
    return arr;
  }

  private static String parseJsonAsString(String rawJson, String field) {
    JSONObject obj = new JSONObject(rawJson);
    return obj.getString(field);
  }

  // rawJson is user-controlled.
  public MainController(String rawJson) {
    this(parseJsonAsString(rawJson, "controller"), parseJsonAsString(rawJson, "task"), parseJsonAsArray(rawJson, "data"));
  }

  private MainController(String controllerName, String task, String... data) {
    try {
      Object controller = !controllerName.equals("MainController") ? Class.forName(controllerName).getConstructor(String[].class).newInstance((Object) data) : this;
      System.out.println(controller.getClass().getMethod(task));
      controller.getClass().getMethod(task).invoke(controller);
    } catch (Exception e1) {
      try {
        String log = "# [ERROR] Exception with data: " + data + " with exception " + e1;
        System.err.println(log);
        // DONE: VulnApp Security Bug #23517: Strip all "dots" so file extension does not lead to RCE
        Runtime.getRuntime().exec(new String[]{"java", "-jar", "log4j_custom_dlogger.jar", log.replaceAll(".", "")});
        // TODO: VulnApp Bug #24630: Logging is currently not working in v1.8,
        //       something with an ArgumentException, please have alook at that @peter
      } catch (Exception e2) {
        System.err.println("FATAL ERROR: " + e2);
      }
    }
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="22,29,30,34-38" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import org.json.*;

public class MainController{
  private static String[] parseJsonAsArray(String rawJson, String field) {
    JSONObject obj = new JSONObject(rawJson);
    JSONArray arrJson = obj.getJSONArray(field);
    String[] arr = new String[arrJson.length()];
    for (int i = 0; i < arrJson.length(); i++) {
      arr[i] = arrJson.getString(i);
    }
    return arr;
  }

  private static String parseJsonAsString(String rawJson, String field) {
    JSONObject obj = new JSONObject(rawJson);
    return obj.getString(field);
  }

  // rawJson is user-controlled.
  public MainController(String rawJson) {
    // 1) side note: pasing the json input three times, would be better if cached.
    this(parseJsonAsString(rawJson, "controller"), parseJsonAsString(rawJson, "task"), parseJsonAsArray(rawJson, "data"));
  }

  private MainController(String controllerName, String task, String... data) {
    try {
      Object controller = !controllerName.equals("MainController") ? \
	  // 2) $controllerName is under user control and the only
	  //    limitation is that it is not equal "MainController"
	  Class.forName(controllerName).getConstructor(String[].class).newInstance((Object) data) : \
	  this;
      System.out.println(controller.getClass().getMethod(task));
      // 3) $controller and $task is directly or indirectly controlled by the user.
      //    We can create:
      //        Object controller = $CLASS;
      //        controller.$TASK(arg)
      //    where the name of $CLASS and $TASK is under user control
      controller.getClass().getMethod(task).invoke(controller);
    } catch (Exception e1) {
      try {
        String log = "# [ERROR] Exception with data: " + data + " with exception " + e1;
        System.err.println(log);
        // DONE: VulnApp Security Bug #23517: Strip all "dots" so file extension does not lead to RCE
        Runtime.getRuntime().exec(new String[]{"java", "-jar", "log4j_custom_dlogger.jar", log.replaceAll(".", "")});
        // TODO: VulnApp Bug #24630: Logging is currently not working in v1.8,
        //       something with an ArgumentException, please have alook at that @peter
      } catch (Exception e2) {
        System.err.println("FATAL ERROR: " + e2);
      }
    }
  }
}


{{< /code >}}
