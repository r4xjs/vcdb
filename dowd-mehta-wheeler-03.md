---

title: dowd-mehta-wheeler-03
author: raxjs
tags: [c]

---

A small C function to handle file requests.

<!--more-->
{{< reference src="https://www.blackhat.com/presentations/bh-europe-06/bh-eu-06-Wheeler-up.pdf" >}}

# Code
{{< code language="c"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
HANDLE GetRequestedFile(LPCSTR requestedFile){
  if(strstr(requestedFile, "..")){
    return INVALID_HANDLE_VALUE;
  }
  if(strcmp(requestedFile, ".config") == 0){
    return INVALID_HANDLE_VALUE;
  }
  return CreateFile(requestedFile,
		    GENERIC_READ,
		    FILE_SHARED_READ,
		    NULL,
		    OPEN_EXISTING,
		    0,
		    NULL);
}

{{< /code >}}

# Solution
{{< code language="c" highlight="3,4,8-11" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
HANDLE GetRequestedFile(LPCSTR requestedFile){
  // 1) requestedFile can still contain the absolute path
  //    to "break out" of the current directory
  if(strstr(requestedFile, "..")){
    return INVALID_HANDLE_VALUE;
  }
  // 2) we can still open ".config" by ".\.config"
  //    or by using the absolute path to ".config".
  //    Windows also uses case insensitive file/directory names,
  //    therefore ".COnFiG" could also be used to bypass this check.
  if(strcmp(requestedFile, ".config") == 0){
    return INVALID_HANDLE_VALUE;
  }
  return CreateFile(requestedFile,
		    GENERIC_READ,
		    FILE_SHARED_READ,
		    NULL,
		    OPEN_EXISTING,
		    0,
		    NULL);
}
{{< /code >}}
