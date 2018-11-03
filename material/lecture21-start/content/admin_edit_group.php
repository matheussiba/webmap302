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
        $group_id = $_GET['id'];
        if (count_field_val($pdo, "groups", "id", $group_id)>0){
            $row=return_field_data($pdo, "groups", "id", $group_id);
            $descr = $row['descr'];
        } else {
            redirect('admin.php');
        }
    } else {
        redirect('admin.php');
    }
    if ($_SERVER['REQUEST_METHOD']=="POST") {
        $descr = $_POST['descr'];
                
        if (!isset($error)) {
            try {
                $sql = "UPDATE groups SET descr=:descr WHERE id=:id";
                $stmnt = $pdo->prepare($sql);
                $user_data = [':descr'=>$descr, ':id'=>$group_id];
                $stmnt->execute($user_data);
                redirect('admin.php?tab=groups');
            } catch(PDOException $e) {
                echo "Error: ".$e->getMessage();
            }
        }
        
    } 
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "../includes/header.php" ?>
    <body>
        <?php include "../includes/nav.php" ?>

        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3">
                    <?php 
                        show_msg();
                        if (isset($error)) {
                            foreach ($error as $msg) {
                                echo "<h4 class='bg-danger text-center'>{$msg}</h4>";
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-login">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form id="register-form" method="post" role="form" >
                                        <div class="form-group">
                                            <textarea name="descr" id="descr" tabindex="7" class="form-control"  placeholder="Description"><?php echo $descr ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="update-submit" id="update-submit" tabindex="4" class="form-control btn btn-custom" value="Update Group">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "../includes/footer.php" ?>
    </body>
</html>