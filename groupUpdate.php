<?php 
    session_start(); 
    require '../database/database.php';
    require 'functions.php';
    
    $groupId = $_POST['groupId'];
    $pdo = Database::connect();
    $sql = 'SELECT * FROM groups WHERE id=?';
    $q = $pdo->prepare($sql);
    $q->execute(array($groupId));
    $groupData = $q->fetch();
    
    if(!isset($_SESSION["fr_person_id"]) 
            || $_SESSION["fr_person_id"] != $groupData['ownerId']){ // if "user" not set or if "user" is not admin
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
    }
    $sessionId = $_SESSION['fr_person_id'];
    
    
    
    if ( isset($_POST['updateFlag'])) {
        // keep track validation errors
        $nameError = null;
        $locationError = null;
        $dateError = null;
        $fileError = null;

        // keep track post values
        $name = $_POST['name'];
        $location = $_POST['location'];
        $time = $_POST['time'];
        $date = $_POST['date'];
        $description = $_POST['description'];
        
        // validate input
        $valid = true;
        if (empty($name)) {
            $nameError = 'Please enter a Name';
            $valid = false;
        }

        if (empty($location)) {
            $locationError = 'Please enter a Location';
            $valid = false;
        }
        
        if (empty($time)) {
            $timeError = 'Please enter a Time';
            $valid = false;
        }elseif(preg_match('/^[0-9]|[0-9]{2}:[0-9]{2}(am|pm)$/', $time) == 0){
            $timeError = 'Please enter a Valid Time';
            $valid = false;
        }

        if (empty($date)) {
            $dateError = 'Please enter a Date';
            $valid = false;
        }
        
        $fileName = null;
        $fileSize = null;
        $fileType = null;
        $fileContent = null;
        $file = $_FILES['file1'];
        if ($file['size'] > 0){
            if ($file['size'] > 2000000){
                $fileError = 'Please use a file smaller that 2MB \n';
                $valid = false;
            }else{
                $fileName = $file['name'];
                $tempName = $file['tmp_name'];
                $fileSize = $file['size'];
                $fileType = $file['type'];

                $fp = fopen($tempName, 'r');
                $fileContent = fread($fp, filesize($tempName));
                fclose($fp);
            }
        }
        //insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE groups SET name=?,description=?,location=?,date=?,time=?,"
                    . "fileName=?,fileSize=?,fileType=?,fileContent=?"
                    . " WHERE id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($name,$description,$location,$date,$time,
                    $fileName,$fileSize,$fileType, $fileContent, $groupId));
            header('Location: groupDetails.php?id='.$groupId);
            exit;
        }
    }
    createHeader();
?>
<body>
    <div class="container">
    
        <div class="span10 offset1">
            <br><br>
            <div class="row">
                    <h2 class="headerColor">Update <?php echo $groupData['name'];?></h2>
            </div>
            <form class="form-horizontal" action="groupUpdate.php" enctype="multipart/form-data" method="post">
                <div class="control-group <?php echo !empty($nameError)?'error':'';?>">
                    <label class="control-label bodyText">Group Name</label>
                    <div class="controls">
                        <input name="name" type="text"  placeholder="Group Name" value="<?php echo $groupData['name'];?>">
                        <?php if (!empty($nameError)): ?>
                                <span class="help-inline"><?php echo $nameError;?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($locationError)?'error':'';?>">
                    <label class="control-label bodyText">Location</label>
                    <div class="controls">
                        <input name="location" type="text" placeholder="Location" value="<?php echo $groupData['location'];?>">
                        <?php if (!empty($locationError)): ?>
                                <span class="help-inline"><?php echo $locationError;?></span>
                        <?php endif;?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($dateError)?'error':'';?>">
                    <label class="control-label bodyText">Date</label>
                    <div class="controls">
                        <input name="date" type="date"  placeholder="Date" value="<?php echo $groupData['date'];?>">
                        <?php if (!empty($dateError)): ?>
                                <span class="help-inline"><?php echo $dateError;?></span>
                        <?php endif;?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($timeError)?'error':'';?>">
                    <label class="control-label bodyText">Time</label>
                    <div class="controls">
                        <input type="text" style="width: 70px;" id="timePicker" name="time" class="time" 
                               value=<?php echo $groupData['time'];?>/>
                        <script type="text/javascript">
                                $(function() {
				$('#timePicker').timepicker({ 'scrollDefault': 'now' });
			});
                        </script>
                        <?php if (!empty($timeError)): ?>
                                <span class="help-inline"><?php echo $timeError;?></span>
                        <?php endif;?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($fileError)?'error':'';?>">
                    <label class="control-label bodyText">Picture of Game</label>
                    <div class="controls bodyText">
                        <input type="file" name="file1" id="file1"/>
                        <?php if (!empty($fileError)): ?>
                                <span class="help-inline"><?php echo $fileError;?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
                    <label class="control-label bodyText">Description</label>
                    <div class="controls">
                        <textarea class="form-control" id="description" name="description" rows="6"><?php echo $groupData['description'];?></textarea>
                    </div>
                </div>
                <div class="form-actions div">
                    <input type="hidden" name="updateFlag" value="true"/>
                    <input type="hidden" name="groupId" value="<?php echo $groupId; ?>">
                    <button type="submit" class="btn">Update</button>
                    <a class="btn btn-info" href="groupDetails.php?=<?php echo $groupId; ?>">Back</a>
                </div>
            </form>
        </div>
				
    </div> <!-- /container -->
  </body>
</html>