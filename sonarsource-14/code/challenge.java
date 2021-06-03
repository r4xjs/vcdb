import javax.servlet.*;
import javax.servlet.annotation.WebServlet;
import java.io.*;
import java.util.Enumeration;
import java.util.HashSet;
import java.util.Set;
import java.util.zip.ZipEntry;
import java.util.zip.ZipFile;
import org.apache.commons.io.FileUtils;
import org.apache.commons.io.IOUtils;
@WebServlet(value="/unzip", name="ZipUtils")
class ZipUtils extends GenericServlet {
    private static final String BASE_DIR = "projects";
    @Override
    public void service(ServletRequest req, ServletResponse res) throws IOException {
        File zipFile = new File(BASE_DIR, req.getParameter("file"));
        if (zipFile.getCanonicalPath().startsWith(BASE_DIR)) {
            File indir = new File("/tmp/local/my_jars");
            unjar(zipFile, indir);
        }
    }
    private File[] unjar(File uploadFile, File inDir) throws IOException {
        String uploadFileName = inDir + File.separator + uploadFile.getName();
        ZipFile uploadZipFile = new ZipFile(uploadFile);
        Set<File> files = new HashSet<File>();
        Enumeration entries = uploadZipFile.entries();
        // unpack uploaded zip file
        while (entries.hasMoreElements()) {
            ZipEntry entry = (ZipEntry) entries.nextElement();
            File fe = new File(uploadFileName, entry.getName());
            if (entry.isDirectory()) {
                fe.mkdirs();
            } else {
                if (fe.getParentFile() != null 
                && !fe.getParentFile().exists()) {
                    fe.getParentFile().mkdirs();
                }
                files.add(fe);
                IOUtils.copy(uploadZipFile.getInputStream(entry), 
                    new BufferedOutputStream(new FileOutputStream(fe)));
            }
        }
        uploadZipFile.close();
        return files.toArray(new File[files.size()]);
    }
}
