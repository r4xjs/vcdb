---

title: sonarsource-14
author: raxjs
tags: [java]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1337064532355633152" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
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

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
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
        // 1) zipFile is user input
        File zipFile = new File(BASE_DIR, req.getParameter("file"));
        if (zipFile.getCanonicalPath().startsWith(BASE_DIR)) {
            // 2) zipFile = projects/../../../../../projectssss.zip will for example also pass
            //    but does not matter in this case
            File indir = new File("/tmp/local/my_jars");
            unjar(zipFile, indir);
        }
    }
    private File[] unjar(File uploadFile, File inDir) throws IOException {
        // uploadFile is user input
        String uploadFileName = inDir + File.separator + uploadFile.getName();
        ZipFile uploadZipFile = new ZipFile(uploadFile);
        Set<File> files = new HashSet<File>();
        // 3) entries are under user control as well, they are the file names in the zip file
        Enumeration entries = uploadZipFile.entries();
        // unpack uploaded zip file
        while (entries.hasMoreElements()) {
            // 4) entry is a file name in the zip file which is under user control
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
                // 5) entry is used here which should leed to path traversal
                //    ... indented solution.
                IOUtils.copy(uploadZipFile.getInputStream(entry), 
                    new BufferedOutputStream(new FileOutputStream(fe)));
            }
        }
        uploadZipFile.close();
        return files.toArray(new File[files.size()]);
    }
}

{{< /code >}}