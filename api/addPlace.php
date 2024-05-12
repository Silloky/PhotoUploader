<?php

require_once($_SERVER['DOCUMENT_ROOT']."/api/auth.php");
if (!isset($_COOKIE['jwt']) || !checkJWT($_COOKIE['jwt'], $jwtKey)['valid']){
    http_response_code(401);
}

$placeInfo = $_POST['placeInfo'];

if ($placeInfo['placeid'] == ''){
    $res = Array(
        'type' => 'error',
        'message' =>  'Place ID is empty',
        'complex_message' => 'Received empty placeid from browser'
    );
}

if ($placeInfo['placeNameEN'] == ''){
    $res = Array(
        'type' => 'error',
        'message' =>  'English Name is empty',
        'complex_message' => 'Received empty placeNameEN from browser'
    );
}

if ($placeInfo['placeNameFR'] == ''){
    $res = Array(
        'type' => 'error',
        'message' =>  'French Name is empty',
        'complex_message' => 'Received empty placeNameFR from browser'
    );
}

if ($placeInfo['latitude'] == ''){
    $res = Array(
        'type' => 'error',
        'message' =>  'Internal Error',
        'complex_message' => 'Internal Error : received null latitude from browser'
    );
}

if ($placeInfo['placeNameFR'] == ''){
    $res = Array(
        'type' => 'error',
        'message' =>  'Internal Error',
        'complex_message' => 'Internal Error : received null longitude from browser'
    );
}

if (!isset($res)){
    $sql = "INSERT INTO places (id, name_en, name_fr, address, latitude, longitude, altitude) VALUES (\"" . $placeInfo['placeid'] . "\", \"" . $placeInfo['placeNameEN'] . "\", \"" . $placeInfo['placeNameFR'] . "\", \"" . $placeInfo['address'] . "\", \"" . $placeInfo['latitude'] . "\", \"" . $placeInfo['longitude'] . "\", \"" . $placeInfo['altitude'] . "\")";
    require('../dbconfig.php');
    if ($conn->query($sql) === TRUE){
        $res = Array(
            'type' => 'success',
            'message' => 'Place successfully created !',
            'complex_message' => 'Place ' . $placeInfo['placeid'] . ' was successfully saved into database.'
        );
    } else {
        $res = Array(
            'type' => 'error',
            'message' =>  $sql,
            'complex_message' => mysqli_error($conn)
        );
    }
}

header('Content-Type: application/json');
echo json_encode($res);