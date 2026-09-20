<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
include("../library.php");

if (isset($_POST['canceled'])) {
    header("Location: MyAccount.php?action=delete");
    exit;
}

if ((isset($_POST['submitted'])) && !isset($_POST['canceled'])) {
    $User = isset($_SESSION["Users"]) ? $_SESSION["Users"] : null;
    $sql = "DELETE FROM users WHERE Username='$User'";
    $conn->query($sql);
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
    <title>Library:Delete</title>
    <meta name="viewport">
    <link rel="stylesheet" href="../libraryCSS/styles.css">
    <link rel="stylesheet" href="../libraryCSS/background.css">
    <link rel="icon" href="../media/icon.png">
</head>

<body>
    <header>
        <br><br>
        <a id="header">Library<img src="../media/headerIcon.png" style="width:3%;height:3%;" alt="Library logo"></a>
    </header>

    <div id="login">
        <h2>Are you sure you want to delete you account?</h2>

        <form method="post" action="">
            <button class="button" type="submit" name="submitted">
                <a class="buttontext">Delete</a>
            </button>

            <form method="post" action="">
                <button class="cancelbutton" type="submit" name="canceled">
                    <a class="buttontext">Cancel</a>
                </button>
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
