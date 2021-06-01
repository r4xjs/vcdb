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

