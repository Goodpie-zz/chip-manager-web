<?php
session_start();
require_once('php/connect.php');
$connection = getConnection("config.ini");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = $_SESSION['id'];
    unset($_SESSION['id']);
    unset($_SESSION['loggin_in']);
}

$connection->query("UPDATE `player` SET `connected`=0 WHERE `ID` =$id");


?>