request * head;
void server_thread() {
  while(1) {
    if(request_availabel()) {
      get_request(head);
      CreateThread(NULL,
		   0,
		   processing_thread_entrypoint,
		   NULL,
		   0);
    } else {
      wait_for_request();
    }
  }
}
void processing_thread_entrypoint() {
  request *req;
  // finf first unprocessed request
  for(req=head; req && !req->processed; req = req->next);
  if(req) {
    req->processed = 1;
    process_request(req);
  }
  ExitThread(0);
}
