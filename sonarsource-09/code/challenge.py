def get_addon_path(self):
    filename = os. path.basename (self.request.GET.get("my_file"))
    tmp_token = self.request.GET.get('my_token')
    path = os. path.join(tempfile.gettempdir(), tmp_token, filename)
    if not os. path.isfile(path):
        raise ValueError("Error! File not found.")
    if hasattr(os, "geteuid") and os.stat(path).st_uid != os.geteuid():
        raise ValueError("Error! File not owned by current user.")
    return path

@csrf_exempt
def form_valid(self, form):
    try:
        installer.install_package(self.get_addon_path())
        response["success"] = True
    except Exception:
        os.unlink(self.get_addon_path())
        response["success"] = False
