<?php
include("database-actions/db-connection.php");

if (isset($_COOKIE["allowed"])) {
    $allowed = $_COOKIE["allowed"];
    $allow = "true";

    if ($allowed[0] === "user") {
        $query_user = "SELECT * FROM users WHERE token='$allowed[1]'";
        $user = mysqli_query($conn, $query_user);
        $row = mysqli_fetch_assoc($user);

        if (empty($row["image"])) {
            if ($row["gender"] === "male") {
                $image = "uploads/default-profile-images/male_profile_image.jpg";
            } else {
                $image = "uploads/default-profile-images/female_profile_image2.jpg";
            }
        } else {
            $image = "uploads/users-images/$row[image]";
        }

        $allow = $row["allow"];
    } else {
        $query_admin = "SELECT * FROM admins WHERE token='$allowed[1]'";
        $admin = mysqli_query($conn, $query_admin);
        $row = mysqli_fetch_assoc($admin);

        if (empty($row["image"])) {
            if ($row["gender"] === "male") {
                $image = "uploads/default-profile-images/male_profile_image.jpg";
            } else {
                $image = "uploads/default-profile-images/female_profile_image2.jpg";
            }
        } else {
            $image = "uploads/admins-images/$row[image]";
        }
    }
}
?>

<?php
include("database-actions/logout.php");
?>

<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary" style="direction: rtl;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php" style="font-weight: 500;">مكتبه تحميل الكتب</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php
                    if (isset($_COOKIE["allowed"])) {
                        if ($_COOKIE["allowed"][0] === "admin") {
                            echo "<li class='nav-item'>
                                    <a class='nav-link' href='categories.php'>control panel</a>
                                </li>";
                        }
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About Us</a>
                    </li>
                    <?php
                    if (isset($_COOKIE["allowed"])) {

                        $verify = "";
                        $update_profile = "signin.php?update=$row[token]";

                        if ($allowed[0] === "user") {
                            if ($allow === "true") {
                                $verify = '<i class="fa-solid fa-circle-check mx-1" style="color: #3fc819;"></i>';
                                $update_profile = "signin.php?update=$row[token]";
                            } else {
                                $verify = '<i class="fa-solid fa-circle-check mx-1" style="color: #de0d0d;"></i>';
                                $update_profile = "";
                            }
                        }

                        echo "<div class='d-flex justify-content-between align-items-center'>
                                <div class='dropdown mx-2'>
                                    <a class='dropdown-toggle btn btn-dark bg-opacity-75 border-0 d-flex align-items-center justify-content-between' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <img src='$image' class=' rounded-circle mx-1' width='33' height='33'>
                                        <span class='mx-1'>$row[name]</span>
                                        $verify
                                    </a>

                                    <ul class='dropdown-menu'>
                                        <li><a class='dropdown-item' href='$update_profile'>تعديل الاكونت</a></li>
                                        <li>
                                            <form method='POST'>
                                                <button type='submit' class='dropdown-item' class='btn border-0' name='logout'>Logout</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>";
                    } else {
                        echo "<li class='nav-item'>
                                <a class='nav-link' href='login.php'>login</a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' href='signin.php?role=user'>signin</a>
                            </li>";
                    }

                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>