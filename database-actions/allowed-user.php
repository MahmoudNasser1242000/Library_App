<?php

include("database-actions/db-connection.php");

$alert = "";
date_default_timezone_set("Africa/Cairo");

if (isset($_POST["check-allow"])) {
    $id = $_GET["allow"];

    // ========================================
    $query_user = "SELECT * FROM users WHERE id=$id";
    $user = mysqli_query($conn, $query_user);
    $row = mysqli_fetch_assoc($user);

    $allow = $row["allow"];
    // ========================================

    if ($allow === "true") {
        $allow_update = "false";
        $alert = "User blocked successfully";

        setcookie("allow_user$row[token]", time() + 60*60*24);
    } else {
        $allow_update = "true";
        $alert = "User unblocked successfully";

        setcookie("allow_user$row[token]", "", time() - 1);
    }

    $query_update = "UPDATE users SET allow='$allow_update' WHERE id=$id";
    $user_update  = mysqli_query($conn, $query_update);

    setcookie("admin_alert", $alert, time() + 3);

    header("Location: all-users.php");
}
