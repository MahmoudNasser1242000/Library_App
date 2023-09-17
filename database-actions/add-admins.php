<?php

include("database-actions/db-connection.php");

$alert = "";
if (isset($_POST["signin"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];

    $password = $_POST["password"];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $password_confirm = $_POST["password-confirm"];
    $birth_date = $_POST["date"];
    $gender = $_POST["gender"];

    $token = bin2hex(random_bytes(16));

    //image
    $image = $_FILES["image"]["name"];
    $image_tmp = $_FILES["image"]["tmp_name"];
    $image_type = pathinfo($image, PATHINFO_EXTENSION);
    if (!empty($image)) {
        $image_size = getimagesize($image_tmp);
    }

    $default_image_types = ["png", "jpg", "jpeg", "webp"];

    // ===================================================================================
    date_default_timezone_set("Africa/Cairo");

    if (empty($name)) {
        $alert = "Name is empty";
    } elseif (strlen($name) > 255 || strlen($name) < 2) {
        $alert = "Name must be between 2 to 255 character";
    } elseif (empty($email)) {
        $alert = "Email is empty";
    } elseif (empty($password)) {
        $alert = "Password is empty";
    } elseif (strlen($password) < 5) {
        $alert = "Password must be at least 5 characters";
    } elseif (empty($password_confirm)) {
        $alert = "Password confirm is empty";
    } elseif ($password_confirm !== $password) {
        $alert = "Wrong password confirm";
    } elseif (!empty($image) && !in_array($image_type, $default_image_types)) {
        $alert = "Image type is wrong";
    } elseif (!empty($image) && ($image_size[0] < 250 || $image_size[1] < 250)) {
        $alert = "Image size is very small";
    } elseif (empty($birth_date)) {
        $alert = "Date is empty";
    } elseif (empty($gender)) {
        $alert = "Gender is empty";
    } else {
        $query_admins = "SELECT * FROM admins WHERE email='$email'";
        $admins = mysqli_query($conn, $query_admins);
        $row = mysqli_fetch_assoc($admins);

        if (!empty($row)) {
            $alert = "Email has allready exist before";
        } else {

            if (empty($image)) {               
                $upload_image_name = "";               
            } else {
                $upload_image_name = rand(0, 1_000_000)."_".date("Y-m-d-h-i-s")."_".str_replace(" ", "-", $name).".$image_type";
            }

            $query = "INSERT INTO admins (name, email, password, image, birth_date, gender, token)
            VALUES ('$name', '$email', '$password_hash', '$upload_image_name', '$birth_date', '$gender', '$token')";
            $add_admin = mysqli_query($conn, $query);

            if (isset($add_admin)) {
                $alert = "Admin added successfully";

                if (!empty($image)) {
                    move_uploaded_file($image_tmp, "uploads/admins-images/$upload_image_name");
                }

                // setcookie("allowed[0]", "admin");
                // setcookie("allowed[1]", $token);
            } else {
                $alert = "Error, can't add the admin";
            }
            
        }
        
    }

    setcookie("signin_alert", $alert, time()+3);

    header("Location: #");
    
}