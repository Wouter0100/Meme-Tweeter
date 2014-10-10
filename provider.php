<?php

abstract class provider {
	
	protected $result = array();
	protected $tmpResult = array();
	
	abstract public function parge();
	
	public function getName() {
		return $this->result['name'];
	}
	
	public function getURL() {
		return $this->result['url'];
	}
	
	public function isSuccessful(){
		return (!empty($this->result['url']) && !empty($this->result['name']));
	}
	
	public function saveIMG(){
		$imgLocation = dirname(__FILE__).'/images/temp.jpg';
		
		file_put_contents($imgLocation, file_get_contents($this->getURL()));
		
		return $imgLocation;
	}
}
