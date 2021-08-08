---

title: rips-12
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

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
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}