<?php // Auth [Authentication layer]
/* Make sure the user is correctly authenticated */

/* For now everybody can enter*/
$auth = true;
$authname = "Administrator";

/*
Internet snippet
<?php
if($_SESSION['http_logged'] != 1) {
      $_SERVER['PHP_AUTH_USER'] = '';
      $_SERVER['PHP_AUTH_PW'] = '';
    }

    if ($_SERVER['PHP_AUTH_USER'] != $your_username || $_SERVER['PHP_AUTH_PW'] != $your_password ) {
      $_SESSION['http_logged'] = 1;
      header('WWW-Authenticate: Basic realm="realm"');
      header('HTTP/1.0 401 Unauthorized');
      exit;
    } else {
      $_SESSION['http_logged'] = 0;
    }
?>

*/

?>