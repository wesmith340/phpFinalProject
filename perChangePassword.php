<?php 
    session_start();
    
    if(!isset($_SESSION["fr_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
    }
    $sessionId = $_SESSION['fr_person_id'];
    require '../database/database.php';
    require 'functions.php'; //Includes the createHeader function

    if ( !empty($_POST)) {
        $password = $_POST['password'];
        $passwordMatch = $_POST['passwordMatch'];
        $passwordHash = md5($password);

        // validate input
        $valid = true;
        if (empty($password)) {
            $passwordError = 'Please enter a password';
            $valid = false;
        }
        if ($password != $passwordMatch){
            $passwordMatchError = 'Passwords do not match';
            $valid = false;
        }

        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE persons SET passwordHash=? WHERE id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($passwordHash,$sessionId));
            Database::disconnect();
            header("Location: perPasswordSuccess.php");
        }
    }
    
    createHeader();
?>

<body>
    <div class="container">
    
        <div class="span10 offset1">
            <br>
            <br>
            <div class="row">
                <h2 class="headerColor">Password Change</h2>
            </div>

            <form class="form-horizontal" action="perChangePassword.php" method="post">
                <div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
                    <label class="control-label bodyText">Password</label>
                    <div class="controls">
                        <input name="password" type="password"  placeholder="Not your SVSU password" value="<?php echo !empty($password)?$password:'';?>">
                        <?php if (!empty($passwordError)): ?>
                                <span class="help-inline"><?php echo $passwordError;?></span>
                        <?php endif;?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($passwordMatchError)?'error':'';?>">
                    <label class="control-label bodyText">Retype Password</label>
                    <div class="controls">
                        <input name="passwordMatch" type="password"  placeholder="Not your SVSU password" value="<?php echo !empty($passwordMatch)?$passwordMatch:'';?>">
                        <?php if (!empty($passwordMatchError)): ?>
                                <span class="help-inline"><?php echo $passwordMatchError;?></span>
                        <?php endif;?>
                    </div>
                </div>
                <div class="form-actions div">
                    <button type="submit" class="btn">Update</button>
                    <a class="btn btn-info" href="groupList.php">Back</a>
                </div>
            </form>
        </div>
				
    </div> <!-- /container -->
  </body>
</html>