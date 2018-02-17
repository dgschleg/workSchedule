<?php
class Period {
    public $day;
    public $start;
    public $end;
    
    public function __construct($dayIn, $startIn, $endIn) {
        $this->day = (int) $dayIn;
        $this->start = (int) $startIn;
        $this->end = (int) $endIn;
    }
}
?>