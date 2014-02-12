<?php
session_start();

require_once 'class/Controller.php';
require_once 'incs/config.php';
require_once 'class/Twig/Autoloader.php';

// Load de l'environnement de dev
if (isset($_GET['env']) && !empty($_GET['env'])) {
	if (is_dir('themes/'. $_GET['env'])) {
		$config['render']['theme'] = $_GET['env'];
		$config['render']['site_http'] = $config['render']['site_http'].'/'. $_GET['env'];
	}
}


// Enregistrement du template Twig
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('themes/'.$config['render']['theme'].'/views');
$twig = new Twig_Environment($loader, array('cache' => false));


// Auto ajout des variables globales Twig
// foreach ($config['render'] as $name => $value) {
// 	$twig->addGlobal($name, $value);
// }
$twig->addGlobal('config', $config);
$twig->addGlobal('current_uri', $_SERVER['REQUEST_URI']);


// envoi vers la default page
if (empty($_GET['p']) && empty($_GET['f'])) {
	header("Location:".$config['render']['site_http'].'/index');
}


// définition de la variable d'appel des Controllers
if (isset($_GET['p']) && !empty($_GET['p'])) {
	$class_name = ucfirst(strtolower($_GET['p']));
} else {
	$class_name = 'Index'; 	
}



// test si le controller exite. Si non, load du template 404.
if(!is_file('class/Controllers/'.$class_name.'.php')) {
	header("Location:".$config['render']['site_http'].'/index');
}


// Instanciation du controller appelé dynamiquement
require_once 'class/Controllers/'.$class_name.'.php';
$class = new $class_name();


// d�finition de la variable d'appel des m�thodes
if (isset($_GET['f']) && !empty($_GET['f'])) {
	$function_name = strtolower($_GET['f']);
} else {
	$function_name = 'home';
}


// check si une fonction particulière a été appelée
if (!method_exists($class, strtolower($function_name))) {
	$function_name = 'home';
}

	
// définition de la variable d'appel des méthodes
if (isset($_GET['args']) && !empty($_GET['args'])) {
	$args = explode('/', $_GET['args']);
} else {
	$args = '';
}

// appel de la fonction correspondante
$class->$function_name($args);
