<?php

require_once($_SERVER['DOCUMENT_ROOT']."/api/auth.php");
if (!isset($_COOKIE['jwt']) || !checkJWT($_COOKIE['jwt'], $jwtKey)['valid']){
    http_response_code(401);
}

$query = $_GET['q'] ?? null;

if ($query == "*") {
    $sql = "SELECT * FROM places";
} else {
    $search = str_replace(' ', ' +', ("+" . $query . "*"));
    $sql = "SELECT * FROM places WHERE MATCH(`name_en`,`name_fr`,`address`) AGAINST ('$search' IN BOOLEAN MODE)";
}

$conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PWD'], $_ENV['DB_NAME']);
$result = mysqli_query($conn,$sql);

// echo highlight_string("<?php\n" . json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC), JSON_PRETTY_PRINT) . "\n;

header('Content-Type: application/json');
echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC), JSON_PRETTY_PRINT);