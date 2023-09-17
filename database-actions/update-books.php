<?php

include("database-actions/db-connection.php");

$alert = "";
date_default_timezone_set("Africa/Cairo");

if (isset($_POST["update-book"])) {
    $title = $_POST["title"];
    $category = $_POST["category"];
    $content = $_POST["content"];
    $date_update = date("Y-m-d h:i:s");

    $author = $_POST["author"];

    //image
    $image = $_FILES["image"]["name"];
    $image_tmp = $_FILES["image"]["tmp_name"];
    $image_type = $_FILES["image"]["type"];
    if (!empty($image)) {
        $image_size = getimagesize($image_tmp);
    }

    $default_image_types = ["image/png", "image/jpg", "image/jpeg", "image/gif", "image/webp"];

    //file
    $book_file = $_FILES["book-file"]["name"];
    $book_file_tmp = $_FILES["book-file"]["tmp_name"];
    $book_file_type = pathinfo($book_file, PATHINFO_EXTENSION);

    $default_file_types = ["pdf", "doc", "docx", "rtf", "txt", "odf", "msword"];

    // =====================================================================================

    $id = $_GET["id"];
    $query_book = "SELECT * FROM books WHERE id=$id";
    $select_book = mysqli_query($conn, $query_book);
    $row = mysqli_fetch_assoc($select_book);

    $old_image = $row["image"]; 
    $old_book = $row["book"]; 

    // =====================================================================================

    if (empty($title)) {
        $alert = "Book title is empty";
    } elseif (strlen($title) > 255 || strlen($title) < 3) {
        $alert = "Book title must be between 3 to 255 character";
    } elseif (empty($category)) {
        $alert = "Book category is empty";
    } elseif (!empty($image) && !in_array($image_type, $default_image_types)) {
        $alert = "Book cover type is wrong";
    } elseif (!empty($image) && ( $image_size[0] < 600 || $image_size[1] < 488 )) {
        $alert = "Book cover is very small"; 
    } elseif (!empty($book_file) && !in_array($book_file_type, $default_file_types)) {
        $alert = "Book file type is wrong";
    } elseif (empty($content)) {
        $alert = "Book content is empty"; 
    } elseif (strlen($content) < 50) {
        $alert = "Book content is must be between 50 to 20,000 character";  
    } else {
        //image
        if (empty($image)) {
            $uploud_image_name = $old_image;
        } else {
            $uploud_image_name = rand(0, 1_000_000)."_".date("Y-m-d-h-i-s")."_".$image;
        }

        //file
        if (empty($book_file)) {
            $uploud_book_name = $old_book;
        } else {
            $uploud_book_name = rand(0, 1_000_000)."_".date("Y-m-d-h-i-s")."_".$book_file;
        }
        

        $query = "UPDATE books SET title='$title', category='$category', image='$uploud_image_name', 
        book='$uploud_book_name', author='$author', content='$content', date_update='$date_update' WHERE id=$id";

        $update_book = mysqli_query($conn, $query);

        if (isset($update_book)) {

            if (!empty($image)) {
                move_uploaded_file($image_tmp, "uploads/book-images/$uploud_image_name");
                unlink("uploads/book-images/$old_image");
            }

            if (!empty($book_file)) {
                move_uploaded_file($book_file_tmp, "uploads/book-files/$uploud_book_name");
                unlink("uploads/book-files/$old_book");
            }

            $alert = "Book updated successfully";
        } else {
            $alert = "Error, Book can not be update";        
        }
        

    }
    
    setcookie("book_alert", $alert, time()+3);

    header("Location: #");
}