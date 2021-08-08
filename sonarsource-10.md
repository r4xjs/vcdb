---

title: sonarsource-10
author: raxjs
tags: [python]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1333803048599121921" >}}

# Code
{{< code language="python"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
from django.contrib import auth, messages
from django.http import HttpResponseRedirect
from django.shortcuts import redirect, render
from django.utils.translation import ugettext as _
from django.views.generic import CreateView, FormView, RedirectView


class RegisterView(CreateView):
    model = User
    form_class = RegistrationForm
    template_name = "register.html"
    success_url = "/"
    def post(self, request, *args, **kwargs):
        form = self.form_class(data=request.POST)
        if form.is_valid():
            user = form.save(commit=False)
            password = form.cleaned_data.get("password1")
            user.set_password(password)
            user.save()
        return redirect("login")
        
        
    def dispatch(self, request, *args, **kwargs):
        if self.request.user.is_authenticated:
            return HttpResponseRedirect(self.get_success_url())
        return super().dispatch(self.request, *args, **kwargs)
    def get_success_url(self):
        if "next" in self.request.GET and self.request.GET["next"] != "":
            return self.request.GET["next"]
        else:
            return self.success_url
            
            
    def get_form_class(self):
        return self.form_class

{{< /code >}}

# Solution
{{< code language="python" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
from django.contrib import auth, messages
from django.http import HttpResponseRedirect
from django.shortcuts import redirect, render
from django.utils.translation import ugettext as _
from django.views.generic import CreateView, FormView, RedirectView


class RegisterView(CreateView):
    model = User
    form_class = RegistrationForm
    template_name = "register.html"
    success_url = "/"
    def post(self, request, *args, **kwargs):
        form = self.form_class(data=request.POST)
        if form.is_valid():
            user = form.save(commit=False)
            password = form.cleaned_data.get("password1")
            user.set_password(password)
            user.save()
        return redirect("login")


    def dispatch(self, request, *args, **kwargs):
        if self.request.user.is_authenticated:
            # 2) open redirect tough GET["next"] parameter
            return HttpResponseRedirect(self.get_success_url())
        return super().dispatch(self.request, *args, **kwargs)
    def get_success_url(self):
        # 1) GET["next"] is user controlled
        if "next" in self.request.GET and self.request.GET["next"] != "":
            return self.request.GET["next"]
        else:
            return self.success_url


    def get_form_class(self):
        return self.form_class



{{< /code >}}