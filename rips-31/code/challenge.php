$new_htaccess_file_path = trim($_POST['aiowps_htaccess_file']);
$is_htaccess = AIOWPSecurity_Utility_Htaccess::check_if_htaccess_contents($new_htaccess_file_path);

static function check_if_htaccess_contents($file)
{
   $is_htaccess = false;
   $file_contents = file_get_contents($file);
   if ($file_contents === FALSE || strlen($file_contents) == 0) {
                return -1;
   }

}
