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

