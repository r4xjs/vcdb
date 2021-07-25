import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.Properties;
import javax.servlet.ServletContext;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;

public class IndexServlet {
    private final ServletContext context;
    private final String templateFile = "/org/java/repository.xml";

    public IndexServlet(ServletContext context) {
        this.context = context;
    }

    public void installRepository(HttpServletRequest req)
            throws ServletException, IOException {
        // 1) $mode and $repHome is user input
        String mode = req.getParameter("mode");
        String repHome = req.getParameter("repository_home");
        if (repHome != null && mode != null && "new".equals(mode)) {
            // 2) mode = "new", repHome = *
            installConfig(new File(repHome));
        }
    }
    private void installConfig(File dest) throws IOException {
        InputStream in = context.getResourceAsStream(templateFile);
        // 3) open user controlled file path here
        OutputStream out = new FileOutputStream(dest);
        byte[] buffer = new byte[8192]; int read;
        while ((read = in.read(buffer)) >= 0) {
            // 4) write to user controlled file path here
            out.write(buffer, 0, read);
        }
        in.close();
        out.close();
    }
}
