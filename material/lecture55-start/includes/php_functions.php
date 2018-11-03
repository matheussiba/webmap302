<?php
    function redirect($loc) {
        header("Location: {$loc}");
    }

    function generate_token() {
        return md5(microtime().mt_rand());
    }

    function logged_in(){
        if (isset($_SESSION['username'])) {
            return true;
        } else {
            if (isset($_COOKIE['username'])) {
                $_SESSION['username'] = $_COOKIE['username'];
                return true;
            } else {
                return false;
            }
        } 
    }

    function set_msg($msg, $level='danger') {
        if (($level!='primary') && ($level!='success') && ($level!='info') && ($level!='warning')) {
            $level='danger';
        }
        if (empty($msg)) {
            unset($_SESSION['message']);
        } else {
            $_SESSION['message']="<h4 class='bg-{$level} text-center'>{$msg}</h4>";
        }
    }

    function show_msg(){
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
    }

    function send_mail($to, $subject, $body, $from, $reply) {
        $headers = "From: {$from}"."\r\n"."Reply-To: {$reply} "." \r\n "."X-Mailer: PHP/".phpversion();
        if ($_SERVER['SERVER_NAME'] != "localhost") {
            mail($to, $subject, $body, $headers);
            set_msg("Email sent to '{$to}'. Please check email to activate your account");
            redirect('index.php');
        } else {
            echo "<hr><p>To: {$to}</p><p>Subject: {$subject}</p><p>{$body}</p><p>".$headers."</p><hr>";
        }
    }

//   ******************  DATABASE FUNCTIONS  ********************************

    function count_field_val($pdo, $tbl, $fld, $val) {
         try {
              $sql="SELECT {$fld} FROM {$tbl} WHERE {$fld}=:value";
              $stmnt=$pdo->prepare($sql);
              $stmnt->execute([':value'=>$val]);
              return $stmnt->rowCount();
         } catch(PDOException $e) {
              return $e->getMessage();
         }
    }

    function return_field_data($pdo, $tbl, $fld, $val) {
         try {
              $sql="SELECT * FROM {$tbl} WHERE {$fld}=:value";
              $stmnt=$pdo->prepare($sql);
              $stmnt->execute([':value'=>$val]);
              return $stmnt->fetch();
         } catch(PDOException $e) {
              return $e->getMessage();
         }
    }

    function get_validationcode($user, $pdo) {
         try {
              $stmnt=$pdo->prepare("SELECT validationcode FROM users WHERE username=:username");
              $stmnt->execute([':username'=>$user]);
              $row = $stmnt->fetch();
              return $row['validationcode'];
         } catch(PDOException $e) {
              return $e->getMessage();
         }        
    }

    function update_login_date($pdo, $user) {
         try {
              $stmnt=$pdo->prepare("UPDATE users SET last_login=current_date WHERE username=:username");
              $stmnt->execute([':username'=>$user]);
         } catch(PDOException $e) {
              return $e->getMessage();
         }        
    }

    function verify_user_group($pdo, $user, $group) {
        $user_row = return_field_data($pdo, "users", "username", $user);
        $user_id = $user_row['id'];
        $group_row = return_field_data($pdo, "groups", "name", $group);
        $group_id = $group_row['id'];
         try {
              $sql="SELECT id FROM user_group_link WHERE user_id={$user_id} AND group_id={$group_id}";
              $stmnt=$pdo->query($sql);
              if ($stmnt->rowCount()>0) {
                  return true;
              } else {
                  return false;
              }
         } catch(PDOException $e) {
              echo $e->getMessage();
              return false;
         }
    }

    function user_pages_count($pdo, $user) {
        try {
            $sql="SELECT u.username, g.name AS group_name, g.descr AS group_descr, p.name ";
            $sql.="as page_name, p.descr as page_descr, p.url ";
            $sql.="FROM users u JOIN user_group_link gu ON u.id=gu.user_id ";
            $sql.="JOIN groups g ON gu.group_id=g.id ";
            $sql.="JOIN pages p ON g.id=p.group_id ";
            $sql.="WHERE username='{$user}' ";
            $sql.="ORDER BY group_name";
            $result = $pdo->query($sql);
            return $result->rowCount();
        } catch(PDOException $e){
            echo "Oops there was an error<br><br>".$e->getMessage();
        }
    }