<?php
session_start();

include "../server/check_cookie_admin.php";
require "../server/db_connect.php";
require "../server/audit-log.php";

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$staff_code = $_POST['staff_code'];
$email = $_POST['email'];
$password = $_POST['password'];
$c_password = $_POST['c_password'];
$group = $_POST['group'];

if($password!=$c_password){
    header("refresh:5; url=create_user_form.php");
    echo '<br>';
    echo"Your passwords do not match";
} else {
    try {
        $sql = "SELECT email FROM staff WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1,$email);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result){
            header("refresh:5; url=create_user_form.php");
            echo '<br>';
            echo "An Account with this email already exists. Try again!";

        } else {
            try {
                $hpswd = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO staff (first_name, last_name, staff_code, email, password, `group`) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1,$first_name);
                $stmt->bindParam(2,$last_name);
                $stmt->bindParam(3,$staff_code);
                $stmt->bindParam(4,$email);
                $stmt->bindParam(5,$hpswd);
                $stmt->bindParam(6,$group);

                $stmt->execute();
                header("refresh:5; url=staff_home.php");
                echo '<br>';
                $staff_id = $_SESSION['staff_id'];
                $s_staff_code = $_SESSION['staff_code'];
                $action = "$s_staff_code created staff $first_name, $last_name, $staff_code, $email, $group";

                logAction($conn, $staff_id, $action);

                echo "Successfully registered";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }

        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}