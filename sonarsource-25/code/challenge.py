import os
import tempfile
'''
Open the pdf reader on windows for the report file
'''
def open_report(report_class, _system="Windows", *args, **kwargs):
    rv = PrintReportEvent.emit(report_class, *args, **kwargs)
    if rv:
        return rv
    filters = kwargs.pop('filters', None)
    if filters:
        kwargs = describe_search_filters_for_reports(filters, **kwargs)
    tmp = tempfile.mktemp(suffix='.pdf', prefix='stoqlib-reporting')
    report = report_class(tmp, *args, **kwargs)
    report.filename = tmp
    if _system == "Windows":
        report.save()
        log.info("Executing PDF reader with %r" % (report.filename, ))
        os.startfile(report.filename)
        return
    if isinstance(report, HTMLReport):
        op = PrintOperationWEasyPrint(report)
        op.set_threaded()
    else:
        op = PrintOperationPoppler(report)
    rv = op.run()
    return rv
