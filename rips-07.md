---

title: rips-07
author: raxjs
tags: [java]

---

Java Servelet that can store JSON user settings via Jackson.

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import com.fasterxml.jackson.core.*;
import javax.servlet.http.*;
import java.io.*;

public class ApiCache extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) throws IOException {
    storeJson(request, "/tmp/getUserInformation.json");
  }

  protected void doGet(HttpServletRequest request,
                       HttpServletResponse response) {
    loadJson();
  }

  public static void loadJson() {
      // Deserialize to an HashMap object with Jackson's JsonParser and read the first 2 entries of the file.
  }

  public static void storeJson(HttpServletRequest request, String filename) throws IOException {
    JsonFactory jsonobject = new JsonFactory();
    JsonGenerator jGenerator = jfactory.createGenerator(new File(filename), JsonEncoding.UTF8);
    jGenerator.writeStartObject();
    jGenerator.writeFieldName("username");
    jGenerator.writeRawValue("\"" + request.getParameter("username") + "\"");
    jGenerator.writeFieldName("permission");
    jGenerator.writeRawValue("\"none\"");
    jGenerator.writeEndObject();
    jGenerator.close();
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="9,27-39" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import com.fasterxml.jackson.core.*;
import javax.servlet.http.*;
import java.io.*;

public class ApiCache extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) throws IOException {
    // 1) $request (user input) goes to storeJson
    storeJson(request, "/tmp/getUserInformation.json");
  }

  protected void doGet(HttpServletRequest request,
                       HttpServletResponse response) {
    loadJson();
  }

  public static void loadJson() {
      // Deserialize to an HashMap object with Jackson's JsonParser and read the first 2 entries of the file.
  }

  public static void storeJson(HttpServletRequest request, String filename) throws IOException {
    JsonFactory jsonobject = new JsonFactory();
    JsonGenerator jGenerator = jfactory.createGenerator(new File(filename), JsonEncoding.UTF8);
    jGenerator.writeStartObject();
    jGenerator.writeFieldName("username");
    // 2) $request['username'] is passed to JsonGenerator::writeRawValue
    //    which allows injecting json into the generated file.
    //    From the docs:
    //    -------------------------------------------------------------------------
    //        Method that will force generator to copy input text *verbatim without
    //        any modifications*, but assuming it must constitute a single legal JSON
    //        value (number, string, boolean, null, Array or List). Assuming this,
    //        proper separators are added if and as needed (comma or colon),
    //        and generator state updated to reflect this.
    //    -------------------------------------------------------------------------
    //    Therefore we can, for example, inject a new permission entry to escalate our
    //    privileges. It would be better if JsonGenerator::writeString or even
    //    writeStringField is used.
    jGenerator.writeRawValue("\"" + request.getParameter("username") + "\"");
    jGenerator.writeFieldName("permission");
    jGenerator.writeRawValue("\"none\"");
    jGenerator.writeEndObject();
    jGenerator.close();
  }
}


{{< /code >}}
