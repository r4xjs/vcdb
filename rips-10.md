---

title: rips-10
author: raxjs
tags: [java]

---

Java Webdav endpoint.

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
@RequestMapping("/webdav")
  public void webdav(HttpServletResponse res, @RequestParam("name") String name) throws IOException {
    res.setContentType("text/xml");
    res.setCharacterEncoding("UTF-8");
    PrintWriter pw = res.getWriter();
    name = name.replace("]]", "");
    pw.print("<person>");
    pw.print("<name><![CDATA[" + name.replace(" ","") + "]]></name>");
    pw.print("</person>");
  }

{{< /code >}}

# Solution
{{< code language="java" highlight="7,8,11-14" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
@RequestMapping("/webdav")
  public void webdav(HttpServletResponse res, @RequestParam("name") String name) throws IOException {
    res.setContentType("text/xml");
    res.setCharacterEncoding("UTF-8");
    PrintWriter pw = res.getWriter();
    // 1) $name is user input and "]]" deleted here. "]]" is not allowed
    //    because it would allow to escape the CDATA context below
    name = name.replace("]]", "");
    pw.print("<person>");
    // 2) also " " is deleted from the user input.
    //    Because "]]" and " " is deleted in that order we can
    //    slip in "]]" by inserting "] ]" and therefore escape
    //    the CDATA context and inject other XML elements.
    pw.print("<name><![CDATA[" + name.replace(" ","") + "]]></name>");
    pw.print("</person>");
  }
{{< /code >}}
