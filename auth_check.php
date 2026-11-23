<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION["matric"])) {
    header("Location: login_page.php");
    exit;
}