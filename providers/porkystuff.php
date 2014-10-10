<?php

class provider_porkystuff extends provider {
	
	public function parge() {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'http://porkystuff.com/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		phpQuery::newDocument(curl_exec($ch));
		curl_close($ch);
		
		pq('img[class="storFedPik"]')->each(function($input){ 
			$this->tmpResult[] = array('name' => pq($input)->attr('alt'), 'url' => pq($input)->attr('src'));
		});
		
		if (!empty($this->tmpResult)) 
			$this->result = $this->tmpResult[array_rand($this->tmpResult)];
	}
	
}
