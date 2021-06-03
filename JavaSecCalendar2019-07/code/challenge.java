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
