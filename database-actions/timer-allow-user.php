<?php

include("database-actions/db-connection.php");

$alert = "";
date_default_timezone_set("Africa/Cairo");

if (isset($_COOKIE["allowed"])) {
    $allowed = $_COOKIE["allowed"];

    if ($allowed[0] === "admin") {
        $query_user_allow = "SELECT * FROM users";
        $user_allow = mysqli_query($conn, $query_user_allow);

        while ($row_user = mysqli_fetch_assoc($user_allow)) {
            $user_token = $row_user["token"];

                //not-allowed                               //allowed
            if (isset($_COOKIE["allow_user$user_token"]) && $_COOKIE["allow_user$user_token"] < time()) {
                setcookie("allow_user$user_token", "", time() - 1);

                $query_allow_update = "UPDATE users SET allow='true' WHERE token='$user_token'";
                $profile_allow_update = mysqli_query($conn, $query_allow_update);

                if (isset($profile_allow_update)) {
                    $alert = "User blocked successfully";
                }

                setcookie("signin_alert", $alert, time() + 3);
                header("Location: all-users.php");
            }

        }

    }
}