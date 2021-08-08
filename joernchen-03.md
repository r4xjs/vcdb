---

title: joernchen-03
author: raxjs
tags: [ruby]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://code-audit-training.gitlab.io/" >}}

# Code
{{< code language="ruby"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
# coding: utf-8
require 'sinatra'

post '/ping' do
    if params['host'] =~ /^(\d{1,3}\.){3}(\d){1,3}$/
        return `ping -c 4 #{params['host']}`
    end
    return "Invalid IP address"
end

{{< /code >}}

# Solution
{{< code language="ruby" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
# coding: utf-8
require 'sinatra'

post '/ping' do
    # 1) params['host'] is user input
    #    The regex check can be passed via a newline
    #    e.g.: 127.0.0.1\nid -a
    if params['host'] =~ /^(\d{1,3}\.){3}(\d){1,3}$/
        return `ping -c 4 #{params['host']}`
    end
    return "Invalid IP address"
end

# example:
#`ping -c 4 127.0.0.1\nid -a`
#=> "PING 127.0.0.1 (127.0.0.1) 56(84) bytes of data.\n64 bytes from 127.0.0.1: icmp_seq=1 ttl=64 time=0.041 ms\n64 bytes from 127.0.0.1: icmp_seq=2 ttl=64 time=0.093 ms\n64 bytes from 127.0.0.1: icmp_seq=3 ttl=64 time=0.091 ms\n64 bytes from 127.0.0.1: icmp_seq=4 ttl=64 time=0.093 ms\n\n--- 127.0.0.1 ping statistics ---\n4 packets transmitted, 4 received, 0% packet loss, time 3051ms\nrtt min/avg/max/mdev = 0.041/0.079/0.093/0.022 ms\nuid=1000(user) gid=1000(user) groups=1000(user),973(libvirt),991(lp),992(kvm)...\n"



{{< /code >}}