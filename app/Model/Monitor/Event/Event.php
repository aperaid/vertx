<?php

namespace App\Model\Monitor\Event;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	public function __construct(){
		$this->color = $this->BGColor($this->taskcode, $this->eventcode);
	}
	
	public $timestamps = false;
	public $color;
	
	private function BGColor($taskcode, $eventcode){
		$color = "none";
		switch ($taskcode){
			case 1:
				switch ($eventcode){
					case 22:
					case 26:
					case 27:
					case 28:
						$color = "LightGray";
						break;
				}
				break;
			case 2:
				switch ($eventcode){
					case 20:
					case 21:
					case 37:
					case 38:
					case 63:
					case 67:
					case 68:
						$color = "LightGreen";
						break;
					case 23:
					case 24:
					case 27:
					case 29:
					case 30:
					case 31:
					case 32:
					case 33:
					case 34:
					case 35:
					case 36:
					case 53:
					case 54:
					case 55:
					case 56:
					case 57:
					case 58:
					case 61:
					case 62:
					case 64:
					case 65:
					case 66:
						$color = "LightGoldenRodYellow";
						break;
					case 45:
					case 50:
						$color = "LightSkyBlue";
						break;
					case 26:
						$color = "LightGray";
						break;
				}
				break;
			case 4:
				switch ($eventcode){
					case 20:
						$color = "LightCoral";
						break;
				}
				break;
			default:
				$color = "none";
				break;
		}
		
		return $color;
	}
}