<?php
    session_start();
    unset($_SESSION['name']);
    unset($_SESSION['position']);
    header('Location: main.php');
?>