<?php

include("database-actions/db-connection.php");

$alert="";
if (isset($_POST["update-category"])) {
    
    $id = $_GET["id"];
    $name = $_POST["name"];

    if (empty($name)) {
        $alert = "warning, category name is empty!";
    } else {
        $query = "UPDATE categories SET name='$name' WHERE id=$id";
        $update_category = mysqli_query($conn, $query);

        if (isset($update_category)) {
            $alert = "Category updated successfully";
        } else {
            $alert = "Error, Category can't be update!";
        }
    }

    
    date_default_timezone_set("Africa/Cairo");
    setcookie("category_alert", $alert, time()+3);

    header("Location: #");
}
