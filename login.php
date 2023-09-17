<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="css/signin.css"> -->

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


<body style="height: 100vh;" class="d-flex justify-content-center align-items-center">

    <div class="w-100">

        <div class="alert mt-4 mx-auto text-center w-25" role="alert">
            <?php echo isset($_COOKIE["login_alert"]) ? $_COOKIE["login_alert"] : ""; ?>
        </div>


        <!-- =========================================================== -->
        <?php
        include("database-actions/login-action.php");
        ?>

        <form class="row w-50 w g-3 mb-5 mt-3 mx-auto" method="POST" enctype="multipart/form-data">
            <div class="col-md-12">
                <label for="user-emal" class="form-label">Email</label>
                <input type="email" class="form-control" id="user-email" name="email">
            </div>
            <div class="col-md-12">
                <label for="user-pass" class="form-label">Password</label>
                <input type="password" class="form-control" id="user-pass" name="password">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-dark rounded-1 <?php echo isset($_COOKIE["allowed"]) ? "disabled" : ""?>" name="login">login</button>
            </div>
        </form>

        <p class="text-center pb-4">
            <a href="login.php" class="d-block mb-2 text-dark link-underline-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-75-hover <?php echo isset($_COOKIE["allowed"])? "d-none" : ""?>">if you don't have an acount, go here</a>
            <a href="index.php" class="d-block text-dark link-underline-dark link-offset-2 link-underline-opacity-0 link-underline-opacity-75-hover">Go back home from here</a>
        </p>


    </div>

    <script src="js/login.js"></script>
</body>


</html>