<?php
// Get connection
require_once('php/connect.php');

$message = "Please fill in the form below";

if (!empty($_POST)) {
    // Open connection
    $connection = getConnection("config.ini");

    // Retrieve POST parameters
    $name = $_POST['name'];
    $user = strtoupper($_POST['user']);
    $password = $_POST['password'];

    // Checks if the user already exists
    $check_username_query = "SELECT * FROM `player` WHERE `username`='$user'";
    $user_result = mysqli_query($connection, $check_username_query);
    $row = $user_result->fetch_assoc();

    // IF the user doesn't exists, create the player
    if ($row == NULL) {
        $query = "INSERT INTO `player` (`username`, `password`, `name`) VALUES ('$user', '$password', '$name')";
        $connection->query($query);
    } else {
        $message = "Sorry, that username already exists";
    }

    // Close connection
    $connection->close();
}

?>

<!-- Creates a user and adds it to the database using Javascript -->

<!DOCTYPE HTML>
<html>
<head>
    <title>Create a user</title>
    <meta charset="utf-8">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,100,900' rel='stylesheet' type='text/css'>
    <!-- Lato Light, Normal, Bold -->
    <link rel="stylesheet" href="css/register_style.css">
</head>

<body>
<div id="main_container">
    <div id="form_parent_container" class="registration">
        <!-- Displayer basic information about signing up -->
        <div id="form_header">
            <h2>Register a Player</h2>
            <p>Register a player and start of with 500 chips!</p>
            <p style="color:#fd625e"><?php echo $message; ?></p>
        </div>
        <!-- Form for the user to fill out to create their account -->
        <div id="form_fields">
            <form method="POST" action="create_player.php">
                <input type="text" name="name" placeholder="Name" maxlength="20"><br/>
                <input type="text" name="user" placeholder="Username (Initials)" maxlength="4"><br/>
                <input type="password" name="password" placeholder="Password" maxlength="20"><br/>
                <input type="submit" id="register_submit" value="Register">
            </form>
        </div>
    </div>
</div>
</body>
</html>