<?php
session_start();
if (isset($_SESSION['user_logged_in'])) {
    header("Location: admin/dashboard.php");
} else {
    header("Location: login.php");
}
exit();