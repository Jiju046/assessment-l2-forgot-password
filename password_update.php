<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $token = $_POST["token"];

    // Check if password and confirm password match and token present in db
    if ($password === $confirmPassword && !empty($password)) {
        $sql = "SELECT * FROM users WHERE reset_token = '$token'";
        $result = $connection->query($sql);

        if ($result) {
            // update password
            $query = "UPDATE users
            SET password = '$password'
            WHERE reset_token = '$token'";

            // error handling
            if ($connection->query($query)) {
                $response['status'] = 1;
                $response['message'] = "Password reset successful.";
            } else {
                $response['status'] = 0;
                $response['message'] = "Password reset failed. Please try again.";
            }
        }
    } else {
        $response['status'] = 0;
        $response['message'] = "please enter a password";
    }

    echo json_encode($response);

    $connection->close();
}
