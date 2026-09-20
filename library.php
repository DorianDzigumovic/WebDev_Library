<?php
    // Docker Compose uses DB_HOST=db. The defaults keep the project usable with
    // the existing XAMPP/local MariaDB setup as well.
    $servername = getenv('DB_HOST') ?: 'localhost';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    $dbname = getenv('DB_NAME') ?: 'library';

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4');
?>
