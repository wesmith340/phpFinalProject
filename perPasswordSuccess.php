<?php
    session_start();
    
    if(!isset($_SESSION["fr_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
    }
    $sessionId = $_SESSION['fr_person_id'];
    require 'functions.php';
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
            <h4 class="bodyText">Password successfully changed</h4>
            <a class="btn btn-info" href="perProfile.php">Back</a>
        </div>
				
    </div> <!-- /container -->
  </body>
</html>