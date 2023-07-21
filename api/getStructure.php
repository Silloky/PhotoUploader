<?php

function getContents($path) {
    $contents = array_diff(scandir($path, SCANDIR_SORT_ASCENDING), array('..', '.'));
    $filtered = array();
    foreach ($contents as $item) {
        $itemPath = $path . '/' . $item;
        if (is_dir($itemPath)){
            $subarray = array('name' => "$item", 'children' => getContents($itemPath));
            array_push($filtered, $subarray);
        }
    }
    return $filtered;
}

$rootDir = '/media';

$structure = json_encode(array('name' => 'root', 'children' => getContents($rootDir)), JSON_PRETTY_PRINT);

// header('Content-Type: application/json');
// echo $structure;