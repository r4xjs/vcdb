---

title: joernchen-06
author: raxjs
tags: [java]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://code-audit-training.gitlab.io/" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import java.io.*;

public class StringStuff {
  private FileInputStream in;

  public StringStuff(String fn) throws FileNotFoundException {
    in = new FileInputStream(fn);
  }

  private int read() throws IOException {
    if (in != null) {
      return in.read();
    } else {
      return -1;
    }
  }

  private String readLine() throws IOException {
    return readLine(new StringBuffer());
  }

  private String readLine(StringBuffer sb) throws IOException {
    boolean finished;
    do {
      int value = read();
      finished = (value == -1 || value == 10);
      if (!finished) {
        sb.append((char) value);
      }
    } while (!finished);
    return sb.toString();
  }

  private boolean checkStr() throws IOException {
    String s;
    while (true) {
      s = readLine();
      if (s == null || s.length() < 1) {
        continue;
      }
      if (s.contains("a")) {
        return true;
      } else {
        return false;
      }
    }
  }

  public static void main(String[] args) throws Exception {
    StringStuff stuff = new StringStuff(args[0]);
    System.out.println(stuff.checkStr());
  }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="8,9,15-18,33,39,47-51,64" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import java.io.*;

public class StringStuff {
  private FileInputStream in;

  public StringStuff(String fn) throws FileNotFoundException {
    // 2) `fn` is user input and is used as file name 
    //    `in` could be under user controll as well 
    in = new FileInputStream(fn);
  }

  private int read() throws IOException {
    if (in != null) {
        // 4) an empty file will always return -1
        // https://docs.oracle.com/javase/7/docs/api/java/io/FileInputStream.html#read()
        // Returns:
        // the next byte of data, or -1 if the end of the file is reached
      return in.read();
    } else {
      return -1;
    }
  }

  private String readLine() throws IOException {
    return readLine(new StringBuffer());
  }

  private String readLine(StringBuffer sb) throws IOException {
    boolean finished;
    do {
      int value = read();
      // 5) on an empty file `value` will be -1  ==> finished will be true
      finished = (value == -1 || value == 10);
      if (!finished) {
        sb.append((char) value);
      }
    } while (!finished);
    // 6) empty string is returned here in the empty file case
    return sb.toString();
  }

  private boolean checkStr() throws IOException {
    String s;
    while (true) {
      s = readLine();
      // 3) s == null check is not needed
      //    the length of s is 0 in the empty file case
      //    and continue will be executed
      //    this will lead to an endless loop between readLine()
      //    and the `continue` statement
      if (s == null || s.length() < 1) {
        continue;
      }
      if (s.contains("a")) {
        return true;
      } else {
        return false;
      }
    }
  }

  public static void main(String[] args) throws Exception {
    // 1) stuff is user input
    StringStuff stuff = new StringStuff(args[0]);
    System.out.println(stuff.checkStr());
  }
}



{{< /code >}}
