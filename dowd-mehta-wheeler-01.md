---

title: dowd-mehta-wheeler-01
author: raxjs
tags: [c, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://www.blackhat.com/presentations/bh-europe-06/bh-eu-06-Wheeler-up.pdf" >}}

# Code
{{< code language="c"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
int allocator(struct memory *h, int length) {
  while(h->next != 0) {
    h = h->next;
  }
  h->next = calloc(length + 4, 1);
  return h->next+4;
}

{{< /code >}}

# Solution
{{< code language="c" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}