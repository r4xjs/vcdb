---

title: dowd-mehta-wheeler-06
author: raxjs
tags: [c]

---

Multithreaded Request processing.

<!--more-->
{{< reference src="https://www.blackhat.com/presentations/bh-europe-06/bh-eu-06-Wheeler-up.pdf" >}}

# Code
{{< code language="c"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
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

{{< /code >}}

# Solution
{{< code language="c" highlight="2,7,9,24-27" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
// 1) global request variable
request * head;
void server_thread() {
  while(1) {
    if(request_availabel()) {
      // 2) insert new request in linked list
      get_request(head);
      // 3) process request in new thread
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
  // find first unprocessed request
  for(req=head; req && !req->processed; req = req->next);
  // 4) two or more threads can end up with the same request
  //    because the time of check (for loop above) and the  
  //    time of use (process_request(req) below is not atomar
  //    --> raise condition
  if(req) {
    req->processed = 1;
    process_request(req);
  }
  ExitThread(0);
}


{{< /code >}}
