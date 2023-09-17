<?php

include("database-actions/db-connection.php");

$alert = "";
if (isset($_POST["delete-user"])) {
    $id = $_GET["delete"];

    // ======================================
    $query_user = "SELECT * FROM users WHERE id=$id";
    $user = mysqli_query($conn, $query_user);
    $row = mysqli_fetch_assoc($user);

    $old_image = $row["image"];
    // ======================================

    $query_delete = "DELETE FROM users WHERE id=$id";
    $user_delete = mysqli_query($conn, $query_delete);

    if (isset($user_delete)) {
        $alert = "User deleted successfully";
    } else {
        $alert = "Error, can't delete user";
    }

    date_default_timezone_set("Africa/Cairo");
    setcookie("category_alert", $alert, time()+3);

    header("Location: all-users.php");
    
}