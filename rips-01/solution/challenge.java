import org.jdom2.Content;
import org.jdom2.Document;
import org.jdom2.JDOMException;
import org.jdom2.input.SAXBuilder;

public class ImportDocument {
  // This function extracts the text of an OpenOffice document
  public static String extractString() throws IOException, JDOMException {
    File initialFile = new File("uploaded_office_doc.odt");                     // 1) read user input
    InputStream in = new FileInputStream(initialFile);                        
    final ZipInputStream zis = new ZipInputStream(in);                          // 2) unzip user input. also zip bomb?
    ZipEntry entry;
    List<Content> content = null;
    while ((entry = zis.getNextEntry()) != null) {
      if (entry.getName().equals("content.xml")) {
        final SAXBuilder sax = new org.jdom2.input.SAXBuilder();
        sax.setFeature("http://javax.xml.XMLConstants/feature/secure-processing",true);
	// Fix:
	// https://cheatsheetseries.owasp.org/cheatsheets/XML_External_Entity_Prevention_Cheat_Sheet.html
	// sax.setFeature("http://apache.org/xml/features/disallow-doctype-decl", true); 
        Document doc = sax.build(zis);                                          // 3) XXE
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


