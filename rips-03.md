---

title: rips-03
author: raxjs
tags: [java]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.velocity.app.VelocityEngine;
import org.apache.velocity.VelocityContext;
import java.util.HashMap;
import java.io.*;
import java.util.Map;

public class TemplateRenderer extends HttpServlet {

    private static final long serialVersionUID = -1915463532411657451L;

    @Override
    protected void doGet(HttpServletRequest request,
                         HttpServletResponse response) throws ServletException, IOException {
        VelocityEngine velocity;
        velocity = new VelocityEngine();
        velocity.init();
        Map<String, Object> hm = new HashMap<String, Object>();
        hm.put("user", request.getParameter("user"));
        String template = request.getParameter("temp");
        VelocityContext context =  new VelocityContext(hm);
        StringWriter tempWriter = new StringWriter(template.length());
        velocity.evaluate(context, tempWriter, "renderFragment", template);
        String rendered = tempWriter.toString();
        response.getWriter().println(rendered);

    }

    @Override
    protected void doPost(HttpServletRequest request,
                          HttpServletResponse response) throws ServletException, IOException {
        //Do some other work
    }



    }

{{< /code >}}

# Solution
{{< code language="java" highlight="22,27,28" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.velocity.app.VelocityEngine;
import org.apache.velocity.VelocityContext;
import java.util.HashMap;
import java.io.*;
import java.util.Map;

public class TemplateRenderer extends HttpServlet {

    private static final long serialVersionUID = -1915463532411657451L;

    @Override
    protected void doGet(HttpServletRequest request,
                         HttpServletResponse response) throws ServletException, IOException {
        VelocityEngine velocity;
        velocity = new VelocityEngine();
        velocity.init();
        Map<String, Object> hm = new HashMap<String, Object>();
	// 1) hm['user'] and template is under user controll
        hm.put("user", request.getParameter("user"));
        String template = request.getParameter("temp");
        VelocityContext context =  new VelocityContext(hm);
        StringWriter tempWriter = new StringWriter(template.length());
	// 2) template injection via template and context
	//    see for example: 
        //    https://www.linkedin.com/pulse/apache-velocity-server-side-template-injection-marjan-sterjev
        velocity.evaluate(context, tempWriter, "renderFragment", template);
        String rendered = tempWriter.toString();
        response.getWriter().println(rendered);

    }

    @Override
    protected void doPost(HttpServletRequest request,
                          HttpServletResponse response) throws ServletException, IOException {
        //Do some other work
    }



    }
{{< /code >}}
