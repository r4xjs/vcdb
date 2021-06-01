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
