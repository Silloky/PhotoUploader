<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$uuid = $_GET['uuid'];

foreach ($_SESSION['photos'] as $photo){
    if ($photo['uuid'] == $uuid){
        break;
    }
}

$messages = Array(
    'Reading',
    'Saving',
    'Setting date',
    'Setting GPS',
    'Wraping up',
    'Done !'
);

$i = 0;
while ($i < 5) {
    if (connection_aborted()) {
        break; // Exit the loop if the client has disconnected
    }
    $response = json_encode(Array(
        'type' => 'info',
        'message' => $messages[$i],
        'complex_message' => "This is the log message : {$messages[$i]}"
    ));
    echo "data: {$response}\n\n";
    flush();
    $i++;
    sleep(2);
}

echo "event: close\n";
echo "data: Closing connection\n\n";
flush();

exit();