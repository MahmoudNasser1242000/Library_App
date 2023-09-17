<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    if (isset($_GET["book"])) {
        $id = $_GET["book"];

        $query_book = "SELECT * FROM books WHERE id=$id";
        $book = mysqli_query($conn, $query_book);
        $row_book = mysqli_fetch_assoc($book);
    }
    ?>

    <?php
    if (isset($_GET["book"]) && isset($_GET["delete-message"]) && isset($_GET["role"])) {
        include("database-actions/delete-message.php");
    }
    ?>

    <body>
        <?php
        include("layout/control-panel.php")
        ?>

        <div class="alert mt-4 mx-auto text-center w-50" role="alert">
            <?php echo isset($_COOKIE["book_alert"]) ? $_COOKIE["book_alert"] : "" ?>
        </div>

        <h3 class="w-50 mx-auto my-5 text-center">Messages of "<?php echo $row_book["title"] ?>"</h3>

        <form class="w-50 mx-auto my-5" action="<?php echo "?book=$id" ?>" method="POST">
            <input type="search" class="form-control search-input" name="search">
            <div class="mt-3 text-center">
                <button class="btn btn-dark" type="submit" name="search-user">search with Name</button>
                <button class="btn btn-dark mx-2" type="submit" name="search-comment">search with Comment</button>
            </div>
            <div class="text-center mt-3">
                <a class="btn btn-dark" href="<?php echo "?book=$id" ?>" role="button">get all Comment</a>
            </div>
        </form>

        <?php
        if (!empty($row_book["comment_rate"])) {
            $messages = unserialize(base64_decode($row_book["comment_rate"]));
            $start_message = 0;
            $complete = "";

            if (isset($_POST["search-user"])) {
                $filter_messages = [];
                foreach ($messages as $item) {
                    $query_users = "SELECT * FROM users WHERE token='$item[user]'";
                    $res = mysqli_query($conn, $query_users);
                    $user_profile = mysqli_fetch_assoc($res);

                    if (!empty($user_profile)) {
                        if (str_contains(trim($user_profile["name"]), trim($_POST["search"]))) {
                            array_push($filter_messages, $item);
                        }
                    }
                }

                $messages = $filter_messages;
                $count_messages = sizeof($filter_messages);
            } elseif (isset($_POST["search-comment"])) {
                $filter_messages = [];
                foreach ($messages as $item) {
                    if (str_contains(trim($item["comment"]), trim($_POST["search"]))) {
                        array_push($filter_messages, $item);
                    }
                }

                $messages = $filter_messages;
                $count_messages = sizeof($filter_messages);
            } else {
                if (isset($_GET["page"])) {
                    $page = $_GET["page"];
                } else {
                    $page = 1;
                }
                $limit_messages = 5;
                $start_message = ($page - 1) * $limit_messages;

                $complete = "true";
                $count_messages = sizeof($messages);
            }

            for ($i = $start_message; $i < $count_messages; $i++) {
                if ($complete === "true") {
                    if (($i >= $limit_messages * $page)) {
                        break;
                    }
                }

                $user_token = $messages[$i]["user"];
                $query_user = "SELECT * FROM users WHERE token='$user_token'";
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

                // =======================================
                $comment = $messages[$i]["comment"];
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
                $date_create = $messages[$i]["date-create"];
                $date_update = $messages[$i]["date-update"];

                echo "<div class='card text-center mb-4 rounded-0'>
                    <div class='card-header'>
                        $row_user[name] 
                        <img src='$profile' class='rounded-circle ms-2' width='35' height='35' alt='...'>
                    </div>
                    <div class='card-body'>
                        <h5 class='card-title text-warning'>
                            $rate
                            <div>
                                $stars   
                            </div>
                        </h5>
                        <p class='card-text'>$comment</p>

                        <button class='btn btn-danger' type='button' data-bs-toggle='modal' data-bs-target='#exampleModal$i'>
                            Delete Message
                        </button>
                        <!-- Modal -->
                        <div style='direction: ltr;' class='modal fade' id='exampleModal$i' tabindex='-1' data-bs-backdrop='static' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                <div class='modal-header'>
                                    <h1 class='modal-title fs-5' id='staticBackdropLabel'>Delete Message</h1>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body text-start'>
                                    Do you want to delete this message?
                                </div>
                                <div class='modal-footer'>
                                    <form action='?book=$id&&delete-message=$i&&role=admin' method='POST'>
                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                        <button type='submit' class='btn btn-success' role='button' name='delete-message'>Yes</button>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='card-footer text-body-secondary'>
                        <span>
                            $date_create
                            <i class='fa-solid fa-calendar-days ms-2' style='color: #090a0c;'></i>
                        </span>
                        " .
                    (!empty($date_update) ?
                        "<span class='mx-3'>
                        $date_update
                        <i class='fa-solid fa-pen-to-square ms-2' style='color: #030303;'></i>
                    </span>" : ""
                    )
                    .
                    "
                    </div>
                </div>";
            }
        }
        ?>

        <!-- ============================================================== -->

        <?php
        $num_messages = sizeof($messages);
        if ($num_messages > 5 && $complete === "true") {
            $total_pages = ceil($num_messages / $limit_messages);

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
                    <li class='page-item rounded-1'>
                        <a class='page-link bg-success text-white' href='?book=$id&&page=$prev'>Prev</a>
                    </li>
            ";

            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<li class='page-item'><a class='page-link' href='?book=$id&&page=$i'>$i</a></li>";
            }

            echo "      <li class='page-item bg-success rounded-1'>
                        <a class='page-link bg-success text-white' href='?book=$id&&page=$next'>Next</a>
                    </li>
                </ul>
            </nav>";
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