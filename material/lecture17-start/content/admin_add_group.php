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
    if ($_SERVER['REQUEST_METHOD']=='POST') {
        $name=$_POST['name'];
        $descr=$_POST['descr'];
        
        if (strlen($name)<3) {
            $error[]="Group name must be at least 3 characters";
        }
        if (!isset($error)) {
            try {
                $stmnt=$pdo->prepare("INSERT INTO groups (name, descr) VALUES (:name, :descr)");
                $stmnt->execute([":name"=>$name, ":descr"=>$descr]);
                set_msg("Group '{$name}' as been added", "success");
                redirect("admin.php?tab=groups");
            } catch(PDOException $e){
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
            <?php 
                show_msg();
            ?>
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3">
                    <?php
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
                                            <input type="text" name="name" id="name" tabindex="1" class="form-control" placeholder="Group Name" required >
                                        </div>
                                        <div class="form-group">
                                            <textarea name="descr" id="descr" tabindex="8" class="form-control" placeholder="Description - Tell us about your organization ?"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-custom" value="Add Group">
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
