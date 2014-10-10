<?php
/* Including classes */
require_once 'twitter.php';
require_once 'phpquery.php';
require_once 'provider.php';
require_once 'config.php';

$db = new mysqli($twitter['hostname'], $twitter['username'], $twitter['password'], $twitter['database']);

if ($db->connect_error) {
    die('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
}

/* Authorization */
Codebird::setConsumerKey($twitter['key'], $twitter['secret']);
$twitter = Codebird::getInstance();
$twitter->setToken($twitter['token'], $twitter['token_secret']);

/* Randomisation and getting used provider */
while(true) {
	$providers = scandir(dirname(__FILE__).'/providers');
	$providers = array_diff($providers, array('.', '..'));
	
	$providerName = substr($providers[array_rand($providers)], 0, -4);
	$providerClassName = 'provider_'.$providerName;
	
	require_once dirname(__FILE__).'/providers/'.$providerName.'.php';
	
	$provider = new $providerClassName();
	$provider->parge();
	
	if ($provider->isSuccessful()) {
		$checkQuery = $db->query('SELECT * FROM tweeted WHERE name = "'.$db->escape_string($provider->getName()).'" OR url = "'.$db->escape_string($provider->getURL()).'" LIMIT 1');
		
		if ($checkQuery->num_rows >= 1) {
			if (isset($_GET['debug']))
				echo 'Already tweeted class "'.get_class($provider).'", name: "'.$provider->getName().'" and URL: "'.$provider->getURL().'".<br/>';
				
			unset($provider);
			continue;
		} else {
			break;
		}
	} else {
		if (isset($_GET['debug']))
			echo 'Unsuccessful class "'.get_class($provider).'", name: "'.$provider->getName().'" and URL: "'.$provider->getURL().'".<br/>';
	}
}

if (!isset($_GET['debug'])) {
	
	$name = $provider->getName();
	
	if (strlen($name) >= 110) {
		$name = substr($name, 0, 110).'...';
	}
	
	/* Tweeting */
	$twitter->statuses_updateWithMedia(array(
		'status' => $name,
		'media[]' => $provider->saveIMG()
	));

	$db->query('INSERT INTO tweeted (name, url) VALUES ("'.$db->escape_string($provider->getName()).'", "'.$db->escape_string($provider->getURL()).'")');
	
} else {
	echo 'Ended with class "'.get_class($provider).'", name: "'.$provider->getName().'" and URL: "'.$provider->getURL().'".';
}
?>
