---

title: rips-50
author: raxjs
tags: [php]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1129417038974164992" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
set_error_handler(function ($no, $str, $file, $line) {
    throw new ErrorException($str, 0, $no, $file, $line);
}, E_ALL);

class ImageLoader {
    public function getResult($uri) {
        if(!filter_var($uri, FILTER_VALIDATE_URL)) {
            return '<p>Please enter valid uri</p>';
        }

        try {
            $image = file_get_contents($uri);
            $path = "./images/" . uniqid() . '.jpg';
            file_put_contents($path, $image);
            if(mime_content_type($path) !== 'image/jpeg') {
                unlink($path);
                return '<p>Only .jpg files allowed</p>';
            }
        } catch (Exception $e) {
            return '<p>There was an error: ' .
                    $e->getMessage() . '</p>';
        }

        return '<img src="' . $path . '" width="100"/>';
    }
}

echo (new ImageLoader())->getResult($_GET['img']);

{{< /code >}}

# Solution
{{< code language="php" highlight="7,14,16,19,20,34" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
set_error_handler(function ($no, $str, $file, $line) {
    throw new ErrorException($str, 0, $no, $file, $line);
}, E_ALL);

class ImageLoader {
	// 2) $uri is user input
    public function getResult($uri) {
        if(!filter_var($uri, FILTER_VALIDATE_URL)) {
            return '<p>Please enter valid uri</p>';
        }

        try {
			// 3) ssrf, can also read all accessible files via file:///, phar:/// can lead to rce
            $image = file_get_contents($uri);
			// 4) uniqid --> is just dependened on the server time in micro seconds
            $path = "./images/" . uniqid() . '.jpg';
            file_put_contents($path, $image);
			// 5) if this errors the file is not unlinked
			//    we can also fool the check by adding one of the jpg magic bytes to the file
            if(mime_content_type($path) !== 'image/jpeg') {
                unlink($path);
                return '<p>Only .jpg files allowed</p>';
            }
        } catch (Exception $e) {
            return '<p>There was an error: ' .
                    $e->getMessage() . '</p>';
        }

        return '<img src="' . $path . '" width="100"/>';
    }
}

// 1) getResult recvs user input
echo (new ImageLoader())->getResult($_GET['img']);

// Summary:
// DOS:
//     e.g. file:///dev/urandom
// arbitrary file read:
//     There is a small raise window between fle_put_contents() and unlink()
//     where the attacker can brute uniqid to read out arbitrary files.... not tested if this can be raised
// SSRF:
//     e.g. http://localhost:8080/f00
// PHAR rce:
//     https://blog.sonarsource.com/new-php-exploitation-technique

{{< /code >}}
