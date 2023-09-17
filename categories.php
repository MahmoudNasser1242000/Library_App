<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/categories.css">
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
    include("database-actions/add-category.php");
    ?>

    <?php
    include("database-actions/db-connection.php");

    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    }
    $limit_categories = 4;
    $start_category = ($page - 1) * $limit_categories;

    $select = "SELECT * FROM categories LIMIT $start_category, $limit_categories";
    $res = mysqli_query($conn, $select);
    ?>
    <!-- ======================================================= -->
    <?php
    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        $query = "SELECT * FROM categories WHERE id=$id";
        $res2 = mysqli_query($conn, $query);
        $row2 = mysqli_fetch_assoc($res2);


        include("database-actions/update-category.php");
    }
    ?>
    <!-- ======================================================= -->
    <?php
    if (isset($_GET["index"])) {
        include("database-actions/delete-category.php");
    }
    ?>

    <body style="height: 100vh;">
        <?php
        include("layout/control-panel.php")
        ?>

        <div class="alert mt-4 mx-auto text-center w-50" role="alert">
            <?php echo isset($_COOKIE["category_alert"]) ? $_COOKIE["category_alert"] : "" ?>
        </div>

        <form class="mt-4 mx-5" style="direction: rtl;" method="POST">
            <div class="mb-3">
                <label for="formGroupExampleInput" class="form-label">اضافه تصنيف</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="write here..." name="name" value="<?php echo isset($row2) ? $row2["name"] : "" ?>">
            </div>

            <button type="submit" class="btn btn-success opacity-75" name="<?php echo isset($row2) ? "update-category" : "add-category" ?>">
                <?php echo isset($row2) ? "تعديل التصنيف" : "اضافه تصنيف" ?>
            </button>
        </form>

        <div class="m-5">
            <table class="table" style="direction: rtl;">
                <thead>
                    <tr>
                        <th>#id</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 0;
                    while ($row = mysqli_fetch_assoc($res)) {
                        $index += 1;
                        echo "<tr>
                        <td>$index</td>
                        <td>$row[name]</td>
                        <td>$row[date]</td>
                        <td>
                            <a class='btn btn-warning' href='?id=$row[id]' role='button'>تعديل التصنيف</a>
                            <button class='btn btn-danger' type='button' data-bs-toggle='modal' data-bs-target='#exampleModal$row[id]'>
                                حذف التصنيف
                            </button>

                            <!-- Modal -->
                            <div style='direction: ltr;' class='modal fade' id='exampleModal$row[id]' tabindex='-1' data-bs-backdrop='static' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h1 class='modal-title fs-5' id='staticBackdropLabel'>Delete Category</h1>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        Do you want to delete this category?
                                    </div>
                                    <div class='modal-footer'>
                                        <form action='?index=$row[id]' method='POST'>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                            <button type='submit' class='btn btn-success' role='button' name='delete-category'>Yes</button>
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


            <?php
            $categories = "SELECT * FROM categories";
            $result = mysqli_query($conn, $categories);
            $num_categories = mysqli_num_rows($result);

            if ($num_categories > 3) {
                $total_pages = ceil($num_categories / $limit_categories);

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

                echo "<div class='d-flex justify-content-center mt-5'>
                            <nav aria-label='Page navigation'>
                        <ul class='pagination'>";

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

                echo "</ul>
                            </nav>
                        </div>";
            }
            ?>


        </div>

        <script src="js/category.js"></script>
    </body>
<?php
} elseif (isset($_COOKIE["allowed"]) && $_COOKIE["allowed"][0] === "user") {
    header("Location: index.php");
} else {
    header("Location: login.php");
}
?>

</html>