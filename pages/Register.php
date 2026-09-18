<!DOCTYPE html>
<html lang=en>
<html>
<head>
    <title>Library:Register</title>
    <meta name="viewport">
    <link rel="stylesheet" href="../libraryCSS/styles.css">
    <link rel="stylesheet" href="../libraryCSS/background.css">
    <link rel="icon" href="../media/icon.png">
</head>

<body>

    <?php
        include("../library.php");

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Username']) && isset($_POST['Password'])) {

            $user   = trim($_POST['Username']);
            $pass   = trim($_POST['Password']);
            $fname  = trim($_POST['FirstName']);
            $sname  = trim($_POST['Surname']);
            $al1    = trim($_POST['AddressLine1']);
            $al2    = trim($_POST['AddressLine2']);
            $city   = trim($_POST['City']);
            $county = trim($_POST['County']);
            $tel    = trim($_POST['Telephone']);
            $mob    = trim($_POST['Mobile']);


            $stmt = $conn->prepare("SELECT 1 FROM Users WHERE Username = ?");
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("s", $user);
            $stmt->execute();
            $stmt->store_result(); 

            $userErrors = [];

            if ($stmt->num_rows > 0) {
                // Username already taken
                $userErrors[] = "Username already taken"; 
                $stmt->close();
            }


            $passErrors = [];

            if (isset($pass)) {
                if (strlen($pass) < 8) {
                    $passErrors[] = "Password must be at least 8 characters.";
                }
                if (!preg_match("/[A-Z]/", $pass)) {
                    $passErrors[] = "Must include an uppercase letter.";
                }
                if (!preg_match("/[a-z]/", $pass)) {
                    $passErrors[] = "Must include a lowercase letter.";
                }
                if (!preg_match("/\d/", $pass)) {
                    $passErrors[] = "Must contain a number.";
                }
            }
            
            if (empty($userErrors) && empty($passErrors)){
                $stmt->close();

                $stmt = $conn->prepare("INSERT INTO Users 
                    (Username, Password, FirstName, Surname, AddressLine1, AddressLine2, City, County, Telephone, Mobile)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }

                $stmt->bind_param(
                    "ssssssssss",
                    $user,
                    $pass,  
                    $fname,
                    $sname,
                    $al1,
                    $al2,
                    $city,
                    $county,
                    $tel,
                    $mob
                );

                if ($stmt->execute()) {
                    echo '<meta http-equiv="refresh" content="0; url=Login.php">';
                    $stmt->close();
                    $conn->close();
                    exit;
                } else {
                    echo "Error inserting user: " . $stmt->error;
                    $stmt->close();
                }
            }

            $conn->close();
        }


    ?>

    <header>
        <button class="gobackbutton">
            <a  href="Login.php" class="goback" >< Go Back</a>
        </button>
        <br>
        <br>
        <a id="header">Library<img src="../media/headerIcon.png" style="width:3%;height:3%;" alt="Library logo"></a>

    </header>

    <div id="register">

        <h1>CREATE ACCOUNT</h1>

        <h2>Your details:</h2>

        <form method="post" action="">
            <label id="UsernameBox" class="loginInput" for="Username">Username:</label>
            <br>
                * <input class="detailsText" type="text" id="Username" name="Username" placeholder="Username" required>
            <br>

            <?php

                if (!empty($userErrors)) {
                    echo "<style> #UsernameBox {border-color: red;}</style>";
                    foreach ($userErrors as $ue) {
                        echo "<a class='error'> " . $ue . "</a><br>";
                    }
                }            
            
            ?>

            <label id="PasswordBox" class="loginInput" for="Password">Password:</label>
            <br>
                * <input class="detailsText" type="password" id="Password" name="Password" placeholder="Password" required>
            <br>

            <?php

                if (!empty($passErrors)) {
                    echo "<style> #PasswordBox {border-color: red;}</style>";
                    foreach ($passErrors as $pe) {
                        echo "<a class='error'>" . $pe . "</a><br>";
                    }
                }            
            
            ?>

            <label id="FirstNameBox" class="loginInput" for="FirstName">First name:</label>
            <br>
                * <input class="detailsText" type="text" id="FirstName" name="FirstName" placeholder="First name" required>
            <br>
            
            
            <script>
                input = document.getElementById("FirstName");

                input.addEventListener("blur", function() {
                    if (this.value.length > 0) {
                        this.style.borderColor = "green";   
                    } else {
                        this.style.borderColor = "gray";
                    }
                });
            </script>

            <label id="SurnameBox" class="loginInput" for="Surname">Surname:</label>
            <br>
                * <input class="detailsText" type="text" id="Surname" name="Surname" placeholder="Surname" required>
            <br>

            <script>
                input = document.getElementById("Surname");

                input.addEventListener("blur", function() {
                    if (this.value.length > 0) {
                        this.style.borderColor = "green";   
                    } else {
                        this.style.borderColor = "gray";
                    }
                });
            </script>
            
            <label id="AddressLine1Box" class="loginInput" for="AddressLine1">Address line 1:</label>
            <br>
                * <input class="detailsText" type="text" id="AddressLine1" name="AddressLine1" placeholder="Address line 1" required>
            <br>
            
            <script>
                input = document.getElementById("AddressLine1");

                input.addEventListener("blur", function() {
                    if (this.value.length > 0) {
                        this.style.borderColor = "green";   
                    } else {
                        this.style.borderColor = "gray";
                    }
                });
            </script>

            <label id="AddressLine2Box" class="loginInput" for="AddressLine2">Address line 2:</label>
            <br>
                <input class="detailsText" type="text" id="AddressLine2" name="AddressLine2" placeholder="Address line 2">
            <br>
            
            <script>
                input = document.getElementById("AddressLine2");

                input.addEventListener("blur", function() {
                    if (this.value.length > 0) {
                        this.style.borderColor = "green";   
                    } else {
                        this.style.borderColor = "gray";
                    }
                });
            </script>

            <label id="CityBox" class="loginInput" for="City">City:</label>
            <br>
                * <input class="detailsText" type="text" id="City" name="City" placeholder="City" required>
            <br>
            
            <script>
                input = document.getElementById("City");

                input.addEventListener("blur", function() {
                    if (this.value.length > 0) {
                        this.style.borderColor = "green";   
                    } else {
                        this.style.borderColor = "gray";
                    }
                });
            </script>

            <label id="County" class="loginInput" for="County">County:</label>
                <br>
                * <select name="County" id="County" required>
                    <option value="" disabled selected>Please choose County</option>
                    <option value="Carlow">Carlow</option>
                    <option value="Cavan">Cavan</option>
                    <option value="Clare">Clare</option>
                    <option value="Cork">Cork</option>
                    <option value="Donegal">Donegal</option>
                    <option value="Dublin">Dublin</option>
                    <option value="Galway">Galway</option>
                    <option value="Kerry">Kerry</option>
                    <option value="Kildare">Kildare</option>
                    <option value="Kilkenny">Kilkenny</option>
                    <option value="Laois">Laois</option>
                    <option value="Leitrim">Leitrim</option>
                    <option value="Limerick">Limerick</option>
                    <option value="Longford">Longford</option>
                    <option value="Louth">Louth</option>
                    <option value="Mayo">Mayo</option>
                    <option value="Meath">Meath</option>
                    <option value="Monaghan">Monaghan</option>
                    <option value="Offaly">Offaly</option>
                    <option value="Roscommon">Roscommon</option>
                    <option value="Sligo">Sligo</option>
                    <option value="Tipperary">Tipperary</option>
                    <option value="Waterford">Waterford</option>
                    <option value="Westmeath">Westmeath</option>
                    <option value="Wexford">Wexford</option>
                    <option value="Wicklow">Wicklow</option>
                </select>
                <br>
            
            <script>
                input = document.getElementById("County");

                input.addEventListener("blur", function() {
                    if (this.value.length > 0) {
                        this.style.borderColor = "green";   
                    } else {
                        this.style.borderColor = "gray";
                    }
                });
            </script>

            <label id="TelephoneBox" class="loginInput" for="Telephone">Telephone:</label>
            <br>
                <input class="detailsText" type="tel" id="Telephone" name="Telephone" placeholder="Eg. 01 234 5678">
            <br>

            <script>
                input = document.getElementById("Telephone");

                input.addEventListener("blur", function() {
                    if (this.value.length > 0) {
                        this.style.borderColor = "green";   
                    } else {
                        this.style.borderColor = "gray";
                    }
                });
            </script>

            <label id="MobileBox" class="loginInput" for="Mobile">Mobile:</label>
            <br>
                <input class="detailsText" type="tel" id="Mobile" name="Mobile" placeholder="Eg. 083 123 4567">
            <br><br>

            <script>
                input = document.getElementById("Mobile");

                input.addEventListener("blur", function() {
                    if (this.value.length > 0) {
                        this.style.borderColor = "green";   
                    } else {
                        this.style.borderColor = "gray";
                    }
                });
            </script>

            <button class="button" type="submit">
                <a class="buttontext">REGISTER</a>
            </button>
        </form>
        <br>
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