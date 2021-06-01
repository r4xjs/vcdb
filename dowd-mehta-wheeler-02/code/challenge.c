char blah[260], buf[256];
sprintf(blah, "%s", "BLAH");
recv(socket, buf, 256, 0);
strncat(blah, buf, 256);
