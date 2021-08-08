---

title: dowd-mehta-wheeler-02
author: raxjs
tags: [c, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://www.blackhat.com/presentations/bh-europe-06/bh-eu-06-Wheeler-up.pdf" >}}

# Code
{{< code language="c"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
char blah[260], buf[256];
sprintf(blah, "%s", "BLAH");
recv(socket, buf, 256, 0);
strncat(blah, buf, 256);

{{< /code >}}

# Solution
{{< code language="c" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}