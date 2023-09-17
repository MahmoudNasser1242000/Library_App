<?php
include("database-actions/db-connection.php");

$alert = "";
if (isset($_POST["add-comment-rate"])) {
    $book_id = $_GET["book"];

    if ($_POST["rate"] === "-0.5") {
        $rate = "";
    } else {
        $rate = $_POST["rate"];
    }
    $comment = $_POST["comment"];

    // =======================================
    $query_book = "SELECT * FROM books WHERE id=$book_id";
    $book = mysqli_query($conn, $query_book);
    $row_book = mysqli_fetch_assoc($book);

    $comment_rate = $row_book["comment_rate"];
    // =======================================
    $token = $_COOKIE["allowed"][1];
    $alert_user = "";

    if (!empty($comment_rate)) {
        $old_messages = unserialize(base64_decode($row_book["comment_rate"]));
        
        for ($i = 0; $i < sizeof($old_messages); $i++) {
            if ($old_messages[$i]["user"] === $token) {
                $alert_user = "done";
            }
        }
    }
    // =======================================

    if (empty($comment)) {
        $alert = "Comment is empty";
    } elseif ($alert_user === "done") {
        $alert = "You had commented before";
    } else {

        $new_message = array(
            "user" => $token,
            "rate" => $rate,
            "comment" => $comment,
            "date-create" => date("Y-m-d h:i:s"),
            "date-update" => ""
        );

        if (empty($row_book["comment_rate"])) {
            $frist_order = array($new_message);
            $date_to_save = $frist_order;
        } else {
            $old_order = $old_messages;
            array_push($old_order, $new_message);
            $date_to_save = $old_order;
        }

        $encoded_data = base64_encode(serialize($date_to_save));

        $query_update = "UPDATE books SET comment_rate='$encoded_data' WHERE id=$book_id";
        $book_update = mysqli_query($conn, $query_update);

        if (isset($book_update)) {
            $alert = "Comment is stored successfully";
        } else {
            $alert = "Error storing comment, please try again";
        }
    }

    date_default_timezone_set("Africa/Cairo");
    setcookie("book_alert", $alert, time() + 3);

    header("Location: show-book.php?book=$book_id");
}
