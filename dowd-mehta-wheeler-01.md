---

title: dowd-mehta-wheeler-01
author: raxjs
tags: [c]

---

An C function which appends a new element in a linked list.

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
{{< code language="c" highlight="2,7-9" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
// 1) length is a signed int while it would be better if it were unsigned
int allocator(struct memory *h, int length) {
  while(h->next != 0) {
    h = h->next;
  }
  // 2) the first argument of calloc (nmemb = length + 4) can interger overflow
  //    which would lead allocator returning memory that is not allocated or used
  //    by other things
  h->next = calloc(length + 4, 1);
  return h->next+4;
}

{{< /code >}}
