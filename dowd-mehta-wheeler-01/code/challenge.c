int allocator(struct memory *h, int length) {
  while(h->next != 0) {
    h = h->next;
  }
  h->next = calloc(length + 4, 1);
  return h->next+4;
}
