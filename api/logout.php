<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

session_unset();
session_destroy();

unset($_COOKIE['jwt']);
setcookie("jwt", "", time()-3600);

header('Content-Type: application/json');

$res = Array(
    'type' => 'success',
    'message' => 'You are logged out ! Redirecting in 5 seconds...',
    'complex_message' => 'Successfully logged user out. Redirecting in 5 seconds'
);

echo json_encode($res);

?>