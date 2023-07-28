<?php

$path = $_POST['path']; // gets the posted path from browser

$basePath = '/media/'; // base path (where Abums is mounted in the container)
$completePath = $basePath . $path; // creates the full path

header('Content-Type: application/json');

if (mkdir("$completePath")){
    $data = array(
        'type' => 'success',
        'message' => 'Successfully created directory !',
        'complex_message' => 'Successfully created directory : mkdir all clear',
    );
} else {
    $data = array(
        'type' => 'error',
        'message' => 'Couldn\'t create directory !',
        'complex_message' => 'Couldn\'t create directory : mkdir throwed ' . error_get_last(),
    );
}
// creates the response JSON

echo json_encode($data); // send the response