<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    // $_SESSION['user_id'] = null;
    // $_SESSION['role_id'] = null;
    // $_SESSION['username'] = null;
}