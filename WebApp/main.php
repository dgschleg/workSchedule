<?php
    $invalidMsg = "";
    require_once("loginDb.php");
	require_once("functions.php");

	session_start();
    if (isset($_POST['submit'])) {
        if (isset($_POST['username']) && isset($_POST['pw'])) {
            $name = trim($_POST['username']);
            $pw = trim($_POST['pw']);

            $db_connection = new mysqli($host, $user, $password, $database);

            if ($db_connection->connect_error) {
                die($db_connection->connect_error);
            } else {
                $invalidMsg .= "Connection to database established<br><br>";
            }

            /* Query */
            $query = "select * from users where username = \"$name\"";

            $result = $db_connection->query($query);
            if (!$result) {
                die("Insertion failed: " . $db_connection->error);
            } else {
                /* Number of rows found */
                $num_rows = $result->num_rows;
                if ($num_rows === 0) {
                    $invalidMsg .= "<strong>No entry exists in the database for the specified email and password</strong><br/><br/>";
                } else {
                    for ($row_index = 0; $row_index < $num_rows; $row_index++) {
                        $result->data_seek($row_index);
                        $row = $result->fetch_array(MYSQLI_ASSOC);

                        if (password_verify($pw, $row['password'])) {
                            $_SESSION['name'] = $row['username'];
							$_SESSION['position'] = $row['position'];
                            header("Location: table.php");
                        }else {
                            $invalidMsg .= "No entry exists in the database for the specified email and password";
                        }
                    }
                }
            }

            /* Closing connection */
            $db_connection->close();
        }else {
            $invalidMsg .= "Please input your username and password";
        }
    }

    if (isset($_POST['register'])) {
        if (isset($_POST['reg_user']) && isset($_POST['reg_pw'])) {
            $name = trim($_POST['reg_user']);
            $pw = trim($_POST['reg_pw']);
            $hashed = password_hash($pw, PASSWORD_BCRYPT);
            $position = $_POST['position'];
			$split = pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
			if ($split != '') {
				$profile = 'data:image/' . $split . ';base64,' . base64_encode(file_get_contents($_FILES['profile']['tmp_name']));
			}
            $db_connection = new mysqli($host, $user, $password, $database);
            if ($db_connection->connect_error) {
                die($db_connection->connect_error);
            } else {
              // var_dump($invalidMsg);
                // echo("What it is: "+$invalidMsg);
                $invalidMsg .= "Connection to database established<br><br>";
            }

			if ($split != '') {
				/* Query */
				$query = "insert into users values ('{$name}', '{$hashed}', '{$position}', '{$profile}')";
			}else {
				$query = "insert into users values ('{$name}', '{$hashed}', '{$position}', NULL)";
			}
            $result = $db_connection->query($query);
            if (!$result) {
                die("Insertion failed: " . $db_connection->error);
            } else {
                $invalidMsg .= "Insertion completed.<br>";
            }

            /* Closing connection */
            $db_connection->close();
        }else {
            $invalidMsg .= "Please input the info.";
        }
    }

	if (isset($_POST['logout'])) {
		unset($_SESSION['name']);
		unset($_SESSION['position']);
	}
?>

<!doctype html>
<html>
    <head>
        <title>Login Page</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="main.css"/>
		<script src="jquery-3.1.1.min.js"></script>
        <script src="main.js"></script>
		<script src=" https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.js"></script>
    </head>

    <body>
        <div id="regbar">
            <h1><a href><label for="login">Login</label></a> |
                <a href><label for="register">Register</label></a></h1>

            <div id="bg">
            <img src="wallpaper.jpg">

            <input id="login" type="radio" name="choose" checked/>

            <div id="login_form">
                <fieldset>
                <h2>Login</h2>
                <form action="main.php" method="post" id="form_login">
                    <strong>Username:</strong><input type="text" name="username" size="25" id="login_user"
					placeholder="Letters only" required/> <br /><br />
                    <strong>Password:</strong><input type="password" name="pw" size="25" id="login_pw"
					placeholder="Letters, numbers only" required/> <br /><br />
                    <input id="loginButton" type="submit" name="submit" value="Login"/>
                    <br />
                    <select>
                        <option value="employee">Employee</option>
                        <option value="supervisor">Supervisor</option>
                    </select>
                </form>

                <br />
                </fieldset>
            </div>

            <input id="register" type="radio" name="choose"/>
            <div id="register_form">
                <fieldset>
                    <h2>Register</h2>
                    <form action="main.php" method="post" enctype="multipart/form-data" id="form_register">
                        <strong>Username:</strong><input type="text" id="reg_user" name="reg_user" size="25"
						placeholder="Letters only" required/> <br /><br />

                        <strong>Password:</strong><input type="password" id="reg_pw" name="reg_pw" size="25"
						placeholder="Letters, numbers only" required/> <br/> <br />
                        Position:
                        <select id="position" name="position">
                            <?php printPositions(""); ?>
                        </select> <br /><br />
                        <strong>Select profile image</strong><input id="profile" type="file" name="profile" id="profile"/>
						<br /><br />
                        <input id="register" type="submit" name="register" value="Register"/>
                    </form>
                </fieldset>
            </div>

        </div>
        </div>
    </body>
</html>
