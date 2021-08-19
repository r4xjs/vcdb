---

title: dowd-mehta-wheeler-05
author: raxjs
tags: [c]

---

Appending space to a global buffer in C.

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
{{< code language="c" highlight="4,5,12,13" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
void buffer_append_space() { // buffer is global
  buffer->alloc += len + 32768;
  // 1) Raise condition here when we have more then one thread.
  //    Raise between the check here and the use later in xrealloc.
  if(buffer->alloc > 0xa00000) {
    fatal("buffer_append_space: alloc %u not supported", buffer->alloc);
  }
  buffer->buf = xrealloc(buffer->buf, buffer->alloc);
}
void buffer_free(Buffer *buffer) {
  // 2) buffer->buf and buffer->alloc is not reset to e. g. 0
  //    which could lead to an use after free later on
  memset(buffer->buf, 0, buffer->alloc);
  xfree(buffer->buf);
}

{{< /code >}}
