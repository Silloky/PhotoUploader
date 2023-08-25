<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

?>

<pre>
    <?php print_r($_SESSION) ?>
</pre>