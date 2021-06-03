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
