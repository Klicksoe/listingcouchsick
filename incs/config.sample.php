<?php


// Configuration propre aux thèmes
$config['render']['theme']				= 'bootstrap';
$config['render']['site_http']			= 'http://localhost';
$config['render']['default_page']		= '/films/derniers';
$config['render']['site_title']			= 'Listing';


// Configuration propre à couchpotato
$config['couchpotato']['active']			= true;
$config['couchpotato']['permit_download']	= true;
$config['couchpotato']['api_dir']			= 'tmp/';
$config['couchpotato']['api_url'] 			= ''; // API URL example : http://localhost:5050/couchpotato/api
$config['couchpotato']['api_key'] 			= ''; // API KEY. Go to Settings => select Advanced Setting on main Settings page
$config['couchpotato']['movie.list']		= ''; // list of movies from couchpotato api. Use direct link to API or path to file downlaoded with cron.sh

// Configuration propre à sickbeard
$config['sickbeard']['active']				= true;
$config['sickbeard']['permit_download']		= true;
$config['sickbeard']['api_url']				= ''; // API URL example : http://localhost:8081/sickbeard/api
$config['sickbeard']['api_key']				= ''; // API KEY. Go to Settings => General => API (enable and random your API key)

// Configuration propre à sockso via ProxyPass Apache
$config['musiques']['active']				= true;
$config['musiques']['subfolder']			= 'music'; // ProxyPassed URL. Link to sockso URL ProxyPassed. Example here : http://localhost/music/

// Configuration propre à Grooveshark
$config['grooveshark']['active']			= true;
$config['grooveshark']['playlist']			= ''; // ID of your Grooveshark playlist

// configuration du menu supplémentaire
$config['menu'][0]['folder']				= 'ebooks';
$config['menu'][0]['name']					= 'Ebooks';
$config['menu'][1]['folder']				= 'others';
$config['menu'][1]['name']					= 'Autres';