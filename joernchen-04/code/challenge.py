import re
import os
import fileinput

print("Gimme a host: " , end='',flush = True)
for line in fileinput.input():
    if re.search('[;\'&{}<>|]', line):
        print("no thanks!")
        exit()
    print("[*] pinging in progress")
    os.system("ping -c 2 %s" % line )
    print("[*] pinging done")
    print("Gimme a host: " , end='',flush = True)
