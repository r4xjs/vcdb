package org.example;
import javax.servlet.ServletException;
import javax.servlet.http.*;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.List;
import java.util.zip.ZipEntry;
import java.util.zip.ZipInputStream;
import org.jdom2.Content;
import org.jdom2.Document;
import org.jdom2.JDOMException;
import org.jdom2.input.SAXBuilder;
public class IndexServlet extends HttpServlet {
    private String extractContent() throws IOException, JDOMException {
        File uploadFile = new File("/users/upload/document.odt");
        InputStream in = new FileInputStream(uploadFile);
        final ZipInputStream zis = new ZipInputStream(in);
        ZipEntry entry;
        List<Content> content = null;
        while ((entry = zis.getNextEntry()) != null) {
            if (entry.getName().equals("content.xml")) {
                final SAXBuilder sax = new org.jdom2.input.SAXBuilder();
                Document doc = sax.build(zis);
                content = doc.getContent();
                StringBuilder sb = new StringBuilder();
                if (content != null) {
                    for (Content item : content) {
                        sb.append(item.getValue());
                    }
                }
                zis.close();
                return sb.toString();
            }
        }
        return null;
    }
    protected void doGet(HttpServletRequest req, HttpServletResponse res)
        throws ServletException, IOException
    {
        try {
            extractContent();
        }
        catch(Exception e) {
            return;
        }
    }
}
