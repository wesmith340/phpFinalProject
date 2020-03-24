<?php
    session_start();
    
    if(!isset($_SESSION["fr_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
    }
    $sessionId = $_SESSION['fr_person_id'];
    require 'functions.php';
    require '../database/database.php';
    
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    if (isset($_POST['updateFlag'])){
        // validate input
        $valid = true;

        if (empty($fname)) {
            $fnameError = 'Please enter your first name';
            $valid = false;
        }

        if (empty($lname)) {
            $lnameError = 'Please enter your last name';
            $valid = false;
        }

        if (empty($email)) {
            $emailError = 'Please enter Email Address';
            $valid = false;
        } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Please enter a valid Email Address';
            $valid = false;
        }

        $pdo = Database::connect();
	$sql = "SELECT COUNT(email) as count FROM persons WHERE email=? AND NOT id=?";
	$q = $pdo->prepare($sql);
        $q->execute(array($email,$sessionId));
        if ($q->fetch()['count'] != 0){
            $emailError = 'Email has already been registered!';
            $valid = false;
        }
        
        if ($valid){
            $sql = "UPDATE persons SET fname=?,lname=?,email=? WHERE id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($fname, $lname, $email,$sessionId));
            header("Location: perProfile.php");
        }
        Database::disconnect();
    }
    createHeader();
?>
<body>
    <div class="container">
    
        <div class="span10 offset1">
            <br>
            <br>
            <div class="row">
                <h2 class="headerColor">Sign Up</h2>
            </div>

            <form class="form-horizontal" action="perUpdate.php" method="post">
                <div class="control-group <?php echo !empty($fnameError)?'error':'';?>">
                    <label class="control-label bodyText">First Name</label>
                    <div class="controls">
                        <input name="fname" type="text"  placeholder="First Name" value="<?php echo !empty($fname)?$fname:'';?>">
                        <?php if (!empty($fnameError)): ?>
                                <span class="help-inline"><?php echo $fnameError;?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($lnameError)?'error':'';?>">
                    <label class="control-label bodyText">Last Name</label>
                    <div class="controls">
                        <input name="lname" type="text"  placeholder="Last Name" value="<?php echo !empty($lname)?$lname:'';?>">
                        <?php if (!empty($lnameError)): ?>
                                <span class="help-inline"><?php echo $lnameError;?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($emailError)?'error':'';?>">
                    <label class="control-label bodyText">Email Address</label>
                    <div class="controls">
                        <input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
                        <?php if (!empty($emailError)): ?>
                                <span class="help-inline"><?php echo $emailError;?></span>
                        <?php endif;?>
                    </div>
                </div>
                <div class="form-actions div">
                    <input type="hidden" name="updateFlag" value="true"/>
                    <button type="submit" class="btn">Update</button>
                    <a class="btn btn-info" href="perProfile.php">Back</a>
                </div>
            </form>
        </div>
				
    </div> <!-- /container -->
  </body>
</html>