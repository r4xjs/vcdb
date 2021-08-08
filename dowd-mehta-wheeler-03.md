---

title: dowd-mehta-wheeler-03
author: raxjs
tags: [c, nosolution]

---

$DESCRIPTION

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
{{< code language="c" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}