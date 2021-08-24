---

title: rips-01
author: raxjs
tags: [java]

---

Importing odt files in Java.

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import org.jdom2.Content;
import org.jdom2.Document;
import org.jdom2.JDOMException;
import org.jdom2.input.SAXBuilder;

public class ImportDocument {
  // This function extracts the text of an OpenOffice document
  public static String extractString() throws IOException, JDOMException {
    File initialFile = new File("uploaded_office_doc.odt");
    InputStream in = new FileInputStream(initialFile);
    final ZipInputStream zis = new ZipInputStream(in);
    ZipEntry entry;
    List<Content> content = null;
    while ((entry = zis.getNextEntry()) != null) {
      if (entry.getName().equals("content.xml")) {
        final SAXBuilder sax = new org.jdom2.input.SAXBuilder();
        sax.setFeature("http://javax.xml.XMLConstants/feature/secure-processing",true);
        Document doc = sax.build(zis);
        content = doc.getContent();
        zis.close();
        break;
      }
    }
    StringBuilder sb = new StringBuilder();
    if (content != null) {
      for(Content item : content){
        sb.append(item.getValue());
      }
    }
    return sb.toString();
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="10,13,21-24" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import org.jdom2.Content;
import org.jdom2.Document;
import org.jdom2.JDOMException;
import org.jdom2.input.SAXBuilder;

public class ImportDocument {
  // This function extracts the text of an OpenOffice document
  public static String extractString() throws IOException, JDOMException {
	// 1) read user input
    File initialFile = new File("uploaded_office_doc.odt");
    InputStream in = new FileInputStream(initialFile);                        
	// 2) unzip user input. also zip bomb?
    final ZipInputStream zis = new ZipInputStream(in);
    ZipEntry entry;
    List<Content> content = null;
    while ((entry = zis.getNextEntry()) != null) {
      if (entry.getName().equals("content.xml")) {
        final SAXBuilder sax = new org.jdom2.input.SAXBuilder();
        sax.setFeature("http://javax.xml.XMLConstants/feature/secure-processing",true);
	// 3) XXE here
	// Fix:
	// https://cheatsheetseries.owasp.org/cheatsheets/XML_External_Entity_Prevention_Cheat_Sheet.html
	// sax.setFeature("http://apache.org/xml/features/disallow-doctype-decl", true); 
        Document doc = sax.build(zis);
        content = doc.getContent();
        zis.close();
        break;
      }
    }
    StringBuilder sb = new StringBuilder();
    if (content != null) {
      for(Content item : content){
        sb.append(item.getValue());
      }
    }
    return sb.toString();
  }
}



{{< /code >}}
