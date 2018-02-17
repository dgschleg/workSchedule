<?php
  require_once "loginDb.php";
  session_start();
  $username = $_SESSION['name'];
  $position = $_SESSION['position'];
  $image = null;
  
	$db_connection = new mysqli($host, $user, $password, $database);
	if ($db_connection->connect_error) {
		die($db_connection->connect_error);
	}
	
	/* Query */
	$query = "select username, position, image from {$table_user} where username = '{$username}'";
			
	/* Executing query */
	$result = $db_connection->query($query);
	$row = $result->fetch_array(MYSQLI_ASSOC);
  $image = $row['image'];
	
	/* Freeing memory */
	$result->close();
	
	/* Closing connection */
	$db_connection->close();
?>

<!doctype html>
<html lang="en">
<head>
  <title>Profile Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <div class="btn-group btn-group-justified">
    <a href="logout.php" class="btn btn-primary">Logout</a>
    <a href="profileEdit.php" class="btn btn-primary">Edit Profile</a>
    <a href="table.php" class="btn btn-primary">View Schedule</a>
  </div>
</div>

<div class="container">
  <div class="jumbotron" style="text-align:center;">
    <h1>My Profile</h1>
        <img src="<?= $image === null? 'null.png': $image?>"  class="img-rounded" alt="testudo" width="270" height="270" ></br></br>
        <p class="bg-primary">Username</p>
        <p class="bg-info"><?=$username?></p>
        <p class="bg-primary">Postion</p>
        <p class="bg-info"><?=$position?></p>
  </div>
</div>

</body>
</html>
