<?php
$serverName = "localhost";
$username = "root";
$password = "root";
$dbName = "password_reset";

// Create connection
$connection = new mysqli($serverName, $username, $password, $dbName);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
