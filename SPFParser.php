<?php

class SPFParser {
	protected $visitedCache = array();


	public function getAllRecords($domain) { 
		return $this->fetchALLRecords($domain,$this->visitedCache);
	}


	private function fetchALLRecords($domain,&$cache) {
		$cache[] = strtolower($domain);
		$ret = array();	
		$result = dns_get_record($domain,DNS_TXT);

		foreach($result as $answer)
			if($answer['type'] == "TXT")
				$string.= " ".$answer['txt'];

		preg_match_all('/\s*(?P<type>.*?)(?:\:|=)\s?(?P<data>.*?)(?:\s|$)/simx', $string, $result, PREG_PATTERN_ORDER);

		for ($i = 0; $i < count($result[0]); $i++) {
			$line = array();

			if(($result['type'][$i] == "include" || $result['type'][$i] == "redirect") && in_array(strtolower($result['data'][$i]),$cache) == false)
				$ret = array_merge($ret,$this->fetchALLRecords($result['data'][$i],$cache));

			if($result['type'][$i] == "ip4" || $result['type'][$i] == "ip6" || $result['type'][$i] == "v"){
					$line['type'] = $result['type'][$i];
					$line['data'] = $result['data'][$i];
					$ret[] =  $line;
//					$ret[] =  $result['data'][$i];
				}
			if($result['type'][$i] == "a") {
					$aResult = dns_get_record($result['data'][$i],DNS_A);
					$line['type'] = $result['type'][$i];
					$line['host'] = $result['data'][$i];
					$line['data'] = $aResult[0]['ip'];
					$ret[] =  $line;
//					$ret[] = 
			}
		}
				
		sort($ret);
		array_unique($ret);
		return $ret;	
	}

	public function __construct() { 

	}

} // EoC