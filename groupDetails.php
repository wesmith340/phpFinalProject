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
    
    $groupId = $_GET['id'];
    
    $pdo = Database::connect();
    $sql = 'SELECT * FROM groups WHERE id = ?';
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $q = $pdo->prepare($sql);
    $q->execute(array($groupId));
    $groupData = $q->fetch();

    $sql = 'SELECT fName, lName FROM persons WHERE id = '.$groupData['ownerId'];
    $q = $pdo->prepare($sql);
    $q->execute();
    $perData = $q->fetch();
    
    $sql = 'Select COUNT(personId) FROM persons_groups WHERE personId=? AND groupId=?';
    $q = $pdo->prepare($sql);
    $q->execute(array($sessionId, $groupId));
    
    $member = true;
    if ($temp = $q->fetchColumn()[0] == 0){
        $member = false;
    }
    Database::disconnect();
    
    $admin = false;
    if ($groupData['ownerId'] == $sessionId){
        $admin = true;
    }
    createHeader();
?>
<body>
    <div class="container">
        <div class="row">
            <h2 class="headerColor">Group Details for <?php echo $groupData['name']?></h2><br>
        </div>
        <div class="row">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th class="tableBackground">
                            <table class="table table-borderless tableBackground">
                                <tr><td class="tableBackground">
                                    <?php
                                    echo '<h3 class="bodyText">Group Creator: '.$perData['fName'].' '.$perData['lName'].'';
                                    echo '</td></tr><tr><td class="tableBackground">';
                                    ?>
                                </td></tr>
                            </table>
                        </th>
                        <th>
                        <?php
                            echo '<img width=200 height="200" src="data:image/jpeg;base64,'.base64_encode($groupData['fileContent']).'"/>';
                        ?>
                        </th>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
            <thead>
                <tr>
                  <th><p class="bodyText">First Name</p></th>
                  <th><p class="bodyText">Last Name</p></th>
                  <th><p class="bodyText">Email</p></th>
                  <th><p class="bodyText">Action</p></th>
                </tr>
            </thead>
                <tbody>
                <?php 
                    $pdo = Database::connect();
                    $sql =    'SELECT * FROM persons, persons_groups '
                            . 'WHERE persons.id = persons_groups.personId '
                            . 'AND persons_groups.groupId = "'.$groupId.'" '
                            . 'ORDER BY persons.id DESC';

                    foreach ($pdo->query($sql) as $row) {
                        echo '<tr>';
                        echo '<td><p class="bodyText">'. $row['fname'] . '</td></p>';
                        echo '<td><p class="bodyText">'. $row['lname'] . '</p></td>';
                        echo '<td><p class="bodyText">'. $row['email'] . '</p></td>';
                        if ($admin || $sessionId == $row['personId']){
                            echo '<form action="deleteMember.php" method="post">';
                            echo '<td><button class="btn btn-danger" type="submit" method="get">Delete</button></td>';
                            echo '<input type="hidden" name="groupId" value="'.$groupId.'"/>';
                            echo '<input type="hidden" name="personId" value="'.$row['personId'].'"/>';
                            echo '</form>';
                        }else{
                            echo '<td></td>';
                        }
                        echo '</tr>';
                    }
                    Database::disconnect();
                ?>
                </tbody>
            </table>
            <?php
                echo '<label class="control-label bodyText">Description</label>';
                echo '<textarea class="textarea" readonly>'.$groupData['description'].'</textarea>';
            ?>
            <br><br>
            <?php
            echo '<table class="table table-borderless table-sm w-auto"style="width:50%;"><tr>';
            echo '<td class="tableBackground">';
            echo '<a class="btn btn-info btnMargin" href="groupList.php">Back</a>';
            echo '</td>';
            if (!$member){ // If user is not a member, show join button
                echo '<td class="tableBackground"><form action="groupJoin.php" method="post">';
                echo '<button type="submit" class="btn btn-success btnMargin" href="groupJoin.php">Join Group</button>';
                echo '<input type="hidden" name="groupId" value="'.$groupId.'"/>';
                echo '</form></td>';
            }
            if ($admin){ // If user is group admin allow update and delete group
                                        
                echo '<td class="tableBackground" >';
                echo '    <form action="groupUpdate.php" method="post">';
                echo '        <button type="submit" class="btn btn-in btnMargin">Update Group</button>';
                echo '        <input type="hidden" name="groupId" value="'.$groupId.'"/>';
                echo '    </form>';
                echo '</td>';

                echo '<td class="tableBackground" >';
                echo '    <form action="groupDelete.php" method="post">';
                echo '<button type="submit" class="btn btn-danger btnMargin">Delete Group</button>';
                echo '<input type="hidden" name="groupId" value="'.$groupId.'"/>';
                echo '    </form>';
                echo '</td>';
                echo ' </tr></table>';
            }?>
    	</div>
    </div> <!-- /container -->
  </body>
</html>