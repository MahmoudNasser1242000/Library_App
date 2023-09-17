<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="layout/pagination.css">
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
if (isset($_COOKIE["allowed"])) {

?>

    <!-- =========================================================== -->
    <?php
    include("database-actions/db-connection.php");

    if (isset($_GET["author"])) {
        $author = $_GET["author"];
    }
    ?>

    <body>
        <?php
        include("layout/header.php");
        ?>

        <!-- ======================================================================= -->

        <div class="mx-4 bg-dark text-white d-flex justify-content-end align-items-center px-3 my-4" style="height: 45px;">
            <span class="mx-1 fs-5">"<?php echo $author ?>"</span>
            <span class="fs-5">جميع كتب المؤلف</span>
        </div>

        <div class="row mx-4" style="direction: rtl;">
            <?php
            if (isset($_GET["page"])) {
                $page = $_GET["page"];
            } else {
                $page = 1;
            }
            $limit_books = 3;
            $start_book = ($page - 1) * $limit_books;

            $query_author_books = "SELECT * FROM books WHERE author='$author' LIMIT $start_book, $limit_books";
            $author_books = mysqli_query($conn, $query_author_books);
            $author_books_num = mysqli_num_rows($author_books);

            if ($author_books_num > 0) {
                while ($row = mysqli_fetch_assoc($author_books)) {
                    if (strlen($row["content"]) > 100) {
                        $row["content"] = mb_substr($row["content"], 0, 101, "UTF-8");
                    }
                    echo "<div class='col-lg-4 col-md-3 col-sm-6' >
                        <div class='card'>
                            <img src='uploads/book-images/$row[image]' style='height: 300px;' class='card-img-top object-fit-fill' alt='...'>
                            <div class='card-body text-center'>
                                <h5 class='card-title'>$row[title]</h5>
                                <p class='card-text mt-3'>$row[content]</p>
                                <a href='show-book.php?book=$row[id]' class='btn btn-dark rounded-1'>تحميل الكتاب</a>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<h1>لا يوجد كتب ....</h1>";
            }

            ?>
        </div>

        <div class="d-flex justify-content-center mt-5">
            <nav>
                <ul class="pagination">
                    <?php
                    $query = "SELECT * FROM books WHERE author='$author'";
                    $res = mysqli_query($conn, $query);
                    $books_num = mysqli_num_rows($res);

                    if ($books_num > 3) {
                        $total_pages = ceil($books_num / $limit_books);

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
                    <a class='page-link bg-dark text-white' href='?author=$author&&page=$prev'>Prev</a>
                </li>";

                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo "<li class='page-item'><a class='page-link' href='?author=$author&&page=$i'>$i</a></li>";
                        }

                        echo "<li class='page-item'>
                    <a class='page-link bg-dark text-white' href='?author=$author&&page=$next'>Next</a>
                </li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>

        <!-- ================================================ -->
        <?php
        include("layout/footer.php");
        ?>    
    </body>
<?php
} else {
    header("Location: login.php");
}
?>

</html>