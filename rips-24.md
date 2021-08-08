---

title: rips-24
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://blog.tracesec.xyz/2020/01/05/JavaSecCalendar2019-Writeup/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
package com.rips.demo.web;
 
import java.io.*;
import java.lang.reflect.*;
 
class Invoker implements Serializable {
 
  private String c;
  private String m;
  private String[] a;
 
  public Invoker(String c, String m, String[] a) {
    this.c = c;
    this.m = m;
    this.a = a;
  }
 
  private void readObject(ObjectInputStream ois) throws IOException, ClassNotFoundException, NoSuchMethodException, IllegalAccessException, InstantiationException, InvocationTargetException {
    ois.defaultReadObject();
    Class clazz = Class.forName(this.c);
    Object obj = clazz.getConstructor(String[].class).newInstance(new Object[]{this.a});
    Method meth = clazz.getMethod(this.m);
    meth.invoke(obj, null);
  }
}
 
class User implements Serializable {
  private String name;
  private String email;
  transient private String password;
 
  public User(String name, String email, String password) {
    this.name = name;
    this.email = email;
    this.password = password;
  }
   
  private void readObject(ObjectInputStream stream)
      throws IOException, ClassNotFoundException {
    stream.defaultReadObject();
    password = (String) stream.readObject();
  }
 
  @Override
  public String toString() {
    return "User{" + "name='" + name + ", email='" + email + "'}";
  }
}

// --------------------------------------------------------------------------------------

@RequestMapping(value = "/unserialize", consumes = "text/xml")
  public void unserialize(@RequestBody String xml, HttpServletResponse res) throws IOException, ParserConfigurationException, SAXException, XPathExpressionException, TransformerException {
    res.setContentType("text/plain");
    // Parse xml string
    DocumentBuilderFactory builderFactory = DocumentBuilderFactory.newInstance();
    builderFactory.setFeature("http://apache.org/xml/features/disallow-doctype-decl",true);
    DocumentBuilder builder = builderFactory.newDocumentBuilder();
    Document xmlDocument = builder.parse(new InputSource(new StringReader(xml)));
    XPath xPath = XPathFactory.newInstance().newXPath();
    String expression = "//com.rips.demo.web.User[@serialization='custom'][1]";
    //only allow User objects to be unserialized!!!
    NodeList nodeList = (NodeList) xPath.compile(expression).evaluate(xmlDocument, XPathConstants.NODESET);
    // Transform node back to xml string
    Transformer transformer = TransformerFactory.newInstance().newTransformer();
    transformer.setOutputProperty(OutputKeys.OMIT_XML_DECLARATION, "yes");
    StringWriter writer = new StringWriter();
    transformer.transform(new DOMSource(nodeList.item(0)), new StreamResult(writer));
    String xmloutput = writer.getBuffer().toString();
    // Unserialze User
    User user = (User) new XStream().fromXML(xmloutput);
    res.getWriter().print("Successfully unserialized "+user.toString());
  }

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}