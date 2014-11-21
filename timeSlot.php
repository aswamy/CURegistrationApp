<?php


	class TimeSlot {

		private $courseName = "";
		private $startTime = "";
		private $length = -1;


		public function __construct( /*string*/ $name = "", /*string*/ $start = "", $length = 0){
			$this->courseName = $name;
			$this->startTime = $start;
			$this->length = $length;
		}
		public function __get($property) {
    		if (property_exists($this, $property)) {
      			return $this->$property;
    		}
  		}

		public function __set($property, $value) {
		    if (property_exists($this, $property)) {
		     	$this->$property = $value;
		    }
			return $this;
		}

	}


?>