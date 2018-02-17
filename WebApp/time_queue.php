<?php

require_once("period.php");

class Time_queue {
    public $mytime, $othertime;
    
    public function __construct() {
        $this->mytime = array();
        $this->othertime = array();
    }
    
    public function add_time($day, $start, $end) {
        $this->mytime[] = new Period($day, $start, $end);        
    }
    
    public function add_othertime($day, $start, $end) {
        $this->othertime[] = new Period($day, $start, $end);
    }
}
?>