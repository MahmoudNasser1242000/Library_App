<?php

include("database-actions/db-connection.php");

$alert = "";
date_default_timezone_set("Africa/Cairo");

if (isset($_POST["update-profile"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];

    $old_password = $_POST["old-password"];
    $new_password = $_POST["new-password"];
    $password_confirm = $_POST["password-confirm"];

    if ($_POST["switch"] === "on") {
        $switch = "on";
    } else {
        $switch = "off";
    }

    $birth_date = $_POST["date"];
    $gender = $_POST["gender"];

    $date_update = date("Y-m-d h:i:s");

    //image
    $image = $_FILES["image"]["name"];
    $image_tmp = $_FILES["image"]["tmp_name"];
    if (!empty($image)) {
        $image_size = getimagesize($image_tmp);
    }
    $image_type = pathinfo($image, PATHINFO_EXTENSION);

    $default_image_types = ["jpg", "png", "jpeg", "webp"];

    // ===========================================================================
    if (isset($_COOKIE["allowed"])) {
        $allowed = $_COOKIE["allowed"];

        if ($allowed[0] === "user") {
            $query_user = "SELECT * FROM users WHERE token='$allowed[1]'";
            $user = mysqli_query($conn, $query_user);
            $row_user = mysqli_fetch_assoc($user);

            $old_image = $row_user["image"];
            $old_pass = $row_user["password"];
            $old_email = $row_user["email"];
        } else {
            $query_admin = "SELECT * FROM admins WHERE token='$allowed[1]'";
            $admin = mysqli_query($conn, $query_admin);
            $row_admin = mysqli_fetch_assoc($admin);

            $old_image = $row_admin["image"];
            $old_pass = $row_admin["password"];
            $old_email = $row_admin["email"];
        }

        if (!empty($old_password)) {
            $password_verify = password_verify($old_password, $old_pass);
        }
    }
    // ===========================================================================

    if (empty($name)) {
        $alert = "Name is empty";
    } elseif (strlen($name) > 255 || strlen($name) < 2) {
        $alert = "Name must be between 2 to 255 character";
    } elseif (empty($email)) {
        $alert = "Email is empty";
    } elseif (!empty($old_password) && !$password_verify) {
        $alert = "Old password is wrong";
    } elseif (empty($old_password) && (!empty($new_password) || !empty($password_confirm))) {
        $alert = "You have to enter old password first";
    } elseif (!empty($new_password) && strlen($new_password) < 5) {
        $alert = "Password must be at least 5 characters";
    } elseif (empty($new_password) && (!empty($old_password) && !empty($password_confirm))) {
        $alert = "You have to enter new password";
    } elseif (empty($password_confirm) && (!empty($new_password) && !empty($old_password))) {
        $alert = "Password confirm is empty";
    } elseif ((!empty($new_password) && !empty($old_password)) && ($password_confirm !== $new_password)) {
        $alert = "Wrong password confirm";
    } elseif (!empty($image) && $switch === "on") {
        $alert = "Choose one of add image or get default";
    } elseif (!empty($image) && !in_array($image_type, $default_image_types)) {
        $alert = "Image type is wrong";
    } elseif (!empty($image) && ($image_size[0] < 250 || $image_size[1] < 250)) {
        $alert = "Image size is very small";
    } elseif (empty($birth_date)) {
        $alert = "Date is empty";
    } elseif (empty($gender)) {
        $alert = "Gender is empty";
    } else {
        if ($allowed[0] === "user") {
            $query = "SELECT * FROM users WHERE email='$email'";
            $res = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($res);
        } else {
            $query = "SELECT * FROM admins WHERE email='$email'";
            $res = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($res);
        }

        if ($email !== $old_email && !empty($row)) {
            $alert = "Email has allready exist before";
        } else {
            if (empty($image)) {
                if ($switch === "on") {
                    $upload_image_name = "";
                } else {
                    $upload_image_name = $old_image;
                }
            } else {
                $upload_image_name = rand(0, 1_000_000) . "_" . date("Y-m-d-h-i-s") . "_" . str_replace(" ", "-", $name) . ".$image_type";
            }

            if (!empty($new_password) && ($new_password !== $old_pass)) {
                $password_update = password_hash($new_password, PASSWORD_DEFAULT);
            } else {
                $password_update = $old_pass;
            }


            if (isset($_COOKIE["allowed"])) {
                $allowed = $_COOKIE["allowed"];

                if ($allowed[0] === "user") {
                    $query_update = "UPDATE users SET 
                    name='$name', email='$email', password='$password_update', image='$upload_image_name', birth_date='$birth_date', gender='$gender', date_update='$date_update' WHERE token='$allowed[1]'";
                } else {
                    $query_update = "UPDATE admins SET 
                    name='$name', email='$email', password='$password_update', image='$upload_image_name', birth_date='$birth_date', gender='$gender', date_update='$date_update' WHERE token='$allowed[1]'";
                }
            }

            $profile_update = mysqli_query($conn, $query_update);

            if (isset($profile_update)) {

                if (!empty($image)) {
                    if ($allowed[0] === "user") {
                        move_uploaded_file($image_tmp, "uploads/users-images/$upload_image_name");
                        if (!empty($old_image)) {
                            unlink("uploads/users-images/$old_image");
                        }
                    } else {
                        move_uploaded_file($image_tmp, "uploads/admins-images/$upload_image_name");
                        if (!empty($old_image)) {
                            unlink("uploads/admins-images/$old_image");
                        }
                    }
                } elseif (empty($image) && $switch === "on") {
                    if ($allowed[0] === "user") {
                        if (!empty($old_image)) {
                            unlink("uploads/users-images/$old_image");
                        }
                    } else {
                        if (!empty($old_image)) {
                            unlink("uploads/admins-images/$old_image");
                        }
                    }
                }

                $alert = "Profile update successfully";
            } else {
                $alert = "Error, can not update profile";
            }
        }
    }

    setcookie("signin_alert", $alert, time() + 3);

    header("Location: #");
}
