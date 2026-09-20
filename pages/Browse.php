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
?>
<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <title>Library:Browse</title>
    <meta name="viewport">
    <link rel="stylesheet" href="../libraryCSS/styles.css">
    <link rel="stylesheet" href="../libraryCSS/background.css">
    <link rel="icon" href="../media/icon.png">
</head>

<body>
    <header>
        <div class="top-nav-bar">

            <a class="heading" href="Index.php">Home</a>
            <a class="heading active" href="Browse.php">Browse Books</a>
            <a class="heading" href="Logout.php">Logout</a>
            <?php
                $sql = "SELECT Username, Password, FirstName, Surname, AddressLine1, AddressLine2, City, County, Telephone, Mobile FROM users WHERE Username='$User'";
                $result = $conn->query($sql);

                if ($result && $row = $result->fetch_assoc()) {
                    echo '<a id="MyAccount" class="heading" href="MyAccount.php"><img src="../media/accountIcon.png" style="width:1.3%;height:1.3%;" alt="Account icon"> My Account : ' . $row["FirstName"] . '</a>';
                }
            ?>
        </div>

        <a id="header">Library<img src="../media/headerIcon.png" style="width:3%;height:3%;" alt="Library logo"></a>

    </header>

    <?php

        $search = "";
        $category = "";
        $page = 1;
        $limit = 5;
        $offset = 0;
        $totalPages = 1;
        $result = null;

        if ($_SERVER["REQUEST_METHOD"] === "GET") {

            if (isset($_GET["search"]) && $_GET["search"] !== "") {
                $search = trim($_GET["search"]);
            }

            if (isset($_GET["category"]) && $_GET["category"] !== "") {
                $category = $_GET["category"];
            }

            if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
                $page = intval($_GET["page"]);
                if ($page < 1) {
                    $page = 1;
                }
            }

            $where = " WHERE 1=1 ";
            $params = array();
            $types = "";

            if ($search !== "") {
                $where .= " AND (BookTitle LIKE ? OR Author LIKE ?) ";
                $params[] = "%" . $search . "%";
                $params[] = "%" . $search . "%";
                $types .= "ss";
            }

            if ($category !== "") {
                $where .= " AND Category = ? ";
                $params[] = $category;
                $types .= "s";
            }

            $count_sql = "SELECT COUNT(*) AS total FROM books" . $where;
            $count_stmt = $conn->prepare($count_sql);

            if (!$count_stmt) {
                die("Prepare failed: " . $conn->error);
            }

            if (!empty($params)) {
                $count_stmt->bind_param($types, ...$params);
            }

            $count_stmt->execute();
            $count_result = $count_stmt->get_result();

            $total_rows = 0;
            if ($count_result && $count_result->num_rows === 1) {
                $row_count = $count_result->fetch_assoc();
                $total_rows = (int)$row_count["total"];
            }

            if ($total_rows > 0) {
                $totalPages = (int)ceil($total_rows / $limit);
            }

            // Keep manually entered page numbers within the available range.
            if ($page > $totalPages) {
                $page = $totalPages;
            }
            $offset = ($page - 1) * $limit;

            $count_stmt->close();

            $main_sql = "SELECT * FROM books" . $where . " LIMIT ? OFFSET ?";
            $main_stmt = $conn->prepare($main_sql);

            if (!$main_stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $types2 = $types . "ii";
            $params2 = $params;
            $params2[] = $limit;
            $params2[] = $offset;

            $main_stmt->bind_param($types2, ...$params2);
            $main_stmt->execute();
            $result = $main_stmt->get_result();
        }
    ?>

    <div id="register">
        <h1>Search Books</h1>

        <form method="GET">
            <section class="searchGrid">
                <div>
                    <label class="loginInput" for="search">Search:</label>
                    <br>
                    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>">

                </div>
                <div>

                    <label class="loginInput" for="category">Category:</label>
                    <br>
                    <select id="category" name="category">
                        <option value="">All</option>

                        <?php
                            $categories = $conn->query("SELECT * FROM category");

                            if ($categories) {
                                while ($c = $categories->fetch_assoc()) {
                                    $categoryID = $c['CategoryID'];
                                    $categoryDesc = htmlspecialchars($c['CategoryDescription']);

                                    echo "<option value='" . htmlspecialchars($categoryID) . "'";
                                    if ($category == $categoryID) {
                                        echo " selected";
                                    }
                                    echo ">" . $categoryDesc . "</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
            </section>

            <br>

            <button class="button" type="submit">
                <a class="buttontext">Search </a>
            </button>
        </form>
    </div>

    <br>

    <table id="bookTable">

        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>Reserved</th>
            <th>Status</th>
        </tr>

        <?php
            if ($result) {
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['BookTitle']); ?></td>
                        <td><?php echo htmlspecialchars($row['Author']); ?></td>
                        <td><?php echo htmlspecialchars($row['Year']); ?></td>
                        <td><?php echo htmlspecialchars($row['Reserved']); ?></td>
                        <td>
                            <?php if ($row['Reserved'] === 'N') { ?>
                                <a id="available" href="../reserve.php?ISBN=<?php echo urlencode($row['ISBN']); ?>">Available</a>
                            <?php } else { ?>
                                <a id="unavailable">Unavailable</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php }
            }
        ?>
    </table>

    <br>

    <center>
        <?php
            if ($totalPages > 1) {
                $pageUrl = function ($pageNumber) use ($search, $category) {
                    return '?search=' . urlencode($search)
                        . '&category=' . urlencode($category)
                        . '&page=' . $pageNumber;
                };

                // Show at most three consecutive page numbers, centred on the current page.
                $startPage = max(1, min($page - 1, $totalPages - 2));
                $endPage = min($totalPages, $startPage + 2);

                echo "<a id='searchPages' href='" . $pageUrl(1) . "' aria-label='Go to first page'>&laquo;</a> ";

                if ($page > 1) {
                    echo "<a id='searchPages' href='" . $pageUrl($page - 1) . "' aria-label='Go to previous page'>&lsaquo;</a> ";
                }

                for ($i = $startPage; $i <= $endPage; $i++) {
                    if ($i == $page) {
                        echo "<span id='currentSearchPage'>" . $i . "</span> ";
                    } else {
                        echo "<a id='searchPages' href='" . $pageUrl($i) . "'>" . $i . "</a> ";
                    }
                }

                if ($page < $totalPages) {
                    echo "<a id='searchPages' href='" . $pageUrl($page + 1) . "' aria-label='Go to next page'>&rsaquo;</a> ";
                }

                echo "<a id='searchPages' href='" . $pageUrl($totalPages) . "' aria-label='Go to last page'>&raquo;</a>";
            }

            if (isset($main_stmt) && $main_stmt instanceof mysqli_stmt) {
                $main_stmt->close();
            }

            $conn->close();
        ?>
    </center>


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
