<?php
function root_checker(){
    include 'db_connect.php';//gets the select user details to check if admin exists or not

    $sql = "SELECT * FROM staff WHERE `group` != 'system'"; // SQL to exclude 'system' users
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a result
    if($result){
        return true;
    } else {
        return false;
    }
}