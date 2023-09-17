<?php

include("database-actions/db-connection.php");

$alert = "";
if (isset($_POST["add-book"])) {
    $title = $_POST["title"];
    $category = $_POST["category"];
    $content = $_POST["content"];

    //image
    $image = $_FILES["image"]["name"];
    $image_tmp = $_FILES["image"]["tmp_name"];
    if (!empty($image)) {
        $image_size = getimagesize($image_tmp);
    }
    $image_type = $_FILES["image"]["type"];

    $default_image_types = ["image/png", "image/jpg", "image/jpeg", "image/gif"];

    //file
    $book_file = $_FILES["book-file"]["name"];
    $book_file_tmp = $_FILES["book-file"]["tmp_name"];
    $book_file_type = pathinfo($book_file, PATHINFO_EXTENSION);

    $default_file_types = ["pdf", "doc", "docx", "rtf", "txt", "odf", "msword"];
    // =========================================
    date_default_timezone_set("Africa/Cairo");

    if (empty($title)) {
        $alert = "Book title is empty";
    } elseif (strlen($title) > 255 || strlen($title) < 3) {
        $alert = "Book title must be between 3 to 255 character";        
    } elseif (empty($category)) {
        $alert = "Book category is empty";        
    } elseif (empty($image)) {
        $alert = "Book cover is empty";        
    } elseif (!in_array($image_type, $default_image_types)) {
        $alert = "Book cover type is wrong";        
    } elseif ($image_size[0] < 600 || $image_size[1] < 488) {
        $alert = "Book cover is very small"; 
    } elseif (empty($book_file)) {
        $alert = "Book file is empty";        
    } elseif (!in_array($book_file_type, $default_file_types)) {
        $alert = "Book file type is wrong";
    }elseif (empty($content)) {
        $alert = "Book content is empty";        
    } elseif (strlen($content) < 50) {
        $alert = "Book content is must be between 50 to 20,000 character";        
    } else {
        //image
        $upload_image_name = rand(0, 1_000_000)."_".date("Y-m-d-h-i-s")."_".$image;

        //file
        $upload_file_name = rand(0, 1_000_000)."_".date("Y-m-d-h-i-s")."_".$book_file;

        //author
        if (empty($_POST["author"])) {
            $author = "";
        } else {
            $author = $_POST["author"];
        }
        

        $query = "INSERT INTO books (title, category, image, book, author, content)
        VALUES ('$title', '$category', '$upload_image_name', '$upload_file_name', '$author', '$content')";

        $add_books = mysqli_query($conn, $query);

        if (isset($add_books)) {
            $alert = "Book added successfully";
            move_uploaded_file($image_tmp, "uploads/book-images/$upload_image_name");     
            move_uploaded_file($book_file_tmp, "uploads/book-files/$upload_file_name");     
        } else {
            $alert = "Error, Book can not be add";        
        }
        
    }

    setcookie("book_alert", $alert, time()+3);

    header("Location: books.php");
}