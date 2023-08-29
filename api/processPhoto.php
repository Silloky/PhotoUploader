<?php
ini_set('display_errors', 1);

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$uuid = $_GET['uuid'];
$basePath = '/media';

foreach ($_SESSION['photos'] as $index=>$photo){
    if ($photo['uuid'] == $uuid){
        break;
    }
}

try {
    sleep(1);
    
    $response = json_encode(Array(
        'type' => 'info',
        'message' => 'Decoding...',
        'complex_message' => "Server Event : Decoding base64 string of image " . $photo['name']
    ));
    echo "data: {$response}\n\n";
    flush();
    $decoded = base64_decode(preg_replace('/data:image\/\w+;base64,/', '', $photo['data']));
    sleep(1);
    
    $response = json_encode(Array(
        'type' => 'info',
        'message' => 'Saving photo on server...',
        'complex_message' => "Server Event : " . $photo['name'] . " is being saved in " . $basePath . $photo['saveLocation'] 
    ));
    echo "data: {$response}\n\n";
    flush();
    if ($photo['saveLocation'] != null){
        $file = $basePath . $photo['saveLocation'] . $photo['name'];
    } else {
        $file = $basePath . '/Unclassified' . $photo['name'];
    }
    json_encode(file_put_contents($file, $decoded));
    
    usleep(1500);
    

    $newEpoch = $photo['lastModified'] / 1000;
    $response = json_encode(Array(
        'type' => 'info',
        'message' => 'Setting new date...',
        'complex_message' => "Server Event : setting user-defined date for " . $photo['name']
    ));
    echo "data: {$response}\n\n";
    flush();
    $date = date('Y:m:d G:i:s', $newEpoch);
    shell_exec("exiftool '-DateTimeOriginal={$date}' '-TimeCreated={$date}' '-DateCreated={$date}' \"{$file}\"");
    sleep(1);
    
    if (isset($photo['gpsLocation'])){
        $response = json_encode(Array(
            'type' => 'info',
            'message' => 'Setting GPS Location...',
            'complex_message' => "Server Event : Seting GPS location of " . $photo['name']
        ));
        echo "data: {$response}\n\n";
        flush();
        require('../dbconfig.php');
        $sql = "SELECT * FROM places WHERE id = '{$photo['gpsLocation']}'";
        $result = mysqli_query($conn,$sql);
        $place = mysqli_fetch_assoc($result);
        if ($place['latitude'] > 0){
            $latRef = 'N';
        } else {
            $latRef = 'S';
        }
        if ($place['longitude'] > 0){
            $longRef = 'E';
        } else {
            $longRef = 'W';
        }
        $gpsCommand = "exiftool -GPSLatitude={$place['latitude']} -GPSLatitudeRef={$latRef} -GPSLongitude={$place['longitude']} -GPSLongitudeRef={$longRef} -GPSAltitude={$place['altitude']} -GPSAltitudeRef='Above Sea Level' \"{$file}\"";
        shell_exec($gpsCommand);
    } else {
        $response = json_encode(Array(
            'type' => 'info',
            'message' => 'No GPS provided',
            'complex_message' => "Server Event : User did not provide any GPS information for " . $photo['name'] . ", skipping GPS"
        ));
        echo "data: {$response}\n\n";
        flush();
    }
    
    sleep(1);
    
    $response = json_encode(Array(
        'type' => 'info',
        'message' => 'Wraping up...',
        'complex_message' => "Server Event : Finishing up for " . $photo['name']
    ));
    echo "data: {$response}\n\n";
    flush();
    shell_exec("rm \"{$file}_original\"");
    unset($_SESSION['photos'][$index]);
    sleep(2);
    
    $response = json_encode(Array(
        'type' => 'info',
        'message' => 'Wraping up...',
        'complex_message' => "Server Event : " . $photo['name'] . " has finished processing"
    ));
    echo "data: {$response}\n\n";
    flush();
} catch (\Throwable $th) {
    $response = json_encode(Array(
        'type' => 'error',
        'message' => 'Error !',
        'complex_message' => 'Server Event for ' . $photo['name'] . ' : ' . $th->getMessage()
    ));
}


echo "event: close\n";
echo "data: Closing connection\n\n";
flush();

exit();