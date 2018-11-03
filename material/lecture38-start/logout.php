<?php
    include "includes/init.php";
    unset($_SESSION['username']);
    if (isset($_COOKIE['username'])) {
        setcookie('username', 'delete', time()-3600);
    }
    redirect('index.php');
?>