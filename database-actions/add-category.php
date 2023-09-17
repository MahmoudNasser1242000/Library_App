<?php

include("database-actions/db-connection.php");

$alert="";
if (isset($_POST["add-category"])) {
    $category = $_POST["name"];

    if (empty($category)) {
        $alert = "warning, category name is empty!";
    } else {
        $query = "INSERT INTO categories (name) VALUES ('$category')";
        $add_category = mysqli_query($conn, $query);

        if (isset($add_category)) {
            $alert = "Category added successfully";
        } else {
            $alert = "Error, Category can't be add!";
        }
        
    }
    
    date_default_timezone_set("Africa/Cairo");
    setcookie("category_alert", $alert, time()+3);

    header("Location: #");
}