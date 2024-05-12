<?php

require_once($_SERVER['DOCUMENT_ROOT']."/api/auth.php");
if (!isset($_COOKIE['jwt']) || !checkJWT($_COOKIE['jwt'], $jwtKey)['valid']){
    http_response_code(401);
}

$path = $_POST['path']; // gets the posted path from browser

$basePath = '/media/';  // base path (where Abums is mounted in the container)
$completePath = $basePath . $path; // creates the full path

header('Content-Type: application/json');

if (file_exists($completePath)){ // tests if the direcfory to delete exists
    // if it does :
    if (count(glob("$completePath/*")) === 0){ // checks if the directory to delete is empty
        // if it is :
        if (rmdir($completePath)){ // removes the directory
            // if the removal is successful
            $response = array(
                'type' => 'success',
                'message' => 'Successfully removed directory',
                'complex_message' => 'Directory ' . $completePath . ' successfully removed : mkdir clear'
            );
        } else {
            // if it is a failure
            $response = array(
                'type' => 'error',
                'message' => 'Couldn\'t remove directory (unknown error)',
                'complex_message' => 'Couldn\'t remove directory ' . $completePath . '  : unknown mkdir error'
            );
        }
    } else {
        // if it isn't :
        $response = array(
            'type' => 'error',
            'message' => 'Couldn\'t remove directory (non-empty)',
            'complex_message' => 'Couldn\'t remove directory ' . $completePath . ' : non empty directory. Please ask system administrator to remove contents first.'
        );
    }
} else {
    // if it doesn't :
    $response = array(
        'type' => 'error',
        'message' => 'Couldn\'t remove directory (non-existent)',
        'complex_message' => 'Couldn\'t remove directory ' . $completePath . ' : non-existent directory'
    );
}

echo json_encode($response); // sends response