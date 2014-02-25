<?php


class Series extends Controller {
	
	function __construct() {
		global $config;
		if (!$config['sickbeard']['active']) {
			header("Location:".$config['render']['site_http']);
		}
	}

	function home() {
		global $twig;
		global $config;

		$data = file_get_contents($config['sickbeard']['api_url'].'/'.$config['sickbeard']['api_key'].'/shows?sort=name');
		$data = json_decode($data);
	
		$series = array();
		foreach($data->data as $key => $val) {
			$serie[] = array(
				'title'	=> $key,
				'cache'	=> $val->cache,
				'id'	=> $val->tvdbid
			);
		}

		echo $twig->render('series.home.twig', array(
			'focus' => 'series',
			'data' 	=> $serie
		));
	}
	
	function image($value) {
		global $config;
		
		header("Content-type: image/jpeg");
		$data = file_get_contents($config['sickbeard']['api_url'].'/'.$config['sickbeard']['api_key'].'/show.getbanner?tvdbid='.$value[0]);
		echo $data;
	}
	
	function derniers() {
		global $twig;
		global $config;
		
		$series = file_get_contents($config['sickbeard']['api_url'].'/'.$config['sickbeard']['api_key'].'/?cmd=history&type=downloaded&limit=25');
		$series = json_decode($series);
		
		$episodes = array();
		foreach($series->data as $donnee) {
			$file = file_get_contents($config['sickbeard']['api_url'].'/'.$config['sickbeard']['api_key'].'/?cmd=episode&tvdbid='.$donnee->tvdbid.'&season='.$donnee->season.'&episode='.$donnee->episode.'&full_path=1');
			$file = json_decode($file);
			
			$episodes[] = array(
				'file'	=> $file->data,
				'data'	=> $donnee	
			);
		}
		
		echo $twig->render('series.derniers.twig', array(
			'focus' => 'series',
			'data' 	=> $episodes
		));
	}
	
	function infos($value) {
		global $twig;
		global $config;
		
		if (!isset($value[0]) || empty($value[0]) || !is_numeric($value[0])) {
			header("Location:".$config['render']['site_http'].'/series/home');
		}
		
		$serie = file_get_contents($config['sickbeard']['api_url'].'/'.$config['sickbeard']['api_key'].'/show?tvdbid='.$value[0]);
		$serie = json_decode($serie);
		
		$directory = $serie->data->location;
		$files = $this->dirToArray('.'.$directory);
		
		echo $twig->render('series.infos.twig', array(
				'focus' => 'series',
				'data' 	=> $serie->data,
				'id'	=> $value[0],
				'files'	=> $files
		));
	}
	
	
	function telecharger($value) {
		global $twig;
		global $config;

		if (!isset($_GET['file']) || empty($_GET['file']) && is_file($_GET['file'])) {
			header("Location:".$config['render']['site_http'].'/series/home');
		}
		
		header("Location:".$config['render']['site_http'].urldecode($_GET['file']));
		
	}

}
