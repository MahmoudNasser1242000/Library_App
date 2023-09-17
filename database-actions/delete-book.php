<?php

include("database-actions/db-connection.php");

$alert = "";
if (isset($_POST["delete-book"])) {
    $id = $_GET["index"];

    // =====================================
    $query_old_book = "SELECT * FROM book WHERE id=$id";
    $old_book = mysqli_query($conn, $query_delete);
    $row = mysqli_fetch_assoc($old_book);

    $old_image = $row["image"];
    $old_book = $row["book"];
    // =====================================

    $query_delete = "DELETE FROM books WHERE id=$id";
    $delete_book = mysqli_query($conn, $query_delete);

    if (isset($delete_book)) {
        $alert = "Book deleted successfully";

        unlink("uploads/book-images/$old_image");
        unlink("uploads/book-files/$old_book");
    } else {
        $alert = "Error, cant't delete this Book write now";
    }
    
    date_default_timezone_set("Africa/Cairo");
    setcookie("book_alert", $alert, time()+3);

    header("Location: #");
}