<?php


class Index extends Controller {
	
	function home() {
		global $config;
		header("Location:".$config['render']['site_http'].$config['render']['default_page']);
	}
	
}