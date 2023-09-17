<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/books.css">
    <link rel="stylesheet" href="layout/control-panel.css">

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
    if (isset($_GET["index"])) {
        include("database-actions/delete-book.php");
    }
    ?>

    <body style="height: 100vh;">

        <?php
        include("layout/control-panel.php")
        ?>

        <div class="alert mt-4 mx-auto text-center w-50" role="alert">
            <?php echo isset($_COOKIE["book_alert"]) ? $_COOKIE["book_alert"] : ""; ?>
        </div>

        <div class="m-4">
            <table class="table" style="direction: rtl;">
                <thead>
                    <tr>
                        <th>#id</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Cover</th>
                        <th>book</th>
                        <th>Date</th>
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
                    $limit_of_books = 4;
                    $start_book = ($page - 1) * $limit_of_books;

                    $query = "SELECT * FROM books ORDER BY id DESC LIMIT $start_book, $limit_of_books";
                    $res = mysqli_query($conn, $query);


                    $index = 0;
                    while ($row = mysqli_fetch_assoc($res)) {
                        $index += 1;
                        echo "<tr>
                        <td>$index</td>
                        <td class='book-title'>$row[title]</td>
                        <td>$row[category]</td>
                        <td><img src='uploads/book-images/$row[image]' width='100' height='100' alt='...'/></td>
                        <td class='book'><a href='uploads/book-files/$row[book]' target='_blank' class='text-dark text-decoration-none'>$row[book]</a></td>
                        <td>$row[date_create]</td>
                        <td>
                            <a href='books.php?id=$row[id]' class='btn btn-warning' role='button'>تعديل الكتاب</a>

                            <button class='btn btn-danger' type='button' data-bs-toggle='modal' data-bs-target='#exampleModal$row[id]'>
                                حذف الكتاب
                            </button>
                            <!-- Modal -->
                            <div style='direction: ltr;' class='modal fade' id='exampleModal$row[id]' tabindex='-1' data-bs-backdrop='static' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h1 class='modal-title fs-5' id='staticBackdropLabel'>Delete Book</h1>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        Do you want to delete this book?
                                    </div>
                                    <div class='modal-footer'>
                                        <form action='?index=$row[id]' method='POST'>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                            <button type='submit' class='btn btn-success' role='button' name='delete-book'>Yes</button>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php
        $query_books = "SELECT * FROM books";
        $books = mysqli_query($conn, $query_books);
        $num_books = mysqli_num_rows($books);

        if ($num_books > 3) {
            $limit_of_pages = ceil($num_books / $limit_of_books);

            if (($page - 1) > 0) {
                $prev = $page - 1;
            } else {
                $prev = 1;
            }

            if (($page + 1) <= $limit_of_pages) {
                $next = $page + 1;
            } else {
                $next = $limit_of_pages;
            }

            echo "<div class='mt-4 d-flex justify-content-center'>
                        <nav aria-label='Page navigation example'>
                    <ul class='pagination'>";

            echo "<li class='page-item'>
                        <a class='page-link bg-success text-white' href='?page=$prev'>Prev</a>
                    </li>";
            for ($i = 1; $i <= $limit_of_pages; $i++) {
                echo "<li class='page-item'><a class='page-link' href='?page=$i'>$i</a></li>";
            }
            echo "<li class='page-item'>
                        <a class='page-link bg-success text-white' href='?page=$next'>Next</a>
                    </li>";

            echo "</ul>
                        </nav>
                    </div>";
        }
        ?>


        <script src="js/books.js"></script>
    </body>
<?php
} elseif (isset($_COOKIE["allowed"]) && $_COOKIE["allowed"][0] === "user") {
    header("Location: index.php");
} else {
    header("Location: login.php");
}
?>

</html>