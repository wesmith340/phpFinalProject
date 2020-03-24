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
    
    $pdo = Database::connect();
    $sql = 'SELECT * FROM persons WHERE id=?';
    $q = $pdo->prepare($sql);
    $q->execute(array($sessionId));
    $perData = $q->fetch();
    
    
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
                <div class="control-group">
                    <label class="control-label bodyText">First Name</label>
                    <div class="controls">
                        <input name="fname" readonly type="text"  placeholder="First Name" value="<?php echo $perData['fname'];?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label bodyText">Last Name</label>
                    <div class="controls">
                        <input name="lname" readonly type="text"  placeholder="Last Name" value="<?php echo $perData['lname'];?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label bodyText">Email Address</label>
                    <div class="controls">
                        <input name="email" readonly type="text" placeholder="Email Address" value="<?php echo $perData['email'];?>">
                    </div>
                </div>
                <div class="form-actions div">
                    <button type="submit" class="btn">Update Profile</button>
                    <a class="btn btn-danger" href="perChangePassword.php">Change Password</a>
                    <a class="btn btn-info" href="groupList.php">Back</a>
                </div>
            </form>
        </div>
				
    </div> <!-- /container -->
  </body>
</html>