<?php

$path = $_POST['path'];

$basePath = '/media/';  
$completePath = $basePath . $path;

header('Content-Type: application/json');

if (file_exists($completePath)){
    if (count(glob("$completePath/*")) === 0){
        if (rmdir($completePath)){
            $response = array(
                'type' => 'success',
                'message' => 'Successfully removed directory',
                'complex_message' => 'Directory ' . $completePath . ' successfully removed : mkdir clear'
            );
        } else {
            $response = array(
                'type' => 'error',
                'message' => 'Couldn\'t remove directory (unknown error)',
                'complex_message' => 'Couldn\'t remove directory ' . $completePath . '  : unknown mkdir error'
            );
        }
    } else {
        $response = array(
            'type' => 'error',
            'message' => 'Couldn\'t remove directory (non-empty)',
            'complex_message' => 'Couldn\'t remove directory ' . $completePath . ' : non empty directory. Please ask system administrator to remove contents first.'
        );
    }
} else {
    $response = array(
        'type' => 'error',
        'message' => 'Couldn\'t remove directory (non-existent)',
        'complex_message' => 'Couldn\'t remove directory ' . $completePath . ' : non-existent directory'
    );
}

echo json_encode($response);