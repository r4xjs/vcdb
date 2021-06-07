...
    while (cp < reqend && isspace(*cp))
	cp++;
    if (cp == reqend || *cp == ',') {
	buf[0] = '\0';
	*data = buf;
	if (cp < reqend)
	    cp++;
	reqpt = cp;
	return v;}
    if (*cp == '=') {
	cp++;
	tp = buf;
	while (cp < reqend && isspace(*cp))
	    cp++;
	while (cp < reqend && *cp != ',')
	    *tp++ = *cp++;
	if (cp < reqend)
	    cp++;
	*tp = '\0';
	while (isspace(*(tp-1)))
	    *(--tp) = '\0';
	reqpt = cp;
	*data = buf;
	return v;
    }
...
