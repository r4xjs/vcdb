---

title: rips-11
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import javax.servlet.http.*;
import java.io.*;
import java.nio.file.Files;
import org.apache.commons.compress.archivers.ArchiveStreamFactory;
import org.apache.commons.compress.archivers.tar.*;
import org.apache.commons.io.IOUtils;

public class ExtractFiles extends HttpServlet {
  private static void extract() throws Exception {
    // /tmp/uploaded.tar is user controlled and an uploaded file.
    final InputStream is = new FileInputStream(new File("/tmp/uploaded.tar"));
    final TarArchiveInputStream tarInputStream = (TarArchiveInputStream) (new ArchiveStreamFactory().createArchiveInputStream(ArchiveStreamFactory.TAR, is));
    File tmpDir = Files.createTempDirectory("test").toFile();
    TarArchiveEntry entry;
    while ((entry = tarInputStream.getNextTarEntry()) != null) {
      File file = new File(tmpDir, entry.getName().replace("../", ""));
      if (entry.isDirectory()) {
        file.mkdirs();
      } else {
        IOUtils.copy(tarInputStream, new FileOutputStream(file));
      }
    }
    is.close();
    tarInputStream.close();
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}