<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="layout/header.css">

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
?>

<?php
$allow = "true";
if (isset($_COOKIE["allowed"])) {
    $allowed = $_COOKIE["allowed"];

    if ($allowed[0] === "user") {
        $query_profile = "SELECT * FROM users WHERE token='$allowed[1]'";
        $profile = mysqli_query($conn, $query_profile);
        $row_user = mysqli_fetch_assoc($profile);

        $allow = $row_user["allow"];
    }
}
?>

<body style="height: 100vh;">

    <?php
    include("layout/header.php");
    ?>

    <!-- ======================================================================= -->
    <?php
    if (!isset($_COOKIE["allowed"])) {
        echo '<div class="alert alert-success mt-4 mx-auto text-center d-block position-fixed fixed-top opacity-75 w-50" role="alert">
                You have to login first!
            </div>';
    }
    ?>

    <div style="height: 500px;" class="position-relative cover">
        <img src="images/pexels-photo-2908984.jpeg" class="w-100 h-100 object-fit-fill" alt="..." />

        <div class="w-100 h-100 bg-dark position-absolute top-0 start-0 end-0 opacity-25">
        </div>

        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
            <span class="Dabja1 w-50 text-white text-center" style="direction: rtl;">
                مكتبه تحميل الكتب مجانا.
            </span>
        </div>

        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
            <span class="Dabja2 w-50 text-white text-center" style="direction: rtl;">
                من اجل نشر المعرفه و الثقافه و غرس حب القراءه بين المتحدثين باللغه العربيه.
            </span>
        </div>
    </div>

    <!-- ======================================================================= -->

    <div class="container" style="margin-top: 80px;">
        <div class="d-flex justify-content-between align-items-center" style="direction: rtl;">
            <h2>الكتب</h2>

            <div class="dropdown">
                <a class="btn btn-dark dropdown-toggle bg-opacity-75 border-0 d-flex justify-content-between align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class='mx-1'>فئات الكتب</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class='dropdown-item' href='index.php'>الكل</a></li>
                    <?php
                    $query_categories = "SELECT * FROM categories ORDER BY id DESC";
                    $categories = mysqli_query($conn, $query_categories);
                    while ($row_category = mysqli_fetch_assoc($categories)) {
                        echo "<li><a class='dropdown-item' href='" . ($allow === "true" && isset($_COOKIE["allowed"]) ? "?category=$row_category[name]" : "") . "'>$row_category[name]</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
        <hr class="my-4" />

        <div class="my-4 row d-flex justify-content-between flex-md-row flex-sm-column flex-wrap">
            <div class="col-md-4 col-sm-12 text-md-start text-sm-center mb-md-0 mb-sm-3">
                <a class="btn btn-outline-dark <?php echo $allow !== "true" || !isset($_COOKIE["allowed"]) ? "disabled" : "" ?>" role="button" href="index.php">Get all books</a>
            </div>
            <form class="d-flex col-md-8 col-sm-12" action="index.php" role="search" method="POST" style="direction: rtl;">
                <input class="form-control ms-2 w-25" type="search" name="search" placeholder="Search..." aria-label="Search">
                <button class="btn btn-outline-dark <?php echo $allow !== "true" || !isset($_COOKIE["allowed"]) ? "disabled" : "" ?>" type="submit" name="search-name">Search book name</button>
                <button class="btn btn-outline-dark mx-2 <?php echo $allow !== "true" || !isset($_COOKIE["allowed"]) ? "disabled" : "" ?>" type="submit" name="search-author">Search author name</button>
            </form>
        </div>

        <div class="row">
            <?php
            //books categories 
            if (isset($_GET["category"])) {
                $category = "WHERE category='$_GET[category]'";
            } else {
                $category = "";
            }

            //pagination
            $complete = "";

            if (isset($_GET["page"])) {
                $page = $_GET["page"];
            } else {
                $page = 1;
            }
            $limit_books = 3;
            $start_book = ($page - 1) * $limit_books;

            if (isset($_POST["search-name"])) {
                $query_books = "SELECT * FROM books WHERE title LIKE '%$_POST[search]%' ORDER BY id DESC";
            } elseif (isset($_POST["search-author"])) {
                $query_books = "SELECT * FROM books WHERE author LIKE '%$_POST[search]%' ORDER BY id DESC";
            } else {
                $complete = "true";
                $query_books = "SELECT * FROM books $category ORDER BY id DESC LIMIT $start_book, $limit_books";
            }

            $books = mysqli_query($conn, $query_books);
            $books_nums = mysqli_num_rows($books);

            if (isset($_COOKIE["allowed"])) {
                if ($books_nums > 0) {
                    while ($row_books = mysqli_fetch_assoc($books)) {
                        if (strlen($row_books["content"]) > 100) {
                            $row_books["content"] = mb_substr($row_books["content"], 0, 101, "UTF-8") . "...";
                        }

                        echo "<div class='col-lg-4 col-md-6'>
                                <div class='card'>
                                    <img src='uploads/book-images/$row_books[image]' style='height: 300px;' class='card-img-top object-fit-fill' alt='...'>
                                    <div class='card-body text-center'>
                                        <h5 class='card-title'>$row_books[title]</h5>
                                        <p class='card-text mt-3'>$row_books[content]</p>
                                        <a href='show-book.php?book=$row_books[id]' class='btn btn-dark rounded-1'>تحميل الكتاب</a>
                                    </div>
                                </div>
                            </div>";
                    }
                } else {
                    echo "<h3 style='direction: rtl;'>لا يوجد كتب ...</h3>";
                }
            } else {
                echo '<div style="direction: rtl;">
                        <div class="card w-25" aria-hidden="true">
                            <img src="uploads/default-profile-images/white3.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                            <h5 class="card-title placeholder-glow">
                                <span class="placeholder col-6"></span>
                            </h5>
                            <p class="card-text placeholder-glow">
                                <span class="placeholder col-6"></span>
                                <span class="placeholder col-5"></span>
                            </p>
                            <a class="btn btn-dark disabled placeholder col-5 py-1 rounded-1"></a>
                            </div>
                        </div>
                    </div>';
            }

            ?>
        </div>
    </div>


    <?php
    $query = "SELECT * FROM books $category ORDER BY id DESC";
    $res = mysqli_query($conn, $query);
    $books_num = mysqli_num_rows($res);
    $total_pages = ceil($books_num / $limit_books);

    if ($allow === "true") {
        if ($books_num > 3 && $complete === "true") {

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

            if (isset($_GET["category"])) {
                $ctg = "category=$_GET[category]&&";
            } else {
                $ctg = "";
            }

            if ($books_num > 3 && isset($_COOKIE["allowed"])) {
                echo "<div class='mt-5 d-flex justify-content-center'>
                        <nav aria-label='Page navigation example'>
                    <ul class='pagination'>";

                echo "<li class='page-item'>
                        <a class='page-link bg-dark text-white' href='?$ctg page=$prev'>Prev</a>
                    </li>";

                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<li class='page-item'><a class='page-link' href='?$ctg page=$i'>$i</a></li>";
                }

                echo "<li class='page-item'>
                        <a class='page-link bg-dark text-white' href='?$ctg page=$next'>Next</a>
                    </li>";

                echo "</ul>
                        </nav>
                    </div>";
            }
        }
    }
    ?>

    <!-- ================================================ -->
    <?php
    include("layout/footer.php");
    ?>
</body>

</html>