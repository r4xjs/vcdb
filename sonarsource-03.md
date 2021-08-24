---

title: sonarsource-03
author: raxjs
tags: [php]

---

Awesom PHP file upload implementation.

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1395710975194460163" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
{
    private $detect_mime = TRUE;
    private $type_regex = '/^([a-z\-]+\/[a-z0-9\-\.\+]+)(;\s.+)?$/';

    public function do_upload($field = 'userfile') {
        $file = $_FILES[$field];
        $this->file_size = $file['size'];
        $this->_file_mime_type($file);
    }

    private function _file_mime_type($file) {
        if (function_exists('finfo_file')) {
            $finfo = @finfo_open(FILEINFO_MIME);
            $mime = @finfo_file($finfo, $file['tmp_name']);
            if (preg_match($this->type_regex, $mime, $match)) {
                return $this->file_type = $match[1];
            }
        }

        $cmd = 'file --brief --mime '.$file['name'].' 2>&1';
        exec($cmd, $mime, $status);
        if ($status === 0 && preg_match($this->type_regex, $mime, $match)) {
            return $this->file_type = $match[1];
        }
    }
}
$upload = new Upload();
$upload->do_upload();

{{< /code >}}

# Solution
{{< code language="php" highlight="7,21-24" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
{
    private $detect_mime = TRUE;
    private $type_regex = '/^([a-z\-]+\/[a-z0-9\-\.\+]+)(;\s.+)?$/';

    public function do_upload($field = 'userfile') {
        // 1) assume $file is user input
        $file = $_FILES[$field];
        $this->file_size = $file['size'];
        $this->_file_mime_type($file);
    }

    private function _file_mime_type($file) {
        if (function_exists('finfo_file')) {
            $finfo = @finfo_open(FILEINFO_MIME);
            $mime = @finfo_file($finfo, $file['tmp_name']);
            if (preg_match($this->type_regex, $mime, $match)) {
                return $this->file_type = $match[1];
            }
        }
        // 2) if `finfo_file` function does not exist or regex does not match
        //    we go here.
        //    $file['name'] is user input and used in $cmd which is passed to `exec`
        //    --> command injection

        $cmd = 'file --brief --mime '.$file['name'].' 2>&1';
        exec($cmd, $mime, $status);
        if ($status === 0 && preg_match($this->type_regex, $mime, $match)) {
            return $this->file_type = $match[1];
        }
    }
}
$upload = new Upload();
$upload->do_upload();



{{< /code >}}
