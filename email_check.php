<?php
session_start();
include "db.php";


// generate token
function generateToken()
{

    $token = bin2hex(random_bytes(16));
    $expire = time() + 600; // 10 minutes expiration
    return array('token' => $token, 'expiration' => $expire);
}

$response = array(); // Initialize response array

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    // check if token exist
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $connection->query($sql);


    if (empty($email)) {

        $response['status'] = 0;
        $response['message'] = "Enter an Email.";
    } elseif ($result->num_rows > 0) {

        $tokenGenerate = generateToken();
        $token = $tokenGenerate['token'];
        $expiration = date('Y-m-d H:i:s', $tokenGenerate['expiration']);

        $query = "UPDATE users
        SET reset_token = '$token', token_expiry = '$expiration'
        WHERE email = '$email'";


        if ($connection->query($query)) {
            $response['status'] = 1;
            $response['message'] =  "Token:$token:$expiration";
            $_SESSION['token'] = $token;
        } else {
            $response['status'] = 0;
            $response['message'] = "Error updating database.";
        }
    } else {
        $response['status'] = 0;
        $response['message'] = "This Email address not exists.";
    }

    echo json_encode($response);

    $connection->close();
}
