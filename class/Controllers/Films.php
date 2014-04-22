<?php


class Films extends Controller {
	
	function __construct() {
		global $config;
		if (!$config['couchpotato']['active']) {
			header("Location:".$config['render']['site_http']);
		}
	}

	function home() {
		global $twig;
		global $config;
		
		$data = array();
		foreach (scandir($config['couchpotato']['api_dir']) as $node) {
			if ($node == '.')  continue;
			if ($node == '..') continue;
			if (is_file($config['couchpotato']['api_dir'].$node)) {
				$movies = file_get_contents($config['couchpotato']['api_dir'].$node);
				$movies = json_decode($movies);
				foreach ($movies->movies as $movie) {
					$data[] = $movie;
				}
			}
		}

		echo $twig->render('films.home.twig', array(
			'focus' => 'films',
			'data' 	=> $data
		));
	}
	
	function derniers() {
		global $twig;
		global $config;
		
		$data = array();
		foreach (scandir($config['couchpotato']['api_dir']) as $node) {
			if ($node == '.')  continue;
			if ($node == '..') continue;
			if (is_file($config['couchpotato']['api_dir'].$node)) {
				$movies = file_get_contents($config['couchpotato']['api_dir'].$node);
				$movies = json_decode($movies);
				foreach ($movies->movies as $movie) {
					$data[$movie->releases[0]->last_edit] = $movie;
				}
			}
		}
		krsort($data);
		
		echo $twig->render('films.home.twig', array(
			'focus' => 'films',
			'data' 	=> $data
		));
	}
	
	
	function telecharger($value) {
		global $twig;
		global $config;

		$movie = file_get_contents($config['couchpotato']['api_url'].'/'.$config['couchpotato']['api_key'].'/media.get?id='.$value[0]);
		$movie = json_decode($movie);
		
		$files = array();
		foreach($movie->movie->releases as $releases) {
			if (count($releases->files) > 0) {
				foreach($releases->files as $file) {
					if ($file->_t == "release") {
						$files[] = array(
							'id'	=> $file->_id,
							'path'	=> $file->movie[0]
						);
					}
				}
			}
		}
		
		if (isset($value[1]) && !empty($value[1])) {
			foreach($files as $data) {
				if ($data['id'] == $value[1]) {
					header("Location:".$config['render']['site_http'].$data['path']);
				}
			}
		}
		
		if (count($files) > 1) {
			
			$filess = array();
			foreach($files as $data) {
				$filess[] = array(
					'id'	=> $data['id'],
					'path'	=> substr($data['path'], strrpos($data['path'], '/') + 1)
				);
			}
			
			echo $twig->render('films.multidl.twig', array(
				'focus' => 'films',
				'parts' => $filess,
				'movie'	=> $movie
			));
			
		} else {
			header("Location:".$config['render']['site_http'].$files[0]['path']);
			
		}
	}
	
	
	function ajax($value) {
		global $twig;
		global $config;

		$movie = file_get_contents($config['couchpotato']['api_url'].'/'.$config['couchpotato']['api_key'].'/media.get?id='.$value[0]);
		$movie = json_decode($movie);
		
		$files = array();
		foreach($movie->movie->releases as $releases) {
			if (count($releases->files) > 0) {
				foreach($releases->files as $file) {
					if ($file->_t == "release") {
						echo $config['render']['site_http'].$file->->movie[0];
					}
				}
			}
		}
	}

}
