<?php

require_once($_SERVER['DOCUMENT_ROOT']."/api/auth.php");
if (!checkJWT($_COOKIE['jwt'], $jwtKey)['valid']){
    http_response_code(401);
}

$query = $_GET['q'] ?? null;

if ($query == "*") {
    $sql = "SELECT * FROM places";
} else {
    $search = str_replace(' ', ' +', ("+" . $query . "*"));
    $sql = "SELECT * FROM places WHERE MATCH(`name_en`,`name_fr`,`address`) AGAINST ('$search' IN BOOLEAN MODE)";
}

require('../dbconfig.php');
$result = mysqli_query($conn,$sql);

// echo highlight_string("<?php\n" . json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC), JSON_PRETTY_PRINT) . "\n;

header('Content-Type: application/json');
echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC), JSON_PRETTY_PRINT);