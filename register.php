<?php

require_once(__DIR__ . "/php/Helpers.php");

$address = Helpers::get_address();

$error_messages = array(
    'empty_form' => 'Please fill in the form below',
    'existing_user' => 'Sorry, this user already exists',
);

$error_message = $error_messages['empty_form'];

if (!empty($_POST)) {
    // Open connection
    $connection = Helpers::get_connection();

    // Retrieve POST parameters
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Checks if the user already exists
    $check_username_query = "SELECT * FROM `player` WHERE `username`=?";
    $user_statement = $connection->prepare($check_username_query);
    $user_statement->bind_param('s', $username);
    $user_statement->execute();
    $user_result = $user_statement->get_result();

    $row = $user_result->fetch_array(MYSQLI_ASSOC);

    // Free memory
    $user_statement->free_result();
    $user_statement->close();

    // IF the user doesn't exists, create the player
    if (!$row) {

        // Prepare and execute query
        $query = "INSERT INTO `player` (`username`, `password`, `chips`, `current_bid`) VALUES (?, ?, 100, 0)";
        $statement = $connection->prepare($query);
        $statement->bind_param('ss', $username, $password);
        $statement->execute();

        // Redirect to login page once successful
        header("Location: http://$address/login.php");
    } else {
        // Username already exists
        $error_message = $error_messages['existing_user'];
    }

    // Close connection
    $connection->close();
}

?>

<!-- Creates a user and adds it to the database using Javascript -->

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Create a user</title>

    <!-- Import style sheet -->
    <link rel="stylesheet" id="form_style" href="style/form_desktop.css">

    <!-- Import Google Fonts (Lato) -->
    <link href='https://fonts.googleapis.com/css?family=Lato:400,100,900' rel='stylesheet' type='text/css'>

</head>

<body>
<div id="main_container">
    <div id="form_parent_container" class="registration">
        <!-- Displayer basic information about signing up -->
        <div id="form_header">
            <h2>Register a Player</h2>
            <p>Register a player and start of with 500 chips!</p>
            <p style="color:#fd625e"><?php echo $error_message; ?></p>
        </div>
        <!-- Form for the user to fill out to create their account -->
        <div id="form_fields">
            <form method="POST" action="register.php">
                <input type="text" name="username" placeholder="Username" maxlength="20"><br/>
                <input type="password" name="password" placeholder="Password" maxlength="20"><br/>
                <input type="submit" id="register_submit" value="Register">
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="js/mobile_dection.js"></script>
</body>
</html>