<?php
if ('restore' == $_GET['action']) {
    $upload = $_FILES['filename'];
    $upload_tmp = $_FILES['filename']['tmp_name'];
    $upload_name = $_FILES['filename']['name'];
    $upload_error = $_FILES['filename']['error'];
    // 1) $upload and $upload_name  is under user control
    if ($upload_error > 0) {
        switch ($upload_error) {
            case UPLOAD_ERR_INI_SIZE:
                break;
            default:
                echo sprintf("Error %s", $upload_error);
        }
    }
    if (!$upload_name && isset($_POST['file'])) {
    $upload_name = filter_input(INPUT_POST,'file',FILTER_SANITIZE_STRING);
    } else {
        // 2) if $upload_name is provided we go here.
        //    In this case the $upload_name is not sanitized
        //    and could include html tags.
        //    The do_upload function should better check for path traversal...
        $ret_val = do_upload($upload_tmp, $upload_name);
    }
    // 3) XSS if the else branch above was taken
    echo '<p><b>restore from ' . $upload_name . '</b>';
}


