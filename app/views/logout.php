<?php

require "../controllers/SessionController.php";

$_SESSION["user_id"] = null;
$_SESSION["role_id"] = null;
$_SESSION["username"] = null;

header("Location:../../index.php");