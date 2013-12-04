<?php
	
	//		.==.		.==.		  
	//	   //`^\\	  //^`\\		 
	//	  // ^ ^\(\__/)/^ ^^\\		
	//	 //^ ^^ ^/6  6\ ^^ ^ \\	   
	//	//^ ^^ ^/( .. )\^ ^ ^ \\	  
	//   // ^^ ^/\| v""v |/\^ ^ ^\\	 
	//  // ^^/\/ /  `~~`  \ \/\^ ^\\	
	//  -----------------------------
	/// HERE BE DRAGONS
	
	function Grab($url) {
		if(function_exists('curl_init')) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$data = curl_exec($ch);
				curl_close($ch);
		} else {
				$data = file_get_contents($url);
		}
		return $data;
	}
	
	function Ordinalize($num) {
		if ( ($num / 10) % 10 != 1 ) {
				switch($num % 10) {
						case 1: return /*$num . */'st';
						case 2: return /*$num . */'nd';
						case 3: return /*$num . */'rd';
				}
		}
		return /*$num . */'th';
	}
	
	function OrdiNice($n) {
		$n = (0 + str_replace(",", "", $n));
		if(!is_numeric($n)) return false;
		if($n > 1000000000000) return round(($n / 1000000000000)) . Ordinalize($n) . ' trillion';
		else if($n > 1000000000) return round(($n / 1000000000)) . Ordinalize($n) . ' billion';
		else if($n > 1000000) return round(($n / 1000000)) . Ordinalize($n) . ' million';
		else if($n > 1000) return round(($n / 1000)) . Ordinalize($n) . ' thousand';
		return Ordinalize($n);
	}
	
	function Nice($n) {
		$n = (0 + str_replace(",", "", $n));
		if(!is_numeric($n)) return false;
		if($n > 1000000000000) return round(($n / 1000000000000)) . ' trillion';
		else if($n > 1000000000) return round(($n / 1000000000)) . ' billion';
		else if($n > 1000000) return round(($n / 1000000)) . ' million';
		else if($n > 1000) return round(($n / 1000)) . ' thousand';
		return Ordinalize($n);
	}
	
	function TotalPixels($username) {
		
		$cachefile = 'cache/'.$username;
		$cachetime = 120 * 48;
		
		
		if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))) {
			return file_get_contents($cachefile);
		}
			
		$page = '';
		$now = 1;
		$total = 0;
		
		do {
			
			if ($now > 1) {
					$page = '?page=' . $now;
			}
			
			$data = json_decode(file_get_contents('http://api.dribbble.com/players/' . $username . '/shots' . $page), 1);
			
			for ($i = 0; $i < count($data['shots']); $i++) {
				$shot = $data['shots'][$i];
				$total += $shot['height'] * $shot['width']; // Hard to explain
			}
			
			$now++;
			
		} while ($data['pages'] >= $now);
		
		
		$fp = fopen($cachefile, 'w');
		fwrite($fp, $total);
		fclose($fp);
		
		return $total;
	}
	
	$total = TotalPixels('anatolinicolae');
	
	echo 'I\'ve dribbbled something like ' . OrdiNice($total) . ' pixel away! <br>';
	// If you're reading this, then my script is probably a success.
	echo 'I\'ve dribbbled something like ' . Nice($total) . ' pixels! <br>';
	// If you're reading this, then my script is probably a double success.
	echo 'I\'ve dribbbled ' . number_format($total) . ' pixels!';
	// If you're reading this, then my script is probably a triple success.
