<?php
// Include your database configuration file
include "config.php";

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Land Problem Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin-top: 50px;
        }
        h1 {
            color: #333;
        }
        .status {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Land Problem Management System</h1>
    <p class="status">Database connected successfully!</p>
</body>
</html>
