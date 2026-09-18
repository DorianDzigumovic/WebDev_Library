<!DOCTYPE html>
<html lang=en>
<html>
<head>
    <title>Library:Login</title>
    <meta name="viewport">
    <link rel="stylesheet" href="../libraryCSS/styles.css">
    <link rel="stylesheet" href="../libraryCSS/background.css">
    <link rel="icon" href="../media/icon.png">
</head>

<body>
    <header>
        <button class="gobackbutton">
            <a  href="Index.php" class="goback" >< Go Back</a>
        </button>
        <br><br>
        <a id="header">Library<img src="../media/headerIcon.png" style="width:3%;height:3%;" alt="Library logo"></a>
    </header>

    <?php 
        $error = $_GET['error'] ?? '';

        $_SESSION['sessionID'] = session_start();

        include("../library.php");
        if ((isset($_POST['Username'])) && (isset($_POST['Password']))) {
            
            if ($_SERVER["REQUEST_METHOD"] === "POST") {

                if (!empty($_POST['Username']) && !empty($_POST['Password'])) {
                    $u = trim($_POST["Username"]);
                    $p = $_POST["Password"];

                    $stmt = $conn->prepare("SELECT Username, Password FROM Users WHERE Username = ?");
                    if (!$stmt) {
                        die("Prepare failed: " . $conn->error);
                    }

                    $stmt->bind_param("s", $u);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows === 1) {
                        $row = $result->fetch_assoc();

            
                        if ($p == $row["Password"]) {

                            $_SESSION["Users"] = $row["Username"]; 
                            $stmt->close();
                            $conn->close();
                            $cookieName = 'SessionID';
                            $cookieValue = session_id();
                            setcookie($cookieName, $cookieValue);
                            header("Location: Index.php");
                            exit;
                        } else {
                            // Password wrong
                            header("Location: Login.php?error=userOrPass");
                            exit;
                        }
                    } else {
                        // No such username
                        header("Location: Login.php?error=userOrPass");
                        exit;
                    }

                    $stmt->close();
                } else {
                    header("Location: Login.php?error=userOrPass");
                    exit;
                }
            }
        }
        $conn->close();
    ?>


    <div id="login">
        <h2>Login:</h2>

        <form method="post" action="">
            <label class="loginInput" for="UserName">Username:</label>
            <br>
                <input class="loginInput" type="text" id="Username" name="Username" placeholder="Username" required>
            <br><br>

            <label class="loginInput" for="Password">Password:</label>
            <br>
                <input class="loginInput" type="password" id="Password" name="Password" placeholder="Password" required>
            <br>

            <?php if ($error == 'userOrPass') { ?>
                <a class="error">Incorrect username or password</a>
                <br>
            <?php } ?>

            <br>

            <button class="button" type="submit">
                <a class="buttontext">SUBMIT</a>
            </button>

        </form>
                    
        <button class="register">
            <a  href="Register.php" class="hyperlink">Don't have an account? Register...</a>
        </button>

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