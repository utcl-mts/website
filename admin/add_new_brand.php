<?php


session_start();
include "../server/check_cookie_admin.php";
include "../server/db_connect.php";
include "../server/audit-log.php";
include "../server/navbar/admin_dashboard.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Add new Brand</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class="full_page_styling">
<br>

<div>
    <ul class="nav_bar">
        <div class="nav_left">
            <li class="navbar_li"><a href="brand_management.php">View All Brands</a></li>
            <li class="navbar_li"><a class='active' href="add_new_brand.php">Create New Brand</a></li>
            <li class="navbar_li"><a href="export_brands.php">Export All Brand</a></li>
        </div>
    </ul>
</div>

    <h1>Add new brand</h1>
            <form method="post" action="">
                    <div class='text-element'>Enter brand name</div>
                    <div class='text-element-faded'>Example: Tesco</div>
                    <input class="text_input" type="text" id="brand" name="brand" required></td>
                    <br><br>
                <button class="submit" type="submit">Submit</button>
            </form>
    </body>
    <br><br>
    <a class="back_link" href="brand_management.php">< Go Back</a>
</html>

<?php

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['brand'])) {
            try {
                $sql = "INSERT INTO brand (brand_name) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $_POST['brand'], PDO::PARAM_STR);
                $stmt->execute();
                echo "<br><br>";
                echo '<div class="success-banner">';
                    echo '<div class="success-header">';
                        echo '<h2>Success</h2>';
                echo '</div>';
                echo '<div class="success-content">';
                    echo '<p>Brand sucessfully added</p>';
                echo '</div>';
                echo '</div>';

                $staff_id = $_SESSION['staff_id'];
                $staff_code = $_SESSION['staff_code'];
                $brand_name = $_POST['brand'];
                $action = "$staff_code created $brand_name";

                logAction($conn, $staff_id, $action);

                header("refresh:10; url=brand_management.php");

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Please fill in the brand name.";
        }
    }

?>