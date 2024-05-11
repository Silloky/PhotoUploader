<?php

require_once($_SERVER['DOCUMENT_ROOT']."/api/auth.php");
if (!checkJWT($_COOKIE['jwt'], $jwtKey)['valid']){
    http_response_code(401);
}

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

?>

<pre>
    <?php print_r($_SESSION) ?>
</pre>