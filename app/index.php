<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if (!isset($_SESSION["role_id"])) {
        $_SESSION["role_id"] = null;
        $_SESSION['user_id'] = null;
    }
}

if (isset($_SESSION["user_id"])) {
    if ($_SESSION["role_id"] == 1) {
        header("Location: /views/home.php");
    } elseif ($_SESSION["role_id"] == 2) {
        header("Location: /views/home.php");
    } elseif ($_SESSION["role_id"] == 3) {
        header("Location: /views/home.php");
    }
} else {
    header("Location: /views/home.php");
    return;
}