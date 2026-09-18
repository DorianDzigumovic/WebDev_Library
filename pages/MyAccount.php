<!DOCTYPE html>
<html lang=en>
<html>
<head>
    <title>Library:My Account</title>
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
    
        if (!isset($_COOKIE['SessionID'])) { ?>

            <script>window.location.replace("Login.php");</script>

    <?php } ?>
    <header>


        <div class="top-nav-bar">
            <a class="heading" href="Index.php">Home</a>
            <a class="heading" href="Browse.php">Browse Books</a>
            <a class="heading" href="Logout.php">Logout</a>

            <?php
                $sql = "SELECT Username, Password, FirstName, Surname, AddressLine1, AddressLine2, City, County, Telephone, Mobile FROM Users WHERE Username='$User'";
                $result = $conn->query($sql);

                if ($result && $row = $result->fetch_assoc()) {
                    echo '<a id="MyAccount" class="heading active" href="MyAccount.php"><img src="../media/accountIcon.png" style="width:1.3%;height:1.3%;" alt="Account icon"> My Account : ' . $row["FirstName"] . '</a>';
                }
            ?>
        </div>

        <a id="header">Library<img src="../media/headerIcon.png" style="width:3%;height:3%;" alt="Library logo"></a>
    </header>

    <br><br><br>

    <div class="nav-bar">
        <a id="nav1" href="MyAccount.php?action=details">MY DETAILS</a>
        <a id="nav2" href="MyAccount.php?action=update">UPDATE PROFILE</a>
        <a id="nav3" href="MyAccount.php?action=reservation">MY RESERVATIONS</a>
        <a id="nav4" href="MyAccount.php?action=delete">DELETE ACCOUNT</a>
    </div>

    <div id="details">

        <?php 
        $action = $_GET['action'] ?? 'details';
        ?>


<!----> <?php if ($action == 'details') { ?>

            <style>
                #nav1 {
                    background-color: white;
                    color: #004C6C;
                    font-weight: bold;
                }
            </style>


            <h2>My Details:</h2>
            <br><br>
            <?php
                echo "<a class='loginInput'>Name : </a><a class='detailsText'>" . $row['FirstName'] . " ". $row['Surname'] . "</a><br><br>";
                echo "<a class='loginInput'>Username : </a><a class='detailsText'>" . $row['Username'] . "</a><br><br>";
                echo "<a class='loginInput'>Password : </a><a class='detailsText'>" . $row['Password'] . "</a><br><br>";
                echo "<a class='loginInput'>Address : </a><a class='detailsText'>" . $row['AddressLine1'] . " ". $row['AddressLine2'] . ", " . $row['City'] . ", " . $row['County'] . "</a><br><br>";
                echo "<a class='loginInput'>Telephone / Mobile : </a><a class='detailsText'>" . $row['Telephone'] . " / ". $row['Mobile'] . "</a><br><br>";
            ?>


