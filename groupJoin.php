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
    if (isset($_POST['joinFlag'])){
        $pdo = Database::connect();
        $sql = 'INSERT INTO persons_groups (personId,groupId) VALUES (?,?)';
        $q = $pdo->prepare($sql);
        $q->execute(array($sessionId, $groupId));
        Database::disconnect();
        
        header('Location: groupDetails.php?id='.$groupId);
        exit;
    }
    
    $pdo = Database::connect();
    $sql = 'SELECT * FROM groups WHERE Id=?';
    $q = $pdo->prepare($sql);
    $q->execute(array($groupId));
    $groupData = $q->fetch();
    Database::disconnect();

    createHeader();
?>
<body>
    <div class="container">
            <div class="row">
                <h2 class="headerColor">Would you like to join <?php echo $groupData['name']; ?>?</h2>
            </div>
        <form class="form-horizontal" action="groupJoin.php" method="post">
        <div class="row">
            <input type="hidden" name="groupId" value="<?php echo $groupId ?>">
            <input type="hidden" name="joinFlag" value="<?php echo 'true' ?>">
            <button type="submit" class="btn btn_success btnMargin">Yes</button>
            <a class="btn btn-info" href="groupDetails.php?id=<?php echo $groupId;?>">Back</a>
    	</div>
        </form>
    </div> <!-- /container -->
</body>
</html>