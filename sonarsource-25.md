---

title: sonarsource-25
author: raxjs
tags: [python]

---

Opening a report file in python.

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1341035697734561792" >}}

# Code
{{< code language="python"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
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

{{< /code >}}

# Solution
{{< code language="python" highlight="14-16,22,23,25" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
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
    # 1) the tempfile.mktemp function is vulnerable to race conditions.
    #    From the docs:
    #    Return an absolute pathname of a file that did not exist at the time the call is made. 
    tmp = tempfile.mktemp(suffix='.pdf', prefix='stoqlib-reporting')
    report = report_class(tmp, *args, **kwargs)
    report.filename = tmp
    if _system == "Windows":
        report.save()
        # 2) for example: an attacker could read the file name here and
        #    overwrite it with custom content
        log.info("Executing PDF reader with %r" % (report.filename, ))
        # 3) start attacker controlled file
        os.startfile(report.filename)
        return
    if isinstance(report, HTMLReport):
        op = PrintOperationWEasyPrint(report)
        op.set_threaded()
    else:
        op = PrintOperationPoppler(report)
    rv = op.run()
    return rv
{{< /code >}}
