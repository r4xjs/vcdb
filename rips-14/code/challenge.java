import java.io.PrintWriter;
import java.util.*;
import javax.servlet.http.*;

public class Export extends HttpServlet {
  protected void doPost(HttpServletRequest request,
                        HttpServletResponse response) throws IOException {
    response.setContentType("text/csv");
    response.setCharacterEncoding("UTF-8");
    PrintWriter out = response.getWriter();

    String content = buildCSV(request);
    out.print(content);
    out.flush();
  }

  public String buildCSV(HttpServletRequest request) {
    {
      StringBuilder str = new StringBuilder();

      List<List<String>> rows = Arrays.asList(
        Arrays.asList("Scott", "editor", request.getParameter("description"))
      );

      str.append("Name");
      str.append(",");
      str.append("Role");
      str.append(",");
      str.append("Description");
      str.append("\n");

      for (List<String> rowData : rows) {
        str.append(String.join(",", rowData));
        str.append("\n");
      }

      return str.toString();
    }
  }
}
