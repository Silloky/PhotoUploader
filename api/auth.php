<?php

use ReallySimpleJWT\Token;
require_once($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/dbconfig.php');

function checkJWT($jwt, $jwtKey) {
    if (Token::validate($jwt, $jwtKey)){
        if (Token::validateExpiration($jwt, $jwtKey)){
            return array(
                "valid" => true,
                "code" => ""
            );
        } else {
            return array(
                "valid" => false,
                "code" => "expired"
            );
        }
    } else {
        return array(
            "valid" => false,
            "code" => "badstruct"
        );
    }
}

function login($conn, $jwtKey){
    header('Content-Type: application/json');
    if (isset($_POST['user']) && isset($_POST['pwd'])) {
        $username = $_POST['user'];
        $pwd = $_POST['pwd'];
        http_response_code(200);
    
        
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn,$sql);
        if ($result->num_rows > 0){
            $hash = (mysqli_fetch_assoc($result))['pwd'];
            if (password_verify($pwd, $hash)){
                $_SESSION['username'] = $username;
                $exp = 3600;
                $jwt = Token::create($username, $jwtKey, (time() + $exp), $_SERVER['SERVER_NAME']);
                $res = Array(
                    'type' => 'token',
                    'token' => $jwt
                );
            } else {
                $toastContents = Array(
                    'type' => 'error',
                    'message' => 'Username and password don\'t match',
                    'complex_message' => 'User entered non-corresponding username-password combination.'
                );
            }
        } else {
            $toastContents = Array(
                'type' => 'error',
                'message' => 'Unknown username',
                'complex_message' => 'Non-existing username'
            );
        }
        if (isset($toastContents)){
            $res = Array(
                'type' => 'toast',
                'toastData' => $toastContents
            );
        }
        echo json_encode($res);
    } else {
        http_response_code(400);
    }
    exit;
}

if (isset($_POST['action']) && $_POST['action'] == "login"){
    login($conn, $jwtKey);
}


?>