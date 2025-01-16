<link rel="stylesheet" href="../style.css">
<body>
<div class="container">

    <!-- universal nav bar-->
    <div class="navbar">

        <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">

        <h1 id="med_tracker">Med Tracker</h1>

        <ul>
            <li><a href="../dashboard/dashboard.php">Home</a></li>
            <li><a href="../insert_data/insert_data_home.php">Insert Data</a></li>
            <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
            <li><a href="../administer/administer.html">Administer Medication</a></li>
            <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
            <li><a href="../student_profile/student_profile.php">Student Profile</a></li>
            <li class="logout"><a>Logout</a></li>
        </ul>

    </div>

    <div class="searchbar">
        <form method="GET" action="">
            <input
                    id="search-input"
                    type="text"
                    name="student_name"
                    placeholder="Enter student name"
                    value="<?php echo htmlspecialchars(isset($_GET['student_name']) ? $_GET['student_name'] : ''); ?>"
            >
            <button id="search-button" type="submit">Search</button>
        </form>
    </div>

    <?php

    session_start();

    // Check for valid session and cookie
    if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
        header("Location: ../index.html");
        exit();
    }

    // Include the database connection file
    include "../server/db_connect.php";

    if (isset($_GET['student_name']) && !empty(trim($_GET['student_name']))) {
        $student_name = trim($_GET['student_name']);

        try {
            // SQL query to retrieve students matching the name
            $sql = "SELECT student_id, CONCAT(first_name, ' ', last_name) AS full_name, year 
                    FROM students 
                    WHERE CONCAT(first_name, ' ', last_name) LIKE :student_name";

            $stmt = $conn->prepare($sql);
            $search_param = '%' . $student_name . '%';
            $stmt->bindParam(':student_name', $search_param, PDO::PARAM_STR);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<div id='student_data'>";
            if ($results) {
                echo "<h2>Select a Student</h2>";
                echo "<form method='POST' action=''>";
                echo "<select name='selected_student' required>";
                echo "<option value=''>--Select a Student--</option>";
                foreach ($results as $row) {
                    $full_name = htmlspecialchars($row['full_name']);
                    $student_id = htmlspecialchars($row['student_id']);
                    echo "<option value='$student_id'>$full_name (Year: " . htmlspecialchars($row['year']) . ")</option>";
                }
                echo "</select>";
                echo "<button type='submit' name='view_student'>View Student</button>";
                echo "</form>";
            } else {
                echo "<p>No records found for the given student name.</p>";
            }
            echo "</div>";

        } catch (PDOException $e) {
            die("<p class='error'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>");
        }
    } else {
        echo "<p>Please enter a student name to search.</p>";
    }

    // Display selected student's data and medication records
    if (isset($_POST['view_student']) && !empty($_POST['selected_student'])) {
        $student_id = $_POST['selected_student'];

        try {
            $sql = "SELECT students.first_name, students.last_name, students.year, med.med_name, brand.brand_name, takes.current_dose, takes.exp_date
                    FROM students 
                    LEFT JOIN takes ON students.student_id = takes.student_id 
                    LEFT JOIN med ON takes.med_id = med.med_id 
                    LEFT JOIN brand ON takes.brand_id = brand.brand_id 
                    WHERE students.student_id = :student_id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();

            $student_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($student_data) {
                $full_name = htmlspecialchars($student_data[0]['first_name'] . ' ' . $student_data[0]['last_name']);
                $year = htmlspecialchars($student_data[0]['year']);
                echo "<h2>Details for $full_name (Year: $year)</h2>";
                echo "<table id='student_table'>";
                echo "<tr><th>Medication</th><th>Brand</th><th>Current Dose</th><th>Expiry Date</th></tr>";
                foreach ($student_data as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['med_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['brand_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['current_dose']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['exp_date']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No medication records found for this student.</p>";
            }

        } catch (PDOException $e) {
            echo "<p class='error'>Failed to retrieve student data: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    ?>
</div>
</body>
