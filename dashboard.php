<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/categories.css">
    <link rel="stylesheet" href="css/dashboard.css">
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
if (isset($_COOKIE["allowed"]) && $_COOKIE["allowed"][0] === "admin") {

?>

    <!-- =========================================================== -->
    <?php
    include("database-actions/db-connection.php");
    ?>

    <?php
    if (isset($_GET["delete"])) {
        include("database-actions/delete-admins.php");
    }
    ?>

    <?php
    if (isset($_COOKIE["allowed"])) {
        $allowed = $_COOKIE["allowed"];

        if ($allowed[0] === "admin") {
            $query_admin = "SELECT * FROM admins WHERE token='$allowed[1]'";
            $res = mysqli_query($conn, $query_admin);
            $row_admin = mysqli_fetch_assoc($res);

            $rank = $row_admin["rank"];
        }
    }
    ?>

    <body style="height: 100vh;">
        <?php
        include("layout/control-panel.php")
        ?>

        <div class="alert mt-4 mx-auto text-center w-50" role="alert">
            <?php echo isset($_COOKIE["admin_alert"]) ? $_COOKIE["admin_alert"] : ""; ?>
        </div>

        <div class="row d-flex justify-content-evenly gap-3 mx-4 mt-5">
            <div class="bg-success opacity-75 statics mb-4 text-white rounded-1 col-lg-3 col-md-4 col-sm-6 d-flex flex-column justify-content-center align-items-center" style="height: 150px;">
                <p class="fs-3 text-center"><a href="categories.php" class="text-decoration-none text-white">Number of categories</a></p>
                <span class="fs-3">
                    <?php
                    $query_categories = "SELECT * FROM categories";
                    $categories = mysqli_query($conn, $query_categories);
                    $num_categories = mysqli_num_rows($categories);
                    echo $num_categories;
                    ?>
                </span>
            </div>

            <div class="bg-success opacity-75 statics mb-4 text-white rounded-1 col-lg-3 col-md-4 col-sm-6 d-flex flex-column justify-content-center align-items-center" style="height: 150px;">
                <p class="fs-3 text-center"><a href="book-actions.php" class="text-decoration-none text-white">Number of Books</a></p>
                <span class="fs-3">
                    <?php
                    $query_books = "SELECT * FROM books";
                    $books = mysqli_query($conn, $query_books);
                    $num_books = mysqli_num_rows($books);
                    echo $num_books;
                    ?>
                </span>
            </div>

            <div class="bg-success opacity-75 statics mb-4 text-white rounded-1 col-lg-3 col-md-4 col-sm-6 d-flex flex-column justify-content-center align-items-center" style="height: 150px;">
                <?php
                $num_messages = 0;
                while ($row_book = mysqli_fetch_assoc($books)) {
                    if (!empty($row_book["comment_rate"])) {
                        $decode_date = unserialize(base64_decode($row_book["comment_rate"]));
                        $num_messages += sizeof($decode_date);
                    }
                }
                ?>
                <p class="fs-3 text-center"><a href="<?php echo ($num_messages > 0) ? "comments-rates.php" : "" ?>" class="text-decoration-none text-white">Number of messages</a></p>
                <span class="fs-3">
                    <?php
                    echo $num_messages;
                    ?>
                </span>
            </div>

            <div class="bg-success opacity-75 statics mb-4 text-white rounded-1 col-lg-3 col-md-4 col-sm-6 d-flex flex-column justify-content-center align-items-center" style="height: 150px;">
                <p class="fs-3 text-center"><a href="<?php echo $rank === "creator" ? "all-users.php" : "" ?>" class="text-decoration-none text-white">Number of users</a></p>
                <span class="fs-3">
                    <?php
                    $query_users = "SELECT * FROM users";
                    $users = mysqli_query($conn, $query_users);
                    $num_users = mysqli_num_rows($users);
                    echo $num_users;
                    ?>
                </span>
            </div>

            <div class="bg-success opacity-75 statics mb-4 text-white rounded-1 col-lg-3 col-md-4 col-sm-6 d-flex flex-column justify-content-center align-items-center" style="height: 150px;">
                <p class="fs-3 text-center">Number of admins</p>
                <span class="fs-3">
                    <?php
                    $query_admins = "SELECT * FROM admins";
                    $admins = mysqli_query($conn, $query_admins);
                    $num_admins = mysqli_num_rows($admins);
                    echo $num_admins;
                    ?>
                </span>
            </div>
        </div>

        <h3 class="mt-5 mx-4 pt-3" style="direction: rtl;">All Admins</h3>
        <hr class="mx-4">

        <div class="m-4">
            <table class="table" style="direction: rtl;">
                <thead>
                    <tr>
                        <th>#id</th>
                        <th>Name</th>
                        <th>Profile</th>
                        <th>Date</th>
                        <th>Gender</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_GET["page"])) {
                        $page = $_GET["page"];
                    } else {
                        $page = 1;
                    }

                    $limit_admins = 4;
                    $start_admin = ($page - 1) * $limit_admins;

                    $query = "SELECT * FROM admins LIMIT $start_admin, $limit_admins";
                    $all_admins = mysqli_query($conn, $query);

                    $index = 0;
                    while ($rows = mysqli_fetch_assoc($all_admins)) {
                        $index += 1;

                        if (empty($rows["image"])) {
                            if ($rows["gender"] === "male") {
                                $profile = "uploads/default-profile-images/male_profile_image.jpg";
                            } else {
                                $profile = "uploads/default-profile-images/female_profile_image2.jpg";
                            }
                        } else {
                            $profile = "uploads/admins-images/$rows[image]";
                        }
                        echo "<tr>
                            <td>$index</td>
                            <td>$rows[name]</td>
                            <td>
                                <img src='$profile' class='rounded-circle' width='60' height='60' alt='...'>
                            </td>
                            <td>$rows[birth_date]</td>
                            <td>$rows[gender]</td>
                            <td>
                                " .
                            ($rank === "creator" ?
                                "<button class='btn btn-danger' type='button' data-bs-toggle='modal' data-bs-target='#exampleModal$rows[id]'>
                                        delete admin
                                    </button>
        
                                    <!-- Modal -->
                                    <div style='direction: ltr;' class='modal fade' id='exampleModal$rows[id]' tabindex='-1' data-bs-backdrop='static' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog'>
                                            <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h1 class='modal-title fs-5' id='staticBackdropLabel'>Delete Admin</h1>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                            </div>
                                            <div class='modal-body'>
                                                Do you want to delete this admin?
                                            </div>
                                            <div class='modal-footer'>
                                                <form action='?delete=$rows[id]' method='POST'>
                                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                                    <button type='submit' class='btn btn-success' role='button' name='delete-admin'>Yes</button>
                                                </form>
                                            </div>
                                            </div>
                                        </div>
                                    </div>" : "")
                            . "
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class=" d-flex justify-content-center my-4">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php
                    $total_pages = ceil($num_admins / $limit_admins);

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

                    echo "<li class='page-item'>
                        <a class='page-link bg-success text-white' href='?page=$prev'>Prev</a>
                    </li>";
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<li class='page-item'>
                            <a class='page-link' href='?page=$i'>$i</a>
                        </li>";
                    }
                    echo "<li class='page-item'>
                        <a class='page-link bg-success text-white' href='?page=$next'>Next</a>
                    </li>";
                    ?>

                </ul>
            </nav>
        </div>

        <script src="js/signin.js"></script>
    </body>
<?php
} elseif (isset($_COOKIE["allowed"]) && $_COOKIE["allowed"][0] === "user") {
    header("Location: index.php");
} else {
    header("Location: login.php");
}
?>

</html>