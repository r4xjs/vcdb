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
