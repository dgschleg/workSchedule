<?php
    session_start();
    require_once("loginDb.php");
    require_once("period.php");
    require_once("time_queue.php");
    require_once("functions.php");
?>

<!doctype html>
<html>
    <head>
        <title>Scheduler</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="table.css" />
        <!-- <script src="jquery-3.1.1.min.js"></script> -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
        <h1>Work Schedule</h1>
        <br />

        <!-- <div id="link">
            <a href="profile.php">Profile Page</a>
        </div> -->

        <table id="schedule" border="1" class="table table-bordered">
            <tr class="info">
                <th></th>
                <th>Sunday</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
            </tr>

            <?php
                for($i = 0, $hour = 8; $i < 11; $i++, $hour++):
                    $cur = mktime($hour, 0, 0, 0, 0 , 0);?>
                    <tr>
                        <td class="bg-info"><?=date("H:i", $cur)?></td>
                        <?php
                            for($bracket = 0; $bracket < 7; $bracket++):?>
                                <td data-row="<?=$i?>" data-col="<?=$bracket?>"></td>
                        <?php endfor?>
                    </tr>
            <?php endfor?>

        </table> <br />
        <form action='insert.php' method='post' id='submit_time'>
            <input type='hidden' id='data' name='data'/>
            <input type='submit' id='submit' value='Submit timesheet'/>
        </form>
        <script src="table.js"></script>


      </div>
    </div>
    </body>
</html>
