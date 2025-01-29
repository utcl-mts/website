<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style/style.css">
    <title>Hours Tracking - Login</title>
</head>
<body class="home">
<div>
    <h1 class="title">UTC Leeds Medical Tracker</h1>
    <form action="login/login.php" method="post">
        <div class='text-element'>Enter email *</div>
        <div class='text-element-faded'>user.user@utcleeds.co.uk</div>
        <input class="text_input" type="text" id="email" name="email" required>
        <br><br>
        <div class='text-element'>Enter password *</div>
        <input class="text_input" type="password" id="password" name="password" required>
        <br><br>
        <input class="submit" type="submit" value="Log In">
    </form> 

    <br><br>

    <?php
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    $error_message = "";

    switch ($error) {
        case 'system_account':
            $error_message = "System accounts cannot be used to log in.";
            break;
        case 'account_archived':
            $error_message = "Your account has been archived. Please contact support for assistance.";
            break;
        case 'invalid_credentials':
            $error_message = "Invalid email or password. Please try again.";
            break;
        case 'cookie_error':
            $error_message = "Your cookie has expired please login again.";
            break;
        case 'unknown_error':
            $error_message = "An unexpected error occurred. Please try again later.";
            break;
        case 'no_access':
            $error_message = "You can't access this page, Please contact support for assistance.";
            break;        
        default:
            $error_message = "An unknown error has occured, Please contact site administrators";
            break;
    }

    echo '<div class="error-banner">';
    echo '<div class="error-header">';
    echo '<h2>Error</h2>';
    echo '</div>';
    echo '<div class="error-content">';
    echo "<div>$error_message</div>";
    echo '</div>';
    echo '</div>';
}
?>

</div>
</body>
</html>