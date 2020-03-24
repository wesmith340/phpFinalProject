<?php
// Start or resume session, and create: $_SESSION[] array
session_start(); 

require '../database/database.php';
require 'functions.php';

if (isset($_GET['error'])){
    $error = "Your username or password is not correct";
}

if ( !empty($_POST)) { // if $_POST filled then process the form

	// initialize $_POST variables
	$username = $_POST['username']; // username is email address
	$password = $_POST['password'];
	$passwordhash = MD5($password);
		
	// verify the username/password
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM persons WHERE email = ? AND passwordHash = ? LIMIT 1";
	$q = $pdo->prepare($sql);
	$q->execute(array($username,$passwordhash));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	
	if($data) { // if successful login set session variables
            $_SESSION['fr_person_id'] = $data['id'];
            $sessionId = $data['id'];
            Database::disconnect();
            header("Location: groupList.php?id=$sessionId ");
            exit();
	}
	else { // otherwise go to login error page
            Database::disconnect();
            header("Location: login.php?error=1");
	}
} 
// if $_POST NOT filled then display login form, below.


createHeader();
?>
<body>
    <div class="container">

        <div class="span10 offset1">

            <br>
            <br>
            <div class="row">
                <h2 class="headerColor">SVSU Tabletop Games Login</h2><br>
            </div>

            <form class="form-horizontal" action="login.php" method="post">

                <div class="control-group">
                    <label class="control-label bodyText">Username (Email)</label>
                    <div class="controls">
                        <input name="username" type="text"  placeholder="me@email.com" required> 
                    </div>	
                </div> 

                <div class="control-group <?php echo !empty($fnameError)?'error':'';?>">
                    <label class="control-label bodyText">Password</label>
                    <div class="controls">
                        <input name="password" type="password" placeholder="" required> 
                        <?php if (!empty($error)): ?>
                                <span class="help-inline bodyText"><?php echo $error;?></span>
                        <?php endif; ?>
                    </div>	
                </div> 

                <div class="form-actions div">
                    <button type="submit" class="btn btn-success">Sign in</button>
                    &nbsp; &nbsp;
                    <a class="btn btn-primary" href="perCreate.php">Join</a>
                </div>

            </form>


        </div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->

  </body>
  
</html>