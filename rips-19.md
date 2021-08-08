---

title: rips-19
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import javax.script.ScriptEngineManager;
import javax.servlet.http.*;
import javax.script.ScriptEngine;
import java.io.IOException;
import java.util.regex.*;

public class RenderExpression extends HttpServlet {
  protected void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException {
    try {
      ScriptEngineManager scriptEngineManager = new ScriptEngineManager();
      ScriptEngine scriptEngine = scriptEngineManager.getEngineByExtension("js");

      String dynamiceCodeHere = request.getParameter("p");
      if (!dynamiceCodeHere.startsWith("\"")) {
        throw new Exception();
      }

      Pattern p = Pattern.compile("([^\".()'\\/,a-zA-z\\s\\\\])|(processbuilder|file|url|runtime|getclass|forname|loadclass|new\\s)");
      Matcher m = p.matcher(dynamiceCodeHere.toLowerCase());
      if (m.find()) {
        throw new Exception();
      }

      scriptEngine.eval(dynamiceCodeHere);
      // Proceed
    } catch(Exception e) {
      response.sendRedirect("/");
    }
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}