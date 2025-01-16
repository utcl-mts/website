<?php

##TODO

?>

<!DOCTYPE html>
<html lang="en">

    <link rel="stylesheet" href="../style.css">

    <body>

        <div class="container">

            <div class="navbar">

                <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">
                <h1 id="med_tracker">Med Tracker</h1>

                <ul>

                    <li><a href="../dashboard/dashboard.php">Home</a></li>
                    <li><a href="../insert_data/insert_data.php">Insert Data</a></li>
                    <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
                    <li><a href="../administer/administer.html">Administer Medication</a></li>
                    <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
                    <li class="logout"><a>Logout</a></li>

                </ul>

            </div>

        </div>

    </body>

</html>

<?php

    $student_id = $_SESSION['student_id'];

    // Fetch student details
    $sql = 'SELECT first_name, last_name from Students where student_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt -> bindParam(1, $student_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $fn = $result['first_name'];
    $ln = $result['last_name'];


?>


