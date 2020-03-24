<?php 
    session_start(); 
    require '../database/database.php';
    require 'functions.php';
    if(!isset($_SESSION["fr_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
    }
    $sessionId = $_SESSION['fr_person_id'];
    
    
    

    if ( !empty($_POST)) {
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
        if (!empty($_FILES['file1'])){
            $file = $_FILES['file1'];
            
                if ($file['size'] <= 0 || $file['size'] > 2000000){
                    $fileError = 'Please use a file smaller that 2MB \n';
                    $valid = false;
                }
                if ($valid){
                    $fileName = $file['name'];
                    $tempName = $file['tmp_name'];
                    $fileSize = $file['size'];
                    $fileType = $file['type'];

                    $fp = fopen($tempName, 'r');
                    $fileContent = fread($fp, filesize($tempName));
                    $fileContent = addslashes($fileContent);
                    fclose($fp);
                }
        }
        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO groups (ownerId, name,description,location,date,time,"
                    . "fileName,fileSize,fileType,fileContent)"
                    . " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($sessionId, $name,$description,$location,$date,$time,
                    $fileName,$fileSize,$fileType, $fileContent));
            
            $groupId = $pdo->lastInsertId();
            
            $sql = "INSERT INTO persons_groups (personId, groupId) VALUES(?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($sessionId, $groupId));
            Database::disconnect();
            header("Location: groupList.php");
        }
    }
    createHeader();
?>
<body>
    <div class="container">
    
        <div class="span10 offset1">
            <br><br>
            <div class="row">
                    <h2 class="headerColor">Create a Group</h2>
            </div>
            <form class="form-horizontal" action="groupCreate.php" enctype="multipart/form-data" method="post">
                <div class="control-group <?php echo !empty($nameError)?'error':'';?>">
                    <label class="control-label bodyText">Group Name</label>
                    <div class="controls">
                        <input name="name" type="text"  placeholder="Group Name" value="<?php echo !empty($name)?$name:'';?>">
                        <?php if (!empty($nameError)): ?>
                                <span class="help-inline"><?php echo $nameError;?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($locationError)?'error':'';?>">
                    <label class="control-label bodyText">Location</label>
                    <div class="controls">
                        <input name="location" type="text" placeholder="Location" value="<?php echo !empty($location)?$location:'';?>">
                        <?php if (!empty($locationError)): ?>
                                <span class="help-inline"><?php echo $locationError;?></span>
                        <?php endif;?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($dateError)?'error':'';?>">
                    <label class="control-label bodyText">Date</label>
                    <div class="controls">
                        <input name="date" type="date"  placeholder="Date" value="<?php echo !empty($date)?$date:'';?>">
                        <?php if (!empty($dateError)): ?>
                                <span class="help-inline"><?php echo $dateError;?></span>
                        <?php endif;?>
                    </div>
                </div>
                <div class="control-group <?php echo !empty($timeError)?'error':'';?>">
                    <label class="control-label bodyText">Time</label>
                    <div class="controls">
                        <input type="text" style="width: 70px;" id="timePicker" name="time" class="time" value="01:30 PM" />
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
                        <textarea class="form-control" id="description" name="description" rows="6"></textarea>
                    </div>
                </div>
                <div class="form-actions div">
                    <button type="submit" class="btn btn-success">Create</button>
                    <a class="btn" href="groupList.php">Back</a>
                </div>
            </form>
        </div>
				
    </div> <!-- /container -->
  </body>
</html>