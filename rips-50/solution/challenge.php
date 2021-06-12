set_error_handler(function ($no, $str, $file, $line) {
    throw new ErrorException($str, 0, $no, $file, $line);
}, E_ALL);

class ImageLoader {
    public function getResult($uri) {                         // 2) $uri is user input
        if(!filter_var($uri, FILTER_VALIDATE_URL)) {
            return '<p>Please enter valid uri</p>';
        }

        try {
            $image = file_get_contents($uri);                 // 3) ssrf, can also read all accessible files via file:///, phar:/// can lead to rce
            $path = "./images/" . uniqid() . '.jpg';          // 4) uniqid --> is just dependened on the server time in micro seconds
            file_put_contents($path, $image);
            if(mime_content_type($path) !== 'image/jpeg') {   // 5) if this errors the file is not unlinked
                unlink($path);                                //    we can also fool the check by adding one of the jpg magic bytes to the file
                return '<p>Only .jpg files allowed</p>';
            }
        } catch (Exception $e) {
            return '<p>There was an error: ' .
                    $e->getMessage() . '</p>';
        }

        return '<img src="' . $path . '" width="100"/>';
    }
}

echo (new ImageLoader())->getResult($_GET['img']);              // 1) getResult recvs user input

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
