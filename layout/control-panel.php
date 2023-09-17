<?php
include("database-actions/db-connection.php");

if (isset($_COOKIE["allowed"])) {
    $allowed = $_COOKIE["allowed"];

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
?>

<?php
include("database-actions/logout.php");
?>
<header class="bg-body-tertiary py-2" style="direction: rtl;">
    <div class="container">
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-lg-2 col-md-2 col-sm-12 mx-0 mb-2">
                <button class="btn border-1 bg-success bg-opacity-75" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                    <i class="fa-solid fa-bars" style="color: #ffffff;"></i>
                </button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                    <div class="offcanvas-header">
                        <h3 class="offcanvas-title text-success" id="offcanvasRightLabel">لوحه التحكم</h3>
                        <button type="button" class="btn-close shadow-none border-0" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body px-0">
                        <ul class="list-group w-100 p-0 rounded-0">
                            <a href="categories.php" class="list-group-item list-group-item-action" aria-current="true">
                                Add categories
                            </a>
                            <a href="books.php" class="list-group-item list-group-item-action">Add Books</a>
                            <a href="book-actions.php" class="list-group-item list-group-item-action">Show All Books</a>
                            <a href="index.php" class="list-group-item list-group-item-action">Go Home</a>
                            <a href="dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
                            <a href="signin.php?role=admin" class="list-group-item list-group-item-action">Add New Admin</a>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-5 col-sm-12 justify-content-sm-center mx-0">
                <div class="d-flex justify-content-evenly align-items-center">
                    <p class="text-dark m-0">معلومات المستخدم</p>

                    <div class="dropdown">
                        <a class="btn btn-success dropdown-toggle bg-opacity-75 border-0 d-flex justify-content-between align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo $image ?>" class=' rounded-circle mx-1' width='33' height='33'>
                            <span class='mx-1'><?php echo $row["name"] ?></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="signin.php?update=<?php echo $row["token"] ?>">تعديل الاكونت</a></li>
                            <li>
                                <form method='POST'>
                                    <button type='submit' class='dropdown-item' class='btn border-0' name='logout'>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>