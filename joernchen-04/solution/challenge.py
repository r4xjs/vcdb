import re
import os
import fileinput

print("Gimme a host: " , end='',flush = True)
for line in fileinput.input():
    # 1) line is user input, the regex
    #    tries to sanitize but misses at least one option
    if re.search('[;\'&{}<>|]', line):
        print("no thanks!")
        exit()
    print("[*] pinging in progress")
    # 2) command injection here
    #    for example:  line = $(id -a) will pass the regex above
    #    and executes a command
    os.system("ping -c 2 %s" % line )
    print("[*] pinging done")
    print("Gimme a host: " , end='',flush = True)


# $ printf "\$(id -a)\n" | python aaa.py
# Gimme a host: [*] pinging in progress
# ping: groups=1000(user),108(vboxusers),209(cups),973(libvirt),991(lp),992(kvm),1001(sudo): Name or service not known
# [*] pinging done
# Gimme a host: %


