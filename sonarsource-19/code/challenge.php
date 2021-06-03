<?php
if ('restore' == $_GET['action']) {
    $upload = $_FILES['filename'];
    $upload_tmp = $_FILES['filename']['tmp_name'];
    $upload_name = $_FILES['filename']['name'];
    $upload_error = $_FILES['filename']['error'];
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
        $ret_val = do_upload($upload_tmp, $upload_name);
    }
    echo '<p><b>restore from ' . $upload_name . '</b>';
}
