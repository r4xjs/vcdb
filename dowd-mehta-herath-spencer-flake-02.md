---

title: dowd-mehta-herath-spencer-flake-02
author: raxjs
tags: [c, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="http://www.blackhat.com/presentations/bh-usa-02/bh-us-02-iss-sourceaudit.ppt" >}}

# Code
{{< code language="c"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
void
replydirname(name, message)
        const char *name, *message;
{
        char npath[MAXPATHLEN];
        int i;

        for (i = 0; *name != '\0' && i < sizeof(npath) - 1; i++, name++) {
                npath[i] = *name;
                if (*name == '"')
                        npath[++i] = '"';
        }
        npath[i] = '\0';
        reply(257, "\"%s\" %s", npath, message);
}

{{< /code >}}

# Solution
{{< code language="c" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}