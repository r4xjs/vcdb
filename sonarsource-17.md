---

title: sonarsource-17
author: raxjs
tags: [python]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1338151696439107584" >}}

# Code
{{< code language="python"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
import os
import requests
import errno
from django.conf import settings
class Sitemap():
    domain = "sonarsource.com"
    def __init__(self, filename="sitemap.xml"):
	self.url = "http://" + self.domain
        if not self.url.endswith('/'):
            self.url += '/'
        self.url += filename
        self.destination = os.path.join(settings.STATIC_ROOT, filename)
	
    def fetch(self, dataset=None):
        headers = {'User-Agent': 'Mozilla/5.0 Chrome/50.2661 Safari/537.36'}
        try:
            response = requests.get(self.url, timeout=30, headers=headers)
        except requests.exceptions.SSLError:
            response = requests.get(self.url, verify=False, headers=headers)
        finally:
            pass
        with open(self.destination, 'wb') as f:
            f.write(response.content)

{{< /code >}}

# Solution
{{< code language="python" highlight="9,13,14,16,27,28" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
import os
import requests
import errno
from django.conf import settings
class Sitemap():
    domain = "sonarsource.com"
    def __init__(self, filename="sitemap.xml"):
        # 1) assume filename is user input
	self.url = "http://" + self.domain
        if not self.url.endswith('/'):
            self.url += '/'
        # 4) if we try things like http://user:password@other-host.to we will fail because
        #    of the appended '/'.
        self.url += filename
        # 2) then dicretory traversal is possible here
        self.destination = os.path.join(settings.STATIC_ROOT, filename)
	
    def fetch(self, dataset=None):
        headers = {'User-Agent': 'Mozilla/5.0 Chrome/50.2661 Safari/537.36'}
        try:
            response = requests.get(self.url, timeout=30, headers=headers)
        except requests.exceptions.SSLError:
            response = requests.get(self.url, verify=False, headers=headers)
        finally:
            pass
        # 3) destination can include a directory traversal payload
        #    like: $STATIC_ROOT/../../../what/ever.txt
        with open(self.destination, 'wb') as f:
            f.write(response.content)

{{< /code >}}
