---

title: dowd-mehta-herath-spencer-flake-02
author: raxjs
tags: [c,real]

---

This is was a vulnerability in the OpenBSD ftpd deamon. 

[CVE-2001-0053](https://nvd.nist.gov/vuln/detail/CVE-2001-0053)

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
{{< code language="c" highlight="9-12,15,16,20-22" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
void
replydirname(name, message)
        const char *name, *message;
{
        char npath[MAXPATHLEN];
        int i;

        // 1) `i` is a signed integer which is compared with a unsigned integer (sizeof()) 
		//     in the for loop, but this was not the actual vuln here.
		//     lets say `MAXPATHLEN = 4` then `sizeof(npath)-1 = 3` then the condition in
		//     the for loop will be true while `i` is between 0..2.
        for (i = 0; *name != '\0' && i < sizeof(npath) - 1; i++, name++) {
                npath[i] = *name;
				// 2) if `i=2` and `name[i] = '"'` then `i` will be increased to
				//    3 in the if block.
                if (*name == '"')
                        npath[++i] = '"';
        }
		// 3) in the above case `i` will be 4 when the loop is left
		//    and we have an off-by-one error, overwriting one zero byte
		//    beyond npath.
        npath[i] = '\0';
        reply(257, "\"%s\" %s", npath, message);
}


{{< /code >}}
