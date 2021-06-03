import org.apache.commons.io.IOUtils;
import javax.servlet.http.*;
import java.io.*;
import java.util.*;

public class FindOnSystem extends HttpServlet {
  protected void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException {
    try {
      String[] binary = {"find", ".", "-type", "d"};
      ArrayList<String> cmd = new ArrayList<>(Arrays.asList(binary));

      String[] options = request.getParameter("options").split(" ");
      for (String i : options) {
        cmd.add(i);
      }

      ProcessBuilder processBuilder = new ProcessBuilder(cmd);
      Process process = processBuilder.start();
      IOUtils.copy(process.getInputStream(),response.getOutputStream());
    } catch(Exception e) {
      response.sendRedirect("/");
    }
  }
}
