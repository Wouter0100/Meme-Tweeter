<?php

class provider_9gag extends provider {
	
	public function parge() {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'http://9gag.com/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		phpQuery::newDocument(curl_exec($ch));
		curl_close($ch);
		
		pq('img[class="badge-item-img"]')->each(function($input){ 
			$this->tmpResult[] = array('name' => pq($input)->attr('alt'), 'url' => pq($input)->attr('src'));
		});
		
		if (!empty($this->tmpResult)) 
			$this->result = $this->tmpResult[array_rand($this->tmpResult)];
	}
	
}
