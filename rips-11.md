---

title: rips-11
author: raxjs
tags: [java]

---

Extracting a tar file in Java.

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
    final TarArchiveInputStream tarInputStream = \
		(TarArchiveInputStream) (new ArchiveStreamFactory(). \
				 createArchiveInputStream(ArchiveStreamFactory.TAR, is));
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
{{< code language="java" highlight="19,21-25" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
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
    final TarArchiveInputStream tarInputStream = \
		(TarArchiveInputStream) (new ArchiveStreamFactory(). \
				 createArchiveInputStream(ArchiveStreamFactory.TAR, is));

    File tmpDir = Files.createTempDirectory("test").toFile();
    TarArchiveEntry entry;
    // 1) if uploaded.tar is user input then entry is also user input
    while ((entry = tarInputStream.getNextTarEntry()) != null) {
      // 2) the tar entry name is sanitized by stripping ../ but this is not recursive
      //    which means we can bypass it by inserting "..././".replace("../", "") wich will
      //    eval to "../"
      //    Therefore we can copy files/directories outside the current working directory
      //    to any location we want.
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
