---

title: dowd-mehta-wheeler-02
author: raxjs
tags: [c]

---

Some simple C code that calls: sprintf, recv, strncat.
Can you spot what can go wrong here?

<!--more-->
{{< reference src="https://www.blackhat.com/presentations/bh-europe-06/bh-eu-06-Wheeler-up.pdf" >}}

# Code
{{< code language="c"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
char blah[260], buf[256];
sprintf(blah, "%s", "BLAH");
recv(socket, buf, 256, 0);
strncat(blah, buf, 256);

{{< /code >}}

# Solution
{{< code language="c" highlight="3,5-8,10-13" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
char blah[260], buf[256];
// 1) blah contains "BLAH\0"
sprintf(blah, "%s", "BLAH");
// 2) we can fill `buf` with 256 bytes, 
//    which is exactly the limit of the buffer.
//    Note: that recv will not store a 0-byte at the end of buf,
//          we can use the full 256 byte as data.
recv(socket, buf, 256, 0);
// 3) `blah` can store 260-4 = 256 bytes after "BLAH".
//    strncat can append 256+1 = 257 byte to `blah`, see
//    the manpage below. This means we have a one 0-byte overflow
//    in `blah`.
strncat(blah, buf, 256);


/*
STRCAT(3)                    Linux Programmer's Manual                   STRCAT(3)

NAME
       strcat, strncat - concatenate two strings

SYNOPSIS
       #include <string.h>

       char *strcat(char *dest, const char *src);

       char *strncat(char *dest, const char *src, size_t n);

DESCRIPTION
       The  strcat() function appends the src string to the dest string, overwrit‐
       ing the terminating null byte ('\0') at the end of dest, and  then  adds  a
       terminating  null  byte.   The strings may not overlap, and the dest string
       must have enough space for the result.  If dest is not large  enough,  pro‐
       gram  behavior  is unpredictable; buffer overruns are a favorite avenue for
       attacking secure programs.

       The strncat() function is similar, except that

       *  it will use at most n bytes from src; and

       *  src does not need to be null-terminated if it contains n or more bytes.

       As with strcat(), the resulting string in dest is always null-terminated.

       If src contains n or more bytes, strncat() writes n+1 bytes to dest (n from
       src  plus  the terminating null byte).  Therefore, the size of dest must be
       at least strlen(dest)+n+1.
*/
{{< /code >}}
