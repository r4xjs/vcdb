void
replydirname(name, message)
        const char *name, *message;
{
        char npath[MAXPATHLEN];
        int i;

        for (i = 0; *name != '\0' && i < sizeof(npath) - 1; i++, name++) {
                npath[i] = *name;
                if (*name == '"')
                        npath[++i] = '"';
        }
        npath[i] = '\0';
        reply(257, "\"%s\" %s", npath, message);
}
