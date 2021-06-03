from __future__ import unicode_literals
import os
import shutil
import tempfile
import traceback
import zipfile
from django import forms
from django.http.response import HttpResponseRedirect
from django.utils.translation import ugettext_lazy as _
from django.views.generic import FormView
from django.views.decorators.csrf import csrf_exempt
class AddonUploadView(FormView):
    form_class = forms.Form
    template_name = "package/addon/upload.jinja"
    def get_addon_path(self):
        filename = os.path.basename(self.request.GET.get("my_file"))
        tmp_token = self.request.GET.get('my_token')
        path = os.path.join(tempfile.gettempdir(), tmp_token, filename)
        if not os.path.isfile(path):
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
        return self.render_to_response(response)
