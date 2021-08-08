---

title: dowd-mehta-wheeler-05
author: raxjs
tags: [c, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://www.blackhat.com/presentations/bh-europe-06/bh-eu-06-Wheeler-up.pdf" >}}

# Code
{{< code language="c"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
void buffer_append_space() { // buffer is global
  buffer->alloc += len + 32768;
  if(buffer->alloc > 0xa00000) {
    fatal("buffer_append_space: alloc %u not supported", buffer->alloc);
  }
  buffer->buf = xrealloc(buffer->buf, buffer->alloc);
}
void buffer_free(Buffer *buffer) {
  memset(buffer->buf, 0, buffer->alloc);
  xfree(buffer->buf);
}


{{< /code >}}

# Solution
{{< code language="c" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}