<?php

require_once('period.php');
require_once('time_queue.php');

function printPositions($select) {
    $arr = ['CS Mailroom', 'CS Undergrad Office', 'CS StaffWorld', 'ISR Office', 'ECE Mailroom'];
    
    foreach($arr as $ele):?>
        <option <?=($select === $ele)? 'selected':''?>><?=$ele?></option>
    <?php endforeach;
}

function selectQuery($query, &$time_json, $toggle, &$db_connection) {
    $result = $db_connection->query($query);
        if (!$result) {
            die("Retrieval failed: ". $db_connection->error);
        } else {
            /* Number of rows found */
            $num_rows = $result->num_rows;
            for ($row_index = 0; $row_index < $num_rows; $row_index++) {
                $result->data_seek($row_index);
                $row = $result->fetch_array(MYSQLI_ASSOC);

                if ($toggle) {
                    $time_json->add_time($row['day'], $row['start'], $row['end']);
                }else {
                    $time_json->add_othertime($row['day'], $row['start'], $row['end']);
                }
            }
        }
}

function executeQuery($query, &$db_connection) {
    $result = $db_connection->query($query);
    if (!$result) {
        die("Retrieval failed: ". $db_connection->error);
    }
}
?>