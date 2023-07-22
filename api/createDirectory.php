<?php

$path = $_POST['path'];

$basePath = '/media/';
$completePath = $basePath . $path;

header('Content-Type: text/plain');

if (mkdir($completePath)){
    $data = array(
        'type' => 'success',
        'message' => 'Everything OK, created directory !'
    );
} else {
    $data = array(
        'type' => 'error',
        'message' => 'Argh ! An error occured ! Couldn\'t create directory !'
    );
}

echo json_encode($data);