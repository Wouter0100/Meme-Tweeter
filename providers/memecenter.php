<?php

class provider_memecenter extends provider {
	
	public function parge() {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'http://www.memecenter.com/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		phpQuery::newDocument(curl_exec($ch));
		curl_close($ch);
		
		pq('img[class="rrcont"]')->each(function($input){ 
			$this->tmpResult[] = array('name' => pq($input)->attr('alt'), 'url' => pq($input)->attr('src'));
		});
		
		if (!empty($this->tmpResult)) 
			$this->result = $this->tmpResult[array_rand($this->tmpResult)];
	}
	
}
