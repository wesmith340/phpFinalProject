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
    
    
    createHeader();
?>
<body>
    <div class="container">
            <div class="row">
                    <h2 class="headerColor">List of Gaming Groups</h2>
            </div>
        <div class="row">
            <p>
                    <a href="groupCreate.php" class="btn btn-success btnMargin">Create Group</a>
                    <a href="perProfile.php" class="btn btn-infor btnMargin">Your Profile</a>
                    <a href="logout.php" class="btn btn-danger btnMargin">Logout</a>
            </p>

            <table class="table table-bordered">
            <thead>
                <tr>
                  <th><p class="bodyText">Name</p></th>
                  <th><p class="bodyText">Location</p></th>
                  <th><p class="bodyText">Meeting Times</p></th>
                  <th><p class="bodyText">Meeting Dates</p></th>
                  <th><p class="bodyText">Action</p></th>
                </tr>
            </thead>
                <tbody>
                <?php 
                    $pdo = Database::connect();
                    $sql = 'SELECT * FROM groups ORDER BY id DESC';
                    foreach ($pdo->query($sql) as $row) {
                        echo '<tr>';
                        echo '<td><p class="bodyText">'. $row['name'] . '</td></p>';
                        echo '<td><p class="bodyText">'. $row['location'] . '</p></td>';
                        echo '<td><p class="bodyText">'. $row['time'] . '</p></td>';
                        echo '<td><p class="bodyText">'. $row['date'] . '</p></td>';
                        echo '<td width=250>';
                        echo '<a class="btn" href="groupDetails.php?id='.$row['id'].'">Details</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    Database::disconnect();
                ?>
                </tbody>
            </table>
    	</div>
    </div> <!-- /container -->
  </body>
</html>