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
    include("database-actions/add-books.php");
    ?>

    <?php
    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        $query_book = "SELECT * FROM books WHERE id=$id";
        $select_book = mysqli_query($conn, $query_book);
        $book = mysqli_fetch_assoc($select_book);

        include("database-actions/update-books.php");
    }
    ?>

    <body style="height: 100vh;">
        <?php
        include("layout/control-panel.php");
        ?>

        <div class="alert mt-4 mx-auto text-center w-50" role="alert">
            <?php echo isset($_COOKIE["book_alert"]) ? $_COOKIE["book_alert"] : ""; ?>
        </div>

        <form method="POST" class="mx-5 mt-4" style="direction: rtl;" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="formGroupExampleInput" class="form-label">Book Title</label>
                <input type="text" class="form-control form-control-lg" id="formGroupExampleInput" name="title" value="<?php echo isset($_GET["id"]) ? $book["title"] : (isset($title) ? $title : '') ?>">
            </div>

            <div class="my-4">
                <label for="exampleDataList" class="form-label">Select Category</label>
                <input class="form-control" list="datalistOptions" id="exampleDataList" name="category" value="<?php echo isset($_GET["id"]) ? $book["category"] : "" ?>">
                <datalist id="datalistOptions">
                    <?php
                    $query_category = "SELECT * FROM categories ORDER BY id DESC";
                    $select_category = mysqli_query($conn, $query_category);

                    while ($row = mysqli_fetch_assoc($select_category)) {
                        echo "<option value='$row[name]'>$row[name]</option>";
                    }
                    ?>
                </datalist>
            </div>

            <button type="button" class="btn btn-dark d-block">
                <label for="formFile1" class="form-label m-0 h-100">Add Book Cover</label>
                <input class="form-control d-none w-100 h-100" type="file" id="formFile1" name="image">
                <i class="fa-solid fa-camera mx-1 h-100" style="color: #ffffff;"></i>
            </button>

            <button type="button" class="btn btn-dark d-block my-3">
                <label for="formFile2" class="form-label m-0 h-100">Add Book File</label>
                <input class="form-control d-none w-100 h-100" type="file" id="formFile2" name="book-file">
                <i class="fa-solid fa-file-export mx-1" style="color: #fafafa;"></i>
            </button>

            <div class="mb-3">
                <label for="formGroupExampleInput" class="form-label">Book Author</label>
                <input type="text" class="form-control form-control-lg" id="formGroupExampleInput" name="author" value="<?php echo isset($_GET["id"]) ? $book["author"] : "" ?>">
            </div>

            <div class="mt-3">
                <label for="exampleFormControlTextarea1" class="form-label">Book Content</label>
                <textarea class="form-control texterea" id="exampleFormControlTextarea1" rows="3" name="content">
                <?php echo isset($_GET["id"]) ? $book["content"] : "" ?>
            </textarea>
            </div>

            <button type="submit" class="btn btn-success mt-4" name="<?php echo isset($_GET["id"]) ? "update-book" : "add-book" ?>">
                <?php echo isset($_GET["id"]) ? "Update Book" : "Add Book" ?>
            </button>
        </form>

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