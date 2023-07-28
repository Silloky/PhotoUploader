<?php

function getContents($path) {
    $contents = array_diff(scandir($path, SCANDIR_SORT_ASCENDING), array('..', '.')); // scans the directory (ls) and excluded Linux '.' and '..'
    $filtered = array();
    foreach ($contents as $item) {
        // loops throuh each child of current directory
        $itemPath = $path . '/' . $item; // forms complete path
        if (is_dir($itemPath)){ // only selects directories
            $subarray = array(
                'name' => "$item",
                'children' => getContents($itemPath) // recursively reruns getContents()
            );
            array_push($filtered, $subarray);
        }
    }
    return $filtered;
}

$rootDir = '/media'; // base path (where Abums is mounted in the container)

$structure = json_encode(array(
    'name' => 'root', 
    'children' => getContents($rootDir)
), JSON_PRETTY_PRINT);

header('Content-Type: application/json');
echo $structure; // responds the structure