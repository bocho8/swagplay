<?php
session_start();

if (isset($_SESSION['is_logged_in'])) {
    $_SESSION = [];

    session_destroy();

    header("Location: ../../index.php");
    exit();
} else {
    header("Location: ../../index.php");
    exit();
}
?>
