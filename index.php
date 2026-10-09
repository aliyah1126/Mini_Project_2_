<?php
session_start();
if (isset($_SESSION["user_id"])) {
    if ($_SESSION["role"] === "admin") {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: student/dashboard.php");
    }
} else {
    header("Location: login.php");
}
exit;
?>