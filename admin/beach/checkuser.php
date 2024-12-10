<?php
session_start();
require_once('../../config/config.php');
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'owner') {
    header('Location: ../login.php');
    exit();
}
?>