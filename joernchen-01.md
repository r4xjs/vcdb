---

title: joernchen-01
author: raxjs
tags: [ruby]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://code-audit-training.gitlab.io/" >}}

# Code
{{< code language="ruby"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
def grep(query, options={})
  ref = options[:ref] ? options[:ref] : "HEAD"
  args = [{}, '-I', '-i', '-c', query, ref, '--']
  args << options[:path] if options[:path]
  result = @git.grep(*args).split("\n")
  result.map do |line|
    branch_and_name, _, count = line.rpartition(":")
    branch, _, name = branch_and_name.partition(':')
    {:name => name, :count => count}
  end
end

{{< /code >}}

# Solution
{{< code language="ruby" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
def grep(query, options={})
  # 1) assume that query and options is user input
  ref = options[:ref] ? options[:ref] : "HEAD"
  # 2) then args contains user input (query & options[:ref] & options[:path])
  args = [{}, '-I', '-i', '-c', query, ref, '--']
  args << options[:path] if options[:path]
  # 3) argument injection via query and options
  #    example:
  #    options[:ref] = <actual query>
  #    query = -O</target/program>
  #    --> git grep -I -i -c -O</target/program> <actual query> --
  #                          ------------------- ---------------
  #                            query              options[:ref]
  result = @git.grep(*args).split("\n")
  result.map do |line|
    branch_and_name, _, count = line.rpartition(":")
    branch, _, name = branch_and_name.partition(':')
    {:name => name, :count => count}
  end
end

# man git grep
# ...
#   -O[<pager>], --open-files-in-pager[=<pager>]
#           Open the matching files in the pager (not the output of grep). If the pager happens to be
#           "less" or "vi", and the user specified only one pattern, the first file is positioned at
#           the first match automatically. The pager argument is optional; if specified, it must be
#           stuck to the option without a space. If pager is unspecified, the default pager will be
#           used (see core.pager in git-config(1)).
# ...

{{< /code >}}