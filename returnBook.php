<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
include("library.php");

$User = isset($_SESSION["Users"]) ? $_SESSION["Users"] : null;

if (!isset($_GET["ISBN"]) || $_GET["ISBN"] === "") {
    header("Location: pages/MyAccount.php?action=reservation");
    exit;
}

$ISBN = $_GET["ISBN"];

$sql = "DELETE FROM reservations WHERE ISBN=? AND Username=?";
$delete = $conn->prepare($sql);
$delete->bind_param("ss", $ISBN, $User);
$delete->execute();
$delete->close();

if (!$delete) {
    die("Prepare failed: " . $conn->error);
}

$sql = "UPDATE books SET Reserved ='N' WHERE ISBN=?";
$update = $conn->prepare($sql);
$update->bind_param("s", $ISBN);
$update->execute();
$update->close();

if (!$update) {
    die("Prepare failed: " . $conn->error);
}

header("Location: pages/MyAccount.php?action=reservation");
exit;
