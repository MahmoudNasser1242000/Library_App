<?php

// include("./db-connection.php");

// if (isset($_GET["index"])) {
//     $id = $_GET["index"];

//     $query = "DELETE FROM categories WHERE id=$id";
//     $delete_category = mysqli_query($conn, $query);
    
// }

// header("Location: ../categories.php");

include("database-actions/db-connection.php");

$alert = "";
if (isset($_POST["delete-category"])) {
    $id= $_GET["index"];

    $query = "DELETE FROM categories WHERE id=$id";
    $delete_category = mysqli_query($conn, $query);

    if (isset($delete_category)) {
        $alert = "Category deleted successfully";
    } else {
        $alert = "Error, cant't delete this category write now";
    }

    date_default_timezone_set("Africa/Cairo");
    setcookie("category_alert", $alert, time()+3);

    header("Location: #");
}