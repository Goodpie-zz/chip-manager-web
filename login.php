<?php
session_start();

require("php/Helpers.php");
require("php/Player.php");

// Get the local address
$address = Helpers::get_address();

// Error message
$error_messages = array(
    'default' => "Please fill in the form below",
    'invalid_credentials' => "Invalid username/password. Please try again!",
    'unknown_user' => "User does not exist. Please <a href='http://$address/register.php'>register!</a>"
);

// Set the default error message
$error_message = $error_messages['default'];

if (isset($_SESSION[Helpers::LOGGED_IN]) == true && isset($_SESSION[Helpers::PLAYER_SESSION])) {
    // User already logged in, redirect to game page
    header("Location: http://$address/play.php");
}

if (!empty($_POST)) {

    // Establish connection
    $connection = Helpers::get_connection();

    // Retrieve POST variables
    $user = strtoupper($_POST['user']);
    $password = $_POST['password'];

    // Check if player exists
    $query = "SELECT * FROM `player` WHERE `username`=? LIMIT 1";
    $statement = $connection->prepare($query);
    $statement->bind_param('s', $user);
    $statement->execute();
    $result = $statement->get_result();

    $row = $result->fetch_array(MYSQLI_ASSOC);

    // Free memory
    $statement->free_result();
    $statement->close();

    // If the user exists, check password and start a session
    if ($row != NULL) {
        if ($row['password'] == $password) {
            // Fetch and update the player
            $player = new Player($row['ID']);
            $player->update();
            $player->set_connection(1);

            // Set session variables
            $_SESSION[Helpers::PLAYER_SESSION] = $player;
            $_SESSION[Helpers::LOGGED_IN] = true;

            // Redirect the user to the game page
            header("Location: http://$address/play.php");
        } else {
            // Change message to tell use that they entered the wrong password
            $error_message = $error_messages['invalid_credentials'];
        }
    } else {
        $error_message = $error_messages['unknown_user'];
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
            <p style="color:#fd625e"><?php echo $error_message; ?></p>
        </div>
        <div id="form_fields">
            <form method="POST" action="login.php">
                <input type="text" name="user" placeholder="Username (Initials)" maxlength="32"><br/>
                <input type="password" name="password" placeholder="Password" maxlength="20"><br/>
                <input type="submit" id="login_submit" value="Login">
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="js/form_handling.js"></script>
</body>
</html>