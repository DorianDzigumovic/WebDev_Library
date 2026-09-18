<!DOCTYPE html>
<html lang=en>
<html>
<head>
    <title>Library:Home</title>
    <meta name="viewport">
    <link rel="stylesheet" href="../libraryCSS/styles.css">
    <link rel="stylesheet" href="../libraryCSS/background.css">
    <link rel="icon" href="../media/icon.png">
</head>

<body>
    <?php
        session_start();
        include("../library.php");

        $User = isset($_SESSION["Users"]) ? $_SESSION["Users"] : null;
    ?>

    <header>
        <div class="top-nav-bar">

            <a class="heading active" href="Index.php">Home</a>
            <a class="heading" href="Browse.php">Browse Books</a>

            <?php if ($User !== null) { ?>

                <a class="heading" href="Logout.php">Logout</a>

                <?php
                    $sql = "SELECT FirstName FROM Users WHERE Username='$User'";
                    $result = $conn->query($sql);

                    if ($result && $row = $result->fetch_assoc()) {
                        echo '<a id="MyAccount" class="heading" href="MyAccount.php?action=details"><img src="../media/accountIcon.png" style="width:1.3%;height:1.3%;" alt="Account icon"> My Account : ' . $row["FirstName"] . '</a>';
                    }
                ?>

            <?php } else { ?>
                <a id="MyAccount" class="heading" href="Login.php"><img src="../media/accountIcon.png" style="width:1.3%;height:1.3%;" alt="Account icon"> Login</a>
            <?php } ?>

        </div>

        <a id="header">Library<img src="../media/headerIcon.png" style="width:3%;height:3%;" alt="Library logo"></a>

    </header>

    <div id="welcome">
        <h1>WELCOME TO THE LIBRARY</h1>
    </div>

    <br><br>

    <div id="container">
            
        <h2> Our reccommendations :</h2>

        <br><br>

        <section class="reccommendations">

            <div class="reccommendImg">
                <a href="Browse.php?search=Da+Vinci+Code">
                    <img id="image1" src="../media/daVinciCode.jpg" alt="Cover of book Da Vinci Code by Dan Brown" width="100%" height="auto">

                    <div class="reccomendDetails">
                        <p>Da Vinci Code by Dan Brown</p>
                    </div>
                </a>
            </div>

            <div class="reccommendImg">
                <a href="Browse.php?search=Tara+Road">
                    <img id="image2" src="../media/taraRoad.jpg" alt="Cover of book Tara Road by Maeve Binchy" width="100%" height="auto">
                    
                    <div class="reccomendDetails">
                        <p>Tara Road by Maeve Binchy</p>
                    </div>
                </a>
            </div>

            <div class="reccommendImg">
                <a href="Browse.php?search=Shooting+History">
                    <img id="image3" src="../media/shootingHistory.jpg" alt="Cover of book Shooting History by Jon Snow" width="100%" height="auto">
                    
                    <div class="reccomendDetails">
                        <p>Shooting History by Jon Snow</p>
                    </div>
                </a>
            </div>

        </section>

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
