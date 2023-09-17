<?php
include("database-actions/db-connection.php");

$alert = "";
if (isset($_POST["delete-message"])) {

    $book_id = $_GET["book"];
    $message_index = $_GET["message"];
    $role = $_GET["role"];

    // =================================================
    $query_book = "SELECT * FROM books WHERE id=$id";
    $book = mysqli_query($conn, $query_book);
    $row_book = mysqli_fetch_assoc($book);
    // =================================================
    $message = unserialize(base64_decode($row_book["comment_rate"]));

    array_splice($message, $message_index, 1);

    $message_after_delete = base64_encode(serialize($message));

    //update in database
    $query_update = "UPDATE books SET comment_rate='$message_after_delete' WHERE id=$book_id";
    $update_message = mysqli_query($conn, $query_update);

    if (isset($update_message)) {
        $alert = "Comment has deleted successfully";
    } else {
        $alert = "Error, comment can't be deleted";
    }

    date_default_timezone_set("Africa/Cairo");
    setcookie("book_alert", $alert, time() + 3);

    if ($role === "user") {
        header("Location: show-book.php?book=$book_id");
    } else {
        header("Location: show-book-messages.php?book=$book_id");
    }
    
}
