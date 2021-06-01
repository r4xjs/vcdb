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
