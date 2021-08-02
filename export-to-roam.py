from glob import glob
from os import path, listdir
import uuid

ext_map = {
    'py':    'python',
    'php':   'php',
    'cs':    'csharp',
    'java':  'java',
    'c':     'c',
    'go':    'go',
    'jsp':   'java',
    'pl':    'perl',
    'rb':    'ruby'
    }

def read_vcdb_dir(d):
    if not path.exists(path.join(d, 'Readme.md')) \
       or not path.exists(path.join(d, 'code')):
        return None
    # we have at least Readme.mde and code from here on
    title = d

    # get the reference from the Readme.md file
    ref = None
    with open(path.join(d, 'Readme.md'), 'r') as fd:
        for line in fd:
            if line.startswith('http'):
                ref = line.rstrip('\n')
                break
    if ref is None:
        print("no ref found")
        return None

    # next get the code challenge
    code = None
    challenge_file = glob(f"{path.join(d, 'code')}/*.*")[0]
    ext = challenge_file.split('.')[-1]
    with open(challenge_file, 'r') as fd:
        code = fd.read()
    if code is None:
        print("no code found")
        return None

    solution = ''
    solution_files = glob(f"{path.join(d, 'solution')}/*.*")
    if len(solution_files) > 0:
        with open(solution_files[0], 'r') as fd:
            solution = fd.read()

    return {'ref': ref,
            'title': title,
            'code': code,
            'solution': solution,
            'ext': ext}

def dir_to_org(d):
    data = read_vcdb_dir(d)
    if data is None:
        return None
    tags = ':vcdb:' + ext_map[data['ext']] + ':'
    if len(data['solution']) == 0:
        tags += 'nosolution:'
    ret = [':PROPERTIES:',
           f":ID:        {str(uuid.uuid4())}",
           f":ROAM_REFS: {data['ref']}",
           ':END:',
           f"#+title: {data['title']}",
           f"#+filetags: {tags}",
           '',
           '* Description',
           '',
           '* Code',
           f"#+begin_src {ext_map[data['ext']]}",
           data['code'],
           '#+end_src',
           '',
           '* Solution',
           f"#+begin_src {ext_map[data['ext']]}",
           data['solution'],
           '#+end_src',
           ]
    return "\n".join(ret)


for d in listdir():
    org_file_content = dir_to_org(d)
    if org_file_content is None:
        continue
    org_file =path.join('roam', f"20210802230817-{d}.org")
    with open(org_file, 'w') as fd:
        fd.write(org_file_content)
