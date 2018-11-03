<?php
    include "../../includes/init.php";
    if (logged_in()) {
        $username=$_SESSION['username'];
        $user_data=return_field_data($pdo, "users", "username", $username);
        unset($user_data['password']);
        unset($user_data['validationcode']);
        unset($user_data['comments']);
        echo json_encode($user_data);
    } else {
        echo "ERROR: No user currently logged in";
    }
?>