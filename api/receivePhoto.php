<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

header('Cache-Control: no-cache');
header('Content-Type: application/json');

try {
    try {
        $photo = json_decode($_POST['photo'], JSON_THROW_ON_ERROR);
    } catch (\Throwable $e) {
        $response = Array(
            'type' => 'error',
            'message' => 'Error',
            'complex_message' => 'An unexpected error occured with json_decode() : ' . $e->getMessage() . '\n'
        );
        echo json_encode($response);
        die();
    }
    
    if (!array_key_exists('photos', $_SESSION)){
        $_SESSION['photos'] = array();
    }
    
    array_push($_SESSION['photos'], $photo);

    $response = Array(
        'type' => 'success',
        'message' => 'Uploading...',
        'complex_message' => 'Photo ' . $photo['name'] . ' has been successfully saved in $_SESSION'
    );
    echo json_encode($response);
} catch (\Throwable $e) {
    $response = Array(
        'type' => 'error',
        'message' => 'Error',
        'complex_message' => 'An unknown error occured : ' . $e->getMessage() . '\n'
    );
    echo json_encode($response);
    die();
}


?>