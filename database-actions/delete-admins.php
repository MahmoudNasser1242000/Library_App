<?php

include("database-actions/db-connection.php");

$alert = "";
if (isset($_POST["delete-admin"])) {
    $id = $_GET["delete"];

    // ===============================================
    $query_admins = "SELECT * FROM admins WHERE id=$id";
    $admin = mysqli_query($conn, $query_admins);
    $row = mysqli_fetch_assoc($admin);

    $old_image = $row["image"];
    // ===============================================

    $query_delete = "DELETE FROM admins WHERE id=$id";
    $delete_admin = mysqli_query($conn, $query_delete);

    if (isset($delete_admin)) {
        $alert = "Admin deleted successfully";

        if (!empty($old_image)) {
            unlink("uploads/admins-images/$old_image");
        }
    } else {
        $alert = "Error, can't delete admin";
    }
    
    date_default_timezone_set("Africa/Cairo");
    setcookie("admin_alert", $alert, time()+3);

    header("Location: #");
}