<?php

date_default_timezone_set("Africa/Cairo");
if (isset($_POST["logout"])) {

    if (isset($_COOKIE["allowed"])) {
        setcookie("allowed[0]", "", time()-1);
        setcookie("allowed[1]", "", time()-1);
    }

    header("Location: index.php");
}
