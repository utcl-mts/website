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
    <title>Hours Tracking - Add New Med</title>
    <link rel="stylesheet" href=../assets/style/style.css>
</head>
<body class="full_page_styling">
<br>

<div>
    <ul class="nav_bar">
        <div class="nav_left">
            <li class="navbar_li"><a href="medication_management.php">View All Medication</a></li>
            <li class="navbar_li"><a class='active' href="add_new_med.php">Create New Medication</a></li>
            <li class="navbar_li"><a href="export_meds.php">Export All Medication</a></li>
        </div>
    </ul>
</div>
<div>
    </div>
    <h1>Create new Medicine</h1>

            <form method="post" action="">
                <div class='text-element'>Enter med name</div>
                <div class='text-element-faded'>Example: Paracetamol</div>
                <input class="text_input" type="text" id="medication" name="medication" required>
                <br><br>
                <button class="submit" type="submit">Submit</button>
            </form>
            <br><br>
            <a class="back_link" href="brand_management.php">< Go Back</a>
        </div>
        
    </body>

</html>


<?php
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!empty($_POST['medication'])) {

            try {

                $sql = "INSERT INTO med (med_name) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $_POST['medication'], PDO::PARAM_STR);
                $stmt->execute();
                echo "<br><br>";
                echo '<div class="success-banner">';
                    echo '<div class="success-header">';
                            echo '<h2>Success</h2>';
                    echo '</div>';
                    echo '<div class="success-content">';
                        echo '<p>Medication successfully added!</p>';
                    echo '</div>';
                echo '</div>';

                $staff_id = $_SESSION['staff_id'];
                $staff_code = $_SESSION['staff_code'];
                $action = "$staff_code created $_POST[medication]";
                $source = "Add New Medication";

                logAction($conn, $staff_id, $action, $source);

                header("refresh:10; url=medication_management.php");

            } catch (PDOException $e) {

            echo "Error: " . $e->getMessage();

            }
        } else {

        echo "Please fill in the medication name.";

        }

    }

?>