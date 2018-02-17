<?php
    require_once("period.php");
    require_once("time_queue.php");
    require_once("functions.php");
	require_once('loginDb.php');
	
	session_start();
    if (isset($_SESSION['name']) && isset($_SESSION['position'])) {
        $name = $_SESSION['name'];
        $position = $_SESSION['position'];
        $db_connection = new mysqli($host, $user, $password, $database);
        if ($db_connection->connect_error) {
            die($db_connection->connect_error);
        } 
        /* Query */
        $query = "select day, start, end from {$table_time} where username = '{$name}' and position = '{$position}'";
        $otherquery = "select day, start, end from {$table_time} where position = '{$position}' and username != '{$name}'";
		$time_json = new Time_queue();
        
        selectQuery($query, $time_json, true, $db_connection);
		selectQuery($otherquery, $time_json, false, $db_connection);
	
        /* Closing connection */
        $db_connection->close();
        
        echo json_encode($time_json);
    }
?>