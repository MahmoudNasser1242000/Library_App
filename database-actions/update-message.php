<?php
include("database-actions/db-connection.php");

$alert = "";
if (isset($_POST["update-comment-rate"])) {
    $book_id = $_GET["book"];
    $message_index = $_GET["update-message"];

    $comment = $_POST["comment"];
    if (empty($_POST["rate"])) {
        $rate = "";
    } else {
        $rate = $_POST["rate"];
    }

    $date_update = date("Y-m-d h:i:s");
    // =======================================================
    $query_book = "SELECT * FROM books WHERE id=$book_id";
    $book = mysqli_query($conn, $query_book);
    $row_book = mysqli_fetch_assoc($book);
    // =======================================================

    if (empty($comment)) {
        $alert = "Comment is empty";
    } else {

        if (!empty($row_book["comment_rate"])) {
            $encode_messages = unserialize(base64_decode($row_book["comment_rate"]));

            $encode_messages[$message_index]["comment"] = $comment;
            $encode_messages[$message_index]["rate"] = $rate;
            $encode_messages[$message_index]["date-update"] = $date_update;

            $msg_after_update = base64_encode(serialize($encode_messages));

            //update in database
            $query_update = "UPDATE books SET comment_rate='$msg_after_update' WHERE id=$book_id";
            $update_message = mysqli_query($conn, $query_update);

            if (isset($update_message)) {
                $alert = "Comment is updated successfully";
            } else {
                $alert = "Error updating comment, please try again";
            }
            
        }
            

    }

    date_default_timezone_set("Africa/Cairo");
    setcookie("book_alert", $alert, time()+3);

    header("Location: show-book.php?book=$book_id");
}
