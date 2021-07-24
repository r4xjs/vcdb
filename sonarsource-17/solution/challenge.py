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
