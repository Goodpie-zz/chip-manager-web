<?php
session_start();
require_once('php/connect.php');

// Error message
$message = "Please fill in the form below";

$config = parse_ini_file("config.ini");
$address = $config['address'];
echo "$address";

// Checks if the user has already logged in, redirects to correct page
if (isset($_SESSION['logged_in']) == true && isset($_SESSION['id'])) {

    header("Location: http://$address/bid_management.php");
}

if (!empty($_POST)) {

    echo "Getting connection...<br/>";
    // Open connection
    $connection = getConnection("config.ini");

    // Retrieve POST variables
    $user = strtoupper($_POST['user']);
    $password = $_POST['password'];

    // SQL queries to check if the player actually exists
    $validate_user_query = "SELECT * FROM `player` WHERE `username`='$user'";
    $result = mysqli_query($connection, $validate_user_query);
    $row = $result->fetch_assoc();

    // If the user exists, check password and start a session
    if ($row != NULL) {
        if ($row['password'] == $password) {
            $id = $row['ID'];
            $_SESSION['id'] = $id;
            $_SESSION['logged_in'] = true;
            header("Location: http://$address/bid_management.php"); // Redirect to bid page
        } else {
            // Change message to tell use that they entered the wrong password
            $message = "Invalid password. Please try again!";
        }
    } else {
        $message = "Please try again!";
    }

    // Close connection
    $connection->close();
}
?>

<!DOCTYPE HTML>

<html>
<head>
    <title>Create a user</title>
    <meta charset="utf-8">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,100,900' rel='stylesheet' type='text/css'>
    <!-- Lato Light, Normal, Bold -->
    <link rel="stylesheet" id="form_style" href="css/form_desktop.css">
</head>

<body>
<div id="main_container">
    <div id="form_parent_container" class="login">
        <!-- Displayer basic information about signing up -->
        <div id="form_header">
            <h2>Login</h2>
            <p>Login to access the main game</p>
            <p style="color:#fd625e"><?php echo $message; ?></p>
        </div>
        <div id="form_fields">
            <form method="POST" action="login.php">
                <input type="text" name="user" placeholder="Username (Initials)" maxlength="4"><br/>
                <input type="password" name="password" placeholder="Password" maxlength="20"><br/>
                <input type="submit" id="login_submit" value="Login">
            </form>
        </div>
    </div>
</div>
<script src="js/jquery.js"></script>
<script src="js/form_handling.js"></script>
</body>
</html>