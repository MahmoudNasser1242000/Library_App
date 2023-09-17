<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/categories.css">
    <link rel="stylesheet" href="layout/control-panel.css">
    <link rel="stylesheet" href="layout/pagination.css">

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
include("database-actions/db-connection.php");

if (isset($_COOKIE["allowed"]) && $_COOKIE["allowed"][0] === "admin") {
    $allowed_admin = $_COOKIE["allowed"];

    $query_admin = "SELECT * FROM admins WHERE token='$allowed_admin[1]'";
    $admin = mysqli_query($conn, $query_admin);
    $row_admin = mysqli_fetch_assoc($admin);

    if ($row_admin["rank"] === "creator") {


?>

        <!-- =========================================================== -->
        <?php
        if (isset($_COOKIE["allowed"])) {
            $allowed = $_COOKIE["allowed"];

            if ($allowed[0] === "admin") {
                if (isset($_POST["search"])) {
                    $search = $_POST["search"];
                }
                $complete = "false";

                // =================pagination=================

                // ============================================

                if (isset($_POST["search-name"])) {
                    $query_users = "SELECT * FROM users WHERE name LIKE '%$search%'";
                } elseif (isset($_POST["search-email"])) {
                    $query_users = "SELECT * FROM users WHERE email LIKE '%$search%'";
                } elseif (isset($_POST["blocked-users"])) {
                    $query_users = "SELECT * FROM users WHERE allow='false' ORDER BY id DESC";
                } elseif (isset($_POST["unblocked-users"])) {
                    $query_users = "SELECT * FROM users WHERE allow='true' ORDER BY id DESC";
                } else {
                    if (isset($_GET["page"])) {
                        $page = $_GET["page"];
                    } else {
                        $page = 1;
                    }
                    $limit_users = 5;
                    $start_user = ($page - 1) * $limit_users;

                    $complete = "true";
                    $query_users = "SELECT * FROM users ORDER BY id DESC LIMIT $start_user, $limit_users";
                }

                $users = mysqli_query($conn, $query_users);
            }
        }
        ?>

        <?php
        if (isset($_GET["delete"])) {
            include("database-actions/delete-user.php");
        }
        ?>

        <?php
        if (isset($_GET["allow"])) {
            include("database-actions/allowed-user.php");
        }
        ?>

        <?php
        include("database-actions/timer-allow-user.php");
        ?>

        <body>
            <?php
            include("layout/control-panel.php")
            ?>

            <h2 class="text-center my-4">All Users</h2>

            <div class="alert mt-4 mx-auto text-center w-50" role="alert">
                <?php echo isset($_COOKIE["admin_alert"]) ? $_COOKIE["admin_alert"] : ""; ?>
            </div>

            <form class="mx-4 mt-4" method="POST">
                <input type="search" class="form-control search-input" name="search">
                <div class="mt-3">
                    <button class="btn btn-dark search-name" type="submit" name="search-name">search with Name</button>
                    <button class="btn btn-dark mx-2 search-email" type="submit" name="search-email">search with Email</button>
                </div>
            </form>

            <div class="mt-5 pt-3 text-center">
                <form method="POST">
                    <button class="btn btn-secondary blocked-user" type="submit" name="blocked-users">get blocked users</button>
                    <button class="btn btn-secondary mx-2 unblocked-user" type="submit" name="unblocked-users">get unblocked users</button>
                    <a class="btn btn-secondary mx-2 all-users" href="all-users.php" role="button">get all users</a>
                </form>
            </div>
            <div class="mx-4 mt-3">
                <table class="table table-bordered" style="direction: rtl;">
                    <thead>
                        <tr>
                            <th>#id</th>
                            <th>Name</th>
                            <th>Profile</th>
                            <th>Date</th>
                            <th>Gender</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="tb-body">
                        <?php
                        $num_users = mysqli_num_rows($users);
                        $index = 0;

                        if ($num_users > 0) {
                            while ($rows = mysqli_fetch_assoc($users)) {
                                $index += 1;

                                if (empty($rows["image"])) {
                                    if ($rows["gender"] === "male") {
                                        $profile = "uploads/default-profile-images/male_profile_image.jpg";
                                    } else {
                                        $profile = "uploads/default-profile-images/female_profile_image2.jpg";
                                    }
                                } else {
                                    $profile = "uploads/users-images/$rows[image]";
                                }

                                if ($rows["allow"] === "true") {
                                    $allow = "disabled";
                                    $not_allow = "";
                                } else {
                                    $allow = "";
                                    $not_allow = "disabled";
                                }

                                if ($rows["allow"] === "true") {
                                    $verify = '<i class="fa-solid fa-circle-check mx-1" style="color: #3fc819;"></i>';
                                } else {
                                    $verify = '<i class="fa-solid fa-circle-check mx-1" style="color: #de0d0d;"></i>';
                                }

                                if (isset($_COOKIE["allow_user$rows[token]"])) {
                                    $allow_user = date("Y-m-d h:i:sa", $_COOKIE["allow_user$rows[token]"]);
                                    $allow_timer = "<span>($allow_user)</span>";
                                } else {
                                    $allow_timer = "";
                                }


                                echo "<tr>
                                <td>$index</td>
                                <td class='user-name'>$rows[name] $allow_timer $verify</td>
                                <td class='d-none user-email'>$rows[email]</td>
                                <td><img src='$profile' class='rounded-circle' width='60' height='60' alt='...'></td>
                                <td>$rows[birth_date]</td>
                                <td>$rows[gender]</td>
                                <td>
                                    <form action='?allow=$rows[id]' method='POST'>
                                        <button class='btn btn-warning mx-2 $not_allow' type='submit' name='check-allow'>Not Allow</button>
                                        <button class='btn btn-success $allow' type='submit' name='check-allow'>Allow</button>
                                    </form>
                                </td>
                                <td class='d-none allow'>
                                    $rows[allow]
                                </td>
                                <td>
                                    <button class='btn btn-danger' type='button' data-bs-toggle='modal' data-bs-target='#exampleModal$rows[id]'>
                                        Delete User
                                    </button>
    
                                    <!-- Modal -->
                                    <div style='direction: ltr;' class='modal fade' id='exampleModal$rows[id]' tabindex='-1' data-bs-backdrop='static' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog'>
                                            <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h1 class='modal-title fs-5' id='staticBackdropLabel'>Delete User</h1>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                            </div>
                                            <div class='modal-body'>
                                                Do you want to delete this user?
                                            </div>
                                            <div class='modal-footer'>
                                                <form action='?delete=$rows[id]' method='POST'>
                                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                                    <button type='submit' class='btn btn-success' role='button' name='delete-user'>Yes</button>
                                                </form>
                                            </div>
                                            </div>
                                        </div>
                                    </div>                            
                                </td>
                            
                            </tr>";
                            }
                        } else {
                            echo "<tr>
                            <td>
                                <h3 style='direction: rtl;'>There are no users ....</h3>
                            </td>
                        </tr>";
                        }

                        ?>
                    </tbody>
                </table>

                <!-- ======================================================== -->

                <?php
                $query = "SELECT * FROM users";
                $res = mysqli_query($conn, $query);
                $num_users = mysqli_num_rows($res);

                if ($num_users > 5 && $complete === "true") {
                    $total_pages = ceil($num_users / $limit_users);

                    if (($page - 1) > 0) {
                        $prev = $page - 1;
                    } else {
                        $prev = 1;
                    }

                    if (($page + 1) <= $total_pages) {
                        $next = $page + 1;
                    } else {
                        $next = $total_pages;
                    }

                    echo "<nav class='d-flex justify-content-center'>
                        <ul class='pagination'>

                            <li class='page-item'>
                                <a class='page-link bg-success text-white' href='?page=$prev'>Prev</a>
                            </li>";

                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<li class='page-item'><a class='page-link' href='?page=$i'>$i</a></li>";
                    }

                    echo "      <li class='page-item'>
                            <a class='page-link bg-success text-white' href='?page=$next'>Next</a>
                        </li>

                    </ul>
                </nav>";
                }

                ?>

            </div>

            <script src="js/signin.js"></script>
            <!-- <script src="js/user-search.js"></script> -->
        </body>
<?php
    } else {
        header("Location: dashboard.php");
    }
} elseif (isset($_COOKIE["allowed"]) && $_COOKIE["allowed"][0] === "user") {
    header("Location: index.php");
} else {
    header("Location: login.php");
}
?>

</html>