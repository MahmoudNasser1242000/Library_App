<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/signin.css">

    <!-- bootstrap -->
    <link rel="stylesheet" href="bootstrap-5.3.0-dist/css/bootstrap.min.css">
    <script src="bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gulzar&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet"> <!-- ------------ -->
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/fontawesome.min.js" integrity="sha512-c41hNYfKMuxafVVmh5X3N/8DiGFFAV/tU2oeNk+upk/dfDAdcbx5FrjFOkFhe4MOLaKlujjkyR4Yn7vImrXjzQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600&family=Gulzar&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet"> <!-- ------------ -->

    <title>Document</title>
</head>

<?php
if (isset($_GET["role"])) {
    if ($_GET["role"] === "user") {
        include("database-actions/add-users.php");
    } else {
        include("database-actions/add-admins.php");
    }
}
// echo bin2hex(random_bytes(16))

// $x= base64_encode("123456");
// echo $x;
// $y = base64_decode($x);
// echo $y;
?>

<?php
include("database-actions/db-connection.php");

if (isset($_GET["update"])) {

    if (isset($_COOKIE["allowed"])) {
        $allowed = $_COOKIE["allowed"];

        if ($allowed[0] === "user") {
            $query_user = "SELECT * FROM users WHERE token='$_GET[update]'";
            $users = mysqli_query($conn, $query_user);
            $rows = mysqli_fetch_assoc($users);
        } else {
            $query_admin = "SELECT * FROM admins WHERE token='$_GET[update]'";
            $admins = mysqli_query($conn, $query_admin);
            $rows = mysqli_fetch_assoc($admins);
        }
    }

    include("database-actions/update-profile.php");
}
?>

<body style="height: 100vh;" class="d-flex justify-content-center align-items-center">

    <div class="w-50 h-100">

        <div class="alert mt-4 mx-auto text-center w-50" role="alert">
            <?php echo isset($_COOKIE["signin_alert"]) ? $_COOKIE["signin_alert"] : ""; ?>
        </div>

        <form class="row g-3 mb-5 mt-3" method="POST" enctype="multipart/form-data">
            <div class="col-md-12">
                <label for="user-name" class="form-label">Name</label>
                <input type="text" class="form-control" id="user-name" name="name" value="<?php echo isset($rows) ? $rows["name"] : "" ?>">
            </div>
            <div class="col-md-12">
                <label for="user-email" class="form-label">Email</label>
                <input type="email" class="form-control" id="user-email" name="email" value="<?php echo isset($rows) ? $rows["email"] : "" ?>">
            </div>
            <?php
            if (isset($rows)) {
                echo "<div class='col-md-12'>
                        <label for='old-pass' class='form-label'>Write Your Old Password</label>
                        <input type='password' class='form-control' id='old-pass' name='old-password'>
                    </div>";
            }
            ?>
            <div class="col-md-12">
                <label for="user-pass" class="form-label"><?php echo isset($rows) ? "Your New Password" : "Password" ?></label>
                <input type="password" class="form-control" id="user-pass" name="<?php echo isset($rows) ? "new-password" : "password" ?>">
            </div>
            <div class="col-md-12">
                <label for="confirm-pass" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-pass" name="password-confirm">
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-dark rounded-1">
                    <label for="user-img" class="form-label m-0 h-100">Add Your Image</label>
                    <input class="form-control d-none w-100 h-100" type="file" id="user-img" name="image">
                    <i class="fa-solid fa-camera mx-1 h-100" style="color: #ffffff;"></i>
                </button>
            </div>
            <?php
            if (isset($rows)) {
                echo '<div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" name="switch">
                            <label class="form-check-label" for="flexSwitchCheckChecked">Click Here If You Don,t Want Profile Image</label>
                        </div>
                    </div>';
            }
            ?>
            <div class="col-12">
                <label for="user-date" class="form-label">Birth Date</label>
                <input type="date" class="form-control" id="user-date" name="date" value="<?php echo isset($rows) ? $rows["birth_date"] : "" ?>">
            </div>
            <fieldset class="row my-3">
                <legend class="col-form-label col-sm-2 pt-0">Gender</legend>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="gridRadios1" value="male" <?php echo isset($rows) ? ($rows["gender"] === "male" ? "checked" : "") : "checked" ?>>
                        <label class="form-check-label" for="gridRadios1">
                            Male
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="gridRadios2" value="female" <?php echo isset($rows) ? ($rows["gender"] === "female" ? "checked" : "") : "" ?>>
                        <label class="form-check-label" for="gridRadios2">
                            Eemale
                        </label>
                    </div>
                </div>
            </fieldset>

            <div class="col-12">
                <button type="submit" class="btn btn-dark rounded-1 <?php echo isset($_COOKIE["allowed"])? "disabled" : ""?>" name="<?php echo isset($rows) ? "update-profile" : "signin" ?>"><?php echo isset($rows) ? "Update Profile" : "Sign in" ?></button>
            </div>
        </form>

        <p class="text-center pb-4">
            <a href="login.php" class="d-block mb-2 text-dark link-underline-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-75-hover <?php echo isset($_COOKIE["allowed"])? "d-none" : ""?>">if you allready have an acount, go here</a>
            <a href="index.php" class="d-block text-dark link-underline-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-75-hover">Go back home from here</a>
        </p>
    </div>

    <script src="js/signin.js"></script>
</body>

</html>