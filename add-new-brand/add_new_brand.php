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

            <form>

                <table>

                    <tr>

                        <td><label for="brand">Brand Name: </label></td>
                        <td><input type="text" id="brand" placeholder="Enter Brand Name" required></td>

                    </tr>


                </table>

            </form>

        </div>

    </body>

</html>

<?php

    $sql = "INSERT INTO brand VALUES =?";

    $stmt = $conn->prepare($sql);
    $stmt -> bindParam(1, $_POST['brand']);
    $stmt -> execute();

?>