<?php
  require_once "loginDb.php";
  require_once "functions.php";
  session_start();

  if (isset($_POST['submitChanges'])) {
    $oldname = $_SESSION['name'];
    $name = trim($_POST['edit_name']);
    $pw = trim($_POST['edit_pw']);
    $hashed = password_hash($pw, PASSWORD_BCRYPT);
    $position = $_POST['edit_pos'];
		$split = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
		if ($split != '') {
				$profile = 'data:image/' . $split . ';base64,' . base64_encode(file_get_contents($_FILES['profile_pic']['tmp_name']));
		}

    $db_connection = new mysqli($host, $user, $password, $database);
    if ($db_connection->connect_error) {
      die($db_connection->connect_error);
    }

		if ($split != '') {
      /* Query */
      $query = "update ${table_user} set username = '{$name}', password = '{$hashed}', position = '{$position}', image = '$profile' where username = '{$oldname}'";
		}else {
      $query = "update ${table_user} set username = '{$name}', password = '{$hashed}', position = '{$position}' where username = '{$oldname}'";


    }

    $result = $db_connection->query($query);
    /* Closing connection */
    $db_connection->close();

    $_SESSION['name'] = $name;
    $_SESSION['position'] = $position;
    header('Location: profile.php');
  }else {

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
  }
?>
<!doctype html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css"> -->

  <!-- <script src="sign.js"></script> -->

</head>
<body>
  <div class="container">
    <div class="btn-group btn-group-justified">
      <a href="logout.php" class="btn btn-primary">Logout</a>
      <a href="profile.php" class="btn btn-primary">Profile Page</a>
      <a href="table.php" class="btn btn-primary">View Schedule</a>
    </div>
  </div>
<div class="container">
  <div class="jumbotron" style="text-align:center;">

<form action="profileEdit.php" method="post" enctype="multipart/form-data">
  <div class="container-fluid" style="text-align:center;">
      <h1>Edit Profile</h1>
      <p>Edit your information and it will be saved in the database</p>
      <div></div>

      <div class="col-sm-6" style="text-align:right;">

        <div class="form-group form-inline " >
          <label for="usr">Username:</label>
          <input type="text" class="form-control" id="usr" name="edit_name" value="<?=$username?>">
        </div>

        <div class="form-group form-inline " >
          <label for="email">Password:</label>
          <input type="password" class="form-control" name="edit_pw" required>
        </div>


        <div class="form-group form-inline " >
          <label for="selPos">Position:</label>
            <select class="form-control" id="selPos" name="edit_pos">
              <?php printPositions($position); ?>
            </select>
        </div>

        <input type="submit" name="submitChanges" id="changes" class="btn btn-primary" value="Submit Changes"/>
      </div>

      <div class="col-sm-4" style="text-align:center;">

        <img src="<?= $image === null? 'null.png': $image?>" id="profPic" class="img-thumbnail" alt="testudo" width="170px" height="170px"></br>

        <label for="changePic" class="btn btn-primary btn-file">change profile pic<input id="changePic" type="file" name="profile_pic" style="display: none;"></label>


      </div>
    </div>
  </form>
</div>
</div>
<script>

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#profPic').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#changePic").change(function(){
    readURL(this);
});



</script>
</body>
</html>
