<?php
    session_start();
    include("library.php");

    if (!isset($_COOKIE['SessionID'])) { ?>

        <script>window.location.replace("Login.php");</script>

    <?php }

    if (!isset($_GET["ISBN"]) || $_GET["ISBN"] === "") {
        header("Location: pages/Browse.php");
        exit;
    }

    $ISBN = $_GET["ISBN"];
    $User = isset($_SESSION["Users"]) ? $_SESSION["Users"] : null;


    $sql = "INSERT INTO reservations (ISBN, Username, ReservedDate) VALUES (?, ?, CURDATE())";
    $insert = $conn->prepare($sql);
    $insert->bind_param("ss", $ISBN, $User);
    $insert->execute();

    $sql = "UPDATE books SET Reserved ='Y' WHERE ISBN=?";
    $update = $conn->prepare($sql);
    $update->bind_param("s", $ISBN);
    $update->execute();
    $update->close();

    
    header("Location: pages/Browse.php");
    exit;
?>