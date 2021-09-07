---

title: rips-12
author: raxjs
tags: [java]

---

Fun with a JSP template.

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
<%@ page import="org.owasp.esapi.ESAPI" %>
<%! String customClass = "default"; %>
<html><body><%@ include file="init.jsp" %>

<div class="<%= customClass %>">
  <%! String username; %>
  <% username = request.getParameter("username"); %>
  Welcome citizen, you have been identified as
  <%
    customClass = request.getParameter("customClass");
    customClass = ESAPI.encoder().encodeForHTML(customClass);
  %>
  <div class="<%= customClass %>">
  <%= ESAPI.encoder().encodeForHTML(username) %>.
</div></div></body></html>
{{< /code >}}

# Solution
{{< code language="java" highlight="11,15-20" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
<%@ page import="org.owasp.esapi.ESAPI" %>
<%! String customClass = "default"; %>
<html><body><%@ include file="init.jsp" %>

<div class="<%= customClass %>">
  <%! String username; %>
  <% username = request.getParameter("username"); %>
  Welcome citizen, you have been identified as
  <%
    // 1) $customClass is user input and is encoded for HTML
    customClass = request.getParameter("customClass");
    customClass = ESAPI.encoder().encodeForHTML(customClass);
  %>
  // 2) the encoded user input ($customClass) is now used
  //    in an HTML attribute context. We can break out of
  //    the HTML attribute context with a " character which
  //    is not escaped by ESAPI.encoder().encodeForHTML().
  //    To fix this issue $customClass should be encoded with
  //    ESAPI.encoder().encodeForHTMLAttribute() instead.
  <div class="<%= customClass %>">
  <%= ESAPI.encoder().encodeForHTML(username) %>.
</div></div></body></html>

// [1] https://javadoc.io/static/org.owasp.esapi/esapi/2.0.1/org/owasp/esapi/Encoder.html#encodeForHTML(java.lang.String)
// [2] https://javadoc.io/static/org.owasp.esapi/esapi/2.0.1/org/owasp/esapi/Encoder.html#encodeForHTMLAttribute(java.lang.String)
{{< /code >}}
