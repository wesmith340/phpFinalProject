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
    
    $groupId = $_POST['groupId'];
    $personId = $_POST['personId'];
    if (isset($_POST['deleteFlag'])){
        $pdo = Database::connect();
        $sql = 'DELETE FROM persons_groups WHERE personId=? AND groupId=?';
        $q = $pdo->prepare($sql);
        $q->execute(array($personId, $groupId));
        Database::disconnect();
        
        header('Location: groupDetails.php?id='.$groupId);
        exit;
    }

    createHeader();
?>
<body>
    <div class="container">
            <div class="row">
                <h2 class="headerColor">Delete Group Member</h2>
            </div>
        <form class="form-horizontal" action="deleteMember.php" method="post">
        <div class="row">
            <h3 class="bodyText">Would you like to delete this member?</h3>
            <input type="hidden" name="groupId" value="<?php echo $groupId ?>">
            <input type="hidden" name="personId" value="<?php echo $personId ?>">
            <input type="hidden" name="deleteFlag" value="<?php echo 'true' ?>">
            <button style="margin-right: 20px;" type="submit" class="btn btn_success">Yes</button>
            <a class="btn btn-info" href="groupDetails.php?id=<?php echo $groupId;?>">Back</a>
    	</div>
        </form>
    </div> <!-- /container -->
  </body>
</html>