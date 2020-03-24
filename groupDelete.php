<?php 
    session_start();
    
    require 'functions.php';
    require '../database/database.php';
    
    $groupId = $_POST['groupId'];
    $pdo = Database::connect();
    $sql = 'SELECT * FROM groups WHERE id=?';
    $q = $pdo->prepare($sql);
    $q->execute(array($groupId));
    $groupData = $q->fetch();
    
    if(!isset($_SESSION["fr_person_id"]) 
            || $_SESSION["fr_person_id"] != $groupData['ownerId']){ // if "user" not set or if "user" is not admin
	//session_destroy();
	//header('Location: login.php');     // go to login page
	//exit;
    }
    $sessionId = $_SESSION['fr_person_id'];
     
    
    if (isset($_POST['deleteFlag'])){
        $pdo = Database::connect();
        $sql = 'DELETE FROM persons_groups WHERE groupId=?';
        $q = $pdo->prepare($sql);
        $q->execute(array($groupId));
        $sql = 'DELETE FROM groups WHERE id=?';
        $q = $pdo->prepare($sql);
        $q->execute(array($groupId));
        Database::disconnect();
        
        header('Location: groupList.php');
        exit;
    }

    createHeader();
?>
<body>
    <div class="container">
            <div class="row">
                <h2 class="headerColor">Delete Group</h2>
            </div>
        <form class="form-horizontal" action="groupDelete.php" method="post">
        <div class="row">
            <h3 class="bodyText">Would you like to delete <?php echo $groupData['name'] ?>?</h3>
            <input type="hidden" name="groupId" value="<?php echo $groupId ?>">
            <input type="hidden" name="deleteFlag" value="<?php echo 'true' ?>">
            <button style="margin-right: 20px;" type="submit" class="btn btn_success">Yes</button>
            <a class="btn btn-info" href="groupDetails.php?id=<?php echo $groupId;?>">Back</a>
    	</div>
        </form>
    </div> <!-- /container -->
  </body>
</html>