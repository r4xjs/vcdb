---

title: rips-10
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

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
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}