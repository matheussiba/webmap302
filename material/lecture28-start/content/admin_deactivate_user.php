<?php include("../includes/init.php");?>
<?php 
    if (logged_in()) {
        $username=$_SESSION['username'];
        if (!verify_user_group($pdo, $username, "Admin")) {
            set_msg("User '{$username}' does not have permission to view this page");
            redirect('../index.php');
        }
    } else {
        set_msg("Please log-in and try again");
        redirect('../index.php');
    } 
?>
<?php
    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];
        $row=return_field_data($pdo, "users", "id", $user_id);
        if ($row['active']==0){
            $active=1;
        } else {
            $active=0;
        }
        try {
            $stmnt=$pdo->prepare("UPDATE users SET active={$active} WHERE id=:id");
            $stmnt->execute([':id'=>$user_id]);
            redirect('admin.php');
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        redirect('admin.php');
    }
   
?>