<!----> <?php } else if ($action == 'update') { ?>

            <style>
                #nav2 {
                    background-color: white;
                    color: #004C6C;
                    font-weight: bold;
                }
            </style>


            <h2>Update Account</h2>

            <?php 

                if ((isset($_POST['Username'])) ||  (isset($_POST['Password'])) || (isset($_POST['FirstName'])) || (isset($_POST['FirstName'])) || (isset($_POST['Surname'])) || (isset($_POST['AddressLine1'])) || (isset($_POST['AddressLine2'])) || (isset($_POST['City'])) || (isset($_POST['County'])) || (isset($_POST['Telephone'])) || (isset($_POST['Mobile'])) ) {
                    // Username
                    if (!empty($_POST['Username'])) {
                        $username = $conn->real_escape_string($_POST['Username']);
                    } else {
                        $username = $row['Username'];
                    }

                    // Password
                    if (!empty($_POST['Password'])) {
                        $pass = $conn->real_escape_string($_POST['Password']);
                    } else {
                        $pass = $row['Password'];
                    }

                    // First name
                    if (!empty($_POST['FirstName'])) {
                        $fname = $conn->real_escape_string($_POST['FirstName']);
                    } else {
                        $fname = $row['FirstName'];
                    }

                    // Surname
                    if (!empty($_POST['Surname'])) {
                        $sname = $conn->real_escape_string($_POST['Surname']);
                    } else {
                        $sname = $row['Surname'];
                    }

                    // Address line 1
                    if (!empty($_POST['AddressLine1'])) {
                        $al1 = $conn->real_escape_string($_POST['AddressLine1']);
                    } else if (empty($row['AddressLine1'])) {
                        $al1 = 'address';
                    } else {
                        $al1 = $row['AddressLine1'];
                    }

                    // Address line 2
                    if (!empty($_POST['AddressLine2'])) {
                        $al2 = $conn->real_escape_string($_POST['AddressLine2']);
                    } else {
                        $al2 = $row['AddressLine2'];
                    }

                    // City
                    if (!empty($_POST['City'])) {
                        $city = $conn->real_escape_string($_POST['City']);
                    } else if (empty($row['City'])) {
                        $city = 'city';
                    } else {
                        $city = $row['City'];
                    }
                    
                    // County
                    if (!empty($_POST['County'])) {
                        $county = $conn->real_escape_string($_POST['County']);
                    } else if (empty($row['County'])) {
                        $county = 'none';
                    } else {
                        $county = $row['County'];
                    }

                    // Telephone
                    if (!empty($_POST['Telephone'])) {
                        $tel = $conn->real_escape_string($_POST['Telephone']);
                    } else if (empty($row['Telephone'])) {
                        $tel = 'none';
                    } else {
                        $tel = $row['Telephone'];
                    }

                    // Mobile
                    if (!empty($_POST['Mobile'])) {
                        $mob = $conn->real_escape_string($_POST['Mobile']);
                    } else if (empty($row['Mobile'])) {
                        $mob = 'none';
                    } else {
                        $mob = $row['Mobile'];
                    }

                    $oldUsername = $row['Username']; // current logged-in username
                    $error = '';

                    // If username changed, check if new one already exists
                    if ($username !== $oldUsername) {
                        $stmt = $conn->prepare("SELECT 1 FROM Users WHERE Username = ?");
                        if (!$stmt) {
                            die("Prepare failed: " . $conn->error);
                        }
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $stmt->store_result();

                        if ($stmt->num_rows > 0) {
                            $error = "User with this username already exists!";
                        }
                        $stmt->close();
                    }

                    if ($error === '') {
                        // Update existing user
                        $stmt = $conn->prepare("UPDATE Users 
                            SET Username = ?, Password = ?, FirstName = ?, Surname = ?, 
                                AddressLine1 = ?, AddressLine2 = ?, City = ?, County = ?, 
                                Telephone = ?, Mobile = ?
                            WHERE Username = ?");

                        if (!$stmt) {
                            die("Prepare failed: " . $conn->error);
                        }

                        $stmt->bind_param(
                            "sssssssssss",
                            $username,
                            $pass,
                            $fname,
                            $sname,
                            $al1,
                            $al2,
                            $city,
                            $county,
                            $tel,
                            $mob,
                            $oldUsername
                        );

                        if ($stmt->execute()) {
                            // Update session if username changed
                            $_SESSION['Users'] = $username;

                            // Redirect back to details page
                            echo '<script>window.location.href="MyAccount.php?action=details";</script>';
                            exit;
                        } else {
                            $error = "Error updating user: " . $stmt->error;
                        }

                        $stmt->close();
                    }

                
                }
            ?>

            <form method="post" action="">

                <label class="loginInput" for="Username">Username:</label>
                <br>
                    <input class="detailsText" type="text" id="Username" name="Username" placeholder="Username">
                <br>

                <label class="loginInput" for="Password">Password:</label>
                <br>
                    <input class="detailsText" type="password" id="Password" name="Password" placeholder="Password">
                <br>
                
                <label class="loginInput" for="FirstName">First name:</label>
                <br>
                    <input class="detailsText" type="text" id="FirstName" name="FirstName" placeholder="First name">
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


                <label class="loginInput" for="Surname">Surname:</label>
                <br>
                    <input class="detailsText" type="text" id="Surname" name="Surname" placeholder="Surname">
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


                <label class="loginInput" for="AddressLine1">Address line 1:</label>
                <br>
                    <input class="detailsText" type="text" id="AddressLine1" name="AddressLine1" placeholder="Address line 1">
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

                
                <label class="loginInput" for="AddressLine2">Address line 2:</label>
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

                
                <label class="loginInput" for="City">City:</label>
                <br>
                    <input class="detailsText" type="text" id="City" name="City" placeholder="City">
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

                
                <p>
                    <select class="loginInput" name="County" id="County">
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
                </p>

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

                
                <label class="loginInput" for="Telephone">Telephone:</label>
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


                <label class="loginInput" for="Mobile">Mobile:</label>
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
                    <br>
                    <span class="buttontext">UPDATE</span>
                    <br><br>
                </button>

            </form>


<!----> <?php } else if ($action == 'reservation') { ?>

            <style>
                #nav3 {
                    background-color: white;
                    color: #004C6C;
                    font-weight: bold;
                }
            </style>

            <?php 

                $page = 1;
                $limit = 5;
                $offset = 0;
                $totalPages = 1;
                $result = null;

                if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
                    $page = intval($_GET["page"]);
                    if ($page < 1) {
                        $page = 1;
                    }
                }

                $offset = ($page - 1) * $limit;

                $countSql = "SELECT COUNT(*) AS total FROM reservations WHERE Username = ?";

                $countStmt = $conn->prepare($countSql);

                if (!$countStmt) {
                    die("Prepare failed: " . $conn->error);
                }

                $countStmt->bind_param("s", $User);
                $countStmt->execute();
                $count_result = $countStmt->get_result();

                $total_rows = 0;
                if ($count_result && $count_result->num_rows === 1) {
                    $row_count = $count_result->fetch_assoc();
                    $total_rows = (int)$row_count["total"];
                }

                if ($total_rows > 0) {
                    $totalPages = ceil($total_rows / $limit);
                }

                $countStmt->close();

                $mainSql = "SELECT b.ISBN, b.BookTitle, b.Author, b.Year, b.Reserved, r.ReservedDate FROM books b INNER JOIN reservations r ON b.ISBN = r.ISBN WHERE r.Username = ? LIMIT ? OFFSET ?";

                $mainStmt = $conn->prepare($mainSql);

                if (!$mainStmt) {
                    die("Prepare failed: " . $conn->error);
                }

                $mainStmt->bind_param("sii", $User, $limit, $offset);
                $mainStmt->execute();
                $result = $mainStmt->get_result();
            ?>

            <h2>My Reservations</h2>

            <?php if ($result && $result->num_rows > 0) { ?>

                <table id="bookTable">

                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Year</th>
                        <th>Reserved</th>
                        <th>Action</th>
                    </tr>

                    <?php while ($row = $result->fetch_assoc()) { ?>
                            
                        <tr>
                            <td><?php echo htmlspecialchars($row['BookTitle']); ?></td>
                            <td><?php echo htmlspecialchars($row['Author']); ?></td>
                            <td><?php echo htmlspecialchars($row['Year']); ?></td>
                            <td><?php echo htmlspecialchars($row['Reserved']); ?></td>
                            <td><a id="returnBook" href="../returnBook.php?ISBN=<?php echo urlencode($row['ISBN']); ?>">Return Book</a></td> 
                        </tr>
                    <?php } ?>
                </table>
            <?php } else {?>

                <p class="text">You have no book reservations</p>
            <?php } ?>

            <br>

            <center>
                <?php
                    if ($totalPages > 1) {
                        for ($i = 1; $i <= $totalPages; $i++) {
                            if ($i == $page) {
                                echo "<span id='currentPage'>" . $i . "</span> ";
                            } else {
                                echo "<a class='pageLink' href='?page=" . $i . "'>" . $i . "</a> ";
                            }
                        }
                    }

                    if (isset($mainStmt) && $mainStmt instanceof mysqli_stmt) {
                        $mainStmt->close();
                    }

                    $conn->close();
                ?>

            </center>


<!----> <?php } else if ($action == 'delete') { ?>


            <style>
                #nav4 {
                    background-color: white;
                    color: #004C6C;
                    font-weight: bold;
                }
            </style>

            <h2>Delete My Account</h2>

            <br><br>

            <p class="text">Delete all data related to your account</p>

            <br><br><br><br>

            <form type="submit" action="Delete.php">
                <button class="button" type="submit">
                    <br>
                    <span class="buttontext">DELETE ACCOUNT</span>
                    <br><br>
                </button>
            </form>

<!----> <?php } ?>
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