---

title: dowd-mehta-wheeler-04
author: raxjs
tags: [c, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://www.blackhat.com/presentations/bh-europe-06/bh-eu-06-Wheeler-up.pdf" >}}

# Code
{{< code language="c"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
char *ProfileDirectory = "c:\profiles";

BOOL LoadProfile(LPCSTR UserName) {
  HANDLE hFile;
  char buf[MAX_PATH];

  if(strlen(UserName) > MAX_PATH - strlen(ProfileDirectory) - 12) {
    return FALSE;
  }

  snprintf(buf, sizeof(buf), "%s\prof_%s.txt", ProfileDirectory, UserName);
  hFile = CreateFile(buf, GENERIC_READ, 0, NULL, OPEN_EXISTING, 0, NULL);

  // ... load profile data ...
}


{{< /code >}}

# Solution
{{< code language="c" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}