<?php


class Grooveshark extends Controller {
	
	function __construct() {
		global $config;
		if (!$config['grooveshark']['active']) {
			header("Location:".$config['render']['site_http']);
		}
	}
	
	function home() {
		global $config;
		global $twig;
		
		echo $twig->render('grooveshark.twig', array(
				'focus' => 'grooveshark'
		));
	}
	
}