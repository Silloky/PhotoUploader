<?php

$path = $_POST['path'];

$basePath = '/media/';
$completePath = $basePath . $path;

header('Content-Type: application/json');

if (mkdir("$completePath")){
    $data = array(
        'type' => 'success',
        'message' => 'Successfully created directory !',
        'complex_message' => 'Successfully created directory : mkdir all clear',
        'complete_path' => $completePath
    );
} else {
    $data = array(
        'type' => 'error',
        'message' => 'Couldn\'t create directory !',
        'complex_message' => 'Couldn\'t create directory : mkdir throwed ' . error_get_last(),
        'complete_path' => $completePath
    );
}

echo json_encode($data);