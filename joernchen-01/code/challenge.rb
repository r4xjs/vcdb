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
