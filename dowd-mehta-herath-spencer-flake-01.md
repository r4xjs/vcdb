---

title: dowd-mehta-herath-spencer-flake-01
author: raxjs
tags: [c, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="http://www.blackhat.com/presentations/bh-usa-02/bh-us-02-iss-sourceaudit.ppt" >}}

# Code
{{< code language="c"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
...
    while (cp < reqend && isspace(*cp))
	cp++;
    if (cp == reqend || *cp == ',') {
	buf[0] = '\0';
	*data = buf;
	if (cp < reqend)
	    cp++;
	reqpt = cp;
	return v;}
    if (*cp == '=') {
	cp++;
	tp = buf;
	while (cp < reqend && isspace(*cp))
	    cp++;
	while (cp < reqend && *cp != ',')
	    *tp++ = *cp++;
	if (cp < reqend)
	    cp++;
	*tp = '\0';
	while (isspace(*(tp-1)))
	    *(--tp) = '\0';
	reqpt = cp;
	*data = buf;
	return v;
    }
...

{{< /code >}}

# Solution
{{< code language="c" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}