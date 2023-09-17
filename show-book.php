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
if (isset($_COOKIE["allowed"])) {

?>

    <!-- =========================================================== -->
    <?php
    include("database-actions/db-connection.php");

    if (isset($_GET["book"])) {
        $id = $_GET["book"];

        $query_book = "SELECT * FROM books WHERE id='$id'";
        $book = mysqli_query($conn, $query_book);
        $row_book = mysqli_fetch_assoc($book);

        // add comment-rate
        if (isset($_COOKIE["allowed"])) {
            $allowed = $_COOKIE["allowed"];

            if ($allowed[0] === "user") {
                include("database-actions/add-comment-rate.php");
            }
        }
    }
    ?>

    <?php
    if (isset($_GET["book"]) && isset($_GET["message"]) && isset($_GET["role"])) {
        include("database-actions/delete-message.php");
    }
    ?>

    <?php
    if (isset($_GET["update-message"]) && isset($_GET["book"])) {
        $index = $_GET["update-message"];

        if (!empty($row_book["comment_rate"])) {
            $decode_message = unserialize(base64_decode($row_book["comment_rate"]));

            $book_message = $decode_message[$index];
        }

        include("database-actions/update-message.php");
    }
    ?>

    <body>

        <?php
        include("layout/header.php");
        ?>

        <!-- ======================================================================= -->
        <div class="alert my-4 mx-auto text-center w-50" role="alert">
            <?php echo isset($_COOKIE["book_alert"]) ? $_COOKIE["book_alert"] : ""; ?>
        </div>

        <?php
        $allow = "true";
        if (isset($_COOKIE["allowed"])) {
            $allowed = $_COOKIE["allowed"];

            if ($allowed[0] === "user") {
                $query = "SELECT * FROM users WHERE token='$allowed[1]'";
                $res = mysqli_query($conn, $query);
                $acount = mysqli_fetch_assoc($res);

                $allow = $acount["allow"];
            }
        }
        ?>
        <div class="container my-5 d-flex justify-content-between align-items-center">
            <div class="row d-flex flex-row-reverse justify-content-between">
                <img src="uploads/book-images/<?php echo $row_book["image"] ?>" class="col-lg-5 col-md-6 object-fit-fill" style="height: 500px;" alt="..." />

                <div class="col-lg-7 col-md-6" style="direction: rtl;">
                    <h2 class="text-secondary"><?php echo $row_book["title"] ?></h2>
                    <hr />
                    <p>
                        <?php echo $row_book["content"] ?>
                    </p>
                    <hr class="w-50">
                    <div class="d-flex">
                        <?php
                        if (!empty($row_book["author"])) {
                            echo "<p>
                                <span><i class='fa-solid fa-user text-secondary'></i></span>
                                <a class='text-dark text-decoration-none' href='" . ($allow === "true" ? "show-author-books.php?author=$row_book[author]" : "") . "'>$row_book[author]</a>
                            </p>";
                        }
                        ?>
                        <p class="mx-2">
                            <span><i class="fa-solid fa-calendar-days text-secondary"></i></span>
                            <span><?php echo $row_book["date_create"] ?></span>
                        </p>
                        <?php
                        if (!empty($row_book["date_update"])) {
                            echo "<p>
                                <span><i class='fa-solid fa-pen-to-square text-secondary'></i></span>
                                <span>$row_book[date_update]</span>
                            </p>";
                        }
                        ?>
                    </div>
                    <a class="btn btn-dark rounded-1 mt-3 <?php echo $allow !== "true" ? "disabled" : "" ?>" role="button" href="uploads/book-files/<?php echo $row_book["book"] ?>" target="_blank">قراءة الكتاب</a>
                    <a class="btn btn-dark rounded-1 mt-3 <?php echo $allow !== "true" ? "disabled" : "" ?>" role="button" href="uploads/book-files/<?php echo $row_book["book"] ?>" download="<?php echo $row_book["title"] ?>">تحميل الكتاب</a>
                </div>
            </div>
        </div>

        <!-- ======================================================================= -->
        <?php
        if (isset($_COOKIE["allowed"])) {
            $allowed = $_COOKIE["allowed"];

            if ($allowed[0] === "user") {
                # code...

        ?>
                <div style="direction: rtl;" class="container my-5">
                    <h2 class="my-4">رأيك في االكتاب</h2>
                    <!-- <hr class='my-4 w-75' /> -->
                    <form method="POST">
                        <div class="my-3" style="direction: ltr;">
                            <div class="d-flex justify-content-between">
                                <span class="me-2">NoN</span>
                                <span class="me-2">0</span>
                                <span class="me-2">0.5</span>
                                <span class="me-2">1</span>
                                <span class="me-2">1.5</span>
                                <span class="me-2">2</span>
                                <span class="me-2">2.5</span>
                                <span class="me-2">3</span>
                                <span class="me-2">3.5</span>
                                <span class="me-2">4</span>
                                <span class="me-2">4.5</span>
                                <span class="me-2">5</span>
                            </div>
                            <input type="range" class="form-range" min="-0.5" max="5" step="0.5" value="<?php echo isset($_GET["update-message"]) ? (!empty($book_message["rate"]) ? $book_message["rate"] : "-0.5") : "-0.5" ?>" id="customRange3" name="rate">
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control texterea" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" name="comment">
                    <?php echo isset($_GET["update-message"]) ? $book_message["comment"] : "" ?>
                </textarea>
                            <label for="floatingTextarea2">تعليقات</label>
                        </div>
                        <button class="btn btn-dark rounded-1 mt-3 <?php echo $allow !== "true" ? "disabled" : "" ?>" type="submit" name="<?php echo isset($_GET["update-message"]) ? "update-comment-rate" : "add-comment-rate" ?>">
                            <?php echo isset($_GET["update-message"]) ? "Update Your Comment And Rate" : "Add Your Comment And Rate" ?>
                        </button>
                    </form>
                </div>
        <?php
            }
        }
        ?>
        <!-- ======================================================================= -->
        <?php
        if (isset($_COOKIE["allowed"])) {
            $allowed = $_COOKIE["allowed"];

            if ($allowed[0] === "user") {
                $query_user = "SELECT * FROM users WHERE token='$allowed[1]'";
                $user = mysqli_query($conn, $query_user);
                $row_user = mysqli_fetch_assoc($user);

                if (empty($row_user["image"])) {
                    if ($row_user["gender"] === "male") {
                        $profile = "uploads/default-profile-images/male_profile_image.jpg";
                    } else {
                        $profile = "uploads/default-profile-images/female_profile_image2.jpg";
                    }
                } else {
                    $profile = "uploads/users-images/$row_user[image]";
                }


                //==============================================
                if (!empty($row_book["comment_rate"])) {
                    $messages = unserialize(base64_decode($row_book["comment_rate"]));

                    if ($allow === "true") {
                        $disabled = "";
                    } else {
                        $disabled = "disabled";
                    }


                    for ($i = 0; $i < sizeof($messages); $i++) {
                        if ($messages[$i]["user"] === $row_user["token"]) {

                            $comment = $messages[$i]["comment"];

                            if (!empty($messages[$i]["date-update"])) {
                                $date = $messages[$i]["date-update"];
                            } else {
                                $date = $messages[$i]["date-create"];
                            }

                            if (empty($messages[$i]["rate"])) {
                                $rate = "";
                                $stars = "";
                            } else {
                                $rate = $messages[$i]["rate"];

                                $stars = "";
                                for ($index = 0; $index < $rate; $index++) {
                                    if (($rate - $index) > "0.5") {
                                        $stars .= "<i class='fa-solid fa-star' style='color: #cfc817;'></i>";
                                    } else {
                                        $stars .= "<i class='fa-solid fa-star-half-stroke' style='color: #cfc817;'></i>";
                                    }
                                }
                            }

                            echo "<div class='container my-5' style='direction: rtl;'>
                                <h5>التعليق الخاص بك</h5>
                                <hr class='my-4'/>

                                <div class='card'>
                                    <div class='card-header d-flex justify-content-between'>
                                        <div>
                                            $row_user[name]
                                            <img src='$profile' class='rounded-circle ms-2' width='35' height='35' alt='...'>
                                        </div>
                                        <div class='d-flex align-items-center'>
                                            $date
                                            <i class='fa-solid fa-calendar-days me-2' style='color: #090a0c;'></i>
                                        </div>
                                    </div>
                                    <div class='card-body'>
                                        <h5 class='card-title mb-3'>
                                            <span class='text-center text-warning d-block' style='width: 112px;'>
                                                $rate
                                            </span>
                                            <div class='d-flex justify-content-end' style='direction: ltr;'>
                                                $stars
                                            </div>
                                        </h5>
                                        <p class='card-text mb-2'>$comment</p>

                                        <div class='d-flex justify-content-end'>
                                            <a href='?book=$id&&update-message=$i' class='btn btn-warning $disabled'>
                                                <i class='fa-solid fa-pen-to-square' style='color: #ffffff;'></i>
                                            </a>
                                            
                                            <button class='btn btn-danger mx-3 $disabled' type='button' data-bs-toggle='modal' data-bs-target='#exampleModal$i'>
                                                <i class='fa-solid fa-trash' style='color: #ffffff;'></i>
                                            </button>
                                            <!-- Modal -->
                                            <div style='direction: ltr;' class='modal fade' id='exampleModal$i' tabindex='-1' data-bs-backdrop='static' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                    <div class='modal-header'>
                                                        <h1 class='modal-title fs-5' id='staticBackdropLabel'>Delete Message</h1>
                                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                    </div>
                                                    <div class='modal-body'>
                                                        Do you want to delete this message?
                                                    </div>
                                                    <div class='modal-footer'>
                                                        <form action='?book=$id&&message=$i&&role=user' method='POST'>
                                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                                            <button type='submit' class='btn btn-success' role='button' name='delete-category'>Yes</button>
                                                        </form>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                        }
                    }
                }
            }
        }
        ?>
        <!-- ======================================================================= -->
        <?php
        $query_book_category = "SELECT * FROM books WHERE category='$row_book[category]' AND id!=$row_book[id] LIMIT 4";
        $book_category = mysqli_query($conn, $query_book_category);
        $book_category_num = mysqli_num_rows($book_category);

        if ($allow === "true") {
            if ($book_category_num > 0) {
                echo "<div class='container' style='direction: rtl; margin-top: 80px;'>
                    <h2>كتب ذات صله</h2>
                    <hr class='my-4'/>
            
                    <div class='row justify-content-between'>
                ";

                while ($row_book_category = mysqli_fetch_assoc($book_category)) {
                    echo "<div class='col-lg-3 col-md-4 col-sm-6 other-books'>
                        <div class='same-books d-flex flex-column align-items-center justify-content-around' style='height: 300px;'>
                            <img src='uploads/book-images/$row_book_category[image]' class='h-75' alt='...'/>
                            <a class='mt-2 mx-3 text-center fs-5 text-dark text-decoration-none' href='show-book.php?book=$row_book_category[id]'>$row_book_category[title]</a>
                        </div>
                    </div>";
                }

                echo "</div>
                </div>";
            }
        } else {

            echo '<div class="container" style="direction: rtl; margin-top: 80px;">
                <h2>كتب لنفس المؤلف</h2>
                <hr class="my-4"/>
        
                <div class="row justify-content-between">
                    <div style="direction: rtl;">
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
                    </div>
                </div>
            </div>';
        }

        ?>
        <!-- ======================================================================= -->

        <?php
        $query_book_author = "SELECT * FROM books WHERE author='$row_book[author]' AND id!=$row_book[id] LIMIT 4";
        $book_author = mysqli_query($conn, $query_book_author);
        $book_author_num = mysqli_num_rows($book_author);

        if ($allow === "true") {

            if ($book_author_num > 0) {
                echo "<div class='container' style='direction: rtl; margin-top: 80px;'>
                <h2>كتب لنفس المؤلف</h2>
                <hr class='my-4'/>
        
                <div class='row justify-content-between'>
            ";

                while ($row_book_author = mysqli_fetch_assoc($book_author)) {
                    echo "<div class='col-lg-3 col-md-4 col-sm-6 other-books'>
                    <div class='same-books d-flex flex-column align-items-center justify-content-around' style='height: 300px;'>
                        <img src='uploads/book-images/$row_book_author[image]' class='h-75' alt='...'/>
                        <a class='mt-2 mx-3 text-center fs-5 text-dark text-decoration-none' href='show-book.php?book=$row_book_author[id]'>$row_book_author[title]</a>
                    </div>
                </div>";
                }

                echo "</div>
            </div>";
            }
        } else {

            echo '<div class="container mb-4" style="direction: rtl; margin-top: 80px;">
                <h2>كتب لنفس المؤلف</h2>
                <hr class="my-4"/>
        
                <div class="row justify-content-between">
                    <div style="direction: rtl;">
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
                    </div>
                </div>
            </div>';
        }
        ?>

        <!-- ================================================ -->
        <?php
        include("layout/footer.php");
        ?>
        <!-- ================================================ -->

        <script src="js/books.js"></script>
    </body>
<?php
} else {
    header("Location: login.php");
}
?>

</html>