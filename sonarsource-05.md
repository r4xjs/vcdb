---

title: sonarsource-05
author: raxjs
tags: [python, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1333803048599121921" >}}

# Code
{{< code language="python"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
class F00(object):

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

{{< /code >}}

# Solution
{{< code language="python" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}