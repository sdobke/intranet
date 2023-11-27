<?php 

class weather{
	public static $response;
	public static $location;
	public static $current;
	public static $nextdays;
	public static $error = false;
	
	public function weather()
	{
		$this->location = 'Buenos Aires';
	}
	
	public function get()
	{
		if (empty($this->location)) {
			$this->error = true;
			return false;
		}
		$requestAddress = "includes/clima/clima.xml";
		$xml_str = file_get_contents($requestAddress,0);
		$xml = new SimplexmlElement(utf8_encode($xml_str));
		if (!$xml->weather->problem_cause) {
			$this->response = $xml->weather;
			$this->parse();
		}else{
			$this->error = true;
		}
	}
	
	public function parse()
	{
		foreach($this->response as $item) {
			$this->current = $item->current_conditions;
			$this->forecast = $item->forecast_information;
			foreach($item->forecast_conditions as $new) {
				$this->nextdays[] = $new;		
			}	
		}
	}
	
	public function display()
	{
		foreach($this->nextdays as $new) {			
			echo '<div class="weatherIcon">';
				echo '<h2>'.traduceDia2(utf8_decode($new->day_of_week['data'])).'</h2>';
				echo '<table><tr><td>';
				
				$file_orig_clima = $new->icon['data'];
				$file_dest_clima = 'includes/clima/iconos/'.end(explode('/',$new->icon['data']));
				if(!file_exists($file_dest_clima)){
					copy ($file_orig_clima,$file_dest_clima);
				}
				
				echo '<img src="'.$file_dest_clima.'"/>';
				echo '</td><td>';
				echo '<br />'.$new->condition['data'];
				echo '<br />Min: '.$new->low['data'].' &deg;C';
				echo '<br />Max: '.$new->high['data'].' &deg;C';
				echo '</td></tr></table>';
			echo '</div>';			
		}	
	}
	
	public function convert($value, $unit = "C"){
		switch($unit){
			case "C":
				return number_format(($value - 32)/1.8);
			break;
			case "F":
				return round($value * 1.8 + 32);
			break;
			default:
				return $value;
				break;
		};
	}	
}
?>