<?php

    // session start to be able to use session variables
    session_start();

    //include database connection
    include '../server/db_connect.php';

    //gets info from form on html page
    $dose = $_POST['dose'];
    $staff_code = $_POST['staff_code'];
    $time = $_POST['time'];

    //puts informaition into administer database
    $sql = "INSERT INTO administer (staff_code,date-time,dose_given) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);

    //bind parameters so that it is more secure of passing the informaitiomn
    $stmt->bindParam(1, $staff_code);
    $stmt->bindParam(2, $time);
    $stmt-> bindParam(3, $dose);

    $stmt->execute();

?>