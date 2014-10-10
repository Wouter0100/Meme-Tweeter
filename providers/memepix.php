<?php

class provider_memepix extends provider {
	
	public function parge() {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'http://memepix.com/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		phpQuery::newDocument(curl_exec($ch));
		curl_close($ch);
		
		pq('img[class="postImage"]')->each(function($input){
			
			$name = pq($input)->parent()->parent()->parent()->children('h3')->children('a')->attr('title');
			
			$this->tmpResult[] = array('name' => $name, 'url' => pq($input)->attr('src'));
		});
		
		if (!empty($this->tmpResult)) 
			$this->result = $this->tmpResult[array_rand($this->tmpResult)];
	}
	
}
