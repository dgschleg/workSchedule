<?php
    require_once('loginDb.php');
    require_once('functions.php');
    
    session_start();
    $username = $_SESSION['name'];
    $position = $_SESSION['position'];
    
    $insert = json_decode($_POST['data']);
    $db_connection = new mysqli($host, $user, $password, $database);
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }
    
    $deleteQ = "delete from {$table_time} where username = '{$username}'";
    executeQuery($deleteQ, $db_connection);
    
    for ($idx = 0; $idx < count($insert); $idx++) {
        /* Query */
        $query = "insert into {$table_time} values ('{$username}', '{$position}', {$insert[$idx]->day}, {$insert[$idx]->start}, {$insert[$idx]->end})";
        executeQuery($query, $db_connection);
    }
    $db_connection->close();
    
    header('Location: table.php');
?>