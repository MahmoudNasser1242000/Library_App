<?php

include("database-actions/db-connection.php");

$alert = "";
if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // =====================================
    date_default_timezone_set("Africa/Cairo");

    if (empty($email)) {
        $alert = "Email is empty";
    } elseif (empty($password)) {
        $alert = "Password is empty";
    } elseif (strlen($password) < 5) {
        $alert = "password must be at least 5 characters";
    } else {
        $query_users = "SELECT * FROM users WHERE email='$email'";
        $users = mysqli_query($conn, $query_users);
        $row_user = mysqli_fetch_assoc($users);
        $pass_user = password_verify($password, $row_user["password"]);

        $query_admins = "SELECT * FROM admins WHERE email='$email'";
        $admins = mysqli_query($conn, $query_admins);
        $row_admin = mysqli_fetch_assoc($admins);
        $pass_admin = password_verify($password, $row_admin["password"]);


        if (!empty($row_user) && !empty($row_admin)) {

            if ($pass_user && $pass_admin) {
                setcookie("allowed[0]", "admin");
                setcookie("allowed[1]", $row_admin["token"]);

                $alert = "Login in (Admin) susseccfully";
            } elseif ($pass_admin) {
                setcookie("allowed[0]", "admin");
                setcookie("allowed[1]", $row_admin["token"]);

                $alert = "Login in (Admin) susseccfully";
            } elseif ($pass_user) {
                setcookie("allowed[0]", "user");
                setcookie("allowed[1]", $row_user["token"]);

                $alert = "Login in (User) susseccfully";
            } else {
                $alert = "Password is wrong";
            }

        } elseif (!empty($row_user)) {

            if ($pass_user) {
                setcookie("allowed[0]", "user");
                setcookie("allowed[1]", $row_user["token"]);

                $alert = "Login in (User) susseccfully";
            } else {
                $alert = "Password is wrong";
            }

        } elseif (!empty($row_admin)) {

            if ($pass_admin) {
                setcookie("allowed[0]", "admin");
                setcookie("allowed[1]", $row_admin["token"]);

                $alert = "Login in (Admin) susseccfully";
            } else {
                $alert = "Password is wrong";
            }

        } else {
            setcookie("allowed[0]", "", time() - 1);
            setcookie("allowed[1]", "", time() - 1);

            $alert = "Email is wrong";
        }
    }

    setcookie("login_alert", $alert, time() + 3);

    header("Location: #");
}
