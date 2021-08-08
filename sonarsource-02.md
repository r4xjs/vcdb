---

title: sonarsource-02
author: raxjs
tags: [java]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1393100930124554242" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
public class IndexServlet extends HttpServlet {
    private String referer;
    private ExportIcalManager exportManager;
    private void exportIcal(HttpServletResponse res, String sessionId)
            throws ServletException, IOException {
	res.addHeader("Access-Control-Allow-Origin", referer);
        res.setContentType("text/plain");
        ExportIcalManager exportManager = new ExportIcalManager(sessionId);
        String filePath = exportManager.exportIcalAgendaForSynchro();
        OutputStream os = res.getOutputStream();
        FileInputStream fs = new FileInputStream(filePath);
        int i;
        while (((i = fs.read()) != -1)) { os.write(i); }
        os.close();
    }

    protected void doPost(HttpServletRequest req, HttpServletResponse res)
            throws ServletException, IOException {
        HttpSession session = req.getSession();
	referer = req.getParameter("referer");
	exportIcal(res, req.getRequestedSessionId());
    }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
public class IndexServlet extends HttpServlet {
    private String referer;
    private ExportIcalManager exportManager;
    private void exportIcal(HttpServletResponse res, String sessionId)
            throws ServletException, IOException {
	res.addHeader("Access-Control-Allow-Origin", referer);       // 2) referer is used to allow arbitrary origins
        res.setContentType("text/plain");
        ExportIcalManager exportManager = new ExportIcalManager(sessionId);
        String filePath = exportManager.exportIcalAgendaForSynchro();
        OutputStream os = res.getOutputStream();
        FileInputStream fs = new FileInputStream(filePath);
        int i;
        while (((i = fs.read()) != -1)) { os.write(i); }
        os.close();
    }

    protected void doPost(HttpServletRequest req, HttpServletResponse res)
            throws ServletException, IOException {
        HttpSession session = req.getSession();
	referer = req.getParameter("referer");                       // 1) referer is user input
	exportIcal(res, req.getRequestedSessionId());
    }
}

{{< /code >}}