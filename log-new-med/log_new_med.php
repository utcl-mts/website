<?php

    // Start a new session or connect to an existing one
    session_start();

    // Include the database connection file
    include "../server/db_connect.php";

?>

<link rel="stylesheet" href="../assets/style/style.css">
<body class="full_page_styling">
<title>Hours Tracking - Create a new med</title>
<div>
    <div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="../dashboard/dashboard.php">Home</a></li>
                <li class="navbar_li"><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
                <li class="navbar_li"><a href="../bigtable/bigtable.php">Student Medication</a></li>
<!--                <li class="navbar_li"><a href="../administer/administer_form.php">Administer Medication</a></li>-->
                <li class="navbar_li"><a href="../log/log_form.php">Create Notes</a></li>
                <li class="navbar_li"><a href="../whole_school/whole_school_table.php">Whole School Medication</a></li>
                <li class="navbar_li"><a href="../student_profile/student_profile.php">Student Profile</a></li>
                <li class="navbar_li"><a href="../edit_details/student_table.php">Student Management</a></li>
            </div>
            <div class="nav_left">
                <li class="navbar_li"><a href="../admin/admin_dashboard.php">Admin Dashboard</a></li>
                <li class="navbar_li"><a href="../logout.php">Logout</a></li>
            </div>
        </ul>
    </div>

    <h1>Create a new med</h1>

            <?php
                // Check if student_id is provided
                if (isset($_POST['student_id'])) {
                    $student_id = htmlspecialchars($_POST['student_id']); // Sanitize input

                    // Fetch student details
                    try {

                        $sql = 'SELECT first_name, last_name FROM Students WHERE student_id = ?';
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(1, $student_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($result) {

                            $fn = htmlspecialchars($result['first_name']); //Used htmlspecialchars to sanitize inputs.
                            $ln = htmlspecialchars($result['last_name']);

                        } else {

                            echo "<p>Student not found.</p>";
                            exit();
                        }

                    } catch (PDOException $e) {

                        echo "<p>Error fetching student details: " . $e->getMessage() . "</p>";
                        exit();
                    }

                    // Fetch medication names
                    try {

                        $sql = "SELECT med_name FROM med";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $meds = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {

                        echo "<p>Error fetching medications: " . $e->getMessage() . "</p>";
                        exit();
                    }

                    // Fetch brand names
                    try {

                        $sql = "SELECT brand_name FROM brand";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    } catch (PDOException $e) {

                        echo "<p>Error fetching brands: " . $e->getMessage() . "</p>";
                        exit();
                    }

                    echo "<form method='post' action='log_new_med_audit.php' id='add_new_med_form'>";

                    echo "<input type='hidden' name='student_id' value='$student_id'>";
                    echo "<label class='text-element' for='meds'>Medication:</label>";
                    echo "<br><br>";
                    echo "<select name='meds' id='meds' required>";
                    echo "<option value=''>Select a Med</option>";
                    

                    foreach ($meds as $med) {

                        echo "<option value='" . htmlspecialchars($med['med_name']) . "'>" . htmlspecialchars($med['med_name']) . "</option>";
                    }
                    echo "</select>";
                    echo "<br><br>";
                    echo "<label class='text-element' for='brand'>Brand:</label>";
                    echo "<br><br>";
                    echo "<select name='brand' id='brand' required>";
                    echo "<option value=''>Select a Brand</option>";
                    foreach ($brands as $brand) {

                        echo "<option value='" . htmlspecialchars($brand['brand_name']) . "'>" . htmlspecialchars($brand['brand_name']) . "</option>";
                    }
                    echo "</select>";
                    echo "<br><br>";
                    echo "<div class='text-element'>Enter the max dose</div>
                        <div class='text-element-faded'>Example: 4</div>";
                    echo "<input class='smaller_int_input' type='number' name='max_dose' id='max_dose' min='0' required>";
                    echo "<br><br>";
                    echo "<div class='text-element'>Enter the min dose</div>
                    <div class='text-element-faded'>Example: 1</div>";
                    echo "<input class='smaller_int_input' type='number' name='min_dose' id='min_dose' min='0' required>";
                    echo "<br><br>";
                    echo "<div class='text-element'>Enter the current dose</div>
                    <div class='text-element-faded'>Example: 16</div>";
                    echo "<input class='smaller_int_input' type='number' name='current_dose' id='current_dose' min='0' required>";
                    echo "<br><br>";
                    echo "<div class='text-element'>Enter the expiry date</div>
                    <div class='text-element-faded'>Example: 12/05/2025</div>";
                    echo "<input class='text_input' type='date' name='expiry' id='expiry' required>";
                    echo "<br><br>";
                    echo "<div class='text-element'>Enter the strength</div>
                    <div class='text-element-faded'>Example: 50</div>";
                    echo "<input class='smaller_int_input' type='number' name='strength' id='strength' min='0' required>";
                    echo "<br><br>";
                    echo "<input class='submit' type='submit' value='Create Log'>";

                    echo "</form>";

                } else {

                    echo "<p>No student ID provided.</p>";
                }

            ?>

        </div>

    </body>

</html>