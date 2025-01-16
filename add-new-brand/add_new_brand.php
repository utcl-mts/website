<?php

##TODO

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['brand'])) {
            try {
                $sql = "INSERT INTO brand (brand_name) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $_POST['brand'], PDO::PARAM_STR);
                $stmt->execute();
                echo "Brand successfully added!";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Please fill in the brand name.";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <link rel="stylesheet" href="../style.css">
        <title>Med Tracker</title>

    </head>

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
                    <li class="logout"><a href="../logout.php">Logout</a></li>

                </ul>

            </div>

            <form method="post" action="">

                <table>

                    <tr>

                        <td><label for="brand">Brand Name: </label></td>
                        <td><input type="text" id="brand" name="brand" placeholder="Enter Brand Name" required></td>

                    </tr>

                </table>

                <button type="submit">Submit</button>

            </form>

        </div>

    </body>

</html>