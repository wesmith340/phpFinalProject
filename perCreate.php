<?php 
    
    require '../database/database.php';
    require 'functions.php'; //Includes the createHeader function

    if ( !empty($_POST)) {
        // keep track validation errors
        $fnameError = null;
        $lnameError = null;
        $emailError = null;
        $passwordError = null;

        // keep track post values
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordHash = md5($password);

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
	$sql = "SELECT * FROM persons";
	foreach($pdo->query($sql) as $row) {
		if($email == $row['email']) {
			$emailError = 'Email has already been registered!';
			$valid = false;
		}
	}

	Database::disconnect();
        
        if (empty($password)) {
            $passwordError = 'Please enter password Number';
            $valid = false;
        }

        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO persons (fname,lname,email,passwordHash) VALUES(?, ?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($fname, $lname, $email,$passwordHash));
            Database::disconnect();
            header("Location: groupList.php");
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
                <h2 class="headerColor">Sign Up</h2>
            </div>

            <form class="form-horizontal" action="perCreate.php" method="post">
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
                <div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
                    <label class="control-label bodyText">Password</label>
                    <div class="controls">
                        <input name="password" type="password"  placeholder="Not your SVSU password" value="<?php echo !empty($password)?$password:'';?>">
                        <?php if (!empty($passwordError)): ?>
                                <span class="help-inline"><?php echo $passwordError;?></span>
                        <?php endif;?>
                    </div>
                </div>
                <div class="form-actions div">
                    <button type="submit" class="btn btn-success">Create</button>
                    <a class="btn" href="login.php">Back</a>
                </div>
            </form>
        </div>
				
    </div> <!-- /container -->
  </body>
</html>