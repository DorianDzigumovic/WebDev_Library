<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
include("../library.php");

$User = isset($_SESSION["Users"]) ? $_SESSION["Users"] : null;

if (!isset($_COOKIE['SessionID'])) {
    header("Location: Login.php");
    exit;
}

if (isset($_POST['canceled'])) {
    header("Location: Index.php");
    exit;
}

if (isset($_POST['submitted']) && !isset($_POST['canceled'])) {
    session_destroy();
    $conn->close();
    setcookie('SessionID', 0, time() - 3600, '/');
    header("Location: Index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <title>Library :Logout</title>
    <link rel="stylesheet" href="../libraryCSS/styles.css">
    <link rel="stylesheet" href="../libraryCSS/background.css">
    <link rel="icon" href="../media/icon.png">
</head>

<body>
    <header>
        <br><br>
        <a id="header">Library<img src="../media/headerIcon.png" style="width:3%;height:3%;" alt="Library icon"></a>
    </header>
    <div id="login">
        <h2>Are you sure you want to logout?</h2>

        <form method="post" action="">
            <button class="button" type="submit" name="submitted">
                <a class="buttontext">LOGOUT</a>
            </button>

            <form method="post" action="">
                <button class="cancelbutton" type="submit" name="canceled"><a class="buttontext">Cancel</a></button>
            </form>
        </form>
    </div>

    <br><br>

    <footer>
        <section class="footerGrid">
            <div class="footerText">
                <br>
                Thank you for using our Library System.
                <br>
                <br>
                Keep reading. Keep exploring.
            </div>
            <div class="footerText">
                Email: support@library.ie
                <br>
                <br>
                Opening Hours: Mon–Fri 9am–6pm
                <br>
                <br>
                Phone: (01) 123 4567
                <br>
                <br>
            </div>
        </section>
    </footer>
</body>
</html>
